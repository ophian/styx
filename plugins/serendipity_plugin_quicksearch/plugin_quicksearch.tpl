<form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search">
    <input type="hidden" name="serendipity[action]" value="search" />
    <input type="hidden" name="serendipity[fullentry]" value="{$fullentry}" />
    <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="Search term(s)" value="" />
    <label for="serendipityQuickSearchTermField"><span class="icon-search" aria-hidden="true"></span><span class="fallback-text">{$CONST.QUICKSEARCH}</span></label>
    <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}" />
</form>
{serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
