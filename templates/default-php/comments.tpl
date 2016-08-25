<?php $i=1; ?>
<?php foreach ($GLOBALS['tpl']['comments'] AS $comment): ?>
    <a id="c<?= $comment['id'] ?>"></a>
    <div id="serendipity_comment_<?= $comment['id'] ?>" class="serendipity_comment serendipity_comment_author_<?= serendipity_makeFilename($comment['author']); ?> <?php if ($entry['author'] == $comment['author']): ?>serendipity_comment_author_self<?php endif; ?><?php if($i%2 == 0): ?>comment_oddbox<?php else: ?>comment_evenbox<?php endif; ?>" style="padding-left: <?= ($comment['depth']*20) ?>px">
        <div class="serendipity_commentBody">
        <?php if ($comment['body'] == 'COMMENT_DELETED'): ?>
            <?= COMMENT_IS_DELETED ?>
        <?php else: ?>
            <?= $comment['body'] ?>
        <?php endif; ?>
        </div>
        <div class="serendipity_comment_source">
            <a class="comment_source_trace" href="#c<?= $comment['id'] ?>">#<?= $comment['trace'] ?></a>
            <span class="comment_source_author">
            <?php if ($comment['email']): ?>
                <a href="mailto:<?= $comment['email'] ?>"><?= $comment['author'] ? $comment['author'] : ANONYMOUS; ?></a>
            <?php else: ?>
                <?= $comment['author'] ? $comment['author'] : ANONYMOUS; ?>
            <?php endif; ?>
            </span>
            <?php if ($comment['url']): ?>
                (<a class="comment_source_url" href="<?= $comment['url'] ?>" title="<?= serendipity_specialchars($comment['url']); ?>"><?= HOMEPAGE ?></a>)
            <?php endif; ?>
            <?= ON ?>
            <span class="comment_source_date"><?= serendipity_formatTime($comment['timestamp'], DATE_FORMAT_SHORT); ?></span>

            <?php if ($entry['is_entry_owner']): ?>
                (<a class="comment_source_ownerlink" href="<?= $comment['link_delete'] ?>" onclick="return confirm('<?= printf(COMMENT_DELETE_CONFIRM, $comment['id'], $comment['author']); ?>');"><?= DELETE ?></a>)
            <?php endif; ?>
            <?php if ($entry['allow_comments'] AND $comment['body'] != 'COMMENT_DELETED'): ?>
                (<a class="comment_reply" href="#serendipity_CommentForm" id="serendipity_reply_<?= $comment['id'] ?>" onclick="document.getElementById('serendipity_replyTo').value='<?= $comment['id'] ?>'; <?= $GLOBALS['tpl']['comment_onchange'] ?>"><?= REPLY ?></a>)
                <div id="serendipity_replyform_<?= $comment['id'] ?>"></div>
            <?php endif; ?>
        </div>
    </div>
    <?php $i++; ?>
<?php endforeach; ?>
<?php if (empty($GLOBALS['tpl']['comments'])): ?>
    <div class="serendipity_center nocomments"><?= NO_COMMENTS ?></div>
<?php endif; ?>