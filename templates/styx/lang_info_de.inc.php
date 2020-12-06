<?php
/*
 * The info.txt charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset
 **/

$info['theme_info_summary'] = 'Das Styx Backend (Beispiel) Default Theme';

$info['theme_info_desc'] = '<u>ACHTUNG&colon;</u> Dieses Theme hat kein eigenes Frontend!<br>
Dieses Backend Beispiel-Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Als Beispiel zeigt es die Möglichkeit, ein eigenes und mehr oder weniger leeres Backend Theme zu erstellen, dass als "Fallback" die Standard "default" Backend Template Dateien nutzt.
Dies bedenkend, sind Sie in der Lage, nur diejenigen Dateien zu ändern und hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.';

$info['theme_info_backend'] = 'Dieses Backend Beispiel-Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Als Beispiel zeigt es die Möglichkeit, ein eigenes und mehr oder weniger leeres Backend Theme zu erstellen, dass als "Fallback" die Standard "default" Backend Template Dateien nutzt.
Dies bedenkend, sind Sie in der Lage, nur diejenigen Dateien zu ändern und hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.
Dieses Backend-Beispiel kann sich in Zukunft ändern, um mehr echte Dateien für die Backend-Generierung vorzuhalten.
Momentan enthält es nur eine Index-Template-Datei, die relevante Informationen und Assets auf der Login-Seite entfernt, wenn Sie nicht angemeldet sind.<br>
* 2017-08-21 - Neu hinzugekommen ist ein Bugfix für die Backend-Ansichten mit "rtl" (right-to-left), auf rechts gedrehtes Schrift-Attribut im &lt;html&gt; Element.<br>
* 2019-09-08 - Die IE8/9 workarounds wurden entfernt.<br>
* 2020-03-25 - Hervorhebung der Styx message styles.<br>
* 2020-06-06 - Verkleinertes font-size der entry_info styles der Eintrags Liste.<br>
* 2020-08-31 - Verbesserung der Login Seite und Update des fullsize preview.<br>
* 2020-10-31 - SVG icon für die eingesprungenen Plugin fieldset legends des Eintragsformulares.<br>
* 2020-12-06 - Erlaube Media Screen iPhone 5/SE mit 320px Größen für mmedia filter HTML datetime Felder.<br><br>

DAS FOLGENDE IST DIE BESCHREIBUNG DES FALLBACK BACKENDS&colon;<br>
Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "default/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfläche.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden möchten, kopieren Sie das Verzeichnis "admin" in ihr Theme.
Ändern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzufügen, und wählen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort können Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bedürfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur für erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!';
