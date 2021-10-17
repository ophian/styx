<?php
if (IN_serendipity !== true) { die ("Don't hack!"); }

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
                                     'currpage2' => $_SERVER['REQUEST_URI']));

// LANDING PAGE - HOME - And Additional Grid Cards
// The first 2 (better say 3 cards, since the first spans over 2) are hard coded in the index.tpl file. At here you can add and define another 3 cards for your landing page pleasure.
// Each card can have an image, for example pointing to the MediaLibrary: $serendipity['baseURL'] . $serendipity['uploadPath'] . 'your/image.styxThumb.png' The already thumb sized images are good enough for single cards.
// Each card is a link, for example pointing to $serendipity['baseURL'] . 'archive' OR a static page at
//          $serendipity['baseURL'] . 'pages/aboutme.html'
// Each card should hold a short title of just 'plain text', eg. 'About me'. "none" for disabling the head markup.
// Each card should hold a short(!) intro or welcome text inside a paragraph, eg 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. [...]</p>' without any link or image or so. You can add multi-paragraphs.
// Taking the advantage to support our new Variations generated image thumbs, the image [avif | webp] value should look like this:
//      'avif' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'relative/path/to/image/.v/imagename.styxThumb.avif',
// and/or
//      'webp' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'relative/path/to/image/.v/imagename.styxThumb.webp'
// It can happen, that some normal image thumbs are smaller in size (KB) than the generated [avif | webp] variation file, see your MediaLibrary images meta info data. In this case just leave the [avif | webp] value empty ''.
/*
$serendipity['smarty']->assign('addcards', array(
    0 => array(
        'image' => array(
            'src'  => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'path/to/sir/thomas.styxThumb.jpg',
            'avif' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'path/to/sir/.v/thomas.styxThumb.avif',
            'webp' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'path/to/sir/.v/thomas.styxThumb.webp'
            ),
        'link'  => $serendipity['baseURL'] . 'archive',
        'title' => 'Blog archives',
        'body'  => '<p>Aliquam lobortis nisi eget turpis blandit rhoncus. Cras placerat accumsan lacus, tristique pellentesque tortor condimentum ut. In hac habitasse platea dictumst. Integer fermentum, velit a vehicula porttitor, leo nunc imperdiet est, vitae imperdiet ipsum velit a sapien. Nulla quis justo in magna porttitor sodales eu non sapien.</p>'
        ),
    1 => array(
        'image' => array(
            'src'  => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'where/is/my/triangel.styxThumb.jpg',
            'avif' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'where/is/my/.v/triangel.styxThumb.avif',
            'webp' => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'where/is/my/.v/triangel.styxThumb.webp'
            ),
        'link'  => $serendipity['baseURL'] . 'pages/contact/',
        'title' => 'Blog bell',
        'body'  => '<p>Ring my Tubular Bells, Sweety!</p>'
        ),
    2 => array(
        'image' => array(
            'src'  => $serendipity['baseURL'] . $serendipity['uploadPath'] . 'we/are/so/damn/chatty.styxThumb.jpg',
            'avif' => '',
            'webp' => ''
            ),
        'link'  => $serendipity['baseURL'] . 'comments/',
        'title' => 'Blog comments',
        'body'  => '<p>Rien de vas plus: Sed consequat lectus diam, vehicula dictum nulla. Sed rutrum mollis enim, in posuere tortor congue sed. Duis ante arcu, bibendum sit amet eleifend ac, molestie eget elit. Integer risus lacus, dapibus ut commodo eu, laoreet at odio. Curabitur varius, urna ac tincidunt gravida, eros dui consectetur nunc, euismod consequat turpis urna non libero.</p>'
        ),
    ));
*/
// STARTPAGE DEVELOPMENT image placeholder service
// Uses default fallback service by: Placeholder Images for every case. Webdesign or Print. It's simple and absolutely free! http://lorempixel.com/
// This is NOT really meant to stay and be used in production modes and is just here for development purposes and views as an example.
// Use the hard coded cards in the index.tpl file, to add your own image links and define them here in the upper 'addcards' array for the additional cards.
// Remove http://lorempixel.com/600/400/{$design.0}/?1 inside the <img src="" >
// After having done so for the first row cards, do it as well for the top Smarty function in your index.tpl file and remove this part: |default:"http://lorempixel.com/600/400/{$design.0}/?{($key+$design.1)}"
// Afterwards disable by # these next 3 lines:
$locs = array('abstract', 'animals', 'business', 'cats', 'city', 'food', 'nightlife', 'fashion', 'people', 'nature', 'sports', 'technics', 'transport');
$rkey = array_rand($locs);
$serendipity['smarty']->assign('design', array(0 => $locs[$rkey], 1 => $rkey));

// don't use the no-conflict jquery mode
$serendipity['capabilities']['jquery-noconflict'] = false;

// PURE template options
$template_config = array(
    array(
        'var'           => 'home_welcome_separator_group_head',
        'type'          => 'content',
        'name'          => 'home_welcome_group_title',
        'default'       => PURE_START_WELCOME_GROUP_TITLE,
    ),
    array(
        'var' => 'pure_welcome',
        'name' => PURE_START_WELCOME,
        'description' => PURE_START_WELCOME_DESC,
        'type' => 'boolean',
        'default' => false,
    ),
    array(
        'var' => 'home_posts_title',
        'name' => PURE_START_HOME_TITLE,
        'description' => PURE_START_HOME_TITLE_DESC,
        'type' => 'string',
        'default' => PURE_START_HOME_TITLE_DEFAULT
    ),
    array(
        'var' => 'home_blog_link',
        'name' => PURE_START_WELCOME_BLOG_LINK_TITLE,
        'type' => 'string',
        'default' => PURE_START_WELCOME_BLOG_LINK_TITLE_DEFAULT,
    ),
    array(
        'var' => 'home_welcome_title',
        'name' => PURE_START_WELCOME_TITLE,
        'description' => PURE_START_WELCOME_TITLE_DESC,
        'type' => 'string',
        'default' => PURE_START_WELCOME_TITLE_DEFAULT
    ),
    array(
        'var' => 'home_welcome_content',
        'name' => PURE_START_WELCOME_CONTENT,
        'type' => 'html',
        'default' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas luctus nisi sit amet turpis placerat lacinia sit amet at lacus. Cras &hellip;</p>

<p>No links here, please! The box itself is a link!</p>

<p>Write your text short enough to <strong>not</strong> expand the height of this neighbours first two grid column box with the 5 latest blog articles!</p>'
    ),
    array(
        'var'  => 'separator1',
        'type' => 'separator',
    ),
    array(
        'var'           => 'separator_head',
        'type'          => 'content',
        'name'          => 'navlinks_group_title',
        'default'       => PURE_START_GROUP_SEPARATOR_TITLE,
    ),
    array(
        'var' => 'use_highlight',
        'name' => PURE_START_USE_HIGHLIGHT,
        'description' => PURE_START_USE_HIGHLIGHT_DESC,
        'type' => 'boolean',
        'default' => false,
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

// Globals and bindings
$top = $serendipity['smarty_vars']['template_option'] ?? '';
$template_config_groups = NULL;
$template_global_config = array('navigation' => true);
$template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
serendipity_loadGlobalThemeOptions($template_config, $template_loaded_config, $template_global_config);
if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}
