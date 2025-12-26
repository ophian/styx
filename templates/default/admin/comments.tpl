{foreach $comments AS $comment}
<article id="c{$smarty.post.serendipity.entry_id}" class="serendipity_comment">
    <header class="clearfix">
        <h4>{if $comment.url}<a href="{$comment.url|escape:'htmlall'}" target="_blank" title="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_ENTRY}</time>:</h4>
    </header>

    <div class="serendipity_commentBody clearfix content">
        {if isset($comment.avatar)}{$comment.avatar}{/if}
        {$comment.body}
    </div>

    <footer>
        <time>{$comment.timestamp|formatTime:'%Y-%m-%d %H:%M'}</time>
{if empty($comment.status)}
        <div class="comment_status comment_status_pending unfloat"><span class="icon-toggle-on" aria-hidden="true" title="{$CONST.COMMENTS_FILTER_NEED_APPROVAL}"></span><span class="visuallyhidden">{$CONST.COMMENTS_FILTER_NEED_APPROVAL}</span></div>
{/if}
        <div id="serendipity_replyform_{$smarty.post.serendipity.entry_id}" class="comment_preview_editstatus">{$comment.preview_editstatus}</div>
    </footer>
</article>
{/foreach}
