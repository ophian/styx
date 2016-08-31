{if $staticpage_results}
<aside class="staticpage_results">
    <h3>{$CONST.STATICPAGE_SEARCHRESULTS|sprintf:$staticpage_searchresults}</h3>
    {foreach $staticpage_results AS $result}
        <a href="{$result.permalink|escape}" title="{$result.pagetitle|escape}"><h2 class="post-title">{$result.headline}</h2></a>
    {/foreach}
</aside>
{/if}