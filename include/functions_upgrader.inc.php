<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (defined('S9Y_FRAMEWORK_UPGRADER')) {
    return;
}
@define('S9Y_FRAMEWORK_UPGRADER', true);

/**
 * This is a list of functions that are used by the upgrader. Define functions here that
 * are not used within usual Serendipity control flow
 */

/* A list of files which got obsoleted in 0.8 */
$obsolete_files = array(
    'serendipity.inc.php',
    'serendipity_layout.inc.php',
    'serendipity_layout_table.inc.php',
    'serendipity_entries_overview.inc.php',
    'serendipity_rss_exchange.inc.php',
    'serendipity_admin_category.inc.php',
    'serendipity_admin_comments.inc.php',
    'serendipity_admin_entries.inc.php',
    'serendipity_admin_images.inc.php',
    'serendipity_admin_installer.inc.php',
    'serendipity_admin_interop.inc.php',
    'serendipity_admin_overview.inc.php',
    'serendipity_admin_plugins.inc.php',
    'serendipity_admin_templates.inc.php',
    'serendipity_admin_upgrader.inc.php',
    'serendipity_admin_users.inc.php',
    'compat.php',
    'serendipity_functions_config.inc.php',
    'serendipity_functions_images.inc.php',
    'serendipity_functions_installer.inc.php',
    'serendipity_genpage.inc.php',
    'serendipity_lang.inc.php',
    'serendipity_plugin_api.php',
    'serendipity_sidebar_items.php',
    'serendipity_db.inc.php',
    'serendipity_db_mysql.inc.php',
    'serendipity_db_mysqli.inc.php',
    'serendipity_db_postgres.inc.php',
    'serendipity_db_pdo-postgres.inc.php',
    'serendipity_db_sqlite.inc.php',
    'serendipity_db_sqlite3.inc.php',
    'htaccess.cgi.errordocs.tpl',
    'htaccess.cgi.normal.tpl',
    'htaccess.cgi.rewrite.tpl',
    'htaccess.errordocs.tpl',
    'htaccess.normal.tpl',
    'htaccess.rewrite.tpl',
    'serendipity_config_local.tpl',
    'serendipity_config_user.tpl',
    'INSTALL',
    'LICENSE',
    'NEWS',
    'README',
    'TODO',
    'upgrade.sh',
    'templates/default/layout.php'
);

/* A list of smarty 2.6.x lib files which got obsoleted in >= 1.7 */
$dead_smarty_files = array(
    'BUGS',
    'ChangeLog',
    'FAQ',
    'INSTALL',
    'libs/config_file.class.php',
    'libs/smarty_compiler.class.php',
    'libs/internals/core.assemble_plugin_filepath.php',
    'libs/internals/core.assign_smarty_interface.php',
    'libs/internals/core.create_dir_structure.php',
    'libs/internals/core.display_debug_console.php',
    'libs/internals/core.get_include_path.php',
    'libs/internals/core.get_microtime.php',
    'libs/internals/core.get_php_resource.php',
    'libs/internals/core.is_secure.php',
    'libs/internals/core.is_trusted.php',
    'libs/internals/core.load_plugins.php',
    'libs/internals/core.load_resource_plugin.php',
    'libs/internals/core.process_cached_inserts.php',
    'libs/internals/core.process_compiled_include.php',
    'libs/internals/core.read_cache_file.php',
    'libs/internals/core.rm_auto.php',
    'libs/internals/core.rmdir.php',
    'libs/internals/core.run_insert_handler.php',
    'libs/internals/core.smarty_include_php.php',
    'libs/internals/core.write_cache_file.php',
    'libs/internals/core.write_compiled_include.php',
    'libs/internals/core.write_compiled_resource.php',
    'libs/internals/core.write_file.php',
    'libs/plugins/compiler.assign.php',
    'libs/plugins/function.assign_debug_info.php',
    'libs/plugins/function.config_load.php',
    'libs/plugins/function.debug.php',
    'libs/plugins/function.eval.php',
    'libs/plugins/function.popup.php',
    'libs/plugins/function.popup_init.php',
    'libs/plugins/modifier.cat.php',
    'libs/plugins/modifier.count_characters.php',
    'libs/plugins/modifier.count_paragraphs.php',
    'libs/plugins/modifier.count_sentences.php',
    'libs/plugins/modifier.count_words.php',
    'libs/plugins/modifier.default.php',
    'libs/plugins/modifier.indent.php',
    'libs/plugins/modifier.lower.php',
    'libs/plugins/modifier.nl2br.php',
    'libs/plugins/modifier.string_format.php',
    'libs/plugins/modifier.strip.php',
    'libs/plugins/modifier.strip_tags.php',
    'libs/plugins/modifier.upper.php',
    'libs/plugins/modifier.wordwrap.php',
    'QUICK_START',
    'NEWS',
    'RELEASE_NOTES',
    'TODO'
);

