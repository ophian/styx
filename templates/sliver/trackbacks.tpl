{foreach $trackbacks AS $trackback}
<article id="c{$trackback.id}" class="{$trackback.type|lower} serendipity_comment {cycle values="odd,even"}">
    <h4><cite>{$trackback.author|default:$CONST.ANONYMOUS}</cite> {$CONST.ON} <time datetime="{$trackback.timestamp|formatTime:"%Y-%m-%dT%H:%M:%SZ"}" pubdate>{$trackback.timestamp|formatTime:$template_option.date_format}</time>: <a href="{$trackback.url|strip_tags}">{$trackback.title}</a></h4>

{* This regex removes a possible avatar image automatically added by the serendipity_event_gravatar plugin *}
{if {$trackback.body|regex_replace:"/^<img.*>$/":''} == ''}
    {if $trackback.type == 'TRACKBACK'}<p class="serendipity_center nocomments">{$CONST.NO_ENTRIES_TO_PRINT}</p>{/if}
{else}
    <details>
        <summary>{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$trackback.title}</summary>
        <div class="clearfix">{$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]</div>
    </details>
{/if}
{if NOT empty($entry.is_entry_owner)}
    <footer>
        <a href="{$serendipityBaseURL}comment.php?serendipity[delete]={$trackback.id}&amp;serendipity[entry]={$trackback.entry_id}&amp;serendipity[type]=trackbacks">{$CONST.DELETE}</a>
    </footer>
{/if}
</article>
{foreachelse}
<p class="serendipity_center nocomments">{$CONST.NO_TRACKBACKS}</p>
{/foreach}
