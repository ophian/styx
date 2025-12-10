{foreach $comments AS $comment}
<article id="c{$comment.id|default:0}" class="serendipity_comment reply">
    <header class="clearfix">
        <h4>{$CONST.IN_REPLY_TO} {$CONST.COMMENT} &laquo; {if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if}, {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}</time> &raquo;</h4>
    </header>

    <div class="serendipity_commentBody clearfix content">
        {if isset($comment.avatar)}{$comment.avatar}{/if}
        {$comment.body}
    </div>
{if $comment.subscribed === 'true'}
    <footer>
        <div class="msg_notice serendipity_subscription_on"><em>{$CONST.ACTIVE_COMMENT_SUBSCRIPTION}</em></div>
    </footer>
{/if}
</article>
{foreachelse}
<p class="nocomments">{$CONST.NO_COMMENTS}</p>
{/foreach}
