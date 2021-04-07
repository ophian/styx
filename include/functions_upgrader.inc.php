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

/* A list of old WYSIWYG-Editor lib directories which got obsolete in 2.0 */
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

/* A list of old Serendipity files which were not marked obsolete with 2.0.0 upgrade - now 2.0.2 */
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

/* A list of old lib directories which were not marked obsolete with 2.0.0 upgrade - now 2.0.2 */
$dead_dirs_200 = array(
    $serendipity['serendipityPath'] . 'htmlarea/plugins/ImageManage',
    $serendipity['serendipityPath'] . 'htmlarea/plugins',
    $serendipity['serendipityPath'] . 'bundled-libs/YahooUI/treeview',
    $serendipity['serendipityPath'] . 'bundled-libs/YahooUI'
);

/* A list of old or beta Serendipity files, which were not marked obsolete with the 2.0.2 upgrade, or were removed by 2.1.0 */
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
/*    'templates/default/admin/entries.tpl',*/
    'templates/default/admin/header_spawn.js',
    'templates/default/admin/image_selector.js',
    'templates/default/admin/imgedit.css',
/*    'templates/default/admin/index.tpl',
    'templates/default/admin/media_choose.tpl',*/
    'templates/default/admin/media_imgedit.tpl',
    'templates/default/admin/media_imgedit_done.tpl',
/*    'templates/default/admin/media_items.tpl',
    'templates/default/admin/media_pane.tpl',
    'templates/default/admin/media_properties.tpl',*/
    'templates/default/admin/media_showitem.tpl',
/*    'templates/default/admin/media_upload.tpl',*/
    'templates/default/admin/pluginmanager.css',
/*    'templates/default/admin/style.css',*/
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

/* A list of Serendipity files, which were removed by 2.2.0 */
$dead_files_220 = array(
    'bundled-libs/simplepie/simplepie.inc'
);

