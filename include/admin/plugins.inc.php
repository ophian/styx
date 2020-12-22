<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('adminPlugins')) {
    return;
}

$data = array();
$core_sidebar_plugins = [
    'serendipity_plugin_archives',
    'serendipity_plugin_authors',
    'serendipity_plugin_calendar',
    'serendipity_plugin_categories',
    'serendipity_plugin_comments',
    'serendipity_plugin_entrylinks',
    'serendipity_plugin_eventwrapper',
    'serendipity_plugin_history',
    'serendipity_plugin_html_nugget',
    'serendipity_plugin_plug',
    'serendipity_plugin_quicksearch',
    'serendipity_plugin_recententries',
    'serendipity_plugin_remoterss',
    'serendipity_plugin_superuser',
    'serendipity_plugin_syndication'
];

include_once S9Y_INCLUDE_PATH . 'include/plugin_api.inc.php';
include_once S9Y_INCLUDE_PATH . 'include/functions_entries_admin.inc.php';
include_once S9Y_INCLUDE_PATH . 'include/functions_plugins_admin.inc.php';
if (!class_exists('Smarty')) {
    @define('SMARTY_DIR', S9Y_PEAR_PATH . 'Smarty/libs/');
    include_once SMARTY_DIR . 'Smarty.class.php';
}

if (isset($_GET['serendipity']['plugin_to_move']) && isset($_GET['submit']) && serendipity_checkFormToken()) {

    if (isset($_GET['serendipity']['event_plugin'])) {
        $plugins = serendipity_plugin_api::enum_plugins('event', false);
    } else {
        $plugins = serendipity_plugin_api::enum_plugins('event', true);
    }

    /* Renumber the sort order to be certain that one actually exists
        Also look for the one we're going to move */
    $idx_to_move = -1;
    for($idx = 0; $idx < count($plugins); $idx++) {
        $plugins[$idx]['sort_order'] = $idx;

        if ($plugins[$idx]['name'] == $_GET['serendipity']['plugin_to_move']) {
            $idx_to_move = $idx;
        }
    }

    /* If idx_to_move is still -1 then we never found it (shouldn't happen under normal conditions)
        Also make sure the swapping idx is around */
    if ($idx_to_move >= 0 && (($_GET['submit'] == 'move down' && $idx_to_move < (count($plugins)-1)) || ($_GET['submit'] == 'move up' && $idx_to_move > 0))) {

        /* Swap the one were moving with the one that's in the spot we're moving to */
        $tmp = $plugins[$idx_to_move]['sort_order'];

        $plugins[$idx_to_move]['sort_order'] = (int)$plugins[$idx_to_move + ($_GET['submit'] == 'move down' ? 1 : -1)]['sort_order'];
        $plugins[$idx_to_move + ($_GET['submit'] == 'move down' ? 1 : -1)]['sort_order'] = (int)$tmp;

        /* Update table */
        foreach($plugins AS $plugin) {
            $key = serendipity_db_escape_string($plugin['name']);
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}plugins SET sort_order = {$plugin['sort_order']} WHERE name='$key'");
        }
    }

    /* TODO: Moving The first Right oriented plugin up,
            or the last left oriented plugin down
            should not be displayed to the user as an option.
            It's a behavior which really has no meaning. */
}

