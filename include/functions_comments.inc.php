<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

/**
 * Check if a comment token (ie. from comment notification email) is valid for a given comment id.
 *
 * @param string    The Token
 * @param int       The comment id
 * @access public
 * @return bool
 */
function serendipity_checkCommentToken($token, $cid) {
    global $serendipity;

    $goodtoken = false;
    if ($serendipity['useCommentTokens']) {
        // Delete any comment tokens older than 1 week.
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                               WHERE okey LIKE 'comment_%' AND name < " . (time() - 604800) );
        // Get the token for this comment id
        $tokencheck = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}options
                                             WHERE okey = 'comment_" . (int)$cid . "' LIMIT 1", true, 'assoc');
        // Verify it against the passed key
        if (is_array($tokencheck)) {
            if ($tokencheck['value'] == $token) {
                $goodtoken = true;  // use this to bypass security checks later
                // if using tokens, delete this comment from that list no matter how we got here
                serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                                       WHERE okey = 'comment_" . (int)$cid . "'");
            }
        }
    }

    return $goodtoken;
}

/**
 * Check if a comment token was submitted to the serendipity main framework.
 * This function can kill the workflow completely, if moderation was wanted.
 *
 * @param  string   The current base URI
 * @access public
 * @return null
 */
function serendipity_checkCommentTokenModeration($uri) {
    global $serendipity;

    // token based comment moderation starts here
    if ($serendipity['useCommentTokens'] === true && preg_match(PAT_DELETE, $uri, $res)) {
        $return_msg = "Error.\n";
        $tokenparse = explode('_', $res[1]);
        // check that we got a 32 char token
        if (is_array($tokenparse)) {
            if (strlen($tokenparse[2]) == 32) {
                if ($tokenparse[0] == 'comment') {
                    if (serendipity_deleteComment($res[2], $res[3], 'comments', $tokenparse[2])) {
                        $return_msg = sprintf(COMMENT_DELETED, $res[2])."\n";
                    } else {
                        $return_msg = sprintf(COMMENT_NOTOKENMATCH, $res[2])."\n";
                    }
                } elseif ($tokenparse[0] == 'trackback') {
                    if (serendipity_deleteComment($res[2], $res[3], 'trackbacks', $tokenparse[2])) {
                        $return_msg = sprintf(TRACKBACK_DELETED, $res[2])."\n";
                    } else {
                        $return_msg = sprintf(TRACKBACK_NOTOKENMATCH, $res[2])."\n";
                    }
                }
            } else {
                $return_msg = sprintf(BADTOKEN)."\n";
            }
            header('Content-Type: text/plain; charset='. LANG_CHARSET);
            die($return_msg);
        }
    }
    if ($serendipity['useCommentTokens'] === true && preg_match(PAT_APPROVE, $uri, $res)) {
        $return_msg = "Error.\n";
        $tokenparse = explode('_', $res[1]);
        // check that we got a 32 char token
        if (is_array($tokenparse)) {
            if (strlen($tokenparse[2]) == 32) {
                if ($tokenparse[0] == 'comment') {
                    if (serendipity_approveComment($res[2], $res[3], false, false, $tokenparse[2])) {
                        $return_msg = sprintf(COMMENT_APPROVED, $res[2])."\n";
                    } else {
                        $return_msg = sprintf(COMMENT_NOTOKENMATCH, $res[2])."\n";
                    }
                } elseif ($tokenparse[0] == 'trackback') {
                    if (serendipity_approveComment($res[2], $res[3], false, false, $tokenparse[2])) {
                        $return_msg = sprintf(TRACKBACK_APPROVED, $res[2])."\n";
                    } else {
                        $return_msg = sprintf(TRACKBACK_NOTOKENMATCH, $res[2])."\n";
                    }
                }
            } else {
                $return_msg = sprintf(BADTOKEN)."\n";
            }
            header('Content-Type: text/plain; charset='. LANG_CHARSET);
            die($return_msg);
        }
    }
}

/**
 * Store the personal details of a commenting user in a cookie (or delete that cookie)
 *
 * @access public
 * @return null
 */
function serendipity_rememberComment() {
    global $serendipity;

    if (isset($serendipity['POST']['remember'])) {
        serendipity_rememberCommentDetails(
          array(
            'url'      => $serendipity['POST']['url'],
            'name'     => $serendipity['POST']['name'],
            'email'    => $serendipity['POST']['email'],
            'remember' => 'checked="checked"'
          )
        );
    } elseif (isset($serendipity['POST']['comment'])) {
        serendipity_forgetCommentDetails(array('url', 'name', 'email', 'remember'));
    }
}

/**
 * Store all options of an array within a permanent cookie
 *
 * @access public
 * @param   array   input array
 * @return null
 */
function serendipity_rememberCommentDetails($details) {
    global $serendipity;

    foreach($details AS $n => $v) {
        serendipity_setCookie($n, $v);
    }
}

/**
 * Purge stored options from a permanent cookie
 *
 * LONG
 *
 * @access public
 * @param   array   Array of key names that shall be deleted inside cookies
 * @return null
 */
function serendipity_forgetCommentDetails($keys) {
    global $serendipity;

    if (!$serendipity['COOKIE']) {
        return;
    }

    foreach($keys AS $n) {
        serendipity_deleteCookie($n);
    }
}

/**
 * Sanitize the comments body to use entities inside <code>|<pre> tags for PLAIN EDITORs
 *
 * @param   string
 * @return  string  The sanitized string
 */
function serendipity_entityCommentCodeTagBlocks($str) {
    $code_callback = function($matches) {
        return '<code' . $matches[1] . '>' . serendipity_entities($matches[2], null, LANG_CHARSET, false) . '</code>';
    };
    $code_callback_pre = function($matches) {
        return '<pre><code' . $matches[1] . '>' . serendipity_entities($matches[2], null, LANG_CHARSET, false) . '</code></pre>';
    };
    $pre_callback = function($matches) {
        return '<pre' . $matches[1] . '><code>' . serendipity_entities($matches[2], null, LANG_CHARSET, false) . '</code></pre>';
    };
    if (false === stripos($str, '<code')) {
        return preg_replace_callback('#<pre(.*?)>(.*?)</pre>#', $pre_callback, $str);
    }
    if (false === stripos($str, '<pre')) {
        return preg_replace_callback("~<code(.*?)>(.*?)</code>~is", $code_callback_pre, $str);
    }
    if (false !== stripos($str, '<code')) {
        return preg_replace_callback("~<code(.*?)>(.*?)</code>~is", $code_callback, $str);
    }
    return $str;
}

/**
 * Sanitize (strips) the comments body for non-allowed HTML only
 * @param   string  The comments body string
 * @return  string  The sanitized string
 */
function serendipity_sanitizeHtmlComments($str) {
    global $serendipity;

    // sanitize and break code blocks which is done per CKEplus plugin independently or by manual inserts for PLAIN EDITORs. (Disable nl2br::comment parsing!)
    // Do not switch the Editor and save a comment using code examples again, since that my lead to hidden code parts due to the nature of processing the codesnippet CKE plugin
    if (empty($serendipity['wysiwyg']) && (strpos($str, '<code') !== false || strpos($str, '<pre') !== false)) {
        $str = serendipity_entityCommentCodeTagBlocks($str);
    }
    // this is still unparsed by nl2br yet
    // don't ever allow executable attributes, see HTML Injection Quick Reference (HIQR) https://mutantzombie.github.io/HIQR/hiqr.html
    $str = str_ireplace(array('javascript:', '<![CDATA[]]>', ']]>', '<--', '-->', 'onchange=', 'onmouseover=', 'onclick=', 'onerror=', 'onevent=', 'onfocus=', 'autofocus='), '', $str);
    // strip against allowed tags
    return strip_tags($str, '<div><p><span><b><strong><center><br><br/><h1><h2><h3><h4><h5><h6><hr><blockquote><em><u><s><sup><ul><ol><li><pre><code>');
}

