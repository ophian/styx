<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

require_once(S9Y_INCLUDE_PATH . 'include/functions_installer.inc.php');
require_once(S9Y_INCLUDE_PATH . 'include/functions_upgrader.inc.php');

define('S9Y_U_ERROR', -1);
define('S9Y_U_WARNING', 0);
define('S9Y_U_SUCCESS', 1);

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
function serendipity_upgraderResultDiagnose($result, $s) {
    global $errorCount, $data;

    if ($result === S9Y_U_SUCCESS) {
        $data['u_success'] = true; // we do not need data here explicitly, but we keep it for possible future purposes
        return '<span class="msg_success">'. $s .'</span>';
    }

    if ($result === S9Y_U_WARNING) {
        $data['u_warning'] = true;
        return '<span class="msg_notice">'. $s .'</span>';
    }

    if ($result === S9Y_U_ERROR) {
        $errorCount++;
        $data['u_error'] = true;
        return '<span class="msg_error">'. $s .'</span>';
    }
}

/* shall we add the function to smarty ?? */
/*
function serendipity_smarty_backend_upgraderResultDiagnose($params, $smarty) {
    $ssb_URD = serendipity_upgraderResultDiagnose($params[0], $params[1]);
    $smarty->assign($ssb_URD);
}
*/

// Setting this value to 'FALSE' is recommended only for SHARED BLOG INSTALLATIONS. This enforces all shared blogs with a common
// codebase to only allow upgrading, no bypassing and thus causing instabilities.
// This variable can also be set as $serendipity['UpgraderShowAbort'] inside serendipity_config_local.inc.php to prevent
// your setting being changed when updating serendipity in first place.
$showAbort  = (isset($serendipity['UpgraderShowAbort']) ? $serendipity['UpgraderShowAbort'] : true);
$data['showAbort'] = $showAbort;

$abortLoc   = $serendipity['serendipityHTTPPath'] . 'serendipity_admin.php?serendipity[action]=ignore';
$upgradeLoc = $serendipity['serendipityHTTPPath'] . 'serendipity_admin.php?serendipity[action]=upgrade';
$data['abortLoc']   = $abortLoc;
$data['upgradeLoc'] = $upgradeLoc;

