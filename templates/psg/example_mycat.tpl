{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
    <dl>
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}

        <dt><a href="{$entry.link}">{$entry.title}</a></dt>
        <dd>{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></dd>
        {/foreach}
    {/foreach}

    </dl>
{serendipity_hookPlugin hook="entries_footer"}
