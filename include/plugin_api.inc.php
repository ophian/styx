<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ('Don\'t hack!');
}

include_once S9Y_INCLUDE_PATH . 'include/functions.inc.php';

/* Core API function mappings
 * This allows the s9y Core to also execute internal core actions on plugin API hooks
 * Future use: Global variable can be customized/overridden by your own plugin on the frontend_configure event
 * or during runtime. The capabilities are theme or plugin based only.
 */
$serendipity['capabilities']['jquery'] = true;
$serendipity['capabilities']['jquery_backend'] = true;
$serendipity['capabilities']['jquery-noconflict'] = true; // set as being deprecated, while we should not need it anymore

$serendipity['core_events']['frontend_header']['jquery'] = 'serendipity_plugin_api_frontend_header';
$serendipity['core_events']['backend_header']['jquery']  = 'serendipity_plugin_api_backend_header';

/**
 * Add jQuery to all frontend templates (in noConflict mode)
 *
 * Args:
 *      - event_name string
 *      - property bag array
 *      - referenced eventData array
 *      - (optional) addData array
 * Returns:
 *      - void
 * @access public
 */
function serendipity_plugin_api_frontend_header(string $event, ?iterable &$bag, iterable|bool|int|string|null &$eventData, iterable|bool|int|string|null &$addData) : void {
    global $serendipity;

    // Only execute if current template (w/o fallback) does not have its own jquery.js file
    // jquery can be disabled if a template's config.inc.php or a plugin sets
    // $serendipity['capabilities']['jquery'] = false

    $check = file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template'] . '/jquery.js');
    if (!$check && $serendipity['capabilities']['jquery']) {
?>
    <script src="<?php echo $serendipity['serendipityHTTPPath']; ?>templates/jquery.js"></script>
<?php
        if ($serendipity['capabilities']['jquery-noconflict']) {
?>
    <script>jQuery.noConflict();</script>
<?php
        }
        // Check Styx WebP support and an is-set frontend theme (modern) modernizr object with WebP module support, returning a non supporting browser.
        // Enable with $serendipity['checkWebPFormat'] = true; in a themes config.inc file.
        if (!empty($serendipity['useWebPFormat']) && !empty($serendipity['checkWebPFormat'])) {
?>
    <script>
        if (typeof Modernizr == 'object') {
            if (Modernizr.on) {
              Modernizr.on('webp', function(result) {
                if (!result) { (function($) { $('a.serendipity_image_link').on( "mouseenter mouseleave", function() { $(this).attr('href', $(this).data('fallback')); }); }) };
              });
            }
        }
    </script>
<?php
        }
    }
}

/**
 * Add jQuery to all backend templates
 *
 * Args:
 *      - event_name string
 *      - property bag array
 *      - referenced eventData array
 *      - (optional) addData array
 * Returns:
 *      - void
 * @access protected
 */
function serendipity_plugin_api_backend_header(string $event, ?iterable &$bag, iterable|bool|int|string|null &$eventData, iterable|bool|int|string|null &$addData) : void {
    global $serendipity;

    // Only execute if current template does not have its own backend_jquery.js file
    // jquery can be disabled if a template's config.inc.php or a plugin sets
    // $serendipity['capabilities']['jquery'] = false

    $check = serendipity_getTemplateFile('jquery_backend.js', 'serendipityPath', true);
    if (!$check && $serendipity['capabilities']['jquery_backend']) {
?>
    <script src="<?php echo $serendipity['serendipityHTTPPath']; ?>templates/jquery.js"></script>
<?php
    }
}

/**
 * Add backend core (pre) hooks
 *
 * Args:
 *      - event_name string
 *      - property bag array
 *      - referenced eventData array
 *      - (optional) addData array
 * Returns:
 *      - void
 * @access protected
 */
function serendipity_plugin_api_core_event_hook(string $event, ?iterable &$bag, iterable|bool|int|string|null &$eventData, iterable|bool|int|string|null &$addData) : void {
    global $serendipity;

    switch($event) {

        case 'external_plugin':
            if ($eventData == 'admin/serendipity_styx.js') {
                // for serendipity_setNotModifiedHeader
                if ($serendipity['CacheControl'] && !ob_get_level()) {
                    ob_start();
                }

                if ($serendipity['CacheControl']) {
                    // Note that no-cache does not mean "don't cache". no-cache allows caches to store a response but requires them to revalidate it before reuse.
                    // If the sense of "don't cache" that you want is actually "don't store", then no-store is the directive to use.
                    if (!empty($_SERVER['SERVER_SOFTWARE']) && str_contains($_SERVER['SERVER_SOFTWARE'], 'LiteSpeed')) {
                        header('Cache-Control: private, max-age=3600, must-revalidate'); // for Hostinger Cache on LiteSpeed
                        header('Pragma:'); // for Hostinger Cache on LiteSpeed
                    } else {
                        header("Cache-Control: no-cache, max-age=3600, s-maxage=3600, must-revalidate, proxy-revalidate"); // 1 hour - if this all works we could set this to 12/24 hours
                    }
                } else {
                    header('Cache-Control: must-revalidate, proxy-revalidate');
                    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time()+3600));
                }
                header('Content-type: application/javascript; charset=' . LANG_CHARSET);
                echo serendipity_smarty_showTemplate('admin/serendipity_styx.js.tpl', null, 'JS', 'include/plugin_api.inc.php:external_plugin');

                if ($serendipity['CacheControl']) {
                    serendipity_setNotModifiedHeader(); // 304
                }
            }
            break;

        case 'backend_save':
        case 'backend_publish':
            // this is preview_iframe.tpl updertHooks [ NOT ONLY! See freetags - keep it strictly set to iframe mode save!]
            if (serendipity_db_bool($serendipity['use_autosave']) && isset($_GET['serendipity']['is_iframe']) && $_GET['serendipity']['is_iframe'] == 'true' && $_GET['serendipity']['iframe_mode'] == 'save') {
                echo "\n".'<script>document.addEventListener("DOMContentLoaded", function() { if (window.parent.Modernizr.indexeddb) { window.parent.serendipity.eraseEntryEditorCache(); } });</script>'."\n";
            }
            break;

    }
}


/**
 * This file defines the plugin API for Serendipity.
 * By extending these classes, you can add your own code
 * to appear in the sidebar(s) of Serendipity.
 *
 * The system defines a number of built-in plugins; these are
 * identified by class_name only.
 *
 * Third-party plugins are identified by the name of the folder into
 * which they were uploaded.
 *
 * The user creates instances of plugins; an instance is assigned
 * an identifier like this:
 *   classname:uniqid()
 *
 * The user can configure instances of plugins.
 */

class serendipity_plugin_api
{

    /**
     * Register the default list of plugins for installation.
     *
     * Args:
     *      -
     * Returns:
     *      - void
     * @access protected
     */
    static function register_default_plugins() : void
    {
        /* Register default sidebar plugins, order matters */
        serendipity_plugin_api::create_plugin_instance('serendipity_plugin_archives');
        serendipity_plugin_api::create_plugin_instance('serendipity_plugin_categories');
        serendipity_plugin_api::create_plugin_instance('serendipity_plugin_syndication');
        serendipity_plugin_api::create_plugin_instance('serendipity_plugin_superuser');
        serendipity_plugin_api::create_plugin_instance('serendipity_plugin_plug');

        /* Register default event plugins */
        serendipity_plugin_api::create_plugin_instance('serendipity_event_s9ymarkup', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_emoticate', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_nl2br', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_spamblock', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_spartacus', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_modemaintain', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_changelog', null, 'event');
        serendipity_plugin_api::create_plugin_instance('serendipity_event_entryproperties', null, 'event');

        /* Register additional plugins? */
        if (file_exists(S9Y_INCLUDE_PATH . 'plugins/preload.txt')) {
            // Expects this format, one plugin per line:
            // serendipity_event_xxx:event
            // serendipity_plugin_xxx:left
            $plugins = file(S9Y_INCLUDE_PATH . 'plugins/preload.txt');
            foreach($plugins AS $plugin) {
                $plugin = trim($plugin);
                if (empty($plugin)) {
                    continue;
                }

                $plugin_info = explode(':', $plugin);
                serendipity_plugin_api::create_plugin_instance($plugin_info[0], null, $plugin_info[1]);
            }
        }
    }

