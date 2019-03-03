{serendipity_hookPlugin hook="entries_header"}
<div class="serendipity_date serendipity_date_summary">{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B, %Y"}</div>

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
<div class="serendipity_entryFooter">
    {serendipity_hookPlugin hook="entries_footer"}
</div>
