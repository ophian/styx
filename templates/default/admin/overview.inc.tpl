    <div id="dashboard_header" class="clearfix">
        <h2>{$CONST.WELCOME_BACK} {$username|escape}</h2>
        <a href="#s9y_links" class="button_link toggle_links"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FURTHER_LINKS}</span></a>
    </div>
{$backend_frontpage_display}
    <div id="dashboard">
    {if isset($published)}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_PUBLISHED|sprintf:$published|escape}</span>
        <hr class="separator">
    {/if}
    {if isset($error_publish)}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PUBLISH_ERROR}: {$error_publish}</span>
        <hr class="separator">
    {/if}

    {if isset($updateCheck) AND ($updateCheck == "stable" OR $updateCheck == "beta")}
        {if $curVersion == -1}
            <section id="dashboard_update" class="clearfix dashboard_widget{if $default_widgets} expand{/if}">
                <h3>{$CONST.UPDATE_NOTIFICATION}</h3>

                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.UPDATE_FAILMSG|sprintf:$releaseFUrl}</span>
                <form id="updateCheckDisable" method="POST">
                    <input type="hidden" name="serendipity[adminAction]" value="updateCheckDisable">
                    {$token}
                    <button type="submit">{$CONST.UPDATE_FAILACTION}</button>
                </form>
            </section>
            <hr class="separator">
        {else if $update}
            <section id="dashboard_update" class="clearfix dashboard_widget{if $default_widgets} expand{/if}">
                <h3>{$CONST.UPDATE_NOTIFICATION}</h3>

                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NEW_VERSION_AVAILABLE|replace:'Serendipity':$curVersName} {$curVersion}</span>
                {$updateButton}
            </section>
            <hr class="separator">
        {/if}
    {/if}

{if $no_create !== true}
    {if $default_widgets}
        <section id="dashboard_comments" class="equal_heights quick_list dashboard_widget">
            <h3>{if 'adminComments'|checkPermission}<a href="serendipity_admin.php?serendipity[adminModule]=comments">{/if}{$CONST.COMMENTS}{if 'adminComments'|checkPermission}</a>{/if}</h3>

            <ol class="plainList">
            {if is_array($comments)}
                {foreach $comments AS $comment}
                <li class="clearfix"><b>{$comment.author|escape|truncate:30:"&hellip;"} {$CONST.IN} <a href="{$comment.entrylink}" title="Comment to {$comment.title}">#{$comment.id}</a></b>
                    <div class="comment_summary">{$comment.body|escape|truncate:100:"&hellip;"}</div>

                    <div id="c{$comment.id}_full" class="comment_full additional_info">{$comment.fullBody|escape}</div>

                    <ul class="plainList actions">
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.EDIT}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
                        <li><a class="button_link comments_reply" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=reply&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.REPLY}"><span class="icon-chat" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.REPLY}</span></a></li>
                    {if ($comment.status == 'pending') OR ($comment.status == 'confirm')}
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=approve&amp;serendipity[id]={$comment.id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.APPROVE}"><span class="icon-toggle-on" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.APPROVE}</span></a></li>
                    {/if}
                    {if ($comment.status == 'approved')}
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=pending&amp;serendipity[id]={$comment.id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.SET_TO_MODERATED}"><span class="icon-toggle-off" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SET_TO_MODERATED}</span></a></li>
                    {/if}
                        <li><a class="button_link comments_delete" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=delete&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" data-delmsg='{($CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author)|escape}' title="{$CONST.DELETE}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a>
                        </li>
                    {if NOT empty($comment.excerpt)}
                        <li><button class="button_link toggle_comment_full" type="button" data-href="#c{$comment.id}_full" title="{$CONST.TOGGLE_ALL}"><span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TOGGLE_ALL}</span></button></li>
                    {/if}
                    </ul>
                {if ($comment.status == 'pending') OR ($comment.status == 'confirm')}
                    <span class="comment_status">{$CONST.COMMENTS_FILTER_NEED_APPROVAL}</span>
                {/if}
                </li>
                {/foreach}
            {else}
                <li><span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_COMMENTS}</span></li>
            {/if}
            </ol>
        </section>

        <section id="dashboard_entries" class="equal_heights quick_list dashboard_widget">
            <h3>{if 'adminEntries'|checkPermission}<a href="serendipity_admin.php?serendipity[adminModule]=entries&amp;serendipity[adminAction]=editSelect">{/if}{$CONST.DASHBOARD_ENTRIES}{if 'adminEntries'|checkPermission}</a>{/if}</h3>

            <ol class="plainList">
            {if is_array($entries)}
                {foreach $entries AS $entry}
                <li class="clearfix">
                    <a href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$entry.id}" title="#{$entry.id}: {$entry.title|escape}">{$entry.title|escape}</a>
                    <ul class="plainList actions">
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=preview&amp;{$urltoken}&amp;serendipity[id]={$entry.id}" title="{$CONST.PREVIEW} #{$entry.id}"><span class="icon-search" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIEW}</span></a></li>
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$entry.id}" title="{$CONST.EDIT} #{$entry.id}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
                        {if $entry.isdraft == "true"}
                            <li>
                                <form method="POST" class="overviewListForm">
                                    <input type="hidden" name="serendipity[adminAction]" value="publish">
                                    <input type="hidden" name="serendipity[id]" value="{$entry.id}">
                                    {$token}
                                    <button class="publish_now" type="submit" title="{$CONST.PUBLISH_NOW}"><span class="icon-rocket" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.PUBLISH_NOW}</span></button>
                                </form>
                            </li>
                        {/if}

                    </ul>
                {if !$showFutureEntries AND ($entry.timestamp >= $serverOffsetHour) AND $entry.isdraft == "false"}
                    <span class="entry_status status_future" title="{$CONST.SCHEDULED}: {$CONST.ENTRY_PUBLISHED_FUTURE}">{$entry.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}</span>
                {/if}
                {if $entry.properties.ep_is_sticky}
                    <span class="entry_status status_sticky">{$CONST.STICKY_POSTINGS}</span>
                {/if}
                {if $entry.isdraft == "true"}
                    <span class="entry_status status_draft">{$CONST.DRAFT}</span>
                {/if}
                </li>
                {/foreach}
            {else}
                <li><span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</span></li>
            {/if}
            </ol>
        </section>
    {/if}

    {if NOT $default_widgets}
        <section id="dashboard_ticker" class="clearfix dashboard_widget{if NOT isset($shortcuts)} expand blend{/if}">
            <h3>{$CONST.DASHBOARD_INFO_HEADER}</h3>

            <div class="{if !isset($shortcuts)}msg_{/if}notice">
                {if isset($shortcuts)}{* $CONST.DASHBOARD_INFO_CONTENT}: *}{else}<span class="icon-info-circled" aria-hidden="true"></span> <em>{$CONST.DASHBOARD_INFO_EMPTY}</em>{/if}
            {if isset($shortcuts)}
                <ul class="plainList">
                {if $comments['pending']['count'] > 0}
                    <li><a href="{$comments['pending']['link']}">{$CONST.COMMENTS_PENDING}</a> [<span class="hl">{$comments['pending']['count']}</span>]</li>
                {/if}
                {if $entries['futures']['count'] > 0}
                    <li><a href="{$entries['futures']['link']}">{$CONST.FUTURES_AVAILABLE}</a> [<span class="hl">{$entries['futures']['count']}</span>]</li>
                {/if}
                {if $entries['drafts']['count'] > 0}
                    <li><a href="{$entries['drafts']['link']}">{$CONST.DRAFTS_AVAILABLE}</a> [<span class="hl">{$entries['drafts']['count']}</span>]</li>
                {/if}
                </ul>
            {/if}
            </div>
        </section>
    {/if}

        {serendipity_hookPlugin hook="backend_dashboard" hookAll="true"}

