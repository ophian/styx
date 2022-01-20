{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
{if NOT empty($taglist)}
    <article id="taglistentries" class="clearfix serendipity_entry">
        <div class="clearfix content serendipity_entry_body">
            <h2>{$head_subtitle}</h2>
    {foreach $entries AS $dategroup}
        {foreach $dategroup.entries AS $entry}

            <div class="static-entries-list">
                ({$dategroup.date|date_format:"%d.%m.%Y"}) <a href="{$entry.link}">{$entry.title|default:$entry.id}</a>
            </div>
        {/foreach}
    {/foreach}

        </div>
    </article>
{else}
{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}

<section id="entries_dategroup" class="serendipity_Entry_Date">
    <header>
        {if NOT $is_single_entry}<p class="serendipity_date">{if $dategroup.is_sticky}{$CONST.STICKY_POSTINGS}{else}{$dategroup.date|formatTime:DATE_FORMAT_ENTRY}{/if}</p>{/if}
    </header>
    {foreach $dategroup.entries AS $entry}{if $is_single_entry AND $view == 'entry'}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" - $entry array relates in trackbacks - and index.tpl Rich Text Editor asset includes *}{/if}

    <article class="post{if $is_single_entry} post_single{/if}{if $dategroup.is_sticky} post_sticky{/if}">
        <header>
            <h2><a href="{$entry.link}">{$entry.title}</a></h2>

            <p class="post_byline">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a>{if $is_single_entry OR $dategroup.is_sticky} {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time>{else}{if $dategroup.date|formatTime:$template_option.date_format != $entry.timestamp|formatTime:$template_option.date_format} {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time>{/if}{/if}</p>
        </header>

        <div class="post_content">
            {if NOT empty($entry.categories)}{foreach $entry.categories AS $entry_category_pre}{if $entry_category_pre.category_icon}<a href="{$entry_category_pre.category_link}"><img class="serendipity_entryIcon" title="{$entry_category_pre.category_name|escape}{$entry_category_pre.category_description|emptyPrefix}" alt="{$entry_category_pre.category_name|escape}" src="{$entry_category_pre.category_icon}"></a>{/if}{/foreach}{/if}

            {$entry.body}
            {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}<a class="post_more" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>{/if}

        </div>
        {if $entry.is_extended}

        <div id="extended" class="post_content">
            {$entry.extended}
        </div>
        {/if}
{if NOT $is_preview}
    {if NOT empty($entry.categories) OR $entry.has_comments OR $entry.has_trackbacks OR NOT empty($entry.freetag.extended) OR NOT empty($entry.is_entry_owner)}

        <footer class="serendipity_entryFooter post_info{if NOT isset($view) OR $view != 'entry'} listed_view{/if}">
            <ul class="plainList">
            {if NOT empty($entry.categories)}

                <li class="post_category"><span>{$CONST.CATEGORIES}: </span>{foreach $entry.categories AS $entry_category}<a class="post_category" href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}</li>
            {/if}
        {if $entry.has_comments OR $entry.has_trackbacks OR NOT empty($entry.has_disqus)}

                <li class="post_comments">
                    {if isset($view) AND $view == 'entry' AND NOT $use_popups}<span>{$CONST.COMMENTS}: </span>{/if}
            {if isset($entry.has_disqus) AND $entry.has_disqus}
                    {$entry.comments}{if $entry.has_trackbacks}, <a href="{$entry.link}#trackbacks">{$entry.trackbacks} {$entry.label_trackbacks}</a>{/if}
            {else if empty($is_single_entry)}
                {if $use_popups}
                    <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;" title="{$entry.comments} {$entry.label_comments}">{$entry.comments} {$entry.label_comments}</a>, <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;" title="{$entry.trackbacks} {$entry.label_trackbacks}">{$entry.trackbacks} {$entry.label_trackbacks}</a>
                {else}
                    <a href="{$entry.link}{if $entry.has_trackbacks AND $entry.trackbacks > 0}#trackbacks{else}#comments{/if}" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments} {$entry.label_comments|replace: $CONST.COMMENTS:''}</a>
                {/if}
            {else if isset($entry.label_comments) OR isset($entry.label_trackbacks)}
                    <a href="{$entry.link}{if $entry.has_trackbacks AND $entry.trackbacks > 0}#trackbacks{else}#comments{/if}" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments} {if $use_popups}{$entry.label_comments}{else}{$entry.label_comments|replace: $CONST.COMMENTS:''}{/if}</a>
            {/if}

                </li>
        {/if}
        {if NOT empty($entry.freetag.tags.tags) AND $entry.freetag.extended === true}

                <li class="post_tags">
                {if NOT isset($view) OR $view != 'entry'}
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tags-fill" role="img" fill="#20c997" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bittag">FreeTags</title>
                      <path fill-rule="evenodd" d="M3 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 7.586 1H3zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                      <path d="M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z"/>
                    </svg>
                {else}<span>{$entry.freetag.tags.description} </span>
                {/if}
                    {foreach $entry.freetag.tags.tags AS $tag}{$tag}{if NOT $tag@last}, {/if}{/foreach}

                </li>
        {/if}
        {if NOT empty($entry.is_entry_owner)}

                <li class="post_admin editentrylink">
                    {if isset($view) AND $view == 'entry'}<span>{$CONST.ADMIN}: </span>{/if}
                    <a href="{$entry.link_edit}">
                      {if isset($view) AND $view == 'entry'}{$CONST.EDIT_ENTRY}{else}{$CONST.EDIT}{/if}
                    </a>
                </li>
        {/if}
        {if isset($view) AND $view == 'entry' AND NOT empty($entry.freetag.related.entries) AND $entry.freetag.extended === true}

                <li class="post_reltags">
                    <span>{$entry.freetag.related.description}</span>
                    <ul class="plainList">
                        {foreach $entry.freetag.related.entries AS $reltag}<li>{$reltag}</li>{/foreach}
                    </ul>
                </li>
        {/if}

            </ul>
            {if isset($view) AND $view == 'entry'}{$entry.add_footer|default:''}{/if}

        </footer>
    {/if}

        <!--
        <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                 xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/"
                 xmlns:dc="http://purl.org/dc/elements/1.1/">
        <rdf:Description
                 rdf:about="{$entry.link_rdf}"
                 trackback:ping="{$entry.link_trackback}"
                 dc:title="{$entry.title_rdf|default:$entry.title}"
                 dc:identifier="{$entry.rdf_ident}" />
        </rdf:RDF>
        -->
        {$entry.plugin_display_dat}
{/if}

