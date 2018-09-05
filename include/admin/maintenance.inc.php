<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

$data = array();

// do not move to end of switch, since this will change smarty assignment scope
ob_start();
include S9Y_INCLUDE_PATH . 'include/admin/import.inc.php';
$data['importMenu'] = ob_get_contents();
ob_end_clean();

$usedSuffixes = @serendipity_db_query("SELECT DISTINCT(thumbnail_name) AS thumbSuffix FROM {$serendipity['dbPrefix']}images", false, 'num');

$data['dbUtf8mb4_ready']     = isset($serendipity['dbUtf8mb4_ready']) ? $serendipity['dbUtf8mb4_ready'] : null;
$data['dbUtf8mb4']           = isset($serendipity['dbUtf8mb4']) ? $serendipity['dbUtf8mb4'] : null;
$data['dbUtf8mb4_converted'] = isset($serendipity['dbUtf8mb4_converted']) ? $serendipity['dbUtf8mb4_converted'] : null;
$data['urltoken']            = serendipity_setFormToken('url');
$data['formtoken']           = serendipity_setFormToken();
$data['thumbsuffix']         = $serendipity['thumbSuffix'];
$data['dbnotmysql']          = ($serendipity['dbType'] == 'mysql' || $serendipity['dbType'] == 'mysqli') ? false : true;
$data['suffixTask']          = (is_array($usedSuffixes) && count($usedSuffixes) > 1) ? true : false;

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
                /* TODO: Enable*/
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

    case 'runcleanup':
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
