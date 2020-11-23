<?php
// Globals
@define('USE_CORENAV', 'Globale Navigation einbinden?');
@define('NAV_MENU', 'navigation');
// Landing page
@define('PURE_START_WELCOME', 'Erlaube eine themeeigene Startseite');
@define('PURE_START_WELCOME_DESC', 'Erlaubt das Ausführen einer themeneigenen Startseite mit einigen neueren Einträgen als Zusammenfassung und einem beliebigen Einleitungstext-Block. Der vollständige Blog-Inhalt befindet sich unter "?frontpage" und ein Link dorthin ist bereits platziert. Siehe die detaillierten Info-Beschreibungen.');
@define('PURE_START_HOME_TITLE', 'Feld links: Überschrift der Artikelliste');
@define('PURE_START_HOME_TITLE_DESC', 'Erste Zeile, linkes und größeres Rasterfeld. Einfacher Titel-Text ohne HTML-Markup. Für Default-Änderungen, lesen Sie sorgfältig die detaillierte "FETCHING ARTICLES" Information zur Ausgestaltung ihrer eigenen Blog Belange!');
@define('PURE_START_HOME_TITLE_DEFAULT', 'Die neuesten Artikel');
@define('PURE_START_WELCOME_BLOG_LINK_TITLE', 'Feld rechts: Feld-Linkname (nur als Tooltip)');
@define('PURE_START_WELCOME_BLOG_LINK_TITLE_DEFAULT', 'Zu meinem Blog..');
@define('PURE_START_WELCOME_TITLE', 'Feld rechts: Überschrift Einleitungstext');
@define('PURE_START_WELCOME_TITLE_DESC', 'Schreiben Sie ein einfaches « none » in das Feld um das Head Markup auszuschalten, oder leeren Sie das Feld um den Abstand zu halten.');
@define('PURE_START_WELCOME_TITLE_DEFAULT', 'Über dieses Blog');
@define('PURE_START_WELCOME_CONTENT', 'Feld rechts: (HTML) Einleitungstext-Block');
//details
@define('PURE_START_WELCOME_GROUP_TITLE', '<h3>Startseiteneinstellungen (optional):</h3>
<p><a class="media_fullsize" href="templates/purestart/pure_home-example-600.webp" data-fallback="templates/purestart/pure_home-example-600.png" title="Vollbild: pure_home-example-600.png (WepP)" data-pwidth="600" data-pheight="683"><picture>
  <source srcset="templates/purestart/home-ex-200.webp" type="image/webp">
  <img class="serendipity_image_right" src="templates/purestart/home-ex-200.png" alt="" />
</picture></a></p>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="öffne/schließe Inhalt">LANDING PAGE - HOME</summary>
  <div class="clearfix helpbox">
    <p>Wie Sie auf dem Beispielbild sehen können, kann Ihr Rasterkartendesign 2 Zeilen mal 3 Spalten für die Startseite haben. Es ist sogar ganz einfach, dies um zusätzliche Zeilen oder Karten zu erweitern.</p>
    <p>Die erste Zeile mit 2 (besser gesagt 3 Karten, denn die erste überspannt zwei Spalten) sind bereits fest in der template Datei index.tpl kodiert, siehe etwa ab Zeile 111. Laden Sie die Datei in ihren Editor, um sie auf Ihre Bedürfnise hin abzustimmen. Aber lesen Sie hier zunächst weiter, um sich besser damit vertraut zu machen.</p>
    <p>Die erste <b>linke</b> Karte ist so gestaltet, dass die letzten Artikel des Blogs als Überschriftliste über <code>{serendipity_fetchPrintEntries ...}</code> geholt werden. <b>Lesen</b> Sie mehr dazu in der detaillierten: "<em>FETCHING ARTICLES</em>" Info-Kasten Beschreibung. Die Standardeinstellung liefert die neuesten 5 Blog-Artikel (und aus allen Kategorien).</p>
    <p>Der Inhalt der <b>rechten</b> oberen Karte ist das, was Sie über die  unten folgenden Theme-Optionen setzen können. Für das <b>HTML</b> Feld beachten Sie:</p>
    <ul>
      <li>Keine Links hier, bitte! Die ganze Boxkarte selbst ist der Link, der auf Ihren eigentliches Blog zeigt (und Sie können keine Links ineinander verschachteln)!</li>
      <li>Schreiben Sie Ihren (p)-aragraphierten Inhaltstext kurz genug, um die Höhe der linken Nachbarkarte mit den "5" neuesten Blogartikeln nicht unnötig zu vergrößern!</li>
    </ul>
    <p>Die Option: "' . PURE_START_HOME_TITLE . '" definiert die Überschrift Ihrer neuesten Artikel des 2-spaltigen Rasterfeldes.<p>
    <p><b><u>Achtung</u>:</b><br>Dieses Startseiten-Beispiel nutzt einen netten Service von «<code> lorempixel.com</code> », der Beispielbilder aus verschiedenen Kategorien einsetzt und solange als "Rückgriff" genutzt wird, wie keine eigenen Bilder gesetzt und definiert sind (siehe index.tpl und die config.inc.php Datei). Sie sollten diese Bilder aber nicht für den Produktivbetrieb verwenden. Sie werden einzig hier benutzt, um einen ersten Eindruck der möglichen Dastellung zu bekommen und dienen nur Development-Zwecken.</p>
  </div>
</details>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="öffne/schließe Inhalt">OPTIONAL GRID CARDS - The 2cd row [++]</summary>
  <div class="clearfix helpbox">
    <p>An dieser Stelle (in der Datei config.inc.php) können Sie sehr einfach, <b>manuell</b> (mit einem Editor-Programm wie Notepad++ oder ähnlich), weitere <b>3 Karten</b> (+/-) für Ihre eigene Startseite hinzufügen und definieren. Die wichtigsten Array-Elemente sind [<em>image, link, title, body</em>]:</p>
    <ul>
      <li>Jede Karte muss mit einem Bild versehen sein, zum Beispiel aus der Mediathek:<br><code>$serendipity[\'baseURL\'] . $serendipity[\'uploadPath\'] . \'your/image.styxThumb.png\'</code>.<br>Querformatige Vorschaubilder (400px) der Mediathek eignen sich von der Größe her gut für diese Karten.</li>
      <li>Jede ganze Karte an sich ist ein Link, der zB auf <code>$serendipity[\'baseURL\'] . \'archive\'</code> oder eine Statische Seite <code>$serendipity[\'baseURL\'] . \'pages/aboutme.html\'</code> verweist.</li>
      <li>Jede Karte sollte einen kurzen Titel aus \'reinem Text\', beispielsweise: \'Über mich\' beinhalten.</li>
      <li>Jede Karte sollte ein kurzes(!) Intro oder Willkommens-Text, eingeschlossen von (P)-aragraphen, zB \'&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. [...]&lt;/p&gt;\' ohne jeden Link oder Bild o.Ä. enthalten. Sie können Mehrfachabsätze, br-Zeilenumbrüche und andere einfache Stilelemente hinzufügen.</li>
    </ul>
    <p>Unter Ausnutzung des Vorteils der Unterstützung unserer neuen webP-generierten Bilder-Thumbnails sollte der image -webp- Wert wie folgt aussehen::<br>
          <code>\'webp\' => $serendipity[\'baseURL\'] . $serendipity[\'uploadPath\'] . \'relative/path/to/image/.v/imagename.styxThumb.webp\'</code>. Beachten Sie den <b>/.v</b> Bild Variations Ordner!</p>
    <p>Es kann vorkommen, dass einige normale Vorschaubilder kleiner (KB) sind als die generierte WebP-Variationsdatei, siehe die Bild-Metadaten ihrer Mediathek. In diesem Fall lassen Sie den webp-Wert durch ein Paar einfache Anführungszeichen \'\' einfach leer.</p>
    <p>Wenn <b>aktiviert</b>, d.h. <em>unkommentiert gesetzt und mit eigenen Inhalten befüllt</em>, führt das Array eine Smarty-Funktion im Kopf der Datei index.tpl aus, um jede durch das Array definierte Gitterkarte zu platzieren.</p>
  </div>
</details>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="öffne/schließe Inhalt">EXAMPLE ARRAY</summary>
  <div class="clearfix helpbox">
    <pre><code class="language-php hljs">$serendipity[<span class="hljs-string">\'smarty\'</span>]-&gt;assign(<span class="hljs-string">\'addcards\'</span>, <span class="hljs-keyword">array</span>(
    <span class="hljs-number">0</span> =&gt; <span class="hljs-keyword">array</span>(
        <span class="hljs-string">\'image\'</span> =&gt; <span class="hljs-keyword">array</span>(
            <span class="hljs-string">\'src\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . $serendipity[<span class="hljs-string">\'uploadPath\'</span>] . <span class="hljs-string">\'path/to/thomas.styxThumb.jpg\'</span>,
            <span class="hljs-string">\'webp\'</span> =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . $serendipity[<span class="hljs-string">\'uploadPath\'</span>] . <span class="hljs-string">\'path/to/.v/thomas.styxThumb.webp\'</span>
            ),
        <span class="hljs-string">\'link\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . <span class="hljs-string">\'archive\'</span>, 
        <span class="hljs-string">\'title\'</span> =&gt; <span class="hljs-string">\'Blog archives\'</span>,
        <span class="hljs-string">\'body\'</span>  =&gt; <span class="hljs-string">\'&lt;p&gt;Aliquam lobortis nisi eget turpis blandit rhoncus. Cras placerat accumsan lacus, tristique pellentesque tortor condimentum ut. In hac habitasse platea dictumst. Integer fermentum, velit a vehicula porttitor, leo nunc imperdiet est, vitae imperdiet ipsum velit a sapien. Nulla quis justo in magna porttitor sodales eu non sapien.&lt;/p&gt;\'</span>
        ),
    <span class="hljs-number">1</span> =&gt; <span class="hljs-keyword">array</span>(
        <span class="hljs-string">\'image\'</span> =&gt; <span class="hljs-keyword">array</span>(
            <span class="hljs-string">\'src\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . $serendipity[<span class="hljs-string">\'uploadPath\'</span>] . <span class="hljs-string">\'2020/10/triangel.styxThumb.jpg\'</span>,
            <span class="hljs-string">\'webp\'</span> =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . $serendipity[<span class="hljs-string">\'uploadPath\'</span>] . <span class="hljs-string">\'2020/10/.v/triangel.styxThumb.webp\'</span>
            ),
        <span class="hljs-string">\'link\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . <span class="hljs-string">\'pages/contact/\'</span>,
        <span class="hljs-string">\'title\'</span> =&gt; <span class="hljs-string">\'Blog bell\'</span>,
        <span class="hljs-string">\'body\'</span>  =&gt; <span class="hljs-string">\'&lt;p&gt;Ring my Tubular Bells, Sweety!&lt;/p&gt;\'</span>
        ),
    <span class="hljs-number">2</span> =&gt; <span class="hljs-keyword">array</span>(
        <span class="hljs-string">\'image\'</span> =&gt; <span class="hljs-keyword">array</span>(
            <span class="hljs-string">\'src\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . $serendipity[<span class="hljs-string">\'uploadPath\'</span>] . <span class="hljs-string">\'we/are/so/chatty.styxThumb.jpg\'</span>,
            <span class="hljs-string">\'webp\'</span> =&gt; <span class="hljs-string">\'\'</span>
            ),
        <span class="hljs-string">\'link\'</span>  =&gt; $serendipity[<span class="hljs-string">\'baseURL\'</span>] . <span class="hljs-string">\'comments/\'</span>,
        <span class="hljs-string">\'title\'</span> =&gt; <span class="hljs-string">\'Blog comments\'</span>,
        <span class="hljs-string">\'body\'</span>  =&gt; <span class="hljs-string">\'&lt;p&gt;Rien de vas plus: Sed consequat lectus diam, vehicula dictum nulla. Sed rutrum mollis enim, in posuere tortor congue sed. Duis ante arcu, bibendum sit amet eleifend ac, molestie eget elit. Integer risus lacus, dapibus ut commodo eu, laoreet at odio. Curabitur varius, urna ac tincidunt gravida, eros dui consectetur nunc, euismod consequat turpis urna non libero.&lt;/p&gt;\'</span>
        ),
    ));
    </code></pre>
  </div>
