<article id="staticpage_{$staticpage_pagetitle|makeFilename}" class="clearfix serendipity_staticpage{if $staticpage_articleformat} serendipity_entry{/if}">
    <h2>{if $staticpage_articleformat}{if $staticpage_articleformattitle}{$staticpage_articleformattitle}{else}{$staticpage_pagetitle|escape}{/if}{else}{if $staticpage_headline}{$staticpage_headline}{else}{$staticpage_pagetitle|escape}{/if}{/if}</h2>
    {if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}
    <div id="staticpage_nav">
    {if $staticpage_shownavi}
        <ul class="staticpage_navigation">
            <li class="staticpage_navigation_left">{if !empty($staticpage_navigation.prev.link)}<a href="{$staticpage_navigation.prev.link}" title="prev">{$staticpage_navigation.prev.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.PREVIOUS}</span>{/if}</li>
            <li class="staticpage_navigation_center">{if !empty($staticpage_navigation.top.topp_name)}<a href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187; {if !empty($staticpage_navigation.top.exit_name)}| <a href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}</li>{* remove the a to show as plain text *}{*
            <li class="staticpage_navigation_center">&#171 <a href="{$staticpage_navigation.top.link}" title="current">{$staticpage_navigation.top.name|escape}</a> &#187;</li> *}{* remove the a to show as plain text *}
            <li class="staticpage_navigation_right">{if !empty($staticpage_navigation.next.link)}<a href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.NEXT}</span>{/if}</li>
        </ul>{* 'top' is just a synonym for current page, or top parent, or exit navigation *}
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
{if $staticpage_pass AND $staticpage_form_pass != $staticpage_pass}
    <form class="staticpage_password_form" action="{$staticpage_form_url}" method="post">
        <label for="serendipity_page_pass">{$CONST.STATICPAGE_PASSWORD_NOTICE}</label>
        <input id="serendipity_page_pass" name="serendipity[pass]" type="password" value="">
        <input class="button-primary" name="submit" type="submit" value="{$CONST.GO}" >
    </form>
{else}
    {if $staticpage_precontent}
    <div class="page_content page_preface">
    {$staticpage_precontent}
    </div>
    {/if}
    {if is_array($staticpage_childpages)}
    <ul class="page_children">
        {foreach $staticpage_childpages AS $childpage}
        <li><a href="{$childpage.permalink}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
        {/foreach}
    </ul>
    {/if}
    {if $staticpage_content}
    <div class="page_content">
    {$staticpage_content}
    </div>
    {/if}
{/if}
{if $staticpage_author OR $staticpage_lastchange OR $staticpage_adminlink}
    <footer class="page_info">
        <p>
        {if $staticpage_author}
            <span class="single_user"><span class="visuallyhidden">{$CONST.POSTED_BY} </span>{$staticpage_author}
        {/if}
        {if $staticpage_author AND $staticpage_lastchange} | </span>{/if}
        {if $staticpage_lastchange}
            <span class="visuallyhidden">{$CONST.ON} </span>
            {if $staticpage_use_lmdate}
            <time datetime="{$staticpage_lastchange|serendipity_html5time}">{$staticpage_lastchange|formatTime:$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}</time>
            {if $staticpage_adminlink AND $staticpage_adminlink.page_user} ({$CONST.CREATED_ON|lower}: {$staticpage_created_on|date_format:"%Y-%m-%d"}){/if}
            {else}
            <time datetime="{$staticpage_created_on|serendipity_html5time}">{$staticpage_created_on|formatTime:$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}</time>
            {if $staticpage_adminlink AND $staticpage_adminlink.page_user} ({$CONST.LAST_UPDATED|lower}: {$staticpage_lastchange|date_format:"%Y-%m-%d"}){/if}
            {/if}
        {/if}
        {if $staticpage_adminlink AND $staticpage_adminlink.page_user}
            | <a href="{$staticpage_adminlink.link_edit}">{$staticpage_adminlink.link_name|escape}</a>
        {/if}
        </p>
    </footer>
{/if}
</article>
