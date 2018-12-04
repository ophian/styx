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

<div class="comments_by_author">
{foreach $comments_by_authors AS $entry_comments}

    <article id="e{$entry_comments@key}" class="serendipity_entry">
        <h4 class="serendipity_title"><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h4>
        {* tpl_comments is the parsed "comments.tpl" template! *}
        <div class="comments_for_entry">
            {$entry_comments.tpl_comments}
        </div>
    </article>
{/foreach}
</div>

<div class="comments_by_author_pagination bottom">
{if $footer_prev_page}
    <a href="{$footer_prev_page}">&laquo; {$CONST.PREVIOUS_PAGE}</a>&#160;&#160;
{/if}
{if NOT empty($footer_info)}
    ({$footer_info})
{/if}
{if $footer_next_page}
    <a href="{$footer_next_page}">&raquo; {$CONST.NEXT_PAGE}</a>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
</div>
