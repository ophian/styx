{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="msg-notice search-msg"><span class="icon-info-circled" aria-hidden="true"></span> <b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>
{elseif isset($content_message)}
    <p class="msg-notice content-msg"><span class="icon-info-circled" aria-hidden="true"></span> {$content_message}</p>
{/if}
{$ENTRIES}
{$ARCHIVES|default:''}