<div class="comments_by_author_pagination" style="text-align: center">
<?php if($GLOBALS['tpl']['footer_prev_page']): ?>
    <a href="<?= $GLOBALS['tpl']['footer_prev_page'] ?>">&laquo; <?= PREVIOUS_PAGE ?></a>&#160;&#160;
<?php endif; ?>
<?php if($GLOBALS['tpl']['footer_info']): ?>
    (<?= $GLOBALS['tpl']['footer_info'] ?>)
<?php endif; ?>
<?php if($GLOBALS['tpl']['footer_next_page']): ?>
    <a href="<?= $GLOBALS['tpl']['footer_next_page'] ?>">&raquo; <?= NEXT_PAGE ?></a>
<?php endif; ?>
<?php $template = str_replace('\\', '/', dirname(__FILE__).'/comments_by_author.tpl'); ?>
<?php serendipity_plugin_api::hook_event('comments_by_author_footer', $template) ?>
</div>

<div class="comments_by_author">
<?php foreach ($GLOBALS['tpl']['comments_by_authors'] AS $entry_comments):?>
    <div class="serendipity_entry">
        <h4 class="serendipity_title"><a href="<?= $entry_comments['link'] ?>"><?= ($entry_comments['title'] ? $entry_comments['title'] : $entry_comments['link']) ?></a></h4>
        <?php /* tpl_comments is the already parsed "comments.tpl" template! */ ?>
        <div class="comments_for_entry">
        <?= $entry_comments['tpl_comments'] ?>
        </div>
    </div>
<?php endforeach; ?>
</div>
