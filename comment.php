<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

# Developer
#if ($_REQUEST['type'] == 'trackback') die('Disabled');

include('serendipity_config.inc.php');
include S9Y_INCLUDE_PATH . 'include/functions_entries_admin.inc.php';

header('Content-Type: text/html; charset=' . LANG_CHARSET);

if (isset($serendipity['GET']['delete'], $serendipity['GET']['entry'], $serendipity['GET']['type'])) {
    serendipity_deleteComment($serendipity['GET']['delete'], $serendipity['GET']['entry'], $serendipity['GET']['type'], $serendipity['GET']['token']);
    if (isset($_SERVER['HTTP_REFERER'])) {
        if (serendipity_isResponseClean($_SERVER['HTTP_REFERER']) && preg_match('@^https?://' . preg_quote($_SERVER['HTTP_HOST'], '@') . '@imsU', $_SERVER['HTTP_REFERER'])) {
            header('Status: 302 Found');
            header('Location: '. $_SERVER['HTTP_REFERER']);
        }
    }
    exit;
}

if (isset($serendipity['GET']['switch'], $serendipity['GET']['entry'])) {
    serendipity_allowCommentsToggle($serendipity['GET']['entry'], $serendipity['GET']['switch']);
}

if (!empty($_REQUEST['c']) && !empty($_REQUEST['hash'])) {
    $res = serendipity_confirmMail($_REQUEST['c'], $_REQUEST['hash']);
    $serendipity['view'] = 'notification';
    $serendipity['GET']['action'] = 'custom';
    $serendipity['smarty_custom_vars'] = array(
        'content_message'            => ($res ? NOTIFICATION_CONFIRM_MAIL : NOTIFICATION_CONFIRM_MAIL_FAIL),
        'subscribe_confirm_error'    => !$res,
        'subscribe_confirm_success'  => $res,
    );
    include S9Y_INCLUDE_PATH . 'include/genpage.inc.php';
    $serendipity['smarty']->display(serendipity_getTemplateFile('index.tpl', 'serendipityPath'));
    exit;
}

if (!empty($_REQUEST['optin'])) {
    $res = serendipity_commentSubscriptionConfirm($_REQUEST['optin']);
    $serendipity['view'] = 'notification';
    $serendipity['GET']['action'] = 'custom';
    $serendipity['smarty_custom_vars'] = array(
        'content_message'           => ($res ? NOTIFICATION_CONFIRM_SUBMAIL : NOTIFICATION_CONFIRM_SUBMAIL_FAIL),
        'subscribe_confirm_error'   => !$res,
        'subscribe_confirm_success' => $res,
    );
    include S9Y_INCLUDE_PATH . 'include/genpage.inc.php';
    $serendipity['smarty']->display(serendipity_getTemplateFile('index.tpl', 'serendipityPath'));
    exit;
}

serendipity_rememberComment();

// Trackback logging. For developers: can be switched to true!
$tb_logging = false;
// Pingback logging. For developers: can be switched to true!
$pb_logging = false;

if ($pb_logging && !empty($_SERVER['CONTENT_TYPE']) && !empty($HTTP_RAW_POST_DATA)) {
    log_pingback('CONTENT_TYPE: ' . $_SERVER['CONTENT_TYPE']);
    log_pingback('HTTP_RAW_POST_DATA: ' .  print_r($HTTP_RAW_POST_DATA, true));
}

if (!($type = @$_REQUEST['type'])) {
    if ($pb_logging) {
        log_pingback('NO TYPE HANDED!');
    }

    // WordPress pingbacks don't give any parameter. If it is a XML POST, assume it's a pingback
    if (isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] == 'text/xml' && !empty($HTTP_RAW_POST_DATA)) {
        $type = 'pingback';
    }
    else {
        $type = 'normal';
    }
}

