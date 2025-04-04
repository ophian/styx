<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (!headers_sent() && session_status() != PHP_SESSION_ACTIVE) {
    session_cache_limiter('public');
}
header('Content-Type: text/xml; charset=utf-8');

@define('IN_RSS', true);

include('serendipity_config.inc.php');
include(S9Y_INCLUDE_PATH . 'include/functions_rss.inc.php');

if ($serendipity['cors']) {
    header('Access-Control-Allow-Origin: *'); // Allow RSS feeds to be read by javascript
}
if (!empty($_GET['url'])) {
    $_ext  = pathinfo(parse_url($_GET['url'], PHP_URL_PATH), PATHINFO_EXTENSION);
    $_name = pathinfo(parse_url($_GET['url'], PHP_URL_PATH), PATHINFO_FILENAME);
}

$version     = $_GET['version'] ?? null;
$description = $serendipity['blogDescription'];
$title       = $serendipity['blogTitle'];
$comments    = FALSE;

if (empty($version)) {
    list($version) = serendipity_discover_rss(($_name ?? null), ($_ext ?? null));
}

if (isset($_GET['category'])) {
    $serendipity['GET']['category'] = $_GET['category'];
}

if (isset($_GET['viewAuthor'])) {
    $serendipity['GET']['viewAuthor'] = $_GET['viewAuthor'];
}

if (!isset($_GET['type'])) {
    $_GET['type'] = 'content';
}

if ($_GET['type'] == 'comments' && !isset($_GET['cid'])) {
    $_GET['cid'] = null;
}

if (!empty($_SERVER['HTTP_USER_AGENT']) && stristr($_SERVER['HTTP_USER_AGENT'], 'feedburner')) {
    $_GET['nocache'] = true;
}

$serendipity['view'] = 'feed';

switch ($_GET['type']) {
    case 'comments_and_trackbacks':
    case 'trackbacks':
    case 'comments':
        $latest_entry = serendipity_fetchComments((int) $_GET['cid'], '1', 'co.id desc', false, $_GET['type']);
        break;
    case 'content':
    default:
        $latest_entry = serendipity_fetchEntries(null, false, 1, false, false, 'last_modified DESC', '', false, true);
    break;
}

if (!isset($_GET['nocache'])) {
    /*
     * Caching logic - Do not send feed if nothing has changed
     * Implementation inspired by Simon Willison [http://simon.incutio.com/archive/2003/04/23/conditionalGet], Thiemo Maettig
     */

    // See if the client has provided the required headers.
    // Always convert the provided header into GMT timezone to allow comparing to the server-side last-modified header
    $modified_since = !empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])
                    ? gmdate('D, d M Y H:i:s \G\M\T', strtotime(stripslashes($_SERVER['HTTP_IF_MODIFIED_SINCE'])))
                    : false;
    $none_match     = !empty($_SERVER['HTTP_IF_NONE_MATCH'])
                    ? str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH']))
                    : false;

    if (is_array($latest_entry) && isset($latest_entry[0]['last_modified'])) {
        $last_modified = gmdate('D, d M Y H:i:s \G\M\T', serendipity_serverOffsetHour($latest_entry[0]['last_modified'], false));
        $etag          = '"' . $last_modified . '"';

        header('Last-Modified: ' . $last_modified);
        header('ETag: '          . $etag);

        if (($none_match == $last_modified && $modified_since == $last_modified) ||
            (!$none_match && $modified_since == $last_modified) ||
            (!$modified_since && $none_match == $last_modified)) {
            header(serendipity_getServerProtocol() . ' 304 Not Modified', true, 304); // force
            header('Status: 304 Not Modified'); // overwrite Status 200
            return;
        }
    }
}

if (isset($modified_since)
&& ((isset($_SERVER['HTTP_USER_AGENT']) && stristr($_SERVER['HTTP_USER_AGENT'], 'planet') !== FALSE) || $serendipity['enforce_RFC2616'])) {
    // People shall get a usual HTTP response according to RFC2616. See serendipity_config.inc.php for details
    $modified_since = FALSE;
}
if (!isset($modified_since)) {
    $modified_since = false;
}

