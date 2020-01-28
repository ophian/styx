<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

$data = array();

$commentsPerPage = (int)(!empty($serendipity['GET']['filter']['perpage']) ? $serendipity['GET']['filter']['perpage'] : 10);
$summaryLength = 118;

$errormsg = '';
$msg = '';
$msgtype = 'notice';

if (!isset($serendipity['allowHtmlComment'])) $serendipity['allowHtmlComment'] = false;

$_id       = !empty($serendipity['GET']['id']) ? (int)$serendipity['GET']['id'] : 0;
$_replyTo  = !empty($serendipity['POST']['replyTo']) ? (int)$serendipity['POST']['replyTo'] : 0;
$_entry_id = !empty($serendipity['GET']['entry_id']) ? (int)$serendipity['GET']['entry_id'] : 0;

if (isset($serendipity['POST']['formAction']) && $serendipity['POST']['formAction'] == 'multiDelete' && sizeof($serendipity['POST']['delete']) != 0 && serendipity_checkFormToken()) {
    if ($serendipity['POST']['togglemoderate'] != '') {
        foreach($serendipity['POST']['delete'] AS $k => $v) {
            $ac = serendipity_approveComment((int)$k, (int)$v, false, 'flip');
            if ($ac > 0) {
                $msg .= ($multi ? '' : DONE . ":\n") . sprintf(COMMENT_APPROVED, (int)$k)."\n";
                $msgtype = 'success';
                $multi = true;
            } else {
                $msg .= DONE . ":\n" . sprintf(COMMENT_MODERATED, (int)$k)."\n";
            }
        }
    } else {
        foreach($serendipity['POST']['delete'] AS $k => $v) {
            if (serendipity_deleteComment($k, $v)) {
                $msg .= ($multi ? '' : DONE . ":\n") . sprintf(COMMENT_DELETED, (int)$k) . "\n";
                $msgtype = 'success';
                $multi = true;
            } else {
                $msg .= ERROR . ': ' . DELETE_COMMENT . ": $k\n";
                $msgtype = 'error';
            }
        }
    }
}

/* We are asked to save the edited comment, and we are not in preview mode */
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'doEdit' && !isset($serendipity['POST']['preview']) && $_id > 0 && serendipity_checkFormToken()) {
    // re-assign this comments parent
    if (isset($serendipity['POST']['commentform']['replyToParent']) && $serendipity['POST']['commentform']['replyToParent'] >= 0) {
        $_replyTo = ($_replyTo != $serendipity['POST']['commentform']['replyToParent']) ? (int)$serendipity['POST']['commentform']['replyToParent'] : $_replyTo;
    }
    if (isset($serendipity['POST']) && $_id > 0) {
        $sql = "UPDATE {$serendipity['dbPrefix']}comments
                   SET
                        author = '" . serendipity_db_escape_string($serendipity['POST']['name'])    . "',
                        email  = '" . serendipity_db_escape_string($serendipity['POST']['email'])   . "',
                        url    = '" . serendipity_db_escape_string($serendipity['POST']['url'])     . "',
                        " . ($_replyTo != $_id ? "parent_id = '" . serendipity_db_escape_string($_replyTo) . "'," : '') . "
                        body   = '" . serendipity_db_escape_string($serendipity['POST']['comment']) . "'
                 WHERE id      = " . $_id . "
                   AND entry_id= " . (int)$serendipity['POST']['entry_id'];
        serendipity_db_query($sql);
        serendipity_plugin_api::hook_event('backend_updatecomment', $serendipity['POST'], $_id);
        $msg .= COMMENT_EDITED."\n";
    }
}

/* Submit a new comment */
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'doReply' && !isset($serendipity['POST']['preview']) && serendipity_checkFormToken()) {
    $comment = array();
    $comment['url']       = $serendipity['POST']['url'];
    $comment['comment']   = trim($serendipity['POST']['comment']);
    $comment['name']      = $serendipity['POST']['name'];
    $comment['email']     = $serendipity['POST']['email'];
    $comment['subscribe'] = $serendipity['POST']['subscribe'] ?? false;
    $comment['parent_id'] = $_replyTo;

    if (!empty($comment['comment'])) {
        if (serendipity_saveComment((int)$serendipity['POST']['entry_id'], $comment, 'NORMAL')) {
            $data['commentReplied'] = true;
            echo serendipity_smarty_showTemplate('admin/comments.inc.tpl', $data);
            return true;
        } else {
            $errormsg .= COMMENT_NOT_ADDED."\n";
            $serendipity['GET']['adminAction'] = 'reply';
        }
    } else {
        $errormsg .= COMMENT_NOT_ADDED."\n";
        $serendipity['GET']['adminAction'] = 'reply';
    }
}

