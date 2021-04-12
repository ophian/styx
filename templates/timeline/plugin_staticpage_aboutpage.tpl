<article id="staticpage_{$staticpage_pagetitle|makeFilename}" class="serendipity_staticpage{if $staticpage_articleformat} post serendipity_entry{/if}">
    <header>
        <h3>{if $staticpage_articleformat}{if $staticpage_articleformattitle}{$staticpage_articleformattitle|escape}{else}{$staticpage_pagetitle|escape}{/if}{else}{if $staticpage_headline}{$staticpage_headline|escape}{else}{$staticpage_pagetitle|escape}{/if}{/if}</h3>
        {if $staticpage_show_breadcrumb}

        <div class="staticpage_breadcrumbs">
            <a href="{$serendipityBaseURL}" title="{$CONST.HOMEPAGE}">{$blogTitle}</a> {if NOT empty($staticpage_navigation.crumbs)} &#187; {/if}

        {foreach $staticpage_navigation.crumbs AS $crumb}

            {if NOT $crumb@first}&#187; {/if}{if $crumb.id != $staticpage_pid}<a href="{$crumb.link}">{$crumb.name|escape}</a>{else}{$crumb.name|escape}{/if}
        {/foreach}

        </div>
        {/if}

    </header>

    {if $staticpage_pass AND $staticpage_form_pass != $staticpage_pass}
        <form class="staticpage_password_form" action="{$staticpage_form_url}" method="post">
            <fieldset>
                <legend>{$CONST.STATICPAGE_PASSWORD_NOTICE}</legend>
                <input name="serendipity[pass]" type="password" value="">
                <input name="submit" type="submit" value="{$CONST.GO}" >
            </fieldset>
        </form>
    {else}

    <section id="entry">
    {if $staticpage_precontent}

        <div class="content serendipity_preface">
            {$staticpage_precontent}
        </div>
    {/if}
    {if is_array($staticpage_childpages)}

        <div class="clearfix content staticpage_childpages">
            <ul id="staticpage_childpages">
            {foreach $staticpage_childpages AS $childpage}

                <li><a href="{$childpage.permalink|escape}" title="{$childpage.pagetitle|escape}">{$childpage.pagetitle|escape}</a></li>
            {/foreach}
            </ul>
        </div>
    {/if}
    {if $staticpage_content}

        <div class="content {if $staticpage_articleformat}serendipity_entry_body{else}staticpage_content{/if}">
            {$staticpage_content}
        </div>
    {/if}
{* CUSTOM TO THIS THEME - CUSTOM STATICPAGE IMAGE *}
    {if NOT empty($staticpage_custom.staticpage_image)}
        {if $staticpage_custom.staticpage_image|is_in_string:'<iframe,<embed,<object'}{* we assume this is a video, just emit the contents of the var *}
            {$staticpage_custom.staticpage_image}
        {else}
            {serendipity_getImageSize file=$staticpage_custom.staticpage_image assign="img_size"}
            <img class="{if $img_size[0]>=800}image-full-width{else}serendipity_image_left{/if}" src="{$staticpage_custom.staticpage_image}" width="{$img_size[0]}" height="{$img_size[1]}" alt=""/>
        {/if}
    {/if}
    </section>
{/if}

    <footer class="staticpage-footer">
    {if is_array($staticpage_navigation) AND ($staticpage_shownavi OR $staticpage_show_breadcrumb)}

        <nav role="navigation">
        {if $staticpage_shownavi}

            <ul class="pager staticpage_navigation">
                <li class="staticpage_navigation_left">{if NOT empty($staticpage_navigation.prev.link)}<a href="{$staticpage_navigation.prev.link}" title="prev"><i class="fas fa-arrow-left" aria-hidden="true"></i> {$staticpage_navigation.prev.name|escape}</a>{else}<span class="staticpage_navigation_dummy">{$CONST.PREVIOUS}</span>{/if}</li>
                <li class="staticpage_navigation_center">{if $staticpage_navigation.top.new}{if NOT empty($staticpage_navigation.top.topp_name)}<a href="{$staticpage_navigation.top.topp_link}" title="top">{$staticpage_navigation.top.topp_name|escape}</a> | {/if}&#171 {$staticpage_navigation.top.curr_name|escape} &#187; {if NOT empty($staticpage_navigation.top.exit_name)}| <a href="{$staticpage_navigation.top.exit_link}" title="exit">{$staticpage_navigation.top.exit_name|escape}</a>{/if}{else}<a href="{$staticpage_navigation.top.link}" title="current page">{$staticpage_navigation.top.name|escape}</a>{/if}</li>
                <li class="staticpage_navigation_right">{if NOT empty($staticpage_navigation.next.link)}<a href="{$staticpage_navigation.next.link}" title="next">{$staticpage_navigation.next.name|escape} <i class="fas fa-arrow-right" aria-hidden="true"></i></a>{else}<span class="staticpage_navigation_dummy">{$CONST.NEXT}</span>{/if}</li>
            </ul>{* 'top' is just a synonym for current page, or top parent, or exit *}
        {/if}
        {if $staticpage_show_breadcrumb}

            <div class="staticpage_navigation_breadcrumb">
                <a href="{$serendipityBaseURL}" title="{$CONST.HOMEPAGE}">{$blogTitle}</a> {if NOT empty($staticpage_navigation.crumbs)} &#187; {/if}

            {foreach $staticpage_navigation.crumbs AS $crumb}

                {if NOT $crumb@first}&#187; {/if}{if $crumb.id != $staticpage_pid}<a href="{$crumb.link}">{$crumb.name|escape}</a>{else}{$crumb.name|escape}{/if}
            {/foreach}
            </div>
        {/if}

        </nav>
    {/if}
    {if (isset($staticpage_custom.show_author) AND $staticpage_custom.show_author == 'true') OR (isset($staticpage_custom.show_date) AND $staticpage_custom.show_date == 'true') OR ($staticpage_adminlink AND $staticpage_adminlink.page_user)}

        <p class="post-meta">
            {if isset($staticpage_custom.show_author) AND $staticpage_custom.show_author == 'true'}{$CONST.POSTED_BY} {$staticpage_author|escape}{/if}{if isset($staticpage_custom.show_date) AND $staticpage_custom.show_date == 'true'}{if $staticpage_custom.show_author == 'true'} {$CONST.ON} {/if}<time datetime="{$staticpage_lastchange|serendipity_html5time}">{$staticpage_lastchange|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>{/if}{if $staticpage_adminlink AND $staticpage_adminlink.page_user}{if (isset($staticpage_custom.show_author) AND $staticpage_custom.show_author == 'true') OR (isset($staticpage_custom.show_date) AND $staticpage_custom.show_date == 'true')}&nbsp;&nbsp;{/if}<a href="{$staticpage_adminlink.link_edit}" title="{$staticpage_adminlink.link_name|escape}"><button class="btn btn-secondary btn-sm"><i class="fas fa-lg fa-edit"></i><span class="sr-only">{$staticpage_adminlink.link_name|escape}</span></button></a>{/if}
        </p>
    {/if}

    </footer>
</article>