switch ($_GET['type']) {
    case 'comments_and_trackbacks':
    case 'trackbacks':
    case 'comments':
        $entries = serendipity_fetchComments((int) $_GET['cid'], $serendipity['RSSfetchLimit'], 'co.id desc', false, $_GET['type']);
        $description = $title . ' - ' . $description;
        if (isset($_GET['cid'])) {
            $title = $title . ' - ' . COMMENTS_FROM . ' "' . $latest_entry[0]['title'] . '"';
        } else {
            $title = $title . ' - ' . COMMENTS;
        }
        $comments = TRUE;
        break;

    case 'content':
    default:
        if (isset($_GET['all']) && $_GET['all']) {
            // Fetch all entries in reverse order for later importing. Fetch sticky entries as normal entries.
            $entries = serendipity_fetchEntries(null, true, '', false, false, 'id ASC', '', false, true);
        } else {
            $entries = serendipity_fetchEntries(null, true, $serendipity['RSSfetchLimit'], false, $modified_since, 'timestamp DESC', '', false, true);
        }
        break;
}

if (isset($serendipity['serendipityRealname'])) {
    $title .= ' (' . LOGIN . ': ' . $serendipity['serendipityRealname'] . ')';
}

if (!empty($serendipity['GET']['category'])) {
    $cInfo = serendipity_fetchCategoryInfo((int)$serendipity['GET']['category']);
    $title = serendipity_utf8_encode(htmlspecialchars($title . ' - '. $cInfo['category_name']));
} elseif (!empty($serendipity['GET']['viewAuthor'])) {
    list($aInfo) = serendipity_fetchAuthor((int) $serendipity['GET']['viewAuthor']);
    $title = serendipity_utf8_encode(htmlspecialchars($aInfo['realname'] . ' - '. $title ));
} else {
    $title = serendipity_utf8_encode(htmlspecialchars($title));
}

$description = serendipity_utf8_encode(htmlspecialchars($description));

// Add simple xls/transform data layer for our atom feed
$xslt = '';
if ($xslt_file = serendipity_getTemplateFile('feed_xslt.xsl', 'serendipityPath')) {
    $xslt_url = serendipity_getTemplateFile('feed_xslt.xsl', 'baseURL');
    $xslt = "<?xml-stylesheet type=\"text/xml\" href=\"$xslt_url\"?>";
}

$metadata = array(
    'title'             => $title,
    'description'       => $description,
    'language'          => $serendipity['lang'],
    'additional_fields' => array(),
    'link'              => $serendipity['baseURL'],
    'email'             => $serendipity['blogMail'],
    'fullFeed'          => false,
    'showMail'          => false,
    'version'           => $version,
    'xslt'              => $xslt
);

if (serendipity_get_config_var('feedBannerURL') != '') {
    $img = serendipity_get_config_var('feedBannerURL');
    $w   = serendipity_get_config_var('feedBannerWidth');
    $h   = serendipity_get_config_var('feedBannerHeight');
} elseif ($banner = serendipity_getTemplateFile('img/rss_banner.png', 'serendipityPath')) {
    $img = serendipity_getTemplateFile('img/rss_banner.png', 'baseURL');
    $i   = getimagesize($banner); // 1:1 ratio for icon !
    $w   = $i[0];
    $h   = $i[1];
} else {
    $img = $serendipity['baseURL'] . $serendipity['templatePath'] . 'styx_logo_150.png'; // 2:1 ratio for logo !
    $w   = 120; //  Maximum value for width is 144, default value is 88.
    $h   = 60; // Maximum value for height is 400, default value is 31.
}

$metadata['additional_fields']['image'] = <<<IMAGE
<image>
    <url>$img</url>
    <title>RSS: $title - $description</title>
    <link>{$serendipity['baseURL']}</link>
    <width>$w</width>
    <height>$h</height>
</image>
IMAGE;

