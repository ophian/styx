{foreach $comments AS $comment}
<article id="c{$comment.id|default:0}" class="serendipity_comment">
    <header class="clearfix">
        <h4>{$CONST.IN_REPLY_TO} {$CONST.COMMENT} &laquo; {if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if}, {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}</time> &raquo;</h4>
    </header>

    <div class="serendipity_commentBody clearfix content">
        {if isset($comment.avatar)}{$comment.avatar}{/if}
        {$comment.body}
    </div>
</article>
{foreachelse}
<p class="nocomments">{$CONST.NO_COMMENTS}</p>
{/foreach}
