{serendipity_hookPlugin hook="entries_header"}
<h3 class="serendipity_date">{$CONST.ARCHIVES}{if NOT empty($category_info.categoryid)} :: {$category_info.category_name}{/if}</h3>
{if isset($archives) AND is_array($archives)}
{foreach $archives AS $archive}
<table class="archives_listing">
    <tr class="archives_header">
        <td class="archives_header" colspan="5"><h2>{$archive.year}</h2></td>
    </tr>
    {foreach $archive.months AS $month}
    <tr class="archives_row">
        <td class="archives_graph" width="100"><img src="{serendipity_getFile file="img/graph_bar_horisontal.png"}" height="10" width="{math width=100 equation="count * width / max" count=$month.entry_count max=$max_entries format="%d"}" alt=""></td>
        <td class="archives_date">{$month.date|formatTime:"%B"}</td>
        <td class="archives_count">{$month.entry_count} {$CONST.ENTRIES}</td>
        <td class="archives_count_link">({if $month.entry_count}<a href="{$month.link}">{/if}{$CONST.VIEW_FULL}{if $month.entry_count}</a>{/if})</td>
        <td class="archives_link">({if $month.entry_count}<a href="{$month.link_summary}">{/if}{$CONST.VIEW_TOPICS}{if $month.entry_count}</a>{/if})</td>
    </tr>
    {/foreach}
</table>
{/foreach}
{/if}
<div class="serendipity_pageFooter">
{serendipity_hookPlugin hook="entries_footer"}
</div>
