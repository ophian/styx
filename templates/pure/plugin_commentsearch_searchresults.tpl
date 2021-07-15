<div class="search-results clearfix">
    <h3>{$CONST.COMMENT_SEARCHRESULTS|sprintf:$comment_searchresults}:</h3>
    {if $comment_results}
    <ul>
    {foreach $comment_results AS $result}
        <li>
            <span>{if $result.type == 'TRACKBACK'}<a href="{$result.url|escape}">{else}<b>{/if}{$result.author|escape}{if $result.type == 'TRACKBACK'}</a>{else}</b>{/if} {$CONST.IN} <a href="{$result.permalink|escape}">{$result.title|escape}</a> {$CONST.ON} <time datetime="{$result.ctimestamp|serendipity_html5time}">{$result.ctimestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time>:</span>
            {$result.comment|strip_tags|strip|truncate:200:" ... "}
        </li>
    {/foreach}
    </ul>
    {else}
    <p class="serendipity_msg_notice">{$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
</div>
