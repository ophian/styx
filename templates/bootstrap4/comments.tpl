<ol class="plainList">
{foreach $comments AS $comment}
    <li id="c{$comment.id|default:0}" class="comment mb-4{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} commentlevel_{if $comment.depth > 8}9{else}{$comment.depth}{/if}{if !isset($comment.id)} comment_preview{/if}">
        <ul class="comment_info plainList">
            <li class="d-inline-block"><svg class="icon-user" role="img" viewbox="0 0 1792 1792" width="1792" height="1792" aria-labelledby="title"><title id="title">{$CONST.POSTED_BY}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#user"></use></svg>{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if}</li>
            <li class="d-inline-block"><svg class="icon-calendar" role="img" viewbox="0 0 1792 1792" width="1792" height="1792" aria-labelledby="title"><title id="title">{$CONST.ON}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#calendar"></use></svg><time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time></li>
        </ul>
        <div class="comment_content clearfix">
        {if $comment.body == 'COMMENT_DELETED'}
            <p class="alert alert-danger" role="alert">{$CONST.COMMENT_IS_DELETED}</p>
        {else}
            {$comment.body}
            {$comment.preview_editstatus|default:''}
        {/if}
        </div>
    {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}
        <a id="serendipity_reply_{$comment.id}" class="comment_reply btn btn-outline-primary btn-sm" href="#serendipity_CommentForm" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}'; {$comment_onchange|default:''}">{$CONST.REPLY}</a>
        <div id="serendipity_replyform_{$comment.id}"></div>
    {/if}
    </li>
{foreachelse}
    <li><p class="alert alert-info" role="alert">{$CONST.NO_COMMENTS}</p></li>
{/foreach}
</ol>