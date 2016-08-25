<aside class="comment_results">
    <h3><?= printf(COMMENT_SEARCHRESULTS, $GLOBALS['tpl']['comment_searchresults']) ?>:</h3>
    <?php if ($GLOBALS['tpl']['comment_results']): ?>
    <ul class="plainList">
    <?php foreach ($GLOBALS['tpl']['comment_results'] AS $result): ?>
        <li><span class="block_level"><?php if ($result['type'] == 'TRACKBACK'): ?><a href="<?= $result['url'] ?>"><?php else: ?><b><?php endif; ?><?= $result['author'] ?><?php if ($result['type'] == 'TRACKBACK'): ?></a><?php else: ?></b><?php endif; ?> <?= IN ?> <a href="<?= $result['permalink'] ?>"><?= $result['title'] ?></a> <?= ON ?> <time datetime="<?= date("c", $result['ctimestamp']) ?>"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $result['ctimestamp']); ?></time>:</span>
        <?= substr(strip_tags($GLOBALS['tpl']['result']['comment']), 0, 200) ?>&hellip;
    </li>
    <?php endforeach; ?>
    </ul>
    <?php else: ?>
    <p><?= NO_ENTRIES_TO_PRINT ?></p>
    <?php endif; ?>
</aside>
