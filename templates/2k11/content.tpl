{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    {if $content_message|strip == $content_message}<p class="serendipity_search"><b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>{else}{$content_message}{/if}
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<p class="content_msg">{$content_message}</p>{else}{$content_message}{/if}
{/if}
{$ENTRIES}
{$ARCHIVES}
