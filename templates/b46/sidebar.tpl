{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
{if $item.class != "serendipity_plugin_syndication" AND NOT empty($item.content)}
    <section class="{$item.class} mb-3{if $item.class == "serendipity_plugin_freetag"} col-lg-12{else} col-lg-4 col-sm-6{/if} px-0">
    {if $item.title != ""}
        <h3>{$item.title}</h3>
    {/if}
        <div class="sidebar_content">
        {$item.content}
        </div>
    </section>
{/if}
{/foreach}
{if $is_raw_mode}
</div>
{/if}