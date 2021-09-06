<?php
// Sliver template v.4.63 2021-09-06
/*
 Sidebars left, Sidebars right, no Sidebars via templates config.
 Additional middle, top, footer Sidebars via admin panel plugin section.

 Uses HTML5 and CSS3 features
 Origin based on Bulletproof template and HTML5 Boilerplate
*/

if (IN_serendipity !== true) {
  die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage' => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2'=> $_SERVER['REQUEST_URI'],
                                     'sliver_credit' => 'Sliver &copy; 2011-'.date('Y').', v4.63'));

/*************************************************************************/
/* Staticpage related article by freetags.
   Better use a theme unique name, eg. mytheme_related_articles.tpl*/
/*************************************************************************/
if (!function_exists('smarty_sliver_show_tags')) {
    function smarty_sliver_show_tags($params, Smarty_Internal_Template $template) {
        global $serendipity;
        $o = isset($serendipity['GET']['tag']) ? $serendipity['GET']['tag'] : null;
        $serendipity['GET']['tag'] = $params['tag'];
        $e = serendipity_smarty_fetchPrintEntries($params, $template);
        echo $e;
        if (!empty($o)) {
            $serendipity['GET']['tag'] = $o;
        } else {
            unset($serendipity['GET']['tag']);
        }
    }
    $serendipity['smarty']->registerPlugin('function', 'sliver_show_tags', 'smarty_sliver_show_tags');
}

if (isset($serendipity['plugindata']['smartyvars']['uriargs'])) {
    #"archives/2018/07/P1.html"
    if (preg_match('/archives\/(\d{4})\/(\d{2})+/', $serendipity['plugindata']['smartyvars']['uriargs'])) {
        $serendipity['smarty']->assign('archives_summary_page', true);
    }
}

$template_config = array(
    array(
        'var'           => 'about',
        'name'          => 'Template Readme',
        'type'          => 'custom',
        'custom'        => THEME_ABOUT,
        'default'       => ''
    ),
    array(
        'var'           => 'sidebars',
        'name'          => 'Sidebars',
        'type'          => 'hidden',
        'value'         => 'left,middle,right,top,footer,hide',
        'default'       => 'left,middle,right,top,footer,hide'
    ),
    array(
        'var'           => 'webfonts',
        'name'          => SLIVER_WEBFONTS,
        'type'          => 'select',
        'default'       => 'none',
        'select_values' => array('none' => SLIVER_NOWEBFONT,
                                 'droid' => 'Droid Sans',
                                 'ptsans' => 'PT Sans',
                                 'osans' => 'Open Sans',
                                 'cabin' => 'Cabin',
                                 'ubuntu' => 'Ubuntu')
    ),
    array(
        'var'           => 'use_corenav',
        'name'          => USE_CORENAV,
        'type'          => 'boolean',
        'default'       => true,
    ),
    array(
        'var'           => 'use_slivers_jQueryMin',
        'name'          => SLIVERS_JQUERY,
        'description'   => SLIVERS_JQUERY_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'use_slivers_codeprettifier',
        'name'          => SLIVERS_PRETTIFY,
        'description'   => sprintf(SLIVERS_PRETTIFY_BLAHBLAH, '<pre class="prettyprint lang-scm">(friends \'of \'(parentheses))</pre>'),
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'use_google_analytics',
        'name'          => GOOGLE_ANALYTICS,
        'description'   => GOOGLE_ANALYTICS_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => false
    ),
    array(
        'var'           => 'google_id',
        'name'          => GOOGLE_ANALYTICS_ID,
        'type'          => 'string',
        'default'       => 'UA-XXXXX-X',
    ),
    array(
        'var'           => 'layouttype',
        'name'          => LAYOUT_TYPE,
        'type'          => 'select',
        'default'       => '2sb',
        'select_values' => array('2sb'  => LAYOUT_SB,
                                 '2bs'  => LAYOUT_BS,
                                 '1col' => LAYOUT_SC)
    ),
    array(
        'var'           => 'firbtitle',
        'name'          => FIR_BTITLE,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'firbdescr',
        'name'          => FIR_BDESCR,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'date_format',
        'name'          => GENERAL_PLUGIN_DATEFORMAT . " (strftime)",
        'type'          => 'select',
        'default'       => DATE_FORMAT_ENTRY,
        'select_values' => array(DATE_FORMAT_ENTRY => DATE_FORMAT_ENTRY,
                                 '%a, %e. %B %Y' => '%a, %e. %B %Y',
                                 '%d-%m-%y' => '%d-%m-%y',
                                 '%m-%d-%y' => '%m-%d-%y',
                                 '%a %d-%m-%y' => '%a %d-%m-%y',
                                 '%a %m-%d-%y' => '%a %m-%d-%y',
                                 '%b %d' => '%b %d',
                                 "%b %d '%y" => "%b %d '%y")
    ),
    array(
        'var'           => 'entryfooterpos',
        'name'          => ENTRY_FOOTER_POS,
        'type'          => 'select',
        'default'       => 'belowentry',
        'select_values' => array('belowentry' => BELOW_ENTRY,
                                 'belowtitle' => BELOW_TITLE,
                                 'splitfoot' => SPLIT_FOOTER)
    ),
    array(
        'var'           => 'footerauthor',
        'name'          => FOOTER_AUTHOR,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'send2printer',
        'name'          => FOOTER_SEND2PRINTER,
        'type'          => 'boolean',
        'default'       => false
    ),
    array(
        'var'           => 'footercategories',
        'name'          => FOOTER_CATEGORIES,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'footertimestamp',
        'name'          => FOOTER_TIMESTAMP,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'footercomments',
        'name'          => FOOTER_COMMENTS,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'footertrackbacks',
        'name'          => FOOTER_TRACKBACKS,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'altcommtrack',
        'name'          => ALT_COMMTRACK,
        'type'          => 'boolean',
        'default'       => false
    ),
    array(
        'var'           => 'show_sticky_entry_footer',
        'name'          => SHOW_STICKY_ENTRY_FOOTER,
        'description'   => SHOW_STICKY_ENTRY_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'show_sticky_entry_heading',
        'name'          => SHOW_STICKY_ENTRY_HEADING,
        'description'   => SHOW_STICKY_ENTRY_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'prev_next_style',
        'name'          => PREV_NEXT_STYLE,
        'type'          => 'select',
        'default'       => 'text',
        'select_values' => array('text' => PREV_NEXT_TEXT,
                                 'texticon' => PREV_NEXT_TEXT_ICON,
                                 'icon' => PREV_NEXT_ICON,
                                 'none' => NONE)
    ),
    array(
        'var'           => 'show_pagination',
        'name'          => SHOW_PAGINATION,
        'type'          => 'boolean',
        'default'       => false
    ),
    array(
        'var'           => 'sitenavpos',
        'name'          => SITENAV_POSITION,
        'description'   => SITENAV_BLAHBLAH,
        'type'          => 'select',
        'default'       => 'none',
        'select_values' => array('none' => SITENAV_NONE,
                                 'above' => SITENAV_ABOVE,
                                 'below' => SITENAV_BELOW,
                                 'left' => SITENAV_LEFT,
                                 'right' => SITENAV_RIGHT)
    ),
    array(
        'var'           => 'sitenavstyle',
        'name'          => SITENAV_STYLE,
        'description'   => SITENAV_STYLE_BLAHBLAH,
        'type'          => 'select',
        'default'       => 'default',
        'select_values' => array('default' => 'default',
                                 'slim' => SITENAV_SLIM,
                                 'ex'   => SITENAV_EXTENDED)
    ),
    array(
        'var'           => 'sitenav_footer',
        'name'          => SITENAV_FOOTER,
        'description'   => SITENAV_FOOTER_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'sitenav_quicksearch',
        'name'          => SITENAV_QUICKSEARCH,
        'description'   => SITENAV_QUICKSEARCH_BLAHBLAH,
        'type'          => 'boolean',
        'default'       => true
    ),
    array(
        'var'           => 'sitenav_sidebar_title',
        'name'          => SITENAV_TITLE,
        'description'   => SITENAV_TITLE_BLAHBLAH,
        'type'          => 'string',
        'default'       => SITENAV_TITLE_TEXT
    )
);

