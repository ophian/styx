{if $is_raw_mode}
    <div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
    {if $item.class != "serendipity_plugin_quicksearch" AND NOT empty($item.content)}
        <section class="sidebar_plugin clearfix {cycle values="odd,even"} {$item.class}">
            {* if $item.title != "" AND $item.class != "serendipity_plugin_freetag" *}
            {if NOT empty($item.title)}
            <h3>{$item.title}</h3>
            {/if}
            <div class="sidebar_content clearfix">{$item.content}</div>
        </section>
    {/if}
{/foreach}
{if $is_raw_mode}
    </div>
{/if}
