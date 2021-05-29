{if $entry.trackbacks > 0}

        <ol class="plainList">
    {foreach $trackbacks AS $trackback}

            <li id="c{$trackback.id}" class="{$trackback.type|lower} {cycle values="tb-odd,tb-even"}">
            {if $trackback.body == '' AND $trackback.type == 'PINGBACK'}
                <div><cite>{$trackback.author|default:$CONST.ANONYMOUS}</cite> {$CONST.ON} <time datetime="{$trackback.timestamp|serendipity_html5time}">{$trackback.timestamp|formatTime:$template_option.date_format}</time>: <a href="{$trackback.url|strip_tags}">{$trackback.title}</a></div>
            {/if}
         {* This regex removes a possible avatar image automatically added by the serendipity_event_gravatar plugin *}
        {if {$trackback.body|regex_replace:"/^<img.*>$/":''} == ''}{***}
            {if $trackback.type == 'TRACKBACK'}    <span class="serendipity_msg_notice no-content">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}
        {else}

            <details>
                <summary><time datetime="{$trackback.timestamp|serendipity_html5time}">{$trackback.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time> | {$CONST.VIEW_EXTENDED_ENTRY|sprintf:"<em>{$trackback.title}</em>"}</summary>
                <div class="clearfix">
                    {$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]
                    <span class="trackback_author">{$CONST.RANGE_FROM} <strong>{$trackback.author|default:$CONST.ANONYMOUS}</strong> {$CONST.IN} <a href="{$trackback.url|strip_tags}">{$trackback.title}</a></span>
                    {if NOT empty($entry.is_entry_owner)}<a href="{$trackback.link_delete}">{$CONST.TRACKBACK} {$CONST.DELETE}</a>{/if}
                </div>
            </details>
        {/if}

            </li>
    {/foreach}

        </ol>
{/if}