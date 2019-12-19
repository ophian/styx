<ol class="plainList">
{foreach $comments AS $comment}
            <li id="c{$comment.id|default:0}" class="comment{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} {cycle values="odd,even"}{if $comment.depth > 15} commentlevel-16{else} commentlevel-{$comment.depth}{/if}">
                <h4>{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND isset($entry) AND $entry.email == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>:</h4>

                <div class="comment_content">
                {if $comment.body == 'COMMENT_DELETED'}
                    <p class="msg_important">{$CONST.COMMENT_IS_DELETED}</p>
                {else}
                    {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
                    {$comment.preview_editstatus|default:''}
                {/if}

                </div>
                <ul class="meta{if isset($comment.type) AND $comment.type == 'TRACKBACK'} tb-meta{/if}">
                {if isset($comment.type) AND $comment.type == 'TRACKBACK'}

                    <li><strong>TRACKBACK</strong></li>
                {/if}

                    <li><time>{$comment.timestamp|formatTime:'%H:%M'}</time></li>
                    <li><a class="comment_source_trace" href="#c{$comment.id|default:0}" title="{$CONST.PURE_PLINK_TITLE}">{$CONST.PURE_PLINK_TEXT}</a></li>
                {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}

                    <li><a class="comment_source_ownerlink" href="{$comment.link_delete}" title="{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}">{$CONST.DELETE}</a></li>
                {/if}
                {if isset($comment.type) AND $comment.type == 'TRACKBACK'}

                    <li>{$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span></li>
                {else}
                {if $comment.parent_id != 0}

                    <li><a class="reply_origin" href="#c{$comment.parent_id}" title="{$CONST.PURE_REPLYORIGIN}: {$CONST.COMMENT} #c{$comment.parent_id}">{$CONST.PURE_REPLYORIGIN}</a></li>
                {/if}
                {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}

                    <li><a id="serendipity_reply_{$comment.id}" class="comment_reply" href="#serendipity_CommentForm" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}'; {$comment_onchange|default:''}">{$CONST.REPLY}</a>
                    <div id="serendipity_replyform_{$comment.id}" class="visuallyhidden"></div></li>
                {/if}
                {/if}

                </ul>
           </li>
{foreachelse}

            <li><span class="msg_notice">{$CONST.NO_COMMENTS}</span></li>
{/foreach}

        </ol>