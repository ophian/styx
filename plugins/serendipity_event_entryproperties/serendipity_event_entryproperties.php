<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_entryproperties extends serendipity_event
{
    var $services, $showPasswordForm;
    var $title = PLUGIN_EVENT_ENTRYPROPERTIES_TITLE;

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',          PLUGIN_EVENT_ENTRYPROPERTIES_TITLE);
        $propbag->add('description',   PLUGIN_EVENT_ENTRYPROPERTIES_DESC . (isset($serendipity['GET']['plugin_to_conf']) ? ' ' . PLUGIN_EVENT_ENTRYPROPERTIES_DESC_PLUS : ''));
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Garvin Hicking, Ian Styx');
        $propbag->add('version',       '1.95');
        $propbag->add('requirements',  array(
            'serendipity' => '5.0',
            'smarty'      => '4.1',
            'php'         => '8.2.0'
        ));
        $propbag->add('event_hooks',    array(
            'frontend_fetchentries'                             => true,
            'frontend_fetchentry'                               => true,
            'backend_publish'                                   => true,
            'backend_save'                                      => true,
            'backend_display'                                   => true,
            'css_backend'                                       => true,
            'backend_import_entry'                              => true,
            'entry_display'                                     => true,
            'entries_header'                                    => true,
            'frontend_entryproperties'                          => true,
            'backend_sidebar_entries_event_display_buildcache'  => true,
            'backend_maintenance'                               => true,
            'backend_cache_entries'                             => true,
            'backend_cache_purge'                               => true,
            'backend_plugins_new_instance'                      => true,
            'frontend_entryproperties_query'                    => true,
            'frontend_entries_rss'                              => true,
            'backend_entry_presave'                             => true,
            'frontend_configure'                                => true
        ));
        $propbag->add('groups', array('BACKEND_EDITOR'));
        $propbag->add('configuration', array('cache', 'autocache', 'sequence', 'use_groups', 'use_users', 'use_ext_joins', 'default_read', 'customfields'));
        $propbag->add('legal',    array(
            'services' => array(),
            'frontend' => array(
                'If password protected entries are used, the password can be sent by the visitor and if access is granted, this is stored in a session variable.',
            ),
            'cookies' => array(
                'Uses PHP session cookie for potential session storage whether access to a password-protected entry is granted'
            ),
            'stores_user_input'     => false,
            'stores_ip'             => false,
            'uses_ip'               => false,
            'transmits_user_input'  => true
        ));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'default_read':
                $propbag->add('type',        'radio');
                $propbag->add('name',        USE_DEFAULT . ': ' . PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS);
                $propbag->add('description', '');
                $propbag->add('radio', array(
                    'value' => array('private', 'public', 'member'),
                    'desc'  => array(PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE, PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC, PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS)
                ));
                $propbag->add('default',     'public');
                $propbag->add('radio_per_row', '1');
                break;

            case 'customfields':
                $propbag->add('type',        'text');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC2 . "\n" . PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC4);
                $propbag->add('default',     'CustomField1, CustomField2, CustomField3');
                $propbag->add('rows',        6);
                break;

            case 'use_groups':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_GROUPS_DESC);
                $propbag->add('default',     'false');
                break;

            case 'use_ext_joins':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_EXTJOINS_DESC);
                $propbag->add('default',     'true');
                break;

            case 'use_users':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_USERS);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_USERS_DESC);
                $propbag->add('default',     'false');
                break;

            case 'cache':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_CACHE);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DESC);
                $propbag->add('default',     'false');
                break;

             case 'autocache':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE_AUTO);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE_AUTO_DESC);
                $propbag->add('default',     'false');
                break;

            case 'sequence':
                $propbag->add('type',        'sequence');
                $propbag->add('name',        PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE);
                $propbag->add('description', PLUGIN_EVENT_ENTRYPROPERTIES_SEQUENCE_DESC);
                $propbag->add('checkable',   true);
                $values = array(
                    'sticky'       => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS),
                    'spacer'       => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_EMPTYBOX),
                    'author'       => array('display' => AUTHOR),
                    'multi_authors'=> array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_MULTI_AUTHORS),
                    'password'     => array('display' => PASSWORD),
                    'frontpage'    => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE),
                    'hiderss'      => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS),
                    'access'       => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS),
                    'markup'       => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_DISABLE_MARKUP),
                    'groups'       => array('display' => PERM_READ . ': ' . GROUP),
                    'authors'      => array('display' => PERM_READ . ': ' . AUTHOR),
                    'customfields' => array('display' => PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS),
                );
                $propbag->add('values',      $values);
                $propbag->add('default',     'sticky,spacer,author,password,frontpage,hiderss,access,markup,groups,authors,multi_authors');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

    function install()
    {
        serendipity_plugin_api::hook_event('backend_cache_entries', $this->title);
    }

    function uninstall(&$propbag)
    {
        serendipity_plugin_api::hook_event('backend_cache_purge', $this->title);
    }

    function &getValidAuthors()
    {
        global $serendipity;

        if (serendipity_checkPermission('adminUsersMaintainOthers')) {
            $users = serendipity_chainByLevel(serendipity_fetchUsers('', 'hidden'));
        } elseif (serendipity_checkPermission('adminUsersMaintainSame')) {
            $users = serendipity_chainByLevel(serendipity_fetchUsers('', serendipity_getGroups($serendipity['authorid'], true)));
        } else {
            $users = serendipity_fetchUsers($serendipity['authorid']);
        }

        return $users;
    }

    function updateCache(&$entry)
    {
        global $serendipity;

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$entry['id'] . " AND property LIKE 'ep_cache_%'");
        $addData = array('from' => 'serendipity_event_entryproperties:updateCache');
        serendipity_plugin_api::hook_event('frontend_display', $entry, $addData);
        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$entry['id'] . ", 'ep_cache_body', '" . serendipity_db_escape_string($entry['body']) . "')");
        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$entry['id'] . ", 'ep_cache_extended', '" . serendipity_db_escape_string($entry['extended']) . "')");
    }

    function getSupportedProperties()
    {
        static $supported_properties = null;

        if ($supported_properties === null) {
            $supported_properties = array('is_sticky', 'spacer', 'access', 'entrypassword', 'no_frontpage', 'hiderss', 'access_groups', 'access_users', 'multi_authors', 'cache_body', 'cache_extended');

            // Capture special characters for "," and ":"
            $special_from = array('\\,', '\\:');
            $special_to   = array(chr(0x01), chr(0x02));
            $string = str_replace($special_from, $special_to, trim($this->get_config('customfields', 'CustomField1, CustomField2, CustomField3')));

            $fields = explode(',', $string);
            if (is_array($fields) && count($fields) > 0) {
                foreach($fields AS $field) {
                    $field = trim($field);
                    if (!empty($field)) {
                        $field = str_replace($special_to, $special_from, $field);
                        $fieldnames = explode(':', $field);
                        $supported_properties[] = $fieldnames[0];
                    }
                }
            }
        }

        return $supported_properties;
    }

    function returnQueryCondition($is_cache)
    {
        $and = '';
        if (!$is_cache) {
            $and = " AND property NOT LIKE 'ep_cache_%' ";
        }

        return $and;
    }

    /**
     * This function converts specific property fields so that they are
     * available natively to other plugins/templates.
     */
    function applyProperties(&$properties)
    {
        global $serendipity;

        if (isset($properties['disable_markups']) && is_array($properties['disable_markups'])) {
            foreach($properties['disable_markups'] AS $idx => $instance) {
                $properties['disable_markup_' . $instance] = $instance;
            }
        }
    }

    function addProperties(&$properties, &$eventData)
    {
        global $serendipity;

        // Get existing data
        $property = serendipity_fetchEntryProperties((int) $eventData['id']);
        $supported_properties = serendipity_event_entryproperties::getSupportedProperties();

        // Cleanup properties first, if none disable_markups plugins were set like in PLAIN TEXT Editor, or a previous selected one was re-set
        if (is_array($serendipity['POST']['properties']) && (!isset($serendipity['POST']['properties']['disable_markups']) || !is_array($serendipity['POST']['properties']['disable_markups']))) {
            $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property LIKE 'ep_disable_markup_%'";
            serendipity_db_query($q);
        }

        // Special case for input type checkbox entryproperties
        $reset_properties = array('is_sticky', 'no_frontpage', 'hiderss');
        // Special case for multi_authors, access_groups and access_users array cases
        array_push($reset_properties, 'multi_authors', 'access_groups', 'access_users'); // merge with array type entryproperties

        // reset a 'member' or 'private' access state to default 'public' on removal of either empty 'access_groups' or 'access_users' array keys
        if (!isset($serendipity['POST']['propertyform']) && is_array($serendipity['POST']['properties']) && (!isset($serendipity['POST']['properties']['access']) || $serendipity['POST']['properties']['access'] != 'public') && !isset($serendipity['POST']['properties']['access_groups'][0]) && !isset($serendipity['POST']['properties']['access_users'][0])) {
            $properties['access'] = 'public';
        }

        foreach($reset_properties AS $property) {
            if (!isset($serendipity['POST']['propertyform']) && is_array($serendipity['POST']['properties']) && !in_array($property, $serendipity['POST']['properties'])) {
                $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property = 'ep_{$property}'";
                serendipity_db_query($q);
            }
        }

        // Special case for disable markups.
        if (isset($serendipity['POST']['properties']['disable_markups']) && is_array($properties['disable_markups'])) {
            $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property LIKE 'ep_disable_markup_%'";
            serendipity_db_query($q);

            foreach($properties['disable_markups'] AS $idx => $instance) {
                $supported_properties[] = 'disable_markup_' . $instance;
            }
        }

        serendipity_plugin_api::hook_event('backend_entryproperties', $supported_properties);

        foreach($supported_properties AS $prop_key) {
            // Do not delete a property if it is not submitted to this function, because serendipity_updertEntry
            // possibly only wants to update entry metadata and left out any specific properties, which need to be kept.
            // An empty string like "" will properly remove an entryproperty, and POST values will always set an array index to an empty string.
            // A (theme config set) $serendipity['POST']['propertyform'] will be set whenever the entryeditor was properly displayed and unticked checkboxes shall remain.
            // (Not for checkboxes, but checkboxes are not used for entryproperties) - (Edit: Well, actually we do have some, see reset special case checkboxed properties above!)
            if (!isset($properties[$prop_key]) && !isset($serendipity['POST']['propertyform'])) {
                continue;
            }

            $prop_val = $properties[$prop_key] ?? null;
            $prop_key = 'ep_' . $prop_key;

            if (is_array($prop_val)) {
                // We should not change this ;; behaviour since we already have other array keys like 'access_groups', 'access_users'
                // or other plugins with the same approach. The real fix to avoid empty array errors is on output at line ~975!
                #if ($prop_key !== 'multi_authors') {
                #    $prop_val = implode(';', $prop_val);
                #} else {
                    // Note: Not sure if this prefix and suffix with ";" is a really good idea.
                    // At least with multi_authors it's a problem, because it creates empty records.
                    // Maybe other keys also need specific fixing.
                    $prop_val = ";" . implode(';', $prop_val) . ";";
                #}
            }

            $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property = '" . serendipity_db_escape_string($prop_key) . "'";
            serendipity_db_query($q);

            if (!empty($prop_val)) {
                $q = "INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$eventData['id'] . ", '" . serendipity_db_escape_string($prop_key) . "', '" . serendipity_db_escape_string($prop_val) . "')";
                serendipity_db_query($q);
            }
        }
    }

    function showPasswordform()
    {
        global $serendipity;

        $url = serendipity_currentURL(true);
        $out = '
        <form action="'.$url.'" method="post" id="entrypasswordform">
            <div>
                <span><label for="entrypassword">' . PASSWORD . ':</label> <input class="input_textbox" id="entrypassword" type="password" name="serendipity[entrypassword]" autocomplete="off" value="" /></span>
                <span><input class="serendipityPrettyButton input_button" type="submit" name="login" value="'.LOGIN.'" /></span>
            </div>
        </form>';

        return $out;
    }

    function showBackend($element, $eventData, $is_sticky, $no_frontpage, $hiderss, $access_values, $access, #[\SensitiveParameter] string $password, $use_groups, $access_groups, $use_users, $access_users, $more = array())
    {
        global $serendipity;

        switch($element) {

            case 'sticky':
?>
                <div id="ep_sticky" class="entryproperties_sticky adv_opts_box form_check">
                    <input id="properties_is_sticky" name="serendipity[properties][is_sticky]" type="checkbox" value="true" <?php echo $is_sticky; ?>>
                    <label for="properties_is_sticky"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS; ?></label>
                </div>
<?php
                break;

            case 'spacer':
?>
                <div id="ep_spacer" class="entryproperties_spacer adv_opts_box form_check">
                    <span><em>spacebox</em></span>
                </div>
<?php
                break;

            case 'frontpage':
?>
                <div id="ep_frontpage" class="entryproperties_frontpage adv_opts_box form_check">
                    <input id="properties_no_frontpage" name="serendipity[properties][no_frontpage]" type="checkbox" value="true" <?php echo $no_frontpage; ?>>
                    <label for="properties_no_frontpage"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_NO_FRONTPAGE; ?> <i class="icon-info-circled" aria-hidden="true" title="<?php echo htmlspecialchars(PLUGIN_EVENT_ENTRYPROPERTIES_RECOMMENDED_SET); ?>"></i></label>
                </div>
<?php
                break;

            case 'hiderss':
?>
                <div id="ep_hiderss" class="entryproperties_hiderss adv_opts_box form_check">
                    <input id="properties_hiderss" name="serendipity[properties][hiderss]" type="checkbox" value="true" <?php echo $hiderss; ?>>
                    <label for="properties_hiderss"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_HIDERSS; ?></label>
                </div>
<?php
                break;

            case 'access':
?>
                <div id="ep_access_list" class="entryproperties_access_list adv_opts_box">
                    <span class="wrap_legend"><legend><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS; ?>:</legend></span>

                    <div class="clearfix">
<?php
                foreach($access_values AS $radio_title => $radio_value) {
?>
                        <div class="form_radio">
                            <input id="properties_access_<?php echo $radio_value; ?>" name="serendipity[properties][access]" type="radio" value="<?php echo $radio_value; ?>"<?php echo $radio_value == $access ? ' checked="checked"' : ''; ?>>
                            <label for="properties_access_<?php echo $radio_value; ?>"><?php echo $radio_title; ?></label>
                        </div>
<?php
                }
?>
                    </div>
                </div>
<?php
                break;

            case 'password':
/*
 The hidden password input element was set to type="password" before and - as a workaround - used to prevent the Firefox browser [early <= 42 versions]
 to auto fill password fields into other normal input fields. Garvin, Jan 15, 2015, https://github.com/s9y/Serendipity/commit/1b9e5de787c36c08a449c631ca271e2a0556770d
 In follow of this, the issue #378 at https://github.com/s9y/Serendipity/issues/378 added https://github.com/s9y/Serendipity/commit/c98e858886ce83877620e84d00f11259c8576354 to the config_local.inc.php.
 This - for the here case - does NOT DO any more.
 This is why the FF only autocomplete="new-password" attribute was added to the real password field, since that officially is the attribute Firefox supports for this case.
 It further turned out that now - currently with FF v120 - other input fields of type="text" did get the auto added "password manager" hotlink added,
 eg. the entry form freetag field, which ann/destr-oys the autocomplete JS of the tags event.
 Other fields do not behave so; Like the entry form "title" input field; So it probably seems related to some misbehaving JS event handler...
 BUT we can prevent this behavior by changing the name="ignore_password" input workaround element field to a type="text" field. Then everything works as desired.
 Keep in mind for later browser changes though!
*/
?>
                <div id="ep_access_pw" class="entryproperties_access_pw adv_opts_box form_field">
                    <label for="properties_access_pw"><?php echo PASSWORD; ?>:</label>
                    <input type="text" name="ignore_password" value="" style="visibility: hidden; width: 1px">
                    <input id="properties_access_pw" name="serendipity[properties][entrypassword]" type="password" autocomplete="new-password" value="<?php echo htmlspecialchars($password); ?>">
                    <i class="icon-info-circled" aria-hidden="true" title="<?php echo htmlspecialchars(ENTRY_PAGE_PASSWORD_INFO_SET); ?>"></i>
                </div>
<?php
                break;

            case 'groups':
                if ($use_groups) {
                    $my_groups = serendipity_getGroups($serendipity['authorid']);
?>
                <div id="ep_access_groups" class="entryproperties_access_groups adv_opts_box form_multiselect">
                    <label for="properties_access_groups"><?php echo PERM_READ . ': ' . GROUP; ?> <i class="icon-info-circled" aria-hidden="true" title="<?php echo htmlspecialchars(PERMISSION_READ_WRITE_ACL_DESC); ?>"></i></label>

                    <select id="properties_access_groups" name="serendipity[properties][access_groups][]" multiple="multiple" size="4" onchange="document.getElementById('properties_access_member').checked = true;">
<?php
                    foreach($my_groups AS $group) {
                        if ('USERLEVEL_' == substr($group['confvalue'], 0, 10)) {
                            $group['name'] = constant($group['confvalue']);
                        }
?>
                        <option value="<?php echo $group['id']; ?>"<?php echo (in_array($group['id'], $access_groups) ? ' selected="selected"' : ''); ?>><?php echo htmlspecialchars($group['name']); ?></option>
<?php
                    }
?>
                    </select>
                </div>
<?php
                }
                break;

            case 'authors':
                if ($use_users) {
?>
                <div id="ep_access_users" class="entryproperties_access_users adv_opts_box form_multiselect">
                    <label for="properties_access_users"><?php echo PERM_READ . ': '. AUTHOR; ?> <i class="icon-info-circled" aria-hidden="true" title="<?php echo htmlspecialchars(PERMISSION_READ_WRITE_ACL_DESC); ?>"></i></label>

                    <select id="properties_access_users" name="serendipity[properties][access_users][]" multiple="multiple" size="4" onchange="document.getElementById('properties_access_member').checked = true;">
<?php
                    $users = serendipity_fetchUsers('', 'hidden');
                    foreach($users AS $user) {
                        $_realname = !empty($user['realname']) ? htmlspecialchars($user['realname']) : 'no realname';
?>
                        <option value="<?php echo $user['authorid']; ?>"<?php echo (in_array($user['authorid'], $access_users) ? ' selected="selected"' : ''); ?>><?php echo $_realname; ?></option>
<?php
                    }
?>
                    </select>
                </div>
<?php
                }
                break;

            case 'multi_authors':
?>
                <div id="ep_access_multiauthors" class="entryproperties_access_multi_authors adv_opts_box form_multiselect">
                    <label for="properties_access_multi_authors"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_MULTI_AUTHORS; ?></label>
                    <select id="properties_access_multi_authors" name="serendipity[properties][multi_authors][]" multiple="multiple">
<?php
                if (isset($serendipity['POST']['properties']['multi_authors'])) {
                    $counter_multi_authors = 0;
                    foreach($serendipity['POST']['properties']['multi_authors'] AS $user) {
                        $selected_users[$counter_multi_authors] = $user;
                        $counter_multi_authors++;
                    }
                } elseif (!empty($eventData['properties']['ep_multi_authors'])) {
                    $counter_multi_authors = 0;
                    foreach($eventData['properties']['ep_multi_authors'] AS $user) {
                        $selected_users[$counter_multi_authors] = $user['author_id'];
                        $counter_multi_authors++;
                    }
                } else {
                    $selected_users = array();
                }

                $avail_users =& $this->getValidAuthors();

                echo '                    <option value="">- - -</option>' . "\n";

                foreach($avail_users AS $user) {
                    echo '                    <option value="' . $user['authorid'] . '"' . (in_array($user['authorid'], $selected_users) ? ' selected="selected"' : '') . '>' . htmlspecialchars($user['realname']) . '</option>' . "\n";
                }
?>
                    </select>
                </div>
<?php
                break;

            case 'author':
?>
                <div id="ep_access_author" class="entryproperties_access_author adv_opts_box form_select">
                    <label for="properties_access_author"><?php echo AUTHOR; ?>:</label>

                    <select id="properties_access_author" name="serendipity[change_author]">
<?php
                if (isset($serendipity['POST']['change_author'])) {
                    $selected_user = $serendipity['POST']['change_author'];
                } elseif (!empty($eventData['authorid'])) {
                    $selected_user = $eventData['authorid'];
                } else {
                    $selected_user = $serendipity['authorid'];
                }
                $avail_users =& $this->getValidAuthors();
                if (is_array($avail_users) && !empty($avail_users)) {
                    foreach($avail_users AS $user) {
                        $_realname = !empty($user['realname']) ? htmlspecialchars($user['realname']) : 'no realname';
                        echo '                        <option value="' . $user['authorid'] . '"' . ($selected_user == $user['authorid'] ? ' selected="selected"' : '') . '>' . $_realname . '</option>' . "\n";
                    }
                }
?>
                    </select>
                </div>
<?php
                break;

            case 'markup':
?>
                <div id="ep_markup" class="entryproperties_markup adv_opts_box form_multiselect">
                    <label for="properties_markup"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_DISABLE_MARKUP; ?></label>

                    <select id="properties_markup" name="serendipity[properties][disable_markups][]" multiple="multiple" size="4">
<?php
                $plugins = serendipity_plugin_api::get_event_plugins();

                if (is_array($plugins)) {
                    foreach($plugins AS $plugin => &$plugin_data) {
                        // mute possible uninitialized items
                        if (!isset($plugin_data['p']->markup_elements) || !is_array(@$plugin_data['p']->markup_elements) || empty($plugin_data['p']->markup_elements)) {
                            continue;
                        }

                        if (isset($serendipity['POST']['properties']['disable_markups']) && in_array($plugin_data['p']->instance, $serendipity['POST']['properties']['disable_markups'])) {
                            $selected = true;
                        } elseif (isset($eventData['properties']['ep_disable_markup_' . $plugin_data['p']->instance])) {
                            $selected = true;
                        } else {
                            $selected = false;
                        }
                        // automatically mark nl2br markup parser as disabled, when WYSIWYG is active
                        if (!$selected && isset($serendipity['wysiwyg']) && $serendipity['wysiwyg'] && $plugin_data['p']->act_pluginPath == 'serendipity_event_nl2br') {
                            $selected = true;
                        }
                        echo '                        <option' . ($selected ? ' selected="selected"' : '') . ' value="' . $plugin_data['p']->instance . '">' . htmlspecialchars($plugin_data['t']) . '</option>' . "\n";
                    }
                }
?>
                    </select>
                </div>
<?php
                break;

            case 'customfields':
?>
                <div id="ep_customfields" class="entryproperties_customfields adv_opts_box">
<?php
                $fields = trim($this->get_config('customfields', 'CustomField1, CustomField2, CustomField3'));
                  // Capture special characters for "," and ":"
                $special_from = array('\\,', '\\:');
                $special_to   = array(chr(0x01), chr(0x02));
                $special_read = array(',', ':');
                $fields = str_replace($special_from, $special_to, $fields);

                if (!empty($fields)) {
                    $fields = explode(',', $fields);
                }
                if (is_array($fields) && count($fields) > 0) { ?>

                    <span class="wrap_legend"><legend><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS; ?>: <button class="toggle_info button_link" type="button" data-href="#ep_customfields_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button></legend></span>
                    <div id="ep_customfields_info" class="additional_info">
                        <span class="msg_hint"><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC1 . sprintf(PLUGIN_EVENT_ENTRYPROPERTIES_CUSTOMFIELDS_DESC3, 'serendipity_admin.php?serendipity[adminModule]=plugins&amp;serendipity[plugin_to_conf]=' . $this->instance); ?></span>
                    </div>
                    <div id="serendipity_customfields" class="clearfix">
<?php
                foreach($fields AS $fieldname) {
                    $fieldparts = explode(':', $fieldname);
                    $fieldname  = $fieldparts[0];
                    $_fieldname = htmlspecialchars(trim($fieldname));

                    if (isset($serendipity['POST']['properties'][$_fieldname])) {
                        $value = $serendipity['POST']['properties'][$_fieldname];
                    } elseif (!empty($eventData['properties']['ep_' . $_fieldname])) {
                        $value = $eventData['properties']['ep_' . $_fieldname];
                    } elseif (isset($fieldparts[1])) {
                        $value = trim(str_replace($special_to, $special_read, $fieldparts[1]));
                    } else {
                        $value = '';
                    }
?>
                        <div id="ep_column_<?php echo $_fieldname; ?>" class="clearfix form_area media_choose">
                            <label for="prop<?php echo $_fieldname; ?>"><?php echo $_fieldname; ?></label>
                            <textarea id="prop<?php echo $_fieldname; ?>" class="change_preview" name="serendipity[properties][<?php echo $_fieldname; ?>]" data-configitem="prop<?php echo $_fieldname; ?>"><?php echo htmlspecialchars($value); ?></textarea>
                            <button class="customfieldMedia" type="button" name="insImage" title="<?php echo MEDIA ; ?>"><span class="icon-picture" aria-hidden="true"></span><span class="visuallyhidden"><?php echo MEDIA ; ?></span></button>
<?php if (preg_match('/(\.jpg|\.png|\.bmp)$/', $value)) { ?>
                            <figure id="prop<?php echo $_fieldname; ?>_preview">
                                <figcaption><?php echo PREVIEW; ?></figcaption>
                                <img src="<?php echo $value; ?>"  alt=""/>
                            </figure>
<?php } ?>
                        </div>
<?php
                }
?>
                    </div>
<?php
                }
?>
                </div>
<?php
                break;
        }
    }

    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;
        static $is_cache = null;
        static $auto_cache = null;
        static $use_groups = null;
        static $use_users = null;
        static $ext_joins = null;

        $hooks = &$bag->get('event_hooks');

        if ($is_cache === null) {
            $is_cache   = serendipity_db_bool($this->get_config('cache', 'false'));
            $auto_cache = serendipity_db_bool($this->get_config('autocache', 'false'));
            $use_groups = serendipity_db_bool($this->get_config('use_groups', 'false'));
            $use_users  = serendipity_db_bool($this->get_config('use_users', 'false'));
            $ext_joins  = serendipity_db_bool($this->get_config('use_ext_joins', 'true'));
        }

        if (isset($hooks[$event])) {

            switch($event) {

                case 'frontend_entryproperties_query':
                    $eventData['and'] = $this->returnQueryCondition($is_cache);
                    break;

                case 'backend_display':
                    $is_sticky =    (isset($eventData['properties']['ep_is_sticky']) && serendipity_db_bool($eventData['properties']['ep_is_sticky']))
                                 || (isset($serendipity['POST']['properties']['is_sticky']) && serendipity_db_bool($serendipity['POST']['properties']['is_sticky']))
                               ? 'checked="checked"'
                               : '';

                    $no_frontpage = (isset($eventData['properties']['ep_no_frontpage']) && serendipity_db_bool($eventData['properties']['ep_no_frontpage']))
                                 || (isset($serendipity['POST']['properties']['no_frontpage']) && serendipity_db_bool($serendipity['POST']['properties']['no_frontpage']))
                               ? 'checked="checked"'
                               : '';

                    $hiderss      = (isset($eventData['properties']['ep_hiderss']) && serendipity_db_bool($eventData['properties']['ep_hiderss']))
                                 || (isset($serendipity['POST']['properties']['hiderss']) && serendipity_db_bool($serendipity['POST']['properties']['hiderss']))
                               ? 'checked="checked"'
                               : '';

                    $access_values = array(
                        PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PRIVATE => 'private',
                        PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_PUBLIC  => 'public',
                        PLUGIN_EVENT_ENTRYPROPERTIES_ACCESS_MEMBERS => 'member',
                    );

                    if (isset($eventData['properties']['ep_access'])) {
                        $access = $eventData['properties']['ep_access'];
                    } elseif (isset($serendipity['POST']['properties']['access'])) {
                        $access = $serendipity['POST']['properties']['access'];
                    } else {
                        $access = $this->get_config('default_read', 'public');
                    }

                    if (isset($eventData['properties']['ep_access_groups'])) {
                        $access_groups = explode(';', $eventData['properties']['ep_access_groups']);
                    } elseif (isset($serendipity['POST']['properties']['access_groups'])) {
                        $access_groups = $serendipity['POST']['properties']['access_groups'];
                    } else {
                        $access_groups = array();
                    }

                    if (isset($eventData['properties']['ep_multi_authors'])) {
                        //$eventData['properties']['ep_multi_authors'] = explode(';', $eventData['properties']['ep_multi_authors']);
                    } elseif (isset($serendipity['POST']['properties']['multi_authors'])) {
                        //$serendipity['POST']['properties']['multi_authors'] = explode(';', $serendipity['properties']['ep_multi_authors']);
                        $eventData['properties']['ep_multi_authors'] = $serendipity['POST']['properties']['multi_authors'];
                    } else {
                        $eventData['properties']['ep_multi_authors'] = array();
                    }

                    if (isset($eventData['properties']['ep_access_users'])) {
                        $access_users = explode(';', $eventData['properties']['ep_access_users']);
                    } elseif (isset($serendipity['POST']['properties']['access_users'])) {
                        $access_users = $serendipity['POST']['properties']['access_users'];
                    } else {
                        $access_users = array();
                    }

                    if (isset($eventData['properties']['ep_entrypassword'])) {
                        $password = $eventData['properties']['ep_entrypassword'];
                    } elseif (isset($serendipity['POST']['properties']['entrypassword'])) {
                        $password = $serendipity['POST']['properties']['entrypassword'];
                    } else {
                        $password = '';
                    }

?>
            <fieldset id="edit_entry_entryproperties" class="entryproperties_entryproperties">
                <span class="wrap_legend"><legend><?php echo PLUGIN_EVENT_ENTRYPROPERTIES_TITLE; ?></legend></span>
                <div class="jump_to_edit_entry_submit ep_entry_submit">
                    <a href="#top" class="x-button_link x-button_up" title="<?php echo UP; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">
                          <path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                    </a>
                </div>
<?php

                    $elements = explode(',', $this->get_config('sequence', 'sticky,spacer,author,password,frontpage,hiderss,access,markup,groups,authors,multi_authors'));
                    foreach($elements AS $element) {
                        $this->showBackend($element, $eventData, $is_sticky, $no_frontpage, $hiderss, $access_values, $access, $password, $use_groups, $access_groups, $use_users, $access_users);
                    }
?>
            </fieldset>
<?php // no additional newline since last in row
                    break;

                case 'css_backend':
                    $eventData .= '

/* entryproperties plugin backend start */

#edit_entry_freetags div,
#edit_entry_custompermalinks div,
.entryproperties_customfields {
    margin-right: 2%;
}
#ep_spacer label {
    opacity: .3;
}
#edit_entry_entryproperties legend {
    margin-top: 1em;
    margin-bottom: 1em;
}
#ep_customfields legend {
    margin-bottom: 0;
}
#ep_customfields .msg_hint {
    margin: .25em 0 0 0;
}
#edit_entry_entryproperties .ep_entry_submit {
    top: initial;
    margin-top: -2.7em; /* without core #adv_opts background: -1.5em; */
    margin-right: auto;
}
#maintenance_buildcache {
    display: inline-table;
    width: 100%;
}
#ep_cacheinfo {
    margin-bottom: 1em;
}

