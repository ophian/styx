{serendipity_hookPlugin hook="entries_header"}
<article class="archives clearfix">
    <h2>{$CONST.ARCHIVES}{if NOT empty($category_info.categoryid)} :: {$category_info.category_name}{/if}</h2>
{if isset($archives) AND is_array($archives)}
{foreach $archives AS $archive}
    <section class="archives_year clearfix {cycle values="arch_odd,arch_even"}">
        <h3>{$archive.year}</h3>

        <ul class="plainList">
        {foreach $archive.months AS $month}
            <li class="archives_month">
                <span class="archives_date">{if $month.entry_count}<a href="{$month.link}" title="{$CONST.VIEW_FULL}">{/if}{$month.date|formatTime:"%B"}{if $month.entry_count}</a>{/if}:</span>
                <span class="archives_count">{if $month.entry_count}<a href="{$month.link_summary}" title="{$CONST.VIEW_TOPICS}">{/if}{$month.entry_count} {$CONST.ENTRIES}{if $month.entry_count}</a>{/if}</span>
            </li>
        {/foreach}
        </ul>
    </section>
{/foreach}
{/if}
</article>
{serendipity_hookPlugin hook="entries_footer"}
