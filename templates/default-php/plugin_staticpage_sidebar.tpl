
<!-- plugin based plugin_staticpage_sidebar.tpl file -->

<?php if ($GLOBALS['tpl']['is_raw_mode']): ?>
<div class="staticpage_sbList">
<?php endif; ?>
<?php if (!empty($GLOBALS['tpl']['staticpage_jsStr'])): ?>
    <div class="staticpage_sbJsList">
    <?= $GLOBALS['tpl']['staticpage_jsStr'] ?>
    </div>
<?php endif; ?>
<?php if (!$GLOBALS['tpl']['staticpage_jsStr'] || empty($GLOBALS['tpl']['staticpage_jsStr'])): ?>
    <?php if (!empty($GLOBALS['tpl']['frontpage_path'])): ?>
        <a class="spp_title" href="<?= $GLOBALS['tpl']['frontpage_path'] ?>"><?= PLUGIN_STATICPAGELIST_FRONTPAGE_LINKNAME ?></a>
    <?php endif; ?>
    <?php if (is_array($GLOBALS['tpl']['staticpage_listContent']) && !empty($GLOBALS['tpl']['staticpage_listContent'])): ?>
        <?php foreach ($GLOBALS['tpl']['staticpage_listContent'] AS $pageList):?>
            <?php if (!empty($pageList['permalink'])): ?>
        <a class="spp_title" href="<?= $pageList['permalink'] ?>" title="<?= serendipity_specialchars($pageList['pagetitle']) ?>" style="padding-left: <?= $pageList['depth'] ?>px;"><?= substr($pageList['headline'], 0, 32); ?>&hellip;</a>
            <?php else: ?>
        <span class="spp_title" style="padding-left: <?= $pageList['depth'] ?>px;"><?= substr($pageList['headline'], 0, 32); ?>&hellip;</span>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php endif; ?>
<?php endif; ?>
<?php if ($GLOBALS['tpl']['is_raw_mode']): ?>
</div>
<?php endif; ?>
