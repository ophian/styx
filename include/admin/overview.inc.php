<?php

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
                'id' => serendipity_specialchars($serendipity['POST']['id']),
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
    $data['usedVersion']  = $serendipity['version'];
    $data['updateCheck']  = $serendipity['updateCheck'];
    $data['curVersion']   = serendipity_getCurrentVersion();
    $data['releaseFUrl']  = serendipity_get_config_var('updateReleaseFileUrl', 'https://raw.githubusercontent.com/ophian/styx/master/docs/RELEASE');
    $data['curVersName']  = $serendipity['updateVersionName'] ?? null;
    $data['update']       = version_compare($data['usedVersion'], $data['curVersion'], '<');
    serendipity_plugin_api::hook_event('plugin_dashboard_updater', $output, $data['curVersion']);
    $output = !empty($output) ? $output : '<span class="msg_error"><span class="icon-info-circled"></span> To get a button, check if the "Serendipity Autoupdate" event plugin is installed!</span>';
    $data['updateButton'] = $output;
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
    $data['entries']['drafts']['count'] = $drafts['count'];
    $data['entries']['drafts']['link'] = 'serendipity_admin.php?serendipity[action]=admin&serendipity[adminModule]=entries&serendipity[adminAction]=editSelect'.$permByAuthor.'&serendipity[catref]=1&serendipity[filter][isdraft]=draft&dashboard[filter][noset]=1&go=1&serendipity[sort][perPage]=12&'.$data['urltoken'].'';
    if ($drafts['count'] > 0) $data['shortcuts'] = true;
}

$data['no_create'] = $serendipity['no_create'];

echo serendipity_smarty_showTemplate('admin/overview.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