/* A list of old WYSIWYG-Editor lib files which got obsoleted in 2.0 */
$dead_htmlarea_files = array(
    'htmlarea.css',
    'htmlarea.js',
    'index.html',
    'license.txt',
    'my_custom.js',
    'popupdiv.js',
    'popupwin.js',
    'reference.html',
    'release-notes.txt',
    'Xinha.css',
    'XinhaCore.js',
    'XinhaLoader.js',
    'XinhaLoader_readme.txt',
    'ckeditor/build-config.js',
    'ckeditor/skins/moono/images/mini.png'
);

/* A list of old WYSIWYG-Editor lib directories which got obsoleted in 2.0 */
$dead_htmlarea_dirs = array(
    $serendipity['serendipityPath'] . 'htmlarea/contrib',
    $serendipity['serendipityPath'] . 'htmlarea/examples',
    $serendipity['serendipityPath'] . 'htmlarea/images',
    $serendipity['serendipityPath'] . 'htmlarea/lang',
    $serendipity['serendipityPath'] . 'htmlarea/modules',
    $serendipity['serendipityPath'] . 'htmlarea/plugins',
    $serendipity['serendipityPath'] . 'htmlarea/popups',
    $serendipity['serendipityPath'] . 'htmlarea/skins',
    $serendipity['serendipityPath'] . 'htmlarea/ckeditor/samples'
);

/* A list of old Serendipity files which were not marked obsolet with 2.0.0 upgrade - now 2.0.2 */
$dead_files_200 = array(
    'serendipity_editor.js',
    'serendipity_define.js.php',
    'bundled-libs/dragdrop.js',
    'bundled-libs/imgedit.js',
    'bundled-libs/Smarty/libs/sysplugins/smarty_config_source.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_config.php',
    'bundled-libs/YahooUI/treeview/license.txt',
    'bundled-libs/YahooUI/treeview/treeview.js',
    'bundled-libs/YahooUI/treeview/YAHOO.js',
    'deployment/serendipity_editor.js',
    'deployment/serendipity_define.js.php',
    'docs/CHANGED_FILES',
    'docs/INSTALL_EMBEDED',
    'docs/INSTALL_SHARED',
    'docs/UPGRADE',
    'docs/upgrade.sh',
    'htmlarea/ChangeLog',
    'htmlarea/dialog.js',
    'htmlarea/release-notes.html',
    'include/plugin_internal.inc.php',
    'templates/HOWTO',
    'templates/blue/htmlarea.css',
    'templates/default/htmlarea.css',
    'templates/default-rtl/htmlarea.css'
);

/* A list of old lib directories which were not marked obsolet with 2.0.0 upgrade - now 2.0.2 */
$dead_dirs_200 = array(
    $serendipity['serendipityPath'] . 'htmlarea/plugins/ImageManage',
    $serendipity['serendipityPath'] . 'htmlarea/plugins',
    $serendipity['serendipityPath'] . 'bundled-libs/YahooUI/treeview',
    $serendipity['serendipityPath'] . 'bundled-libs/YahooUI'
);

