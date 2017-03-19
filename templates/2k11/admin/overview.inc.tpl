    <div id="dashboard_header" class="clearfix">
        <h2>{$CONST.WELCOME_BACK} {$username|escape}</h2>
        <a href="#s9y_links" class="button_link toggle_links"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FURTHER_LINKS}</span></a>
    </div>
{$backend_frontpage_display}
    <div id="dashboard">
    {if $published}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_PUBLISHED|sprintf:$published|escape}</span>
        <hr class="separator">
    {/if}
    {if $error_publish}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PUBLISH_ERROR}: {$error_publish}</span>
        <hr class="separator">
    {/if}

    {if $updateCheck == "stable" OR $updateCheck == "beta"}
        {if $curVersion == -1}
            <section id="dashboard_update" class="clearfix dashboard_widget{if $default_widgets} expand{/if}">
                <h3>{$CONST.UPDATE_NOTIFICATION}</h3>

                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.UPDATE_FAILMSG}</span>
                <form id="updateCheckDisable" method="POST">
                    <input type="hidden" name="serendipity[adminAction]" value="updateCheckDisable" />
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
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=approve&amp;serendipity[id]={$comment.id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.APPROVE}"><span class="icon-thumbs-up-alt" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.APPROVE}</span></a></li>
                    {/if}
                    {if ($comment.status == 'approved')}
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=pending&amp;serendipity[id]={$comment.id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" title="{$CONST.SET_TO_MODERATED}"><span class="icon-thumbs-down-alt" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SET_TO_MODERATED}</span></a></li>
                    {/if}
                        <li><a class="button_link comments_delete" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=delete&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;serendipity[authorid]={$comment.authorid}&amp;{$urltoken}" data-delmsg='{($CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author)|escape}' title="{$CONST.DELETE}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a>
                        </li>
                    {if $comment.excerpt}
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
                                    <input type="hidden" name="serendipity[adminAction]" value="publish" />
                                    <input type="hidden" name="serendipity[id]" value="{$entry.id}" />
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

        {serendipity_hookPlugin hook="backend_dashboard" hookAll="true"}
    {/if}

        <section id="s9y_links" class="clearfix mfp-hide dashboard_widget">
            <h3>{$CONST.FURTHER_LINKS}</h3>

            <ul class="plainList">
                <li><a href="https://www.s9y.org/">{$CONST.FURTHER_LINKS_S9Y}</a></li>
                <li><a href="https://docs.s9y.org/docs/">{$CONST.FURTHER_LINKS_S9Y_DOCS}</a></li>
                <li><a href="https://blog.s9y.org/">{$CONST.FURTHER_LINKS_S9Y_BLOG}</a></li>
                <li><a href="https://board.s9y.org/">{$CONST.FURTHER_LINKS_S9Y_FORUMS}</a></li>
                <li><a href="http://spartacus.s9y.org/">{$CONST.FURTHER_LINKS_S9Y_SPARTACUS}</a></li>
                <li><a href="https://github.com/s9y/Serendipity">GitHub Serendipity</a></li>
                <li><a href="https://github.com/ophian/styx">GitHub Styx</a></li>
                <li><a href="https://github.com/ophian/styx/wiki">GitHub Styx Wiki</a></li>
                <li><a class="s9y_bookmarklet" href="{$bookmarklet}" title="{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET_DESC}">{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET}</a></li>
            </ul>
        </section>

        <section id="s9y_quicktip" class="clearfix mfp-hide quick_list dashboard_widget">
            <h3>Styx Quick Tip</h3>
            <ol class="plainList quick_info">
                <li>
                    <b>I. What is this?</b><br>
                    <span><em>This is the &#187;<b>Backend</b>&#171;; The place for administration.<br>It is not accessible for the public, which only has access to the &#187;<b>Frontend</b>&#171;, the published <u>View</u>, ordered by your <u>Theme</u>.</em></span>
                </li>
                <li>
                    <b>II. Configurate the Dashboard?</b><br>
                    <span><em>Open &#187;{$CONST.PERSONAL_SETTINGS}&#171; options via top nav <span class="icon-cog-alt" aria-hidden="true"></span> button.</em></span>
                </li>
                <li>
                    <b>III. Add even more to the Dashboard?</b><br>
                    <span><em>Open up the plugin list via &#187;{$CONST.MENU_SETTINGS}</em> &#10140; <em>{$CONST.MENU_PLUGINS}&#171; and install an <u>event</u> plugin, eg the recommended &#187;Serendipity Autoupdate&#171; Plugin. You may find it in the DASHBOARD group category.</em></span>
                </li>
                <li>
                    <b>IV. Searching for more themes?</b><br>
                    <span><em>Open the &#187;Spartacus&#171; Event Plugin Configuration and enable the themes option. This is disabled by default, since it can take a little longer to fetch the data on first call.</em></span>
                </li>
                <li>
                    <b>V. Specific Configurations?</b><br>
                    <span><em>For example the configuration for the Autoupdate is done specifically in its plugin configuration and the more general behaviour is set in the &#187;{$CONST.CONFIGURATION}</em> &#10140; <em>{$CONST.INSTALL_CAT_SETTINGS}&#171; Section. Global theme options are set near that too, but some themes have their own configuration page, like the standard theme 2k11. The blog language in example is set in &#187;{$CONST.CONFIGURATION}&#171;... and in &#187;{$CONST.PERSONAL_SETTINGS}&#171; for the user.</em></span>
                </li>
                <li>
                    <b>VI. First Start Recommendation:</b><br>
                    <span><em>Do <u>not</u> start by installing various plugins at once. Each one allocates additional resources, like RAM or time, and slows down your blog. Keeping this in mind you may test and remove all you want.</em></span><br>
                    <span><em>Themes are template based by the Smarty template engine, easy to learn. Each theme may easily be extended by creating a user.css file with some overwriting stylesheets of your need. If you want to have even more flexibility and independency, make yourself a copy-theme and extend it without having to mind any further system update.</em></span><br>
                    <span><em><br>Read the Documentation and the FAQ for more.</em></span><br>
                    <span><em>This System is highly configurable and some of these advanced options are not recommended to use naively without deeper knowledge of what they will do.</em></span>
                </li>
                <li>
                    <b>VII. Styx Guide:</b><br>
                    <span><em>Read the important Styx Upgrade Documentation and the hitchhikers guide to the Styx Backend for more</em> <a href="https://github.com/ophian/styx/wiki" target="_blank">here</a>.</span>
                </li>
            </ol>
        </section>
    </div>

<script type="text/javascript">
$(document).ready(function() {
    if (typeof(serendipity) != 'object' || typeof(serendipity.spawn) != 'function') {
        $('#dashboard_header').after("<span class=\"msg_error\"><span class=\"icon-attention-circled\"></span> {$CONST.JS_FAILURE|sprintf:$js_failure_file|escape:javascript}</span>");
    }
});
</script>