/**
 * Prepare a comment for output under different conditions using plain text nl2nr or nl2p option
 * and handle code parts in string (in case again). This does not care about using WYSIWYG or not,
 * since it is used for comment archives (summary) views.
 * This is plain fetch data from database w/o having passed via NL2BR
 */
function serendipity_prepCommentNewline($string, $parsed=false) {
    // check for simple p-tag first - do not if true. This is/was a comment by ISOBR default.
    if (false === strpos($string, '</p>')) {
        $parsed = true;
    }
    // then check code parts within pre tags for nl2br plugin comment(true) option
    if ($parsed && preg_match_all('/<pre>(.*?)<\/pre>/s', $string, $matches)) {
        foreach($matches[1] AS $a) {
            $string = str_replace($a, str_replace("<br />", '', $a), $string);
        }
    }
    return $string;
}

/**
 * Check a comment being stripped for output under different conditions
 */
function serendipity_isCommentStripped($string, $stripped=false) {
    if (empty(trim($string))) {
        return true;
    } else {
        return $stripped;
    }
}

/**
 * Display the Comment form for entries
 *
 * @access public
 * @param   int     The EntryID to show the commentform for
 * @param   string  The URL that acts as the target of the HTML Form
 * @param   array   Array of existing comments to this entry
 * @param   array   Array of personal details data (i.e. from Cookie or POST input)
 * @param   boolean Toggle whether to show extended options of the comment form
 * @param   boolean Toggle whether comments to this entry are allowed
 * @param   array   The data of the entry that the comment is referring to
 * @return null
 */
function serendipity_displayCommentForm($id, $url = '', $comments = NULL, $data = NULL, $showToolbar = true, $moderate_comments = true, $entry = null) {
    global $serendipity;

    if ($comments === NULL) {
        if (empty($id)) {
            $comments = array();
        } else {
            $comments = serendipity_fetchComments($id);
        }
    }
    // Do not run on $comments(true) the Frontend entries case @L~1400
    if (is_array($comments)) {
        // this are all comments data of this entry. $_commentform_replyTo is the parent ID of this current answer.
        // fetch all current possible comment ids for this current answer
        foreach ($comments AS $comment) {
            if (isset($data['id']) && $comment['id'] != $data['id']) {
                $entry_comment_parents[] = array('id' => $comment['id'], 'name' => str_replace(array('[', ']', '(', ')', '\'', '"'), '', serendipity_specialchars(substr($comment['author'], 0, 16))));
            }
        }
        if (isset($entry_comment_parents)) {
            $entry_comment_parents[] = 0; // add a parent 0 ID
            sort($entry_comment_parents);
        }
    }

    // First is Backend only, since it is either simple parentID OR 0 - the 2cd is the generated HTML selection dropdown for the Frontend
    $_commentform_replyTo = (defined('IN_serendipity_admin') && IN_serendipity_admin === true && isset($data['replyTo']))
        ? $data['replyTo']
        : serendipity_generateCommentList($id, $comments, (!empty($data['replyTo']) ? $data['replyTo'] : 0)); // last ternary is only for Frontend comment preview cases

    $commentform_data = array(
        'commentform_action'         => $url,
        'commentform_id'             => (int)$id,
        'commentform_name'           => isset($data['name'])      ? serendipity_specialchars($data['name'])    : (isset($serendipity['COOKIE']['name'])     ? serendipity_specialchars($serendipity['COOKIE']['name']) : ''),
        'commentform_email'          => isset($data['email'])     ? serendipity_specialchars($data['email'])   : (isset($serendipity['COOKIE']['email'])    ? serendipity_specialchars($serendipity['COOKIE']['email']) : ''),
        'commentform_url'            => isset($data['url'])       ? serendipity_specialchars($data['url'])     : (isset($serendipity['COOKIE']['url'])      ? serendipity_specialchars($serendipity['COOKIE']['url']) : ''),
        'commentform_remember'       => isset($data['remember'])  ? ' checked="checked"'                       : (isset($serendipity['COOKIE']['remember']) ? ' checked="checked"' : ''),
        'commentform_replyTo'        => $_commentform_replyTo,
        'commentform_changeReplyTo'  => !empty($entry_comment_parents) ? $entry_comment_parents : null,
        'commentform_subscribe'      => isset($data['subscribe']) ? ' checked="checked"' : '',
        'commentform_data'           => isset($data['comment'])   ? serendipity_specialchars($data['comment']) : '',
        'is_commentform_showToolbar' => $showToolbar,
        'is_allowSubscriptions'      => (serendipity_db_bool($serendipity['allowSubscriptions']) || $serendipity['allowSubscriptions'] === 'fulltext' ? true : false),
        'is_moderate_comments'       => $moderate_comments,
        'commentform_entry'          => $entry
    );

    $serendipity['smarty']->assign($commentform_data);

    if (!isset($serendipity['commentaire']['killswitch'])) {
        serendipity_smarty_fetch('COMMENTFORM', 'commentform.tpl');
    } else {
        $serendipity['smarty']->assign('COMMENTFORM', '<div class="serendipity_msg_important">' . PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH . '</div>');
    }
}

/**
 * Fetch an array of comments to a specific entry id
 *
 * @access public
 * @param   int     The Entry ID to fetch comments for
 * @param   int     How many comments to fetch (empty: all)
 * @param   string  How shall comments be ordered (ASC|DESC)
 * @param   boolean Shall non-approved comments be displayed?
 * @param   string  Comment type to fetch
 * @return  array   The SQL result of comments
 */
function serendipity_fetchComments($id, $limit = null, $order = '', $showAll = false, $type = 'NORMAL', $where = '') {
    global $serendipity;

    $and = '';

    if (!empty($limit)) {
        $limit = serendipity_db_limit_sql($limit);
    } else {
        $limit = '';
    }

    if ($type == 'comments' || $type == 'NORMAL' || empty($type)) {
        $type = 'NORMAL';
    } elseif ($type == 'trackbacks') {
        $type = 'TRACKBACK';
    } elseif ($type == 'pingbacks') {
        $type = 'PINGBACK';
    } elseif ($type == 'comments_and_trackbacks') {
        $type = '%';
    } else {
        $type = '';
    }

    if (empty($type)) {
        return false;
    }

    if (!empty($id)) {
        $and .= " AND co.entry_id = '" . (int)$id ."'";
    }

    if (!$showAll) {
        $and .= ' AND co.status = \'approved\'';
    }

    $and .= $where;

    if ($serendipity['dbType'] == 'postgres' ||
        $serendipity['dbType'] == 'pdo-postgres') {
        $group    = '';
        $distinct = 'DISTINCT';
    } else {
        $group    = 'GROUP BY co.id';
        $distinct = '';
    }

    $cond = array();
    serendipity_plugin_api::hook_event('frontend_fetchcomments', $cond, array('source' => 'comments'));
    $cond['joins'] = $cond['joins'] ?? '';
    $cond['and']   = $cond['and'] ?? '';
    if (!empty($cond['and'])) {
        $and = str_replace(' AND 1', '', $and); // Remove serendipity_printCommentsByAuthor() required faked 'where' condition
        $where = empty($and) ? ' WHERE ' : ' AND ';
    }
    $and .=  $where . $cond['and'];
    //echo $and;

    // KEEP IN MIND:
    // 1. entries author == loginname, but the authors realname is the one used for public !!
    // 2. For comment summary pages the $entry array for $entry.email was not available by Smarty scope, so
    //      - instead of workaround this issue in serendipity_printCommentsByAuthor() -
    //      we just JOIN the REAL authors email for the post comment pc-owner selector right away into the comments array here.
    $query = "SELECT $distinct
                    co.id,
                    co.entry_id, co.timestamp, co.title AS ctitle, co.email, co.url, co.ip, co.body, co.type, co.subscribed,
                    co.author,
                    e.title,
                    e.timestamp AS entrytimestamp,
                    e.id AS entryid,
                    e.authorid,
                    a.realname AS entryauthor,
                    a.email AS authoremail,
                    co.id AS commentid,
                    co.parent_id AS parent_id,
                    co.status
              FROM
                    {$serendipity['dbPrefix']}comments AS co
                    LEFT JOIN {$serendipity['dbPrefix']}entries AS e ON (co.entry_id = e.id)
                    LEFT JOIN {$serendipity['dbPrefix']}authors AS a ON (e.authorid = a.authorid AND e.author = a.username)
                    {$cond['joins']}
              WHERE co.type LIKE '" . $type . "' AND co.entry_id > 0 $and
              $group
              ORDER BY
                    " . (empty($order) ? 'co.id' : $order) . "
                    $limit";
    // DEBUG:
    // die($query);
    $comments = serendipity_db_query($query, false, 'assoc');

    if (!is_array($comments)) {
        $comments = array();
    }

    $addData = array('id' => $id, 'limit' => $limit, 'order' => $order, 'showAll' => $showAll, 'type' => $type, 'where' => $where);
    serendipity_plugin_api::hook_event('fetchcomments', $comments, $addData);

    return $comments;
}

