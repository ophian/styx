{if isset($searchresult_tooShort) OR isset($searchresult_error) OR isset($searchresult_noEntries) OR isset($searchresult_results)}
    <p class="alert alert-primary" role="alert"><b>{$CONST.QUICKSEARCH}:</b> {$content_message}</p>
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}{if $content_message == $CONST.URL_NOT_FOUND}<p class="alert alert-info" role="alert">{else}<p>{/if}{$content_message}</p>{else}{$content_message}{/if}
{/if}
{$ENTRIES}
{$ARCHIVES}