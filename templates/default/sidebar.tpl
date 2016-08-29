{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
    <div class="serendipitySideBarItem container_{$item.class}">
        {if $item.title != ""}<h3 class="serendipitySideBarTitle {$item.class}">{$item.title}</h3>{/if}
        <div class="serendipitySideBarContent">{$item.content}</div>
    </div>
{/foreach}
{if $is_raw_mode}
</div>
{/if}
