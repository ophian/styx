<!-- CONTENT START -->

<?php if (isset($GLOBALS['tpl']['searchresult_tooShort'])): ?>
    <h3 class="serendipity_date"><?= QUICKSEARCH ?></h3>
    <div class="serendipity_search serendipity_search_tooshort"><?= $GLOBALS['tpl']['content_message']; ?></div>
<?php elseif (isset($GLOBALS['tpl']['searchresult_error'])): ?>
    <h3 class="serendipity_date"><?= QUICKSEARCH ?></h3>
    <div class="serendipity_search serendipity_search_error"><?= $GLOBALS['tpl']['content_message']; ?></div>
<?php elseif (isset($GLOBALS['tpl']['searchresult_noEntries'])): ?>
    <h3 class="serendipity_date"><?= QUICKSEARCH ?></h3>
    <div class="serendipity_search serendipity_search_noentries"><?= $GLOBALS['tpl']['content_message']; ?></div>
    <?php if (empty($GLOBALS['tpl']['searchresult_results']) && !empty($GLOBALS['tpl']['comment_searchresults']) && !empty($GLOBALS['tpl']['comment_results'])): ?><?= $GLOBALS['tpl']['comment_search_result']; ?><?php endif; ?>
<?php elseif (isset($GLOBALS['tpl']['searchresult_results'])): ?>
    <h3 class="serendipity_date"><?= QUICKSEARCH ?></h3>
    <div class="serendipity_search serendipity_search_results"><?= $GLOBALS['tpl']['content_message']; ?></div>
<?php elseif (isset($GLOBALS['tpl']['content_message'])): ?>
    <div class="serendipity_content_message"><?= $GLOBALS['tpl']['content_message']; ?></div>
<?php endif; ?>

<?= $GLOBALS['tpl']['ENTRIES']; ?>
<?= $GLOBALS['tpl']['ARCHIVES']; ?>

<!-- CONTENT END -->