/**
 * Create a HTML SELECT dropdown field which represents all hierarchical comments
 *
 * @access public
 * @param   int     The entry ID to show comments for
 * @param   array   The existing comments for this entry
 * @param   int     The ID of the comment that is being referred to (last selection)
 * @param   int     The parent ID of the last comment [for recursive usage]
 * @param   int     The current nesting/hierarchy level [for recursive usage]
 * @param   string  The HTML indention string that gets preceded to a comment [for recursive usage]
 * @return  string  The HTML SELECT code
 */
function serendipity_generateCommentList($id, $comments = NULL, $selected = 0, $parent = 0, $level = 0, $indent = '') {
    global $serendipity;

    if (!is_array($comments)) {
        if (empty($id)) {
            $comments = array();
        } else {
            $comments = serendipity_fetchComments($id);
        }
    }

    $retval = $parent ? '' : '<select id="serendipity_replyTo" onchange="' . (!empty($serendipity['plugindata']['onchange']) ? $serendipity['plugindata']['onchange'] : '') . '" name="serendipity[replyTo]">'."\n".'<option value="0">[ ' . TOP_LEVEL . ' ]</option>'."\n";

    $i = 0;
    foreach($comments AS $comment) {
        if ($comment['parent_id'] == $parent) {
            $i++;
            $retval .= '<option value="' . $comment['id'] . '"'. ($selected == $comment['id'] || (isset($serendipity['POST']['replyTo']) && $comment['id'] == $serendipity['POST']['replyTo']) ? ' selected="selected"' : '') .'>' . str_repeat('&#160;', $level * 2) . '#' . $indent . $i . ': ' . (empty($comment['author']) ? ANONYMOUS : serendipity_specialchars($comment['author'])) . ' ' . ON . ' ' . serendipity_mb('ucfirst', serendipity_strftime(DATE_FORMAT_SHORT, $comment['timestamp'])) . "</option>\n";
            $retval .= serendipity_generateCommentList($id, $comments, $selected, $comment['id'], $level + 1, $indent . $i . '.');
        }
    }
    $retval .= $parent ? '' : '</select>';

    return $retval;
}

/**
 * Print a list of comments to an entry
 *
 * @access public
 * @param   array       The list of comments to display
 * @param   int         The parentID of a comment to show. Can contain the constant for VIEWMODE_THREADED/LINEAR. [recursive usage]
 * @param   int         The current nesting depth of a comment [recursive usage]
 * @param   string      A string representing the actual comment (1.1.2.1)
 * @param   string      A string representing the Smarty block - default: COMMENTS | empty: trackbacks | NULL no smarty_file load
 * @param   string      A string representing the frontend template file to fetch
 * @return  string      The HTML construct of all comments
 */
function serendipity_printComments($comments, $parentid = 0, $depth = 0, $trace = null, $smarty_block = 'COMMENTS', $smarty_file = 'comments.tpl') {
    global $serendipity;
    static $_smartyComments;

    /* - $_smartyComments holds the ending Smarty array.
       - $depth is the current depth of the recurrence.
       - $i is the position in the current depth. */

    if ($parentid === VIEWMODE_THREADED) {
        $parentid = 0;
    }

    /* Wait a second, we just got attacked by a call with level 0,
       this must mean we've started over */
    if ($depth == 0) {
        $_smartyComments = array();
    }

    if (!isset($serendipity['allowHtmlComment'])) $serendipity['allowHtmlComment'] = false;

    $i = 0;
    foreach($comments AS $comment) {
        if ($parentid === VIEWMODE_LINEAR || !isset($comment['parent_id']) || $comment['parent_id'] == $parentid) {
            $i++;

            if ($serendipity['allowHtmlComment']) {
                $comment['comment'] = serendipity_sanitizeHtmlComments((string)$comment['body']); // cast as string (for PREVIEW modes only)
            } else {
                $comment['comment'] = serendipity_specialchars(strip_tags((string)$comment['body'])); // cast as strings (for PREVIEW mode only)
            }
            $comment['url'] = strip_tags((string)$comment['url']); // via serendipity_smarty_printComments() to not error strip sanitizers
            if (isset($comment['id'])) {
                $comment['link_delete'] = $serendipity['baseURL'] . 'comment.php?serendipity[delete]=' . $comment['id'] . '&amp;serendipity[entry]=' . $comment['entry_id'] . '&amp;serendipity[type]=comments&amp;' . serendipity_setFormToken('url');
            }

            /* Fix invalid cases in protocol part */
            if (!empty($comment['url'])) {
                $comment['url'] = preg_replace('@^http://@i', 'http://', $comment['url']);
                $comment['url'] = preg_replace('@^https://@i', 'https://', $comment['url']);
            }
            /* Fix fucked links */
            if (!empty($comment['url']) && substr($comment['url'], 0, 7) != 'http://' && substr($comment['url'], 0, 8) != 'https://') {
                $comment['url'] = 'http://' . $comment['url'];
            }

            if (!empty($comment['url'])) {
                if (!@parse_url($comment['url'])) {
                    $comment['url'] = '';
                }
                $comment['url'] = serendipity_specialchars($comment['url'], ENT_QUOTES);
            }

            // Since this is a looped setting, destroy (a possible - see below) global transported var for the hook and follow-up comments
            if ($serendipity['allowHtmlComment'] && isset($serendipity['comment_dismarkup_temp'])) {
                unset($serendipity['POST']['properties']['disable_markups']);
                unset($serendipity['comment_dismarkup_temp']);
            }

            // check the origin field entry to HTML display each comment using NL2P in Backend and/or Frontend - and in shortcut /comments/ pages
            if ($serendipity['allowHtmlComment'] && false !== strpos($comment['body'], '</p>')) {
                // disable NL2BR plugin parsing, for the NL2BR newline to p-tag option
                $serendipity['POST']['properties']['disable_markups'] = array(true);
                // Set a temporary GLOBAL to know this has run. This is more strict than checking disable_markups only (see above)
                $serendipity['comment_dismarkup_temp'] = true;
            }

            $addData = array('from' => 'functions_entries:printComments');
            serendipity_plugin_api::hook_event('frontend_display', $comment, $addData);

            $comment['clear_email'] = !empty($comment['email']) ? $comment['email'] : null; // independently from spamblock no_email option, since used for selector (self/owner) checks only!

            if (isset($comment['no_email']) && $comment['no_email']) {
                $comment['email'] = false;
            } elseif (!empty($comment['email'])) {
                $comment['email'] = serendipity_specialchars(str_replace('@', '[at]', $comment['email']));
            }

            // Frontend entry comments - do for both else add ($serendipity['allowHtmlComment'] && )
            if (isset($comment['type']) && $comment['type'] == 'NORMAL' && empty(trim($comment['comment']))) {
                $comment['comment'] = '<span class="serendipity_msg_important msg_error"><strong>Security Alert</strong>: Empty, since removed probably bad injection</span>';
            }

            $comment['body']    = $comment['comment'];
            $comment['pos']     = $i;
            $comment['trace']   = $trace . $i;
            $comment['depth']   = $depth;
            $comment['author']  = serendipity_specialchars($comment['author']);
            if (isset($comment['title'])) {
                $comment['title'] = serendipity_specialchars($comment['title']);
            }
            if (serendipity_userLoggedIn()) {
                // these pop-up in the edit preview of Backend comments for logged-in users
                if (isset($comment['subscribed']) && $comment['subscribed'] == 'true') {
                    if ($comment['status'] == 'approved') {
                        $comment['preview_editstatus'] = '<div class="msg_notice serendipity_subscription_on"><em>' . ACTIVE_COMMENT_SUBSCRIPTION . "</em></div>\n";
                    } else {
                        $comment['preview_editstatus'] = '<div class="msg_notice serendipity_subscription_pending"><em>' . PENDING_COMMENT_SUBSCRIPTION . "</em></div>\n";
                    }
                } else {
                    // why not ... :)
                    $comment['preview_editstatus'] = '<div class="msg_notice serendipity_subscription_off"><em>' . NO_COMMENT_SUBSCRIPTION . "</em></div>\n";
                }
            }
            $comment['preview_editstatus'] = $comment['preview_editstatus'] ?? null;

            $_smartyComments[] = $comment;
            // NOTE: There is no THREADed VIEW on comment summary pages. See commentByAuthors, this is hard called by VIEWMODE_LINEAR
            if (isset($comment['id']) && $comment['id'] && $parentid !== VIEWMODE_LINEAR ) {
                serendipity_printComments($comments, $comment['id'], ($depth+1), ($trace . $i . '.'), $smarty_block, $smarty_file);
            }
        }
    }

    /* We are inside a recursive child, and we need to break out */
    if ($depth !== 0) {
        return true;
    }

    $_blocktype = ($smarty_block == 'COMMENTS' || $smarty_block === null) ? 'comments' : 'trackbacks';
    $serendipity['smarty']->assignByRef($_blocktype, $_smartyComments);
    unset($_smartyComments);

    // Backend preview
    if (is_null($smarty_block)) {
        return;
    }
    return serendipity_smarty_fetch($smarty_block, $smarty_file);
}

