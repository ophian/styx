{serendipity_hookPlugin hook="entries_header"}

<div class="article clearfix">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <p><a href="{$serendipityHTTPPath}archive">&larr; {$CONST.ARCHIVE_TEXT_SUMMARY}</a></p>

    <dl class="entries-list">
{foreach $entries AS $sentries}
    {foreach $sentries.entries AS $entry}
        <dt id="easeout"><a href="{$entry.link}" rel="bookmark">{$entry.title}</a></dt>
        <dd><span title="{$entry.timestamp|formatTime:'%A, %d. %B %Y'} {$CONST.AT} {$entry.timestamp|formatTime:'%H:%M'}">{$entry.timestamp|formatTime:'%d.%m.%y'}</span></dd>
    {/foreach}
{/foreach}
  </dl>
</div>
{if empty($is_single_entry) AND empty($is_preview) AND isset($view) AND $view != 'plugin' AND isset($footer_totalPages) AND $footer_totalPages > 1}

<section id="section_pagination">
  <div id="center"{if NOT $template_option.show_pagination} class="serendipity_entriesFooter"{/if}>
    {if $footer_prev_page}
        {if $template_option.prev_next_style == 'texticon'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><img alt="{$CONST.PREVIOUS_PAGE}" title="{$CONST.PREVIOUS_PAGE}" src="{serendipity_getFile file="img/back.png"}">{$CONST.PREVIOUS_PAGE}</a>
        {elseif  $template_option.prev_next_style == 'icon'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><img alt="{$CONST.PREVIOUS_PAGE}" src="{serendipity_getFile file="img/back.png"}">{$CONST.PREVIOUS_PAGE}</a>
        {elseif $template_option.prev_next_style == 'text'}

            <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}">&#171; {$CONST.PREVIOUS_PAGE}</a>&#160;&#160;
        {/if}
    {/if}
    {if NOT empty($footer_info)}

        ({$footer_info})
    {/if}
    {if $footer_next_page}
        {if $template_option.prev_next_style == 'texticon'}

            <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}">{$CONST.NEXT_PAGE}<img alt="{$CONST.NEXT_PAGE}" title="{$CONST.NEXT_PAGE}" src="{serendipity_getFile file="img/forward.png"}"></a>
        {elseif $template_option.prev_next_style == 'icon'}

            <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}"><img alt="{$CONST.NEXT_PAGE}" src="{serendipity_getFile file="img/forward.png"}"></a>
        {elseif $template_option.prev_next_style == 'text'}

             <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}">{$CONST.NEXT_PAGE} &#187;</a>
        {/if}
    {/if}
    {if $template_option.show_pagination AND $footer_totalPages > 1}

        <div class="pagination">
            {assign var="paginationStartPage" value="`$footer_currentPage-3`"}
            {if $footer_currentPage+3 > $footer_totalPages}
                {assign var="paginationStartPage" value="`$footer_totalPages-6`"}
            {/if}
            {if $paginationStartPage <= 0}
                {assign var="paginationStartPage" value="1"}
            {/if}
            {if $footer_prev_page}

                <a title="{$CONST.PREVIOUS_PAGE}" href="{$footer_prev_page}"><span class="pagearrow">&#9668;</span></a>
            {/if}
            {if $paginationStartPage > 1}

                <a href="{$footer_pageLink|replace:'%s':'1'}">1</a>
            {/if}
            {if $paginationStartPage > 2}&hellip;{/if}
            {section name=i start=$paginationStartPage loop=$footer_totalPages+1 max=7}
                {if $smarty.section.i.index != $footer_currentPage}

                    <a href="{$footer_pageLink|replace:'%s':$smarty.section.i.index}">{$smarty.section.i.index}</a>
                {else}

                    <span id="thispage">{$smarty.section.i.index}</span>
                {/if}
            {/section}
            {if $smarty.section.i.index < $footer_totalPages}&hellip;{/if}
            {if $smarty.section.i.index <= $footer_totalPages}

                <a href="{$footer_pageLink|replace:'%s':$footer_totalPages}">{$footer_totalPages}</a>
            {/if}
            {if $footer_next_page}

                <a title="{$CONST.NEXT_PAGE}" href="{$footer_next_page}"><span class="pagearrow">&#9658;</span></a>
            {/if}

        </div>
    {/if}

  </div><!-- // "id:#center" end -->
</section><!-- // "id:#section_pagination" end -->
{/if}
{serendipity_hookPlugin hook="entries_footer"}
