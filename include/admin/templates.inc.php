<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('adminTemplates')) {
    return;
}

class template_option
{
    var $config = null;
    var $values = null;
    var $keys   = null;

    function introspect_config_item($item, &$bag)
    {
        foreach($this->config[$item] AS $key => $val) {
            $bag->add($key, $val);
        }
    }

    function get_config($item)
    {
        return $this->values[$item];
    }

    function set_config($name, $value, $implodekey = '^')
    {
        global $serendipity;

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                                    WHERE okey = 't_" . serendipity_db_escape_string($serendipity['template']) . "'
                                      AND name = '" . serendipity_db_escape_string($name) . "'");

        if (isset($this->config[$name]['scope']) && $this->config[$name]['scope'] == 'global') {
            serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                                   WHERE okey = 't_global'
                                     AND name = '" . serendipity_db_escape_string($name) . "'");
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey)
                                       VALUES ('" . serendipity_db_escape_string($name) . "', '" . serendipity_db_escape_string($value) . "', 't_global')");
        } else {
            serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                                   WHERE okey = 't_" . serendipity_db_escape_string($serendipity['template']) . "'
                                     AND name = '" . serendipity_db_escape_string($name) . "'");
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey)
                                       VALUES ('" . serendipity_db_escape_string($name) . "', '" . serendipity_db_escape_string($value) . "', 't_" . serendipity_db_escape_string($serendipity['template']) . "')");
        }
        return true;
    }

    function import(&$config)
    {
        foreach($config AS $key => $item) {
            if (!isset($item['var'])) continue;
            $this->config[$item['var']] = $item;
            $this->keys[$item['var']]   = $item['var'];
        }
    }

}

$data = array();

if ($serendipity['GET']['adminAction'] == 'editConfiguration') {
    $data['adminAction'] = 'editConfiguration';
}

if (($serendipity['GET']['adminAction'] == 'install' || $serendipity['GET']['adminAction'] == 'install-frontend' || $serendipity['GET']['adminAction'] == 'install-backend') && serendipity_checkFormToken()) {
    serendipity_plugin_api::hook_event('backend_templates_fetchtemplate', $serendipity);

    $themeInfo = serendipity_fetchTemplateInfo(serendipity_specialchars($serendipity['GET']['theme']));

    // A separate hook is used post installation, for plugins to possibly perform some actions
    serendipity_plugin_api::hook_event('backend_templates_install', $serendipity['GET']['theme'], $themeInfo);

    if ($serendipity['GET']['adminAction'] == 'install' || $serendipity['GET']['adminAction'] == 'install-frontend') {
        serendipity_set_config_var('template', serendipity_specialchars($serendipity['GET']['theme']));
    }

    if ($serendipity['GET']['adminAction'] == 'install-backend' && $themeInfo['custom_admin_interface'] == YES) {
        serendipity_set_config_var('template_backend', serendipity_specialchars($serendipity['GET']['theme']));
    } else {
        // template_engine was set by default to default, which screws up the fallback chain (to the default-template first)
        // The "Engine" now only applies to FRONTEND themes. Backend themes will always fall back to our default backend theme only, to ensure proper backend operation.
        serendipity_set_config_var('template_engine', null);
        if (isset($themeInfo['engine'])) {
            serendipity_set_config_var('template_engine', $themeInfo['engine']);
        }
    }
    serendipity_set_config_var('last_template_change', time());

    $data['adminAction'] = 'install';
    $data['install_template'] = serendipity_specialchars($serendipity['GET']['theme']);
}

if (@file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template'] .'/layout.php')) {
    $data['deprecated'] = true;
}

$data['cur_template']         = $serendipity['template'];
$data['cur_template_backend'] = $serendipity['template_backend'];
$data['cur_template_info']    = serendipity_fetchTemplateInfo($serendipity['template']);

