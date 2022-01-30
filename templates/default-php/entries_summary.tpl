<?php serendipity_plugin_api::hook_event('entries_header', $GLOBALS['tpl']['entry_id']); ?>
<div class="serendipity_date"><?= TOPICS_OF ?> <?= serendipity_formatTime("%B, %Y", $GLOBALS['tpl']['dateRange'][0]); ?></div>

<div class="serendipity_entry">
    <ul>
    <?php foreach ($GLOBALS['tpl']['entries'] AS $sentries):?>
        <?php foreach ($sentries['entries'] AS $entry):?>
            <li><a href="<?= $entry['link'] ?>"><?= $entry['title'] ?></a>
                <div class="summary_posted_by"><?= POSTED_BY ?> <span class="posted_by_author"><?= $entry['author'] ?></span> <?= ON ?> <span class="posted_by_date"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $entry['timestamp']); ?></span></div>
            </li>
        <?php endforeach; ?>
    <?php endforeach; ?>
    </ul>
</div>
<div class="serendipity_entryFooter">
<?php serendipity_plugin_api::hook_event('entries_footer', $GLOBALS['tpl']['entry_id']); ?>
</div>