/**
 * Fetches and prints a listing of comments by author
 */
function serendipity_printCommentsByAuthor() {
    global $serendipity;

    $type = serendipity_db_escape_string($serendipity['GET']['commentMode']);

    if (!isset($serendipity['allowHtmlComment'])) $serendipity['allowHtmlComment'] = false;
    if (!empty($serendipity['GET']['viewCommentAuthor'])) {
        $sql_where = " AND co.author = '" . serendipity_db_escape_string($serendipity['GET']['viewCommentAuthor']) . "'";
        $group_by  = "GROUP BY co.author";
    } else {
        $sql_where = " AND 1"; // Required fake 'where' condition
        $group_by  = "";
    }

    if (!empty($serendipity['GET']['commentStartTime'])) {
        $sql_where .= " AND co.timestamp >= " . (int)$serendipity['GET']['commentStartTime'];
    }

    if (!empty($serendipity['GET']['commentEndTime'])) {
        $sql_where .= " AND co.timestamp <= " . (int)$serendipity['GET']['commentEndTime'];
    }

    if (empty($serendipity['GET']['page'])) {
        $serendipity['GET']['page'] = 1;
    }

    $sql_limit = $serendipity['CBAfetchLimit'] * ((int)$serendipity['GET']['page']-1) . ',' . $serendipity['CBAfetchLimit'];
    $c = serendipity_fetchComments(null, $sql_limit, 'co.entry_id DESC, co.id ASC', false, $type, $sql_where);
    $entry_comments = array();

    if (is_array($c) && !empty($c)) {
        foreach($c AS $i => $comment) {
            $comment['meta'] = 'no-';
            if (!isset($entry_comments[$comment['entry_id']])) {
                $comment['link'] = serendipity_archiveURL($comment['entry_id'], $comment['title'], 'serendipityHTTPPath', true, array('timestamp' => $comment['entrytimestamp']));
                $entry_comments[$comment['entry_id']] = $comment;
            }
            if ($serendipity['allowHtmlComment']) {
                $comment['body'] = serendipity_prepCommentNewline($comment['body']);
            }
            $entry_comments[$comment['entry_id']]['comments'][] = $comment;
        }

        foreach($entry_comments AS $entry_id => $_data) {
            $entry_comments[$entry_id]['tpl_comments'] = serendipity_printComments($_data['comments'], VIEWMODE_LINEAR, 0, null, 'COMMENTS', 'pcomments.tpl');
        }
    }

    $serendipity['smarty']->assignByRef('comments_by_authors', $entry_comments);
    $serendipity['smarty']->assign('typeview', $type); // only for comment archive pages; not 'viewtype', since that is used for response header return codes

    // we need this here for the counter paging sql query - elsewise $type is top-checked in serendipity_fetchComments()
    if ($type == 'comments' || $type == 'NORMAL' || empty($type)) {
        $_type = 'NORMAL';
    } elseif ($type == 'trackbacks') {
        $_type = 'TRACKBACK';
    } elseif ($type == 'pingbacks') {
        $_type = 'PINGBACK';
    } elseif ($type == 'comments_and_trackbacks') {
        $_type = '%';
    } else {
        $_type = '';
    }

    if (empty($_type)) {
        return false;
    }

    $and = '';
    $cond = array();
    serendipity_plugin_api::hook_event('frontend_fetchcomments', $cond, array('source' => 'comments_counter'));
    $cond['joins'] = $cond['joins'] ?? '';
    $cond['and']   = $cond['and'] ?? '';
    if (!empty($cond['and'])) {
        $sql_where = str_replace(' AND 1', '', $sql_where); // Remove this faked 'where' condition
        $and = ' AND ';
    }
    $cond['and'] =  "\n" . $and . $cond['and'];

    $fc = "SELECT count(co.id) AS counter
             FROM {$serendipity['dbPrefix']}comments AS co
             {$cond['joins']}
            WHERE co.entry_id > 0
              AND co.type LIKE '" . $_type . "'
              AND co.status = 'approved' " . $sql_where . "{$cond['and']} "
            . $group_by;
    // DEBUG:
    // die($fc);
    $cc = serendipity_db_query($fc, true, 'assoc');

    $totalComments = !isset($cc['counter']) ? 0 : $cc['counter'];

    serendipity_printEntryFooter('', $totalComments, $serendipity['CBAfetchLimit']);

    serendipity_smarty_fetch('ENTRIES', 'comments_by_author.tpl');

    return true;
}

/**
 * Delete a specific comment
 *
 * @access public
 * @param   int     The ID of the comment to delete
 * @param   int     The ID of the entry the comment belongs to [safety]
 * @param   string  The type of a comment (comments/trackback)
 * @param   string  The 32 character token [if using token based moderation]
 * @return  boolean Return whether the action was successful)
 */
