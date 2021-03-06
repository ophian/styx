<?php
// About
@define('THEME_ABOUT', '<div class="template_about_box"><h3>Willkommen bei Sliver!</h3>Dieses Template nutzt HTML5 Techniken (boilerplate), wie Semantik und CSS3 Stylesheets und kommt mit einem aktivierten JQuery Script, damit Javascript (js/script.js) in den Templates genutzt werden kann.<ul><li>Einstellung des gew�nschten Seiten-Layout mittels Seitenleisten rechts, links und Einspaltig hier in dieser Template-Konfiguration.</li><li>Einstellung zus�tzlicher Seitenleisten oben, mittig und unten in der Serendipity Plugin Section.</li><li>Auswahl einer der voreingestellten Navigations Button Styles.</li></ul></div>');
// Stylesheet
@define('USER_STYLESHEET', 'Zus�tzliches Benutzerstylesheet einbinden.');
@define('USER_STYLESHEET_BLAHBLAH', 'Dieses Stylesheet muss vom Benutzer im Template-css-Verzeichnis angelegt werden. Es muss user.css hei�en und kann benutzt werden, um ausgew�hlte Styles zu �berschreiben.');
// Layout
@define('LAYOUT_TYPE', 'Layout des Blogs (B = Blogeintr�ge, S = Seitenleiste, CF = Content first)');
@define('LAYOUT_SB', 'Zweispaltig, Seitenleiste links');
@define('LAYOUT_BS', 'Zweispaltig, Seitenleiste rechts, CF');
@define('LAYOUT_SC', 'Einspaltig, Seitenleiste(n) unten, CF');
// Fahrner Image Replacement
@define('FIR_BTITLE', 'Blogtitel im Header anzeigen');
@define('FIR_BDESCR', 'Blogbeschreibung im Header anzeigen');
// Date format
@define('BP_DATE_FORMAT', 'Datumsformat');
// fonts
@define('SLIVER_WEBFONTS', 'Einen von Google gehosteten Webfont nutzen?');
@define('SLIVER_NOWEBFONT', 'Keinen Webfont einbinden');
// Entry footer
@define('ENTRY_FOOTER_POS', 'Position des Eintragsfu�es');
@define('BELOW_ENTRY', 'Unter dem Eintrag');
@define('BELOW_TITLE', 'Unter dem Titel des Eintrags');
@define('SPLIT_FOOTER', 'Aufgeteilter Eintragsfu�');
@define('FOOTER_AUTHOR', 'Verfasser im Eintragsfu� anzeigen');
@define('FOOTER_SEND2PRINTER', '"Sende an Drucker" im Eintragsfu� anzeigen');
@define('SEND2_PRINTER', 'Drucken');
@define('FOOTER_CATEGORIES', 'Kategorie(n) im Eintragsfu� anzeigen');
@define('FOOTER_TIMESTAMP', 'Zeitstempel im Eintragsfu� anzeigen');
@define('FOOTER_COMMENTS', 'Anzahl der Kommentare im Eintragsfu� anzeigen');
@define('FOOTER_TRACKBACKS', 'Anzahl der Trackbacks im Eintragsfu� anzeigen');
@define('ALT_COMMTRACK', 'Alternative Darstellung der Anzahl der Kommentare und Trackbacks benutzen');
@define('ALT_COMMTRACK_BLAHBLAH', '(z.B. "Keine Kommentare" bzw. "1 Kommentar" statt "Kommentare(0)" bzw. "Kommentare(1)")');
@define('SHOW_STICKY_ENTRY_FOOTER', 'Eintragsfu� f�r dauerhafte Eintr�ge anzeigen');
@define('SHOW_STICKY_ENTRY_HEADING', 'Eintragstitel f�r dauerhafte Eintr�ge anzeigen');
@define('SHOW_STICKY_ENTRY_BLAHBLAH', '(ben�tigt das Plugin "Erweiterte Eigenschaften von Artikeln")');
// Page footer next page  and previous page links
@define('PREV_NEXT_STYLE', 'Links zur Seitenpagination im Seitenfu� anzeigen als');
@define('PREV_NEXT_TEXT', 'Nur Text');
@define('PREV_NEXT_TEXT_ICON', 'Text und Icon');
@define('PREV_NEXT_ICON', 'Nur Icon');
@define('SHOW_PAGINATION', 'Zus�tzliche Seitennummerierung (Pagination) anzeigen');
// Counter code
@define('COUNTER_CODE', 'Code f�r Counter und/oder Statistik-Tools einf�gen');
@define('USE_COUNTER', 'Oben eingegeben Counter-Code in das Blog einbinden');
// Additional footer text
@define('FOOTER_TEXT', 'Hier zus�tzlichen Text, der im Seitenfuss erscheinen soll, einf�gen.');
@define('USE_FOOTER_TEXT', 'Oben eingegebenen Text einbinden');
// jquery support
@define('SLIVERS_JQUERY', 'Slivers jQuery nutzen?');
@define('SLIVERS_JQUERY_BLAHBLAH', 'Bindet Slivers und/oder ajax.googleapis.com jquery.min.js ein');
// code prettify support
@define('SLIVERS_PRETTIFY', 'Slivers Code Prettify nutzen?');
@define('SLIVERS_PRETTIFY_BLAHBLAH', 'Bindet lokales prettify.js (minifizierter Google Originalcode). Nutze als "%s"');
// google analytics support
@define('GOOGLE_ANALYTICS', 'Google Analytics nutzen?');
@define('GOOGLE_ANALYTICS_BLAHBLAH', 'Bindet das google-analytics.com/ga.js Script ein');
@define('GOOGLE_ANALYTICS_ID', 'Google Analytics ID');
// Navigation
@define('USE_CORENAV', 'Benutze globale Navigation?');
// Sitenav
@define('SITENAV_POSITION', 'Darstellung der Navigationsleiste');
@define('SITENAV_BLAHBLAH', 'Gemeinhin wird das Static Pages Plugin (serendipity_event_staticpage) dazu benutzt, um CMS �hnliche Seiten oder Navigationsseiten herzustellen, die hier durch einen Link verlinkt werden k�nnen.');
@define('SITENAV_NONE', 'Keine Navigationsleiste');
@define('SITENAV_ABOVE', '�ber dem Kopfbereich');
@define('SITENAV_BELOW', 'Unter dem Kopfbereich');
@define('SITENAV_LEFT', 'Oben in der linken Seitenleiste');
@define('SITENAV_RIGHT', 'Oben in der rechten Seitenleiste');
@define('SITENAV_FOOTER', 'Links der Navigationleiste zus�tzlich im Seitenfuss anzeigen');
@define('SITENAV_FOOTER_BLAHBLAH', '(werden nicht angezeigt, wenn oben "Keine Navigationsleiste" ausgew�hlt wurde)');
@define('SITENAV_QUICKSEARCH', 'Suchfeld in der Navigationsleiste anzeigen');
@define('SITENAV_QUICKSEARCH_BLAHBLAH', '(funktioniert nur, wenn Navigationsleiste �ber oder unter dem Kopfbereich; Anzeige des entsprechenden Seitenleistenplugins wird automatisch unterdr�ckt)');
@define('SITENAV_TITLE', 'Titel des Navigations-Men�s');
@define('SITENAV_TITLE_BLAHBLAH', '(nur bei Anzeige in der Seitenleiste)');
@define('SITENAV_TITLE_TEXT', 'Hauptmen�');

