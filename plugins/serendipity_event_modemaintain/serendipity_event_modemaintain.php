<?php

// This line makes sure that plugins can only be called from the Serendipity Framework.
if (IN_serendipity !== true) {
    die ("Don't hack!");
}

// Load possible language files.
@serendipity_plugin_api::load_language(dirname(__FILE__));

 /**
  * Class member instance attribute values
  * Members must be initialized with a constant expression (like a string constant, numeric literal, etc), not a dynamic expression!
  */
@define(MODEMAINTAIN_PRESET_MOMATXT, sprintf(PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT, $serendipity['blogTitle']));

// Extend the base class
class serendipity_event_modemaintain extends serendipity_plugin
{
    var $title = PLUGIN_MODEMAINTAIN_TITLE;
    /**
     * Access property maintenanceText
     * Set Frontend maintenance mode text (set to pre-defined constant, as not allowed to use dynamic Serendipity array var)
     * @var string
     */
    protected $maintenanceText = MODEMAINTAIN_PRESET_MOMATXT;

    /**
     * Access property blockMaintenance
     * Enable Maintenance-UI maintenance mode button in update element
     * @var boolean
     */
    protected $blockMaintenance = true;

    // Setup metadata
    function introspect(&$propbag)
    {
        $propbag->add('name',           PLUGIN_MODEMAINTAIN_TITLE);
        $propbag->add('description',    PLUGIN_MODEMAINTAIN_TITLE_DESC);
        $propbag->add('stackable',      false);
        $propbag->add('author',        'Ian');
        $propbag->add('version',       '1.11');
        $propbag->add('requirements',  array(
            'serendipity' => '2.0.99',
            'php'         => '5.3.0'
        ));
        $propbag->add('event_hooks',    array(
            'css_backend'         => true,
            'frontend_configure'  => true,
            'backend_maintenance' => true,
            'external_plugin'     => true
        ));
        $propbag->add('configuration',  array('fiveothree'));
        $propbag->add('groups',         array('BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'fiveothree':
                $propbag->add('type',        'text');
                $propbag->add('rows',        3);
                $propbag->add('name',        PLUGIN_MODEMAINTAIN_MAINTAIN_NOTE);
                $propbag->add('description', '');
                $propbag->add('default',     str_replace(array('&#187;','&#171;'), '"', MODEMAINTAIN_PRESET_MOMATXT));
                break;

            default:
                return false;
        }
        return true;
    }

    // Setup title
    function generate_content(&$title)
    {
        $title = $this->title;
    }

    /**
     * Set Maintenance Mode header 505 Temporarily Unavailable
     *
     * @access    private
     * @return
     */
    private function service_mode()
    {
        $retry = 300; // seconds
        $protocol = $_SERVER["SERVER_PROTOCOL"];
        if ( 'HTTP/1.1' != $protocol && 'HTTP/1.0' != $protocol ) {
            $protocol = 'HTTP/1.0';
        }
        serendipity_header( "$protocol 503 Service Temporarily Unavailable", true, 503 );
        serendipity_header( 'Status: 503 Service Temporarily Unavailable', true, 503 );
        serendipity_header( 'X-S9y-Maintenance: true' ); // Used for debugging detection
        serendipity_header( 'Content-Type: text/html; charset=utf-8' );
        serendipity_header( "Retry-After: $retry" );
        serendipity_die(nl2br("<h2>503 - SERENDIPITY STYX SERVICE MODE</h2>\n".$this->maintenanceText));
        exit; // actually no need, but for security reasons left alive
    }

    /**
     * Set automatic strong cookie autologin if in maintenance mode
     *
     * @access    private
     * @param     boolean    set/unset
     */
    private static function service_autologin($set=null)
    {
        global $serendipity;

        if ($set && isset($serendipity['COOKIE']['author_information'])) {
            // set a global var to remember automatic autologin
            $serendipity['maintain']['autologin'] = true;
            serendipity_setCookie('maintain_autologin', 'true');
        }
        if (!$set && ($serendipity['maintain']['autologin'] || isset($serendipity['COOKIE']['maintain_autologin'])) ) {
            // automatic autologin logout
            $serendipity['maintain']['autologin'] = false;
            serendipity_deleteCookie('maintain_autologin');
        }
    }

    /**
     * Set upgraders maintenance mode
     *
     * @access    private
     * @param     boolean    set/unset
     */
    private function s9y_maintenance_mode($mode=false)
    {
        global $serendipity;

        if (!serendipity_checkPermission('adminUsers')) {
            return;
        }
        // return user to use the autologin cookie to stay logged-in while in service maintenance mode
        $this->service_autologin($mode);

        $privateVariables = array();
        $privateVariables['maintenance'] = $mode ? 'true' : 'false'; // we cannot write real booleans here, as the function does not provide it
        $r = serendipity_updateLocalConfig(
                $serendipity['dbName'],
                $serendipity['dbPrefix'],
                $serendipity['dbHost'],
                $serendipity['dbUser'],
                $serendipity['dbPass'],
                $serendipity['dbType'],
                $serendipity['dbPersistent'],
                $privateVariables
            );
        #echo serendipity_db_bool($mode) ? 'true' : 'false'; // ajax post answer to validate as string - see service_autologin echos
        return $r;
    }

    // Listen on events
    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            switch($event) {
                case 'frontend_configure':
                    // If the browser was closed without an unset maintenance mode,
                    // check maintenance autologin cookie to be able to return to login page at least w/o the 503 unavailable mode page
                    if (isset($serendipity['COOKIE']['maintain_autologin'])) {
                        $superuser = true;
                    } else {
                        $superuser = false;
                    }
                    $this->maintenanceText = (string) $this->get_config('fiveothree', MODEMAINTAIN_PRESET_MOMATXT);

                    // This will stop Serendipity immediately throwing a '503 Service Temporarily Unavailable' maintenance message,
                    // if var is set to true and user is not authenticated and logged into admin users.
                    // This $serendipity['maintenance'] var is stored in serendipity_config_local.inc file!
                    if (!$superuser && !serendipity_checkPermission('adminUsers') && serendipity_db_bool($serendipity['maintenance']) ) {
                        $this->service_mode();
                    }
                    break;

                case 'external_plugin':
                    $db['jspost'] = explode('/', $eventData);
                    $refererurl = $serendipity['baseURL'] . "serendipity_admin.php?serendipity[adminModule]=maintenance";

                    // [0]=modemaintence; [1]=setmoma [boolean]
                    if ($db['jspost'][0] == 'maintenance') {
                        $this->s9y_maintenance_mode(true);
                        header('Status: 302 Found');
                        header('Location: '. $refererurl);
                        serendipity_die(sprintf(PLUGIN_MODEMAINTAIN_RETURN, $db['jspost'][0], $refererurl));
                    }
                    if ($db['jspost'][0] == 'public') {
                        $this->s9y_maintenance_mode(false);
                        header('Status: 302 Found');
                        header('Location: '. $refererurl);
                        serendipity_die(sprintf(PLUGIN_MODEMAINTAIN_RETURN, $db['jspost'][0], $refererurl));
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
                    // do not allow session based authentication
                    if ($_SESSION['serendipityAuthedUser'] == true && !isset($serendipity['COOKIE']['author_information'])) {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_TITLE_AUTOLOGIN?></span>
    </section>

<?php
                        break;
                    }
                    $ew = "Experimental Warning: Currently there is a strange issue. You need to access the setmode and the undo button twice, with a ~1 second delay inbetween, OR click somewhere else and return, to see the button change (which has to).";
                    if (serendipity_db_bool($serendipity['maintenance']) !== true && $this->blockMaintenance) {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <a id="moma" class="button_link state_submit" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/maintenance/" title=""><span><?=PLUGIN_MODEMAINTAIN_BUTTON?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#moma_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <span id="moma_info" class="comment_status additional_info"><?=PLUGIN_MODEMAINTAIN_TITLE_DESC?> <?=PLUGIN_DASHBOARD_MAINTENANCE_MODE_DESC?></span>
        <span class="msg_hint" style="margin: 0 0 .5em 0"><span class="icon-info-circled" aria-hidden="true"></span> <?=$ew?></span>
    </section>

<?php
                    } else {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <a id="moma" class="button_link state_cancel" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/public/" title=""><span><?=PLUGIN_MODEMAINTAIN_FREEBUTTON?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#moma_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <span id="moma_info" class="comment_status additional_info"><?=PLUGIN_MODEMAINTAIN_TITLE_DESC?> <?=PLUGIN_DASHBOARD_MAINTENANCE_MODE_DESC?></span>
        <span class="msg_hint" style="margin: 0 0 .5em 0"><span class="icon-info-circled" aria-hidden="true"></span> <?=$ew?></span>
    </section>

<?php
                    }
                    break;

                case 'css_backend':
                    $eventData .= '

#maintenance_moma .comment_status {
    float: none;
    margin: 0 0 .5em;
}
.no-flexbox #maintenance_test.quick_list {
    margin: 0 0 1em 2%;
}
#maintenance_test .comment_status {
    margin: 0 0 .5em;
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