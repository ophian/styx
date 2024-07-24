<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

$data   = array(); // init Smarty assignment data array
$output = array(); // init backend_frontpage_display hook array
$output['probe'] = '';

// Alert non accessible SQLite database on login
if (isset($serendipity['POST']['admin']['user']) && stristr($serendipity['dbType'], 'sqlite') && (defined('S9Y_DB_INCLUDED') && S9Y_DB_INCLUDED === true)) {
    $errs  = array();
    $probe = array('dbName' => $serendipity['dbName']);
    serendipity_db_probe($probe, $errs);
    $errs = (count($errs) > 0) ? $errs : null;
    if (is_array($errs)) {
        $output['probe'] = '<span class="msg_error"><span class="icon-info-circled"></span> The SQLite Database is not accessible. Please check availability or missing write permissions!</span>'."\n";
    }
    unset($probe);
}

if (isset($serendipity['POST']['adminAction'])) {
    switch($serendipity['POST']['adminAction']) {
        case 'publish':
            if (!serendipity_checkFormToken()) {
                break;
            }
            $success = serendipity_updertEntry(array(
                'id' => htmlspecialchars($serendipity['POST']['id']),
                'timestamp' => time(),
                'isdraft' => 0
            ));
            if (is_numeric($success)) {
                $data['published'] = $success;
            } else {
                $data['error_publish'] = $success;
            }
            break;

        case 'updateCheckDisable':
            if (!serendipity_checkFormToken() || !serendipity_checkPermission('blogConfiguration')) {
                break;
            }
            serendipity_set_config_var('updateCheck', false);
            break;
    }
}

$user = serendipity_fetchAuthor($serendipity['authorid']);
// chrome-compatible, from Oliver Gassner, adapted from TextPattern. Hi guys, keep it up. :-)
$bookmarklet = "javascript:var%20d=document,w=window,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),f='" . $serendipity['baseURL'] . "',l=d.location,e=encodeURIComponent,p='serendipity_admin.php?serendipity[adminModule]=entries&serendipity[adminAction]=new&serendipity[title]='+e(d.title)+'&serendipity[body]='+e(s)+'&serendipity[url]='+location.href,u=f+p;a=function(){%20%20if(!w.open(u,'t','toolbar=0,resizable=1,scrollbars=1,status=1,width=800,height=800'))%20%20%20%20l.href=u;};if(/Firefox/.test(navigator.userAgent))%20%20setTimeout(a,0);else%20%20a();void(0)";

$data['bookmarklet'] = $bookmarklet;
$data['username'] = $user[0]['realname'];
$data['js_failure_file'] = serendipity_getTemplateFile('admin/serendipity_styx.js');

serendipity_plugin_api::hook_event('backend_frontpage_display', $output);
$data['backend_frontpage_display'] = isset($output['more']) ? $output['probe'] . $output['more'] : '';
$output = array(); // re-new array for the autoupdate empty check below

