{foreach $remoterss_items.items AS $item}

<div class="rss_item">
    {if $remoterss_items.use_rss_link}
    <div class="rss_link"><a href="{$item.link|escape}"{if $remoterss_items.target} target="{$remoterss_items.target}"{/if}>
    {/if}
    {if $remoterss_items.bulletimg}
        <img src="{$remoterss_items.bulletimg}" border="0" alt="**" />
    {/if}
    {foreach $item.display_elements AS $entry}
        {if NOT $entry@first}<span class="rss_{$entry@key}">{/if}{$entry|escape}{if NOT $entry@first}</span>{/if}
    {if $entry@first}</a></div>{/if}
    {foreachelse}</a></div>
    {/foreach}
    {if false !== $item.timestamp AND $remoterss_items.displaydate}

    <div class="serendipitySideBarDate">
        {$item.timestamp|formatTime:$remoterss_items.dateformat|escape}
    </div>
    {/if}

</div>

{/foreach}
