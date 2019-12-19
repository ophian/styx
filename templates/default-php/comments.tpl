<?php $i=1; ?>
<?php foreach ($GLOBALS['tpl']['comments'] AS $comment): ?>
    <a id="c<?= isset($comment['id']) ? $comment['id'] : 0; ?>"></a>
    <div id="serendipity_comment_<?= isset($comment['id']) ? $comment['id'] : 0; ?>" class="serendipity_comment serendipity_comment_author_<?= serendipity_makeFilename($comment['author']); ?><?php if ( isset($GLOBALS['tpl']['entry']) && $GLOBALS['tpl']['entry']['author'] == $comment['author'] && $GLOBALS['tpl']['entry']['email'] == $GLOBALS['tpl']['commentform_entry']['email']): ?> serendipity_comment_author_self<?php endif; ?> <?php if($i%2 == 0): ?>comment_oddbox<?php else: ?>comment_evenbox<?php endif; ?>" style="padding-left: <?= ($comment['depth']*20) ?>px">
        <div class="serendipity_commentBody">
        <?php if ($comment['body'] == 'COMMENT_DELETED'): ?>
            <?= COMMENT_IS_DELETED ?>
        <?php else: ?>
            <?= $comment['body'] ?><?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?> [&hellip;]<?php endif; ?>
            <?= ($comment['preview_editstatus'] ?? '') ?>
        <?php endif; ?>
        </div>
        <div class="serendipity_comment_source">
         <?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?>
            <strong>[TRACKBACK]</strong> <?= TRACKED ?>:
        <?php endif; ?>
            <a class="comment_source_trace" href="#c<?= isset($comment['id']) ? $comment['id'] : 0; ?>">#<?= $comment['trace'] ?></a>
            <span class="comment_source_author">
         <?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?>
            <strong><?= WEBLOG ?>:</strong>
        <?php endif; ?>
            <?php if ($comment['email']): ?>
                <a href="mailto:<?= $comment['email'] ?>"><?= $comment['author'] ? $comment['author'] : ANONYMOUS; ?></a>
            <?php else: ?>
                <?= $comment['author'] ? $comment['author'] : ANONYMOUS; ?>
            <?php endif; ?>
            <?php if (isset($comment['entryauthor']) && $comment['entryauthor'] == $comment['author'] AND isset($GLOBALS['tpl']['entry']) AND $GLOBALS['tpl']['entry']['email'] == $comment['clear_email']): ?> <span class="pc-owner">Post author</span> <?php endif; ?>
         <?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?>
            <br />
            <?= IN ?> <?= TITLE ?>: <span class="comment_source_ctitle"><?= $comment['ctitle'] ?></span>
        <?php endif; ?>
            </span>
            <?php if ($comment['url']): ?>
                (<a class="comment_source_url" href="<?= serendipity_specialchars($comment['url']) ?>" title="<?= serendipity_specialchars($comment['url']); ?>"><?= HOMEPAGE ?></a>)
            <?php endif; ?>
            <?= ON ?>
            <span class="comment_source_date"><?= serendipity_formatTime(DATE_FORMAT_SHORT, $comment['timestamp']); ?></span>

            <?php if (isset($GLOBALS['tpl']['entry']) && @$GLOBALS['tpl']['entry']['is_entry_owner'] && !empty($comment['id'])): ?>
                (<a class="comment_source_ownerlink" href="<?= $comment['link_delete'] ?>" onclick="return confirm('<?= sprintf(COMMENT_DELETE_CONFIRM, (isset($comment['id']) ? $comment['id'] : 0), $comment['author']); ?>');"><?= DELETE ?></a>)
            <?php endif; ?>
            <?php if (isset($comment['id']) && !empty($GLOBALS['tpl']['commentform_entry']['allow_comments']) && $comment['body'] != 'COMMENT_DELETED'): ?>
                (<a class="comment_reply" href="#serendipity_CommentForm" id="serendipity_reply_<?= $comment['id'] ?>" onclick="document.getElementById('serendipity_replyTo').value='<?= $comment['id'] ?>'; <?= @$GLOBALS['tpl']['comment_onchange'] ?>"><?= REPLY ?></a>)
                <div id="serendipity_replyform_<?= $comment['id'] ?>"></div>
            <?php endif; ?>
        </div>
    </div>
    <?php $i++; ?>
<?php endforeach; ?>
<?php if (empty($GLOBALS['tpl']['comments'])): ?>
    <div class="serendipity_center nocomments"><?= NO_COMMENTS ?></div>
<?php endif; ?>
