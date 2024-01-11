{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="alert alert-primary" role="alert"><b>{$CONST.QUICKSEARCH}:</b> {$content_message}{if NOT empty($footer_info)}<span class="searchpage float-end">{$CONST.PAGE} {$footer_currentPage}/{$footer_totalPages}</span>{/if}</p>
{if empty($searchresult_results) AND NOT empty($comment_searchresults) AND NOT empty($comment_results)}
    {$comment_search_result}
{/if}
{elseif isset($content_message)}
{if $content_message|strip == $content_message}{if $content_message == $CONST.URL_NOT_FOUND}
    <p class="alert alert-danger">
{else}
    <p>
{/if}
        {$content_message}
    </p>
{else}
    {$content_message}
{/if}
{/if}
{$ENTRIES}
{$ARCHIVES}