function serendipity_deleteComment($id, $entry_id, $type='comments', $token=false) {
    global $serendipity;

    $id       = (int)$id;
    $entry_id = (int)$entry_id;
    if ($id < 1 OR $entry_id < 1) {
        return false;
    }

    $goodtoken = serendipity_checkCommentToken($token, $id);

    if ($_SESSION['serendipityAuthedUser'] === true || $goodtoken) {

        $admin = '';
        if (!$goodtoken && !serendipity_checkPermission('adminEntriesMaintainOthers')) {
            $admin = " AND authorid = " . (int)$_SESSION['serendipityAuthorid'];

            // Load articles author id and check it
            $sql = serendipity_db_query("SELECT authorid FROM {$serendipity['dbPrefix']}entries
                                          WHERE id = ". $entry_id, true);
            if ($sql['authorid'] != $serendipity['authorid']) {
                return false; // wrong user having no adminEntriesMaintainOthers right
            }
        }

        /* We have to figure out if the comment we are about to delete, is awaiting approval,
           if so - we should *not* subtract it from the entries table */
        $sql = serendipity_db_query("SELECT type, status, parent_id, body FROM {$serendipity['dbPrefix']}comments
                                      WHERE entry_id = ". $entry_id ."
                                        AND id = ". $id, true);

        /* Check to see if the comment has children
         * if it does, don't delete, but replace with "*(COMMENT DELETED)*"
           to delete a tree, delete children first */
        $has_parent = serendipity_db_query("SELECT count(id) AS count
                                              FROM {$serendipity['dbPrefix']}comments
                                             WHERE parent_id = ". $id . "
                                             LIMIT 1", true);

        if (is_array($has_parent) && isset($has_parent['count']) && $has_parent['count'] > 0 && $sql['body'] != 'COMMENT_DELETED') {
            // Comment has childs, so don't delete it.
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}comments
                                     SET body = 'COMMENT_DELETED'
                                   WHERE id = " . $id);
        } else {
            // Comment has no childs or has already been deleted; It can be safely removed.
            serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}comments
                                        WHERE entry_id = ". $entry_id ."
                                                AND id = ". $id);

            if (is_array($sql) && $sql['status'] !== 'pending') {
                if (!empty($sql['type']) && $sql['type'] != 'NORMAL') {
                    $type = 'trackbacks';
                } else {
                    $type = 'comments';
                }
                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}entries SET $type = $type-1 WHERE id = ". $entry_id ." AND $type > 0 $admin");
            }

            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}comments SET parent_id = " . (int)$sql['parent_id'] . " WHERE parent_id = " . $id);
        }

        $addData = array('cid' => $id, 'entry_id' => $entry_id);
        serendipity_plugin_api::hook_event('backend_deletecomment', $sql, $addData);

        return true;
    } else {
        return false;
    }
}

/**
 * Toggle whether an entry allows comments
 *
 * @access public
 * @param   int     The ID of the entry where the switch shall be toggled
 * @param   string  Whether the entry shall be opened or closed for comments
 * @return null
 */
function serendipity_allowCommentsToggle($entry_id, $switch = 'disable') {
    global $serendipity;

    // toggle comments by authorized staff only
    if ($_SESSION['serendipityAuthedUser'] === true && serendipity_checkFormToken()) {
        $admin = '';
        if (!serendipity_checkPermission('adminEntriesMaintainOthers')) {
            $admin = " AND authorid = " . (int)$_SESSION['serendipityAuthorid'];
        }

        $query = "UPDATE {$serendipity['dbPrefix']}entries SET allow_comments = '" . ($switch == 'disable' ? 'false' : 'true') . "' WHERE id = '". (int)$entry_id ."' $admin";
        serendipity_db_query($query);
        if (serendipity_isResponseClean($_SERVER['HTTP_REFERER'])) {
            header('Status: 302 Found');
            header('Location: '. $_SERVER['HTTP_REFERER']);
            exit;
        }
    } else {
        die('What are you up to? You need to be an admin to close comments');
    }
}

/**
 * Approve a comment
 *
 * LONG
 *
 * @access public
 * @param  int         The ID of the comment to approve
 * @param  int         The ID of the entry a comment belongs to
 * @param  boolean     Whether to force approving a comment despite of its current status
 * @param  boolean     If set to true, a comment will be moderated instead of approved.
 * @param  string      The 32 character token [if using token based moderation]
 * @return boolean     Success or failure
 */
function serendipity_approveComment($cid, $entry_id, $force = false, $moderate = false, $token = false) {
    global $serendipity;

    $goodtoken = serendipity_checkCommentToken($token, $cid);

    // Get data about the comment, we need this query because this function can be called from anywhere
    // This also makes sure we are either the author of the comment, or a USERLEVEL_ADMIN
    $sql = "SELECT c.*, e.title, a.email AS authoremail, a.mail_comments, e.timestamp AS entry_timestamp, e.last_modified AS entry_last_modified, e.authorid AS entry_authorid
                FROM {$serendipity['dbPrefix']}comments c
                LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
                LEFT JOIN {$serendipity['dbPrefix']}authors a ON (e.authorid = a.authorid)
                WHERE c.id = '". (int)$cid ."'
                    ". ((!serendipity_checkPermission('adminEntriesMaintainOthers') && $force !== true && !$goodtoken) ? "AND e.authorid = '". (int)$serendipity['authorid'] ."'" : '') ."
                    ". (($force === true) ? "" : "AND status = 'pending'");
    $rs  = serendipity_db_query($sql, true);

    // Check for adminEntriesMaintainOthers
    if (!$force && !$goodtoken && (is_bool($rs) || $rs['entry_authorid'] != $serendipity['authorid']) && !serendipity_checkPermission('adminEntriesMaintainOthers')) {
        return false; // wrong user having no adminEntriesMaintainOthers right
    }

    $flip = false;
    if ($moderate === 'flip') {
        $flip = true;

        if ($rs['status'] == 'pending') {
            $sql = "UPDATE {$serendipity['dbPrefix']}comments SET status = 'approved' WHERE id = ". (int)$cid;
            $moderate = false;
        } else {
            $sql = "UPDATE {$serendipity['dbPrefix']}comments SET status = 'pending' WHERE id = ". (int)$cid;
            $moderate = true;
        }
    } elseif ($moderate) {
        $sql = "UPDATE {$serendipity['dbPrefix']}comments SET status = 'pending' WHERE id = ". (int)$cid;
    } else {
        $sql = "UPDATE {$serendipity['dbPrefix']}comments SET status = 'approved' WHERE id = ". (int)$cid;
    }
    serendipity_db_query($sql);

    $field = ($rs['type'] == 'NORMAL' ? 'comments' : 'trackbacks');
    // Check when the entry was published. If it is older than max_last_modified allows, the last_modified date of that entry
    // will not be pushed. With this we make sure that an RSS feed will not be updated on a client's reader and marked as new
    // only because someone made an comment to an old entry.
    if ($rs['entry_timestamp'] > time() - $serendipity['max_last_modified']) {
        $lm = time();
    } else {
        $lm = (int)$rs['entry_last_modified'];
    }

    $counter_comments = serendipity_db_query("SELECT count(id) AS counter
                                                FROM {$serendipity['dbPrefix']}comments
                                               WHERE status = 'approved'
                                                 AND type   = 'NORMAL'
                                                 AND entry_id = " . (int)$entry_id . "
                                            GROUP BY entry_id", true);

    $counter_tb = serendipity_db_query("SELECT count(id) AS counter
                                          FROM {$serendipity['dbPrefix']}comments
                                         WHERE status = 'approved'
                                           AND (type = 'TRACKBACK' or type = 'PINGBACK')
                                           AND entry_id = " . (int)$entry_id . "
                                      GROUP BY entry_id", true);

    $counter_comments = $counter_comments['counter'] ?? 0;
    $counter_tb = $counter_tb['counter'] ?? 0;

    $query = "UPDATE {$serendipity['dbPrefix']}entries
                 SET comments      = " . (int)$counter_comments . ",
                     trackbacks    = " . (int)$counter_tb . ",
                     last_modified = ". $lm ."
               WHERE id = ". (int)$entry_id;
    serendipity_db_query($query);

    /* It's already approved, don't spam people */
    if ($rs === false) {
        return false;
    }

    if (!$moderate) {
        if ($serendipity['allowSubscriptions'] === 'fulltext') {
            serendipity_mailSubscribers($entry_id, $rs['author'], $rs['email'], $rs['title'], $rs['authoremail'], $cid, $rs['body']);
        } elseif (serendipity_db_bool($serendipity['allowSubscriptions'])) {
            serendipity_mailSubscribers($entry_id, $rs['author'], $rs['email'], $rs['title'], $rs['authoremail'], $cid);
        }

        serendipity_plugin_api::hook_event('backend_approvecomment', $rs);
    }

    serendipity_cleanCache();
    if ($flip) {
        if ($moderate) return -1; // comment set to pending
        if (!$moderate) return 1; // comment set to approved
    }

    return true;
}

/**
 * Confirm a mail authentication request
 *
 * @access public
 * @param   int     The ID of a comment
 * @param   string  The confirmation hash
 * @return  boolean
 */
function serendipity_confirmMail($cid, $hash) {
    global $serendipity;

    $q = "SELECT c.entry_id, e.title, e.timestamp, e.id
            FROM {$serendipity['dbPrefix']}comments AS c
            JOIN {$serendipity['dbPrefix']}entries AS e
              ON (e.id = c.entry_id)
           WHERE c.status = 'confirm" . serendipity_db_escape_string($hash) . "'
             AND c.id     = '" . (int)$cid . "'";
    $confirm = serendipity_db_query($q, true);

    if ($confirm['entry_id'] > 0) {
        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}options
                                 SET okey = 'mail_confirm'
                               WHERE okey = 'mail_confirm" . serendipity_db_escape_string($hash) . "'");

        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}comments
                                 SET status = 'pending'
                               WHERE status = 'confirm" . serendipity_db_escape_string($hash) . "'
                                 AND id     = '" . (int)$cid . "'");

        // TODO?
        /* if (serendipity_db_bool($confirm['mail_comments'])) {
            serendipity_sendComment($cid, $row['email'], $name, $email, $url, $id, $row['title'], $comments, $type, serendipity_db_bool($ca['moderate_comments']));
        }
        */

        serendipity_approveComment($cid, $confirm['entry_id'], true);
        return $confirm;
    } else {
        return false;
    }
}

