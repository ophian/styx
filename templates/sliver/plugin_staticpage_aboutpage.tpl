{* Sliver frontend plugin_staticpage_aboutpage.tpl (overview) file v. 1.10, 2020-04-17 *}
{if $staticpage_articleformat}
<div class="serendipity_Entry_Date">
    <h5 class="serendipity_date">{if $staticpage_articleformattitle}{$staticpage_articleformattitle}{else}{$staticpage_pagetitle|escape}{/if}</h5>
{/if}

    {if !$staticpage_articleformat}<h2>{if $staticpage_headline}{$staticpage_headline}{else}{$staticpage_pagetitle|escape}{/if}</h2>{/if}

{if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}
    <div id="staticpage_nav">
    {if $staticpage_shownavi}
        <ul class="staticpage_navigation">
            <li class="staticpage_navigation_left">{if !empty($staticpage_navigation.prev.link)}<a href="{$staticpage_navigation.prev.link}" title="prev">{$staticpage_navigation.prev.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.PREVIOUS}</span>{/if}</li>
            <li class="staticpage_navigation_center">{if $staticpage_navigation.top.new}{if !empty($staticpage_navigation.top.topp_name)}<a href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187; {if !empty($staticpage_navigation.top.exit_name)}| <a href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}{else}<a href="{$staticpage_navigation.top.link}" title="current page">{$staticpage_navigation.top.name|escape}</a>{/if}</li>
            <li class="staticpage_navigation_right">{if !empty($staticpage_navigation.next.link)}<a href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.NEXT}</span>{/if}</li>
        </ul>{* 'top' is just a synonym for current page, or top parent, or exit *}
    {/if}
    {if $staticpage_show_breadcrumb}
        <div class="staticpage_navigation_breadcrumb">
            <a href="{$serendipityBaseURL}">{$CONST.HOMEPAGE}</a>{if !empty($staticpage_navigation.crumbs)} &#187; {/if}
        {foreach $staticpage_navigation.crumbs AS $crumb}
            {if !$crumb@first}&#187; {/if}{if $crumb.id != $staticpage_pid}<a href="{$crumb.link}">{$crumb.name|escape}</a>{else}{$crumb.name|escape}{/if}
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
        <form action="{$staticpage_form_url}" method="post">
            <div>
                <input type="password" name="serendipity[pass]" value="">
                <input type="submit" name="submit" value="{$CONST.GO}">
             </div>
        </form>
{else}

    {if $staticpage_precontent}
    <div class="clearfix content serendipity_preface">
    {$staticpage_precontent}
    </div>
    {/if}
    {* simple view
    {if is_array($staticpage_childpages)}
    <div class="clearfix content staticpage_childpages">
        <ul id="staticpage_childpages">
            {foreach $staticpage_childpages AS $childpage}
            <li><a href="{$childpage.permalink}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
            {/foreach}
        </ul>
    </div>
    {/if}
    *}
    {* extendet view *}
    {if is_array($staticpage_extchildpages)}
    <dl class="staticpage_list_of_childpages">
    {foreach $staticpage_extchildpages AS $child}
        <dt>{if $child.image}<img src="{$child.image}" alt="" />{/if}<a href="{$child.permalink}">{$child.pagetitle|escape}</a></dt>
        <dd>{$child.precontent|truncate:200:"...":true}</dd>
    {/foreach}
    </dl>
    {/if}

    {if $staticpage_content}
    <div class="clearfix content {if $staticpage_articleformat}serendipity_entry_body{else}staticpage_content{/if}">
    {$staticpage_content}
    </div>
    {/if}

{/if}

{if $staticpage_articleformat}
        </div>
    </div>
</div>
{/if}

{if $staticpage_articleformat}
<div class="serendipity_Entry_Date serendipity_staticpage">
{/if}

{if $staticpage_author OR $staticpage_lastchange OR $staticpage_adminlink}
    <footer class="staticpage_metainfo staticpage_overview_metainfo">
        <p>
        {if $staticpage_author}
            <span class="single_user"><span class="visuallyhidden">{$CONST.POSTED_BY} </span>{$staticpage_author|escape}
        {/if}
        {if $staticpage_author AND $staticpage_lastchange} | </span>{/if}
        {if $staticpage_lastchange}
            <span class="visuallyhidden">{$CONST.ON} </span>
            {if $staticpage_use_lmdate}
            <time datetime="{$staticpage_lastchange|serendipity_html5time}">{$staticpage_lastchange|formatTime:{$template_option.date_format|default:"%A, %e. %B %Y"}}</time>
            {if $staticpage_adminlink AND $staticpage_adminlink.page_user} ({$CONST.CREATED_ON|lower}: {$staticpage_created_on|date_format:"%Y-%m-%d"}){/if}
            {else}
            <time datetime="{$staticpage_created_on|serendipity_html5time}">{$staticpage_created_on|formatTime:{$template_option.date_format|default:"%A, %e. %B %Y"}}</time>
            {if $staticpage_adminlink AND $staticpage_adminlink.page_user} ({$CONST.LAST_UPDATED|lower}: {$staticpage_lastchange|date_format:"%Y-%m-%d"}){/if}
            {/if}
        {/if}
        {if $staticpage_adminlink AND $staticpage_adminlink.page_user}
            | <span class="editentrylink"><a href="{$staticpage_adminlink.link_edit}">{$staticpage_adminlink.link_name|escape}</a></span>
        {/if}
        </p>
    </footer>
{/if}

{if $staticpage_articleformat}
</div>
{/if}
