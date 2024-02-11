<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details
# Translation (c) by Jo Christian Oterhals <oterhals@gmail.com>
/* vim: set sts=4 ts=4 expandtab : */

@define('LANG_CHARSET', 'ISO-8859-1');
@define('SQL_CHARSET', 'latin1');
@define('DATE_LOCALES', 'no_NO.ISO-8859-1, no_NO.ISO8859-1, norwegian, no, no_NO, no_');
@define('DATE_FORMAT_ENTRY', '%A - %e. %B %Y');
@define('DATE_FORMAT_SHORT', '%d.%m.%Y %H:%M');
@define('WYSIWYG_LANG', 'no_NO');
@define('LANG_DIRECTION', 'ltr');

@define('SERENDIPITY_ADMIN_SUITE', 'Serendipity Styx Administrasjonssuite');
@define('HAVE_TO_BE_LOGGED_ON', 'Du m� v�re logget inn for � kunne se denne siden');
@define('WRONG_USERNAME_OR_PASSWORD', 'Du har tastet inn feil brukernavn eller passord');
@define('APPEARANCE', 'Utseende');
@define('MANAGE_STYLES', 'Administrer temaer');
@define('CONFIGURE_PLUGINS', 'Konfigurerer plugins');
@define('CONFIGURATION', 'Konfigurasjon');
@define('BACK_TO_BLOG', 'Tilbake til weblogg');
@define('LOGIN', 'Logg inn');
@define('LOGOUT', 'Logg ut');
@define('LOGGEDOUT', 'Logget ut.');
@define('CREATE', 'Opprett');
@define('SAVE', 'Lagre');
@define('CREATE_NEW_CAT', 'Oppret ny kategori');
@define('I_WANT_THUMB', 'Jeg vil bruke minibildet i min artikkel.');
@define('I_WANT_BIG_IMAGE', 'Jeg vil bruke det originale bildet i min artikkel.');
@define('I_WANT_NO_LINK', 'Vis bildet uten link');
@define('I_WANT_IT_TO_LINK', 'Vis bildet med link');
@define('BACK', 'Tilbake');
@define('FORWARD', 'Frem');
@define('ANONYMOUS', 'Anonym');
@define('NEW_TRACKBACK_TO', 'Ny trackback opprettet til');
@define('NEW_COMMENT_TO', 'Ny kommentar opprettet til');
@define('RECENT', 'Nyere...');
@define('OLDER', 'Eldre...');
@define('DONE', 'Ferdig');
@define('WELCOME_BACK', 'Velkommen tilbake,');
@define('TITLE', 'Tittel');
@define('DESCRIPTION', 'Beskrivelse');
@define('PLACEMENT', 'Plassering');
@define('DELETE', 'Slett');
@define('OPEN', 'Open');
@define('CLOSE', 'Close');
@define('UP', 'OPP');
@define('DOWN', 'NED');
@define('ENTRIES', 'artikler');
@define('NEW_ENTRY', 'Ny artikkel');
@define('EDIT_ENTRIES', 'Rediger artikler');
@define('CATEGORIES', 'Kategorier');
@define('IMAGESYNC_WARNING', 'ADVARSEL:<br>Dette kan ta lang tid hvis det er mange bilder uten minibilder. Particularly with migrations of old blogs, further preliminary considerations and knowledge are necessary! Read about it on <a href="https://ophian.github.io/hc/en/media-migration-tasks.html" target="_new">this help page</a>, carefully.');
@define('CREATE_THUMBS', 'Lag minibilder p� nytt');
@define('MANAGE_IMAGES', 'Administrer bilder');
@define('NAME', 'Navn');
@define('EMAIL', 'Email');
@define('HOMEPAGE', 'Hjemmeside');
@define('COMMENT', 'Kommentar');
@define('REMEMBER_INFO', 'Husk opplysninger?');
@define('SUBMIT_COMMENT', 'Legg til kommentar');
@define('NO_ENTRIES_TO_PRINT', 'Der er ingen artikler � vise');
@define('COMMENTS', 'Kommentarer');
@define('ADD_COMMENT', 'Legg til kommentar');
@define('NO_COMMENTS', 'Ingen kommentar');
@define('POSTED_BY', 'Skrevet av');
@define('ON', 'aktiv');
@define('A_NEW_COMMENT_BLAHBLAH', 'En ny kommentar har blitt opprettet p� din blog "%s", til artiklen med navnet "%s".');
@define('A_NEW_TRACKBACK_BLAHBLAH', 'En ny trackback har blitt laget til din artikkel ved navn "%s".');
@define('NO_CATEGORY', 'Ingen kategori');
@define('ENTRY_BODY', 'Artikkelinnhold');
@define('EXTENDED_BODY', 'Utvidet innhold');
@define('CATEGORY', 'Kategori');
@define('EDIT', 'Rediger');
@define('NO_ENTRIES_BLAHBLAH', 'Ingen artikler ble funnet som inneholdt %s');
@define('YOUR_SEARCH_RETURNED_BLAHBLAH', 'Ditt s�k p� %s retunerte %s resultater');
@define('IMAGE', 'Bilde');
@define('ERROR_FILE_NOT_EXISTS', 'Feil: Det gamle filnavnet finnes ikke!');
@define('ERROR_FILE_EXISTS', 'Feil: Det nye filnavnet brukes allerede, velg et andet!');
@define('ERROR_SELECTION', 'Error: Changing both selection in media properties at the same time is not allowed. Go back and try again!');
@define('ERROR_SOMETHING', 'Feil: Der gikk noe galt');
@define('ADDING_IMAGE', 'Legg til et bilde...');
@define('THUMB_CREATED_DONE', 'Minibilde %s er oprettet.<br>Utf�rt.'); // ADD: and of all variations
@define('ERROR_FILE_EXISTS_ALREADY', 'Feil: Filen finnes allerede p� din maskin!');
@define('NOT_AVAILABLE', 'N/A'); // short!
@define('USER_ACTION', 'User action');
@define('MANDATORY', '[ mandatory ]');
@define('OPTIONAL', '[ optional ]');
@define('GO', 'Begynn!');
@define('NEWSIZE', 'Ny st�rrelse: ');
@define('RESIZE_BLAHBLAH', 'Endre st�rrelse p� %s');
@define('ORIGINAL_SIZE', 'Original st�rrelse: <i>%sx%s</i> pixel');
@define('HERE_YOU_CAN_ENTER_BLAHBLAH', 'Her kan du justere bildets st�rrelse. Hvis du vil bevare proporsjonene, beh�ver du bare taste en av verdiene og trykke p� TAB-tasten. Jeg vil automatisk beregne den andre verdien.<br><b>PLEASE NOTE:</b> This is not a high end image editor resizing tool, finetuned for the need of a specific image.<br>Every scale returns with a more or less increasing loss of image quality compared to the origin input file. And this increases with each further scaling!<br><b>VARIATION:</b> Since we assume you <b>keep</b> the files proportion, a scaled image "format" variation ["image.avif" and/or "image.webp"] change will be applied to the Origin files variation only and <b>NOT</b> to the variation thumbnail, which - by certain image property conditions - would probably blow up its filesize. If you really need an image scale with an <b>other</b> proportion <b>and</b> an additional changed variation thumb dimension size, activate the "<em>..thumb variation</em>" checkbox.');
@define('SCALE_THUMB_VARIATION', 'Force scaled thumb variation changes');
@define('QUICKJUMP_CALENDAR', 'Hurtigkalender');
@define('QUICKSEARCH', 'S�k');
@define('SEARCH_FOR_ENTRY', 'S�k etter en artikkel');
@define('ARCHIVES', 'Arkiver');
@define('BROWSE_ARCHIVES', 'Bla gjennom arkiv etter m�neder');
@define('TOP_REFERRER', 'Referanser');
@define('SHOWS_TOP_SITES', 'Viser de sitene som oftest linker til din blog');
@define('TOP_EXITS', 'Exit-sider');
@define('SHOWS_TOP_EXIT', 'Viser de exit-sider det har blitt klikket flest ganger p�');
@define('SYNDICATION', 'Syndikering');
@define('SHOWS_RSS_BLAHBLAH', 'Viser RSS syndikeringslinker');
@define('ADVERTISES_BLAHBLAH', 'Reklamer for din blogs opprinnelse');
@define('HTML_NUGGET', 'HTML Nugget');
@define('HOLDS_A_BLAHBLAH', 'Inneholder en bit HTML i din sidebar');
@define('TITLE_FOR_NUGGET', 'Tittel p� din nugget');
@define('THE_NUGGET', 'HTML-innholdet av din nugget');
@define('SUBSCRIBE_TO_BLOG', 'Syndiker denne bloggen');
@define('YOU_CHOSE', 'Du valgte %s');
@define('FILE_SIZE', 'File size');// keep short!
@define('IMAGE_SIZE', 'Bildest�rrelse');
@define('IMAGE_AS_A_LINK', 'Sett inn bilde');
@define('POWERED_BY', 'Drives av');
@define('TRACKBACKS', 'Trackbacks');
@define('TRACKBACK', 'Trackback');
@define('NO_TRACKBACKS', 'Ingen Trackbacks');
@define('TOPICS_OF', 'Emner av');
@define('VIEW_FULL', 'Vis fullt');
@define('VIEW_TOPICS', 'Vis temaer');
@define('AT', 'kl');
@define('SET_AS_TEMPLATE', 'Sett som mal');
@define('IN', 'i');
@define('EXCERPT', 'Utdrag');
@define('TRACKED', 'Tracked');
@define('LINK_TO_ENTRY', 'Link til artikkel');
@define('LINK_TO_REMOTE_ENTRY', 'Link til ekstern artikkel');
@define('IP_ADDRESS', 'IP-adresse');
@define('USER', 'Bruker');
@define('THUMBNAIL_USING_OWN', 'Bruker %s i seg selv som minibilde, fordi det allerede er s� lite.');
@define('THUMBNAIL_FAILED_COPY', 'Ville gjerne ha brukt %s som minibilde i seg selv, men kunne ikke kopiere!');
@define('AUTHOR', 'Forfatter');
@define('LAST_UPDATED', 'Sist opdatert');
@define('TRACKBACK_SPECIFIC', 'Trackback URI til denne artikkel');
@define('DIRECT_LINK', 'Direktelink til denne artikkel');
@define('COMMENT_ADDED', 'Din kommentar %sble lagt til.');
@define('COMMENT_ADDED_CLICK', 'Klik %her for � g� tilbake%s til kommentarene, og %sher for � lukke%s dette vinduet.');
@define('COMMENT_NOT_ADDED_CLICK', 'Klikk %sher for � g� tilbake%s til kommentarene, og %sher for � lukke%s dette vinduet. ');
@define('COMMENTS_DISABLE', 'Tillat ikke kommentarer til denne artikkelen');
@define('COMMENTS_ENABLE', 'Tillat kommentarer til denne artikkelen');
@define('COMMENTS_CLOSED', 'Forfatteren har valgt ikke � tillate kommentarer til denne artikkelen');
@define('EMPTY_COMMENT', 'Din kommentar indeholdt ikke noe, vennligst g� %stilbage%s og pr�v igjen');
@define('ENTRIES_FOR', 'Artikler fra %s');
@define('DOCUMENT_NOT_FOUND', 'Dokumentet %s kunne ikke finnes.');
@define('USERNAME', 'Brukernavn');
@define('PASSWORD', 'Passord');
@define('AUTOMATIC_LOGIN', 'Lagre oplysningerne');
@define('SERENDIPITY_INSTALLATION', 'Serendipity Installasjon');
@define('LEFT', 'venstre');
@define('RIGHT', 'h�yre');
@define('HIDDEN', 'skjult');
@define('REMOVE_TICKED_PLUGINS', 'Fjern valgte plugins');
@define('SAVE_CHANGES_TO_LAYOUT', 'Lagre layout-endringene');
@define('REQUIRED_FIELD', 'Required field');
@define('COMMENTS_FROM', 'Kommentar fra');
@define('ERROR', 'Feil');
@define('ENTRY_SAVED', 'Din artikkel ble lagret');
@define('DELETE_SURE', 'Er du sikker p� du vil slette #%s permanent?');
@define('NOT_REALLY', 'Egentlig ikke...');
@define('DUMP_IT', 'Kast den!');
@define('RIP_ENTRY', 'Farvel artikkel #%s');
@define('CATEGORY_DELETED_ARTICLES_MOVED', 'Kategori #%s ble slettet. Gamle artikler flyttet til kategori #%s');
@define('CATEGORY_DELETED', 'Kategori #%s ble slettet.');
@define('INVALID_CATEGORY', 'Ingen kategori ble valgt for sletting');
@define('CATEGORY_SAVED', 'Kategori lagret');
@define('SELECT_TEMPLATE', 'Velg den malen du �nsker � bruke til din blog');
@define('ENTRIES_NOT_SUCCESSFULLY_INSERTED', 'Det var problemer under innsettelsen av artiklene!');
@define('YES', 'Ja');
@define('NO', 'Nei');
@define('USE_DEFAULT', 'Default');
@define('CHECK_N_SAVE', 'Sjekk &amp; lagre');
@define('DIRECTORY_NON_EXISTENT', 'Directory %s does not exist. Maybe is a virtual redirector directory.');
@define('DIRECTORY_WRITE_ERROR', 'Kan ikke skrive til biblioteket %s. Sjekk filrettighederne.');
@define('DIRECTORY_CREATE_ERROR', 'Biblioteket %s eksisterer ikke og kunne ikke op1prettes. Vennligst opprett biblioteket manuelt');
@define('DIRECTORY_RUN_CMD', '&nbsp;-&gt; Kj�r <i>%s %s</i>');
@define('CANT_EXECUTE_BINARY', 'Kan ikke eksekvere %s');
@define('CANT_EXECUTE_EXTENSION', 'Cannot execute the %s extension library. Please allow in PHP.ini or load the missing module via servers package manager.');
@define('FILE_WRITE_ERROR', 'Kan ikke skrive til filen %s.');
@define('FILE_CREATE_YOURSELF', 'Venligst opprett filen selv og sjekk dens rettigheter');
@define('COPY_CODE_BELOW', '<br>* Kopier koden nedenunder og plasser den i %s i din %s mappe:<b><pre>%s</pre></b>' . "\n");
@define('WWW_USER', 'Endre www til den bruker som apache k�rer som (fx. nobody).');
@define('BROWSER_RELOAD', 'N�r du har gjort dette, s� tryk p� browserens "oppdater"-knapp');
@define('RELOAD_THIS_PAGE', 'Please reload this <a href="%s">%s</a> page to fetch the changed values before submitting again!');
@define('DIAGNOSTIC_ERROR', 'Vi har opdaget noen feil mens vi k�rte noen diagnostikker p� dine oppgitte informasjoner:');
@define('SERENDIPITY_NOT_INSTALLED', 'Serendipity er enn� ikke installert. Klikk <a href="%s">her for � installere</a> n�.');
@define('INCLUDE_ERROR', 'Serendipity feil: Kunne ikke inkludere %s - Avbryter.');
@define('DATABASE_ERROR', 'Serendipity feil: Kunne ikke oprette forbindelse til databasen - Avbryter.');
@define('CREATE_DATABASE', 'Oppretter standarddatabase-setup...');
@define('ATTEMPT_WRITE_FILE', 'Fors�ker � skrive til %s ...');
@define('WRITTEN_N_SAVED', 'Konfigurasjon skrevet &amp; lagret');
@define('IMAGE_ALIGNMENT', 'Bildejustering');
@define('ENTER_NEW_NAME', 'Oppgi det nye navn for: ');
@define('RESIZING', 'Endre dimensjoner');
@define('RESIZE_DONE', 'Ferdig (Endret %s bilder).');
@define('SYNCING', 'Synkronisere databasen med bildemappen');
@define('SYNC_OPTION_LEGEND', 'Thumbnail Synchronization Options');
@define('SYNC_OPTION_KEEPTHUMBS', 'Keep all existing thumbnails');
@define('SYNC_OPTION_SIZECHECKTHUMBS', 'Keep existing thumbnails only if they are the correct size');
@define('SYNC_OPTION_DELETETHUMBS', 'Regenerate all (<em>*.%s</em>) thumbnails');
@define('SYNC_OPTION_CONVERTTHUMBS', 'Convert old existing thumbnail names');
@define('SYNC_OPTION_CONVERTTHUMBS_INFO', 'WARNING: This option is not active, as long the thumbSuffix has not changed.<br>It converts existing thumbnails, which are not named by the current thumbSuffix-scheme: <em>*.%s</em>, in the database, the filesystem and already used in entries to the same suffix naming scheme. This can take long! <b>It does not matter keeping them as is</b>, but to include them for the "Regenerate all" option, you need to do this first.');
@define('SYNC_DONE', 'Ferdig (Synkroniserte %s bilder).');
@define('FILE_NOT_FOUND', 'Kunne ikke finne filen ved navn <b>%s</b>, den er kanskje allerede slettet?');
@define('ABORT_NOW', 'Avbryt n�');
@define('REMOTE_FILE_NOT_FOUND', 'Filen kunne ikke finnes p� serveren, er du sikker p� at URL-en: <b>%s</b> er korrekt?');
@define('FILE_FETCHED', '%s hentet: %s');
@define('FILE_UPLOADED', 'Filen %s er lastet opp: %s');
@define('WORD_OR', 'eller');
@define('SCALING_IMAGE', 'Endre st�rrelsen p� %s til %s x %s px');
@define('FORCE_RELOAD', 'With certain image characteristics it can occasionally happen that the old image is still present in the browser cache. If so, check into the MediaLibrary again and force a hard reload of your browser [Ctrl]+[F5], to actually see the scaled image.');
@define('KEEP_PROPORTIONS', 'Bevar forholdet');
@define('REALLY_SCALE_IMAGE', 'Er du sikker p� at du vil endre st�rrelsen p� dette bildet? Det er ingen vei tilbake!');
@define('TOGGLE_ALL', 'Vis/Skjul alle');
@define('TOGGLE_OPTION', 'Vis/Skjul denne opsjonen');
@define('SUBSCRIBE_TO_THIS_ENTRY', 'Abonn�r p� denne artikkelen');
@define('UNSUBSCRIBE_OK', "%s er ikke lenger abonnent p� denne artikkelen");
@define('NEW_COMMENT_TO_SUBSCRIBED_ENTRY', 'Ny kommentar til abonnert artikkel "%s"');
@define('SUBSCRIPTION_MAIL', "Hej %s,\n\nEn ny kommentar er blitt lagt til en artikkel du abonnerer p� hos \"%s\", ved navn \"%s\"\nNavnet p� skribenten er: %s\n\nDu kan finne artikkelen her: %s\n\nDu kan stoppe ditt abonnement ved � klikke p� denne linken: %s\n");
@define('SUBSCRIPTION_TRACKBACK_MAIL', "Hei %s,\n\nEn ny trackback er laget til en artikkel du abonnerer p� hos \"%s\", ved navn \"%s\"\nNavnet p� skribenten er: %s\n\nDu kan finne artiklen her: %s\n\nDu kan stoppe ditt abonnement ved � klikke p� denne linken: %s\n");
@define('SIGNATURE', "\n-- \n%s bruker %s.\nDen bedste blogg p� dette, du kan ogs� bruke det.\nKikk n�rmere p� <%s> for � finne mere informasjon.");
@define('SYNDICATION_PLUGIN_20', 'RSS 2.0-feed');
@define('SYNDICATION_PLUGIN_20c', 'RSS 2.0-kommentarer');
@define('SYNDICATION_PLUGIN_GENERIC_FEED', '%s-feed');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR', 'Feltet "Hovedredakt�r"');
@define('SYNDICATION_PLUGIN_WEBMASTER', 'Feltet "webmaster"');
@define('SYNDICATION_PLUGIN_BANNERURL', 'Bilde til RSS-feed');
@define('SYNDICATION_PLUGIN_BANNERWIDTH', 'Bildebredde');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT', 'Bildeh�jde');
@define('SYNDICATION_PLUGIN_WEBMASTER_DESC', 'Emailadressen til webmasteren, hvis tilgjengelig. (tom: skjult) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR_DESC', 'E-mailadressen til redakt�ren, hvis tilgjengelig. (tom: skjult) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_BANNERURL_DESC', 'URL til et bilde i GIF/JPEG/PNG format, hvis tilgjengelig. (tom: serendipity-logo)');
@define('SYNDICATION_PLUGIN_BANNERWIDTH_DESC', 'i pixels, max. 144');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT_DESC', 'i pixels, max. 400');
@define('SYNDICATION_PLUGIN_TTL', 'Feltet "ttl" (time-to-live)');
@define('SYNDICATION_PLUGIN_TTL_DESC', 'Antallet minutter din blog skal caches av eksterne sider/programmer (tom: skjult) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_PUBDATE', 'Feltet "pubDate"');
@define('SYNDICATION_PLUGIN_PUBDATE_DESC', 'Skal "pubDate"-feltet v�re innkapslet til en RSS-kanal, for � vise datoen p� den nyeste artikkelen?');
@define('CONTENT', 'Innhold');
@define('TYPE', 'Type');
@define('DRAFT', 'Utkast');
@define('PUBLISH', 'Publiser');
@define('PREVIEW', 'Vis');
@define('ALL_ENTRIES', 'All entries');
@define('DATE', 'Dato');
@define('DATE_FORMAT_2', 'Y-m-d H:i'); // Needs to be ISO 8601 compliant for date conversion!
@define('DATE_INVALID', 'Advarsel: Den datoen du tastet inn var ugyldig. Den skal tastes inn i f�lgende format: ����-MM-DD TT:MM.');
@define('CATEGORY_PLUGIN_DESC', 'Viser listen over kategorier.');
@define('ALL_AUTHORS', 'Alle forfattere');
@define('CATEGORIES_TO_FETCH', 'Kategorier som skal hentes');
@define('CATEGORIES_TO_FETCH_DESC', 'Hent kategorier fra en bestemt forfatter?');
@define('PAGE_BROWSE_ENTRIES', 'Side %s av %s, i alt %s artikler');
@define('PAGE', 'Page');
@define('PREVIOUS_PAGE', 'forrige side');
@define('NEXT_PAGE', 'neste side');
@define('ALL_CATEGORIES', 'Alle kategorier');
@define('DO_MARKUP', 'Utf�rt Markup-transformasjon');
@define('DO_MARKUP_DESCRIPTION', 'Bruk (plugin) markup-transformasjoner p� teksten (smilies, bbcode, s9y-snarveismarkeringer, markdown, etc.). Hvis deaktivert, gjengis innholdet 1:1 og eventuell HTML-formatering bevares. Hvis dette alternativet er aktivert, kan andre plugins endre innholdet i nuggeten.');
@define('GENERAL_PLUGIN_DATEFORMAT', 'Datoformat');
@define('GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH', 'Formatet p� artiklens faktiske dato, brug PHPs strftime()-variabler. (Standard: "%s")');
@define('ERROR_TEMPLATE_FILE', 'Kunne ikke �pne malen, oppater Serendipity!');
@define('ADVANCED_OPTIONS', 'Avanserte innstillinger');
@define('EDIT_ENTRY', 'Rediger artikkel');
@define('HTACCESS_ERROR', 'For � kunne sjekke din lokale webservers installasjon, m� Serendipity v�re i stand til � skrive til filen ".htaccess". Dette var ikke mulig pga. feil rettigheter. Endre rettighetene slik: <br>&nbsp;&nbsp;%s<br>og oppdater denne siden.');
@define('SORT_ORDER', 'Sorter etter');
@define('SORT_ORDER_NAME', 'Filnavn');
@define('SORT_ORDER_EXTENSION', 'Filtype');
@define('SORT_ORDER_SIZE', 'Filst�rrelse');
@define('SORT_ORDER_WIDTH', 'Bildebredde');
@define('SORT_ORDER_HEIGHT', 'Bildeh�yde');
@define('SORT_ORDER_DATE', 'Upload-dato');
@define('SORT_ORDER_ASC', 'Stigende');
@define('SORT_ORDER_DESC', 'Fallende');
@define('THUMBNAIL_SIZE', 'Thumb size'); // keep short
@define('THUMBNAIL_SHORT', 'Mini');
@define('ORIGINAL_SHORT', 'Origin');
@define('APPLY_MARKUP_TO', 'Formater %s');
@define('CALENDAR_BEGINNING_OF_WEEK', 'Starten p� uken');
@define('SERENDIPITY_NEEDS_UPGRADE', 'Serendipity har opdaget at din n�v�rende konfigurasjon passer til versjon %s. Serendipity selv er installert som version %s, Det er n�dvendig � oppgradere! <a href="%s">Klikk her!</a>');
@define('SERENDIPITY_UPGRADER_WELCOME', 'Velkommen til Serendipity oppgraderingsscript.');
@define('SERENDIPITY_UPGRADER_PURPOSE', 'Scriptet vil hjelpe deg med � oppgradere Serendipity %s.');
@define('SERENDIPITY_UPGRADER_WHY', 'Denne meldingen vises fordi Serendipity versjon %s er installert, men databasen er enn� ikke opgradert til denne versjonen.');
@define('SERENDIPITY_UPGRADER_DATABASE_UPDATES', 'Databasen oppdateringer (%s)');
@define('SERENDIPITY_UPGRADER_FOUND_SQL_FILES', 'F�lgende .sql-filer m� kj�res f�r Serendipity igjen kan fungere normalt.');
@define('SERENDIPITY_UPGRADER_VERSION_SPECIFIC', 'Versjonsspesifikke oppgaver');
@define('SERENDIPITY_UPGRADER_NO_VERSION_SPECIFIC', 'Ingen versjonsspesifikke oppgaver funnet');
@define('SERENDIPITY_UPGRADER_PROCEED_QUESTION', '�nsker du at ovenst�ende opgaver utf�res?');
@define('SERENDIPITY_UPGRADER_PROCEED_ABORT', 'Nei, jeg utf�rer dem manuelt');
@define('SERENDIPITY_UPGRADER_PROCEED_DOIT', 'Ja takk!');
@define('SERENDIPITY_UPGRADER_NO_UPGRADES', 'Det ser ut til at oppgradering ikke er n�dvendig.');
@define('SERENDIPITY_UPGRADER_PROCEED_WITH_TASK', 'Even when no specific upgrade tasks are required and only version-dependent notices for the update appear, it is recommended to use the green "' . SERENDIPITY_UPGRADER_PROCEED_DOIT . '" button.');
@define('SERENDIPITY_UPGRADER_CONSIDER_DONE', 'Serendipity er oppgradert');
@define('SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED', 'Du har ignorert en del av oppgraderingen. Vennligst unders�k hvorvidt databasen er korrekt opdatert, og planlagte opgaver utf�rt. After having finished your reasoned work for hold-back, this can be done automatically by just resetting the version in your serendipity_config_local.inc (only) file and run this upgrade page again via your backend page.');
@define('SERENDIPITY_UPGRADER_NOW_UPGRADED', 'Din Serendipity-installation er n� oppgradert til version %s');
@define('SERENDIPITY_UPGRADER_RETURN_HERE', 'Du kan vende tilbake til din blog ved � klikke %sher%s');
@define('MANAGE_USERS', 'H�ndter brukere');
@define('CREATE_NEW_USER', 'Opprett ny bruker');
@define('CREATE_NOT_AUTHORIZED', 'Du kan ikke redigere brukere med det samme brukerniv� som deg selv');
@define('CREATE_NOT_AUTHORIZED_USERLEVEL', 'Du kan ikke opprette brukere med et h�yere brukerniv� enn deg selv');
@define('CREATED_USER', 'En ny bruker %s er opprettet');
@define('MODIFIED_USER', 'Egenskapene for brukeren "%s" er endret');
@define('USER_LEVEL', 'brukerniv�');
@define('DELETE_USER', 'Du er i ferd med � slette bruker #%d %s. Er du sikker? Dette vil forhindre visning av alle artikler skrevet av brukeren.');
@define('DELETED_USER', 'Bruker #%d %s er slettet.');
@define('LIMIT_TO_NUMBER', 'Hvor mange punkter skal vises?');
@define('ENTRIES_PER_PAGE', 'artikler per side');

