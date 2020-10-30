<ol class="plainList">
{foreach $comments AS $comment}
    <li id="c{$comment.id|default:0}" class="comment mb-4{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} {cycle values="odd,even"} commentlevel_{if $comment.depth > 19}20{else}{$comment.depth}{/if}{if !isset($comment.id)} comment_preview{/if}">
        <ul class="comment_info plainList">
            <li class="d-inline-block{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} pc-owner{/if}">
                {if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email}
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-person" role="img" fill="#007bff" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">{$CONST.POSTED_BY} Post author</title>
                  <path fill-rule="evenodd" d="M12 1H4a1 1 0 0 0-1 1v10.755S4 11 8 11s5 1.755 5 1.755V2a1 1 0 0 0-1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
                  <path fill-rule="evenodd" d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
                {else}
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-file-person" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">{$CONST.POSTED_BY}</title>
                  <path fill-rule="evenodd" d="M12 1H4a1 1 0 0 0-1 1v10.755S4 11 8 11s5 1.755 5 1.755V2a1 1 0 0 0-1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4z"/>
                  <path fill-rule="evenodd" d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                </svg>
                {/if}
                {if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if}
            </li>
            <li class="d-inline-block">
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar3" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">{$CONST.ON}</title>
                  <path fill-rule="evenodd" d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                  <path fill-rule="evenodd" d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                </svg>
                <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time> {$CONST.AT} <time>{$comment.timestamp|formatTime:'%H:%M'}</time>
            </li>
        </ul>
        <div class="comment_content clearfix">
        {if $comment.body == 'COMMENT_DELETED'}
            <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.COMMENT_IS_DELETED}</p>
        {else}
            {$comment.body}
        {/if}
        </div>
        {if NOT empty($entry.allow_comments)}
        <ul class="plainList meta{if isset($comment.type) AND $comment.type == 'TRACKBACK'} tb-meta{/if}">
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}

            <li><strong>TRACKBACK</strong></li>
        {/if}

            <li><a class="btn btn-outline-secondary btn-sm comment_source_trace" href="#c{$comment.id|default:0}" title="{$CONST.BS_PLINK_TITLE}">{$CONST.BS_PLINK_TEXT}</a></li>
        {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}

            <li><a class="btn btn-outline-secondary btn-admin btn-sm comment_source_ownerlink" href="{$comment.link_delete}" title="{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}">{$CONST.DELETE}</a></li>
        {/if}
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}

            <li>{$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span></li>
        {else}
        {if $comment.parent_id != 0}

            <li><a class="btn btn-outline-secondary btn-sm reply_origin" href="#c{$comment.parent_id}" title="{$CONST.BS_REPLYORIGIN}: {$CONST.COMMENT} #c{$comment.parent_id}">{$CONST.BS_REPLYORIGIN}</a></li>
        {/if}
        {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}

            <li><a id="serendipity_reply_{$comment.id}" class="btn btn-outline-secondary btn-sm comment_reply" href="#serendipity_CommentForm" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}'; {$comment_onchange|default:''}">{$CONST.REPLY}</a>
            <div id="serendipity_replyform_{$comment.id}" class="sr-only"></div></li>
        {/if}
        {/if}

            <li>{$comment.preview_editstatus|default:''}</li>
        </ul>
        {/if}

    </li>
{foreachelse}
    <li><p class="alert alert-secondary" role="alert">{$CONST.NO_COMMENTS}</p></li>
{/foreach}
</ol>
