<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

/**
 * Convert a PHP Ini setting to a boolean flag
 *
 * @access public
 * @param   mixed       input variable
 * @return  boolean     output variable
 */
function serendipity_ini_bool($var) {
    return ($var === 'on' || $var == '1');
}

/**
 * convert a size value from a PHP.ini to a bytesize
 *
 * @access public
 * @param   string  size value from PHP.ini
 * @return  string  bytesize
 */
function serendipity_ini_bytesize($val) {
    if (!is_numeric($val) || $val == '')
        return 0;

    switch(substr($val, -1)) {
        case 'k':
        case 'K':
            return (int) ($val * 1024);
            break;
        case 'm':
        case 'M':
            return (int) ($val * 1048576);
            break;
        default:
            return $val;
   }
}

/**
 * Update the serendipity_config_local.inc.php file with core information
 *
 * @access public
 * @param   string  Database name
 * @param   string  Database prefix
 * @param   string  Database host
 * @param   string  Database user
 * @param   string  Database password
 * @param   string  Database type
 * @param   string  Use persistent connections?
 * @param   array   An array of additional variables to be put into the config file
 * @return true
 */
function serendipity_updateLocalConfig($dbName, $dbPrefix, $dbHost, $dbUser, $dbPass, $dbType, $dbPersistent, $privateVariables = null) {
    global $serendipity;
    umask(0000);

    $file = 'serendipity_config_local.inc.php';
    $path = $serendipity['serendipityPath'];

    $oldconfig = @file_get_contents($path . $file);
    $configfp  = fopen($path . $file, 'w');

    if (!is_resource($configfp)) {
        $errs[] = sprintf(FILE_WRITE_ERROR, $file);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chown -R www:www', $path) . ' (' . WWW_USER . ')';
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chmod 770'       , $path);
        $errs[] = BROWSER_RELOAD;

        return $errs;
    }

    if (isset($_POST['sqlitedbName']) && !empty($_POST['sqlitedbName'])) {
        $dbName = $_POST['sqlitedbName'];
    }

    $file_start    = "<?php\n"
                   . "\t/*\n"
                   . "\t  Serendipity configuration file\n";
    $file_mark     = "\n\t// End of Serendipity configuration file"
                   . "\n\t// You can place your own special variables after here:\n";
    $file_end      = "\n?>";
    $file_personal = '';

    preg_match('@' . preg_quote($file_start) . '.*' . preg_quote($file_mark) . '(.+)' . preg_quote($file_end) . '@imsU', $oldconfig, $match);
    if (!empty($match[1])) {
        $file_personal = $match[1];
    }

    fwrite($configfp, $file_start);

    fwrite($configfp, "\t  Written on ". date('r') ."\n");
    fwrite($configfp, "\t*/\n\n");

    fwrite($configfp, "\t\$serendipity['versionInstalled']  = '{$serendipity['version']}';\n");
    fwrite($configfp, "\t\$serendipity['dbName']            = '" . addslashes($dbName) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbPrefix']          = '" . addslashes($dbPrefix) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbHost']            = '" . addslashes($dbHost) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbUser']            = '" . addslashes($dbUser) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbPass']            = '" . addslashes($dbPass) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbType']            = '" . addslashes($dbType) . "';\n");
    fwrite($configfp, "\t\$serendipity['dbPersistent']      = ". (serendipity_db_bool($dbPersistent) ? 'true' : 'false') .";\n");
    if ($serendipity['dbNames']) {
        if (defined('SQL_CHARSET') && !defined('SQL_CHARSET_INIT')) {
            fwrite($configfp, "\t\$serendipity['dbCharset']         = '" . addslashes(SQL_CHARSET) . "';\n");
        } else {
            if ($dbType == 'mysqli' && $serendipity['dbUtf8mb4_converted']) {
                fwrite($configfp, "\t\$serendipity['dbCharset']         = 'utf8mb4';\n");
            } else {
                fwrite($configfp, "\t\$serendipity['dbCharset']         = 'utf8';\n");
            }
        }
    }

    if (is_array($privateVariables) && count($privateVariables) > 0) {
        foreach($privateVariables AS $p_idx => $p_val) {
            fwrite($configfp, "\t\$serendipity['{$p_idx}']  = '" . addslashes($p_val) . "';\n");
        }
    }

    fwrite($configfp, $file_mark .  $file_personal . $file_end);

    fclose($configfp);

    @chmod($path . $file, 0700);
    return true;
}

/**
 * Setup the core database tables
 *
 * Creates the needed tables - beware, they will be empty and need to be stuffed with
 * default templates and such...
 *
 * @param  dbType POST install data
 * @access public
 * @return null
 */
function serendipity_installDatabase($type = '') {
    global $serendipity;

    // PostgreSQL and SQLite do not care about string length, other than as required by the SQL standard and define the N in varchar(N) as characters (not bytes).
    // MySQL decided to store codepoints in fixed-size areas and can not index columns larger than 767 bytes. "UTF8MB4" has an index length limitation of VARCHAR(191).
    // Not exactly! Its 767 bytes with InnoDB. Dunno on ORACLE MySQL MyISAM...
    //              A 1000 bytes with MyISAM/ARIA. And even 2000 bytes with ARIA as of MariaDB 10.5.
    //              Thus having 191, 250, 255 (normal) varchar field max key length with different engines and some other variations with Indexes on multi-fields.
    if ($type == 'mysqli') {
        // Print the MySQL version
        $serendipity['db_server_info'] = mysqli_get_server_info($serendipity['dbConn']); // eg.  == 5.5.5-10.4.11-MariaDB
        // be a little paranoid...
        if (substr($serendipity['db_server_info'], 0, 6) === '5.5.5-') {
            // strip any possible added prefix having this 5.5.5 version string (which was never released). PHP up from 8.0.16 now strips it correctly.
            $serendipity['db_server_info'] = str_replace('5.5.5-', '', $serendipity['db_server_info']);
        }
        $db_version_match = explode('-', $serendipity['db_server_info']);
        if (stristr(strtolower($serendipity['db_server_info']), 'mariadb')) {
            if (version_compare($db_version_match[0], '10.5.0', '>=')) {
                $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db.sql'); // 255 - MariaDB 10.5 ARIA versions with max key 2000 bytes
            } elseif (version_compare($db_version_match[0], '10.3.0', '>=')) {
                $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db_mb4-k1.sql'); // 250 - MariaDB 10.3 and 10.4 ARIA versions with max key 1000 bytes
            } else {
                $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db_mb4.sql'); // 191 - for old InnoDB
            }
        } else {
            // Oracle MySQL - https://dev.mysql.com/doc/refman/5.7/en/innodb-limits.html
            if (version_compare($db_version_match[0], '5.7.7', '>=')) {
                $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db.sql'); // 255 varchar key length - InnoDB (Since MySQL 5.7 innodb_large_prefix is enabled by default allowing up to 3072 bytes)
            } else {
                $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db_mb4.sql'); // 191 varchar key length - old Oracles MySQL InnoDB (191 characters * 4 bytes = 764 bytes which is less than the maximum length of 767 bytes allowed when innoDB_large_prefix is disabled)
            }
        }
    } else {
        $serendipity['db_server_info'] = 'other';
        $queries = serendipity_parse_sql_tables(S9Y_INCLUDE_PATH . 'sql/db.sql'); // sQLite / PostgreSQL
    }
    $queries = str_replace('{PREFIX}', $serendipity['dbPrefix'], $queries);

    foreach($queries AS $query) {
        $return = serendipity_db_schema_import($query);
        if (is_string($return)) {
            echo "SQL-ERROR: " . $return . "<br>\n";
            echo "QUERY: <pre>" . $query . "</pre><br>\n";
        }
    }

    if (file_exists(S9Y_INCLUDE_PATH . 'sql/preload.sql')) {
        $queries = serendipity_parse_sql_inserts(S9Y_INCLUDE_PATH . 'sql/preload.sql');
        $queries = str_replace('{PREFIX}', $serendipity['dbPrefix'], $queries);
        foreach($queries AS $query) {
            $return = serendipity_db_schema_import($query);
            if (is_string($return)) {
                echo "SQL-ERROR: " . $return . "<br>\n";
                echo "QUERY: <pre>" . $query . "</pre><br>\n";
            }
        }
    }
}

/**
 * Check a default value of a config item from the configuration template files
 *
 * @access public
 * @param   string      Name of the config item to check
 * @param   string      The default value, if none is found
 * @param   boolean     If true, it's the personal config template, if false its the global config template
 * @param   string      Protected fields will not be echoed in the HTML form
 * @return  string      The default value
 */
function serendipity_query_default($optname, $default, $usertemplate = false, $type = 'string') {
    global $serendipity;

    /* I won't tell you the password, it's a salted hash anyway, you can't do anything with it */
    if ($type == 'protected' && IS_installed === true) {
        return '';
    }

    switch ($optname) {
        case 'permalinkStructure':
            return $default;

        case 'dbType' :
            if (extension_loaded('PDO') &&
                in_array('pgsql', PDO::getAvailableDrivers())) {
                $type = 'pdo-postgres';
            }
            if (extension_loaded('pgsql')) {
                $type = 'postgres';
            }
            if (extension_loaded('mysql')) {
                $type = 'mysql';
            }
            if (extension_loaded('mysqli')) {
                $type = 'mysqli';
            }
            return $type;

        case 'serendipityPath':
            if (empty($_SERVER['PHP_SELF'])) {
                $test_path1 = $_SERVER['DOCUMENT_ROOT'] . rtrim(dirname($_SERVER['SCRIPT_FILENAME']), '/') . '/';
            } else {
                $test_path1 = $_SERVER['DOCUMENT_ROOT'] . rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/';
            }
            $test_path2 = serendipity_getRealDir(__FILE__);

            if (!empty($_SERVER['ORIG_PATH_TRANSLATED']) && file_exists(dirname($_SERVER['ORIG_PATH_TRANSLATED']) . '/serendipity_admin.php')) {
                return realpath(rtrim(dirname($_SERVER['ORIG_PATH_TRANSLATED']), '/')) . '/';
            }

            if (file_exists($test_path1 . 'serendipity_admin.php')) {
                return $test_path1;
            } elseif (defined('S9Y_DATA_PATH')) {
                // Shared installation!
                return S9Y_DATA_PATH;
            } else {
                return $test_path2;
            }

        case 'serendipityHTTPPath':
            return rtrim(dirname($_SERVER['PHP_SELF']), '/') .'/';

        case 'defaultBaseURL':
        case 'baseURL':
            $ssl  = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on';
            $port = $_SERVER['SERVER_PORT'];

            return sprintf('http%s://%s%s%s',

                            $ssl ? 's' : '',
                            preg_replace('@^([^:]+):?.*$@', '\1', $_SERVER['HTTP_HOST']),
                            (($ssl && $port != 443) || (!$ssl && $port != 80)) ? (':' . $port) : '',
                            rtrim(dirname($_SERVER['PHP_SELF']), '/') .'/'
                   );

        case 'convert':
            $path = array();

            $path[] = ini_get('safe_mode_exec_dir');

            if (isset($_SERVER['PATH'])) {
                $path = array_merge($path, explode(PATH_SEPARATOR, $_SERVER['PATH']));
                // remove unwanted empty or system32 path parts, so that wrong system32/convert.exe is prevented.
                foreach($path AS $pk => $pv) {
                    if (stripos($pv, 'system32') !== false || empty($pv)) {
                        unset($path[$pk]);
                    }
                }
                $path = array_values($path); // 'reindex' array
            }

            /* add some other possible locations to the path while we are at it,
             * as these are not always included in the apache path */
            $path[] = '/usr/X11R6/bin';
            $path[] = '/usr/bin';
            $path[] = '/usr/local/bin';

            foreach($path AS $dir) {
                if (!empty($dir) && (function_exists('is_executable') && @is_readable($dir) && @is_executable($dir . '/convert')) || @is_file($dir . '/convert')) {
                    return $dir . '/convert';
                }

                if (!empty($dir) && (function_exists('is_executable') && @is_readable($dir . '/convert') && @is_executable($dir . '/convert.exe')) || @is_file($dir . '/convert.exe')) {
                    return $dir . '/convert.exe';
                }
            }
            return $default;

        case 'rewrite':
            return serendipity_check_rewrite($default);

        default:
            if ($usertemplate) {
                return serendipity_get_user_var($optname, $serendipity['authorid'], $default);
            }

        return $default;
    }
}

/**
 * Parse a configuration template file
 *
 * @access public
 * @param   string      Path to the s9y configuration template file
 * @param   array       An array of config areas/sections that shall be returned from the template
 * @param   array       Restrict the return of template variables to items containing a specific flag
 * @return  array       An array with configuration items, keys and values
 */
function serendipity_parseTemplate($filename, $areas = null, $onlyFlags = null) {
    global $serendipity;

    $userlevel = $serendipity['serendipityUserlevel'] ?? null;

    if (IS_installed === false) {
        $userlevel = USERLEVEL_ADMIN;
    }

    $config = @include($filename);
    if (! is_array($config)) {
        printf(INCLUDE_ERROR,$filename);
    }

    foreach($config AS $n => $category) {
        /* If $areas is an array, we filter out those categories, not within the array */
        if (is_array($areas) && !in_array($n, $areas)) {
            unset($config[$n]);
            continue;
        }

        foreach($category['items'] AS $i => $item) {
            $items = &$config[$n]['items'][$i];

            if (!isset($items['userlevel']) || !is_numeric($items['userlevel'])) {
                $items['userlevel'] = USERLEVEL_ADMIN;
            }

            if (!isset($items['permission']) && $userlevel < $items['userlevel']) {
                unset($config[$n]['items'][$i]);
                continue;
            } elseif (!is_array($items['permission']) && !serendipity_checkPermission($items['permission'])) {
                unset($config[$n]['items'][$i]);
                continue;
            } elseif (is_array($items['permission'])) {
                $one_found = false;
                $all_found = true;
                foreach($items['permission'] AS $check_permission) {
                    if (serendipity_checkPermission($check_permission)) {
                        $one_found = true;
                    } else {
                        $all_found = false;
                    }
                }

                if (!isset($items['perm_mode'])) {
                    $items['perm_mode'] = 'or';
                }

                if ($items['perm_mode'] == 'or' && !$one_found) {
                    unset($config[$n]['items'][$i]);
                    continue;
                } elseif ($items['perm_mode'] == 'and' && !$one_found && !$all_found) {
                    unset($config[$n]['items'][$i]);
                    continue;
                }
            }

            if (!isset($items['flags']) || !is_array($items['flags'])) {
                $items['flags'] = array();
            }

            if (is_array($onlyFlags)) {
                foreach($onlyFlags AS $onlyFlag) {
                    if (!in_array($onlyFlag, $items['flags'])) {
                        unset($config[$n]['items'][$i]);
                        continue;
                    }
                }
            }
        }

        if (sizeof($config[$n]['items']) < 1) {
            unset($config[$n]);
        }
    }

    return $config;
}

/**
 * Replace some variables within config item values with the right values
 *
 * @access public
 * @param   string  Input string
 * @return  string  Output string
 */
function serendipity_replaceEmbeddedConfigVars($s) {
    return str_replace(
                  array(
                    '%clock%'
                  ),

                  array(
                    date('H:i')
                  ),

                  $s);
}

/**
 * Pre-process the configuration value and put it into a HTML output field (radio, password, text, select, ...)
 *
 * @access public
 * @param   string  The type of the configuration item
 * @param   string  The name of the configuration item
 * @param   string  The current value of the configuration item
 * @param   string  The default value of the configuration item
 * @return null
 */
function serendipity_guessInput($type, $name, $value = '', $default = '') {
    $data = array();
    $curOptions = array();

    switch ($type) {
        case 'string':
            if ($name == 'enableBackendPopupGranular' && !empty($default)) {
                if ($_GET['serendipity']['adminModule'] == 'users' && $_GET['serendipity']['adminAction'] == 'new') {
                    $value = $default;
                }
            }
            /* no need for nothing else here yet, while this is a special case pre-filled-configuration-set only - just for the USERS administration NEW form */
            break;

        case 'bool':
            $value = serendipity_get_bool($value);
            if ($value === null) {
                $value = $default;
            }
            break;

        case 'multilist':
            $default = (array)$default;
            $value = (array)$value;
            foreach($default AS $k => $v) {
                $selected = false;
                foreach($value AS $vk => $vv) {
                    if ($vv['confkey'] == $v['confkey']) {
                        $selected = true;
                    }
                }
                $curOptions[$name][$k]['selected'] = $selected;
            }
            break;

        case 'list':
            $cval = $value;
            $default = (array)$default;
            foreach($default AS $k => $v) {
                $selected = ($k == $value); // BE strictly unstrict here! Else you may compare ints with strings!
                if (empty($cval) && ($k === 'false' || $k === null)) {
                    $selected = true;
                }
                $curOptions[$name][$k]['selected'] = $selected;
            }
            break;
    }

    $data['type'] = $type;
    $data['name'] = $name;
    $data['value'] = $value;
    $data['default'] = $default;
    $data['selected'] = $curOptions;

    return serendipity_smarty_showTemplate('admin/guess_input.tpl', $data);
}

/**
 * Parses the configuration array and displays the configuration screen
 *
 * @access public
 * @param   array       Configuration superarray
 * @param   array       The previous values submitted by the user
 * @param   boolean     If true, no HTML FORM container will be emitted
 * @param   boolean     If true, the user can turn folded (config) sections on and off
 * @param   boolean     If true, the user can NOT display possibly dangerous options
 * @return null
 */
function serendipity_printConfigTemplate($config, $from = false, $noForm = false, $allowToggle = true, $showDangerous = false) {

    $data = array();
    $data['noForm'] = $noForm;
    $data['formToken'] = serendipity_setFormToken();
    $data['allowToggle'] = $allowToggle;

    foreach($config AS &$category) {
        foreach($category['items'] AS &$item) {

            $value = $from[$item['var']] ?? null;

            /* Calculate value if we are not installed, how clever :) */
            if ($from == false) {
                $value = serendipity_query_default($item['var'], $item['default']);
            }

            /* Check for installOnly flag */
            if (in_array('installOnly', $item['flags']) && IS_installed === true) {
                continue;
            }

            if (in_array('hideValue', $item['flags'])) {
                $value = '';
            }

            if (!$showDangerous && isset($item['view']) && $item['view'] == 'dangerous') {
                continue;
            }

            if (in_array('config', $item['flags']) && isset($from['authorid'])) {
                $value = serendipity_get_user_config_var($item['var'], $from['authorid'], $item['default']);
            }

            if (in_array('parseDescription', $item['flags'])) {
                $item['description'] = serendipity_replaceEmbeddedConfigVars($item['description']);
            }

            if (in_array('probeDefault', $item['flags'])) {
                $item['default'] = serendipity_probeInstallation($item['var']);
            }

            if (in_array('ignore', $item['flags'])) {
                $item['ignore'] = true;
            }

            if (in_array('ifEmpty', $item['flags']) && empty($value)) {
                $value = serendipity_query_default($item['var'], $item['default']);
            }

            $item['guessedInput'] = serendipity_guessInput($item['type'], $item['var'], $value, $item['default']);
        }
    }
    $data['config'] = $config;

    return serendipity_smarty_showTemplate('admin/config_template.tpl', $data);
}

/**
 * Parse .sql files for use within Serendipity, query by query,
 * accepting only CREATE commands.
 *
 * @access public
 * @param   string  The filename of the SQL file
 * @return array    An array of queries to execute
 */
function serendipity_parse_sql_tables($filename) {
    $in_table = 0;
    $queries = array();

    $fp = fopen($filename, 'r', 1);
    if ($fp) {
        while (!@feof($fp)) {
            $line = trim(fgets($fp, 4096));
            if ($in_table) {
                $def .= $line;
                if (preg_match('/^\)\s*(type\=\S+|\{UTF_8\})?\s*\;$/i', $line)) {
                    $in_table = 0;
                    array_push($queries, $def);
                }
            } else {
                if (preg_match('#^create table \{PREFIX\}\S+\s*\(#i', $line)) {
                    $in_table = 1;
                    $def = $line;
                }

                if (preg_match('#^create\s*(\{fulltext\}|unique|\{fulltext_mysql\})?\s*index#i', $line)) {
                    array_push($queries, $line);
                }
            }
        }
        fclose($fp);
    }

    return $queries;
}

/**
 * Parse .sql files for use within Serendipity, query by query,
 * accepting only INSERT commands.
 *
 * @access public
 * @param   string  The filename of the SQL file
 * @return array    An array of queries to execute
 */
function serendipity_parse_sql_inserts($filename) {
    $queries = array();

    $fp = fopen($filename, 'r', 1);
    if ($fp) {
        while (!@feof($fp)) {
            $line = trim(fgets($fp, 65536));
            if (preg_match('#^insert\s*into.*;$#i', $line)) {
                array_push($queries, $line);
            }
        }
    }
    fclose($fp);

    return $queries;
}

/**
 * Check the serendipity Installation for problems, during installation
 *
 * @access public
 * @return boolean  Errors encountered?
 */
function serendipity_checkInstallation() {
    global $serendipity, $umask;

    $errs = array();

    serendipity_initPermalinks();

    $ipath = serendipity_specialchars($_POST['serendipityPath']);
    $upath = serendipity_specialchars($_POST['uploadPath']);

    // Check dirs
    if (!is_dir($_POST['serendipityPath'])) {
        $errs[] = sprintf(DIRECTORY_NON_EXISTANT, $ipath);
    }
    elseif (!is_writable($_POST['serendipityPath']) ) {
        $errs[] = sprintf(DIRECTORY_WRITE_ERROR, $ipath);
    }
    elseif (!is_dir($_POST['serendipityPath'] . $_POST['uploadPath'] ) && @mkdir($_POST['serendipityPath'] . $_POST['uploadPath'], $umask) !== true) {
        $errs[] = sprintf(DIRECTORY_CREATE_ERROR, $ipath . $upath);
    }
    elseif (!is_writable($_POST['serendipityPath'] . $_POST['uploadPath'])) {
        $errs[] = sprintf(DIRECTORY_WRITE_ERROR, $ipath . $upath);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chmod go+rws', $ipath . $upath);
    }

    // Attempt to create the template compile directory, it might already be there, but we just want to be sure
    if (!is_dir($_POST['serendipityPath'] . PATH_SMARTY_COMPILE) && @mkdir($_POST['serendipityPath'] . PATH_SMARTY_COMPILE, $umask) !== true) {
        $errs[] = sprintf(DIRECTORY_CREATE_ERROR, $ipath . PATH_SMARTY_COMPILE);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'mkdir', $ipath . PATH_SMARTY_COMPILE);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chmod go+rwx', $ipath . PATH_SMARTY_COMPILE);
    } elseif (is_dir($_POST['serendipityPath'] . PATH_SMARTY_COMPILE) && !is_writable($_POST['serendipityPath'] . PATH_SMARTY_COMPILE) && @chmod($_POST['serendipityPath'] . PATH_SMARTY_COMPILE, $umask) !== true) {
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chmod go+rwx', $ipath . PATH_SMARTY_COMPILE);
    }

    // Attempt to create the archives directory
    if (!is_dir($_POST['serendipityPath'] . PATH_ARCHIVES) && @mkdir($_POST['serendipityPath'] . PATH_ARCHIVES, $umask) !== true) {
        $errs[] = sprintf(DIRECTORY_CREATE_ERROR, $ipath . PATH_ARCHIVES);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'mkdir', $ipath . PATH_ARCHIVES);
        $errs[] = sprintf(DIRECTORY_RUN_CMD, 'chmod go+rwx', $ipath . PATH_ARCHIVES);
    }

    // Check imagick
    if ($_POST['magick'] == 'true' && function_exists('is_executable') && !@is_executable($_POST['convert'])) {
        $errs[] = sprintf(CANT_EXECUTE_BINARY, 'convert imagemagick');
    }

    if ($_POST['dbType'] == 'sqlite' || $_POST['dbType'] == 'sqlite3' || $_POST['dbType'] == 'pdo-sqlite' || $_POST['dbType'] == 'sqlite3oo') {
        // We don't want that our SQLite db file can be guessed from other applications on a server
        // and have access to ours. So we randomize the SQLite dbname.
        $_POST['sqlitedbName'] = $_POST['dbName'] . '_' . md5(time());
    }

    if (empty($_POST['dbPrefix']) && empty($serendipity['dbPrefix'])) {
        $errs[] = sprintf(EMPTY_SETTING, INSTALL_DBPREFIX);
    } elseif (!preg_match('@^[a-z0-9_]+$@i', $_POST['dbPrefix'])) {
        $errs[] = INSTALL_DBPREFIX_INVALID;
    }

    if (empty($_POST['pass']) || $_POST['pass'] != $_POST['pass2']) {
        $errs[] = INSTALL_PASSWORD_INVALID;
    }

    $serendipity['dbType'] = preg_replace('@[^a-z0-9-]@imsU', '', $_POST['dbType']);
    // Include the database for the first time
    // For shared installations, probe the file on include path
    include_once(S9Y_INCLUDE_PATH . 'include/db/db.inc.php');

    // possible failure
    if (defined('S9Y_DB_INCLUDED') && S9Y_DB_INCLUDED === true) {
        serendipity_db_probe($_POST, $errs);
    }

    return (count($errs) > 0 ? $errs : '');
}

