<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/* This is a small hack to allow CSS display during installations and upgrades */
define('IN_installer', true);
define('IN_upgrader', true);
define('IN_CSS', true);

if (!headers_sent() && session_status() != PHP_SESSION_ACTIVE) {
    session_cache_limiter('public');
}

if (!defined('S9Y_FRAMEWORK')) {
    include('serendipity_config.inc.php');
}

if (!isset($css_mode)) {
    if (!empty($serendipity['GET']['css_mode'])) {
        $css_mode = $serendipity['GET']['css_mode'];
    } else {
        $css_mode = 'serendipity.css';
    }
}

switch($css_mode) {
    case 'external_plugin':
        $css_root = '../';
    case 'serendipity.css':
    default:
        $css_hook = 'css';
        $css_file = 'style.css';
        $css_userfile = 'user.css';
        break;

    case 'serendipity_admin.css':
        // This constant is needed to properly set the template context for the backend.
        @define('IN_serendipity_admin', true);
        $css_hook = 'css_backend';
        $css_file = 'admin/style.css';
        $css_userfile = 'admin/user.css';
        break;
}

/**
 * Print out the Stylesheet
 *
 * @param string file file name
 * @param string dir (optional) The relative directory path
 * @param string root (optional) Whether to change files relative replacement {TEMPLATE_PATH} path because of subdirectory /plugin call
 *
 * @return string file contents
 */
function serendipity_printStylesheet($file, $dir = '', $root = '') {
    if (empty($file) || $file == 'admin/user.css' || $file == 'user.css') {
        return; // it does not exists since having no serendipityPath !
    }
    return "\n/* auto include $dir */\n\n" . str_replace(
            array(
               '{TEMPLATE_PATH}',
               '{LANG_DIRECTION}'
            ),

            array(
               $root . dirname($dir) . '/',
               LANG_DIRECTION
            ),

            file_get_contents($file, 1));
}

// We want the css file to immediate be recognized as a new file when changes have happened. This is done by checking and setting the ETag hash. We don't do query string timestamps any more!
// Note that no-cache does not mean "don't cache". no-cache allows caches to store a response but requires them to revalidate it before reuse.
// If the sense of "don't cache" that you want is actually "don't store", then no-store is the directive to use.
header("Cache-Control: no-cache, max-age=3600"); // 1 hour - if this all works we could even set this to 12/24 hours

header('Content-type: text/css; charset=' . LANG_CHARSET); // set correct mime type

if (IS_installed === false) {
    if (file_exists(S9Y_INCLUDE_PATH . 'templates/' . $serendipity['defaultTemplate'] . '/' . $css_file)) {
        echo serendipity_printStylesheet('templates/' . $serendipity['defaultTemplate'] . '/' . $css_file, 'templates/' . $serendipity['defaultTemplate'] . '/' . $css_file);
    }
    die();
}

if (!isset($css_root) || $css_root != '../') {
    $css_root = '';
}

// Use output buffering to capture all output. This is necessary
// because a plugin might call 'echo' directly instead of adding
// the desired output to the hook parameter '$out'.
ob_start();

// First all of our fallback classes, so they can be overridden by the usual template.
// The second (which could also use an @-silence) is just a "faked" call, to catch the files
// relative path in _getTemplateFile (w/o the 2cd parameters serendipityHTTPPath key default)
// of the same template directory for debug like messages.
// This path isn't defined as a $serendipity GLOBAL and therefore throws an undefined index.
// Since there may be more of this we just check for isset($serendipity[$key]) in there.
$out = serendipity_printStylesheet(
            serendipity_getTemplateFile('style_fallback.css', 'serendipityPath'),
            serendipity_getTemplateFile('style_fallback.css', '')
);

$out .= serendipity_printStylesheet(
            serendipity_getTemplateFile($css_file, 'serendipityPath'),
            serendipity_getTemplateFile($css_file, ''),
            $css_root
);
serendipity_plugin_api::hook_event($css_hook, $out);

// Do not allow force_frontend_fallback for all three! (NO! For style_fallback.css this is obvious (normally).
// But for the user.css files this is an vital behaviour, since the fall back line is always [0]user, [1]default, [2]standard - theme. Independently from 3rd param force_frontend_fallback true/false usage!)
$out .= serendipity_printStylesheet(
            serendipity_getTemplateFile($css_userfile, 'serendipityPath', true),
            serendipity_getTemplateFile($css_userfile, '', true),
            $css_root
);

echo $out;

serendipity_setNotModifiedHeader(); // 304

/* vim: set sts=4 ts=4 expandtab : */