// Sets a pending comment into a hidden state, since the editor does not want to approve nor delete - Gets out of pending notices
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'hide' && serendipity_checkFormToken()) {
    $sql = "UPDATE {$serendipity['dbPrefix']}comments
               SET status = 'hidden'
             WHERE id     = " . $_id . "
               AND status = 'pending'";
    serendipity_db_query($sql);
    $msg .= COMMENT_EDITED." (".PLUGIN_INACTIVE.")\n";
}

// Sets a hidden comment back into a pending public state, since the editor does now want to approve and publish it
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'public' && serendipity_checkFormToken()) {
    $sql = "UPDATE {$serendipity['dbPrefix']}comments
               SET status = 'pending'
             WHERE id     = " . $_id . "
               AND status = 'hidden'";
    serendipity_db_query($sql);
    $msg .= COMMENT_EDITED." (".PLUGIN_ACTIVE.")\n";
}

/* We approve a comment */
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'approve' && serendipity_checkFormToken()) {
    $sql = "SELECT c.*, e.title, a.email AS authoremail, a.mail_comments
              FROM {$serendipity['dbPrefix']}comments c
         LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
         LEFT JOIN {$serendipity['dbPrefix']}authors a ON (e.authorid = a.authorid)
             WHERE c.id = " . $_id  ." AND (status = 'pending' OR status LIKE 'confirm%')";
    $rs = serendipity_db_query($sql, true);

    if ($rs === false) {
        $errormsg .= ERROR .': '. sprintf(COMMENT_ALREADY_APPROVED, $_id)."\n";
    } else {
        serendipity_approveComment($_id, (int)$rs['entry_id']);
        $msg .= DONE . ': '. sprintf(COMMENT_APPROVED, $_id)."\n";
        $msgtype = 'success';
    }
}

/* We set an already approved comment back to moderate */
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'pending' && serendipity_checkFormToken()) {
    $sql = "SELECT c.*, e.title, a.email AS authoremail, a.mail_comments
              FROM {$serendipity['dbPrefix']}comments c
         LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
         LEFT JOIN {$serendipity['dbPrefix']}authors a ON (e.authorid = a.authorid)
             WHERE c.id = " . $_id  ." AND status = 'approved'";
    $rs = serendipity_db_query($sql, true);

    if ($rs === false) {
        $errormsg .= ERROR .': '. sprintf(COMMENT_ALREADY_APPROVED, $_id)."\n";
    } else {
        serendipity_approveComment($_id, (int)$rs['entry_id'], true, true);
        $msg .= DONE . ': '. sprintf(COMMENT_MODERATED, $_id)."\n";
    }
}

/* We are asked to delete a comment */
if (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'delete' && serendipity_checkFormToken()) {
    if (serendipity_deleteComment($_id, $_entry_id)) {
        $msg .= DONE . ":\n" . sprintf(COMMENT_DELETED, $_id)."\n";
        $msgtype = 'success';
    } else {
        $msg .= ERROR . ': ' . DELETE_COMMENT . ": $_id\n";
        $msgtype = 'error';
    }
}

