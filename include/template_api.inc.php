<?php
/*
 *@author Garvin Hicking
 *@state EXPERIMENTAL

 This file provides a basic Smarty emulating layer
 You can use different template engines than the default Smarty one
 by putting a "template.inc.php" file into your template directory.
 It should look something like this:

 <?php
 include_once S9Y_INCLUDE_PATH . 'include/template_api.inc.php';
 $GLOBALS['template'] = new serendipity_smarty_emulator();
 $GLOBALS['serendipity']['smarty'] =& $GLOBALS['template'];
 ?>

 You could of course also use inherited different template classes. It is important
 that your own template object contains method declarations like the class below
 for full interoperability to Serendipity templates. It is important that
 you assign a reference copy of your template object to the $serendipity['smarty']
 object for backwards compatibility.

 All variables that are assigned from this class to your templates/.php files
 will be put into $GLOBALS['tpl'].

 Since the scope of includes can vary, you'll need to use $GLOBALS['tpl'] instead
 of just $tpl in some cases. Thus it's recommended to always use the $GLOBALS['tpl']
 way. Also it's safer to use $GLOBALS['serendipity'] / $GLOBALS['template'] in most
 cases because of the same reason.

 Instead of Smarty $CONST.xxx constants you can use the usual 'xxx' constant access
 method by PHP.

 You can use any Smarty template file to construct your custom PHP template. You
 just need to do this:

  - Replace '{$variable}' calls with '<?= $GLOBALS['tpl']['variable'] ?>'.

  - Replace '{$variable|XXX}' smarty modifiers with corresponding PHP code, like:
    '<?= substr($GLOBALS['tpl']['XXX'], 0, 25) ?>'
     would correspond with
    '{$variable|truncate:'...':25}'
  - Replace '{if CONDITION} ... {/if}' checks with '<?php if (CONDITION): ?> ... <?php endif; ?>'

  - Replace '{foreach} ... {/foreach}' calls correspondingly.

  - Replace '{smartycommand param1=x param2=x}' function calls with
    '<?= $GLOBALS['template']->call('smartycommand', array('param1' => 'x', 'param2' => 'x')); ?>' ones

  - NOTA BENE: Be aware that many Smarty function calls are just wrappers to Serendipity API
    calls. To save grandma's performance pennies you should search the original Serendipity API
    function before calling them with the $GLOBALS['template']->call() wrapper! This costs dearly.

 The Serendipity Admin backend will still make use of Smarty. It rocks.

 Know your PHP before you think about using this. :-)
*/

declare(strict_types=1);

/* wrapper fake class */
class Smarty
{
    /**
     * smarty version
     */
    const SMARTY_VERSION = '4.1.39~'; // while some Plugins check if Smarty::SMARTY_VERSION is defined to switch API methods
}

/* PHP template Smarty emulator */
class serendipity_smarty_emulator
{
    private $compile_dir = '/tmp'; // Not used
    private $security_settings = []; // Not used

    private $config_dir = null;
    private $_configDirNormalized = null;
    private $_templateDirNormalized = null;
    private $_joined_config_dir = null;

    /**
     * flags for normalized template directory entries
     *
     * @var array
     */
    private $_processedConfigDir = []; // cloned from Smarty, since used in methods below

    /**
     * template directory
     *
     * @var array
     */
    private $template_dir = ['./templates/', './plugins/']; // cloned from Smarty, and extended with plugins, since used in methods below

    /**
     * Directory separator
     *
     * @var string
     */
    public $ds = DIRECTORY_SEPARATOR;

    /**
     * Registers plugin to be used in templates
     *
     * @api  Smarty::registerPlugin()
     * @link http://www.smarty.net/docs/en/api.register.plugin.tpl
     *
     * Args:
     *      - \Smarty_Internal_TemplateBase|\Smarty_Internal_Template|\Smarty object
     *      - plugin type
     *      - name of template tag
     *      - PHP callback to register
     *      - if true (default) this
     *      - function is cacheable
     *      - caching attributes if any
     * Returns:
     *      - \Smarty|\Smarty_Internal_Template
     * @access public
     * ##@throws SmartyException when the plugin tag is invalid
     */
    public function registerPlugin(object|string $obj, string $type, string|iterable $name, ?callable $callback = null, ?bool $cacheable = true, string|iterable|null $cache_attr = null) : callable|string
    {
        $smarty = $obj->smarty ?? $obj;
        if (isset($smarty->registered_plugins[ $type ][ $name ])) {
            #throw new SmartyException("Plugin tag \"{$name}\" already registered");
            $this->trigger_error("Plugin tag '{$name}' already registered\n");
        } elseif (!is_callable($callback)) {
            #throw new ErrorException("Plugin '{$type}' calling '{$name}' not callable");
            #$this->trigger_error("Plugin '{$type}' calling '{$name}' not callable\n");
            //do nothing to not break the workflow
        } else {
            $smarty->registered_plugins[ $type ][ $name ] = array($callback, (bool) $cacheable, (array) $cache_attr);
        }
        return $obj;
    }

