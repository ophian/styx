<table class="calendar table table-striped table-{*if $template_option.bs_navbar_style == 'light'}light{else}dark{/if*}">
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
        <a href="{$plugin_calendar_head.uri_previous}">&larr;<span> {$CONST.BACK}</span></a>
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
        {* $plugin_calendar_head.maxScroll|formatTime:"%Y%m":false}>={$plugin_calendar_head.month_date|formatTime:"%Y%m":false *}
{if $plugin_calendar_head.maxScroll ge $plugin_calendar_head.month_date}
        <a href="{$plugin_calendar_head.uri_next}"><span>{$CONST.FORWARD} </span>&rarr;</a>
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
