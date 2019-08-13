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

<div class="comments_by_author_pagination top">
{if $footer_prev_page}
    <a href="{$footer_prev_page}">&laquo; {$CONST.PREVIOUS_PAGE}</a>&#160;&#160;
{/if}
{if $footer_info}
    ({$footer_info})
{/if}
{if $footer_next_page}
    <a href="{$footer_next_page}">{$CONST.NEXT_PAGE} &raquo;</a>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
</div>

<div class="comments_by_author">
{foreach $comments_by_authors AS $entry_comments}
    <article id="e{$entry_comments@key}" class="clearfix serendipity_entry byauthor">
        <h4 class="serendipity_title"><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h4>
        {* tpl_comments is the already parsed "comments.tpl" template for each entry, which lacks the $entry array var, since never reached entries.tpl ! *}
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
{if $footer_info}
    ({$footer_info})
{/if}
{if $footer_next_page}
    <a href="{$footer_next_page}">{$CONST.NEXT_PAGE} &raquo;</a>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
</div>

<!-- Sliver/comments-by-author -->
