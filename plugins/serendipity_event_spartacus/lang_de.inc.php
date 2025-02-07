<?php

/**
 *  @version 1.0
 *  @author Konrad Bauckmeier <kontakt@dd4kids.de>
 *  @translated 2009/06/03
 */
@define('PLUGIN_EVENT_SPARTACUS_NAME', 'Spartacus');
@define('PLUGIN_EVENT_SPARTACUS_DESC', '[S]erendipity [P]lugin [A]ccess [R]epository [T]ool [A]nd [C]ustomization/[U]nification [S]ystem - Installiert Plugins direkt aus dem Netz.');
@define('PLUGIN_EVENT_SPARTACUS_FETCH', 'Hier klicken um ein neues %s aus dem Netz zu installieren.');
@define('PLUGIN_EVENT_SPARTACUS_FETCHERROR', 'Die URL %s konnte nicht geöffnet werden. Möglicherweise existieren vorübergehende Server- oder Netzwerkprobleme. Versuchen Sie zuerst die Seite im Browser zu aktualisieren (F5).');
@define('PLUGIN_EVENT_SPARTACUS_FETCHING', 'Versuche URL %s zu öffnen...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_URL', '%s bytes von obiger URL geladen. Speichere Inhalt als %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_CACHE', '%s bytes von bereits bestehender Datei geladen. Speichere Inhalt als %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_DONE', 'Daten erfolgreich geladen.');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_XML', 'Datei/Mirror Speicherort (XML-Metadaten)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_FILES', 'Datei/Mirror Speicherort (Downloads)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_DESC', 'Wählen Sie den Download-Server. Ändern Sie diesen Wert nur, wenn Sie wissen, was Sie tun und ein Server nicht mehr reagiert. Diese Option ist hauptsächlich für zukünftige Server reserviert.');

@define('PLUGIN_EVENT_SPARTACUS_CHOWN', 'Eigentümer der heruntergeladenen Dateien');
@define('PLUGIN_EVENT_SPARTACUS_CHOWN_DESC', 'Hier kann der FTP/Shell-Benutzer (z.B. "nobody") angegeben werden, der für von Spartacus heruntergeladene Dateien verwendet wird. Falls leer, wird keine Änderung des Eigentümers vorgenommen.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD', 'Zugriffsrechte der heruntergeladenen Dateien');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DESC', 'Hier kann der Oktagonale Dateimodus (z.B: "0777") für von Spartacus heruntergeladene Dateien angegeben werden. Falls dieser Wert leergelassen wird, verwendet Serendipity die Standardmaske des Systems. Nicht alle Server unterstützen eine Änderung dieser Dateirechte. Stellen Sie unbedingt sicher, dass die von Ihnen gewählten Rechte das Lesen und Schreiben des Webserver-Benutzers weiterhin erlauben - sonst könnte Serendipity/Spartacus keine bestehenden Dateien überschreiben.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR', 'Zugriffsrechte der heruntergeladenen Verzeichnisse');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR_DESC', 'Hier kann der Oktagonale Dateimodus (z.B: "0777") für von Spartacus heruntergeladene Verzeichnisse angegeben werden. Falls dieser Wert leergelassen wird, verwendet Serendipity die Standardmaske des Systems. Nicht alle Server unterstützen eine Änderung dieser Verzeichnisrechte. Stellen Sie unbedingt sicher, dass die von Ihnen gewählten Rechte das Lesen und Schreiben des Webserver-Benutzers weiterhin erlauben - sonst könnte Serendipity/Spartacus keine bestehenden Dateien überschreiben.');
@define('PLUGIN_EVENT_SPARTACUS_CHECK', 'Plugins updaten');
@define('PLUGIN_EVENT_SPARTACUS_CHECK_SIDEBAR', 'Updates (Seitenleisten-Plugins)');
@define('PLUGIN_EVENT_SPARTACUS_CHECK_EVENT', 'Updates (Ereignis-Plugins)');
@define('PLUGIN_EVENT_SPARTACUS_CHECK_HINT', 'Sie können mehrere Plugins auf einmal installieren indem sie diesen Link in einem neuen Tab öffnen (mittlerer Mausbutton)');