/* TRANSLATE */
@define('PERMISSIONS', 'Rettigheter');
@define('SECURITY', 'Security');
@define('INTEGRITY', 'Verify Installation Integrity');
@define('CHECKSUMS_NOT_FOUND', 'Unable to compare checksums! (No checksums.inc.php in main directory, or DEV version)');
@define('CHECKSUMS_PASS', 'All required files verified.');
@define('CHECKSUM_FAILED', '%s corrupt or modified: failed verification');

/* DATABASE SETTINGS */
@define('INSTALL_CAT_DB', 'Databaseinnstillinger');
@define('INSTALL_CAT_DB_DESC', 'Her kan du taste inn all databaseinformasjon');
@define('INSTALL_DBTYPE', 'Databasetype');
@define('INSTALL_DBTYPE_DESC', 'Databasetype');
@define('INSTALL_DBHOST', 'Databaseserver');
@define('INSTALL_DBHOST_DESC', 'Hostname til din databaseserver');
@define('INSTALL_DBUSER', 'Databasebrukernavn');
@define('INSTALL_DBUSER_DESC', 'Brukernavnet som er brukt til � koble til databasen din');
@define('INSTALL_DBPASS', 'Databasepassord');
@define('INSTALL_DBPASS_DESC', 'Passordet som passer til brukernavnet over');
@define('INSTALL_DBNAME', 'Databasenavn');
@define('INSTALL_DBNAME_DESC', 'Navnet p� din database');
@define('INSTALL_DBPREFIX', 'Databasetabell-prefiks');
@define('INSTALL_DBPREFIX_DESC', 'Prefiks p� tabellnavn, f.eks. styx_');

/* PATHS */
@define('INSTALL_CAT_PATHS', 'Baner');
@define('INSTALL_CAT_PATHS_DESC', 'Forskjellige baner til forskjellige essensielle mapper og filer. Glem ikke etterf�lgende slasher p� biblioteker!');
@define('INSTALL_FULLPATH', 'Full bane');
@define('INSTALL_FULLPATH_DESC', 'Den fulle og absolutte bane til din Serendipity-installasjon');
@define('INSTALL_UPLOADPATH', 'Upload-bane');
@define('INSTALL_UPLOADPATH_DESC', 'Alle uploads blir plassert her, relativ til \'Full bane\' - typisk \'uploads/\'');
@define('INSTALL_RELPATH', 'Relativ bane');
@define('INSTALL_RELPATH_DESC', 'Bane til Serendipity i henhold til din browser, typisk \'/serendipity/\'');
@define('INSTALL_RELTEMPLPATH', 'Relativ mal-bane');
@define('INSTALL_RELTEMPLPATH_DESC', 'Bane til mappen som inneholder dine maler - Relativ til \'relative bane\'');
@define('INSTALL_RELUPLOADPATH', 'Relativ upload-bane');
@define('INSTALL_RELUPLOADPATH_DESC', 'Bane til dine uploads i henhold til din browser - Relativ til \'relative bane\'');
@define('INSTALL_URL', 'URL til din blogg');
@define('INSTALL_URL_DESC', 'Base-URL for din Serendipity-installasjon');
@define('INSTALL_INDEXFILE', 'Index-fil');
@define('INSTALL_INDEXFILE_DESC', 'Navnet p� din Serendipity index-fil');

/* GENERAL SETTINGS */
@define('INSTALL_CAT_SETTINGS', 'Generelle innstillinger');
@define('INSTALL_CAT_SETTINGS_DESC', 'Tilpass m�ten Serendipity oppf�rer seg p�');
@define('INSTALL_USERNAME', 'Admin-brukernavn');
@define('INSTALL_USERNAME_DESC', 'Brukernavn til admin-login');
@define('INSTALL_PASSWORD', 'Admin-passord');
@define('INSTALL_PASSWORD_DESC', 'Passord til admin-login');
@define('INSTALL_EMAIL', 'Admin-email');
@define('INSTALL_EMAIL_DESC', 'E-mailadresse til eieren av bloggen');
@define('INSTALL_SENDMAIL', 'Send e-mailer til admin?');
@define('INSTALL_SENDMAIL_DESC', 'Vil du motta email n�r kommentare blir gitt til dine artikler?');
@define('INSTALL_SUBSCRIBE', 'Tillat brukere � abonnere p� artikler?');
@define('INSTALL_SUBSCRIBE_DESC', 'Tillat brukere � abonnere p� en artikkel og dermed motte en mail n�r det oprettes nye kommentare p� den artikkelen');
@define('INSTALL_BLOGNAME', 'Navn p� blogg');
@define('INSTALL_BLOGNAME_DESC', 'Tittelen p� din blogg');
@define('INSTALL_BLOGDESC', 'Blogg-beskrivelse');
@define('INSTALL_BLOGDESC_DESC', 'Beskrivelse av din blogg');
@define('INSTALL_LANG', 'Spr�k');
@define('INSTALL_LANG_DESC', 'Velg spr�ket p� din blogg');

