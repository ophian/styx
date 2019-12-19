{foreach $comments AS $comment}
    <article id="c{$comment.id|default:0}" class="comment{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} {cycle values="odd,even"}{if $comment.depth > 8} commentlevel-9{else} commentlevel-{$comment.depth}{/if}">
        <header class="clearfix">
            <h4>{if $comment.url}<a href="{$comment.url|escape:'htmlall'}">{/if}{$comment.author|default:$CONST.ANONYMOUS}{if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND isset($entry) AND $entry.email == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}{if $comment.url}</a>{/if}{if isset($comment.spice_twitter_name) AND $comment.spice_twitter_name AND NOT $comment.spice_twitter_followme} (<a href="{$comment.spice_twitter_url}"{if $comment.spice_twitter_nofollow} rel="nofollow"{/if}>@{$comment.spice_twitter_name}</a>){/if} {$CONST.ON} <time datetime="{$comment.timestamp|serendipity_html5time}">{$comment.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>{if isset($comment.meta)} | <time>{$comment.timestamp|formatTime:'%H:%M'}</time>{/if}:</h4>
        {if isset($comment.spice_twitter_name) AND $comment.spice_twitter_name AND $comment.spice_twitter_followme}
            <div class="twitter_follow">
                {$comment.spice_twitter_followme}
            </div>
        {/if}
        </header>

        <div class="clearfix">
            {$comment.avatar|default:''}
        {if $comment.body == 'COMMENT_DELETED'}
            <p class="msg-warning"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.COMMENT_IS_DELETED}</p>
        {else}
            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
        {/if}
        </div>

        <footer>
        {if isset($comment.spice_article_name) AND $comment.spice_article_name}
            <p>{$comment.spice_article_prefix}: <a{if $comment.spice_article_nofollow} rel="nofollow"{/if} href="{$comment.spice_article_url}">{$comment.spice_article_name}</a></p>
        {/if}
            <ul class="{$comment.meta|default:''}meta{if isset($comment.type) AND $comment.type == 'TRACKBACK'} tb-meta{/if}">
        {if empty($comment.id) AND isset($smarty.post.serendipity.preview)}
                <li><strong>{$CONST.PREVIEW|upper}</strong></li>
        {else if NOT isset($comment.meta)}
            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}
                <li><strong>TRACKBACK</strong></li>
            {/if}
                <li><time>{$comment.timestamp|formatTime:'%H:%M'}</time></li>
                <li><a class="comment_source_trace" href="#c{$comment.id|default:0}" title="{$CONST.NEXT_PLINK_TITLE}">{$CONST.NEXT_PLINK_TEXT}</a></li>
            {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}
                <li><a class="comment_source_ownerlink" href="{$comment.link_delete}" title="{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}">{$CONST.DELETE}</a></li>
            {/if}
            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}
                <li>{$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span></li>
            {else}
        {if NOT empty($template_option.refcomments)}
            {if $comment.parent_id != '0'}
                <li><a class="reply_origin" href="#c{$comment.parent_id}" title="{$CONST.NEXT_REPLYORIGIN}: {$CONST.COMMENT} #c{$comment.parent_id}">{$CONST.NEXT_REPLYORIGIN}</a></li>
            {/if}
        {/if}
            {if isset($comment.id) AND NOT empty($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}
                <li><a id="serendipity_reply_{$comment.id}" class="comment_reply" href="#serendipity_CommentForm">{$CONST.REPLY}</a>
                <div id="serendipity_replyform_{$comment.id}" class="visuallyhidden"></div></li>
            {/if}
            {/if}
        {/if}
            </ul>
        </footer>
    </article>
{foreachelse}
    <p class="msg-notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_COMMENTS}</p>
{/foreach}
