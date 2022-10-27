<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

$serendipity = array();

if (!defined('PATH_SEPARATOR')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        define('PATH_SEPARATOR', ';');
    } else {
        define('PATH_SEPARATOR', ':');
    }
}

if (!defined('DIRECTORY_SEPARATOR')) {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        define('DIRECTORY_SEPARATOR', '\\');
    } else {
        define('DIRECTORY_SEPARATOR', '/');
    }
}

/**
 * Create a snapshot of the current memory usage
 *
 * This functions makes use of static function properties to store the last used memory and the intermediate snapshots.
 * @access public
 * @param  string   A label for debugging output
 * @return boolean  Return whether the snapshot could be evaluated
 */
function memSnap($tshow = '') {
    static $avail    = null;
    static $show     = true;
    static $memUsage = 0;

    if (!$show) {
        return false;
    }

    if ($avail === false) {
        return true;
    } elseif ($avail === null) {
        if (function_exists('memory_get_usage')) {
            $avail = memory_get_usage();
        } else {
            $avail = false;
            return false;
        }
    }

    if ($memUsage === 0) {
        $memUsage = $avail;
    }

    $current = memory_get_usage();
    $memUsage = $current;
    return '[' . date('d.m.Y H:i') . '] ' . number_format($current - $memUsage, 2, ',', '.') . ' label "' . $tshow . '", totaling ' . number_format($current, 2, ',', '.') . '<br />' . "\n";
}

/**
 * Make fatal Errors readable
 *
 * @access public
 *
 * @return string  constant error string as Exception
 */
function fatalErrorShutdownHandler() {
    $last_error = @error_get_last();
    if (@$last_error['type'] === E_ERROR) {
        // fatal error send to
        errorToExceptionHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
    }
}

/**
 * Make readable error types for debugging error_reporting levels
 *
 * @access public
 * @param  int     error value
 * @return string  constant error string
 */
function debug_ErrorLevelType($type) {
    switch($type)
    {
        case E_ERROR: // 1 //
            return 'E_ERROR';
        case E_WARNING: // 2 //
            return 'E_WARNING';
        case E_PARSE: // 4 //
            return 'E_PARSE';
        case E_NOTICE: // 8 //
            return 'E_NOTICE';
        case E_CORE_ERROR: // 16 //
            return 'E_CORE_ERROR';
        case E_CORE_WARNING: // 32 //
            return 'E_CORE_WARNING';
        case E_COMPILE_ERROR: // 64 //
            return 'E_COMPILE_ERROR';
        case E_COMPILE_WARNING: // 128 //
            return 'E_COMPILE_WARNING';
        case E_USER_ERROR: // 256 //
            return 'E_USER_ERROR';
        case E_USER_WARNING: // 512 //
            return 'E_USER_WARNING';
        case E_USER_NOTICE: // 1024 //
            return 'E_USER_NOTICE';
        case E_STRICT: // 2048 //
            return 'E_STRICT';
        case E_RECOVERABLE_ERROR: // 4096 //
            return 'E_RECOVERABLE_ERROR';
        case E_DEPRECATED: // 8192 //
            return 'E_DEPRECATED';
        case E_USER_DEPRECATED: // 16384 //
            return 'E_USER_DEPRECATED';
    }
    return '';
}

/**
 * Set our own Exception handler to convert all errors into Exceptions automatically
 * function_exists() avoids 'cannot redeclare previously declared' fatal errors in XML feed context.
 *
 * See Notes about returning false
 * PHP 7.2 note - The $errcontext argument contains all local variables of the error site. Given its rare usage,
 * and the problems it causes with internal optimisations, it has now been deprecated. Instead,
 * a debugger should be used to retrieve information on local variables at the error site.
 *
 * @access public
 * @param  standard
 * @return null
 */
