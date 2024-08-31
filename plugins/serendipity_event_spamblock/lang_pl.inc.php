<?php

/**
 *  @version 
 *  @author Kostas CoSTa Brzezinski <costa@kofeina.net>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Obrona przed spamem');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'R�ne metody na zwalczanie spamu w komentarzach. Jest to gl�wny trzon dzialan antyspamowych. Nie usuwaj!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Obrona przed spamem: Nieprawid�owy komentarz.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Obrona przed spamem: Nie mo�esz wprowadzi� kolejnego komentarza tak pr�dko. Prosz�, poczekaj chwil� (obrona przed floodem).');

@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Ten blog znajduje si� w trybie "Ca�kowita blokada komentarzy". Prosz�, wr�� tu za jaki� czas, kiedy ten tryb zostanie zniesiony.');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Nie zezwalaj na zduplikowane komentarze');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Nie zezwalaj u�ytkownikom na dodanie komentarza, kt�ry ma tak� sam� zawarto�� jak dopiero co dodany komentarz (kolejne dodanie takiego samego komentarza)');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Ca�kowita blokada komentarzy');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Czasowe wy��czenie mo�liwo�ci komentowania dla wszystkich wpis�w. Przydatne kiedy Tw�j blog jest pod zmasowanym spamerskim atakiem.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'Czas blokowania adresu IP');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Zezw�l na komentarze z danego adresu IP co n minut. Przydatne dla blokowania komentarzowych flood�w (wiele komentarzy z tego samego adresu IP w kr�tkim czasie).');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'W��cz Captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Wymusza na u�ytkowniku wpisanie specjalnego losowego kodu wy�wietlanego w wygenrowanym obrazku. Prosz�, zwr�� uwag� na to, �e ludzie maj�cy problemy ze wzrokiem mog� mie� problemy z odczytaniem kodu. To avoid having to use visible Captchas at all, try out the extending Spamblock Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Prosz�, wpisz widoczny na obrazku kod do odpowiedniego pola. Tw�j komentarz zostanie dodany tylko gdy wpisany kod b�dzie si� zgadza� z tym widocznym na obrazku. Prosz�, upewnij si�, �e Twoja przegl�darka ma w��czon� obs�ug� cookies (ciasteczek) lub Tw�j komentarz nie przejdzie poprawnie weryfikacji.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Wpisz ci�g znak�w, kt�ry tu widzisz do odpowiedniego pola!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Wpisz ci�g znak�w widoczny na obrazku powy�ej: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'Wprowadzi�e�(a�) nieprawid�owy ci�g. Zerknij na obrazek i wprowad� odpowiedni ci�g ponownie.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Captcha nie b�dzie dzia�a�o na Twoim serwerze. Potrzebujesz zainstalowanych bibliotek GDLib i freetype oraz upewnij si�, �e odpowiednie pliki .ttf znajuj� si� w katalogu wtyczki.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Wymu� Captcha po ilu dniach');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Mo�esz wymusi� uzycie Captcha po okre�lonej ilo�ci dni. Wprowad� ilo�c dni, po kt�rych wprowadzenie Captcha b�dzie wymagane. Je�li ustawione na 0 - b�dzie wymagane zawsze.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Wymu� moderowanie komentarzy po ilu dniach');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'Mo�esz ustawi� wym�g moderowania wszystkich komentarzy. Wprowad� ilo�� dni, po kt�rych komentarze powinny podlega� auto-moderacji. 0 wy��cza automoderacj�.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Jak wiele link�w musi wyst�pi� by komentarz podlega� moderacji');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'Je�li w komentarzu wyst�pi podana ilo�� link�w b�dzie on wymaga� moderacji. 0 oznacza wy��czenie tej opcji.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Jak wiele link�w musi wyst�pi� by komentarz zosta� odrzucony');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Je�li w komentarzu wyst�pi podana ilo�� link�w, komentarz zostanie automatycznie odrzucony. 0 oznacza wy��czenie tej opcji.');

@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Z powodu warunk�w okre�lonych przez administratora bloga, Tw�j komentarz zosta� oznaczony jako "wymagaj�cy sprawdzenia".');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Kolor t�a captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Wprowad� warto�ci RGB: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Po�o�enie pliku z logiem');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Informacja o odrzuconych/wymagaj�cych moderowania komentarzach mo�e by� przechowywania w pliku. Wyczy�� to pole je�li chcesz wy��czy� logowanie.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Nag�y wypadek: mo�liwo�� komentowania zosta�a wy��czona');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Zduplikowany komentarz');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'Zablokowane IP');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'B��dnie wprowadzone captcha (wprowadzono: %s, powinno by�: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Auto-moderacja po X dniach');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Zbyt wiele link�w');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'Zbyt wiele link�w');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Ukryj adresy e-mail komentuj�cych');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Adresy e-mail komentuj�cych nie b�d� pokazywane');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Adresy e-mail nie b�d� pokazywane i b�d� u�ywane tylko do cel�w wysy�ania powiadomie� drog� e-mailow�');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Wybierz metod� logowania');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Logi o odrzuconych komentarzach mog� by� przechowywane w Bazie danych lub w pliku tekstowym');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Plik (patrz "Po�o�enie pliku z logiem")');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Baza danych');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Bez logowania');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Jak obs�ugiwa� komentarze dokonywane via API');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'To ustawienie odnosi si� do komentarzy przesy�anych przez API (�lady (Trackbacks), WFW:commentAPI). Je�li wybrane jest ustawienie "moderuj", wszystkie te komentarze zawsze musz� by� wpierw zatwierdzone. Je�li wybrane jest ustawienie "odrzucaj" - taki spos�b komentowania jest zupe�nie wy��czony. Je�li wybrane jest ustawienie "�adne" - komentarze dokonywane w opisany spos�b b�d� traktowane jak zwyk�e komentarze.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'moderuj');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'odrzucaj');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'Niedozwolone jest komentowanie via API (jak np. �lady (Trackbacki))');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Aktywuj filtr s�owny');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Przeszukuje komentarze na okoliczno�� wyst�pienia pewnych ci�g�w znak�w i oznacza je jako spam');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Filtr URLi');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Dozwolone s� wyra�enia regularne, oddzielaj wyra�enia �rednikami (;)');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Filtr nazw autor�w');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', 'Dozwolone s� wyra�enia regularne, oddzielaj wyra�enia �rednikami (;)');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Filtr tre�ci komentarza');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Z�y adres e-mail');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'Sprawdza� adresy e-mail?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Wymagane pola komentarza');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Wprowad� list� wymaganych p�l, kt�re musz� by� wype�nione przez u�ytkownika. Rozdzielaj pola przecinkami (,). Dost�pne nazwy p�l: name, email, url, replyTo, comment');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'Nie wype�ni�e� pola  %s!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Konfiguracja metod atyspamowych');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Blokuj tego autora przez wtyczk� Obrona przed spamem');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Blokuj ten URL przez wtyczk� Obrona przed spamem');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Odblokuj tego autora we wtyczce Obrona przed spamem');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Odblokuj ten URL we wtyczce Obrona przed spamem');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Tytu� wpisu jest taki sam jak tre�� komentarza');
#@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Odrzucaj komentarze, kt�re zawieraj� tylko tytu�'); // translate again

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'Sprawdzaj URLe �lad�w (Trackback�w)');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'Zezw�l na pozostawienie �ladu (Trackback) tylko gdy zawiera on link do Twojego bloga');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'Z�y URL �ladu (Trackbacka)');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_SCRAMBLE', 'Rozstrzelone captcha');

@define('PLUGIN_EVENT_SPAMBLOCK_HIDE', 'Wy��cz wtyczk� dla Autor�w');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_DESC', 'Mo�esz zezwoli� na komentowanie wpis�w przez Autor�w nale��cych do zaznaczonych grup bez w��czonej ochorny antyspamowej.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET', 'Klucz Akismet API');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_DESC', 'Akismet.com to centralny serwer antyspamowy i zawieraj�cy tzw. blacklisty. Mo�e analizowa� komentarze i sprawdza�, czy taki komentarz zosta� zg�oszony jako spam. Akismet zosta� stworzony dla systemu WordPress ale mo�e by� wykorzystywany przez inne systemy. Potrzebujesz klucza API (API Key) ze strony http://www.akismet.com - klucz otrzymasz po rejestracji w serwisie http://www.wordpress.com/. Je�li pozostawisz to pole puste, technologia Akismet nie b�dzie wykorzystywana.');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_FILTER', 'Jak traktowa� spam zg�oszony przez Akismet');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST', 'U�yto filtra Akismet.com Blacklist');

@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_TREAT', 'Co zrobi� z komentarzami, kt�re b�d� automoderowane?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT', 'Co zrobi� ze �ladami, kt�re b�d� automoderowane?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT', 'Wymusza� moderowanie �lad�w po jak wielu dniach');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_DESC', 'Mo�esz wymusi� moderowanie wszelkich �lad�w do Twoich wpis�w. Wprowad� wiek wpisu (w dniach), po kt�rym ka�dy �lad pozostawiany po tym terminie b�dzie musia� by� moderowany przed opublikowaniem.');

@define('PLUGIN_EVENT_SPAMBLOCK_CSRF', 'U�y� ochrony CSRF dla komentarzy?');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_DESC', 'Po w��czeniu tej opcji ka�demu komentarzowi b�dzie przyporz�dkowywana warto�� hash, dzi�ki kt�rej b�dzie mo�na sprawdzi�, czy komentarz zosta� pozostawiony przez u�ytkownika z prawid�owym ID sesji. To ustawienie zmniejszy ilos� spamu i ograniczy mo�liwo�� komentowania przez CSRF ale jednocze�nie uniemo�liwi komentowanie u�ytkownikom nie korzystaj�cym z ciastek (cookies) w ich przegl�darkach.');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON', 'Tw�j komentarz nie posiada� numeru hash sesji. Komentarze mog� by� pozostawiane na tym blogu tylko gdy Twoja przegl�darka ma w��czon� obs�ug� ciasteczek (cookies)!');