/**
 * Create the files needed by Serendipity [htaccess/serendipity_config_local.inc.php]
 *
 * @access public
 * @param   string      Path to the serendipity directory
 * @return  true
 */
function serendipity_installFiles($serendipity_core = '') {
    global $serendipity;

    // This variable is transmitted from serendipity_admin_installer. If an empty variable is used,
    // this means that serendipity_installFiles() was called from the auto-updater facility.
    if (empty($serendipity_core)) {
        $serendipity_core = $serendipity['serendipityPath'];
    }

    $htaccess = @file_get_contents($serendipity_core . '.htaccess');

    // Let this function be callable outside installation and let it use existing settings.
    $import = array('rewrite', 'serendipityHTTPPath', 'indexFile');
    foreach($import AS $key) {
        if (empty($_POST[$key]) && isset($serendipity[$key])) {
            $$key = $serendipity[$key];
        } else {
            $$key = $_POST[$key];
        }
    }

    if (php_sapi_name() == 'cgi' || php_sapi_name() == 'cgi-fcgi' || php_sapi_name() == 'fpm-fcgi' || (php_sapi_name() === 'cli' OR defined('STDIN')) || false !== strpos(php_sapi_name(), 'cgi')) {
        $htaccess_cgi = '_cgi';
    } else {
        $htaccess_cgi = '';
    }

    /* If this file exists, a previous install failed painfully. We must consider the safer alternative now */
    if (file_exists($serendipity_core . '.installer_detection_failsafe')) {
        $htaccess_cgi = '_cgi';
        @unlink($serendipity_core . '.htaccess');
    }

    /* Detect compatibility with php_value directives */
    if ($htaccess_cgi == '') {
        $response = '';
        $serendipity_root = dirname($_SERVER['PHP_SELF']) . '/';
        $serendipity_host = preg_replace('@^([^:]+):?.*$@', '\1', $_SERVER['HTTP_HOST']);

        $old_htaccess = @file_get_contents($serendipity_core . '.htaccess');
        $fp = @fopen($serendipity_core . '.htaccess', 'w');
        if ($fp) {
            fwrite($fp, 'php_value register_globals off'. "\n" .'php_value session.use_trans_sid 0');
            fclose($fp);

            $safeFP = @fopen($serendipity_core . '.installer_detection_failsafe', 'w');
            fclose($safeFP);
            $sock = fsockopen($serendipity_host, $_SERVER['SERVER_PORT'], $errorno, $errorstring, 10);
            if ($sock) {
                fputs($sock, "GET {$serendipityHTTPPath} HTTP/1.0\r\n");
                fputs($sock, "Host: $serendipity_host\r\n");
                fputs($sock, "User-Agent: Serendipity/{$serendipity['version']}\r\n");
                fputs($sock, "Connection: close\r\n\r\n");

                while (!feof($sock) && strlen($response) < 4096) {
                    $response .= fgets($sock, 400);
                }
                fclose($sock);
            }

            # If we get HTTP 500 Internal Server Error, we have to use the .cgi template
            if (preg_match('@^HTTP/\d\.\d 500@', $response)) {
                $htaccess_cgi = '_cgi';
            }

            if (!empty($old_htaccess)) {
                $fp = @fopen($serendipity_core . '.htaccess', 'w');
                fwrite($fp, $old_htaccess);
                fclose($fp);
            } else {
                @unlink($serendipity_core . '.htaccess');
            }

            @unlink($serendipity_core . '.installer_detection_failsafe');
        }
    }

    if ($rewrite == 'rewrite2') {
        $template = 'htaccess' . $htaccess_cgi . '_rewrite2.tpl';
    } elseif ($rewrite == 'rewrite') {
        $template = 'htaccess' . $htaccess_cgi . '_rewrite.tpl';
    } elseif ($rewrite == 'errordocs') {
        $template = 'htaccess' . $htaccess_cgi . '_errordocs.tpl';
    } else {
        $template = 'htaccess' . $htaccess_cgi . '_normal.tpl';
    }

    if (!($a = file(S9Y_INCLUDE_PATH . 'include/tpl/' . $template, 1))) {
        $errs[] = ERROR_TEMPLATE_FILE;
    }

    // When we write this file we cannot rely on the constants defined
    // earlier, as they do not yet contain the updated contents from the
    // new config. Thus we re-define those. We do still use constants
    // for backwards/code compatibility.

    $PAT = serendipity_permalinkPatterns(true);

    $content = str_replace(
                 array(
                   '{PREFIX}',
                   '{indexFile}',
                   '{PAT_UNSUBSCRIBE}', '{PATH_UNSUBSCRIBE}',
                   '{PAT_ARCHIVES}', '{PATH_ARCHIVES}',
                   '{PAT_FEEDS}', '{PATH_FEEDS}',
                   '{PAT_FEED}',
                   '{PAT_ADMIN}', '{PATH_ADMIN}',
                   '{PAT_ARCHIVE}', '{PATH_ARCHIVE}',
                   '{PAT_PLUGIN}', '{PATH_PLUGIN}',
                   '{PAT_DELETE}', '{PATH_DELETE}',
                   '{PAT_APPROVE}', '{PATH_APPROVE}',
                   '{PAT_SEARCH}', '{PATH_SEARCH}',
                   '{PAT_COMMENTS}', '{PATH_COMMENTS}',
                   '{PAT_CSS}',
                   '{PAT_JS}',
                   '{PAT_PERMALINK}',
                   '{PAT_PERMALINK_AUTHORS}',
                   '{PAT_PERMALINK_FEEDCATEGORIES}',
                   '{PAT_PERMALINK_CATEGORIES}',
                   '{PAT_PERMALINK_FEEDAUTHORS}'
                 ),

                 array(
                   $serendipityHTTPPath,
                   $indexFile,
                   trim($PAT['UNSUBSCRIBE'], '@/i'), $serendipity['permalinkUnsubscribePath'],
                   trim($PAT['ARCHIVES'], '@/i'),    $serendipity['permalinkArchivesPath'],
                   trim($PAT['FEEDS'], '@/i'),       $serendipity['permalinkFeedsPath'],
                   trim(PAT_FEED, '@/i'),
                   trim($PAT['ADMIN'], '@/i'),       $serendipity['permalinkAdminPath'],
                   trim($PAT['ARCHIVE'], '@/i'),     $serendipity['permalinkArchivePath'],
                   trim($PAT['PLUGIN'], '@/i'),      $serendipity['permalinkPluginPath'],
                   trim($PAT['DELETE'], '@/i'),      $serendipity['permalinkDeletePath'],
                   trim($PAT['APPROVE'], '@/i'),     $serendipity['permalinkApprovePath'],
                   trim($PAT['SEARCH'], '@/i'),      $serendipity['permalinkSearchPath'],
                   trim($PAT['COMMENTS'], '@/i'),    $serendipity['permalinkCommentsPath'],
                   trim(PAT_CSS, '@/i'),
                   trim(PAT_JS, '@/i'),
                   trim($PAT['PERMALINK'], '@/i'),
                   trim($PAT['PERMALINK_AUTHORS'], '@/i'),
                   trim($PAT['PERMALINK_FEEDCATEGORIES'], '@/i'),
                   trim($PAT['PERMALINK_CATEGORIES'], '@/i'),
                   trim($PAT['PERMALINK_FEEDAUTHORS'], '@/i')
                 ),

                 implode('', $a)
              );

    $fp = @fopen($serendipity_core . '.htaccess', 'w');
    if (!$fp) {
        $errs[] = sprintf(FILE_WRITE_ERROR, $serendipity_core . '.htaccess') . ' ' . FILE_CREATE_YOURSELF;
        $errs[] = sprintf(COPY_CODE_BELOW , $serendipity_core . '.htaccess', 'serendipity', serendipity_specialchars($content));
        return $errs;
    } else {
        // Check if an old htaccess file existed and try to preserve its contents. Otherwise completely wipe the file.
        if ($htaccess != '' && preg_match('@^(.*)#\s+BEGIN\s+s9y.*#\s+END\s+s9y(.*)$@isU', $htaccess, $match)) {
            // Code outside from s9y-code was found.
            fwrite($fp, $match[1] . $content . $match[2]);
        } else {
            fwrite($fp, $content);
        }
        fclose($fp);
        return true;
    }
}

