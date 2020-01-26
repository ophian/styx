<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

$data = array();

// do not move to end of switch, since this will change Smarty assignment scope
ob_start();
include S9Y_INCLUDE_PATH . 'include/admin/import.inc.php';
$data['importMenu'] = ob_get_contents();
ob_end_clean();

$keepthemes = [ '_assets', '2k11', 'styx', 'bootstrap4', 'bulletproof', 'clean-blog',
                'default', 'default-php', 'dude', 'next', 'pure', 'skeleton',
                'sliver', 'timeline' ];
$keepevplugins = [ 'bbcode', 'changelog', 'emoticate', 'entryproperties', 'mailer', 'modemaintain',
            'nl2br', 'plugup', 's9ymarkup', 'spamblock', 'spartacus', 'xhtmlcleanup' ];
$keepsbplugins = [ 'archives', 'authors', 'calendar', 'categories', 'comments', 'entrylinks',
            'eventwrapper', 'history', 'html_nugget', 'plug', 'quicksearch', 'recententries',
            'remoterss', 'superuser', 'syndication' ];

if ($serendipity['GET']['adminAction'] == 'cleartemp' || $serendipity['GET']['adminAction'] == 'clearplug') {
    include_once S9Y_INCLUDE_PATH . 'include/functions_upgrader.inc.php';
}

$usedSuffixes = @serendipity_db_query("SELECT DISTINCT(thumbnail_name) AS thumbSuffix FROM {$serendipity['dbPrefix']}images WHERE thumbnail_name != ''", false, 'num');

// UTF8MB4 - check for a possible previous bad install via simple install mode
if ($serendipity['dbUtf8mb4'] === false && $serendipity['dbUtf8mb4_converted'] === null && $serendipity['dbType'] == 'mysqli'){
    if ($serendipity['dbCharset'] == 'utf8mb4' && mysqli_character_set_name($serendipity['dbConn']) == 'utf8mb4') {
        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config SET name='dbUtf8mb4_converted', value='true', authorid=0");
        $serendipity['dbUtf8mb4'] = $serendipity['dbUtf8mb4_converted'] = true;
        echo '<span class="msg_success"><strong>Maintenance:</strong> Automatically fixed a missing database utf8mb4 Collation variable once.</span>';
    }
}
$data['dbUtf8mb4_ready']     = $serendipity['dbUtf8mb4_ready'] ?? null; // Smarty generic
$data['dbUtf8mb4']           = $serendipity['dbUtf8mb4'] ?? null; // Smarty generic
$data['dbUtf8mb4_converted'] = $serendipity['dbUtf8mb4_converted'] ?? null; // Smarty generic
$data['urltoken']            = serendipity_setFormToken('url');
$data['formtoken']           = serendipity_setFormToken();
$data['thumbsuffix']         = $serendipity['thumbSuffix'];
$data['dbnotmysql']          = false;//($serendipity['dbType'] == 'mysql' || $serendipity['dbType'] == 'mysqli') ? false : true; // Remove completely, when new LIKE solution found guilty
$data['dbUtf8mb4_ready']     = in_array($serendipity['dbType'], ['sqlite3', 'sqlite3oo', 'pdo-sqlite', 'postgres', 'pdo-postgres']); // we assume that postgres is at least >= version 9.4, with UTF8 full Unicode, 8-bit, 1-4 Bytes/Char support
$data['suffixTask']          = (is_array($usedSuffixes) && count($usedSuffixes) > 1) ? true : false;
$data['variationTask']       = (!(serendipity_db_bool(serendipity_get_config_var('upgrade_variation_done', 'false')) && !empty($serendipity['upgrade_variation_done']))) ? true : false;
$data['zombP']               = null;
$data['zombT']               = null;

