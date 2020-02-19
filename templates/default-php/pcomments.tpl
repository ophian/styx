<?php $i=1; ?>
<?php foreach ($GLOBALS['tpl']['comments'] AS $comment):?>
<div id="c<?= isset($comment['id']) ? $comment['id'] : 0; ?>" class="serendipity_comment serendipity_comment_author_<?= serendipity_makeFilename($comment['author']); ?><?php if (isset($comment['entryauthor']) && $comment['entryauthor'] == $comment['author'] && $comment['authoremail'] == $comment['clear_email']): ?> serendipity_comment_author_self<?php endif; ?> <?php if ($i%2 == 0): ?>comment_oddbox<?php else: ?>comment_evenbox<?php endif; ?> commentlevel-<?= $comment['depth'] ?>">
    <div class="comment_header">
        <h4><?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?><strong>[TRACKBACK]</strong> <?= TRACKED ?>:<?php endif; ?><?php if ($comment['url']): ?><a href="<?= serendipity_specialchars($comment['url']) ?>"><?php endif; ?><?= $comment['author'] ? $comment['author'] : ANONYMOUS; ?> <?php if (isset($comment['entryauthor']) && $comment['entryauthor'] == $comment['author'] AND $comment['authoremail'] == $comment['clear_email']): ?> <span class="pc-owner">Post author</span> <?php endif; ?><?php if ($comment['url']): ?></a><?php endif; ?> <?= ON ?> <time datetime="<?= serendipity_formatTime(DATE_FORMAT_SHORT, $comment['timestamp']); ?>"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $comment['timestamp']); ?></time><?php if (isset($comment['meta'])): ?> | <time><?= serendipity_formatTime('%H:%M', $comment['timestamp']) ?></time><?php endif; ?>:</h4>
    </div>
<?php if ($comment['body'] != ''): ?>

    <div class="comment_content">
        <?= isset($comment['avatar']) ? $comment['avatar'] : ''; ?>
        <?php if (isset($comment['type']) && $comment['type'] == 'TRACKBACK'): ?><?= str_replace('  ', ' ', strip_tags($comment['body'])) ?> [&hellip;]<?php else: ?><?= $comment['body'] ?><?php endif; ?>
    </div>
<?php endif; ?>

</div>
<?php $i++; ?>
<?php endforeach; ?>
