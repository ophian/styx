{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}
    {foreach $dategroup.entries AS $entry}
    {if $is_single_entry AND $view == 'entry'}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" - $entry array relates in trackbacks - and index.tpl Rich Text Editor asset includes *}{/if}
    <article class="post{if $dategroup.is_sticky} sticky{/if} clearfix">
        <header>
            <h2 class="post-title"><a href="{$entry.link}">{$entry.title}</a></h2>

            <span class="post-info">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></span>
        </header>

        <div class="clearfix">
        {$entry.body}
        </div>
        {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}
        <a class="read-more" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>
        {/if}
        {if $entry.is_extended}
        <div id="extended" class="clearfix">
        {$entry.extended}
        </div>
        {/if}

    {if NOT $is_preview}
        <footer class="post-info">
            <ul class="meta">
            {if NOT empty($entry.categories)}
                <li><span class="info-label">{$CONST.CATEGORIES}: </span>{foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}</li>
            {/if}
        {if $entry.has_comments AND empty($is_single_entry)}
            {if $use_popups}
                <li><a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;" title="{$entry.comments} {$entry.label_comments}">{$entry.comments} {$entry.label_comments}</a>, <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;" title="{$entry.trackbacks} {$entry.label_trackbacks}">{$entry.trackbacks} {$entry.label_trackbacks}</a></li>
            {else}
                <li><a href="{$entry.link}{if $entry.has_trackbacks AND $entry.trackbacks > 0}#trackbacks{else}#comments{/if}" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments} {$entry.label_comments}</a></li>
            {/if}
        {else if isset($entry.label_comments) OR isset($entry.label_trackbacks)}
                <li><a href="{$entry.link}{if $entry.has_trackbacks AND $entry.trackbacks > 0}#trackbacks{else}#comments{/if}" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments} {$entry.label_comments}</a></li>
        {/if}
            </ul>
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
        <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_DELETED}
        <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_APPROVED}
        <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_DELETED}
        <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_APPROVED}
        <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</p>
    {/if}
    <section id="trackbacks" class="clearfix">
        <h3>{$CONST.TRACKBACKS}</h3>

        <a class="trackback-url" rel="nofollow" href="{$entry.link_trackback}" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>

        <p class="msg-notice trackback-hint"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;<u>{$entry.rdf_ident|escape}</u>&laquo;</p>

        {serendipity_printTrackbacks entry=$entry.id}
    </section>

    <section id="comments" class="clearfix">
        <h3>{$CONST.COMMENTS}</h3>

        <span class="comment-view">{$CONST.DISPLAY_COMMENTS_AS} {if $entry.viewmode eq $CONST.VIEWMODE_LINEAR}{$CONST.COMMENTS_VIEWMODE_LINEAR} | <a href="{$entry.link_viewmode_threaded}#comments" rel="nofollow">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>{else}<a rel="nofollow" href="{$entry.link_viewmode_linear}#comments">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | {$CONST.COMMENTS_VIEWMODE_THREADED}{/if}</span>

        {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
    {if NOT empty($entry.is_entry_owner)}
        {if $entry.allow_comments}
        <a class="comments-enable" href="{$entry.link_deny_comments}">{$CONST.COMMENTS_DISABLE}</a>
        {else}
        <a class="comments-enable" href="{$entry.link_allow_comments}">{$CONST.COMMENTS_ENABLE}</a>
        {/if}
    {/if}
    </section>

    <a id="feedback"></a>
    {foreach $comments_messagestack AS $message}
    <p class="msg-warning"><span class="icon-info-circled" aria-hidden="true"></span> {$message}</p>
    {/foreach}
    {if $is_comment_added}
    <p class="msg-success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.COMMENT_ADDED|sprintf:"<a href=\"{if $is_logged_in}$commentform_action}{/if}#c{$smarty.get.last_insert_cid}\">#{$smarty.get.last_insert_cid}</a> "}</p>
    {if $is_logged_in}
    <section id="reply" class="clearfix">
        <h3>{$CONST.ADD_COMMENT}</h3>
        {$COMMENTFORM}
    </section>
    {/if}
    {elseif $is_comment_moderate}
    <p class="msg-notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.COMMENT_ADDED|sprintf:''} {$CONST.THIS_COMMENT_NEEDS_REVIEW}</p>
    {elseif NOT $entry.allow_comments}
    <p class="msg-warning"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.COMMENTS_CLOSED}</p>
    {else}
    <section id="reply" class="clearfix">
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
    <p class="msg-notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
{/if}
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}
    <nav class="pagination clearfix">
        {if NOT empty($footer_info)}<h3>{$footer_info}</h3>{/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul>
            <li class="prev-page">{if $footer_prev_page}<a href="{$footer_prev_page}"><span class="icon-angle-circled-left" aria-hidden="true"></span><span class="fallback-text">{$CONST.PREVIOUS_PAGE}</span></a>{else}<span class="no-page"><span class="icon-angle-circled-left" aria-hidden="true"></span><span class="fallback-text">{$CONST.NO_ENTRIES_TO_PRINT}</span></span>{/if}</li>
            <li class="next-page">{if $footer_next_page}<a href="{$footer_next_page}"><span class="icon-angle-circled-right" aria-hidden="true"></span><span class="fallback-text">{$CONST.NEXT_PAGE}</span></a>{else}<span class="no-page"><span class="icon-angle-circled-right" aria-hidden="true"></span><span class="fallback-text">{$CONST.NO_ENTRIES_TO_PRINT}</span></span>{/if}</li>
        </ul>
    {/if}
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
