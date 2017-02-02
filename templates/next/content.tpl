{if $searchresult_tooShort OR $searchresult_error OR $searchresult_noEntries OR $searchresult_results}
    <p class="msg-notice search-msg"><span class="icon-info-circled" aria-hidden="true"></span> <b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>
{elseif $content_message}
    <p class="msg-notice content-msg"><span class="icon-info-circled" aria-hidden="true"></span> {$content_message}</p>
{/if}
{$ENTRIES}
{$ARCHIVES}