{if $is_single_entry AND NOT $is_preview}
    {if $CONST.DATA_UNSUBSCRIBED}
        <p class="msg_notice">{$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_DELETED}
        <p class="msg_important">{$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_APPROVED}
        <p class="msg_notice">{$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_DELETED}
        <p class="msg_important">{$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_APPROVED}
        <p class="msg_notice">{$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</p>
    {/if}
    {if $entry.trackbacks > 0}

    <section id="trackbacks">
        <h3>{$CONST.TRACKBACKS}</h3>
        <a id="trackback_url" rel="nofollow" href="{$entry.link_trackback}" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>

        <p class="msg_notice trackback-hint">{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;<u>{$entry.rdf_ident|escape}</u>&laquo;</p>

        <div id="serendipity_trackbacklist">
        {serendipity_printTrackbacks entry=$entry.id}
        </div>
    </section>
    {else}

    <a id="trackback_url" rel="nofollow" href="{$entry.link_trackback}" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>
    <p class="msg_notice trackback-hint">{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;<u>{$entry.rdf_ident|escape}</u>&laquo;</p>
    {/if}
    {if $entry.comments > 0}

    <section id="comments">
        <h3>{$CONST.COMMENTS}</h3>

        <p class="manage_comments">{$CONST.DISPLAY_COMMENTS_AS}
        {if $entry.viewmode eq $CONST.VIEWMODE_LINEAR}
           {$CONST.COMMENTS_VIEWMODE_LINEAR} | <a href="{$entry.link_viewmode_threaded}#comments" rel="nofollow">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>
        {else}
           <a rel="nofollow" href="{$entry.link_viewmode_linear}#comments">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | {$CONST.COMMENTS_VIEWMODE_THREADED}
        {/if}
        </p>

        <div id="serendipity_commentlist">
        {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
        </div>
    </section>
    {/if}

    <a id="feedback"></a>
    {foreach $comments_messagestack AS $message}
    <p class="msg_important">{$message}</p>
    {/foreach}
    {if $is_comment_added}
    <p class="msg_notice">{$CONST.COMMENT_ADDED}</p>
    {elseif $is_comment_moderate}
    <p class="msg_important">{$CONST.COMMENT_ADDED}{$CONST.THIS_COMMENT_NEEDS_REVIEW}</p>
    {elseif NOT $entry.allow_comments}
    <p class="msg_important">{$CONST.COMMENTS_CLOSED}</p>
    {else}

    <section id="reply">
        <h3>{$CONST.ADD_COMMENT}</h3>

{$COMMENTFORM}
    </section>
    {/if}
{/if}
    {$entry.backend_preview}

    </article>
    {/foreach}

</section>
{/foreach}
{else}
    {if NOT $plugin_clean_page AND $view != '404'}

    <p class="msg_notice"><span class="ico icon-info" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
{/if}

 {/if}{* NOT taglist end *}

{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}
{if NOT empty($taglist)}
    {if $footer_prev_page}{assign var="footer_prev_page" value=$footer_prev_page|replace:'/plugin/':'/plugin/taglist/'}{/if}
    {if $footer_next_page}{assign var="footer_next_page" value=$footer_next_page|replace:'/plugin/':'/plugin/taglist/'}{/if}
{/if}

    <nav class="pager">
        {if NOT empty($footer_info)}<p>{$footer_info}</p>{/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul class="plainList">
            <li class="pager_prev">{if $footer_prev_page}<a href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a>{else}&nbsp;{/if}</li>
            <li class="pager_next">{if $footer_next_page}<a href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a>{else}&nbsp;{/if}</li>
        </ul>
    {/if}

    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}