{serendipity_hookPlugin hook="entries_header"}
{counter start=0 assign='entry_count'}
{foreach $entries AS $countme}
    {foreach $countme.entries AS $entry}
        {counter assign='entry_count'}
    {/foreach}
{/foreach}

<article class="archive-summary">
    <h2>{if $category}{$category_info.category_name} - {$entry_count} {/if}{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page} <span class="archive_summary_pageof">{$CONST.PAGE}/{$footer_currentPage}</span>{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>
    <ul class="archives_summary plainList">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <li><h3><a href="{$entry.link}">{$entry.title}</a></h3>
             <p class="post-meta">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></p>
        </li>
        {/foreach}
    {/foreach}
    </ul>
</article>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}
{if NOT empty($footer_info)}

    <p class="summary serendipity_center">{$footer_info}</p>
{/if}

    <nav role="navigation">
        <ul class="pager pagination justify-content-center mx-auto">
            {if $footer_prev_page}<li class="previous page-item"><a class="page-link" href="{$footer_prev_page}"><i class="fa fa-arrow-left" aria-hidden="true"></i> {$CONST.PREVIOUS_PAGE}</a></li>{/if}
            {if $footer_next_page}<li class="next page-item"><a class="page-link" href="{$footer_next_page}">{$CONST.NEXT_PAGE} <i class="fa fa-arrow-right" aria-hidden="true"></i></a></li>{/if}
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}