    /**
     * Create an instance of a plugin.
     *
     * $plugin_class_id is of the form:
     *    class_name         for a built-in plugin
     * or
     *    plugin_dir_name    for a third-party plugin
     * returns the instance identifier for the newly created plugin.
     *
     * TO BE IMPLEMENTED:                                                       ??????????????????????
     * If $copy_from_instance is not null, and identifies another plugin
     * of the same class, then the persistent state will be copied.
     * This allows the user to clone a plugin.
     *
     * Args:
     *      - classname of the plugin to insert (see description above for details)
     *      - (reserved) variable to indicate a copy of an existing instance
     *      - The type of the plugin to insert (event/left/right/hide/eventh)
     *      - The authorid of the plugin owner
     *      - The source path of the plugin file
     * Returns:
     *      - Escaped string ID of the new plugin
     * @access protected
     */
    static function create_plugin_instance(string $plugin_class_id, ?string $copy_from_instance = null, string $default_placement = 'right', string $authorid = '0', string $pluginPath = '') : string
    {
        global $serendipity;

        $id = bin2hex(random_bytes(16)); // add entropy, since the installers first ~10 (depending on response time) enabled plugins all get the same token, while uniqid is Unixtime(8) + Microseconds(5)

        $key = $plugin_class_id . ':' . $id;
        $key = serendipity_db_escape_string($key);

        // Secure Plugin path. No leading slashes, no backslashes, no "up" directories
        $pluginPath = preg_replace('@^(/)@', '', (string) $pluginPath);
        $pluginPath = str_replace(array('..', "\\"), array('', '/'), (string) serendipity_db_escape_string($pluginPath));

        if ($pluginPath == 'online_repository') {
            $pluginPath = $key;
        }

        $rs = serendipity_db_query("SELECT MAX(sort_order) AS sort_order_max FROM {$serendipity['dbPrefix']}plugins WHERE placement = '$default_placement'", true, 'num');

        if (is_array($rs) && isset($rs[0]) && is_numeric($rs[0])) {
            $nextidx = intval($rs[0] + 1);
        } else {
            $nextidx = 0;
        }

        $serendipity['debug']['pluginload'][] = 'Installing plugin: ' . print_r(func_get_args(), true);

        $iq = "INSERT INTO {$serendipity['dbPrefix']}plugins (name, sort_order, placement, authorid, path)
                    VALUES ('" . htmlspecialchars($key) . "', $nextidx, '$default_placement', '$authorid', '" . htmlspecialchars($pluginPath) . "')";
        $serendipity['debug']['pluginload'][] = $iq;
        serendipity_db_query($iq);
        serendipity_plugin_api::hook_event('backend_plugins_new_instance', $key, array('default_placement' => $default_placement));

        /* Check for multiple dependencies */
        $plugin =& serendipity_plugin_api::load_plugin($key, $authorid, $pluginPath);
        if (is_object($plugin)) {
            $bag = new serendipity_property_bag;
            $plugin->introspect($bag);
            serendipity_plugin_api::get_event_plugins(false, true); // Refresh static list of plugins to allow execution of added plugin
            $plugin->register_dependencies(false, $authorid);
            $plugin->install();
        } else {
            $serendipity['debug']['pluginload'][] = 'Loading plugin failed painfully. File not found?';
            echo '<span class="msg_error">' . ERROR . ': ' . htmlspecialchars($key) . ' (' . htmlspecialchars($pluginPath) . ')</span>';
        }

        return $key;
    }

    /**
     * Removes a plugin by it's instance name
     *
     * Args:
     *      - The name of the plugin id ("serendipity_plugin_xxx:1232132fsdf")
     * Returns:
     *      - void
     * @access protected
     */
    static function remove_plugin_instance(string $plugin_instance_id) : void
    {
        global $serendipity;

        $plugin_instance_id = serendipity_db_escape_string($plugin_instance_id);

        $plugin =& serendipity_plugin_api::load_plugin($plugin_instance_id);
        if (is_object($plugin)) {
            $bag = new serendipity_property_bag;
            $plugin->introspect($bag);
            $plugin->uninstall($bag);
        }

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}plugins WHERE name='$plugin_instance_id'");

