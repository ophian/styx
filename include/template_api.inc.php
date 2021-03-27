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

  - NOTA BENE: Be aware that many smarty function calls are just wrappers to Serendipity API
    calls. To save grandma's performance pennies you should search the original Serendipity API
    function before calling them with the $GLOBALS['template']->call() wrapper! This costs dearly.

 The Serendipity Admin backend will still make use of Smarty. It rocks.

 Know your PHP before you think about using this. :-)
*/

/* wrapper fake class */
class Smarty
{
    /**
     * smarty version
     */
    const SMARTY_VERSION = '3.1.34~'; // while some Plugins check if Smarty::SMARTY_VERSION is defined to switch API methods
}

/* PHP template Smarty emulator */
class serendipity_smarty_emulator
{
    var $compile_dir = '/tmp'; // Not used
    var $security_settings = array(); // Not used

    /**
     * flags for normalized template directory entries
     *
     * @var array
     */
    protected $_processedConfigDir = array(); // cloned from Smarty, since used in methods below

    /**
     * template directory
     *
     * @var array
     */
    protected $template_dir = array('./templates/', './plugins/'); // cloned from Smarty, and extended with plugins, since used in methods below

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
     * @param \Smarty_Internal_TemplateBase|\Smarty_Internal_Template|\Smarty $obj
     * @param  string                                                         $type       plugin type
     * @param  string                                                         $name       name of template tag
     * @param  callback                                                       $callback   PHP callback to register
     * @param  bool                                                           $cacheable  if true (default) this
     *                                                                                    function is cache able
     * @param  mixed                                                          $cache_attr caching attributes if any
     *
     * @return \Smarty|\Smarty_Internal_Template
     * @throws SmartyException              when the plugin tag is invalid
     */
    public function registerPlugin($obj, $type, $name, $callback = null, $cacheable = true, $cache_attr = null)
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
     * @param   mixed       Either a variable name, or an array of variables
     * @param   mixed       Either null or the variable content.
     * @access public
     * @return null
    */
    function assign($tpl_var, $value = null)
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
     * @param   string      Variable name
     * @param   mixed       Referenced variable
     * @access public
     * @return null
    */
    function assignByRef($tpl_var, &$value)
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
     * @param   string      Function name to call.
     * @param   array       Array of parameters
     * @access public
     * @return  string      Output
     */
    function call($funcname, $params)
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
            return '<span class="msg_error">ERROR: ' . serendipity_specialchars($funcname) . " NOT FOUND.</span>\n";
        }
    }

    /**
     * Outputs a smarty template.
     *
     * @param   string      Full path to template file
     * @access public
     * @return boolean
     */
    function display($resource_name)
    {
        return include $resource_name;
    }

    /**
     * Triggers a template error
     *
     * @param   string      Error message
     * @access public
     * @return null
     */
    function trigger_error($txt)
    {
        trigger_error("SMARTY EMULATOR ERROR: $txt", E_USER_WARNING);
    }

    /**
     * Echoes a default value. Append multiple values and will output the first non empty value.
     *
     * @param   mixed    The value to emit.
     * @access public
     * @return null
     */
    function getdefault()
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
     * @param mixed $index index of directory to get, null to get all
     *
     * @return array configuration directory
     */
    public function getConfigDir($index = null)
    {
        return $this->getTemplateDir($index, true);
    }

    /**
     * Get template directories
     *
     * @param mixed $index    index of directory to get, null to get all
     * @param bool  $isConfig true for config_dir
     *
     * @return array list of template directories, or directory of $index
     */
    public function getTemplateDir($index = null, $isConfig = false)
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
     * @param string $path      file path
     * @param bool   $realpath  if true - convert to absolute
     *                          false - convert to relative
     *                          null - keep as it is but remove /./ /../
     *
     * @return string
     */
    public function _realpath($path, $realpath = null)
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
        if (strpos($path, '..' . $this->ds) != false &&
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
     * @param bool $isConfig true for config_dir
     *
     */
    private function _normalizeTemplateConfig($isConfig)
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
     * @param $data
     *
     * @return mixed variable value or an empty array of variables
     */
    public function getTemplateVars($data)
    {
        if (!empty($GLOBALS['tpl'][$data])) {
            return $GLOBALS['tpl'][$data];
        } else {
            $_result = array();
            return $_result;
        }
    }

    /**
     * Parses a template file into another.
     *
     * @param   string      The path to the resource name (prefixed with 'file:' usually)
     * @param   string      The Cache ID (not used)
     * @param   string      The Compile ID (not used)
     * @param   boolean     Output data (true) or return it (false)?
     * @access public
     * @return null
     */
    function &fetch($resource_name, $cache_id = null, $compile_id = null, $display = false)
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
     * @param string|array $tpl_var the template variable(s) to clear
     *
     * @return \Smarty_Internal_Data|\Smarty_Internal_Template|\Smarty
     */
    public function clearAssign($tpl_var)
    {
        if (is_array($tpl_var)) {
            foreach ($tpl_var as $curr_var) {
                unset($GLOBALS['tpl'][$data]->tpl_vars[$curr_var]);
            }
        } else {
            unset($GLOBALS['tpl'][$data]->tpl_vars[$tpl_var]);
        }
        return $GLOBALS['tpl'][$data];
    }

}

/**
 * @author Garvin Hicking, Ian
 * @state EXPERIMENTAL
 *
 * XML Engine
 */

class serendipity_smarty_emulator_xml extends serendipity_smarty_emulator
{
    /**
     * Check matching backend routings
     */
    function match() {
        if (isset($GLOBALS['matches'][2]) && $GLOBALS['matches'][2] == 'admin/serendipity_styx.js') {
            return false;
        } elseif ($GLOBALS['matches'][1] == 'serendipity_admin.js') {
            return false;
        } elseif ($GLOBALS['matches'][1] == 'serendipity_admin.css') {
            return false;
        } elseif (defined('IN_serendipity_admin')) {
            return false;
        } elseif ($GLOBALS['matches'][1] == 'plugin' && isset($GLOBALS['matches'][2])) {
            return false;
        }
        return true;
    }

    /**
     * Test install
     *
     * @param null $errors
     */
    public function testInstall(&$errors = null)
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
     * @access public
     * @return null
     */
    function &fetch($resource_name, $cache_id = NULL, $compile_id = NULL, $display = false)
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
     * @access public
     * @return null
     */
    function display($resource_name)
    {
        if (!$this->match()) { return false; }
        echo "</serendipity>\n";
        return true;
    }

    /**
     * PHP5 constructor
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
     * @param   mixed       Either a variable name, or an array of variables
     * @param   mixed       Either null or the variable content.
     * @access public
     * @return null
     */
    function assign($tpl_var, $value = null, $level = 0)
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
     * @param   string      Variable name
     * @param   mixed       Referenced variable
     * @access public
     * @return null
     */
    function assignByRef($tpl_var, &$value)
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
     * @param  int     The intend level
     * @param  mixed   The XML element name
     * @param  mixed   The XML element value
     */
    function createXML(&$level, &$key, &$val)
    {
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
            echo str_repeat("\t", $level) . "<$openkey>" . serendipity_specialchars($val) . "</$closekey>\n";
        }
    }

}
