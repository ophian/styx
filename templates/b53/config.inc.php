<?php
if (IN_serendipity !== true) { die ("Don't hack!"); }

//@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2' => $_SERVER['REQUEST_URI']));

define('THEME_COLORSET', 'Theme colorset');

$colorsets = array(
    'blue'      => '--bd-blue',
    'purple'    => '--bd-purple',
    'pink'      => '--bd-pink',
    'red'       => '--bd-red',
    'orange'    => '--bd-orange',
    'yellow'    => '--bd-yellow',
    'green'     => '--bd-green',
    'teal'      => '--bd-teal',
    'cyan'      => '--bd-cyan',
    'gray'      => '--bd-gray',
    'darkgray'  => '--bd-darkgray',
    'black'     => '--bd-black',
    'violet'    => '--bd-violet',
    'indigo'    => '--bd-indigo'
);

foreach(array_keys($colorsets) AS $ckey) {
    $showblock[] = "showblock $ckey";
}

$template_config = array(
    array(
        'var'           => 'colorset',
        'name'          => THEME_COLORSET,
        'type'          => 'radio',
        'radio'         => array('value' => array_values($colorsets),
                                 'desc'  => $showblock ?? array_keys($colorsets)),
        'default'       => '--bd-indigo'
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

if (!function_exists('serendipity_plugin_api_pre_event_hook')) {
  function serendipity_plugin_api_pre_event_hook($event, &$bag, &$eventData, &$addData) {
    global $serendipity;

    switch($event) {
        case 'js_backend':
echo "(() => {
    'use strict'

    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.configuration_group div:nth-child(1) .form_radio label')
          .forEach(color => {
                const selector = color.innerHTML;
                const title = selector.replace(/showblock /i, '');
                color.title = title;
                color.innerHTML = '<div class=\"' + selector + '\"></div>';
          });
        document.querySelectorAll('.configuration_group div:nth-child(1) .form_radio input')
          .forEach(input => {
                input.title = input.title.replace(/showblock /i, '');
          });
    })
})();\n\n";
            break;

        case 'css_backend':
            $eventData .= '

:root {
    --bd-blue-bg-rgb: 13.520718,110.062154,253.437846;
    --bd-purple-bg-rgb: 111.520718,66.062154,193.437846;
    --bd-pink-bg-rgb: 214.520718,51.062154,132.437846;
    --bd-red-bg-rgb: 220.520718,53.062154,69.437846;
    --bd-orange-bg-rgb: 253.520718,126.062154,20.437846;
    --bd-yellow-bg-rgb: 255.520718,193.062154,7.437846;
    --bd-green-bg-rgb: 25.520718,135.062154,84.437846;
    --bd-teal-bg-rgb: 32.520718,201.062154,151.437846;
    --bd-cyan-bg-rgb: 13.520718,202.062154,40.437846;
    --bd-gray-bg-rgb: 173.520718,181.062154,189.437846;
    --bd-darkgray-bg-rgb: 156.520718,156.062154,156.437846;
    --bd-black-bg-rgb: 1.520718,1.062154,1.437846;
    --bd-violet-bg-rgb: 112.520718,44.062154,249.437846;
    --bd-indigo-bg-rgb: 102.520718,16.062154,242.437846;
}
.showblock {
    display: inline-flex;
    width: 1.5em;
    height: 1.5em;
}
.showblock.blue {
    background-image: linear-gradient(rgba(var(--bd-blue-bg-rgb, 1)), rgba(var(--bd-blue-bg-rgb, 0.95)));
}
.showblock.purple {
    background-image: linear-gradient(rgba(var(--bd-purple-bg-rgb, 1)), rgba(var(--bd-purple-bg-rgb, 0.95)));
}
.showblock.pink {
    background-image: linear-gradient(rgba(var(--bd-pink-bg-rgb, 1)), rgba(var(--bd-pink-bg-rgb, 0.95)));
}
.showblock.red {
    background-image: linear-gradient(rgba(var(--bd-red-bg-rgb, 1)), rgba(var(--bd-red-bg-rgb, 0.95)));
}
.showblock.orange {
    background-image: linear-gradient(rgba(var(--bd-orange-bg-rgb, 1)), rgba(var(--bd-orange-bg-rgb, 0.95)));
}
.showblock.yellow {
    background-image: linear-gradient(rgba(var(--bd-yellow-bg-rgb, 1)), rgba(var(--bd-yellow-bg-rgb, 0.95)));
}
.showblock.green {
    background-image: linear-gradient(rgba(var(--bd-green-bg-rgb, 1)), rgba(var(--bd-green-bg-rgb, 0.95)));
}
.showblock.teal {
    background-image: linear-gradient(rgba(var(--bd-teal-bg-rgb, 1)), rgba(var(--bd-teal-bg-rgb, 0.95)));
}
.showblock.cyan {
    background-image: linear-gradient(rgba(var(--bd-cyan-bg-rgb, 1)), rgba(var(--bd-cyan-bg-rgb, 0.95)));
}
.showblock.gray {
    background-image: linear-gradient(rgba(var(--bd-gray-bg-rgb, 1)), rgba(var(--bd-gray-bg-rgb, 0.95)));
}
.showblock.darkgray {
    background-image: linear-gradient(rgba(var(--bd-darkgray-bg-rgb, 1)), rgba(var(--bd-darkgray-bg-rgb, 0.95)));
}
.showblock.black {
    background-image: linear-gradient(rgba(var(--bd-black-bg-rgb, 1)), rgba(var(--bd-black-bg-rgb, 0.95)));
}
.showblock.violet {
    background-image: linear-gradient(rgba(var(--bd-violet-bg-rgb, 1)), rgba(var(--bd-violet-bg-rgb, 0.95)));
}
.showblock.indigo {
    background-image: linear-gradient(rgba(var(--bd-indigo-bg-rgb, 1)), rgba(var(--bd-indigo-bg-rgb, 0.95)));
}
';
            break;

        case 'css':
            $template_config = array();
            $top = $serendipity['smarty_vars']['template_option'] ?? '';
            $template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
            if (!empty($template_loaded_config['colorset'])) {
                $eventData = str_replace('var(--bd-default', "var({$template_loaded_config['colorset']}", $eventData);
            }
            break;
    }

  }
}

if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}
// Disable the use of Serendipity JQuery in index header
$serendipity['capabilities']['jquery'] = false;
// Disable the use of Serendipity JQuery noConflict mode
$serendipity['capabilities']['jquery-noconflict'] = false;
