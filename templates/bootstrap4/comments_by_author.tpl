<h2 class="visuallyhidden">{$CONST.COMMENTS}</h2>
{foreach $comments_by_authors AS $entry_comments}
<article class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">{$entry_comments.tpl_comments}</div>
</article>
{/foreach}
{if !empty($footer_info) OR !empty($footer_prev_page) OR !empty($footer_next_page)}
    <nav class="comments_by_author_pagination" aria-label="{$footer_info}" title="{$footer_info}">
        <ul class="pagination justify-content-between">
            <li class="page-item{if empty($footer_prev_page)} disabled{/if}">
            {if !empty($footer_prev_page)}
                <a class="page-link" href="{$footer_prev_page}">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">{$CONST.PREVIOUS_PAGE}</span>
                </a>
            {/if}
            </li>
            <li class="page-item{if empty($footer_next_page)} disabled{/if}">
            {if !empty($footer_next_page)}
                <a class="page-link" href="{$footer_next_page}">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">{$CONST.NEXT_PAGE}</span>
                </a>
            {/if}
            </li>
    </nav>
{/if}
{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