/* desktop */
@media only screen and (min-width: 768px) {
    #ep_sticky,
    #ep_spacer,
    #ep_gather,
    #ep_frontpage,
    #ep_hiderss,
    #ep_access_list,
    #ep_access_pw,
    #ep_access_groups,
    #ep_access_users,
    #ep_access_author,
    #ep_access_multiauthors,
    #ep_markup {
        float: left;
        margin-right: 2%;
        width: 48%;
    }
    #ep_access_list {
        margin-top: 0.75em;
    }
    #ep_access_pw input {
        width: 64%;
    }
    #edit_entry_entryproperties .ep_entry_submit {
        margin-right: 1.2em;
    }
    #sequence {
        float: right;
        margin-right: inherit;
    }
    #ep_cacheinfo {
        margin-bottom: 1.5em;
    }
}
/* mobile */
@media only screen and (max-width: 768px) {
    #ep_sticky,
    #ep_spacer,
    #ep_gather,
    #ep_frontpage,
    #ep_hiderss,
    #ep_access_list,
    #ep_access_pw,
    #ep_access_groups,
    #ep_access_users,
    #ep_access_author,
    #ep_markup {
        float: unset;
        width: 100%;
    }
    #ep_spacer {
        visibility: hidden;
        display: none;
    }
}

