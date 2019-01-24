<div class="staticpage_results">
    <p class="staticpage_result_header"><?= sprintf(STATICPAGE_SEARCHRESULTS, $GLOBALS['tpl']['staticpage_searchresults']) ?></p>

    <?php if ($GLOBALS['tpl']['staticpage_results']): ?>
    <ul class="staticpage_result">
    <?php foreach ($GLOBALS['tpl']['staticpage_results'] AS $result):?>
        <li><strong><a href="<?= $result['permalink'] ?>" title="<?= $result['pagetitle'] ?>"><?php if (!empty($result['headline'])): ?><?= $result['headline'] ?><?php else: ?><?= $result['pagetitle'] ?><?php endif; ?></a></strong> (<?= $result['realname'] ?>)<br>
        <?= substr(preg_replace('/(?:[ \t]*(?:\n|\r\n?)){2,}/', " ", strip_tags($result['content'])), 0, 200) ?>&hellip;</li>
    <?php endforeach; ?>
    </ul>
    <?php endif; ?>
</div>
