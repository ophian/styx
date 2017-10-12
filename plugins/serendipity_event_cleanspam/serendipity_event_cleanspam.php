<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_cleanspam extends serendipity_event
{
    var $title = PLUGIN_CLEANSPAM_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',          PLUGIN_CLEANSPAM_NAME);
        $propbag->add('description',   PLUGIN_CLEANSPAM_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Ian');
        $propbag->add('version',       '1.1');
        $propbag->add('requirements',  array(
            'serendipity' => '2.3.0',
        ));
        $propbag->add('event_hooks', array(
            'css_backend'         => true,
            'backend_maintenance' => true,
            'external_plugin'     => true
        ));
        $propbag->add('groups', array('BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN'));

    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

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
                    $part = explode('/', $eventData);
                    if ($part[0] == 'cleanspam') {
                        $sbldone = $vdone = false;
                        $append = 'false';

                        if ($part[1] == 'log') {
                            $cleanspamlog = serendipity_db_query("SELECT * , from_unixtime( timestamp ) AS tdate FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' ORDER BY tdate DESC");
                            if (is_object($serendipity['logger'])) {
                                $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START serendipity_event_cleanspam SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
                                $serendipity['logger']->debug("LOG: " . print_r($cleanspamlog,1));
                                $append = 'logged';
                            }
                            unset($cleanspamlog);
                        }

                        // we can cleanup all field-"type" ('REJECTED' or 'MODERATE') which are probably all the spammer logs at once
                        if ($part[1] == 'all') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' OR type LIKE 'reject'");
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'moderate' AND body=''"); // To make this case insensive, use WHERE BINARY type LIKE, but we don't care since we search for empty bodies
                            $sbldone = true;
                        }

                        // or do it by field-"type" ('REJECTED' or 'MODERATE') and field-"reason" 'No API-created comments allowed', 'BEE Honeypot%', 'BEE HiddenCaptcha%', 'Caught by the Bayes-Plugin%', 'IP validation%', 'IP Validierung%', 'Kontrola IP adresy%'
                        if ($part[1] == 'multi') {
                            $multir = $serendipity['POST']['cleanspam']['multi_reasons'];
                            if (is_array($multir) & !empty($multir)) {
                                foreach($multir AS $p) {
                                    if ($p == 'api') {
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason='".PLUGIN_EVENT_SPAMBLOCK_REASON_API."'"); // (since already translated variously.., we have to use the constant
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason='".PLUGIN_EVENT_SPAMBLOCK_REASON_API."'"); // (since already translated variously.., we have to use the constant
                                        $sbldone = true;
                                    }
                                    if ($p == 'hpot') {
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'BEE Honeypot%'"); // (approximately big data)
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'BEE Honeypot%'"); // (approximately big data)
                                        $sbldone = true;
                                    }
                                    if ($p == 'hcap') {
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'BEE HiddenCaptcha%'"); // (approximately small data)
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'BEE HiddenCaptcha%'"); // (approximately small data)
                                        $sbldone = true;
                                    }
                                    if ($p == 'ipv') {
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'IP validation%' OR reason LIKE 'IP Validierung%' OR reason LIKE 'Kontrola IP adresy%'"); // (en, de, cs, cz, sk) (approximately mid-big data)
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'IP validation%' OR reason LIKE 'IP Validierung%' OR reason LIKE 'Kontrola IP adresy%'"); // (en, de, cs, cz, sk) (approximately mid-big data)
                                        $sbldone = true;
                                    }
                                    if ($p == 'cbay') {
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'Caught by the Bayes-Plugin%'"); // (approximately mid-big data)
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'Caught by the Bayes-Plugin%'"); // (approximately mid-big data)
                                        $sbldone = true;
                                    }
                                }
                            }
                        }

                        if ($part[1] == 'visits') {
                            $multiyears = $serendipity['POST']['cleanspam']['multi_years'];
                            if (is_array($multiyears) & !empty($multiyears)) {
                                set_time_limit(0);
                                // since using the (%) wildcard, check first if table exists
                                if (stristr($serendipity['dbType'], 'postgres')) $ct = "SELECT counter_id FROM {$serendipity['dbPrefix']}visitors LIMIT 1";
                                if (stristr($serendipity['dbType'], 'sqlite')) $ct = "SELECT name FROM sqlite_master WHERE type='table' AND name='{$serendipity['dbPrefix']}visitors'";
                                if ($serendipity['dbType'] == 'mysqli') $ct = "SHOW TABLES LIKE '{$serendipity['dbPrefix']}visitors'";
                                if ($x = serendipity_db_query($ct, true, null, false, false, false, true) !== false ) {
                                    foreach($multiyears AS $my) {
                                        $s = "$my-%";
                                        @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}visitors WHERE day LIKE '$s'", true, null, false, false, false, true);
                                    }
                                    if ($x) $vdone = true;
                                }
                            }
                        }

                        if ($sbldone || $vdone) {
                            $t = $sbldone ? 'spamblocklog' : 'visitors';
                            switch($serendipity['dbType']) {
                                case 'sqlite':
                                case 'sqlite3':
                                case 'sqlite3oo':
                                case 'pdo-sqlite':
                                    $sql = "VACUUM";
                                    break;
                                case 'pdo-postgres':
                                case 'postgres':
                                    $sql = "VACUUM";
                                    break;
                                case 'mysql':
                                case 'mysqli':
                                    $sql = "OPTIMIZE TABLE {$serendipity['dbPrefix']}$t";
                                    break;
                            }
                            if (isset($sql)) {
                                @serendipity_db_query($sql);
                                $append = 'true';
                            }
                        }
                        // exit
                        header('Location: ' . $serendipity['baseURL'] . 'serendipity_admin.php' . ($serendipity['rewrite'] == 'none' ? '?/' : '?') . 'serendipity[adminModule]=maintenance&serendipity[cleanspamsg]='.$append);
                    }
                    break;

                case 'backend_maintenance':
                    if (!serendipity_checkPermission('adminUsers')) {
                        return false;
                    }
                    $allnum = @serendipity_db_query("SELECT count(1) FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND type LIKE 'reject' AND type LIKE 'MODERATE'", true);
                    $allnum = is_numeric($allnum[0]) ? $allnum[0] : 0;

