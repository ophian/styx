{serendipity_hookPlugin hook="entries_header"}
            <article class="archive archive_summary">
                <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page} <span class="archive_summary_pageof">{$CONST.PAGE}/{$footer_currentPage}</span>{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

                <dl>
{foreach $entries AS $sentries}
{foreach $sentries.entries AS $entry}
{if isset($entry.properties.ep_no_frontpage) AND $entry.properties.ep_no_frontpage == true}{continue}{/if}
                    <dt><a href="{$entry.link}">{$entry.title}</a></dt>
                    <dd>{$CONST.POSTED_BY} <a href="{$entry.link_author}">{$entry.author}</a> {$CONST.ON} <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time></dd>
{/foreach}
{/foreach}
                </dl>
            </article>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

            <nav class="pager">
{if NOT empty($footer_info)}
                <p>{$footer_info}</p>

{if $is_embedded != true}
                <div class="totop">
                    <a href="#topofpage">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-up" viewBox="0 0 16 16">
                            <title>to top of page</title>
                            <path fill-rule="evenodd" d="M3.5 10a.5.5 0 0 1-.5-.5v-8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 0 0 1h2A1.5 1.5 0 0 0 14 9.5v-8A1.5 1.5 0 0 0 12.5 0h-9A1.5 1.5 0 0 0 2 1.5v8A1.5 1.5 0 0 0 3.5 11h2a.5.5 0 0 0 0-1h-2z"/>
                            <path fill-rule="evenodd" d="M7.646 4.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707V14.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3z"/>
                        </svg>
                    </a>
                </div>
{/if}{/if}
{if $footer_prev_page OR $footer_next_page}

                <ul class="plainList">
                    <li class="pager_prev">{if $footer_prev_page}<a href="{$footer_prev_page}">{$CONST.PREVIOUS_PAGE}</a>{else}&nbsp;{/if}</li>
                    <li class="pager_next">{if $footer_next_page}<a href="{$footer_next_page}">{$CONST.NEXT_PAGE}</a>{else}&nbsp;{/if}</li>
                </ul>
{/if}
            </nav>
{/if}

{if $dateRange.0 !== 1}{serendipity_hookPlugin hook="entries_footer"}{/if}
