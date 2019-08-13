<!-- Viewed by: {$customarchive_filter} example customarchive.tpl in template Sliver -->

<form action="?" method="post">
    <input type="hidden" name="serendipity[subpage]" value="{$staticpage_pagetitle}">
    <input type="hidden" name="serendipity[filter]" value="{$customarchive_filter}">
    <input type="hidden" name="serendipity[mode]" value="{$customarchive_mode}">

{foreach $customarchive_search AS $searchfield => $searchdata}
    <label for="key_{$searchdata.key}">{$searchfield}</label><br>

    {if $searchdata.type == 'text'}
    <input id="key{$searchdata.key}" type="text" name="serendipity[search][{$searchdata.key}]" value="{if NOT empty($customarchive_searchdata)}{pickKey array=$customarchive_searchdata key=$searchdata.key}{/if}">
    {elseif $searchdata.type == 'int'}
    <input id="key{$searchdata.key}" type="text" name="serendipity[search][{$searchdata.key}][from]" value="{if NOT empty($customarchive_searchdata_from)}{pickKey array=$customarchive_searchdata_from key=$searchdata.key}{/if}">
    {$CONST.RANGE_TO|lower}
    <input type="text" name="serendipity[search][{$searchdata.key}][to]" value="{if NOT empty($customarchive_searchdata_to)}{pickKey array=$customarchive_searchdata_to key=$searchdata.key}{/if}">
    {/if}
    <br>
{/foreach}

    <input class="input_button" type="submit" name="{$CONST.GO}" value="{$CONST.QUICKSEARCH}">
</form>

<table id="ap_liste">
<tr>
{foreach $customarchive_props AS $propkey => $prop}

    <th align="center">
    <a href="{$serendipityBaseURL}{$serendipityIndexFile}?serendipity[subpage]={$staticpage_pagetitle}&amp;serendipity[filter]={$propkey|replace:'ep_':''}&amp;serendipity[mode]={if $propkey|replace:'ep_':'' == $customarchive_filter}{$customarchive_nextmode}{else}ASC{/if}">{$prop}
    &nbsp;<img src="{$serendipityBaseURL}plugins/serendipity_event_customarchive/{if $propkey|replace:'ep_':'' == $customarchive_filter}{$customarchive_nextmode}{else}asc{/if}.gif"></a>
    </th>
{/foreach}

</tr>
{foreach $customarchive_entries AS $dategroup}

    {foreach $dategroup.entries AS $entry}
    <tr style="background-color:#F7F7F7;font-weight:bold;">
        {foreach $customarchive_props AS $propkey => $prop}
            <td>
                <div style="margin: 3px 5px">
                    {pickKey array=$entry.properties key=$propkey default='empty'}{pickKey array=$customarchive_infoprops key=$propkey default=''}
                </div>
            </td>
        {/foreach}
    </tr>

    <tr>
        <td colspan="{$propkey@total}">
        <div style="margin: 5px 5px;">
            <a href="{$entry.link}"><img style="float: left; margin: 3px" src="{if NOT empty($entry)}{pickKey array=$entry.properties key=$customarchive_picture}{/if}"></a>
            <a href="{$entry.link}">{$entry.title}</a><br>

            {if NOT empty($entry)}{pickKey array=$entry.properties key=$customarchive_teaser}{/if}
        </div>
        </td>
    </tr>
    <tr style="height:10px;"></tr>
    {/foreach}
{/foreach}

</table>