/* APPEARANCE AND OPTIONS */
@define('INSTALL_CAT_DISPLAY', 'Utseende og innstillinger');
@define('INSTALL_CAT_DISPLAY_DESC', 'Tilpass hvordan Serendipity ser ut og f�les');
@define('INSTALL_WYSIWYG', 'Bruk WYSIWYG-editor');
@define('INSTALL_WYSIWYG_DESC', 'Vil du benytte WYSIWYG-editoren?<br>For more comfort and quicker updates it is recommended to install the extended CKEditor Plus event Plugin!');
@define('INSTALL_POPUP', 'Aktiver bruk av popup-vinduer');
@define('INSTALL_POPUP_DESC', 'Vil du �pne kommentarer og trackbacks i et popupvindu?');
@define('INSTALL_EMBED', 'Er Serendipity embedded?');
@define('INSTALL_EMBED_DESC', 'Hvis du �nsker � kapsle Serendipity inn i en normal side, aktiver da dette direktivet for � ignorere headere og kun skrive ut innholdet. Du kan benytte indexFile-direktivet til � lage en wrapper-fil hvor du plasserer din normale hjemmesides headere. Konsulter README filen for mer informasjon!');
@define('INSTALL_TOP_AS_LINKS', 'Vis topp utgangssider/referanser som links?');
@define('INSTALL_TOP_AS_LINKS_DESC', '"no": Utgangssider og referanser blir vist som ren tekst for � forhindre Google-spam.  "yes": Utgangssider og referanser blir vist som hyperlinker.  "default": Bruk innstillinger fra global konfigurasjon (anbefalt).');
@define('INSTALL_BLOCKREF', 'Blokk�r referanser');
@define('INSTALL_BLOCKREF_DESC', 'Er det noen spesielle hosts du ikke �nsker skal vises i referanselisten?  Seprarer listen av hostnavn med \';\' og merk deg at disse er blokkert med substring-masker!');
@define('INSTALL_REWRITE', 'URL-omskrivning');
@define('INSTALL_REWRITE_DESC', 'Velg hvilken regel du �nsker � bruke n�r du genererer URL-er. Aktivering av omskrivningsregler vil gj�re URL-ene dine flotte og gj�re din side mer egnet for indeksering av roboter som Google. Webserveren m� enten st�tte mod_rewrite eller "AllowOverride All" for ditt Serendipity-bibliotek. Standardindstillingen er automatisk beregnet');

/* IMAGECONVERSION SETTINGS */
@define('INSTALL_CAT_IMAGECONV', 'Bildekonverterings-innstillinger');
@define('INSTALL_CAT_IMAGECONV_DESC', 'Generell informasjon om hvordan Serendipity skal h�ndtere bilder');
@define('INSTALL_IMAGEMAGICK', 'Bruk Imagemagick');
@define('INSTALL_IMAGEMAGICK_DESC', 'Har du ImageMagick installert og �nsker � bruke det til � endre st�rrelse p� bilder?');
@define('INSTALL_IMAGEMAGICKPATH', 'Bane til convert bin�rfil');
@define('INSTALL_IMAGEMAGICKPATH_DESC', 'Full bane og navn p� din ImageMagick convert bin�rfil');
@define('INSTALL_THUMBSUFFIX', 'Thumbnail-suffiks');
@define('INSTALL_THUMBSUFFIX_DESC', 'Thumbnails vil bli gitt navn etter f�lgende m�nster: original.[suffix].ext');
@define('INSTALL_THUMBWIDTH', 'Thumbnail-dimensjoner');
@define('INSTALL_THUMBWIDTH_DESC', 'Statisk maksimalbredde p� en auto-genereret thumbnail');
@define('INSTALL_IMAGEDIM', 'Image constrained dimension');
@define('INSTALL_IMAGEDIM_LARGEST', 'Largest');
@define('INSTALL_IMAGEDIM_WIDTH', 'Width');
@define('INSTALL_IMAGEDIM_HEIGHT', 'Height');
@define('INSTALL_IMAGEDIM_DESC', 'Dimension to be constrained to the image max size. The default "' .
    INSTALL_IMAGEDIM_LARGEST .  '" limits both dimensions, so neither can be greater than the max size; "' .
    INSTALL_IMAGEDIM_WIDTH . '" and "' .  INSTALL_IMAGEDIM_HEIGHT .
    '" only limit the chosen dimension, so the other could be larger than the max size.');

/* PERSONAL DETAILS */
@define('USERCONF_CAT_PERSONAL', 'Dine personlige detaljer');
@define('USERCONF_CAT_PERSONAL_DESC', 'Endre dine personlige detaljer');
@define('USERCONF_USERNAME', 'Ditt brukernavn');
@define('USERCONF_USERNAME_DESC', 'Brukernavnet du vil bruke for � logge inn i bloggen');
@define('USERCONF_PASSWORD', 'Ditt passord');
@define('USERCONF_PASSWORD_DESC', 'Passordet du vil bruke for � logge inn i bloggen');
@define('USERCONF_PASSWORD_RANDOM', 'New cryptographically secure password as a copyable proposal');
@define('USERCONF_EMAIL', 'Din e-mailadresse');
@define('USERCONF_EMAIL_DESC', 'Din personlige e-mailadresse');
@define('USERCONF_SENDCOMMENTS', 'Send beskjeder om nye kommentarer?');
@define('USERCONF_SENDCOMMENTS_DESC', '�nsker du � motta en mail n�r nye kommentarer blir lagt til dine artikler?');
@define('USERCONF_SENDTRACKBACKS', 'Send bjeskeder om nye trackbacks?');
@define('USERCONF_SENDTRACKBACKS_DESC', '�nsker du � motta en mail n�r nye trackbacks blir lagt til dine artikler?');
@define('USERCONF_ALLOWPUBLISH', 'Tillatelse: Publisere artikler?');
@define('USERCONF_ALLOWPUBLISH_DESC', 'Kan denne brukeren publisere artikler?');
@define('USERCONF_DARKMODE', 'Styx Theme Dark Mode');

@define('DIRECTORIES_AVAILABLE', 'I listen over tilgjengelige undermapper, kan du klikke p� en ny mappe for � oprette en ny mappe innenfor den stukturen.');
@define('ALL_DIRECTORIES', 'alle mapper');
@define('MANAGE_DIRECTORIES', 'H�ndter mapper');
@define('DIRECTORY_CREATED', 'Mappen <strong>%s</strong> er oprettet.');
@define('PARENT_DIRECTORY', 'Hovedmappe');
@define('ERROR_NO_DIRECTORY', 'Feil: Mappen %s eksisterer ikke');
@define('CHECKING_DIRECTORY', 'Sjekker filer i mappen %s');
@define('DELETING_FILE', 'Sletter fil %s...');
@define('ERROR_DIRECTORY_NOT_EMPTY', 'Kunne ikke fjerne en ikke-tom mappe. Afkryss "tving gjennom sletting"-feltet hvis du vil slette disse og trykk deretter p� Submit igjen. Eksisterende filer er:');
@define('DIRECTORY_DELETE_FAILED', 'Sletting av mappe %s mislyktes. Sjekk rettigheter eller ovenst�ende beskjeder.');
@define('DIRECTORY_DELETE_SUCCESS', 'Mappen %s er slettet.');
@define('SKIPPING_FILE_EXTENSION', 'Ignorerte filen: Manglende filtype %s.');
@define('SKIPPING_FILE_UNREADABLE', 'Ignorerte fil: %s er ikke lesbar.');
@define('FOUND_FILE', 'Fant ny/endret fil: %s.');
@define('ALREADY_SUBCATEGORY', '%s er allerede en underkategori av %s.');
@define('PARENT_CATEGORY', 'Hovedkategori');
@define('IN_REPLY_TO', 'Som svar p�');
@define('TOP_LEVEL', 'Topniv�');
@define('XML_IMAGE_TO_DISPLAY', 'XML-knapp');
@define('XML_IMAGE_TO_DISPLAY_DESC', 'Linker til XML-feeder vil bli vist med dette bildet.  Etterlat tomt for standard, tast inn \'none\' for � deaktivere.');
@define('SUCCESS', 'Suksess');
@define('NUMBER_FORMAT_DECIMALS', '2');
@define('NUMBER_FORMAT_DECPOINT', ',');
@define('NUMBER_FORMAT_THOUSANDS', '.');
@define('POWERED_BY_SHOW_TEXT', 'Vis "%s" som tekst');
@define('POWERED_BY_SHOW_TEXT_DESC', 'Vil vise "Serendipity Styx Weblog" som tekst');
@define('POWERED_BY_SHOW_IMAGE', 'Vis "%s" med en logo');
@define('POWERED_BY_SHOW_IMAGE_DESC', 'Vis %s-logoen');
@define('SETTINGS_SAVED_AT', 'De nye innstillgene er lagret kl %s');
@define('PLUGIN_ITEM_DISPLAY', 'Hvor skal enheten vises?');
@define('PLUGIN_ITEM_DISPLAY_EXTENDED', 'Kun udvidet artikkel');
@define('PLUGIN_ITEM_DISPLAY_OVERVIEW', 'Kun i oversikten');
@define('PLUGIN_ITEM_DISPLAY_BOTH', 'Hele tiden');
@define('RSS_IMPORT_CATEGORY', 'Bruk denne kategorien for kategoril�se artikler');
@define('ERROR_UNKNOWN_NOUPLOAD', 'Det oppstod en ukjent feil, filen ble ikke lastet opp. Kanskje er din filst�rrelse st�rre end den maksimale st�rrelse tillatt av ditt serveroppsett. Sp�r din webtilbyder eller rediger din php.ini fil for � tillate uploads av st�rre filer.');
@define('COMMENTS_WILL_BE_MODERATED', 'Kommentarer p� denne artikkelen vil f�rst bli vist n�r de er blitt godkjendt.');
@define('YOU_HAVE_THESE_OPTIONS', 'Du har f�lgende muligheter:');
@define('THIS_COMMENT_NEEDS_REVIEW', 'Advarsel: Denne kommentaren krever godkjennelse f�r den blir vist.');
@define('DELETE_COMMENT', 'Slett kommentar');
@define('APPROVE_COMMENT', 'Godkjenn kommentar');
@define('REQUIRES_REVIEW', 'Krever godkjennelse');
@define('COMMENT_APPROVED', 'Kommentar #%s er godkjent');
@define('COMMENT_DELETED', 'Kommentar #%s er slettet');
@define('VIEW', 'Vis');
@define('COMMENT_ALREADY_APPROVED', 'Kommentar #%s ser allerede ut til � v�re godkjent');
@define('COMMENT_EDITED', 'Den valgte kommentar er redigert');
@define('AWAKE', 'Fade in');
@define('HIDE', 'Skjul');
@define('VIEW_EXTENDED_ENTRY', 'Les resten av "%s"');
@define('TRACKBACK_SPECIFIC_ON_CLICK', 'This link is not active. It contains a copyable trackback URI to manually send ping- & trackbacks to this entry for older Blogs; Eg. (still valid) via the provided entry field of the serendipity_event_trackback plugin. Serendipity and other Blog systems nowadays recognize the trackback URL automatically by the article URL. The trackback URI for your Sender entry link therefore is as follows:');
@define('THIS_TRACKBACK_NEEDS_REVIEW', 'Advarsel:  Denne trackbacken trenger godkjennelse f�r den blir vist.');
@define('DELETE_TRACKBACK', 'Slett trackback');
@define('APPROVE_TRACKBACK', 'Godkjenn trackback');
@define('TRACKBACK_APPROVED', 'Trackback #%s har blitt godkjent');
@define('TRACKBACK_DELETED', 'Trackback #%s har blitt slettet');
@define('COMMENTS_MODERATE', 'Kommentarer og trackback til denne posten krever moderasjon.');
@define('PLUGIN_SUPERUSER_HTTPS', 'Bruk https ved innlogging');
@define('PLUGIN_SUPERUSER_HTTPS_DESC', 'Peker login-linken til en https-adresse. Webserveren din m� st�tte dette!');
@define('INSTALL_SHOW_EXTERNAL_LINKS', 'Gj�re eksterne lenker klikkbare?');
@define('INSTALL_SHOW_EXTERNAL_LINKS_DESC', '"no": Usjekkede eksterne lenker (topp utgangssider, referanser, brukerkommentarer) er ikke vist - vist som ren tekst hvor mulig, for � forhindre Google-spam (anbefalt).  "yes":  Usjekkede eksterne lenker blir vist som hyperlenker.  Kan bli overstyrt i sidebar plugin-konfigurasjon!');
@define('PAGE_BROWSE_COMMENTS', 'Side %s av %s, totalt %s kommentarer');
@define('FILTERS', 'Filtere');
@define('FIND_ENTRIES', 'Finn poster');
@define('FIND_COMMENTS', 'Finn kommentarer');
@define('FIND_MEDIA', 'Finn media');
@define('FILTER_DIRECTORY', 'Katalog');
@define('SORT_BY', 'Sorter p�');
@define('TRACKBACK_COULD_NOT_CONNECT', 'Ingen Trackback sendt:  Kunne ikke �pne tilkobling til %s p� port %d');
@define('MEDIA', 'Media');
@define('MEDIA_LIBRARY', 'Media-bibliotek');
@define('ADD_MEDIA_PICTELEMENT', 'Use &lt;picture&gt; element');
@define('ADD_MEDIA', 'Legg til media');
@define('ENTER_MEDIA_URL', 'Oppgi en URL til en fil som skal hentes:');
@define('ENTER_MEDIA_UPLOAD', 'Velg en fil du �nsker � laste opp:');
@define('SAVE_FILE_AS', 'Lagre filen som:');
@define('STORE_IN_DIRECTORY', 'Lagre i f�lgende katalog: ');
@define('MEDIA_RENAME', 'Gi denne filen nytt navn');
@define('IMAGE_RESIZE', 'Endre st�rrelsen p� dette bildet');
@define('MEDIA_DELETE', 'Slett denne filen');
@define('FILES_PER_PAGE', 'Antall filer per side');
@define('CLICK_FILE_TO_INSERT', 'Klikk p� filen du �nsker � sette inn:');
@define('SELECT_FILE', 'Velg fil � sette inn');
@define('SELECT_PAGE', 'Select page:');
@define('MEDIA_FULLSIZE', 'Full st�rrelse');
@define('CALENDAR_BOW_DESC', 'Ukedagen som skal v�re starten p� uken.  Standard er mandag');
@define('SUPERUSER', 'Blogg-administrasjon');
@define('ALLOWS_YOU_BLAHBLAH', 'Legger en link til blogg-administrasjonen i sidebaren.');