</details>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="öffne/schließe Inhalt">FETCHING ARTICLES</summary>
  <div class="clearfix helpbox">
    <p>Finden Sie eine solche Smarty Funktion (Zeile ~125): <code>{serendipity_fetchPrintEntries short_archives=true ... limit="0,5"}</code> in Ihrer <b>index.tpl</b> Datei und stimmen diese auf Ihre eigenen Bedürfnisse hin ab.</p>
    <p>Wählen Sie eine einzelne category ID, oder kombinieren Sie mehrere Kategorien mittel Semikolon «<code> category="1;2;5"</code> ». Setzen Sie diesen Parameter gar nicht, wenn Sie die aktuellsten Einträge aller Kategorien wünschen.</p>
    <p>Setzen Sie (optional) eine «<code> template="mycat.tpl"</code> » Parameter-Option, wenn Sie eine eigene template Datei für die Ausgabe erstellt haben; siehe Standard- und Beispieldatei in "<code>templates/default/smarty_entries_short_archives.tpl</code>" oder kopieren und verändern Sie die "<code>entries_summary.tpl</code>" Datei dieses Themes (aber Achtung: <code>{$dateRange.0|&hellip;}</code> liegt hier nicht vor).</p>
    <p>Der «<code> limit="0,5"</code> » Parameter definiert Auswahllimitierungen für die Datenbankabfrage. Dies ist eine Zeichenfolge, die angibt, wie viele Blog-Artikel ausgelesen werden sollen. Diese Zeichenfolge kann entweder eine einzelne Zahl oder eine Angabe <code>X, Y</code> enthalten, wobei <code>X</code> der Index des Artikels ist, aus dem die Artikel angezeigt werden, und <code>Y</code> die Anzahl der zu lesenden Artikel ist. Ein Parameter <code>limit="5, 10"</code> würde 10 Artikel ausgeben und die ersten 5 aktuellen Artikel überspringen. Wenn dieser Parameter nicht angegeben wird, werden so viele Artikel gelesen, wie in der Konfiguration des Blogs als Standard definiert sind.</p>
  </div>
</details>');
// pure
@define('PURE_START_GROUP_SEPARATOR_TITLE', '<h3>Globale Theme und Navigations-Tab Optionen:</h3>');
@define('PURE_START_PLINK_TEXT', 'Link');
@define('PURE_START_PLINK_TITLE', 'Permanenter Link zu diesem Kommentar');
@define('PURE_START_REPLYORIGIN', 'Antwort auf');
@define('PURE_START_USE_HIGHLIGHT', 'Lade code highlight js in der Eintrags Liste');
@define('PURE_START_USE_HIGHLIGHT_DESC', 'Normalerweise wird dieses Javascript nur auf Einzelseiten für die Hervorhebung von code Beispielen in Kommentaren geladen. Sollten Sie aber solche auch in Ihren Einträgen verwenden, möchten Sie es vielleicht auch in der Eintragsliste aktiv geladen haben.');