        if (is_object($plugin)) {
            $plugin->register_dependencies(true);
        }

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name LIKE '$plugin_instance_id/%'");
    }

    /**
     * Removes an empty plugin configuration value
     *
     * Args:
     *      - The name of the plugin id ("serendipity_plugin_xxx:1232132fsdf")
     *      - An array of configuration item names
     * Returns:
     *      - void
     * @access protected
     */
    static function remove_plugin_value(string $plugin_instance_id, $where) : void
    {
        global $serendipity;

        $where_sql = array();
        foreach($where AS $key) {
            $where_sql[] = "(name = '{$plugin_instance_id}/{$key}' AND value = '') "; // be strict with config option name and space for OR
        }

        $query = "DELETE FROM  {$serendipity['dbPrefix']}config
                        WHERE " . implode(' OR ', $where_sql);
        serendipity_db_query($query);
    }

    /**
     * Retrieve a list of available plugin classes
     *
     * This function searches through all directories and loaded internal files and tries
     * to detect the serendipity plugins.
     *
     * Args:
     *      - If true, only event plugins will be searched. If false, sidebar plugins will be searched.
     * Returns:
     *      - Classes array
     * @access protected
     */
    static function &enum_plugin_classes(bool $event_only = false) : iterable
    {
        global $serendipity;

        $classes = array();

        /* built-in classes first */
        $cls = get_declared_classes();
        foreach($cls AS $class_name) {
            if (strncmp($class_name, 'serendipity_', 6)) {
                continue;
            }

            $p = get_parent_class($class_name);
            while ($p != 'serendipity_plugin' && $p != 'serendipity_event' && $p !== false) {
                $p = get_parent_class($p);
            }

            // This 'internal_' assignment is homegrown code, having classes outside the default -
            // it so additionally is to be understood only taking into account the history of previously 'embedded' internal @plugins -
            // and will be cleared already in traverse_plugin_dir() method
            if ($p == 'serendipity_plugin' && $class_name != 'serendipity_event' && !$event_only) {
                $classes[$class_name] = array('name'       => $class_name,
                                              'type'       => 'internal_event',
                                              'true_name'  => $class_name,
                                              'pluginPath' => '');
            } elseif ($p == 'serendipity_event' && $class_name != 'serendipity_event' && $event_only) {
                $classes[$class_name] = array('name'       => $class_name,
                                              'type'       => 'internal_plugin',
                                              'true_name'  => $class_name,
                                              'pluginPath' => '');
            }
        }

        /* GLOBAL third-party classes next */
        $ppath = serendipity_getRealDir(__FILE__) . 'plugins';
        serendipity_plugin_api::traverse_plugin_dir($ppath, $classes, $event_only);

        /* LOCAL third-party classes next */
        $local_ppath = $serendipity['serendipityPath'] . 'plugins';
        if ($ppath != $local_ppath) {
            serendipity_plugin_api::traverse_plugin_dir($local_ppath, $classes, $event_only);
        }

        return $classes;
    }

    /**
     * Traverse a specific directory and search if a serendipity plugin exists there.
     *
     * Args:
     *      - The path to start from (usually '.')
     *      - A referenced array of currently found classes (null type case possible with emerge_spartacus !)
     *      - If true, only event plugins will be searched. If false, only sidebar plugins will be searched.
     *      - The main dir where we started searching from [for recursive use]
     * Returns:
     *      - void
     * @access protected
     */
    static function traverse_plugin_dir(string $ppath, ?iterable &$classes, bool $event_only, string $maindir = '') : void
    {
        $d = @opendir($ppath);
        if ($d) {
            while (($f = readdir($d)) !== false) {
                if ($f[0] == '.' || $f == 'CVS' || !is_dir($ppath . '/' . $f) || !is_readable($ppath . '/' .$f)) {
                    continue;
                }

                $subd = opendir($ppath . '/' . $f);
                if (!$subd) {
                    continue;
                }

                // Instead of only looking for directories, search for files within subdirectories
                $final_loop = false;
                while (($subf = readdir($subd)) !== false) {

                    if ($subf[0] == '.' || $subf == 'CVS') {
                        continue;
                    }

                    if (!$final_loop && is_dir($ppath . '/' . $f . '/' . $subf) && $maindir != $ppath . '/' . $f) {
                        // Search for another level of subdirectories
                        serendipity_plugin_api::traverse_plugin_dir($ppath . '/' . $f, $classes, $event_only, $f . '/');
                        // We can break after that operation because the current directory has been fully checked already.
                        $final_loop = true;
                    }

                    if (!preg_match('@^[^_]+_(event|plugin)_.+\.php$@i', $subf)) {
                        continue;
                    }

                    $class_name = str_replace('.php', '', $subf);
                    // If an external plugin/event already exists as internal, remove the internal reference because its redundant
                    if (isset($classes['@' . $class_name])) {
                        unset($classes['@' . $class_name]);
                    }

                    // A local plugin will be preferred over general plugins [used when calling this function the second time]
                    if (isset($classes[$class_name])) {
                        unset($classes[$class_name]);
                    }

                    if (!is_null($event_only) && $event_only && !serendipity_plugin_api::is_event_plugin($subf)) {
                        continue;
                    }

                    if (!is_null($event_only) && !$event_only && serendipity_plugin_api::is_event_plugin($subf)) {
                        continue;
                    }

                    $classes[$class_name] = array('name'       => $class_name,
                                                  'true_name'  => $class_name,
                                                  'type'       => 'additional_plugin',
                                                  'pluginPath' => $maindir . $f);
                }
                closedir($subd);
            }
            closedir($d);
        }
    }

    /**
     * Returns a list of currently installed plugins
     *
     * Args:
     *      - The filter for plugins (left|right|hide|event|eventh)
     * Returns:
     *      - The list of plugins
     * @access protected
     */
    static function get_installed_plugins($filter = '*') : iterable
    {
        $plugins = serendipity_plugin_api::enum_plugins($filter);
        $res = array();
        foreach((array)$plugins AS $plugin) {
            list($class_name) = explode(':', $plugin['name']);
            $class_name = ltrim($class_name, '@');
            $res[] = $class_name;
        }
        return $res;
    }

    /**
     * Searches for installed plugins based on specific conditions
     *
     * Args:
     *      - The filter for plugins (left|right|hide|event|eventh)
     *      - If true, the filtering logic will be reversed and all plugins that are NOT part of the filter will be returned
     *      - Filter by a specific classname (like 'serendipity_plugin_archives'). Can take SQL wildcards.
     *      - Filter by a specific plugin instance id
     * Returns:
     *      - Returns the associative array of found plugins in the database
     * @access protected
     */
    static function enum_plugins(string $filter = '*', bool $negate = false, ?string $classname = null, ?string $id = null) : iterable|string|bool
    {
        global $serendipity;

        $sql   = "SELECT * from {$serendipity['dbPrefix']}plugins ";
        $where = array();

        if ($filter !== '*') {
            if ($negate) {
                $where[] = " placement != '" . serendipity_db_escape_string($filter) . "' ";
            } else {
                $where[] = " placement =  '" . serendipity_db_escape_string($filter) . "' ";
            }
        }

        if (!empty($classname)) { // checking      @serendipity_xyz is deprecated since old serendipity
            $where[] = " (name LIKE '@" . serendipity_db_escape_string($classname) . "%' OR name LIKE '" . serendipity_db_escape_string($classname) . "%') ";
        }

        if (!empty($id)) {
            $where[] = " name = '" . serendipity_db_escape_string($id) . "' ";
        }

        if (count($where) > 0) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $sql .= ' ORDER BY placement, sort_order';

        return serendipity_db_query($sql);
    }

    /**
     * Count the number of plugins to which the filter criteria matches
     *
     * Args:
     *      - The filter for plugins (left|right|hide|event|eventh)
     *      - If true, the filtering logic will be reversed and all plugins that are NOT part of the filter will be evaluated
     * Returns:
     *      - Number of plugins that were found
     * @access protected
     */
    static function count_plugins(string $filter = '*', bool $negate = false) : int
    {
        global $serendipity;

        // Can be shortcircuited via a $serendipity['prevent_sidebar_plugins_(left|right|event)'] variable! // mute possible uninitialized globals
        if (!$negate && !empty($serendipity['prevent_sidebar_plugins_' . $filter]) && $serendipity['prevent_sidebar_plugins_' . $filter] == true) {
            return 0;
        }

        $sql = "SELECT COUNT(placement) AS count from {$serendipity['dbPrefix']}plugins ";

        if ($filter !== '*') {
            if ($negate) {
                $sql .= "WHERE placement != '$filter' ";
            } else {
                $sql .= "WHERE placement='$filter' ";
            }
        }

        $count = serendipity_db_query($sql, true);
        if (is_array($count) && isset($count[0])) {
            return (int) $count[0];
        }

        return 0;
    }

    /**
     * Detect the filename to use for a specific plugin
     *
     * Args:
     *      - The name of the plugin ('serendipity_event_archive')
     *      - The path to the plugin file (if empty, the current path structure will be used.)
     *      - If an instance ID is passed this means, the plugin to be loaded is internally available
     * Returns:
     *      - Returns the filename to include for a specific plugin
     * @access protected
     */
    static function includePlugin(string $name, string $pluginPath = '', string $instance_id = '') : string|false
    {
        global $serendipity;

        if (empty($pluginPath)) {
            $pluginPath = $name;
        }

        $file = false;

        // Security constraint
        $pluginFile = 'plugins/' . $pluginPath . '/' . $name . '.php';
        $pluginFile = preg_replace('@([\r\n\t\0\\\]+|\.\.+)@', '', $pluginFile);

        // First try the local path, and then (if existing) a shared library repository ...
        // Internal plugins ignored.
        if (file_exists($serendipity['serendipityPath'] . $pluginFile)) {
            $file = $serendipity['serendipityPath'] . $pluginFile;
        } elseif (file_exists(S9Y_INCLUDE_PATH . $pluginFile)) {
            $file = S9Y_INCLUDE_PATH . $pluginFile;
        }

        return $file;
    }

    /**
     * Returns the plugin class name by a plugin instance ID
     *
     * Args:
     *      - The ID of a plugin
     *      - If true, the plugin is a internal plugin (prefixed with '@'). (Unused, keep for compat.)
     * Returns:
     *      - The classname of the plugin
     * @access protected
     */
    static function getClassByInstanceID(string $instance_id, bool &$is_internal) : string
    {
        $instance   = explode(':', $instance_id);
        $class_name = ltrim($instance[0], '@');
        return $class_name;
    }

    /**
     * Probes for the plugin filename
     * Auto-detect a plugin and see if the file information is given, and if not, detect it.
     *
     * Args:
     *      - The ID of a plugin to load
     *      - A reference variable that will hold the class name of the plugin (do not pass manually)
     *      - A reference variable that will hold the path to the plugin (do not pass manually)
     * Returns:
     *      - Returns the filename of a plugin to load
     * @access protected
     */
    static function probePlugin(string $instance_id, ?string &$class_name, string &$pluginPath) : string|bool
    {
        global $serendipity;

        $filename    = false;
        $is_internal = false;

        $class_name  = serendipity_plugin_api::getClassByInstanceID($instance_id, $is_internal);

        if (!$is_internal) {
            /* plugin from the plugins/ dir */
            // $serendipity['debug']['pluginload'][] = "Including plugin $class_name, $pluginPath";
            $filename = serendipity_plugin_api::includePlugin($class_name, $pluginPath, $instance_id);
            if (empty($filename) && !empty($instance_id)) {
                // $serendipity['debug']['pluginload'][] = "No valid path/filename found.";
                $sql = "SELECT path FROM {$serendipity['dbPrefix']}plugins WHERE name = '" . serendipity_db_escape_string($instance_id) . "'";
                $plugdata = serendipity_db_query($sql, single: true, expectError: true);
                if (is_array($plugdata) && isset($plugdata[0])) {
                    $pluginPath = $plugdata[0];
                }

                if (empty($pluginPath)) {
                    $pluginPath = $class_name;
                }

                // $serendipity['debug']['pluginload'][] = "Including plugin(2) $class_name, $pluginPath";
                $filename = serendipity_plugin_api::includePlugin($class_name, $pluginPath);
            }

            if (empty($filename)) {
                $serendipity['debug']['pluginload'][] = 'No valid path/filename found. Aborting.';
                $retval = false;
                return $retval;
            }
        }

        // $serendipity['debug']['pluginload'][] = "Found plugin file $filename";
        return $filename;
    }

    /**
     * Instantiates a plugin class
     *
     * Args:
     *      - The ID of the plugin to load
     *      - The owner of the plugin (can be autodetected)
     *      - The path to a plugin (can be autodetected)
     *      - The filename of a plugin (can be autodetected)
     * Returns:
     *      - object
     * @access protected
     */
    static function &load_plugin(string $instance_id, ?string $authorid = null, $pluginPath = '', ?string $pluginFile = null) : object|false
    {
        global $serendipity;

        if ($pluginFile === null) {
            $class_name = '';
            // $serendipity['debug']['pluginload'][] = "Init probe for plugin $instance_id, $class_name, $pluginPath";
            $pluginFile = serendipity_plugin_api::probePlugin($instance_id, $class_name, $pluginPath);
        } else {
            $is_internal = false;
            // $serendipity['debug']['pluginload'][] = "getClassByInstanceID $instance_id, $is_internal";
            $class_name  = serendipity_plugin_api::getClassByInstanceID($instance_id, $is_internal);
        }

        if (!class_exists($class_name) && !empty($pluginFile)) {
            // $serendipity['debug']['pluginload'][] = "Classname does not exist. Including $pluginFile.";
            include($pluginFile);
        }

        if (!class_exists($class_name)) {
            $serendipity['debug']['pluginload'][] = "Classname $class_name still does not exist. Aborting.";
            $r = false;
            return $r; // avoid Notice: Only variable references should be returned by reference
        }

        // $serendipity['debug']['pluginload'][] = "Returning new $class_name($instance_id)";
        $p = new $class_name($instance_id);
        if (!is_null($authorid)) {
            $p->serendipity_owner = $authorid;
        } else {
            $sql = "SELECT authorid from {$serendipity['dbPrefix']}plugins WHERE name = '" . serendipity_db_escape_string($instance_id) . "'";
            $owner = serendipity_db_query($sql, true);
            if (is_array($owner) && isset($owner[0])) {
                $p->serendipity_owner = $owner[0];
            }
        }

        $p->pluginPath = $p->act_pluginPath = $pluginPath;
        if (empty($p->act_pluginPath)) {
            $p->act_pluginPath = $class_name;
        }
        $p->pluginFile = $pluginFile;

        return $p;
    }

    /**
     * Gets cached properties/information about a specific plugin, auto-loads a cache of all plugins
     *
     * Args:
     *      - The filename of the plugin to get information about
     *      - A referenced array that holds information about the plugin instance (self::load_plugin() response)
     *      - The type of the plugin (event|sidebar) - the location is either (local|Spartacus)
     * Returns:
     *      - Information about the plugin
     * @access protected
     */
    static function &getPluginInfo(string &$pluginFile, iterable &$class_data, string $type) : iterable|object
    {
        global $serendipity;
        static $pluginlist = null;

        if ($pluginlist === null) {
            $data = serendipity_db_query("SELECT p.*,
                                                 pc.category
                                            FROM {$serendipity['dbPrefix']}pluginlist AS p
                                 LEFT OUTER JOIN {$serendipity['dbPrefix']}plugincategories AS pc
                                              ON pc.class_name = p.class_name
                                           WHERE p.pluginlocation = 'local' AND
                                                 p.plugintype     = '" . serendipity_db_escape_string($type) . "'");
            if (is_array($data)) {
                foreach($data AS $p) {
                    if (isset($p['pluginpath'])) {
                        $p['pluginPath'] = $p['pluginpath'];
                    }
                    if (!isset($pluginlist[$p['plugin_file']])) {
                        $pluginlist[$p['plugin_file']] = $p;
                    }

                    $pluginlist[$p['plugin_file']]['groups'][] = $p['category'];
                }
            }
        }

        if (isset($pluginlist[$pluginFile]) && is_array($pluginlist[$pluginFile]) && !preg_match('@plugin_internal\.inc\.php@', $pluginFile)) {
            $data = $pluginlist[$pluginFile];
            if ((int) filemtime($pluginFile) == (int) $data['last_modified']) {
                $data['stackable'] = serendipity_db_bool(($data['stackable'] ?? false));
                $plugin = $data; // avoid Notice: Only variable references should be returned by reference (? while $pluginFile is in $data ?)
                return $plugin;
            }
        }

        $plugin =& serendipity_plugin_api::load_plugin($class_data['name'], null, $class_data['pluginPath'], $pluginFile);

        return $plugin;
    }

    /**
     * Set cache information about a plugin
     *
     * Args:
     *      - Either an plugin object or a plugin information array that holds the information about the plugin
     *      - The filename of the plugin
     *      - The property bag object bundled with the plugin
     *      - Previous/additional information about the plugin
     *      - The pluginlocation/type of a plugin (local|Spartacus)
     *      - The plugintype/type of a plugin (event|sidebar)
     * Returns:
     *      - return $data is either array (when object) or object (when array) - so type iterable
     * @access protected
     */
    static function &setPluginInfo(iterable|object &$plugin, string &$pluginFile, iterable|int|object &$bag, iterable|int &$class_data, string $pluginlocation = 'local', string $plugintype = '') : iterable
    {
        global $serendipity;

        static $dbfields = array(
            'plugin_file',
            'class_name',
            'plugin_class',
            'pluginPath',
            'name',
            'description',
            'version',
            'upgrade_version',
            'plugintype',
            'pluginlocation',
            'stackable',
            'author',
            'requirements',
            'website',
            'last_modified'
        );

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}pluginlist WHERE plugin_file = '" . serendipity_db_escape_string($pluginFile) . "' AND pluginlocation = '" . serendipity_db_escape_string($pluginlocation) . "'");

        if (!empty($pluginFile) && file_exists($pluginFile)) {
            $lastModified = filemtime($pluginFile);
        } else {
            $lastModified = 0;
        }

        if (is_object($plugin)) {
            $data = array(
                'class_name'      => get_class($plugin),
                'stackable'       => $bag->get('stackable'),
                'name'            => $bag->get('name'),
                'description'     => $bag->get('description'),
                'author'          => $bag->get('author'),
                'version'         => $bag->get('version'),
                'upgrade_version' => ($class_data['upgrade_version'] ?? $bag->get('version')),
                'requirements'    => serialize($bag->get('requirements')),
                'website'         => $bag->get('website'),
                'plugin_class'    => $class_data['name'],
                'pluginPath'      => $class_data['pluginPath'],
                'plugin_file'     => $pluginFile,
                'pluginlocation'  => $pluginlocation,
                'plugintype'      => $plugintype,
                'last_modified'   => $lastModified
            );
            $groups = $bag->get('groups');
        } elseif (is_array($plugin)) {
            $data = $plugin;
            $groups = $data['groups'];
            unset($data['installable']);
            unset($data['true_name']);
            unset($data['customURI']);
            unset($data['groups']);
            if (isset($data['pluginpath'])) {
                $data['pluginPath'] = $data['pluginpath'];
            }
            $data['requirements'] = serialize($data['requirements']);
        }

        if (!isset($data['stackable']) || empty($data['stackable'])) {
            $data['stackable'] = '0';
        }

        if (!isset($data['last_modified'])) {
            $data['last_modified'] = (string)$lastModified;
        }

        // Only insert data keys that exist in the DB.
        $insertdata = array();
        foreach($dbfields AS $field) {
            // if set, data has them all
            if (isset($data[$field])) {
                $insertdata[$field] = $data[$field];
            }
        }

        if (isset($data['upgradeable']) && $data['upgradeable']) {
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                     SET upgrade_version = '" . serendipity_db_escape_string($data['upgrade_version']) . "'
                                   WHERE plugin_class    = '" . serendipity_db_escape_string($data['plugin_class']) . "'");
        }
        serendipity_db_insert('pluginlist', $insertdata);

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}plugincategories WHERE class_name = '" . serendipity_db_escape_string($data['class_name']) . "'");
        foreach((array)$groups AS $group) {
            if (empty($group)) {
                continue;
            }

            $cat = array(
                'class_name'  => $data['class_name'],
                'category'    => $group
            );
            serendipity_db_insert('plugincategories', $cat);
        }

        $data['groups'] = $groups;

        return $data;
    }

    /**
     * Moves a sidebar plugin to a different side or up/down
     *
     * Args:
     *      - The instance ID of a plugin
     *      - The new placement of a plugin (left|right|hide|event|eventh)
     *      - A new sort order for the plugin
     * Returns:
     *      - void - error msg string or fail
     * @access protected
     */
    static function update_plugin_placement(string $name, string $placement, ?string $order = null) : string|bool
    {
        global $serendipity;

        $admin = '';
        if (!serendipity_checkPermission('adminPlugins') && $placement == 'hide') {
            // Only administrators can set plugins to 'hide' if they are not the owners.
            $admin = " AND (authorid = 0 OR authorid = {$serendipity['authorid']})";
        }

        $sql = "UPDATE {$serendipity['dbPrefix']}plugins set placement='$placement' ";

        if ($order !== null) {
            $sql .= ", sort_order=$order ";
        }

        $sql .= "WHERE name='$name' $admin";

        return serendipity_db_query($sql);
    }

    /**
     * Updates the ownership information about a plugin
     *
     * Args:
     *      - The instance ID of the plugin
     *      - The ID of the new author owner of the plugin
     * Returns:
     *      - void - error msg string or fail
     * @access protected
     */
    static function update_plugin_owner(string $name, string $authorid) : string|bool|null
    {
        global $serendipity;

        if (empty($authorid) && $authorid != '0') {
            return null;
        }

        $admin = '';
        if (!serendipity_checkPermission('adminPlugins')) {
            $admin = " AND (authorid = 0 OR authorid = {$serendipity['authorid']})";
        }

        $sql = "UPDATE {$serendipity['dbPrefix']}plugins SET authorid='$authorid' WHERE name='$name' $admin";

        return serendipity_db_query($sql);
    }

    /**
     * Get a list of Sidebar plugins and pass them to Smarty
     *
     * Args:
     *      - The side of plugins to show (left/right/hide/event/eventh)
     *      - Indicates whether only all plugins should be shown that are not in the $side list
     *      - Only show plugins of this plugin class
     *      - Only show a plugin with this instance ID
     *      - The template file name to fetch
     * Returns:
     *      - Smarty HTML output
     * @access protected
     */
    static function generate_plugins(string $side, bool $negate = false, ?string $class = null, ?string $id = null, string $tpl = 'sidebar.tpl') : ?string
    {
        global $serendipity;

        $plugins = serendipity_plugin_api::enum_plugins($side, $negate, $class, $id);

        if (!is_array($plugins)) {
            return null;
        }

        if (!isset($serendipity['smarty'])) {
            $serendipity['smarty_raw_mode'] = true;
            serendipity_smarty_init();
        }

        $pluginData = array();
        $addData    = func_get_args();
        serendipity_plugin_api::hook_event('frontend_generate_plugins', $plugins, $addData);

        if (count($plugins) == 0) {
            $serendipity['prevent_sidebar_plugins_' . $side] = true;
        }

        $loggedin = false;
        if (serendipity_userLoggedIn() && serendipity_checkPermission('adminPlugins')) {
            $loggedin = true;
        }

        foreach($plugins AS $plugin_data) {
            $plugin =& serendipity_plugin_api::load_plugin($plugin_data['name'], $plugin_data['authorid'], $plugin_data['path']);
            if (is_object($plugin)) {
                $class  = get_class($plugin);
                $title  = '';

                /* TODO: make generate_content NOT echo its output */
                ob_start();
                $show_plugin = $plugin->generate_content($title);
                $content = ob_get_contents();
                ob_end_clean();

                if (empty(trim($content))) {
                    $content = "\n"; // for next edit nuggets link markup w/o prior generate_content() stuff
                }

                if ($loggedin) {
                    $content .= '                    <div class="serendipity_edit_nugget"><a href="' . $serendipity['serendipityHTTPPath'] . 'serendipity_admin.php?serendipity[adminModule]=plugins&amp;serendipity[plugin_to_conf]=' . htmlentities($plugin->instance) . '">' . EDIT . '</a></div>';
                }

                if ($show_plugin !== false) {
                    $pluginData[] = array('side'    => $side,
                                          'class'   => $class,
                                          'title'   => $title,
                                          'content' => $content,
                                          'id'      => $plugin->instance);
                }
            } else {
                    $pluginData[] = array('side'          => $side,
                                          'title'         => ERROR,
                                          'class'         => $class,
                                          'content'       => sprintf(INCLUDE_ERROR, $plugin_data['name']));
            }
        }

        serendipity_plugin_api::hook_event('frontend_sidebar_plugins', $pluginData, $addData);

        $serendipity['smarty']->assignByRef('plugindata', $pluginData);
        $serendipity['smarty']->assign('pluginside', ucfirst($side));

        return serendipity_smarty_fetch('sidebar_'. $side, $tpl, true);
    }

    /**
     * Gets the title of a plugin to be shown in plugin overview
     *
     * Args:
     *      - The plugin object
     *      - The default title, if none was configured
     * Returns:
     *      - The title of the plugin
     * @access protected
     */
    static function get_plugin_title(object &$plugin, string $default_title = '') : string
    {
        global $serendipity;

        // Generate plugin output. Make sure that by probing the plugin, no events are actually called. After that,
        // restore setting of 'no_events'.

        if (!is_null($plugin->title)) {
            // Preferred way of fetching a plugins title
            $title = &$plugin->title;
        } else {
            $ne = (isset($serendipity['no_events']) && $serendipity['no_events'] ? true : false);
            $serendipity['no_events'] = true;
            ob_start();
            $plugin->generate_content($title);
            ob_end_clean();
            $serendipity['no_events'] = $ne;
        }

        if (is_null($title) || strlen(trim($title)) == 0) {
            if (!empty($default_title)) {
                $title = $default_title;
            } else {
                $title = $plugin->instance;
            }
        }

        return $title;
    }

    /**
     * Check if a plugin is an event plugin
     *
     * Refactoring: decompose conditional
     *
     * Args:
     *      - Name of a plugin
     * Returns:
     *      - boolean
     * @access protected
     */
    static function is_event_plugin(string $name) : bool
    {
        return str_contains($name, '_event_');
    }

    /**
     * Prepares a cache of all event plugins and load them in queue so that they can be fetched
     *
     * Args:
     *      - If set to a string, a certain event plugin (eventwrapper) cache object will be returned by this function
     *      - If set to true, the list of cached event plugins will be refreshed
     * Returns:
     *      - Either returns the whole list of event plugins, or only a specific instance
     * @access protected
     */
    static function &get_event_plugins(string|bool $getInstance = false, bool $refresh = false) : iterable|object|string|false
    {
        static $event_plugins;
        static $false = false;

        if (!$refresh && isset($event_plugins) && is_array($event_plugins)) {
            if ($getInstance) {
                if (isset($event_plugins[$getInstance]['p'])) {
                    return $event_plugins[$getInstance]['p'];
                }
                return $false;
            }
            return $event_plugins;
        }

        $plugins = serendipity_plugin_api::enum_plugins('event');
        if (!is_array($plugins)) {
            return $false;
        }

        $event_plugins = array();
        foreach($plugins AS $plugin_data) {
            if ($event_plugins[$plugin_data['name']]['p'] = &serendipity_plugin_api::load_plugin($plugin_data['name'], $plugin_data['authorid'], $plugin_data['path'])) {
                /* query for its name, description and configuration data */
                $event_plugins[$plugin_data['name']]['b'] = new serendipity_property_bag;
                $event_plugins[$plugin_data['name']]['p']->introspect($event_plugins[$plugin_data['name']]['b']);
                $event_plugins[$plugin_data['name']]['t'] = serendipity_plugin_api::get_plugin_title($event_plugins[$plugin_data['name']]['p']);
            } else {
                unset($event_plugins[$plugin_data['name']]); // Unset failed plugins
            }
        }

        if ($getInstance) {
            if (isset($event_plugins[$getInstance]['p'])) {
                return $event_plugins[$getInstance]['p'];
            }
            return $false;
        }

        return $event_plugins;
    }

    /**
     * Executes a specific event hook
     *
     * If you want to temporarily block any event plugins, you can set $serendipity['no_events'] before
     * this method call.
     *
     * Args:
     *      - The name of the event to hook on to
     *      - May contain any type of variables that are passed by reference to an event plugin - bool(true) on empty data cases, like requesting an authors entries which has none yet, or empty categories data...
     *      - May contain any type of variables that are passed to an event plugin
     * Returns:
     *      - True on success, False on bypassed, permission failure
     * @access protected
     */
    static function hook_event(string $event_name, iterable|bool|int|string|null &$eventData, iterable|bool|int|string|null $addData = null) : bool
    {
        global $serendipity;

        // Can be bypassed globally by setting $serendipity['no_events'] = TRUE;
        if (isset($serendipity['no_events']) && $serendipity['no_events'] == true) {
            return false;
        }

        if (!isset($serendipity['enablePluginACL']) || ($serendipity['enablePluginACL'] && !serendipity_hasPluginPermissions($event_name))) {
            return false;
        }

        // We can NOT use a "return by reference" here, because then when
        // a plugin executes another event_hook, the referenced variable within
        // that call will overwrite the previous original plugin listing and
        // skip the execution of any follow-up plugins.
        $plugins = serendipity_plugin_api::get_event_plugins();

        if (isset($serendipity['core_events'][$event_name])) {
            foreach($serendipity['core_events'][$event_name] AS $apifunc_key => $apifunc) {
                $apifunc($event_name, $bag, $eventData, $addData);
            }
        }

        // execute backend needed core hooks
        serendipity_plugin_api_core_event_hook($event_name, $bag, $eventData, $addData);

        if (function_exists('serendipity_plugin_api_pre_event_hook')) {
            $apifunc = 'serendipity_plugin_api_pre_event_hook';
            $apifunc($event_name, $bag, $eventData, $addData);
        }

        // Function names cannot contain ":" etc, so if we ever have event looks like "backend:js" this
        // needs to be replaced to "backend_js". The real event name is passed as a function argument
        // These specific per-hook functions are utilized for theme's config.inc.php files
        // that act as an engine for other themes.
        $safe_event_name = preg_replace('@[^a-z0-9_]+@i', '_', $event_name);
        if (function_exists('serendipity_plugin_api_pre_event_hook_' . $safe_event_name)) {
            $apifunc = 'serendipity_plugin_api_pre_event_hook_' . $safe_event_name;
            $apifunc($event_name, $bag, $eventData, $addData);
        }

        if (is_array($plugins)) {
            foreach($plugins AS $plugin => &$plugin_data) {
                $bag    = &$plugin_data['b'];
                $phooks = &$bag->get('event_hooks');
                if (isset($phooks[$event_name])) {

                    // Check for cachable events.
                    if (is_array($eventData) && isset($eventData['is_cached']) && $eventData['is_cached']) {
                        $chooks = &$bag->get('cachable_events');
                        if (is_array($chooks) && isset($chooks[$event_name])) {
                            continue;
                        }
                    }

                    if ($serendipity['enablePluginACL'] && !serendipity_hasPluginPermissions($plugin)) {
                        continue;
                    }
                    $plugin_data['p']->event_hook($event_name, $bag, $eventData, $addData);
                }
            }

            if (function_exists('serendipity_plugin_api_event_hook')) {
                $apifunc = 'serendipity_plugin_api_event_hook';
                $apifunc($event_name, $bag, $eventData, $addData);
            }

            if (function_exists('serendipity_plugin_api_event_hook_' . $safe_event_name)) {
                $apifunc = 'serendipity_plugin_api_event_hook_' . $safe_event_name;
                $apifunc($event_name, $bag, $eventData, $addData);
            }

        }

        return true;
    }

    /**
     * Checks if a specific plugin instance is already installed
     *
     * Args:
     *      - A name (may contain wildcards) of a plugin class to check
     * Returns:
     *      - The plugin_instance_id if a plugin was found, else False
     * @access protected
     */
    static function exists(string $instance_id) : string|false
    {
        global $serendipity;

        if (!str_contains($instance_id, ':')) {
            $instance_id .= ':';
        }

        $existing = serendipity_db_query("SELECT name FROM {$serendipity['dbPrefix']}plugins WHERE name LIKE '%" . serendipity_db_escape_string($instance_id) . "%'");

        if (is_array($existing) && !empty($existing[0][0])) {
            return $existing[0][0]; // plugin_instance_id
        }

        return false;
    }

    /**
     * Install a new plugin by ensuring that it does not already exist
     *
     * Args:
     *      - The classname of the plugin
     *      - The new owner author
     *      - Indicates if the plugin is an event plugin
     * Returns:
     *      - Returns the plugin string or false, if failure
     * @access protected
     */
    static function &autodetect_instance(string $plugin_name, ?string $authorid, bool $is_event_plugin = false) : string|false
    {
        if ($is_event_plugin) {
            $side = 'event';
        } else {
            $side = 'right';
        }

        $classes = serendipity_plugin_api::enum_plugin_classes();
        if (isset($classes[$plugin_name])) {
            $instance = serendipity_plugin_api::create_plugin_instance($plugin_name, null, $side, $authorid, $classes[$plugin_name]['pluginPath']);
        } else {
            $instance = false;
        }

        return $instance;
    }

    /**
     * Probe for a language include with constants. Still include defines later on, if some constants were missing
     *
     * Args:
     *      - current plugin's path
     * Returns:
     *      - void
     * @access protected
     */
    static function load_language(string $path) : void
    {
        global $serendipity;

        $_charset = is_dir($path . '/' . $serendipity['charset']) ? $serendipity['charset'] : ''; // Temporary compat check as long as not having converted all official plugins

        $probelang = $path . '/' . $_charset . 'lang_' . $serendipity['lang'] . '.inc.php';
        if (file_exists($probelang)) {
            include $probelang;
        }

        include $path . '/lang_en.inc.php';
    }
}

