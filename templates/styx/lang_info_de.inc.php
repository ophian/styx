<?php
/*
 * The info.txt charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset
 **/

$info['theme_info_summary'] = 'Das Styx Standard Backend Theme';

$info['theme_info_desc'] = '<u>ACHTUNG&colon;</u> Dieses Theme hat kein eigenes Frontend!<br>
Dieses Standard Backend Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu �ndern oder hinzuzuf�gen, die notwendig sind, um Ihre momentanen Bed�rfnisse zu erf�llen.';

$info['theme_info_backend'] = 'Dieses Standard Backend-Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Als Serendipity Styx Standard Backend "Beispiel" zeigt es die M�glichkeit, ein eigenes und mehr oder weniger template-leeres Backend Theme zu erstellen das als "Fallback" die "default" Backend Template Dateien nutzt.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu �ndern oder hinzuzuf�gen, die notwendig sind, um Ihre momentanen Bed�rfnisse zu erf�llen.
Momentan enth�lt es nur eine angepasste index und eine upgrader.inc Template Datei, die, au�er dem neuen "Dark Mode", nur bestimmte irrelevante Informationen und Assets auf der Login-Seite entfernen, wenn Sie nicht angemeldet sind.
Dieses Standard Backend verf�gt �ber eine eigene styles Auszeichnungs-Datei, �ber die stetig Verbesserungen im Backend vorgenommen werden.

<p>DAS FOLGENDE IST DIE BESCHREIBUNG DES FALLBACK BACKENDS&colon;<br>
Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "default/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfl�che.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden m�chten, kopieren Sie das Verzeichnis "admin" in ihr Theme.
�ndern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzuf�gen, und w�hlen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort k�nnen Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bed�rfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur f�r erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!</p>';
