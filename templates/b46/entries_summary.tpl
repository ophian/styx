{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{if $dateRange.0 === 1 OR isset($footer_currentPage)}{$head_subtitle}{if $footer_prev_page OR $footer_next_page}: {$CONST.PAGE} {$footer_currentPage}{/if}{else}{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}{/if}</h2>

    <dl class="row">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}

        <dt class="col-xs-12 col-lg-7">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link-45deg" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
              <title id="title">{$CONST.LINK_TO_ENTRY}</title>
              <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
            </svg>
            <a href="{$entry.link}">{$entry.title}</a>
        </dt>
        <dd class="col-xs-6 col-lg-3"><a href="{$entry.link_author}">{$entry.author}</a></dd>
        <dd class="col-xs-6 col-lg-2"><time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:'%d. %m. %Y'}</time></dd>
        {/foreach}
    {/foreach}

    </dl>
{if empty($footer_prev_page) AND empty($footer_next_page)}
    <a class="btn btn-outline-secondary btn-sm comment_source_trace" href="#" onclick="window.history.back();return false;" title="{$CONST.BACK}">{$CONST.BACK}</a>
{/if}
</article>
{if (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav class="col-sm-12" aria-label="{$footer_info|default:''}" title="{$footer_info|default:''}">
        <ul class="entries_pagination pagination justify-content-between">
            <li class="page-item prev{if empty($footer_prev_page)} disabled{/if}">
            {if $footer_prev_page}
                <a class="page-link" href="{$footer_prev_page}">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-left" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="title">{$CONST.PREVIOUS_PAGE}</title>
                      <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                      <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <span class="sr-only">{$CONST.PREVIOUS_PAGE}</span>
                </a>
            {/if}
            </li>
            <li class="page-item info{if empty($footer_info)} disabled{/if}">{$footer_info}</li>
            <li class="page-item next{if empty($footer_next_page)} disabled{/if}">
            {if $footer_next_page}
                <a class="page-link" href="{$footer_next_page}">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-right" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="title">{$CONST.NEXT_PAGE}</title>
                      <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
                      <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    <span class="sr-only">{$CONST.NEXT_PAGE}</span>
                </a>
            {/if}
            </li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}