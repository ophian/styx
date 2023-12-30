<?php

@define('PLUGIN_CHANGELOG_TITLE', 'Serendipity-Log Reader');
@define('PLUGIN_CHANGELOG_DESC', 'Gewährt leichten Zugang zum Serendipity ChangeLog und optional aktivierten Error/Debug-Log Dateien.');
@define('PLUGIN_CHANGELOG_TITLE_DESC', 'Bitte lesen Sie sorgfältig das Serendipity ChangeLog für die aktuelle Version: "%s" durch, um sicherzugehen, dass Ihnen keine Änderung oder Anweisung für benutzerdefinierte Themes oder Plugins verborgen bleibt.');

@define('PLUGIN_CHANGELOG_MAINTAIN', 'Zeige Serendipity Logfiles');
@define('PLUGIN_CHANGELOG_BUTTON', 'Öffne Changelog');
@define('PLUGIN_CHANGELOG_DELETEOLDLOGS', 'Lösche alte Logfiles');

@define('PLUGIN_CHANGELOG_LOGGER_BUTTON', 'Öffne Logdatei');
@define('PLUGIN_CHANGELOG_LOGGER_DESC', 'Öffnet die letzte Logdatei für die Debugging-Session, welches in der Konfiguration: "Generelle Einstellung" gesetzt wurde. Löschen löscht alle alten Log-Dateien.');
@define('PLUGIN_CHANGELOG_LOGGER_HAS_LOGS', 'Das VERZEICHNIS "templates_c/logs/" enthält %d text logger Log-Dateien.');
@define('PLUGIN_CHANGELOG_LOGGER_NUKE_WARNING', "BEACHTEN SIE:
Zur Erinnerung: Diese Log-Dateien sind letztlich immer nur einfache Textdateien.
Sie sollten diese nach Verwendung besser löschen, zB. für alle älteren über den
Serendipity Logfiles Button auf der Wartungsseite. So gehen Sie sicher, dass
trotz der aktiven Restriktionen gegen einen nicht autorisierten Zugang, durch
Serendipity Styx im Allgemeinen, und für diese URL durch das Changelog-Plugin im
Speziellen, die nötigen Berechtigungen des \"templates_c\" Verzeichnisses nicht
doch anderweitig ausgenutzt werden können!
(Insbesondere dann, wenn Sie keinen NCSA-Server kompatiblen Webserver wie APACHE
 verwenden!)");

@define('PLUGIN_CHANGELOG_LOGGER_BACKBLAH', 'BITTE DEN BACK BUTTON DES BROWSERS NUTZEN, UM ZUR WARTUNGSSEITE ZURÜCKZUKEHREN.');

