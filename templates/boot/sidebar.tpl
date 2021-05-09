{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
{if $item.class != "serendipity_plugin_quicksearch" AND NOT empty($item.content)}
    <section class="serendipitySideBarItem sidebar_widget {$item.class}">
    {if $item.title != ""}
        <h3 class="serendipitySideBarTitle">{$item.title}</h3>
    {/if}
        <div class="serendipitySideBarContent sidebar_content">
        {$item.content}
        </div>
    </section>
{/if}
{/foreach}
{if $is_raw_mode}
</div>
{/if}
