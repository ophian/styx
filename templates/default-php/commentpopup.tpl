<!DOCTYPE html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
<head>
    <meta charset="<?= $GLOBALS['tpl']['head_charset'] ?>">
    <title><?= !empty($GLOBALS['tpl']['head_title']) ? $GLOBALS['tpl']['head_title'] : $GLOBALS['tpl']['blogTitle']; ?> <?php if ($GLOBALS['tpl']['head_subtitle']): ?> - <?= $GLOBALS['tpl']['head_subtitle'] ?><?php endif; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Powered-By" content="Serendipity Styx Edition v.<?= $GLOBALS['tpl']['serendipityVersion'] ?>">
    <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?>serendipity.css.php">
<?php serendipity_plugin_api::hook_event('frontend_header', $GLOBALS['tpl']); ?>
</head>

<body id="serendipity_comment_page" class="s9y_wrap">

<?php if (!empty($GLOBALS['tpl']['is_comment_added'])): ?>

    <div class="popup_comments_message popup_comments_message_added"><?= COMMENT_ADDED ?><?= $GLOBALS['tpl']['comment_string'][0] ?><a href="<?= $GLOBALS['tpl']['comment_url'] ?>"><?= $GLOBALS['tpl']['comment_string'][1] ?></a><?= $GLOBALS['tpl']['comment_string'][2] ?><a href="#" onclick="self.close()"><?= $GLOBALS['tpl']['comment_string'][3] ?></a><?= $GLOBALS['tpl']['comment_string'][4] ?></div>

<?php elseif (!empty($GLOBALS['tpl']['is_comment_notadded'])): ?>

    <div class="popup_comments_message popup_comments_message_notadded"><?= COMMENT_NOT_ADDED ?><?= $GLOBALS['tpl']['comment_string'][0] ?><a href="<?= $GLOBALS['tpl']['comment_url'] ?>"><?= $GLOBALS['tpl']['comment_string'][1] ?></a><?= $GLOBALS['tpl']['comment_string'][2] ?><a href="#" onclick="self.close()"><?= $GLOBALS['tpl']['comment_string'][3] ?></a><?= $GLOBALS['tpl']['comment_string'][4] ?></div>

<?php elseif (!empty($GLOBALS['tpl']['is_comment_empty'])): ?>

    <div class="popup_comments_message popup_comments_message_empty"><?= $GLOBALS['tpl']['comment_string'][0] ?><a href="#" onclick="history.go(-1)"><?= $GLOBALS['tpl']['comment_string'][1] ?></a></div>

<?php elseif (!empty($GLOBALS['tpl']['is_showtrackbacks'])): ?>

    <h3 class="popup_content serendipity_commentsTitle"><?= TRACKBACKS ?></h3>
    <dl>
        <dt><strong><?= TRACKBACK_SPECIFIC ?>:</strong></dt>
        <dd><a rel="nofollow" href="<?= $GLOBALS['tpl']['comment_url'] ?>"><?= $GLOBALS['tpl']['comment_url'] ?></a></dd>

        <dt><strong><?= DIRECT_LINK ?>:</strong></dt>
        <dd><a href="<?= $GLOBALS['tpl']['comment_entryurl'] ?>"><?= $GLOBALS['tpl']['comment_entryurl'] ?></a></dd>
    </dl>

    <?= $GLOBALS['template']->call('printTrackbacks', array('entry' => $GLOBALS['tpl']['entry_id'])); ?>

    <br>

<?php elseif ($GLOBALS['tpl']['is_showcomments']): ?>

    <h3 class="popup_content serendipity_commentsTitle"><?= COMMENTS ?></h3>

<?php /* NO popup viewmode possible without assigning/scoping entry array or assigning these variables singularly */ ?>

    <div id="serendipity_commentlist">
    <?= $GLOBALS['template']->call('printComments', array('entry' => $GLOBALS['tpl']['entry_id'])); ?>
    </div>
    <?php if ($GLOBALS['tpl']['is_comment_allowed']): ?>
        <div class="serendipity_commentsTitle"><?= ADD_COMMENT ?></div>
        <?= $GLOBALS['tpl']['COMMENTFORM'] ?>
    <?php else: ?>
        <br>
        <div class="serendipity_center serendipity_msg_important"><?= COMMENTS_CLOSED ?></div>
    <?php endif; ?>

<?php endif; ?>

<?php serendipity_plugin_api::hook_event('frontend_footer', $GLOBALS['tpl']); ?>
</body>
</html>
