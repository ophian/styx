<?php

if (IN_serendipity !== true) { die ("Don't hack!"); }

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2' => $_SERVER['REQUEST_URI']));

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
       'var' => 'hugo',
       'name' => B46_HUGO,
       'description' => B46_TEASE . B46_TEASE_COND,
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'card',
       'name' => B46_CARD,
       'description' => B46_TEASE . B46_TEASE_COND . B46_CARD_META,
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'featured',
       'name' => B46_LEAD,
       'description' => B46_TEASE . B46_LEAD_DESC . "image=${serendipity['serendipityHTTPPath']}${serendipity['templatePath']}${serendipity['template']}/img/hsfcrds.webp&height=350px&title=Title of a longer featured blog post&text=Multiple lines of text that form the lede, informing new readers quickly and efficiently about what’s most interesting in this post’s contents. This background image is borrowed from ARTE House of Cards preview for template demo example only. Please do not use without permission!&url=#&link=Continue reading...",
       'type' => 'string',
       'default' => 0
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
    ),
    array(
        'var' => 'lineup',
        'name' => B46_NAV_ONELINE,
        'type' => 'boolean',
        'default' => false
    )
);

$top = $serendipity['smarty_vars']['template_option'] ?? '';
$template_config_groups = NULL;
$template_global_config = array('navigation' => true);
$template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
serendipity_loadGlobalThemeOptions($template_config, $template_loaded_config, $template_global_config);

// PHP 8 can handle != while PHP 7.3+ does need !== in both file conditions!
if ($template_loaded_config['featured'] !== '0') {
    parse_str($template_loaded_config['featured'], $fpost);
    $serendipity['smarty']->assign('featured_post', $fpost);
}
if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}

// Disable the use of Serendipity JQuery in index header
$serendipity['capabilities']['jquery'] = false;
// Disable the use of Serendipity JQuery noConflict mode
$serendipity['capabilities']['jquery-noconflict'] = false;
