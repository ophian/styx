<?php

/**
 *  @version 1.0
 *  @author Konrad Bauckmeier <kontakt@dd4kids.de>
 *  @translated 2009/06/03
 *
 *  @version 1.52
 *  @author Ian
 *  @translated 2017/04/14
 */
@define('PLUGIN_EVENT_ENTRYPROPERTIES_TITLE', 'Erweiterte Eigenschaften von Artikeln');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DESC', 'Eine Vielzahl von Methoden zur Erweiterung des Eintragsformulars, wie z.B. Cache, nicht-�ffentliche Artikel, Dauerhafte Artikel, Rechtemanagement, benutzerdefinierte Felder, etc.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS', 'Dauerhafte Artikel');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS', 'Artikel k�nnen gelesen werden von');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE', 'mir selbst');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS', 'Co-Autoren');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC', 'allen');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE', 'Artikel cachen?');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC', 'Falls diese Option aktiviert ist, wird eine Cache-Version des Artikels gespeichert. Dieses Caching wird zwar die Performance erh�hen, die Flexibilit�t anderer Plugins aber eventuell einschr�nken. Sollten Sie den Rich-Text Editor verwenden (wysiwyg) ist ein cache eigentlich sinnlos, au�er Sie verwenden viele Plugins die das Ausgabemarkup weiter ver�ndern.');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE', 'Cachen aller Artikel');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT', 'Suche nach zu cachenden Artikeln...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO', 'Bearbeite Artikel %d bis %d');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING', 'Erzeuge Cache f�r Artikel #%d, <em>%s</em>...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHED', 'Artikel gecached.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE', 'Alle Artikel gecached.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED', 'Caching der Artikel ABGEBROCHEN.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL', ' (insgesamt %d Artikel vorhanden)...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NL2BR', 'Automatischen Zeilenumbruch deaktivieren');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE', 'Nicht in Artikel�bersicht zeigen');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS', 'Leserechte auf Gruppen beschr�nken');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC', 'Wenn aktiviert, k�nnen Leserechte abh�ngig von Gruppen vergeben werden. Dies wirkt sich stark auf die Performance der Artikel�bersicht aus. Aktivieren Sie die Option daher nur, wenn Sie sie wirklich ben�tigen.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS', 'Leserechte auf Benutzer beschr�nken');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC', 'Wenn aktiviert, k�nnen Leserechte abh�ngig von einzelnen Benutzern vergeben werden. Dies wirkt sich stark auf die Performance der Artikel�bersicht aus. Aktivieren Sie die Option daher nur, wenn Sie sie wirklich ben�tigen.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS', 'Eintragsinhalt im RSS-Feed verstecken');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS_DESC', 'Falls aktiviert, wird dieser Artikel im RSS-Feed ohne Inhalt dargestellt und sofort per URL aufgerufen.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS', 'Freie Felder');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1', 'Zus�tzliche, freie Felder k�nnen in Ihrem Theme an beliebigen Stellen eingesetzt werden. Daf�r m�ssen Sie nur Ihr entries.tpl-Template bearbeiten und Smarty-Tags wie {$entry.properties.ep_MyCustomField} an gew�nschter Stelle einf�gen. Bitte beachten Sie den Pr�fix ep_ f�r jedes Feld! ');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2', 'Geben Sie hier eine Liste von kommaseparierten Feldnamen an, die Sie f�r die Eintr�ge verwenden m�chten. Keine Sonderzeichen und Leerzeichen benutzen. Beispiel: "Customfield1, Customfield2". Zus�tzliche, freie Felder k�nnen in Ihrem Theme an beliebigen Stellen eingesetzt werden. Daf�r m�ssen Sie nur Ihr entries.tpl-Template bearbeiten und Smarty-Tags wie {$entry.properties.ep_MyCustomField} an gew�nschter Stelle einf�gen. Bitte beachten Sie hier das Pr�fix "ep_" f�r jedes Feld!');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3', 'Die Liste verf�gbarer freier Felder kann in der <a href="%s" target="_blank" rel="noopener" title="' . PLUGIN_EVENT_ENTRYPROPERTIES_TITLE . '">Plugin-Konfiguration</a> bearbeitet werden.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC4', 'Sie k�nnen den Standardwert f�r jedes benutzerdefinierte Feld eintragen, in dem Sie "Customfield1:Standardwert1, Customfield2:Standardwert2" benutzen. Wenn Sie Sonderzeichen wie ":" und "," im Standardwert nutzen m�ssen, f�gen Sie einen "\\" backslash voran, beispielsweise "Standardwert1:Ich will\\:Coookies\\, Muffins und W�rstchen, Standardwert2:Danke\\, ich habe fertig". F�r die bessere Lesbarkeit, k�nnen Sie eine neue Zeile vor jedem benutzerdefinierten Feld einf�gen.');

// Next lines were translated on 2009/06/03
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DISABLE_MARKUP', 'Formatierungs-PlugIns f�r diesen Eintrag deaktivieren');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS', 'Verwende erweiterte Datenbankabfragen');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS_DESC', 'Wenn aktiviert, werden zus�tzliche Datenbankabfragen ausgef�hrt. Damit wird es m�glich, "dauerhafte", nicht in der "Artikel�bersicht aufgef�hrte", und im RSS-Feed "versteckte Artikel" zu verwenden. Wenn diese Funktionen nicht ben�tigt werden, kann das Abschalten der Abfragen die Geschwindigkeit verbessern.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE', 'Reihenfolge der Optionen');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE_DESC', 'Hier kann ausgew�hlt werden, welche Optionen in welcher Reihenfolge im Editiermodus des Artikels angezeigt werden.');

// Next lines were translated on 2017/04/02
@define('PLUGIN_EVENT_ENTRYPROPERTIES_EMPTYBOX', 'Funktionslose (Geschwister-)Leer-Box');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE_DESC', 'Dies ist der Cache des entryproperties event Plugins. Er ist <u>verschieden</u> vom Cache in der "%s" / "%s" Option. Er erlaubt bereits formatierte Eintr�ge in der Datenbank abzulegen, so wie sie normalerweise durch die gesetzten Markup Plugins, zB. nl2br, oder markdown, textile, etc. f�r das Frontend behandelt ausgegeben werden.<p>Wenn sie das Caching hier erlauben, werden Sie zu einer Seite weitergeleitet, wo sie die Eintr�ge des Blogs in 25-iger Schritten portionsweise durchlaufen. Dies geschieht zur besseren Kontrolle und damit die Ressourcen des Systems nicht �berschritten werden wenn viele Eintr�ge zugleich formatiert und abgespeichert werden m�ssen.</p>Sobald dies getan ist, sind keine weiteren �nderungen in der Reihenfolge oder im Austausch von installierten Markup Plugins f�r die Ausgabe m�glich, ohne das dieser Cache erneut durchlaufen wird. Nat�rlich werden erneut gespeicherte alte Eintr�ge �ber das Eintragsformular auch f�r den Cache Eintrag gespeichert. Dieses Verhalten muss erinnert werden, wenn zuk�nftig Konfigurationen des Systems vorgenommen werden, damit man nicht durcheinander kommt.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_MULTI_AUTHORS', 'Mehrere Autoren');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_RECOMMENDED_SET', 'Artikel�bersichten zeigen alle "einfachen" [body] Eintragsfelder, zB. auch dann, wenn die Einzel-Eintragsansicht durch ein hier gesetztes Passwort gesch�tzt ist. Ansonsten k�nnen Sie in diesem Fall den sch�tzenswerten Inhalt aber auch in das erweiterte Feld einf�gen.');

