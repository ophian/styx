<?php

@define('PLUGIN_EVENT_MLORPHANS_NAME', 'Waisenmanager');
@define('PLUGIN_EVENT_MLORPHANS_DESC', 'Erlaubt, die Mediathek ab 100 vorhandenen Bildern nach verwaisten Bild-Dateien zu durchsuchen. Platzieren Sie es in der Plugin-Liste am besten vor den anderen Wartungsplugins (spamblock, modemaintain, changelog).');
@define('PLUGIN_EVENT_MLORPHANS_SUBMIT', 'Suche nach Bilder-Waisen');

@define('MLORPHAN_MTASK_ML_REAL_IMAGES', 'Die Mediathek enthält %d echte lokale Bilder.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_MATCHES', '<b>%d</b> mit ID ausgezeichnete img src Inhalte wurden in den Blogeinträgen und Statischen Seiten gefunden und im ($im) array abgelegt (<em>Doubletten sind möglich</em>).');
@define('MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR', 'Bitte korrigieren Sie umgehend: Eintrag: %d  [<em>%s</em> textarea Feld] auf ein <em>voraussichtlich</em> <b>falsch</b> genutztes s9ymdb: ID (%d) tag zu einem Bild src Pfad von "<em>%s</em>" und starten danach dieses Skript erneut. Für die gesuchte Bild ID, durchforsten Sie das Array in der untenstehenden Ausklappbox "view array" nach "<em>%s</em>", um die korrekte Bild ID [id] zu erfahren.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_RESULTCHECK_ACTION', '<em>Prüfe auf falsch gesetzte Bild-s9ymdb:IDs und/oder auf falsche Namen... und entferne gefundene Bilder aus dem Array...</em>');
@define('MLORPHAN_MTASK_PNCASE_REVERSECHECK_ACTION', '<em>Letzte Gegenprüfung gegen Datenbankinhalte... und mögliche quickblog Bild tags...</em>');
@define('MLORPHAN_MTASK_PNCASE_TITLE', 'Wiederholende Überprüfung der gefundenen verwaisten Bild-IDs');
@define('MLORPHAN_MTASK_PNCASE_NOTE', '<em>die möglicherweise immer noch in Einträgen oder Statischen Seiten vorkommen. Dies sind wahrscheinlich unbekannte regex pattern Treffer, oder Pfade die nicht zu diesem Blog gehören.</em><br>Wenn Sie möchten, überprüfen Sie folgende Einträge und starten Sie dieses Skript erneut.');
@define('MLORPHAN_MTASK_PNCASE_EXCLUDING', 'Ausschließen dieser Bild-IDs: (<b>%s</b>) vom Waisen-Array.');
@define('MLORPHAN_MTASK_PNCASE_OK', 'Sieht gut für mich aus...');
@define('MLORPHAN_MTASK_FOUND_IMAGE_ORPHANS', '<b>%d</b> verbliebene und wahrscheinlich unbenutzte Bild-Waisen-Array-Elemente bleiben übrig.');
@define('MLORPHAN_MTASK_REMOVE_IMAGE_ORPHANS', 'Sie können nun %d Bilder-Waisen aus der Mediathek löschen!');
@define('MLORPHAN_MTASK_LAST_ACTION_NOTE', '<b>Achtung:</b> <span>Es <b>können</b> immer noch Bilder in diesem Array und Ihren Einträgen enhalten sein, die <b>keinen</b> dazugehörenden <code>&lt;-- s9ymdb:N --&gt;</code> Mediathek ID tag besitzen. Wenn Sie sich dessen nicht sicher sind, vermeiden Sie die folgende Löschaktion, oder besser, tragen Sie die korrekten tag IDs in diese Einträge ein und starten anschließend dieses Skript neu! (<em>Empfohlen!</em>)</span><br>
    <span>Dieser Waisenmanager kümmert sich ebenfalls nicht um Datenbank-gecachte Einträge aus den Erweiterte(n) Eigenschaften von Artikeln (plugin entryproperties). Sollten Sie also vorhandene Media tag IDs (siehe mögliche Fehlermeldungen ganz oben) nachträglich in Artikeln haben korrigieren müssen, gilt dies natürlich auch für gecachte Einträge. Wenn Sie diesen Cache also benötigen und benutzen (dies sollte heutzutage aber nicht vonnöten sein), starten sie nach der erfolgreichen Löschung die dazugehörige Wartungsaufgabe um die (EP) Cacheeinträge neu bilden zu lassen, was aber gegebenenfalls andere Probleme zB. mit NL2BR und Zeilenumbrüchen in sehr alten Artikeln nach sich ziehen kann.</span>');
@define('MLORPHAN_MTASK_PURGED_SUCCESS', 'Erfolgreich gelöschte Bilder nach ID: (<b>%s</b>) in der Datenbank und dem Dateisystem der Mediathek.');
@define('MLORPHAN_MTASK_RESPECTIVELY', 'bzw.');