$metadata['additional_fields']['image_atom10'] = <<<IMAGE
<icon><svg xmlns="http://www.w3.org/2000/svg" version="1.1" style="vertical-align: text-bottom; width: 2.5em; height: 2.5em; float: right;" class="pr-1" id="RSSicon" viewBox="0 0 256 256"><defs><linearGradient x1="0.085" y1="0.085" x2="0.915" y2="0.915" id="RSSg"><stop offset="0.0" stop-color="#E3702D"></stop><stop offset="0.1071" stop-color="#EA7D31"></stop><stop offset="0.3503" stop-color="#F69537"></stop><stop offset="0.5" stop-color="#FB9E3A"></stop><stop offset="0.7016" stop-color="#EA7C31"></stop><stop offset="0.8866" stop-color="#DE642B"></stop><stop offset="1.0" stop-color="#D95B29"></stop></linearGradient></defs><rect width="256" height="256" rx="55" ry="55" x="0" y="0" fill="#CC5D15"></rect><rect width="246" height="246" rx="50" ry="50" x="5" y="5" fill="#F49C52"></rect><rect width="236" height="236" rx="47" ry="47" x="10" y="10" fill="url(#RSSg)"></rect><circle cx="68" cy="189" r="24" fill="#FFF"></circle><path d="M160 213h-34a82 82 0 0 0 -82 -82v-34a116 116 0 0 1 116 116z" fill="#FFF"></path><path d="M184 213A140 140 0 0 0 44 73 V 38a175 175 0 0 1 175 175z" fill="#FFF"></path></svg></icon>
IMAGE;

$metadata['additional_fields']['image_rss10_channel'] = '<image rdf:resource="' . $img . '" />';
$metadata['additional_fields']['image_rss10_rdf'] = <<<IMAGE
<image rdf:about="$img">
    <url>$img</url>
    <title>RSS: $title - $description</title>
    <link>{$serendipity['baseURL']}</link>
    <width>$w</width>
    <height>$h</height>
</image>
IMAGE;

// Now, if set, stitch together any fields that have been configured in the syndication plugin.
// First, do some sanity checks
$metadata['additional_fields']['channel'] = '';
$rssFields = array('feedManagingEditor' => 'managingEditor', 'feedWebmaster' => 'webMaster', 'feedTtl' => 'ttl', 'feedPubDate' => 'pubDate');
$pubDateFallback = serendipity_get_config_var('last_template_change');

foreach( $rssFields AS $configName => $field) {
    $fieldValue = serendipity_get_config_var($configName);

    switch($field) {
        case 'pubDate':
            if (serendipity_db_bool($fieldValue)) {
                $pdate = empty($entries[0]) ? $pubDateFallback : ($entries[0]['last_modified'] ?? 1556988924); // ternary fake timestamp since probably only used when runtime errors appear
                $fieldValue  = gmdate('D, d M Y H:i:s \G\M\T', (int) $pdate);
            } else {
                $fieldValue  = '';
            }
            break;

        // Each new RSS-field which needs rewrite of its content should get its own case here.

        default:
            break;
    }

    if ($fieldValue != '') {
        $metadata['additional_fields']['channel'] .= '<' . $field . '>' . $fieldValue . '</' . $field . '>' . "\n";
    }
}

if (is_array($metadata['additional_fields'])) {
    // Fix up array keys, because "." are not allowed when wanting to output using Smarty
    foreach($metadata['additional_fields'] AS $_aid => $af) {
        $aid = str_replace('.', '', $_aid);
        $metadata['additional_fields'][$aid] = $af;
    }
}
$metadata['fullFeed'] = serendipity_get_config_var('feedFull', false);
if ($metadata['fullFeed'] === 'client') {
    if (array_key_exists('fullFeed', $_GET) || array_key_exists('fullFeed', $serendipity['GET'])) {
        $metadata['fullFeed'] = true;
    } else {
        $metadata['fullFeed'] = false;
    }
} else {
    $metadata['fullFeed'] = serendipity_db_bool($metadata['fullFeed']);
}

