<?php

/**
 *  @version 1.0
 *  @author Konrad Bauckmeier <kontakt@dd4kids.de>
 *  @translated 2009/06/03
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Spamschutz');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Mehrere M�glichkeiten, um Kommentarspam einzud�mmen. Dies ist das Kernst�ck aller Anti-Spam-Ma�nahmen. Nicht entfernen!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Spamschutz: Ung�ltiger Kommentar!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Spamschutz: Ein weiterer Kommentar kann innerhalb so kurzer Zeit nicht �bermittelt werden.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Dieses Blog ist im "Notfall Kommentar"-Modus. Bitte kommen Sie sp�ter wieder.');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Keine doppelten Kommentare erlauben');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Verbietet Benutzern einen Kommentar zu �bermitteln, der gleichlautend bereits besteht.');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Notfall-Blockade von Kommentaren');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', '�bergangsweise Kommentare zu allen Eintr�gen verbieten. N�tzlich, wenn das Blog unter andauerndem Spam-Beschuss leidet.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'IP-Block Intervall');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Schr�nkt die Anzahl an Kommentare pro IP ein, indem nur alle X Minuten ein Kommentar erlaubt wird. Hilfreich, um Spamfluten derselben IP abzuwehren.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Captchas aktivieren');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Erfordert die Eingabe eines zuf�lligen Buchstabenfolge vom Benutzer, damit ein Kommentar angenommen wird. Diese Eingabe kann von Spambots nicht get�tigt werden und verhindert so automatische Kommentare. Jedoch k�nnen behinderte oder blinde Personen mit der Darstellung solcher Eingabegrafiken Probleme haben. Um sichtbare Captchas ganz zu vermeiden, k�nnen Sie das erweiternde Spamblock Bee Plugin ausprobieren.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Um maschinelle und automatische �bertragung von Spamkommentaren zu verhindern, bitte die Zeichenfolge im dargestellten Bild in der Eingabemaske eintragen. Nur wenn die Zeichenfolge richtig eingegeben wurde, kann der Kommentar angenommen werden. Bitte beachten Sie, dass Ihr Browser Cookies unterst�tzen muss, um dieses Verfahren anzuwenden.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Bitte die dargestellte Zeichenfolge in die Eingabemaske eintragen!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Hier die Zeichenfolge der Spamschutz-Grafik eintragen: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'Sie haben nicht die richtige Spamschutz-Zeichenfolge eingetragen, die in der Grafik dargestellt wurde. Bitte sehen Sie sich dieses Bild erneut an und tragen Sie die korrekte Zeichenfolge ein.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Captchas k�nnen auf Ihrem Server nicht dargestellt werden. Sie ben�tigen GDLib und die freetype Bibliotheken, sowie die richtigen .TTF Dateien.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Captchas nach X Tagen erzwingen');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Captchas k�nnen abh�ngig vom Alter des Artikels eingeblendet werden. Tragen Sie das Minimalalter eines Artikels in Tagen ein, ab dem Captchas erforderlich werden sollen. Falls auf 0 gesetzt, sind Captchas immer erforderlich.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Kommentarmoderation nach X Tagen erzwingen');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'Eingehende Artikelkommentare k�nnen, abh�ngig vom Alter des Artikels, automatisch auf "Moderation" gestellt werden, folgend "auto-moderiert" genannt. Tragen Sie hier das Minimalalter eines Artikels in Tagen ein, ab dem jeder Kommentar zun�chst automatisch in die Moderations-Warteschlange gestellt wird. 0 bedeutet, dass "auto-moderiert" nicht verwendet wird.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Erforderliche Anzahl an Links f�r Moderation');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'Wenn in einem Kommentar eine bestimmte Anzahl an Links vorhanden ist, kann der Kommentar automatisch auf "Moderation" gestellt werden. Falls auf 0 gesetzt, wird diese Linkpr�fung nicht vorgenommen.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Erforderliche Anzahl an Links f�r Abweisung');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Wenn in einem Kommentar eine bestimmte Anzahl an Links vorhanden ist, kann der Kommentar automatisch abgelehnt werden. Falls auf 0 gesetzt, wird diese Linkpr�fung nicht vorgenommen.');

@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Aufgrund einiger Bedingungen wird der Kommentar moderiert und erst nach Best�tigung des Blog-Eigent�mers dargestellt.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Hintergrundfarbe des Captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'RGB Werte eingeben: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Speicherplatz f�r das Logfile');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Einige Informationen �ber die Abweisung/Moderation von Kommentaren kann in ein Logfile geschrieben werden. Wenn diese Option auf einen leeren Wert gesetzt wird, findet keine Protokollierung statt.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Notfall-Blockade');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Doppelter Kommentar');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'IP-Block');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Captcha ung�ltig (Eingegeben: %s, Erwartet: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Moderation nach X Tagen');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Zu viele Links');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'Zu viele Links');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'E-Mail-Adressen bei Kommentatoren verstecken');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Zeigt in den Kommentaren keine E-Mail Adressen der jeweiligen Kommentatoren an');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Die angegebene E-Mail-Adresse wird nicht dargestellt, sondern nur f�r eventuelle Benachrichtigungen verwendet.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Protokollierung von fehlgeschlagenen Kommentaren');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Die Protokollierung von fehlgeschlagenen Kommentaren und deren Gr�nden kann auf mehrere Arten durchgef�hrt werden.');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Einfache Datei (siehe Option "Logfile")');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Datenbank');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Keine Protokollierung');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Behandlung von per API �bermittelten Kommentaren');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'Diese Einstellung bestimmt, wie per API abgegebene Kommentare (Trackbacks, Pingbacks, wfw:commentApi) behandelt werden. Falls diese Einstellung auf "moderieren" gestellt ist, m�ssen alle solche Kommentare immer best�tigt werden. Falls auf "abweisen" gestellt, werden solche Kommentare global nicht erlaubt. Bei der Einstellung "keine" werden solche Kommentare wie andere behandelt.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'moderieren');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'abweisen');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'Keine API-erstellten Kommentare (u.a. Trackbacks) erlaubt');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_DATE', 'Keine Trackbacks au�erhalb Zeitfenster');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Wortfilter aktivieren');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Durchsucht Kommentare nach speziellen Zeichenketten und markiert diese als Spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Wortfilter f�r URLs');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Regul�re Ausdr�cke sind erlaubt, Zeichenketten durch Semikolon (;) zu trennen. Das @-Zeichen muss mit \\@, der Punkt als \\. angegeben werden.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Wortfilter f�r Autorennamen');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Ung�ltige E-Mail-Adresse!');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'Auf ung�ltige E-Mail-Adressen pr�fen?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Pflichtfelder');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Geben Sie die Liste von Pflichtfeldern bei der Abgabe eines Kommentars ein. Mehrere Felder k�nnen mit "," getrennt werden. Verf�gbare Felder sind: name, email, url, replyTo, comment');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'Sie haben das Feld "%s" nicht ausgef�llt!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Anti-Spam-Ma�nahmen konfigurieren');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Diesen Autor via Spamschutz blockieren');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Diese URL via Spamschutz blockieren');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL', 'Diese E-Mail via Spamschutz blockieren');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Blockade dieses Autors via Spamschutz aufheben');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Blockade dieser URL via Spamschutz aufheben');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL', 'Blockade dieser E-Mail via Spamschutz aufheben');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Artikeltitel ist derselbe wie der Kommentar!');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Kommentare abweisen, die Bekanntes enthalten');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE_DESC', 'Kommentare abweisen, die nur aus dem Artikeltitel oder aus einer Kombination von Artikel- und Blogtitel bestehen. Wird oft von automatischen Bots benutzt.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_SCRAMBLE', 'St�rkere Captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE', 'Spamblock f�r Autoren deaktivieren');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_DESC', 'Autoren der aktivierten Benutzergruppen k�nnen Kommentare ohne Spampr�fung schreiben.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET', 'Akismet API Key');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_DESC', 'Akismet.com ist ein zentraler Anti-Spam und Blacklisting-Dienst. Er kann eingehende Kommentare analysieren und mit einer Liste abgleichen um so SPAM zu entdecken. Akismet wurde f�r WordPress entwickelt, kann aber auch von anderen Blog-Anwendungen genutzt werden. Sie ben�tigen jedoch einen API Schl�ssel von http://www.akismet.com, indem Sie sich dort registrieren. Falls Sie diesen Wert leer lassen, wird Akismet nicht verwendet.');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_FILTER', 'Behandlung von Akismet-Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST', 'Von Akismet.com gefiltert.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Wortfilter f�r Inhalt');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS', 'Wortfilter f�r E-Mail-Adressen');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'Trackbacks/Pingbacks: URLs pr�fen');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'Einen API Kommentar (Trackback/Pingback) nur dann zulassen, wenn Ihre URL auch auf der Zielseite genannt wird.');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'API-Kommentar - Blog URL nicht gefunden.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_TREAT', 'Was soll mit auto-moderierten Kommentaren passieren?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT', 'Zuweisung nach der Auto-Moderation?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT_DESC', 'Nachtr�gliche Behandlung von auto-moderierten Track-/Pingbacks');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT', 'Auto-Moderation nach X Tagen erzwingen');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_DESC', 'Alle eingehenden Trackbacks/Pingbacks zu einem Artikel k�nnen, abh�ngig vom Alter des Artikels, automatisch auf "Moderation" gestellt werden, folgend "auto-moderiert" genannt. Tragen Sie hier das Minimalalter eines Artikels in Tagen ein, ab dem jedes Trackback/Pingback zun�chst automatisch in die Moderations-Warteschlange gestellt wird. 0 bedeutet, dass "auto-moderiert" nicht verwendet wird.');

@define('PLUGIN_EVENT_SPAMBLOCK_CSRF', 'Direktkommentare verbieten? (XSRF-Schutz)');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_DESC', 'Falls aktiviert, ist es Besuchern nicht m�glich, durch direktes Anspringen des zu kommentierenden Artikels einen Kommentar abzugeben. Dies blockt Spambots, aber auch Personen, die beispielsweise direkt aus ihrem RSS-Reader heraus kommentieren wollen oder die Cookies deaktiviert haben. Technisch wird dies erreicht, in dem ein spezieller Hash-Wert gesetzt wird, der nur bei g�ltiger Session-ID vorhanden ist. Weiterhin wird dadurch verhindert, dass mittels XSRF-Angriffen Kommentare unter ihrem Namen abgesetzt werden k�nnen. ');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON', 'Ihr Kommentar enthielt keinen g�ltigen Session-Hash. Kommentare auf diesem Blog k�nnen nur mit aktivierten Cookies hinterlassen werden, und Sie m�ssen bereits eine weitere URL des Blogs ge�ffnet haben, bevor Sie einen Kommentar absenden k�nnen!');

@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS', 'SPAM IP Adressen via HTaccess blocken?');
@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS_DESC', 'Wenn Sie diese Option einschalten, dann werden IPs von denen SPAM gesendet wurde zu Ihrer .htaccess Datei hinzugef�gt. Die .htaccess Datei wird regelm��ig mit den abgelehnten IPs des letzten Monats erneuert.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOOK', 'So sehen Ihre Captchas im Moment aus. Nachdem Sie die Einstellungen ge�ndert und gespeichert haben k�nnen Sie durch einen Klick auf diese das Aussehen hier erneuern.');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION', 'Trackbacks/Pingbacks: IP Validierung');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_DESC', 'Soll die IP des Senders bei Trackbacks/Pingbacks mit der IP des Hosts �bereinstimmen, auf den der Kommentar gesetzt werden soll? (EMPFOHLEN!)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION', 'IP Validierung : %s [%s] != Sender IP [%s]');

@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_DESC', 'Falls deaktiviert wird keine E-Mail-Pr�fung ausgef�hrt. Falls auf "Ja" gesetzt wird eine E-Mail-Adresse auf syntaktische Korrektheit gepr�ft. "Immer best�tigen" bedeutet, dass ein Kommentator seine Kommentare jedesmal per E-Mail best�tigen muss. "Einmal best�tigen" hei�t, dass er beim ersten Kommentare seine Identit�t best�tigt, und danach immer ohne weitere Moderation kommentieren darf.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ONCE', 'Einmal best�tigen');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ALWAYS', 'Immer best�tigen');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL', 'Sie erhalten nun eine E-Mail-Benachrichtigung, mit der Sie ihren Kommentar freischalten k�nnen.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_INFO', 'Um einen Kommentar hinterlassen zu k�nnen, erhalten Sie nach dem Kommentieren eine E-Mail mit Aktivierungslink an ihre angegebene Adresse.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER', 'Antispam-Server');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER_DESC', 'F�r welchen Antispam-Server soll obiger API-Key gelten? Die anonymisierten Varianten bedeuten, dass alle �bertragenen Daten zu den Antispam-Servern keine Angabe von Name oder E-Mail-Adresse enthalten. Dies reduziert jedoch auch die Spam-Erkennungsrate.');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS', 'TypePad Antispam');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET', 'Akismet');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS_ANON', 'TypePad Antispam (anonymisiert)');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET_ANON', 'Akismet (anonymisiert)');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE', 'URLs von IP-Validierung ausnehmen');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE_DESC', 'URLs, die von der IP-Validierung ausgeschlossen werden sollen. ' . PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);

@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_TITLE', 'Spamblock Wartung');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN', 'Aufr�umen der Datenbank Spam-Logs');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN_DESC', 'R�umt Datenbank-Logeintr�ge auf, die bestimmten Kriterien unterliegen. Dies sollte periodisch angesto�en werden, da Spammer die Spamblog Logs kontinuierlich aufbl�hen und das Blog immer weiter verlangsamen. Erfahrungsgem�� sind, je nach Einstellung der vorhandenen Spamblog Plugins, die beiden Typen (moderate, rejected) haupts�chlich mit Spam best�ckt.');

@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_BUTTON', 'L�sche: Alle');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_DESC', 'L�sche <b>alle</b> Log-Eintr�ge des Typs: \'REJECTED\'; und vom Typ: \'MODERATE\' in der Datenbank-Tabelle "spamblocklog", die ein leeres comment Feld haben. Augenblicklich sind %d Eintr�ge zu diesen Typen enthalten.');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_BUTTON', 'L�sche: Selektiv');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_DESC', 'L�sche <b>Einzeln</b> oder per <b>Mehrfachauswahl</b> vom Tabellenfeld "reason" LIKE "items". Gilt f�r die Typen: \'REJECTED\' und \'MODERATE\'!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MSG_DONE', 'L�schung erfolgt!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SELECT', 'Einzelselektion nach Kriterien');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_BUTTON', 'Debug Log-Sicherung');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_DESC', 'Schreibe die zu l�schenden spamblocklog Eintr�ge in die heutige Debug Log Datei. (Siehe Wartung ... Serendipity Logfiles.) Da dies sehr gro� werden kann, sollte dies nur f�r echte Debugging Zwecke erfolgen!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_LOGMSG_DONE', 'Geschrieben in Debug Logger!');

@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC', 'Zeitfenster f�r erlaubte Kommentare');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_DESC', 'Um zu vermeiden, dass �ltere Beitr�ge ihres Blogs mit Kommentaren geflutet werden, kann die (Form) Kommentarfunktion eines Artikels generell nur f�r einen Zeitraum von X Tagen seit dem Artikeldatum erlaubt werden. Der default Wert ist "0" und erlaubt (User) Kommentare zu jedem vorhandenen Artikel ohne Altersbegrenzung (sofern nicht anderweitig beschr�nkt).');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT', 'Trackbacks nur im Zeitfenster erlauben?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT_DESC', '"Ja" wird valide Trackbacks/Pingbacks, die nach diesem Zeitfenster f�r Blog Eintr�ge eintreffen, blockieren und abweisen. (Andere Optionen k�nnen dies m�glicherweise beeinflussen.)');

@define('PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION', 'Haupt-Konfiguration');
@define('PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION_DESC', 'Die Benennung unterscheidet sich intern als (User) Kommentare und (Blog) Trackbacks/Pingbacks. Allgemein wird hier das Wort "Kommentar(e)" f�r beide Erscheinungsformen verwendet, wenn nicht gesondert unterschieden (s.a. "Trackbacks") oder darauf hingewiesen wird, oder der Kontext eindeutig ist.');

