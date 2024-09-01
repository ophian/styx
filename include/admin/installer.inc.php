<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

umask(0000);
$umask = 0775;

@define('IN_installer', true);

define('S9Y_I_ERROR', -1);
define('S9Y_I_WARNING', 0);
define('S9Y_I_SUCCESS', 1);

if (empty($_SESSION['install_token'])) {
    $_SESSION['install_token'] = bin2hex(random_bytes(32)); // = strlen 20=40 like sha1 0R 32=64
}

// smartification needs to pull everything first for installation and db purposes
//include(S9Y_INCLUDE_PATH . 'serendipity_config.inc.php');

$data = array();

if (defined('S9Y_DATA_PATH')) {
    // Shared installation. S9Y_INCLUDE_PATH points to repository,
    // S9Y_DATA_PATH points to the local directory.
    $basedir = S9Y_DATA_PATH;
} else {
    // Usual installation within DOCUMENT_ROOT.
    $basedir = serendipity_query_default('serendipityPath', false);
}

$data['is_errors'] = false;
$data['basedir'] = $basedir;
$data['phpversion'] = PHP_VERSION;
$data['versionInstalled'] = $serendipity['versionInstalled'] ?? '';
$data['templatePath'] = $serendipity['templatePath'];
$data['installerHTTPPath'] = str_replace('//', '/', dirname($_SERVER['PHP_SELF']) . '/'); // since different OS handlers for enddir

/**
 * Checks a return code constant if it is successful or an error and return HTML code
 *
 * The diagnosis checks return codes of several PHP checks. Depending
 * on the input, a specially formatted string is returned.
 *
 * @access public
 * @param  int      Return code
 * @param  string   String to return wrapped in special HTML markup
 * @return string   returned String
 */
function serendipity_installerResultDiagnose($result, $s) {
    global $errorCount, $data;
    if ($result === S9Y_I_SUCCESS) {
        $data['i_success'] = true; // we do not need data here explicitly, but we keep it for possible future purposes
        return '<span class="msg_success">'. $s .'</span>';
    }
    if ($result === S9Y_I_WARNING) {
        $data['i_warning'] = true;
        return '<span class="msg_notice">'. $s .'<span class="visuallyhidden"> installerResultDiagnoseNoticeNoDefault [?]</span></span>';
    }
    if ($result === S9Y_I_ERROR) {
        $errorCount++;
        $data['i_error'] = true;
        return '<span class="msg_error">'. $s .'<span class="visuallyhidden"> installerResultDiagnoseError [!]</span></span>';
    }
}

/* If register_globals is enabled and we use the dual GET/POST submission method, we will
   receive the value of the POST-variable inside the GET-variable, which is of course unwanted.
   Thus we transfer a new variable GETSTEP via POST and set that to an internal GET value. */
if (!empty($serendipity['POST']['getstep']) && is_numeric($serendipity['POST']['getstep'])) {
    $serendipity['GET']['step'] = $serendipity['POST']['getstep'];
}

$from = null;

/* From configuration to install */
if (sizeof($_POST) > 1 && $serendipity['GET']['step'] == '3') {
    /* One problem, if the user chose to do an easy install, not all config vars has been transferred
       Therefore we fetch all config vars with their default values, and merge them with our POST data */

    $config = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE);
    foreach($config AS $category) {
        foreach($category['items'] AS $item) {
            if (!isset($_POST[$item['var']])) {
                $_POST[$item['var']] = serendipity_query_default($item['var'], $item['default']);
            }
        }
    }

    if (is_array($errors = serendipity_checkInstallation())) {
        $data['is_errors'] = true;
        $data['errors'] = $errors;
        $from = $_POST;
        /* Back to configuration, user did something wrong */
        $serendipity['GET']['step'] = $data['prevstep'] = $serendipity['POST']['step'];
    } else {
        /* We're good, move to install process */
        $serendipity['GET']['step'] = '3';
    }
}

$serendipity['template'] = $serendipity['template'] ?? '';
$serendipity['GET']['step'] = $serendipity['GET']['step'] ?? 0;
$data['s9yGETstep'] = $serendipity['GET']['step']; // its a mixed type
$data['install_blank'] = false;
$data['getstepint0'] = null;

$install_token = '';
$install_token_file = realpath(dirname(__FILE__) . '/../../') . '/install_token.php';
if (IN_installer === true && IS_installed === false && defined('DEPLOYMENT_PATH')) {
    $install_deployment_token_file = DEPLOYMENT_PATH . '/install_token.php';
}
if ((file_exists($install_token_file) && is_readable($install_token_file) && filesize($install_token_file) > 0)
  || isset($install_deployment_token_file) && file_exists($install_deployment_token_file) && is_readable($install_deployment_token_file) && filesize($install_deployment_token_file) > 0)
{
    $install_token_file = file_exists($install_token_file) ? $install_token_file : $install_deployment_token_file;
    if (preg_match('@install_token\s*=\s*[\'"]([a-z\.0-9]+)[\'"];@imsU', file_get_contents($install_token_file), $tokenmatch)) {
        $install_token = $tokenmatch[1];
    } else {
        $data['install_blank'] = true;
    }
} else {
    // does not exist yet
    $data['install_blank'] = true;
}

