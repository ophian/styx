<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('adminUsersGroups')) {
    return;
}

$data = array();

/* Delete a group */
if (isset($_POST['DELETE_YES']) && serendipity_checkFormToken()) {
    $group = serendipity_fetchGroup($serendipity['POST']['group']);
    if (serendipity_deleteGroup($serendipity['POST']['group'])) {
        $data['delete_yes'] = true;
    } else {
        $data['delete_yes'] = false;
    }
    $data['group_id'] = $serendipity['POST']['group'];
    $data['group'] = $group;
}

/* Save new group */
if (isset($_POST['SAVE_NEW']) && serendipity_checkFormToken()) {
    $_group = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}groups WHERE name = '" . serendipity_db_escape_string($serendipity['POST']['name']) . "'", true, 'assoc');
    if (!is_array($_group)) {
        $serendipity['POST']['group'] = serendipity_addGroup($serendipity['POST']['name']);
        $_forbidden_plugins = $serendipity['POST']['forbidden_plugins'] ?? null;
        $_forbidden_hooks   = $serendipity['POST']['forbidden_hooks']   ?? null;
        $perms = serendipity_getAllPermissionNames();
        serendipity_updateGroupConfig($serendipity['POST']['group'], $perms, $serendipity['POST'], false, $_forbidden_plugins, $_forbidden_hooks);
        $data['save_new'] = true;
        $data['name'] = $serendipity['POST']['name'];
        $data['group_id'] = $serendipity['POST']['group'];
    } else {
        $data['group_taken'] = true;
        unset($_group);
    }
}

/* Edit a group */
if (isset($_POST['SAVE_EDIT']) && serendipity_checkFormToken()) {
    $perms = serendipity_getAllPermissionNames();
    $_forbidden_plugins = $serendipity['POST']['forbidden_plugins'] ?? null;
    $_forbidden_hooks   = $serendipity['POST']['forbidden_hooks']   ?? null;
    serendipity_updateGroupConfig($serendipity['POST']['group'], $perms, $serendipity['POST'], false, $_forbidden_plugins, $_forbidden_hooks);
    $data['save_edit'] = true;
    $data['name'] = $serendipity['POST']['name'];
}

if ($serendipity['GET']['adminAction'] != 'delete') {
    $data['delete'] = false;

    if (serendipity_checkPermission('adminUsersMaintainOthers')) {
        $groups = serendipity_getAllGroups();
    } elseif (serendipity_checkPermission('adminUsersMaintainSame')) {
        $groups = serendipity_getAllGroups($serendipity['authorid']);
    } else {
        $groups = array();
    }
    $data['groups'] = $groups;
    if (!(isset($_POST['NEW']) || $serendipity['GET']['adminAction'] == 'new')) {
        $data['start'] = true;
    }
    $data['deleteFormToken'] = serendipity_setFormToken('url');
}

if ($serendipity['GET']['adminAction'] == 'edit' || isset($_POST['NEW']) || $serendipity['GET']['adminAction'] == 'new') {
    if (isset($_POST['NEW']) || $serendipity['GET']['adminAction'] == 'new') {
        $data['new'] = true;
    } else {
        $data['edit'] = true;
    }
    $data['formToken'] = serendipity_setFormToken();
    $data['alevel'] = $serendipity['serendipityUserlevel'] == USERLEVEL_ADMIN ? true: false;
    $data['clevel'] = $serendipity['serendipityUserlevel'] == USERLEVEL_CHIEF ? true: false;

    if ($serendipity['GET']['adminAction'] == 'edit') {
        $group = serendipity_fetchGroup($serendipity['GET']['group']);
        $from = &$group;
    } else {
        $from = array();
    }
    $data['from'] = $from;

    $allusers = serendipity_fetchUsers();
    $users    = isset($from['id']) ? serendipity_getGroupUsers($from['id']) : array();

    $selected = array();
    foreach((array)$users AS $user) {
        $selected[$user['id']] = true;
    }
    $data['selected'] = $selected;
    $data['allusers'] = $allusers;

    $perms = serendipity_getAllPermissionNames();
    ksort($perms);
    $data['perms'] = $perms;

    foreach($perms AS $perm => $userlevels) {
        if ((defined('PERMISSION_' . strtoupper($perm)))) {
            list($name, $note) = ($perm != 'hiddenGroup' ? explode(':', (constant('PERMISSION_' . strtoupper($perm)))) : ['Hidden group / Non-Author', '']); // mute notice/warning(PHP8) since CONSTANT $name isn't set for [Hidden group / Non-Author] and [userlevel]
            $data['perms'][$perm]['permission_name'] = $name;
            $data['perms'][$perm]['permission_note'] = $note;
        } else {
            $data['perms'][$perm]['permission_name'] = $perm;
        }
        // excludes hiddenGroup and siteAutoUpgrades per ADMINISTRATOR from possible permission denied ..
        if (!serendipity_checkPermission($perm) && $perm != 'hiddenGroup' && $perm != 'siteAutoUpgrades') {
            $data['perms'][$perm]['permission'] = false;
        } else {
            $data['perms'][$perm]['permission'] = true;
        }
        // Now that THIS is done .. we keep siteAutoUpgrades non-editable for CHIEFs and Others
        if ($perm == 'siteAutoUpgrades' && $serendipity['serendipityUserlevel'] <= USERLEVEL_CHIEF) {
            $data['perms'][$perm]['permission'] = false; // sets "[siteAutoUpgrades]: No"
        }
    }

    if ($serendipity['enablePluginACL']) {
        if (!isset($from['id'])) $from['id'] = null;
        $data['enablePluginACL'] = true;
        $allplugins =& serendipity_plugin_api::get_event_plugins();
        $allhooks   = array();
        $data['allplugins'] = $allplugins;
        foreach($allplugins AS $plugid => $currentplugin) {
            foreach($currentplugin['b']->properties['event_hooks'] AS $hook => $set) {
                $allhooks[$hook] = array();
            }
            $data['allplugins'][$plugid]['has_permission'] = serendipity_hasPluginPermissions($plugid, $from['id']);
        }
        ksort($allhooks);

        $data['allhooks'] = $allhooks;
        foreach($allhooks AS $hook => $set) {
            $data['allhooks'][$hook]['has_permission'] = serendipity_hasPluginPermissions($hook, $from['id']);
        }
    }

} elseif ($serendipity['GET']['adminAction'] == 'delete') {
    $data['delete'] = true;
    $group = serendipity_fetchGroup($serendipity['GET']['group']);
    $data['group_id'] = $serendipity['GET']['group'];
    $data['group'] = $group;
    $data['formToken'] = serendipity_setFormToken();
}

echo serendipity_smarty_showTemplate('admin/groups.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
?>