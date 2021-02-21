{foreach $comments AS $comment}
<section id="c{$comment.id|default:0}" class="serendipity_comment {cycle values="odd,even"}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} serendipity_comment_author_self{/if} commentlevel-{$comment.depth}">
    <h4>{if isset($comment.avatar)}{$comment.avatar} {/if}{if $comment.url}{if in_array($comment.type, ['TRACKBACK', 'PINGBACK'])}<span class="tp-url" title="{$comment.ctitle} URL">{/if}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {else}{assign "ex" true}{/if}{if $comment.url}</a>{if in_array($comment.type, ['TRACKBACK', 'PINGBACK'])}</span>{/if}{/if}{if isset($ex) AND $ex === true AND $comment.type == 'NORMAL'}, {/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>{if isset($comment.meta)} | <time>{$comment.timestamp|formatTime:'%H:%M'}</time>{/if}</h4>
{if $comment.body != ''}

    <div class="comment_content">
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
    </div>
{/if}

</section>
{/foreach}
