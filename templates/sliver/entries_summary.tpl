{serendipity_hookPlugin hook="entries_header"}

<div class="article clearfix">
    <h2>{$CONST.TOPICS_OF} {$dateRange.0|formatTime:"%B %Y"}</h2>

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

{serendipity_hookPlugin hook="entries_footer"}
