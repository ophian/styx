<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (defined('S9Y_FRAMEWORK_PLUGINS_ADMIN')) {
    return;
}
@define('S9Y_FRAMEWORK_PLUGINS_ADMIN', true);

/**
 * Returns the name of a usergroup.
 *
 * If a special constant is defined for the name of the group, the name has been
 * internationalized. This applies to the Admin/Chief Editor/Editor group names,
 * which are different for each language. If such a constant is not present,
 * the simple name of the group will be returned.
 *
 * Args:
 *      - name of the group
 * Returns:
 *      - realname of the group
 * @access public
 */
function serendipity_groupname(string $group) : string {
    if (defined('PLUGIN_GROUP_' . $group)) {
        return constant('PLUGIN_GROUP_' . $group);
    } else {
        return $group;
    }
}

/**
 * Sort the pluginlist by case-insensitive string functions
 *
 * Args:
 *      - Compared Plugin #1
 *      - Compared Plugin #2
 * Returns:
 *      - Return code for array comparison
 * @access public
 */
function serendipity_pluginListSort(iterable $x, iterable $y) : int {
    $xd = $x['description'] ?? '';
    $yd = $y['description'] ?? '';
    return strnatcasecmp($x['name'] . ' - ' . $xd, $y['name'] . ' - ' . $yd);
}

/**
 * Show the list of plugins
 *
 * Shows a HTML list of all installed plugins, complete with config/delete/sort order options
 *
 * Args:
 *      - Indicates if event plugins (TRUE) or sidebar plugins (FALSE) shall be shown
 *      - sidebars Array OR NULL
 * Returns:
 *      - String result of serendipity_smarty_showTemplate()
 * @access public
 */
