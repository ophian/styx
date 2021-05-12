{*
<h3>Example for including a static page into another (without navigation)</h3>
{staticpage_display template="plugin_staticpage_includeentry.tpl" pagevar="" id="13" permalink="" pagetitle="" authorid="" query=""}
*}
<article id="page_{$staticpage_pagetitle|makeFilename}" class="page page_includeentry">
{if $staticpage_precontent}
    <div class="page_content mb-3 page_preface">
    {$staticpage_precontent}
    </div>
{/if}
{if is_array($staticpage_childpages)}
    <ul id="page_childpages" class="page_children">
    {foreach $staticpage_childpages AS $childpage}
        <li><a href="{$childpage.permalink|escape}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
    {/foreach}
    </ul>
{/if}
{if $staticpage_content}
    <div class="page_content mb-3">
    {$staticpage_content}
    </div>
{/if}</article>