?>

    <section id="maintenance_cleanspam" class="quick_list">
        <h3><?php echo PLUGIN_CLEANSPAM_MAINTAIN; ?></h3>
        <h4>
            <?php echo PLUGIN_CLEANSPAM_INFO; ?>
            <button class="toggle_info button_link cleanspam_info" type="button" data-href="#cleanspam_info_desc"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
            <button class="toggle_info button_link cleanspam_info cleanspam_toggle" type="button" data-href="#cleanspam_action_access"><span class="icon-down-dir" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo TOGGLE_OPTION; ?></span></button>
        </h4>
        <span id="cleanspam_info_desc" class="comment_status additional_info"><?php echo PLUGIN_CLEANSPAM_INFO_DESC; ?></span>
<?php
switch ($serendipity['GET']['cleanspamsg']) {
    case 'true':
        echo '<p class="msg_success" style="margin:0"><span class="icon-ok-circled" aria-hidden="true"></span> ' . PLUGIN_CLEANSPAM_MSG_DONE . "<p>\n";
        break;
    case 'false':
        echo '<p class="msg_error" style="margin:0"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . "<p>\n";
        break;
    case 'logged':
        echo '<p class="msg_notice" style="margin:0"><span class="icon-ok-circled" aria-hidden="true"></span> See Debug Logger output!' . "<p>\n";
        break;
    default:
        //void
        break;
}
?>

        <div id="cleanspam_action_access" class="additional_info">
            <a id="cpmall" class="button_link state_submit" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/all" title=""><span><?php echo PLUGIN_CLEANSPAM_ALL_BUTTON; ?></span></a>
            <button class="toggle_info button_link" style="margin: 1em 0" type="button" data-href="#cpmall_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
            <span id="cpmall_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_CLEANSPAM_ALL_DESC, $allnum); ?></span>
            <div class="serendipity_cpmdiff" style="margin-top: .5em;">
                <h4>
                    <?php echo PLUGIN_CLEANSPAM_SELECT; ?>
                    <button class="toggle_info button_link" type="button" data-href="#cleanspam_access_multi_reasons"><span class="icon-down-dir" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo TOGGLE_OPTION; ?></span></button>
                </h4>
                <form id="maintenance_cleanspam_multi" enctype="multipart/form-data" action="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/multi" method="post">
                    <select id="cleanspam_access_multi_reasons" class="additional_info" name="serendipity[cleanspam][multi_reasons][]" multiple="multiple">
                        <option value="">- - -</option>
                        <option value="api">LIKE "<?php echo PLUGIN_EVENT_SPAMBLOCK_REASON_API; ?>"</option>
                        <option value="hpot">LIKE "BEE Honeypot%"</option>
                        <option value="hcap">LIKE "BEE HiddenCaptcha%"</option>
                        <option value="ipv">LIKE "Caught by the Bayes-Plugin%"</option>
                        <option value="cbay">LIKE "IP validation%" in (de, en, cs, cz, sk) languages</option>
                    </select>
                    <div class="form_buttons form_cpm">
                        <input class="state_submit" name="spamclean_multi" type="submit" value="<?php echo PLUGIN_CLEANSPAM_MULTI_BUTTON; ?>">
                        <button class="toggle_info button_link" type="button" data-href="#cpmdiff_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
                    </div>
                    <span id="cpmdiff_info" class="comment_status additional_info"><?php echo PLUGIN_CLEANSPAM_MULTI_DESC; ?></span>
                </form>
            </div>
            <hr>
