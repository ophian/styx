<!-- CONTENT START -->

{if isset($searchresult_tooShort)}
    <div class="serendipity_search serendipity_search_tooshort">{$content_message}</div>
{elseif isset($searchresult_error)}
    <div class="serendipity_search serendipity_search_error">{$content_message}</div>
{elseif isset($searchresult_noEntries)}
    <div class="serendipity_search serendipity_search_noentries">{$content_message}</div>
{elseif isset($searchresult_results)}
    <div class="serendipity_search serendipity_search_results">{$content_message}</div>
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<div class="serendipity_content_message">{$content_message}</div>{else}{$content_message}{/if}
{/if}

{$ENTRIES}
{$ARCHIVES}

<!-- CONTENT END -->