/**
 * Check the flags of a configuration item for their belonging into a template
 *
 * @access public
 * @param   array       An item to check
 * @param   array       The area (configuration|local) where the config item might be displayed
 * @return  boolean
 */
function serendipity_checkConfigItemFlags(&$item, $area) {
    if (in_array('nosave', $item['flags'])) {
        return false;
    }

    if (in_array('local', $item['flags']) && $area == 'configuration') {
        return false;
    }

    if (in_array('config', $item['flags']) && $area == 'local') {
        return false;
    }

    return true;
}

/**
 * When paths or other options are changed in the s9y configuration, update the core files
 *
 * @access public
 * @return boolean
 */
function serendipity_updateConfiguration() {
    global $serendipity, $umask;

    // Save all basic config variables to the database
    $config = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE);

    if (isset($_POST['sqlitedbName']) && !empty($_POST['sqlitedbName'])) {
        $_POST['dbName'] = $_POST['sqlitedbName'];
    }

    // Password can be hidden in re-configuring, but we need to store old password
    if (empty($_POST['dbPass']) && !empty($serendipity['dbPass'])) {
        $_POST['dbPass'] = $serendipity['dbPass'];
    }

    foreach($config AS $category) {
        foreach($category['items'] AS $item) {
            /* Don't save trash */
            if (!serendipity_checkConfigItemFlags($item, 'configuration')) {
                continue;
            }

            if (!isset($item['userlevel'])) {
                $item['userlevel'] = USERLEVEL_ADMIN;
            }

            // Check permission set. Changes to blogConfiguration or siteConfiguration items
            // always required authorid = 0, so that it be not specific to a userlogin
            if ((isset($serendipity['serendipityUserlevel']) && $serendipity['serendipityUserlevel'] >= $item['userlevel']) || IS_installed === false) {
                $authorid = 0;
            } elseif ($item['permission'] == 'blogConfiguration' && serendipity_checkPermission('blogConfiguration')) {
                $authorid = 0;
            } elseif ($item['permission'] == 'siteConfiguration' && serendipity_checkPermission('siteConfiguration')) {
                $authorid = 0;
            } elseif ($item['permission'] == 'siteAutoUpgrades' && serendipity_checkPermission('siteAutoUpgrades')) {
                $authorid = 0;
            } else {
                $authorid = $serendipity['authorid'];
            }

            if (is_array($_POST[$item['var']])) {
                // Arrays not allowed. Use first index value.
                $_POST[$item['var']] = key($_POST[$item['var']]);

                // If it still is an array, munge it all together.
                if (is_array($_POST[$item['var']])) {
                    $_POST[$item['var']] = @implode(',', $_POST[$item['var']]);
                }
            }

            serendipity_set_config_var($item['var'], $_POST[$item['var']], $authorid);
        }
    }

    // check and set image Libraries WebP file Support w/o notice
    $setWebP = serendipity_checkWebPSupport();
    if ($setWebP) {
        serendipity_set_config_var('hasWebPSupport', 'true', 0);
        $serendipity['useWebPFormat'] = true;
    }

    // check and set image Libraries AV image file Support w/o notice
    $setAVIF = serendipity_checkAvifSupport();
    if ($setAVIF) {
        serendipity_set_config_var('hasAvifSupport', 'true', 0);
        $serendipity['useAvifFormat'] = true;
    }

    if (IS_installed === false || serendipity_checkPermission('siteConfiguration')) {
        return serendipity_updateLocalConfig($_POST['dbName'],
                                             $_POST['dbPrefix'],
                                             $_POST['dbHost'],
                                             $_POST['dbUser'],
                                             $_POST['dbPass'],
                                             $_POST['dbType'],
                                             $_POST['dbPersistent']);
    } else {
        return true;
    }
}