// FROM HERE don't touch for type hinting or return types w/o doing this in all clones

/**
 * Holds a bunch of properties; since Serendipity 0.8 only one value per key is
 * allowed [was never really useful]
 */
class serendipity_property_bag
{
    /**
     * @access  private
     * @var array   property storage container.
     */
    var $properties = array();

    /**
     * @access private
     * @var    string   Name of the property bag
     */
    var $name = null;

    /**
     * Adds a property value to the bag
     *
     * Args:
     *      - The name of the property
     *      - The value of a property
     * Returns:
     *      - void
     * @access protected
     * @return null
     */
    function add($name, $value)
    {
        $this->properties[$name] = $value;
    }

    /**
     * Returns a property value of a bag
     *
     * Args:
     *      - Name of property to fetch
     * Returns:
     *      - void
     * @access protected
     * @return  mixed   The value of the property
     */
    function &get($name)
    {
        return $this->properties[$name];
    }

    /**
     * Check if a specific property name is already set
     *
     * Args:
     *      - Name of the property to check
     * Returns:
     *      - void
     * @access protected
     * @return  boolean True, if already set.
     */
    function is_set($name)
    {
        return isset($this->properties[$name]);
    }

}

/**
 * A core plugin, with methods that both event and sidebar plugins share
 */
