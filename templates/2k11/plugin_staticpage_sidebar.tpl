{if !empty($staticpage_jsStr)}

    <div class="staticpage_sbJsList">
    {$staticpage_jsStr}
    </div>
{/if}
{if !$staticpage_jsStr OR empty($staticpage_jsStr)}

    <ul class="plainList">
        {if $frontpage_path}
        <li><a href="{$frontpage_path}">{$CONST.PLUGIN_STATICPAGELIST_FRONTPAGE_LINKNAME}</a></li>
        {/if}
    {if is_array($staticpage_listContent) AND !empty($staticpage_listContent)}
    {foreach $staticpage_listContent AS $pageList}
        {if !empty($pageList.permalink)}
        <li class="depth_{$pageList.depth}"><a href="{$pageList.permalink}" title="{$pageList.pagetitle}">{$pageList.headline|truncate:32:"..."}</a></li>
        {else}
        <li class="depth_{$pageList.depth}">{$pageList.headline|truncate:32:"..."}</li>
        {/if}
    {/foreach}
    {/if}

    </ul>
{/if}
