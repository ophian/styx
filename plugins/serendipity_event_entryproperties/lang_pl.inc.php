<?php

/**
 *  @version
 *  @author Kostas CoSTa Brzezinski <costa@kofeina.net>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_ENTRYPROPERTIES_TITLE', 'Rozszerzone w�a�ciwo�ci wpis�w');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DESC', '(buforowanie, wpisy niepubliczne, wpisy "przyklejone")');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS', 'Zaznacz ten wpis jako "przyklejony"');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS', 'Wpisy mog� by� czytane przez');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE', 'Mnie');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS', 'Wsp�autor�w');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC', 'Wszystkich');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE', 'Zezwala� na buforowanie (cache) wpis�w?');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC', 'Po w��czeniu tej opcji przy ka�dym zapisie wpisu b�dzie tworzona jego wersja umieszczana w cache (podr�cznym buforze strony). To spowoduje wzrost wydajno�ci ale mo�e spowodowa� problemy przy wsp�dzia�aniu z innymi wtyczkami. If you use the rich-text editor (wysiwyg) a cache is actually useless, unless you use many plugins that further change the output markup.');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE', 'Buforuj (cache) wpisy');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT', 'Pobieranie nast�pnej porcji wpisow...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO', 'Pobieranie wpis�w %d do %d');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING', 'Buforowanie (cache) wpisu #%d, <em>%s</em>...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHED', 'Wpis umieszczono w buforze (cache).');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE', 'Buforowanie wpis�w (cache) uko�czone.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED', 'Buforowanie wpis�w ANULOWANE.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL', ' (��cznie %d wpis�w)...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE', 'Ukryj wpis (nie b�dzie wy�wietlany przy przegl�dzie wpis�w i na stronie g��wnej)');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS', 'U�yj ogranicze� grup');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC', 'Je�li opcja zostanie w��czona, b�dzie mo�na zdefiniowa� dla jakich grup (i ich cz�onk�w) wpis b�dzie widoczny. To ustawienie ma ogromny wp�yw na wydajno�� wi�c u�ywaj wy��cznie je�li na prawd� potrzebujesz tej funkcjonalno�ci.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS', 'U�yj ogranicze� u�ytkownik�w');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC', 'Je�li opcja zostanie w��czona, b�dzie mo�na zdefiniowa� dla jakich konkrtenych u�ytkownik�w wpis b�dzie widoczny. To ustawienie ma ogromny wp�yw na wydajno�� wi�c u�ywaj wy��cznie je�li na prawd� potrzebujesz tej funkcjonalno�ci.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS', 'Nie pokazuj tre�ci w RSS');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS_DESC', 'Je�li ta opcja zostanie w��czona, tre�� wpisu nie b�dzie pokazywana w kanale RSS.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS', 'Pola u�ytkownika');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1', 'Dodatkowe pola mog� byc u�yte w Twoim schemacie w miejscach, w kt�rych chcesz je pokaza�. Aby uzyska� ten efekt wyedytuj plik entries.tpl swojego schematu i wstaw tagi Smarty {$entry.properties.ep_MyCustomField} w kodzie HTML gdzie uwa�asz to za stosowne. Zwr�� uwag� na prefiks ep_ dla ka�dego pola.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2', 'Tu mo�na wpisa� nazwy (rozdzielone przecinkami) dodatkowych p�l, kt�re b�d� dost�pne przy edycji ka�dego wpisu. Nie nalezy u�ywa� znak�w specjalnych lub spacji dla nazw tych p�l. Przyk�ad: "Customfield1, Customfield2". ' . PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1);
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3', 'Lista dost�pnych p�l uzytkownika mo�e by� zmieniona w module <a href="%s" target="_blank" rel="noopener" title="' . PLUGIN_EVENT_ENTRYPROPERTIES_TITLE . '">konfiguracji wtyczki</a>.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_DISABLE_MARKUP', 'Wy��cz wtyczki Znacznik�w dla tego wpisu:');