class serendipity_plugin
{
    public $instance          = null;
    public $protected         = false;
    public $wrap_class        = 'serendipitySideBarItem';
    public $title_class       = 'serendipitySideBarTitle';
    public $content_class     = 'serendipitySideBarContent';
    public $title             = null;
    public $pluginPath        = null;
    public $act_pluginPath    = null;
    public $pluginFile        = null;
    public $serendipity_owner = null;
    public $dependencies            = [];
    public $markup_elements         = [];
    public $supported_properties    = [];

    /**
     * The constructor of a plugin
     *
     * Needs to be implemented by your own class.
     * Be sure to call this method from your derived classes constructors,
     * otherwise your config data will not be stored or retrieved correctly
     *
     * Args:
     *      -
     * Returns:
     *      - void
     * @access protected
     * @return true
     */
    function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Perform configuration routines
     *
     * Called by Serendipity when the plugin is being configured.
     * Can be used to query the database for configuration values that
     * only need to be available for the global configuration and not
     * on each page request.
     *
     * Args:
     *      -
     * Returns:
     *      - true
     * @access protected
     */
    function performConfig(&$bag)
    {
        return true;
    }

    /**
     * Perform install routines
     *
     * Called by Serendipity when the plugin is first installed.
     * Can be used to install database tables etc.
     *
     * Args:
     *      -
     * Returns:
     *      - true
     * @access protected
     */
    function install()
    {
        return true;
    }

