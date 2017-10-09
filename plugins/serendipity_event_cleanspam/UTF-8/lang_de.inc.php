<?php

/**
 *  @version 1.0
 *  @author Ian
 *  @translated 2017/10/09
 */

@define('PLUGIN_CLEANSPAM_NAME', 'Wartungs-Cleanup der Spam Logs');
@define('PLUGIN_CLEANSPAM_DESC', 'Räumt Datenbank-Logeinträge auf, die bestimmten Kriterien unterliegen. Dies sollte periodisch angestoßen werden, da Spammer die Spamblog Logs kontinuierlich aufblähen und das Blog immer weiter verlangsamen.');
@define('PLUGIN_CLEANSPAM_MAINTAIN', 'Aufräumen der Spam-Logs');

@define('PLUGIN_CLEANSPAM_ALL_BUTTON', 'Lösche: Alle');
@define('PLUGIN_CLEANSPAM_ALL_DESC', 'Lösche <b>alle</b> Log-Einträge des Typs: \'REJECTED\' in der Datenbank.Tabelle "spamblocklog". Augenblicklich ("%d") enthalten.');
@define('PLUGIN_CLEANSPAM_MULTI_BUTTON', 'Lösche: selektiv');
@define('PLUGIN_CLEANSPAM_YEARS_BUTTON', 'Lösche: Jahre');
@define('PLUGIN_CLEANSPAM_MULTI_DESC', 'Lösche individuell <b>multi</b>-selected oder <b>Einzeln</b> von Tabellenfeld "reason" LIKE "items"');
@define('PLUGIN_CLEANSPAM_MSG_DONE', 'Löschung erfolgt!');
@define('PLUGIN_CLEANSPAM_SELECT', 'Einzelselektion nach Kriterien');
@define('PLUGIN_CLEANSPAM_VISITORS', 'Lösche Besucher nach Jahren');
@define('PLUGIN_CLEANSPAM_VISITORS_DESC', 'Multi-select <b>Jahre</b>, um die Datenbank Log-Tabelle "visitors" der Besucher zu säubern. <u>ACHTUNG: </u><br>Dies beeinflusst die Statistik Historie des Statistik-Ereignis-Plugins.');