@define('CALENDAR', 'Kalender');
@define('SUPERUSER_OPEN_ADMIN', '�pne administrasjon');
@define('SUPERUSER_OPEN_LOGIN', '�pne login-skjerm');
@define('INVERT_SELECTIONS', 'Inverter valgene');
@define('COMMENTS_DELETE_CONFIRM', 'Er du sikker p� at du �nsker � slette de valgte kommentarene?');
@define('COMMENT_DELETE_CONFIRM', 'Er du sikker p� at du �nsker � slette kommentar #%d, skrevet av %s?');
@define('DELETE_SELECTED_COMMENTS', 'Slett valgte kommentarer');
@define('VIEW_COMMENT', 'Se kommentar');
@define('VIEW_ENTRY', 'Se posting');
@define('DELETE_FILE_FAIL', 'Kan ikke slette filen <b>%s</b>');
@define('DELETE_THUMBNAIL', 'Slettet bildet med navn <b>%s</b>');
@define('DELETE_FILE', 'Slettet filen kalt <b>%s</b>');
@define('ABOUT_TO_DELETE_FILE', 'Du er i ferd med � slette <b>%s</b><br>Hvis du bruker denne filen i noen av postene dine vil dette for�rsake d�de lenker eller bilder<br>Er du sikker p� at du vil g� videre?');
@define('ABOUT_TO_DELETE_FILES', 'You are about to delete a bunch of files at once.<br>If you are using these in some of your entries, it will cause dead links or images<br>Are you sure you wish to proceed?');
@define('TRACKBACK_SENDING', 'Sender trackback til URI %s...');
@define('TRACKBACK_SENT', 'Trackback suksess');
@define('TRACKBACK_FAILED', 'Trackback slo feil: %s');
@define('TRACKBACK_NOT_FOUND', 'Fant ingen trackback-URI.');
@define('TRACKBACK_URI_MISMATCH', 'Den autofunnede trackback-URI-en stemmer ikke overens med v�r m�l-URI.');
@define('TRACKBACK_CHECKING', 'Sjekker <u>%s</u> for mulige trackbacks...');
@define('TRACKBACK_NO_DATA', 'M�l inneholdt ingen data');
@define('TRACKBACK_SIZE', 'M�l-URI oversteg maksimum filst�rrelse p� %s bytes.');
@define('COMMENTS_VIEWMODE_THREADED', 'Tr�det');
@define('COMMENTS_VIEWMODE_LINEAR', 'Line�r');
@define('DISPLAY_COMMENTS_AS', 'Vis kommentarer som');
@define('SIDEBAR_PLUGINS', 'Sidebar-plugins');
@define('EVENT_PLUGINS', 'Hendelses-lugins');
@define('ADD_MEDIA_BLAHBLAH', '<b>Legg til en fil til ditt mediabibliotek:</b><p>Her kan du laste opp mediafiler, eller be meg om � hente dem fra en adresse p� nettet!  Hvis du ikke har et passende bilde, <a href="https://images.google.com" rel="noopener" target="_blank">s�k etter bilder p� Google</a> som passer til tankene dine.  Resultatene er ofte nyttige og morsomme :)</p><p><b>Velg metode:</b></p><br>');
@define('COMMENTS_FILTER_SHOW', 'Vis');
@define('COMMENTS_FILTER_ALL', 'Alle');
@define('COMMENTS_FILTER_APPROVED_ONLY', 'Only approved'); // Translate
@define('COMMENTS_FILTER_HIDDEN_ONLY', 'Only hidden'); // Translate
@define('COMMENTS_FILTER_APPROVAL_ONLY', 'Only pending'); // Translate
@define('COMMENTS_FILTER_CONFIRM_ONLY', 'Only confirmable'); // Translate
@define('COMMENTS_FILTER_NEED_APPROVAL', 'Pending approval'); // Translate
@define('COMMENTS_FILTER_NEED_CONFIRM', 'Pending confirmation'); // Translate
@define('RSS_IMPORT_BODYONLY', 'Put all imported text in the "body" section and do not split up into "extended entry" section.'); // Translate
@define('SYNDICATION_PLUGIN_FULLFEED', 'Show full articles with extended body inside RSS feed'); // Translate
@define('MT_DATA_FILE', 'Movable Type data file'); // Translate
@define('FORCE', 'Force'); // Translate
@define('CREATE_AUTHOR', 'Create author \'%s\'.'); // Translate
@define('CREATE_CATEGORY', 'Create category \'%s\'.'); // Translate
@define('MYSQL_REQUIRED', 'You must have the MySQL extension in order to perform this action.'); // Translate
@define('PGSQL_REQUIRED', 'You must have the PostgreSQL extension in order to perform this action.');
@define('COULDNT_CONNECT', 'Could not connect to MySQL database: %s.'); // Translate
@define('PGSQL_COULDNT_CONNECT', 'Could not connect to PostgreSQL database: %s.');
@define('COULDNT_SELECT_DB', 'Could not select database: %s.'); // Translate
@define('COULDNT_SELECT_USER_INFO', 'Could not select user information: %s.'); // Translate
@define('COULDNT_SELECT_CATEGORY_INFO', 'Could not select category information: %s.'); // Translate
@define('COULDNT_SELECT_ENTRY_INFO', 'Could not select entry information: %s.'); // Translate
@define('COULDNT_SELECT_COMMENT_INFO', 'Could not select comment information: %s.'); // Translate
@define('WEEK', 'Uke');
@define('WEEKS', 'Uker');
@define('MONTHS', 'M�neder');
@define('DAYS', 'Dager');
@define('ARCHIVE_FREQUENCY', 'Calendar item frequency'); // Translate
@define('ARCHIVE_FREQUENCY_DESC', 'The calendar interval to use between each item in the list'); // Translate
@define('ARCHIVE_COUNT', 'Number of items in the list'); // Translate
@define('ARCHIVE_COUNT_DESC', 'The total number of months, weeks or days to display'); // Translate
@define('BELOW_IS_A_LIST_OF_INSTALLED_PLUGINS', 'Below is a list of installed plugins'); // Translate
@define('SIDEBAR_PLUGIN', 'sidebar plugin'); // Translate
@define('EVENT_PLUGIN', 'event plugin'); // Translate
@define('CLICK_HERE_TO_INSTALL_PLUGIN', 'Click here to install a new %s'); // Translate
@define('VERSION', 'Versjon');
@define('INSTALL', 'Installer');
@define('ALREADY_INSTALLED', 'Allerede installert');
@define('SELECT_A_PLUGIN_TO_ADD', 'Select the plugin which you wish to install'); // Translate
@define('INSTALL_OFFSET', 'Server time Offset'); // Translate
@define('INSTALL_OFFSET_ON_SERVER_TIME', 'Base offset on server timezone?');
@define('INSTALL_OFFSET_ON_SERVER_TIME_DESC', 'Offset entry times on server timezone or not. Select yes to base offset on server timezone and no to offset on GMT.');
@define('STICKY_POSTINGS', 'Faste oppslag');
@define('INSTALL_FETCHLIMIT', 'Entries to display on frontpage'); // Translate
@define('INSTALL_FETCHLIMIT_DESC', 'Number of entries to display for each page on the frontend'); // Translate
@define('IMPORT_ENTRIES', 'Import data'); // Translate
@define('EXPORT_ENTRIES', 'Export entries'); // Translate
@define('IMPORT_WELCOME', 'Welcome to the Serendipity import utility'); // Translate
@define('IMPORT_WHAT_CAN', 'Here you can import entries from other weblog software applications'); // Translate
@define('IMPORT_SELECT', 'Please select the software you wish to import from'); // Translate
@define('IMPORT_PLEASE_ENTER', 'Please enter the data as requested below'); // Translate
@define('IMPORT_NOW', 'Import now!'); // Translate
@define('IMPORT_STARTING', 'Starting import procedure...'); // Translate
@define('IMPORT_FAILED', 'Import failed'); // Translate
@define('IMPORT_DONE', 'Import successfully completed'); // Translate
@define('IMPORT_WEBLOG_APP', 'Weblog application'); // Translate
@define('EXPORT_FEED', 'Export full RSS feed'); // Translate
@define('IMPORT_STATUS', 'Status after import'); // Translate
@define('IMPORT_GENERIC_RSS', 'Generic RSS import'); // Translate
@define('ACTIVATE_AUTODISCOVERY', 'Send Trackbacks to links found in the entry'); // Translate
@define('WELCOME_TO_ADMIN', 'Welcome to the Serendipity Styx Administration Suite.'); // Translate
@define('PLEASE_ENTER_CREDENTIALS', 'Please enter your credentials below.'); // Translate
@define('ADMIN_FOOTER_POWERED_BY', 'Powered by Serendipity %s and PHP %s'); // Translate
@define('INSTALL_USEGZIP', 'Use gzip compressed pages'); // Translate
@define('INSTALL_USEGZIP_DESC', 'To speed up delivery of pages, we can compress the pages we send to the visitor, given that his browser supports this. This is recommended.'); // Translate
@define('INSTALL_SHOWFUTURE', 'Show future entries'); // Translate
@define('INSTALL_SHOWFUTURE_DESC', 'If enabled, this will show all entries in the future on your blog. Default is to hide those entries and only show them if the publish date has arrived.'); // Translate
@define('INSTALL_DBPERSISTENT', 'Use persistent connections'); // Translate
@define('INSTALL_DBPERSISTENT_DESC', 'Enable the usage of persistent database connections, read more <a href="https://php.net/manual/features.persistent-connections.php" rel="noopener" target="_blank">here</a>. This is normally not recommended.'); // Translate
@define('NO_IMAGES_FOUND', 'Ingen bilder funnet');
@define('PERSONAL_SETTINGS', 'Personal Settings'); // Translate
@define('REFERER', 'Referrer'); // Translate
@define('NOT_FOUND', 'Not found'); // Translate
@define('NOT_WRITABLE', 'Not writable'); // Translate
@define('WRITABLE', 'Writable'); // Translate
@define('PROBLEM_DIAGNOSTIC', 'Due to a problematic diagnostic, you cannot continue with the installation before the above errors are fixed'); // Translate
@define('SELECT_INSTALLATION_TYPE', 'Select which installation type you wish to use'); // Translate
@define('WELCOME_TO_INSTALLATION', 'Welcome to the Serendipity Styx Installation'); // Translate
@define('FIRST_WE_TAKE_A_LOOK', 'First we will take a look at your current setup and attempt to diagnose any compatibility problems'); // Translate
@define('ERRORS_ARE_DISPLAYED_IN', 'Errors are displayed in %s, recommendations in %s and success in %s'); // Translate
@define('RED', 'r�d');
@define('YELLOW', 'gul');
@define('GREEN', 'green'); // Translate
@define('PRE_INSTALLATION_REPORT', 'Serendipity Styx v.%s pre-installation report'); // Translate
@define('RECOMMENDED', 'Recommended'); // Translate
@define('ACTUAL', 'Actual'); // Translate
@define('PHPINI_CONFIGURATION', 'php.ini configuration'); // Translate
@define('PHP_INSTALLATION', 'PHP installation'); // Translate
@define('THEY_DO', 'they do'); // Translate
@define('THEY_DONT', 'they don\'t'); // Translate
@define('SIMPLE_INSTALLATION', 'Simple installation'); // Translate
@define('EXPERT_INSTALLATION', 'Expert installation'); // Translate
@define('COMPLETE_INSTALLATION', 'Complete installation'); // Translate
@define('WONT_INSTALL_DB_AGAIN', 'won\'t install the database again'); // Translate
@define('CHECK_DATABASE_EXISTS', 'Checking to see if the database and tables already exists'); // Translate
@define('CREATING_PRIMARY_AUTHOR', 'Creating primary author \'%s\''); // Translate
@define('SETTING_DEFAULT_TEMPLATE', 'Setting default template'); // Translate
@define('INSTALLING_DEFAULT_PLUGINS', 'Installing default plugins'); // Translate
@define('SERENDIPITY_INSTALLED', 'Serendipity Styx has been successfully installed'); // Translate
@define('VISIT_BLOG_HERE', 'Visit your new blog here'); // Translate
@define('THANK_YOU_FOR_CHOOSING', 'Thank you for choosing Serendipity Styx'); // Translate
@define('ERROR_DETECTED_IN_INSTALL', 'An error was detected in the installation'); // Translate
@define('OPERATING_SYSTEM', 'Operating system'); // Translate
@define('WEBSERVER_SAPI', 'Webserver SAPI'); // Translate
@define('IMAGE_ROTATE_LEFT', 'Rotate image 90 degrees counter-clockwise'); // Translate
@define('IMAGE_ROTATE_RIGHT', 'Rotate image 90 degrees clockwise'); // Translate
@define('TEMPLATE_SET', '\'%s\' has been set as your active template'); // Translate
@define('SEARCH_ERROR', 'The search function did not work as expected. Notice for the administrator of this blog: This may happen because of missing index keys in your database. On MySQL systems your database user account needs to be privileged to execute this query: <pre>CREATE FULLTEXT INDEX entry_idx on %sentries (title,body,extended)</pre> The specific error returned by the database was: <pre>%s</pre>'); // Translate
@define('EDIT_THIS_CAT', 'Editing "%s"'); // Translate
@define('CATEGORY_REMAINING', 'Delete this category and move its entries to this category'); // Translate
@define('CATEGORY_INDEX', 'Below is a list of categories available to your entries'); // Translate
@define('NO_CATEGORIES', 'No categories'); // Translate
@define('RESET_DATE', 'Reset date'); // Translate
@define('RESET_DATE_DESC', 'Click here to reset the date to the current time'); // Translate
@define('PROBLEM_PERMISSIONS_HOWTO', 'Permissions can be set by running shell command: `<em>%s</em>` on the failed directory, or by setting this using an FTP program'); // Translate
@define('WARNING_TEMPLATE_DEPRECATED', 'Warning: Your current template is using a deprecated template method, you are advised to update if possible'); // Translate
@define('ENTRY_PUBLISHED_FUTURE', 'This entry is not yet published.'); // Translate
@define('ENTRIES_BY', 'Entries by %s'); // Translate
@define('PREVIOUS', 'Forrige');
@define('NEXT', 'Neste');
@define('APPROVE', 'Godkjenn');
@define('CATEGORY_ALREADY_EXIST', 'A category with the name "%s" already exist'); // Translate
@define('IMPORT_NOTES', 'Note:'); // Translate
@define('ERROR_FILE_FORBIDDEN', 'You are not allowed to upload files with active content'); // Translate
@define('ADMIN', 'Administration'); // Re-Translate
@define('ADMIN_FRONTPAGE', 'AdminPanel'); // Translate
@define('QUOTE', 'Quote'); // Translate
@define('IFRAME_SAVE', 'Serendipity is now saving your entry, creating trackbacks and performing possible XML-RPC calls. This may take a while..'); // Translate
@define('IFRAME_SAVE_DRAFT', 'A draft of this entry has been saved'); // Translate
@define('IFRAME_PREVIEW', 'Serendipity is now creating the preview of your entry...'); // Translate
@define('IFRAME_WARNING', 'Your browser does not support the concept of iframes. Please open your serendipity_config.inc.php file and set $serendipity[\'use_iframe\'] variable to FALSE.'); // Translate
@define('NONE', 'ingen');
@define('USERCONF_CAT_DEFAULT_NEW_ENTRY', 'Default settings for new entries'); // Translate
@define('UPGRADE', 'Upgrade'); // Translate
@define('UPGRADE_TO_VERSION', '<b>Upgrade to version:</b> %s'); // Translate
@define('DELETE_DIRECTORY', 'Delete directory'); // Translate
@define('DELETE_DIRECTORY_DESC', 'Du er i ferd med � slette en katalog som kan inneholde mediefiler og elementer som allerede er brukt i oppf�ringene dine. V�r sikker!');
@define('FORCE_DELETE', 'Delete ALL files in this directory, including those not known by Serendipity'); // Translate
@define('CREATE_DIRECTORY', 'Create directory'); // Translate
@define('CREATE_NEW_DIRECTORY', 'Create new directory'); // Translate
@define('CREATE_DIRECTORY_DESC', 'Here you can create a new directory to place media files in. Choose the name for your new directory and select an optional parent directory to place it in.'); // Translate
@define('BASE_DIRECTORY', 'Base directory'); // Translate
@define('USERLEVEL_EDITOR_DESC', 'Standard editor'); // Translate
@define('USERLEVEL_CHIEF_DESC', 'Chief editor'); // Translate
@define('USERLEVEL_ADMIN_DESC', 'Administrator'); // Translate
@define('USERCONF_USERLEVEL', 'Access level'); // Translate
@define('USERCONF_USERLEVEL_DESC', 'This level is used to determine what kind of access this user has to the Blog. User privileges are handled by group memberships!'); // Translate
@define('USER_SELF_INFO', 'Logged in as %s (%s)'); // Translate
@define('USER_ALERT', 'Userinfo');
@define('USER_PERMISSION_NOTIFIER_DRAFT_MODE', 'You have not yet been granted the right to publish your entries directly. Until sufficient trust is built, inform your assigned editor-in-chief that your entry is ready for publication and approval.');
@define('USER_PERMISSION_NOTIFIER_RESET', 'In case of temporary revocation of rights, please clarify the reasons in a friendly personal conversation.');
@define('ADMIN_ENTRIES', 'Entries'); // Translate
@define('RECHECK_INSTALLATION', 'Recheck installation'); // Translate
@define('IMAGICK_EXEC_ERROR', 'Unable to execute: "%s", error: %s, return var: %d'); // Translate
@define('INSTALL_OFFSET_DESC', 'Enter the amount of hours between the date of your webserver (current: %clock%) and your desired time zone'); // Translate
@define('UNMET_REQUIREMENTS', 'Requirements failed: %s'); // Translate
@define('CHARSET', 'Charset');
@define('AUTOLANG', 'Use visitor\'s browser language as default');
@define('AUTOLANG_DESC', 'If enabled, this will use the visitor\'s browser language setting to determine the default language of your entry and interface language.');
@define('INSTALL_AUTODETECT_URL', 'Autodetect used HTTP-Host'); // Translate
@define('INSTALL_AUTODETECT_URL_DESC', 'If set to "true", Serendipity will ensure that the HTTP Host which was used by your visitor is used as your BaseURL setting. Enabling this will let you be able to use multiple domain names for your Serendipity Blog, and use the domain for all follow-up links which the user used to access your blog.'); // Translate
@define('CONVERT_HTMLENTITIES', 'Try to auto-convert to/from HTML entities? Check your DB import data first. (Mostly "No", if both use UTF-8.)');
@define('EMPTY_SETTING', 'You did not specify a valid value for "%s"!');
@define('USERCONF_REALNAME', 'Real name'); // Translate
@define('USERCONF_REALNAME_DESC', 'The full name of the author. This is the name seen by readers'); // Translate
@define('HOTLINK_DONE', 'File "%s" hotlinked.<br>Internal name: \'%s\'. Done.'); // Translate
@define('ENTER_MEDIA_URL_METHOD', 'Fetch method:'); // Translate
@define('ADD_MEDIA_BLAHBLAH_NOTE', 'Note: If you choose to hotlink to server, make sure you have permission to hotlink to the designated website, or the website is yours. Hotlink allows you to use off-site images without storing them locally.'); // Translate
@define('MEDIA_HOTLINKED', 'hotlinked'); // Translate
@define('FETCH_METHOD_IMAGE', 'Download image to your server'); // Translate
@define('FETCH_METHOD_HOTLINK', 'Hotlink to server'); // Translate
@define('DELETE_HOTLINK_FILE', 'Deleted the hotlinked file entitled <b>%s</b>'); // Translate
@define('SYNDICATION_PLUGIN_SHOW_MAIL', 'Show E-Mail addresses?');
@define('IMAGE_MORE_INPUT', 'Add more images'); // Translate
@define('BACKEND_TITLE', 'Additional information in Plugin Configuration screen'); // Translate
@define('BACKEND_TITLE_FOR_NUGGET', 'Here you can define a short custom string which is displayed in the Plugin Configuration screen together with the description of the plugin. If you have multiple stacked plugins or HTML nuggets with an empty title, this helps to distinct the plugins from another.'); // Translate
@define('CATEGORIES_ALLOW_SELECT', 'Allow visitors to display multiple categories at once?'); // Translate
@define('CATEGORIES_ALLOW_SELECT_DESC', 'If this option is enabled, a checkbox will be put next to each category in this sidebar plugin. Users can check those boxes and then see entries belonging to their selection.'); // Translate
@define('PAGE_BROWSE_PLUGINS', 'Page %s of %s, totaling %s plugins.');
@define('INSTALL_CAT_PERMALINKS', 'Permalinks');
@define('INSTALL_CAT_PERMALINKS_DESC', 'Defines various URL patterns to define permanent links in your blog. It is suggested that you use the defaults; if not, you should try to use the %id% value where possible to prevent Serendipity from querying the database to lookup the target URL.');
@define('INSTALL_PERMALINK', 'Permalink Entry URL structure');
@define('INSTALL_PERMALINK_DESC', 'Here you can define the relative URL structure beginning from your base URL to where entries may become available. You can use the variables %id%, %title%, %day%, %month%, %year% and any other characters.');
@define('INSTALL_PERMALINK_AUTHOR', 'Permalink Author URL structure');
@define('INSTALL_PERMALINK_AUTHOR_DESC', 'Here you can define the relative URL structure beginning from your base URL to where entries from certain authors may become available. You can use the variables %id%, %realname%, %username%, %email% and any other characters.');
@define('INSTALL_PERMALINK_CATEGORY', 'Permalink Category URL structure');
@define('INSTALL_PERMALINK_CATEGORY_DESC', 'Here you can define the relative URL structure beginning from your base URL to where entries from certain categories may become available. You can use the variables %id%, %name%, %parentname%, %description% and any other characters.');
@define('INSTALL_PERMALINK_FEEDCATEGORY', 'Permalink RSS-Feed Category URL structure');
@define('INSTALL_PERMALINK_FEEDCATEGORY_DESC', 'Here you can define the relative URL structure beginning from your base URL to where RSS-feeds from certain categories may become available. You can use the variables %id%, %name%, %description% and any other characters.');
@define('INSTALL_PERMALINK_ID_WARNING', 'If you remove the essential %id% variable, Serendipity can not create an exact relationship. This has effects on various accesses and subsequent processes and is not recommended without taking your own responsibility!');
@define('INSTALL_PERMALINK_ARCHIVESPATH', 'Path to archives');
@define('INSTALL_PERMALINK_ARCHIVEPATH', 'Path to archive');
@define('INSTALL_PERMALINK_CATEGORIESPATH', 'Path to categories');
@define('INSTALL_PERMALINK_UNSUBSCRIBEPATH', 'Path to unsubscribe comments');
@define('INSTALL_PERMALINK_DELETEPATH', 'Path to delete comments');
@define('INSTALL_PERMALINK_APPROVEPATH', 'Path to approve comments');
@define('INSTALL_PERMALINK_FEEDSPATH', 'Path to RSS Feeds');
@define('INSTALL_PERMALINK_PLUGINPATH', 'Path to single plugin');
@define('INSTALL_PERMALINK_ADMINPATH', 'Path to admin');
@define('INSTALL_PERMALINK_SEARCHPATH', 'Path to search');
@define('INSTALL_CAL', 'Calendar Type');
@define('INSTALL_CAL_DESC', 'Choose your desired Calendar format');
@define('REPLY', 'Svar');
@define('USERCONF_GROUPS', 'Group Memberships');
@define('USERCONF_GROUPS_DESC', 'This user is a member of the following groups. Multiple memberships are possible.');
@define('GROUPCONF_GROUPS', 'Selectable members of this group');
@define('MANAGE_GROUPS', 'Manage groups');
@define('DELETED_GROUP', 'Group #%d \'%s\' deleted.');
@define('CREATED_GROUP', 'A new group #%d \'%s\' has been created');
@define('MODIFIED_GROUP', 'The properties of group \'%s\' have been changed');
@define('GROUP', 'Gruppe');
@define('CREATE_NEW_GROUP', 'Create new group');
@define('DELETE_GROUP', 'You are about to delete group #%d \'%s\'. Are you serious?');
@define('SYNDICATION_PLUGIN_FEEDBURNERID', 'FeedBurner ID');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_DESC', 'The ID of the feed you wish to publish');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG', 'FeedBurner image');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG_DESC', 'Name of image to display (or leave blank for counter), located on feedburner.com, ex: fbapix.gif');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE', 'FeedBurner title');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE_DESC', 'Title (if any) to display alongside the image');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT', 'FeedBurner image text');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT_DESC', 'Text (if any) to display when hovering the image');
@define('SEARCH_TOO_SHORT', 'Your search-query must be longer than 3 characters. You can try to append * to shorter words, like: s9y* to trick the search into using shorter words.');
@define('INSTALL_DBPORT', 'Database port');
@define('INSTALL_DBPORT_DESC', 'The port used to connect to your database server');
@define('PLUGIN_GROUP_FRONTEND_EXTERNAL_SERVICES', 'Frontend: External Services');
@define('PLUGIN_GROUP_FRONTEND_FEATURES', 'Frontend: Features');
@define('PLUGIN_GROUP_FRONTEND_FULL_MODS', 'Frontend: Full Mods');
@define('PLUGIN_GROUP_FRONTEND_VIEWS', 'Frontend: Views');
@define('PLUGIN_GROUP_FRONTEND_ENTRY_RELATED', 'Frontend: Entry Related');
@define('PLUGIN_GROUP_BACKEND_EDITOR', 'Backend: Editor');
@define('PLUGIN_GROUP_BACKEND_USERMANAGEMENT', 'Backend: Usermanagement');
@define('PLUGIN_GROUP_BACKEND_METAINFORMATION', 'Backend: Meta information');
@define('PLUGIN_GROUP_BACKEND_TEMPLATES', 'Backend: Templates');
@define('PLUGIN_GROUP_BACKEND_FEATURES', 'Backend: Features');
@define('PLUGIN_GROUP_BACKEND_MAINTAIN', 'Backend: Maintenance');
@define('PLUGIN_GROUP_BACKEND_DASHBOARD', 'Backend: Startpage');
@define('PLUGIN_GROUP_BACKEND_ADMIN', ADMIN); // is constant, no quotes, no translate!
@define('PLUGIN_GROUP_IMAGES', 'Images');
@define('PLUGIN_GROUP_ANTISPAM', 'Antispam');
@define('PLUGIN_GROUP_MARKUP', 'Markup');
@define('PLUGIN_GROUP_STATISTICS', 'Statistics');

 // GROUP PERMISSIONS   no translate first part until ':', since config variable!