    /**
     * Assign one or multiple template variable
     *
     * Args:
     *      - Either a variable name, or an array of variables
     *      - Either NULL or the variable content.
     * Returns:
     *      - True
     * @access public
    */
    function assign(string|iterable $tpl_var, iterable|string|int|bool|float|null $value = null) : true
    {
        if (is_array($tpl_var)) {
            foreach($tpl_var AS $key => $val) {
                if ($key != '') {
                    $GLOBALS['tpl'][$key] = $tpl_var[$key];
                }
            }
        } else {
            $GLOBALS['tpl'][$tpl_var] = $value;
        }

        return true;
    }

    /**
     * Assign one or multiple template variable by reference - Smarty API Change > 3.0
     *
     * Args:
     *      - Variable name
     *      - Referenced variable
     * Returns:
     *      - True
     * @access public
    */
    function assignByRef(string $tpl_var, iterable|string|int|null &$value) : true
    {
        $GLOBALS['tpl'][$tpl_var] =& $value;

        return true;
    }

    /**
     * Helper function to call a 'serendipity_smarty_xx' function with less parameters.
     *   All serendipity smarty functions called with ($params, Smarty_Internal_Template $template)
     *   need a clone in here without instance of Smarty_Internal_Template,
     *   since an instance of serendipity_smarty_emulator is given!
     *
     * Args:
     *      - Function name to call.
     *      - Array of parameters
     * Returns:
     *      - Output string
     * @access public
     */
    function call(string $funcname, iterable $params) : string
    {
        // enable for cloned methods
        /*if (method_exists($GLOBALS['template'], 'emulator_' . $funcname)) {
            // we need to get rid of instance of Smarty_Internal_Template
            return call_user_func('self::emulator_' . $funcname, $params, $this);
        } else */
        if (function_exists('serendipity_smarty_' . $funcname)) {
            return call_user_func('serendipity_smarty_' . $funcname, $params, $this);
        } elseif (function_exists('serendipity_' . $funcname)) {
            return call_user_func('serendipity_' . $funcname, $params, $this);
        } elseif (function_exists($funcname)) {
            return call_user_func($funcname, $params, $this);
        } else {
            return '<span class="msg_error">ERROR: ' . htmlspecialchars($funcname) . " NOT FOUND.</span>\n";
        }
    }

    /**
     * Outputs a smarty template.
     *
     * Args:
     *      - Full path to template file
     * Returns:
     *      - boolean
     * @access public
     */
    function display(string $resource_name) : bool|int
    {
        return include $resource_name;
    }

    /**
     * Triggers a template error
     *
     * Args:
     *      - Error message
     * Returns:
     *      - void
     * @access public
     */
    function trigger_error(string $txt) : void
    {
        trigger_error("SMARTY EMULATOR ERROR: $txt", E_USER_WARNING);
    }

    /**
     * Echoes a default value. Append multiple values and will output the first non empty value.
     *
     * Args:
     *      - The value to emit
     * Returns:
     *      - The found global value OR False when not
     * @access public
     */
    function getdefault() : string|false
    {
        $vars = func_get_args();
        foreach($vars AS $title) {
            if (!empty($GLOBALS['tpl'][$title])) {
                return $GLOBALS['tpl'][$title];
            }
        }

        return false;
    }

    /**
     * Get config directory
     *
     * Args:
     *      - index of directory to get, NULL to get all
     * Returns:
     *      - array configuration directory
     * @access public
     */
    public function getConfigDir(string|int|null $index = null) : ?iterable
    {
        return $this->getTemplateDir($index, true);
    }