/* A list of Serendipity plugin .htaccess files, which are not necessary to keep with the 2.3.0 upgrade any more */
$dead_files_230hta = array(
    'plugins/serendipity_event_aggregator/.htaccess',
    'plugins/serendipity_event_amazonchooser/.htaccess',
    'plugins/serendipity_event_assigncategories/.htaccess',
    'plugins/serendipity_event_autotitle/.htaccess',
    'plugins/serendipity_event_autoupdate/.htaccess',
    'plugins/serendipity_event_backend/.htaccess',
    'plugins/serendipity_event_blogpdf/.htaccess',
    'plugins/serendipity_event_browserid/.htaccess',
    'plugins/serendipity_event_cal/.htaccess',
    'plugins/serendipity_event_categorytemplates/.htaccess',
    'plugins/serendipity_event_ckeditor/.htaccess',
    'plugins/serendipity_event_comics/.htaccess',
    'plugins/serendipity_event_commentedit/.htaccess',
    'plugins/serendipity_event_commentsearch/.htaccess',
    'plugins/serendipity_event_commentspice/.htaccess',
    'plugins/serendipity_event_communityrating/.htaccess',
    'plugins/serendipity_event_contactform/.htaccess',
    'plugins/serendipity_event_cpgselector/.htaccess',
    'plugins/serendipity_event_cronjob/.htaccess',
    'plugins/serendipity_event_customarchive/.htaccess',
    'plugins/serendipity_event_dbclean/.htaccess',
    'plugins/serendipity_event_disqus/.htaccess',
    'plugins/serendipity_event_downloadmanager/.htaccess',
    'plugins/serendipity_event_email_bot_obfuscator/.htaccess',
    'plugins/serendipity_event_emoticonchooser/.htaccess',
    'plugins/serendipity_event_externalauth/.htaccess',
    'plugins/serendipity_event_facebook/.htaccess',
    'plugins/serendipity_event_faq/.htaccess',
    'plugins/serendipity_event_faq/img/.htaccess',
    'plugins/serendipity_event_filter_entries/.htaccess',
    'plugins/serendipity_event_findmore/.htaccess',
    'plugins/serendipity_event_flattr/.htaccess',
    'plugins/serendipity_event_flickr/.htaccess',
    'plugins/serendipity_event_forgotpassword/.htaccess',
    'plugins/serendipity_event_forum/.htaccess',
    'plugins/serendipity_event_forum/img/.htaccess',
    'plugins/serendipity_event_freetag/.htaccess',
    'plugins/serendipity_event_geotag/.htaccess',
    'plugins/serendipity_event_geourl/.htaccess',
    'plugins/serendipity_event_geshi/.htaccess',
    'plugins/serendipity_event_getid3/.htaccess',
    'plugins/serendipity_event_google_analytics/.htaccess',
    'plugins/serendipity_event_google_sitemap/.htaccess',
    'plugins/serendipity_event_gravatar/.htaccess',
    'plugins/serendipity_event_guestbook/.htaccess',
    'plugins/serendipity_event_imageselectorplus/.htaccess',
    'plugins/serendipity_event_includeentry/.htaccess',
    'plugins/serendipity_event_karma/.htaccess',
    'plugins/serendipity_event_lightbox/.htaccess',
    'plugins/serendipity_event_linklist/.htaccess',
    'plugins/serendipity_event_linktoolbar/.htaccess',
    'plugins/serendipity_event_linktrimmer/.htaccess',
    'plugins/serendipity_event_livecomment/.htaccess',
    'plugins/serendipity_event_lsrstopper/.htaccess',
    'plugins/serendipity_event_markdown/.htaccess',
    'plugins/serendipity_event_metadesc/.htaccess',
    'plugins/serendipity_event_microformats/.htaccess',
    'plugins/serendipity_event_mobile_output/.htaccess',
    'plugins/serendipity_event_multilingual/.htaccess',
    'plugins/serendipity_event_mycalendar/.htaccess',
    'plugins/serendipity_event_mymood/.htaccess',
    'plugins/serendipity_event_oembed/.htaccess',
    'plugins/serendipity_event_openid/.htaccess',
    'plugins/serendipity_event_page_nugget/.htaccess',
    'plugins/serendipity_event_podcast/.htaccess',
    'plugins/serendipity_event_popfetcher/.htaccess',
    'plugins/serendipity_event_proxy_realip/.htaccess',
    'plugins/serendipity_event_recaptcha/.htaccess',
    'plugins/serendipity_event_searchhighlight/.htaccess',
    'plugins/serendipity_event_smartymarkup/.htaccess',
    'plugins/serendipity_event_spamblock_bayes/.htaccess',
    'plugins/serendipity_event_staticpage/.htaccess',
    'plugins/serendipity_event_statistics/.htaccess',
    'plugins/serendipity_event_suggest/.htaccess',
    'plugins/serendipity_event_template_editor/.htaccess',
    'plugins/serendipity_event_tinymce/.htaccess',
    'plugins/serendipity_event_todolist/.htaccess',
    'plugins/serendipity_event_trackback/.htaccess',
    'plugins/serendipity_event_wikilinks/.htaccess',
    'plugins/serendipity_event_xmlrpc/.htaccess',
    'plugins/serendipity_event_dpsyntaxhighlighter/.htaccess',
    'plugins/serendipity_plugin_twitter/.htaccess'
);

/* A list of Styx files, which were removed or renamed by 2.4.0 */
$dead_files_240 = array(
    'bundled-libs/Smarty/libs/plugins/shared.mb_wordwrap.php',
/*    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_compile_block_child.php',
    'bundled-libs/Smarty/libs/sysplugins/smarty_internal_compile_block_parent.php',*/
    'sql/dbpre.sql'
);

/* A list of old or removed directories for 2.4.0 */
$dead_dirs_240 = array(
    $serendipity['serendipityPath'] . 'plugins/serendipity_event_cleanspam'
);

