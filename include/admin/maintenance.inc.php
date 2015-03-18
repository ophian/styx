<?php

$data = array();

// do not move to end of switch, since this will change smarty assignment scope
ob_start();
include S9Y_INCLUDE_PATH . 'include/admin/import.inc.php';
$data['importMenu'] = ob_get_contents();
ob_end_clean();

switch($serendipity['GET']['adminAction']) {
    case 'integrity':
        $data['action'] = "integrity";

        if (!is_readable(S9Y_INCLUDE_PATH . 'checksums.inc.php') || 0 == filesize(S9Y_INCLUDE_PATH . 'checksums.inc.php') ) {
            $data['noChecksum'] = true;
            break;
        }
        $data['badsums'] = serendipity_verifyFTPChecksums();
        break;

    case 'runcleanup':
        // The smarty method clearCompiledTemplate() clears all compiled smarty template files in templates_c
        // Since there may be other compiled template files in templates_c too, we have to restrict this call() to clear the blogs template only,
        // to not have the following automated recompile, force the servers memory to get exhausted,
        // when using plugins like serendipity_event_gravatar plugin, which can eat up some MB...
        // Restriction to template means: leave the page we are on: ../admin/index.tpl and all others, which are set, included and compiled by runtime. (plugins, etc. this can be quite some..!)
        if(method_exists($serendipity['smarty'], 'clearCompiledTemplate')) {
            $data['cleanup_finish']   = (int)$serendipity['smarty']->clearCompiledTemplate(null, $serendipity['template']);
            $data['cleanup_template'] = $serendipity['template'];
        }
        break;
}

$data['unusedTables'] = array();
$data['unusedPlugins'] = array();

$plugins         = serendipity_plugin_api::get_installed_plugins();
$classes_event   = serendipity_plugin_api::enum_plugin_classes(true);
$classes_sidebar = serendipity_plugin_api::enum_plugin_classes();
$classes         = $classes_event + $classes_sidebar;

switch($serendipity['dbType']) {
    case 'sqlite':
    case 'sqlite3':
    case 'sqlite3oo':
    case 'pdo-sqlite':
        $q = "SELECT tbl_name AS table_name FROM sqlite_master WHERE type = 'table'";
        break;

    case 'pdo-postgres':
    case 'postgres':
        $q = "SELECT table_name FROM information_schema.tables WHERE table_catalog = '{$serendipity['dbName']}'";
        break;

    case 'mysql':
    case 'mysqli':
    default:
        $q = "SELECT table_name FROM information_schema.tables WHERE table_schema = '{$serendipity['dbName']}'";
        break;
}

$tables = serendipity_db_query($q);
$core_tables = array(
    $serendipity['dbPrefix'] . 'authors' => true,
    $serendipity['dbPrefix'] . 'groups' => true,
    $serendipity['dbPrefix'] . 'groupconfig' => true,
    $serendipity['dbPrefix'] . 'authorgroups' => true,
    $serendipity['dbPrefix'] . 'access' => true,
    $serendipity['dbPrefix'] . 'comments' => true,
    $serendipity['dbPrefix'] . 'entries' => true,
    $serendipity['dbPrefix'] . 'references' => true,
    $serendipity['dbPrefix'] . 'exits' => true,
    $serendipity['dbPrefix'] . 'referrers' => true,
    $serendipity['dbPrefix'] . 'config' => true,
    $serendipity['dbPrefix'] . 'options' => true,
    $serendipity['dbPrefix'] . 'suppress' => true,
    $serendipity['dbPrefix'] . 'plugins' => true,
    $serendipity['dbPrefix'] . 'category' => true,
    $serendipity['dbPrefix'] . 'images' => true,
    $serendipity['dbPrefix'] . 'entrycat' => true,
    $serendipity['dbPrefix'] . 'entryproperties' => true,
    $serendipity['dbPrefix'] . 'mediaproperties' => true,
    $serendipity['dbPrefix'] . 'permalinks' => true,
    $serendipity['dbPrefix'] . 'plugincategories' => true,
    $serendipity['dbPrefix'] . 'pluginlist' => true,
);