/**
 * Get the root directory of Serendipity
 *
 * @access public
 * @return  string      The root directory of Serendipity
 */
function serendipity_httpCoreDir() {
    if (!empty($_SERVER['SCRIPT_FILENAME']) && substr(php_sapi_name(), 0, 3) != 'cgi') {
        return dirname($_SERVER['SCRIPT_FILENAME']) . '/';
    }

    if (!empty($_SERVER['ORIG_PATH_TRANSLATED'])) {
        return dirname(realpath($_SERVER['ORIG_PATH_TRANSLATED'])) . '/';
    }

    return $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) . '/';
}

/**
 * Delete obsolete files from Serendipity
 *
 * @access public
 * @param   array       List of files to remove (backup is tried)
 * @return boolean
 */
function serendipity_removeFiles($files = null) {
    global $errors;

    if (!is_array($files)) {
        return;
    }

    $backupdir = S9Y_INCLUDE_PATH . 'backup';
    if (!is_dir($backupdir)) {
        @mkdir($backupdir, 0777);
        if (!is_dir($backupdir)) {
            $errors[] = sprintf(DIRECTORY_CREATE_ERROR, $backupdir);
            return false;
        }
    }

    if (!is_writable($backupdir)) {
        $errors[] = sprintf(DIRECTORY_WRITE_ERROR, $backupdir);
        return false;
    }

    foreach($files AS $file) {
        $source   = S9Y_INCLUDE_PATH . $file;
        $sanefile = str_replace('/', '_', $file);
        $target   = $backupdir . '/' . $sanefile;

        if (!file_exists($source)) {
            continue;
        }

        if (file_exists($target)) {
            $target = $backupdir . '/' . time() . '.' . $sanefile; // Backup file already exists. Append with timestamp as name.
        }

        if (!is_writable($source)) {
            $errors[] = sprintf(FILE_WRITE_ERROR, $source) . '<br>';
        } else {
            rename($source, $target);
        }
    }
}