/* A list of Styx files, to be removed or renamed by 2.5.0 */
$dead_files_250 = array(
    'bundled-libs/HTTP/Request.php',
    'bundled-libs/NET/CheckIP.php',
    'bundled-libs/NET/Socket.php',
    'bundled-libs/NET/URL.php',
    'templates/2k11/feed_0.91.tpl',
    'templates/2k11/feed_1.0.tpl',
    'templates/2k11/feed_2.0.tpl',
    'templates/2k11/feed_atom0.3.tpl',
    'templates/2k11/feed_atom1.0.tpl',
    'templates/2k11/feed_opml1.0.tpl',
    'templates/2k11/UTF-8/lang_en.inc.php',
    'templates/bootstrap4/UTF-8/lang_en.inc.php',
    'templates/bulletproof/UTF-8/lang_en.inc.php',
    'templates/default/admin/img/readonly.png',
    'templates/next/UTF-8/lang_en.inc.php'
);

/* A list of old or removed directories for 2.5.0 */
$dead_dirs_250 = array(
    $serendipity['serendipityPath'] . 'bundled-libs/HTTP/Request',
    $serendipity['serendipityPath'] . 'plugins/serendipity_event_browsercompatibility'
);

/* A list of Styx files, to be removed or renamed by 2.6.0 */
$dead_files_260 = array(
    'templates_c/.htaccess',
    'templates/2k11/preview_backend_fullsize.jpg',
    'templates/clean-blog/backend_templates/default_staticpage_backend.tpl',
    'templates/default/admin/README.txt',
    'templates/timeline/backend_templates/default_staticpage_backend.tpl'
);

/* A list of old or removed directories for 2.6.0 */
$dead_dirs_260 = array(
    $serendipity['serendipityPath'] . 'templates/2k11/admin'
);

/* A list of Styx files, to be removed or renamed by 2.7.0 */
$dead_files_270 = array(
    'include/admin/entries_overview.inc.php',
    'templates/default/admin/entries_overview.inc.tpl'
);

