<?php serendipity_plugin_api::hook_event('entries_header', $GLOBALS['tpl']['entry_id']); ?>
<h3 class="serendipity_date"><?= ARCHIVES ?><?php if (!empty($GLOBALS['tpl']['category_info']['categoryid'])):?> :: <?= $GLOBALS['tpl']['category_info']['category_name'] ?><?php endif; ?></h3>
<?php if (is_array($GLOBALS['tpl']['archives'])):
foreach ($GLOBALS['tpl']['archives'] AS $archive): if ($archive['sum'] === 0): continue; endif; ?>
<table class="archives_listing">
    <tr class="archives_header">
        <td class="archives_header" colspan="5"><h2><?= $archive['year'] ?></h2></td>
    </tr>
    <?php foreach ($archive['months'] AS $month):?>
    <tr class="archives_row">
        <td class="archives_graph" width="100"><img src="<?= serendipity_getTemplateFile("img/graph_bar_horisontal.png"); ?>" height="10" width="<?= ceil(($month['entry_count'] * 100 / $GLOBALS['tpl']['max_entries'])) ?>"></td>
        <td class="archives_date"><?= serendipity_formatTime("%B", $month['date']); ?></td>
        <td class="archives_count"><?= $month['entry_count'] ?> <?= ENTRIES ?></td>
        <td class="archives_count_link">(<?php if ($month['entry_count']): ?><a href="<?= $month['link'] ?>"><?php endif; ?><?= VIEW_FULL ?><?php if ($month['entry_count']): ?></a><?php endif; ?>)</td>
        <td class="archives_link">(<?php if ($month['entry_count']): ?><a href="<?= $month['link_summary'] ?>"><?php endif; ?><?= VIEW_TOPICS ?><?php if ($month['entry_count']): ?></a><?php endif; ?>)</td>
    </tr>
    <?php endforeach; ?>
</table>
<?php endforeach; ?>
<?php endif; ?>
<div class="serendipity_entries_footer">
    <?php serendipity_plugin_api::hook_event('entries_footer', $GLOBALS['tpl']['entry_id']); ?>
</div>
