<?php

/**
 *  @file lang_cs.inc.php 1658.2 2011-04-17 14:17:14 VladaAjgl
 *  @author Vladimir Ajgl <vlada@ajgl.cz>
 *  EN-Revision: Revision of lang_en.inc.php
 *  @author Vladim�r Ajgl <vlada@ajgl.cz>
 *  @revisionDate 2009/02/16
 *  @author Vladim�r Ajgl <vlada@ajgl.cz>
 *  @revisionDate 2009/07/06
 *  @author Vladim�r Ajgl <vlada@ajgl.cz>
 *  @revisionDate 2011/04/17
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Spam Protector');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Mno�stv� metod na ochranu proti spamu. Toto je z�kladom opatren� proti spamu. Neodstranujte!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Ochrana proti spamu: Neplatn� zpr�va.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Ochrana proti spamu: Nem��e� poslat koment�� tak brzy po odesl�n� jin�ho koment��e.');

@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'Tento blog se nach�z� v m�du "Nouzov� blokov�n� v�ech koment�r�", zkuste to jindy.');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Nepovolovat opakuj�c� se koment��e');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Nepovolovat u�ivatel�m odeslat koment��, kter� m� stejn� obsah jako jin� ji� odeslan� koment��.');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Nouzov� vypnut� koment���');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Do�asn� vypne koment��e pro v�echny p��sp�vky. U�ite�n� funkce, pokud je v� blog pod �tokem spamer�.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'Blokov�n� IP adres');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Povol poslat z jedn� IP adresy jeden koment�� za n minut. U�ite�n� pro zabr�n�n� z�plav koment���.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Povolit kryptogramy');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'P�isp�vatel bude nucen zadat ��sla z n�hodn� vygenerovan�ho deformovan�ho obr�zku. Toto znemo�n� automatick� p�id�v�n� koment���, nap�. hackersk�m strojem. M�jte pros�m na pam�ti, �e lid� s po�kozen�m zrakem mohou m�t pot�e se �ten�m t�chto kryptogram�. To avoid having to use visible Captchas at all, try out the extending Spamblock Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'V r�mci boje proti koment��ov�m spamu zadejte pros�m znaky, kter� vid�te n�e. V� koment�� bude posl�n pouze pokud tyto znaky budou souhlasit. Ujist�te se, �e V� prohl�e� podporuje a p�ij�m� cookies. Jinak va�e koment��e nemohou b�t spr�vn� ov��en�.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Do pol��ka n�e zadejte znaky, kter� vid�te nad t�mto textem.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Zadejte znaky z protispamov�ho obr�zku: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'Nezadal jsi spr�vn� znaky z protispamov�ho obr�zku. Pod�vej se na n�j znovu a zadej znaky sp�vn�.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Kryptogramy vypnuty. Pot�ebujete GDLib a freetype knihovny zkompilovan� v PHP, podobn� je pot�eba m�t soubory.TTF (fonty) v adres���ch pluginu "spamblock" .');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Vynutit kryptogramy po kolika dnech');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Kryptogramy (spamov� obr�zky) mohou b�t vynucov�ny v z�vislosti na st��� �l�nku. Zadejte po�et dn�, po kter�ch bude nutn� zadat spr�vn� text z kryptogramu pro vlo�en� koment��e. Po�et nastaven� na 0 znamen�, �e kryptogramy budou vy�adov�ny ihned po vyd�n�.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Vynutit moderov�n� (schvalov�n�) koment��� po kolika dnech');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'M��e� automaticky nastavit v�echny koment��e jako moderovan�. Po uplynut� zde zadan� doby od vyd�n� �l�nku bude t�eba potvrzovat (auto-moderovat) koment��e. 0 znamen� ��dn� potvrzov�n�.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Kolik odkaz� v jednom koment��i povolit, ne� bude automaticky nastaven ke schv�len�');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'Kdy� se v koment��i objev� v�ce ne� zde zadan� po�et odkaz� &lt;a href="..."&gt;, bude automaticky nastaven ke sch�vlen�. 0 znamen� ��dn� kontroly mno�stv� odkaz�.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Kolik odkaz� v jednom koment��i povolit, ne� bude zam�tnut');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'Kdy� se v koment��i objev� v�ce ne� zde zadan� po�et odkaz� &lt;a href="..."&gt;, bude zam�tnut. 0 znamen� ��dn� kontroly mno�stv� odkaz�.');

@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'V� koment�� vy�aduje souhlas provozovatele blogu. Nepos�lejte jej znovu, vy�kejte na jeho potvrzen�.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Pozad� kryptogram�');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Zadej RGB hodnotu: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Um�st�n� logu');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Informace o zam�tnut�ch/moderovan�ch p��sp�vc�ch mohou b�t zapisov�ny do logu. Nastavte na pr�zdn� �e�t�zet pro vypnut� logov�n�.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Nouzov� blokov�n� koment���');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Duplicitn� koment��');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'IP-blok');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Nespr�vn� kryptogram (Zad�no: %s, Spr�vn� m� b�t: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Automatick� moderov�n� (schvalov�n�) po X dnech');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'P��li� mnoho odkaz� (odezev)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'P��li� mnoho odkaz� (odezev)');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Schovat e-mailovou adresu pisatel� koment���');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Schov� e-mailov� adresy p�isp�vatel� v koment���ch');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'E-mailov� adresy nebudou zobrazov�ny, budou pou�ity pouze pro ozn�men� elektronickou po�tou.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Vyberte metodu logov�n�');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Logov�n� zam�tnut�ch koment��� m��e b�t prov�d�no bu� v datab�zi nebo v textov�m souboru');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Soubor (viz. volba "logfile" n�e)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Datab�ze');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Nelogovat');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Jak zach�zet s koment��i p�idan�mi p�es API');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'Toto se t�k� moderov�n� (schvalov�n�) koment��� vytvo�en�ch p�es vol�n� API funkc� (tedy uvnit� syst�mu Serendipity)(Trackbacks, WFW:commentAPI comments). Nastaveno na "moderovat", v�echny koment��e musej� b�t nejd��v schv�leny. Nastaveno na "zam�tnout", budou �pln� zak�z�ny. Nastaveno na "none", s t�mito zvl�tn�mi koment��i bude zach�zeno jako s b�n�mi koment��i.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'moderovat');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'zam�tnout');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', '��dn� API koment��e (jako nap�. odezvy) nejsou povoleny');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Aktivovat slovn�kov� filtr');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Hled� v koment���ch jist� �et�zce obsa�en� ve slovn�ku. V p��pad� �sp�chu vyhodnot� koment�� jako spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Pou��t filtr na URL adresy');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Regul�rn� v�razy povoleny, �et�zce (jednotliv� adresy) od�lujte st�edn�kem (;). Speci�ln� znaky jako zavin�� (@) mus�te escapovat - \\@.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Pou��t filtr na jm�na autor�');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Pou��t filtr pro t�lo koment��e');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS', 'Pou��t filtr na e-mailovou adresu');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Nespr�vn� e-mailov� adresa');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'Zkontrolovat e-mailov� adresy?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Vy�adovan� pole koment��e');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Zadejte seznam pol�, kter� mus� b�t vypln�ny pro odesl�n� koment��e. V�ce pol� odd�lujte ��rkou ",". V �vahu p�ipadaj� pole: name, email, url, replyTo, comment');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'Nezadal jsi pole %s!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Konfigurovat antispamov� metody');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Blokovat tohoto autora pluginem "Spamblock"');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Blokovat tuto URL adresu pluginem "Spamblock"');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL', 'Blokovat tuto e-mailovou adresu pluginem "Spamblock"');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Zru�it blokov�n� tohoto autora');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Zru�it blokov�n� t�to URL adresy');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL', 'Zru�it blokov�n� t�to e-mailov� adresy');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Nadpis koment��e je stejn� jako jeho t�lo');
#@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Odm�tne koment��e, kter� v t�le obsahuj� pouze nadpis.'); // translate again

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'Kontrolovat URL odezev');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'Povolit pouze odezvy, kde str�nka odezvy opravdu obsahuje odkaz na V� blog - kontroluje str�nku odezvy.');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'Trackback URL invalid.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_SCRAMBLE', 'Pom�chan� kryptogramy');

@define('PLUGIN_EVENT_SPAMBLOCK_HIDE', 'Vypni spamblock pro n�sleduj�c� Autory');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_DESC', 'Autor�m v n�sleduj�c�ch skupin�ch m��e� povolit pos�l�n� p��sp�vk�, ani� by tyto byly kontrolov�ny na spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET', 'Akismet API Key');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_DESC', 'Akismet.com je centr�ln� anti-spamov� a blacklistov� server. M��e analyzovat p��choz� koment��e a kontrolovat, jestli jsou vedeny jako spam. Akismet byl vyvinut speci�ln� pro WordPress, ale m��e b�t pou�it� i v jin�ch syst�mech. Pot�ebuje� k tomu API Key z  http://www.akismet.com, kter� z�sk� registrac� na http://www.wordpress.com/. Pokud nech� toto pole pr�zdn�, Akismet nebude pou��v�n.');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_FILTER', 'Jak ozna�ovat p��sp�vek ozna�en� Akismetem jako spam?');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST', 'Zam�tnuto blacklistem Akismet.com');

@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_TREAT', 'Co ud�lat s koment��i ozna�en�mi jako auto-moderova�mi?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT', 'Co ud�lat s odezvami ozna�en�mi jako auto-moderova�mi?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT', 'Vynutit moderov�n� odezev po kolika dnech');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_DESC', 'M��e� automaticky nastavit v�echny odezvy na �l�nky jako moderovan�. Zadej po�et dn� od vyd�n�, po jejich� uplynut� budou ozna�eny jako auto-moderovan�. 0 znamen� ��dn� moderov�n� (schvalov�n�)');

@define('PLUGIN_EVENT_SPAMBLOCK_CSRF', 'Pou��t CSRF Ochranu pro koment��e?');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_DESC', 'Pokud je povoleno, speci�ln� hash hodnota bude hl�dat, �e koment��e mohou poslat pouze u�ivatel� s platn�m ��slem session. To zm�rn� spam a zabr�n� u�ivatel�m v p�id�v�n� koment��� p�es CSRF, ale tak� to zabr�n� p�id�vat koment��e u�ivatel�m, kte�� nemaj� zapnut� cookies.');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON', 'V� koment�� neobsahuje Session-Hash. Koment��e mohou b�t na tomto blogu posl�ny pouze se zapnut�mi cookies!');

@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS', 'Blokuj �patn� IP adresy pomoc� HTaccess?');
@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS_DESC', 'Povolen� t�to volby p�id� IP adresy, ze kter�ch p�ich�z� spam do souboru .htaccess. Soubor .htaccess bude pravideln� aktualizov�n o zak�zan� adresy ka�d� m�s�c.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOOK', 'Takto pr�v� vypadaj� va�e kryptogramy. Pokud jste zm�nili a ulo�ili nastaven� v��e a chcete vid�t aktu�ln� vzhled kryptogramu, jednodu�e na n�j klikn�te a on se obnov�.');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION', 'Odezvy/ozn�men�: kontrola ip adresy');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_DESC', 'M�la by IP odes�latele souhlasit s IP hosta, kter�mu je zas�l�na odezva/ozn�men� (trackaback/pingback)? (DOPORU�ENO!)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION', 'Kontrola IP adresy: %s [%s] != ip adresa odes�latele [%s]');

@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_DESC', 'Pokud je zak�z�no, nebude se prov�d�t ��dn� kontrola email�. Pokud je nastaveno na "Ano", pak komentuj�c� mus� napsat platnou emailovou adresu. Pokud je nastaveno na "V�dy potvrzovat", komentuj�c� bude muset potvrdit sv�j koemnt�� pomoc� emailu, kter� mu bude zasl�n (kliknut�m na zaslan� odkaz). Pokud je nastaveno na "Potvrdit poprv�", u�ivatel mus� potvrdit sv�j prvn� koment�� (kliknut�m na zaslan� odkaz). P�i dal��ch jeho koment���ch nebude potvrzen� po�adov�no.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ONCE', 'Potvrdit poprv�');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ALWAYS', 'V�dy potvrzovat');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL', 'B�hem n�kolika okam�ik� na sv�j mail obdr��te zpr�vu, pomoc� kter� potvrd�te sv�j koment��.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_INFO', 'Abyste mohli zanechat koment��, mus�te ho potvrdit pomoc� e-mailu. Po odesl�n� formul��e s koment��em V�m bude zasl�n mail, s jeho� pomoc� dokon��te vlo�en� koment��e.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER', 'Antispamov� server');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER_DESC', 'Na kter�m serveru je zaregistrovan� v��e zadan� kl��? Anonymn� znamen�, �e data pos�lan� webov� slu�b� neobsahuj� u�ivatelsk� jm�no a emailovou adresu. I tato volba sni�uje mno�stv� spamu, i kdy� ne tak dob�e, jako neanonymn�.');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS', 'TypePad Antispam');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET', 'p�vodn� Akismet');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS_ANON', 'TypePad Antispam (anonymn�)');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET_ANON', 'p�vodn� Akismet (anonymn�)');

// Next lines were translated on 2009/07/06
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE', 'Vylou�it URL adresy z ov��en� IP adresy');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE_DESC', 'URL adresy, kter� se nemaj� ov��ovat na IP adresu.');

// Next lines were translated on 2011/04/17
@define('PLUGIN_EVENT_SPAMBLOCK_SPAM', 'Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_NOT_SPAM', 'Nen� spam');