/**
 * Check image libraries for PHP WebP-Format file support by used library
 * @return bool
 */
function serendipity_checkWebPSupport($set = false, $msg = false) {
    global $serendipity;

    if (!isset($serendipity['magick']) || $serendipity['magick'] !== true) {
        if (!function_exists('gd_info')) return false;
        $gd = gd_info();
        $webpSupport = $gd['WebP Support'] ?? false;
        if ($webpSupport === false && $msg) {
            print "<b>WebP-Support</b>: Your current PHP GD Version is ' {$gd['GD Version']}' and has no WebP Support, please upgrade!<br>\n";
        }
    } else {
        @exec($serendipity['convert'] . " -version", $out, $result);
        if ($result == 0 || $result[0] == 0) {
            @preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $out[0], $v);
            if (version_compare($v[1], '6.9.9') <= 0) {
                if ($msg) {
                    print "<b>WebP-Support</b>: Your current ImageMagick Version {$v[1]} is '6.9.9' or older, please upgrade!<br>\n";
                }
                return false;
            } else {
                $webpSupport = true;
            }
        }
    }
    if ($webpSupport && $set) {
        serendipity_set_config_var('hasWebPSupport', 'true', 0);
        $serendipity['useWebPFormat'] = true;
    }

    return $webpSupport;
}