if (!function_exists('errorToExceptionHandler')) {
    function errorToExceptionHandler($errNo, $errStr, $errFile = '', $errLine = NULL, $errContext = array()) {
        global $serendipity;

        // By default, we will continue our process flow, unless exit is true:
        $exit = false;

        switch ($errNo) {
            case E_ERROR:
            case E_USER_ERROR:
                $type = 'Fatal Error';
                $exit = true;
                break;
            case E_USER_WARNING:
            case E_WARNING:
                $type = 'Warning';
                break;
            case E_USER_NOTICE:
            case E_NOTICE:
            case @E_STRICT:
            case @E_DEPRECATED:
            case @E_USER_DEPRECATED:
                $type = 'Notice';
                break;
            case @E_RECOVERABLE_ERROR:
                $type = 'Catchable';
                break;
            default:
                $type = 'Unknown Error';
                $exit = true;
                break;
        }

        // NOTE: We do NOT use ini_get('error_reporting'), because that would return the global error reporting,
        // and not the one in our current content. @-silenced errors would otherwise never be caught on.
        $rep  = error_reporting();
        $args = func_get_args();

        // Bypass error processing because it's @-silenced. Bit operator & !!
        if (!($rep & $errNo)) {
            return false; // Silenced
        }
        // if not using Serendipity testing and user or ISP has set PHPs display_errors to show no errors at all, respect this:
        if ($serendipity['production'] === true && ini_get('display_errors') == 0) {
            return false;
        }
        // Several plugins might not adapt to proper style. This should not completely kill our execution. Additionally: PHP 8 throws notices for our constant fallback mechanism overall. Work around this behaviour until further.
        if ($serendipity['production'] !== 'debug' && (preg_match('@Declaration.*should be compatible with@i', $args[1]) || preg_match('@Constant.*already defined@i', $args[1]))) {
            #if (!headers_sent()) echo "<strong>Compatibility warning:</strong> Please upgrade file old '{$args[2]}', it contains incompatible signatures.<br/>Details: {$args[1]}<br/>";
            return false;
        }

        /*
         * $serendipity['production'] can be:
         *
         * (bool) TRUE:         Live-blog, conceal error messages
         * (bool) FALSE         rc/beta/alpha/cvs builds
         * (string) 'debug'     Developer build, specifically enabled.
         */
        $debug_note = ($serendipity['production'] !== 'debug' && !in_array($type, ['Warning', 'Notice', 'Catchable']))
            ? "<br />\n".'For more details set $serendipity[\'production\'] = \'debug\' in serendipity_config_local.inc.php to receive a full stack-trace.'
            : '';
        $head = '';

        // Debug environments shall be verbose... (with the exception of warnings we'd like to suppress, while our workflow is build on it!)
        if ($serendipity['production'] === 'debug') {
            if (!in_array($type, ['Warning', 'Notice', 'Catchable'])) {
                echo " == ERROR-REPORT (DEBUGGING ENABLED) == <br />\n";
                echo " == (When you copy this debug output to a forum or other places, make sure to remove your username/passwords, as they may be contained within function calls) == \n";
                echo "<pre>\n";
                // trying to be as detailed as possible - but avoid using args containing sensible data like passwords
                if (function_exists('debug_backtrace')) {
                    $debugbacktrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 8);
                    print_r($debugbacktrace);
                    $dbt = 1;
                }
                // print_r($args); // debugging [Use with care! Not to public, since holding password and credentials!!!]
                // debugbacktrace is nice, but additional it is good to have the verbosity of SPL EXCEPTIONS, except for db connect errors
                echo "</pre>\n";
                $debug_note = '';
            } else {
                return false; // DEVs, just disable to see them fail! (As being the simplest approach. For development cases there is more finetuning possible!)
            }
        }
        if ($serendipity['production'] === false) {
            $head = " == ERROR-REPORT (RC/BETA/ALPHA-BUILDS) == \n";
            // Display error (production: FALSE and trigger_errors with E_USER_ERROR
            if (!isset($serendipity['dbConn']) || !$serendipity['dbConn'] || $exit) {
                echo '<p>'.$head.'</p><p><b>' . $type . ':</b> '.$errStr . ' in ' . $errFile . ' on line ' . $errLine . '.' . $debug_note . "</p>\n";
            } else {
                if (!empty($debug_note) && false !== strpos($errStr, $debug_note)) echo $head . $debug_note."\n\n";
                // die into Exception, else echo to page top (if not caught within a HTML element like select) and resume
                if (!in_array($type, ['Warning', 'Notice', 'Catchable'])) {
                    echo '<pre style="white-space: pre-line;">'."\n\n";
                    throw new \ErrorException("$type: $errStr, 0, $errNo, $errFile, $errLine"); // tracepath = all, if not ini_set('display_errors', 0);
                    echo "</pre>\n";
                } else {
                    echo '<div id="serendipity_error_top" class="error_totop"><b>' . $type . ':</b> ' . $errStr . ' in ' . $errFile . ': ' . $errLine . '.' . $debug_note . "</div>\n";
                }
                if (!$serendipity['dbConn'] || $exit) {
                    exit; // make sure to exit in case of database connection errors or fatal errors.
                }
            }
        } else {
            // Only display error (production/debug blog) if an admin is logged in, else we discard the error.
            if (isset($serendipity['serendipityUserlevel']) && $serendipity['serendipityUserlevel'] >= USERLEVEL_ADMIN) {
                if ($serendipity['production'] === 'debug') {
                    $debug_note = "<br />\n" . (!empty($dbt) ? 'See DEBUG tracepath at page end!' : ' == ERROR-REPORT (DEBUGGING ENABLED) ==');
                }
                if ($serendipity['production'] === true) {
                    $debug_note = "<br />\nAdministrative Login Error $type only - not seen by visitors! Send us a note what happened where and when, please.";
                }
                $str = '<div><b>' . $type . ':</b> '.$errStr . ' in ' . $errFile . ': ' . $errLine . '.' . $debug_note . '</div>';
                if (headers_sent()) {
                    serendipity_die($str); // case HTTP headers: needs to halt with die() here,
                                           // else it will pass through and gets written underneath blog content, or into streamed js files, which hardly isn't seen by many users
                } else {
                    echo '<div id="serendipity_error_top" class="error_totop">' . $str . "</div>\n";
                }
            }
        }
    }
}

