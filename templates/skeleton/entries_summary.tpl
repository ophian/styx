{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page} <span class="archive_summary_pageof">{$CONST.PAGE}/{$footer_currentPage}</span>{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <ul class="plainList">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <li><a href="{$entry.link}">{$entry.title}</a>
            <span class="archive_byline">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></span>
        </li>
        {/foreach}
    {/foreach}
    </ul>
</article>
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="pager u-cf" role="navigation">
    {if NOT empty($footer_info)}
        <p>{$footer_info}</p>
    {/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul class="plainList">
        {if $footer_prev_page}
            <li class="pager_prev u-pull-left"><a class="button button-primary" href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a></li>
        {/if}
        {if $footer_next_page}
            <li class="pager_next u-pull-right"><a class="button button-primary" href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a></li>
        {/if}
        </ul>
    {/if}
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
