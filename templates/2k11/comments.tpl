{foreach $comments AS $comment}
<article id="c{$comment.id}" class="serendipity_comment{if ( ($entry.author == $comment.author) AND ($entry.email == $commentform_entry.email) ) OR ( ($comment.entry_author_realname == $comment.author) AND ($comment.entry_author_email == $comment.clear_email) )} serendipity_comment_author_self{/if} {cycle values="odd,even"} {if $comment.depth > 8}commentlevel-9{else}commentlevel-{$comment.depth}{/if}">
    <header class="clearfix">
        <h4{if $comment.spice_twitter_name AND $comment.spice_twitter_followme} class="short-heading"{/if}>{if $comment.url}<a href="{$comment.url}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if ( ($entry.author == $comment.author) AND ($entry.email == $commentform_entry.email) ) OR ( ($comment.entry_author_realname == $comment.author) AND ($comment.entry_author_email == $comment.clear_email) )} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if}{if $comment.spice_twitter_name AND NOT $comment.spice_twitter_followme} (<a href="{$comment.spice_twitter_url}"{if $comment.spice_twitter_nofollow} rel="nofollow"{/if}>@{$comment.spice_twitter_name}</a>){/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:$template_option.date_format}</time>:</h4>
    {if $comment.spice_twitter_name AND $comment.spice_twitter_followme}
        <div class="twitter_follow"><a href="{$comment.spice_twitter_url}"{if $comment.spice_twitter_nofollow} rel="nofollow"{/if}><span class="visuallyhidden">@{$comment.spice_twitter_name}</span></a>
        {if $comment.spice_twitter_followme}{$comment.spice_twitter_followme}{/if}
        </div>
    {/if}
    </header>

    <div class="serendipity_commentBody clearfix content">
    {if $comment.avatar}{$comment.avatar}{/if}
    {if $comment.body == 'COMMENT_DELETED'}
        {$CONST.COMMENT_IS_DELETED}
    {else}
        {if $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
    {/if}
    </div>

    <footer>
    {if empty($comment.id) AND isset($smarty.post.serendipity.preview)}
        <strong>{$CONST.PREVIEW|upper}</strong>
    {/if}
    {if $comment.type == 'TRACKBACK'}
        <strong>TRACKBACK</strong>
    {/if}
        <time>{$comment.timestamp|formatTime:'%H:%M'}</time>
        | <a class="comment_source_trace" href="{$comment.url|escape:'htmlall'}#c{$comment.id}" title="{$CONST.TWOK11_PLINK_TITLE}">{$CONST.TWOK11_PLINK_TEXT}</a>
    {if $entry.is_entry_owner}
        | <a class="comment_source_ownerlink" href="{$comment.link_delete}" title="{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}">{$CONST.DELETE}</a>
    {/if}
    {if $comment.type == 'TRACKBACK'}
        {$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span>
    {else}
{if $template_option.refcomments == true}
    {if $comment.parent_id != '0'}
        | <a class="reply_origin" href="#c{$comment.parent_id}" title="{$CONST.TWOK11_REPLYORIGIN}: {$CONST.COMMENT} #c{$comment.parent_id}">{$CONST.TWOK11_REPLYORIGIN}</a>
    {/if}
{/if}
        | <a class="comment_reply" href="#serendipity_CommentForm" id="serendipity_reply_{$comment.id}"{if $comment_onchange != ''} onclick="{$comment_onchange}"{/if}>{$CONST.REPLY}</a>
        <div id="serendipity_replyform_{$comment.id}"></div>
    {/if}
    </footer>
</article>
{foreachelse}
<p class="nocomments">{$CONST.NO_COMMENTS}</p>
{/foreach}
