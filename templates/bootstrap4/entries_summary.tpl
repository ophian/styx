{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <dl class="row">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}
        <dt class="col-xs-12 col-lg-7"><a href="{$entry.link}">{$entry.title}</a></dt>
        <dd class="col-xs-6 col-lg-3"><a href="{$entry.link_author}">{$entry.author}</a></dd>
        <dd class="col-xs-6 col-lg-2"><time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:'%d. %m. %Y'}</time></dd>
        {/foreach}
    {/foreach}
    </dl>
</article>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav aria-label="{$footer_info|default:''}" title="{$footer_info|default:''}">
        <ul class="pagination justify-content-between">
            <li class="page-item{if empty($footer_prev_page)} disabled{/if}">
            {if $footer_prev_page}
                <a class="page-link" href="{$footer_prev_page}">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">{$CONST.PREVIOUS_PAGE}</span>
                </a>
            {/if}
            </li>
            <li class="page-item info{if empty($footer_info)} disabled{/if}">{$footer_info}</li>
            <li class="page-item{if empty($footer_next_page)} disabled{/if}">
            {if $footer_next_page}
                <a class="page-link" href="{$footer_next_page}">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">{$CONST.NEXT_PAGE}</span>
                </a>
            {/if}
            </li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}