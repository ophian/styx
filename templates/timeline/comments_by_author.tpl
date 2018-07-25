{foreach $comments_by_authors AS $entry_comments}
<article class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">{$entry_comments.tpl_comments}</div>
</article>
{/foreach}
{*
{if NOT empty($footer_info) OR $footer_prev_page OR $footer_next_page}
    <div class="serendipity_pageSummary">
    {if NOT empty($footer_info)}
        <p class="summary serendipity_center">{$footer_info}</p>
    {/if}
        <nav class="pagination">
            {if $footer_prev_page}<a class="btn btn-md btn-default btn-theme" href="{$footer_prev_page}"><i class="fa fa-arrow-left" aria-hidden="true"></i><span class="sr-only">{$CONST.PREVIOUS_PAGE}</span></a>{/if}
            {if $footer_next_page}<a class="btn btn-md btn-default btn-theme" href="{$footer_next_page}"><i class="fa fa-arrow-right" aria-hidden="true"></i><span class="sr-only">{$CONST.NEXT_PAGE}</span></a>{/if}
        </nav>
{/if}
*}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