/* We are either in edit mode, or preview mode */
if (isset($serendipity['GET']['adminAction'])
    && ($serendipity['GET']['adminAction'] == 'edit' || $serendipity['GET']['adminAction'] == 'reply') || isset($serendipity['POST']['preview'])) {

    $serendipity['smarty_raw_mode'] = true; // Force output of Smarty stuff in the backend
    if (!is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }
    $serendipity['smarty']->assign('comment_wysiwyg', ($serendipity['allowHtmlComment'] && $serendipity['wysiwyg']));
    if ($serendipity['allowHtmlComment'] && $serendipity['wysiwyg']) {
        // define the script here and NOT in template for secureness, since we don't want any possible manipulation!
        $ckescript = "
        <script>
            window.onload = function() {
                var plugIN = (typeof CKECONFIG_CODE_ON === 'undefined' || !CKECONFIG_CODE_ON) ? 'emoji' : 'codesnippet,emoji';
                CKEDITOR.replace( 'serendipity_commentform_comment',
                {
                    toolbar : [['Undo','Redo'],['Format'],['Bold','Italic','Underline','Strike','Superscript','TextColor','-','NumberedList','BulletedList','Outdent','Blockquote'],['JustifyBlock','JustifyCenter'],['SpecialChar'],['Maximize'],['CodeSnippet','EmojiPanel'],['Source']],
                    toolbarGroups: null,
                    entities: false,
                    htmlEncodeOutput: false,
                    extraAllowedContent: 'div(*);p(*);ul(*);pre;code{*}(*)',
                    extraPlugins: plugIN
                });
            }
        </script>";
        $serendipity['smarty']->assign('secure_simple_ckeditor', $ckescript);
    }

    if ($_id > 0 && $_entry_id > 0 && ($serendipity['GET']['adminAction'] == 'reply' || $serendipity['GET']['adminAction'] == 'doReply')) {
        $c = serendipity_fetchComments($_entry_id, 1, 'co.id', false, 'NORMAL', ' AND co.id=' . $_id);
        $p = $c[0]['parent_id'] = 0; // copy comments parent_id, since we also want to get a previewed reply view for multidepth comments
        if (isset($serendipity['POST']['preview'])) {
            $c[] = array(
                    'email'     => $serendipity['POST']['email'],
                    'author'    => $serendipity['POST']['name'],
                    'body'      => $serendipity['POST']['comment'],
                    'url'       => $serendipity['POST']['url'],
                    'timestamp' => time(),
                    'parent_id' => $_id
            );
        }

        $target_url = '?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=doReply&amp;serendipity[id]=' . $_id . '&amp;serendipity[entry_id]=' . $_entry_id . '&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;' . serendipity_setFormToken('url');
        $out        = serendipity_printComments($c, $p);
        $codata     = $serendipity['POST'];

        $serendipity['smarty']->display(serendipity_getTemplateFile('admin/comment_reply.tpl', 'serendipityPath')); // no need for a compile file
        #echo serendipity_smarty_showTemplate('admin/comment_reply.tpl');

        $codata['replyTo'] = $_id;
        if (!isset($codata['name'])) {
            $codata['name'] = $serendipity['serendipityRealname'];
        }

        if (!isset($codata['email'])) {
            $codata['email'] = $serendipity['serendipityEmail'];
        }
    } else {
        $target_url = '?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=doEdit&amp;serendipity[id]=' . $_id . '&amp;serendipity[entry_id]=' . $_entry_id . '&amp;' . serendipity_setFormToken('url');

        /* If we are not in preview, we need comment data from our database */
        if ($_id > 0 && !isset($serendipity['POST']['preview'])) {
            $comment = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}comments WHERE id = ". $_id);
            $codata['id']         = $comment[0]['id'];
            $codata['name']       = $comment[0]['author'];
            $codata['email']      = $comment[0]['email'];
            $codata['url']        = $comment[0]['url'];
            $codata['replyTo']    = $comment[0]['parent_id'];
            $codata['comment']    = (($serendipity['allowHtmlComment'] && $serendipity['wysiwyg']) && false === strpos($comment[0]['body'], '</p>'))
                                        ? nl2br($comment[0]['body'])
                                        : $comment[0]['body'];

        /* If we are in preview, we get comment data from our form */
        } elseif (isset($serendipity['POST']['preview'])) {
            $codata['id']         = $serendipity['POST']['comment_id'];
            $codata['name']       = $serendipity['POST']['name'];
            $codata['email']      = $serendipity['POST']['email'];
            $codata['url']        = $serendipity['POST']['url'];
            $codata['replyTo']    = $_replyTo;
            $codata['comment']    = $serendipity['POST']['comment'];
            $pc_data = array(
                array(
                    'email'     => $serendipity['POST']['email'],
                    'author'    => $serendipity['POST']['name'],
                    'body'      => $serendipity['POST']['comment'],
                    'url'       => $serendipity['POST']['url'],
                    'timestamp' => time()
                )
            );

            serendipity_printComments($pc_data);
            // Displays the PREVIEW of your edited backend comment via edit. For future backend purposes we want it to be out of standard (frontend) template, therefore we have a backend only file stored in admin/
            $serendipity['smarty']->display(serendipity_getTemplateFile('admin/comments.tpl', 'serendipityPath')); // no need for a compile file
            #echo serendipity_smarty_showTemplate('admin/comments.tpl');
        }
    }

    if (!empty($codata['url']) && substr($codata['url'], 0, 7) != 'http://' && substr($codata['url'], 0, 8) != 'https://') {
        $codata['url'] = 'http://' . $codata['url'];
    }

    serendipity_displayCommentForm($_entry_id, $target_url, NULL, $codata, false, false);

    // Displays the backend comment form. For future backend purposes we want it to be out of standard (frontend) template, therefore we have a backend only file stored in admin/
    $serendipity['smarty']->display(serendipity_getTemplateFile('admin/commentform.tpl', 'serendipityPath')); // no need for a compile file
    #echo serendipity_smarty_showTemplate('admin/commentform.tpl');

    return true;
}