function show_plugins(bool $event_only = false, ?iterable $sidebars = null) : string {
    global $serendipity;
    static $users = array(); // run once only & keep

    $sql_filter = '';
    if (is_array($sidebars)) {
        foreach($sidebars AS $sidebar) {
            $up = strtoupper($sidebar);
            if ($sidebar == 'hide') {
                $opts[$sidebar] = HIDDEN;
            } elseif (defined('SIDEBAR_' . $up)) {
                $opts[$sidebar] = constant('SIDEBAR_' . $up);
            } elseif (defined($up)) {
                $opts[$sidebar] = constant($up);
            } else {
                $opts[$sidebar] = $up;
            }
            $sql_filter .= "AND placement != '" . serendipity_db_escape_string($sidebar) . "' ";
        }
    }

    if (!$event_only) {
        $sql   = "SELECT * from {$serendipity['dbPrefix']}plugins
                   WHERE placement != 'event'
                     AND placement != 'eventh'
                         " . $sql_filter;
        $invisible_plugins = serendipity_db_query($sql);
        if (is_array($invisible_plugins)) {
            $sidebars[]   = 'NONE';
            $opts['NONE'] = NONE;
        }
    }

    $opts['event'] = PLUGIN_ACTIVE;
    $opts['eventh'] = PLUGIN_INACTIVE;

    $data = array(); // init smarty assigned data

    $data['event_only'] = $event_only;
    if (!$event_only) {
        $data['is_first'] = true;
    }

    $data['serendipity_setFormToken'] = serendipity_setFormToken();
    $data['serendipity_setFormTokenUrl'] = serendipity_setFormToken('url');

    // Block display the plugins per placement location
    if ($event_only) {
        $plugin_placements = array('event', 'eventh');
    } else {
        $plugin_placements = $sidebars;
    }
    $data['plugin_placements'] = $plugin_placements;

    if (empty($users)) {
        $users = serendipity_fetchUsers('', 'hidden');
    }
    $data['users'] = $users;

    $i = 0;
    foreach($plugin_placements AS $plugin_placement) {
        if (!$event_only && $plugin_placement == 'NONE') {
            $is_invisible = true;
        } else {
            $is_invisible = false;
        }
        $data['placement'][$plugin_placement]['ptitle'] = $ptitle = $opts[$plugin_placement];
        $data['placement'][$plugin_placement]['pid'] = $pid = $plugin_placement;

        if ($is_invisible) {
            $plugins = $invisible_plugins;
        } else {
            $plugins = serendipity_plugin_api::enum_plugins($plugin_placement);
        }

        if (!is_array($plugins)) {
            continue;
        }

        $sort_idx = 0;
        foreach($plugins AS $plugin_data) {
            $i++;
            $plugin  =& serendipity_plugin_api::load_plugin($plugin_data['name'], $plugin_data['authorid']);
            $key     = urlencode($plugin_data['name']);
            #$css_key = 's9ypid' . str_replace('%', '-', $key);
            $crc32   = hash('xxh32', (string) random_int(0, 0x3fff));
            $is_plugin_owner    = ($plugin_data['authorid'] == $serendipity['authorid'] || serendipity_checkPermission('adminPluginsMaintainOthers'));
            $is_plugin_editable = ($is_plugin_owner || $plugin_data['authorid'] == '0');
            $cname = explode(':', $plugin_data['name']);

            if (!is_object($plugin)) {
                $name = $title = ERROR . '!';
                $desc          = ERROR . ': ' . $plugin_data['name'];
                $can_configure = false;
            } else {
                // query for its name, description and configuration data
                $bag = new serendipity_property_bag;
                $plugin->introspect($bag);

                $name  = htmlspecialchars($bag->get('name') ?? '');
                $kname = str_replace(array('serendipity_event_', 'serendipity_plugin_'), '', ($cname[0] ?? ''));

                $desc  = '<details id="details_'.$crc32.'" class="plugin_data">';
                $desc .= '<summary><var class="perm_name" title="'.$cname[0].'">' . $kname . ' <span class="icon-info-circled" aria-hidden="true"></span></var></summary>';
                $desc .= '<div class="plugin_desc clearfix">' . htmlspecialchars($bag->get('description') ?? '') . '</div>';
                $desc .= '<span class="block_level">' . VERSION  . ': ' . $bag->get('version') . '</span>';
                $desc .= '</details>';

                $title = serendipity_plugin_api::get_plugin_title($plugin, '[' . $name . ']');

                if ($bag->is_set('configuration') && ($plugin->protected === FALSE || $plugin_data['authorid'] == '0' || $plugin_data['authorid'] == $serendipity['authorid'] || serendipity_checkPermission('adminPluginsMaintainOthers'))) {
                    $can_configure = true;
                } else {
                    $can_configure = false;
                }
            }

            if ($opts === null) {
                $opts = array(
                    'left'  => LEFT,
                    'right' => RIGHT,
                    'hide'  => HIDDEN
                );
            }

            $event_opts = array(
                            'event'  => PLUGIN_ACTIVE,
                            'eventh' => PLUGIN_INACTIVE,
                        );

            if ($event_only) {
                $gopts = $event_opts;
            } else {
                $gopts = $opts;
            }

            $data['placement'][$plugin_placement]['plugin_data'][$i]['sort_idx'] = $sort_idx;
            #$data['placement'][$plugin_placement]['plugin_data'][$i]['css_key'] = $css_key; // previously used very long key for unique items
            $data['placement'][$plugin_placement]['plugin_data'][$i]['is_plugin_editable'] = $is_plugin_editable;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['is_plugin_owner'] = $is_plugin_owner;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['name'] = $plugin_data['name'];
            $data['placement'][$plugin_placement]['plugin_data'][$i]['authorid'] = $plugin_data['authorid'];
            $data['placement'][$plugin_placement]['plugin_data'][$i]['can_configure'] = $can_configure;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['key'] = $key;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['css_key'] = $crc32;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['title'] = $title;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['desc'] = $desc;
            $data['placement'][$plugin_placement]['plugin_data'][$i]['placement'] = $plugin_data['placement'];
            $data['placement'][$plugin_placement]['plugin_data'][$i]['gopts'] = $gopts;
            $sort_idx++;
        }

    }

    $data['total'] = $i;
    return serendipity_smarty_showTemplate('admin/show_plugins.fnc.tpl', $data);
}

/**
 * Show the plugin / theme configuration
 *
 * Args:
 *      - A plugin object
 *      - The plugins property bag array
 *      - The name of the plugin
 *      - The description of the plugin
 *      - The property bag 'configuration' array, holding the array of config items.
 *      - Shows the surrounding HTML table?
 *      - Shows the FORM submit button?
 *      - Shows a plugin's "example" method output?
 *      - Spawn a plugins' configuration WYSIWYG items?
 *      - The array index name of POSTed values ($serendipity['POST'][xxx]) which is either 'template' or 'plugin',
 *                       but since used for BACK buttoning in plugin_config.tpl too, the event plugin categorytemplates
 *                       uses 'categorytemplate'.
 *      - An array that groups certain config keys
 * Returns:
 *      - The configuration HTML string result of serendipity_smarty_showTemplate() OR false
 * @access public
 */