/* A list of old or beta Serendipity files, which were not marked obsolet with the 2.0.2 upgrade, or were removed by 2.1.0 */
$dead_files_202 = array(
    'composer.phar',
    'bundled-libs/katzgrau/klogger/phpunit.xml',
    'bundled-libs/PEAR5.php',
    'bundled-libs/Smarty/.travis.yml',
    'bundled-libs/Smarty/composer.json',
    'bundled-libs/Smarty/COPYING.lib',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_extension_clear.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_extension_codeframe.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_extension_config.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_extension_defaulttemplatehandler.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_filter_handler.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_function_call_handler.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_get_include_path.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_runtime_inline.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_utility.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_write_file.php',
    'bundled-libs/Smarty/travis.ini',
    'docs/CHANGED_FILES',
    'docs/INSTALL_EMBEDED',
    'docs/INSTALL_SHARED',
    'docs/UPGRADE',
    'docs/upgrade.sh',
    'htmlarea/autoload.php',
    'htmlarea/ckeditor/ckeditor/build-config.js',
    'htmlarea/composer.json',
    'htmlarea/composer.lock',
    'plugins/serendipity_event_gravatar/ycon/UTF-8/lang_pl.inc.php',
    'plugins/serendipity_event_gravatar/UTF-8/documentation_cz.html',
    'plugins/serendipity_event_gravatar/UTF-8/documentation_cs.html',
    'plugins/serendipity_event_creativecommons/UTF-8/documentation_cz.html',
    'plugins/serendipity_event_creativecommons/UTF-8/documentation_cs.html',
    'templates/default/dragdrop.js',
    'templates/default/imgedit.js',
    'templates/default/admin/admin_scripts.js',
    'templates/default/admin/entries.tpl',
    'templates/default/admin/header_spawn.js',
    'templates/default/admin/image_selector.js',
    'templates/default/admin/imgedit.css',
    'templates/default/admin/index.tpl',
    'templates/default/admin/media_choose.tpl',
    'templates/default/admin/media_imgedit.tpl',
    'templates/default/admin/media_imgedit_done.tpl',
    'templates/default/admin/media_items.tpl',
    'templates/default/admin/media_pane.tpl',
    'templates/default/admin/media_properties.tpl',
    'templates/default/admin/media_showitem.tpl',
    'templates/default/admin/media_upload.tpl',
    'templates/default/admin/pluginmanager.css',
    'templates/default/admin/style.css',
    'templates/default/admin/img/accept.png',
    'templates/default/admin/img/admin_msg_error.png',
    'templates/default/admin/img/admin_msg_note.png',
    'templates/default/admin/img/admin_msg_success.png',
    'templates/default/admin/img/background.jpg',
    'templates/default/admin/img/banner_background.png',
    'templates/default/admin/img/big_delete.png',
    'templates/default/admin/img/big_rename.png',
    'templates/default/admin/img/big_resize.png',
    'templates/default/admin/img/big_rotate_ccw.png',
    'templates/default/admin/img/big_rotate_cw.png',
    'templates/default/admin/img/big_zoom.png',
    'templates/default/admin/img/button_background.png',
    'templates/default/admin/img/clock.png',
    'templates/default/admin/img/clock_future.png',
    'templates/default/admin/img/configure.png',
    'templates/default/admin/img/delete.png',
    'templates/default/admin/img/downarrow.png',
    'templates/default/admin/img/edit.png',
    'templates/default/admin/img/folder.png',
    'templates/default/admin/img/grablet.gif',
    'templates/default/admin/img/grablet_over.gif',
    'templates/default/admin/img/imgedit_area.gif',
    'templates/default/admin/img/imgedit_orientation.gif',
    'templates/default/admin/img/imgedit_slider.gif',
    'templates/default/admin/img/imgedit_varea.gif',
    'templates/default/admin/img/infobar_background.png',
    'templates/default/admin/img/install.png',
    'templates/default/admin/img/install_now.png',
    'templates/default/admin/img/install_now_spartacus.png',
    'templates/default/admin/img/install_template.png',
    'templates/default/admin/img/menu_background.png',
    'templates/default/admin/img/menuheader_background.png',
    'templates/default/admin/img/menuitem.png',
    'templates/default/admin/img/next.png',
    'templates/default/admin/img/previous.png',
    'templates/default/admin/img/rotate.png',
    'templates/default/admin/img/unconfigure.png',
    'templates/default/admin/img/uparrow.png',
    'templates/default/admin/img/upgrade_now.png',
    'templates/default/admin/img/user_admin.png',
    'templates/default/admin/img/user_chief.png',
    'templates/default/admin/img/user_editor.png',
    'templates/default/admin/img/zoom.png',
    'templates/2k11/admin/media_showitem.tpl',
    'templates/Sagittarius-A/admin/media_showitem.tpl'
);