if ($_GET['type'] == 'content' && !isset($_GET['category']) && !isset($serendipity['GET']['tag']) &&
    serendipity_db_bool(serendipity_get_config_var('feedForceCustom', false)) &&
    !preg_match('@FeedBurn@i', $_SERVER['HTTP_USER_AGENT']) && // the hardcoded pass for feedburner (it is officially shut down since end of 2012, but still alive in 2018) is for BC. New services should just use the forceLocal-param
    !isset($_GET['forceLocal']))
{
    header('Status: 302 Found');
    header('Location: ' . serendipity_get_config_var('feedCustom'));
    exit;
}
$metadata['showMail'] = serendipity_db_bool(serendipity_get_config_var('feedShowMail', $metadata['showMail']));

$file_version = preg_replace('@[^0-9a-z\.-_]@i', '', $version);
$metadata['template_file'] = serendipity_getTemplateFile('feed_' . $file_version . '.tpl', 'serendipityPath');

serendipity_smarty_init();
serendipity_plugin_api::hook_event('frontend_rss', $metadata);

if (!$metadata['template_file'] || $metadata['template_file'] == 'feed_' . $file_version . '.tpl') {
    die("Invalid RSS version specified or RSS-template file 'feed_$file_version.tpl' not found\n");
}

$self_url = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . htmlspecialchars($_SERVER['REQUEST_URI']);
if (!is_array($entries)) {
    $entries = array();
}

if (!empty($entries[0]['last_modified'])) {
    $gm_modified = gmdate('Y-m-d\TH:i:s\Z', serendipity_serverOffsetHour($entries[0]['last_modified']));
} else {
    $gm_modified = gmdate('Y-m-d\TH:i:s\Z', serendipity_serverOffsetHour());
}

serendipity_printEntries_rss($entries, $version, $comments, $metadata['fullFeed'], $metadata['showMail']);

$namespace_hook   = 'frontend_display:unknown:namespace';
$once_display_dat = '';

switch($version) {
    case 'xsl':
        // For people wanting extra RFC compliance
        #header('Content-Type: application/xml; charset=utf-8');
        #header('x-content-type-options: nosniff');
        $namespace_hook = 'frontend_display:xslt:namespace';
        break;

    case 'opml1.0':
        $namespace_hook = 'frontend_display:opml-1.0:namespace';
        break;

    case '1.0':
        $namespace_hook = 'frontend_display:rss-1.0:namespace';
        serendipity_plugin_api::hook_event('frontend_display:rss-1.0:once', $entries); // for CC license sources
        $once_display_dat = $entries['display_dat'] ?? null;
        unset($entries['display_dat']);
        break;

    case '2.0':
        $namespace_hook = 'frontend_display:rss-2.0:namespace';
        break;

    case 'rdf':
        $namespace_hook = 'frontend_display:rss-rdf:namespace';
        break;

    case 'atom1.0':
        // For people wanting extra RFC compliance
        // header('Content-Type: application/atom+xml; charset=utf-8');
        $namespace_hook = 'frontend_display:atom-1.0:namespace';
        break;
}

serendipity_plugin_api::hook_event($namespace_hook, $entries);

$namespace_display_dat = $entries['display_dat'] ?? null;
$channel_display_dat   = $entries['channel_dat'] ?? null;

unset($entries['display_dat']);
unset($entries['channel_dat']);

$serendipity['smarty']->assignByRef('metadata', $metadata);
$serendipity['smarty']->assignByRef('entries', $entries);
$serendipity['smarty']->assignByRef('namespace_display_dat', $namespace_display_dat);
$serendipity['smarty']->assignByRef('channel_display_dat', $channel_display_dat);
$serendipity['smarty']->assignByRef('once_display_dat', $once_display_dat);

$serendipity['smarty']->assign(
    array(
        'is_comments'           => $comments,
        'last_modified'         => $gm_modified,
        'self_url'              => $self_url,
    )
);
$serendipity['smarty']->display($metadata['template_file']);

/* vim: set sts=4 ts=4 expandtab : */
