{serendipity_hookPlugin hook="entries_header"}
<article class="archives">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <ul class="archives_summary plainList">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <li><a href="{$entry.link}">{$entry.title}</a>
            <span class="serendipity_byline block_level"><span class="single_user">{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} </span><time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></span>
        </li>
        {/foreach}
    {/foreach}
    </ul>
</article>
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="serendipity_pagination block_level">
        <h2 class="visuallyhidden">{$CONST.TWOK11_PAG_TITLE}</h2>

        <ul class="clearfix">
            {if NOT empty($footer_info)}
            <li class="info"><span>{$footer_info}</span></li>
            {/if}
            <li class="prev">{if $footer_prev_page}<a href="{$footer_prev_page}">{/if}{if $footer_prev_page}&larr; {$CONST.PREVIOUS_PAGE}{else}&nbsp;{/if}{if $footer_prev_page}</a>{/if}</li>
            <li class="next">{if $footer_next_page}<a href="{$footer_next_page}">{/if}{if $footer_next_page}{$CONST.NEXT_PAGE} &rarr;{else}&nbsp;{/if}{if $footer_next_page}</a>{/if}</li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
