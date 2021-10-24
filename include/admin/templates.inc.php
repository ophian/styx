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

if ($serendipity['template'] == 'bulletproof' && !is_dir($serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template'])) {
    echo '<span class="msg_error"><span class="icon-attention-circled"></span> Your theme <strong>' . $serendipity['template'] . '</strong> has been removed to additional themes. The Standard theme temporarily occurs here as fallback, while in the frontend the fallback use the "default" theme. Please change to any self-choosen theme to remove this message!</span>';
    $serendipity['template'] = $serendipity['defaultTemplate']; // fall back to Standard in case of error
}

$data['cur_template']         = $serendipity['template'];
$data['cur_template_backend'] = $serendipity['template_backend'];
$data['cur_template_info']    = serendipity_fetchTemplateInfo($serendipity['template']);

// NOTE: config.inc.php currently only applies to frontend configuration. Backend configuration is not planned yet, and would preferably use a "config_backend.inc.php" file!
// leave this open to list view AND editConfiguration page, as long it does not error in cke
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

// template config is added by global, and, if filled, already a preset build by serendipity_admin.php serendipity_plugin_api::hook_event('backend_configure', $serendipity); at line 17;
// Thus we need to strictly check editConfiguration and - NOT to forget - independently the POSTed configure array
if (is_array($template_config)
&& (  (isset($serendipity['POST']['adminAction']) && $serendipity['POST']['adminAction'] == 'configure')
   || (isset($serendipity['GET']['adminAction']) && $serendipity['GET']['adminAction'] == 'editConfiguration')
)) {
    serendipity_plugin_api::hook_event('backend_templates_configuration_top', $template_config);
    $data['has_config'] = true;

    if (isset($serendipity['POST']['adminAction']) && $serendipity['POST']['adminAction'] == 'configure' && serendipity_checkFormToken()) {
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
$core_templates = ['2k11', 'styx', 'b46', 'b5blog', 'boot', 'bootstrap4', 'clean-blog', 'default', 'default-php', 'dude', 'next', 'psg', 'pure', 'skeleton', 'sliver', 'timeline'];
$data['core_templates'] = array();

foreach($themes AS $theme) {
    $_theme = $theme == '2k11' ? 'n2k11' : $theme; // for sorting placement only, left to Next
    $stack[$_theme] = serendipity_fetchTemplateInfo($theme);
    #$stack[$theme] = serendipity_fetchTemplateInfo($theme);
}
ksort($stack, SORT_NATURAL | SORT_FLAG_CASE);

foreach($stack AS $theme => $info) {
    $theme = $theme == 'n2k11' ? '2k11' : $theme; // back, see above
    $info['custom_config'] = $info['custom_config'] ?? null;
    $data['templates'][$theme]['info'] = $info;

    foreach(array('', '_backend') AS $backendId) {
        // LOCAL front- and backend themes
        if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . "/preview${backendId}_fullsize.jpg")) {
            if (empty($backendId) && @file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.png')) {
                $png = true;
                if (false === (@filesize($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.png') <= @filesize($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.jpg'))) {
                    $png = false;
                }
                // AVIF
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview.avif')) {
                    $data['templates'][$theme]['preview_avif'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview.avif';
                }
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.avif')) {
                    $data['templates'][$theme]['fullsize_preview_avif'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.avif';
                }
                // check the normal backend theme for a special backend image fullsize avif file, else the fullsize jpg is used (i.e. current backend is Styx, but default backend needs this set - or vice versa) 
                if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $theme . '/preview_backend_fullsize.avif')) {
                    $data['templates'][$theme]["fullsize_backend_preview_avif"] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_backend_fullsize.avif';
                }
                // WebP
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
                // ORIGIN
                $data['templates'][$theme]['preview'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . ($png ? '/preview.png' : '/preview_fullsize.jpg');
                $data['templates'][$theme]['fullsize_preview'] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . '/preview_fullsize.jpg';

                // CORE THEMES VARIATION SIZING checks
                // compare preview preview Variation filesizes for srcsets fillup
                if (!empty($data['templates'][$theme]['preview_avif']) && !empty($data['templates'][$theme]['preview_webp'])) {
                    $data['templates'][$theme]['preview_avif'] = (filesize($data['templates'][$theme]['preview_avif']) > filesize($data['templates'][$theme]['preview_webp']))
                                            ? null
                                            : $data['templates'][$theme]['preview_avif'];
                }
                // compare fullsize preview Variation filesizes for srcsets fillup
                if (!empty($data['templates'][$theme]['fullsize_preview_avif']) && !empty($data['templates'][$theme]['fullsize_preview_webp'])) {
                    $data['templates'][$theme]['fullsize_preview_avif'] = (filesize($data['templates'][$theme]['fullsize_preview_avif']) > filesize($data['templates'][$theme]['fullsize_preview_webp']))
                                            ? null
                                            : $data['templates'][$theme]['fullsize_preview_avif'];
                }
                // compare fullsize backend preview Variation filesizes for srcsets fillup
                if (!empty($data['templates'][$theme]['fullsize_backend_preview_avif']) && !empty($data['templates'][$theme]['fullsize_backend_preview_webp'])) {
                    $data['templates'][$theme]['fullsize_backend_preview_avif'] = (filesize($data['templates'][$theme]['fullsize_backend_preview_avif']) > filesize($data['templates'][$theme]['fullsize_backend_preview_webp']))
                                            ? null
                                            : $data['templates'][$theme]['fullsize_backend_preview_avif'];
                }
            } else {
                // check the backend theme for the jpg fallback case URL
                $data['templates'][$theme]["fullsize${backendId}_preview"] = $serendipity['baseURL'] . $serendipity['templatePath'] . $theme . "/preview${backendId}_fullsize.jpg";
            }
            // Fallback: Avoid old themes debug sets with uninitialized variation variables in PHP 8
            $data['templates'][$theme]['preview_avif']          = $data['templates'][$theme]['preview_avif']          ?? null;
            $data['templates'][$theme]['preview_webp']          = $data['templates'][$theme]['preview_webp']          ?? null;
            $data['templates'][$theme]['fullsize_preview_avif'] = $data['templates'][$theme]['fullsize_preview_avif'] ?? null;
            $data['templates'][$theme]['fullsize_preview_webp'] = $data['templates'][$theme]['fullsize_preview_webp'] ?? null;
            $data['templates'][$theme]['fullsize_backend_preview_avif'] = $data['templates'][$theme]['fullsize_backend_preview_avif'] ?? null;
        }
        // Now the REMOTE TEMPLATES list for the better cached image case, which are build and placed by Spartacus :: buildTemplateList()
        // Cache store them first via Spartacus inside templates_c/template_cache, then get them here
        // NOTE: preview{$backendId}_fullsizeURL is not actually set in Spartacus yet, so you need to enable the additional_themes fetch in there
        elseif (!empty($info["preview{$backendId}_fullsizeURL"])) {
            if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.avif")) {
                $data['templates'][$theme]["fullsize${backendId}_preview_avif"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.avif";
                if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.jpg")) {
                    $data['templates'][$theme]["fullsize${backendId}_preview"]  =  $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
                }
                if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.png")) {
                    $data['templates'][$theme]["preview${backendId}"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}_preview.png";
                    if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.avif")) {
                        $data['templates'][$theme]["preview_avif"]   =   $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}_preview.avif";
                    }
               }
            } elseif (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.webp")) {
                $data['templates'][$theme]["fullsize${backendId}_preview_webp"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.webp";
                if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.jpg")) {
                    $data['templates'][$theme]["fullsize${backendId}_preview"]  =  $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
                }
                if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.png")) {
                    $data['templates'][$theme]["preview${backendId}"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}_preview.png";
                    if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.webp")) {
                        $data['templates'][$theme]["preview_webp"]   =   $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}_preview.webp";
                    }
               }
            } elseif (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.png")) {
                $data['templates'][$theme]["fullsize${backendId}_preview"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}_preview.png";
            } elseif (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.jpg")) {
                $data['templates'][$theme]["fullsize${backendId}_preview"]  = $serendipity['baseURL'] . 'templates_c/template_cache/'. $theme . "${backendId}.jpg";
            } else {
                $data['templates'][$theme]["fullsize${backendId}_preview"]  = $info["preview${backendId}_fullsizeURL"];
            }

            // REMOTE THEMES VARIATION SIZING checks
            // compare FULLSIZE preview Variation filesizes for srcsets fillup.
            // NOTE for the CACHED files: The origin "preview_fullsize.jpg" theme file is simply stored as "themeName.jpg" and the "preview.png" files as "themeName_preview.png".
            if (!empty($data['templates'][$theme]["fullsize${backendId}_preview_avif"]) && !empty($data['templates'][$theme]["fullsize${backendId}_preview_webp"])) {
                $data['templates'][$theme]["fullsize${backendId}_preview_avif"] = (filesize($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.avif") > filesize($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}.webp"))
                                        ? null
                                        : $data['templates'][$theme]["fullsize${backendId}_preview_avif"];
            }
            // compare PREVIEW preview Variation filesizes for srcsets fillup
            if (!empty($data['templates'][$theme]["preview_avif"]) && !empty($data['templates'][$theme]["preview_webp"])) {
                $data['templates'][$theme]["preview_avif"] = (filesize($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.avif") > filesize($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $theme . "${backendId}_preview.webp"))
                                        ? null
                                        : $data['templates'][$theme]["preview_avif"];
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
                $data['templates'][$theme]["preview${backendId}"] = $info["previewURL${backendId}"] ?? null;
           }
        }
        // this is Spartacus only "blog.s9y.org" templates case
        #if (!empty($info['demoURL'])) {
        #    $data['templates'][$theme]['demoURL'] = $info['demoURL'];
        #}
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

@define('RESPONSIVE', 'Responsive');
@define('MOBILE', 'Mobile');

echo serendipity_smarty_showTemplate('admin/templates.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