// NOTE: config.inc.php currently only applies to frontend configuration. Backend configuration is not planned yet, and would preferably use a "config_backend.inc.php" file!
if (isset($data['cur_template_info']['custom_config_engine']) && file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $data['cur_template_info']['custom_config_engine'] . '/config.inc.php')) {
    serendipity_smarty_init();
    $old_template_config_groups = $template_config_groups;
    include_once $serendipity['serendipityPath'] . $serendipity['templatePath'] . $data['cur_template_info']['custom_config_engine'] . '/config.inc.php';
    // in case of theme switch, check to unset config_group array
    if ($serendipity['GET']['adminAction'] == 'install' && $serendipity['GET']['adminModule'] == 'templates') {
        // array diff - but do not do this for bulletproof, as this is the only one which needs them in case of reloads (temporary)
        if ($old_template_config_groups === $template_config_groups && $serendipity['GET']['theme'] != 'bulletproof') {
            $template_config_groups = NULL; // force destroy previous config_group array!
        }
    }
    unset($old_template_config_groups);
} else {
    if ($serendipity['GET']['adminAction'] == 'install' && $serendipity['GET']['adminModule'] == 'templates') {
        #include_once $serendipity['serendipityPath'] . $serendipity['templatePath'] . '/default/config_fallback.inc.php';
        $template_config_groups = NULL;
        $template_config        = NULL;
        $template_loaded_config = NULL;
    }
}

if (is_array($template_config)) {
    serendipity_plugin_api::hook_event('backend_templates_configuration_top', $template_config);
    $data['has_config'] = true;

    if ($serendipity['POST']['adminAction'] == 'configure' && serendipity_checkFormToken()) {
        $storage = new template_option();
        $storage->import($template_config);
        foreach($serendipity['POST']['template'] AS $option => $value) {
            $storage->set_config($option, $value);
        }
        $data['adminAction'] = 'configure';
        $data['save_time'] = sprintf(SETTINGS_SAVED_AT, serendipity_strftime('%H:%M:%S'));
    }

    $data['form_token'] = serendipity_setFormToken();

    include_once S9Y_INCLUDE_PATH . 'include/functions_plugins_admin.inc.php';

    $template_vars =& serendipity_loadThemeOptions($template_config);

    $template_options = new template_option();
    $template_options->import($template_config);
    $template_options->values =& $template_vars;

    $data['configuration'] = serendipity_plugin_config(
        $template_options,
        $template_vars,
        $serendipity['template'],
        $serendipity['template'],
        $template_options->keys,
        true,
        true,
        true,
        true,
        'template',
        $template_config_groups
    );

    serendipity_plugin_api::hook_event('backend_templates_configuration_bottom', $template_config);
} else {
    serendipity_plugin_api::hook_event('backend_templates_configuration_none', $template_config);
}

$i = 0;
$stack = array();
serendipity_plugin_api::hook_event('backend_templates_fetchlist', $stack);
$themes = serendipity_fetchTemplates();
$data['templates'] = array();
$core_templates = ['2k11', '2styx', 'bootstrap4', 'bulletproof', 'clean-blog', 'default', 'default-php', 'next', 'skeleton', 'timeline'];
$data['core_templates'] = array();

foreach($themes AS $theme) {
    $stack[$theme] = serendipity_fetchTemplateInfo($theme);
}
ksort($stack);

