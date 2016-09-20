{if $searchresult_tooShort OR $searchresult_error OR $searchresult_noEntries OR $searchresult_results}
    <p class="search_posts"><b>{$CONST.QUICKSEARCH}</b> {$content_message}</p>
{elseif $content_message}
    <p>{$content_message}</p>
{/if}
{$ENTRIES}
{$ARCHIVES}
