<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

/**
 * The default URI starter route to index.php including uriArguments
 */
function serveIndex() {
    global $serendipity;

    $serendipity['view'] = (false === strpos($_SERVER['QUERY_STRING'], 'frontpage')) ? 'start' : 'entries';

    if ($serendipity['GET']['action'] == 'search') {
        $serendipity['view'] = 'search';
        $serendipity['uriArguments'] = array(PATH_SEARCH, urlencode($serendipity['GET']['searchTerm']));
    } else {
        $serendipity['uriArguments'][] = PATH_ARCHIVES;
    }

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default 404 fallback if nothing to serve was found
 */
function serve404() {
    global $serendipity;

    $serendipity['view'] = '404';
    $serendipity['viewtype'] = '404_4';
    $serendipity['uriArguments'] = array(PATH_ARCHIVES); // re-write wrong uri arguments for entrypaging
    if (!isset($serendipity['embed']) || serendipity_db_bool($serendipity['embed']) === false) {
        $serendipity['content_message'] = URL_NOT_FOUND;
    }

    header('HTTP/1.0 404 Not found');
    header('Status: 404 Not found');

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * Helper function. Attempt to locate hidden variables within the URI
 */
function locateHiddenVariables($_args) {
    global $serendipity;

    foreach($_args AS $k => $v) {
        if ($v == PATH_COMMENTS || $v == PATH_CATEGORIES || $v == PATH_ARCHIVE || $v == PATH_ARCHIVES) {
            continue;
        }

        if (isset($v[0]) && $v[0] == 'P') { /* Page */
            $page = substr($v, 1);
            // check for someone is willingly trying to break Serendipity by adding page orders > P2500.., which could result in breaking db limits - so we set a hard page limit
            if ($page > $serendipity['max_page_limit']) {
                return $_args;
            }
            if (is_numeric($page)) {
                $serendipity['GET']['page'] = $page;
                unset($_args[$k]);
                unset($serendipity['uriArguments'][$k]);
            }
        } elseif (isset($v[0]) && $v[0] == 'A') { /* Author */
            $url_author = substr($v, 1);
            if (is_numeric($url_author)) {
                $serendipity['GET']['viewAuthor'] = $_GET['viewAuthor'] = (int)$url_author;
                unset($_args[$k]);
            }
        } elseif ($v == 'summary') { /* Summary */
            $serendipity['short_archives'] = true;
            $serendipity['head_subtitle'] .= SUMMARY . ' - ';
            unset($_args[$k]);
        } elseif (isset($v[0]) && $v[0] == 'C') { /* C.ategory in "/categories/" and "/archives/" like URIs */
            $cat = substr($v, 1);
            if (is_numeric($cat)) {
                $serendipity['GET']['category'] = $cat;
                unset($_args[$k]);
            }
        }
    }

    return $_args;
}

/**
 * The default URI route to comments.php
 */
function serveComments() {
    global $serendipity;

    $serendipity['view'] = 'comments';
    $uri = $_SERVER['REQUEST_URI'];
    $args = serendipity_getUriArguments($uri, true); // Need to also match "." character
    $timedesc = array();

    /* Attempt to locate hidden variables within the URI */
    $_args = locateHiddenVariables($args);
    foreach($_args AS $k => $v) {
        if ($v == PATH_COMMENTS) {
            $serendipity['GET']['commentMode'] = $v;
            continue;
        }

        if (preg_match('@^(last|f|t|from|to)[\s_\-]*([\d\-/ ]+)$@', strtolower(urldecode($v)), $m)) {
            if ($m[1] == 'last') {
                $usetime = time() - ($m[2]*86400); // this means in the last x days, eg /last_5/ = time() - 432000, NOT equally last x comments ! Oh Oh, Garv!
                $serendipity['GET']['commentStartTime'] = $usetime;
                $timedesc['start'] = serendipity_strftime(DATE_FORMAT_SHORT, $usetime);
                continue;
            }

            $date = strtotime($m[2]);
            if ($date < 1) {
                continue;
            }

            if ($m[1] == 'f' || $m[1] == 'from') {
                $serendipity['GET']['commentStartTime'] = $date;
                $timedesc['start'] = serendipity_strftime(DATE_FORMAT_SHORT, $date);
            } else {
                $serendipity['GET']['commentEndTime'] = $date;
                $timedesc['end'] = serendipity_strftime(DATE_FORMAT_SHORT, $date);
            }
        } elseif (in_array($v, ['trackbacks', 'pingbacks', 'comments_and_trackbacks', 'comments'])) {
            $serendipity['GET']['commentMode'] = $v;
        } elseif (!empty($v)) {
            $serendipity['GET']['viewCommentAuthor'] = $serendipity['GET']['viewCommentAuthor'] ?? '';
            $serendipity['GET']['viewCommentAuthor'] .= urldecode($v);
        }
    }

    $serendipity['head_title'] = COMMENTS_FROM . ' ' . serendipity_specialchars(($serendipity['GET']['viewCommentAuthor'] ?? ''));
    if (isset($timedesc['start']) && isset($timedesc['end'])) {
        $serendipity['head_title'] .= ' (' . $timedesc['start'] . ' - ' . $timedesc['end'] . ')';
    } elseif (isset($timedesc['start'])) {
        $serendipity['head_title'] .= ' (&gt; ' . $timedesc['start'] . ')';
    } elseif (isset($timedesc['end'])) {
        $serendipity['head_title'] .= ' (&lt; ' . $timedesc['end'] . ')';
    }
    $serendipity['head_subtitle'] = $serendipity['blogTitle'];
    $serendipity['GET']['action'] = 'comments';

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to serve virtual javascript
 */
function serveJS($js_mode) {
    global $serendipity;

    $serendipity['view'] = 'js';

    header('Cache-Control:');
    header('Pragma:');
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time()+3600));
    header('Content-type: application/javascript; charset=' . LANG_CHARSET);

    $out = '';

    // We only need this for frontend actions, eg. "2k11" pushing js into serendipity.js. The backend js does well without calling genpage here.
    if (!defined('IN_serendipity_admin')) {
        // Set action 'empty' will break genpage GET action loop to nothing,
        // leaving it empty will unnecessarily run serendipity_printEntries().
        $serendipity['GET']['action'] = 'empty';
        include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
    }

    if ($js_mode == 'serendipity_admin.js') {
        serendipity_plugin_api::hook_event('js_backend', $out);
    } else {
        serendipity_plugin_api::hook_event('js', $out);
    }
    echo $out;
}

/**
 * The default URI route to serve virtual stylesheets
 */
function serveCSS($css_mode) {
    global $serendipity;

    serendipity_smarty_init();
    $serendipity['view'] = 'css';

    include(S9Y_INCLUDE_PATH . 'serendipity.css.php');
}

/**
 * The default URI route to serve search requests with uriArguments
 */
function serveSearch() {
    global $serendipity;

    $serendipity['view'] = 'search';
    $_args = $serendipity['uriArguments'];

    /* Attempt to locate hidden variables within the URI */
    $search = array();
    foreach($_args AS $k => $v) {
        if ($v == PATH_SEARCH) {
            continue;
        }

        if ($k === array_key_last($_args) && false !== preg_match('@P\d+@', $v)) { /* Page */
            $page = substr($v, 1);
            // check for someone is willingly trying to break Serendipity by adding page orders > P2500.., which could result in breaking db limits - so we set a hard page limit
            if ($page > $serendipity['max_page_limit']) {
                return $_args;
            }
            if (is_numeric($page)) {
                $serendipity['GET']['page'] = $page;
                unset($_args[$k]);
                unset($serendipity['uriArguments'][$k]);
            } else {
                $search[] = $v;
            }
        } else {
            $search[] = $v;
        }
    }

    $serendipity['GET']['action']     = 'search';
    $serendipity['GET']['searchTerm'] = urldecode(serendipity_specialchars(strip_tags(implode(' ', $search))));

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to serve virtual author page/entry requests with uriArguments
 */
function serveAuthorPage($matches, $is_multiauth=false) {
    global $serendipity;

    $serendipity['view'] = 'authors';
    unset($serendipity['uInfo']); // see below

    if ($is_multiauth) {
        $serendipity['GET']['viewAuthor'] = serendipity_specialchars(implode(';', $serendipity['POST']['multiAuth']));
        $serendipity['uriArguments'][]    = PATH_AUTHORS;
        $serendipity['uriArguments'][]    = serendipity_db_escape_string($serendipity['GET']['viewAuthor']) . '-multi';
    } elseif (empty($matches[1]) && preg_match('@'.PATH_AUTHORS.'/([0-9;]+)@', $uri, $multimatch)) {
        $is_multiauth = true;
        $serendipity['GET']['viewAuthor'] = $multimatch[1];
    } else {
        $serendipity['GET']['viewAuthor'] = $matches[1];
    }

    $serendipity['GET']['action'] = 'read';

    $_args = locateHiddenVariables($serendipity['uriArguments']);

    if (!$is_multiauth) {
        $matches[1] = serendipity_searchPermalink($serendipity['permalinkAuthorStructure'], implode('/', $_args), ($matches[1] ?? ''), 'author');
        $serendipity['GET']['viewAuthor'] = $matches[1];
        $serendipity['GET']['action'] = 'read';
    }

    $uInfo = serendipity_fetchUsers($serendipity['GET']['viewAuthor']);
    $serendipity['uInfo'][0] = ['realname' => $uInfo[0]['realname'], 'username' => $uInfo[0]['username'], 'email' => $uInfo[0]['email']]; // Selected give-away for userprofiles since $GLOBALS['uInfo'] is gone in 'entries_header'

    if (!is_array($uInfo)) {
        $serendipity['view'] = '404';
        $serendipity['viewtype'] = '404_3';

        header('HTTP/1.0 404 Not found');
        header('Status: 404 Not found');
    } else {
        $serendipity['head_title']    = sprintf(ENTRIES_BY, $uInfo[0]['realname']);
        $serendipity['head_subtitle'] = $serendipity['blogTitle'];
    }

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to serve virtual category page/entry requests with uriArguments
 */
function serveCategory($matches, $is_multicat=false) {
    global $serendipity;

    $serendipity['view'] = 'categories';
    $uri = $_SERVER['REQUEST_URI'];

    if ($is_multicat) {
        if (isset($serendipity['POST']['isMultiCat']) && $serendipity['POST']['isMultiCat'] != RESET_FILTERS) {
            $serendipity['GET']['category'] = serendipity_specialchars(implode(';', $serendipity['POST']['multiCat']));
        } else {
            $serendipity['GET']['category'] = '';
        }
        $serendipity['uriArguments'][]  = PATH_CATEGORIES;
        if (!empty($serendipity['GET']['category'])) {
            $serendipity['uriArguments'][]  = serendipity_db_escape_string($serendipity['GET']['category']) . '-multi';
        }
    } elseif (preg_match('@/([0-9;]+)@', $uri, $multimatch)) {
        if (stristr($multimatch[1], ';')) {
            $is_multicat = true;
            $serendipity['GET']['category'] = $multimatch[1];
        }
    }

    $serendipity['GET']['action'] = 'read';

    $_args = locateHiddenVariables($serendipity['uriArguments']);

    if (!$is_multicat) {
        $matches[1] = serendipity_searchPermalink($serendipity['permalinkCategoryStructure'], implode('/', $_args), ($matches[1] ?? ''), 'category');
        $serendipity['GET']['category'] = $matches[1];
    }
    $cInfo = serendipity_fetchCategoryInfo($serendipity['GET']['category']); // category already secured to be an integer only

    if (!is_array($cInfo)) {
        $serendipity['view'] = '404';
        $serendipity['viewtype'] = '404_2';

        header('HTTP/1.0 404 Not found');
        header('Status: 404 Not found');
    } else {
        $serendipity['head_title'] = $cInfo['category_name'];
        if (isset($serendipity['GET']['page'])) {
            $serendipity['head_title'] .= ' - ' . serendipity_specialchars($serendipity['GET']['page']);
        }
        $serendipity['head_subtitle'] = $serendipity['blogTitle'];
    }

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to serve virtual archive entry requests with uriArguments
 */
function serveArchive() {
    global $serendipity;

    $serendipity['view'] = 'archive';
    $serendipity['GET']['action'] = 'archives';

    locateHiddenVariables($serendipity['uriArguments']);

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to redirect to the backend entry page by allowed shortcuts
 */
function gotoAdmin() {
    global $serendipity;

    $base = $serendipity['baseURL'];
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
        $base = str_replace('http://', 'https://', $base);
    }
    header('Status: 302 Found');
    header("Location: {$base}serendipity_admin.php");
}

/**
 * The default URI route to redirect plugin external_plugin Args
 */
function servePlugin($matches) {
    global $serendipity;

    $serendipity['view'] = 'plugin';

    serendipity_plugin_api::hook_event('external_plugin', $matches[2]);
}

/**
 * The default URI route to rss.php to serve feeds
 */
function serveFeed($matches) {
    global $serendipity;

    $serendipity['view'] = 'feed';
    header('Content-Type: text/html; charset=utf-8');
    $uri = $_SERVER['REQUEST_URI'];

    if (preg_match('@/(index|atom[0-9]*|rdf|rss|comments|trackbacks|comments_and_trackbacks|opml)\.(rss[0-9]?|rdf|rss|xml|atom)@', $uri, $vmatches)) {
        list($_GET['version'], $_GET['type']) = serendipity_discover_rss($vmatches[1], $vmatches[2]);
    }

    if (is_array($matches)) {
        if (preg_match('@(/?' . preg_quote(PATH_FEEDS, '@') . '/)(.+?)(?:\.rss)?$@i', $uri, $uriparts)) {
            if (strpos($uriparts[2], $serendipity['permalinkCategoriesPath']) === 0) {
                $catid = serendipity_searchPermalink($serendipity['permalinkFeedCategoryStructure'], $uriparts[2], ($matches[1] ?? ''), 'category');
                if (is_numeric($catid) && $catid > 0) {
                    $_GET['category'] = $catid;
                }
            } elseif (strpos($uriparts[2], $serendipity['permalinkAuthorsPath']) === 0) {
                $authorid = serendipity_searchPermalink($serendipity['permalinkFeedAuthorStructure'], $uriparts[2], ($matches[1] ?? ''), 'author');
                if (is_numeric($authorid) && $authorid > 0) {
                    $_GET['viewAuthor'] = $authorid;
                }
            }
        }
    }

    include(S9Y_INCLUDE_PATH . 'rss.php');
}

/**
 * The default URI route to serve entry requests
 */
function serveEntry($matches) {
    global $serendipity;

    $serendipity['view'] = 'entry';
    $uri = $_SERVER['REQUEST_URI'];

    if (isset($serendipity['GET']['id'])) {
        $matches[1] = (int)$serendipity['GET']['id'];
    } elseif (isset($_GET['p'])) {
        $matches[1] = $_GET['p'];
    } else {
        $matches[1] = serendipity_searchPermalink($serendipity['permalinkStructure'], $uri, (!empty($matches[2]) ? $matches[2] : $matches[1]), 'entry');
    }
    serendipity_rememberComment();

    if (!empty($serendipity['POST']['submit']) && !isset($_REQUEST['serendipity']['csuccess'])) {

        $comment['url']       = $serendipity['POST']['url'];
        $comment['comment']   = trim((string)$serendipity['POST']['comment']);
        $comment['name']      = $serendipity['POST']['name'];
        $comment['email']     = $serendipity['POST']['email'];
        $comment['subscribe'] = $serendipity['POST']['subscribe'] ?? null;
        $comment['parent_id'] = $serendipity['POST']['replyTo'];

        if (!empty($comment['comment'])) {
            if (serendipity_saveComment($serendipity['POST']['entry_id'], $comment, 'NORMAL')) {
                // $serendipity['last_insert_comment_id'] used for for comment added messaging
                $sc_url = ($_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . (strstr($_SERVER['REQUEST_URI'], '?') ? '&' : '?') . 'serendipity[csuccess]=' . ($serendipity['csuccess'] ?? 'true') . '&last_insert_cid=' . ($serendipity['last_insert_comment_id'] ?? '') . '#feedback';
                unset($serendipity['last_insert_comment_id']); // remove the temporary global, set in function serendipity_saveComment
                if (serendipity_isResponseClean($sc_url)) {
                    header('Status: 302 Found');
                    header('Location: ' . $sc_url);
                }
                exit;
            } else {
                $serendipity['messagestack']['comments'][] = COMMENT_NOT_ADDED;
            }
        } else {
            $serendipity['messagestack']['comments'][] = sprintf(EMPTY_COMMENT, '', '');
        }
    }

    $id = (int)$matches[1];
    if ($id === 0) {
        $id = false;
    }

    $_GET['serendipity']['action'] = 'read';
    $_GET['serendipity']['id']     = $id;

    $title = serendipity_db_query("SELECT title FROM {$serendipity['dbPrefix']}entries WHERE id=$id AND isdraft = 'false' " . (!serendipity_db_bool($serendipity['showFutureEntries']) ? ' AND timestamp <= ' . serendipity_db_time() : ''), true);
    if (is_array($title)) {
        $serendipity['head_title']    = serendipity_specialchars($title[0]);
        $serendipity['head_subtitle'] = serendipity_specialchars($serendipity['blogTitle']);
    } else {
        $serendipity['view'] = '404';
        $serendipity['viewtype'] = '404_1';

        header('HTTP/1.0 404 Not found');
        header('Status: 404 Not found');
        // here we give back an empty entries array and else print out the "no entries available" message in theme
    }

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');
}

/**
 * The default URI route to serve virtual archives requests
 */
function serveArchives() {
    global $serendipity;

    $serendipity['view'] = 'archives';

    $_args = locateHiddenVariables($serendipity['uriArguments']);
    foreach($_args AS $k => $v) {
        if ($v[0] == 'W') { /* Week */
            $week = substr($v, 1);
            if (is_numeric($week)) {
                unset($_args[$k]);
            }
        }
    }

    if (!isset($_args[1])) $_args[1] = null; // PHP 8 fix for key 1 for range paging
    if (!isset($_args[2])) $_args[2] = null; // PHP 8 fix for key 2 for range paging
    if (!isset($_args[3])) $_args[3] = null; // PHP 8 fix for key 3 for archives listing

    /* We must always *assume* that Year, Month and Day are the first 3 arguments */
    list(,$year, $month, $day) = $_args; // keep empty param, is 'archives'

    $serendipity['GET']['action']     = 'read';
    $serendipity['GET']['hidefooter'] = true;

    $_utime = false; // no UNIX start range - build real timestamp ranges
    if (is_null($year) && is_null($month) && is_null($day) && isset($serendipity['uriArguments'][1]) && $serendipity['uriArguments'][1] === 'summary') {
        $_utime = true;
    }

    // sets a default case
    if (!isset($year) && !$_utime) {
        $year = date('Y');
        $month = date('m');
        $day = date('j');
        $serendipity['GET']['action']     = null;
        $serendipity['GET']['hidefooter'] = null;
    }

    if (isset($year) && !is_numeric($year)) {
        $year = date('Y');
    }

    if (isset($month) && !is_numeric($month)) {
        $month = date('m');
    }

    if (isset($day) && !is_numeric($day)) {
        $day = date('d');
    }

    switch($serendipity['calendar']) {
        case 'gregorian':
        default:
            $gday = 1;

            if (isset($week)) {
                $tm = strtotime('+ '. ($week-2) .' WEEKS monday', mktime(0, 0, 0, 1, 1, (int) $year));
                $ts = mktime(0, 0, 1, date('m', $tm), date('j', $tm), (int) $year);
                $te = mktime(23, 59, 59, date('m', $tm), date('j', $tm)+7, (int) $year);
                $date = serendipity_formatTime(WEEK .' '. $week .', %Y', $ts, false);
            } else {
                // all entry summary order only
                if ($_utime === true) {
                    $ts = mktime(1, 0, 1, 1, 1, 1970); // like DateTimeInterface constants: 1970-01-01T01:00:00+01:00 will give us a start $dateRange key 0 = 1
                    $te = time();
                    $date = 'alltime';
                    $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique
                } else {
                    // we have a full date day order OR either the default case for current date archives ie. /archives/P2.html OR 'C' (category), 'A' (author) alike archives pages
                    if ($day) {
                        $ts = mktime(0, 0, 1, (int) $month, (int) $day, (int) $year);
                        $te = mktime(23, 59, 59, (int) $month, (int) $day, (int) $year);
                        $date = serendipity_formatTime(DATE_FORMAT_ENTRY, $ts, false);
                    // we have a year order only AND this can be either /archives/2018/summary.html OR /archives/2018.html
                    } elseif ($year && !isset($month)) {
                        $ts = mktime(0, 0, 1, 1, 1, (int) $year);
                        $te = mktime(23, 59, 59, 12, 31, (int) $year);
                        $date = $year;
                        $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique - reset by case in genpage
                    // we have a month & year only order
                    } else {
                        $ts = mktime(0, 0, 1, (int) $month, (int) $gday, (int) $year);
                        if (!isset($gday2)) {
                            $gday2 = date('t', $ts);
                        }
                        $te = mktime(23, 59, 59, (int) $month, (int) $gday2, (int) $year);
                        $date = serendipity_formatTime('%B %Y', $ts, false);
                        $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique
                    }
                }
            }
            break;

        case 'persian-utf8':
            require_once S9Y_INCLUDE_PATH . 'include/functions_calendars.inc.php';
            $gday = 1;

            if (isset($week)) {
                --$week;
                $week *= 7;
                ++$week;
                $day = $week;

                // convert day number of year to day number of month AND month number of year
                $j_days_in_month = array(0, 31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
                $g_y = date('Y', time());
                if (($g_y % 4) == 3) $j_days_in_month[12]++;

                for($i=1; isset($j_days_in_month[$i]); ++$i) {
                    if (($day-$j_days_in_month[$i]) > 0) {
                        $day -= $j_days_in_month[$i];
                    } else {
                        break;
                    }
                }

                $tm = persian_mktime(0, 0, 0, $i, $day, $year);
                $ts = persian_mktime(0, 0, 1, persian_date_utf('m', $tm), persian_date_utf('j', $tm), $year);
                $te = persian_mktime(23, 59, 59, persian_date_utf('m', $tm), persian_date_utf('j', $tm)+7, $year);
                $date = serendipity_formatTime(WEEK .' '. $week .'، %Y', $ts, false);
            } else {
                // all entry summary order only
                if ($_utime === true) {
                    $ts = persian_mktime(1, 0, 1, 1, 1, 1970); // like DateTimeInterface constants: 1970-01-01T01:00:00+01:00 will give us a start $dateRange key 0 = 1 - unknown for me with persian though
                    $te = time();
                    $date = 'alltime';
                    $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique
                } else {
                    // we have a full date day order OR either the default case for current date archives ie. /archives/P2.html OR 'C' (category), 'A' (author) alike archives pages
                    if ($day) {
                        $ts = persian_mktime(0, 0, 1, $month, $day, $year);
                        $te = persian_mktime(23, 59, 59, $month, $day, $year);
                        $date = serendipity_formatTime(DATE_FORMAT_ENTRY, $ts, false);
                    // we have a year order only AND this can be either /archives/2018/summary.html OR /archives/2018.html
                    } elseif ($year && !isset($month)) {
                        $ts = persian_mktime(0, 0, 1, 1, 1, $year);
                        $te = persian_mktime(23, 59, 59, 12, 31, $year);
                        $date = $year;
                        $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique - reset by case in genpage
                    // we have a month & year only order
                    } else {
                        $ts = persian_mktime(0, 0, 1, (int) $month, (int) $gday, (int) $year);
                        if (!isset($gday2)) {
                            $gday2 = persian_date_utf('t', $ts);
                        }
                        $te = persian_mktime(23, 59, 59, (int) $month, (int) $gday2, (int) $year);
                        $date = serendipity_formatTime('%B %Y', $ts, false);
                        $serendipity['summaryFetchLimit'] = $serendipity['fetchLimit'] != 25 ? 25 : 24; // check case to make it independently unique
                    }
                }
            }

            list($year, $month, $day) = p2g($year, $month, $day);
            break;
    }

    $serendipity['range'] = array($ts, $te);

    if ($serendipity['GET']['action'] == 'read') {
        if (isset($serendipity['GET']['category'])) {
            $cInfo = serendipity_fetchCategoryInfo($serendipity['GET']['category']); // category already secured to be an integer only
            $serendipity['head_title'] = $cInfo['category_name'];
        }
        if ($date == 'alltime') {
            $serendipity['head_subtitle'] .= ALL_ENTRIES;
        } else {
            $serendipity['head_subtitle'] .= sprintf(ENTRIES_FOR, $date);
        }
    }

    include(S9Y_INCLUDE_PATH . 'include/genpage.inc.php');

}
