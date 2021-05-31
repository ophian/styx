<!-- ENTRIES START -->
{capture name="plugin_hook_entries_header" assign="pluginHook_entries"}{serendipity_hookPlugin hook="entries_header" addData=$entry_id}{/capture}

{if NOT empty($pluginHook_entries)}

<section id="section_hookPlugin_entries">
  {$pluginHook_entries}

</section><!-- // "id:#section_hookPlugin_entries" end -->
{/if}
{if isset($view) AND (($view == 'archives' AND isset($head_subtitle) AND NOT empty($archives_summary_page)) OR ($view == 'frontpage'))}

    <div id="archives_nav" class="archives_index_navigation">
        <ul class="archives_index">
              <li class="archive_item"><a href="{$serendipityBaseURL}">{$blogTitle|truncate:20:"&hellip;":false}</a> &raquo; {if NOT empty($category_info.category_name)}{$CONST.CATEGORY}: {$category_info.category_name} &raquo; {/if}{$CONST.STATICPAGE_ARTICLE_OVERVIEW}{if $view == 'archives'}: {$dateRange.0|formatTime:"%B %Y"}{/if}</li>
        </ul>
    </div>
{/if}
{if isset($view) AND $view == 'categories'}

    <div id="categories_nav" class="categories_index_navigation">
        <ul class="categories_index">
            <li class="category_item"><a href="{$serendipityBaseURL}">{$blogTitle|truncate:20:"&hellip;":false}</a>&raquo; {$CONST.STATICPAGE_ARTICLE_OVERVIEW} {$CONST.CATEGORY}: {$category_info.category_name}</li>
        </ul>
    </div>
{/if}
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

{if $is_single_entry AND NOT $is_preview AND NOT empty($smarty_entrypaging)}
    <div id="serendipity_smarty_entrypaging">
        {if NOT empty($pagination_prev_link)}
            <div class="smarty_pagination_left"><a href="{$pagination_prev_link}" title="{$pagination_prev_title}"><svg viewbox="0 0 100 100"><path class="arrow" d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" /></svg></a></div>
        {/if}
        {if NOT empty($pagination_next_link)}
            <div class="smarty_pagination_right"><a href="{$pagination_next_link}" title="{$pagination_next_title}"><svg viewbox="0 0 100 100"><path class="arrow" d="M 50,0 L 60,10 L 20,50 L 60,90 L 50,100 L 0,50 Z" /></svg></a></div>
        {/if}
    </div>
{/if}

{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}{if $is_preview AND $dategroup@index > 0}{break}{/if}

