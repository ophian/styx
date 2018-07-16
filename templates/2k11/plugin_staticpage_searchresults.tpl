<aside class="staticpage_results">
    <h3>{$CONST.STATICPAGE_SEARCHRESULTS|sprintf:$staticpage_searchresults}</h3>
    {if $staticpage_results}
    <ul class="plainList">
    {foreach $staticpage_results AS $result}
        <li>
            <span class="block_level"><a href="{$result.permalink|escape}" title="{$result.pagetitle|escape}">{if NOT empty($result.headline)}{$result.headline}{else}{$result.pagetitle|escape}{/if}</a> ({$result.realname|escape})</span>
            {$result.content|strip_tags|strip|truncate:200:"&hellip;"}
        </li>
    {/foreach}
    </ul>
    {else}
    <p>{$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
</aside>
