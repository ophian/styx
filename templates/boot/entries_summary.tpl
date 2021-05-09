{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}</h2>

    <dl>
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <dt><a href="{$entry.link}">{$entry.title}</a></dt>
        <dd>{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></dd>
        {/foreach}
    {/foreach}
    </dl>
</article>
{serendipity_hookPlugin hook="entries_footer"}