    /**
     * Get template directories
     *
     * Args:
     *      - index of directory to get, null to get all
     *      - $isConfig true for config_dir
     * Returns:
     *      - array list of template directories, OR directory of $index OR NULL
     * @access public
     */
    public function getTemplateDir(string|int|null $index = null, bool $isConfig = false) : ?iterable
    {
        if ($isConfig) {
            $dir = &$this->config_dir;
            $this->_configDirNormalized = false;
        } else {
            $dir = &$this->template_dir;
            $this->_templateDirNormalized = false;
        }
        if ($isConfig ? !$this->_configDirNormalized : !$this->_templateDirNormalized) {
            $this->_normalizeTemplateConfig($isConfig);
        }
        if ($index !== null) {
            $d = $dir[$index] ?? null;
            return $d;
        }
        return $dir;
    }

    /**
     * Normalize path
     *  - remove /./ and /../
     *  - make it absolute if required
     *
     * Args:
     *      - file path
     *      - if true - convert to absolute
     *           false - convert to relative
     *           null - keep as it is but remove /./ /../
     * Returns:
     *      - path string
     * @access public
     */
    public function _realpath(string $path, ?bool $realpath = null) : string
    {
        $nds = $this->ds == '/' ? '\\' : '/';
        // normalize $this->ds
        $path = str_replace($nds, $this->ds, $path);
        preg_match('%^(?<root>(?:[[:alpha:]]:[\\\\]|/|[\\\\]{2}[[:alpha:]]+|[[:print:]]{2,}:[/]{2}|[\\\\])?)(?<path>(?:[[:print:]]*))$%',
                   $path, $parts);
        $path = $parts[ 'path' ];
        if ($parts[ 'root' ] == '\\') {
            $parts[ 'root' ] = substr(getcwd(), 0, 2) . $parts[ 'root' ];
        } else {
            if ($realpath !== null && !$parts[ 'root' ]) {
                $path = getcwd() . $this->ds . $path;
            }
        }
        // remove noop 'DIRECTORY_SEPARATOR DIRECTORY_SEPARATOR' and 'DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR' patterns
        $path = preg_replace('#([\\\\/]([.]?[\\\\/])+)#', $this->ds, $path);
        // resolve '..DIRECTORY_SEPARATOR' pattern, smallest first
        if (str_contains($path, '..' . $this->ds) &&
            preg_match_all('#(([.]?[\\\\/])*([.][.])[\\\\/]([.]?[\\\\/])*)+#', $path, $match)
        ) {
            $counts = array();
            foreach($match[0] AS $m) {
                $counts[] = (int) ((strlen($m) - 1) / 3);
            }
            sort($counts);
            foreach($counts AS $count) {
                $path = preg_replace('#(([\\\\/]([.]?[\\\\/])*[^\\\\/.]+){' . $count .
                                     '}[\\\\/]([.]?[\\\\/])*([.][.][\\\\/]([.]?[\\\\/])*){' . $count . '})(?=[^.])#',
                                     $this->ds, $path);
            }
        }

        return $parts[ 'root' ] . $path;
    }

    /**
     * Normalize template_dir or config_dir
     *
     * Args:
     *      - $isConfig true for config_dir
     * Returns:
     *      - void
     * @access public
     */
    private function _normalizeTemplateConfig(bool $isConfig) : void
    {
        if ($isConfig) {
            $processed = &$this->_processedConfigDir;
            $dir = &$this->config_dir;
        } else {
            $processed = &$this->_processedTemplateDir;
            $dir = &$this->template_dir;
        }
        if (!is_array($dir)) {
            $dir = (array) $dir;
        }
        foreach($dir AS $k => $v) {
            if (!isset($processed[ $k ])) {
                $dir[ $k ] = $v = $this->_realpath(rtrim($v, "/\\") . $this->ds, true);
                $processed[ $k ] = true;
            }
        }
        $isConfig ? $this->_configDirNormalized = true : $this->_templateDirNormalized = true;
        $isConfig ? $this->_joined_config_dir = join('#', $this->config_dir) :
            $this->_joined_template_dir = join('#', $this->template_dir);
    }

    /**
     * Returns a single template variables
     *
     * @api  Smarty::getTemplateVars()
     * @link http://www.smarty.net/docs/en/api.get.template.vars.tpl
     *
     * Args:
     *      - data string
     * Returns:
     *      - variable value or an empty array of variables
     * @access public
     */
    public function getTemplateVars(string $data) : string|iterable|true
    {
        if (!empty($GLOBALS['tpl'][$data])) {
            return $GLOBALS['tpl'][$data];
        } else {
            $_result = '';
            return $_result;
        }
    }