{/if}{* no create end *}

        <section id="s9y_links" class="clearfix mfp-hide dashboard_widget">
            <h3>{$CONST.FURTHER_LINKS}</h3>

            <ul class="plainList">
                <li><a target="_blank" href="https://ophian.github.io/">{$CONST.FURTHER_LINKS_S9Y} (Styx)</a></li>
                <li><a target="_blank" href="https://ophian.github.io/hc/en/">{$CONST.FURTHER_LINKS_S9Y_DOCS} (Styx)</a></li>
                <li><a target="_blank" href="https://ophian.github.io/book/">Serendipity Styx Edition Book [de]</a></li>
                <li><a target="_blank" href="https://ophian.github.io/blog">{$CONST.FURTHER_LINKS_S9Y_BLOG} (Styx)</a></li>
                <li><a target="_blank" href="https://board.s9y.org/">{$CONST.FURTHER_LINKS_S9Y_FORUMS} (S9y)</a></li>
                <li><a target="_blank" href="https://ophian.github.io/plugins/">{$CONST.FURTHER_LINKS_S9Y_SPARTACUS} (Styx) Web</a></li>
                <li><a target="_blank" href="https://github.com/ophian/styx">GitHub Serendipity Styx Edition</a></li>
                <li>&nbsp;</li>
                <li><a class="s9y_bookmarklet" href="{$bookmarklet}" title="{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET_DESC}">{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET}</a></li>
            </ul>
        </section>

        <section id="s9y_quicktip" class="clearfix mfp-hide quick_list dashboard_widget">
            {if $head_charset == 'UTF-8'}
            {include "./doc/UTF-8/quicktip_$lang.tpl" caching}
            {else}
            {include "./doc/quicktip_$lang.tpl" caching}
            {/if}
        </section>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    if (typeof(serendipity) != 'object' || typeof(serendipity.spawn) != 'function') {
        $('#dashboard_header').after("<span class=\"msg_error\"><span class=\"icon-attention-circled\"></span> {$CONST.JS_FAILURE|sprintf:$js_failure_file|escape:javascript}</span>");
    }
    if ($("#dashboard_ticker").hasClass('blend')) {
        if (Cookies.get('styx_tickerBlend')) {
            $("#dashboard_ticker").hide();
        } else {
            $("#dashboard_ticker").delay(5000).fadeOut( 2500, 'linear' );
            Cookies.set('styx_tickerBlend', true, { sameSite: 'lax' });
        }
    }
    if ($("#dashboard_plugup").hasClass('blend')) {
        if (Cookies.get('styx_plugupBlend')) {
            $("#dashboard_plugup").hide();
        } else {
            $("#dashboard_plugup").delay(5000).fadeOut( 2500, 'linear' );
            Cookies.set('styx_plugupBlend', true, { sameSite: 'lax' });
        }
    }
});
</script>
