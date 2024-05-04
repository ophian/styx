<article id="page_{$staticpage_pagetitle|makeFilename}" class="page col-xs-12 col-lg-12">
    <header>
       <h2>{if $staticpage_articleformat}{if $staticpage_articleformattitle}{$staticpage_articleformattitle|escape}{else}{$staticpage_pagetitle|escape}{/if}{else}{if $staticpage_headline}{$staticpage_headline|escape}{else}{$staticpage_pagetitle|escape}{/if}{/if}</h2>
    </header>
    {if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}

    <div id="staticpage_nav">
    {if $staticpage_shownavi}
        <ul class="staticpage_navigation">
            <li class="staticpage_navigation_left">{if NOT empty($staticpage_navigation.prev.link)}<a href="{$staticpage_navigation.prev.link}" title="prev">{$staticpage_navigation.prev.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.PREVIOUS}</span>{/if}</li>
            <li class="staticpage_navigation_center">{if NOT empty($staticpage_navigation.top.topp_name)}<a href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187; {if NOT empty($staticpage_navigation.top.exit_name)}| <a href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}</li>
            <li class="staticpage_navigation_right">{if NOT empty($staticpage_navigation.next.link)}<a href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.NEXT}</span>{/if}</li>
        </ul>{*'top' is just a name for current page, or top parent, or exit *}
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
{if $staticpage_pass AND $staticpage_form_pass != $staticpage_pass}
    <form class="staticpage_password_form" action="{$staticpage_form_url}" method="post">
        <label for="serendipity_page_pass">{$CONST.STATICPAGE_PASSWORD_NOTICE}</label>
        <input id="serendipity_page_pass" name="serendipity[pass]" type="password" autocomplete="new-password" value="">
        <input name="submit" type="submit" value="{$CONST.GO}" >
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
        <li><a href="{$childpage.permalink|escape}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
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
        <ul class="plainList">
            <li class="d-inline-block">
                {if $staticpage_author}<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-person" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">{$CONST.POSTED_BY}</title>
                  <path fill-rule="evenodd" d="M12 1H4a1 1 0 0 0-1 1v10.755S4 11 8 11s5 1.755 5 1.755V2a1 1 0 0 0-1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
                  <path fill-rule="evenodd" d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
                {$staticpage_author|escape}
            {/if}</li>
            <li class="d-inline-block">
                {if $staticpage_lastchange}<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar3" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">{$CONST.ON}</title>
                  <path fill-rule="evenodd" d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                  <path fill-rule="evenodd" d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
                <time datetime="{$staticpage_lastchange|serendipity_html5time}">{$staticpage_lastchange|date_format:$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}</time>
            {/if}</li>
        {if $staticpage_adminlink AND $staticpage_adminlink.page_user}
            <li class="d-inline-block bi bi-pencil-square text-editicon serendipity_edit_nugget editentrylink btn btn-admin btn-sm"><a href="{$staticpage_adminlink.link_edit}">{$staticpage_adminlink.link_name|escape}</a></li>
        {/if}</ul>
    </footer>
{/if}
</article>