/* Researchable fields */
$filters = array('author', 'email', 'ip', 'url', 'body', 'referer');
$and = $searchString = ''; // init default

/* Compress the filters into an "AND" SQL query, and a querystring */
foreach($filters AS $filter) {
    $and          .= (!empty($serendipity['GET']['filter'][$filter]) ? "AND c.". $filter ." LIKE '%". serendipity_db_escape_string($serendipity['GET']['filter'][$filter]) ."%'" : "");
    $searchString .= (!empty($serendipity['GET']['filter'][$filter]) ? "&amp;serendipity[filter][". $filter ."]=". serendipity_specialchars($serendipity['GET']['filter'][$filter]) : "");
}

// init default
$serendipity['GET']['filter']['show'] = $serendipity['GET']['filter']['show'] ?? '';
$serendipity['GET']['filter']['type'] = $serendipity['GET']['filter']['type'] ?? '';
$serendipity['GET']['page']           = $serendipity['GET']['page'] ?? 0;

if ($serendipity['GET']['filter']['show'] == 'approved') {
    $and          .= "AND status = 'approved'";
    $searchString .= '&amp;serendipity[filter][show]=approved';
} elseif ($serendipity['GET']['filter']['show'] == 'pending') {
    $and          .= "AND status = 'hidden' OR status = 'pending'";
    $searchString .= '&amp;serendipity[filter][show]=pending';
} elseif ($serendipity['GET']['filter']['show'] == 'confirm') {
    $and          .= "AND status = 'hidden' OR status LIKE 'confirm%'";
    $searchString .= '&amp;serendipity[filter][show]=confirm';
} else {
    $serendipity['GET']['filter']['show'] = 'all';
}

if ($serendipity['GET']['filter']['type'] == 'TRACKBACK') {
    $c_type = 'TRACKBACK';
    $searchString .= '&amp;serendipity[filter][type]=TRACKBACK';
} elseif ($serendipity['GET']['filter']['type'] == 'PINGBACK') {
    $c_type = 'PINGBACK';
    $searchString .= '&amp;serendipity[filter][type]=PINGBACK';
} elseif ($serendipity['GET']['filter']['type'] == 'NORMAL') {
    $c_type = 'NORMAL';
    $searchString .= '&amp;serendipity[filter][type]=NORMAL';
} else {
    $c_type = null;
}

if ($commentsPerPage != 10) {
    $searchString .= '&amp;serendipity[filter][perpage]=' . $commentsPerPage;
}

$searchString .= '&amp;' . serendipity_setFormToken('url');
$ctype = $c_type !== null ? " AND c.type = '$c_type' " : '';
$perm = !serendipity_checkPermission('adminEntriesMaintainOthers') ? 'AND e.authorid = ' . (int)$serendipity['authorid'] : '';

