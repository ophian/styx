<?php
if (IN_serendipity !== true) { die ("Don't hack!"); }

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2' => $_SERVER['REQUEST_URI']));

$serendipity['smarty']->assign(
    array(
        'red' => (isset($template_loaded_config['featured']) ? serendipity_fetchEntry('id', $template_loaded_config['featured'], false) : 0),
        'cal' => (isset($template_loaded_config['cardone']) ? serendipity_fetchEntry('id', $template_loaded_config['cardone'], false) : 0),
        'car' => (isset($template_loaded_config['cardtwo']) ? serendipity_fetchEntry('id', $template_loaded_config['cardtwo'], false) : 0),
        'cot' => ($template_loaded_config['cdothmb'] ?? 0),
        'ctt' => ($template_loaded_config['cdtthmb'] ?? 0)
    )
);

$template_config = array(
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
       'var' => 'featured',
       'name' => 'Featured blog post',
       'description' => 'Add the ID of your written blog entry. Set 0 for none.',
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'cardone',
       'name' => 'Left Card post',
       'description' => 'Add the ID of your written blog entry. Set 0 for none.',
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'cot',
       'name' => 'Left Card thumb image',
       'type' => 'string',
       'description' => 'Add a HTTP URL Path of a local image in size [200x250] px. Set 0 for placeholder SVG image.',
       'default' => 0
    ),
    array(
       'var' => 'cardtwo',
       'name' => 'Right Card post',
       'description' => 'Add the ID of your written blog entry. Set 0 for none.',
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'ctt',
       'name' => 'Right Card thumb image',
       'description' => 'Add a HTTP URL Path of a local image in size [200x250] px. Set 0 for placeholder SVG image.',
       'type' => 'string',
       'default' => 0
    ),
    array(
       'var' => 'title',
       'name' => 'Entries Welcome title',
       'description' => 'Set 0 for none.',
       'type' => 'string',
       'default' => 'From the Styx Firehose'
    ),
    array(
       'var' => 'about',
       'name' => 'About Box',
       'type' => 'boolean',
       'default' => true
    ),
    array(
       'var' => 'abouttitle',
       'name' => 'About Box title',
       'type' => 'string',
       'default' => 'About'
    ),
    array(
       'var' => 'abouttext',
       'name' => 'About Box text',
       'type' => 'text',
       'rows' => 3,
       'default' => 'Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.'
    ),
    array(
       'var' => 'use_corenav',
       'name' => USE_CORENAV,
       'type' => 'boolean',
       'default' => false,
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
