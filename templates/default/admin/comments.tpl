{foreach $comments AS $comment}
<article id="c{$smarty.post.serendipity.entry_id}" class="serendipity_comment">
    <header class="clearfix">
        <h4>{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_ENTRY}</time>:</h4>
    </header>

    <div class="serendipity_commentBody clearfix content">
        {$comment.avatar|default:''}
        {$comment.body}
    </div>

    <footer>
        <time>{$comment.timestamp|formatTime:'%Y-%m-%d %H:%M'}</time>
        <div id="serendipity_replyform_{$smarty.post.serendipity.entry_id}" class="comment_preview_editstatus">{$comment.preview_editstatus}</div>
    </footer>
</article>
{/foreach}
