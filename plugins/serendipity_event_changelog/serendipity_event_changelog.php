<?php

// This line makes sure that plugins can only be called from the Serendipity Framework.
if (IN_serendipity !== true) {
    die ("Don't hack!");
}

// Load possible language files.
@serendipity_plugin_api::load_language(dirname(__FILE__));

// Extend the base class
class serendipity_event_changelog extends serendipity_plugin
{
    var $title = PLUGIN_CHANGELOG_TITLE;

    // Setup metadata
    function introspect(&$propbag)
    {
        $propbag->add('name',           PLUGIN_CHANGELOG_TITLE);
        $propbag->add('description',    PLUGIN_CHANGELOG_DESC);
        $propbag->add('stackable',      false);
        $propbag->add('author',        'Ian Styx');
        $propbag->add('version',       '1.35');
        $propbag->add('requirements',  array(
            'serendipity' => '2.0.2',
            'php'         => '5.3.0'
        ));
        $propbag->add('event_hooks',    array(
            'css_backend'         => true,
            'backend_maintenance' => true,
            'external_plugin'     => true
        ));
        $propbag->add('groups',         array('BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN', 'MAINTENANCE'));
    }

    // Setup title
    function generate_content(&$title)
    {
        $title = $this->title;
    }

    // Listen on events
    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            switch($event) {
                case 'external_plugin':
                    if (!serendipity_checkPermission('adminUsers')) {
                        if (defined('IN_serendipity_admin')) echo "Don't hack! Admin permissions required.";
                        return false;
                    }
                    $separator = "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n\n";
                    $part = explode('/', $eventData);
                    if ($part[0] == 'changelog') {
                        if (!headers_sent()) {
                            header('HTTP/1.0 200');
                            header('Status: 200 OK');
                        }
                        header('Content-language: en');
                        header('Content-type: text/plain; charset=ISO-8859-1');
                        $mb_blah = (LANG_CHARSET != 'ISO-8859-1') ? mb_convert_encoding(PLUGIN_CHANGELOG_LOGGER_BACKBLAH, 'ISO-8859-1', LANG_CHARSET) : PLUGIN_CHANGELOG_LOGGER_BACKBLAH;
                        $file =  $mb_blah . "\n\n" . file_get_contents($serendipity['serendipityPath'] . 'docs/NEWS');
                        echo $file;
                    }
                    if ($part[0] == 'logs' && isset($serendipity['logger']) && is_object($serendipity['logger'])) {
                        if (!headers_sent()) {
                            header('HTTP/1.0 200');
                            header('Status: 200 OK');
                        }

                        header('Content-language: en');
                        header('Content-type: text/html; charset=UTF-8');
                        $files = glob($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/logs/*.txt');
                        $files = array_combine($files, array_map("filemtime", $files));
                        $x = count($files);
                        // Whow, this took long to debug ...
                        // Since Firefox & Chromium encase the $file content with a DOM added <pre> tag,
                        // either styled by an alternate header added plaintext.css resource file, or via inline styles
                        // with "word-wrap: break-word; white-space: pre-wrap;", we do not have automatic line-breaks disabled on long debug text lines. Which looks ugly to Debuggers!
                        // This only works with "white-space: pre;", which is not Browsers Standard!
                        // Thus we have to NOT send this as "Content-type: text/plain;" and use "text/html" instead and enclose our output with the <xmp> tag.
                        // The HTML Example Element (<xmp>) renders text between the start and end tags without interpreting the HTML in between and is using a monospaced font, which is exactly what we want here.
                        // The recommendation is to use <code> or <pre> nowadays, but they both fail rendering properly for different debug text situations.
                        // The <plaintext> and <listing> elements, are similar to <xmp> but also set obsolete. Tag <plaintext> works, but its </endtag> is displayed; <listing> badly fails like <pre> on non-escaped content like regex patterns.
                        // Only <xmp> is left working absolutely fine! And w/o being displayed itself! Proofed with both main browser even in DEV versions. So we will keep it for a while.
                        // To MOZILLA/GOOGLE Devs: PLEASE DO NOT REMOVE IT! As seen here, it is a valid and very useful tag! (Examples in: http://www.the-pope.com/listin.html)
                        echo '<xmp>';
                        echo sprintf(PLUGIN_CHANGELOG_LOGGER_HAS_LOGS, $x) . "\n";
                        echo PLUGIN_CHANGELOG_LOGGER_NUKE_WARNING . "\n\n";
                        echo $separator;
                        print_r(array_keys($files));
                        echo $separator;
                        arsort($files);
                        $latest_file = key($files);
                        $content = file_get_contents($latest_file);
                        $file  = PLUGIN_CHANGELOG_LOGGER_BACKBLAH . "\n\n$separator";
                        $file .= empty($content) ? '<<< File: '. str_replace($serendipity['serendipityPath'], '', $latest_file) . ' - ' .NO_ENTRIES_TO_PRINT . ' >>>' : $content;
                        unset($content);
                        echo $file;
                        echo '</xmp>';
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
?>

    <section id="maintenance_logview" class="quick_list">
        <h3><?php echo PLUGIN_CHANGELOG_MAINTAIN; ?></h3>

        <a id="logview" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/changelog" title=""><span><?php echo PLUGIN_CHANGELOG_BUTTON; ?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#logview_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
        <span id="logview_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_CHANGELOG_TITLE_DESC, $serendipity['version']); ?></span>
<?php
                    if (isset($serendipity['logger']) && is_object($serendipity['logger'])) {
                        $files = glob($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/logs/*.txt');
                        // cleanup empty files automatically
                        foreach($files as $filename) {
                            if (filesize($filename) < 1) {
                                @unlink($filename);
                            }
                        }
                        sleep(1);
                        // do it again, Sam :)
                        $files = glob($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/logs/*.txt');
                        $files = array_combine($files, array_map("filemtime", $files));
                        array_pop($files);
                        #if (!empty($files)) print_r(array_keys($files));
                        $delOld = !empty($files) ? '<a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=deletelogs" title="' . PLUGIN_CHANGELOG_DELETEOLDLOGS .'"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden">' . PLUGIN_CHANGELOG_DELETEOLDLOGS .'</span></a>' : '';
                        if (!empty($files) && $serendipity['GET']['adminModule'] == 'maintenance' && $serendipity['GET']['adminAction'] == 'deletelogs' && serendipity_checkPermission('adminImagesDelete')) {
                            @array_map('unlink', array_keys($files));
                        }
?>

        <div class="serendipity_logger">
        <?php echo $delOld; ?>
        <a id="logger" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/logs" title=""><span><?php echo PLUGIN_CHANGELOG_LOGGER_BUTTON; ?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#logger_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
        <span id="logger_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_CHANGELOG_LOGGER_DESC, $serendipity['version']); ?></span>
        </div>

<?php
                    }
?>
    </section>
<?php
                    break;

                case 'css_backend':
                    $eventData .= '

#maintenance_logview .comment_status {
    float: none;
    margin: 0 0 .5em;
}
.no-flexbox #maintenance_logview.quick_list {
    margin: 0 0 1em 2%;
}
#maintenance_logview .serendipity_logger {
    margin-bottom: 1em;
}
#maintenance_logview .serendipity_logger .comment_status {
    margin: 0.5em 0 .5em;
}

';
                    break;

                default:
                    return false;

            }
            return true;
        } else {
            return false;
        }
    }

}

?>