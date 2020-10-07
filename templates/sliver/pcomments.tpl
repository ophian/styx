{foreach $comments AS $comment}
<section id="c{$comment.id|default:0}" class="serendipity_comment {cycle values="odd,even"}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} serendipity_comment_author_self{/if} commentlevel-{$comment.depth}">
    <header class="clearfix">
        <h4>{if $comment.url}<a rel="external nofollow" href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>{if isset($comment.meta)} | <time>{$comment.timestamp|formatTime:'%H:%M'}</time>{/if}:</h4>
    </header>

    <div class="comment-content{if isset($comment.type) AND $comment.type == 'PINGBACK'} ping{/if}">
        {$comment.avatar|default:''}
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{if isset($comment.type) AND $comment.type == 'PINGBACK'}[PingBack]{else}{$comment.body}{/if}{/if}
    </div>
</section>
{/foreach}