<?php
        $years = array('2003','2004','2005','2006','2007','2007','2008','2009','2010','2011','2012','2013','2014','2015','2016');
?>
            <div class="serendipity_cpmbyy">
                <?php echo PLUGIN_CLEANSPAM_VISITORS; ?>
                <button class="toggle_info button_link" type="button" data-href="#cleanspam_access_multi_years"><span class="icon-down-dir" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo TOGGLE_OPTION; ?></span></button>
                <form id="maintenance_cleanvisits_multi" enctype="multipart/form-data" action="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/visits" method="post">
                    <select id="cleanspam_access_multi_years" class="additional_info" name="serendipity[cleanspam][multi_years][]" multiple="multiple">
                        <option value="">Multi Select Years</option>
<?php
                    foreach ($years AS $year) {
                        echo "<option value=\"$year\">$year</option>\n";
                    }
?>
                    </select>
                    <div class="form_buttons form_cpm">
                        <input class="state_submit" name="visitorsclean_multi" type="submit" value="<?php echo PLUGIN_CLEANSPAM_YEARS_BUTTON; ?>">
                        <button class="toggle_info button_link" type="button" data-href="#cpmvisits_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
                    </div>
                    <span id="cpmvisits_info" class="comment_status additional_info"><?php echo PLUGIN_CLEANSPAM_VISITORS_DESC; ?></span>
                </form>
            </div>
        </div>

    </section>
<?php
                    break;

                case 'css_backend':
                    $eventData .= '

#maintenance_cleanspam .comment_status {
    float: none;
    margin: 0 0 .5em;
}
.no-flexbox #maintenance_cleanspam.quick_list {
    margin: 0 0 1em 2%;
}
.form_cpm, #cleanspam_action_access hr {
    margin: .5em auto;
}
#maintenance_cleanspam h4, #cleanspam_action_access h4 {
    margin: 0 auto .25em;
}
#maintenance_cleanspam h4 {
    font-weight: normal;
}
.cleanspam_info {
    margin: .5em 0;
}
.toggle_info.cleanspam_info:visited, .toggle_info.cleanspam_info:hover, .toggle_info.cleanspam_info:focus, .toggle_info.cleanspam_info:active {
    margin: .5em 0;
}
.cleanspam_toggle {
    float: right;
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