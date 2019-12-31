<ul class="comment-list">
{foreach $comments AS $comment}
    {if $comment@first}{assign var="prevdepth" value=$comment.depth}{/if}
    {if ($comment.depth == $prevdepth) AND NOT $comment@first}
        </li>
    {elseif $comment.depth < $prevdepth}
        {for $i=1 to $prevdepth-$comment.depth}
            </li></ul>
        {/for}
        </li>
    {elseif $comment.depth > $prevdepth}
        <ul class="comment-children">
    {/if}
    <li id="comment-{$comment.id|default:0}" class="comment-list-item">
        <a id="c{$comment.id|default:0}"></a>
        <div id="div-comment-{$comment.id|default:0}" class="comment_any {cycle values="comment_odd,comment_even"} comment_author_{$comment.author|makeFilename}{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if}">
            {$comment.avatar|default:''}
            <div class="comment-list-item-body">
                <h5 class="comment-author-heading">
                    <span class="comment-author-details">
                        {if $comment.url}
                            <a class="comment-author-url" href="{$comment.url|escape:'htmlall'}" title="{$comment.url|escape}" rel="external nofollow">{$comment.author|default:$CONST.ANONYMOUS}</a>
                        {else}
                            {$comment.author|default:$CONST.ANONYMOUS}
                        {/if}
                    {if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND $comment.authoremail == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}
                    </span>&nbsp;
                    <time class="comment-date" datetime="{$comment.timestamp|serendipity_html5time}">{if $template_option.comment_time_format =='time'}{$comment.timestamp|formatTime:'%b %e. %Y'} {$CONST.AT} {$comment.timestamp|formatTime:'%I:%M %p'}{else}{elapsed_time_words from_time=$comment.timestamp}{/if}</time>
                </h5>
                <div class="comment-content">
                        {if $comment.body == 'COMMENT_DELETED'}
                            {$CONST.COMMENT_IS_DELETED}
                        {else}
                            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
                            {$comment.preview_editstatus|default:''}
                        {/if}
                </div>
                <div class="comment-meta">
                    <a class="comment-source-trace btn btn-sm btn-default btn-theme" href="#c{$comment.id|default:0}">#{$comment.trace}</a>
                    {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}
                        <a class="comment-source-ownerlink comment-reply-link btn btn-sm btn-default btn-theme" href="{$comment.link_delete}" onclick="return confirm('{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}');" title="{$CONST.DELETE}"><i class="fas fa-lg fa-trash-alt"></i><span class="sr-only"> {$CONST.DELETE}</span></a>
                    {/if}

                    {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}
                        <a class="comment-reply-link btn btn-sm btn-default btn-theme" href="#serendipity_CommentForm" id="serendipity_reply_{$comment.id}" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}'; {$comment_onchange|default:''}" title="{$CONST.REPLY}"><i class="fas fa-lg fa-reply"></i><span class="sr-only"> {$CONST.REPLY}</span></a>
                        <div id="serendipity_replyform_{$comment.id}"></div>
                    {/if}
                </div>
            </div>
        </div>
    {if $comment@last}
        {if $comment.depth>0}
            {for $i=1 to $comment.depth}
                </li></ul>
            {/for}
        {/if}
        </li>
    {/if}
    {assign var="prevdepth" value=$comment.depth}
{/foreach}
</ul>