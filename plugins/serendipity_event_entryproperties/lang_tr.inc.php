<?php

/**
 *  @version
 *  @author Translator Name <ahmetusal@gmail.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_ENTRYPROPERTIES_TITLE', 'Yaz�lar i�in geni�letilmi� �zelliklerin tan�m�');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_DESC', '(�nbellek, anonim olmayan yaz�lar, yap��kan iletiler)');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS', 'Bu yaz�y� yap��kan ileti olarak i�aretle');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS', 'Bu yaz�y� okuma izni olanlar:');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE', 'Kendim');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS', 'Yazarlar');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC', 'Herkes');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE', 'Yaz�lar �nbelleklensin mi?');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC', 'E�er bu se�enek etkinse, yaz�y� her kaydetti�inizde �nbelleklenmi� bir s�r�m kendili�inden olu�turulacakt�r. �nbellekleme yaz�lar�n yay�n esnas�nda daha �abuk eri�ilmesine imkan verir, siteye eri�im h�z�n� artt�r�r, Ama di�er eklentilerle beraber �al���rken �l�eklenebilir olma imkan�n� da azalt�r. If you use the rich-text editor (wysiwyg) a cache is actually useless, unless you use many plugins that further change the output markup.');
@define('PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE', 'Yaz�lar� �nbellekleme i�lemini ger�ekle�tir');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT', 'Yaz�lar�n kayd� gelecek i�lem i�in �ekiliyor...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO', 'Yaz�lar�n �ekilme i�lemi %d dan %d');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING', 'Building cache for entry�u yaz� i�in �nbellekleme i�lemi:
#%d, <em>%s</em>...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHED', 'Yaz� �nbellekle�e aktar�ld�.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE', 'Yaz� �nbellekleme i�lemi tamamland�.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED', 'Yaz� �nbellekleme i�lemi iptal edildi.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL', ' (toplamda %d yaz�)...');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NL2BR', 'nl2br Kapal�');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE', 'Yaz�y�  �ng�r�n�m / anasayfadan gizle');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS', 'Grup tabanl� yetkilendirme kullan');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC', 'E�er bu se�enek etkinle�tirilirse, hangi kullan�c� grubunun kullan�c�lar�n�n bu yaz�y� okuma yetkisi oldu�unu belirleyebilirsiniz. Bu se�enek performans� olumsuz etkileyebilir. Sadece ger�ekten ihtiyac�n�z varsa bu se�ene�i etkinle�tirmeniz �nerilir.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS', 'Kullan�c� tabanl� yetkilendirme kullan');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC', 'E�er bu se�enek etkinle�tirilirse, Bu yaz�y� okuma izni olan �zel kullan�c�lar� tan�mlayabilirsiniz. Bu se�enek performans� olumsuz
etkileyebilir. Sadece ger�ekten ihtiyac�n�z varsa bu se�ene�i etkinle�tirmeniz �nerilir.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS', '��eri�i RSS Beslemesinden gizle');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS_DESC', 'E�er bu se�enek etkinle�tirilirse, i�eri�i RSS Beslemesinden gizlenecektir.');

@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS', '�zel Alanlar');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1', '�ablonunuza eklenmek �zere ek �zel alanlar belirleme imkan�.Bu alanlar nerede kullanmak istiyorsan�z orada g�sterilecek �ekilde eklenebilir. Tam olarak istedi�iniz t�rden bir �zelle�tirme i�in entries.tpl �ablon dosyan�z� d�zenleyin ve  Smarty etiketlerini HTML etiketleme yap�s� i�inde
{$entry.properties.ep_MyCustomField} gibi yerle�tirin. Not: her alanda �n ek ep_ �eklinde olmal�d�r.');
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2', 'Buraya virg�lle ayr�lm�� �zel alanlar�n�z� listeleyebilirsiniz ve bunlar� her yaz�n�z i�in kullanabilirsiniz. �zel harf karakterleri ya da bo�luk karakteri kullanmamaya �zen g�sterin - �rne�in:"Ozelalan1, Ozelalan2" �eklinde kullan�n.' . PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1);
@define('PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3', 'Listelenen �zel alanlar <a href="%s" target="_blank" rel="noopener" title="' . PLUGIN_EVENT_ENTRYPROPERTIES_TITLE . '">eklenti yap�land�rma</a> b�l�m�nden de�i�tirilebilir.');

