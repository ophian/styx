{serendipity_hookPlugin hook="entries_header"}
{counter start=0 assign='entry_count'}
{foreach $entries AS $countme}
    {foreach $countme.entries AS $entry}
        {counter assign='entry_count'}
    {/foreach}
{/foreach}
<article class="archive-summary">
    <h3>{if $category}{$category_info.category_name} - {$entry_count|default:''} {/if}{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h3>
    <div class="archives_summary">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
            <div class="row each-archive-entry">
                <div class="col-md-2 archive-post-thumb">
                    {if NOT empty($entry.properties.timeline_image) AND $entry.properties.timeline_image|is_in_string:'<iframe,<embed,<object'}{* we assume this is a video, just emit the contents of the var *}
                        <div>{$entry.properties.timeline_image}</div>
                    {else}
                        <a href="{$entry.link}" title="{$entry.title}"><img class="img-thumbnail" {if NOT empty($entry.properties.timeline_image)}src="{$entry.properties.timeline_image}"{else}src="{serendipity_getFile file='img/image_unavailable.jpg'}"{/if} alt=""/></a>
                    {/if}
                </div>
                <div class="col-md-10 archive-post-body">
                    <h4><a href="{$entry.link}">{$entry.title}</a></h4>
                    <p class="post-info"><span class="sr-only">{$CONST.POSTED_BY}</span>
                        <span class="sr-only"> {$CONST.ON}</span><span class="entry-timestamp"><i class="far fa-clock" aria-hidden="true"></i><time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></span>
                    </p>
                    {if $entry.body}
                        {$entry.body|strip_tags|truncate:180:" ..."}
                    {else}
                        {$entry.extended|strip_tags|truncate:180:" ..."}
                    {/if}
                </div>
            </div>
            <hr>
        {/foreach}
    {/foreach}
    </div>
</article>
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <div class="serendipity_pageSummary mx-auto">
        {if NOT empty($footer_info)}
            <p class="summary serendipity_center">{$footer_info}</p>
        {/if}

        {if $footer_totalPages > 1}
            <nav class="{if $template_option.display_as_timeline}center-{/if}pagination">
                {assign var="paginationStartPage" value="`$footer_currentPage-3`"}
                {if ($footer_currentPage+3) > $footer_totalPages}
                    {assign var="paginationStartPage" value="`$footer_totalPages-4`"}
                {/if}
                {if $paginationStartPage <= 0}
                    {assign var="paginationStartPage" value="1"}
                {/if}
                {if $footer_prev_page}
                    <a class="btn btn-secondary btn-md btn-theme" title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><i class="fas fa-arrow-left" aria-hidden="true"></i><span class="sr-only">{$CONST.PREVIOUS_PAGE}</span></a>
                {/if}
                {if $paginationStartPage > 1}
                    <a class="btn btn-secondary btn-md btn-theme" href="{'1'|string_format:$footer_pageLink}">1</a>
                {/if}
                {if $paginationStartPage > 2}
                    &hellip;
                {/if}
                {section name=i start=$paginationStartPage loop=($footer_totalPages+1) max=5}
                    {if $smarty.section.i.index != $footer_currentPage}
                        <a class="btn btn-secondary btn-md btn-theme" href="{$smarty.section.i.index|string_format:$footer_pageLink}">{$smarty.section.i.index}</a>
                    {else}
                        <span class="thispage btn btn-secondary btn-md btn-theme disabled">{$smarty.section.i.index}</span>
                    {/if}
                {/section}
                {if $smarty.section.i.index < $footer_totalPages}
                    &hellip;
                {/if}
                {if $smarty.section.i.index <= $footer_totalPages}
                    <a class="btn btn-secondary btn-md btn-theme" href="{$footer_totalPages|string_format:$footer_pageLink}">{$footer_totalPages}</a>
                {/if}
                {if $footer_next_page}
                    <a class="btn btn-secondary btn-md btn-theme" title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}"><i class="fas fa-arrow-right" aria-hidden="true"></i><span class="sr-only">{$CONST.NEXT_PAGE}</span></a>
                {/if}
            </nav>
        {/if}
    </div>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
