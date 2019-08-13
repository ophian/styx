<div class="staticpage_results">
    <p class="staticpage_result_header">{$CONST.STATICPAGE_SEARCHRESULTS|sprintf:$staticpage_searchresults}</p>

    {if $staticpage_results}
    <dl class="staticpage_result">
    {foreach $staticpage_results AS $result}
        <dt><strong><a href="{$result.permalink|escape}" title="{$result.pagetitle|escape}">{if !empty($result.headline)}{$result.headline}{else}{$result.pagetitle|escape}{/if}</a></strong> ({$result.realname})</dt>
        <dd>{$result.content|strip_tags|strip|truncate:200:"&hellip;"}</dd>
    {/foreach}
    </dl>
    {/if}
</div>
