<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

include_once('serendipity_config.inc.php');

include_once(S9Y_INCLUDE_PATH . 'include/plugin_api.inc.php');

$uri = $_SERVER['REQUEST_URI']; // need to define this again here, as index.php (v2.1+) no longer includes this file
$uri_addData = array(
    'startpage' => false,
    'uriargs'   => implode('/', serendipity_getUriArguments($uri, true)),
    'view'      => $serendipity['view'],
    'viewtype'  => $serendipity['viewtype'] ?? ''
);

if ((empty($uri_addData['uriargs']) || trim($uri_addData['uriargs']) == $serendipity['indexFile']) && empty($serendipity['GET']['subpage'])) {
    $uri_addData['startpage'] = true;
}

$serendipity['plugindata']['smartyvars'] = $uri_addData; // Plugins can change this global variable, so we cache previously set $vars, which is $view etc. and re-add per init afterwards
serendipity_plugin_api::hook_event('genpage', $uri, $uri_addData);
serendipity_smarty_init($serendipity['plugindata']['smartyvars']);

$leftSidebarElements  = serendipity_plugin_api::count_plugins('left');
$rightSidebarElements = serendipity_plugin_api::count_plugins('right');
$serendipity['smarty']->assignByRef('leftSidebarElements', $leftSidebarElements);
$serendipity['smarty']->assignByRef('rightSidebarElements', $rightSidebarElements);

$is_archives = false;
$is_search_empty = false;

/* Disabled again, since it borks search requests with blogs having a staticpage startpage and the follow-up pagination pages! Too much to fiddle for such a simple change.
// Allow search requests per post
if (empty($serendipity['GET']['searchTerm']) && !empty($serendipity['POST']['searchTerm'])) {
    $serendipity['GET']['action'] = 'search';
    $serendipity['GET']['searchTerm'] = $serendipity['POST']['searchTerm'];
    $serendipity['uriArguments'][] = serendipity_specialchars($serendipity['POST']['searchTerm']);
}
*/

// mute possible uninitialized GET action item to fallback to default
switch (@$serendipity['GET']['action']) {
    // User wants to read the diary
    case 'read':
        if (isset($serendipity['GET']['id'])) {
            $entry = array(serendipity_fetchEntry('id', (int)$serendipity['GET']['id']));
            // If none or invalid, reset everything related to not leak caches, non-public posts or so
            if (!is_array($entry) || count($entry) < 1 || !is_array($entry[0])) {
                unset($serendipity['GET']['id']);
                $entry = array(array());
                $serendipity['head_title'] = serendipity_specialchars($serendipity['blogTitle']);
                $serendipity['head_subtitle'] = '';
                $serendipity['smarty']->assign('head_title', $serendipity['head_title']);
                $serendipity['smarty']->assign('head_subtitle', $serendipity['head_subtitle']);
                $serendipity['view'] = '404';
                $serendipity['content_message'] = URL_NOT_FOUND;
                serendipity_header('HTTP/1.0 404 Not found');
                serendipity_header('Status: 404 Not found');
            }

            serendipity_printEntries($entry, 1);
        } else {
            $range = $serendipity['range'] ?? null;
            // reset summaryFetchLimit on year month ie. '/archives/2018/07.html' cases
            if (isset($serendipity['summaryFetchLimit']) && !serendipity_contains('summary', $serendipity['uriArguments'])) {
                $serendipity['summaryFetchLimit'] = null;
            }
            $fetchLimit = $serendipity['summaryFetchLimit'] ?? $serendipity['fetchLimit'];
            serendipity_printEntries(serendipity_fetchEntries($range, true, $fetchLimit));
        }
        break;

    // User searches
    case 'search':
        $r = serendipity_searchEntries($serendipity['GET']['searchTerm']);
        if (strlen($serendipity['GET']['searchTerm']) <= 3) {
            $is_search_empty = true;
            $serendipity['smarty']->assign(
                array(
                    'content_message'       => SEARCH_TOO_SHORT,
                    'searchresult_tooShort' => true
                )
            );
            break;
        }

        if (is_string($r) && $r !== true) {
            $is_search_empty = true;
            $serendipity['smarty']->assign(
                array(
                    'content_message'    => sprintf(SEARCH_ERROR, $serendipity['dbPrefix'], $r),
                    'searchresult_error' => true
                )
            );
            break;
        } elseif ($r === true) {
            $is_search_empty = true;
            $serendipity['smarty']->assign(
                array(
                    'content_message'        => sprintf(NO_ENTRIES_BLAHBLAH, '<span class="searchterm">' . $serendipity['GET']['searchTerm'] . '</span>'),
                    'searchresult_noEntries' => true
                )
            );
            break;
        }

        $serendipity['smarty']->assign(
            array(
                'content_message'        => sprintf(YOUR_SEARCH_RETURNED_BLAHBLAH, '<span class="searchterm">' . $serendipity['GET']['searchTerm'] . '</span>', '<span class="searchresults">' . serendipity_getTotalEntries() . '</span>'),
                'searchresult_results'   => true,
                'searchresult_fullentry' => $serendipity['GET']['fullentry'] ?? ''
            )
        );

        serendipity_printEntries($r);
        break;

    // Show the comments
    case 'comments':
        serendipity_printCommentsByAuthor();
        # use 'content_message' for pagination?
        break;

    // Show the archive
    case 'archives':
        $is_archives = true;
        $serendipity['head_subtitle'] = ARCHIVES;
        $serendipity['smarty']->assign('head_subtitle', $serendipity['head_subtitle']);
        serendipity_printArchives();
        break;

    case 'custom':
        $serendipity['smarty']->assign('ENTRIES', ''); // At here too, content.tpl Smarty ENTRIES block variable needs to be set empty, else we'd need |default:'' modifier everywhere
        if (isset($serendipity['smarty_custom_vars']) && is_array($serendipity['smarty_custom_vars'])) {
            $serendipity['smarty']->assign($serendipity['smarty_custom_vars']);
        }
        break;

    case 'empty':
        break;

    // Welcome screen or whatever
    default:
        serendipity_printEntries(serendipity_fetchEntries(null, true, $serendipity['fetchLimit']));
        break;
}

// mute possible uninitialized search action
if (@$serendipity['GET']['action'] != 'search' && !empty($serendipity['content_message']) && $serendipity['view'] != 'plugin' && (isset($serendipity['viewtype']) && $serendipity['viewtype'] != '404_1')) {
    $serendipity['smarty']->assign('content_message', $serendipity['content_message']);
}

// default init these Serendipity Smarty Blocks for content.tpl
if (!$is_archives) {
    $serendipity['smarty']->assign('ARCHIVES', '');
}
if ($is_search_empty) {
    $serendipity['smarty']->assign('ENTRIES', '');
}

serendipity_smarty_fetch('CONTENT', 'content.tpl');

// global inits, waiting to be filled
$serendipity['smarty']->assign('ENTRIES', '');
$serendipity['smarty']->assign('raw_data', '');


/* vim: set sts=4 ts=4 expandtab : */