/**
 * Store the comment made by a visitor in the database
 *
 * @access public
 * @param   int     The ID of an entry
 * @param   array   An array that holds the input data from the visitor
 * @param   string  The type of a comment (normal/trackback)
 * @param   string  Where did a comment come from? (internal|trackback|plugin) [currently consistent informative only]
 * @param   string  Normally entry specific handle data. May change by Additional plugin data (spamblock plugin etc.)
 * @return  boolean Returns true if the comment could be added
 */
function serendipity_insertComment($id, $commentInfo, $type = 'NORMAL', $source = 'internal', $ca = array()) {
    global $serendipity;

    if (!empty($ca['status'])) {
        $commentInfo['status'] = $ca['status'];
    }

    if (isset($serendipity['serendipityAuthedUser']) && $serendipity['serendipityAuthedUser'] === true) {
        $authorReply = true;
        $authorEmail = $serendipity['serendipityEmail'];
    } else {
        $authorReply = false;
        $authorEmail = '';
    }

    $_setTo_moderation = isset($ca['moderate_comments']) ? serendipity_db_bool($ca['moderate_comments']) : true; // check empty $ca (i.e. xmlrpc) case
    // Hey - We just trust CHIEF and ADMIN USERLEVELs and set comments approved by default - spamblock already allowed registered authors
    if ($_setTo_moderation && (isset($serendipity['serendipityAuthedUser']) && $serendipity['serendipityAuthedUser']) && serendipity_checkPermission('adminEntriesMaintainOthers')) {
        $_setTo_moderation = false;
    }
    $title         = serendipity_db_escape_string(($commentInfo['title'] ?? ''));
    $comments      = $commentInfo['comment'];
    $ip            = serendipity_db_escape_string(($commentInfo['ip'] ?? $_SERVER['REMOTE_ADDR']));
    $commentsFixed = serendipity_db_escape_string($commentInfo['comment']);
    $name          = serendipity_db_escape_string($commentInfo['name']);
    $url           = serendipity_db_escape_string($commentInfo['url']);
    $email         = serendipity_db_escape_string($commentInfo['email']);
    $parentid      = (isset($commentInfo['parent_id']) && is_numeric($commentInfo['parent_id'])) ? $commentInfo['parent_id'] : 0;
    $status        = serendipity_db_escape_string(($commentInfo['status'] ?? ($_setTo_moderation ? 'pending' : 'approved')));
    $t             = serendipity_db_escape_string((!empty($commentInfo['time']) ? $commentInfo['time'] : time())); // may come by as returncode false via plugin
    $referer       = substr(serendipity_db_escape_string(($_SESSION['HTTP_REFERER'] ?? '')), 0, 200);

    $query = "SELECT a.email, e.title, a.mail_comments, a.mail_trackbacks
                FROM {$serendipity['dbPrefix']}entries AS e
     LEFT OUTER JOIN {$serendipity['dbPrefix']}authors AS a
                  ON a.authorid = e.authorid
               WHERE e.id  = '". (int)$id ."'
                 AND e.isdraft = 'false'";
    if (!serendipity_db_bool($serendipity['showFutureEntries'])) {
        $query .= " AND e.timestamp <= " . serendipity_db_time();
    }

    $row = serendipity_db_query($query, true); // Get info on author/entry
    if (!is_array($row) || empty($id)) {
        // No associated entry found.
        if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
            $fp = fopen('trackback2.log', 'a');
            fwrite($fp, '[' . date('d.m.Y H:i') . '] entry reference not found: ' . $query . "\n");
            fclose($fp);
        }

        return false;
    }

    $send_optin = false;
    if (isset($commentInfo['subscribe'])) {
        if (!isset($serendipity['allowSubscriptionsOptIn']) || $serendipity['allowSubscriptionsOptIn']) {
            $subscribe = 'false';
            $send_optin = true;
        } else {
            $subscribe = 'true';
        }
    } else {
        $subscribe = 'false';
    }

    $dbhash = bin2hex(random_bytes(16));

    if ($status == 'confirm') {
        $dbstatus = 'confirm' . $dbhash;
    } elseif ($status == 'confirm1') {
        $auth = serendipity_db_query("SELECT *
                                        FROM {$serendipity['dbPrefix']}options
                                       WHERE okey  = 'mail_confirm'
                                         AND name  = '" . $email . "'
                                         AND value = '" . $name . "'", true);
        if (!is_array($auth)) {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey)
                                       VALUES ('{$email}', '{$name}', 'mail_confirm{$dbhash}')");
            $dbstatus = 'confirm' . $dbhash;
        } else {
            $serendipity['csuccess'] = 'true';
            $status = $dbstatus = 'approved';
        }
    } else {
        $dbstatus = $status;
    }

    $query  = "INSERT INTO {$serendipity['dbPrefix']}comments (entry_id, parent_id, ip, author, email, url, body, type, timestamp, title, subscribed, status, referer)";
    $query .= " VALUES ('". (int)$id ."', '$parentid', '$ip', '$name', '$email', '$url', '$commentsFixed', '$type', '$t', '$title', '$subscribe', '$dbstatus', '$referer')";

    if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
        $fp = fopen('trackback2.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] SQL: ' . $query . "\n");
    }

    serendipity_db_query($query);
    $cid = serendipity_db_insert_id('comments', 'id');

    $_mail_comments    = serendipity_db_bool($row['mail_comments']);
    $_mail_trackbacks  = serendipity_db_bool($row['mail_trackbacks']);
    $_send_mod_comment = ($type == 'NORMAL' && $_mail_comments && $_setTo_moderation) ? true : false;
    $_send_mod_tpback  = (($type == 'TRACKBACK' || $type == 'PINGBACK') && $_mail_trackbacks && $_setTo_moderation) ? true : false;

    // Send mail to the author if he chose to receive these mails, or if the comment is awaiting moderation
    # 1. Don't do this on STATUS confirm and hidden pendings
    # 2. Check by OR - first come, first serve:
    #   a.1) Comment - moderation and PP set mail_comments are true OR
    #   a.2) Track/Pingback - moderation and PP set mail_trackbacks are true
    #   b) Type Comment and PP set mail_comments are true
    #   c) Type Track/Pingback and PP set mail_trackbacks are true
    # 3. NOT isset serendipityAuthedUser and authorEmail vs systemEmail are different
    if ($status != 'confirm' && $status != 'hidden' && (
        ($_send_mod_comment || $_send_mod_tpback)
        || ($type == 'NORMAL' && $_mail_comments)
        || (($type == 'TRACKBACK' || $type == 'PINGBACK') && $_mail_trackbacks))) {
            if (! ($authorReply && $authorEmail == $row['email'])) {
                serendipity_sendComment($cid, $row['email'], $name, $email, $url, $id, $row['title'], $comments, $type, $_setTo_moderation, $referer);
            }
    }

    // Approve with force, if moderation is disabled
    if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
        fwrite($fp, '[' . date('d.m.Y H:i') . '] status: ' . $status . ', moderate: ' . $ca['moderate_comments'] . "\n");
    }

    if ($status != 'confirm' && (empty($ca['moderate_comments']) || $_setTo_moderation == false)) {
        if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
            fwrite($fp, '[' . date('d.m.Y H:i') . '] Approving...' . "\n");
        }
        serendipity_approveComment($cid, $id, true);
    } elseif (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
        fwrite($fp, '[' . date('d.m.Y H:i') . '] No need to approve...' . "\n");
    }

    if ($status == 'confirm') {
        $subject = sprintf(NEW_COMMENT_TO_SUBSCRIBED_ENTRY, $row['title']);
        $message = sprintf(CONFIRMATION_MAIL_ALWAYS,
                            $name,
                            $row['title'],
                            $commentsFixed,
                            $serendipity['baseURL'] . 'comment.php?c=' . $cid . '&hash=' . $dbhash);

        serendipity_sendMail($email, $subject, $message, $serendipity['blogMail']);
    } elseif ($status == 'confirm1') {
        $subject = sprintf(NEW_COMMENT_TO_SUBSCRIBED_ENTRY, $row['title']);
        $message = sprintf(CONFIRMATION_MAIL_ONCE,
                            $name,
                            $row['title'],
                            $commentsFixed,
                            $serendipity['baseURL'] . 'comment.php?c=' . $cid . '&hash=' . $dbhash);

        serendipity_sendMail($email, $subject, $message, $serendipity['blogMail']);
    }

    if ($send_optin) {
        $dupe_check = serendipity_db_query("SELECT count(entry_id) AS counter
                                              FROM {$serendipity['dbPrefix']}comments
                                             WHERE entry_id = " . (int)$id . "
                                               AND email = '$email'
                                               AND subscribed = 'true'", true);
        if (!is_array($dupe_check) || $dupe_check['counter'] < 1) {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey)
                                       VALUES ('" . time() . "', '$cid', 'commentsub_{$dbhash}')");

            $subject = sprintf(NEW_COMMENT_TO_SUBSCRIBED_ENTRY, $row['title']);
            $message = sprintf(CONFIRMATION_MAIL_SUBSCRIPTION,
                                $name,
                                $row['title'],
                                serendipity_archiveURL($id, $row['title'], 'baseURL', true, array('timestamp' => time())),
                                $serendipity['baseURL'] . 'comment.php?optin=' . $dbhash);

            serendipity_sendMail($email, $subject, $message, $serendipity['blogMail']);
        }
    }

    if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
        fclose($fp);
    }

    serendipity_cleanCache();
    return $cid;
}