function serendipity_plugin_config(object &$plugin, object|iterable &$bag, string &$name, string &$desc, iterable &$config_names, bool $showTable = true, bool $showSubmit = true, bool $showExample = true, bool $spawnNuggets = true, string $postKey = 'plugin', ?iterable $config_groups = NULL) : string|false {
    global $serendipity;

    if (empty($config_names)) {
        return false;
    }

    $tfile = '/admin/plugin_config_item.tpl';

    $data = array();

    if ($showSubmit && $postKey != 'plugin') {
        $data['showSubmit_head'] = true;
    }

    $_postKey = ($postKey == 'categorytemplate') ? 'template' : $postKey;

    if ($showTable) {
        $data['showTable'] = true;
    }

    $elcount = 0;
    $htmlnugget = array();
    $plugin_options = array();

    $data['desc']         = ($_postKey != 'template') ? $desc : ''; // Add for "plugin_config.tpl" to set a plugin head "plugin_togglegroup simple" [info] fieldset
    $data['config_names'] = $config_names;

    foreach($config_names AS $config_item) {
        $elcount++;
        $cbag = new serendipity_property_bag;
        $plugin->introspect_config_item($config_item, $cbag);

        $data['cname']  = $cname = htmlspecialchars($cbag->get('name') ?? ''); // might be a non set, so check string type
        $data['cdesc']  = $cdesc = htmlspecialchars($cbag->get('description') ?? ''); // Ditto
        $value          = $plugin->get_config($config_item, 'unset');
        $lang_direction = htmlspecialchars($cbag->get('lang_direction') ?? ''); // Ditto

        if (empty($lang_direction)) {
            $lang_direction = LANG_DIRECTION;
        }
        $data['lang_direction'] = $lang_direction;

        /* Apparently no value was set for this config item */
        if ($value === 'unset') {
            /* Try to get the default value for the config item */
            $value = $cbag->get('default');

            /* Still, we don't have a value, try and get (bool)false - from an old plugin */
            if ($value === '') {
                $value = $plugin->get_config($config_item, false, true);
            }
        }

        if (isset($_POST['serendipity'][$_postKey][$config_item])) {
            if (is_array($_POST['serendipity'][$_postKey][$config_item])) {
                $hvalue = $_POST['serendipity'][$_postKey][$config_item];
                array_walk($hvalue, 'htmlspecialchars');
                if (is_array($hvalue)) {
                    $hvalue = $hvalue[0]; // use the 0 key as explicite string type for trim() !!
                } else {
                    $hvalue = $hvalue;
                }
            } else {
                $hvalue = htmlspecialchars($_POST['serendipity'][$_postKey][$config_item]);
            }
        } else {
            $hvalue = is_string($value) ? htmlspecialchars($value) : $value;
        }

        $radio      = array();
        $select     = array();
        $per_row    = null;
        $text_rows  = null;
        $input_type = null;

        $data['is_multi_select'] = $is_multi_select = false;
        $data['ctype']           = $ctype = $cbag->get('type');

        $data['elcount']     = $elcount;
        $data['hvalue']      = is_string($hvalue) ? trim($hvalue) : $hvalue;
        $data['postKey']     = $_postKey;
        $data['config_item'] = $config_item;
        // implement double tab (8 space) indent for configuration group items for the plugin_config_item.tpl file
        $data['group_ident'] = (is_array($config_groups) && in_array($config_item, array_merge(...array_values($config_groups)), true)) ? true : false;

        $assign_plugin_config = function($data) use (&$plugin_options, $tfile, $config_item) {
            $plugin_options[$config_item] = array(
                'config' => serendipity_smarty_showTemplate($tfile, $data),
                'ctype'  => $data['ctype']
            );
        };

        switch ($ctype) {
            case 'separator':
            case 'suboption':
                $assign_plugin_config($data);
                break;

            case 'multiselect':
                $data['is_multi_select'] = $is_multi_select = true;

            case 'select':
                $data['ctype'] = 'select';
                if (is_array($hvalue)) {
                    $selected_options = $hvalue;
                } elseif ($is_multi_select && is_string($hvalue)) {
                    $selected_options = explode('^', $hvalue);
                } else {
                    $selected_options = array($hvalue => $hvalue);
                }

                $data['selected_options'] = $selected_options;
                $data['pre_selected']     = $pre_selected = (array)$cbag->get('select_preselected');
                $data['select_size']      = $select_size  = $cbag->get('select_size');
                $data['select']           = $select       = $cbag->get('select_values');

                $assign_plugin_config($data);
                break;

            case 'tristate':
                $data['ctype'] = 'tristate';
                $per_row = 3;
                $radio['value'][] = 'default';
                $radio['desc'][]  = USE_DEFAULT;

            case 'boolean':
                $data['ctype'] = 'boolean';
                $radio['value'][] = 'true';
                $radio['desc'][]  = YES;

                $radio['value'][] = 'false';
                $radio['desc'][]  = NO;

            case 'radio':
                $data['ctype'] = 'radio';
                if (!count($radio) > 0) {
                    $radio = $cbag->get('radio');
                }

                if (empty($per_row)) {
                    $per_row = $cbag->get('radio_per_row');
                    if (empty($per_row)) {
                        $per_row = 2;
                    }
                }
                $data['per_row'] = $per_row;
                $data['radio_button'] = array();
                $counter = 0;
                foreach($radio['value'] AS $radio_index => $radio_value) {
                    $id = htmlspecialchars($config_item . $radio_value);
                    $counter++;
                    $checked = '';

                    if ($radio_value == 'true' && ($hvalue === true || $hvalue === '1' || $hvalue === 'true')) {
                        $checked = ' checked';
                    } elseif ($radio_value == 'false' && ($hvalue === false || $hvalue === '' || $hvalue === 'false')) {
                        $checked = ' checked';
                    } elseif (is_numeric($hvalue) ? $radio_value == $hvalue : $radio_value === $hvalue) { // $hvalue may be a numeric string like 2, 3 or 4
                        $checked = ' checked';
                    }
                    $data['radio_button'][$radio_index]['id'] = $id;
                    $data['radio_button'][$radio_index]['checked'] = $checked;
                    $data['radio_button'][$radio_index]['counter'] = $counter;
                    $data['radio_button'][$radio_index]['value'] = $radio_value;
                    $data['radio_button'][$radio_index]['index'] = htmlspecialchars($radio['desc'][$radio_index]);
                }

                $assign_plugin_config($data);
                break;

            case 'string':
                $data['ctype'] = 'string';
                if (empty($input_type)) {
                    $input_type = $cbag->get('input_type');
                    if (empty($input_type)) {
                        $input_type = 'text';
                    }
                }
                $data['input_type'] = $input_type;
                $assign_plugin_config($data);
                break;

            case 'html':
                $data['ctype'] = 'html';
                $data['pdata']['markupeditor'] = $serendipity['pdata']['markupeditor'] ?? null;
                $data['pdata']['markupeditortype'] = $serendipity['pdata']['markupeditortype'] ?? null;
                // no break [PSR-2] - extends text

            case 'text':
                $data['ctype'] = 'text';
                if (empty($text_rows)) {
                    $text_rows = $cbag->get('rows');
                    if (empty($text_rows)) {
                        $text_rows = 20;
                    }
                }
                $data['text_rows'] = $text_rows;
                if ($cbag->get('type') == 'html') {
                    $data['ctype'] = 'html'; // YES! DO it again, since the 'html' ctype case is overridden by 'text' case when a plugin config has both, 'html' and 'text' fields
                    $htmlnugget[] = $elcount;
                    #if (!function_exists('serendipity_emit_htmlarea_code')) { // added by ba8e0e346c137e7677260bf394b57a6a9b7580d8 but it does not say why ... I think this is obsolete now
                    #    @include_once dirname(__FILE__) . '/functions_entries_admin.inc.php';
                    #}
                    // use SpawnMulti false per default (for multi nugget textareas, eg linklist sidebar plugin) - but where do we use jsname though?
                    serendipity_emit_htmlarea_code("nuggets{$elcount}", "nuggets{$elcount}"); // item, jsname ??? TODO: Check this all, if wrong or legacy... mix
                    if ($spawnNuggets && isset($serendipity['wysiwyg']) && $serendipity['wysiwyg'] && count($htmlnugget) > 0) {
                        $data['wysiwyg'] = true;
                    }
                }
                $assign_plugin_config($data);
                break;

            case 'content':
                $data['ctype'] = 'content';
                $data['cbag_default'] = $cbag->get('default');
                $assign_plugin_config($data);
                break;

            case 'custom':
                $data['ctype'] = 'custom';
                $data['cbag_custom'] = $cbag->get('custom');
                $assign_plugin_config($data);
                break;

            case 'color':
                $data['ctype'] = 'color';
                $data['input_type'] = 'color';
                $data['cbag_value'] = $cbag->get('value');
                $assign_plugin_config($data);
                break;

            case 'hidden':
                $data['ctype'] = 'hidden';
                $data['cbag_value'] = $cbag->get('value');
                $assign_plugin_config($data);
                break;

            case 'media':
                if ($value === false) break;
                $data['ctype'] = 'media';
                // Print the HTML to display the popup media selector
                $preview_width = $cbag->get('preview_width');
                if (!$preview_width || $preview_width == '') {
                  $preview_width = '400px';
                }
                $preview_height = $cbag->get('preview_height');
                if (!$preview_height || $preview_height == '') {
                  $preview_height = '100px';
                }
                $data['preview_width']  = $preview_width;
                $data['preview_height'] = $preview_height;
                $data['value'] = $value;
                $data['value_name'] = str_replace('.' . $serendipity['thumbSuffix'], '', pathinfo($value, PATHINFO_FILENAME)); // get pure name w/o Serendipity Thumb suffix for alt and title attributes
                // WebP
                $rpath = serendipity_generate_webpPathURI((string) $value); // the relative document root value filepath
                $data['value_webp'] = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                        ? $rpath
                                        : null; // file exist needs full path to check
                // AVIF
                $rpath = serendipity_generate_webpPathURI((string) $value, 'avif'); // the relative document root value filepath
                $data['value_avif'] = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                        ? $rpath
                                        : null; // file exist needs full path to check
                // compare Variation filesizes for srcsets
                if ($data['value_avif'] != null && $data['value_webp'] != null) {
                    $data['value_avif'] = (filesize(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $data['value_avif']) > filesize(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $data['value_webp']))
                                            ? null
                                            : $data['value_avif'];
                }

                $assign_plugin_config($data);
                break;

            case 'sequence':
                $data['ctype'] = 'sequence';
                // For the drag-n-drop to work, the list must be included in
                // a container (probably an <ol>) that JavaScript can access
                // (easiest by ID), with <li> children that have unique IDs,
                // and handles with ids of 'g'.$li_id.
                // I can't get it to work unless there's a class of
                // pluginmanager_container on the ol, either.
                // The drag-n-drop returns the list of IDs in order.
                $data['sequencejs_output'] = $sequencejs_output = $serendipity['sequencejs_output'] ?? null;
                if (empty($sequencejs_output)) {
                    $serendipity['sequencejs_output'] = true;
                }

                // I want this generic sequence widget to hide the ID, but
                // display a name or description with an optional picture.
                // (This would allow users to identify choices by thumbnail.)
                // Therefore, I need an array with keys 'id', 'display', and
                // 'imgurl' (or similar) to generate each list item.

                // Get the data we need to display the list
                if (!$value) {
                    $value = $eventData['default'] ?? null;
                }
                $data['value'] = $value;
                $data['cname'] = $cname = $cbag->get('name');
                $data['cdesc'] = $cdesc = $cbag->get('description');
                $data['checkable'] = $checkable = $cbag->get('checkable');

                /** Unordered array of values */
                $items = $cbag->get('values');
                if (!is_array($items)) { $items = null; }
                /** Array specifying order to use values in $items */
                $order = null;
                if ($value) {
                    $data['store_order'] = $store_order = $order = explode(',', $value);
                }
                // initialize as array on empty for Smarty template to make empty checkable(s) run
                if (empty($data['store_order'])) $data['store_order'] = array();

                // $items is the list of things to sequence.  It's not in
                // order, and reordering PHP arrays is problematic.  So
                // we keep it unordered, and access its values according
                // to another array (appropriately named $order).
                if (is_array($items)) {
                    // Allow simple value for any sequence item
                    foreach($items AS $key => $item) {
                        if (!is_array($item)) {
                            // Replace this item with an empty array
                            unset($items[$key]);
                            $items[$item] = array();
                        }
                    }

                    // Make sure all the items are in the order list; new items
                    // go on the end (new items could have been added without
                    // this config item being updated)
                    // Also fill out thumbnails and display names
                    foreach($items AS $id => $junk) {
                        if ($order == null) {
                            $order = array($id);
                        } else if (!in_array($id, $order)) {
                            $order[] = $id;
                        }
                        // If there's no defined display name, default to the ID
                        if (!isset($items[$id]['display'])) {
                            $items[$id]['display'] = $id;
                        }
                        // If there's no image, we just won't display anything.
                    }

                    // Make sure all the items to be ordered exist!  Otherwise
                    // we could try to sequence nothing.
                    $filtered = array();
                    foreach($order AS $id) {
                        if (array_key_exists($id, $items)) {
                            $filtered[] = $id;
                        }
                    }
                    $order = $filtered;
                } else {
                    // If there's nothing to sequence, make the order in
                    // which to use them valid, but empty
                    $order = array();
                }

                // Start the row, add one cell for the name and description
                $data['items'] = $items;
                $sort_idx = 0;
                $data['last'] = $last = count($order) - 1;
                foreach($order AS $id) {
                    // Create the variables required to print this item
                    if ($sort_idx > 0) {
                        $swapping = $order;
                        $temp = $swapping[(int)$sort_idx];
                        $swapping[(int)$sort_idx] = $swapping[(int)($sort_idx - 1)];
                        $swapping[(int)($sort_idx - 1)] = $temp;
                        $data['order_id'][$sort_idx]['oneup'] = $oneup = implode(',' , $swapping);
                    }
                    if ($sort_idx < $last) {
                        $swapping = $order;
                        $temp = $swapping[(int)$sort_idx];
                        $swapping[(int)$sort_idx] = $swapping[(int)($sort_idx + 1)];
                        $swapping[(int)($sort_idx + 1)] = $temp;
                        $data['order_id'][$sort_idx]['onedown'] = $onedown = implode(',' , $swapping);
                    }

                    $data['order_id'][$sort_idx]['id'] = $id;
                    $data['order_id'][$sort_idx]['sort_idx'] = $sort_idx;
                    // Print the HTML
                    //
                    // Set the item and its ID
                    // Make a handle with ID 'g$id'
                    // Add the item contents
                    // Luddite submit buttons (please, think of the scriptless!)
                    // Next, please
                    $sort_idx++;
                } // foreach end

                if (!is_array($items) || empty($order)) {
                    // Print the empty message
                    $data['no_sequence'] = NONE;
                }
                // Print the Javascript to drag-n-drop the list
                // Finish the row
                $assign_plugin_config($data);
                break;

            default:
                $data['ctype'] = 'default';
                // Unknown configuration key. Let the plugin handle it.
                $addData = func_get_args();
                $eventData = array(
                    'config_item' => $config_item,
                    'cbag'        => $cbag,
                    'plugin'      => $plugin,
                    'value'       => $value,
                    'bag'         => $bag,
                    'postKey'     => $postKey
                );
                ob_start();
                serendipity_plugin_api::hook_event('backend_pluginconfig_' . $ctype, $eventData, $addData);
                $plugin_options[$config_item]['config'] = ob_get_contents();
                $plugin_options[$config_item]['ctype'] = 'default';
                ob_end_clean();
                break;
        }

    }

    // Extract plugin basename by instance for top right floated toggle group fieldset IDs
    $pbname = isset($plugin->instance) ? explode(':', substr($plugin->instance, strrpos($plugin->instance, '_') + 1)) : [0 => null];
    $data['plugin_basename'] = $pbname[0];
    $data['config_groups']   = $config_groups;
    $data['plugin_options']  = $plugin_options;

    if (is_array($config_groups)) {
        foreach($config_groups AS $config_header => $config_groupkeys) {
            foreach($config_groupkeys AS $config_groupkey) {
                unset($plugin_options[$config_groupkey]);
            }
        }
    }

    $data['plugin_options_ungrouped'] = $plugin_options;

    $data['button_postKey'] = $postKey; // reset the postKey assignment for buttoning

    if ($showSubmit) {
        $data['showSubmit_foot'] = true;
    }

    if ($showExample && method_exists($plugin, 'example') ) {
        $data['showExample'] = true;
        $data['plugin_example'] = $plugin->example();
    }

    if ($spawnNuggets && isset($serendipity['wysiwyg']) && $serendipity['wysiwyg'] && count($htmlnugget) > 0) {
        $data['spawnNuggets'] = true;
        $ev = array('nuggets' => $htmlnugget, 'skip_nuggets' => false);
        serendipity_plugin_api::hook_event('backend_wysiwyg_nuggets', $ev);
        $data['ev'] = $ev;
    }

    return serendipity_smarty_showTemplate('admin/plugin_config.tpl', $data);
}