    /**
     * Parses a template file into another.
     *
     * Args:
     *      - The path to the resource name (prefixed with 'file:' usually)
     *      - The Cache ID (not used)
     *      - The Compile ID (not used)
     *      - Output data (true) or return it (false)?
     * Returns:
     *      - Content string OR boolean
     * @access public
     */
    function &fetch(string $resource_name, ?string $cache_id = null, ?string $compile_id = null, ?bool $display = false) : string|bool
    {
        $resource_name = str_replace('file:', '', $resource_name);

        if (!$display) {
            ob_start();
        }

        if (file_exists($resource_name)) {
            include $resource_name;
        } else {
            return false;
        }

        if (!$display) {
            $out = ob_get_contents();
            ob_end_clean();
            return $out;
        } else {
            return true;
        }
    }

    /**
     * clear the given assigned template variable(s).
     *
     * Args:
     *      - $tpl_var the template variable(s) to clear
     * Returns:
     *      - \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty
     * @access public
     */
    public function clearAssign(string|iterable $tpl_var) : string|iterable
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var AS $curr_var) {
                unset($GLOBALS['tpl'][$data]->tpl_vars[$curr_var]);
            }
        } else {
            unset($GLOBALS['tpl'][$data]->tpl_vars[$tpl_var]);
        }
        return $GLOBALS['tpl'][$data];
    }

}

/**
 * @author Garvin Hicking, Ian Styx
 * @state EXPERIMENTAL
 *
 * XML Engine
 */

class serendipity_smarty_emulator_xml extends serendipity_smarty_emulator
{
    /**
     * Check matching backend routings
     *
     * Args:
     *      -
     * Returns:
     *      - boolean
     * @access public
     */
    function match() : bool
    {
        if (isset($GLOBALS['matches'][2]) && $GLOBALS['matches'][2] == 'admin/serendipity_styx.js') {
            return false;
        } elseif (isset($GLOBALS['matches'][1]) && $GLOBALS['matches'][1] == 'serendipity_admin.js') {
            return false;
        } elseif (isset($GLOBALS['matches'][1]) && $GLOBALS['matches'][1] == 'serendipity_admin.css') {
            return false;
        } elseif (defined('IN_serendipity_admin')) {
            return false;
        } elseif (isset($GLOBALS['matches'][1]) && $GLOBALS['matches'][1] == 'plugin' && isset($GLOBALS['matches'][2])) {
            return false;
        }
        return true;
    }

    /**
     * Test install
     *
     * Args:
     *      - Error string OR NULL
     * Returns:
     *      - void
     * @access public
     */
    public function testInstall(?string &$errors = null) : void
    {
        $_stream_resolve_include_path = function_exists('stream_resolve_include_path');

        // test if all registered template_dir are accessible
        foreach($GLOBALS['template']->getTemplateDir() AS $template_dir) {
            $_template_dir = $template_dir;
            $template_dir = realpath($template_dir);
            // resolve include_path or fail existence
            if (!$template_dir) {
                if ($smarty->use_include_path && !preg_match('/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/', $_template_dir)) {
                    // try PHP include_path
                    if ($_stream_resolve_include_path) {
                        $template_dir = stream_resolve_include_path($_template_dir);
                    } else {
                        $template_dir = $smarty->ext->_getIncludePath->getIncludePath($_template_dir, null, $smarty);
                    }

                    if ($template_dir !== false) {
                        if ($errors === null) {
                            echo "$template_dir is OK.\n";
                        }

                        continue;
                    } else {
                        $status = false;
                        $message = "FAILED: $_template_dir does not exist (and couldn't be found in include_path either)";
                        if ($errors === null) {
                            echo $message . ".\n";
                        } else {
                            $errors[ 'template_dir' ] = $message;
                        }

                        continue;
                    }
                } else {
                    $status = false;
                    $message = "FAILED: $_template_dir does not exist";
                    if ($errors === null) {
                        echo $message . ".\n";
                    } else {
                        $errors[ 'template_dir' ] = $message;
                    }

                    continue;
                }
            }

            if (!is_dir($template_dir)) {
                $status = false;
                $message = "FAILED: $template_dir is not a directory";
                if ($errors === null) {
                    echo $message . ".\n";
                } else {
                    $errors[ 'template_dir' ] = $message;
                }
            } elseif (!is_readable($template_dir)) {
                $status = false;
                $message = "FAILED: $template_dir is not readable";
                if ($errors === null) {
                    echo $message . ".\n";
                } else {
                    $errors[ 'template_dir' ] = $message;
                }
            } else {
                if ($errors === null) {
                    echo "$template_dir is OK.\n";
                }
            }
        }

    }

