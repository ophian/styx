<?php if ($GLOBALS['tpl']['is_raw_mode']): ?>
<div id="serendipity<?= $GLOBALS['tpl']['pluginside'] ?>SideBar">
<?php endif; ?>
<?php foreach($GLOBALS['tpl']['plugindata'] AS $item): ?>
<?php if (! empty($item['content']) && $item['class'] != 'serendipity_plugin_remoterss'): ?>
    <div class="serendipitySideBarItem container_<?= $item['class'] ?>">
        <?php if ($item['title'] != ''): ?><h3 class="serendipitySideBarTitle <?= $item['class'] ?>"><?= $item['title'] ?></h3><?php endif; ?>
        <div class="serendipitySideBarContent"><?= $item['content'] ?></div>
    </div>
<?php endif; ?>
<?php endforeach; ?>
<?php if ($GLOBALS['tpl']['is_raw_mode']): ?>
</div>
<?php endif; ?>
