{foreach $comments AS $comment}
    <a id="c{$comment.id|default:0}"></a>
    <div id="serendipity_comment_{$comment.id|default:0}" class="serendipity_comment serendipity_comment_author_{$comment.author|makeFilename}{if isset($entry) AND $entry.author == $comment.author AND $entry.email == $comment.clear_email} serendipity_comment_author_self{/if} {cycle values="comment_oddbox,comment_evenbox"}"{if $comment.depth > 0} style="padding-left: {$comment.depth*20}px"{/if}>
        <div class="serendipity_commentBody">
        {if $comment.body == 'COMMENT_DELETED'}
            {$CONST.COMMENT_IS_DELETED}
        {else}
            {if isset($comment.type) AND $comment.type == 'TRACKBACK'}{$comment.body|strip_tags:false} [&hellip;]{else}{$comment.body}{/if}
        {/if}
        </div>
        <div class="serendipity_comment_source">
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}
            <strong>[TRACKBACK]</strong> {$CONST.TRACKED}:
        {/if}
            <a class="comment_source_trace" href="{$comment.url|escape:'htmlall'}#c{$comment.id|default:0}">#{$comment.trace}</a>
            <span class="comment_source_author">
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}
            <strong>{$CONST.WEBLOG}:</strong>
        {/if}
            {if $comment.email}
                <a href="mailto:{$comment.email}">{$comment.author|default:$CONST.ANONYMOUS}</a>
            {else}
                {$comment.author|default:$CONST.ANONYMOUS}
            {/if}
            {if isset($comment.entryauthor) AND $comment.entryauthor == $comment.author AND isset($entry) AND $entry.email == $comment.clear_email} <span class="pc-owner">Post author</span> {/if}
        {if isset($comment.type) AND $comment.type == 'TRACKBACK'}
            <br />
            {$CONST.IN} {$CONST.TITLE}: <span class="comment_source_ctitle">{$comment.ctitle|truncate:42|wordwrap:15:"\n":true|escape}</span>
        {/if}
            </span>
            {if $comment.url}
                (<a class="comment_source_url" href="{$comment.url}" title="{$comment.url|escape}">{$CONST.HOMEPAGE}</a>)
            {/if}
            {$CONST.ON}
            <span class="comment_source_date">{$comment.timestamp|formatTime:$CONST.DATE_FORMAT_SHORT}</span>

            {if isset($entry) AND NOT empty($entry.is_entry_owner) AND NOT empty($comment.id)}
                (<a class="comment_source_ownerlink" href="{$comment.link_delete}" onclick="return confirm('{$CONST.COMMENT_DELETE_CONFIRM|sprintf:$comment.id:$comment.author}');">{$CONST.DELETE}</a>)
            {/if}
            {if isset($comment.id) AND isset($entry.allow_comments) AND $comment.body != 'COMMENT_DELETED'}
                (<a class="comment_reply" href="#serendipity_CommentForm" id="serendipity_reply_{$comment.id}" onclick="document.getElementById('serendipity_replyTo').value='{$comment.id}'; {$comment_onchange|default:''}">{$CONST.REPLY}</a>)
                <div id="serendipity_replyform_{$comment.id}"></div>
            {/if}
        </div>
    </div>
{foreachelse}
    <div class="serendipity_center nocomments">{$CONST.NO_COMMENTS}</div>
{/foreach}