/* A list of Styx files, to be removed or renamed by 3.0.0 */
$dead_files_300 = array(
    'include/db/generic.inc.php',
    'templates/default/admin/js/jquery.cookie.js',
    'sql/db_update_0.2_0.3_mysql.sql',
    'sql/db_update_0.2_0.3_postgres.sql',
    'sql/db_update_0.3_0.4_mysql.sql',
    'sql/db_update_0.3_0.4_postgres.sql',
    'sql/db_update_0.5.1_0.6_mysql.sql',
    'sql/db_update_0.5.1_0.6_postgres.sql',
    'sql/db_update_0.5_0.5.1_mysql.sql',
    'sql/db_update_0.5_0.5.1_postgres.sql',
    'sql/db_update_0.6.10_0.6.11_mysql.sql',
    'sql/db_update_0.6.10_0.6.11_postgres.sql',
    'sql/db_update_0.6.1_0.6.2_mysql.sql',
    'sql/db_update_0.6.1_0.6.2_postgres.sql',
    'sql/db_update_0.6.2_0.6.3_mysql.sql',
    'sql/db_update_0.6.2_0.6.3_postgres.sql',
    'sql/db_update_0.6.3_0.6.4_mysql.sql',
    'sql/db_update_0.6.3_0.6.4_postgres.sql',
    'sql/db_update_0.6.4_0.6.5_mysql.sql',
    'sql/db_update_0.6.4_0.6.5_postgres.sql',
    'sql/db_update_0.6.5_0.6.6_mysql.sql',
    'sql/db_update_0.6.5_0.6.6_postgres.sql',
    'sql/db_update_0.6.6_0.6.7_mysql.sql',
    'sql/db_update_0.6.6_0.6.7_postgres.sql',
    'sql/db_update_0.6.8_0.6.9_mysql.sql',
    'sql/db_update_0.6.8_0.6.9_postgres.sql',
    'sql/db_update_0.6.9_0.7.0_mysql.sql',
    'sql/db_update_0.6.9_0.7.0_postgresql.sql',
    'sql/db_update_0.6_0.6.1_mysql.sql',
    'sql/db_update_0.6_0.6.1_postgres.sql',
    'sql/db_update_0.8-alpha10_0.8-alpha11_mysql.sql',
    'sql/db_update_0.8-alpha11_0.8-alpha12_mysql.sql',
    'sql/db_update_0.8-alpha11_0.8-alpha12_postgres.sql',
    'sql/db_update_0.8-alpha11_0.8-alpha12_sqlite.sql',
    'sql/db_update_0.8-alpha12_0.8-alpha13_mysql.sql',
    'sql/db_update_0.8-alpha12_0.8-alpha13_postgres.sql',
    'sql/db_update_0.8-alpha12_0.8-alpha13_sqlite.sql',
    'sql/db_update_0.8-alpha1_0.8-alpha2_mysql.sql',
    'sql/db_update_0.8-alpha4_0.8-alpha5_mysql.sql',
    'sql/db_update_0.8-alpha4_0.8-alpha5_postgres.sql',
    'sql/db_update_0.8-alpha4_0.8-alpha5_sqlite.sql',
    'sql/db_update_0.8-alpha5_0.8-alpha6_mysql.sql',
    'sql/db_update_0.8-alpha5_0.8-alpha6_postgres.sql',
    'sql/db_update_0.8-alpha5_0.8-alpha6_sqlite.sql',
    'sql/db_update_0.8-beta3_0.8-beta4_mysql.sql',
    'sql/db_update_0.8-beta3_0.8-beta4_postgres.sql',
    'sql/db_update_0.8-beta3_0.8-beta4_sqlite.sql',
    'sql/db_update_0.8-beta5_0.8-beta6_mysql.sql',
    'sql/db_update_0.8-beta5_0.8-beta6_postgres.sql',
    'sql/db_update_0.8-beta5_0.8-beta6_sqlite.sql',
    'sql/db_update_0.9-alpha1_0.9-alpha2_mysql.sql',
    'sql/db_update_0.9-alpha1_0.9-alpha2_postgres.sql',
    'sql/db_update_0.9-alpha1_0.9-alpha2_sqlite.sql',
    'sql/db_update_0.9-alpha2_0.9-alpha3_mysql.sql',
    'sql/db_update_0.9-alpha3_0.9-alpha4_mysql.sql',
    'sql/db_update_0.9-alpha4_0.9-alpha5_mysql.sql',
    'sql/db_update_1.1-alpha1_1.1-alpha2_mysql.sql',
    'sql/db_update_1.1-alpha2_1.1-alpha3_mysql.sql',
    'sql/db_update_1.1-alpha3_1.1-alpha4_mysql.sql',
    'sql/db_update_1.1-alpha4_1.1-alpha5_mysql.sql',
    'sql/db_update_1.1-alpha4_1.1-alpha5_postgres.sql',
    'sql/db_update_1.1-alpha4_1.1-alpha5_sqlite.sql',
    'sql/db_update_1.1-alpha5_1.1-alpha6_mysql.sql',
    'sql/db_update_1.1-alpha5_1.1-alpha6_postgres.sql',
    'sql/db_update_1.1-alpha5_1.1-alpha6_sqlite.sql',
    'sql/db_update_1.1-beta3_1.1-beta4_mysql.sql',
    'sql/db_update_1.1-beta3_1.1-beta4_postgres.sql',
    'sql/db_update_1.1-beta3_1.1-beta4_sqlite.sql',
    'sql/db_update_1.2-alpha1_1.2_alpha2_mysql.sql',
    'sql/db_update_1.2-alpha1_1.2_alpha2_postgres.sql',
    'sql/db_update_1.2-alpha1_1.2_alpha2_sqlite.sql',
    'sql/db_update_1.2-alpha2_1.2-alpha3_mysql.sql',
    'sql/db_update_1.2-alpha2_1.2-alpha3_postgres.sql',
    'sql/db_update_1.2-alpha2_1.2-alpha3_sqlite.sql',
    'sql/db_update_1.2-alpha3_1.2_alpha4_mysql.sql',
    'sql/db_update_1.2-alpha3_1.2_alpha4_postgres.sql',
    'sql/db_update_1.2-alpha3_1.2_alpha4_sqlite.sql',
    'sql/db_update_1.5-alpha1_1.5-alpha2_mysql.sql',
    'sql/db_update_1.5-alpha1_1.5-alpha2_postgres.sql',
    'sql/db_update_1.5-alpha1_1.5-alpha2_sqlite.sql',
    'templates/pure/modernizr.min.js'
);