/* Paging */
$sql = serendipity_db_query("SELECT COUNT(*) AS total FROM {$serendipity['dbPrefix']}comments c
                          LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
                              WHERE 1 = 1 $ctype $perm $and", true);

$totalComments = $sql['total'];
$pages = ($commentsPerPage == COMMENTS_FILTER_ALL ? 1 : ceil($totalComments/(int)$commentsPerPage));
$page = (int)$serendipity['GET']['page'];
if ($page == 0 || $page > $pages) {
    $page = 1;
}

$linkPrevious = 'serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[page]='. ($page-1) . $searchString;
$linkNext     = 'serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[page]='. ($page+1) . $searchString;
$linkFirst    = 'serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[page]=' . 1 . $searchString;
$linkLast     = 'serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[page]=' . $pages . $searchString;

$filter_vals  = array(10, 20, 50, COMMENTS_FILTER_ALL);

if ($commentsPerPage == COMMENTS_FILTER_ALL) {
    $limit = '';
} else {
    $limit = serendipity_db_limit_sql(serendipity_db_limit(($page-1)*(int)$commentsPerPage, (int)$commentsPerPage));
}

$sql = serendipity_db_query("SELECT c.*, e.title FROM {$serendipity['dbPrefix']}comments c
                          LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
                              WHERE 1 = 1 $ctype $perm $and
                           ORDER BY c.id DESC $limit");

if (serendipity_checkPermission('adminComments')) {
    ob_start();
    # This event has to get send here so the spamblock-plugin can block an author now and the comment_page show that on this pageload
    serendipity_plugin_api::hook_event('backend_comments_top', $sql);
    $data['backend_comments_top'] = ob_get_contents();
    ob_end_clean();
}

$data['commentsPerPage'] = $commentsPerPage;
$data['totalComments']   = $totalComments;
$data['pages']           = $pages;
$data['page']            = $page;
$data['linkFirst']       = $linkFirst;
$data['linkPrevious']    = $linkPrevious;
$data['linkNext']        = $linkNext;
$data['linkLast']        = $linkLast;
$data['searchString']    = $searchString;
$data['filter_vals']     = $filter_vals;
$data['c_list']          = ((is_array($sql) && !empty($sql)) ? true : false);
$data['c_type']          = $c_type;

$i = 0;
$comments = array();
$cofakets = time();

if (is_array($sql)) {
    foreach($sql AS $rs) {
        $i++;
        $comment = array(
            'fullBody'  => $rs['body'],
            'summary'   => serendipity_mb('substr', $rs['body'], 0, $summaryLength),
            'status'    => $rs['status'],
            'type'      => $rs['type'],
            'stype'     => $rs['type'] == 'NORMAL' ? 'C' : ($rs['type'] == 'TRACKBACK' ? 'T' : 'P'),
            'id'        => $rs['id'],
            'title'     => $rs['title'],
            'timestamp' => $rs['timestamp'],
            'pubdate'   => date('c', (int)$rs['timestamp']), /* added to comment array to support HTML5 time tags in tpl */
            'referer'   => $rs['referer'],
            'url'       => $rs['url'],
            'ip'        => $rs['ip'],
            'entry_url' => serendipity_archiveURL($rs['entry_id'], $rs['title'], 'baseURL', true, array('timestamp' => $rs['timestamp'])),
            'email'     => $rs['email'],
            'author'    => (empty($rs['author']) ? ANONYMOUS : $rs['author']),
            'is_owner'  => ($rs['email'] === $serendipity['email'] && $rs['author'] === $serendipity['realname']),
            'entry_id'  => $rs['entry_id'],
            'subscribed'=> $rs['subscribed']
        );

        $entrylink = serendipity_archiveURL($comment['entry_id'], 'comments', 'serendipityHTTPPath', true, array('timestamp' => $cofakets)) . '#c' . $comment['id'];
        $comment['entrylink'] = $entrylink;

        if ($serendipity['allowHtmlComment']) {
            // this replaces stripping tags OR the serendipity_htmlspecialchars() usage
            $comment['fullBody'] = serendipity_sanitizeHtmlComments($comment['fullBody']);
            $is_html = ($comment['fullBody'] != strip_tags($comment['fullBody'])) ? true : false;
        }

        if (strlen($comment['fullBody']) > strlen($comment['summary']) ) {
            $comment['excerpt'] = true;
            // When summary is not the full body, strip any HTML tags from summary, as it might break and leave unclosed HTML.
            if ($serendipity['allowHtmlComment']) {
                $_summary = htmlspecialchars(str_replace('  ', ' ', strip_tags($comment['summary'])), ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, LANG_CHARSET, false);
                $stripped = ($comment['summary'] != $_summary) ? true : false;
                $comment['summary']  = $_summary;
                $comment['fullBody'] = $is_html ? $comment['fullBody'] : nl2br($comment['fullBody']);
            } else {
                $comment['summary']  = str_replace(array('\r\n','\n\r','\n','\r','  '), ' ', trim(strip_tags($comment['summary']))); // keep in mind: for "newline" search pattern are single, for replace double quotes!
                $comment['fullBody'] = nl2br(htmlspecialchars($comment['fullBody'], ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, LANG_CHARSET, false));
            }

        } else {
            if ($serendipity['allowHtmlComment']) {
                // excerpt allows to open up a non-stripped fullBody box, if summary was stripped before!
                $comment['excerpt']  = ($comment['summary'] < strip_tags($comment['summary'])) ? true : false;
                $comment['fullBody'] = $is_html ? $comment['fullBody'] : nl2br($comment['fullBody']);
                // Strip any HTML tags from summary, as it might break and leave unclosed HTML. But we want a space where previously was a tag following a tagged newline, like for "<p>xxx</p>\n<p>xxx</p>".
                $comment['summary'] = str_replace('  ', ' ', trim(strip_tags(str_replace('<', ' <', $comment['summary']))));
            } else {
                $comment['excerpt']  = (strlen($comment['summary']) < strlen(nl2br(strip_tags($comment['summary'])))) ? true : false; // allows to open up a non stripped fullBody box, if summary was stripped before!
                $comment['summary']  = htmlspecialchars(strip_tags($comment['summary']), ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, LANG_CHARSET, false);
                $comment['fullBody'] = nl2br(htmlspecialchars($comment['fullBody'], ENT_COMPAT | ENT_HTML401 | ENT_SUBSTITUTE, LANG_CHARSET, false));
            }
        }

        // Backend only: Do for both - else add ($serendipity['allowHtmlComment'] && )
        if ($comment['type'] == 'NORMAL' && serendipity_isCommentStripped($comment['summary'], $comment['excerpt'])) {
            if (empty($comment['summary'])) {
                $comment['summary'] .= '<span class="msg_error"><strong>Security Alert</strong>: Empty, since removed probably bad injection. Check with disabled HTML-comments mode and EDIT.</span>';
            } else {
                $comment['summary'] .= '<span class="summary_stripped" title="HTML - Stripped by security! Review content in EDIT or VIEW mode">&hellip;<span class="icon-code"></span></span>';
            }
        }
        serendipity_plugin_api::hook_event('backend_view_comment', $comment, '&amp;serendipity[page]='. $page . $searchString);

        $class = 'serendipity_admin_list_item_' . (($i % 2 == 0 ) ? 'even' : 'uneven');

        if ($comment['status'] == 'pending') {
            $class .= ' serendipity_admin_comment_pending';
            $header_class = 'serendipityAdminMsgNote serendipity_admin_comment_pending_header';
        } elseif (strstr($comment['status'], 'confirm')) {
            $class .= ' serendipity_admin_comment_pending serendipity_admin_comment_confirm';
            $header_class = 'serendipityAdminMsgNote serendipity_admin_comment_pending_header serendipity_admin_comment_confirm_header';
        } else {
            $header_class = '';
        }

        $comment['class'] = $class;
        $comment['header_class'] = $header_class;

        if (!empty($comment['url']) && substr($comment['url'], 0, 7) != 'http://' && substr($comment['url'], 0, 8) != 'https://') {
            $comment['url'] = 'http://' . $comment['url'];
        }
        // include all comment vars back into upper array to assign to Smarty
        $comments[] = $comment;
    }
}

$data['comments']      = $comments;
$data['errormsg']      = $errormsg;
$data['msg']           = $msg;
$data['msgtype']       = $msgtype;

$data['urltoken']      = serendipity_setFormToken('url');
$data['formtoken']     = serendipity_setFormToken();
$data['get']['filter'] = $serendipity['GET']['filter']; // don't trust {$smarty.get.vars} if not proofed, as we often change GET vars via serendipity['GET'] by runtime

if (!is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

echo serendipity_smarty_showTemplate('admin/comments.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