$legacy_plugintables = array(
    'serendipity_event_spamblock' 
        => array($serendipity['dbPrefix'] . 'spamblocklog'),
    'serendipity_event_staticpage' 
        => array($serendipity['dbPrefix'] . 'staticpages', 
                 $serendipity['dbPrefix'] . 'staticpages_types', 
                 $serendipity['dbPrefix'] . 'staticpage_categorypage', 
                 $serendipity['dbPrefix'] . 'staticpage_custom'),
    'serendipity_event_freetag' 
        => array($serendipity['dbPrefix'] . 'entrytags', 
                 $serendipity['dbPrefix'] . 'tagkeywords'),
    'serendipity_event_ljupdate' 
        => array($serendipity['dbPrefix'] . 'lj_entries'),
    'serendipity_event_trackback' 
        => array($serendipity['dbPrefix'] . 'delayed_trackbacks'),
    'serendipity_event_externalauth' 
        => array($serendipity['dbPrefix'] . 'loginlog'),
    'serendipity_event_photoblog' 
        => array($serendipity['dbPrefix'] . 'photoblog'),
    'serendipity_plugin_adduser' 
        => array($serendipity['dbPrefix'] . 'pending_authors'),
    'serendipity_event_linklist' 
        => array($serendipity['dbPrefix'] . 'links', 
                 $serendipity['dbPrefix'] . 'link_category'),
    'serendipity_event_guestbook' 
        => array($serendipity['dbPrefix'] . 'guestbook'),
    'serendipity_event_mymood' 
        => array($serendipity['dbPrefix'] . 'mymood'),
    'serendipity_event_aggregator' 
        => array($serendipity['dbPrefix'] . 'aggregator_feeds', 
                 $serendipity['dbPrefix'] . 'aggregator_md5', 
                 $serendipity['dbPrefix'] . 'aggregator_feedcat', 
                 $serendipity['dbPrefix'] . 'aggregator_feedlist'),
    'serendipity_event_todolist' 
        => array($serendipity['dbPrefix'] . 'project_colors', 
                 $serendipity['dbPrefix'] . 'percentagedone', 
                 $serendipity['dbPrefix'] . 'project_category'),
    'serendipity_plugin_pollbox' 
        => array($serendipity['dbPrefix'] . 'polls', 
                 $serendipity['dbPrefix'] . 'polls_options'),
    'serendipity_event_userprofiles' 
        => array($serendipity['dbPrefix'] . 'profiles'),
    'serendipity_event_mycalendar' 
        => array($serendipity['dbPrefix'] . 'mycalendar'),
    'serendipity_event_xsstrust' 
        => array($serendipity['dbPrefix'] . 'ethics'),
    'serendipity_plugin_currently' 
        => array($serendipity['dbPrefix'] . 'currently'),
    'serendipity_event_includeentry' 
        => array($serendipity['dbPrefix'] . 'staticblocks'),
    'serendipity_event_categorytemplates' 
        => array($serendipity['dbPrefix'] . 'categorytemplates'),
    'serendipity_event_downloadmanager' 
        => array($serendipity['dbPrefix'] . 'dma_downloadmanager_files', 
                 $serendipity['dbPrefix'] . 'dma_downloadmanager_categories', 
                 $serendipity['dbPrefix'] . 'dma_downloadmanager_categories_tmp'),
    'serendipity_event_backup' 
        => array($serendipity['dbPrefix'] . 'dma_sqlbackup', 
                 $serendipity['dbPrefix'] . 'dma_htmlbackup'),
    'serendipity_event_forum' 
        => array($serendipity['dbPrefix'] . 'dma_forum_boards', 
                 $serendipity['dbPrefix'] . 'dma_forum_threads', 
                 $serendipity['dbPrefix'] . 'dma_forum_posts', 
                 $serendipity['dbPrefix'] . 'dma_forum_uploads', 
                 $serendipity['dbPrefix'] . 'dma_forum_users', 
                 $serendipity['dbPrefix'] . 'dma_forum_uploads_tmp', 
                 $serendipity['dbPrefix'] . 'dma_forum_threads_tmp'),
    'serendipity_event_markread' 
        => array($serendipity['dbPrefix'] . 'marked'),
    'serendipity_event_forgotpassword' 
        => array($serendipity['dbPrefix'] . 'forgotpassword'),
    'serendipity_event_faq' 
        => array($serendipity['dbPrefix'] . 'faqs', 
                 $serendipity['dbPrefix'] . 'faq_categorys'),
    'serendipity_event_versioning' 
        => array($serendipity['dbPrefix'] . 'versioning'),
    'serendipity_event_wikilinks' 
        => array($serendipity['dbPrefix'] . 'wikireferences'),
    'serendipity_event_quicklink' 
        => array($serendipity['dbPrefix'] . 'quicklink'),
    'serendipity_event_suggest' 
        => array($serendipity['dbPrefix'] . 'suggestmails'),
    'serendipity_event_cronjob'
        => array($serendipity['dbPrefix'] . 'cronjoblog'),
    'serendipity_event_adminnotes' 
        => array($serendipity['dbPrefix'] . 'adminnotes', 
                 $serendipity['dbPrefix'] . 'adminnotes_to_groups'),
    'serendipity_event_dejure' 
        => array($serendipity['dbPrefix'] . 'dejure'),
    'serendipity_event_openid' 
        => array($serendipity['dbPrefix'] . 'openid_authors'),
    'serendipity_plugin_twitter' 
        => array($serendipity['dbPrefix'] . 'tweets',
                 $serendipity['dbPrefix'] . 'tweetbackhistory', 
                 $serendipity['dbPrefix'] . 'tweetbackshorturls'),
    'serendipity_event_twitter' 
        => array($serendipity['dbPrefix'] . 'tweets', 
                 $serendipity['dbPrefix'] . 'tweetbackhistory', 
                 $serendipity['dbPrefix'] . 'tweetbackshorturls'),
    'serendipity_event_linktrimmer' 
        => array($serendipity['dbPrefix'] . 'linktrimmer'),
    'serendipity_event_spamblock_bayes' 
        => array($serendipity['dbPrefix'] . 'spamblock_bayes', 
                 $serendipity['dbPrefix'] . 'spamblock_bayes_recycler'),
    'serendipity_event_cal' 
        => array($serendipity['dbPrefix'] . 'eventcal'),
    'serendipity_event_karma' 
        => array($serendipity['dbPrefix'] . 'karmalog', 
                 $serendipity['dbPrefix'] . 'karma'),
    'serendipity_event_oembed' 
        => array($serendipity['dbPrefix'] . 'oembeds'),
    'serendipity_event_realtimecomments' 
        => array($serendipity['dbPrefix'] . 'rtcomments_comments'),
    'serendipity_event_commentspice' 
        => array($serendipity['dbPrefix'] . 'commentspice'),
    'serendipity_event_facebook' 
        => array($serendipity['dbPrefix'] . 'facebook'),
    'serendipity_plugin_shoutbox' 
        => array($serendipity['dbPrefix'] . 'shoutbox'),
    'serendipity_event_statistics' 
        => array($serendipity['dbPrefix'] . 'visitors', 
                 $serendipity['dbPrefix'] . 'visitors_count', 
                 $serendipity['dbPrefix'] . 'refs')
);