/**
 * Check image libraries for PHP AV-Image-File (avif) support by used library
 * Basically, AVIF is far more expensive to encode than WebP. It is an extremely taxing process with respect to both CPU and memory.
 * Both image libraries (IM/GD) show a huge performance lack building bigger avif images, so we hope the best for the future!
 * @return bool
 */
function serendipity_checkAvifSupport($set = false, $msg = false) {
    global $serendipity;

    if (PHP_VERSION_ID < 80100) {
        return false;
    }

    if (!isset($serendipity['magick']) || $serendipity['magick'] !== true) {
        if (!function_exists('gd_info')) return false;
        $gd = gd_info();
        $avifSupport = $gd['AVIF Support'] ?? false;
        if ($avifSupport === false && $msg) {
            print "<b>AV-Image-File (avif) Support</b>: Your current PHP GD Version is ' {$gd['GD Version']}' and has no AV-Image-File (avif) Support, please upgrade!<br>\n";
        }
    } else {
        @exec($serendipity['convert'] . " -version", $out, $result);
        if ($result == 0 || $result[0] == 0) {
            @preg_match('/ImageMagick ([0-9]+\.[0-9]+\.[0-9]+)/', $out[0], $v); // IM since 7.0.25 supports AVIF
            if (version_compare($v[1], '7.0.24') <= 0) {
                if ($msg) {
                    print "<b>AV-Image-File (avif) Support</b>: Your current ImageMagick Version {$v[1]} is '7.0.24' or older, please upgrade!<br>\n";
                }
                return false;
            } else {
                $avifSupport = true;
            }
        }
    }
    if ($avifSupport && $set) {
        serendipity_set_config_var('hasAvifSupport', 'true', 0);
        $serendipity['useAvifFormat'] = true;
    }

    return $avifSupport;
}

