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
            <h2 title="+ {$CONST.TRACKBACKS} / {$CONST.PINGBACKS}">{$CONST.COMMENTS}</h2>

            <form action="" method="GET">
                {$formtoken}
                <input name="serendipity[adminModule]" type="hidden" value="comments">
                <input name="serendipity[page]" type="hidden" value="{$page}">

                <ul class="filters_toolbar plainList">
                    <li><a class="button_link" href="#filter_comments" title="{$CONST.FILTERS}"><span class="icon-filter" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FILTERS}</span></a></li>
                    <li><div class="backend_comments">{$backend_comments_top|default:''}</div></li>
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
                                <option value="pending"{if $get.filter.show == 'pending'} selected{/if}>{$CONST.COMMENTS_FILTER_NEED_APPROVAL}</option>
                                <option value="confirm"{if $get.filter.show == 'confirm'} selected{/if}>{$CONST.COMMENTS_FILTER_NEED_CONFIRM}</option>
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

                            <h4 id="c{$comment.id}" class="{$comment.type|lower}"><span class="text-normal" title="{$comment.author|escape}">{$comment.author|escape|truncate:15:"&hellip;":true}</span> <span class="text-normal">{$CONST.IN}</span> <span class="comment-type-title" title="{($comment.type == 'NORMAL') ? $CONST.COMMENT : (($comment.type == 'TRACKBACK') ? $CONST.TRACKBACK : $CONST.PINGBACK )}">#{$comment.id}</span> <span class="ucc-pinned-to" title="{$CONST.IN_REPLY_TO}"></span>
                                <a href="{$comment.entry_url}" title="{$comment.title|escape}">{$comment.title|escape|truncate:36:"&hellip;":true}</a>
                                <span class="text-normal">{$CONST.ON} {$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT|truncate:10:''}</span> <span class="icon-clock" title="{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}" aria-hidden="true"></span>
                                <button class="toggle_info button_link" type="button" data-href="#comment_data_{$comment.id}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> More</span></button>
                            </h4>
                        {if ($comment.status == 'pending') OR ($comment.status == 'confirm')}
                            <span class="comment_status">{$CONST.COMMENTS_FILTER_NEED_APPROVAL}</span>
                        {/if}
                        {if $comment.status == 'hidden'}
                            <span class="comment_status">{$CONST.COMMENTS_FILTER_NEED_APPROVAL} <span class="icon-right-open" aria-hidden="true"></span> {$CONST.HIDDEN}</span>
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
                                    <dd>{if empty($comment.url)}N/A{else}<a href="{$comment.url|escape}" title="{$comment.url|escape}">{$comment.url|escape|truncate:72:"&hellip;"}</a> {/if} {$comment.action_url|default:''}</dd>
                                    <dt>{$CONST.REFERER}:</dt>
                                    <dd>{if empty($comment.referer)}N/A{else}<a href="{$comment.referer|escape}" title="{$comment.referer|escape}">{$comment.referer|escape|truncate:72:"&hellip;"}</a>{/if} {$comment.action_referer|default:''}</dd>
                                </dl>
                            </div>

                            <div id="c{$comment.id}_summary" class="comment_summary{if $comment.stype == 'P'} ping{/if}">{$comment.summary}</div>

                            <div id="c{$comment.id}_full" class="clearfix comment_full additional_info">{$comment.fullBody}</div>

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
                        $('#filter_perpage option:selected').removeAttr('selected');
                        $('#filter_show option:selected').removeAttr('selected');
                        $('#filter_type option:selected').removeAttr('selected');
                    });
                });
            </script>
    {/if}
{/if}