if (!function_exists('file_get_contents')) {
    function file_get_contents($filename, $use_include_path = 0) {
        $file = fopen($filename, 'rb', $use_include_path);
        $data = '';
        if ($file) {
            while (!feof($file)) {
                $data .= fread($file, 4096);
            }
            fclose($file);
        }

        return $data;
    }
}

if (extension_loaded('filter') && function_exists('input_name_to_filter') && input_name_to_filter(ini_get('filter.default')) !== FILTER_UNSAFE_RAW) {
    foreach($_POST AS $key => $value) {
        $_POST[$key] = input_get(INPUT_POST, $key, FILTER_UNSAFE_RAW);
    }
    foreach($_GET AS $key => $value) {
        $_GET[$key] = input_get(INPUT_GET, $key, FILTER_UNSAFE_RAW);
    }
    foreach($_COOKIE AS $key => $value) {
        $_COOKIE[$key] = input_get(INPUT_COOKIE, $key, FILTER_UNSAFE_RAW);
    }
    // NOT YET IMPLEMENTED IN PHP:
    /*
    foreach($_SESSION AS $key => $value) {
        $_SESSION[$key] = input_get(INPUT_SESSION, $key, FILTER_UNSAFE_RAW);
    }
    */
}

if (extension_loaded('filter') && function_exists('filter_id') && function_exists('filter_input') && filter_id(ini_get('filter.default')) !== FILTER_UNSAFE_RAW) {
    foreach($_POST AS $key => $value) {
        $_POST[$key] = filter_input(INPUT_POST, $key, FILTER_UNSAFE_RAW);
    }
    foreach($_GET AS $key => $value) {
        $_GET[$key] = filter_input(INPUT_GET, $key, FILTER_UNSAFE_RAW);
    }
    foreach($_COOKIE AS $key => $value) {
        $_COOKIE[$key] = filter_input(INPUT_COOKIE, $key, FILTER_UNSAFE_RAW);
    }

    // NOT YET IMPLEMENTED IN PHP:
    /*
    foreach($_SESSION AS $key => $value) {
        $_SESSION[$key] = filter_input(INPUT_SESSION, $key, FILTER_UNSAFE_RAW);
    }
    */
}