// MAKE SURE it is the Administrator alike Group only to access it!
// If you have an "Editor in Chief" user which you want to have access on site Auto-Upgrades you can do the following for example as ADMINISTRATOR.
// Either: Add a new SPECIAL group with 'siteAutoUpgrades' [x] AND 'Hidden group / Non-Author' [x] and, afterwards in USERS, assign it to this single "special" user
//             which you want to be able to have upgrade permission and which also MUST have "Editor in CHIEF" group permission;
// Or copy the CHIEF group to a CHIEF+ group including 'siteAutoUpgrades' [x] and assign this (including the origin CHIEF group - SINGULARLY to the "special" user of request.
// Do not change the CHIEF group itself, for security! Although the 2cd does look more straightforward, the 1st is the recommended and easier approach for that case!
// Do not give that Group more rights than a CHIEF already has, except the two noted above. In special, this means to keep:
//    adminPluginsMaintainOthers, adminUsersMaintainOthers and siteConfiguration assigned to the ADMINISTRATOR only!!
if (false !== ((serendipity_checkPermission('siteConfiguration') || serendipity_checkPermission('siteAutoUpgrades')) && serendipity_checkPermission('adminUsersGroups'))) {
    // Update check
    $data['usedVersion']  = $serendipity['version'];
    $data['updateCheck']  = $serendipity['updateCheck'];
    $data['curVersion']   = serendipity_getCurrentVersion();
    $data['releaseFUrl']  = serendipity_get_config_var('updateReleaseFileUrl', 'https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE');
    $data['isCustom']     = $data['releaseFUrl'] != 'https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE' ? true : false;
    $data['curVersName']  = $serendipity['updateVersionName'] ?? null;
    $data['update']       = $data['curVersion'] !== -1 ? version_compare($data['usedVersion'], $data['curVersion'], '<') : false;
    $is_major             = $data['curVersion'] !== -1 ? ($data['curVersion'][0] > 4 && PHP_VERSION_ID >= 80200) : false;
    if ($is_major === true || ($data['curVersion'] !== -1 && $data['curVersion'][0] < 5)) {
        serendipity_plugin_api::hook_event('plugin_dashboard_updater', $output, $data['curVersion']);
    } else {
        $data['update'] = true; // Announce available upgrade todo
        $str = 'This major series upgrade is available via the "automatic upgrade", but requires at least <b><u>%s</u></b> as the minimum version. Please upgrade to <b>%s</b> first. Do NOT pull custom upgrades without !';
        $output = '<span class="msg_notice"><span class="icon-info-circled"></span> ' . sprintf($str, 'PHP 8.2', 'PHP 8.2');
    }
    $output = !empty($output) ? $output : '<span class="msg_error"><span class="icon-info-circled"></span> To get a button, check if the "Serendipity Autoupdate" event plugin is installed!</span>';
    $data['updateButton'] = $output;

    // Check remote sysinfo ticker messages for the Admins
    $store_message = false;
    // Get_config: Allow remote ticker messages for the Admins
    if (serendipity_db_bool(serendipity_get_config_var('remoteticker', 'true'))) {
        $author = $user[0]['realname'] . '_' . $serendipity['authorid'];
        if (isset($serendipity['POST']['sysinfo']['go']) && !empty($serendipity['POST']['sysinfo']['checked']['hash'])) {
            foreach ($serendipity['POST']['sysinfo']['checked']['hash'] AS $post_hash) {
                if (!empty($post_hash[1])) {
                    $hash = serendipity_db_escape_string($post_hash[1]);
                    $hide_hashes[] = $hash;
                    $aha = hash('xxh128', "{$author}{$hash}");
                    if ($hash != '0') {
                        // delete the sysinfo_ticker hash message item from database. It will be renewed in the fnc serendipity_sysInfoTicker() unless being marked as read checked and stored hashes.
                        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options WHERE name = 'sysinfo_ticker' AND okey = 'l_sysinfo_{$aha}' AND value = '$hash'");
                        $store_message = true;
                    }
                }
            }
        }
        $pshv = []; // array init for previously-stored-hash-values
        // get the stored away serialized hashes array (PG needs the ORDER BY added to DISTINCT. In this case it does not matter for MySQL and SQLite)
        $stored_hashes = serendipity_db_query("SELECT DISTINCT value FROM {$serendipity['dbPrefix']}options
                                                WHERE okey = 'l_read_sysinfo_hashes' AND name < " . serendipity_db_get_unixTimestamp(cts: true) . " ORDER BY value");
        // serialized result is type of array
        if (is_array($stored_hashes)) {
            foreach ($stored_hashes AS $shv) {
                $pshv[] = unserialize($shv['value']);
            }
        }

        // flatten the possible multidim array with already stored hashes (by differing timestamps) to then sort out uniques only
        if (!empty($pshv)) {
            // check for potential multidimensional array values
            $is_multi = (function($pshv) { foreach ($pshv as $a) { if (is_array($a)) return true; } return false; });
            if ($is_multi) {
                $temp = array();
                array_walk_recursive($pshv, function($value) use (&$temp){ $temp[] = $value; });
                $pshv = array_unique($temp, SORT_REGULAR);
            }
        }

        // Merge the two for new only, for current remote and already stored hashes
        if (!empty($hide_hashes) && !empty($pshv)) {
            $hashes = array_unique(array_merge($hide_hashes, $pshv));
        } else {
            $hashes = $hide_hashes ?? [];
        }
        // New and unchecked hash messages only
        if (!empty($hashes)) {
            if ($store_message) {
                echo '<span class="msg_success"><span class="icon-ok-circled"></span> Store hidden message identifiers. Please return to this page for the updated request.</span>'."\n";
            }
            // Store hidden hashes away for half a year, a year or even two... until nuked
            $ts = time(); // No internal _serverOffsetHour() call, since fully compat with DateTimeImmutable Zone
            $rh = serialize($hashes);
            @serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey) VALUES ('$ts', '$rh', 'l_read_sysinfo_hashes')");
            // garbage collect old stored hashes
            $date_gc = new \DateTimeImmutable('-6 Month');
            $tso = $date_gc->getTimestamp();
            serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options WHERE okey = 'l_read_sysinfo_hashes' AND name < '$tso'");
        }

        // read the ticker for new - but if already having been stored send previously-stored-hash-values to xml readout to exclude
        $data['sysinfo'] = serendipity_sysInfoTicker(true, $author, $pshv); // yes check-it !
    }
}

