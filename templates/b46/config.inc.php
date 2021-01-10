<?php

if (IN_serendipity !== true) { die ("Don't hack!"); }

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2' => $_SERVER['REQUEST_URI']));

if (class_exists('serendipity_event_spamblock')) {
    $required_fieldlist = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE '%spamblock%required_fields'", true, 'assoc');
} elseif (class_exists('serendipity_event_commentspice')) {
    $required_fieldlist = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE name LIKE '%commentspice%required_fields'", true, 'assoc');
}

if (is_array($required_fieldlist)) {
    $required_fields = explode(',', $required_fieldlist['value']);
    $smarty_required_fields = array();

    foreach($required_fields AS $required_field) {
        $required_field = trim($required_field);

        if (empty($required_field)) continue;
            $smarty_required_fields[$required_field] = $required_field;
        }

    $serendipity['smarty']->assign('required_fields', $smarty_required_fields);
}

//  Used to fake substr for dropdown nav items
if (!function_exists('serendipity_substr')) {
    function serendipity_substr($s, $f = 0, $l = 0) {
        if ($l == 0) {
           return substr($s, $f);
        } elseif ($f === null) {
           return substr($s, $l);
        } else {
           return substr($s, $f, $l);
        }
    }
    $serendipity['smarty']->registerPlugin('modifier', 'smartySubstr', 'serendipity_substr');
}

$template_config = array(
    array(
      'var'           => 'infomore',
      'name'          => 'infomore',
      'type'          => 'custom',
      'custom'        => B46_INSTR,
    ),
    array(
        'var' => 'date_format',
        'name' => GENERAL_PLUGIN_DATEFORMAT . " (http://php.net/strftime)",
        'type' => 'select',
        'default' => DATE_FORMAT_ENTRY,
        'select_values' => array(DATE_FORMAT_ENTRY => DATE_FORMAT_ENTRY,
                                '%A, %e. %B %Y' => '%A, %e. %B %Y',
                                '%a, %e. %B %Y' => '%a, %e. %B %Y',
                                '%e. %B %Y' => '%e. %B %Y',
                                '%d.%m.%y' => '%d.%m.%y',
                                '%d.%m.%Y' => '%d.%m.%Y',
                                '%A, %m/%d/%Y' => '%A, %m/%d/%Y',
                                '%a, %m/%d/%y' => '%a, %m/%d/%y',
                                '%m/%d/%y' => '%m/%d/%y',
                                '%m/%d/%Y' => '%m/%d/%Y',
                                '%Y-%m-%d' => '%Y-%m-%d')
    ),
    array(
       'var' => 'navsearch',
       'name' => B46_USE_SEARCH,
       'type' => 'boolean',
       'default' => true
    ),
    array(
       'var' => 'scrollbtn',
       'name' => B46_JUMPSCROLL,
       'type' => 'boolean',
       'default' => true
    ),
    array(
       'var' => 'bs_rss',
       'name' => BS_RSS,
       'type' => 'boolean',
       'default' => true
    ),
    array(
        'var' => 'use_corenav',
        'name' => B46_USE_CORENAV,
        'type' => 'boolean',
        'default' => false
    )
);

$top = $serendipity['smarty_vars']['template_option'] ?? '';
$template_config_groups = NULL;
$template_global_config = array('navigation' => true);
$template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
serendipity_loadGlobalThemeOptions($template_config, $template_loaded_config, $template_global_config);

if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}

// Disable the use of Serendipity JQuery in index header
$serendipity['capabilities']['jquery'] = false;
// Disable the use of Serendipity JQuery noConflict mode
$serendipity['capabilities']['jquery-noconflict'] = false;
