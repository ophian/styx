<!-- CONTENT START -->

{if isset($searchresult_tooShort)}
    <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
    <div class="serendipity_search serendipity_search_tooshort">{$content_message}</div>
{elseif isset($searchresult_error)}
    <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
    <div class="serendipity_search serendipity_search_error">{$content_message}</div>
{elseif isset($searchresult_noEntries)}
    <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
    <div class="serendipity_search serendipity_search_noentries">{$content_message}</div>
    {if empty($searchresult_results) AND NOT empty($comment_searchresults) AND NOT empty($comment_results)}{$comment_search_result}{/if}
{elseif isset($searchresult_results)}
    <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
    <div class="serendipity_search serendipity_search_results">{$content_message}</div>
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<div class="serendipity_content_message">{$content_message}</div>{else}{$content_message}{/if}
{/if}

{$ENTRIES}
{$ARCHIVES}

<!-- CONTENT END -->
