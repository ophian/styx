{serendipity_hookPlugin hook="entries_header"}
<div class="serendipity_date">{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B, %Y"}</div>

<div class="serendipity_entry">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
            <div class="archive_summary">
                <h4 class="archive_summary_title">{$entry.id} - <a href="{$entry.link}">{$entry.title|truncate:80:" ..."}</a></h4>
                {$entry.timestamp|formatTime:DATE_FORMAT_ENTRY}. {$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {if NOT empty($entry.categories)} {$CONST.IN} {foreach $entry.categories AS $entry_category}<a href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{/foreach}{/if}</div>
        {/foreach}
    {/foreach}

<div class="serendipity_entries_footer">
    {serendipity_hookPlugin hook="entries_footer"}
</div>
