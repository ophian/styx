{foreach $comments_by_authors AS $entry_comments}
<article class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">{$entry_comments.tpl_comments}</div>
</article>
{/foreach}
{if NOT empty($footer_info) OR $footer_prev_page OR $footer_next_page}
    <nav class="pager comments_by_author_pagination" role="navigation">
    {if NOT empty($footer_info)}
        <p class="summary serendipity_center">{$footer_info}</p>
    {/if}
        <ul class="clearfix">
            <li class="previous">{if $footer_prev_page}<a class="button button-primary" href="{$footer_prev_page}">{/if}{if $footer_prev_page}&larr; {$CONST.PREVIOUS_PAGE}{else}&nbsp;{/if}{if $footer_prev_page}</a>{/if}</li>
            <li class="next">{if $footer_next_page}<a class="button button-primary" href="{$footer_next_page}">{/if}{if $footer_next_page}{$CONST.NEXT_PAGE} &rarr;{else}&nbsp;{/if}{if $footer_next_page}</a>{/if}</li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