/**
 * Get the real directory of this function file
 *
 * @access public
 * @param   string      A filename to strip extra paths from
 * @return  string      The real directory name
 */
function serendipity_getRealDir($file) {
    $dir = str_replace( '\\', '/', dirname($file));
    $base = preg_replace('@/include$@', '', $dir) . '/';
    return $base;
}

/**
 * Try to detect if apache URL rewriting is available
 *
 * This function makes a dummy HTTP request and sees if it works
 *
 * @access public
 * @param   string      The default option when rewrite fails
 * @return  string      The best preference option for URL rewriting
 */
function serendipity_check_rewrite($default) {
    global $serendipity;

    if (IS_installed === true) {
        return $default;
    }

    if (function_exists('apache_get_modules') ) {
        if (in_array('mod_rewrite', apache_get_modules())) {
            $default = 'rewrite';
            return $default;
        }
    } elseif (function_exists('phpinfo' ) && false === strpos(ini_get('disable_functions'), 'phpinfo')) {
        ob_start();
        phpinfo(INFO_MODULES);
        $phpinfo = ob_get_clean();

        if (false !== strpos($phpinfo, 'mod_rewrite')) {
            $default = 'rewrite';
            return $default;
        }
    }

    $serendipity_root = dirname($_SERVER['PHP_SELF']) . '/';
    $serendipity_core = serendipity_httpCoreDir();
    $old_htaccess     = @file_get_contents($serendipity_core . '.htaccess');
    $fp               = @fopen($serendipity_core . '.htaccess', 'w');
    $serendipity_host = preg_replace('@^([^:]+):?.*$@', '\1', $_SERVER['HTTP_HOST']);

    if (!$fp) {
        printf(HTACCESS_ERROR,
          '<b>chmod go+rwx ' . getcwd() . '/</b>'
        );
        return $default;
    } else {
        fwrite($fp, 'ErrorDocument 404 ' . addslashes($serendipity_root) . 'index.php');
        fclose($fp);

        // Do a request on a nonexistent file to see, if our htaccess allows ErrorDocument
        $sock = @fsockopen($serendipity_host, $_SERVER['SERVER_PORT'], $errorno, $errorstring, 10);
        $response = '';

        if ($sock) {
            fputs($sock, "GET {$_SERVER['PHP_SELF']}nonexistent HTTP/1.0\r\n");
            fputs($sock, "Host: $serendipity_host\r\n");
            fputs($sock, "User-Agent: Serendipity/{$serendipity['version']}\r\n");
            fputs($sock, "Connection: close\r\n\r\n");

            while (!feof($sock) && strlen($response) < 4096) {
                $response .= fgets($sock, 400);
            }
            fclose($sock);
        }

        if (preg_match('@^HTTP/\d\.\d 200@', $response) || preg_match('@^HTTP/\d\.\d 302@', $response)) {
            $default = 'errordocs';
        } else {
            $default = 'none';
        }

        if (!empty($old_htaccess)) {
            $fp = @fopen($serendipity_core . '.htaccess', 'w');
            fwrite($fp, $old_htaccess);
            fclose($fp);
        } else {
            @unlink($serendipity_core . '.htaccess');
        }

        return $default;
    }
}

/**
 * Remove old configuration values that are no longer used by Serendipity
 *
 * @access public
 * @return null
 */
function serendipity_removeObsoleteVars() {

    $config = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE);
    foreach($config AS $category) {
        foreach($category['items'] AS $item) {
            /* Remove trash */
            if (!serendipity_checkConfigItemFlags($item, 'remove')) {
                serendipity_remove_config_var($item['var'], 0);
            }
        }
    }
}

/**
 * Retrieve an FTP-compatible checksum for a file.
 *
 * @access  public
 * @param   string      filename is the path to the file to checksum
 * @param   string      type forces a particular interpretation of newlines.  Mime
 *          types and strings starting with 'text' will cause newlines to be stripped
 *          before the checksum is calculated (default: null, determine from finfo
 *          and extension)
 * @return  string      An MD5 checksum of the file, with newlines removed if it's
 *          an ASCII type; or false if the file cannot be read
 */
