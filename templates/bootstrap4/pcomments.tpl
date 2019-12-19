<ol class="plainList">
{foreach $comments AS $comment}
    <li id="c{$comment.id|default:0}" class="comment mb-4{if isset($comment.entry_author_realname) AND $comment.entry_author_realname == $comment.author AND $comment.entry_author_email == $comment.clear_email} serendipity_comment_author_self{/if} commentlevel_{$comment.depth}">
        <ul class="comment_info plainList">
            <li class="d-inline-block"><svg class="icon-user" role="img" viewbox="0 0 1792 1792" width="1792" height="1792" aria-labelledby="title"><title id="title">{$CONST.POSTED_BY}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#user"></use></svg>{if isset($comment.entry_author_realname) AND $comment.entry_author_realname == $comment.author AND $comment.entry_author_email == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if $comment.url}</a>{/if}</li>
            <li class="d-inline-block"><svg class="icon-calendar" role="img" viewbox="0 0 1792 1792" width="1792" height="1792" aria-labelledby="title"><title id="title">{$CONST.ON}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#calendar"></use></svg><time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time></li>
        </ul>
        <div class="comment_content clearfix">
            {$comment.body}
        </div>
    </li>
{/foreach}
</ol>