<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

umask(0000);
$umask = 0775;
@define('IN_installer', true);

if (!isset($_POST['installAction'])) {
    $_POST['installAction'] = '';
}

if (!serendipity_checkPermission('siteConfiguration') && !serendipity_checkPermission('blogConfiguration')) {
    return;
}

$data = array();

if ($_POST['installAction'] == 'check' && serendipity_checkFormToken()) {

    $data['installAction'] = 'check';
    $oldConfig = $serendipity;
    $res = serendipity_updateConfiguration();
    $data['res'] = $res;

    if (is_array($res)) {
        $data['diagnosticError'] = true;
    } else {
        /* If we have new rewrite rules, then install them */
        $permalinkOld = array(
            $oldConfig['serendipityHTTPPath'],
            $oldConfig['serendipityPath'],
            $oldConfig['defaultBaseURL'],
            $oldConfig['indexFile'],
            $oldConfig['rewrite']);

        $permalinkNew = array(
            $serendipity['serendipityHTTPPath'],
            $serendipity['serendipityPath'],
            $serendipity['defaultBaseURL'],
            $serendipity['indexFile'],
            $serendipity['rewrite']);

        // Compare all old permalink section values against new one. A change in any of those
        // will force to update the .htaccess for rewrite rules.
        $permconf = serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE);
        if (is_array($permconf) && is_array($permconf['permalinks']['items'])) {
            foreach($permconf['permalinks']['items'] AS $permitem) {
                $permalinkOld[] = $oldConfig[$permitem['var']];
                $permalinkNew[] = $serendipity[$permitem['var']];
            }
        }

        if (serendipity_checkPermission('siteConfiguration') && serialize($permalinkOld) != serialize($permalinkNew)) {
            $data['htaccessRewrite'] = true;
            $data['serendipityPath'] = $serendipity['serendipityPath'];
            $res = serendipity_installFiles($serendipity['serendipityPath']);
            $data['res'] = $res;
            serendipity_buildPermalinks();
        }
    }
}

// set a config default value - else the input will be empty, since a config default value still relies on the global $serendipity array
// https://raw.githubusercontent.com/s9y/Serendipity/master/docs/RELEASE
if (empty($serendipity['updateReleaseFileUrl'])) {
    $serendipity['updateReleaseFileUrl'] = 'https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE';
}

// CONFIGURATION LANGUAGE STORED SET EXCEPTION, i.e.
// The public set global language is set to Spanish [es], but the Administrators personal preference language is using German [de] and the Administrator also wants to view the frontend - logged-in - to display in German [de].
// Without this temporary switch - defined in serendipity_getSessionLanguage() - the configuration form language would show up as selected German [de] without actually having being set to it yet,
// since it it guess-build on $serendipity['lang'] value.
// All available lang vars in SESSION, COOKIE, and the Serendipity global have to stay valid as defined by the workload. But for the configuration form we need the real set stored 'lang' state.
if (isset($serendipity['configurated_lang']) && $serendipity['lang'] != $serendipity['configurated_lang']) {
    // Temporary copy to pass over to build form and to guessInput and then return
    $_serendipity['lang'] = $serendipity['lang'];
    $serendipity['lang'] = $serendipity['configurated_lang'];
    unset($serendipity['configurated_lang']);
}

// A pre parsed and rendered template, analogue to 'ENTRIES' etc
$data['CONFIG'] = serendipity_printConfigTemplate(serendipity_parseTemplate(S9Y_CONFIG_TEMPLATE), $serendipity, false);

if (isset($_serendipity['lang'])) {
    // Reset to normal
    $serendipity['lang'] = $_serendipity['lang'];
    unset($_serendipity['lang']);
}

if (!is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

echo serendipity_smarty_showTemplate('admin/configuration.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
?>