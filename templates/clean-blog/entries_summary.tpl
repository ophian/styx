{serendipity_hookPlugin hook="entries_header"}
{counter start=0 assign='entry_count'}
{foreach $entries AS $countme}
    {foreach $countme.entries AS $entry}
        {counter assign='entry_count'}
    {/foreach}
{/foreach}

<article class="archive-summary">
    <h2>{if $category}{$category_info.category_name} - {/if}{$entry_count} {$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B, %Y"}</h2>
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
{serendipity_hookPlugin hook="entries_footer"}