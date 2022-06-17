<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_mlorphans extends serendipity_event
{
    var $title = PLUGIN_EVENT_MLORPHANS_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',          PLUGIN_EVENT_MLORPHANS_NAME);
        $propbag->add('description',   PLUGIN_EVENT_MLORPHANS_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Ian Styx');
        $propbag->add('version',       '1.04');
        $propbag->add('requirements',  array(
            'serendipity' => '3.0',
            'smarty'      => '3.1.0',
            'php'         => '7.1.0'
        ));
        $propbag->add('event_hooks',    array(
            'css_backend'         => true,
            'backend_maintenance' => true
        ));
        $propbag->add('groups',         array('BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN', 'MAINTENANCE'));
    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

    function example()
    {
        return '<h3>' . PLUGIN_EVENT_MLORPHANS_NAME . '</h3>' .
        '<span class="msg_notice">' . PLUGIN_EVENT_MLORPHANS_DESC ."</span>\n";
    }

    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            switch($event) {

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('siteConfiguration')) {
                        return false;
                    }
                    $countdImages = @serendipity_db_query("SELECT count(id) FROM {$serendipity['dbPrefix']}images WHERE mime LIKE 'image/%' AND hotlink IS NULL", true, 'num');
                    $orphanTask   = (serendipity_checkPermission('siteConfiguration') && is_array($countdImages) && $countdImages[0] >= 100) ? true : false;

                    if ($orphanTask) {
?>

    <section id="maintenance_orphanmanager" class="quick_list">
        <h3><?=MEDIA_LIBRARY?>: <?=PLUGIN_EVENT_MLORPHANS_NAME?></h3>
        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=imageorphans" title="<?=strtolower(PLUGIN_EVENT_MLORPHANS_SUBMIT)?>"><span><?=PLUGIN_EVENT_MLORPHANS_SUBMIT?></span></a>
    </section>

<?php
                    }
                    break;

                case 'css_backend':
                    $eventData .= '

/* plugin serendipity_event_mlorphans start */

    #maintenance_orphaned_images { border: 1px solid #ddd; padding: 0 .5em; }
    #maintenance_orphaned_images .checkbyid { background: #eee; box-sizing: border-box; border: 1px solid #aaa; padding: .5em; }
    #maintenance_orphaned_images .checkbyid label { font-weight: 600; color: #666; }
    #maintenance_orphaned_images .compact { display: inline; }
    #maintenance_orphaned_images legend {
        background: #ddd;
        background-image: -webkit-linear-gradient(#fff, #ddd);
        background-image: linear-gradient(#fff, #ddd);
        border-bottom: 1px solid #aaa;
        color: #666;
        font-size: 1em;
        margin: auto -.5em;
        padding: .5em 1em; }
    .msg_notice.orphan h3,
    #maintenance_orphaned_images .msg_notice.no-margin { margin-top: .5em; margin-bottom: .5em; }
    #maintenance_orphaned_images .msg_notice.no-margin { font-style: oblique; }
    #maintenance_orphaned_images pre {
        width: auto;
        min-width: 24em;
        height: 24em;
        display: inline-grid;
        overflow: auto;
        background: #fefefe;
        border: 1px solid #ccc;
        margin: .5em auto;
        padding: .5em; }

/* plugin serendipity_event_mlorphans end */
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