    /**
     * Perform uninstall routines
     *
     * Called by Serendipity when the plugin is removed/uninstalled.
     * Can be used to drop installed database tables etc.
     *
     * Args:
     *      - A property bag object
     * Returns:
     *      - true
     * @access protected
     */
    function uninstall(&$propbag)
    {
        return true;
    }

    /**
     * The introspection function of a plugin, to setup properties
     *
     * Called by Serendipity when it wants to display information
     * about your plugin.
     * You need to override this method in your child class.
     *
     * Args:
     *      - A property bag object you can manipulate
     * Returns:
     *      - void
     * @access protected
     * @return true
     */
    function introspect(&$propbag)
    {
        $propbag->add('copyright', 'MIT License');
        $propbag->add('name'     , get_class($this));

        // $propbag->add(
        //   'configuration',
        //   array(
        //     'text field',
        //     'foo bar'
        //   )
        // );

        $this->protected = false; // If set to TRUE, only allows the owner of the plugin to modify its configuration

        return true;
    }

    /**
     * Introspection of a plugin configuration item
     *
     * Called by Serendipity when it wants to display the configuration
     * editor for your plugin.
     * $name is the name of a configuration item you added in
     * your introspect method.
     * You need to fill the property bag with appropriate items
     * that describe the type and value(s) for that particular
     * configuration option.
     * You need to override this method in your child class if
     * you have configuration options.
     *
     * Args:
     *      - Name of the config item
     *      - A property bag object you can store the configuration in
     * Returns:
     *      - void
     * @access protected
     * @return
     */
    function introspect_config_item($name, &$propbag)
    {
        return false;
    }