/* Functions which needs to be run if installed version is equal or lower */
$tasks = array(
            array(  'version'   => '0.5.1',
                    'function'  => 'serendipity_syncThumbs',
                    'title'     => 'Image Sync',
                    'desc'      => 'Version 0.5.1 introduces image sync with the database'. "\n" .
                                   'With your permission I would like to perform the image sync'),

            array(  'version'   => '0.6.5',
                    'function'  => 'serendipity_rebuildCategoryTree',
                    'title'     => 'Nested subcategories, post to multiple categories',
                    'desc'      => 'This update will update the categories table of your database and update the relations from entries to categories.'. "\n" .
                                   'This is a possibly dangerous task to perform, so <strong style="color: red">make sure you have a backup of your database!</strong>'),

            array(  'version'   => '0.6.8',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'Changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.6.10',
                    'functon'   => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'Changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.6.12',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'Changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.8-alpha3',
                    'function'  => 'serendipity_removeFiles',
                    'arguments' => array($obsolete_files),
                    'title'     => 'Removal of obsolete files',
                    'desc'      => '<p>The directory structure has been reworked. The following files will be moved to a folder called "backup". If you made manual changes to those files, be sure to read the file docs/CHANGED_FILES to re-implement your changes.</p><pre>' . implode(', ', $obsolete_files) . '</pre>'),

            array(  'version'   => '0.8-alpha4',
                    'function'  => 'serendipity_removeFiles',
                    'arguments' => array(array('serendipity_entries.php')),
                    'title'     => 'Removal of serendipity_entries.php',
                    'desc'      => 'In order to implement the new administration, we have to remove the leftovers'),

            array(  'version'   => '0.8-alpha4',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'In order to implement the new administration, changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.8-alpha7',
                    'function'  => 'serendipity_removeObsoleteVars',
                    'title'     => 'Removal of obsolete configuration variables',
                    'desc'      => 'Because of the new configuration parsing methods, some database variables are now only stored in serendipity_config_local.inc.php. Those obsolete variables will be removed from the database'),

            array(  'version'   => '0.8-alpha8',
                    'function'  => array('serendipity_plugin_api', 'create_plugin_instance'),
                    'arguments' => array('serendipity_event_browsercompatibility', null, 'event'),
                    'title'     => 'Plugin for Browser Compatibility',
                    'desc'      => 'Includes some CSS-behaviours and other functions to maximize browser compatibility'),

            array(  'version'   => '0.8-alpha9',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'In order to implement author views, changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.8-alpha11',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'In order to implement URL rewrite improvement, changes were made to the .htaccess file, you need to regenerate it'),

            array(  'version'   => '0.8-alpha12',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file "entries.tpl" has changed.',
                    'desc'      => 'Authors can now have longer real names instead of only their loginnames. Those new fields need to be displayed in your Template, if you manually created one. Following variables were changes:
                                   <b>{$entry.username}</b> =&gt; <b>{$entry.author}</b>
                                   <b>{$entry.link_username}</b> =&gt; <b>{$entry.link_author}</b>
                                   Those variables have been replaced in all bundled templates and those in our additional_themes repository.
                                   ' . serendipity_upgraderResultDiagnose(S9Y_U_WARNING, 'Manual user interaction is required! This can NOT be done automatically!')),

            array(  'version'   => '0.8-beta3',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('markup_column_names'),
                    'title'     => 'Configuration options of markup plugins',
                    'desc'      => '<p>Because of the latest multilingual improvements in Serendipity, the database key names for certain configuration directives only found in markup plugins need to be renamed.</p>'
                                 . '<p>This will be automatically handled by Serendipity for all internally bundled and external plugins. If you are using the external plugins "GeShi" and "Markdown", please make sure you will upgrade to their latest versions!</p>'
                                 . '<p>We also advise that you check the plugin configuration of all your markup plugins (like emoticate, nl2br, s9ymarkup, bbcode) and see if the settings you made are all properly migrated.</p>'),

            array(  'version'   => '0.8-beta5',
                    'function'  => 'serendipity_smarty_purge',
                    'title'     => 'Clear Smarty compiled templates',
                    'desc'      => 'Smarty has been upgraded to its latest stable version, and we therefore need to purge all compiled templates and cache'),

            array(  'version'   => '0.9-alpha2',
                    'function'  => 'serendipity_buildPermalinks',
                    'title'     => 'Build permalink patterns',
                    'desc'      => 'This version introduces user-configurable Permalinks and needs to pre-cache the list of all permalinks to be later able to fetch the corresponding entries for a permalink.'),

            array(  'version'   => '0.9-alpha3',
                    'function'  => 'serendipity_addDefaultGroups',
                    'title'     => 'Introduce author groups',
                    'desc'      => 'This version introduces customizable user groups. Your existing users will be migrated into the new default groups.'),

            array(  'version'   => '1.7-rc2',
                    'type'      => 'PLUGIN_NOTICE',
                    'title'     => '<b>PLUGIN NOTICE:</b> Due to PHP 5.2+\'s raised error reporting, every Serendipity event plugin needs to conform to the core plugin API method signature.',
                    'desc'      => '<p>All internal and spartacus plugins have been updated to reflect this change. The most important signatures are:</p>'
                                 . '<p><strong>function uninstall(&$propbag)</strong><br />'
                                 . '<strong>function event_hook($event, &$bag, &$eventData, $addData = null)</strong></p>'
                                 . '<p>Older plugins specifically did not always include the <strong>$addData</strong> signature. Make sure this exists.
                                   If after installation you get uncircumventable errors, you can make sure to set <strong>$serendipity[\'production\'] = true;</strong> in your <strong>serendipity_config_local.inc.php</strong> file. This should lower error reporting to a way that will not interfere with incompatible problem. But this is no solution in the long run, you need to update your plugins.
                                   Also, the serendipity_event_browsercompatibility plugin has been removed, because it\'s functionality was no longer required. You should uninstall that plugin if you are currently using it.</p>'),

            array(  'version'   => '1.7-rc2',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file "entries.tpl" needs a specific assignment',
                    'desc'      => 'To transport the $entry variable to sub-templates like comments.tpl and trackbacks.tpl.
                                   All internal and spartacus templates have been updated, so make sure you are using a recent version of your blog\'s template.
                                   If you have your own custom template, be sure within your {foreach from=$dategroup.entries item="entry"} loop has this line after it:
                                   <strong>{assign var="entry" value=$entry scope="parent"}</strong>'),

            array(  'version'   => '1.7.1',
                    'function'  => 'serendipity_copyBaseURL',
                    'title'     => 'Copy baseURL',
                    'desc'      => 'The baseURL option was moved to the defaultBaseURL-Option in the backend-configuration. To reflect that change in the database and to prevent future bugs, baseURL should copied to defaultBaseURL if that options is not set already.'),

            array(  'version'   => '1.7.1',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The Bulletproof template config has changed, to avoid a backend template view conflict with the "categorytemplates" plugin.',
                    'desc'      => 'Please check any used <strong>copy</strong> of an old BP template config.inc.php file, in the colorset if(...) conditionals at around line 29 in config.inc.php, to be the same as in the origin bulletproof.'),

            array(  'version'   => '1.7.1',
                    'function'  => 'serendipity_killPlugin',
                    'arguments' => array('serendipity_event_browsercompatibility'),
                    'title'     => 'Remove obsolete plugin',
                    'desc'      => 'The "browsercompatibility" plugin is no longer supported (and no longer required with recent browsers), so it will be automatically uninstalled.'),

            array(  'version'   => '2.0-alpha2',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array($serendipity['serendipityPath'] . 'bundled-libs/Smarty', $dead_smarty_files, array('internals'), true),
                    'title'     => 'Removal of obsolete and dead Smarty 2.6.x files',
                    'desc'      => 'Smarty 3.x brought a new file structure. The following dead files will be removed from "bundled-libs/Smarty/libs".<br /><pre>' . implode(', ', $dead_smarty_files) . '</pre>'),

            array(  'version'   => '2.0-alpha3',
                    'function'  => 'serendipity_upgrader_rename_plugins',
                    'title'     => 'Move internal plugins to "normal" plugin directory structure.',
                    'desc'      => 'A list of internal plugins that previously lived in include/plugin_internal.inc.php were moved into the proper plugins/ subdirectory structure. This task will migrate any possible references to such plugins to the new format.'),

            array(  'version'   => '2.0-alpha4',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array($serendipity['serendipityPath'] . 'htmlarea', $dead_htmlarea_files, array('internals'), true),
                    'title'     => 'Removal of obsolete and dead htmlarea files',
                    'desc'      => 'Serendipity 2.0 replaces old WYSIWYG-Editors in htmlarea directory with CKEDITOR. The following dead files will be removed from "/htmlarea".<br /><pre>' . implode(', ', $dead_htmlarea_files) . '</pre>'),

            array(  'version'   => '2.0-alpha4',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_htmlarea_dirs),
                    'title'     => 'Removal of obsolete and dead htmlarea directories',
                    'desc'      => 'Serendipity 2.0 replaces old WYSIWYG-Editors in htmlarea directory with CKEDITOR. The following dead directories will be completely removed from "/htmlarea".<br /><pre>' . implode(', ', $dead_htmlarea_dirs) . '</pre>'),

            array(  'version'   => '2.0-beta3',
                    'function'  => 'serendipity_upgrader_move_syndication_config',
                    'title'     => 'Export syndication plugin options',
                    'desc'      => 'Serendipity 2.0 moved the more generic feed option from the syndication plugin into the core. They will be set equivalent to their old configuration.'),

            array(  'version'   => '2.0-beta5',
                    'function'  => 'serendipity_killPlugin',
                    'arguments' => array('serendipity_event_autosave'),
                    'title'     => 'Remove autosave plugin',
                    'desc'      => 'Serendipity 2.0 includes autosave functionality, and the autosave plugin collides with new functionality. It has to be removed.'),

            array(  'version'   => '2.0-beta5',
                    'function'  => 'serendipity_killPlugin',
                    'arguments' => array('serendipity_event_dashboard'),
                    'title'     => 'Remove dashboard plugin',
                    'desc'      => 'Serendipity 2.0 includes a dashboard in the admin theme. The separate plugin for 1.x has to be removed.'),

            array(  'version'   => '2.0-beta6',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update of .htaccess file',
                    'desc'      => 'Changes were made to the .htaccess file to allow for new patterns, it will be recreated. If you manually modified the file, make sure your modification are in place afterwards.'),

            array(  'version'   => '2.0.2',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_200, array('internals'), true),
                    'title'     => 'Removal of obsolete and still resting files in 2.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_200) . '</pre>'),

            array(  'version'   => '2.0.2',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_200),
                    'title'     => 'Removal of obsolete and dead directories',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_200) . '</pre>'),

            array(  'version'   => '2.0.2',
                    'function'  => 'serendipity_upgrader_rewriteFeedIcon',
                    'title'     => 'Rewrite path of big feedicon',
                    'desc'      => 'Rewrite path of the big feedicon to not include the template path, since that path is not automatically detected'),

            array(  'version'   => '2.1.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_202, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.0.2',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_202) . '</pre>'),

            array(  'version'   => '2.1.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_202),
                    'title'     => 'Removal of obsolete and dead directories',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_202) . '</pre>'),

            array(  'version'   => '2.1.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> Check the Styx 2.1 changeLog doc/NEWS file for theme file changes',
                    'desc'      => 'Serendipity Styx 2.1.0 fixes an issue creating the entry ID, in <em>"preview.tpl"</em>, caused by a wrong javascript execution.
                                    Please read the docs/NEWS file to add this manually to your custom/unsupported/copy theme.'),

            array(  'version'   => '2.1.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The "default", "default-php" and "default-xml" themes have been reworked.',
                    'desc'      => 'This had effects to some depending themes ("blue", "idea" and "default-rtl") delivered by core.
                                    Spartacus themes may be effected too. Also the general theme fallback stack has changed a little,
                                    regarding images and some special files. Read the Styx changeLog NEWS file carefully for more detailed information.
                                    Truly check if your theme is touched by this all.'),

            array(  'version'   => '2.1.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file <em>"entries.tpl"</em> needs a specific assignment',
                    'desc'      => 'to transport the $entry variable to "sub"-templates like comments.tpl and trackbacks.tpl.
                                   Due to scoping changes in recent Smarty versions we were in need to touch this again.
                                   All release shipped templates files have been changed already, so make sure you are using a recent version of your blog\'s theme template.
                                   If using a custom template, make sure the {foreach from=$dategroup.entries item="entry"} loop has this line after it:
                                   <strong>{assign var="entry" value=$entry scope="root"}</strong>, which "scope" had to change to "root" because of the mentioned class and the Smarty upgrades.'),

            array(  'version'   => '2.1.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file <em>"comments.tpl"</em> and <em>"comments_by_authors.tpl"</em>',
                    'desc'      => 'now use some slightly changed variables, to check and set the "serendipity_comment_author_self" class. Please make sure to check both your files, if have in your custom theme.'),

            array(  'version'   => '2.1.0',
                    'function'  => 'serendipity_upgrader_rename_plugins',
                    'title'     => 'Reset internal plugin names to a current format.',
                    'desc'      => 'Queue the database to rename internal used plugins. This task will migrate any possible references to such plugins to the new format.'),

            array(  'version'   => '2.1.0',
                    'function'  => 'serendipity_cleanUpDirectories_SPL',
                    'arguments' => array($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE),
                    'title'     => 'Removal of empty directories in templates_c',
                    'desc'      => 'Purges empty Smarty (and other) directory leftovers.'),

            array(  'version'   => '2.1.0',
                    'function'  => 'serendipity_cleanUpOldCompilerFiles_SPL',
                    'arguments' => array($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE),
                    'title'     => 'Removal of possible old Smarty2 compiler files leftovers in the root of templates_c',
                    'desc'      => ''),

            array(  'version'   => '2.2.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> Due to changes to the Cookie LOGIN kept password strength generation with Styx 2.2.0 under PHP 7+,',
                    'desc'      => 'you shall <b>NOT</b> close your browser after the update, your current checkup and/or maintenance session, without fully terminating your current LOGIN session per LOGOUT. Else you\'ll get an error with the automated login on LOGIN. This only is a need when on PHP 7+ and only ONCE! In the case of updating your system to PHP 7 later on, please remember to terminate your Cookie kept login data for the switch once too, per LOGOUT."'),

            array(  'version'   => '2.2.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file <em>"entries.tpl"</em> now uses a slightly changed variable check,',
                    'desc'      => 'to evaluate if a 404 error page was assigned before announcing the possible "NO ENTRIES TO PRINT" constant error. Please make sure to check your own / or copy theme files to get the best results. Change "{if NOT $plugin_clean_page}" to "{if NOT $plugin_clean_page AND $view != \'404\'}."'),

            array(  'version'   => '2.2.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file <em>"plugin_staticpage.tpl"</em> and its siblings were changed',
                    'desc'      => 'regarding separator spaces in the breadcrumb navigation. Keep your own / or copy theme files in touch.'),

            array(  'version'   => '2.2.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_220, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.2.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_220) . '</pre>'),

            array(  'version'   => '2.2.0',
                    'type'      => 'PLUGIN_NOTICE',
                    'title'     => '<b>PLUGIN_NOTICE:</b> The Spartacus plugin mirror <em>"Netmirror"</em> server is <b>not</b> the default mirror any more.',
                    'desc'      => 'The Spartacus mirror <em>"Netmirror"</em> has been down for a while. Please use the GitHub or the S9y server for plugin and theme locations. This does not hit Styx by default, since Styx uses a custom plugin repository.'),

            array(  'version'   => '2.2.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> The template file <em>"entries.tpl"</em> was changed for the trackback URI constant.',
                    'desc'      => 'Keep your own / or copy theme files in touch. See all themes with date: 2017-06-21 for examples.<br>Serendipity also introduces a new trackback detection alternative to RDF, by adding a "rel=trackback" element to the "index.tpl" header. The standard theme 2k11 was already changed to use it. You may want to spread and test this any further.'),

            array(  'version'   => '2.3-beta1',
                    'function'  => 'serendipity_installFiles',
                    'title'     => 'Update .htaccess file',
                    'desc'      => 'Adds a new "documentation.*.html" rewrite rule exception to allow calling plugin documentation URLs.'),

            array(  'version'   => '2.4.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_230hta, array('internals'), true),
                    'title'     => 'Removal of special plugin .htaccess files since Styx v.2.3-beta1.',
                    'desc'      => 'As long as documentary .htaccess rewrite files were available via Spartacus, this upgrade task had to run on every system upgrade. Probably this is the last time.<details id="details_htaccesstask240" class="plugin_data"><summary role="button" aria-expanded="false">open details</summary><div>The following files will be removed from your system if available.<pre>' . implode(', ', $dead_files_230hta) . '</pre></div></details>'),

            array(  'version'   => '2.4.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_240, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.4.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_240) . '</pre>'),

            array(  'version'   => '2.4.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_240),
                    'title'     => 'Removal of obsolete and dead directories',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_240) . '</pre>'),

            array(  'version'   => '2.4.0',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('moved_to_spartacus'),
                    'title'     => 'Fix Update version of certain plugins',
                    'desc'      => 'This fixes some upgrade_version values for core to Spartacus (additional_plugins) moved plugins, which were not correctly synced in the pluginlist for plugin update tasks.<pre><strong>For clarification</strong><br>This database conversion task pulls all plugins into the update cycle which take place as REAL dir/file occurrences in the plugin path ("/plugins"), regardless of whether they are installed for the system set active or hidden!<br>This is necessary to effectively use the database plugin list as a real Spartacus image.<br>So don\'t be surprised if the next time you perform a plugin update search, you will have plugins available that you haven\'t installed yourself.<br>They are only updated, not installed!</pre>'),

            array(  'version'   => '2.5-beta1',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> login COOKIE data encryption changed for PHP 7.1.3+',
                    'desc'      => 'Due to changes to the (optional) keep password login COOKIE crypt generation with Styx 2.5.0-alpha2 with PHP 7.1.3+, you shall <b>NOT</b> close your browser after the update, your current checkup and/or maintenance session, <b>without</b> fully terminating your current LOGIN session per <b>LOGOUT</b>. Else you\'ll get an error with the automated login on LOGIN and are stuck. This only is a need when upgrading PHP 7.1.3+ versions and only ONCE for this current beta upgrade! If you upgrade to a later Styx version (like the 2.5.0 release), you don\'t need to care.'),

            array(  'version'   => '2.5-beta1',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> All release themes were changed',
                    'desc'      => 'regarding Serendipity message colorizing for consistency. Please adapt this selector change to your copy or Spartacus pulled themes. Read the ChangeLog for more.'),

            array(  'version'   => '2.5.0',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('moved_to_spartacus'),
                    'title'     => 'Spartacus Plugin Synchronizer',
                    'desc'      => '...an "On-Release" version-check synchronizer task.'),

            array(  'version'   => '2.5.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_250, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.5.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_250) . '</pre>'),

            array(  'version'   => '2.5.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_250),
                    'title'     => 'Removal of obsolete and dead directories',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_250) . '</pre>'),

            array(  'version'   => '2.5.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> More themes were changed',
                    'desc'      => 'for webfonts, calendar, typos, display of post comment owner and trackbacks. Please adapt these changes to your template copy. Read the ChangeLog for more.'),

            array(  'version'   => '2.6-beta1',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_260, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.6.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_260) . '</pre>'),

            array(  'version'   => '2.6-beta1',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('spartacus_custom_reset'),
                    'title'     => 'Spartacus (Developer) Plugin Update Synchronizer',
                    'desc'      => 'The custom Styx mirror location was removed in favour of the official gitHub repository location. Themes are still installed by the s9y origin gitHub repository location. The custom mirror is now either empty or set to "none", or points to your custom repository location for themes and plugins. Please check your custom mirror option after upgrade to be set empty for Styx.'),

            array(  'version'   => '2.6-beta1',
                    'type'      => 'HIDDEN',
                    'title'     => 'Hidden',
                    'desc'      => 'added temporary styles for upgrade.<style> .upgrader_tasks dt b { font-weight: bolder; color: #3e5f81; border: 1px solid; padding: 2px 5px; background-color: #EEE; } .upgrader_tasks dd { display: inline-block; background: #fcf8e3; border: 1px solid #fbeed5; color: #c09853; padding: 2px 5px; }</style>'),

            array(  'version'   => '2.6-beta1',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> All release themes were changed',
                    'desc'      => 'for logic, initialization checks, etc. Please adapt these changes to your template copies. Read the ChangeLog for more. Styx recommends to start you copy theme from scratch, since not all of them were explicitly mentioned!'),

            array(  'version'   => '2.6-beta2',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('wrong_upgrade_version'),
                    'title'     => 'Spartacus (Developer) Plugin Update Synchronizer',
                    'desc'      => '<b>ZARATHUSTRA</b> - [Z]ero [A]ccess [R]epository [A]nd [T]emporary/able [H]ealth [U]pgrade [S]ynchronizer [T]ask [R]egulation [A]ctor.'),

            array(  'version'   => '2.6-beta2',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_260),
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> Styx moved the Smarty Backend templates to the "default" theme',
                    'desc'      => 'If you have any custom or developer files in the "templates/2k11/admin" directory, make a backup copy before proceeding. Do not try to keep this directory, since further file development already went to the new location! The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_260) . '</pre>'),

            array(  'version'   => '2.6.2',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> All core delivered themes were changed',
                    'desc'      => 'fixing a regression in the "entries.tpl" file for the entries list pagination condition. PLEASE adapt to your copy themes <pre>{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}</pre>'),

            array(  'version'   => '2.6.4',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> All core delivered themes were changed',
                    'desc'      => 'for comment owner selectors and the new (paged comments) "pcomments.tpl" file . PLEASE check your copy themes.'),

            array(  'version'   => '2.7.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_270, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.2.7.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_270) . '</pre>'),

            array(  'version'   => '2.7.0',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('wrong_upgrade_version'),
                    'title'     => 'Spartacus (Developer) Plugin Update (Database) Synchronizer',
                    'desc'      => '<b>ZARATHUSTRA</b> - [Z]ero [A]ccess [R]epository [A]nd [T]emporary/able [H]ealth [U]pgrade [S]ynchronizer [T]ask [R]egulation [A]ctor.'),

            array(  'version'   => '2.8.0',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> All core delivered themes were changed.',
                    'desc'      => 'Please read the more detailed ChangeLog file via the backends "Maintenance" page. And PLEASE check your copy themes.'),

            array(  'version'   => '2.8.0',
                    'type'      => 'CONFIGURATION_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE:</b> Missing (default) media property fields on elder systems',
                    'desc'      => 'cause some media backend forms to not display all available items. Please READ about this "issue" later on in the ChangeLog file via the backends "Maintenance" page and re-check your settings.'),

            array(  'version'   => '2.8.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> Your current <b>PHP</b> ' . PHP_VERSION . ' version is: ' . ((version_compare(PHP_VERSION, '7.0.0') >= 0) ? 'OK' : 'outdated') . '.',
                    'desc'      => 'The PHP recommended version for Serendipity Styx 2.8.0 is <b>PHP 7.3</b>.x. This Styx Series <b>2</b> will end here and only get security fixes as minor point releases for a short time. PLEASE NOTE: The <b>next</b> regular Serendipity Styx <b>major</b> upgrade to <b>3.0</b> requires at least <b>PHP 7.2</b> as the minimum.'),

            array(  'version'   => '2.9.2',
                    'type'      => 'ENTRIES_NOTICE',
                    'title'     => '<b>ENTRIES_NOTICE:</b> A bugfix for a special task cased entries timestamp was applied.',
                    'desc'      => 'Please read the more detailed ChangeLog file via the backends "Maintenance" page after having done the upgrade, to catch up, if you probably were hit by this issue.'),

            array(  'version'   => '2.9.2',
                    'type'      => 'CONFIGURATION_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE:</b> An Upgrade configuration needs to be done for upcoming Styx 3.0 upgrades!',
                    'desc'      => 'If you <strong>want</strong> or <strong>have to</strong> use elder than PHP 7.3 versions for a longer time, please read <a href="https://ophian.github.io/2019/08/19/Serendipity-Styx-2.9.1-released/" target="_blank">this blog entry</a>.<p>To not get in conflict with the upcoming next major 3.0 upgrades, you <strong>have to</strong> set up a <strong>new</strong> Update-RELEASE-file <strong>URL</strong> in your Backend Configuration Panel.<br>Open <strong>Configuration</strong> - <strong>General Settings</strong> - and see the option <strong>Update RELEASE-file URL</strong>. There you add this new URL, pointing to the branch RELEASE file and submit the form:<br><em>https://raw.githubusercontent.com/ophian/styx/styx2.9/docs/RELEASE</em></p><p>Now you will only get future update request notes if a new branch point release, like (next) 2.9.3 is prepared to supply.<br>If you then are ready for upcoming Styx 3.0 Next, you just change it back to the master branch at:<br><em>https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE</em></p>'),

            array(  'version'   => '2.9.3',
                    'type'      => 'CONFIGURATION_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE_REMINDER:</b>',
                    'desc'      => 'As having said before, a <b>manual</b> upgrade configuration may need to be done for upcoming Styx 3.0 upgrades!<br>If you <strong>want</strong> or <strong>have to</strong> use elder versions than PHP 7.3 for a longer time, <strong>or</strong> you don\'t have a minimum server system like Debian 10 (buster) for other relevant essentials like openSSL, please read <a href="https://ophian.github.io/2019/08/19/Serendipity-Styx-2.9.1-released/" target="_blank">this blog entry</a>.<p>To not get in conflict with the upcoming next major 3.0 upgrades, you <strong>have to</strong> set up a <strong>new</strong> Update-RELEASE-file <strong>URL</strong> in your Backend Configuration Panel.<br>Open <strong>Configuration</strong> - <strong>General Settings</strong> - and see the option <strong>Update RELEASE-file URL</strong>. There you add this new URL, pointing to the branch RELEASE file and submit the form:<br><em>https://raw.githubusercontent.com/ophian/styx/styx2.9/docs/RELEASE</em></p><p>Now you will only get future update request notes if a new branch point release, like (next) 2.9.4 is prepared to supply.<br>If you then are ready for upcoming Styx 3.0 Next, you just change it back to the master branch at:<br><em>https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE</em></p>'),

);

// TODO: Do something meaningful with 'type', since having key type and the bold title (type) is redundant!

/* Fetch SQL files which needs to be run */
$dir      = opendir(S9Y_INCLUDE_PATH . 'sql/');
$tmpfiles = array();
while (($file = readdir($dir)) !== false ) {
    if (preg_match('@db_update_(.*)_(.*)_(.*).sql@', $file, $res)) {
        list(, $verFrom, $verTo, $dbType) = $res;
        if (version_compare($verFrom, $serendipity['versionInstalled']) >= 0) {
            $tmpFiles[$verFrom][$dbType] = $file;
        }
    }
}

$sqlfiles = array();
if (is_array($tmpFiles)) {
    foreach($tmpFiles AS $version => $db) {
        if (array_key_exists($serendipity['dbType'], $db) === false ) {
            $sqlfiles[$version] = $db['mysql'];
        } else {
            $sqlfiles[$version] = $db[$serendipity['dbType']];
        }
    }
}

@uksort($sqlfiles, 'strnatcasecmp');

if ($serendipity['GET']['action'] == 'ignore') {
    /* Todo: Don't know what to put here? */

} elseif ($serendipity['GET']['action'] == 'upgrade') {
    serendipity_smarty_purge();

    $errors = array();

    /* Install SQL update files */
    foreach($sqlfiles AS $sqlfile) {
        $sql = file_get_contents(S9Y_INCLUDE_PATH .'sql/'. $sqlfile);
        $sql = str_replace('{PREFIX}', $serendipity['dbPrefix'], $sql);
        preg_match_all("@(.*);@iUs", $sql, $res);
        foreach($res[0] AS $sql) {
            $r = serendipity_db_schema_import($sql);
            if (is_string($r)) {
                $errors[] = trim($r);
            }
        }
    }

    /* Call functions */
    $data['call_tasks'] = array();
    foreach($tasks AS $task) {
        if (!empty($task['function']) && version_compare($serendipity['versionInstalled'], $task['version'], '<') && version_compare($task['version'], $serendipity['version'], '<=')) {
            if (is_callable($task['function'])) {
                $data['is_callable_task'] = true;
                $data['call_tasks'][] = sprintf('Calling %s ...<br />', (is_array($task['function']) ? $task['function'][0] . '::'. $task['function'][1] : $task['function']));

                if (empty($task['arguments'])) {
                    call_user_func($task['function']);
                } else {
                    call_user_func_array($task['function'], $task['arguments']);
                }
            } else {
                $errors[] = 'Unable to call '. $task['function'];
            }
        }
    }

    if (sizeof($errors)) {
        $data['errors'] = $errors;
    }

    /* I don't care what you told me, I will always nuke Smarty cache */
    serendipity_smarty_purge();

}

$data['s9y_version']           = $serendipity['version'];
$data['s9y_version_installed'] = $serendipity['versionInstalled'];

if (($showAbort && $serendipity['GET']['action'] == 'ignore') || $serendipity['GET']['action'] == 'upgrade') {
    $privateVariables = array();
    if (isset($serendipity['UpgraderShowAbort'])) {
        $privateVariables['UpgraderShowAbort'] = $serendipity['UpgraderShowAbort'];
    }
    // on upgrade, check if maintenance mode is set
    if (isset($serendipity['maintenance'])) {
        $privateVariables['maintenance'] = $serendipity['maintenance'];
    }

    $r = serendipity_updateLocalConfig(
            $serendipity['dbName'],
            $serendipity['dbPrefix'],
            $serendipity['dbHost'],
            $serendipity['dbUser'],
            $serendipity['dbPass'],
            $serendipity['dbType'],
            $serendipity['dbPersistent'],
            $privateVariables
    );

    if ($serendipity['GET']['action'] == 'ignore') {
        $data['ignore'] = true;
    } elseif ($serendipity['GET']['action'] == 'upgrade') {
        // void
    }
    $data['return_here'] = true;
    $data['print_UPGRADER_RETURN_HERE'] = sprintf(SERENDIPITY_UPGRADER_RETURN_HERE, '<a href="'. $serendipity['serendipityHTTPPath'] .'">', '</a>');
    if (isset($serendipity['COOKIE']['author_information']) && serendipity_checkPermission('adminUsers')) {
        $data['print_UPGRADER_RETURN_HERE'] .= ' / <a href="' . $serendipity['baseURL'] . 'serendipity_admin.php">' . SERENDIPITY_ADMIN_SUITE . '</a>';
    } else {
        $_SESSION['serendipityAuthedUser'] = false;
        @session_destroy();
    }
} else {
    $data['upgrade'] = true;
    $data['result_diagnose'] = sprintf(ERRORS_ARE_DISPLAYED_IN, serendipity_upgraderResultDiagnose(S9Y_U_ERROR, RED), serendipity_upgraderResultDiagnose(S9Y_U_WARNING, YELLOW), serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, GREEN));

    $errorCount = 0;
    $showWritableNote = false;
    $basedir = $serendipity['serendipityPath'];
    $data['basedir'] = $basedir;

    $data['upgraderResultDiagnose1'] = array();
    if (is_readable($basedir . 'checksums.inc.php')) {
        $data['checksums'] = true;
        $badsums = serendipity_verifyFTPChecksums();

        if (empty($badsums)) {
            $data['upgraderResultDiagnose1'][] = serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, CHECKSUMS_PASS);
        } else {
            foreach($badsums AS $rfile => $sum) {
                $data['upgraderResultDiagnose1'][] = serendipity_upgraderResultDiagnose(S9Y_U_WARNING, sprintf(CHECKSUM_FAILED, $rfile));
            }
        }
    } // End if checksums

    $data['upgraderResultDiagnose2'] = array();
    if (is_writable($basedir)) {
        $data['upgraderResultDiagnose2'][] = serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, WRITABLE);
    } else {
        $showWritableNote = false;
        #Figure out if we're set up a little more securely
        #PATH_SMARTY_COMPILE/
        #uploads/
        #archives/
        #.htaccess
        #serendipity_config_local.inc.php
        # For completeness we could test to make sure the directories
        # really are directories, but that's probably overkill
        foreach(array('archives/', PATH_SMARTY_COMPILE . '/', 'uploads/', '.htaccess', 'serendipity_config_local.inc.php') AS $path) {
            if (!is_writeable($basedir . $path)) {
                $data['upgraderResultDiagnose2'][] = serendipity_upgraderResultDiagnose(S9Y_U_ERROR, NOT_WRITABLE);
                $showWritableNote = true;
                break;
            }
        }

        if (!$showWritableNote) {
            $data['upgraderResultDiagnose2'][] = serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, WRITABLE);
        }
    }

    $data['upgraderResultDiagnose3'] = array();
    if (is_writable($basedir . PATH_SMARTY_COMPILE)) {
         $data['upgraderResultDiagnose3'][] = serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, WRITABLE);
    } else {
         $data['upgraderResultDiagnose3'][] = serendipity_upgraderResultDiagnose(S9Y_U_ERROR, NOT_WRITABLE);
         $showWritableNote = true;
    }

    $data['upgraderResultDiagnose4'] = array();
    if (is_dir($basedir . $serendipity['uploadHTTPPath'])) {
        $data['uploadHTTPPath']   = $serendipity['uploadHTTPPath'];
        $data['isdir_uploadpath'] = true;
        if (is_writable($basedir . $serendipity['uploadHTTPPath'])) {
            $data['upgraderResultDiagnose4'][] = serendipity_upgraderResultDiagnose(S9Y_U_SUCCESS, WRITABLE);
        } else {
            $data['upgraderResultDiagnose4'][] = serendipity_upgraderResultDiagnose(S9Y_U_ERROR, NOT_WRITABLE);
            $showWritableNote = true;
        }
    }

    $data['showWritableNote'] = $showWritableNote;

    $data['errorCount'] = $errorCount;
    if ($errorCount < 1) {
        if (sizeof($sqlfiles) > 0) {
            $data['database_update_types'] = sprintf(SERENDIPITY_UPGRADER_DATABASE_UPDATES, $serendipity['dbType']);
            $data['sqlfiles'] = $sqlfiles;
        }

        $taskCount = 0;
        $data['tasks'] = array();
        foreach($tasks AS $task) {
            if (version_compare($serendipity['versionInstalled'], $task['version'], '<') && version_compare($task['version'], $serendipity['version'], '<='))  {
                $data['tasks'][] = $task;
                $taskCount++;
            }
        }

        $data['taskCount'] = $taskCount;
    }
}

$data['get']['action'] = $serendipity['GET']['action']; // don't trust {$smarty.get.vars} if not proofed, as we often change GET vars via serendipity['GET'] by runtime
$data['templatePath']  = $serendipity['templatePath'];

if (!is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

/* see on top */
#$serendipity['smarty']->registerPlugin('function', 'serendipity_upgraderResultDiagnose', 'serendipity_smarty_backend_upgraderResultDiagnose');

echo serendipity_smarty_showTemplate('admin/upgrader.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
