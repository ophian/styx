<?php
/*
 * The info.txt UTF-8 charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as UTF-8 file without BOM.
 **/

$info['theme_info_summary'] = 'Das Styx Standard Backend Theme';

$info['theme_info_desc'] = '<u>ACHTUNG&colon;</u> Dieses Theme hat kein eigenes Frontend!<br>
Dieses Standard Backend Theme verweist auf die Kern Styx Backend Templates im "default/admin" Unterverzeichnis.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu ändern oder hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.';

$info['theme_info_backend'] = 'Dieses Standard Backend-Theme verweist auf die Kern Styx Backend Templates im "default/admin" Unterverzeichnis.
Als Serendipity Styx Standard Backend "Beispiel" zeigt es die Möglichkeit, ein eigenes und mehr oder weniger "template-leeres" Backend Theme zu erstellen, das als "Fallback" die "default" Backend Template Dateien nutzt.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu ändern oder hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.
Momentan enthält es außer einer angepassten index Datei nur noch die installer.inc und upgrader.inc Template Dateien, deren drei, außer dem "Dark Mode", nur bestimmte irrelevante Informationen und Assets auf der Login-Seite entfernen, wenn Sie nicht angemeldet sind.
Dieses Standard Backend verfügt über eigene stylesheet Auszeichnungs-Dateien, über die kontinuierlich und stetig Verbesserungen im Backend vorgenommen werden.

<p>DAS FOLGENDE IST DIE BESCHREIBUNG DES FALLBACK BACKENDS&colon;<br>
Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "default/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfläche.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden möchten, kopieren Sie das Verzeichnis "admin" in Ihr Theme.
Ändern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzufügen, und wählen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort können Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bedürfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur für erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!</p>';