function serendipity_FTPChecksum($filename, $type = null) {
    // Only read the finfo database once
    static $debug_exts = array();

    // Must be able to read the file
    if (!is_readable($filename)) {
        return false;
    }

    // Figure out whether it's binary or text by extension
    if ($type == null) {
        $parts = pathinfo($filename);
        $ext = '';
        // Some PHP versions throw a warning if the index doesn't exist
        if (isset($parts['extension'])) {
            $ext = $parts['extension'];
        }
        // If they're case-insensitive equal, strcasecmp() returns 0, or
        // 'false'.  So I use && to find if any of them are 0, in the
        // most likely fail-fast order.
        if (strcasecmp($ext, 'php') &&
            strcasecmp($ext, 'tpl') &&
            strcasecmp($ext, 'sql') &&
            strcasecmp($ext, 'js') &&
            strcasecmp($ext, 'txt') &&
            strcasecmp($ext, 'htc') &&
            strcasecmp($ext, 'css') &&
            strcasecmp($ext, 'dist') &&
            strcasecmp($ext, 'lib') &&
            strcasecmp($ext, 'sh') &&
            strcasecmp($ext, 'html') &&
            strcasecmp($ext, 'htm') &&
            !empty($ext)) {
            if (!in_array($ext, array_keys($debug_exts))) {
                $debug_exts[$ext] = $filename;
            }
            $type = 'bin';
        } else {
            $type = 'text';
        }
    }

    // Calculate the checksum
    $md5 = false;
    if (stristr($type, 'text')) {
        // This is a text-type file.  We need to remove linefeeds before
        // calculating a checksum, to account for possible FTP conversions
        // that are inconvenient, but still valid.  But we don't want to
        // allow newlines anywhere; just different *kinds* of newlines.
        $newlines = array("#\r\n#", "#\r#", "#\n#");
        $file = file_get_contents($filename);
        $file = preg_replace($newlines, ' ', $file);
        $md5 = md5($file);
    } else {
        // Just get its md5sum
        $md5 = md5_file($filename);
    }

    return $md5;
}

/**
 * Validate checksums for all required files.
 *
 * @return A list of all files that failed checksum, where keys are the
 *    relative path of the file, and values are the bad checksum
 */
function serendipity_verifyFTPChecksums() {
    global $serendipity;

    $badsums = array();

    // Load the checksums
    $f = S9Y_INCLUDE_PATH . 'checksums.inc.php';

    if (!file_exists($f) || filesize($f) < 1) {
        return $badsums;
    }

    require_once $f;
    // Verify that every file in the checksum list was uploaded correctly
    $basedir = realpath(dirname(__FILE__) . '/../');

    if (!isset($serendipity['checksums_' . $serendipity['version']]) || !is_array($serendipity['checksums_' . $serendipity['version']])) {
        return $badsums;
    }

    foreach($serendipity['checksums_' . $serendipity['version']] AS $prel => $sum) {
        $path = $basedir . '/' . $prel;
        // Don't take checksums of directories
        if (is_dir($path)) {
            // Weird that it's even here.
            continue;
        }

        // Can't checksum unreadable or nonexistent files
        if (!is_readable($path)) {
            $badsums[$prel] = 'missing';
            continue;
        }

        // Validate checksum
        $calcsum = serendipity_FTPChecksum($path);
        if ($sum != $calcsum) {
            $badsums[$prel] = $calcsum;
            continue;
        }
    }

    return $badsums;
}

/**
 * Check the Serendipity docs/RELEASE file for the newest available (stable/beta) version
 *
 * @return  string/integer  filed version / -1 error code
 */
function serendipity_getCurrentVersion() {
    global $serendipity;

    if ($serendipity['updateCheck'] != 'stable' && $serendipity['updateCheck'] != 'beta') {
        return -1;
    }
    // https://raw.githubusercontent.com/s9y/Serendipity/master/docs/RELEASE
    $config_rv = serendipity_get_config_var('updateReleaseFileUrl', 'https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE');

    $serendipity['updateVersionName'] = (false !== strpos((string)$config_rv, 'styx')) ? 'Styx' : 'Serendipity';

    // Perform update check once a day. We use a suffix of the configured channel, so when
    // the user switches channels, it has its own timer.
    // Since Styx allows 'stable' over 'beta' preference, check a 'stable' overruling a set 'beta' first,
    // in the case last_update_version_'beta' and 'stable' were already set to config,
    if ($serendipity['updateCheck'] == 'beta' && isset($serendipity['last_update_check_stable']) && $serendipity['last_update_check_stable'] >= (time()-86400)) {
        // Last update was performed less than a day ago. Return last result.
        return $serendipity['last_update_version_stable'];
    }
    // then run the normal check.
    if (isset($serendipity['last_update_check_' . $serendipity['updateCheck']]) && $serendipity['last_update_check_' . $serendipity['updateCheck']] >= (time()-86400)) {
        // Last update was performed less than a day ago. Return last result.
        return $serendipity['last_update_version_' . $serendipity['updateCheck']];
    }

    serendipity_set_config_var('last_update_check_' . $serendipity['updateCheck'], time());

    $updateURL = serendipity_specialchars(strip_tags((string)$config_rv));
    $context   = stream_context_create(array('http' => array('timeout' => 5.0)));
    $file      = @file_get_contents($updateURL, false, $context);
    // Some servers return a " Warning: file_get_contents(): https:// wrapper is disabled in the server configuration by allow_url_fopen=0 " so we use Curl instead
    if (!$file) {
        if (function_exists('curl_init')) {
            $ch = curl_init($updateURL);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, '5');
            $file = curl_exec($ch);
            curl_close($ch);
        }
    }

    if ($file) {
        $file = serendipity_specialchars(strip_tags($file));
        if ($serendipity['updateCheck'] == 'stable') {
            if (preg_match('/^stable:(.+)\b/m', $file, $match)) {
                serendipity_set_config_var('last_update_version_' . $serendipity['updateCheck'], $match[1]);
                return $match[1];
            }
        }
        if ($serendipity['updateCheck'] == 'beta') {
            if (preg_match('/^stable:(.+)\b/m', $file, $match)) {
                $stable = $match[1];
            }
            if (preg_match('/^beta:(.+)\b/m', $file, $match)) {
                $beta = $match[1];
            }
            if (version_compare($beta, $stable, '>') ) {
                serendipity_set_config_var('last_update_version_' . $serendipity['updateCheck'], $beta);
                return $beta;
            } else {
                serendipity_set_config_var('last_update_version_' . $serendipity['updateCheck'], $stable);
                return $stable;
            }
        }
    }

    return -1;
}

/* vim: set sts=4 ts=4 sw=4 expandtab : */