/* A list of old non-empty directories which were removed with 2.1.0 */
$dead_dirs_202 = array(
    $serendipity['serendipityPath'] . 'bundled-libs/docs',
    $serendipity['serendipityPath'] . 'bundled-libs/katzgrau/klogger/tests',
    $serendipity['serendipityPath'] . 'bundled-libs/psr/log/Psr/Log/Test',
    $serendipity['serendipityPath'] . 'bundled-libs/Text',
    $serendipity['serendipityPath'] . 'htmlarea/ckeditor/ckeditor',
    $serendipity['serendipityPath'] . 'htmlarea/composer',
    $serendipity['serendipityPath'] . 'plugins/serendipity_event_gravatar/ycon/UTF-8',
    $serendipity['serendipityPath'] . 'templates/2k11/admin/img',
    $serendipity['serendipityPath'] . 'templates/default/treeview',
    $serendipity['serendipityPath'] . 'templates/default/YahooUI',
/*    $serendipity['serendipityPath'] . 'templates/bulletproof/admin',*/
    $serendipity['serendipityPath'] . 'templates/default-rtl/admin',
    $serendipity['serendipityPath'] . 'templates/carl_contest/admin',
    $serendipity['serendipityPath'] . 'templates/competition/admin',
    $serendipity['serendipityPath'] . 'templates/contest/admin'
);

/**
 * recursive directory call to purge files and directories
 *
 * @param array $dir directories
 * @return void
 */
function recursive_directory_iterator($dir = array()) {
    foreach ($dir AS $path) {
        serendipity_removeDeadFiles_SPL($path);
        @rmdir($path);
    }
}

/**
 * Fix inpropper plugin constant names
 *
 * Before Serendipity 0.8, some plugins contained localized strings for indiciating some
 * configuration values. That got deprecated, and replaced by a language-independent constant.
 *
 * @access private
 * @param  string   (reserved for future use)
 * @return boolean
 */
function serendipity_fixPlugins($case) {
    global $serendipity;

    switch($case) {
        case 'markup_column_names':
            $affected_plugins = array(
                'serendipity_event_bbcode',
                'serendipity_event_contentrewrite',
                'serendipity_event_emoticate',
                'serendipity_event_geshi',
                'serendipity_event_nl2br',
                'serendipity_event_textwiki',
                'serendipity_event_trackexits',
                'serendipity_event_xhtmlcleanup',
                'serendipity_event_markdown',
                'serendipity_event_s9ymarkup',
                'serendipity_event_searchhighlight',
                'serendipity_event_textile'
            );

            $elements = array(
                'ENTRY_BODY',
                'EXTENDED_BODY',
                'COMMENT',
                'HTML_NUGGET'
            );

            $where = array();
            foreach($affected_plugins AS $plugin) {
                $where[] = "name LIKE '$plugin:%'";
            }

            $rows = serendipity_db_query("SELECT name, value, authorid
                                            FROM {$serendipity['dbPrefix']}config
                                           WHERE " . implode(' OR ', $where));
            if (!is_array($rows)) {
                return false;
            }

            foreach($rows AS $row) {
                if (preg_match('@^(serendipity_event_.+):([a-z0-9]+)/(.+)@i', $row['name'], $plugin_data)) {
                    foreach($elements AS $element) {
                        if ($plugin_data[3] != constant($element)) {
                            continue;
                        }

                        $new = $plugin_data[1] . ':' . $plugin_data[2] . '/' . $element;
                        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config
                                                 SET name     = '$new'
                                               WHERE name     = '{$row['name']}'
                                                 AND value    = '{$row['value']}'
                                                 AND authorid = '{$row['authorid']}'");
                    }
                }
            }

            return true;
            break;
    }
}

/**
 * Create default groups, when migrating.
 *
 * @access private
 */
function serendipity_addDefaultGroups() {
    global $serendipity;

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}groups");
    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}groupconfig");
    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authorgroups");

    serendipity_addDefaultGroup(USERLEVEL_EDITOR_DESC, USERLEVEL_EDITOR);
    serendipity_addDefaultGroup(USERLEVEL_CHIEF_DESC,  USERLEVEL_CHIEF);
    serendipity_addDefaultGroup(USERLEVEL_ADMIN_DESC,  USERLEVEL_ADMIN);
}


/**
 * baseURL is now defaultBaseURL in the database, so copy if not already set
 *
 */
function serendipity_copyBaseURL() {
    global $serendipity;
    if ((serendipity_get_config_var("defaultBaseURL") === false || serendipity_get_config_var("defaultBaseURL") == "" ) && serendipity_get_config_var("baseURL") !== false) {
        serendipity_set_config_var("defaultBaseURL", serendipity_get_config_var("baseURL"));
    }
}

/**
 * DELETEs a plugin name value in the database by name
 *
 * @param   string  $name
 *
 * @return void
 */
