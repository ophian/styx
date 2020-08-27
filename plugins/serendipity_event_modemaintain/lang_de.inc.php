<?php

@define('PLUGIN_MODEMAINTAIN_TITLE', 'Wartungs-Modus');
@define('PLUGIN_MODEMAINTAIN_TITLE_DESC', 'Erlaubt das �ffentliche Blog - das Frontend - in einen "503 - Service Temporarily Unavailable" Modus zu versetzen.');

@define('PLUGIN_MODEMAINTAIN_MAINTAIN', 'Service Wartungs-Modus');
@define('PLUGIN_DASHBOARD_MAINTENANCE_MODE_ACTIVE', '...aktiver Wartungsmodus...');
@define('PLUGIN_MODEMAINTAIN_INFOALERT', 'Achtung: Bitte Beschreibung lesen!');
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_MODE_DESC', "ACHTUNG:<br>\n<b>Nicht</b> ausloggen, den Browser oder das Tab schlie�en, oder das generelle Konfigurations-Formular absenden, ohne den Wartungsmodus zur�ckgesetzt zu haben!");
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_EXWARNING_DESC', "ACHTUNG:<br>\nEs <em>kann</em> unter Umst�nden eine (Session basierende) Situation geben, bei dem obiger 503-Submit-Knopf nach dem Absenden und Seitenreload seine Farbe (gr�n/rot) nicht unmittelbar �ndert, um anzuzeigen, in welchem Modus sich die Wartung gerade wirklich befindet. In diesem Fall w�hlen Sie irgendeine andere Backendseite aus der Seitenleiste und kehren dann in die Wartung zur�ck. Erst dann sehen Sie den augenblicklichen Status.");
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_EMERGENCY_DESC', "IM NOTFALL:<br>\nWenn Sie sich jemals ausloggen, ohne den 503 Maintenance Mode zur�ckgestellt zu haben, oder ihr Login Cookie besch�digt oder gel�scht wurde, m�ssen Sie die &dollar;serendipity['maintenance'] Variable in der serendipity_config_local.inc.php Datei manuell auf 'false' stellen, um sich und der �ffentlichkeit wieder Zugang zu ihrem Blog zu erm�glichen!");

@define('PLUGIN_MODEMAINTAIN_MAINTAIN_NOTE', '�ffentlicher Wartungs-Modus Text');
@define('PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT', 'This site &#187;%s&#171; is currently undergoing some maintenance work and therefore is temporarily unavailable. Please visit us later.');
@define('PLUGIN_MODEMAINTAIN_MAINTAIN_USELOGO', 'Binde das Serendipity Logo ein?');

@define('PLUGIN_MODEMAINTAIN_BUTTON', 'Aktiviere den 503 Modus');
@define('PLUGIN_MODEMAINTAIN_FREEBUTTON', 'Zur�cksetzen des 503 Modus');
@define('PLUGIN_MODEMAINTAIN_RETURN', 'Das Blog ist jetzt im %s Modus. <a href="%s">Zur�ck</a> zum Backend f�r die anstehenden Wartungsarbeiten.');

@define('PLUGIN_MODEMAINTAIN_TITLE_AUTOLOGIN', 'Um den Wartungsmodus administrieren zu k�nnen, m�ssen Sie sich abmelden und mit dem "Daten speichern" Modus wieder anmelden.');

@define('PLUGIN_MODEMAINTAIN_WARNLOGOFF', 'Hey, Sie sind im <span class="fivezerothree">503</span>-Wartungs-Modus! - <b>Befreien</b> Sie den Modus <a href="%s">hier</a>, <b>bevor</b> Sie sich abmelden!');
@define('PLUGIN_MODEMAINTAIN_WARNGLOBALCONFIGFORM', 'Hey, Sie sind im <span class="fivezerothree">503</span>-Wartungs-Modus! - <b>Befreien</b> Sie den Modus <a href="%s">hier</a>, <b>bevor</b> Sie das generelle Konfigurations-Formular �ndern und/oder absenden k�nnen!');

@define('PLUGIN_MODEMAINTAIN_HINT_MAINTENANCE_MODE', 'Wenn l�nger andauernd, oder mit m�glichen Frontend-Auswirkungen verbunden, k�nnte dies eine g�ltige Aufgabe sein, den Wartungs-Modus zu nutzen!');

