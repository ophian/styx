<?php

// This line makes sure that plugins can only be called from the Serendipity Framework.
if (IN_serendipity !== true) {
    die ("Don't hack!");
}

// Load possible language files.
@serendipity_plugin_api::load_language(dirname(__FILE__));

// Extend the base class
class serendipity_event_modemaintain extends serendipity_event
{
    var $title = PLUGIN_MODEMAINTAIN_TITLE;
    /**
     * Access property maintenanceText
     * Set Frontend maintenance mode text (set to pre-defined constant, as not allowed to use dynamic Serendipity array var)
     * @var string
     */
    protected $maintenanceText = PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT;

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
        $propbag->add('author',        'Ian Styx');
        $propbag->add('version',       '1.38');
        $propbag->add('requirements',  array(
            'serendipity' => '4.1',
            'php'         => '7.4.0'
        ));
        $propbag->add('event_hooks',    array(
            'css_backend'         => true,
            'frontend_configure'  => true,
            'backend_maintenance' => true,
            'external_plugin'     => true
        ));
        $propbag->add('configuration',  array('momatext', 'use_logo'));
        $propbag->add('groups',         array('BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN', 'MAINTENANCE'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'momatext':
                $propbag->add('type',        'text');
                $propbag->add('rows',        3);
                $propbag->add('name',        PLUGIN_MODEMAINTAIN_MAINTAIN_NOTE);
                $propbag->add('description', '');
                $propbag->add('default',     str_replace(array('&#187;','&#171;'), '"', PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT));
                break;

            case 'use_logo':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_MODEMAINTAIN_MAINTAIN_USELOGO);
                $propbag->add('description', '');
                $propbag->add('default',     'true');
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
    private function service_mode($logo='')
    {
        $retry = 300; // seconds
        serendipity_header( serendipity_getServerProtocol() . ' 503 Service Temporarily Unavailable', true, 503 );
        serendipity_header( 'Status: 503 Service Temporarily Unavailable', true, 503 );
        serendipity_header( 'X-S9y-Maintenance: true' ); // Used for debugging detection
        serendipity_header( 'Content-Type: text/html; charset=utf-8' );
        serendipity_header( "Retry-After: $retry" );
        serendipity_die(nl2br("$logo".$this->maintenanceText), null);
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
        if (!$set && ((!isset($serendipity['maintain']['autologin']) || $serendipity['maintain']['autologin'] === true) || isset($serendipity['COOKIE']['maintain_autologin'])) ) {
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
                    // check the authentication and UserLevel for security
                    if (isset($serendipity['COOKIE']['maintain_autologin']) && serendipity_userLoggedIn() && $_SESSION['serendipityUserlevel'] == '255') {
                        $superuser = true;
                    } else {
                        $superuser = false;
                    }
                    $this->maintenanceText = (string) $this->get_config('momatext', PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT);

                    // This will stop Serendipity immediately throwing a '503 Service Temporarily Unavailable' maintenance message,
                    // if var is set to true and user is not authenticated and logged into admin users.
                    // This $serendipity['maintenance'] var is stored in serendipity_config_local.inc file!
                    if (!$superuser && !serendipity_checkPermission('adminUsers') && isset($serendipity['maintenance']) && serendipity_db_bool($serendipity['maintenance']) ) {
                        $logo = serendipity_db_bool($this->get_config('use_logo', 'true')) ? '<a href="https://ophian.github.io/" target="_blank"><img class="logo" src="'.$serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 'styx_logo_150.png" title="&copy; Serendipity Styx Edition" alt="Serendipity Styx" /></a>' : '';
                        $this->service_mode($logo);
                    }
                    break;

                case 'external_plugin':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
                    $db['jspost'] = explode('/', $eventData);
                    $refererurl   = $serendipity['baseURL'] . 'serendipity_admin.php?serendipity[adminModule]=maintenance';

                    // [0]=modemaintenance; [1]=setmoma [boolean]
                    if ($db['jspost'][0] == 'maintenance') {
                        $this->s9y_maintenance_mode(true);
                        header('Status: 302 Found');
                        header('Location: '. $refererurl . '&momashut');
                        #header('Location: '. $refererurl);
                        serendipity_die(sprintf(PLUGIN_MODEMAINTAIN_RETURN, $db['jspost'][0], $refererurl));
                    }
                    if ($db['jspost'][0] == 'public') {
                        $this->s9y_maintenance_mode(false);
                        header('Status: 302 Found');
                        header('Location: '. $refererurl . '&momaopen');
                        #header('Location: '. $refererurl);
                        serendipity_die(sprintf(PLUGIN_MODEMAINTAIN_RETURN, $db['jspost'][0], $refererurl));
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }

                    // Try to workaround the reloading "button change issue" which is based to writing the moma local variable and refreshing all variables afterwards correctly to check them up and again
                    if (isset($_GET['momashut']) || isset($_GET['momaopen'])) {
                        sleep(2); // some servers just need 1, some 2 ... some do not care and some do even need more..
                        header('Location: '. $serendipity['baseURL'] . 'serendipity_admin.php?serendipity[adminModule]=maintenance');
                    }

                    // do not allow session based authentication
                    if ($_SESSION['serendipityAuthedUser'] == true && !isset($serendipity['COOKIE']['author_information'])) {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <button class="toggle_info button_link stat" type="button" data-href="#moma_logininfo"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <div id="moma_logininfo" class="msg_notice msg-btm additional_info"><?=PLUGIN_MODEMAINTAIN_TITLE_AUTOLOGIN?></div>
    </section>

<?php
                        break;
                    }
                    $catch24 = (OPENSSL_VERSION_NUMBER < 269488207) ? true : false;
                    if ($catch24) {
                        if (isset($serendipity['COOKIE']['author_information'])) {
                            $t = serendipity_db_query("SELECT name FROM {$serendipity['dbPrefix']}options
                                                        WHERE okey = 'l_" . serendipity_db_escape_string($serendipity['COOKIE']['author_information']) . "'");
                            if (isset($t[0]['name'])) {
                                $date = new DateTime();
                                $timeZone = $date->getTimezone();
                                $tz = $timeZone->getName();
                                $sess_start = DateTime::createFromFormat('U', $t[0]['name'], new DateTimeZone($tz));
                                $againstnow = new DateTime("now", new DateTimeZone($tz)); // The time zone is always "+00: 00" (UTC)! If a time zone is set as an optional parameter, this is ignored.
                                $interval = $sess_start->diff($againstnow);
                            }
                        }
                        // ToDo: Shall it have a secured offset of -N minutes ?
                        if (isset($interval)) {
                            $dth = $interval->format('%H'); // Normally a 24-hour format of an hour with leading zeros
                            $dtm = $interval->format('%i'); // Normally Minutes with leading zeros
                            #echo "Session started $dth Hours $dtm Minutes ago";echo " &uArr; (increasing)<br>\n";
                            $h = ($dtm == 00) ? 24-$dth : 23-$dth;
                            if (strlen($h) < 2) $h = "0$h"; // But well, I actually do still need this though!
                            $m = $dtm < 1 ? '00' : 60-$dtm;
                            if (strlen($m) < 2) $m = "0$m"; // But well, I actually do still need this though!
                            $timeleft = "$h:$m"; // remain time left
                            if ($timeleft == '24:00') $timeleft = '00:00'; // The old Session has ended, wait a minute for a new 24h Session start
                            #echo "Remaining maintenance working time left = $timeleft (h:m)";echo " &dArr; (DESC)<br>\n";
                        } else {
                            $timeleft = 'NULL';
                        }
                    }
                    $catch24_msg = ($catch24 && !empty($timeleft))
                                    ? '<span class="msg_notice" style="margin-top:0"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(PLUGIN_MODEMAINTAIN_OPENSSL_TIME_RESTRICTION, $timeleft) . "</span>\n"
                                    : '';
                    if ((!isset($serendipity['maintenance']) || serendipity_db_bool($serendipity['maintenance']) !== true) && $this->blockMaintenance) {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <a id="moma" class="button_link state_submit" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/maintenance/" title="<?=PLUGIN_MODEMAINTAIN_INFOALERT?>"><span><?=PLUGIN_MODEMAINTAIN_BUTTON?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#moma_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <div id="moma_info" class="comment_status additional_info"><?=PLUGIN_MODEMAINTAIN_TITLE_DESC?> <br><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_MODE_DESC?>
        <p><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_EXWARNING_DESC?></p>
        <div><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_EMERGENCY_DESC?></div></div>
        <?=$catch24_msg?>
    </section>

<?php
                    } else {
?>

    <section id="maintenance_moma" class="quick_list">
        <h3><?=PLUGIN_MODEMAINTAIN_MAINTAIN?></h3>

        <a id="moma" class="button_link state_cancel" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/public/" title="<?=PLUGIN_MODEMAINTAIN_INFOALERT?>"><span><?=PLUGIN_MODEMAINTAIN_FREEBUTTON?></span></a>
        <button class="toggle_info button_link" type="button" data-href="#moma_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <div id="moma_info" class="comment_status additional_info"><?=PLUGIN_MODEMAINTAIN_TITLE_DESC?> <br><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_MODE_DESC?>
        <p><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_EXWARNING_DESC?></p>
        <div><span class="icon-info-circled" aria-hidden="true"></span> <?=PLUGIN_MODEMAINTAIN_DASHBOARD_EMERGENCY_DESC?></div></div>
        <?=$catch24_msg?>
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
.fivezerothree {
    color: #ff4136;
    font-weight: bold;
    font-family: monospace;
    font-size: 1.5em;
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