@define('PERMISSION_PERSONALCONFIGURATION', 'personalConfiguration: Access personal configuration');
@define('PERMISSION_PERSONALCONFIGURATIONUSERLEVEL', 'personalConfigurationUserlevel: Change userlevels');
@define('PERMISSION_PERSONALCONFIGURATIONNOCREATE', 'personalConfigurationNoCreate: Change "forbid creating entries"');
@define('PERMISSION_PERSONALCONFIGURATIONRIGHTPUBLISH', 'personalConfigurationRightPublish: Change right to publish entries');
@define('PERMISSION_SITECONFIGURATION', 'siteConfiguration: Access system configuration');
@define('PERMISSION_SITEAUTOUPGRADES', 'siteAutoUpgrades: Access system autoupgrades');
@define('PERMISSION_BLOGCONFIGURATION', 'blogConfiguration: Access blog-centric configuration');
@define('PERMISSION_ADMINENTRIES', 'adminEntries: Administrate entries');
@define('PERMISSION_ADMINENTRIESMAINTAINOTHERS', 'adminEntriesMaintainOthers: Administrate other user\'s entries');
@define('PERMISSION_ADMINIMPORT', 'adminImport: Import entries');
@define('PERMISSION_ADMINCATEGORIES', 'adminCategories: Administrate categories');
@define('PERMISSION_ADMINCATEGORIESMAINTAINOTHERS', 'adminCategoriesMaintainOthers: Administrate other user\'s categories');
@define('PERMISSION_ADMINCATEGORIESDELETE', 'adminCategoriesDelete: Delete categories');
@define('PERMISSION_ADMINUSERS', 'adminUsers: Administrate users');
@define('PERMISSION_ADMINUSERSDELETE', 'adminUsersDelete: Delete users');
@define('PERMISSION_ADMINUSERSEDITUSERLEVEL', 'adminUsersEditUserlevel: Change userlevel');
@define('PERMISSION_ADMINUSERSMAINTAINSAME', 'adminUsersMaintainSame: Administrate users that are in your group(s)');
@define('PERMISSION_ADMINUSERSMAINTAINOTHERS', 'adminUsersMaintainOthers: Administrate users that are not in your group(s)');
@define('PERMISSION_ADMINUSERSCREATENEW', 'adminUsersCreateNew: Create new users');
@define('PERMISSION_ADMINUSERSGROUPS', 'adminUsersGroups: Administrate usergroups');
@define('PERMISSION_ADMINPLUGINS', 'adminPlugins: Administrate plugins');
@define('PERMISSION_ADMINPLUGINSMAINTAINOTHERS', 'adminPluginsMaintainOthers: Administrate other user\'s plugins');
@define('PERMISSION_ADMINIMAGES', 'adminImages: Administrate media files');
@define('PERMISSION_ADMINIMAGESDIRECTORIES', 'adminImagesDirectories: Administrate media directories');
@define('PERMISSION_ADMINIMAGESADD', 'adminImagesAdd: Add new media files');
@define('PERMISSION_ADMINIMAGESDELETE', 'adminImagesDelete: Delete media files');
@define('PERMISSION_ADMINIMAGESMAINTAINOTHERS', 'adminImagesMaintainOthers: Administrate other user\'s media files');
@define('PERMISSION_ADMINIMAGESVIEW', 'adminImagesView: View media files');
@define('PERMISSION_ADMINIMAGESSYNC', 'adminImagesSync: Sync thumbnails');
@define('PERMISSION_ADMINIMAGESVIEWOTHERS', 'adminImagesViewOthers: View other user\'s media files');
@define('PERMISSION_ADMINCOMMENTS', 'adminComments: Administrate comments');
@define('PERMISSION_ADMINTEMPLATES', 'adminTemplates: Administrate templates');

@define('GROUP_ADMIN_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights, might implement security risks. There are at least 3 permission flags [<em>adminPluginsMaintainOthers</em>, <em>adminUsersMaintainOthers</em> and <em>siteConfiguration</em>] which should stick to the ADMINISTRATOR <b>only</b>! Otherwise, vital conditions of your blog are endangered. Compare and understand what are the main differences between you, the ADMIN, and between "Editors in CHIEF" and normal "USERs". The [<em>siteAutoUpgrades</em>] permission flag is for a special cased and assigned CHIEF only. Read in the ChangeLog, the Styx Sites Help Center or the german Book on how to use it!');
@define('GROUP_CHIEF_INFO_DESC', '<b>Keep in mind:</b> Changing or giving certain rights to normal USERs, might implement security risks. You should deeply check which permission flag should be allowed/removed, compared to a standard USER! Otherwise, vital conditions of certain areas are endangered. Compare and understand what are the main differences between you, the "Editor in CHIEF" and normal "USERs". Read in the Styx Sites Help Center or the german Book for more information!');

@define('INSTALL_BLOG_EMAIL', 'Blog\'s E-Mail address');
@define('INSTALL_BLOG_EMAIL_DESC', 'This configures the E-Mail address that is used as the "From"-Part of outgoing mails. Be sure to set this to an address that is recognized by the mailserver used on your host - many mailservers reject messages that have unknown From-addresses.');
@define('CATEGORIES_PARENT_BASE', 'Only show categories below...');
@define('CATEGORIES_PARENT_BASE_DESC', 'You can choose a parent category so that only the child categories are shown.');
@define('CATEGORIES_HIDE_PARALLEL', 'Hide categories that are not part of the category tree');
@define('CATEGORIES_HIDE_PARALLEL_DESC', 'If you want to hide categories that are part of a different category tree, you need to enable this. This feature made most sense in the past, when used in conjunction with a "multi-Blog" like system using the "Properties/Templates of categories" plugin. However, this is no longer the case, since this plugin in its version greater than/equal to v.1.50 can calculate hidden categories independently and better. So you should only use this option if you have a specific use case outside of said categorytemplates plugin, i.e if you choose multi categories by the categories checkbox selection.');
@define('CHARSET_NATIVE', 'Native');
@define('INSTALL_CHARSET', 'Charset selection');
@define('INSTALL_CHARSET_DESC', 'Here you can toggle UTF-8 or native (ISO, EUC, ...) charactersets. Some languages only have UTF-8 translations so that setting the charset to "Native" will have no effects. UTF-8 is suggested for new installations. Do not change this setting if you have already made entries with special characters - this may lead to corrupt characters. Be sure to read more on https://ophian.github.io/hc/en/i18n.html about this issue.');
@define('CALENDAR_ENABLE_EXTERNAL_EVENTS', 'Enable Plugin API hook');
@define('CALENDAR_EXTEVENT_DESC', 'If enabled, plugins can hook into the calendar to display their own events highlighted. Only enable if you have installed plugins that need this, otherwise it just decreases performance.');
@define('XMLRPC_NO_LONGER_BUNDLED', 'The XML-RPC API Interface to Serendipity is no longer bundled because of ongoing security issues with this API and not many people using it. Thus you need to install the XML-RPC Plugin to use the XML-RPC API. The URL to use in your applications will NOT change - as soon as you have installed the plugin, you will again be able to use the API.');
@define('PERM_READ', 'Read permission');
@define('PERM_WRITE', 'Write permission');
@define('PERM_DENIED', 'Permission denied.');
@define('INSTALL_ACL', 'Apply read-permissions for categories');
@define('INSTALL_ACL_DESC', 'If enabled, the usergroup permission settings you setup for categories will be applied when logged-in users view your blog. If disabled, the read-permissions of the categories are NOT applied, but the positive effect is a little speedup on your blog. So if you don\'t need multi-user read permissions for your blog, disable this setting.');
@define('PLUGIN_API_VALIDATE_ERROR', 'Configuration syntax wrong for option "%s". Needs content of type "%s".');
@define('PLUGIN_API_GENERIC_SUBOPTION_DESC', '<b>ATTENTION</b>: Certain options open or close pending suboptions [+] only after submission sets. Also, certain options can deactivate already set options or reset them to the default value, so that in case of a reconsideration a new setting or activation might be necessary.');
@define('USERCONF_CHECK_PASSWORD', 'Old Password');
@define('USERCONF_CHECK_PASSWORD_DESC', 'If you change the password in the field above, you need to enter the current user password into this field.');
@define('USERCONF_CHECK_PASSWORD_DESC_ADDNOTE', 'Use carefully, since any following permissible backend action will force you to a new login afterwards - so only usable once, per Login-Session!');
@define('USERCONF_CHECK_PASSWORD_ERROR', 'You did not specify the right old password, and are not authorized to change the new password. Your settings were not saved.');
@define('ERROR_XSRF', 'Your browser did not sent a valid HTTP-Referrer string. This may have either been caused by a misconfigured browser/proxy or by a Cross Site Request Forgery (XSRF) aimed at you. The action you requested could not be completed.');
@define('INSTALL_PERMALINK_FEEDAUTHOR_DESC', 'Here you can define the relative URL structure beginning from your base URL to where RSS-feeds from specific users may be viewed. You can use the variables %id%, %realname%, %username%, %email% and any other characters.');
@define('INSTALL_PERMALINK_FEEDAUTHOR', 'Permalink RSS-Feed Author URL structure');
@define('INSTALL_PERMALINK_AUTHORSPATH', 'Path to authors');
@define('AUTHORS', 'Authors');
@define('AUTHORS_ALLOW_SELECT', 'Allow to display multiple author views?');
@define('AUTHORS_ALLOW_SELECT_DESC', 'If this option is enabled, a checkbox will be put next to each author in this sidebar plugin.  Users can check those boxes and see entries matching their selection.');
@define('AUTHOR_PLUGIN_DESC', 'Shows a list of authors');
@define('CATEGORY_PLUGIN_TEMPLATE', 'Enable Smarty-Templates?');
@define('CATEGORY_PLUGIN_TEMPLATE_DESC', 'If this option is enabled, the plugin will utilize Smarty-Templating features to output the category listing. If you enable this, you can change the layout via the "plugin_categories.tpl" template file. Enabling this option will impact performance, so if you do not need to make customizations, leave it disabled.');
@define('CATEGORY_PLUGIN_SHOWCOUNT', 'Show number of entries per category?');
@define('AUTHORS_SHOW_ARTICLE_COUNT', 'Show number of articles next to author name?');
@define('AUTHORS_SHOW_ARTICLE_COUNT_DESC', 'If this option is enabled, the number of articles by this author is shown next to the authors name in parentheses.');
@define('CUSTOM_ADMIN_INTERFACE', 'Custom admin interface');

