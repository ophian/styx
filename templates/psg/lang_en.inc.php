<?php
// Globals
@define('USE_CORENAV', 'Use global navigation?');
@define('NAV_MENU', 'navigation');
// Landing page
@define('PURE_START_WELCOME', 'Enable a theme start landing page');
@define('PURE_START_WELCOME_DESC', 'Allows to run a theme own start landing page with some recent entries as summary and a heading welcome styled text block. Full blog content is at "?frontpage" and a link is already placed into. Read the detailed info descriptions.');
@define('PURE_START_HOME_TITLE', 'Left Card: Heading articles list');
@define('PURE_START_HOME_TITLE_DESC', 'First row, left card; Simple title without HTML-markup. For default changes, carefully read the detailed: "FETCHING ARTICLES" info for your own blog needs!');
@define('PURE_START_HOME_TITLE_DEFAULT', 'Most recent posts');
@define('PURE_START_WELCOME_BLOG_LINK_TITLE', 'Right Card: Card link name (as tooltip)');
@define('PURE_START_WELCOME_BLOG_LINK_TITLE_DEFAULT', 'Enter my blog..');
@define('PURE_START_WELCOME_TITLE', 'Right Card: Head title');
@define('PURE_START_WELCOME_TITLE_DESC', 'Enter plain « none » to disable headings markup or empty the field to keep the space.');
@define('PURE_START_WELCOME_TITLE_DEFAULT', 'About this blog');
@define('PURE_START_WELCOME_CONTENT', 'Right Card: (HTML) content block');
//details
@define('PURE_START_WELCOME_GROUP_TITLE', '<h3>Startpage settings (optional):</h3>
<p><a class="media_fullsize" href="templates/psg/home-example-600.webp" data-fallback="templates/psg/home-example-600.png" title="Vollbild: home-example-600.png (WepP)" data-pwidth="600" data-pheight="683"><picture>
  <source srcset="templates/psg/home-ex-200.webp" type="image/webp">
  <img class="serendipity_image_right" src="templates/psg/home-ex-200.png" alt="" />
</picture></a></p>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="open/close information content">LANDING PAGE - HOME</summary>
  <div class="clearfix helpbox">
    <p>As you can see in the example image, your starting grid card design can have 2 rows by 3 columns for the startpage. It is even easy to extend this by additional rows too.</p>
    <p>The first row with 2 (better say 3 cards, since the first spans over 2 columns) are already hard coded in the index.tpl file, see at line ~111. Check into to change for your needs. But first keep reading-on to get known about it.</p>
    <p>The first <b>left</b> card is made up for fetching the latest articles to show up by <code>{serendipity_fetchPrintEntries ...}</code>. <b>Read</b> the detailed: "<em>FETCHING ARTICLES</em>" info box description. The default setting is fetching the latest 5 blog articles by all categories.</p>
    <p>The <b>right</b> card content is what you can fully define and set per config option down below. Keep in mind for the <b>HTML</b> field:</p>
    <ul>
      <li>No links here, please! The box itself is a link pointing to your actual blog and you cannot nest links into each other!</li>
      <li>Write your paragraphed content text short enough to not expand the height of this neighbour\'s first two grid column box with the "5" latest blog articles!</li>
    </ul>
    <p>The option: "' . PURE_START_HOME_TITLE . '" defines the head title of your latest articles first card 2-column grid box.<p>
    <p><b><u>Note</u>:</b><br>This homepage example uses a nice service from «<code> lorempixel.com</code> » which uses sample images from different categories as a fallback and is used as long as no self-defined images are set (see index.tpl and the config.inc.php file). You should not use these sample images for production use. They are only used here to get a first impression of the possible presentation and are only for development purposes.</p>
  </div>
</details>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="open/close information content">OPTIONAL GRID CARDS - The 2cd row [++]</summary>
  <div class="clearfix helpbox">
    <p> At here (in the config.inc.php file) you can very easily, <b>manually</b> (by editor program notepad++ or alike) add and define another <b>3 cards</b> (or +/-) for your landing page pleasure. The main array items are [<em>image, link, title, body</em>]:</p>
    <ul>
      <li>Each card shall have an image, for example pointing to the MediaLibrary:<br><code>$serendipity[\'baseURL\'] . $serendipity[\'uploadPath\'] . \'your/image.styxThumb.png\'</code>.<br>The MediaLibrary thumb\'ed (400px, landscape) images are already good enough for single cards too.</li>
      <li>Each card is a link, for example pointing to <code>$serendipity[\'baseURL\'] . \'archive\'</code> OR a static page at <code>$serendipity[\'baseURL\'] . \'pages/aboutme.html\'</code></li>
      <li>Each card should hold a short title of just \'plain text\', eg. \'About me\'</li>
      <li>Each card should hold a short(!) intro or welcome text inside a paragraph, eg \'&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipiscing elit. [...]&lt;/p&gt;\' without any link or image or so. You can add multi-paragraphs, br-linebreaks and other simple styling elements.</li>
    </ul>
    <p>Taking the advantage to support our new webP generated image thumbs, the image webp value should look like this:<br>
          <code>\'webp\' => $serendipity[\'baseURL\'] . $serendipity[\'uploadPath\'] . \'relative/path/to/image/.v/imagename.styxThumb.webp\'</code>. Note the <b>/.v</b> image variations directory!</p>
    <p>It may happen, that some normal image thumbs are smaller in size (KB) than the generated web variation file, see your MediaLibrary images meta info data. In this case just leave the webp value empty by a pair of single quotes \'\'.</p>
    <p><b>If enabled</b>, say: <em>set uncommented and filled up with your own content</em>, <b>the array</b> runs a smarty function <b>on top</b> of the index.tpl file to place each grid card defined by the array.</p>
  </div>
</details>
<details>
  <summary class="button button_link" role="button" aria-expanded="false" title="open/close information content">EXAMPLE ARRAY</summary>
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
  <summary class="button button_link" role="button" aria-expanded="false" title="open/close information content">FETCHING ARTICLES</summary>
  <div class="clearfix helpbox">
    <p>In your <b>index.tpl</b> file find this Smarty function (line ~125): <code>{serendipity_fetchPrintEntries short_archives=true ... limit="0,5"}</code> and tweak it for your current needs.</p>
    <p>Choose a single category ID, or nest multi-categories by semicolon «<code> category="1;2;5"</code> ». Do not set this parameter at all if you want only the most recent entries of all categories.</p>
    <p>Set a (optional) «<code> template="mycat.tpl"</code> » parameter option if you need a very own created template file; See default example file in "<code>templates/default/smarty_entries_short_archives.tpl</code>" or copy and style this themes "<code>entries_summary.tpl</code>" file (note: <code>{$dateRange.0|&hellip;}</code> does not exist).</p>
    <p>The «<code> limit="0,5"</code> » parameter defines ranging limits for the database fetch. This is a string that indicates how many blog articles should be read. This string can contain either a single number or a specification <code>X, Y</code>, where <code>X</code> is the index of the article from which articles are displayed and <code>Y</code> is the number of articles to be read. A parameter <code>limit="5, 10"</code> would output 10 articles and skip the first 5 recent articles. If this parameter is not specified, as many articles are read as the default is defined in the configuration of the blog..</p>
  </div>
</details>');
@define('PURE_START_USE_HIGHLIGHT', 'Load code highlight js for entries list');
@define('PURE_START_USE_HIGHLIGHT_DESC', 'Normally this will automatically load only in single entry views for comments, but if you have code snips in your entries you might want to allow this within entries too.');
