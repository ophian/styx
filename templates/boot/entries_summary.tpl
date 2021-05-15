{serendipity_hookPlugin hook="entries_header"}
<article class="archive archive_summary">
    <h2>{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}</h2>

    <dl class="row">
    {foreach $entries AS $sentries}
        {foreach $sentries.entries AS $entry}

        <dt class="col-12 col-sm-6 col-md-12 col-lg-6 col-xl-7">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link-45deg" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
              <title id="title">{$CONST.LINK_TO_ENTRY}</title>
              <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
            </svg>
            <a href="{$entry.link}">{$entry.title}</a>
        </dt>
        <dd class="col-6 col-sm-3 col-md-6 col-lg-3 col-xl-3"><a href="{$entry.link_author}">{$entry.author}</a></dd>
        <dd class="col-6 col-sm-3 col-md-6 col-lg-3 col-xl-2"><time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:'%d. %m. %Y'}</time></dd>
        {/foreach}
    {/foreach}

    </dl>
    <a class="btn btn-outline-secondary btn-sm comment_source_trace" href="#" onclick="window.history.back();return false;" title="{$CONST.BACK}">{$CONST.BACK}</a>
</article>
{serendipity_hookPlugin hook="entries_footer"}