// Merge GET and POST and COOKIE into the global serendipity array - referenced
// It is vital that also an empty array is mapped as a reference
// because the s9y core actually sets new array key values sometimes in $_GET and
// sometimes in $serendipity['GET'] (and POST/COOKIE).
// It is IMHO not advised to unify $_GET & ['GET'] into $serendipity['GET'] generally,
// since using both is NOT by accident or BAD coding than a sensible finetuned reaction
// on different states of the workflow! (See for example external_plugin redirections.)
// IMHO: Playing around to unify this will probably bork some essential extended features!
if (!array_key_exists('serendipity', $_GET) || !is_array($_GET['serendipity'])) {
    $serendipity['GET'] = array();
}
$serendipity['GET'] = &$_GET['serendipity'];
// We don't (!) need to do this pre-check/set on $_POST and $_COOKIE since they ARE set!
$serendipity['POST'] = &$_POST['serendipity'];
$serendipity['COOKIE'] = &$_COOKIE['serendipity'];

// Attempt to fix IIS compatibility
if (empty($_SERVER['REQUEST_URI'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['SCRIPT_NAME'] . '?' . (!empty($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '');
}

/**
 * Translate values coming from the Database into native PHP variables to detect boolean values.
 *
 * @access public
 * @param   string      input value
 * @return  boolean     boolean output value
 */
function serendipity_get_bool($item) {
    static $translation = array('true'  => true,
                                'false' => false);

    if (isset($translation[$item])) {
        return $translation[$item];
    } else {
        return $item;
    }
}

/**
 * Get the current charset
 *
 * @return  string      Empty string or "UTF-8/".
 */
function serendipity_getCharset() {
    global $serendipity;

    $charset = $serendipity['charset'] ?? 'UTF-8/';
    if (!empty($_POST['charset'])) {
        if ($_POST['charset'] == 'UTF-8/') {
            $charset = 'UTF-8/';
        } else {
            $charset = '';
        }
    }

    if (!empty($serendipity['POST']['charset'])) {
        if ($serendipity['POST']['charset'] == 'UTF-8/') {
            $charset = 'UTF-8/';
        } else {
            $charset = '';
        }
    }
    return $charset;
}

/**
 * Detect the language of the User Agent/Visitor
 *
 * This function needs to be included at this point so that it is globally available, also
 * during installation.
 *
 * @access public
 * @param   boolean     Toggle whether to include the language that has been autodetected.
 * @return  string      Return the first detected language name
 */
function serendipity_detectLang($use_include = false) {
    global $serendipity;

    $supported_languages = array_keys($serendipity['languages']);
    $possible_languages  = explode(',', ($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? ''));
    if (is_array($possible_languages)) {
        $charset = serendipity_getCharset();

        foreach($possible_languages AS $index => $lang) {
            $preferred_language = strtolower(preg_replace('@^([^\-_;]*)_?.*$@', '\1', $lang));
            if (in_array($preferred_language, $supported_languages)) {
                if ($use_include) {
                    @include_once(S9Y_INCLUDE_PATH . 'lang/' . $charset . 'serendipity_lang_' . $preferred_language . '.inc.php');
                    $serendipity['autolang'] = $preferred_language;
                }
                return $preferred_language;
            }
        }
    }

    return $serendipity['lang'] ?? null; // default fallback to avoid unset lang key warnings
}

/**
 * Get the current serendipity version, minus the "-alpha", "-beta" or whatever tags
 *
 * @access public
 * @param  string   Serendipity version
 * @return string   Serendipity version, stripped of unneeded parts
 */
function serendipity_getCoreVersion($version) {
    return preg_replace('@^([0-9\.]+).*$@', '\1', $version);
}

/**
 * Make Serendipity emit an error message and terminate the script
 *
 * @access public
 * @param   string  HTML error to die with
 * @param   bool    By null is for Maintenance mode
 * @return null
 */
function serendipity_die($html, $error = true) {
    $charset = !defined('LANG_CHARSET') ? 'UTF-8' : LANG_CHARSET;
    $title   = $error ? 'Fatal Error' : '503 Service unavailable';
    $name    = $error ? 'Error' : 'Maintenance';
    $color   = $error ? 'crimson' : 'midnightblue';
    $type    = $error ? 'an error' : 'maintenance';
    $what    = $error ? 'suspended' : 'unavailable';
    $help    = $error ? '<p>Please inform the Administrator of this site!</p>' : '';
    die('<!DOCTYPE html>
<html lang="en">
<head>
    <meta content="text/html; charset=' . $charset . '" http-equiv="Content-Type">
    <meta name="robots" content="noindex,nofollow" />

    <title>' . $title . '</title>
    <style>
        html { height: 100%; font-family: \'Terminal Dosis\', calibri, tahoma, sans-serif; }
        body { color: ' . $color . '; background: -webkit-gradient(linear, left top, right bottom, from(#fff), to(#e1d9d9)) fixed; background: linear-gradient(135deg, #fff 0%, #e1d9d9) fixed; }
        .container { width: 60rem; margin: 10rem auto; }
        h1 { font-weight: bold; line-height: 1.125; margin-bottom: 0.1rem; }
        h2 { margin:0 }
        .msg_alert { display: block; margin: 1.5em 0; padding: .5em; width: 95%; background: #f2dede; border: 1px solid #e4b9b9; color: #b94a48; }
        .msg_alert .logo { float: right; margin-top: -6rem; margin-right: 2rem; }
        .msg_alert .logo:after { clear: right; }
    </style>
</head>
<body>
    <div class="container">
        <h1> ' . $name . ' </h1>
        <h2> System is temporarily unavailable </h2>
        <p> Due to '.$type.', the system is temporarily ' . $what . '. </p>
        <p> Please visit us again in a few minutes. </p>
        <div class="msg_alert">' . $html . '</div>
        ' . $help . '
    </div>
</body>
</html>');
}

/*
 *  Some defaults for our config vars.
 *  They are likely to be overwritten later in the code
 */
$serendipity['templatePath'] = 'templates/';
if (!isset($serendipity['serendipityPath'])) {
    $serendipity['serendipityPath'] = (defined('S9Y_INCLUDE_PATH') ? S9Y_INCLUDE_PATH : './');
}

$serendipity['indexFile'] = 'index.php';

if (function_exists('date_default_timezone_get')) {
    // We currently offer no Timezone setting (only offset to UTC), so we
    // rely on the OS' timezone.
    @date_default_timezone_set(@date_default_timezone_get());
}

/**
 * Serendipity htmlspecialchars mapper
 * ... not yet using PHP 8.1.0 default flags ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 - maybe with Styx 4.0
 */
function serendipity_specialchars($string, $flags = null, $encoding = LANG_CHARSET, $double_encode = true) {
    $flags = ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE;
    if (!$encoding || $encoding == 'LANG_CHARSET') {
        // if called before LANG_CHARSET is set, we need to set a fallback encoding to not throw a PHP warning that
        // would kill s9y blogs sometimes (https://github.com/s9y/Serendipity/issues/236)
        $encoding = 'UTF-8';
    }

    return htmlspecialchars((string)$string, $flags, $encoding, $double_encode);
}

/**
 * Serendipity htmlentities mapper
 * @see serendipity_specialchars()
 */
function serendipity_entities($string, $flags = null, $encoding = LANG_CHARSET, $double_encode = true) {
    $flags = ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE;
    if (!$encoding || $encoding == 'LANG_CHARSET') {
        $encoding = 'UTF-8';
    }
    return htmlentities((string)$string, $flags, $encoding, $double_encode);
}

/**
 * Serendipity html_entity_decode mapper
 * @see serendipity_specialchars()
 */
function serendipity_entity_decode($string, $flags = null, $encoding = LANG_CHARSET) {
    $flags = ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE;
    if (!$encoding || $encoding == 'LANG_CHARSET') {
        $encoding = 'UTF-8';
    }
    return html_entity_decode((string)$string, $flags, $encoding);
}

/* vim: set sts=4 ts=4 expandtab : */
