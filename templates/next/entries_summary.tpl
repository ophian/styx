{serendipity_hookPlugin hook="entries_header"}
<article id="archives" class="clearfix">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <ul class="summary">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <li><a href="{$entry.link}">{$entry.title}</a>
            <span>{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></span>
        </li>
        {/foreach}
    {/foreach}
    </ul>
</article>
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="pagination clearfix">
        {if NOT empty($footer_info)}<h3>{$footer_info}</h3>{/if}
    {if $footer_prev_page OR $footer_next_page}
        <ul>
            <li class="prev-page">{if $footer_prev_page}<a href="{$footer_prev_page}"><span class="icon-angle-circled-left" aria-hidden="true"></span><span class="fallback-text">{$CONST.PREVIOUS_PAGE}</span></a>{else}<span class="no-page"><span class="icon-angle-circled-left" aria-hidden="true"></span><span class="fallback-text">{$CONST.NO_ENTRIES_TO_PRINT}</span></span>{/if}</li>
            <li class="next-page">{if $footer_next_page}<a href="{$footer_next_page}"><span class="icon-angle-circled-right" aria-hidden="true"></span><span class="fallback-text">{$CONST.NEXT_PAGE}</span></a>{else}<span class="no-page"><span class="icon-angle-circled-right" aria-hidden="true"></span><span class="fallback-text">{$CONST.NO_ENTRIES_TO_PRINT}</span></span>{/if}</li>
        </ul>
    {/if}
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
