{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
{if $item.class != "serendipity_plugin_quicksearch" AND NOT empty($item.content)}
    <section class="widget {$item.class}">
    {if $item.title != ""}
        <h3>{$item.title}</h3>
    {/if}
        {$item.content}
    </section>
{/if}
{/foreach}
{if $is_raw_mode}
</div>
{/if}
