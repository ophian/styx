<?php
// General
@define('ERROR_404', 'Fehler 404 - Die angeforderte Seite wurde nicht gefunden.');
@define('SEARCH_WHAT', 'Wonach soll gesucht werden?'); //used on quicksearch modal
@define('SEARCH', 'Suche');
@define('TOGGLE_NAV', 'Navigation'); //only seen by screen readers
@define('CLOSE', 'Schlie�en'); //close button on search form
@define('READ_MORE', 'Mehr lesen');
@define('FOOTER', 'unten');

//Option groups and instructions
@define('THEME_SOCIAL_LINKS', 'Social-Links');
@define('THEME_PAGE_OPTIONS', 'Seiten-Optionen');
@define('THEME_NAVIGATION', 'Navigations-Optionen');
@define('THEME_README', 'Lies mich');
@define('THEME_IDENTITY', 'Seiten-Identit�t');
@define('THEME_EP_YES', '<p class="msg_success">Das Plugin "Erweiterte Eigenschaften von Artikeln" (serendipity_event_entryproperties) wird ben�tigt. Es ist installiert und aktiv.</p>');
@define('THEME_EP_NO', '<p class="msg_error">Das Plugin "Erweiterte Eigenschaften von Artikeln" (serendipity_event_entryproperties) wird ben�tigt. Es ist entweder nicht installiert oder inaktiv. Bitte das Plugin installieren, um alle Features dieses Themes voll zu nutzen.</p>');
@define('THEME_INSTRUCTIONS', '<p>Dieses Theme zeigt Blogbeitr�ge auf einer linearen Zeitleiste an. Jede Gruppe von Monatsbeitr�gen kann auch auf der Zeitleiste angezeigt oder ausgeblendet werden.</p>
<p>Dieses Theme verwendet eine rechte und eine untere Seitenleiste. Eine oder beide Seitenleisten k�nnen deaktiviert werden, indem Sie die Seitenleisten-Plugins l�schen oder die Seitenleisten-Plugins in die Spalte "versteckt" in der Plugin-Konfiguration verschieben.</p>
<p>Dieses Theme kann so konfiguriert werden, dass Kategorien und Eintrags-Tags aus den jeweiligen Sidebar-Plugins auf der Archivseite angezeigt werden. Siehe "' . THEME_PAGE_OPTIONS . '" unten.</p>
<p>Wenn Sie das Avatar-Plugin (serendipity_event_gravatar) verwenden, konfigurieren Sie die Option "Smarty-Tag erzeugen = ja" f�r eine bessere Darstellung von Kommentar-Avataren.</p>
<p>Konfigurieren Sie die serendipity_event_freetag-Option "Erweitertes Smarty = ja" f�r eine sch�nere Darstellung von Tags in der Fu�zeile des Eintrags.</p>');
@define('THEME_CUSTOM_FIELD_HEADING', 'Freie Felder f�r Eintr�ge');
@define('THEME_CUSTOM_FIELD_DEFINITION', 'Diese optionalen Felder sind nur bei Verwendung dieses Themes (timeline) gesetzt. Das Event-Plugin serendipity_event_entryproperties ("Erweiterte Eigenschaften von Artikeln") muss ebenfalls installiert sein, um diese Felder zu verwenden. Das Eintragsbild wird sowohl in der Zeitleiste als auch oben in jedem detaillierten Eintrag angezeigt.');
@define('THEME_ENTRY_IMAGE', 'Zugeordnetes Eintrags Bild.');
@define('THEME_DEMO_AVAILBLE', 'Eine komplette englische Anleitung zur <a href="http://www.optional-necessity.com/demo/timeline/archives/13-Using-the-Timeline-theme.html">Konfiguration und Benutzung von Timeline</a> findet sich in der <a href="http://www.optional-necessity.com/demo/timeline/">Timeline theme demo</a>.');

//Navigation
@define('USE_CORENAV', 'Globale Navigation verwenden?');

//Page Options
@define('USE_GOOGLEFONTS', 'Google Webfonts einbinden?');
@define('THEME_COLORSET', 'Farbsystem');
@define('THEME_SKINSET', 'Theme-Skin');
@define('HEADER_IMG', 'Optionales Kopfzeilenbild. Leer f�r Blognamen');
@define('HEADER_IMG_DESC', 'Empfohlene Gr��e des Logo Header-Bildes: 150 x 40px. Wird nur angezeigt, wenn "' . USE_CORENAV . '" auf "'. YES .'" steht.');
@define('SUBHEADER_IMG', 'Optionales Subheader Hintergrund-Bild');
@define('SUBHEADER_IMG_DESC', 'Dieses Hintergrundbild in voller Breite erscheint unterhalb des Header-Logos und der Site-Navigation.');
@define('HEADERS_USE_VARIATION', 'Ausgabe als Bild-Variations Format, wenn erh�ltlich?');
@define('ENTRY_DATE_FORMAT', 'Datumsformat f�r Eintr�ge');
@define('COMMENT_TIME_FORMAT', 'Zeitformat f�r Kommentare und Trackbacks');
@define('WORDS', 'Textfassung');
@define('TIMESTAMP', 'Zeitstempel');
@define('DISPLAY_AS_TIMELINE', 'Timeline-Format verwenden');
@define('DISPLAY_AS_TIMELINE_DESC', 'Timeline-Format f�r Blog-Posts verwenden. Falls Nein werden Blog-Posts im �blichen Blog-Format ausgegeben.');
@define('MONTHS_ON_TIMELINE', 'Monatsnamen auf der Zeitleiste anzeigen');
@define('MONTHS_ON_TIMELINE_DESC', 'Der Monatsname wird als �berschrift auf der Zeitleiste f�r jeden Monat der Eintr�ge angezeigt.');
@define('MONTHS_ON_TIMELINE_FORMAT', 'Timeline Monatsformat');
@define('CATEGORIES_ON_ARCHIVE', 'Kategorien auf der Archivseite anzeigen');
@define('CATEGORIES_ON_ARCHIVE_DESC', 'Das Seitenleisten-Plugin "Kategorien" (serendipity_plugin_categories) muss installiert und die Option "Smarty-Templating aktivieren?" aktiviert sein, damit Kategorien auf der Archiv-Seite angezeigt werden.');
@define('CATEGORY_RSS_ON_ARCHIVE', 'RSS-Symbol neben jeder Kategorie auf der Archivseite anzeigen');
@define('TAGS_ON_ARCHIVE', 'Tags auf der Archivseite anzeigen');
@define('TAGS_ON_ARCHIVE_DESC', 'Das Seitenleisten-Plugin "Getaggte Artikel" (serendipity_plugin_freetag) muss installiert sein und die Option "Sidebar template" muss auf "archive_freetag.tpl" gesetzt werden, damit Tags auf der Archiv-Seite angezeigt werden.');

//Social media
@define('SOCIAL_ICONS_AMOUNT', 'Anzahl der Links zu sozialen Medien eingeben');
@define('SOCIAL_NETWORK_SERVICE', 'Social-Media-Dienst f�r Link ausw�hlen');
@define('SOCIAL_ICON_URL', 'URL f�r Social-Media-Dienst-Link');
@define('COPYRIGHT', 'Copyright');

//time ago in words function
@define('ELAPSED_LESS_THAN_MINUTE_AGO', 'Vor weniger als einer Minute');
@define('ELAPSED_ONE_MINUTE_AGO', 'Vor einer Minute');
@define('ELAPSED_ONE_DAY_AGO', 'Vor einem Tag');
@define('ELAPSED_MINUTES_AGO', 'Vor %s Minuten');
@define('ELAPSED_HOURS_AGO', 'Vor %s Stunden');
@define('ELAPSED_DAYS_AGO', 'Vor %s Tagen');
@define('ELAPSED_MONTHS_AGO', 'Vor %s Monaten');
@define('ELAPSED_YEARS_AGO', 'Vor %s Jahren'); //not currently using this, but defining just in case
@define('ELAPSED_ABOUT_ONE_HOUR_AGO', 'Vor ungef�hr einer Stunde'); // greater than 45 minutes, less than 90 minutes
@define('ELAPSED_ABOUT_ONE_MONTH_AGO', 'Vor ungef�hr einem Monat'); // greater than 30 days, less than 60 days
@define('ELAPSED_ABOUT_ONE_YEAR_AGO', 'Vor ungef�hr einem Jahr'); // greater than one year, less than 2 years
@define('ELAPSED_OVER_YEARS_AGO', 'Vor mehr als %s Jahren');// greater than 2 years

//Static Pages
@define('STATIC_SHOW_AUTHOR_TEXT', 'Autorennamen anzeigen');
@define('STATIC_SHOW_DATE_TEXT', 'Datum anzeigen');
@define('STATIC_SHOW_SIDEBARS_TEXT', 'Seitenleisten anzeigen?');

