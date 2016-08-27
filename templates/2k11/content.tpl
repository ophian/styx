{if $searchresult_tooShort OR $searchresult_error OR $searchresult_noEntries OR $searchresult_results}
    <p class="serendipity_search"><b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>
{elseif $content_message}
    <p class="content_msg">{$content_message}</p>
{/if}
{$ENTRIES}
{$ARCHIVES}
