{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar" class="p-4">
{/if}
{foreach $plugindata AS $item}
{if $item.class != "serendipity_plugin_quicksearch" AND NOT empty($item.content)}
    <section class="serendipitySideBarItem sidebar_widget {$item.class} p-4 mb-3 bg-light rounded">
    {if $item.title != ""}
        <h4 class="fst-italic serendipitySideBarTitle">{$item.title}</h4>
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
