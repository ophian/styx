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
        <div id="div-comment-{$comment.id|default:0}" class="serendipity_comment {cycle values="odd,even"} comment_author_{$comment.author|makeFilename}{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if}">
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

                    </span>
                    <time class="comment-date" datetime="{$comment.timestamp|serendipity_html5time}">{if isset($template_option.comment_time_format) AND $template_option.comment_time_format == 'time'}{$comment.timestamp|formatTime:'%b %e. %Y'} {$CONST.AT} {$comment.timestamp|formatTime:'%I:%M %p'}{/if}</time>
                </h5>
                <div class="comment-content{if isset($comment.type) AND $comment.type == 'PINGBACK'} ping{/if}">
                    {if $comment.body == 'COMMENT_DELETED'}

                        {$CONST.COMMENT_IS_DELETED}
                    {else}

                        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{if isset($comment.type) AND $comment.type == 'PINGBACK'}[PingBack]{else}{$comment.body}{/if}{/if}
                        {$comment.preview_editstatus|default:''}
                    {/if}

                </div>
                <div class="comment-meta">
                {if $smarty.get.serendipity.action != 'comments'}

                    <a class="comment-source-trace btn btn-sm" href="#c{$comment.id|default:0}">#{$comment.trace}</a>
                {/if}
                {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}

                    <a class="comment-source-ownerlink comment-reply-link btn btn-sm" href="{$comment.link_delete}" onclick="return confirm('{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}');" title="{$CONST.DELETE}"><i class="fa fa-lg fa-trash-o"></i><span class="sr-only"> {$CONST.DELETE}</span></a>
                {/if}
                {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}

                    <a class="comment-reply-link btn btn-sm" href="#serendipity_CommentForm" id="serendipity_reply_{$comment.id}" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}';{if NOT empty($comment_onchange)} {$comment_onchange|default:''}{/if}" title="{$CONST.REPLY}"><i class="fa fa-lg fa-reply"></i><span class="sr-only"> {$CONST.REPLY}</span></a>
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
{foreachelse}

    <li class="serendipity_center nocomments">{$CONST.NO_COMMENTS}</li>
{/foreach}

</ul>