$lifetime = (int)ini_get('session.cookie_lifetime');
if ($lifetime == 0 || ini_get('session.gc_maxlifetime') < $lifetime) {
    $lifetime = (int)ini_get('session.gc_maxlifetime');
}

$data['install_token'] = $_SESSION['install_token'];
$data['install_token_pass'] = (!empty($install_token) && !empty($_SESSION['install_token']) && $install_token == $_SESSION['install_token']);
$data['install_token_fail'] = false;
$data['install_token_file'] = basename($install_token_file);
$data['install_lifetime'] = ceil($lifetime/60);
$data['styxversion'] = 'Styx ' . $serendipity['version']; // footer only

if ((int) $serendipity['GET']['step'] !== 0 && !$data['install_token_pass']) {
    // Do not allow user to proceed to any action step unless token matches
    $data['s9yGETstep'] = $serendipity['GET']['step'] = 0;
    $data['install_token_fail'] = true;
}

if ((int) $serendipity['GET']['step'] === 0) {
    if (!empty($install_token) && !$data['install_token_pass']) {
        $data['install_token_fail'] = true;
    }
    $data['getstepint0'] = true;
    $data['print_ERRORS_ARE_DISPLAYED_IN'] = sprintf(ERRORS_ARE_DISPLAYED_IN, serendipity_installerResultDiagnose(S9Y_I_ERROR, RED), serendipity_installerResultDiagnose(S9Y_I_WARNING, YELLOW), serendipity_installerResultDiagnose(S9Y_I_SUCCESS, GREEN));
    $data['s9yversion'] = $serendipity['version'];

    $errorCount = 0;

    if (is_readable(S9Y_INCLUDE_PATH . 'checksums.inc.php')) {
        $badsums = serendipity_verifyFTPChecksums();
        if (empty($badsums)) {
            $data['installerResultDiagnose_CHECKSUMS'][] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, CHECKSUMS_PASS);
        } else {
            foreach($badsums AS $file => $sum) {
                $data['installerResultDiagnose_CHECKSUMS'][] = serendipity_installerResultDiagnose(S9Y_I_WARNING, sprintf(CHECKSUM_FAILED, $file));
            }
        }
    } else {
        $data['installerResultDiagnose_CHECKSUMS'][] = serendipity_installerResultDiagnose(S9Y_I_WARNING, CHECKSUMS_NOT_FOUND);
    }

    $data['php_uname']     = php_uname('s') .' '. php_uname('r') .', '. php_uname('m');
    $data['php_sapi_name'] = php_sapi_name();

    if (version_compare(PHP_VERSION, '8.2.0', '>=')) {
        $data['installerResultDiagnose_VERSION'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES .', '. PHP_VERSION);
    } else {
        $data['installerResultDiagnose_VERSION'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, NO);
    }

    if (sizeof(($_res = serendipity_probeInstallation('dbType'))) == 0) {
        $data['installerResultDiagnose_DBTYPE'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, NONE);
    } else {
        $data['installerResultDiagnose_DBTYPE'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, implode(', ', $_res));
    }

    if (extension_loaded('session')) {
        $data['installerResultDiagnose_SESSION'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_SESSION'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('pcre')) {
        $data['installerResultDiagnose_PCRE'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_PCRE'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('gd')) {
        $data['installerResultDiagnose_GD'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_GD'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('openssl')) {
        $data['installerResultDiagnose_OPENSSL'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
        if (OPENSSL_VERSION_NUMBER >= 269488207) {
            $data['installerResultDiagnose_OPENSSL_version'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
        } else {
            $data['installerResultDiagnose_OPENSSL_version'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
        }
    } else {
        $data['installerResultDiagnose_OPENSSL'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('mbstring')) {
        $data['installerResultDiagnose_MBSTR'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_MBSTR'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('iconv')) {
        $data['installerResultDiagnose_ICONV'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_ICONV'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('xml')) {
        $data['installerResultDiagnose_XML'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_XML'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if (extension_loaded('zlib')) {
        $data['installerResultDiagnose_ZLIB'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
    } else {
        $data['installerResultDiagnose_ZLIB'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
    }

    if ($binary = serendipity_query_default('convert', false)) {
        $data['installerResultDiagnose_IM'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, $binary);
    } else {
        $data['installerResultDiagnose_IM'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NOT_FOUND);
    }

    if (!serendipity_ini_bool(ini_get('safe_mode'))) {
        $data['installerResultDiagnose_SSM'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, 'OFF');
    } else {
        $data['installerResultDiagnose_SSM'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, 'ON');
    }

    if (serendipity_ini_bool(ini_get('register_globals'))) {
        $data['installerResultDiagnose_SRG'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, 'ON');
    } else {
        $data['installerResultDiagnose_SRG'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, 'OFF');
    }

    if (!serendipity_ini_bool(ini_get('session.use_trans_sid'))) {
        $data['installerResultDiagnose_SSUTS'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, 'OFF');
    } else {
        $data['installerResultDiagnose_SSUTS'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, 'ON');
    }

    if (serendipity_ini_bool(ini_get('allow_url_fopen'))) {
        $data['installerResultDiagnose_SAUF'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, 'ON');
    } else {
        $data['installerResultDiagnose_SAUF'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, 'OFF');
    }

    if (serendipity_ini_bool(ini_get('file_uploads'))) {
        $data['installerResultDiagnose_SFU'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, 'ON');
    } else {
        $data['installerResultDiagnose_SFU'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, 'OFF');
    }

    if (serendipity_ini_bytesize(ini_get('post_max_size')) >= (10*1024*1024)) {
        $data['installerResultDiagnose_SPMS'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, ini_get('post_max_size'));
    } else {
        $data['installerResultDiagnose_SPMS'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, ini_get('post_max_size'));
    }

    if (serendipity_ini_bytesize(ini_get('upload_max_filesize')) >= (10*1024*1024)) {
        $data['installerResultDiagnose_SUMF'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, ini_get('upload_max_filesize'));
    } else {
        $data['installerResultDiagnose_SUMF'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, ini_get('upload_max_filesize'));
    }

    if (serendipity_ini_bytesize(ini_get('memory_limit')) >= ((PHP_INT_SIZE == 4 ? 8 : 16)*1024*1024)) {
        $data['installerResultDiagnose_SML'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, ini_get('memory_limit'));
    } else {
        $data['installerResultDiagnose_SML'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, ini_get('memory_limit'));
    }

    $basewritable = False;
    if (is_writable($basedir)) {
        $data['installerResultDiagnose_BASE_WRITABLE'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
        $basewritable = True;
    }

    if (is_writable($basedir . PATH_SMARTY_COMPILE)) {
        $data['installerResultDiagnose_COMPILE'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
    } else {
        if ($basewritable && !is_dir($basedir . PATH_SMARTY_COMPILE)) {
            $data['installerResultDiagnose_COMPILE'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
            #This directory will be created later in the process
        } else {
            $data['installerResultDiagnose_COMPILE'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, NOT_WRITABLE);
            $showWritableNote = true;
        }
    }

    if (is_writable($basedir . 'archives/')) {
        $data['installerResultDiagnose_ARCHIVES'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
    } else {
        if ($basewritable && !is_dir($basedir . 'archives/')) {
            $data['installerResultDiagnose_ARCHIVES'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
            #This directory will be created later in the process
        } else {
            $data['installerResultDiagnose_ARCHIVES'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, NOT_WRITABLE);
            $showWritableNote = true;
        }
    }

    if (is_writable($basedir . 'plugins/')) {
        $data['installerResultDiagnose_PLUGINS'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
    } else {
        $data['installerResultDiagnose_PLUGINS'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NOT_WRITABLE . NOT_WRITABLE_SPARTACUS);
    }

    if (is_dir($basedir .'uploads/')) {
        $data['is_dir_uploads'] = true;
        if (is_writable($basedir . 'uploads/')) {
            $data['installerResultDiagnose_UPLOADS'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
        } else {
            if ($basewritable && !is_dir($basedir . 'uploads/')) {
                $data['installerResultDiagnose_UPLOADS'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, WRITABLE);
                #This directory will be created later in the process
            } else {
                $data['installerResultDiagnose_UPLOADS'] = serendipity_installerResultDiagnose(S9Y_I_ERROR, NOT_WRITABLE);
                $showWritableNote = true;
            }
        }
    }

    if (function_exists('is_executable')) {
        $data['is_imb_executable'] = true;
        if ($binary = serendipity_query_default('convert', false)) {
            if (is_executable($binary)) {
                $data['installerResultDiagnose_IMB'] = serendipity_installerResultDiagnose(S9Y_I_SUCCESS, YES);
            } else {
                $data['installerResultDiagnose_IMB'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NO);
            }
        } else {
            $data['installerResultDiagnose_IMB'] = serendipity_installerResultDiagnose(S9Y_I_WARNING, NOT_FOUND);
        }
    }

    $data['showWritableNote'] = $showWritableNote ?? false;
    $data['errorCount'] = $errorCount;

} elseif ($serendipity['GET']['step'] == '2a') {
    $config = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE, null, array('simpleInstall'));
    $data['ob_serendipity_printConfigTemplate'] = serendipity_printConfigTemplate($config, $from ?? false, true, false);

} elseif ($serendipity['GET']['step'] == '2b') {
    $config = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE);
    $data['ob_serendipity_printConfigTemplate'] = serendipity_printConfigTemplate($config, $from ?? false, true, false);

} elseif ($serendipity['GET']['step'] == '3') {
    $serendipity['dbPrefix'] = $_POST['dbPrefix'];

    // do not allow cheating installer steps
    if (!function_exists('serendipity_db_query') && trim($serendipity['dbPrefix']) == '') {
        serendipity_die('<p class="msg_error">' . ERROR_SOMETHING . '..</p><p>' . SERENDIPITY_INSTALLATION . ': ' . sprintf(SERENDIPITY_NOT_INSTALLED, 'index.php') ." [!]</p>\n");
    }
    $t = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}authors", expectError: true);
    $data['authors_query'] = $t;

    if (is_array($t)) {
        // void
    } else {
        serendipity_installDatabase($_POST['dbType']);
        $data['install_DB'] = true;

        $authorid = serendipity_addAuthor($_POST['user'], $_POST['pass'], $_POST['realname'], $_POST['email'], USERLEVEL_ADMIN);
        if ($authorid === 0) {
            throw new Exception('"Create author ID failed: An unexpected [type] error has occurred. Check your database logs why the last insert ID may have failed"'); // fatal error without doing any more damage
        }
        $mail_comments = serendipity_db_bool($_POST['want_mail']) ? '1' : '0';
        serendipity_set_user_var('mail_comments', $mail_comments, $authorid);
        serendipity_set_user_var('mail_trackbacks', $mail_comments, $authorid);
        serendipity_set_user_var('right_publish', '1', $authorid);
        serendipity_addDefaultGroup('USERLEVEL_EDITOR_DESC', USERLEVEL_EDITOR);
        serendipity_addDefaultGroup('USERLEVEL_CHIEF_DESC',  USERLEVEL_CHIEF);
        serendipity_addDefaultGroup('USERLEVEL_ADMIN_DESC',  USERLEVEL_ADMIN);
        $data['add_authors'] = true;

        serendipity_set_config_var('enableBackendPopupGranular', 'categories,tags,links', $authorid);
        serendipity_set_config_var('template', $serendipity['defaultTemplate']);
        $data['set_template_vars'] = true;

        include_once S9Y_INCLUDE_PATH . 'include/plugin_api.inc.php';
        serendipity_plugin_api::register_default_plugins();
        $data['register_default_plugins'] = true;

        // INSTALLERS utf8mb4 installation/migration logic goes here, right before serendipity_updateLocalConfig is called via serendipity_updateConfiguration()
        if (defined('SQL_CHARSET')) {
            /* Assume utf8mb4_unicode_520_ci OR utf8mb4_unicode_ci OR utf8mb4_general_ci OR a language mb4 server collation variant is set having still latin1 pointed out */
            if (in_array(SQL_CHARSET, ['latin1', 'utf8']) && serendipity_db_bool($_POST['dbNames']) && $_POST['dbType'] == 'mysqli') {
                /* Utilize utf8mb4 for the dbCharset variable, if the server supports that */
                $mysql_version = mysqli_get_server_info($serendipity['dbConn']);
                if (version_compare($mysql_version, '5.5.3', '>=')) {
                    serendipity_set_config_var('dbUtf8mb4_converted', 'true');
                    $serendipity['dbUtf8mb4_converted'] = true;
                    define('SQL_CHARSET_INIT', true);
                }
            }
        }
    }

    $errors = serendipity_installFiles($basedir);
    $data['errors_sif'] = $errors;
    $data['prevstep']   = $serendipity['POST']['step'];

    if (serendipity_updateConfiguration()) {
        $data['s9y_installed'] = true;
    }
    echo serendipity_smarty_showTemplate('admin/installer.inc.tpl', $data);
    return;
}

include_once  dirname(dirname(__FILE__)) . '/functions.inc.php';

if (!isset($serendipity['smarty']) || !is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

$serendipity['smarty']->assign($data);
$tfile = serendipity_getTemplateFile('admin/installer.inc.tpl');

$content = '';
ob_start();
include $tfile;
$content = ob_get_contents();
ob_end_clean();

// eval a string template and do not store compiled code
echo $serendipity['smarty']->display('eval:'.$content);


/* vim: set sts=4 ts=4 expandtab : */
