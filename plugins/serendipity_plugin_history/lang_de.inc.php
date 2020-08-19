<?php

/**
 *  @version 1.0
 *  @author Konrad Bauckmeier <kontakt@dd4kids.de>
 *  @translated 2009/06/03
 */
@define('PLUGIN_HISTORY_NAME', 'Geschichte');
@define('PLUGIN_HISTORY_DESC', 'Zeigt Einträge eines einstellbaren Alters an.');
@define('PLUGIN_HISTORY_MIN_AGE', 'Mindestalter');
@define('PLUGIN_HISTORY_MIN_AGE_DESC', 'Mindestalter der Einträge (in Tagen).');
@define('PLUGIN_HISTORY_MAX_AGE', 'Höchstalter');
@define('PLUGIN_HISTORY_MAX_AGE_DESC','Höchstalter der Einträge (in Tagen).');
@define('PLUGIN_HISTORY_MAX_ENTRIES', 'Maximale Anzahl der Einträge');
@define('PLUGIN_HISTORY_MAX_ENTRIES_DESC', 'Wieviele Einträge sollen maximal angezeigt werden? Im Falle eines definierten Zeitraumes mit "Anzahl der durchlaufenden Jahre" größer 1, beschränkt dies die tatsächlich dargestellte Maximal-Zahl der Einträge.');
@define('PLUGIN_HISTORY_SHOWFULL', 'Ganze Einträge');
@define('PLUGIN_HISTORY_SHOWFULL_DESC', 'Nicht nur Überschriften, sondern ganze Einträge anzeigen.');
@define('PLUGIN_HISTORY_INTRO', 'Intro');
@define('PLUGIN_HISTORY_INTRO_DESC', 'Kurzes Vorwort, wie: \'Ein Jahr vorher sagte ich:\'');
@define('PLUGIN_HISTORY_OUTRO', 'Outro');
@define('PLUGIN_HISTORY_OUTRO_DESC', 'Kurzes Sclusswort, wie: \'Schön, nicht wahr..?\'');
@define('PLUGIN_HISTORY_DISPLAYDATE', 'Datum anzeigen');
@define('PLUGIN_HISTORY_DISPLAYDATE_DESC', 'Vor jedem Eintrag das Datum anzeigen?');
@define('PLUGIN_HISTORY_MAXLENGTH', 'Überschriftenlänge');
@define('PLUGIN_HISTORY_MAXLENGTH_DESC', 'Nach wievielen Zeichen sollen die Überschriften abgeschnitten werden (0 für gar nicht)?');
@define('PLUGIN_HISTORY_SPECIALAGE', 'Vorgefertigter Zeitrahmen');
@define('PLUGIN_HISTORY_SPECIALAGE_DESC', 'Wenn Sie statt einem vorgefertigten lieber einen eigenen Zeitraum einstellen möchten, wählen Sie \'Anderer\' und füllen die unteren beiden Felder aus.');
@define('PLUGIN_HISTORY_SPECIALAGE_YEAR', 'Zeigt Einträge vom selben Datum des letzten Jahres an.');
@define('PLUGIN_HISTORY_CUSTOMAGE', 'Zeitrahmen selbst einstellen');
@define('PLUGIN_HISTORY_OYA', 'Heute vor einem Jahr');
@define('PLUGIN_HISTORY_MYSELF', 'Anderer');
@define('PLUGIN_HISTORY_DISPLAYAUTHOR', 'Zeige den Namen des Authors');
@define('PLUGIN_HISTORY_MULTIYEARS', 'Anzahl der durchlaufenden Jahre');
@define('PLUGIN_HISTORY_MULTIYEARS_DESC', 'Setzen Sie die Anzahl der Jahre, die durchlaufen werden sollen, wenn Sie "Heute vor einem Jahr" als Zeitrahmen ausgewählt haben. Standard ist 1 (das aktuelle Jahr). Setzen Sie dafür die voranstehenden "Mindestalter" und "Höchstalter" Einträge auf exakt 365 Tage. Bei Auswahl von mehreren Jahren wird die ausgegebene History Sidebar Box mitsamt möglichen Intro und Outro aus Performancegründen für diesen Tag in der "templates_c/history_daylist.dat" Datei gecached.');
@define('PLUGIN_HISTORY_MULTIYEARS_EMPTY', 'Optionaler Text, für Eintrags Cache von leeren Jahren');

