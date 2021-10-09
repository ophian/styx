<?php if ($GLOBALS['tpl']['view'] == 'comments'): ?>
<?php if ($GLOBALS['tpl']['typeview'] == 'comments'): ?>
    <h2 class="comments_permalink"><?=WEBLOG?> <?=COMMENTS?></h2>
<?php elseif ($GLOBALS['tpl']['typeview'] == 'trackbacks'): ?>
    <h2 class="comments_permalink"><?=WEBLOG?> <?=TRACKBACKS?></h2>
<?php elseif ($GLOBALS['tpl']['typeview'] == 'pingbacks'): ?>
    <h2 class="comments_permalink"><?=WEBLOG?> <?=PINGBACKS?></h2>
<?php elseif ($GLOBALS['tpl']['typeview'] == 'comments_and_trackbacks'): ?>
    <h2 class="comments_permalink"><?=WEBLOG?> <?=COMMENTS?>/<?=TRACKBACKS?>/<?=PINGBACKS?></h2>
<?php endif; ?>
<?php endif; ?>

<div class="comments_by_author">
<?php foreach ($GLOBALS['tpl']['comments_by_authors'] AS $entry_comments):?>
    <article class="serendipity_entry">
        <h4 class="serendipity_title"><a href="<?=$entry_comments['link']?>"><?=($entry_comments['title'] ? $entry_comments['title'] : $entry_comments['link'])?></a></h4>
        <?php /* tpl_comments is the already parsed "pcomments.tpl" template! */ ?>
        <div class="comments_for_entry">
            <?=$entry_comments['tpl_comments']?>
        </div>
    </article>
<?php endforeach; ?>
</div>

<div class="comments_by_author_pagination bottom">
<?php if ($GLOBALS['tpl']['footer_prev_page']): ?>
    <a href="<?= $GLOBALS['tpl']['footer_prev_page'] ?>">&laquo; <?=PREVIOUS_PAGE?></a>&#160;&#160;
<?php endif; ?>
<?php if (!empty($GLOBALS['tpl']['footer_info'])): ?>
    (<?=$GLOBALS['tpl']['footer_info']?>)
<?php endif; ?>
<?php if ($GLOBALS['tpl']['footer_next_page']): ?>
    <a href="<?=$GLOBALS['tpl']['footer_next_page']?>">&raquo; <?=NEXT_PAGE?></a>
<?php endif; ?>
<?php serendipity_plugin_api::hook_event('comments_by_author_footer', $GLOBALS['tpl']) ?>
</div>
