<!-- ENTRIES START -->

<?php if ($GLOBALS['tpl']['entries']): ?>
<?= STATICPAGE_NEW_HEADLINES ?>

<ul>
<?php foreach ($GLOBALS['tpl']['entries'] AS $dategroup): ?>
    <?php foreach ($GLOBALS['tpl']['dategroup']['entries'] AS $entry): ?>
    <li class="static-entries">
        (<?= serendipity_formatTime("%d.%m.%Y", $dategroup['date']); ?>) <a href="<?= $entry['link'] ?>"><?= (!empty($entry['title']) ? $entry['title'] : $entry['id']) ?></a>
    </li>
    <?php endforeach; ?>
<?php endforeach; ?>
</ul>


<?php /*  for normal static pages  */ ?>
&raquo; <a href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?><?php $GLOBALS['template']->call('getCategoryLinkByID', array('cid' => $GLOBALS['tpl']['staticpage_related_category_id'])); ?>"><?= STATICPAGE_ARTICLE_OVERVIEW ?></a><br />

<?php /* for a staticpage as startpage  */ ?>
<?php /* &raquo; <a href="<?= $GLOBALS['tpl']['serendipityArchiveURL'] ?>/P1.html"><?= STATICPAGE_ARTICLE_OVERVIEW ?></a><br />  */ ?>

<?php endif; ?>
<!-- ENTRIES END -->