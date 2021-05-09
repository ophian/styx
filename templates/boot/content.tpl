{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="serendipity_msg_notice search_msg"><strong>{$CONST.QUICKSEARCH}:</strong> {$content_message}</p>
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<p class="serendipity_msg_notice content_msg">{$content_message}</p>{else}{$content_message}{/if}
{/if}
{$ENTRIES}
{$ARCHIVES}
