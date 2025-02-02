<?php if (is_array($GLOBALS['tpl']['trackbacks'])):
foreach($GLOBALS['tpl']['trackbacks'] AS $trackback): ?>
    <div class="serendipity_comment">
        <a id="c<?= $trackback['id'] ?>"></a>
        <div class="serendipity_commentBody">
            <a href="<?= strip_tags($trackback['url']); ?>" <?php serendipity_xhtml_target('blank'); ?>><?= $trackback['title'] ?></a><?php if ($trackback['type'] == 'TRACKBACK'): ?><br><?php endif; ?>
            <?= htmlspecialchars(strip_tags($trackback['body'])); ?> [&hellip;]
        </div>
        <div class="serendipity_comment_source">
            <b>Weblog:</b> <?= $trackback['author'] ? $trackback['author'] : ANONYMOUS; ?><br>
            <b><?= TRACKED ?>:</b> <?= serendipity_formatTime('%b %d, %H:%M', $trackback['timestamp']); ?>
        <?php if ($GLOBALS['tpl']['commentform_entry']['is_entry_owner']): ?>
            (<a href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>comment.php?serendipity[delete]=<?= $trackback['id'] ?>&amp;serendipity[entry]=<?= $trackback['entry_id'] ?>&amp;serendipity[type]=trackbacks"><?= DELETE ?></a>)
        <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>
<?php endif; ?>
<?php if (empty($GLOBALS['tpl']['trackbacks'])): ?>
    <div class="serendipity_center"><?= NO_TRACKBACKS ?></div>
<?php endif; ?>