/* A list of old or removed directories for 3.0.0 */
$dead_dirs_300 = array(
    $serendipity['serendipityPath'] . 'bundled-libs/paragonie/random_compat',
    $serendipity['serendipityPath'] . 'bundled-libs/cryptor',
    $serendipity['serendipityPath'] . 'bundled-libs/zendframework',
    $serendipity['serendipityPath'] . 'htmlarea',
    $serendipity['serendipityPath'] . 'templates/2styx',
    $serendipity['serendipityPath'] . 'templates_c/2styx'
);

/* A list of Styx files, to be removed or renamed by 3.1.0 */
$dead_files_310 = array(
    'templates/default/admin/serendipity_editor.js',
    'templates/default/admin/serendipity_editor.js.php',
    'templates/default/admin/serendipity_editor.js.tpl',
    'templates/default-php/admin/serendipity_editor.js.tpl'
);

/* A list of old or removed directories for 3.1.0 */
$dead_dirs_310 = array(
    $serendipity['serendipityPath'] . 'cache'
);

/* A list of Styx files, to be removed or renamed by 3.2.0 */
$dead_files_320 = array(
    'plugins/serendipity_event_autoupdate/UTF-8/lang_en.inc.php',
    'templates/pure/legal.txt',
    'templates/pure19/legal.txt'
);

/* A list of old or removed directories for 3.3.0 */
$dead_dirs_330 = array(
    $serendipity['serendipityPath'] . 'templates/bulletproof',
    $serendipity['serendipityPath'] . 'templates/pure19'
);

/* A list of Styx files, to be removed or renamed by 3.3.0 */
$dead_files_330 = array(
    'include/db/mysql.inc.php',
    'include/db/sqlrelay.inc.php',
    'templates/default/feed_atom0.3.tpl',
    'templates/default/feed_0.91.tpl'
);

/**
 * recursive directory call to purge files and directories
 *
 * @param array $dir directories
 * @return void
 */
function recursive_directory_iterator($dir = array()) {
    if (null === $dir) {
        return;
    }
    foreach($dir AS $path) {
        serendipity_removeDeadFiles_SPL($path);
        if (is_dir($path)) @rmdir($path);
    }
}

/**
 * Set/change config variables; Fix pluginlist for upgrade cases.
 *
 * @access private
 * @param  string   (reserved for future use)
 * @return boolean
 */
