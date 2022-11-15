{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="search_posts"><b>{$CONST.QUICKSEARCH}</b> {$content_message}</p>
    {if empty($searchresult_results) AND NOT empty($comment_searchresults) AND NOT empty($comment_results)}{$comment_search_result}{/if}
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<p class="content-msg">{$content_message}</p>{else}{$content_message}{/if}
{/if}
{$ENTRIES}
{$ARCHIVES}
