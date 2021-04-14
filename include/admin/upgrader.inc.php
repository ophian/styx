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
$showAbort  = $serendipity['UpgraderShowAbort'] ?? true;
$data['showAbort'] = $showAbort;

$abortLoc   = $serendipity['serendipityHTTPPath'] . 'serendipity_admin.php?serendipity[action]=ignore';
$upgradeLoc = $serendipity['serendipityHTTPPath'] . 'serendipity_admin.php?serendipity[action]=upgrade';
$data['abortLoc']   = $abortLoc;
$data['upgradeLoc'] = $upgradeLoc;

/* Functions which needs to be run if installed version is equal or lower */
$tasks = array(
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
                    'desc'      => 'If you <strong>want</strong> or <strong>have to</strong> use elder than PHP 7.3 versions for a longer time, please read <a href="https://ophian.github.io/2019/08/19/Serendipity-Styx-2.9.1-released/" rel="noopener" target="_blank">this blog entry</a>.<p>To not get in conflict with the upcoming next major 3.0 upgrades, you <strong>have to</strong> set up a <strong>new</strong> Update-RELEASE-file <strong>URL</strong> in your Backend Configuration Panel.<br>Open <strong>Configuration</strong> - <strong>General Settings</strong> - and see the option <strong>Update RELEASE-file URL</strong>. There you add this new URL, pointing to the branch RELEASE file and submit the form:<br><em>https://raw.githubusercontent.com/ophian/styx/styx2.9/docs/RELEASE</em></p><p>Now you will only get future update request notes if a new branch point release, like (next) 2.9.3 is prepared to supply.<br>If you then are ready for upcoming Styx 3.0 Next, you just change it back to the master branch at:<br><em>https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE</em></p>'),

            array(  'version'   => '2.9.3',
                    'type'      => 'CONFIGURATION_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE_REMINDER:</b>',
                    'desc'      => 'As having said before, a <b>manual</b> upgrade configuration may need to be done for upcoming Styx 3.0 upgrades!<br>If you <strong>want</strong> or <strong>have to</strong> use elder versions than PHP 7.3 for a longer time, <strong>or</strong> you don\'t have a minimum server system like Debian 10 (buster) for other relevant essentials like openSSL, please read <a href="https://ophian.github.io/2019/08/19/Serendipity-Styx-2.9.1-released/" rel="noopener" target="_blank">this blog entry</a>.<p>To not get in conflict with the upcoming next major 3.0 upgrades, you <strong>have to</strong> set up a <strong>new</strong> Update-RELEASE-file <strong>URL</strong> in your Backend Configuration Panel.<br>Open <strong>Configuration</strong> - <strong>General Settings</strong> - and see the option <strong>Update RELEASE-file URL</strong>. There you add this new URL, pointing to the branch RELEASE file and submit the form:<br><em>https://raw.githubusercontent.com/ophian/styx/styx2.9/docs/RELEASE</em></p><p>Now you will only get future update request notes if a new branch point release, like (next) 2.9.4 is prepared to supply.<br>If you then are ready for upcoming Styx 3.0 Next, you just change it back to the master branch at:<br><em>https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE</em></p>'),

            array(  'version'   => '3.0-alpha2',
                    'function'  => 'serendipity_checkWebPSupport',
                    'arguments' => array(true),
                    'title'     => 'Check Image Libraries for WebP file support',
                    'desc'      => 'Sets a global variable if the PHP build-in GD-library or the used ImageMagick version were build with WebP file support. If so, and you already had it set by hand, please remove your temporary set $serendipity[\'useWebPFormat\'] variable in your serendipity_config_local.inc.php file.'),

            array(  'version'   => '3.0-alpha2',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> Your current <b>PHP</b> ' . PHP_VERSION . ' version is: ' . ((version_compare(PHP_VERSION, '7.3.0') >= 0) ? 'OK' : 'outdated') . '.',
                    'desc'      => 'The recommended and required PHP version for Serendipity Styx 3.0-alpha3+ will probably be <b>PHP 7.3</b>.x. Make sure to upgrade until then.'),

            array(  'version'   => '3.0-alpha3',
                    'type'      => 'CONFIGURATION_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE:</b> "Build WebP Image Variations" Maintenance MediaLibrary Synchronizer task available',
                    'desc'      => 'Maybe you haven\'t_noticed yet: In the Maintenance MediaLibrary Synchronizer task box you are able to automatically run a WebP-Variation format upgrade task for your existing MediaLibrary images once. After that you\'ll have the counterpart function available, that lets you delete all WebP-Variations. And so forth.'),

            array(  'version'   => '3.0-alpha3',
                    'type'      => 'TEMPLATE_NOTICE',
                    'title'     => '<b>TEMPLATE_NOTICE:</b> New core delivered themes were added',
                    'desc'      => 'Sliver, a responsive variant of Bulletproof, "The Big Ease" Dude and Pure (2019) were newly added to core themes. The Pure (2019) theme is the new Styx Standard theme.'),

            array(  'version'   => '3.0-alpha3',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_300),
                    'title'     => '<b>IMPORTANT_CORE_NOTICE:</b> Styx removed the PHP5 random_bytes polyfill, which is now part of PHP7.',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_300) . '</pre>'),

            array(  'version'   => '3.0-alpha3',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>CONFIGURATION_NOTICE:</b> Serendipity Styx is complete! Now having its own "additional_templates" repository, available via Spartacus.',
                    'desc'      => 'All available themes were upgraded to HTML5, were fixed, improved and checked, and at least basically made working with Serendipity Styx Revisions.
                    <p>Some, which had been worthy, because of well done structured design or laid groundwork, even got more than just a simple HTML5/CSS upgrade and are now able to act as references for theme copies, theme-dependency-childs, or easy to change to a custom user theme.</p>
                    Please, <b>carefully</b> read the regarding changelog entry for this matter and for preparations to make this available in Styx.'),

            array(  'version'   => '3.0-alpha3',
                    'function'  => 'serendipity_purgeTemplatesCache',
                    'arguments' => array(true),
                    'title'     => 'Automatic cleanup of "additional_themes" cached preview images',
                    'desc'      => 'Run it, to further be able, to get a fresh build of all relevant images preview files for the themes list. (Recommended!)'),

            array(  'version'   => '3.0-alpha4',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('change_backend_name'),
                    'title'     => 'Change Backend template name',
                    'desc'      => 'Changes and removes the "2styx" backend template for the directory renaming change to "styx". If you have your own tweaks inside, back them up before you proceed!'),

            array(  'version'   => '3.0-beta1',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_300, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.3.0.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_300) . '</pre>'),

            array(  'version'   => '3.0-beta1',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_300),
                    'title'     => 'Styx removed the Zend DB framework.',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_300) . '</pre>'),

            array(  'version'   => '3.0.1',
                    'function'  => 'serendipity_upgrader_move_syndication_config',
                    'title'     => 'Improve old 2014 2.0-beta1 export syndication plugin options for cleanup',
                    'desc'      => 'Serendipity 2.0 moved the more generic feed option from the syndication plugin into the core. They were set equivalent to their old configuration and the old ones will now be removed from the syndication config sets.'),

            array(  'version'   => '3.0.1',
                    'function'  => 'serendipity_cleanupConfigVars',
                    'arguments' => array('eyecandy'),
                    'title'     => 'Automatic database config cleanup for singular removed variables',
                    'desc'      => 'Removing old javascript advanced variable "eyecandy" for/since Serendipity 2.0-beta1.'),

            array(  'version'   => '3.0.1',
                    'function'  => 'serendipity_cleanupConfigVars',
                    'arguments' => array('wysiwygToolbar'),
                    'title'     => 'Automatic database config cleanup for singular removed variables',
                    'desc'      => 'Removing "wysiwygToolbar" variable for the Styx 3.0 upgrade.'),

            array(  'version'   => '3.1.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_310),
                    'title'     => 'Styx moved the Smarty (real) cache directory into archives.',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_310) . '</pre>'),

            array(  'version'   => '3.1.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_310, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.3.1.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_310) . '</pre>'),

            array(  'version'   => '3.2.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_320, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.3.2.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_320) . '</pre>'),

            array(  'version'   => '3.2.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => 'Some fixes for MediaLibrary actions regarding WebP variation images have been applied.',
                    'desc'      => 'Please read the NEWS changelog (via maintenance tool) for these fixes with Serendipity Styx Revisions since 3.0 regarding checks and possible actions for you to do!'),

            array(  'version'   => '3.2.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>THEMES_NOTICE:</b> Lots of smaller bugfix were patched into core themes.',
                    'desc'      => 'Please read the more detailed ChangeLog file via the backends "Maintenance" page after having done the upgrade, to catch up, if you need to to check your COPY-Themes.'),

            array(  'version'   => '3.2.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => 'Due to the mentioned MediaLibrary improvements/fixes regarding WebP variation images, some MediaLibrary related plugins do now raise up to a Styx 3.2 requirement.',
                    'desc'      => 'Please run Spartacus plugin updates again after the upgrade has finished to get important image-plugin improves for Serendipity Styx Revisions since 3.0. To not have a delayed cache Wait-Time, save the Spartacus plugin configuration once before to get a new XML file.'),

            array(  'version'   => '3.3.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_330),
                    'title'     => 'Styx removed some obsolete core themes. "Bulletproof" further-on lives in Spartacus::additional_themes.',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_330) . '</pre>'),

            array(  'version'   => '3.3.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_330, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.3.3.0',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_330) . '</pre>'),

            array(  'version'   => '3.3.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>THEMES_NOTICE:</b> Lots of fixes regarding PHP 8 were patched into core themes.',
                    'desc'      => 'Please read the more detailed ChangeLog file via the backends "Maintenance" page after having done the upgrade, to catch up, if you need to to check your COPY/CHILD-Themes.'),

            array(  'version'   => '3.3.1',
                    'function'  => 'serendipity_fixPlugins',
                    'arguments' => array('cleanup_default_widgets'),
                    'title'     => 'Configuration Update Synchronizer',
                    'desc'      => 'This task removes personal configuration items for 3.3.1 removed old "dashboard" default_widgets.'),

            array(  'version'   => '3.4.0',
                    'type'      => 'IMPORTANT_CORE_NOTICE',
                    'title'     => '<b>PLUGINS_NOTICE:</b> A long time deprecated set addLoadEvent() JavaScript compatibility function initializer got removed from core.',
                    'desc'      => 'Please check personal plugins not use this function init. Use vanilla <code>document.addEventListener("DOMContentLoaded", function() { funcname(); });</code> or jQuery <code>$(document).ready(function() { funcname(); });</code> instead.'),

            array(  'version'   => '3.4.0',
                    'function'  => 'recursive_directory_iterator',
                    'arguments' => array($dead_dirs_340),
                    'title'     => 'Styx removed some themes framework assets. "B46" further-on loads these bootstrap assets from templates/_assets/b4. If you have copy themes with own index.tpl or preview_iframe.tpl files please adapt to use {serendipity_getFile file="js/bootstrap.min.js"} and {serendipity_getFile file="css/bootstrap.min.css"} calls.',
                    'desc'      => 'The following old dead directories will be removed from your system.<br><pre>' . implode(', ', $dead_dirs_340) . '</pre>'),

            array(  'version'   => '3.4.0',
                    'function'  => 'serendipity_removeDeadFiles_SPL',
                    'arguments' => array(substr($serendipity['serendipityPath'], 0, -1), $dead_files_340, array('internals'), true),
                    'title'     => 'Removal of old dead files in v.3.4.0 (see previous bootstrap assets note)',
                    'desc'      => 'The following old dead files will be removed from your system.<br><pre>' . implode(', ', $dead_files_340) . '</pre>'),

);
// TODO: Do something meaningful with 'type', since having key type and the bold title (type) is redundant!

/* Fetch SQL files which needs to be run */
$dir      = opendir(S9Y_INCLUDE_PATH . 'sql/');
$tmpFiles = array();
while (($file = readdir($dir)) !== false ) {
    if (preg_match('@db_update_(.*)_(.*)_(.*).sql@', $file, $res)) {
        list(, $verFrom, $verTo, $dbType) = $res;
        if (version_compare($verFrom, $serendipity['versionInstalled']) >= 0) {
            $tmpFiles[$verFrom][$dbType] = $file;
        }
    }
}

$sqlfiles = array();
if (!empty($tmpFiles)) {
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
                    if (is_array($task['arguments'])) {
                        reset($task['arguments']); // rewind the pointer for a possible extra call
                    }
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
            if (!is_writable($basedir . $path)) {
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