function serendipity_fixPlugins($case) {
    global $serendipity;

    switch($case) {
        case 'wrong_upgrade_version':
            // ZARATHUSTRA - Temporary Repair Service for wrong set (local) pluginlocation upgrade_version - keep for future repairs
            $rows = serendipity_db_query("SELECT a.class_name, a.version, a.upgrade_version, b.upgrade_version AS new_version, a.plugintype, a.pluginlocation
                                            FROM {$serendipity['dbPrefix']}pluginlist a
                                       LEFT JOIN {$serendipity['dbPrefix']}pluginlist b
                                              ON a.pluginlocation = 'local' AND b.pluginlocation = 'Spartacus' AND a.upgrade_version < b.upgrade_version
                                           WHERE a.class_name = b.class_name");
            if (is_array($rows)) {
                foreach($rows AS $row) {
                    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                             SET upgrade_version = '" . serendipity_db_escape_string($row['new_version']) . "'
                                           WHERE class_name = '". serendipity_db_escape_string($row['class_name']) . "'
                                             AND pluginlocation = 'local'");
                }
                unset($rows);
            }
            // second case where remote plugins do not have a upgrade_version and the version is the latest Spartacus version
            $rows = serendipity_db_query("SELECT a.class_name, a.version, a.upgrade_version, b.version AS new_version, a.plugintype, a.pluginlocation
                                            FROM {$serendipity['dbPrefix']}pluginlist a
                                       LEFT JOIN {$serendipity['dbPrefix']}pluginlist b
                                              ON a.pluginlocation = 'local' AND b.pluginlocation = 'Spartacus' AND
                                                (b.upgrade_version IS NULL OR b.upgrade_version = '') AND a.upgrade_version < b.version
                                           WHERE a.class_name = b.class_name");
            if (is_array($rows)) {
                foreach($rows AS $row) {
                    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                             SET upgrade_version = '" . serendipity_db_escape_string($row['new_version']) . "'
                                           WHERE class_name = '". serendipity_db_escape_string($row['class_name']) . "'
                                             AND pluginlocation = 'local'");
                }
                unset($rows);
            }
            return true;
            break;

        case 'change_backend_name':
            if ($serendipity['template_backend'] == '2styx') {
                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config
                                         SET value = 'styx'
                                       WHERE name = 'template_backend'
                                         AND value = '2styx'");
                recursive_directory_iterator($dead_dirs_300);
            }
            return true;
            break;

        case 'spartacus_custom_reset':
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}config
                                     SET value = ''
                                   WHERE name LIKE '%custommirror'
                                     AND value = 'https://raw.githubusercontent.com/ophian/additional_plugins/master/'");
            return true;
            break;

        // Styx 2.4 moved some core plugins to the additional_plugins Spartacus repository. This checks for a proper upgrade version. It will also fix some older issues with moved plugins.
        // To catch em all, a plugin list sync should already have run before !!
        case 'moved_to_spartacus':
            $rows = serendipity_db_query("SELECT a.class_name, a.version, a.upgrade_version, b.version AS new_version, a.plugintype, a.pluginlocation
                                            FROM {$serendipity['dbPrefix']}pluginlist a
                                       LEFT JOIN {$serendipity['dbPrefix']}pluginlist b
                                              ON (a.pluginlocation = 'local' AND a.upgrade_version != '' AND b.pluginlocation = 'Spartacus' AND b.upgrade_version = '')
                                           WHERE a.class_name = b.class_name AND a.upgrade_version < b.version");
            if (!is_array($rows)) {
                return false;
            }

            foreach($rows AS $row) {
                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}pluginlist
                                         SET upgrade_version = '" . serendipity_db_escape_string($row['new_version']) . "'
                                       WHERE class_name = '". serendipity_db_escape_string($row['class_name']) . "'
                                         AND pluginlocation = 'local'");
            }
            unset($rows);
            return true;
            break;

        case 'cleanup_default_widgets':
            serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name = 'default_widgets'");
            return true;
            break;
    }
}

/**
 * DELETEs a plugin name value in the database by name [2.0 betas]
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
 * @param   string  $dir directory
 * @param   array   $deadfiles dead files list
 * @param   array   $purgedir dead directories list
 * @param   boolean $list_only run list only else recursive default
 *
 * @return void
 */
function serendipity_removeDeadFiles_SPL($dir=null, $deadfiles=null, $purgedir=null, $list_only=false) {
    if (!is_dir($dir)) {
        return;
    }
    try {
        $_dir = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
    } catch (Throwable $t) {
        return;
    }

    $debugSPL = false; // dev debug only
    $iterator = new RecursiveIteratorIterator($_dir, RecursiveIteratorIterator::CHILD_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD); // path, mode, flag
    $search   = array("\\", '//');
    $replace  = array('/');

    foreach($iterator AS $file) {
        $thisfile = str_replace($search, $replace, $file->__toString());
        if ($file->isFile()) {
            if (is_array($deadfiles) && !empty($deadfiles)) {
                foreach($deadfiles AS $deadfile) {
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
                foreach($purgedir AS $pdir) {
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
        foreach($files AS $fileinfo) {
            if ($fileinfo->isDir()) {
                // Count the number of "children" from the main directory iterator
                if (iterator_count($files->getChildren()) === 0) {
                    #DEBUG#echo $fileinfo->getRealPath()."\n<br>";
                    @rmdir($fileinfo->getRealPath());
                }
            }
        }
        return true;
    } catch (Throwable $t) {
        return;
    }
}

/**
 * Remove and cleanup old compiled Smarty2 files using the Standard PHP Library (SPL) iterator
 * Use as an upgrade task probably only once!
 *
 * @access private
 *
 * @param   string $path parent directory
 *
 * @return void
 */
function serendipity_cleanUpOldCompilerFiles_SPL( $path=null ) {
    if (!is_dir($path)) {
        return;
    }
    foreach(new DirectoryIterator($path) AS $iterator) {
        if ($iterator->isDot() || $iterator->isDir() || $iterator->getFilename()[0] === '.' ||
                (false === stripos($iterator->getFilename(), '.tpl.php') &&
                    (strlen($iterator->getFilename()) !== 9 && !empty($iterator->getExtension()) || false !== stripos($iterator->getFilename(), 'cache_'))
                )
            ) continue;
            // removes wrtFWSSdg and name^%%4A^4AD^4AD14682%%content.tpl.php example files only
        #echo  $path . '/' . $iterator->getFilename() . "<br>\n";
        @unlink($path. '/' . $iterator->getFilename());
    }
    return;
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
            $plugin['name'] = str_replace('serendipity_html_nugget_plugin', 'serendipity_plugin_html_nugget', $plugin['name']); // ditto force renaming of old nugget plugin name explicitly
#            $plugin['name'] = str_replace('serendipity_plugin_topreferers', 'serendipity_plugin_topreferrers', $plugin['name']); // ditto old plugin now lives as topreferrers plugin
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
    foreach($optionsToPort AS $oldPluginOption => $newGeneralOption) {
        $value = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE 'serendipity_plugin_syndication%{$oldPluginOption}'", true);
        if (is_array($value)) {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}config (name, value) VALUES ('$newGeneralOption', '". serendipity_db_escape_string($value[0]) ."')");
        }
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name LIKE 'serendipity_plugin_syndication%{$oldPluginOption}'");
    }

    $fbid = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE 'serendipity_plugin_syndication%fb_id'", true);
    $show_feedburner = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE 'serendipity_plugin_syndication%show_feedburner'");
    if ($show_feedburner == 'force') {
        if (!empty($fbid[0])) {
            $fburl = 'https://feeds.feedburner.com/' . $fbid[0];
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}config (name, value) VALUES ('feedCustom', '" . serendipity_db_escape_string($fburl) ."')");
        }
    }
    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name LIKE 'serendipity_plugin_syndication%show_feedburner'");
}

/**
 * Purge files in the template_cache directory per Upgrade task to allow fetching new preview images.
 *       OR delete single theme previews per Maintenance Theme Clearance Spot request
 * @param mixed     $themes     All themes is true, else array of themes, else default false
 *
 * @return bool
 */
function serendipity_purgeTemplatesCache($themes=false) {
    global $serendipity;

    $path = $serendipity["serendipityPath"] . PATH_SMARTY_COMPILE . '/template_cache';
    if ($themes === true) {
        // check Styx 3.0 current first items - the jpg file is there, all others are 3.0 dev + only
        if (file_exists($path . '/1024px.jpg') && !file_exists($path . '/1024px_preview.png') && !file_exists($path . '/1024px.webp') && !file_exists($path . '/1024px_preview.webp')) {
            return serendipity_removeDeadFiles_SPL($path);
        }
    } else {
        $files = @scandir($path);
        if (is_array($themes) && !empty($themes)) {
            foreach ($themes AS $theme) {
                $themename = basename($theme);
                if (is_array($files) && in_array($themename . '.jpg', $files)) {
                    @unlink($path . '/' . $themename); // possible old empty occurrences which were placed when no valid image was available
                    @unlink($path . '/' . $themename . '.jpg'); // the fullsize
                    @unlink($path . '/' . $themename . '.webp'); // the fullsize variation
                    @unlink($path . '/' . $themename . '_preview.png'); // the preview
                    @unlink($path . '/' . $themename . '_preview.webp'); // the preview variation
                }
            }
            return true;
        }
    }
    return false;
}

/**
 * Delete old config Plugin Variables that have completely been removed.
 */
function serendipity_cleanupConfigVars($name='') {
        global $serendipity;

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name = '" . serendipity_db_escape_string($name) . "'");
}
