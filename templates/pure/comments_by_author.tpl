{if $view == 'comments'}
{if $typeview == 'comments'}
    <h2 class="comments_permalink">{$CONST.WEBLOG} {$CONST.COMMENTS}</h2>
{elseif $typeview == 'trackbacks'}
    <h2 class="comments_permalink">{$CONST.WEBLOG} {$CONST.TRACKBACKS}</h2>
{elseif $typeview == 'pingbacks'}
    <h2 class="comments_permalink">{$CONST.WEBLOG} {$CONST.PINGBACKS}</h2>
{elseif $typeview == 'comments_and_trackbacks'}
    <h2 class="comments_permalink">{$CONST.WEBLOG} {$CONST.COMMENTS}/{$CONST.TRACKBACKS}/{$CONST.PINGBACKS}</h2>
{/if}
{/if}
{foreach $comments_by_authors AS $entry_comments}

<article id="e{$entry_comments@key}" class="clearfix post byauthor">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">
        {$entry_comments.tpl_comments}
    </div>
</article>
{/foreach}
{if NOT empty($footer_info) OR $footer_prev_page OR $footer_next_page}

    <nav class="pager comments_by_author_pagination">
        {if NOT empty($footer_info)}<p>{$footer_info}</p>{/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul class="plainList">
            <li class="pager_prev">{if $footer_prev_page}<a href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a>{else}&nbsp;{/if}</li>
            <li class="pager_next">{if $footer_next_page}<a href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a>{else}&nbsp;{/if}</li>
        </ul>
    {/if}
    </nav>
{/if}

{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
