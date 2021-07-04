<?php

/**
 *  @version
 *  @file
 *  @author
 *  DE-Revision: Revision of UTF-8/lang_de.inc.php
 */

@define('PLUGIN_EVENT_NL2BR_NAME', 'Textformatierung: NL2BR');
@define('PLUGIN_EVENT_NL2BR_DESC', 'Konvertiert Zeilenumbrüche zu HTML');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP', 'Überprüfe Markup-Plugins?');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP_DESC', 'Überprüft automatisch auf existierende Markup-Plugins, um die weitere Ausführung des NL2BR-Plugins zu untersagen. Dies gilt dann, wenn WYSIWYG oder spezifische Markup-Plugins entdeckt werden.');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS', 'Ausnahmen für alle folgenden Regeln');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS_DESC', 'Eine Liste von benutzerdefinierten HTML-Tags, innerhalb derer keine Umbrüche konvertiert werden sollen. Konfigurationsvorschlag: "nl,pre,geshi,textarea". Trennen Sie mehrere HTML-Tags mit Komma. Hinweis: Die eingegebenen Tags sind reguläre Ausdrücke!');
@define('PLUGIN_EVENT_NL2BR_PTAGS', 'Nutze P-Tags');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC', 'Setze statt br-Tags p-Tags ein.');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC2', 'Dies kann bei verschachtelten Markup-Fällen aber zu Fehlinterpretationen führen!');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG', 'ISOBR Isolations-Default BR Einstellung');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG_DESC', 'Mit dem funktionslosen NONE-HTML-Tag <nl> </nl> als NL2BR Isolations-Default Einstellung, kann die NL2BR Funktion nun so genutzt werden, dass alles innerhalb dieses Tags nicht von NL2BR geparst wird. Auch nicht verschachtelte mehrfach Vorkommen im Text werden unterstützt! Beispiel: <nl>do not parse newline to br inside this multiline-textblock</nl>');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS', 'Nutze BR-Clean-Tags fallback, wenn ISOBR false');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS_DESC', 'Bei Benutzung von <HTML-Tags> in den Einträgen, die nicht zufriedenstellend mit der ISOBR Config-Option gelöst werden können, lösche nl2br Umbruch nach <tag>. Dies gilt für alle <tags>, die mit > oder >\n enden! Default (table|thead|tbody|tfoot|th|tr|td|caption|colgroup|col|ol|ul|li|dl|dt|dd)');
@define('PLUGIN_EVENT_NL2BR_CONFIG_ERROR', 'Konfigurations Fehler! Die Option: "%s" wurde zurückgesetzt, weil die Option \'%s\' aktiv geschaltet war! Benutzen sie bitte nur eine dieser Optionen.');

@define('PLUGIN_EVENT_NL2BR_ABOUT_TITLE', 'BITTE BEACHTEN Sie die Auswirkungen dieses Markup-Plugins:');
@define('PLUGIN_EVENT_NL2BR_ABOUT_DESC', '<p>Dieses Plugin überträgt Zeilenumbrüche in HTML-Zeilenumbrüche, so dass sie in Ihrem Blog-Eintrag erscheinen.</p>
<p><b><u>Vorbemerkung</u>:</b> Die Serendipity Standard Auslieferung nutzt per default seit jeher keine anderen Markup Plugins. Diese Textform nennen wir hier PLAIN (TEXT) EDITOR. Text ist reiner Text und per ENTER oder strukturell eingefügte Zeilenumbrüche werden kodiert in der Datenbank gespeichert und durch dieses Plugin erst bei Ausgabe zur Laufzeit in HTML verwandelt.</p>
<p><b>PLAIN EDITOR</b>s Basis-Funktionalität: Konvertiere die Zeilenumbrüche zu &lt;br&gt; - Tags.<br>
<b>PLAIN EDITOR</b>s Erweiterte Funktionalität: Parse den Text in &lt;p&gt;-Tags unter Berücksichtigung der HTML-Syntax wo sie erlaubt sind und automatische Ignorierung bei vorformatiertem Text mit &lt;pre&gt; oder innerhalb von &lt;style&gt; oder &lt;svg&gt;-Tags.</p>
<p>Dies kann insbesondere dann für Sie zu Problemen führen, wenn Sie während des Betriebs ihres Blogs das Markup-Plugin wechseln, danach also Inhalte mit unterschiedlichen Anforderungen in den Eintragstabellen zu finden sind:</p>
<ul>
    <li>Der eingebaute <strong>WYSIWYG-Editor</strong> und das <strong>CKEditor Plus</strong> Plugin speichern bereits korrektes HTML - bereit zur Ausgabe - und schalten automatisch das NL2BR Plugin für die Ausgabe ab. (Ansonsten gäbe es eine Verdopplung aller codierten Zeilenumbrüche und würde das Ausgabelayout zumindest verändern oder sogar zerstören.)</li>
    <li>Wenn Sie andere Markup-Plugins in Verbindung mit diesem Plugin verwenden, die bereits Zeilenumbrüche übersetzen. Die <strong>TEXTILE</strong>- und <strong>MARKDOWN</strong>-Plugins sind Beispiele für solche Plugins. (Auch für diese beiden gibt es entsprechende Vorkehrungen zur Abschaltung von NL2BR.)</li>
</ul>
<p>Dieses "<em>Problem</em>" gilt in hohem Maße aber nur, wenn sie sehr alte Einträge aus der Frühzeit von Serendipity haben, bei denen der Markup Zustand bzw. die NL2BR-Anforderung nicht entsprechend hinterlegt wurden.</p>
<p>Um weitere Probleme zu vermeiden, sollten Sie das nl2br-Plugin entweder für Einträge global oder per Eintrag im Abschnitt "Erweiterte Eigenschaften" eines Eintrags deaktivieren, wenn Sie das Plugin für die Eingabeeigenschaften (entryproperties) installiert haben.</p>
<p><u>Genereller Hinweis:</u> Das nl2br Plugin ist also nur wirklich sinnvoll, wenn Sie</p>
<ul>
    <li>keine anderen Markup-Plugins verwenden - oder</li>
    <li>keinen WYSIWYG-Editor verwenden - oder</li>
    <li>lediglich Linebreak-Transformationen auf Kommentare zu Ihren Blog-Einträgen anwenden möchten, und keine möglichen Markups anderer Plugins zulassen, die Sie nur für Blogeinträge verwenden.</li>
</ul>
<p>NL2BR ist ein Kurzform-Wort. Lese als: Funktion "NL zu BR", <b>nicht</b> "NL zwei BR"!</p>');