@define('ARCHIVE_TEXT_INTRO', 'Die Archive im Sliver Template bieten verschiedene M�glichkeiten �ltere Inhalte zu finden. Blog-Eintr�ge sind in <a href="#bycats">Kategorien</a> (Anzahl der Eintr�ge je Kategorie in Klammern) eingeordnet und mit der H�ufigkeit des Vorkommens nach gewichteten <a href="#bytags">Tags</a> versehen, zudem gibt es ein nach <a href="#bydate">Datum</a> geordnetes Archiv.');
@define('ARCHIVE_TEXT_ADD', ''); // disable this empty one to use next
#@define('ARCHIVE_TEXT_ADD', 'Au�erdem gibt es noch das <a href="%spages/xxx.html"> XXX </a>, in dem ich lesenswerte Artikel aus dem Weblog oder anderen Seiten zu ausgew�hlten Themen gruppiert aufliste.');
@define('ARCHIVE_TEXT_YEARMONTH', 'Die hier verlinkten Archivseiten zeigen eine Auflistung der Eintr�ge in den betreffenden Monaten an.');
@define('ARCHIVE_TEXT_SUMMARY', 'Archiv�bersicht');
/* Additional sidebars */
@define('TOP', 'oben');
@define('FOOTER', 'unten');
@define('MIDDLE', 'inmitten');
/* Navigation styles */
@define('SITENAV_STYLE', 'W�hle die Navigationsleisten-CSS-Button-Styles.');
@define('SITENAV_STYLE_BLAHBLAH', 'Dies gilt nur die Buttons �ber oder unter dem Kopfbereich, nicht in den Seitenleisten. Default = nutze default CSS mit Hintergrundbild (id:#sitenav) - Einfach = nutze einfaches CSS style (id:#nav). - Erweitert = nutze erweiterte Styles mit CSS3 Techniken (id:#sitenav-extended).');
@define('SITENAV_SLIM', 'einfach');
@define('SITENAV_EXTENDED', 'erweitert');
/* Config groups */
@define('THEME_WELCOME', 'Willkommen');
@define('THEME_LAYOUT', 'Gestaltung');
@define('THEME_ENTRIES', 'Eintr�ge');
@define('THEME_SITENAV', 'Navigation');
@define('THEME_NAV', 'Navigationseintr�ge');

