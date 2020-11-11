<?php

// This line makes sure that plugins can only be called from the Serendipity Framework.
if (IN_serendipity !== true) {
    die ("Don't hack!");
}

// Load possible language files.
@serendipity_plugin_api::load_language(dirname(__FILE__));

// Extend the base class
class serendipity_event_plugup extends serendipity_plugin
{
    var $title = PLUGIN_EVENT_PLUGUP_TITLE;

    // Setup metadata
    function introspect(&$propbag) {
        $propbag->add('name',           PLUGIN_EVENT_PLUGUP_TITLE);
        $propbag->add('description',    PLUGIN_EVENT_PLUGUP_TITLE_DESC);
        $propbag->add('stackable',      false);
        $propbag->add('author',         'Ian Styx');
        $propbag->add('version',        '1.12');
        $propbag->add('requirements',   array(
            'serendipity' => '2.7.0',
            'smarty'      => '3.1.0',
            'php'         => '7.0.0'
        ));
        $propbag->add('groups', array('BACKEND_ADMIN','BACKEND_DASHBOARD'));
        $propbag->add('event_hooks',    array(
            'backend_dashboard'         => true,
            'backend_plugins_fetchlist' => true,
            'backend_plugins_update'    => true,
            'css_backend'               => true
        ));
        $propbag->add('configuration',  array(
            'show_updates',
            'spartacus_url'
        ));
        // Register (multiple) dependencies. KEY is the name of the depending plugin. VALUE is a mode of either 'remove' or 'keep'.
        // If the mode 'remove' is set, removing the plugin results in a removal of the depending plugin.
        // 'Keep' means to not touch the depending plugin.
        $this->dependencies = array('serendipity_event_spartacus' => 'keep');
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {

            case 'show_updates':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_PLUGUP_SHOW_UPDATE_NOTIFIER);
                $propbag->add('description', PLUGIN_EVENT_PLUGUP_SHOW_UPDATE_NOTIFIER_DESC);
                $propbag->add('default',     'true');
                break;

            case 'spartacus_url':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_PLUGUP_SPARTACUS_URL);
                $propbag->add('description', PLUGIN_EVENT_PLUGUP_SPARTACUS_URL_DESC);
                $propbag->add('default',     $serendipity['rewrite'] != 'none'
                                                ? $serendipity['baseURL'] . 'plugin/spartacus_remote'
                                                : $serendipity['baseURL'] . $serendipity['indexFile'] . '?/plugin/spartacus_remote');
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

    // set plugin check cookie cache
    function plug_cache($ts = 0)
    {
        global $serendipity;

        // get the data
        $defurl = $serendipity['baseURL'] . ($serendipity['rewrite'] == 'none' ? $serendipity['indexFile'] . '?/' : '') . 'plugin/spartacus_remote';
        $url    = $this->get_config('spartacus_url', $defurl);

        try {
            $inc = @file_get_contents($url);
        } catch (Throwable $t) {
            echo "Error: " . $t->getMessage();
            return 0;
        }
        if ($inc) {
            $event  = (int)substr_count($inc, 'UPGRADE: serendipity_event');
            $plugin = (int)substr_count($inc, 'UPGRADE: serendipity_plugin');
            #echo "$url\n$event\n$plugin\n";
            // store
            serendipity_setCookie('plugsEvent', $event, true, $ts);
            serendipity_setCookie('plugsPlugin', $plugin, true, $ts);
            serendipity_setCookie('plugsCheckTime', $ts, true, $ts);

            return (int)($event+$plugin);
        }
    }

    /**
     * Purges plugup cookies after plugin updates.
     * May be called by other plugins
     * @see serendipity_event_ckeditor upgrade as example
     */
    public static function purge_plugupCookies()
    {
        global $serendipity;
        // purge plugup plugin cookies
        if (isset($serendipity['COOKIE']['plugsEvent'])) @serendipity_deleteCookie('plugsEvent');
        if (isset($serendipity['COOKIE']['plugsPlugin'])) @serendipity_deleteCookie('plugsPlugin');
        if (isset($serendipity['COOKIE']['plugsCheckTime'])) @serendipity_deleteCookie('plugsCheckTime');
        #echo "PLUGUP: update done - plugup cookies purged\n"; // OK
    }