@define('COMMENT_NOT_ADDED', 'Kommentaren din kunne ikke legges til, fordi kommentarer for denne oppf�ringen enten har blitt deaktivert, du har angitt ugyldige data, eller kommentaren din ble fanget opp av anti-spam-m�linger.');
@define('INSTALL_TRACKREF', 'Enable referrer tracking?');
@define('INSTALL_TRACKREF_DESC', 'Enabling the referrer tracking will show you which sites refer to your articles. Today this is often abused for spamming, so you can disable it if you want.');
@define('CATEGORIES_HIDE_PARENT', 'Hide the selected parent category?');
@define('CATEGORIES_HIDE_PARENT_DESC', 'If you restrict the listing of categories to a specific category, by default you will see that parent category within the output listing. If you disable this option, the parent category name will not be displayed.');
@define('WARNING_NO_GROUPS_SELECTED', 'Warning: You did not select any group memberships. This would effectively log you out of the usergroup management, and thus your group memberships were not changed.');
@define('INSTALL_RSSFETCHLIMIT', 'Entries to display in Feeds');
@define('INSTALL_RSSFETCHLIMIT_DESC', 'Number of entries to display for each page on the RSS Feed.');
@define('INSTALL_CBAFETCHLIMIT', 'Comments to display per comment summary pages');
@define('INSTALL_CBAFETCHLIMIT_DESC', 'Number of comments to display for each page on the so called "Comment overview" /comments/ pages.');
@define('INSTALL_DB_UTF8', 'Enable DB-charset conversion');
@define('INSTALL_DB_UTF8_DESC', 'Issues a MySQL "SET NAMES" query to indicate the required charset for the database. Turn this on or off, if you see weird or missing characters in your blog.');
@define('ONTHEFLYSYNCH', 'Enable on-the-fly media synchronization');
@define('ONTHEFLYSYNCH_DESC', 'If enabled, Serendipity will compare the media database with the files stored on your server and synchronize the database and directory contents. This is - especially due to the additional variation formats - a rather time-consuming monitoring instrument and can increasingly slow down a growing MediaLibrary, since each call of the same must permanently run through all(!) files, check and re-evaluate, including the resulting necessary changes. But since the latter happens correspondingly often, this step becomes accordingly shorter. Otherwise use the first two "Media library: Rebuild Thumbs" actions in the maintenance section from time to time, which also include a final synchronization! So a "Yes" is recommended here if you either often work around directly in the file system of the MediaLibrary yourself, use this option only temporarily or do not notice any particular slowdown, or are a developer/tester with correspondingly many false/positive results.');
@define('USERCONF_CHECK_USERNAME_ERROR', 'The username cannot be left blank.');
@define('FURTHER_LINKS', 'Further Links');
@define('FURTHER_LINKS_S9Y', 'Serendipity Homepage');
@define('FURTHER_LINKS_S9Y_DOCS', 'Serendipity Documentation');
@define('FURTHER_LINKS_S9Y_BLOG', 'Official Blog');
@define('FURTHER_LINKS_S9Y_FORUMS', 'Forums');
@define('FURTHER_LINKS_S9Y_SPARTACUS', 'Spartacus');
@define('COMMENT_IS_DELETED', '(Comment removed)');

@define('CURRENT_AUTHOR', 'Current author');

@define('WORD_NEW', 'New');
@define('SHOW_MEDIA_TOOLBAR', 'Show toolbar within media selector popup?');
@define('MEDIA_KEYWORDS', 'Media keywords');
@define('MEDIA_KEYWORDS_DESC', 'Enter a list of ";" separated words that you want to use as pre-defined keywords for media items.');
@define('MEDIA_EXIF', 'Import EXIF/JPEG image data');
@define('MEDIA_EXIF_DESC', 'If enabled, existing EXIF/JPEG metadata of images will be parsed and stored in the database for display in the media gallery.');
@define('MEDIA_PROP', 'Media properties');
@define('MEDIA_PROP_STATUS', 'This Form values "alt", "comment"s and "title" as <b>public</b> media properties have not been saved yet, OR equal the default. Currently, an image title-attribute is auto-build by the files realname! Also, beware of saving the copyright value with the here eventually pre-set login name of yours, when you don\'t actually own the rights! Better use "unknown", or such, or leave empty.');
@define('MEDIA_CREATEVARS', 'Add additional image variations');

@define('DIALOG_DELETE_VARIATIONS_PERITEM', 'Yes [ENTER-key] will delete all occurrences of this file; No [SPACE-key] only deletes the image variations (if any), so that they can be rebuilt afterwards via the [+] icon; Cancel [ESC-key] will do nothing! "Yes" and "No" confirmation actions in the following can also be aborted.');
@define('DIALOG_DELETE_FILE_CONTINUE', 'Delete file "%s"... Continue ?');
@define('DIALOG_DELETE_VARIATIONS', 'Delete Variations');

@define('GO_ADD_PROPERTIES', 'Go & enter properties');
@define('MEDIA_PROPERTY_DPI', 'DPI');
@define('MEDIA_PROPERTY_COPYRIGHT', 'Copyright');
@define('MEDIA_PROPERTY_COMMENT1', 'Public Comment');
@define('MEDIA_PROPERTY_COMMENT2', 'Internal Comment');
@define('MEDIA_PROPERTY_TITLE', 'Title');
@define('MEDIA_PROP_DESC', 'Enter a list of ";" separated property fields you want to define for each media file');
@define('MEDIA_PROP_MULTIDESC', '(You can append ":MULTI" after any item to indicate that this item will contain long text instead of just some characters)');

@define('STYLE_OPTIONS_NONE', 'This theme/style has no specific options. To see how your template can specify options, read the Technical Documentation on "https://ophian.github.io/hc/en/templating.html#docs-theme-options" about "Configuration of Theme options".');
@define('STYLE_OPTIONS', 'Theme/Style options');

@define('PLUGIN_AVAILABLE_COUNT', 'Total: %d plugins.');

@define('SYNDICATION_RFC2616', 'Activate strict RFC2616 RSS-Feed compliance');
@define('SYNDICATION_RFC2616_DESC', 'NOT Enforcing RFC2616 means that all Conditional GETs to Serendipity will return entries last modified since the time of the last request. With that setting to "false", your visitors will get all articles since their last request, which is considered a good thing. However, some Agents like Planet act weird, if that happens, at it also violates RFC2616. So if you set this option to "TRUE" you will comply with that RFC, but readers of your RSS feed might miss items in their holidays. So either way, either it hurts Aggregators like Planet, or it hurts actual readers of your blog. If you are facing complaints from either side, you can toggle this option.');
@define('MEDIA_PROPERTY_DATE', 'Associated Date');
@define('MEDIA_PROPERTY_RUN_LENGTH', 'Run-Length');
@define('FILENAME_REASSIGNED', 'Automagically assigned new file name: %s');
@define('MEDIA_UPLOAD_SIZE', 'Max. file upload size');
@define('MEDIA_UPLOAD_SIZE_DESC', 'Enter the maximum filesize for uploaded files in bytes. This setting can be overruled by server-side settings in PHP.ini: upload_max_filesize, post_max_size, max_input_time all take precedence over this option. An empty string means to only use the server-side limits.');
@define('MEDIA_UPLOAD_SIZEERROR', 'Error: You cannot upload files larger than %s bytes!');
@define('MEDIA_UPLOAD_MAXWIDTH', 'Max. width of image files for upload');
@define('MEDIA_UPLOAD_MAXWIDTH_DESC', 'Enter the maximum image width in pixels for uploaded images.');
@define('MEDIA_UPLOAD_MAXHEIGHT', 'Max. height of image files for upload');
@define('MEDIA_UPLOAD_MAXHEIGHT_DESC', 'Enter the maximum image height in pixels for uploaded images.');
@define('MEDIA_UPLOAD_DIMERROR', 'Error: One setting prevents to upload image files larger than %s x %s pixels! Check your Configuration section: "%s" settings. You may want to additionally activate the "%s"-Option to make this work.');

@define('MEDIA_TARGET', 'Target for this link');
@define('MEDIA_TARGET_JS', 'Popup window (via JavaScript, adaptive size)');
@define('MEDIA_ENTRY', 'Isolated Entry');
@define('MEDIA_TARGET_BLANK', 'Popup window (via target=_blank)');

@define('MEDIA_DYN_RESIZE', 'Allow dynamic image resizing?');
@define('MEDIA_DYN_RESIZE_DESC', 'If enabled, the serendipity_admin_image_selector.php file can return images in any requested size via a GET variable. The results are cached, and thus can create a large filebase if you make intensive use of it.');

@define('MEDIA_DIRECTORY_MOVED', 'Directory and files were successfully moved to %s');
@define('MEDIA_DIRECTORY_MOVE_ERROR', 'Directory and files could not be moved to %s!');
#@define('MEDIA_DIRECTORY_MOVE_ENTRY', 'On Non-MySQL databases, iterating through every article to replace the old directory URLs with new directory URLs is <b>not</b> possible. You will need to manually edit your entries to fix new URLs. You can still move your old directory back to where it was, if that is too cumbersome for you.');
@define('MEDIA_DIRECTORY_MOVE_ENTRIES', 'Moved the URL of the moved directory in %s entries.');
@define('MEDIA_FILE_RENAME_ENTRY', 'The filename was changed in %s entries.');
@define('PLUGIN_ACTIVE', 'Active');
@define('PLUGIN_INACTIVE', 'Inactive');

@define('INSTALL_PERMALINK_COMMENTSPATH', 'Path to comments');
@define('PERM_SET_CHILD', 'Set the same permissions on all child directories');
@define('PERMISSION_FORBIDDEN_PLUGINS', 'Forbidden plugins');
@define('PERMISSION_FORBIDDEN_HOOKS', 'Forbidden events');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE', 'Enable Plugin ACL for usergroups?');
@define('PERMISSION_FORBIDDEN_PLUGINACL_ENABLE_DESC', 'If the option "Plugin ACL for usergroups" is enabled in the configuration, you can specify which usergroups are allowed to execute certain plugins/events.');
@define('PERMISSION_READ_WRITE_ACL_DESC', 'By default, the read/write permissions are set to "0", i.e. "All authors". However, if you set them as an administrator, for example to Standard editor, equal to "1", you can no longer change back afterwards, since you have withdrawn the right yourself. So make sure to always include higher-ranking user groups if you want them to continue to have access to it.');

@define('DELETE_SELECTED_ENTRIES', 'Delete selected entries');
@define('PLUGIN_AUTHORS_MINCOUNT', 'Show only authors with at least X articles');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET', 'Bookmarklet');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET_DESC', 'Bookmark this link and then use it on any page you want to Blog about, to quickly access your Serendipity Blogs backend entry form (when logged in).');
@define('IMPORT_WP_PAGES', 'Also fetch attachments and staticpages as normal blog entries?');
@define('USERCONF_CREATE', 'Disable user / forbid activity?');
@define('USERCONF_CREATE_DESC', 'If selected, the user will not have any editing or creation possibilities on the blog anymore. When logging in to the backend, he cannot do anything else apart from logging out and viewing his personal configuration.');
@define('CATEGORY_HIDE_SUB', 'Hide postings made to sub-categories?');
@define('CATEGORY_HIDE_SUB_DESC', 'By default, when you browse a category also entries of any subcategory are displayed. If this option is turned on, only postings of the currently selected category are displayed.');
@define('PINGBACK_SENDING', 'Sending pingback to URI %s...');
@define('PINGBACK_SENT', 'Pingback successful');
@define('PINGBACK_FAILED', 'Pingback failed: %s');
@define('PINGBACK_NOT_FOUND', 'No pingback-URI found.');
@define('CATEGORY_PLUGIN_HIDEZEROCOUNT', 'Hide archives link when no entries were made in that timespan (requires counting entries)');
@define('RSS_IMPORT_WPXRSS', 'WordPress eXtended RSS import, requires PHP5 and might take up much memory');
@define('SET_TO_MODERATED', 'Moderate');
@define('COMMENT_MODERATED', 'Comment #%s has successfully been set as moderated');
@define('CENTER', 'center');
@define('FULL_COMMENT_TEXT', 'Yes, with full comment text');

@define('SECURITY_ALERT', 'Security Alert');
@define('COMMENT_SUMMARY_STRIPPED_VIEW_SECURED', 'Disable HTML-comments mode for check. Then use EDIT or the toggle option for a secured preview.'); // no quotes!
@define('COMMENT_SUMMARY_STRIPPED_EMPTY', 'Empty, since removed probably bad injection.');
@define('COMMENT_SUMMARY_STRIPPED', 'HTML - Stripped by security! Review content in EDIT or VIEW mode.'); // no quotes!

@define('COMMENT_TOKENS', 'Use Tokens for Comment Moderation?');
@define('COMMENT_TOKENS_DESC', 'If tokens are used, comments can be approved and deleted by clicking the email links without requiring login access to the blog. Note that this is a convenience feature, and if your mails get hijacked, those people can approve/delete the referenced comment without further authentication.');
@define('COMMENT_NOTOKENMATCH', 'Moderation link has expired or comment #%s has already been approved or deleted');
@define('TRACKBACK_NOTOKENMATCH', 'Moderation link has expired or trackback #%s has already been approved or deleted');
@define('BADTOKEN', 'Invalid Moderation Link');