/**
 * Confirm a comment subscription
 *
 * @access public
 * @param   string  The confirmation hash
 * @return  boolean
 */
function serendipity_commentSubscriptionConfirm($hash) {
    global $serendipity;

    // Delete possible current cookie. Also delete any confirmation hashes that smell like 3-week-old, dead fish.
    if (stristr($serendipity['dbType'], 'sqlite')) {
        $cast = "name";
    } elseif (stristr($serendipity['dbType'], 'postgres')) {
        // Adds explicits casting for postgresql.
        $cast = "cast(name AS integer)";
    } else {
        // and all others eg. mysql(i), zend-db, ...
        $cast = "cast(name AS UNSIGNED)";
    }

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                           WHERE okey LIKE 'commentsub_%' AND $cast < " . (time() - 1814400));

    $hashinfo = serendipity_db_query("SELECT value
                                        FROM {$serendipity['dbPrefix']}options
                                       WHERE okey = 'commentsub_" . serendipity_db_escape_string($hash) . "'", true);

    if (is_array($hashinfo) && $hashinfo['value'] > 0) {
        $cid = (int)$hashinfo['value'];
        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}comments
                                 SET subscribed = 'true'
                               WHERE id = $cid");

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                               WHERE okey = 'commentsub_" . serendipity_db_escape_string($hash) . "'");

        return $cid;
    }
}

/**
 * Save a comment made by a visitor
 *
 * @access public
 * @param   int     The ID of an entry
 * @param   array   An array that holds the input data from the visitor
 * @param   string  The type of a comment (normal/trackback)
 * @param   string  Where did a comment come from? (internal|trackback|plugin)
 * @return  boolean Returns true if the comment could be added
 */
function serendipity_saveComment($id, $commentInfo, $type = 'NORMAL', $source = 'internal') {
    global $serendipity;

    $query = "SELECT id, allow_comments, moderate_comments, last_modified, timestamp, title FROM {$serendipity['dbPrefix']}entries WHERE id = '". (int)$id ."'";
    $ca    = serendipity_db_query($query, true);
    $users = serendipity_fetchUsers();
    $isUin = false;
    foreach($users AS $user) {
        if (!empty($user['realname'])) $allusers[] = $user['realname'];
    }
    if (in_array($commentInfo['name'], (array)$allusers) && !serendipity_userLoggedIn()) {
        $isUin = true; // do not allow using existing author user names
    }
    $commentInfo['type'] = $type;
    $commentInfo['source'] = $source; // put into commentInfo array

    // Secure email addresses, only one [first] allowed to not mail to multiple recipients
    $mailparts = explode(',', $commentInfo['email']);
    $commentInfo['email'] = trim($mailparts[0]);

    serendipity_plugin_api::hook_event('frontend_saveComment', $ca, $commentInfo);
    if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
        $fp = fopen('trackback2.log', 'a');
        fwrite($fp, '[' . date('d.m.Y H:i') . '] ' . print_r($ca, 1) . "\n");
        fclose($fp);
    }
    if ((!is_array($ca) || serendipity_db_bool($ca['allow_comments'])) && !$isUin) {
        if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
            $fp = fopen('trackback2.log', 'a');
            fwrite($fp, '[' . date('d.m.Y H:i') . '] insert comment into DB' . "\n");
            fclose($fp);
        }

        $commentInfo['comment_cid'] = serendipity_insertComment($id, $commentInfo, $type, $source, $ca);
        $commentInfo['comment_id'] = $id;
        serendipity_plugin_api::hook_event('frontend_saveComment_finish', $ca, $commentInfo);
        return true;
    } else {
        if (isset($GLOBALS['tb_logging']) && $GLOBALS['tb_logging']) {
            $fp = fopen('trackback2.log', 'a');
            fwrite($fp, '[' . date('d.m.Y H:i') . '] discarding comment from DB' . "\n");
            fclose($fp);
        }
        return false;
    }
}

/**
 * Send a mail to all subscribers of an entry about a new comment
 *
 * @access public
 * @param   int     The ID of the entry where a new comment has been made
 * @param   string  The name of the latest poster to an entry
 * @param   string  The email address of the latest poster to an entry
 * @param   string  The title of the entry
 * @param   string  The mail address used to send emails from
 * @param   int     The ID of the comment that has been made
 * @param   string  The body of the comment that has been made
 * @return null
 */
