{if !empty($errormsg)}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$errormsg|nl2br}</span>
{/if}
{if !empty($msg)}
    {if $msgtype == 'notice'}
            <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$msg|nl2br}</span>
    {else}
            <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$msg|nl2br}</span>
    {/if}
{/if}
{if isset($commentReplied) AND $commentReplied}
            <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.COMMENT_ADDED}</span>
            <button id="comment_replied" type="button">{$CONST.BACK}</button>
{else}
            <h2 title="+ {$CONST.TRACKBACKS} / {$CONST.PINGBACKS}">{$CONST.COMMENTS}{if (NOT empty($smarty.get.serendipity.filter.author) OR NOT empty($smarty.get.serendipity.filter.email) OR NOT empty($smarty.get.serendipity.filter.url) OR NOT empty($smarty.get.serendipity.filter.ip) OR NOT empty($smarty.get.serendipity.filter.body) OR NOT empty($smarty.get.serendipity.filter.referer) OR (isset($smarty.get.serendipity.filter.show) AND $smarty.get.serendipity.filter.show != 'all') OR (isset($smarty.get.serendipity.filter.type) AND $smarty.get.serendipity.filter.type != '')) AND empty($smarty.get.comment_filters_reset)} <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-filter-circle-fill" fill="#3e5f81" xmlns="http://www.w3.org/2000/svg"><title id="title">{$CONST.FILTERS}</title><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM3.5 5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zM5 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/></svg>{/if}</h2>

            <form action="" method="GET">
                {$formtoken}
                <input name="serendipity[adminModule]" type="hidden" value="comments">
                <input name="serendipity[page]" type="hidden" value="{$page}">

                <ul class="filters_toolbar filter_comments plainList">
                    <li><a class="button_link" href="#filter_comments" title="{$CONST.FILTERS}"><span class="icon-filter" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FILTERS}</span></a></li>
                    <li><div class="backend_comments">{$backend_comments_top|default:''}</div></li>
                    <li><button class="button_link toggle_comment_full" type="button" data-href="{foreach $comments AS $cf}#{$cf.id}{if isset($cf@last) && $cf@last}{else},{/if}{/foreach}" title="{$CONST.TOGGLE_OPTION}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z"/><path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8zm0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5z"/></svg> <span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TOGGLE_OPTION}</span></button></li>
                </ul>

                <fieldset id="filter_comments" class="additional_info filter_pane">
                    <legend class="visuallyhidden">{$CONST.FILTERS} ({$CONST.FIND_COMMENTS})</legend>

                    <div class="clearfix inputs">
                        <div class="form_field">
                            <label for="filter_author">{$CONST.AUTHOR}</label>
                            <input id="filter_author" name="serendipity[filter][author]" type="text" value="{if NOT empty($get.filter.author)}{$get.filter.author|escape}{/if}">
                        </div>

                        <div class="form_field">
                            <label for="filter_email">{$CONST.EMAIL}</label>
                            <input id="filter_email" name="serendipity[filter][email]" type="text" value="{if NOT empty($get.filter.email)}{$get.filter.email|escape}{/if}">
                        </div>

                        <div class="form_field">
                            <label for="filter_url">{$CONST.HOMEPAGE}</label>
                            <input id="filter_url" name="serendipity[filter][url]" type="text" value="{if NOT empty($get.filter.url)}{$get.filter.url|escape}{/if}">
                        </div>

                        <div class="form_field">
                            <label for="filter_ip">IP</label>
                            <input id="filter_ip" name="serendipity[filter][ip]" type="text" value="{if NOT empty($get.filter.ip)}{$get.filter.ip|escape}{/if}">
                        </div>

                        <div class="form_field">
                            <label for="filter_body">{$CONST.CONTENT}</label>
                            <input id="filter_body" name="serendipity[filter][body]" type="text" value="{if NOT empty($get.filter.body)}{$get.filter.body|escape}{/if}">
                        </div>

                        <div class="form_field">
                            <label for="filter_referer">{$CONST.REFERER}</label>
                            <input id="filter_referer" name="serendipity[filter][referer]" type="text" value="{if NOT empty($get.filter.referer)}{$get.filter.referer|escape}{/if}">
                        </div>

                        <div class="form_select">
                            <label for="filter_perpage">{$CONST.COMMENTS}</label>
                            <select id="filter_perpage" name="serendipity[filter][perpage]">
                            {foreach $filter_vals AS $filter}
                                <option value="{$filter}" {($commentsPerPage == $filter) ? ' selected' : ''}>{$filter}</option>
                            {/foreach}
                            </select>
                        </div>

                        <div class="form_select">
                            <label for="filter_show">{$CONST.COMMENTS_FILTER_SHOW}</label>
                            <select id="filter_show" name="serendipity[filter][show]">
                                <option value="all"{if $get.filter.show == 'all'} selected{/if}>{$CONST.COMMENTS_FILTER_ALL}</option>
                                <option value="approved"{if $get.filter.show == 'approved'} selected{/if}>{$CONST.COMMENTS_FILTER_APPROVED_ONLY}</option>
                                <option value="hidden"{if $get.filter.show == 'hidden'} selected{/if}>{$CONST.COMMENTS_FILTER_HIDDEN_ONLY}</option>
                                <option value="pending"{if $get.filter.show == 'pending'} selected{/if}>{$CONST.COMMENTS_FILTER_APPROVAL_ONLY}</option>
                                <option value="confirm"{if $get.filter.show == 'confirm'} selected{/if}>{$CONST.COMMENTS_FILTER_CONFIRM_ONLY}</option>
                            </select>
                        </div>

                        <div class="form_select">
                            <label for="filter_type">{$CONST.TYPE}</label>
                            <select id="filter_type" name="serendipity[filter][type]">
                                <option value="">{$CONST.COMMENTS_FILTER_ALL}</option>
                                <option value="NORMAL"{if $c_type == 'NORMAL'} selected{/if}>{$CONST.COMMENTS}</option>
                                <option value="TRACKBACK"{if $c_type == 'TRACKBACK'} selected{/if}>{$CONST.TRACKBACKS}</option>
                                <option value="PINGBACK"{if $c_type == 'PINGBACK'} selected{/if}>{$CONST.PINGBACKS}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form_buttons">
                        <input name="submit" type="submit" value="{$CONST.GO}"> <input class="reset_comment_filters state_cancel" name="comment_filters_reset" title="{$CONST.RESET_FILTERS}" type="submit" value="Reset">
                    </div>
                </fieldset>
            </form>
    {if $c_list === false}

            <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span>
                {if $c_type == 'TRACKBACK'}
                    {$CONST.NO_TRACKBACKS}
                {else if $c_type == 'PINGBACK'}
                    {$CONST.NO_PINGBACKS}
                {else}
                    {$CONST.NO_COMMENTS}
                {/if}
            </span>

            {if !empty($c_type) OR isset($smarty.get.submit)}<a class="button_link" href="serendipity_admin.php?serendipity[adminModule]=comments">{$CONST.BACK}</a>{/if}
    {else}

            <form id="formMultiSelect" name="formMultiSelect" action="" method="POST">
                {$formtoken}
                <input name="serendipity[formAction]" type="hidden" value="multiDelete">

                <div class="clearfix comments_pane">
                {if is_array($comments)}

                    <ul id="serendipity_comments_list" class="clearfix plainList zebra_list">
                    {foreach $comments AS $comment}

                        <li id="comment_{$comment.id}" class="clearfix {cycle values="odd,even"}{if ($comment.status == 'pending') OR ($comment.status == 'confirm')} pending{/if}{if $comment.is_owner} owner{/if}">
                            <div class="form_check">
                                <input id="multidelete_comment{$comment.id}" class="multicheck" type="checkbox" name="serendipity[delete][{$comment.id}]" value="{$comment.entry_id}" data-multixid="comment_{$comment.id}">
                                <label for="serendipity_multidelete_comment_{$comment.id}" class="visuallyhidden">{$CONST.TOGGLE_SELECT}</label>
                            </div>

                            <h4 id="c{$comment.id}" class="{$comment.type|lower}"><span class="text-normal" title="{$comment.author|escape}">{$comment.author|escape|truncate:15:"&hellip;":true}</span> <span class="text-normal">{$CONST.IN}</span> <span class="comment-type-title" title="{($comment.type == 'NORMAL') ? $CONST.COMMENT : (($comment.type == 'TRACKBACK') ? $CONST.TRACKBACK : $CONST.PINGBACK )}">#{$comment.id}</span> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-pin-angle-fill" viewBox="0 0 16 16"><title id="title">{$CONST.IN_REPLY_TO}</title><path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/></svg>
                                <a class="{if $comment.status == 'approved'}hide-{/if}linkout" href="{$comment.entry_url}" title="{$comment.title|escape}">{$comment.title|escape|truncate:36:"&hellip;":true}</a>
                                <span class="text-normal">{$CONST.ON} {$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT|truncate:10:''}</span> <span class="icon-clock" title="{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}" aria-hidden="true"></span>
                                <button class="toggle_info button_link" type="button" data-href="#comment_data_{$comment.id}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
                            </h4>
                        {if $comment.status == 'pending'}

                            <span class="comment_status comment_status_{$comment.status}">{$CONST.COMMENTS_FILTER_NEED_APPROVAL}</span>
                        {/if}
                        {if $comment.status == 'confirm'}

                            <span class="comment_status comment_status_{$comment.status}">{$CONST.COMMENTS_FILTER_NEED_CONFIRM}</span>
                        {/if}
                        {if $comment.status == 'hidden'}

                            <span class="comment_status comment_status_{$comment.status}">{$CONST.COMMENTS_FILTER_NEED_APPROVAL} <span class="icon-right-open" aria-hidden="true"></span> {$CONST.HIDDEN}</span>
                        {/if}

                            <div id="comment_data_{$comment.id}" class="clearfix additional_info">
                                <dl class="comment_data{if $comment.stype == 'P'} ping{/if} clearfix">
                                    <dt>{$CONST.AUTHOR}:</dt>
                                    <dd>{$comment.author|escape|truncate:72:"&hellip;"} {$comment.action_author}</dd>
                                    <dt>{$CONST.EMAIL}:</dt>
                                    <dd>{if empty($comment.email)}N/A{else}<a href="mailto:{$comment.email|escape}" title="{$comment.email|escape}">{$comment.email|escape|truncate:72:"&hellip;"}</a>{if $comment.subscribed == 'true'} <i>({$CONST.ACTIVE_COMMENT_SUBSCRIPTION})</i>{/if}{/if} {$comment.action_email|default:''}</dd>
                                    <dt>IP:</dt>
                                    <dd>{if empty($comment.ip)}N/A{else}{$comment.ip|escape}{/if} {$comment.action_ip|default:''}</dd>
                                    <dt>URL:</dt>
                                    <dd>{if empty($comment.url)}N/A{else}<a href="{$comment.url|escape}" title="{$comment.url|escape}">{$comment.url|escape|truncate:72:"&hellip;"}</a> {/if}{if isset($comment.action_url)} {$comment.action_url|default:''}{/if}</dd>
                                    <dt>{$CONST.REFERER}:</dt>
                                    <dd>{if empty($comment.referer)}N/A{else}<a href="{$comment.referer|escape}" title="{$comment.referer|escape}">{$comment.referer|escape|truncate:72:"&hellip;"}</a>{/if} {$comment.action_referer|default:''}</dd>
                                </dl>
                            </div>

                            <div id="c{$comment.id}_summary" class="comment_summary{if $comment.stype == 'P'} ping{/if}">{$comment.summary}{if empty($comment.summary) AND $comment.type == 'PINGBACK'}<u>PING by:</u>: {$comment.url}{/if}</div>

                            <div id="c{$comment.id}_full" class="clearfix comment_full additional_info">
                                {$comment.fullBody}
                            </div>

                            <ul class="plainList clearfix {$comment.type|lower} actions">
                                <li><a class="button_link" href="{$comment.entrylink}" title="{$CONST.VIEW}"><span class="icon-search" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.VIEW}</span></a></li>
{if $comment.type == 'NORMAL'}
                                <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;{$urltoken}" title="{$CONST.EDIT}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
{if NOT in_array($comment.status, ['hidden', 'pending'])}
                                <li><a class="button_link comments_reply" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=reply&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;{$urltoken}" title="{$CONST.REPLY}"><span class="icon-chat" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.REPLY}</span></a></li>
{/if}
{/if}
{if ($comment.status == 'pending') OR ($comment.status == 'confirm')}
                                <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=hide&amp;serendipity[id]={$comment.id}&amp;{$urltoken}" title="{$CONST.PLUGIN_INACTIVE}"><span class="icon-eye-off" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.PLUGIN_INACTIVE}</span></a></li>
                                <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=approve&amp;serendipity[id]={$comment.id}&amp;{$urltoken}" title="{$CONST.APPROVE}"><span class="icon-toggle-on" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.APPROVE}</span></a></li>
{/if}
{if $comment.status == 'hidden'}
                                <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=public&amp;serendipity[id]={$comment.id}&amp;{$urltoken}" title="{$CONST.PLUGIN_ACTIVE}"><span class="icon-eye" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.PLUGIN_ACTIVE}</span></a></li>
{/if}
{if ($comment.status == 'approved')}
                                <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=pending&amp;serendipity[id]={$comment.id}&amp;{$urltoken}" title="{$CONST.SET_TO_MODERATED}"><span class="icon-toggle-off" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SET_TO_MODERATED}</span></a></li>
{/if}
                                <li><a class="button_link comments_delete" href="?serendipity[action]=admin&amp;serendipity[adminModule]=comments&amp;serendipity[adminAction]=delete&amp;serendipity[id]={$comment.id}&amp;serendipity[entry_id]={$comment.entry_id}&amp;{$urltoken}" data-delmsg='{($CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author)|escape}' title="{$CONST.DELETE}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a></li>
{if $comment.excerpt}
                                <li><button class="button_link toggle_comment_full" type="button" data-href="#c{$comment.id}_full" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TOGGLE_OPTION}</span></button></li>
{/if}
                            </ul>
                            {if NOT empty($comment.action_more)}{$comment.action_more}{/if}

                            <div class="comment_type {$comment.type|lower}" title="{($comment.type == 'NORMAL') ? $CONST.COMMENT : (($comment.type == 'TRACKBACK') ? $CONST.TRACKBACK : 'Pingback' )}"><span class="stype">{$comment.stype}</span></div>

                        </li>
                    {/foreach}

                    </ul>
                {/if}
                {if ($page != 1 AND $page <= $pages) OR $page != $pages}

                    <nav class="pagination">
                        <h3>{$CONST.PAGE_BROWSE_COMMENTS|sprintf:$page:$pages:$totalComments}</h3>

                        <ul class="clearfix">{* set last before next, since float fixes the order *}
                            <li class="first">{if $page > 1}<a class="button_link" href="{$linkFirst}" title="{$CONST.FIRST_PAGE}"><span class="visuallyhidden">{$CONST.FIRST_PAGE} </span><span class="icon-to-start" aria-hidden="true"></span></a>{/if}</li>
                            <li class="prev">{if ($page != 1 AND $page <= $pages)}<a class="button_link" href="{$linkPrevious}" title="{$CONST.PREVIOUS}"><span class="icon-left-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIOUS}</span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                            <li class="last">{if $page < $pages}<a class="button_link" href="{$linkLast}" title="{$CONST.LAST_PAGE}"><span class="visuallyhidden">{$CONST.LAST_PAGE} </span><span class="icon-to-end" aria-hidden="true"></span></a>{/if}</li>
                            <li class="next">{if $page != $pages}<a class="button_link" href="{$linkNext}" title="{$CONST.NEXT}"><span class="visuallyhidden">{$CONST.NEXT} </span><span class="icon-right-dir" aria-hidden="true"></span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                        </ul>
                    </nav>
                {/if}

                </div>

                <div class="form_buttons">
                    <input class="invert_selection" name="toggle" type="button" value="{$CONST.INVERT_SELECTIONS}">
                    <input class="state_cancel comments_multidelete" name="toggle" type="submit" value="{$CONST.DELETE}" data-delmsg="{$CONST.COMMENTS_DELETE_CONFIRM}">
                    <input name="serendipity[togglemoderate]" type="submit" value="{$CONST.APPROVE}">
                </div>
            </form>

            <script>
                $(document).ready(function() {
                    $('#filter_comments').find('.reset_comment_filters').addClass('reset_filter');
                    $('.reset_filter').click(function() {
                        $('#filter_author').attr('value', '');
                        $('#filter_email').attr('value', '');
                        $('#filter_url').attr('value', '');
                        $('#filter_ip').attr('value', '');
                        $('#filter_body').attr('value', '');
                        $('#filter_referer').attr('value', '');
                        $('#filter_perpage option:selected').prop('selected', false);
                        $('#filter_show option:selected').prop('selected', false);
                        $('#filter_type option:selected').prop('selected', false);
                    });
                    if (STYX_DARKMODE === true && {$wysiwyg_comment} === true) {
                        $('.comment_full').find('code[class^="language-"]').parent('pre').attr('title', 'See this snippets codehighlight color either in Rich Text comment edit form or in your frontend');
                    }
                });
            </script>
    {/if}
{/if}
