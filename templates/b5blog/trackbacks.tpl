    <ol class="plainList">
    {foreach $trackbacks AS $trackback}
        <li id="c{$trackback.id}" class="{$trackback.type|lower} {cycle values="tb-odd,tb-even"}">
     {* This regex removes a possible avatar image automatically added by the serendipity_event_gravatar plugin *}
    {if {$trackback.body|regex_replace:"/^<img.*>$/":''} == ''}
        {if $trackback.type == 'TRACKBACK'}<span class="serendipity_msg_notice no-content">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}
    {else}
        <details>
            <summary><time datetime="{$trackback.timestamp|serendipity_html5time}">{$trackback.timestamp|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</time> | {$CONST.VIEW_EXTENDED_ENTRY|sprintf:$trackback.title}</summary>
            <div class="clearfix">
                {$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]
            </div>
        </details>
    {/if}
        </li>
    {/foreach}
    </ol>
