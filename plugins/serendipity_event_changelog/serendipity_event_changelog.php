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
        $propbag->add('version',       '1.00');
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
                    $part = explode('/', $eventData);
                    if ($part[0] == 'changelog') {
                        if (!headers_sent()) {
                            header('HTTP/1.0 200');
                            header('Status: 200 OK');
                        }
                        header('Content-language: en');
                        header('Content-type: text/plain; charset=ISO-8859-1');
                        $file = "PLEASE USE BROWSER BACK BUTTON TO RETURN TO MAINTENANCE PAGE.\n\n" . file_get_contents($serendipity['serendipityPath'].'docs/NEWS');
                        echo $file;
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
?>

    <section id="maintenance_logview" class="equal_heights quick_list">
        <h3><?php echo PLUGIN_CHANGELOG_MAINTAIN; ?></h3>

        <a id="logview" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?') ?>plugin/changelog" title=""><span><?php echo PLUGIN_CHANGELOG_BUTTON; ?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#logview_info"><span class="icon-info-circled"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
        <span id="logview_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_CHANGELOG_TITLE_DESC, $serendipity['version']); ?></span>
    </section>

<?php
                    break;

                case 'css_backend':
                    $eventData .= '

#maintenance_logview .comment_status {
    float: none;
    margin: 0 0 .5em;
}
#maintenance_logview.quick_list {
    margin: 0 0 1em 2%;
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