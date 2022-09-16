{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page} <span class="archive_summary_pageof">{$CONST.PAGE}/{$footer_currentPage}</span>{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <dl>
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <dt><a href="{$entry.link}">{$entry.title}</a></dt>
        <dd>{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></dd>
        {/foreach}
    {/foreach}
    </dl>
</article>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="pager">
        {if NOT empty($footer_info)}<p>{$footer_info}</p>{/if}
    {if $footer_prev_page OR $footer_next_page}

        <ul class="plainList">
            <li class="pager_prev">{if $footer_prev_page}<a href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a>{else}&nbsp;{/if}</li>
            <li class="pager_next">{if $footer_next_page}<a href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a>{else}&nbsp;{/if}</li>
        </ul>
    {/if}

    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
