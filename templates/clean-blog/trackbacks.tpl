<ul class="comment-list">
{foreach $trackbacks AS $trackback}
    <li id="comment-c{$trackback.id}" class="comment-list-item">
        <a id="c{$trackback.id}"></a>
        <div id="div-comment-c{$trackback.id}" class="comment_any {cycle values="comment_odd,comment_even"} comment_author_{$trackback.author|default:$CONST.ANONYMOUS} {if $trackback.author == $blogTitle}serendipity_comment_author_self{/if}">
            {$trackback.avatar|default:''}
            <div class="comment-list-item-body">
                <h5 class="comment-author-heading">
                    <span class="comment-author-details">{$trackback.author|default:$CONST.ANONYMOUS}</span>&nbsp;
                    <time class="comment-date" datetime="{$trackback.timestamp|serendipity_html5time}">{if $template_option.comment_time_format =='time'}{$trackback.timestamp|formatTime:'%b %e. %Y'} {$CONST.AT} {$trackback.timestamp|formatTime:'%I:%M %p'}{else}{elapsed_time_words from_time=$trackback.timestamp}{/if}</time>
                </h5>
                <div class="comment-content">
                        <a href="{$trackback.url|strip_tags}" {'blank'|xhtml_target}>{$trackback.title}</a><br />
                        {$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]
                </div>
                <div class="comment-meta">
                    {if NOT empty($entry.is_entry_owner)}
                        <a href="{$serendipityBaseURL}comment.php?serendipity[delete]={$trackback.id}&amp;serendipity[entry]={$trackback.entry_id}&amp;serendipity[type]=trackbacks" title="{$CONST.DELETE}"><button class="btn btn-secondary btn-sm"><i class="fa fa-lg fa-trash-o"></i><span class="sr-only"> {$CONST.DELETE}</span></button></a>
                    {/if}
                </div>
            </div>
        </div>
    </li>
{/foreach}
</ul>           