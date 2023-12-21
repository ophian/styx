                            <ol class="plainList">
{foreach $comments AS $comment}
                                <li id="c{$comment.id|default:0}" class="comment{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} {cycle values="odd,even"}{if $comment.depth > 15} commentlevel-16{else} commentlevel-{$comment.depth}{/if}{if $comment.depth > 8} commentlevel-maxpart{/if}{if !isset($comment.id)} preview{/if}">
                                    <h4>{if isset($comment.avatar)}{$comment.avatar} {/if}{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>:</h4>

                                    <div class="comment_content">
{if $comment.body == 'COMMENT_DELETED'}
                                        <p class="msg_important">{$CONST.COMMENT_IS_DELETED}</p>
{else}
{if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]
{else}
{* no indent since db data *}
{$comment.body}
{/if}
                                        {$comment.preview_editstatus|default:''}
{/if}
                                    </div>
                                    <ul class="meta{if isset($comment.type) AND $comment.type == 'TRACKBACK'} tb-meta{/if}">
{if isset($comment.type) AND $comment.type == 'TRACKBACK'}
                                        <li><strong>TRACKBACK</strong></li>
{/if}
{if isset($entry)}
{* avoid running these "pure" theme constant when answering in backend *}
                                        <li><time>{$comment.timestamp|formatTime:'%H:%M'}</time></li>
                                        <li><a class="comment_source_trace" href="#c{$comment.id|default:0}" title="{$CONST.PURE_PLINK_TITLE}">{$CONST.PURE_PLINK_TEXT}</a></li>
{/if}
{if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}
                                        <li><a class="comment_source_ownerlink" href="{$comment.link_delete}" title="{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}">{$CONST.DELETE}</a></li>
{/if}
{if isset($comment.type) AND $comment.type == 'TRACKBACK'}
                                        <li>{$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span></li>
{else}
{if $comment.parent_id != 0 AND isset($entry)}
{* avoid running these "pure" theme constant when answering in backend *}
                                        <li><a class="reply_origin" href="#c{$comment.parent_id}" title="{$CONST.PURE_REPLYORIGIN}: {$CONST.COMMENT} #c{$comment.parent_id}">{$CONST.PURE_REPLYORIGIN}</a></li>
{/if}
{if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}
                                        <li><a id="serendipity_reply_{$comment.id}" class="comment_reply" href="#serendipity_CommentForm" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}';{if NOT empty($comment_onchange)} {$comment_onchange|default:''}{/if}">{$CONST.REPLY}</a><div id="serendipity_replyform_{$comment.id}" class="visuallyhidden"></div></li>
{/if}
{if NOT isset($comment.id) AND NOT empty($comment.author) AND NOT empty($comment.body)}
                                        <li class="comment_preview_gotoform">
                                          <button type="button" class="btn btn-secondary">
                                            <a href="#feedback">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-textarea-t" viewBox="0 0 16 16">
                                                <title>Comment Preview: Return to comment form</title>
                                                <path d="M1.5 2.5A1.5 1.5 0 0 1 3 1h10a1.5 1.5 0 0 1 1.5 1.5v3.563a2 2 0 0 1 0 3.874V13.5A1.5 1.5 0 0 1 13 15H3a1.5 1.5 0 0 1-1.5-1.5V9.937a2 2 0 0 1 0-3.874V2.5zm1 3.563a2 2 0 0 1 0 3.874V13.5a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V9.937a2 2 0 0 1 0-3.874V2.5A.5.5 0 0 0 13 2H3a.5.5 0 0 0-.5.5v3.563zM2 7a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm12 0a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/>
                                                <path d="M11.434 4H4.566L4.5 5.994h.386c.21-1.252.612-1.446 2.173-1.495l.343-.011v6.343c0 .537-.116.665-1.049.748V12h3.294v-.421c-.938-.083-1.054-.21-1.054-.748V4.488l.348.01c1.56.05 1.963.244 2.173 1.496h.386L11.434 4z"/>
                                              </svg>
                                            </a>
                                          </button>
                                        </li>
{/if}
{/if}
                                    </ul>
{if NOT $comment@last}
                                </li>

{else}
                                </li>
{/if}
{foreachelse}
                                <li><span class="msg_notice">{$CONST.NO_COMMENTS}</span></li>
{/foreach}
                            </ol>
