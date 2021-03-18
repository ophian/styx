<?php

@define('B46_USE_CORENAV', 'Globale Navigation einbinden?');
@define('B46_SEND_MAIL', 'Schicke Email');
@define('B46_USE_SEARCH', 'Zeige Suche im Kopf?');
@define('B46_JUMPSCROLL', 'Nutze scrolljump Button?');
@define('B46_HUGO', 'Artikel [text] als klappbarer Aufmacher mit Länge');
@define('B46_HUGO_TTT', 'Öffnen und schließen mit Mausklick, oder, wenn Feld aktiv, mit Leertaste der Tastatur');
@define('B46_HUGO_TITLE_ELSE', 'Zum Artikel');
@define('B46_TEASE', '0 meint: Nicht aktiviert!');
@define('B46_TEASE_COND', ' - Nur Ziffern & wähle Typ entw./oder!'); // start with space!
@define('B46_CARD', 'Artikel [text] als Kachel Aufmacher mit der Länge');
@define('B46_CARD_META', ' Die jeweilige Kartenzeile mit den Benutzer & Datum Metadaten kann u.U. zu lang werden und wird dann versteckt abgeschnitten. Bitte überprüfen Sie Ihren persönlichen Längenbedarf durch die Verwendung von kurzen Benutzernamen und/oder durch diese Konfiguration des Datumsformates (s.o.).'); // start with space!
@define('B46_CARD_TITLE_ELSE', 'Kein Artikel Vorschau Text vorhanden');
@define('B46_LEAD', 'Bei Aufmacher, vorangestellter Feature Artikel');
@define('B46_LEAD_DESC', ' Oder tragen Sie folgende urlartige "array" Konfiguration (bei unveränderten Schlüsseln) ein. Die Schlüssel "title" und "text" sind mindestens erforderlich: '); // start with space!
@define('B46_NAV_ONELINE', 'Navigation als eigenständige Zeile?');

// If used within template files, add previously parent theme defines here, since the lang CONSTANT engine:fallback will work only for the config.inc file up from Styx 3.3.1!
@define('BS_REPLYORIGIN', 'Antwort auf');

@define('B46_INSTR', '<details><summary role="button" aria-expanded="false">B46: Click me to open extended helper information Readme</summary>
<br><b>Hinweis:</b> "b46" ist ein Upgrade Engine:Template des "bootstrap4" Themes.
<ul>
    <li>Für die Darstellung der Tags unter Einträgen, erlauben Sie die "Erweitertes Smarty" Option im Freetag Plugin.</li>
    <li>Lassen Sie in diesen Theme Optionen das "#" in der URL eines Navigationsleisten-Links stehen, so kann in der index.tpl Haupt-Template Datei ein popover Submenu manuell erstellt werden. Beispiel vorhanden.</li>
</ul>
<b>Featured Article Pro Tip:</b>
<ul>
    <li>Legen Sie eine neue Kategorie mit dem Namen "feature" an.<br>Erklären Sie sich in der Beschreibung (für später) selbst, was diese bedeutet, zB. "A special category for temporary featured articles (b46)". Speichern Sie die neue Kategorie ab.</li>
    <li>Installieren Sie anschließend das "Eigenschaften/Templates von Kategorien (serendipity_event_categorytemplates)" Ereignis Plugin und konfigurieren Sie es mit den default Eigenschaften ( <em>Nein, timestamp DESC, Nein, Leer </em>). Dies ist, wie der folgende Schritt, optional.</li>
    <li>Gehen Sie zurück zur Kategorienliste und rufen Sie die eben neu erstellte Kategorie "feature" zum Bearbeiten auf. Diese hat nun am unteren Ende eine neue Options-Sektion "'.sprintf(ADDITIONAL_PROPERTIES_BY_PLUGIN, "Eigenschaften/Templates von Kategorien").'". Aktivieren Sie darin die letzte Option, damit die Kategorie künftig von Eintragslisten und RSS-Feeds ausgeschlossen wird. (<em>Zu Recht können Sie im Weiteren bemerken, dass das ja im Folgenden beim Eintrag ebenfalls aktiviert werden wird. Doch würde <u>diese</u> Plugin Einstellung zusätzlich bei anderen Listen/Verlinkungen wie dem entrypaging Plugin helfen. Experimentieren Sie also damit, ob sie es überhaupt benötigen.</em>)</li>
    <li>Erstellen Sie nun einen Eintrag der im Weiteren als Featured Article in diesen Theme Optionen als "'.B46_LEAD.'" verlinkt werden soll. Achten Sie darauf, diesen Eintrag nur der Kategorie "feature" zuzuordnen und in den "Erweiterten Eigenschaften von Artikeln" des Eintragsformulares ein Häckchen bei "Nicht in Artikelübersicht zeigen" und (optional) "Eintragsinhalt im RSS-Feed verstecken" zu setzen.</li>
    <li>Nutzen Sie einfach die Artikel Vorschau, um sich den Link zum Artikel einfach herauszukopieren (Titel mit rechten Maustaste "Link speichern"). Den kopierten Link setzen Sie dort ein, wo hier beispielhaft "#" als "url=#" im Array: "<em>image=/uploads/features/fa_1.webp&height=350px&title=My first longer featured blog post with ID 1&text=Summary feature of my post’s contents.&url=#&link=Continue reading...</em>" steht (siehe voll funktionierendes Demo Beispiel in der Info zur Option "'.B46_LEAD.'").</li>
</ul>
<p>Et voilà ! Ihr herausgehobener "featured article" wird nun auf den Blog-Artikel als ganzer Artikel verweisen. Ansonsten ist er nur über den Archives-/Kategorielink im Blog erreichbar. Wird dieser Artikel irgendwann für einen anderen/neuen als normaler Artikel zurückgestuft, so ändern Sie im Artikel selbst die Kategorie und nehmen die beiden Häcken in den Erweiterte Eigenschaften von Artikeln (wie oben beschrieben) wieder heraus. Sie sehen also mit dieser Theme Option eine andere Art des Umganges mit quasi "Dauerhaften Artikeln", die ansonsten natürlich zum Serendipity Standardrepertoire in den "Erweiterten Artikeleigenschaften" gehören.</p>
</details>');