function serendipity_killPlugin($name) {
    global $serendipity;

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}plugins WHERE name LIKE '" . serendipity_db_escape_string($name) . "%'");
}

/**
 * Empty a given directory recursively using the Standard PHP Library (SPL) iterator
 * Use as full purge by serendipity_removeDeadFiles_SPL(/path/to/dir)
 * Or strict by serendipity_removeDeadFiles_SPL('/path/to/dir', $filelist, $directorylist, true)
 *
 * @access private
 *
 * @param   string $dir directory
 * @param   array $deadfiles dead files list
 * @param   array $purgedir dead directories list
 * @param   boolean $list_only run list only else recursive default
 *
 * @return void
 */
function serendipity_removeDeadFiles_SPL($dir=null, $deadfiles=null, $purgedir=null, $list_only=false) {

    if (!is_dir($dir)) return;
    try {
        $_dir = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    // NOTE: UnexpectedValueException thrown for PHP >= 5.3
    } catch (Exception $e) {
        return;
    }

    $debugSPL = false; // dev debug only
    $iterator = new RecursiveIteratorIterator($_dir, RecursiveIteratorIterator::CHILD_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD); // path, mode, flag
    $search   = array("\\", '//');
    $replace  = array('/');

    foreach ($iterator AS $file) {
        $thisfile = str_replace($search, $replace, $file->__toString());
        if ($file->isFile()) {
            if (is_array($deadfiles) && !empty($deadfiles)) {
                foreach ($deadfiles AS $deadfile) {
                    if ($debugSPL) {
                        if (basename($deadfile) == basename($thisfile)) echo 'LIST FILE: ' . $dir . '/' . $deadfile . ' == ' . $thisfile . ' && basename(file) == ' . basename($thisfile) . "<br>\n";
                    }
                    if ($dir . '/' . $deadfile === $thisfile) {
                        if ($debugSPL) {
                            echo '<b>LIST & REMOVE FILE</b>: ' . basename($deadfile) . ' == <b>REAL FILE</b>: ' . basename($thisfile) . '<br><u>Remove</u>: ' . $thisfile . "<br>\n";
                        }
                        @unlink($thisfile);
                        continue;
                    }
                }
            } else {
                // this is original file purge
                if ($debugSPL) {
                    echo '<b>FULL PURGE EACH FILE</b>: ' . $thisfile . "<br>\n";
                }
                @unlink($thisfile);
            }
        } else {
            if (is_array($purgedir) && !empty($purgedir) ) {
                foreach ($purgedir AS $pdir) {
                    if (basename($thisfile) == $pdir) {
                        if ($debugSPL) {
                            echo '<b><u>LIST & REMOVE EMPTY DIRECTORY</u></b>: ' . $thisfile . "<br><br>\n";
                        }
                        @rmdir($thisfile);
                        continue;
                    }
                }
            }
            // this is original directory purge
            if (!$list_only) {
                if ($debugSPL) {
                    echo '<b><u>FULL PURGE DIRECTORY</u></b>: ' . $thisfile . "<br>\n";
                }
                @rmdir($thisfile);
            }
        }
    }
}

/**
 * Remove and cleanup empty directories using the Standard PHP Library (SPL) iterator
 * Use as a standard upgrade task on every release!
 *
 * @access private
 *
 * @param   string $path parent directory
 *
 * @return void
 */
function serendipity_cleanUpDirectories_SPL( $path=null ) {
    if (!is_dir($path)) {
        return;
    }
    try {
        $files = array();
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files AS $fileinfo) {
            if ($fileinfo->isDir()) {
                // Count the number of "children" from the main directory iterator
                if (iterator_count($files->getChildren()) === 0) {
                    #DEBUG#echo $fileinfo->getRealPath()."\n<br>";
                    @rmdir($fileinfo->getRealPath());
                }
            }
        }
        return true;
    } catch (Exception $e) {
        return;
    }
}

/**
 * Select and rename old plugin (class) name values in the database by name
 * due to plugin naming convention (serendipity_plugin_* and serendipity_event_*)
 * and now being filed and removed into '/plugins' with 2.0
 *
 * @return void
 */