foreach($stack AS $theme => $info) {
    $data['templates'][$theme]['info'] = $info;

    foreach(array('', '_backend') AS $backendId) {
        // LOCAL front- and backend themes
        if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . "/preview${backendId}_fullsize.jpg")) {
            if (empty($backendId) && @file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.png')) {
                $png = true;
                if (false === (@filesize($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.png') <= @filesize($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.jpg'))) {
                    $png = false;
                }
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.webp')) {
                    $data['templates'][$theme]['preview_webp'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview.webp';
                }
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.webp')) {
                    $data['templates'][$theme]['fullsize_preview_webp'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.webp';
                }
                // check the normal backend theme for a special backend image fullsize webp file, else the fullsize jpg is used (i.e. current backend is Styx, but default backend needs this set - or vice versa) 
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_backend_fullsize.webp')) {
                    $data['templates'][$theme]["fullsize_backend_preview_webp"] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_backend_fullsize.webp';
                }
                $data['templates'][$theme]['preview'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . ($png ? '/preview.png' : '/preview_fullsize.jpg');
                $data['templates'][$theme]['fullsize_preview'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.jpg';
            } else {
                // check the backend theme for the jpg fallback case URL
                $data['templates'][$theme]["fullsize${backendId}_preview"] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . "/preview${backendId}_fullsize.jpg";
            }
        } // now the REMOTE TEMPLATES list for the better cached image case, which are build and placed by Spartacus buildTemplateList()
        elseif (!empty($info["preview{$backendId}_fullsizeURL"])) { // preview{$backendId}_fullsizeURL is not actually set in Spartacus yet, so enable additional_themes fetch in there
            if (file_exists($serendipity['serendipityPath'] . '/templates_c/template_cache/'. $theme . "${backendId}.webp")) {
                $data['templates'][$theme]["fullsize${backendId}_preview_webp"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.webp";
                if (file_exists($serendipity['serendipityPath'] . '/templates_c/template_cache/'. $theme . "${backendId}.jpg")) {
                    $data['templates'][$theme]["fullsize${backendId}_preview"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
                }
            } elseif (file_exists($serendipity['serendipityPath'] . '/templates_c/template_cache/'. $theme . "${backendId}.png")) {
                $data['templates'][$theme]["fullsize${backendId}_preview"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
            } elseif (file_exists($serendipity['serendipityPath'] . '/templates_c/template_cache/'. $theme . "${backendId}.jpg")) {
                $data['templates'][$theme]["fullsize${backendId}_preview"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
            } else {
                $data['templates'][$theme]["fullsize${backendId}_preview"] = $info["preview${backendId}_fullsizeURL"];
            }
        }

        if (!empty($backendId)) {
            $previewType = '.png';
            if ($backendId) {
                $previewType = '.jpg';
            }

            if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . "/preview${backendId}${previewType}")) {
                $data['templates'][$theme]["preview${backendId}"] = $serendipity['templatePath'] . $theme . "/preview${backendId}${previewType}";
            } elseif (!empty($info['previewURL'])) {
                $data['templates'][$theme]["preview${backendId}"] = @$info["previewURL${backendId}"];
           }
        }
        // this is Spartacus only "blog.s9y.org" templates case
        if (!empty($info['demoURL'])) {
            $data['templates'][$theme]['demoURL'] = $info['demoURL'];
        }
    }

    $unmetRequirements = array();
    if (isset($info['require serendipity']) && version_compare($info['require serendipity'], serendipity_getCoreVersion($serendipity['version']), '>')) {
        $unmetRequirements[] = 'Serendipity '. $info['require serendipity'];
        $data['templates'][$theme]['unmetRequirements'] = sprintf(UNMET_REQUIREMENTS, implode(', ', $unmetRequirements));
    }

    if (in_array($theme, $core_templates)) {
        $data['core_templates'][$theme] = $data['templates'][$theme];
        if ($theme != $serendipity['template'] && $theme != $serendipity['template_backend']) {
            unset($data['templates'][$theme]);
        }
    }
}

$data['cur_tpl']         = $data['templates'][$serendipity['template']];
$data['cur_tpl_backend'] = $data['templates'][$serendipity['template_backend']];
$data['urltoken']        = serendipity_setFormToken('url');

unset($data['templates'][$serendipity['template']]);
if ($serendipity['template'] != $serendipity['template_backend'] && isset($data['core_templates'][$serendipity['template_backend']]) && isset($data['templates'][$serendipity['template_backend']])) {
    // when we could not unset a template because it is a backend template, and when that template is also a recommended template, then it will now
    // be in recommended and in the normal template list. We just detected that and have to remove it
    unset($data['templates'][$serendipity['template_backend']]);
}
unset($data['core_templates'][$serendipity['template']]);

echo serendipity_smarty_showTemplate('admin/templates.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