<section id="section_dategroup_entries" class="hentry serendipity_Entry_Date{if $dategroup.is_sticky} serendipity_Sticky_Entry{/if}">
    <header id="header_dategroup_entries">
    {if $dategroup.is_sticky}
        {if $template_option.show_sticky_entry_heading}

            <h3 class="serendipity_date">{$CONST.STICKY_POSTINGS}</h3>
        {/if}
    {else}

        <h3 class="serendipity_date"><abbr class="published" title="{$dategroup.date|formatTime:'%Y-%m-%dT%H:%M:%S%Z'}">{$dategroup.date|formatTime:$template_option.date_format}</abbr></h3>
    {/if}

    </header>

    <article id="article_dategroup_entries">
    {foreach $dategroup.entries AS $entry}
      {if $is_single_entry AND $view == 'entry'}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" - $entry array relates in trackbacks - and index.tpl Rich Text Editor asset includes *}{/if}

      <section id="section_dategroup_entry">
        <header id="header_dategroup_entry">
          <h4 class="entry-title serendipity_title"><a href="{$entry.link}" rel="bookmark">{$entry.title}</a></h4>
        </header>

        <article id="article_dategroup_entry">
          <section id="section_entry_author" class="serendipity_entry serendipity_entry_author_{$entry.author|makeFilename}{if NOT empty($entry.is_entry_owner)} serendipity_entry_author_self{/if}">

            <header id="header_entry_author">
            {if (NOT $dategroup.is_sticky OR ($dategroup.is_sticky and $template_option.show_sticky_entry_footer))}
                {if $template_option.entryfooterpos == 'belowtitle'}

                    <div class="serendipity_entryFooter belowtitle">
                        {if $template_option.footerauthor}

                            {$CONST.POSTED_BY} <address class="author"><a href="{$entry.link_author}">{$entry.author}</a></address>
                        {/if}
                        {if $template_option.footercategories}
                            {if NOT empty($entry.categories)}

                                {$CONST.IN} {foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
                            {/if}
                        {/if}
                        {if $template_option.footertimestamp}
                            {if $dategroup.is_sticky}{$CONST.ON}{else}{$CONST.AT}{/if}

                            <a href="{$entry.link}">{if $dategroup.is_sticky}{$entry.timestamp|formatTime:$template_option.date_format} {/if}{$entry.timestamp|formatTime:'%H:%M'}</a>
                        {/if}
                        {if $template_option.footercomments}
                            {if $entry.has_comments}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link}#comments">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link}#comments">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if $template_option.footertrackbacks}
                            {if $entry.has_trackbacks}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link}#trackbacks">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link}#trackbacks">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}

                        <div class="editentrylink"><a href="{$entry.link_edit}">{$CONST.EDIT_ENTRY}</a></div>
                        {/if}

                        {$entry.add_footer|default:''}
                    </div>
                {/if}
                {if $template_option.entryfooterpos == 'splitfoot'}
                  {if NOT $template_option.footerauthor AND NOT $template_option.footercategories AND NOT $template_option.footertimestamp}
                  {else}

                    <div class="serendipity_entryFooter byline">
                        {if $template_option.footerauthor}

                            {$CONST.POSTED_BY} <address class="author"><a href="{$entry.link_author}">{$entry.author}</a></address>
                        {/if}
                        {if $template_option.footercategories}
                            {if NOT empty($entry.categories)}

                                {$CONST.IN} {foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
                            {/if}
                        {/if}
                        {if $template_option.footertimestamp}
                            {if $dategroup.is_sticky}{$CONST.ON}{else}{$CONST.AT}{/if}

                            <a href="{$entry.link}">{if $dategroup.is_sticky}{$entry.timestamp|formatTime:$template_option.date_format} {/if}{$entry.timestamp|formatTime:'%H:%M'}</a>
                        {/if}

                    </div>
                  {/if}
                {/if}
            {/if}
            {if NOT empty($entry.categories)}

                <span class="serendipity_entryIcon">
                    {foreach $entry.categories AS $entry_category}
                        {if $entry_category.category_icon}

                            <a href="{$entry_category.category_link}"><img class="serendipity_entryIcon" title="{$entry_category.category_name|escape}{$entry_category.category_description|emptyPrefix}" alt="{$entry_category.category_name|escape}" src="{$entry_category.category_icon|escape}"></a>
                        {/if}
                    {/foreach}

               </span>
            {/if}

            </header><!-- // "id:#header_entry_author" end -->

            <article id="article_entry_author">
              <div class="entry-content serendipity_entry_body">
                {$entry.body}
                {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}

                    <div class="clear continue_reading"><a href="{$entry.link}#extended" title="{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title|truncate:50:"&hellip;"}">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title|truncate:50:"&hellip;"} &#187;</a></div>
                {/if}

              </div>

              {if $entry.is_extended}

              <div class="clear serendipity_entry_extended"><a id="extended"></a>{$entry.extended}</div>
              {/if}

            </article><!-- // "id:#article_entry_author" end -->

            {if (NOT $dategroup.is_sticky OR ($dategroup.is_sticky and $template_option.show_sticky_entry_footer))}

            <footer id="footer_entry_author">
                {if $template_option.entryfooterpos == 'belowentry'}

                    <div class="clear serendipity_entryFooter belowentry">
                        {if $template_option.footerauthor}

                            {$CONST.POSTED_BY} <address class="author"><a href="{$entry.link_author}">{$entry.author}</a></address>
                        {/if}
                        {if $template_option.footercategories}
                            {if NOT empty($entry.categories)}

                                {$CONST.IN} {foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
                            {/if}
                        {/if}

                        {if $template_option.footertimestamp}
                            {if $dategroup.is_sticky}{$CONST.ON}{else}{$CONST.AT}{/if}

                                <a href="{$entry.link}">{if $dategroup.is_sticky}{$entry.timestamp|formatTime:$template_option.date_format} {/if}{$entry.timestamp|formatTime:'%H:%M'}</a>
                        {/if}
                        {if $template_option.footercomments}
                            {if $entry.has_comments}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link}#comments">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link}#comments">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if $template_option.footertrackbacks}
                            {if $entry.has_trackbacks}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link}#trackbacks">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link}#trackbacks">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if $template_option.send2printer}

                            | <a href="javascript:window.print()">{$CONST.SEND2_PRINTER}</a>
                        {/if}

                        {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}

                            <div class="editentrylink"><a href="{$entry.link_edit}">{$CONST.EDIT_ENTRY}</a></div>
                        {/if}

                        {$entry.add_footer|default:''}

                    </div>
                {/if}

                {if $template_option.entryfooterpos == 'splitfoot'}
                    <div class="serendipity_entryFooter infofooter">
                        {if $template_option.footercomments}
                            {if $entry.has_comments}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        <a href="{$entry.link_popup_comments}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        <a href="{$entry.link}#comments">{if $entry.comments == 0}{$CONST.NO_COMMENTS}{else}{$entry.comments} {$entry.label_comments}{/if}</a>
                                    {else}

                                        <a href="{$entry.link}#comments">{$entry.label_comments} ({$entry.comments})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if $template_option.footertrackbacks}
                            {if $entry.has_trackbacks}
                                {if $use_popups}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link_popup_trackbacks}" onclick="window.open(this.href, 'comments', 'width=600,height=600,scrollbars=yes,resizable=yes'); return false;">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {else}
                                    {if $template_option.altcommtrack}

                                        | <a href="{$entry.link}#trackbacks">{if $entry.trackbacks == 0}{$CONST.NO_TRACKBACKS}{else}{$entry.trackbacks} {$entry.label_trackbacks}{/if}</a>
                                    {else}

                                        | <a href="{$entry.link}#trackbacks">{$entry.label_trackbacks} ({$entry.trackbacks})</a>
                                    {/if}
                                {/if}
                            {/if}
                        {/if}
                        {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}

                            <div class="editentrylink"><a href="{$entry.link_edit}">{$CONST.EDIT_ENTRY}</a></div>
                        {/if}

                        {$entry.add_footer|default:''}

                    </div>
                {/if}

            </footer><!-- // "id:#footer_entry_author" end -->
            {/if}

            {if ($is_single_entry OR $is_preview)}
                {* microformats_show data=$entry.properties type="hReview" *}
            {/if}

          </section><!-- // "id:#section_entry_author" end -->
        </article><!-- // "id:#article_dategroup_entry" end -->

        <!--
        <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                 xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/"
                 xmlns:dc="http://purl.org/dc/elements/1.1/">
        <rdf:Description
                 rdf:about="{$entry.link_rdf}"
                 trackback:ping="{$entry.link_trackback}"
                 dc:title="{$entry.title_rdf|default:$entry.title}"
                 dc:identifier="{$entry.rdf_ident}">
        </rdf:RDF>
        -->

    {if $is_single_entry}

        <footer id="footer_dategroup_entry">
        {$entry.plugin_display_dat}

        {if $is_single_entry AND NOT $is_preview}
            {if $CONST.DATA_UNSUBSCRIBED}

                <div class="serendipity_center serendipity_msg_notice">{$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</div>
            {/if}
            {if $CONST.DATA_TRACKBACK_DELETED}

                <div class="serendipity_center serendipity_msg_notice">{$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</div>
            {/if}
            {if $CONST.DATA_TRACKBACK_APPROVED}

                <div class="serendipity_center serendipity_msg_notice">{$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</div>
            {/if}
            {if $CONST.DATA_COMMENT_DELETED}

                <div class="serendipity_center serendipity_msg_notice">{$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</div>
            {/if}
            {if $CONST.DATA_COMMENT_APPROVED}

                <div class="serendipity_center serendipity_msg_notice">{$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</div>
            {/if}

            <div class="serendipity_comments serendipity_section_trackbacks">
                <a id="trackbacks"></a>
                <div class="serendipity_commentsTitle">{$CONST.TRACKBACKS}</div>
                <div class="serendipity_center">
                    <a rel="nofollow" href="{$entry.link_trackback}" onclick="alert('{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;'); return false;" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>
                </div>
                <div id="serendipity_trackbacklist">
                {serendipity_printTrackbacks entry=$entry.id}
                </div>
            </div>

            <div class="serendipity_comments serendipity_section_comments">
                <a id="comments"></a>
                <div class="serendipity_commentsTitle">{$CONST.COMMENTS}</div>
                <div class="serendipity_center">{$CONST.DISPLAY_COMMENTS_AS}
                {if $entry.viewmode eq $CONST.VIEWMODE_LINEAR}

                    ({$CONST.COMMENTS_VIEWMODE_LINEAR} | <a href="{$entry.link_viewmode_threaded}#comments" rel="nofollow">{$CONST.COMMENTS_VIEWMODE_THREADED}</a>)
                {else}

                    (<a rel="nofollow" href="{$entry.link_viewmode_linear}#comments">{$CONST.COMMENTS_VIEWMODE_LINEAR}</a> | {$CONST.COMMENTS_VIEWMODE_THREADED})
                {/if}

                </div>
                <div id="serendipity_commentlist">
                {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
                </div>

            {if NOT empty($entry.is_entry_owner)}
                {if $entry.allow_comments}

                    <div class="serendipity_center">(<a href="{$entry.link_deny_comments}">{$CONST.COMMENTS_DISABLE}</a>)</div>
                {else}

                    <div class="serendipity_center">(<a href="{$entry.link_allow_comments}">{$CONST.COMMENTS_ENABLE}</a>)</div>
                {/if}
            {/if}

                <a id="feedback"></a>

                {foreach $comments_messagestack AS $message}

                    <div class="serendipity_center serendipity_msg_important">{$message}</div>
                {/foreach}
                {if $is_comment_moderate}

                    <div class="serendipity_center serendipity_msg_notice">{$CONST.COMMENT_ADDED}<br>{$CONST.THIS_COMMENT_NEEDS_REVIEW}</div>
                {elseif $is_comment_added}

                    <div class="serendipity_center serendipity_msg_notice">{$CONST.COMMENT_ADDED}</div>
{if $is_logged_in}
                    <div class="serendipity_section_commentform">
                       <div class="serendipity_commentsTitle">{$CONST.ADD_COMMENT}</div>
                       {$COMMENTFORM}
                    </div>
{/if}
                {elseif NOT $entry.allow_comments}

                    <div class="serendipity_center serendipity_msg_important">{$CONST.COMMENTS_CLOSED}</div>
                {else}

                    <div class="serendipity_section_commentform">
                       <div class="serendipity_commentsTitle">{$CONST.ADD_COMMENT}</div>
                       {$COMMENTFORM}

                    </div>
                {/if}

            </div>
        {/if}

        {$entry.backend_preview}

        </footer><!-- // "id:#footer_dategroup_entry" end -->
        {/if}

      </section><!-- // "id:#section_dategroup_entry" end -->
    {/foreach}

    </article><!-- // "id:#article_dategroup_entries" end -->
</section><!-- // "id:#section_dategroup_entries" end -->

{/foreach}
{else}
    {if empty($plugin_clean_page) AND isset($view) AND $view != '404'}

<section id="section_noentries">
  <div class="serendipity_overview_noentries">
    {$CONST.NO_ENTRIES_TO_PRINT}

  </div>
</section>
    {/if}
{/if}

{/if}{* not taglist end *}

{if empty($is_single_entry) AND empty($is_preview) AND isset($view) AND $view != 'plugin' AND isset($footer_totalPages) AND $footer_totalPages > 1}
{if NOT empty($taglist)}{* this is for case taglist 100+ entries *}
    {if $footer_prev_page}{assign var="footer_prev_page" value=$footer_prev_page|replace:'/plugin/':'/plugin/taglist/'}{/if}
    {if $footer_next_page}{assign var="footer_next_page" value=$footer_next_page|replace:'/plugin/':'/plugin/taglist/'}{/if}
{/if}

<section id="section_pagination">
  <div id="center"{if NOT $template_option.show_pagination} class="serendipity_entriesFooter"{/if}>
    {if $footer_prev_page}
        {if $template_option.prev_next_style == 'texticon'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><img alt="{$CONST.PREVIOUS_PAGE}" title="{$CONST.PREVIOUS_PAGE}" src="{serendipity_getFile file="img/back.png"}">{$CONST.PREVIOUS_PAGE}</a>
        {elseif  $template_option.prev_next_style == 'icon'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><img alt="{$CONST.PREVIOUS_PAGE}" src="{serendipity_getFile file="img/back.png"}">{$CONST.PREVIOUS_PAGE}</a>
        {elseif $template_option.prev_next_style == 'text'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}">&#171; {$CONST.PREVIOUS_PAGE}</a>&#160;&#160;
        {/if}
    {/if}
    {if NOT empty($footer_info)}

        ({$footer_info})
    {/if}
    {if $footer_next_page}
        {if $template_option.prev_next_style == 'texticon'}

            <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}">{$CONST.NEXT_PAGE}<img alt="{$CONST.NEXT_PAGE}" title="{$CONST.NEXT_PAGE}" src="{serendipity_getFile file="img/forward.png"}"></a>
        {elseif $template_option.prev_next_style == 'icon'}

            <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}"><img alt="{$CONST.NEXT_PAGE}" src="{serendipity_getFile file="img/forward.png"}"></a>
        {elseif $template_option.prev_next_style == 'text'}

             <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}">{$CONST.NEXT_PAGE} &#187;</a>
        {/if}
    {/if}
    {if $template_option.show_pagination AND $footer_totalPages > 1}

        <div class="pagination">
            {assign var="paginationStartPage" value="`$footer_currentPage-3`"}
            {if $footer_currentPage+3 > $footer_totalPages}
                {assign var="paginationStartPage" value="`$footer_totalPages-6`"}
            {/if}
            {if $paginationStartPage <= 0}
                {assign var="paginationStartPage" value="1"}
            {/if}
            {if $footer_prev_page}

                <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><span class="pagearrow">&#9668;</span></a>
            {/if}
            {if $paginationStartPage > 1}

                <a href="{$footer_pageLink|replace:'%s':'1'}">1</a>
            {/if}
            {if $paginationStartPage > 2}&hellip;{/if}
            {section name=i start=$paginationStartPage loop=$footer_totalPages+1 max=7}
                {if $smarty.section.i.index != $footer_currentPage}

                    <a href="{$footer_pageLink|replace:'%s':$smarty.section.i.index}">{$smarty.section.i.index}</a>
                {else}

                    <span id="thispage">{$smarty.section.i.index}</span>
                {/if}
            {/section}
            {if $smarty.section.i.index < $footer_totalPages}&hellip;{/if}
            {if $smarty.section.i.index <= $footer_totalPages}

                <a href="{$footer_pageLink|replace:'%s':$footer_totalPages}">{$footer_totalPages}</a>
            {/if}
            {if $footer_next_page}

                <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}"><span class="pagearrow">&#9658;</span></a>
            {/if}

        </div>
    {/if}

  </div><!-- // "id:#center" end -->
</section><!-- // "id:#section_pagination" end -->
{/if}

{serendipity_hookPlugin hook="entries_footer"}

<!-- ENTRIES END -->
