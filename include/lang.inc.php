<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (isset($serendipity['lang']) && !isset($serendipity['languages'][$serendipity['lang']])) {
    $serendipity['lang'] = $serendipity['autolang'];
}

if (!defined('serendipity_LANG_LOADED') || serendipity_LANG_LOADED !== true) {
    // The following variable can be set in serendipity_config_local.inc.php to force your templates being able to use language override includes
    // An un-promoted private variable set, to first serve template based serendipity lang constants that have higher priority.
    // We do not allow this for backend constants and both, the fallback "default" and the standard theme (currently "pure") !
    if (isset($serendipity['useTemplateLanguage']) && $serendipity['useTemplateLanguage'] === true && !empty($serendipity['template'])
    && !defined('IN_serendipity_admin') && !in_array($serendipity['template'], ['default', 'default-php', $serendipity['defaultTemplate']])) {
        if (defined('S9Y_DATA_PATH')) {
            @include_once (S9Y_DATA_PATH . 'templates/' . $serendipity['template'] . '/' . LANG_CHARSET . '/lang_' . $serendipity['lang'] . '.inc.php');
            @include_once (S9Y_DATA_PATH . 'templates/' . $serendipity['template'] . '/lang_en.inc.php');
        } else {
            @include_once (S9Y_INCLUDE_PATH . 'templates/' . $serendipity['template'] . '/' . LANG_CHARSET . '/lang_' . $serendipity['lang'] . '.inc.php');
            @include_once (S9Y_INCLUDE_PATH . 'templates/' . $serendipity['template'] . '/lang_en.inc.php');
        }
    }

    // Try and include preferred language from the configured setting

    if (@include(S9Y_INCLUDE_PATH . 'lang/serendipity_lang_'. $serendipity['lang'] .'.inc.php') ) {
        // Only here can we truly say the language is loaded
        define('serendipity_LANG_LOADED', true);
        if (function_exists('serendipity_db_reconnect')) {
            serendipity_db_reconnect();
        }
    } elseif (IS_installed === false || (defined('IS_up2date') && IS_up2date === false)) {   /* -- Auto-Guess -- */
        // If no config file is loaded, language includes are not available.
        // Now include one. Try to auto-guess the language by looking up the HTTP_ACCEPT_LANGUAGE.
        serendipity_detectLang(true);
    }

    // Do fall back to english
    if (IS_installed === false || (defined('IS_up2date') && IS_up2date === false)) {
        @include_once(S9Y_INCLUDE_PATH . 'lang/serendipity_lang_en.inc.php');
    }
}

if (!defined('serendipity_MB_LOADED') && defined('serendipity_LANG_LOADED')) {
    // Needs to be included here because we need access to constant LANG_CHARSET defined in languages (not available for compat.inc.php)

    // Normally mb_language() is used for encoding e-mail messages.
    // Valid languages are "Japanese", "ja", "English", "en" and "uni" (UTF-8). mb_send_mail() uses this setting to encode e-mail.
    // Language and its setting is: ISO-2022-JP/Base64 for Japanese, UTF-8/Base64 for uni, ISO-8859-1/quoted printable for English
    @mb_language('uni');
    @mb_internal_encoding(LANG_CHARSET);

    define('serendipity_MB_LOADED', true);
}

/* vim: set sts=4 ts=4 expandtab : */