$data['urltoken'] = serendipity_setFormToken('url');
$data['token'] = serendipity_setFormToken();

// Inits
$data['shortcuts'] = $data['comments']['pending'] = $data['entries']['futures'] =  $data['entries']['drafts'] = null;

// SQL
$cjoin  = ($serendipity['serendipityUserlevel'] == USERLEVEL_EDITOR) ? "
        LEFT JOIN {$serendipity['dbPrefix']}authors a ON (e.authorid = a.authorid)
            WHERE e.authorid = " . (int)$serendipity['authorid']
        : '';
$where = !empty($cjoin) ? 'AND' : 'WHERE';
$cquery = "SELECT COUNT(c.id) AS newcom
             FROM {$serendipity['dbPrefix']}comments c
        LEFT JOIN {$serendipity['dbPrefix']}entries e ON (e.id = c.entry_id)
           $cjoin
           $where status = 'pending'";
$efilter  = ($serendipity['serendipityUserlevel'] == USERLEVEL_EDITOR) ? ' AND e.authorid = ' . (int)$serendipity['authorid'] : '';
$comments = serendipity_db_query($cquery);
$futures  = serendipity_db_query("SELECT COUNT(e.id) AS count FROM {$serendipity['dbPrefix']}entries AS e $cjoin $where e.timestamp >= " . serendipity_serverOffsetHour() . $efilter, true);
$drafts   = serendipity_db_query("SELECT COUNT(e.id) AS count FROM {$serendipity['dbPrefix']}entries AS e $cjoin $where e.isdraft = 'true'" . $efilter, true);

$permByAuthor = (!serendipity_checkPermission('adminUsers') && (int)$serendipity['authorid'] > 1) ? '&serendipity[filter][author]=' .(int)$serendipity['authorid'] : '';

// Assign
if (is_array($comments)) {
    $data['comments']['pending']['count'] = $comments[0]['newcom'];
    $data['comments']['pending']['link'] = 'serendipity_admin.php?'.$data['urltoken'].'&serendipity[adminModule]=comments'.$permByAuthor.'&serendipity[filter][show]=pending&submit=1';
    if ($comments[0]['newcom'] > 0) $data['shortcuts'] = true;
}
if (is_array($futures)) {
    $data['entries']['futures']['count'] = $futures['count'];
    $data['entries']['futures']['link']  = 'serendipity_admin.php?serendipity[adminModule]=entries&serendipity[adminAction]=editSelect&'.$data['urltoken'];
    if ($futures['count'] > 0) $data['shortcuts'] = true;
}
if (is_array($drafts)) {
    $data['entries']['drafts']['count'] = $drafts['count']; // catref GET avoids loading a previous session cookie author ID, see categories list and filter import regeneration in entries.inc
    $data['entries']['drafts']['link'] = 'serendipity_admin.php?serendipity[action]=admin&serendipity[adminModule]=entries&serendipity[adminAction]=editSelect'.$permByAuthor.'&serendipity[catref]=1&serendipity[filter][isdraft]=draft&dashboard[filter][noset]=1&go=1&serendipity[sort][perPage]=12&'.$data['urltoken'].'';
    if ($drafts['count'] > 0) $data['shortcuts'] = true;
}

$data['no_create'] = $serendipity['no_create'];

echo serendipity_smarty_showTemplate('admin/overview.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