if (is_array($tables)) {
    foreach($tables AS $table) {
        // Filter: Only tables that match our own dbPrefix
        if (!preg_match('/^' . preg_quote($serendipity['dbPrefix']) . '/', $table['table_name'])) {
            continue;
        }

        // Filter: No core tables
        if (isset($core_tables[$table['table_name']])) {
            continue;
        }

        $data['unusedTables'][$table['table_name']] = $table['table_name'];
    }
} else {
    $data['unusedTablesError'] = $tables;
    $data['unusedTablesQuery'] = $q;
}

foreach ($classes as $class_data) {
    if (in_array($class_data['true_name'], $plugins)) {
        // Plugin is actively installed, keep associated tables.

        $pluginFile =  serendipity_plugin_api::probePlugin($class_data['name'], $class_data['classname'], $class_data['pluginPath']);
        // spartacus is only set becasue getPluginInfo needs a type parameter
        $plugin     =& serendipity_plugin_api::getPluginInfo($pluginFile, $class_data, 'spartacus');
        if (is_object($plugin)) {
            $bag = new serendipity_property_bag;
            $plugin->introspect($bag);
            if ($bag->is_set('tables')) {
                foreach ($bag->get('tables') AS $table => $definition) {
                    if ($table != 'version') {
                        unset($data['unusedTables'][$table]);
                    }
                }
            }

            if ($legacy_plugintables[$class_data['true_name']]) {
                foreach($legacy_plugintables[$class_data['true_name']] AS $idx => $table) {
                    unset($data['unusedTables'][$table]);
                }
            }
        }
    } else {
        // Plugin is not installed.
        $data['unusedPlugins'][] = $class_data['pluginPath'];
    }
}

echo serendipity_smarty_show('admin/maintenance.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