// Disable the use of Serendipity JQuery in index header
$serendipity['capabilities']['jquery'] = false;
// Disable the use of Serendipity JQuery noConflict mode
$serendipity['capabilities']['jquery-noconflict'] = false;

// count additional sidebar values in the admin panels plugin section
$topSidebarElements    = serendipity_plugin_api::count_plugins('top');
$middleSidebarElements = serendipity_plugin_api::count_plugins('middle');
$footerSidebarElements = serendipity_plugin_api::count_plugins('footer');
// assign them to smarty
$serendipity['smarty']->assignByRef('topSidebarElements', $topSidebarElements);
$serendipity['smarty']->assignByRef('middleSidebarElements', $middleSidebarElements);
$serendipity['smarty']->assignByRef('footerSidebarElements', $footerSidebarElements);

$top = $serendipity['smarty_vars']['template_option'] ?? '';
$template_config_groups = null;
$template_global_config = array('navigation' => true);
$template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
serendipity_loadGlobalThemeOptions($template_config, $template_loaded_config, $template_global_config); // since $template_loaded_config can somehow not be loaded global

if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}

$navlinks = array( 'use_corenav', 'amount');
for ($i = 0; $i < $template_loaded_config['amount']; $i++) {
    array_push($navlinks, 'navlink' . $i . 'text' ,'navlink' . $i . 'url');
}

$template_config_groups = array(
    THEME_WELCOME   => array('about'),
    THEME_LAYOUT    => array('sidebars', 'webfonts', 'use_slivers_jQueryMin', 'use_slivers_codeprettifier', 'use_google_analytics', 'google_id', 'layouttype', 'firbtitle', 'firbdescr'),
    THEME_ENTRIES   => array('date_format', 'entryfooterpos', 'footerauthor', 'send2printer', 'footercategories', 'footertimestamp', 'footercomments', 'footertrackbacks', 'altcommtrack', 'show_sticky_entry_footer', 'show_sticky_entry_heading', 'prev_next_style', 'show_pagination'),
    THEME_SITENAV   => array('sitenavpos', 'sitenavstyle', 'sitenav_footer', 'sitenav_quicksearch', 'sitenav_sidebar_title'),
    THEME_NAV       => $navlinks
);
