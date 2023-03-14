{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}
<section id="entries_dategroup" class="serendipity_Entry_Date">
{if $dategroup.is_sticky OR $dategroup.entries|count > 1}
    <header>
        <p class="serendipity_date text-end">{if $dategroup.is_sticky}{$CONST.STICKY_POSTINGS}{else}{$dategroup.date|formatTime:DATE_FORMAT_ENTRY}{/if}</p>
    </header>{/if}
    {foreach $dategroup.entries AS $entry}{if $is_single_entry AND $view == 'entry'}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" - $entry array relates in trackbacks - and index.tpl Rich Text Editor asset includes *}{/if}
    
    <article class="post{if $is_single_entry} post_single{/if}{if $dategroup.is_sticky} post_sticky{/if}">
        <header>
            <h2 class="blog-post-title mb-1 fst-italic"><a href="{$entry.link}">{$entry.title}</a></h2>

            <p class="post_byline">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></p>
        </header>

        <div class="post_content">
            {if NOT empty($entry.categories)}{foreach $entry.categories AS $entry_category_pre}{if $entry_category_pre.category_icon}<a href="{$entry_category_pre.category_link}"><img class="serendipity_entryIcon" title="{$entry_category_pre.category_name|escape}{$entry_category_pre.category_description|emptyPrefix}" alt="{$entry_category_pre.category_name|escape}" src="{$entry_category_pre.category_icon}"></a>{/if}{/foreach}{/if}
            {$entry.body}
            {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}
            <a class="post_more d-block mb-2 text-end" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>
            {/if}
        </div>
        {if $entry.is_extended}

        <div id="extended" class="post_content">
            {$entry.extended}
        </div>
        {/if}
    {if NOT $is_preview}

        <footer class="post_info">
        {if NOT empty($entry.categories) OR $entry.has_comments}

            <ul class="post_meta plainList">
            {if NOT empty($entry.categories)}

                <li class="post_category d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tag-fill" role="img" fill="#dc3545" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bitcat">{$CONST.CATEGORIES}</title>
                      <path fill-rule="evenodd" d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    </svg>
                    {foreach $entry.categories AS $entry_category}<a class="post_category" href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
                {if $entry.has_comments OR NOT empty($entry.freetag.tags)}
                    <svg class="bi me-1" width="16" height="16" role="img"><use xlink:href="#grip-horizontal"/></svg>
                {/if}</li>
            {/if}
            {if $entry.has_comments}

                <li class="post_comments d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-right-dots-fill" role="img" fill="#17a2b8" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bitcom">{$entry.label_comments}</title>
                      <path fill-rule="evenodd" d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353V2zM5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    <a href="{$entry.link}#comments" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments}</a>
                    {if $entry.allow_comments}

                    <svg class="bi me-1" width="16" height="16" role="img"><use xlink:href="#grip-horizontal"/></svg>
                    <a class="post_toform" href="{$entry.link}#reply"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="toform">{$CONST.ADD_COMMENT}</title>
                      <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                      <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                    </svg></a>{/if}
                    {if NOT empty($entry.freetag.tags.tags) OR (NOT empty($entry.is_entry_owner) AND NOT $is_preview AND empty($entry.freetag.tags.tags))}

                    <svg class="bi me-1" width="16" height="16" role="img"><use xlink:href="#grip-horizontal"/></svg>{/if}

                </li>
            {/if}
            {if NOT empty($entry.freetag.tags.tags)}

                <li class="post_tags d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tags-fill" role="img" fill="#20c997" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bittag">Free Tags</title>
                      <path fill-rule="evenodd" d="M3 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 7.586 1H3zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                      <path d="M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z"/>
                    </svg>
                    {if NOT $is_preview}{foreach $entry.freetag.tags.tags AS $tag}{$tag}{if NOT $tag@last}, {/if}{/foreach}{/if}
                {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}

                    <svg class="bi me-1" width="16" height="16" role="img"><use xlink:href="#grip-horizontal"/></svg>
                {/if}

                </li>
            {/if}
            {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}

                <li class="post_admin d-inline-block text-editicon editentrylink">
                    <a class="btn btn-secondary btn-sm btn-admin" href="{$entry.link_edit}">
                      <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="bitmod">{$CONST.EDIT_ENTRY}</title><use xlink:href="#pencil-square"/></svg>{$CONST.EDIT}
                    </a>
                </li>
            {/if}

            </ul>
        {/if}
            {$entry.add_footer|default:''}{* disable to stop adding tags *}

        </footer>
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
        <p class="serendipity_msg_notice">{$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_DELETED}
        <p class="serendipity_msg_important">{$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_APPROVED}
        <p class="serendipity_msg_notice">{$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_DELETED}
        <p class="serendipity_msg_important">{$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_APPROVED}
        <p class="serendipity_msg_notice">{$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</p>
    {/if}

    <section id="trackbacks">
        <h3 class="fst-italic">{$CONST.TRACKBACKS}</h3>

        <a id="trackback_url" rel="nofollow" href="{$entry.link_trackback}" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>

        <p class="trackback-hint alert alert-info d-none" role="alert"><svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;<u>{$entry.rdf_ident|escape}</u>&laquo;</p>
    {if $entry.trackbacks > 0}

        <div id="serendipity_trackbacklist">
        {serendipity_printTrackbacks entry=$entry.id}
        </div>
    {/if}

    </section>

    <section id="comments">
        <h3 class="fst-italic">{$CONST.COMMENTS}</h3>

        <p class="manage_comments">{$CONST.DISPLAY_COMMENTS_AS}
        {if $entry.viewmode == $CONST.VIEWMODE_LINEAR}
            <a class="btn btn-secondary btn-sm disabled" rel="nofollow" href="#">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | <a class="btn btn-secondary btn-sm" rel="nofollow" href="{$entry.link_viewmode_threaded}#comments">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>
        {else}
           <a class="btn btn-secondary btn-sm" rel="nofollow" href="{$entry.link_viewmode_linear}#comments">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | <a class="btn btn-secondary btn-sm disabled" rel="nofollow" href="#">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>
        {/if}
        </p>

        <div id="serendipity_commentlist">
        {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
        </div>
    {if NOT empty($entry.is_entry_owner)}
        {if $entry.allow_comments}

        <a class="btn btn-secondary btn-sm btn-admin" href="{$entry.link_deny_comments}">{$CONST.COMMENTS_DISABLE}</a>
        {else}

        <a class="btn btn-secondary btn-sm btn-admin" href="{$entry.link_allow_comments}">{$CONST.COMMENTS_ENABLE}</a>
        {/if}
    {/if}

    </section>

    <a id="feedback"></a>
    {foreach $comments_messagestack AS $message}
    <p class="serendipity_msg_important">{$message}</p>
    {/foreach}
    {if $is_comment_added}
    <p class="serendipity_msg_notice">{$CONST.COMMENT_ADDED|sprintf:"<a href=\"{if $is_logged_in}$commentform_action}{/if}#c{$smarty.get.last_insert_cid}\">#{$smarty.get.last_insert_cid}</a> "}</p>
    {elseif $is_comment_moderate}
    <p class="serendipity_msg_important">{$CONST.COMMENT_ADDED|sprintf:''} {$CONST.THIS_COMMENT_NEEDS_REVIEW}</p>
    {elseif NOT $entry.allow_comments}
    <p class="serendipity_msg_important">{$CONST.COMMENTS_CLOSED}</p>
    {else}

    <section id="reply">
        <h3 class="fst-italic">{$CONST.ADD_COMMENT}</h3>
        {$COMMENTFORM}
    </section>
    {/if}
{/if}
    {$entry.backend_preview}

    </article>
    {/foreach}

</section>
{foreachelse}
    {if NOT $plugin_clean_page AND $view != '404'}
    <p class="serendipity_msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
{/foreach}
{/if}
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="pager blog-pagination" aria-label="Pagination">
        {if NOT empty($footer_info)}<p class="d-flex justify-content-center fst-italic">{$footer_info}</p>{/if}

      <div class="d-flex justify-content-center">
    {if $footer_prev_page OR $footer_next_page}
        <a class="btn btn-outline-secondary{if NOT $footer_prev_page} disabled{/if}"{if NOT $footer_prev_page} href="#" tabindex="-1" aria-disabled="true"{else} href="{$footer_prev_page}"{/if}>{$CONST.PREVIOUS_PAGE}</a>
        <div class="mx-auto"></div>
        <a class="btn btn-outline-secondary{if NOT $footer_next_page} disabled{/if}"{if NOT $footer_next_page} href="#" tabindex="-1" aria-disabled="true"{else} href="{$footer_next_page}"{/if}>{$CONST.NEXT_PAGE}</a>
    {/if}

      </div>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
