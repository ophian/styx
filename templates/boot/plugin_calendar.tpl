<table class="calendar table table-striped table-light">
<thead>
    <tr>
    {foreach $plugin_calendar_dow AS $dow}
        <th scope="col"><abbr title="{$dow.date|formatTime:"%A":false}">{$dow.date|formatTime:"%a":false}</abbr></th>
    {/foreach}
    </tr>
</thead>
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
<table class="calendar-nav">
<tfoot>
    <tr>
        <td class="prev">
{if $plugin_calendar_head.minScroll le $plugin_calendar_head.month_date}
        <a href="{$plugin_calendar_head.uri_previous}" title="{$CONST.BACK}">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M12.5 15a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5zM10 8a.5.5 0 0 1-.5.5H3.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L3.707 7.5H9.5a.5.5 0 0 1 .5.5z"/>
          </svg>
          <span> {$CONST.BACK}</span>
        </a>
{else}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-align-start" viewBox="0 0 16 16">
          <title>No blog entries before this date</title>
          <path fill-rule="evenodd" d="M1.5 1a.5.5 0 0 1 .5.5v13a.5.5 0 0 1-1 0v-13a.5.5 0 0 1 .5-.5z"/>
          <path d="M3 7a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7z"/>
        </svg>
{/if}
        </td>
        <td class="month">
            <a href="{$plugin_calendar_head.uri_month}">{$plugin_calendar_head.month_date|formatTime:"%B &rsquo;%y":false}</a>
        </td>
        <td class="next">
{if $plugin_calendar_head.maxScroll ge $plugin_calendar_head.month_date}
        <a href="{$plugin_calendar_head.uri_next}" title="{$CONST.FORWARD}">
          <span>{$CONST.FORWARD} </span>
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
          </svg>
        </a>
{else}
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-align-end" viewBox="0 0 16 16">
          <title>No further blog entries beyond this date</title>
          <path fill-rule="evenodd" d="M14.5 1a.5.5 0 0 0-.5.5v13a.5.5 0 0 0 1 0v-13a.5.5 0 0 0-.5-.5z"/>
          <path d="M13 7a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V7z"/>
        </svg>
{/if}
        </td>
    </tr>
</tfoot>
</table>