    /**
     * Parses a template file into another.
     *
     * Args:
     *      - resource name string
     *      - cache ID OR NULL
     *      - compile ID OR NULL
     *      - Whether to display? (unused)
     * Returns:
     *      - file content OR boolean
     * @access public
     */
    function &fetch(string $resource_name, ?string $cache_id = NULL, ?string $compile_id = NULL, ?bool $display = false) : string|bool
    {
        if (isset($GLOBALS['matches'][2]) && $GLOBALS['matches'][2] == 'admin/serendipity_styx.js') {
            if (!is_object($GLOBALS['serendipity']['smarty'])) {
                ob_start();
                $tfile = $GLOBALS['serendipity']['serendipityPath'] . $GLOBALS['serendipity']['templatePath'] . 'default/admin/serendipity_styx.js.php';
                echo "/* Dynamically fetched {$GLOBALS['serendipity']['templatePath']}default/admin/serendipity_styx.js.php on " . date('Y-m-d H:i') . ", called by: serendipity_smarty_emulator_xml class */\n";
                include $tfile;
                $out = ob_get_contents();
                ob_end_clean();
                return $out;
            } else {
                return file_get_contents(serendipity_getTemplateFile('admin/serendipity_styx.js.tpl', 'serendipityPath'));
            }
            return false;
        }
        return true;
    }

    /**
     * Outputs a smarty template.
     *
     * Args:
     *      - resource name string
     * Returns:
     *      - boolean
     * @access public
     */
    function display(string $resource_name) : bool|int
    {
        if (!$this->match()) { return false; }
        echo "</serendipity>\n";
        return true;
    }

    /**
     * PHP5 constructor
     *
     * Args:
     *      -
     * Returns:
     *      - May return false
     * @access public
     */
    function __construct()
    {
        if (!$this->match()) { return false; }
        header('Content-Type: text/xml; charset=' . LANG_CHARSET);
        echo '<?xml version="1.0" encoding="' . LANG_CHARSET . '" ?>' . "\n";
        /*
        echo '<?xml-stylesheet href="' . serendipity_getTemplateFile('xml.css') . '" type="text/css" ?>' . "\n";
        */
        echo "<serendipity>\n";
        ob_end_flush(); // This ends the started ob from index.php!
    }

    /**
     * Assign one or multiple template variable
     * @TODO: Why can't this function accept references. This sucks.
     *
     * Args:
     *      - Either a variable name, or an array of variables
     *      - Either null or the variable content.
     * Returns:
     *      - boolean
     * @access public
     */
    function assign(string|iterable $tpl_var, iterable|string|int|bool|float|null $value = null, int $level = 0) : true
    {
        if (!$this->match()) { return false; }
        if (is_array($tpl_var)) {
            foreach($tpl_var AS $key => $val) {
                $this->createXML($level, $key, $val);
            }
        } else {
            $this->createXML($level, $tpl_var, $value);
        }

        return true;
    }

    /**
     * Assign one or multiple template variable by reference - Smarty API Change > 3.0
     *
     * Args:
     *      - Variable name
     *      - Referenced variable
     * Returns:
     *      - boolean
     * @access public
     */
    function assignByRef(string $tpl_var, iterable|string|int|null &$value) : true
    {
        if (!$this->match()) { return false; }
        if (is_array($value)) {
            foreach($value AS $key => $val) {
                $this->createXML($level, $key, $val);
            }
        } else {
            $this->createXML($level, $tpl_var, $value);
        }

        return true;
    }

    /**
     * Create the XML output for an element
     *
     * Args:
     *      - The intend level
     *      - The XML element name
     *      - The XML element value
     * Returns:
     *      - May return False or NULL
     * @access public
     */
    function createXML(int &$level, int|string &$key, string|iterable &$val) : ?false
    {
        if (is_null($level)) { return null; }
        if (!$this->match()) { return false; }
        if (is_numeric($key)) {
            $openkey  = 'item index="' . $key . '"';
            $closekey = 'item';
        } else {
            $openkey = $closekey = str_replace(':', '_', $key);
        }

        if (is_array($val)) {
            echo str_repeat("\t", $level) . "<$openkey>\n";
            $this->assign($val, null, $level + 1);
            echo str_repeat("\t", $level) . "</$closekey>\n";
        } else {
            echo str_repeat("\t", $level) . "<$openkey>" . htmlspecialchars($val) . "</$closekey>\n";
        }
    }

}