function serendipity_mailSubscribers($entry_id, $poster, $posterMail, $title, $fromEmail = 'none@example.com', $cid = null, $body = null) {
    global $serendipity;

    $entryURI = serendipity_archiveURL($entry_id, $title, 'baseURL', true, array('timestamp' => time())) . ($cid > 0 ? '#c' . $cid : '');
    $subject =  sprintf(NEW_COMMENT_TO_SUBSCRIBED_ENTRY, $title);

    $pgsql_insert = '';
    $mysql_insert = '';
    if ($serendipity['dbType'] == 'postgres' ||
        $serendipity['dbType'] == 'pdo-postgres') {
        $pgsql_insert = 'DISTINCT ON (email)';
    } else {
        $mysql_insert = 'GROUP BY email';
    }

    $sql = "SELECT $pgsql_insert author, email, type
              FROM {$serendipity['dbPrefix']}comments
             WHERE entry_id = '". (int)$entry_id ."'
               AND email <> '" . serendipity_db_escape_string($posterMail) . "'
               AND email <> ''
               AND subscribed = 'true' $mysql_insert";
    $subscribers = serendipity_db_query($sql);

    if (!is_array($subscribers)) {
        return;
    }

    foreach($subscribers AS $subscriber) {
        if ($subscriber['type'] == 'TRACKBACK') {
            $text = sprintf(
                      SUBSCRIPTION_TRACKBACK_MAIL,

                      $subscriber['author'],
                      $serendipity['blogTitle'],
                      $title,
                      $poster,
                      ($body ? "\n\n" . $body . "\n" : '') . $entryURI,
                      serendipity_rewriteURL('unsubscribe/' . urlencode($subscriber['email']) . '/' . (int)$entry_id, 'baseURL')
            );
        } else {
            $text = sprintf(
                      SUBSCRIPTION_MAIL,

                      $subscriber['author'],
                      $serendipity['blogTitle'],
                      $title,
                      $poster,
                      ($body ? "\n\n" . $body . "\n" : '') . $entryURI,
                      serendipity_rewriteURL('unsubscribe/' . urlencode($subscriber['email']) . '/' . (int)$entry_id, 'baseURL')
            );
        }
        serendipity_sendMail($subscriber['email'], $subject, $text, $fromEmail);
    }
}

/**
 * Cancel a subscription to an entry
 *
 * @access public
 * @param   string      E-Mail address to cancel subscription
 * @param   int         The entry ID to unsubscribe from
 * @return  int         Return number of unsubscriptions
 */
function serendipity_cancelSubscription($email, $entry_id) {
    global $serendipity;

    $sql = "UPDATE {$serendipity['dbPrefix']}comments
               SET subscribed = 'false'
             WHERE entry_id = '". (int)$entry_id ."'
               AND email = '" . serendipity_db_escape_string($email) . "'";
    serendipity_db_query($sql);

    return serendipity_db_affected_rows();
}

/**
 * Send a comment notice to the admin/author of an entry
 *
 * @access public
 * @param  int      ID of the comment that has been made
 * @param  string   Author's email address to send the mail to
 * @param  string   The name of the sender
 * @param  string   The URL of the sender
 * @param  int      The ID of the entry that has been commented
 * @param  string   The title of the entry that has been commented
 * @param  string   The text of the comment
 * @param  string   The type of the comment (normal|trackback)
 * @param  boolean  Toggle Whether comments to this entry need approval
 * @return boolean  Return success of sending the mails
 */
function serendipity_sendComment($comment_id, $to, $fromName, $fromEmail, $fromUrl, $id, $title, $comment, $type = 'NORMAL', $moderate_comment = false, $referer = '') {
    global $serendipity;

    if (empty($fromName)) {
        $fromName = ANONYMOUS;
    }

    $entryURI = serendipity_archiveURL($id, $title, 'baseURL', true, array('timestamp' => time()));
    $path     = ($type == 'TRACKBACK' || $type == 'PINGBACK') ? 'trackback' : 'comment';

    // Check for using Tokens
    if ($serendipity['useCommentTokens']) {
        $token = bin2hex(random_bytes(16));
        $path  = $path . "_token_" . $token;

        // Delete any comment tokens older than 1 week.
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                               WHERE okey LIKE 'comment_%' AND name < " . (time() - 604800) );

        // Issue new comment moderation hash
        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey)
                              VALUES ('" . time() . "', '$token', 'comment_" . $comment_id ."')");
    }

    $deleteURI  = serendipity_rewriteURL(PATH_DELETE . '/'. $path .'/' . $comment_id . '/' . $id . '-' . serendipity_makeFilename($title)  . '.html', 'baseURL');
    $approveURI = serendipity_rewriteURL(PATH_APPROVE . '/'. $path .'/' . $comment_id . '/' . $id . '-' . serendipity_makeFilename($title)  . '.html', 'baseURL');

    $eventData = array( 'comment_id'       => $comment_id,
                        'entry_id'         => $id,
                        'entryURI'         => $entryURI,
                        'path'             => $path,
                        'deleteURI'        => $deleteURI,
                        'approveURI'       => $approveURI,
                        'moderate_comment' => $moderate_comment,
                        'action_more'      => array());
    serendipity_plugin_api::hook_event('backend_sendcomment', $eventData);

    $action_more = '';
    foreach($eventData['action_more'] AS $action) {
        $action_more .= "\n" . str_repeat(' ', 3) . $action;
    }

    if ($type == 'TRACKBACK' || $type == 'PINGBACK') {

        /******************* TRACKBACKS *******************/
        $subject =  ($moderate_comment ? '[' . REQUIRES_REVIEW . '] ' : '') . NEW_TRACKBACK_TO . ' ' . $title;
        $text = sprintf(A_NEW_TRACKBACK_BLAHBLAH, $title)
              . "\n"
              . "\n" . REQUIRES_REVIEW          . ': ' . (($moderate_comment) ? YES : NO) . (isset($serendipity['moderate_reason']) ? ' (' . $serendipity['moderate_reason'] . ')' : '')
              . "\n" . LINK_TO_ENTRY            . ': ' . $entryURI
              . "\n" . WEBLOG                   . ': ' . stripslashes($fromName)
              . "\n" . LINK_TO_REMOTE_ENTRY     . ': ' . $fromUrl
              . "\n"
              . "\n" . EXCERPT . ':'
              . "\n" . strip_tags($comment)
              . "\n"
              . "\n" . '----'
              . "\n" . YOU_HAVE_THESE_OPTIONS
              . (($moderate_comment) ? "\n" . str_repeat(' ', 2) . THIS_TRACKBACK_NEEDS_REVIEW : '')
              . "\n" . str_repeat(' ', 3) . str_pad(VIEW_ENTRY,  15) . ' -- '. $entryURI
              . "\n" . str_repeat(' ', 3) . str_pad(DELETE_TRACKBACK,  15) . ' -- '. $deleteURI
              . (($moderate_comment) ? "\n" . str_repeat(' ', 3) . str_pad(APPROVE_TRACKBACK, 15) . ' -- '. $approveURI : '')
              . $action_more;

    } else {

        /******************* COMMENTS *********************/
        $subject = ($moderate_comment ? '[' . REQUIRES_REVIEW . '] ' : '') . NEW_COMMENT_TO . ' ' . $title;
        $text = sprintf(A_NEW_COMMENT_BLAHBLAH, $serendipity['blogTitle'], $title)
              . "\n" . LINK_TO_ENTRY . ': ' . $entryURI
              . "\n"
              . "\n" . REQUIRES_REVIEW         . ': ' . (($moderate_comment) ? YES : NO) . (isset($serendipity['moderate_reason']) ? ' (' . $serendipity['moderate_reason'] . ')' : '')
              . "\n" . IP_ADDRESS . ': ' . $_SERVER['REMOTE_ADDR']
              . "\n" . NAME       . ': ' . $fromName
              . "\n" . EMAIL      . ': ' . $fromEmail
              . "\n" . HOMEPAGE   . ': ' . $fromUrl
              . "\n" . REFERER    . ': ' . $referer
              . "\n"
              . "\n" . COMMENTS                . ': '
              . "\n" . strip_tags($comment)
              . "\n"
              . "\n" . '----'
              . "\n" . YOU_HAVE_THESE_OPTIONS
              . (($moderate_comment) ? "\n" . str_repeat(' ', 2) . THIS_COMMENT_NEEDS_REVIEW : '')
              . "\n" . str_repeat(' ', 3) . str_pad(VIEW_COMMENT,  15) . ' -- '. $entryURI .'#c'. $comment_id
              . "\n" . str_repeat(' ', 3) . str_pad(DELETE_COMMENT,  15) . ' -- '. $deleteURI
              . (($moderate_comment) ? "\n" . str_repeat(' ', 3) . str_pad(APPROVE_COMMENT, 15) . ' -- '. $approveURI : '')
              . $action_more;
    }

    return serendipity_sendMail($to, $subject, $text, $fromEmail, null, $fromName);
}
