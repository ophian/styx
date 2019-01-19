{* bulletproof frontend plugin_staticpage.tpl file v. 1.07, 2019-01-18 *}
{if $staticpage_articleformat}
<div id="staticpage_{$staticpage_pagetitle|makeFilename}" class="serendipity_Entry_Date serendipity_staticpage">
    <h3 class="serendipity_date">{if $staticpage_articleformattitle}{$staticpage_articleformattitle}{else}{$staticpage_pagetitle|escape}{/if}</h3>
{else}
<div class="staticpage_content">
{/if}

    <h4>{if $staticpage_headline}{$staticpage_headline}{else}{$staticpage_pagetitle|escape}{/if}</h4>
{if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}
    <div id="staticpage_nav">
    {if $staticpage_shownavi}
        <ul class="staticpage_navigation">
            <li class="staticpage_navigation_left">{if NOT empty($staticpage_navigation.prev.link)}<a href="{$staticpage_navigation.prev.link}" title="prev">{$staticpage_navigation.prev.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.PREVIOUS}</span>{/if}</li>
            <li class="staticpage_navigation_center">{if $staticpage_navigation.top.new}{if NOT empty($staticpage_navigation.top.topp_name)}<a href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187; {if NOT empty($staticpage_navigation.top.exit_name)}| <a href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}{else}<a href="{$staticpage_navigation.top.link}" title="current page">{$staticpage_navigation.top.name|escape}</a>{/if}</li>
            <li class="staticpage_navigation_right">{if NOT empty($staticpage_navigation.next.link)}<a href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.NEXT}</span>{/if}</li>
        </ul>{* 'top' is just a synonym for current page, or top parent, or exit *}
    {/if}
    {if $staticpage_show_breadcrumb}
        <div class="staticpage_navigation_breadcrumb">
            <a href="{$serendipityBaseURL}">{$CONST.HOMEPAGE}</a>{if NOT empty($staticpage_navigation.crumbs)} &#187; {/if}
        {foreach $staticpage_navigation.crumbs AS $crumb}
            {if NOT $crumb@first}&#187; {/if}{if $crumb.id != $staticpage_pid}<a href="{$crumb.link}">{$crumb.name|escape}</a>{else}{$crumb.name|escape}{/if}
        {/foreach}
        </div>
    {/if}
    </div>
{/if}

{if $staticpage_articleformat}
    <div class="serendipity_entry">
        <div class="serendipity_entry_body">
{/if}

{if $staticpage_pass AND $staticpage_form_pass != $staticpage_pass}
        <div class="staticpage_password">{$CONST.STATICPAGE_PASSWORD_NOTICE}</div>
        <form class="staticpage_password_form" action="{$staticpage_form_url}" method="post">
            <div>
                <input type="password" name="serendipity[pass]" value="" />
                <input type="submit" name="submit" value="{$CONST.GO}" />
             </div>
        </form>
{else}
        <div class="staticpage_precontent">{$staticpage_precontent}</div>
        {if is_array($staticpage_childpages)}
        <ul id="staticpage_childpages">
            {foreach $staticpage_childpages AS $childpage}
            <li><a href="{$childpage.permalink}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
            {/foreach}
        </ul>
        {/if}
        <div class="staticpage_content">{$staticpage_content}</div>
{/if}

{if $staticpage_articleformat}
        </div>
    </div>
</div>
{/if}

{if $staticpage_articleformat}
<div class="serendipity_Entry_Date serendipity_staticpage">
{/if}

{if $staticpage_author}
    <div class="staticpage_author">{$staticpage_author|escape}</div>
{/if}

    <div class="staticpage_metainfo">
{if $staticpage_lastchange}
    <span class="staticpage_metainfo_lastchange">{$staticpage_lastchange|date_format:"%Y-%m-%d"}</span>
{/if}

{if $staticpage_adminlink AND $staticpage_adminlink.page_user}
    | <a class="staticpage_metainfo_editlink" href="{$staticpage_adminlink.link_edit}">{$staticpage_adminlink.link_name|escape}</a>
{/if}
    </div>

</div>

