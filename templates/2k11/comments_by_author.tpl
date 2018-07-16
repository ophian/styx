<h2 class="visuallyhidden">{$CONST.COMMENTS}</h2>
{foreach $comments_by_authors AS $entry_comments}
<article class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">{$entry_comments.tpl_comments}</div>
</article>
{/foreach}
{if NOT empty($footer_info) OR NOT empty($footer_prev_page) OR NOT empty($footer_next_page)}
    <nav class="serendipity_pagination block_level comments_by_author_pagination">
        <ul class="clearfix">
            {if NOT empty($footer_info)}
            <li class="info"><span>{$footer_info}</span></li>
            {/if}
            <li class="prev">{if NOT empty($footer_prev_page)}<a href="{$footer_prev_page}">{/if}{if NOT empty($footer_prev_page)}&larr; {$CONST.PREVIOUS_PAGE}{else}&nbsp;{/if}{if NOT empty($footer_prev_page)}</a>{/if}</li>
            <li class="next">{if NOT empty($footer_next_page)}<a href="{$footer_next_page}">{/if}{if NOT empty($footer_next_page)}{$CONST.NEXT_PAGE} &rarr;{else}&nbsp;{/if}{if NOT empty($footer_next_page)}</a>{/if}</li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