/* entryproperties plugin backend end */

';
                    break;

                case 'backend_maintenance':
                    if ($is_cache && $serendipity['serendipityUserlevel'] >= USERLEVEL_ADMIN) {
?>

    <section id="maintenance_buildcache" class="quick_list">
        <h3><?=PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE?></h3>
        <a class="button_link build_cache" href="?serendipity[adminModule]=event_display&amp;serendipity[adminAction]=buildcache" data-delmsg="<?=PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT?>"><?=PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE?> (EP)</a>
        <button class="toggle_info button_link" type="button" data-href="#ep_cacheinfo"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?=MORE?></span></button>
        <div id="ep_cacheinfo" class="comment_status additional_info">
            <?=sprintf(PLUGIN_EVENT_ENTRYPROPERTY_BUILDCACHE_DESC, CONFIGURATION, INSTALL_CAT_SETTINGS)?>
        </div>
    </section>
<?php
                    }
                    break;

                case 'backend_import_entry':
                    //TODO: (ph) Maybe handle caching?
                    if (is_array($addData) && !$addData['nl2br']){
                        $props = array();
                        $props['no_nl2br'] = 'true';
                        $this->addProperties($props, $eventData);
                    }
                    break;

                case 'backend_sidebar_entries_event_display_buildcache':
                    if ($is_cache) {
                        $per_fetch = 25;
                        $page = $serendipity['GET']['page'] ?? 1;
                        $from = (($page-1)*$per_fetch);
                        $to   = ($page*$per_fetch);
                        printf('<h2>' . PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNO, $from, $to);
                        $entries = serendipity_fetchEntries(
                            null,
                            true,
                            $per_fetch,
                            false,
                            false,
                            'timestamp DESC',
                            '',
                            true
                        );

                        $total = serendipity_getTotalEntries();
                        printf(PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_TOTAL . "</h2>\n", $total);

                        if (is_array($entries)) {
                            echo '<ul class="plainList">';
                            foreach($entries AS $idx => $entry) {
                                printf('<li>' . PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_BUILDING, $entry['id'], htmlspecialchars($entry['title']));
                                $this->updateCache($entry);
                                echo ' ' . PLUGIN_EVENT_ENTRYPROPERTIES_CACHED . '</li>';
                            }
                            echo '</ul>';
                        }

                        if ($to < $total) {
                            if ($auto_cache) {
                                sleep(3);
?>
                        <script>
                            if (window.setTimeout(function() { confirm("<?php echo htmlspecialchars(PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT); ?>"); }, 1)) {
                                location.href = "?serendipity[adminModule]=event_display&serendipity[adminAction]=buildcache&serendipity[page]=<?php echo ($page+1); ?>";
                            } else {
                                alert("<?php echo htmlspecialchars(PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED); ?>");
                            }
                        </script>
<?php
                            } else {
?>
                        <script>
                            if (confirm("<?php echo htmlspecialchars(PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_FETCHNEXT); ?>")) {
                                location.href = "?serendipity[adminModule]=event_display&serendipity[adminAction]=buildcache&serendipity[page]=<?php echo ($page+1); ?>";
                            } else {
                                alert("<?php echo htmlspecialchars(PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_ABORTED); ?>");
                            }
                        </script>
<?php
                            }
                        } else {
                            echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span>' . PLUGIN_EVENT_ENTRYPROPERTIES_CACHE_DONE . '</span>';
                        }
                    }
                    break;

                case 'backend_cache_entries':
                    if (!$is_cache) {
                        return true;
                    }

                    $entries = serendipity_fetchEntries(
                        null,
                        true,
                        $serendipity['fetchLimit'],
                        false,
                        false,
                        'timestamp DESC',
                        '',
                        true
                    );

                    if (is_array($entries) && !empty($entries)) {
                        foreach($entries AS $idx => $entry) {
                            $this->updateCache($entry);
                        }
                    }
                    break;

                case 'backend_cache_purge':
                    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE property LIKE 'ep_cache_%'");
                    break;

                case 'backend_entry_presave':
                    if (isset($serendipity['POST']['properties']) && is_array($serendipity['POST']['properties'])) {
                        $this->applyProperties($serendipity['POST']['properties']);
                    }
                    break;

                case 'backend_publish':
                case 'backend_save':
                    if (!isset($eventData['id'])) {
                        return true;
                    }

                    if (!empty($serendipity['POST']['change_author']) && $serendipity['POST']['change_author'] != $eventData['id']) {
                        // Check again if the POSTed value is an author that the current user has "access" to.
                        $avail_users =& $this->getValidAuthors();
                        $new_authorid = (int)$serendipity['POST']['change_author'];
                        foreach($avail_users AS $user) {
                            if ($new_authorid == $user['authorid']) {
                                serendipity_db_query("UPDATE {$serendipity['dbPrefix']}entries SET authorid = " . $new_authorid . " WHERE id = " . (int)$eventData['id']);
                            }
                        }
                    }

                    if ($is_cache) {
                        // Previous calls to frontend_display used the "no_scramble" attribute to not tinker with the data.
                        // We now need to call those plugins that have not yet operated before.
                        $to_addData = array('from' => 'entryproperties:' . $event);
                        serendipity_plugin_api::hook_event('frontend_display_cache', $eventData, $to_addData);
                        $serendipity['POST']['properties']['cache_body']     = $eventData['body'];
                        $serendipity['POST']['properties']['cache_extended'] = $eventData['extended'];
                    }

                    if (isset($serendipity['POST']['properties']['access_groups']) && is_array($serendipity['POST']['properties']['access_groups']) && $serendipity['POST']['properties']['access'] != 'member') {
                        unset($serendipity['POST']['properties']['access_groups']);
                    }

                    if (isset($serendipity['POST']['properties']['access_users']) && is_array($serendipity['POST']['properties']['access_users']) && $serendipity['POST']['properties']['access'] != 'member') {
                        unset($serendipity['POST']['properties']['access_users']);
                    }

                    $this->addProperties($serendipity['POST']['properties'], $eventData);
                    break;

                case 'frontend_configure':
                    if (isset($serendipity['POST']['id']) && empty($serendipity['GET']['id'])) {
                        $serendipity['GET']['id']      = &$serendipity['POST']['id'];
                        $serendipity['GET']['subpage'] = &$serendipity['POST']['subpage'];
                    }
                    break;

                case 'frontend_entryproperties':
                    $and = $this->returnQueryCondition($is_cache);
                    $q   = "SELECT entryid, property, value FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid IN (" . implode(', ', array_keys($addData)) . ") $and";

                    $properties = serendipity_db_query($q);
                    if (!is_array($properties)) {
                        return true;
                    }

                    foreach($properties AS $idx => $row) {
                        if ($row['property'] == "ep_multi_authors") {
                            $tmp = explode(";", $row['value']);
                            $counter = 0;
                            unset($eventData[$addData[$row['entryid']]]['properties'][$row['property']]);
                            foreach($tmp AS $key => $value) {
                                if (empty($value)) continue;
                                $tmp_author_array = serendipity_fetchAuthor($value);
                                $eventData[$addData[$row['entryid']]]['properties'][$row['property']][$counter]['author_id'] = $value;
                                $eventData[$addData[$row['entryid']]]['properties'][$row['property']][$counter]['author_name'] = $tmp_author_array[0]['realname'];
                                $eventData[$addData[$row['entryid']]]['properties'][$row['property']][$counter]['author_url'] = serendipity_authorURL($tmp_author_array[0]);
                                $counter++;
                            }
                        } else {
                            $eventData[$addData[$row['entryid']]]['properties'][$row['property']] = $row['value'];
                        }
                    }
                    break;

                case 'entry_display':
                    // PH: This is done after Garvins suggestion to patch-up $eventData in case an entry
                    //     is in the process of being created. This must be done for the extended properties
                    //     to be applied in the preview.

                    if (isset($serendipity['GET']['id']) && isset($eventData[0]['properties']['ep_entrypassword'])) {

                        if (isset($_SESSION['entrypassword_unlocked']) && $_SESSION['entrypassword_unlocked'][$serendipity['GET']['id']] == hash('xxh128', $eventData[0]['properties']['ep_entrypassword'])
                        || isset($serendipity['POST']['entrypassword']) && $eventData[0]['properties']['ep_entrypassword'] == $serendipity['POST']['entrypassword'])
                        {
                            // Do not show login form again, once we have first enabled it.
                            $_SESSION['entrypassword_unlocked'][$serendipity['GET']['id']] = hash('xxh128', $eventData[0]['properties']['ep_entrypassword']);
                        } else {
                            // Adding eventData makes no real sense for excluding eventData items like in 'entries_footer',
                            // apart from the preview, the entries list and 'external_plugin' hook which all play after,
                            // since entryproperties is the last plugin in the row!
                            if (is_array($eventData)) {
                                $eventData['clean_page'] = true; // This is important to not display an entry list!
                            } else {
                                $eventData = array('clean_page' => true);
                            }
                            $this->showPasswordForm = true;
                        }
                    }

                    if ($addData['preview'] && isset($serendipity['POST']['properties']) && is_array($serendipity['POST']['properties']) && count($serendipity['POST']['properties']) > 0){
                        $parr = array();
                        $supported_properties = serendipity_event_entryproperties::getSupportedProperties();
                        foreach($supported_properties AS $prop_key) {
                            if (isset($serendipity['POST']['properties'][$prop_key]))
                                $eventData[0]['properties']['ep_' . $prop_key] = $serendipity['POST']['properties'][$prop_key];
                        }
                    }
                    break;

                case 'entries_header':
                    if ($this->showPasswordForm) {
                        echo $this->showPasswordform();
                    }
                    break;

                case 'frontend_fetchentries':
                case 'frontend_fetchentry':
                    $joins = array();
                    $conds = array();
                    if (!$ext_joins) {
                        return true;
                    }
                    if (isset($_SESSION['serendipityAuthedUser']) && $_SESSION['serendipityAuthedUser'] === true) {
                        $conds[] = "
                        (
                            ep_access.property IS NULL OR ep_access.value = 'member' OR ep_access.value = 'public' OR ( ep_access.value = 'private' AND e.authorid = " . (int)$serendipity['authorid'] . " )
                        ) ";

                        if ($use_groups) {
                            $mygroups  = serendipity_checkPermission(returnMyGroups: true);
                            $groupcond = array();
                            foreach((array)$mygroups AS $mygroup) {
                                $groupcond[] .= "ep_access_groups.value LIKE '%;$mygroup;%'";
                            }
                            if (count($groupcond) > 0) {
                                $conds[] = " (ep_access_groups.property IS NULL OR (ep_access.value = 'member' AND (" . implode(' OR ', $groupcond) . ")))";
                            }
                        }

                        if ($use_users) {
                            $conds[] = " (ep_access_users.property IS NULL OR (ep_access.value = 'member' AND (ep_access_users.value LIKE '%;" . (int)$serendipity['authorid'] . ";%' OR e.authorid = " . (int)$serendipity['authorid'] . "))) ";
                        }
                    } else {
                        $conds[] = " (ep_access.property IS NULL OR ep_access.value = 'public')";
                    }

                    // only DO this on article overview / Frontpage - NOT for author, category, or free-tagged entries, any Backend related, or archives views and for searched entries.
                    if (!isset($serendipity['GET']['viewAuthor'])
                     && !isset($serendipity['GET']['category'])
                     && !isset($serendipity['plugin_vars']['tag'])
                     && !isset($serendipity['GET']['adminModule'])
                     && (!isset($serendipity['GET']['action']) || $serendipity['GET']['action'] != 'read')
                     && $event == 'frontend_fetchentries' && $addData['source'] != 'search') {
                        $conds[] = "
                        ( ep_no_frontpage.property IS NULL OR ep_no_frontpage.value != 'true' )\n";
                        $joins[] = "\n                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_no_frontpage
                           ON (e.id = ep_no_frontpage.entryid AND ep_no_frontpage.property = 'ep_no_frontpage')";
                    }

                    if (count($conds) > 0) {
                        $cond = implode("\n                    AND ", $conds);
                        if (empty($eventData['and'])) {
                            $eventData['and'] = " WHERE $cond ";
                        } else {
                            $eventData['and'] .= "\n                    AND $cond ";
                        }
                    }

                    $conds = array();
                    if ($is_cache && (!isset($addData['noCache']) || !$addData['noCache'])) {
                        $conds[] = 'ep_cache_extended.value AS ep_cache_extended,';
                        $conds[] = 'ep_cache_body.value     AS ep_cache_body,';
                    }

                    if ((!isset($addData['noSticky']) || $addData['noSticky'] !== true) && !isset($serendipity['skipSticky'])) {
                        $conds[] = '                    ep_sticky.value AS orderkey'; // is the last $cond addkey in order (except in serendipity_searchEntries(), but we have a comma conditioning there)
                        $stickey = true; // sticky key case true call
                    } else {
                        $conds[] = '                    e.isdraft AS orderkey'; // Ditto
                        $stickey = false; // include sort sticky key case Null or false, but in special for draft=true calls. Also the default orderkey ASCending sort state.
                    }

                    $cond = implode("\n", $conds);
                    if (empty($eventData['addkey'])) {
                        $eventData['addkey'] = $cond;
                    } else {
                        $eventData['addkey'] .= $cond;
                    }

                    // Fix putting sticky entry on the last page in postgreSQL setups. S9y 0.8.5 bugfix in 2005 by Nate Johnston.
                    // BUT showing drafts needs ASC!! while no-draft works well with it too!! We need to (re-)check this for postgres and the if sticky thing again, though.
                    if ($stickey) {
                        if ($serendipity['dbType'] == 'postgres' || $serendipity['dbType'] == 'pdo-postgres') {
                            // PostgreSQL is a bit weird here. Empty columns with NULL or "" content for
                            // orderkey would get sorted on top when using DESC, and only after those
                            // the "true" content would be inserted. Thus we order ASC in postgreSQL,
                            // and silently wonder. Thanks to Nate Johnston for working this out!
                            $cond = 'orderkey ASC';
                        } else {
                            $cond = 'orderkey DESC';
                        }
                    } else {
                        $cond = 'orderkey ASC';
                    }

                    if (empty($eventData['orderby'])) {
                        $eventData['orderby'] = $cond;
                    } else {
                        $eventData['orderby'] = $cond . ', ' . $eventData['orderby'];
                    }

                    if ($is_cache && (!isset($addData['noCache']) || !$addData['noCache'])) {
                        $joins[] = "                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_cache_extended
                           ON (e.id = ep_cache_extended.entryid AND ep_cache_extended.property = 'ep_cache_extended')";
                        $joins[] = "                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_cache_body
                           ON (e.id = ep_cache_body.entryid AND ep_cache_body.property = 'ep_cache_body')";
                    }
                    $joins[] = "\n                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_access
                           ON (e.id = ep_access.entryid AND ep_access.property = 'ep_access')";
                    if ($use_groups) {
                        $joins[] = "                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_access_groups
                           ON (e.id = ep_access_groups.entryid AND ep_access_groups.property = 'ep_access_groups')";
                    }

                    if ($use_users) {
                        $joins[] = "                    LEFT OUTER JOIN {$serendipity['dbPrefix']}entryproperties ep_access_users
                           ON (e.id = ep_access_users.entryid AND ep_access_users.property = 'ep_access_users')";
                    }
                    // $serendipity['skipSticky'] is not documented anywhere, is it for debugging?
                    if ((!isset($addData['noSticky']) || $addData['noSticky'] !== true) && !isset($serendipity['skipSticky'])) {
                        $joins[] = "                    LEFT JOIN {$serendipity['dbPrefix']}entryproperties ep_sticky
                           ON (e.id = ep_sticky.entryid AND ep_sticky.property = 'ep_is_sticky')";
                    }

                    $join = implode("\n", $joins);
                    if (empty($eventData['joins'])) {
                        $eventData['joins'] = $join;
                    } else {
                        $eventData['joins'] .= $join;
                    }
                    break;

                case 'frontend_entries_rss':
                    if (is_array($eventData)) {
                        foreach($eventData AS $idx => $entry) {
                            if (isset($entry['properties']) && is_array($entry['properties']) && isset($entry['properties']['ep_hiderss']) && $entry['properties']['ep_hiderss']) {
                                unset($eventData[$idx]['body']);
                                unset($eventData[$idx]['extended']);
                                unset($eventData[$idx]['exflag']);
                            }
                        }
                    }
                    break;

                case 'backend_plugins_new_instance':
                    // This hook will always push the entryproperties plugin as last in queue.
                    // Happens always when a new plugin is added.
                    // This is important because of its caching mechanism!

                    // Fetch maximum sort_order value. This will be the new value of our current plugin.
                    $q  = "SELECT MAX(sort_order) AS sort_order_max FROM {$serendipity['dbPrefix']}plugins WHERE placement = '" . $addData['default_placement'] . "'";
                    $rs = serendipity_db_query($q, true, 'num');

                    // Fetch current sort_order of current plugin.
                    $q   = "SELECT sort_order FROM {$serendipity['dbPrefix']}plugins WHERE name = '" . $this->instance . "'";
                    $cur = serendipity_db_query($q, true, 'num');

                    if ($addData['default_placement'] == 'event') {
                        // Decrease sort_order of all plugins after current plugin by one.
                        $q = "UPDATE {$serendipity['dbPrefix']}plugins SET sort_order = sort_order - 1 WHERE placement = '" . $addData['default_placement'] . "' AND sort_order > " . intval($cur[0]);
                        serendipity_db_query($q);

                        // Set current plugin as last plugin in queue.
                        $q = "UPDATE {$serendipity['dbPrefix']}plugins SET sort_order = " . intval($rs[0]) . " WHERE name = '" . $this->instance . "'";
                        serendipity_db_query($q);
                    }
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

/* vim: set sts=4 ts=4 expandtab : */
?>