@define('CONFIRMATION_MAIL_ALWAYS', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n");
@define('CONFIRMATION_MAIL_ONCE', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled one-time mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n\nAfter you have done that, you can always post comments on that blog with your username and e-mail address without receiving such notifications.");
@define('INSTALL_SUBSCRIBE_OPTIN', 'Use Double-Opt In for comment subscriptions?');
@define('INSTALL_SUBSCRIBE_OPTIN_DESC', 'If enabled, when a comment is made where the person wants to be notified via e-mail about new comments to the same entry, he must confirm his subscription to the entry. This Double-Opt In is required by german law, for example.');
@define('CONFIRMATION_MAIL_SUBSCRIPTION', "Hello %s,\n\nYou have requested to be notified for comments to \"%s\" (<%s>). To approve this subscription (\"Double Opt In\") please click this link:\n<%s>\n.");
@define('NOTIFICATION_CONFIRM_SUBMAIL', 'Your confirmation of your comment subscription has been successfully entered.');
@define('NOTIFICATION_CONFIRM_MAIL', 'Your confirmation of the comment has been successfully entered.');
@define('NOTIFICATION_CONFIRM_SUBMAIL_FAIL', 'Your comment subscription could not be confirmed. Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must request a new confirmation mail.');
@define('NOTIFICATION_CONFIRM_MAIL_FAIL', 'Your comment confirmation could not be confirmed.  Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must send your comment again.');
@define('PLUGIN_DOCUMENTATION', 'Documentation');
@define('PLUGIN_DOCUMENTATION_LOCAL', 'Local Documentation');
@define('PLUGIN_DOCUMENTATION_CHANGELOG', 'Version history');
@define('SYNDICATION_PLUGIN_BIGIMG', 'Big Image');
@define('SYNDICATION_PLUGIN_BIGIMG_DESC', 'Display a (big) image at the top of the feeds in sidebar, enter full or absolute URL to image file. Set to "none" to show a textlink (the old default)');
@define('SYNDICATION_PLUGIN_FEEDNAME', 'Displayed name for "feed"');
@define('SYNDICATION_PLUGIN_FEEDNAME_DESC', 'Enter an optional custom name for the feeds (defaults to "feed" when empty)');
@define('SYNDICATION_PLUGIN_COMMENTNAME', 'Displayed name for "comment" feed');
@define('SYNDICATION_PLUGIN_COMMENTNAME_DESC', 'Enter an optional custom name for the comment feed');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD', '(If you enter an absolute URL with http://... here, this URL will be used as the redirection target in case you have enabled the "Force" option for FeedBurner. Note that this can also be a URL independent to FeedBurner. For new Google FeedBurner feeds, you need to enter http://feeds2.feedburner.com/yourfeedname here)');

@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD2', 'If you set this option to "Force" you can forward the RSS feed to any webservice, not only FeedBurner. Look at the option "Feedburner ID" below to enter an absolute URL)');
@define('NOT_WRITABLE_SPARTACUS', ' Recommended! (Required, when you plan to use Spartacus plugin for remote plugin download.)');
@define('MEDIA_ALT', 'ALT-Attribute (depiction or short description)');
@define('MEDIA_PROPERTY_ALT', 'Depiction (summary for ALT-Attribute)');

@define('MEDIA_TITLE', 'TITLE-Attribute (will be displayed on mouse over)');

@define('QUICKSEARCH_SORT', 'How should search-results be sorted?');

@define('QUICKSEARCH_SORT_RELEVANCE', 'Relevance');

@define('PERMISSION_HIDDENGROUP', 'Hidden group / Non-Author');

@define('SEARCH_FULLENTRY', 'Show full entry');
@define('NAVLINK_AMOUNT', 'Enter number of links in the navbar (and save this form)');
@define('NAV_LINK_TEXT', 'Enter the navbar link text');
@define('NAV_LINK_URL', 'Enter the full URL of your link');
@define('MODERATE_SELECTED_COMMENTS', 'Accept selected comments');
@define('WEBLOG', 'Weblog');
@define('ACTIVE_COMMENT_SUBSCRIPTION', 'Subscribed');
@define('PENDING_COMMENT_SUBSCRIPTION', 'Pending confirmation');
@define('NO_COMMENT_SUBSCRIPTION', 'Not subscribed');
@define('SUMMARY', 'Summary');

@define('ARCHIVE_SORT_STABLE', 'Stable Archives');
@define('ARCHIVE_SORT_STABLE_DESC', 'Sort the archive-pages descending, so they are stable. Default sort is ascending.');
@define('PLAIN_ASCII_NAMES', '(no special characters, umlauts)');

// New 2.0 constants
@define('SIMPLE_FILTERS', 'Simplified filters');
@define('SIMPLE_FILTERS_DESC', 'When enabled, search forms and filter functions are reduced to essential options. When disabled, you will see every possible filter option, like in the "Media library" or the "Edit entries" list, under condition of actual permission.');
@define('ENTRY_PAGE_PASSWORD_INFO_SET', 'Entries related passwords are - unlike login passwords - stored unencrypted, thus simple and unsecured.
You should not want to operate a security-relevant access system with them!');
@define('TOGGLE_SELECT', 'Mark for selection');
@define('MORE', 'More');
@define('ENTRY_STATUS', 'Entry status');
@define('SCHEDULED', 'Scheduled');
@define('PUBLISHED', 'Published');
@define('ENTRY_METADATA', 'Entry metadata');
@define('NAVIGATION', 'Navigation');
@define('MAIN_MENU', 'Main menu');
@define('MENU_PERSONAL', 'Personal menu');
@define('MENU_DASHBOARD', 'Dashboard');
@define('MENU_ACTIVITY', 'Activity');
@define('MENU_SETTINGS', 'Settings');
@define('MENU_TEMPLATES', 'Templates');
@define('MENU_PLUGINS', 'Plugins');
@define('MENU_USERS', 'Users');
@define('MENU_GROUPS', 'Groups');
@define('MENU_MAINTENANCE', 'Maintenance');
@define('ALIGN_TOP', 'Top');
@define('ALIGN_LEFT', 'Left');
@define('ALIGN_RIGHT', 'Right');
@define('SHOW_METADATA', 'Show metadata');
@define('RANGE_FROM', 'From');
@define('RANGE_TO', 'To');
@define('UPLOAD', 'Upload');
@define('DOWNLOAD', 'Download');
@define('ENTRY_PUBLISHED', 'Entry #%s published');
@define('PUBLISH_ERROR', 'Error publishing entry:');
@define('UPDATE_NOTIFICATION', 'Update notification');
@define('NEW_VERSION_AVAILABLE', 'New Serendipity version available: ');
@define('MOVE', 'Move');
@define('MOVE_UP', 'Move up');
@define('MOVE_DOWN', 'Move down');
@define('INSTALL_NEW_SIDEBAR_PLUGIN', 'Install a new sidebar plugin');
@define('INSTALL_NEW_EVENT_PLUGIN', 'Install a new event plugin');
@define('TEMPLATE_OPTIONS', 'Template options');
@define('CURRENT_TEMPLATE', 'Current Template');
@define('TEMPLATE_INFO', 'Show template info');
@define('AVAILABLE_TEMPLATES', 'Available Templates');
@define('TIMESTAMP_RESET', 'The timestamp has been reset to the current time.');

@define('CLEANCOMPILE_PASS', '[smarty clearCompiledTemplate(%s)]');
@define('CLEANCOMPILE_FAIL', 'No files available for clearing.');
@define('CLEANCOMPILE_TITLE', 'Clear template cache');
@define('CLEANCOMPILE_INFO', 'This will purge all compiled template files of the current active template. Compiled templates will be automatically re-created on demand by the Smarty framework.');
@define('INSTALLER_KEY', 'Key');
@define('INSTALLER_VALUE', 'Value');
@define('CURRENT_TAB', 'Current tab: ');
@define('PINGBACKS', 'Pingbacks');
@define('PINGBACK', 'Pingback');
@define('NO_PINGBACKS', 'No Pingbacks');
@define('GROUP_NAME_DESC', "Use as uppercased eg. 'EXAMPLE_GROUP' name, but not as a constant 'USERLEVEL_XYZ' group name.");
@define('INSTALLER_CLI_TOOLS', 'Server-side command line tools');
@define('INSTALLER_CLI_TOOLNAME', 'CLI tool');
@define('INSTALLER_CLI_TOOLSTATUS', 'Executable?');
@define('VIDEO', 'Video');
@define('RESET_STATUS', 'Reset status');
@define('RESET_FILTERS', 'Reset filters');
@define('UPDATE_FAILMSG', 'Check for new Serendipity version failed. This can happen because either the URL %s is down, your server blocks outgoing connections or there are other connection issues.');
@define('UPDATE_FAILACTION', 'Disable automatic update check');
@define('UPDATE_NOTIFICATION_DESC', 'Show the update notification on the backend startpage, and for which channel? Beta includes Stable releases.');
@define('FRONTEND', 'Frontend');
@define('BACKEND', 'Backend');
@define('MEDIA_UPLOAD_RESIZE', 'Resize on upload');
@define('MEDIA_UPLOAD_RESIZE_DESC', 'Resize images according to configured maximum/minimum dimensions on upload using Javascript. This will also change the uploader to use Ajax and thus remove the Property-Button.<br>NOTICE: This option might prevent other extended options to behave in order, in special, when the imageselectorplus event plugin is used!');
@define('LOG_LEVEL', 'Log Level');
@define('LOG_LEVEL_DESC', 'At certain places in the Serendipity code we have placed debugging breakpoints. If this option is set to "Debug", it will write this debug output to templates_c/logs/. You should only enable this option if you are experiencing bugs in those areas, or if you are a developer. Setting this option to "Error" will enable logging PHP errors, overwriting the PHP error_log setting.');
@define('DEBUG', 'Debug');
@define('CUSTOM_CONFIG', 'Custom configuration file');
@define('PLUGIN_ALREADY_INSTALLED', 'Plugin already installed, and does not support multiple installation ("stackable").');
@define('PLUGIN_UPDATES_DONE', 'All Plugins updated!');
@define('STACKABLE_PLUGIN', 'Stackable plugin!');
@define('STACKED_PLUGIN', 'Stacked plugin!');
@define('MULTISTACK_PLUGIN', 'Multi-stacked plugin!');
@define('INSTALL_DBPREFIX_INVALID', 'The database table name prefix must not be empty and may only contain letters, numbers and the underscore character.');
@define('SYNDICATION_PLUGIN_SUBTOME', 'subToMe');
@define('SYNDICATION_PLUGIN_SUBTOME_DESC', 'Load the external subToMe javascript and show the internal subToMe icon-button, a layer to make feed subscription easier.');
@define('INSTALL_BACKENDPOPUP', 'Enable use of popup windows for the backend');
@define('INSTALL_BACKENDPOPUP_DESC', 'Do you want to use popup windows for some backend functionality? When disabled (default), inline modal dialogs will be used for e.g. the category selector and MediaLibrary. On the other hand this popup-window option only works for some elements, like the MediaLibrary and some plugins. Others, like categories, will show up embedded.');
@define('UPDATE_STABLE', 'stable');
@define('UPDATE_BETA', 'beta');
@define('SYNDICATION_PLUGIN_FEEDFORMAT', 'Feed format');
@define('SYNDICATION_PLUGIN_FEEDFORMAT_DESC', 'Which format shall be used for all feeds. Both are supported in all common readers');
@define('SYNDICATION_PLUGIN_COMMENTFEED', 'Comment feed');
@define('SYNDICATION_PLUGIN_COMMENTFEED_DESC', 'Show an additional link to a comment feed. This should be interesting only to the blogauthor itself');
@define('SYNDICATION_PLUGIN_FEEDICON', 'Feed icon');
@define('SYNDICATION_PLUGIN_FEEDICON_DESC', 'Show a (big) icon instead of a textlink to the feed. Set to "none" to deactivate, or to "feedburner" to show a feedburner counter if an id is given below');
@define('SYNDICATION_PLUGIN_CUSTOMURL', 'Custom URL');
@define('SYNDICATION_PLUGIN_CUSTOMURL_DESC', 'If you want to link to the custom feed specified in the Blog configuration, enable this option.');
@define('FEED_CUSTOM', 'Custom feed URL');
@define('FEED_CUSTOM_DESC', 'If set, a custom feed URL can be set to forward Feedreaders to a specific URL. Useful for statistical analyzers like Feedburner, in which case you would enter your Feedburner-URL here.');
@define('FEED_FORCE', 'Force custom feed URL?');
@define('FEED_FORCE_DESC', 'If enabled, the URL entered above will be mandatory for Feedreaders, and your usual feed cannot be accessed from clients.');
@define('NO_UPDATES', 'No plugin updates are available');
@define('PLUGIN_GROUP_ALL', 'All categories');

@define('CONF_USE_AUTOSAVE', 'Enable autosave-feature');
@define('CONF_USE_AUTOSAVE_DESC', 'When enabled, the text you enter into Blog entries will be periodically saved in your browser\'s session storage. If your browser crashes during writing, the next time you create a new entry, the text will be restored from this autosave.');
@define('INSTALL_CAT_FEEDS', 'Feed Settings');
@define('INSTALL_CAT_FEEDS_DESC', 'Customize how Serendipity feeds Feeds');

@define('CATEGORY_PLUGIN_SHOWALL', 'Show a link to "All categories"?');
@define('CATEGORY_PLUGIN_SHOWALL_DESC', 'If enabled, a link for the visitor to display the Blog with no category restriction will be added.');
@define('SERENDIPITY_PHPVERSION_FAIL', 'Serendipity requires a PHP version >= %2$s - you are running a lower version (%1$s) and need to upgrade your PHP version. Most providers offer you to switch to newer PHP versions through their admin panels or .htaccess directives.');
@define('TOGGLE_VIEW', 'Switch category view mode');
@define('PUBLISH_NOW', 'Publish this entry now (sets current time and date)');
@define('EDITOR_TAGS', 'Tags');
@define('EDITOR_NO_TAGS', 'No tags');
@define('DASHBOARD_ENTRIES', 'In Progress');
@define('INSTALL_PASSWORD2', 'Admin password (verify)');
@define('INSTALL_PASSWORD2_DESC', 'Password for admin login, enter again to verify.');
@define('INSTALL_PASSWORD_INVALID', 'Your entered passwords for the administrator user do not match.');
@define('INSTALL_BACKENDPOPUP_GRANULAR', 'Force specific backend embed/popup behavior');
@define('INSTALL_BACKENDPOPUP_GRANULAR_DESC', 'If popups are generally disabled (see above), the use of "popups" can be bypassed in special places. This applies to real window-popups with ("<em>images</em>" via entry form and "<em>comments</em>" via comment Reply), and <u>vice versa</u> in writing, for modal "popups" only, with (<em>categories, tags, links</em>) as (embedded default) settings, in that they are written here for the first two and exactly <b>not</b> listed for the latter three, on account of the default settings. The complete (comma separated) list is: ');
@define('START_UPDATE', 'Starting Update ...');
@define('UPDATE_ALL', 'Update All');
@define('JS_FAILURE', 'The Serendipity JavaScript-library could not be loaded. This can happen due to PHP or Plugin errors, or even a malformed browser cache. To check the exact error please open <a href="%1$s">%1$s</a> manually in your browser and check for error messages.');
@define('THEMES_PREVIEW_BLOG', 'See demo on blog.s9y.org');
@define('SYNDICATION_PLUGIN_XML_DESC', 'Set to "none" if you only want to show a text link.');
@define('MULTICHECK_NO_ITEM', 'No item selected, please check at least one. <a href="%s">Return to previous page</a>.');
@define('MULTICHECK_NO_DIR', 'No directory selected, please choose one. <a href="%s">Return to previous page</a>.');
@define('BULKMOVE_INFO', 'Bulk-move info');
@define('BULKMOVE_INFO_DESC', '
Place a tick next to the relevant file names and select down here the location to move them to.<br>
<strong>Note:</strong><br>
This action takes effect immediately without further prompting. All selected files are physically moved to the new location and all blog entries are searched for the relevant path parts and altered. <sup>[1]</sup><br>
This automatic replacement also runs through static page entries of the staticpage plugin and entries of the possibly used (<em>output-ready</em>) entry cache of the entryproperties plugin (<em>see Maintenance</em>).<br>
<strong>Addendum:</strong><br>
If you use the latter cache, you may <sup>[1]</sup> have to save changed blog entries for the cache again, due to the changed image path. <sup>[2]</sup>
<p>
    <sup>(1) <em>Such replacement routines are only as good as the expected source material. Especially on old blogs with old entries ahead the "Serendipity Styx" era, you should always check the results personally. It would be good to have some prior knowledge and an idea of which entries the shift would affect.</em></sup><br>
    <sup>(2) <em>Check your frontend (while logged in) for possible image entries that are no longer displayed and simply save those entries (using the "Edit" link there) via the entry form of the backend for the entry cache, if they have already received the new image file path as a result of the move.</em></sup>
</p>');
@define('FIRST_PAGE', 'First Page');
@define('LAST_PAGE', 'Last Page');
@define('MEDIA_PROPERTIES_DONE', 'Properties of #%d changed.');
@define('DIRECTORY_INFO', 'Directory info');
@define('DIRECTORY_INFO_DESC', 'Directories reflect their physical folders directory name. If you want to change or move directories which contain items, you have two choices. Either create the directory or subdirectory you want, then move the items to the new directory via the MediaLibrary and afterwards, delete the empty old directory there. Or completely change the whole old directory via the edit directory button below and rename it to whatever you like (existing subdir/ + newname). This will move all directories and items and change referring Blog entries.');
@define('MEDIA_RESIZE_EXISTS', 'File dimensions already exist!');
@define('USE_CACHE', 'Enable caching');
@define('USE_CACHE_DESC', 'Enables an internal cache to not repeat specific database queries. This reduces the load on servers with medium to high traffic and improves page load time.');
@define('CONFIG_PERMALINK_PATH_DESC', 'Please note that you have to use a "prefix" (keyword) so that Serendipity can properly map the URL to the proper action. You may change the prefix to any unique ASCII name, but not remove it. This applies to all path prefix definitions. Also note that this prefix is not already used elsewhere in your URL path to the blog.');

@define('HIDE_SUBDIR_FILES', 'Hide Files of Subdirectories');

@define('UPDATE_NOTIFICATION_URL', 'Update RELEASE-file URL');
@define('UPDATE_NOTIFICATION_URL_DESC', 'This is Styx! Do not change, if not applying a different RELEASE file location for custom core downloads in combination with the Serendipity Autoupdate plugin. An intranet URL could then be alike "https://localhost/git/you/styx/docs/RELEASE" or whatever. A here provided URL points to a file containing the current released Serendipity stable and beta version numbers per line, eg. "stable:5.3.0". The Styx repository default file URL is: "https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE".');

@define('URL_NOT_FOUND', '[ 404 ] - The page you have requested could not be found. Continue reading here.');

@define('CONFIG_ALLOW_LOCAL_URL', 'Allow to fetch data from local URLs');
@define('CONFIG_ALLOW_LOCAL_URL_DESC', 'By default, it is forbidden due to security constrains to fetch data from local URLs to prevent Server-Side Request Forgery (SSRF). If you use a local intranet, you can enable this option to allow fetching data.');
@define('REMOTE_FILE_INVALID', 'The given URL "%s" appears to be local and is not allowed to be fetched. You can allow this by setting the option "Allow to fetch data from local URLs" in your Blog configuration.');

@define('INSTALLER_TOKEN', 'Temporary installer token');
@define('INSTALLER_TOKEN_CHECK', 'Token check');
@define('INSTALLER_TOKEN_STATUS', 'Token status');
@define('INSTALLER_TOKEN_NOTE', 'To continue a secure installation, you need to create a file called "%s" with the string "<strong>&lt;?php $install_token = \'%s\'; ?&gt;</strong>" within this directory. Once that file exists, you must continue the installation within the next %s minutes and do not close your browser window, and you need to have Cookies enabled.');
@define('INSTALLER_TOKEN_MISMATCH', 'You are not authorized to continue installation, since your install token is not identical (%s) to the one contained in the file "%s". Please make sure you created the file with the right content. You can get a new token by deleting the file.');
@define('INSTALLER_TOKEN_MATCH', 'Your secure install token is valid.');

@define('INSTALLER_COMPLETE', 'Installation complete.');

@define('DASHBOARD_INFO_HEADER', 'Overview');
@define('DASHBOARD_INFO_CONTENT', 'Shortcuts');
@define('DASHBOARD_INFO_EMPTY', 'We don\'t have enough data to show anything useful. No pending comments, future or draft entries are available.');
@define('DASHBOARD_SYSTEM_TICKER', 'Remote System Notification');
@define('DASHBOARD_SYSTEM_TICKER_DESC', 'Allows hideable remote system notifications in the backend dashboard panel.');
@define('DASHBOARD_SYSINFO_GREETING', 'Hello Administrator ! The following messages require your brief administrative attention:');
@define('DASHBOARD_SYSINFO_HIDE_NOTE', 'Check for: &raquo;&nbsp;<em>I understand! Don\'t show me again!</em>&nbsp;&laquo;');
@define('COMMENTS_PENDING', 'Pending comments');
@define('FUTURES_AVAILABLE', 'Future entries');
@define('DRAFTS_AVAILABLE', 'Draft entries');

@define('MEDIA_GALLERY_SELECTION', 'This particular selection for media galleries only shows directory images of the same level. It does not contain a statement to also display the images of sub folders, as you might be used to. The number of possible preview images that can be displayed at the same time is limited to <b>48 items</b>. Restructure your MediaLibrary accordingly!<br>This media gallery directory selection only shows thumbnails (optionally configurable linking to the big picture). If your preview images do not meet the standard width of 400px, and are much smaller than the defined gallery format of 260px for the per row option, it is possible that you get into display problems in this selection as well as afterwards in the frontend entry.<br>The <b>order</b> of displayed gallery items is not configurable here and can only be changed afterwards within the displayed source code of your editors dropping textarea window. If you there use the WYSIWYG-Editor, please do <strong>not</strong> simply use the internal <em>drag & drop</em> feature of the WYSIWYG-mode (editor) window, since that may destroy or at least mess up the gallery selection for your entry. Though <b>it is</b> possible to use the drag & drop feature, you then need to check the dragged & dropped image moves in source afterwards to be correctly placed within the gallery block container.');

@define('IMAGE_LINK_TO_BIG', 'Link to the larger image');

@define('MAINTENANCE_COLUMN_SORTNOTE', 'This page uses a variable block column sorting; It may change the column block order dynamically by expanding block content.');

@define('UTF8MB4_MIGRATION_TITLE', 'Database: UTF-8-MB4 migration');
@define('UTF8MB4_MIGRATION_ERROR', 'An error occurred with the UTF-8 migration:<br>&nbsp;&nbsp;&nbsp;&nbsp;<em>%s</em><br>Do <b>not</b> continue without having solved the error!');
@define('UTF8MB4_MIGRATION_TASK_RETURN', 'The migration task returned:');
@define('UTF8MB4_MIGRATION_TASK_HAVE', 'The following SQL commands have been executed:');
@define('UTF8MB4_MIGRATION_TASK_CAN', 'The following SQL commands can be executed:');
@define('UTF8MB4_MIGRATION_TASK_DONE_SHORT', 'UTF-8-MB4 charset');
@define('UTF8MB4_MIGRATION_TASK_DONE', 'Your Blog is using the UTF-8 charset with Multibyte-Extension.');
@define('UTF8MB4_MIGRATION_INFO', 'When using Serendipity Styx 2.4+ with MySQLi and UTF-8 charsets (this is the default), the database tables and indexes can be migrated from UTF-8 to UTF-8 with Multibyte-Extension, to also support unicode characters outside the Basic Multilingual Plane (BMP), such as <b>Emojis</b>. Utf8mb4 is a superset of utf8.');
@define('UTF8MB4_MIGRATION_BUTTON_CHECK', 'Simulate / Check');
@define('UTF8MB4_MIGRATION_BUTTON_EXECUTE', 'Execute');
@define('UTF8MB4_MIGRATION_INFO_DESC', 'This task will allow you to perform an upgrade from UTF-8 to UTF8MB4. This will also automatically try to adjust index sizes and issue converting MySQL server commands.
<p>This is a task that can probably fail to properly convert your data, so be sure to make a full SQL backup before you perform the upgrade!</p>
Please run the simulation first, to get a list of SQL statements, that will be issued on your installation, and make sure there are no (red) errors in the simulation or first execution before continuing.
Run the <b>executor</b> [execute] task as long it appears, to fully convert the database tables and re-set the new dbCharset to "utf8mb4" in your configuration file. Do not reload your browser tab. You are finished with a <b>green</b> success message!');
@define('UTF8MB4_MIGRATION_FAIL', 'Your current installation either does not use the UTF-8 charset already, does not use the MySQLi driver, or the server version is lower than 5.5.3 and does not support UTF8MB4. To be able to use UTF8MB4, make sure your Blog is configured for the UTF-8 charset, and make sure existing data is also converted to UTF-8 (by using a tool like mysqldump to export, convert to UTF-8 and import).');

#@define('MEDIA_THUMBURL_REPLACE_ENTRY', 'On Non-MySQL databases, iterating through every article to replace the old thumbSuffix URLs with the new thumbSuffix URLs is <b>not</b> possible. You will need to manually edit your entries to fix these old URLs. You can still rename your thumbSuffix back to the old name, or just live with all current stored suffixes (see above), if that is too cumbersome for you.');

@define('HTML_COMMENTS', 'Allow HTML comments');
@define('HTML_COMMENTS_DESC', 'If the RICH Text editor (WYSIWYG) option in personal preferences is set true, you may additionally allow tag-restricted HTML comments and "pre/code" tag parts displayed in backend and frontend pages, but edited by Editor in backend only. Keep in mind: This options liberates old comments to display their content. So better check them up before (!), that you don\'t have accidentally approved spoofed content in your database stored comments. Otherwise, RICH Text editor comments are also suitable to get rid of annoying bot program spammers. This option currently only works completely with the standard "Pure" theme and a few others and is therefore only recommended there!');

@define('CORE_THEMES', 'Styx Core Themes');
@define('THEMEMANAGER', 'Theme Cleanup Manager');
@define('THEMEMANAGER_ZOMB_OK', 'Well done! Chosen themes purged!');
@define('THEMEMANAGER_LOCALTHEMES', 'Check old local themes');
@define('THEMEMANAGER_SUBMIT', 'Purge selected themes');
@define('THEMEMANAGER_INFO', 'Multi-select and purge old theme zombies. Make Styx happy and delete this old crap! :) If you ever need it again just load it with Spartacus, to get the last maintained fresh version. On the other hand, don\'t do this when having changed anything within such theme without having a backup!');

@define('GALLERY_ORIENTATION', 'Gallery Orientation');
@define('GALLERY_ORIENTATION_PERCOL', 'Thumb item order per <b>column</b>, arranged vertical (recommended). No size restriction!');
@define('GALLERY_ORIENTATION_PERROW', 'Thumb item order per <b>row</b>, left to right. Max-width: 260px restriction.');
@define('GALLERY_ORIENTATION_STRICTCOL', 'Strict <b>columns</b> depend containers content size:');

@define('ADDITIONAL_PROPERTIES_BY_PLUGIN', 'Additional properties by Plugin: %s');

@define('PLUGINMANAGER', 'Plugin Cleanup Manager');
@define('PLUGINMANAGER_ZOMB_OK', 'Well done! Old plugin zombies purged!');
@define('PLUGINMANAGER_LOCALPLUGINS', 'Check for local plugin zombies');
@define('PLUGINMANAGER_SUBMIT', 'Purge plugin zombies');
@define('PLUGINMANAGER_INFO', 'Often there are older systems with plugins that were never activated or stored as local zombies in the database and thus never synchronized. Such plugins appear in the list as local plugins when searching for new plugins to install, although a newer version might be found on Spartacus. Such plugin zombies should be deleted so that the latest version can always be accessed in case of need. Sometimes, however, these are developer versions that have only fallen into oblivion. So think carefully before deleting marked plugins.');

@define('MEDIA_RENAME_ERROR_RELOAD', 'If you see this message there was probably an error message and something could not be done. To be able to undo any partial changes you may have made, the current MediaLibrary page is not reloaded by the following button. This means that the same action can be repeated directly with the old name so that the successing changes are back to the old state. On the other hand, partial changes that have already been made only become visible when you reload the current page manually! It is a question of which option you give priority to. The former is recommended, even if the error message probably repeats itself similarly, in order to only then trace the cause of the actual error message. Copy the error message for the subsequent debugging!');

@define('THUMBFILE_SIZE', 'Thumb size');
@define('PATH', 'Path');

@define('MEDIA_EXTENSION_FAILURE', 'The uploaded file "<b>%s</b>" was identified as a mime originating "<b>%s</b>" file, with a media library database stored extension name of "<b>%s</b>". This extension name has a length format of "%s", being <b>greater</b> than the allowed max extension length of "%s" for image files. Maybe it is not in the right format for <b>web based</b> image files ("bmp", "gif", "jpg", "jpeg", "png", "tiff", "webp", "avif")?');
@define('MEDIA_EXTENSION_FAILURE_REPAIR', 'Please change the file manually in your "uploads/" file system and run a sync for thumbnail creation in the backends "Maintenance" section afterwards, to make it available as a valid image. Deleting the file via the MediaLibrary and additionally uploading a corrected file version will do too, but it shatters the incremental ID counter of the database and is therefore not necessarily recommended.');

@define('SYNC_OPTION_BUILDVARIATIONS', 'Build Image Picture-Element Format-Variations');
@define('SYNC_OPTION_PURGEVARIATIONS', 'Purge all Image Picture-Element Format-Variations');
@define('SYNC_BUILD_VARIATIONS', 'Build Picture-Element Format-Variations');
@define('SYNC_PURGED_VARIATIONS', 'Purged Picture-Element Format-Variation files');
@define('SYNC_VARIATION_ITERATION_LIST_TITLE', 'Variations Image list iteration for purge request:');
@define('SYNC_IMAGE_LIST_ITERATION_RANGE_PART', 'Image list iteration part: <b>%s</b> of <b>"%s"</b> items in total');
@define('SYNC_IMAGE_LIST_ITERATION_RANGE_DONE', 'Iteration <b>%s</b> %s. <b>%s</b> items have been successfully created.');

@define('PLEASESELECT', 'Please select');
@define('WORD_XOR', 'Either / Or');
@define('FORMATS', 'Image format');
@define('VARIATION', 'Image variation');
@define('MEDIA_PROPERTIES_SELECT_INFO_DESC', 'If a files selection change is necessary: Either use the directory <b>OR</b> the Image format selection change per submit.<br>You cannot change both at the same time! This will also not work if a filename with this new format already exists. Make sure to have this checked before!');
@define('MEDIA_PROPERTIES_FORMAT_VARIATIONS', '<b>CAREFULLY NOTE FOR WEBP / AVIF FORMAT CASES:</b><br>ORIGIN files format variations probably already exist and are not affected by this change. This action here turns your ORIGIN file and Thumbnail into the variation format, but uses a quality level of 100% when using the "GD Lib", to avoid any quality loss. (<em>On the other hand the ImageMagick Library is set to "auto" here.</em>) For this very reason you should not use WebP/AVIF formats for <b>GD</b> cases, where your existing file format has already been optimized for the Web. In this case formatting to variation formats will probably just blow up the files filesize.');

@define('ENTRY_QUICKPIN', 'Set as a temporary Quick-Pin to the entries list');

@define('COMMENT_CHANGE_PARENT_INFO', 'Be careful changing the replyTo comment.<br><strong>Know</strong> what you do! (Check the Link c# ID)');

@define('ERROR_TRY_ANOTHER_USERNAME', 'Please try another username');
@define('ERROR_TRY_ANOTHER_GROUPNAME', 'Please try another groupname');
@define('ERROR_DONT_SHOOT_YOURSELF', 'You should never delete yourself: %s: %s, %s.');
@define('ERROR_DONT_CUT_YOUR_WHINEYARD', 'You should never delete the highest GROUP LEVEL you are in: %s: %s.');

@define('MEDIA_SERVE_INFO', 'Serve media buttons description info');
@define('PICTURE_FORMAT_BUTTON_DESC', 'Simple img element - <b>vs</b> - The modern & recommended & containerized form of delivering images including Variations! Normally called "responsive images" container, but here used for responsive Variation formats!');

@define('ENABLEAVIF', 'Enable use of AVIF Variations up from PHP 8.1');
@define('ENABLEAVIF_DESC', 'Image AVIF variations can be very demanding on resources, since a lot of Ram and CPU/GPU cores are needed to encode images into the AV1 format. Mass uploads and mass conversions (see "Maintenance") are therefore not recommended. Learn to handle on some examples before you generally allow to keep it enabled. PHP 8.1 still lacks a crucial build-in feature to read size information from AVIF files using the usual methods. For the time being, this also means that the image functions of the MediaLibrary "Resize this image" and "Rotate image 90 degrees" cannot be used for all formats when using AVIF, since each of these actions affects the original image as well as its variations. PHP 8.2 solves this issue by adding the missing feature.');

