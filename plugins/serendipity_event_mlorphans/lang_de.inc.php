<?php

@define('PLUGIN_EVENT_MLORPHANS_NAME', 'Waisenmanager');
@define('PLUGIN_EVENT_MLORPHANS_DESC', 'Erlaubt, die Mediathek ab 100 vorhandenen Bildern nach verwaisten Bild-Dateien zu durchsuchen. Platzieren Sie es in der Plugin-Liste am besten vor den anderen Wartungsplugins (spamblock, modemaintain, changelog).');
@define('PLUGIN_EVENT_MLORPHANS_SUBMIT', 'Suche nach Bilder-Waisen');

@define('MLORPHAN_MTASK_ML_REAL_IMAGES', 'Die Mediathek enth�lt %d echte lokale Bilder.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_MATCHES', '<b>%d</b> mit ID ausgezeichnete img src Inhalte wurden in den Blogeintr�gen und Statischen Seiten gefunden und im ($im) array abgelegt (<em>Doubletten sind m�glich</em>).');
@define('MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR', 'Bitte korrigieren Sie umgehend: Eintrag: %d  [<em>%s</em> textarea Feld] auf ein <em>voraussichtlich</em> <b>falsch</b> genutztes s9ymdb: ID (%d) tag zu einem Bild src Pfad von "<em>%s</em>" und starten danach dieses Skript erneut. F�r die gesuchte Bild ID, durchforsten Sie das Array in der untenstehenden Ausklappbox "view array" nach "<em>%s</em>", um die korrekte Bild ID [id] zu erfahren.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_RESULTCHECK_ACTION', '<em>Pr�fe auf falsch gesetzte Bild-s9ymdb:IDs und/oder auf falsche Namen... und entferne gefundene Bilder aus dem Array...</em>');
@define('MLORPHAN_MTASK_PNCASE_REVERSECHECK_ACTION', '<em>Letzte Gegenpr�fung gegen Datenbankinhalte... und m�gliche quickblog Bild tags...</em>');
@define('MLORPHAN_MTASK_PNCASE_TITLE', 'Wiederholende �berpr�fung der gefundenen verwaisten Bild-IDs');
@define('MLORPHAN_MTASK_PNCASE_NOTE', '<em>die m�glicherweise immer noch in Eintr�gen oder Statischen Seiten vorkommen. Dies sind wahrscheinlich unbekannte regex pattern Treffer, oder Pfade die nicht zu diesem Blog geh�ren.</em><br>Wenn Sie m�chten, �berpr�fen Sie folgende Eintr�ge und starten Sie dieses Skript erneut.');
@define('MLORPHAN_MTASK_PNCASE_EXCLUDING', 'Ausschlie�en dieser Bild-IDs: (<b>%s</b>) vom Waisen-Array.');
@define('MLORPHAN_MTASK_PNCASE_OK', 'Sieht gut f�r mich aus...');
@define('MLORPHAN_MTASK_FOUND_IMAGE_ORPHANS', '<b>%d</b> verbliebene und wahrscheinlich unbenutzte Bild-Waisen-Array-Elemente bleiben �brig.');
@define('MLORPHAN_MTASK_REMOVE_IMAGE_ORPHANS', 'Sie k�nnen nun %d Bilder-Waisen aus der Mediathek l�schen!');
@define('MLORPHAN_MTASK_LAST_ACTION_NOTE', '<b>Achtung:</b> <span>Es <b>k�nnen</b> immer noch Bilder in diesem Array und Ihren Eintr�gen enhalten sein, die <b>keinen</b> dazugeh�renden <code>&lt;-- s9ymdb:N --&gt;</code> Mediathek ID tag besitzen. Wenn Sie sich dessen nicht sicher sind, vermeiden Sie die folgende L�schaktion, oder besser, tragen Sie die korrekten tag IDs in diese Eintr�ge ein und starten anschlie�end dieses Skript neu! (<em>Empfohlen!</em>)</span><br>
    <span>Dieser Waisenmanager k�mmert sich ebenfalls nicht um Datenbank-gecachte Eintr�ge aus den Erweiterte(n) Eigenschaften von Artikeln (plugin entryproperties). Sollten Sie also vorhandene Media tag IDs (siehe m�gliche Fehlermeldungen ganz oben) nachtr�glich in Artikeln haben korrigieren m�ssen, gilt dies nat�rlich auch f�r gecachte Eintr�ge. Wenn Sie diesen Cache also ben�tigen und benutzen (dies sollte heutzutage aber nicht vonn�ten sein), starten sie nach der erfolgreichen L�schung die dazugeh�rige Wartungsaufgabe um die (EP) Cacheeintr�ge neu bilden zu lassen, was aber gegebenenfalls andere Probleme zB. mit NL2BR und Zeilenumbr�chen in sehr alten Artikeln nach sich ziehen kann.</span>');
@define('MLORPHAN_MTASK_PURGED_SUCCESS', 'Erfolgreich gel�schte Bilder nach ID: (<b>%s</b>) in der Datenbank und dem Dateisystem der Mediathek.');
@define('MLORPHAN_MTASK_RESPECTIVELY', 'bzw.');

