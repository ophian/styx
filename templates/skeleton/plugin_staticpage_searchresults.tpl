{if $staticpage_results}
<div class="page_results">
    <h3>{$CONST.STATICPAGE_SEARCHRESULTS|sprintf:$staticpage_searchresults}</h3>

    <ul class="plainList">
    {foreach $staticpage_results AS $result}
        <li><a href="{$result.permalink|escape}" title="{$result.pagetitle|escape}">{if NOT empty($result.headline)}{$result.headline}{else}{$result.pagetitle|escape}{/if}</a> ({$result.realname|escape})
            <div class="page_result_content">{$result.content|strip_tags|strip|truncate:200:"&hellip;"}</div>
        </li>
    {/foreach}
    </ul>
</div>
{/if}
