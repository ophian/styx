{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="serendipity_search"><b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>
{elseif isset($content_message)}
    <p class="content_msg">{$content_message}</p>
{/if}
{$ENTRIES}
{$ARCHIVES|default:''}
