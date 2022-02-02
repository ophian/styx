<?php serendipity_plugin_api::hook_event('entries_header', $GLOBALS['tpl']['entry_id']); ?>
<div class="serendipity_date"><?php if ($GLOBALS['tpl']['dateRange'][0] === 1 || isset($GLOBALS['tpl']['footer_currentPage'])): ?><?= $GLOBALS['tpl']['head_subtitle'] ?><?php if ($GLOBALS['tpl']['footer_prev_page'] || $GLOBALS['tpl']['footer_next_page']): ?> <span class="archive_summary_pageof"><?= PAGE ?>/<?= $GLOBALS['tpl']['footer_currentPage'] ?></span><?php endif; ?><?php else: ?><?= TOPICS_OF ?> <?= serendipity_formatTime("%B, %Y", $GLOBALS['tpl']['dateRange'][0]); ?><?php endif; ?></div>

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
<?php if (!isset($GLOBALS['tpl']['$is_single_entry']) && !$GLOBALS['tpl']['is_preview'] && !$GLOBALS['tpl']['plugin_clean_page'] && (!empty($GLOBALS['tpl']['footer_prev_page']) OR !empty($GLOBALS['tpl']['footer_next_page']))): ?>

    <div class="serendipity_entries_footer">
    <?php if ($GLOBALS['tpl']['footer_prev_page']): ?>
        <a href="<?= $GLOBALS['tpl']['footer_prev_page'] ?>">&laquo; <?= PREVIOUS_PAGE; ?></a>&#160;&#160;
    <?php endif; ?>

    <?php if (!empty($GLOBALS['tpl']['footer_info'])): ?>
        (<?= $GLOBALS['tpl']['footer_info'] ?>)
    <?php endif; ?>

    <?php if ($GLOBALS['tpl']['footer_next_page']): ?>
        <a href="<?= $GLOBALS['tpl']['footer_next_page'] ?>">&raquo; <?= NEXT_PAGE; ?></a>
    <?php endif; ?>
    </div>
<?php endif; ?>
<div class="serendipity_entryFooter">
<?php serendipity_plugin_api::hook_event('entries_footer', $GLOBALS['tpl']['entry_id']); ?>
</div>
