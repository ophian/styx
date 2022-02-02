{serendipity_hookPlugin hook="entries_header"}
<h3 class="serendipity_date serendipity_date_summary">{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page} <span class="archive_summary_pageof">{$CONST.PAGE}/{$footer_currentPage}</span>{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h3>

<div class="serendipity_entry serendipity_entry_summary">
    <ul class="entries_summary">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
            <li><a href="{$entry.link}">{$entry.title}</a>
                <div class="summary_posted_by">{$CONST.POSTED_BY} <span class="posted_by_author">{$entry.author}</span> {$CONST.ON} <span class="posted_by_date">{$entry.timestamp|formatTime:DATE_FORMAT_ENTRY}</span></div></li>
        {/foreach}
    {/foreach}
    </ul>
</div>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <div class="serendipity_entries_footer">
    {if $footer_prev_page}
        <a href="{$footer_prev_page}">&laquo; {$CONST.PREVIOUS_PAGE}</a>&#160;&#160;
    {/if}

    {if NOT empty($footer_info)}
        ({$footer_info})
    {/if}

    {if $footer_next_page}
        <a href="{$footer_next_page}">&raquo; {$CONST.NEXT_PAGE}</a>
    {/if}

    </div>
{/if}

<div class="serendipity_entryFooter">
    {serendipity_hookPlugin hook="entries_footer"}
</div>
