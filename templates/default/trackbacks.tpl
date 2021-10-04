{foreach $trackbacks AS $trackback}

    <div class="serendipity_comment">
        <a id="c{$trackback.id}"></a>
        <div class="serendipity_commentBody">
            <a href="{$trackback.url|strip_tags}" {'blank'|xhtml_target}>{$trackback.title}</a>{if $trackback.type == 'TRACKBACK'}<br>{/if}
            {$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]
        </div>
        <div class="serendipity_comment_source">
            <strong>{$CONST.WEBLOG}:</strong> {$trackback.author|default:$CONST.ANONYMOUS}<br>
            <strong>{$CONST.TRACKED}:</strong> {$trackback.timestamp|formatTime:'%b %d, %H:%M'}
            {if NOT empty($entry.is_entry_owner)}(<a href="{$trackback.link_delete}">{$CONST.DELETE}</a>){/if}

        </div>
    </div>
{foreachelse}
    <div class="serendipity_center">{$CONST.NO_TRACKBACKS}</div>
{/foreach}