    /**
     * Validate plugin configuration options.
     *
     * Called from Plugin Configuration manager. Can be extended by your own plugin, if you need.
     *
     * Args:
     *      - Name of the config item to validate
     *      - Property bag of the config item
     *      - The value of a config item
     * Returns:
     *      - void
     * @access protected
     * @return
     */
    function validate($config_item, &$cbag, &$value)
    {
        static $pattern_mail  = '([\.\-\+~@_0-9a-z]+?)';
        static $pattern_url   = '([@!=~\?:&;0-9a-z#\.\-_\/]+?)';

        $validate = $cbag->get('validate');
        $valid    = true;

        if (!empty($validate)) {
            switch ($validate) {
                case 'string':
                    if (!preg_match('@^\w*$@i', $value)) {
                        $valid = false;
                    }
                    break;

                case 'words':
                    if (!preg_match('@^[\w\s\r\n,\.\-!\?:;&_/=%\$]*$@i', $value)) {
                        $valid = false;
                    }
                    break;

                case 'number':
                    if (!preg_match('@^[\d]*$@', $value)) {
                        $valid = false;
                    }
                    break;

                case 'url':
                    if (!preg_match('�^' . $pattern_url . '$�', $value)) {
                        $valid = false;
                    }
                    break;

                case 'mail':
                    if (!preg_match('�^' . $pattern_mail . '$�', $value)) {
                        $valid = false;
                    }
                    break;

                case 'path':
                    if (!preg_match('@^[\w/_.\-~]$@', $value)) {
                        $valid = false;
                    }
                    break;

                default:
                    if (!preg_match($validate, $value)) {
                        $valid = false;
                    }
                    break;
            }

            $error = $cbag->get('validate_error');
            if ($valid) {
                return true;
            } elseif (!empty($error)) {
                return $error;
            } else {
                return sprintf(PLUGIN_API_VALIDATE_ERROR, $config_item, $validate);
            }
        }

        return true;
    }

    /**
     * Output plugin's contents (Sidebar plugins)
     *
     * Called by Serendipity when it wants your plugin to display itself.
     * You need to set $title to be whatever text you want want to
     * appear in the item caption space.
     * Simply echo/print your content to the output; Serendipity will
     * capture it and make things work.
     * You need to override this method in your child class.
     *
     * Args:
     *      - The referenced variable that holds the sidebar title of your plugin.
     * Returns:
     *      - void
     * @access protected
     */
    function generate_content(string &$title)
    {
        $title = 'Sample!';
        echo     'This is a sample!';
    }

    /**
     * Get a config value of the plugin
     *
     * Args:
     *      - Name of the config value to fetch
     *      - The default value of a configuration item, if not set
     *      - If true, the default value will only be set if the plugin config item was not set.
     * Returns:
     *      - void
     * @access protected
     * @return  mixed   The value of the config item
     */
    function get_config($name, $defaultvalue = null, $empty = true)
    {
        if (is_int($defaultvalue)) {
            $defaultvalue = (string) $defaultvalue;
        }
        $_res = serendipity_get_config_var($this->instance . '/' . $name, $defaultvalue, $empty);

        if (is_null($_res)) {
            // A protected plugin by a specific owner may not have its values stored in $serendipity
            // because of the special authorid. To display such contents, we need to fetch it
            // separately from the DB.
            $_res = serendipity_get_user_config_var($this->instance . '/' . $name, null, $defaultvalue);
        }

        if (is_null($_res)) {
            $cbag = new serendipity_property_bag;
            $this->introspect_config_item($name, $cbag);
            $_res = $cbag->get('default');
            unset($cbag);
            // Set the fetched value, so the default will not be fetched the next config call time
            $this->set_config($name, $_res);
        }

        return $_res;
    }

