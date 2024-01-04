<?php

/**
 *  @version 1.0
 *  @author Konrad Bauckmeier <kontakt@dd4kids.de>
 *  @translated 2009/06/03
 */
@define('PLUGIN_HISTORY_NAME', 'Geschichte');
@define('PLUGIN_HISTORY_DESC', 'Zeigt Eintr�ge eines einstellbaren Alters an.');
@define('PLUGIN_HISTORY_MIN_AGE', 'Mindestalter');
@define('PLUGIN_HISTORY_MIN_AGE_DESC', 'Mindestalter der Eintr�ge (in Tagen).');
@define('PLUGIN_HISTORY_MAX_AGE', 'H�chstalter');
@define('PLUGIN_HISTORY_MAX_AGE_DESC','H�chstalter der Eintr�ge (in Tagen).');
@define('PLUGIN_HISTORY_MAX_ENTRIES', 'Maximale Anzahl der Eintr�ge');
@define('PLUGIN_HISTORY_MAX_ENTRIES_DESC', 'Wieviele Eintr�ge sollen maximal angezeigt werden? Im Falle eines definierten Zeitraumes mit "Anzahl der durchlaufenden Jahre" gr��er 1, beschr�nkt dies die tats�chlich dargestellte Maximal-Zahl der Eintr�ge.');
@define('PLUGIN_HISTORY_SHOWFULL', 'Ganze Eintr�ge');
@define('PLUGIN_HISTORY_SHOWFULL_DESC', 'Nicht nur �berschriften, sondern ganze Eintr�ge anzeigen.');
@define('PLUGIN_HISTORY_INTRO', 'Intro');
@define('PLUGIN_HISTORY_INTRO_DESC', 'Kurzes Vorwort, wie: \'Ein Jahr vorher sagte ich:\'');
@define('PLUGIN_HISTORY_OUTRO', 'Outro');
@define('PLUGIN_HISTORY_OUTRO_DESC', 'Kurzes Schlusswort, wie: \'Sch�n, nicht wahr..?\'');
@define('PLUGIN_HISTORY_DISPLAYDATE', 'Datum anzeigen');
@define('PLUGIN_HISTORY_DISPLAYDATE_DESC', 'Vor jedem Eintrag das Datum anzeigen?');
@define('PLUGIN_HISTORY_MAXLENGTH', 'L�nge der �berschriften');
@define('PLUGIN_HISTORY_MAXLENGTH_DESC', 'Nach wie vielen Zeichen sollen die �berschriften abgeschnitten werden (0 f�r gar nicht)?');
@define('PLUGIN_HISTORY_SPECIALAGE', 'Vorgefertigter Zeitrahmen');
@define('PLUGIN_HISTORY_SPECIALAGE_DESC', 'Wenn Sie statt einem vorgefertigten lieber einen eigenen Zeitraum einstellen m�chten, w�hlen Sie \'Anderer\' und f�llen die unteren beiden Felder aus. (Persische Kalender ausgenommen.)');
@define('PLUGIN_HISTORY_SPECIALAGE_YEAR', 'Zeigt Eintr�ge vom selben Datum des letzten Jahres an.');
@define('PLUGIN_HISTORY_CUSTOMAGE', 'Zeitrahmen selbst einstellen');
@define('PLUGIN_HISTORY_OYA', 'Heute vor einem Jahr');
@define('PLUGIN_HISTORY_MYSELF', 'Anderer');
@define('PLUGIN_HISTORY_DISPLAYAUTHOR', 'Zeige den Namen des Authors');
@define('PLUGIN_HISTORY_MULTIYEARS', 'Anzahl der durchlaufenden Jahre');
@define('PLUGIN_HISTORY_MULTIYEARS_DESC', 'Setzen Sie die Anzahl der Jahre, die durchlaufen werden sollen, wenn Sie "Heute vor einem Jahr" als Zeitrahmen ausgew�hlt haben. Standard ist 1 (das aktuelle Jahr). Setzen Sie daf�r die voranstehenden "Mindestalter" und "H�chstalter" Eintr�ge auf exakt 365 Tage. Bei Auswahl von mehreren Jahren wird die ausgegebene History Sidebar Box mitsamt m�glichen Intro und Outro aus Performancegr�nden f�r diesen Tag in der "templates_c/history_daylist.dat" Datei gecached.');
@define('PLUGIN_HISTORY_MULTIYEARS_EMPTY', 'Optionaler Text, f�r Eintrags Cache von leeren Jahren');

