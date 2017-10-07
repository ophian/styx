<?php
/*
 * The info.txt charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['languages'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset
 **/

$info['theme_info_summary'] = 'Das Styx Backend/Frontend Standard Theme';

$info['theme_info_desc'] = 'Dieses Theme definiert den augenblicklichen Standard, der im Serendipity Styx Kern definiert und benutzt wird.
In Bezug auf die schier unendlichen Möglichkeiten von Serendipity, ist es das augenblicklich am besten verknüpfte und verzahnte Theme,
welches die meisten dieser Möglichkeiten in einfacher Weise innerhalb des eigenen Rahmens abdeckt, so dass man es leicht durchschauen kann.
<br><br>
<u><b>Eigenes Theme erstellen</b></u><br>
Kopieren Sie ein vorhandenes Theme oder fügen Sie einen neuen und eindeutigen Verzeichnisnamen in das Verzeichnis "templates/" ein, zB. "example".
Fügen Sie eine <b>info.txt</b> Datei mit den Komponenten von 2k11 hinzu, um damit zu beginnen.
Ändern Sie den Namen, zB. zu "mein Example" und fügen Sie das aktuelle Datum hinzu.<br>
Wenn Sie ihr neues Theme auf 2k11 zurückgreifen lassen wollen, geben Sie der info.txt Datei nun eine "Engine&colon; 2k11"-Zeile hinzu.
Wenn Sie diese Zeile nicht nutzen, muss das eigene Theme entweder alle Template-Dateien selbst enthalten, oder ihr Theme wird auf das "Serendipity Default" Template zurückgreifen.<br><br>
Setzen Sie ensprechende Zeilen "Require Serendipity&colon; 2.0" für eine Voraussetzung, "Backend&colon; Yes",
wenn Sie ein eigenes Backend nutzen und "Recommended&colon; Yes", wenn Sie es zur empfohlenen Theme-Sektion hinzufügen wollen.<br><br>
Zurück in der Template-Liste, laden Sie die Seite neu und wählen Sie ihr neues Theme anhand des gegebenen Namens.';

$info['theme_info_backend'] = 'Dieses Theme beherbergt die Styx-Kern Backend Templates im Unterverzeichnis "2k11/admin".
Die Template-Dateien in diesem Verzeichnis bilden und erstellen das Aussehen der kompletten Admin-Oberfläche.
Sie enthalten auch einige Workflow- und Logikfunktionen sowie eigene Javascript-Bibliotheken.
Wenn Sie ein eigenes Backend-Theme verwenden möchten, kopieren Sie das Verzeichnis "admin" in ihr Theme.
Ändern Sie dort die Datei "info.txt", um eine Zeile "Backend&colon; Yes" hinzuzufügen, und wählen Sie das neue Backend-Theme in der neu geladenen Themenliste aus.
Ab sofort können Sie die Dateien und Stile des eigenen Backend-Themes bearbeiten und an Ihre erweiterten Bedürfnisse anpassen.<br>
<u><b>Bitte beachten Sie:</b></u> Dies ist nur für erfahrene Benutzer empfehlenswert und komplett abseits der Update-Funktionen!';
