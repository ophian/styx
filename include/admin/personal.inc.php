<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('personalConfiguration')) {
    return;
}

define('USERCONF_NEW_PASSWDEX_TOOLTIP_INFO', ' - ' . mb_strtolower(WORD_OR) . " -<br>\n" . sprintf('<span class="newrex" title="' . USERCONF_PASSWORD_RANDOM . '"><span class="icon-info-circled" aria-hidden="true"></span>%s</span>', serendipity_generate_password(20)));// no space with %s !

$data = array();
$from = array();

if ($serendipity['GET']['adminAction'] == 'save' && serendipity_checkFormToken()) {
    $config = serendipity_parseTemplate(S9Y_CONFIG_USERTEMPLATE);
    $data['adminAction'] = 'save';
    if ((!serendipity_checkPermission('adminUsersEditUserlevel') || !serendipity_checkPermission('adminUsersMaintainOthers'))
          && isset($_POST['userlevel']) && (int) $_POST['userlevel'] > $serendipity['serendipityUserlevel']) {
        $data['not_authorized'] = true;
    } elseif (empty($_POST['username'])) {
        $data['empty_username'] = true;
    } elseif (  (!empty($_POST['password'])
                    &&
                !empty($_POST['check_password'])
                    &&
                $_POST['check_password'] != $_SESSION['serendipityPassword']
                    &&
                serendipity_passwordhash($_POST['check_password']) != $_SESSION['serendipityPassword'])
                ||
                (!empty($_POST['password'])
                    &&
                empty($_POST['check_password'])
                    &&
                $_POST['password'] != $_SESSION['serendipityPassword']
                    &&
                serendipity_passwordhash($_POST['password']) != $_SESSION['serendipityPassword'])
            ) {
         $data['password_check_fail'] = true;
    } else {
        $valid_groups = serendipity_getGroups($serendipity['authorid'], true);
        $data['realname'] = $_POST['realname'];
        foreach($config AS $category) {
            foreach($category['items'] AS $item) {
                if (in_array('groups', $item['flags'])) {
                    if (serendipity_checkPermission('adminUsersMaintainOthers')) {
                        // Void, no fixing necessary
                    } elseif (serendipity_checkPermission('adminUsersMaintainSame')) {
                        if (!isset($_POST[$item['var']]) || !is_array($_POST[$item['var']])) {
                            continue;
                        }

                        // Check that no user may assign groups he's not allowed to.
                        foreach($_POST[$item['var']] AS $groupkey => $groupval) {
                            if (in_array($groupval, $valid_groups)) {
                                continue;
                            } elseif ($groupval == 2 && in_array(3, $valid_groups)) {
                                // Admin is allowed to assign users to chief editors
                                continue;
                            } elseif ($groupval == 1 && in_array(2, $valid_groups)) {
                                // Chief is allowed to assign users to editors
                                continue;
                            }

                            unset($_POST[$item['var']][$groupkey]);
                        }

                    } else {
                        continue;
                    }
/*
                    if (count($_POST[$item['var']]) < 1) {
                        echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . WARNING_NO_GROUPS_SELECTED . "</span>\n";
                    } else {
                        serendipity_updateGroups($_POST[$item['var']], $serendipity['authorid'], false);
                    }
*/
                    continue;
                }

                // Moved to group administration:
                if ($item['var'] == 'userlevel') continue;
                if (isset($item['view']) && $item['view'] == 'dangerous') continue;

                if (serendipity_checkConfigItemFlags($item, 'local')) {
                    serendipity_set_user_var($item['var'], $_POST[$item['var']], (int) $serendipity['authorid'], true);
                }

                if (serendipity_checkConfigItemFlags($item, 'configuration')) {
                    serendipity_set_config_var($item['var'], $_POST[$item['var']], (int) $serendipity['authorid']);
                }
            }

            if (isset($serendipity['POST']['authorid'])) {
                $pl_data = array(
                    'id'       => (int) $serendipity['POST']['authorid'],
                    'authorid' => (int) $serendipity['POST']['authorid'],
                    'username' => $_POST['username'],
                    'realname' => $_POST['realname'],
                    'email'    => $_POST['email']
                );
                serendipity_updatePermalink($pl_data, 'author');
                serendipity_plugin_api::hook_event('backend_users_edit', $pl_data);
            }
        }
        if ($serendipity['authorid'] === $_SESSION['serendipityAuthorid']) {
            if (is_null($serendipity['detected_lang'])) {
                $_SESSION['serendipityLanguage'] = $serendipity['lang'];
            }
        }
        $from = $_POST;
    }
}

$data['formToken'] = serendipity_setFormToken();
$template       = serendipity_parseTemplate(S9Y_CONFIG_USERTEMPLATE);
$user           = serendipity_fetchUsers($serendipity['authorid']);
$from           = $user[0];
$from['groups'] = serendipity_getGroups($serendipity['authorid']);
unset($from['password']);

// A pre parsed and rendered template, analogue to 'ENTRIES' etc
$data['CONFIG'] = serendipity_printConfigTemplate($template, $from, true);

$add = array('internal' => true);
serendipity_plugin_api::hook_event('backend_sidebar_entries_event_display_profiles', $from, $add);

if (!is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

echo serendipity_smarty_showTemplate('admin/personal.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
