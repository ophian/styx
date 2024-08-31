<?php

/**
 *  @version  
 *  @file 
 *  @author 
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Spammv�rn');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'Fj�ldamargar lei�ir til a� hindra spamm � athugasemdir. This is the cores backbone of Anti-Spam measures. Do not remove!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Spammhindrun: �leyfileg skilabo�.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Spammhindrun: �� getur ekki sent inn athugasemd svona sk�mmu eftir fyrri athugasemd.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_RBL', 'Spammhindrun: IP tala t�lvunnar sem �� ert a� senda fr� er skr�� sem opinn p�st�j�nn.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_SURBL', 'Spammhindrun: Athugasemdin ��n inniheldur sl�� sem er skr�� � SURBL lista.');

@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', '�etta blogg er � "Ney�ar-athugasemdal�singu", vinsamlegast komdu aftur s��ar');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Banna tv�rit athugasemda');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Banna gestum a� senda inn athugasemd sem er me� sama innihald og ��ursend athugasemd');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Ney�ar-athugasemdal�sing');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Banna t�mabundi� athugasemdir fyrir allar f�rslur. Nytsamlegt ef bloggi� �itt er undir spamm�r�s.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'T�mabil IP t�lu banna');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Leyfa IP t�lu einungis a� senda inn athugasemd � n m�n�tna fresti. Nytsamlegt til a� hindra athugasemdafl��.');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL', 'Synja athugasemdum fr� RBL-skr��um netum');
@define('PLUGIN_EVENT_SPAMBLOCK_RBL_DESC', 'A� virkja �etta mun l�ta kerfi� neita athugasemdum fr� netum sem eru skr�� � RBL lista. Athuga�u a� �etta getur haft �hrif � notendur proxy-�j�na e�a innhringinotendur.');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL', 'Neita athugasemdum sem innihalda SUBRL-skr�� net');
@define('PLUGIN_EVENT_SPAMBLOCK_SURBL_DESC', 'Neita athugasemdum sem innihalda SURBL-skr�� net');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST', 'Vi� hva�a RBL skal haft samband?');
@define('PLUGIN_EVENT_SPAMBLOCK_RBLLIST_DESC', 'Synja athugasemdum byggt � RVL listum sem fengnir hafa veri�. For�ast lista me� breytileg (dynamic) net.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Virkja "Captchas"');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Ney�ir notanda til a� sl� inn slembistreng sem s�st � s�rtilb�inni mynd. �etta mun hindra sj�lfvirkar innsendingar � bloggi�. Athuga�u �� a� f�lk me� skerta sj�n g�ti �tt erfitt me� a� �esa strenginn. To avoid having to use visible Captchas at all, try out the extending Spamblock Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'Til a� hindra sj�lfvirkar sendingar augl�singa � bloggi�, sl��u �� vinsamlegast inn strenginn � myndinni a� ne�an � vi�komandi reit. Athugasemdin ��n ver�ur einungis send ef strengurinn passar vi� myndina. Vinsamlegast gakktu �r skugga um a� vafrinn �inn sty�ji og sam�ykki k�kur, annars getur athugasemdin ��n getur ekki veri� sannpr�fu� � r�ttan h�tt.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Sl��u inn strenginn sem �� s�r� h�r � reitinn!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Sl��u inn strenginn �r spammvarnarmyndinni a� ofan: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', '�� sl�st ekki inn strenginn sem var s�ndur r�tt. Vinsamlegast horf�u � myndina og sl��u inn gildin sem eru s�nd �ar.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', '"Captchas" �virk � �j�ninum ��num. �� �arft GDLib og freetype pakkana uppsetta fyrir PHP, og �arft a� hafa .TTF skr�rnar � m�ppunni �inni.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Ney�a "captchas" eftir hversu marga daga?');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', '"Captchas" geta veri� sett inn eftir aldri f�rslanna. Sl��u inn �ann fj�lda daga sem �� vilt a� l��i ��ur en innsettning "captchas" er nau�synleg. Ef stillt � 0 munu "captchas" alltaf vera notu�.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Ney�a yfirlestur athugasemda eftir hversu marga daga?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', '�� getur stillt kerfi� �annig a� allar athugasemdir �urfi sam�ykki. Sl��u inn �ann aldur f�rsla � d�gum sem �� vilt a� �urfi yfirlestur til sam�ykkis. 0 ���ir a� kerfi� mun ekki sj�lfkrafa bi�ja um sam�ykki �itt.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'Hversu margir hlekkir ��ur en athugasemd �arf sam�ykki?');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', '�egar athugasemd n�r �kve�num fj�lda hlekkja mun s� athugasemd vera send til yfirlesningar. 0 ���ir a� engin hlekkjatalning fer fram.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'Hversu margir hlekkir ��ur en athugasemd er hafna�?');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', '�egar athugasemd n�r �kve�num fj�lda hlekkja mun �eirri athugasemd vera hafna�d. 0 ���ir a� engin hlekkjatalning far fram.');

@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Vegna �kve�inna skilyr�a var athugasemd ��n send til yfirlesningar af eiganda bloggkerfisins ��ur en h�n er birt.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Litur bakgrunns "captcha"-sins');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Sl��u inn RGB gildi: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Sta�setning atbur�askr�ar (logfile)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Uppl�singar um athugasemdir sem hafa veri� sendar til yfirlesturs e�a hafna� geta veri� skr��ar � atbur�askr�. Haf�u �ennan streng t�man ef �� vilt hafa atbur�askr�ningu �virka.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Ney�ar-athugasemdal�sing');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Tv�rit af athugasemd');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'IP-synjun');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_RBL', 'RBL-synjun');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_SURBL', 'SURBL-synjun');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', '�gilt "captcha" (F�kk: %s, Bj�st vi�: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Sj�lfvirk yfirlesningarb�n eftir X daga');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Of margir hlekkir');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'Of margir hlekkir');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Fela netf�ng notenda sem skr� athugasemdir');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Mun ekki s�na netf�ng notenda sem skr� athugasemdir');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'Netf�ng munu ekki vera s�nd, og einungis notu� fyrir tilkynningar sendar � p�sti');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Veldu tegund atbur�askr�ninga');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Atbur�askr�ning synja�ra athugasemda getur veri� ger� � gagnagrunn e�a venjulega textaskr�');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'Skr� (sj� "atbur�askr�" valm�guleika a� ne�an)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Gagnagrunnur');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'Engin atbur�askr�ning');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'Hvernig skal me�h�ndla athugasemdir skr��ar gegnum API kerfi?');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', '�etta hefur �hrif � tegund sam�ykkis sem ��rf er � vegna athugasemda sendra � gegnum API tengingar (Tilv�sanir, WFW:commentAPI athugasemdir). Ef stillt � "yfirlestur" munu allar �essar athugasemdir �urfa sam�ykki fyrir birtingu. Ef stillt � "synja" munu sl�kar athugasemdir vera algj�rlega banna�ar. Ef stillt � "ekkert" munu athugasemdirnar vera me�h�ndla�ar eins og venjulegar athugasemdir.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'yfirlestur');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'synja');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'Engar API athugasemdir (eins og tilv�sanir) leyf�ar');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Virkja or�as�u');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Leitar a� �kve�num strengjum � athugasemdum og merkir ��r sem spamm.');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Or�as�a fyrir hlekki');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Regular Expressions leyf�. Strengir a�skildir me� sem�kommu (;).');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Or�as�a fyrir h�fundan�fn');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', 'Regular Expressions leyf�. Strengir a�skildir me� sem�kommu (;).');

