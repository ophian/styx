<form id="searchform" action="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?><?= $GLOBALS['tpl']['serendipityIndexFile'] ?>" method="get" role="search">
    <input type="hidden" name="serendipity[action]" value="search">
    <input type="hidden" name="serendipity[fullentry]" value="<?= $GLOBALS['tpl']['fullentry'] ?>">
    <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="Search term(s)" value="">
    <label for="serendipityQuickSearchTermField"><span class="icon-search" aria-hidden="true"></span><span class="fallback-text"><?= QUICKSEARCH ?></span></label>
    <input id="searchsend" name="serendipity[searchButton]" type="submit" value="<?= GO ?>">
</form>
<?php serendipity_smarty_hookPlugin(array('hook' => 'quicksearch_plugin', 'hookAll' => 'true'), $GLOBALS['tpl']); ?>
