<table class="serendipity_calendar">
<thead>
    <tr>
    {foreach $plugin_calendar_dow AS $dow}
        <th scope="col"><abbr title="{$dow.date|formatTime:"%A":false}">{$dow.date|formatTime:"%a":false}</abbr></th>
    {/foreach}
    </tr>
</thead>
<tfoot>
    <tr>
        <td class="prev">
        {if $plugin_calendar_head.minScroll le $plugin_calendar_head.month_date}
        <a href="{$plugin_calendar_head.uri_previous}">&larr;<span class="visuallyhidden"> {$CONST.BACK}</span></a>
        {/if}
        </td>
        <td class="current_month" colspan="5">
            <a href="{$plugin_calendar_head.uri_month}">{$plugin_calendar_head.month_date|formatTime:"%B &rsquo;%y":false}</a>
        </td>
        <td class="next">
        {if $plugin_calendar_head.maxScroll ge $plugin_calendar_head.month_date}
        <a href="{$plugin_calendar_head.uri_next}"><span class="visuallyhidden">{$CONST.FORWARD} </span>&rarr;</a>
        {/if}
        </td>
    </tr>
</tfoot>
<tbody>
{foreach $plugin_calendar_weeks AS $week}
    <tr>
    {foreach $week.days AS $day}
        <td class="{$day.classes}"{if isset($day.properties.Title)} title="{$day.properties.Title}"{/if}>{if isset($day.properties.Active) AND $day.properties.Active}<a href="{$day.properties.Link}">{/if}{$day.name|default:"&#160;"}{if isset($day.properties.Active) AND $day.properties.Active}</a>{/if}</td>
    {/foreach}
    </tr>
{/foreach}
</tbody>
</table>