@define('PLUGIN_EVENT_SPARTACUS_REPOSITORY_ERROR', '(Der Mirror-Speicherort antwortet mit Fehler %s.)');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHCHECK', 'Die Daten des Spartacus-Speicherorts konnte nicht empfangen werden. Prüfe Verfügbarkeit der Quelle "%s"');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHERROR', 'Die Prüfung der Verfügbarkeit einer Spartacus-Quelle konnte nicht durchgeführt werden (HTTP-Code %s). Bitte probieren Sie es später wieder.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHLINK', '<a target="_blank" rel="noopener" href="%s">Klicken Sie hier um die Spartacus-Verfügbarkeitsprüfung anzusehen</a> und dessen Erreichbarkeit zu überprüfen.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHBLOCKED', 'SPARTACUS konnte keine Test-Verbindung zu Google herstellen (Fehler %d: %s).<br>Ihr Server blockiert vermutlich ausgehende Verbindungen. SPARTACUS kann so nicht ausgeführt werden, da auf keine der SPARTACUS-Quellen zugegriffen werden kann. <b>Bitte kontaktieren Sie ihren Web-Provider und bitten ihn, ausgehende HTTP-Verbindungen zuzulassen.</b> Plugins können nach wie vor auch lokal installiert werden. Laden Sie dazu einfach ein Plugin von <a href="http://spartacus.s9y.org">der originalen SPARTACUS Webseite</a> herunter, entpacken es und laden es in ihr Serendipity "plugins"-Verzeichnis hoch. Für Styx verlinken sie zur <a href="https://ophian.github.io/plugins/">Styx Spartacus Webseite</a>.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHDOWN', 'SPARTACUS konnte eine Test-Verbindung zu Google herstellen, aber nicht zum Spartacus-Speicherort. Möglicherweise blockiert ihr Server ausgehende Verbindungen, oder die Spartacus-Quelle ist nicht erreichbar. Kontaktieren Sie bitte ihren Web-Provider um sicherzustellen, dass ausgehende HTTP-Verbindungen möglich sind. <b>Sie können SPARTACUS erst nutzen, wenn ihr Server auf die Spartacus-Speicherorte zugreifen kann.</b>');

@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR', 'Eigene Mirror-Quelle');
@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC', 'Diese Option ist (normalerweise) nur für Experten gedacht. Falls keiner der voreingestellten Mirror-Server aufgrund von Downtime oder Problemen verfügbar ist, kann hier ein eigener Server-Name wie "https://mirror.org/styx/" eingetragen werden. Dort müssen die XML-Dateien für Spartacus im Verzeichnis liegen, und Unterverzeichnisse wie "additional_plugins" und "additional_themes" existieren. Geben Sie hier nur Mirrors ein, denen Sie voll vertrauen, und auf denen eine Kopie des Repositories gespeichert ist. Mehrere Mirrors können mit "|" getrennt eingegeben werden.');
@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC_ADD', 'ACHTUNG: Styx zieht seine Daten direkt von GitHub, wie oben eingestellt. Möchten Sie grundsätzlich nur die obigen Spiegelserver nutzen, müssen Sie vor jedem Konfigurationssubmit die eigenen Spiegelserver per Hand löschen, bzw. einmalig "none" hineinschreiben (ohne "").');

