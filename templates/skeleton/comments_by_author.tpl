<h2 class="visuallyhidden">{$CONST.COMMENTS}</h2>
{foreach $comments_by_authors AS $entry_comments}
<article class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">{$entry_comments.tpl_comments}</div>
</article>
{/foreach}
{if !empty($footer_info) OR !empty($footer_prev_page) OR !empty($footer_next_page)}
    <nav class="pager u-cf comments_by_author_pagination" role="navigation">
    {if !empty($footer_info)}
        <p class="info">{$footer_info}</p>
    {/if}
        <ul class="clearfix">
            <li class="pager_prev u-pull-left">{if !empty($footer_prev_page)}<a class="button button-primary" href="{$footer_prev_page}">{/if}{if !empty($footer_prev_page)}&larr; {$CONST.PREVIOUS_PAGE}{else}&nbsp;{/if}{if !empty($footer_prev_page)}</a>{/if}</li>
            <li class="pager_next u-pull-right">{if !empty($footer_next_page)}<a class="button button-primary" href="{$footer_next_page}">{/if}{if !empty($footer_next_page)}{$CONST.NEXT_PAGE} &rarr;{else}&nbsp;{/if}{if !empty($footer_next_page)}</a>{/if}</li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
