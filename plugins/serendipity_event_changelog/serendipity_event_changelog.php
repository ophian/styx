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
        $propbag->add('description',    '');
        $propbag->add('stackable',      false);
        $propbag->add('author',        'Ian');
        $propbag->add('version',       '1.22');
        $propbag->add('requirements',  array(
            'serendipity' => '2.0.2',
            'php'         => '5.3.0'
        ));
        $propbag->add('event_hooks',    array(
            'css_backend'         => true,
            'backend_maintenance' => true,
            'external_plugin'     => true
        ));
        $propbag->add('groups',         array('BACKEND', 'BACKEND_FEATURES', 'MAINTAIN'));
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
                        return false;
                    }
                    $part = explode('/', $eventData);
                    if ($part[0] == 'changelog') {
                        if (!headers_sent()) {
                            header('HTTP/1.0 200');
                            header('Status: 200 OK');
                        }
                        header('Content-language: en');
                        header('Content-type: text/plain; charset=ISO-8859-1');
                        $file = "PLEASE USE BROWSER BACK BUTTON TO RETURN TO MAINTENANCE PAGE.\n\n" . file_get_contents($serendipity['serendipityPath'] . 'docs/NEWS');
                        echo $file;
                    }
                    if ($part[0] == 'logs' && is_object($serendipity['logger'])) {
                        if (!headers_sent()) {
                            header('HTTP/1.0 200');
                            header('Status: 200 OK');
                        }
                        header('Content-language: en');
                        header('Content-type: text/plain; charset=UTF-8');
                        $files = glob($serendipity['serendipityPath'] . 'templates_c/logs/*.txt');
                        $files = array_combine($files, array_map("filemtime", $files));
                        $x = count($files);
                        echo sprintf(PLUGIN_LOGGER_NUKE_WARNING, $x)."\n\n";
                        echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n";
                        print_r(array_keys($files));
                        echo "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -\n\n";
                        arsort($files);
                        $latest_file = key($files);
                        $file = "PLEASE USE BROWSER BACK BUTTON TO RETURN TO MAINTENANCE PAGE.\n\n" . file_get_contents($latest_file);
                        echo $file;
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
?>

    <section id="maintenance_logview" class="quick_list">
        <h3><?php echo PLUGIN_CHANGELOG_MAINTAIN; ?></h3>

        <a id="logview" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?') ?>plugin/changelog" title=""><span><?php echo PLUGIN_CHANGELOG_BUTTON; ?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#logview_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
        <span id="logview_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_CHANGELOG_TITLE_DESC, $serendipity['version']); ?></span>
<?php
                    if (is_object($serendipity['logger'])) {
                        $files = glob($serendipity['serendipityPath'] . 'templates_c/logs/*.txt');
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
        <a id="logger" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?') ?>plugin/logs" title=""><span><?php echo PLUGIN_LOGGER_BUTTON; ?></span></a>
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