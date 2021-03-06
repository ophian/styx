<table class="serendipity_calendar">
    <tr>
        <td class="serendipity_calendarHeader">
<?php if ($GLOBALS['tpl']['plugin_calendar_head']['minScroll'] <= $GLOBALS['tpl']['plugin_calendar_head']['month_date']): ?>
            <a title="<?= BACK ?>" href="<?= $GLOBALS['tpl']['plugin_calendar_head']['uri_previous'] ?>"><img alt="<?= BACK ?>" src="<?php echo serendipity_getTemplateFile('img/back-r.png'); ?>" width="16" height="12" /></a>
<?php endif; ?>
        </td>

        <td colspan="5" class="serendipity_calendarHeader">
            <b><a href="<?= $GLOBALS['tpl']['plugin_calendar_head']['uri_month'] ?>"><?= serendipity_formatTime("%B &rsquo;%y", $GLOBALS['tpl']['plugin_calendar_head']['month_date']); ?></a></b>
        </td>

        <td class="serendipity_calendarHeader">
<?php if ($GLOBALS['tpl']['plugin_calendar_head']['maxScroll'] >= $GLOBALS['tpl']['plugin_calendar_head']['month_date']): ?>
            <a title="<?= FORWARD ?>" href="<?= $GLOBALS['tpl']['plugin_calendar_head']['uri_next'] ?>"><img alt="<?= FORWARD ?>" src="<?php echo serendipity_getTemplateFile('img/forward-r.png'); ?>" width="16" height="12" /></a>
<?php endif; ?>
        </td>
    </tr>

    <tr>
    <?php foreach($GLOBALS['tpl']['plugin_calendar_dow'] AS $dow):?>
        <td scope="col" abbr="<?= serendipity_formatTime('%A', $dow['date']); ?>" title="<?= serendipity_formatTime('%A', $dow['date']); ?>" class="serendipity_weekDayName" align="center"><?= serendipity_formatTime('%a', $dow['date']); ?></td>
    <?php endforeach; ?>
    </tr>

    <?php foreach($GLOBALS['tpl']['plugin_calendar_weeks'] AS $week):?>
        <tr class="serendipity_calendar">
        <?php foreach($week['days'] AS $day):?>
            <td class="serendipity_calendarDay <?= $day['classes'] ?>"<?php if (isset($day['properties']['Title'])): ?> title="<?= $day['properties']['Title'] ?>"<?php endif; ?>><?php if (isset($day['properties']['Active']) && $day['properties']['Active']): ?><a href="<?= $day['properties']['Link'] ?>"><?php endif; ?><?= $day['name']; ?><?php if (isset($day['properties']['Active']) && $day['properties']['Active']): ?></a><?php endif; ?></td>
        <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>
