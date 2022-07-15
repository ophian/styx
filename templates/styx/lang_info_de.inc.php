<?php
/*
 * The info.txt charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset
 **/

$info['theme_info_summary'] = 'Das Styx Standard Backend ("Beispiel") Default Theme';

$info['theme_info_desc'] = '<u>ACHTUNG&colon;</u> Dieses Theme hat kein eigenes Frontend!<br>
Dieses Standard Backend Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu ändern oder hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.';

$info['theme_info_backend'] = 'Dieses Standard Backend-Theme verweist auf die Styx Kern Backend Templates im "default/admin" Unterverzeichnis.
Als Serendipity Styx Standard Backend "Beispiel" zeigt es die Möglichkeit, ein eigenes und mehr oder weniger template-leeres Backend Theme zu erstellen das als "Fallback" die "default" Backend Template Dateien nutzt.
Es zeigt auf einfache Weise, wie Sie damit in der Lage sind, nur diejenigen Dateien zu ändern oder hinzuzufügen, die notwendig sind, um Ihre momentanen Bedürfnisse zu erfüllen.
Momentan enthält es eine angepasste index-Template-Datei, die außer dem neuen "Dark Mode" nur bestimmte irrelevante Informationen und Assets auf der Login-Seite entfernt, wenn Sie nicht angemeldet sind.
Dieses Standard Backend verfügt über eine eigene styles Auszeichnung, analog zur "default/admin/styles.css" Datei, die folgende, fortlaufend aktualisierte Verbesserungen im Backend vornimmt:
<pre>
* 2017-08-21 - Neu hinzugekommen ist ein Bugfix für die Backend-Ansichten mit "rtl" (right-to-left), auf rechts gedrehtes Schrift-Attribut im &lt;html&gt; Element.
* 2019-09-08 - Die IE8/9 workarounds wurden entfernt.
* 2020-03-25 - Hervorhebung der Styx message styles.
* 2020-06-06 - Verkleinertes font-size der entry_info styles der Eintrags Liste.
* 2020-08-31 - Verbesserung der Login Seite und Update des fullsize preview.
* 2020-10-31 - SVG icon für die eingesprungenen Plugin fieldset legends des Eintragsformulares.
* 2020-12-06 - Erlaube Media Screen iPhone 5/SE mit 320px Größen für media filter HTML datetime Felder.
* 2021-02 / 2021-03 - Kleinere Verbesserungen im den CSS styles.
* 2021-03-26 - Entries/Comments Filter Toolbars werden ab 360px Screen Breiten nach rechts verschoben.
* 2021-03-29 - Erweiterung mit rechts gefloateten Toolbar Gruppen für Plugins/Themes/Eigene-Einstellungen mit "all" klappbaren Konfigurations Gruppen Containern.
* 2021-04-01 - Cleanup für media inputs in Konfigurationsseiten.
* 2021-04-28 - Mozilla: Fix des rechts fehlenden blau-schimmer Schattens bei bestimmten Klapp-bar-Buttons
* 2021-05-05 - Verbesserte Eingabe-/Radiofelder für die (blaue) Standard-Mozilla(89+)/Chromium-Darstellung
* 2021-06-19 - Styx Dark Mode
* 2021-12-06 - Erweiterung für Styx login Page Dark Mode
* 2022-07-15 - Verbesserte media properties styles inclusive EXIF data und responsive fluids
</pre>

DAS FOLGENDE IST DIE BESCHREIBUNG DES FALLBACK BACKENDS&colon;<br>
Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "default/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfläche.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden möchten, kopieren Sie das Verzeichnis "admin" in ihr Theme.
Ändern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzufügen, und wählen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort können Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bedürfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur für erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!';