switch($serendipity['GET']['adminAction']) {
    case 'integrity':
        $data['action'] = 'integrity';
        if (strpos($serendipity['version'], '-alpha') || (!is_readable(S9Y_INCLUDE_PATH . 'checksums.inc.php') || 0 == filesize(S9Y_INCLUDE_PATH . 'checksums.inc.php')) ) {
            $data['noChecksum'] = true;
            break;
        }
        $data['badsums'] = serendipity_verifyFTPChecksums();
        break;

    case 'utf8mb4':
        $data['dbUtf8mb4_simulated'] = true;
        if (!serendipity_checkPermission('siteConfiguration') || !serendipity_checkFormToken()) {
            $data['dbUtf8mb4_error'] = PERM_DENIED;
            break;
        }

        if (!function_exists('serendipity_db_migrate_index') || !$serendipity['dbUtf8mb4_ready']) {
            $data['dbUtf8mb4_error'] = 'Not UTF-8mb4 ready.';
        }

        if ($serendipity['dbUtf8mb4']) {

            if (isset($serendipity['POST']['adminOption']['execute'])) {
                $data['dbUtf8mb4_migrate'] = serendipity_db_migrate_index(false, $serendipity['dbPrefix']);
                #echo '<pre>'.print_r($data['dbUtf8mb4_migrate'],1).'</pre>';
                serendipity_set_config_var('dbUtf8mb4_converted', 'true');
                $data['dbUtf8mb4_executed'] = true;
            } else {
                $data['dbUtf8mb4_migrate'] = serendipity_db_migrate_index(true, $serendipity['dbPrefix']);
            }

            if (is_array($data['dbUtf8mb4_migrate']['errors']) && count($data['dbUtf8mb4_migrate']['errors']) > 0) {
                $data['dbUtf8mb4_simulated'] = false;
            }
        }
        break;

    case 'imageorphans':
        if (serendipity_checkPermission('siteConfiguration')) {
            include_once S9Y_INCLUDE_PATH . 'include/admin/mlorphans_task.inc.php';
        }
        break;

    case 'checkplug':
        if (!serendipity_checkPermission('siteConfiguration')) {
            $data['pluginmanager_error'] = PERM_DENIED;
            break;
        }
        $extpluginzs = serendipity_db_query("SELECT a.class_name AS path
                                            FROM {$serendipity['dbPrefix']}pluginlist a
                                       LEFT JOIN {$serendipity['dbPrefix']}pluginlist b
                                              ON a.pluginlocation = 'local' AND b.pluginlocation = 'Spartacus' AND
                                                (b.upgrade_version IS NULL OR b.upgrade_version = '') AND a.upgrade_version < b.version
                                           WHERE a.class_name = b.class_name");
        if (!empty($extpluginzs) && is_array($extpluginzs)) {
            foreach ($extpluginzs AS $pstack) $plugins[] = $pstack['path'];

            $dir = new DirectoryIterator($serendipity['serendipityPath'] . 'plugins');
            foreach ($dir AS $fileinfo) {
                if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                    $dirname = str_replace(array('serendipity_event_', 'serendipity_plugin_'), '', $fileinfo->getFilename());
                    // exclude release plugin names
                    if (!in_array($dirname, $keepevplugins) && !in_array($dirname, $keepsbplugins)) {
                        if (in_array($fileinfo->getFilename(), $plugins)) {
                            #echo $dirname."<br>\n";
                            $data['local_plugins'][$fileinfo->getFilename()] = $dirname;
                        }
                    }
                }
            }
        }
        $data['select_localplugins_total'] = isset($data['local_plugins']) ? count($data['local_plugins']) : 0;
        break;

    case 'clearplug':
        if (!serendipity_checkPermission('siteConfiguration') || !serendipity_checkFormToken()) {
            $data['pluginmanager_error'] = PERM_DENIED;
            break;
        }
        if (isset($serendipity['POST']['clearplug']['multi_plugins']) && is_array($serendipity['POST']['clearplug']['multi_plugins'])) {
            $postplugz = $serendipity['POST']['clearplug']['multi_plugins'];
            $plugzombies = array();
            foreach ($postplugz AS $plugz) {
                $plugzombies[] = $serendipity['serendipityPath'] . 'plugins/' . $plugz;
            }
            if (!empty($plugzombies)) {
                // do purge
                recursive_directory_iterator($plugzombies);
                // test the first for messaging, since method does not return boolean
                if (!is_dir($plugzombies[0])) {
                    $data['zombP'] = true;
                }
            }
        }
        break;

    case 'checktemp':
        if (!serendipity_checkPermission('siteConfiguration')) {
            $data['thememanager_error'] = PERM_DENIED;
            break;
        }
        $dir = new DirectoryIterator($serendipity['serendipityPath'] . 'templates');
        foreach ($dir AS $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $dirname = $fileinfo->getFilename();
                // exclude release theme names
                if (!in_array($dirname, $keepthemes)) {
                    #echo $dirname."<br>\n";
                    if ($dirname != $serendipity['template']) {
                        $data['local_themes'][] = $dirname;
                    }
                }
            }
        }
        $data['select_localthemes_total'] = isset($data['local_themes']) ? count($data['local_themes']) : 0;
        break;

    case 'cleartemp':
        if (!serendipity_checkPermission('siteConfiguration') || !serendipity_checkFormToken()) {
            $data['thememanager_error'] = PERM_DENIED;
            break;
        }
        if (isset($serendipity['POST']['cleartemp']['multi_themes']) && is_array($serendipity['POST']['cleartemp']['multi_themes'])) {
                $postthemes = $serendipity['POST']['cleartemp']['multi_themes'];
                $themezombies = array();
                foreach ($postthemes AS $theme) {
                    // exclude release theme names and prepend fullpath
                    if (!in_array($postthemes, $keepthemes)) {
                        $themezombies[] = $serendipity['serendipityPath'] . 'templates/' . $theme;
                    }
                }
                if (!empty($themezombies)) {
                    // do purge
                    recursive_directory_iterator($themezombies);
                    // test the first for messaging, since method does not return boolean
                    if (!is_dir($themezombies[0])) {
                        $data['zombT'] = true;
                    }
                    // purge spartacus theme template previews cache by theme
                    serendipity_purgeTemplatesCache($themezombies);
                }
        }
        break;

    case 'clearcomp':
        // The Smarty method clearCompiledTemplate() clears all compiled Smarty template files in templates_c and is loaded dynamically by the extension handler when called.
        // Since there may be other compiled template files in templates_c too, we have to restrict this call() to clear the current Blogs template only,
        // to not have the following automated recompile, force the servers memory to get exhausted
        //    (eg. when using plugins like the serendipity_event_gravatar plugin, which can eat up some MB...).
        // Restriction to template means: leave/recompile the page we are on: ie. ../admin/index.tpl and all others, which are set, included and compiled by runtime. (plugins, etc. this can be quite some..!)
        if (is_object($serendipity['smarty'])) {
            // since we use different $compile_id directories for current Backend/Frontend themes to make compilation checks easier and explicit, we now have to clear them both.
            $cct_backend  = $serendipity['smarty']->clearCompiledTemplate(null, $serendipity['template_backend']); // silent result
            $cct_frontend = $serendipity['smarty']->clearCompiledTemplate(null, $serendipity['template']);
            // But we only return the result for the Frontend template
            $data['cleanup_finish']   = $cct_frontend;
            $data['cleanup_template'] = $serendipity['template'];
        }
        break;
}

echo serendipity_smarty_showTemplate('admin/maintenance.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
