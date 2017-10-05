{* DEV backend comments preview file - anything more to change or to simplify here, since used in backend only? *}
{foreach $comments AS $comment}
<article id="c{$comment.id}" class="serendipity_comment{if ( ($entry.author == $comment.author) AND ($entry.email == $commentform_entry.email) ) OR ( ($comment.entry_author_realname == $comment.author) AND ($comment.entry_author_email == $comment.clear_email) )} serendipity_comment_author_self{/if}">
    <header class="clearfix">
        <h4>{if $comment.url}<a href="{$comment.url}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$template_option.date_format}</time>:</h4>
    </header>

    <div class="serendipity_commentBody clearfix content">
    {if $comment.body == 'COMMENT_DELETED'}
        {$CONST.COMMENT_IS_DELETED}
    {else}
        {$comment.body}
    {/if}
    </div>

    <footer>
        <time>{$comment.timestamp|formatTime:'%H:%M'}</time>
    </footer>
</article>
{foreachelse}
<p class="nocomments">{$CONST.NO_COMMENTS}</p>
{/foreach}
