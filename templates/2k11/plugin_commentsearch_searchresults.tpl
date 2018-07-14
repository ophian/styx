<aside class="comment_results">
    <h3>{$CONST.COMMENT_SEARCHRESULTS|sprintf:$comment_searchresults}:</h3>
    {if $comment_results}
    <ul class="plainList">
    {foreach $comment_results AS $result}
        <li>
            <span class="block_level">{if $result.type == 'TRACKBACK'}<a href="{$result.url|escape}">{else}<b>{/if}{$result.author|escape}{if $result.type == 'TRACKBACK'}</a>{else}</b>{/if} {$CONST.IN} <a href="{$result.permalink|escape}">{$result.title|escape}</a> {$CONST.ON} <time datetime="{$result.ctimestamp|serendipity_html5time}">{$result.ctimestamp|formatTime:$template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY}</time>:</span>
            {$result.comment|strip_tags|strip|truncate:200:" ... "}
        </li>
    {/foreach}
    </ul>
    {else}
    <p>{$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
</aside>
