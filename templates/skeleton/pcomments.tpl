{foreach $comments AS $comment}
    <section id="c{$comment.id|default:0}" class="comment {cycle values="odd,even"}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} serendipity_comment_author_self{/if} commentlevel-{$comment.depth}">
        <h4>{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>:</h4>

        <div class="comment_content u-cf">
            {if isset($comment.avatar)}{$comment.avatar}{/if}
            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
        </div>
    </section>
{/foreach}
