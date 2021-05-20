<article id="page_{$staticpage_pagetitle|makeFilename}" class="page">
    <header>
        <h2>{if $staticpage_articleformat}{if $staticpage_articleformattitle}{$staticpage_articleformattitle|escape}{else}{$staticpage_pagetitle|escape}{/if}{else}{if $staticpage_headline}{$staticpage_headline|escape}{else}{$staticpage_pagetitle|escape}{/if}{/if}</h2>
    </header>
{if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}

    <div id="staticpage_nav" class="border mb-3">
    {if $staticpage_shownavi}   <ul class="pagination justify-content-between m-0 px-2 py-1">
            <li class="page-item{if empty($staticpage_navigation.prev.link)} disabled{/if} text-start text-truncate pagenav_left">{if NOT empty($staticpage_navigation.prev.link)}<a class="btn btn-outline-secondary btn-sm" href="{$staticpage_navigation.prev.link}" title="prev">{$staticpage_navigation.prev.name|escape}</a>{else}<a class="page-link p-0 border-0" href="#" tabindex="-1" aria-disabled="true">{$CONST.PREVIOUS}</a>{/if}</li>
            <li class="page-item text-center px-2 pagenav_center{if empty($staticpage_navigation.top.topp_name)} active" aria-current="page{/if}">{if NOT empty($staticpage_navigation.top.topp_name)}<a class="btn btn-outline-secondary btn-sm" href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187;{if NOT empty($staticpage_navigation.top.exit_name)} | <a class="btn btn-outline-secondary btn-sm" href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}</li>
            <li class="page-item{if empty($staticpage_navigation.next.link)} disabled{/if} text-end text-truncate pagenav_right">{if NOT empty($staticpage_navigation.next.link)}<a class="btn btn-outline-secondary btn-sm" href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape}</a>{else}<a class="page-link p-0 border-0" href="#" tabindex="-1" aria-disabled="true">{$CONST.NEXT}</a>{/if}</li>
        </ul>{*'top' is just a name for current page, or top parent, or exit *}
    {/if}
    {if $staticpage_show_breadcrumb}

        <nav style="--bs-breadcrumb-divider: '&#187;';" aria-label="breadcrumb">
          <ol class="breadcrumb mx-2 mb-1">
            <li class="breadcrumb-item"><a href="{$serendipityBaseURL}">{$CONST.HOMEPAGE}</a></li>
{foreach $staticpage_navigation.crumbs AS $crumb}
            <li class="breadcrumb-item{if $crumb.id == $staticpage_pid} active" aria-current="page{/if}">{if $crumb.id != $staticpage_pid}<a href="{$crumb.link}">{$crumb.name|escape}</a>{else}{$crumb.name|escape}{/if}</li>
{/foreach}
          </ol>
        </nav>
    {/if}</div>
{/if}
{if $staticpage_pass AND $staticpage_form_pass != $staticpage_pass}
    <form class="staticpage_password_form mb-3" action="{$staticpage_form_url}" method="post">
    <fieldset>
        <label for="serendipity_page_pass">{$CONST.STATICPAGE_PASSWORD_NOTICE}</label>
        <input id="serendipity_page_pass" name="serendipity[pass]" type="password" value="">
        <input name="submit" type="submit" value="{$CONST.GO}">
    </fieldset>
    </form>
{else}
    {if $staticpage_precontent}

    <div class="page_content mb-3 page_preface">
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

    <div class="page_content mb-3">
    {$staticpage_content}
    </div>
    {/if}
{/if}

<div class="page_content page_preface staticpage_related_category_entry_list">
 {serendipity_fetchPrintEntries category=$staticpage_related_category_id template="staticpage-entries-listing.tpl" limit="5" noSticky="true"}
</div>

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
            {if $staticpage_lastchange}
                <span class="visuallyhidden">{$CONST.ON} </span>
                {if $staticpage_use_lmdate}
                <time datetime="{$staticpage_lastchange|serendipity_html5time}">{$staticpage_lastchange|formatTime:{$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}}</time>
                {if $staticpage_adminlink AND $staticpage_adminlink.page_user} (<svg class="bi flex-shrink-0 mx-1" width="16" height="16" role="img" aria-label="Edit:"><title id="title">{$CONST.CREATED_ON|lower}</title><use xlink:href="#pencil-square"/></svg>{$staticpage_created_on|date_format:"%Y-%m-%d"}){/if}
                {else}
                <time datetime="{$staticpage_created_on|serendipity_html5time}">{$staticpage_created_on|formatTime:{$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}}</time>
                {if $staticpage_adminlink AND $staticpage_adminlink.page_user} (<svg class="bi flex-shrink-0 mx-1" width="16" height="16" role="img" aria-label="Edit:"><title id="title">{$CONST.LAST_UPDATED|lower}</title><use xlink:href="#pencil-square"/></svg>{$staticpage_lastchange|date_format:"%Y-%m-%d"}){/if}
                {/if}
            {/if}
            {/if}</li>
        {if $staticpage_adminlink AND $staticpage_adminlink.page_user}

            <li class="d-flex flex-row-reverse text-editicon editentrylink"><a class="btn btn-secondary btn-sm btn-admin" href="{$staticpage_adminlink.link_edit}"><svg class="bi flex-shrink-0 me-2 mb-1" width="16" height="16" role="img" aria-label="Edit:"><use xlink:href="#pencil-square"/></svg>{$staticpage_adminlink.link_name|escape}</a></li>
        {/if}</ul>
    </footer>
{/if}</article>