    /**
     * Sets a configuration value for a plugin
     *
     * Args:
     *      - Name of the plugin configuration item
     *      - Value of the plugin configuration item
     *      - A concatenation key for imploding arrays
     * Returns:
     *      - void
     * @access protected
     * @return
     */
    function set_config($name, $value, $implodekey = '^')
    {
        $name = $this->instance . '/' . $name;

        if (is_array($value)) {
            $dbvalue = implode($implodekey, $value);
            $_POST['serendipity']['plugin'][$name] = $dbvalue;
        } else {
            $dbvalue = $value;
        }

        return serendipity_set_config_var($name, $dbvalue);
    }

    /**
     * Garbage Collection
     *
     * Called by Serendipity after insertion of a config item. If you want to kick out certain
     * elements based on contents, create the corresponding function here.
     *
     * Args:
     *      -
     * Returns:
     *      - void
     * @access protected
     * @return true
     */
    function cleanup()
    {
        // Cleanup. Remove all empty configs on SAVECONF-Submit.
        // serendipity_plugin_api::remove_plugin_value($this->instance, array('configname1', 'configname2'));
        return true;
    }

    /**
     * Auto-Register dependencies of a plugin
     *
     * This method evaluates the "dependencies" member variable to check which plugins need to be installed.
     *
     * Args:
     *      - If true, a depending plugin will be removed when this plugin is uninstalled
     *      - The owner id of the current plugin
     * Returns:
     *      - void
     * @access protected
     * @return true
     */
    function register_dependencies($remove = false, $authorid = '0')
    {
        if (isset($this->dependencies) && is_array($this->dependencies)) {

            if ($remove) {
                $dependencies = explode(';', $this->get_config('dependencies', ''));
                $modes        = explode(';', $this->get_config('dependency_modes', ''));

                if (!empty($dependencies) && is_array($dependencies)) {
                    foreach($dependencies AS $idx => $dependency) {
                        if ($modes[$idx] == 'remove' && serendipity_plugin_api::exists($dependency)) {
                            serendipity_plugin_api::remove_plugin_instance($dependency);
                        }
                    }
                }
            } else {
                $keys  = array();
                $modes = array();
                foreach($this->dependencies AS $dependency => $mode) {
                    $exists = serendipity_plugin_api::exists($dependency);
                    if (!$exists) {
                        if (serendipity_plugin_api::is_event_plugin($dependency)) {
                            $keys[] = serendipity_plugin_api::autodetect_instance($dependency, $authorid, true);
                        } else {
                            $keys[] = serendipity_plugin_api::autodetect_instance($dependency, $authorid, false);
                        }
                    } else {
                        $keys[] = $exists;
                    }

                    $modes[] = $mode;
                }

                $this->set_config('dependencies',     implode(';', $keys));
                $this->set_config('dependency_modes', implode(';', $modes));
            }
        }

        return true;
    }

    /**
     * Parses a smarty template file (which can be stored in either the plugin directory, the user template directory
     * or the default template directory, and return the parsed output.
     *
     * Args:
     *      - template filename (no directory!)
     *      - Called by a plugin (defaults true), since we do not have a theme using it yet
     * Returns:
     *      - void
     * @access protected
     * @return string   Parsed Smarty return
     */
    function &parseTemplate($filename, $plugin = true)
    {
        global $serendipity;

        $filename = basename($filename);
        $tfile    = serendipity_getTemplateFile($filename, 'serendipityPath', true, $plugin); // use the simple plugin fallback cascade
        if (!$tfile || $tfile == $filename) {
            $tfile = dirname($this->pluginFile) . '/' . $filename;
        }

        $template = $serendipity['smarty']->fetch('file:'. $tfile);// avoid Notice: Only variable references should be returned by reference
        return $template;
    }

}

/**
 * Events can be called on several occasions when s9y performs an action.
 * One or multiple plugin can be registered for each of those hooks.
 */
class serendipity_event extends serendipity_plugin
{

    /**
     * The class constructor
     *
     * Be sure to call this method from your derived classes constructors,
     * otherwise your config data will not be stored or retrieved correctly
     *
     * Args:
     *      - The instance name
     * Returns:
     *      - void
     * @access protected
     */
    function __construct($instance)
    {
        $this->instance = $instance;
    }

    /**
     * Gets a reference to an $entry / $eventData array pointer, interacting with Cache-Options
     *
     * This function is used by specific event plugins that require to properly get a reference
     * to the 'extended' or 'body' field of an entry superarray. If they would immediately operate
     * on the 'body' field, it might get overwritten by other plugins later on.
     *
     * Args:
     *      - The fieldname to get a reference for - default is 'body'
     *      - The entry superarray to get the reference from
     * Returns:
     *      - void
     * @access protected
     * @return  array       The value of the array for the fieldname (reference)
     */
    function &getFieldReference($fieldname, &$eventData)
    {
        $fieldname = $fieldname ?? 'body'; // Avoids: Required parameter $param_required follows optional parameter $param_optional in ...

        // Get a reference to a content field (body/extended) of
        // $entries input data. This is a unifying function because
        // several plugins are using similar fields.

        if (is_array($eventData) && isset($eventData[0]) && is_array($eventData[0]) && is_array($eventData[0]['properties'])) {
            if (!empty($eventData[0]['properties']['ep_cache_' . $fieldname])) {

                // It may happen that there is no extended entry to concatenate to. In that case,
                // create a dummy extended entry.
                if (!isset($eventData[0]['properties']['ep_cache_' . $fieldname])) {
                    $eventData[0]['properties']['ep_cache_' . $fieldname] = '';
                }

                $key = &$eventData[0]['properties']['ep_cache_' . $fieldname];
            } else {
                $key = &$eventData[0][$fieldname];
            }
        } elseif (isset($eventData['properties']) && is_array($eventData['properties'])) {
            if (!empty($eventData['properties']['ep_cache_' . $fieldname])) {
                $key = &$eventData['properties']['ep_cache_' . $fieldname];
            } else {
                $key = &$eventData[$fieldname];
            }
        } elseif (isset($eventData[0][$fieldname])) {
            $key = &$eventData[0][$fieldname];
        } elseif (isset($eventData[$fieldname])) {
            $key = &$eventData[$fieldname];
        } else {
            $key = '';
        }

        return $key;
    }

    /**
     * Main logic for making a plugin "listen" to an event
     *
     * This method is called by the main plugin API for every event, that is executed.
     * You need to implement each actions that shall be performed by your plugin here.
     *
     * Args:
     *      - The name of the executed event
     *      - A property bag for the current plugin
     *      - Any referenced event data from the serendipity_plugin_api::hook_event() function
     *      - Any additional data from the hook_event call
     * Returns:
     *      - true
     * @access protected
     */
    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        // Define event hooks here, if you want your plugin to execute those instead of being a sidebar item.
        // Look at in/external plugins 'serendipity_event_mailer' or 'serendipity_event_weblogping' for usage.
        // Currently available events:
        //   backend_publish [after insertion of a new article in your s9y-backend]
        //   backend_display [after displaying an article in your s9y-backend]
        //   frontend_display [before displaying an article in your s9y-frontend]
        //   frontend_comment [after displaying the "enter comment" dialog]
        //   ...and some more in the meanwhile...! :)
        return true;
    }

}

/* vim: set sts=4 ts=4 expandtab : */