if (isset($_GET['serendipity']['plugin_to_conf'])) {

    /* configure a specific instance */
    $plugin =& serendipity_plugin_api::load_plugin($_GET['serendipity']['plugin_to_conf']);

    if (!($plugin->protected === FALSE || $plugin->serendipity_owner == '0' || $plugin->serendipity_owner == $serendipity['authorid'] || serendipity_checkPermission('adminPluginsMaintainOthers'))) {
        return;
    }
    $data['plugin_to_conf'] = true;

    $bag = new serendipity_property_bag;
    $plugin->introspect($bag);

    if (method_exists($plugin, 'performConfig')) {
        $plugin->performConfig($bag);
    }

    $name    = serendipity_specialchars($bag->get('name'));
    $desc    = serendipity_specialchars($bag->get('description'));
    $license = serendipity_specialchars($bag->get('license'));

    $documentation = $bag->get('website');
    $config_names  = $bag->get('configuration');
    $config_groups = $bag->get('config_groups');

    $data['has_config_groups'] = (!empty($config_groups) && is_array($config_groups)) ? count($config_groups) : 0;

    if (isset($_POST['SAVECONF']) && serendipity_checkFormToken()) {
        /* enum properties and set their values */

        $save_errors = array();
        foreach($config_names AS $config_item) {
            $cbag = new serendipity_property_bag;
            if ($plugin->introspect_config_item($config_item, $cbag)) {
                $value    = $_POST['serendipity']['plugin'][$config_item] ?? '';
                $validate = $plugin->validate($config_item, $cbag, $value);
                if ($validate === true) {
                    if (!empty($_POST['serendipity']['plugin']['override'][$config_item])) {
                        $value = $_POST['serendipity']['plugin']['override'][$config_item];
                    }

                    if (isset($_POST['serendipity']['plugin']['activate']) && isset($_POST['serendipity']['plugin']['activate'][$config_item]) && is_array($_POST['serendipity']['plugin']['activate'][$config_item])) {
                        $active_values = array();
                        foreach($_POST['serendipity']['plugin']['activate'][$config_item] AS $ordered_item_value) {
                            $ordered_item_value;
                            $active_values[] = $ordered_item_value;
                        }
                        $value = implode(',', $active_values);
                    }

                    $plugin->set_config($config_item, $value);
                } else {
                    $save_errors[] = $validate;
                }
            }
        }

        $plugin->cleanup();
    }

    if (isset($save_errors) && is_array($save_errors) && count($save_errors) > 0) {
        $data['save_errors'] = $save_errors;
    } elseif (isset($_POST['SAVECONF'])) {
        $data['saveconf'] = true;
        $data['timestamp'] = serendipity_strftime('%H:%M:%S');
    }
    $data['formToken'] = serendipity_setFormToken();
    $data['name'] = $name;
    $data['class'] = get_class($plugin);
    $data['desc'] = $desc;
    $data['documentation'] = $documentation;
    $data['plugin'] = $plugin;

    // hey, check and assign some info about being stacked or stackable
    $_chckInstalledPlugins = serendipity_plugin_api::get_installed_plugins();
    $_c = array_count_values($_chckInstalledPlugins);
    if ($_c[$data['class']] > 1) $cc = true;

    $data['is_stackable'] = $bag->get('stackable') ?? false;
    $data['no_stack']     = $plugin->instance ? false : true; // we are in an instance so this cannot become true
    $data['has_stack']    = $plugin->instance ? true : false;
    $data['multi_stack']  = $cc ?? false;

    if (@file_exists(dirname($plugin->pluginFile) . '/ChangeLog')) {
        $data['changelog'] = true;
    }

    if (@file_exists(dirname($plugin->pluginFile) . '/documentation_' . $serendipity['lang'] . '.html')) {
        $data['documentation_local'] = '/documentation_' . $serendipity['lang'] . '.html';
    } elseif (@file_exists(dirname($plugin->pluginFile) . '/documentation_en.html')) {
        $data['documentation_local'] = '/documentation_en.html';
    } elseif (@file_exists(dirname($plugin->pluginFile) . '/documentation.html')) {
         $data['documentation_local'] = '/documentation.html';
    } elseif (@file_exists(dirname($plugin->pluginFile) . '/README')) {
        $data['documentation_local'] = '/README';
    }

    $data['license'] = $license;

    // A pre parsed and rendered template, analogue to 'ENTRIES' etc
    $data['CONFIG'] = serendipity_plugin_config($plugin, $bag, $name, $desc, $config_names, true, true, true, true, 'plugin', $config_groups);

} elseif ($serendipity['GET']['adminAction'] == 'addnew') {

    $serendipity['GET']['type'] = !empty($serendipity['GET']['type']) ? $serendipity['GET']['type'] : 'sidebar';
    $data['adminAction'] = 'addnew';
    $data['type'] = $serendipity['GET']['type'];
    $data['urltoken'] = serendipity_setFormToken('url');

    $foreignPlugins = $pluginstack = $errorstack = array();
    serendipity_plugin_api::hook_event('backend_plugins_fetchlist', $foreignPlugins);
    $pluginstack = array_merge((array)$foreignPlugins['pluginstack'], $pluginstack);
    $errorstack  = array_merge((array)$foreignPlugins['errorstack'], $errorstack);

    if (isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] == 'UPGRADE') {
        // since sqlite being too slow for a full xml check and pluginlist rewrite - exceed the time limit
        if (stristr($serendipity['dbType'], 'sqlite')) {
            set_time_limit(0);
        }
        // for UPGRADES, the distinction in sidebar and event-plugins is not useful. We will fetch both and mix the lists
        if ($serendipity['GET']['type'] == 'event') {
            $serendipity['GET']['type'] = 'sidebar';
        } else {
            $serendipity['GET']['type'] = 'event';
        }
        $foreignPluginsTemp = array();
        serendipity_plugin_api::hook_event('backend_plugins_fetchlist', $foreignPluginsTemp);
        // in case of event upgrade this now is the event plugins array and following pluginstack is merged both
        $pluginstack    = array_merge((array)$foreignPluginsTemp['pluginstack'], $pluginstack);
        $errorstack     = array_merge((array)$foreignPluginsTemp['errorstack'], $errorstack);
        $foreignPlugins = array_merge($foreignPlugins, $foreignPluginsTemp);
    }

    $plugins = serendipity_plugin_api::get_installed_plugins();
    $classes = serendipity_plugin_api::enum_plugin_classes(($serendipity['GET']['type'] === 'event'));

    // Select local sidebar Plugins for purge cleanup
    if ($serendipity['GET']['type'] === 'sidebar') {
        $locs = serendipity_db_query("SELECT plugin_file, class_name FROM {$serendipity['dbPrefix']}pluginlist WHERE plugintype = 'sidebar' AND pluginlocation  = 'local'", false, 'assoc');
        $locals = [];
        if (is_array($locs)) {
            foreach ($locs AS $loc) {
                $locals[] = array('class_name' => $loc['class_name'], 'plugin_file' => $loc['plugin_file']);
            }
        }
    }

    if (isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] == 'UPGRADE') {
        $classes = array_merge($classes, serendipity_plugin_api::enum_plugin_classes(!($serendipity['GET']['type'] === 'event'))); // normally fetches case 'sidebar'
        $data['type'] = 'both';
    }
    usort($classes, 'serendipity_pluginListSort');

    $counter = 0;
    foreach($classes AS $class_data) {
        // Essential 'UPGRADE' Synchro check against all local sidebar plugins which come by release, regardless their state, all others are verified against other arrays
        $_type      = in_array($class_data['name'], $core_sidebar_plugins) ? 'sidebar' : $serendipity['GET']['type'];
        $pluginFile =  serendipity_plugin_api::probePlugin($class_data['name'], $class_data['classname'], $class_data['pluginPath']);
        $plugin     =& serendipity_plugin_api::getPluginInfo($pluginFile, $class_data, $_type);

        if (is_object($plugin)) {
            // Object is returned when a plugin could not be cached.
            $bag = new serendipity_property_bag;
            $plugin->introspect($bag);

            // If a foreign plugin is upgradeable, keep the new version number.
            if (isset($foreignPlugins['pluginstack'][$class_data['name']]) && $foreignPlugins['pluginstack'][$class_data['name']]['upgradeable']) {
                $class_data['upgrade_version'] = $foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'];
                if (isset($foreignPlugins['pluginstack'][$class_data['name']]['changelog'])) {
                    // remember temporary, in case the update is not done immediately
                    $_SESSION['foreignPlugins_remoteChangeLogPath'][$class_data['name']]['changelog'] = $foreignPlugins['pluginstack'][$class_data['name']]['changelog'];
                }
            } elseif (!isset($class_data['upgrade_version'])) {
                $class_data['upgrade_version'] = '';
            }

            $_ptype = $foreignPlugins['pluginstack'][$class_data['name']]['plugintype'] ?? $_type; // Gotcha! Now also matches core sidebar plugins!!
            // 1st param $plugin object is returned if a plugin is NOT a core only plugin
            $props  = serendipity_plugin_api::setPluginInfo($plugin, $pluginFile, $bag, $class_data, 'local', $_ptype);

            $counter++;
        } elseif (is_array($plugin)) {
            // Array is returned if a plugin could be fetched from info cache
            $props = $plugin;
        } else {
            $props = false;
        }

        if (is_array($props)) {
            $local_upset = false;
            if (!isset($props['upgradeable'])) {
                $props['upgradeable'] = false; // default define - Matches all plugins that are stackable/installable
            }
            if (!isset($props['customURI'])) {
                $props['customURI'] = '';
            }
            // make if/or readable
            $upgrade = false;
            // event plugins upgradeable
            if (version_compare($props['version'], $props['upgrade_version'], '<')) {
                $upgrade = true;
                $up_case = 1; // event plugins upgradeable
            }
            // sidebar plugins upgradeable
            if (!$upgrade && (
                    isset($foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'])
                    && version_compare($props['version'], $foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'], '<')
            )) {
                $upgrade = true;
                $up_case = 2; // sidebar plugins upgradeable
            }
            if ($upgrade) {
                #debug# echo $class_data['name']." version_compare_upcase = $up_case<br>\n";
                $props['upgradeable'] = true; // For the very most Spartacus::checkPlugin() already took care of false/true
                $props['remote_path'] = $serendipity['spartacus_rawPluginPath'];
                // since we merged sidebar and event plugins before, we can no longer rely on Spartacus' $foreignPlugins['baseURI']
                // NOTE: This is not nice and it would be better to move it into the plugins array instead, but that collides with the cache
                if (strpos($class_data['name'], 'serendipity_plugin') !== false) {
                    $baseURI = '&amp;serendipity[spartacus_fetch]=sidebar';
                } else {
                    $baseURI = '&amp;serendipity[spartacus_fetch]=event';
                }
                // Check local sidebar plugins for a NEW remote upgrade_version
                if (($props['plugintype'] == 'sidebar' || $props['upgrade_version'] == '' || $props['version'] == $props['upgrade_version']) && $props['pluginlocation'] == 'local'
                && (
                    isset($foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'])
                    && version_compare($props['version'], $foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'], '<')
                    )
                ) {
                    $props['upgrade_version'] = $foreignPlugins['pluginstack'][$class_data['name']]['upgrade_version'];
                    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                             SET upgrade_version = '" . serendipity_db_escape_string($props['upgrade_version']) . "'
                                           WHERE plugin_class    = '" . serendipity_db_escape_string($props['plugin_class']) . "'
                                             AND pluginlocation  = 'local'");
                    $local_upset = true;
                }
                $props['customURI'] .= $baseURI . $foreignPlugins['upgradeURI'];
            }
            // Check all other local sidebar|event plugins in array (runs once only!)
            if ($props['upgrade_version'] == '' && $props['pluginlocation'] == 'local' && !$local_upset) {
                $props['upgrade_version'] = $props['version'];
                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                         SET upgrade_version = '" . serendipity_db_escape_string($props['upgrade_version']) . "'
                                       WHERE plugin_class    = '" . serendipity_db_escape_string($props['plugin_class']) . "'
                                         AND pluginlocation  = 'local'");
            }
            // remove from locals list, since what is left is a physically purged plugin
            if (!empty($locals) && ($key = array_search($props['class_name'], array_map(function($map){return $map['class_name'];}, $locals))) !== false) {
                unset($locals[$key]);
            }

            $_installed            = in_array($class_data['true_name'], $plugins);
            $props['stackable']    = ($props['stackable'] === true && $_installed);
            $props['installable']  = !($props['stackable'] === false && $_installed);
            $_prop_requirements    = (isset($props['requirements']) && is_array($props['requirements'])) ? serialize($props['requirements']) : null;
            $props['requirements'] = isset($props['requirements']) ? unserialize($_prop_requirements) : '';
            if (isset($foreignPlugins['pluginstack'][$class_data['name']]['changelog'])) {
                $props['changelog'] = $foreignPlugins['pluginstack'][$class_data['name']]['changelog'];
            }
            // read temporary session stored data, in case the plugin update was not accessed immediately
            if (empty($props['changelog']) && isset($_SESSION['foreignPlugins_remoteChangeLogPath'][$class_data['name']]['changelog'])) {
                $props['changelog'] = $_SESSION['foreignPlugins_remoteChangeLogPath'][$class_data['name']]['changelog'];
            }
            // cut and prep an existing constant, since we don't have this very often...
            if (!empty($props['website'])) {
                $ref = explode(':', PLUGIN_GROUP_FRONTEND_EXTERNAL_SERVICES);
                $props['exdoc'] = trim(end($ref)); // avoid Notice: Only variable references should be returned by reference
            }
            // Fill this property, since it is locally there - but this does not mean we have to use it (although in addNew and upgrade only).
            // What we definitely want for upgradeable plugins is the new remote changelog path! @see above.
            // Better only mute possible undefined only_group, since else case to "blurry"
            if (empty($props['changelog']) && (isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] != 'UPGRADE') && @file_exists(dirname($props['plugin_file']) . '/ChangeLog')) {
                $props['changelog'] = 'plugins/' . $props['pluginPath'] . '/ChangeLog';
            }
            // assume session has timed out (since not upgraded at session runtime) and pluginstack is array and fetched from pluginlist table 
            if (empty($props['changelog']) && isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] == 'UPGRADE' && !isset($_SESSION['foreignPlugins_remoteChangeLogPath'][$class_data['name']]['changelog']) && @file_exists(dirname($props['plugin_file']) . '/ChangeLog')) {
                if (serendipity_request_url($serendipity['spartacus_rawPluginPath'] . $class_data['name'] . '/ChangeLog', 'get')) {
                    // remember temporary, in case the update is not done immediately
                    $_SESSION['foreignPlugins_remoteChangeLogPath'][$class_data['name']]['changelog'] = $serendipity['spartacus_rawPluginPath'] . $class_data['name'] . '/ChangeLog';
                    $props['changelog'] = $serendipity['spartacus_rawPluginPath'] . $class_data['name'] . '/ChangeLog';
                }
            }

            if (empty($props['local_documentation'])) {
                if (@file_exists(dirname($props['plugin_file']) . '/documentation_' . $serendipity['lang'] . '.html')) {
                    $props['local_documentation'] = 'plugins/' . $props['pluginPath'] . '/documentation_' . $serendipity['lang'] . '.html';
                } elseif (@file_exists(dirname($props['plugin_file']) . '/documentation_en.html')) {
                    $props['local_documentation'] = 'plugins/' . $props['pluginPath'] . '/documentation_en.html';
                } elseif (@file_exists(dirname($props['plugin_file']) . '/documentation.html')) {
                    $props['local_documentation'] = 'plugins/' . $props['pluginPath'] . '/documentation.html';
                } elseif (@file_exists(dirname($props['plugin_file']) . '/README')) {
                    $props['local_documentation'] = 'plugins/' . $props['pluginPath'] . '/README';
                }
                if (isset($props['local_documentation'])) {
                    $props['local_documentation_name'] = basename($props['local_documentation']);
                }
            }

            $pluginstack[$class_data['true_name']] = $props;
        } else {
            // False is returned if a plugin could not be instantiated
            $errorstack[] = $class_data['true_name'];
        }
    }

    if (!empty($locals)) {
        $li_st = '';
        foreach ($locals AS $removed) {
            // Check up physically purged sidebar plugins - we don't want to have them in our db list
            // But do we actually care about bundled plugins or plugin dependencies? No, not really, since purging is an expressed user action! ( But we better check the first, though! ;-) )
            if (!file_exists($removed['plugin_file']) && false === strpos($removed['plugin_file'], 'serendipity_event_')) {
                serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}pluginlist
                                       WHERE plugin_file = '" . serendipity_db_escape_string($removed['plugin_file']) . "'
                                         AND pluginlocation  = 'local'");
                $li_st .= '<li> ' . sprintf(DELETE_FILE . '. ', $removed['class_name']) . "</li>\n";
            }
        }
        if (!empty($li_st)) {
            echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> '.sprintf(SERENDIPITY_UPGRADER_DATABASE_UPDATES, SIDEBAR_PLUGIN)."\n".'<ul style="margin:0 auto">'."\n";
            echo $li_st. "\n</ul>\n</span>\n";
        }
    }

    usort($pluginstack, 'serendipity_pluginListSort');
    $pluggroups     = array();
    $pluggroups[''] = array();
    foreach($pluginstack AS $plugname => $plugdata) {
        if (!isset($plugdata['stackable'])) {
            /*  Default new install "fake" define
                Matches all "foreign" plugins, merged into pluginstack, that are NOT stackable (OR don't have this property)
                AND are not already installed AND were not cached AND only live as new in the xml files that have just been renewed.
                After this first run all properties are listed and read "correct" in the pluginlist database table.
                New plugins do always get the stackable property set to 0, until they are installed for the first time and then are read properly by the propbag! */
            $plugdata['stackable'] = false;
        }
        if (isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] == 'ALL') {
            $pluggroups['ALL'][] = $plugdata;
        } elseif (isset($serendipity['GET']['only_group']) && $serendipity['GET']['only_group'] == 'UPGRADE' && $plugdata['upgradeable']) {
            if ($plugdata['class_name'] == 'serendipity_event_ckeditor' && count($pluginstack) > 1) {
                $ov = implode('.', explode('.', $plugdata['version'], -1));         // check old lib version vs
                $nv = implode('.', explode('.', $plugdata['upgrade_version'], -1)); // (possible) new lib version
                if (version_compare($ov, $nv, '<')) {
                    $plugdata['single_upgrade'] = true; // mark this a special plugin to only UPGRADE per item
                }
            }
            $pluggroups['UPGRADE'][] = $plugdata;
        } elseif (is_array($plugdata['groups'])) {
            foreach($plugdata['groups'] AS $group) {
                $pluggroups[$group][] = $plugdata;
            }
        } else {
            $pluggroups[''][] = $plugdata;
        }
    }
    ksort($pluggroups);

    $data['count_pluginstack'] = count($pluginstack);
    $data['errorstack'] = $errorstack;

    $available_groups = array_keys($pluggroups);
    $data['available_groups'] = $available_groups;
    $groupnames = array();
    foreach($available_groups AS $available_group) {
        $groupnames[$available_group] = serendipity_groupname($available_group);
    }
    $data['groupnames'] = $groupnames;
    $data['pluggroups'] = $pluggroups;
    $data['formToken']  = serendipity_setFormToken();
    $data['only_group'] = $serendipity['GET']['only_group'] ?? '';
    $data['available_upgrades'] = isset($pluggroups['UPGRADE']);
    $requirement_failures = array();

    foreach($pluggroups AS $pluggroup => $groupstack) {
        foreach($groupstack AS $plug) {
            if (!empty($plug['requirements']['serendipity']) && version_compare($plug['requirements']['serendipity'], serendipity_getCoreVersion($serendipity['version']), '>')) {
                $requirement_failures[$plug['class_name']] = array('styx' => true);
            }

            if (!empty($plug['requirements']['php']) && version_compare($plug['requirements']['php'], PHP_VERSION, '>')) {
                if (isset($requirement_failures[$plug['class_name']])) {
                    $requirement_failures[$plug['class_name']] = array_merge($requirement_failures[$plug['class_name']] , array('php' => true));
                } else {
                    $requirement_failures[$plug['class_name']] = array('php' => true);
                }
            }

            if (!empty($plug['requirements']['smarty']) && version_compare($plug['requirements']['smarty'], Smarty::SMARTY_VERSION, '>')) {
                if (isset($requirement_failures[$plug['class_name']])) {
                     $requirement_failures[$plug['class_name']] = array_merge($requirement_failures[$plug['class_name']] , array('smarty' => true));
                } else {
                    $requirement_failures[$plug['class_name']] = array('smarty' => true);
                }
            }
        }
    }
    $data['requirement_failures'] = $requirement_failures;

} elseif ($serendipity['GET']['adminAction'] == 'renderOverlay') {
    $data['adminAction'] = 'overlay';
} else {
    /* show general plugin list */

    /* get sidebar locations */
    serendipity_smarty_init();

    if (is_array($template_config)) {
        $template_vars =& serendipity_loadThemeOptions($template_config);
    }

    $col_assoc = array(
        'event_col'  => 'event',
        'eventh_col' => 'eventh'
    );

    if (isset($template_vars['sidebars'])) {
        $sidebars = explode(',', $template_vars['sidebars']);
    } elseif (isset($serendipity['sidebars'])) {
        $sidebars = $serendipity['sidebars'];
    } else {
        $sidebars = array('left', 'hide', 'right');
    }

    foreach($sidebars AS $sidebar) {
        $col_assoc[$sidebar . '_col'] = $sidebar;
    }

    if (isset($_POST['SAVE']) && serendipity_checkFormToken()) {
        $pos = 0;
        foreach($serendipity['POST']['plugin'] AS $plugin) {
            if (!isset($plugin['authorid'])) {
                $plugin['authorid'] = 0;
            }
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}plugins
                                     SET sort_order = " .  $pos . "
                                   WHERE name='" . serendipity_db_escape_string($plugin['id']) . "'");

            serendipity_plugin_api::update_plugin_placement(
                addslashes($plugin['id']),
                addslashes($plugin['placement'])
            );

            serendipity_plugin_api::update_plugin_owner(
                addslashes($plugin['id']),
                addslashes((int)$plugin['authorid'])
            );
            $pos++;
        }
    }

    if (isset($serendipity['GET']['install_plugin']) && serendipity_checkFormToken()) {
        $authorid = $serendipity['authorid'];
        if (serendipity_checkPermission('adminPluginsMaintainOthers')) {
            $authorid = '0';
        }
        if ($serendipity['ajax']) {
            // we need to catch the Spartacus messages to return only them to the Ajax call (used by the update all button)
            ob_start();
        }

        $fetchplugin_data = array('GET'     => &$serendipity['GET'],
                                  'install' => true);

        // hook into Spartacus to download/upgrade the plugin
        serendipity_plugin_api::hook_event('backend_plugins_fetchplugin', $fetchplugin_data);
        // Spartacus hook 'backend_plugins_fetchplugin' will set $eventData['install'] to false, if ($eventData['GET']['spartacus_upgrade']) is true on UPGRADE requests

        // we now have to check that the plugin is not already installed, or stackable, to prevent invalid double instances
        $new_plugin = true;
        // and we want to check this only on INSTALLation requests
        if ($fetchplugin_data['install']) {
            foreach(serendipity_plugin_api::get_installed_plugins() AS $pluginName) {
                if ($serendipity['GET']['install_plugin'] === $pluginName) {
                    $existingPlugin =& serendipity_plugin_api::load_plugin($serendipity['GET']['install_plugin']);
                    if (is_object($existingPlugin)) {
                        $bag = new serendipity_property_bag;
                        $existingPlugin->introspect($bag);
                        if ($bag->get('stackable') != true) {
                            $new_plugin = false;
                        }
                    }
                    break;
                }
            }
        }

        $data['new_plugin_failed'] = ! $new_plugin; // is true on false and vice versa

        if ($fetchplugin_data['install'] && $new_plugin) {
            $serendipity['debug']['pluginload'] = array();
            $inst = serendipity_plugin_api::create_plugin_instance($serendipity['GET']['install_plugin'], null, (serendipity_plugin_api::is_event_plugin($serendipity['GET']['install_plugin']) ? 'event': 'right'), $authorid, serendipity_db_escape_string($serendipity['GET']['pluginPath']));

            /* Load the new plugin */
            $plugin = &serendipity_plugin_api::load_plugin($inst);
            if (!is_object($plugin)) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <strong>DEBUG:</strong> Plugin ' . serendipity_specialchars($inst) . ' not an object: ' . serendipity_specialchars(print_r($plugin, true))
                    . '.<br>Input: ' . serendipity_specialchars(print_r($serendipity['GET'], true)) . ".<br><br>\n\n
                    This error can happen if a plugin was not properly downloaded (check your plugins directory if the requested plugin
                    was downloaded) or the inclusion of a file failed (permissions?)<br>\n";
                echo "Backtrace:<br>\n" . nl2br(serendipity_specialchars(implode("\n", $serendipity['debug']['pluginload']))) . "<br></span>\n";
            }
            $bag = new serendipity_property_bag;
            $plugin->introspect($bag);

            serendipity_plugin_api::hook_event('backend_plugins_install', $serendipity['GET']['install_plugin'], $fetchplugin_data);

            if ($bag->is_set('configuration')) {
                /* Only play with the plugin if there is something to play with */
                echo '<script type="text/javascript">location.href = \'' . $serendipity['baseURL'] . 'serendipity_admin.php?serendipity[adminModule]=plugins&serendipity[plugin_to_conf]=' . $inst . '\';</script>';
                die();
            } else {
                /* If no config is available, redirect to plugin overview, because we do not want that a user can install the plugin a second time via accidental browser refresh */
                echo '<script type="text/javascript">location.href = \'' . $serendipity['baseURL'] . 'serendipity_admin.php?serendipity[adminModule]=plugins\';</script>';
                die();
            }
        } else {
            // destroy eventually stored session changelog path data
            unset($_SESSION['foreignPlugins_remoteChangeLogPath'][$serendipity['GET']['install_plugin']]['changelog']);
            // PLEASE NOTE, in this plugins event hook you have to use die() after the redirect, if in need to force the direct config fallback, eg. see CKEditor plugin
            serendipity_plugin_api::hook_event('backend_plugins_update', $serendipity['GET']['install_plugin'], $fetchplugin_data);
        }

        if ($serendipity['ajax']) {
            $data['ajax_output'] = ob_get_contents();
            ob_end_clean();
        }
    }

    if (isset($_POST['REMOVE']) && serendipity_checkFormToken()) {
        if (is_array($_POST['serendipity']['plugin_to_remove'])) {
            $msg = '<ul class="msg_success plainList plugins_removed">
            <span><span class="icon-ok-circled" aria-hidden="true"></span> ' . REMOVE_TICKED_PLUGINS . ": </span>\n";
            foreach($_POST['serendipity']['plugin_to_remove'] AS $key) {
                $plugin =& serendipity_plugin_api::load_plugin($key);

                if ($plugin->serendipity_owner == '0' || $plugin->serendipity_owner == $serendipity['authorid'] || serendipity_checkPermission('adminPluginsMaintainOthers')) {
                    serendipity_plugin_api::remove_plugin_instance($key);
                    $msg .= '<li>' . $key . ' - ' . DONE . "</li>\n";
                }
            }
            $msg .= "</ul>\n";
            echo $msg;
        }
    }

    if (isset($_POST['SAVE'])) {
        $data['save'] = true;
        $data['timestamp'] = serendipity_strftime('%H:%M:%S');
    }

    ob_start();
    serendipity_plugin_api::hook_event('backend_pluginlisting_header', $null);
    $data['backend_pluginlisting_header'] = ob_get_contents();
    ob_end_clean();

    ob_start();
    serendipity_plugin_api::hook_event('backend_plugins_sidebar_header', $serendipity);
    $data['backend_plugins_sidebar_header'] = ob_get_contents();
    ob_end_clean();

    $data['sidebar_plugins'] = show_plugins(false, $sidebars);

    ob_start();
    serendipity_plugin_api::hook_event('backend_plugins_event_header', $serendipity);
    $data['backend_plugins_event_header'] = ob_get_contents();
    ob_end_clean();

    $data['event_plugins'] = show_plugins(true);

    if (isset($serendipity['memSnaps']) && count($serendipity['memSnaps']) > 0) {
        $data['$memsnaps'] = $serendipity['memSnaps'];
    }
    $data['updateAllMsg'] = !empty($serendipity['GET']['updateAllMsg']) ? $serendipity['GET']['updateAllMsg'] : false;
}

echo serendipity_smarty_showTemplate('admin/plugins.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