    // Listen on events
    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            switch($event) {

                case 'backend_dashboard':
                    if (!serendipity_checkPermission('adminPluginsMaintainOthers')) {
                        break;
                    }
                    if (serendipity_db_bool($this->get_config('show_updates', 'true')) && serendipity_checkPermission('siteConfiguration') || serendipity_checkPermission('blogConfiguration')) {
                        $time = time();
                        $ts   = $time + 60*60*6; // current time + 6 hours
                        $ct   = !empty($serendipity['COOKIE']['plugsCheckTime']) ? $serendipity['COOKIE']['plugsCheckTime'] : ($time-100);
                        $checked = ($time < $ct) ? false : true;
                        if (!isset($serendipity['COOKIE']['plugsEvent']) && !isset($serendipity['COOKIE']['plugsPlugin']) && $checked) {
                            $num    = $this->plug_cache($ts);
                            #echo 'set cookies ' . $ts; // OK
                        } else {
                            $event  = (int) ($serendipity['COOKIE']['plugsEvent'] ??  0);
                            $plugin = (int) ($serendipity['COOKIE']['plugsPlugin'] ??  0);
                            $num    = (int) ($event+$plugin);
                            #echo $event . ' + ' . $plugin . ' = ' .$num; // OK
                        }
                    // We need to set (float) other (heigher sized) dashboard widget boxes to the right, eg. the feedly box, since all other boxes use float:left
                    // and -if not- would make the height-size per row like an equal-height box, which is NOT want we want to have!
                    // We want the dashboard widgets to easily float into the 2-grid space available in height.
                    }
                    // we still have to unset these two plugin cookies in case of
                    // - updates all done in serendipity_styx.js in serendipity.updateNext() for the javascript update all solution and
                    // - try to unset them here in the 'backend_plugins_update' hook via the plugin API for Spartacus cases!
?>

    <section id="dashboard_plugup" class="clearfix dashboard_widget<?php if ($num == 0) { echo ' blend'; } ?>">
        <h3><?php echo PLUGIN_DASHBOARD_PLUGUP_BOX_TITLE; ?></h3>
        <div id="dash_plup" class="plugups">
            <div id="plup_header" class="plup_header">
<?php       if ($num == 0) {
                echo '<span class="msg_hint"><span class="icon-info-circled" aria-hidden="true"></span> ' . PLUGIN_DASHBOARD_PLUGUP_UP_TO_DATE . "</span>\n";
            } else {
?>
                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> <?php echo sprintf(PLUGIN_DASHBOARD_PLUGUP_UP_AVAILABLE, $num) ?></span>
                <?php /*if ($plugin > 0 || $event > 0) {*/ ?>
                <div id="plup_notice" class="clearfix">
                    <a id="upgrade_plugins" class="button_link state_submit" href="?serendipity[adminModule]=plugins&amp;serendipity[adminAction]=addnew&amp;serendipity[only_group]=UPGRADE"><?php echo PLUGIN_EVENT_SPARTACUS_CHECK ?></a>
                </div>
<?php           /*} else { echo "<span>(<em>".PLUGIN_DASHBOARD_PLUGUP_RELOAD."</em>)</span>\n"; }*/
            }
?>
            </div>
        </div>
    </section>

<?php
                    break;

                case 'backend_plugins_fetchlist':
                case 'backend_plugins_update':
                    $this->purge_plugupCookies();
                    break;

                case 'css_backend':
                    if (!serendipity_checkPermission('adminPluginsMaintainOthers')) {
                        break;
                    }
                    // append!
                    $eventData .= '

/* serendipity event_plugup start */

#dashboard_plugup .plugups { margin: .5em 0;}

/* serendipity event_plugup end */

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