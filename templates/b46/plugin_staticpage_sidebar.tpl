<div class="b46 staticpage_sidebar">
{if NOT empty($staticpage_jsStr)}
    <div class="staticpage_sbJsList">
    {$staticpage_jsStr}
    </div>
{/if}
{if NOT $staticpage_jsStr OR empty($staticpage_jsStr)}
{if NOT empty($frontpage_path)}
    <a class="spp_title" href="{$frontpage_path}">{$CONST.PLUGIN_STATICPAGELIST_FRONTPAGE_LINKNAME}</a>
{/if}
{if is_array($staticpage_listContent) AND NOT empty($staticpage_listContent)}
<ul class="plainList">
    {foreach $staticpage_listContent AS $pageList}
    <li>{if !empty($pageList.permalink)}
        <a class="sp_title" href="{$pageList.permalink}" title="{$pageList.pagetitle|escape}" style="padding-left: {$pageList.depth}px;">{$pageList.headline|truncate:32:"&hellip;"}</a>
    {else}
        <span class="sp_title" style="padding-left: {$pageList.depth}px;">{$pageList.headline|truncate:32:"&hellip;"}</span>
    {/if}</li>
    {/foreach}
</ul>
{/if}
{/if}
</div>
