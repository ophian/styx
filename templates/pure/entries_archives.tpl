{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_overview">
    <h2>{$CONST.ARCHIVES}</h2>
{if isset($archives) AND is_array($archives)}
{foreach $archives AS $archive}{if $archive.sum === 0}{continue}{/if}
    <section class="archive_year">
        <h3>{$archive.year}</h3>

        <dl>
        {foreach $archive.months AS $month}
            {if $month.entry_count > 0}
            <dt>{if $month.entry_count}<a href="{$month.link}" title="{$CONST.VIEW_FULL}">{/if}{$month.date|formatTime:"%B"}{if $month.entry_count}</a>{/if}:</dt>
            <dd>{if $month.entry_count}<a href="{$month.link_summary}" title="{$CONST.VIEW_TOPICS}">{/if}{$month.entry_count} {$CONST.ENTRIES}{if $month.entry_count}</a>{/if}</dd>
            {/if}
        {/foreach}
        </dl>
    </section>
{/foreach}
{/if}
</article>
{serendipity_hookPlugin hook="entries_footer"}
