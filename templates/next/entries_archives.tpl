{serendipity_hookPlugin hook="entries_header"}
<article id="archives" class="clearfix">
    <h2>{$CONST.ARCHIVES}{if NOT empty($category_info.categoryid)} :: {$category_info.category_name}{/if}</h2>

{if isset($archives) AND is_array($archives)}
{foreach $archives AS $archive}
    <section class="{cycle values="odd,even"}">
        <h3>{$archive.year}</h3>

        <ul class="year">
        {foreach $archive.months AS $month}
            {if $month.entry_count > 0}
            <li class="month">
                <span class="date">{if $month.entry_count}<a href="{$month.link}" title="{$CONST.VIEW_FULL}">{/if}{$month.date|formatTime:"%B"}{if $month.entry_count}</a>{/if}:</span>
                <span class="count">{if $month.entry_count}<a href="{$month.link_summary}" title="{$CONST.VIEW_TOPICS}">{/if}{$month.entry_count} {$CONST.ENTRIES}{if $month.entry_count}</a>{/if}</span>
            </li>
            {/if}
        {/foreach}
        </ul>
    </section>
{/foreach}
{/if}
</article>
{serendipity_hookPlugin hook="entries_footer"}