// Next lines were translated on 2009/06/03
@define('PLUGIN_EVENT_SPARTACUS_TRYCURL', 'Versuche cURL Bibliothek aus Fallback zu nutzen...');
@define('PLUGIN_EVENT_SPARTACUS_CURLFAIL', 'cURL Bibliothek gab auch einen Fehler zurück.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHFIREWALLED', 'Es war nicht möglich, die benötigten Daten vom Spartacus Verzeichnis zu laden, aber der Status des Verzeichnisses war abrufbar. Das bedeutet, dass Ihr Provider eine inhaltsbasierte Firewall verwendet und den Abruf von PHP code über das Netz mittels mod_security oder anderen Reverse-Proxies verhindert. Sie müssen entweder ihren Provider bitten, diesen Schutz abzuschalten oder Sie können das Spartacus Plugin nicht verwenden und müssen die Dateien manuell herunterladen.');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_PLUGINS', 'Spartacus verwenden, um Plugins zu laden?');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_THEMES', 'Spartacus verwenden, um Themes zu laden?');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE', 'Plugin-Versions Fernabfrage zulassen?');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_DESC', 'Wenn aktiviert, können Besucher von "%s" Versions-Informationen über alle installierten Plugins abrufen. Es wird dringend empfohlen, diese URL mittels benutzerdefinierten .htaccess Regeln vor unautorisiertem Zugriff zu schützen.');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL', 'Pfad zum Fernabruf der Versions-Information');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL_DESC', 'Gibt den letzten ("geheimen") Teil der URI an, welche die Benutzer wissen müssen, um den Fernabruf der Versions-Informationen durchzuführen.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CONNECT', 'FTP Fehler: Kann nicht per FTP verbinden.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_MKDIR', 'FTP Fehler: Kann das Verzeichnis (%s) nicht anlegen.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CHMOD', 'FTP Fehler: Kann die Verzeichnisrechte von (%s) nicht ändern.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_SUCCESS', 'FTP: Verzeichnis (%s) erfolgreich angelegt.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_USE', 'Lege Verzeichnis unter Verwendung von FTP an?');
@define('PLUGIN_EVENT_SPARTACUS_FTP_USE_DESC', 'Wenn PHP im safe_mode läuft, gelten einige Einschränkungen. Das Ergebnis dieser Einschränkungen ist, das in ein Verzeichnis, welches auf normale Weise erstellt wurde, nicht hochgeladen werden kann. Wird das Verzeichnis aber per FTP angelegt, funktioniert es. Wenn also am Webserver safe_mode = on eingestellt ist, ist dies der einzige Weg um SPARTACUS zu nutzen bzw. Mediendateien (Bilder usw.) hochzuladen. Die folgenden Zugangsdaten für Ihren Server müssen dazu ausgefüllt werden');
@define('PLUGIN_EVENT_SPARTACUS_FTP_SERVER', 'FTP Serveradresse');
@define('PLUGIN_EVENT_SPARTACUS_FTP_USERNAME', 'FTP Benutzername');
@define('PLUGIN_EVENT_SPARTACUS_FTP_PASS', 'FTP Passwort');
@define('PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR', 'FTP Serendipity Verzeichnis');
@define('PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR_DESC', 'Beim Login über FTP ist das Startverzeichnis nicht notwendigerweise das Serendipity-Verzeichnis. In diesem Fall ist es hier möglich, den Pfad vom FTP-Verzeichnis zum Serendipity-Verzeichnis anzugeben.');

@define('PLUGIN_EVENT_SPARTACUS_CSPRNG', ' (Zufällig generierte und kopierfertige Zeichenfolge durch Laden dieser Optionsseite: "%s%s". Kopieren Sie diese "gehashte" Zeichenkette (ohne ""), um sie zusammen mit dem serendipity_event_plugup Plugin für einen Plugin-Update Hinweis zu nutzen. Erlauben Sie dafür obige Fernabfrage Option. Solch eine Seite enthält, im Gegensatz zu der originalen "spartacus_remote" Zeichenkette, keine nennenswert zu schützende Information, außer der, dass ganz allgemein Plugin Updates vorliegen.)');

@define('PLUGIN_EVENT_SPARTACUS_ENABLE_THEMES_DESC', 'Da die Vorschaubilder für die zusätzlichen Themes beim ersten tatsächlichen Durchlauf zwischengespeichert werden, kann dieser Abruf eine Weile dauern (2-3 Minuten), wenn Sie den Themes Link in der Seitenleiste zum ersten Mal aufrufen, nachdem Sie diese Option auf Ja (aktiviert) gesetzt haben. Warten Sie, bis die Seite ihre Hintergrundarbeit beendet hat. Sollte die Ausführung mit einem "PHP Fatal error:  Maximum execution time" Fehler fehlschlagen, laden Sie die Seite einfach neu, um diesen Lauf zu beenden.');

