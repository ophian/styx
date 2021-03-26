<?php
/*
 * The info.txt UTF-8 charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as UTF-8 file without BOM.
 **/

$info['theme_info_summary'] = 'Das Serendipity Styx Basis-Template.';

$info['theme_info_desc'] = 'Als HTML5 Frontend Theme neu überarbeitet für Styx um es voll "Responsiv" zu machen (3-2-1),
ohne dabei allzuviel von seinem alten HTML(4) Markup zu ändern.<br>
Es arbeitet als vollwertiges "Fallback" für die PHP- und XML-Engine und als allgemeiner Datei-Reserve-Pool für alle sonstigen Themes.<br>
Für Backend-Infos konsultieren Sie die augenblicklichen Backend Infos.<br>
<br>
Im Unterschied zu den Serendipity <b>Standard</b> Templates (früher "Bulletproof", später "2k11", jetzt "Pure &lsaquo; 2020 &rsaquo;") dient dieses Theme als grundlegende System Basis
und als allgemeines Backup- und "Rückfall"-Theme, solange nicht spezielle Anweisungen (*) oder interne Gründe etwas anderes erzwingen,
zB. wenn etwas zur Smarty-Kompilierung gesucht und nicht in der üblichen Theme oder Fallback-Theme-Kaskade gefunden wurde.<br>
<br>
<span class="footnote">[*] Das heißt, wenn keine "Engine" (in der info.txt) und keine eigene Serendipity Stylesheet-Datei (style.css)
gesetzt sind, wie es zB. für das "Default-php" oder "Default-xml" Theme der Fall ist.</span>';

$info['theme_info_backend'] = 'Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "default/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfläche.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden möchten, kopieren Sie das Verzeichnis "admin" in ihr Theme.
Ändern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzufügen, und wählen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort können Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bedürfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur für erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!';