if ($type == 'trackback') {
    if (!isset($_REQUEST['entry_id']) && !empty($_REQUEST['amp;entry_id'])) {
        $_REQUEST['entry_id'] = $_REQUEST['amp;entry_id'];
        unset($_REQUEST['amp;entry_id']);
    }

    if ($tb_logging) {
        log_trackback('[' . date('d.m.Y H:i') . '] RECEIVED TRACKBACK' . "\n");
        log_trackback('[' . date('d.m.Y H:i') . '] _REQUEST ' . print_r($_REQUEST, true) . "\n");
    }

    $uri = $_SERVER['REQUEST_URI'];
    if (!empty($_REQUEST['entry_id'])) {
        $id = (int)$_REQUEST['entry_id'];
    } else if (preg_match('@/(\d+)_[^/]*$@', $uri, $matches)) {
        $id = (int)$matches[1];
    }

    if ($tb_logging) {
        log_trackback('[' . date('d.m.Y H:i') . '] Match on ' . $uri . "\n");
        log_trackback('[' . date('d.m.Y H:i') . '] ID: ' . $id . "\n");
    }

    if (isset($id) && !empty($_REQUEST['url']) && !empty($_REQUEST['title']) && !empty($_REQUEST['blog_name']) && !empty($_REQUEST['excerpt']) && add_trackback($id, $_REQUEST['title'], $_REQUEST['url'], $_REQUEST['blog_name'], $_REQUEST['excerpt'])) {
        if ($tb_logging) {
            log_trackback('[' . date('d.m.Y H:i') . '] TRACKBACK SUCCESS' . "\n");
            log_trackback('---------------------------------------');
        }
        report_trackback_success();
    } else {
        if ($tb_logging) {
            log_trackback('[' . date('d.m.Y H:i') . '] TRACKBACK FAILURE' . "\n");
            log_trackback('---------------------------------------');
        }
        report_trackback_failure();
    }

} else if ($type == 'pingback') {
    if ($pb_logging) {
        log_pingback('RECEIVED PINGBACK');
        log_pingback('HTTP_RAW_POST_DATA: ' . print_r($HTTP_RAW_POST_DATA, true));
    }
    if (add_pingback($_REQUEST['entry_id'], $HTTP_RAW_POST_DATA)) {
        log_pingback('PINGBACK SUCCESS');
        log_pingback('---------------------------------------');
        report_pingback_success();
    } else {
        log_pingback('PINGBACK FAILURE');
        log_pingback('---------------------------------------');
        report_pingback_failure();
    }
} else {
    $id = (int)(!empty($serendipity['POST']['entry_id']) ? $serendipity['POST']['entry_id'] : $serendipity['GET']['entry_id']);
    $serendipity['head_subtitle'] = COMMENTS;
    $serendipity['smarty_file'] = 'commentpopup.tpl';
    serendipity_smarty_init();

    if ($id == 0) {
        return false;
    } else {
        $serendipity['smarty']->assign('entry_id', $id);
    }

    if (isset($_GET['success']) && $_GET['success'] == 'true') {
        $serendipity['smarty']->assign(
            array(
                'is_comment_added'   => true,
                'comment_url'        => serendipity_specialchars($_GET['url']) . '&amp;serendipity[entry_id]=' . $id,
                'comment_string'     => explode('%s', COMMENT_ADDED_CLICK)
            )
        );
    } else if (!isset($serendipity['POST']['submit'])) {
        if (isset($serendipity['GET']['type']) && $serendipity['GET']['type'] == 'trackbacks') {
            $query = "SELECT title, timestamp FROM {$serendipity['dbPrefix']}entries WHERE id = '". $id ."'";
            $entry = serendipity_db_query($query);
            $entry = serendipity_archiveURL($id, $entry[0]['title'], 'baseURL', true, array('timestamp' => $entry[0]['timestamp']));

            $serendipity['smarty']->assign(
                array(
                    'is_showtrackbacks' => true,
                    'comment_url'       => $serendipity['baseURL'] . 'comment.php?type=trackback&amp;entry_id=' . $id,
                    'comment_entryurl'  => $entry
                )
            );
        } else {
            /* see below, since we need the $entry array assigned for authors comment author_self comparison via commentpopup template file */
            $query = "SELECT e.id, e.author, e.authorid, a.email, e.last_modified, e.timestamp, e.allow_comments, e.moderate_comments
                        FROM {$serendipity['dbPrefix']}entries e
                   LEFT JOIN {$serendipity['dbPrefix']}authors a
                          ON (e.authorid = a.authorid)
                       WHERE e.id = '" . $id . "'";
            $ca    = serendipity_db_query($query, true);
            serendipity_plugin_api::hook_event('frontend_display:html:per_entry', $id);
            $_opentopublic = $serendipity['commentaire']['opentopublic'] ?? 0;
            if ($_opentopublic > 0) {
                $ftstamp = ((time() + 24*60*60) - $_opentopublic);
                if ($ca['timestamp'] < $ftstamp) {
                    $ca['allow_comments'] = false; // adds COMMENTS_CLOSED message
                }
            }
            if (!isset($serendipity['view'])) {
                $serendipity['view'] = 'comments';
            }
            if (is_bool($ca)) {
                $ca = ['allow_comments' => false, 'moderate_comments' => true];
            }
            $comment_allowed = (serendipity_db_bool($ca['allow_comments']) || !is_array($ca)) ? true : false;
            $serendipity['smarty']->assign(
                array(
                    'entry'              => $ca,
                    'is_showcomments'    => true,
                    'is_comment_allowed' => $comment_allowed
                )
            );

            if ($comment_allowed) {
                serendipity_displayCommentForm($id, '?', NULL, $serendipity['POST'], true, serendipity_db_bool($ca['moderate_comments']), $ca);
            }
        }
    } else {
        $comment['url']       = $serendipity['POST']['url'];
        $comment['comment']   = trim((string)$serendipity['POST']['comment']);
        $comment['name']      = $serendipity['POST']['name'];
        $comment['email']     = $serendipity['POST']['email'];
        $comment['subscribe'] = $serendipity['POST']['subscribe'] ?? '';
        $comment['parent_id'] = $serendipity['POST']['replyTo'];
        if (!empty($comment['comment'])) {
            if (serendipity_saveComment($id, $comment, 'NORMAL')) {
                $sc_url = $serendipity['baseURL'] . 'comment.php?serendipity[entry_id]=' . $id . '&success=true&url=' . urlencode($_SERVER['HTTP_REFERER']);
                if (serendipity_isResponseClean($sc_url)) {
                    header('Status: 302 Found');
                    header('Location: ' . $sc_url);
                }
                exit;
            } else {
                $serendipity['smarty']->assign(
                    array(
                        'is_comment_notadded' => true,
                        'comment_url'         => serendipity_specialchars($_SERVER['HTTP_REFERER']),
                        'comment_string'      => explode('%s', COMMENT_NOT_ADDED_CLICK)
                    )
                );
            }
        } else {
            $serendipity['smarty']->assign(
                array(
                    'is_comment_empty' => true,
                    'comment_url'      => serendipity_specialchars($_SERVER['HTTP_REFERER']),
                    'comment_string'   => explode('%s', EMPTY_COMMENT)
                )
            );
        }
    }

    $serendipity['smarty']->display(serendipity_getTemplateFile($serendipity['smarty_file'], 'serendipityPath'));
}

// See http://php.net/manual/en/function.fopen.php for possible fullpath issues
// Debug logging for pingback receiving
function log_pingback($message){
    global $pb_logging;
    if ($pb_logging) {
        $fp = fopen('pingback.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] ' . $message . "\n");
        fclose($fp);
    }
}
// Debug logging trackback receiving
function log_trackback($message){
    global $tb_logging;
    if ($tb_logging) {
        $fp = fopen('trackback.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] ' . $message . "\n");
        fclose($fp);
    }
}

/* vim: set sts=4 ts=4 expandtab : */
