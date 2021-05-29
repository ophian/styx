{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}
    {foreach $dategroup.entries AS $entry}
    {if $is_single_entry AND ($entry.comments > 0 OR $entry.trackbacks > 0)}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" etc *}{/if}
    <article class="post{if $is_single_entry} post_single{/if}{if $dategroup.is_sticky} post_sticky{/if}">
        <header>
            <h2><a href="{$entry.link}">{$entry.title}</a></h2>

            <p class="post_byline">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></p>
        </header>

        <div class="post_content">
        {if NOT empty($entry.categories)}{foreach $entry.categories AS $entry_category}{if $entry_category.category_icon}<a href="{$entry_category.category_link}"><img class="serendipity_entryIcon" title="{$entry_category.category_name|escape}{$entry_category.category_description|emptyPrefix}" alt="{$entry_category.category_name|escape}" src="{$entry_category.category_icon|escape}"></a>{/if}{/foreach}{/if}
        {$entry.body}
        {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}
        <a class="button read_more" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>
        {/if}
        </div>
        {if $entry.is_extended}
        <div id="extended" class="post_content">
        {$entry.extended}
        </div>
        {/if}

    {if NOT $is_preview}
        <footer class="post_footer u-cf">
            <div class="post_meta">
            {if NOT empty($entry.categories)}
                <span class="info_label">{$CONST.CATEGORIES}: </span>{foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
            {/if}
            {if NOT empty($entry.categories) AND $entry.has_comments} | {/if}
            {if $entry.has_comments}
                <a href="{$entry.link}#comments" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments} {$entry.label_comments}</a>
            {/if}
            </div>
            {$entry.add_footer|default:''}
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
        <p class="serendipity_msg_success">{$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_DELETED}
        <p class="serendipity_msg_important">{$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_APPROVED}
        <p class="serendipity_msg_success">{$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_DELETED}
        <p class="serendipity_msg_important">{$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_APPROVED}
        <p class="serendipity_msg_success">{$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</p>
    {/if}

    <section id="trackbacks">
        <h3>{$CONST.TRACKBACKS}</h3>

        <a class="button trackback_url" rel="nofollow" href="{$entry.link_trackback}" onclick="alert('{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;'); return false;" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>
    {if $entry.trackbacks > 0}

        {serendipity_printTrackbacks entry=$entry.id}
    {/if}

    </section>

    <section id="comments">
        <h3>{$CONST.COMMENTS}</h3>

        <div class="comments_view">{$CONST.DISPLAY_COMMENTS_AS} {if $entry.viewmode eq $CONST.VIEWMODE_LINEAR}{$CONST.COMMENTS_VIEWMODE_LINEAR} | <a href="{$entry.link_viewmode_threaded}#comments" rel="nofollow">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>{else}<a rel="nofollow" href="{$entry.link_viewmode_linear}#comments">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | {$CONST.COMMENTS_VIEWMODE_THREADED}{/if}</div>

        {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
    {if NOT empty($entry.is_entry_owner)}
        {if $entry.allow_comments}
        <a class="comments_enable" href="{$entry.link_deny_comments}">{$CONST.COMMENTS_DISABLE}</a>
        {else}
        <a class="comments_enable" href="{$entry.link_allow_comments}">{$CONST.COMMENTS_ENABLE}</a>
        {/if}
    {/if}
    </section>

    <a id="feedback"></a>
    {foreach $comments_messagestack AS $message}
    <p class="serendipity_msg_important">{$message}</p>
    {/foreach}
    {if $is_comment_added}
    <p class="serendipity_msg_success">{$CONST.COMMENT_ADDED}</p>
    {elseif $is_comment_moderate}
    <p class="serendipity_msg_important">{$CONST.COMMENT_ADDED}{$CONST.THIS_COMMENT_NEEDS_REVIEW}</p>
    {elseif NOT $entry.allow_comments}
    <p class="serendipity_msg_important">{$CONST.COMMENTS_CLOSED}</p>
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
{/foreach}
{else}
    {if NOT $plugin_clean_page AND $view != '404'}
    <p class="serendipity_msg_notice">{$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
{/if}

{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}
    <nav class="pager u-cf" role="navigation">
    {if NOT empty($footer_info)}
        <p>{$footer_info}</p>
    {/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul class="plainList">
        {if $footer_prev_page}
            <li class="pager_prev u-pull-left"><a class="button button-primary" href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a></li>
        {/if}
        {if $footer_next_page}
            <li class="pager_next u-pull-right"><a class="button button-primary" href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a></li>
        {/if}
        </ul>
    {/if}
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