function serendipity_upgrader_rename_plugins() {
    global $serendipity;

    $plugs = serendipity_db_query("SELECT name FROM {$serendipity['dbPrefix']}plugins WHERE name LIKE '@%' OR name LIKE 'serendipity_html_nugget_plugin%'");

    if (is_array($plugs)) {
        foreach($plugs AS $plugin) {
            $origname = $plugin['name'];
            $plugin['name'] = str_replace('@', '', $plugin['name']);
            $plugin['name'] = preg_replace('@serendipity_([^_]+)_plugin@i', 'serendipity_plugin_\1', $plugin['name']); // force old (core) plugins to plugin naming convention with 2.0+
            $plugin['name'] = str_replace('serendipity_html_nugget_plugin', 'serendipity_plugin_html_nugget', $plugin['name']); // dito force renaming of old nugget plugin name explicitly
#            $plugin['name'] = str_replace('serendipity_plugin_topreferers', 'serendipity_plugin_topreferrers', $plugin['name']); // dito old plugin now lives as topreferrers plugin
            $pluginparts = explode(':', $plugin['name']);

            echo "<!-- " . serendipity_specialchars($origname) . " &gt;&gt; " . serendipity_specialchars($plugin['name']) . "-->\n";
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}plugins SET name = '" . serendipity_db_escape_string($plugin['name']) . "', path = '" . serendipity_db_escape_string($pluginparts[0]) . "' WHERE name = '" . serendipity_db_escape_string($origname) . "'");
        }
    }

    $configs = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}config WHERE name LIKE '@%' OR name LIKE 'serendipity_html_nugget_plugin%'");

    if (is_array($configs)) {
        foreach($configs AS $config) {
            $origname = $config['name'];
            $config['name'] = str_replace('@', '', $config['name']);
            $config['name'] = preg_replace('@serendipity_([^_]+)_plugin@i', 'serendipity_plugin_\1', $config['name']);
            $config['name'] = str_replace('serendipity_html_nugget_plugin', 'serendipity_plugin_html_nugget', $config['name']);
            #$configparts = explode(':', $config['name']);

            echo "<!--[C] " . serendipity_specialchars($origname) . " &gt;&gt; " . serendipity_specialchars($config['name']) . "-->\n";
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config SET name = '" . serendipity_db_escape_string($config['name']) . "' WHERE name = '" . serendipity_db_escape_string($origname) . "'");
        }
    }
}

/**
 * Select and rewrite the syndication plugin feed icon path value in the database with 2.0+
 *
 * @return void
 */
function serendipity_upgrader_rewriteFeedIcon() {
    global $serendipity;

    $path = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE '%serendipity_plugin_syndication%big_img'", true);
    if (is_array($path)) {
        $path = $path[0];
    }
    $path = preg_replace('#' . $serendipity['serendipityHTTPPath'] . 'templates/[^/]*/#', '', $path);
    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config SET value = '" . serendipity_db_escape_string($path) . "' WHERE name LIKE '%serendipity_plugin_syndication%big_img'");
}

/**
 * Select and rewrite syndication plugin feed config value names in the database with 2.0+
 *
 * @return void
 */
function serendipity_upgrader_move_syndication_config() {
    global $serendipity;

    $optionsToPort = array( 'bannerURL'             => 'feedBannerURL',
                            'fullfeed'              => 'feedFull',
                            'bannerWidth'           => 'feedBannerWidth',
                            'bannerHeight'          => 'feedBannerHeight',
                            'show_mail'             => 'feedShowMail',
                            'field_managingEditor'  => 'feedManagingEditor',
                            'field_webMaster'       => 'feedWebmaster',
                            'field_ttl'             => 'feedTtl',
                            'field_pubDate'         => 'feedPubDate'
                    );
    foreach ($optionsToPort AS $oldPluginOption => $newGeneralOption) {
        $value = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE NAME LIKE 'serendipity_plugin_syndication%{$oldPluginOption}'", true);
        if (is_array($value)) {
            $value = $value[0];
        }
        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}config(name, value) VALUES ('$newGeneralOption', '". serendipity_db_escape_string($value) ."')");
    }

    $fbid = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE NAME LIKE 'serendipity_plugin_syndication%fb_id'");
    $show_feedburner = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE NAME LIKE 'serendipity_plugin_syndication%show_feedburner'");
    if ($show_feedburner == 'force') {
        if (!empty($fbid)) {
            $fburl = 'http://feeds.feedburner.com/' . $fbid;
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}config(name, value) VALUES ('feedCustom', '" . serendipity_db_escape_string($fburl) ."')");
        }
    }
}
