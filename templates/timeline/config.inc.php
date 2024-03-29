<?php
if (IN_serendipity !== true) {
  die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

$serendipity['smarty']->assign(array('currpage'  => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']));

$serendipity['smarty']->assign('archiveURL', serendipity_rewriteURL(PATH_ARCHIVE));

if ((isset($serendipity['GET']['adminModule']) && $serendipity['GET']['adminModule'] == 'templates') || (isset($serendipity['POST']['adminModule']) && $serendipity['POST']['adminModule'] == 'templates') || $serendipity['GET']['adminAction'] == 'cattemplate') {
    $css_files = glob(dirname(__FILE__) . '/*_style.css');
    foreach($css_files AS $css_file) {
        $css_file = str_replace('_style.css', '', basename($css_file));
        if (!isset($colorsets[$css_file])) {
            $colorsets[$css_file] = $css_file;
        }
    }

    $skin_files = glob(dirname(__FILE__) . '/*_skin.css');
    $skinsets[''] = 'light'; // light is default, but light_skin.css does not exist as light styles are written into style.css
    foreach($skin_files AS $skin_file) {
        $skin_file = str_replace('_skin.css', '', basename($skin_file));
        if (!isset($skinsets[$skin_file])) {
            $skinsets[$skin_file] = $skin_file;
        }
    }
}

if (!function_exists('distanceOfTimeInWords')) {
    // show elapsed time in words, such as x hours ago.
    function distanceOfTimeInWords($fromTime, $toTime = 0) {
        $distanceInSeconds = round(abs($toTime - $fromTime));
        $distanceInMinutes = round($distanceInSeconds / 60);

        if ( $distanceInMinutes <= 1 ) {
            if ( $distanceInSeconds < 60 ) {
                return ELAPSED_LESS_THAN_MINUTE_AGO;
            }
            return ELAPSED_ONE_MINUTE_AGO;
        }
        if ( $distanceInMinutes < 45 ) {
            return (sprintf(ELAPSED_MINUTES_AGO, $distanceInMinutes));
        }
        if ( $distanceInMinutes < 90 ) {
            return ELAPSED_ABOUT_ONE_HOUR_AGO;
        }
        // less than 24 hours
        if ( $distanceInMinutes < 1440 ) {
            return (sprintf(ELAPSED_HOURS_AGO, round(floatval($distanceInMinutes) / 60.0)));
        }
        //less than 48hours
        if ( $distanceInMinutes < 2880 ) {
            return ELAPSED_ONE_DAY_AGO;
        }
        // less than 30 days
        if ( $distanceInMinutes < 43200 ) {
            return (sprintf(ELAPSED_DAYS_AGO, round(floatval($distanceInMinutes) / 1440)));
        }
        //less than 60 days
        if ( $distanceInMinutes < 86400 ) {
            return ELAPSED_ABOUT_ONE_MONTH_AGO;
        }
        // less than 365 days
        if ( $distanceInMinutes < 525600 ) {
            return (sprintf(ELAPSED_MONTHS_AGO, round(floatval($distanceInMinutes) / 43200)));
        }
        // less than 2 years
        if ( $distanceInMinutes < 1051199 ) {
            return ELAPSED_ABOUT_ONE_YEAR_AGO;
        }
        return (sprintf(ELAPSED_OVER_YEARS_AGO, round(floatval($distanceInMinutes) / 525600)));
    }
}

if (!function_exists('timeAgoInWords')) {

    function timeAgoInWords($params, $template) {
        return distanceOfTimeInWords($params['from_time'], time());
    }
    // Smarty function to use distanceOfTimeInWords function
    // call from tpl as {elapsed_time_words from_time=$comment.timestamp}
    $serendipity['smarty']->registerPlugin('function', 'elapsed_time_words', 'timeAgoInWords');
}


//  Used to determine if entry image begins with <iframe,<embed,<object.  See entries.tpl
function is_in_string($string, $search) {
    $array = explode(',', $search);
    foreach($array AS $idx => $searchval) {
        if (strpos($string, $searchval) === 0) {
            return true;
        }
    }
    return false;
}
$serendipity['smarty']->registerPlugin('modifier', 'is_in_string', 'is_in_string');

if (class_exists('serendipity_event_entryproperties')) {
    $ep_msg = THEME_EP_YES;
} else {
    $ep_msg = THEME_EP_NO;
}

$template_config = array(
    array(
        'var'           => 'sidebars',
        'type'          => 'hidden',
        'value'         => 'right,footer,hide',
    ),
    array(
        'var'           => 'theme_instructions',
        'type'          => 'content',
        'default'       => '<p>' . THEME_DEMO_AVAILABLE . '</p>' . $ep_msg . THEME_INSTRUCTIONS . '<p>' . CATEGORIES_ON_ARCHIVE_DESC . '</p><p>' . TAGS_ON_ARCHIVE_DESC . '</p>',
    ),
    array(
        'var'           => 'use_googlefonts',
        'name'          => USE_GOOGLEFONTS,
        'type'          => 'boolean',
        'default'       => true,
    ),
    array(
        'var'           => 'use_corenav',
        'name'          => USE_CORENAV,
        'type'          => 'boolean',
        'default'       => false,
    ),
    array(
        'var'           => 'colorset',
        'name'          => THEME_COLORSET,
        'type'          => 'select',
        'default'       => 'green',
        'select_values' => isset($colorsets) ? $colorsets : 'green'
    ),
    array(
        'var'           => 'skinset',
        'name'          => THEME_SKINSET,
        'type'          => 'select',
        'default'       => 'light',
        'select_values' => isset($skinsets) ? $skinsets : 'light'
    ),
    array(
        'var'           => 'header_img',
        'name'          => HEADER_IMG,
        'description'   => HEADER_IMG_DESC,
        'type'          => 'media',
        'default'       => serendipity_getTemplateFile('header.jpg', 'serendipityHTTPPath', true)
    ),
    array(
        'var'           => 'subheader_img',
        'name'          => SUBHEADER_IMG,
        'description'   => SUBHEADER_IMG_DESC,
        'type'          => 'media',
        'default'       => serendipity_getTemplateFile('subheader.jpg', 'serendipityHTTPPath', true)
    ),
    array(
        'var'           => 'use_variation',
        'name'          => HEADERS_USE_VARIATION,
        'description'   => '',
        'type'          => 'radio',
        'radio'         => array('value' => array('true', 'false'),
                                 'desc'  => array(YES, NO)),
        'default'       => 'true'
    ),
    array(
        'var'           => 'date_format',
        'name'          => ENTRY_DATE_FORMAT . ' (http://php.net/strftime)',
        'type'          => 'string',
        'default'       => DATE_FORMAT_ENTRY,
    ),
    array(
        'var'           => 'comment_time_format',
        'name'          => COMMENT_TIME_FORMAT,
        'type'          => 'select',
        'default'       => 'words',
        'select_values' => array('words' => WORDS,
                                 'time'  => TIMESTAMP)
    ),
    array(
        'var'           => 'display_as_timeline',
        'name'          => DISPLAY_AS_TIMELINE,
        'description'   => DISPLAY_AS_TIMELINE_DESC,
        'type'          => 'boolean',
        'default'       => true,
    ),
    array(
        'var'           => 'months_on_timeline',
        'name'          => MONTHS_ON_TIMELINE,
        'description'   => MONTHS_ON_TIMELINE_DESC,
        'type'          => 'boolean',
        'default'       => true,
    ),
    array(
        'var'           => 'months_on_timeline_format',
        'name'          => MONTHS_ON_TIMELINE_FORMAT . ' (http://php.net/strftime)',
        'type'          => 'string',
        'default'       => '%B, %Y',
    ),
    array(
        'var'           => 'categories_on_archive',
        'name'          => CATEGORIES_ON_ARCHIVE,
        'description'   => CATEGORIES_ON_ARCHIVE_DESC,
        'type'          => 'boolean',
        'default'       => false,
    ),
    array(
        'var'           => 'category_rss_archive',
        'name'          => CATEGORY_RSS_ON_ARCHIVE,
        'type'          => 'boolean',
        'default'       => true,
    ),
    array(
        'var'           => 'tags_on_archive',
        'name'          => TAGS_ON_ARCHIVE,
        'description'   => TAGS_ON_ARCHIVE_DESC,
        'type'          => 'boolean',
        'default'       => false,
    ),
    array(
        'var'           => 'copyright',
        'name'          => COPYRIGHT,
        'type'          => 'string',
        'default'       => 'Copyright &copy; ' . $serendipity['blogTitle'] . ' ' . date('Y') . '.  <a href="#">Link 1</a> | <a href="#">Link 2</a> | <a href="' . $serendipity['baseURL'] . 'serendipity_admin.php">Login</a>',
    ),
    array(
        'var'           => 'social_icons_amount',
        'name'          => SOCIAL_ICONS_AMOUNT,
        'type'          => 'string',
        'default'       => '2',
    )
);

// register footer sidebar with Smarty
$footerSidebarElements = serendipity_plugin_api::count_plugins('footer');
$serendipity['smarty']->assignByRef('footerSidebarElements', $footerSidebarElements);

$top = $serendipity['smarty_vars']['template_option'] ?? '';
#$template_config_groups = NULL; // disabled, since used below
$template_global_config = array('navigation' => true);
$template_loaded_config = serendipity_loadThemeOptions($template_config, $top, true);
serendipity_loadGlobalThemeOptions($template_config, $template_loaded_config, $template_global_config);

if (isset($_SESSION['serendipityUseTemplate'])) {
    $template_loaded_config['use_corenav'] = false;
}

if (false !== serendipity_db_bool($template_loaded_config['use_variation'])) {
    if (!empty($template_loaded_config['header_img'])) {
        $rpath = serendipity_generate_webpPathURI($template_loaded_config['header_img'], 'avif');
        if (!file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)) {
            $rpath = serendipity_generate_webpPathURI($template_loaded_config['header_img']);
        }
        $template_loaded_config['header_img'] = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                                    ? $rpath
                                                    : $template_loaded_config['header_img']; // file exist needs full path to check
    }
    if (!empty($template_loaded_config['subheader_img'])) {
        $rpath = serendipity_generate_webpPathURI($template_loaded_config['subheader_img'], 'avif'); // check first, then overwrite
        if (!file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)) {
            $rpath = serendipity_generate_webpPathURI($template_loaded_config['subheader_img']);
        }
        $template_loaded_config['subheader_img'] = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                                    ? $rpath
                                                    : $template_loaded_config['subheader_img']; // file exist needs full path to check
    }
}

$serendipity['template_loaded_config'] = $template_loaded_config;

// if number of icons has changed, show new count
if (isset($_POST['serendipity']['template']['social_icons_amount']) && serendipity_userLoggedIn() && serendipity_checkPermission('adminTemplates')) {
    $temp_post = $_POST['serendipity']['template']['social_icons_amount'];
    if (is_numeric($temp_post)) {
       $template_loaded_config['social_icons_amount'] = $temp_post;
    }
}

$socialicons = array();
for ($i = 0; $i < $template_loaded_config['social_icons_amount']; $i++) {
    $template_config[] = array(
        'var'           => 'social_icon' . $i . 'service',
        'name'          => SOCIAL_NETWORK_SERVICE . ' #' .($i+1),
        'type'          => 'select',
        'default'       => 'RSS',
        'select_values' => array('Amazon'             => 'Amazon',
                                 'Amazon Pay'         => 'Amazon Pay',
                                 'Apple Pay'          => 'Apple Pay',
                                 'Comment'            => 'Comment',
                                 'Diaspora'           => 'Diaspora',
                                 'Digg'               => 'Digg',
                                 'Dribbble'           => 'Dribbble',
                                 'Dropbox'            => 'Dropbox',
                                 'Email'              => 'Email',
                                 'Etsy'               => 'Etsy',
                                 'Facebook'           => 'Facebook',
                                 'Facebook-Messenger' => 'Facebook-Messenger',
                                 'Flickr'             => 'Flickr',
                                 'Github'             => 'Github',
                                 'Google'             => 'Google',
                                 'Home'               => 'Home',
                                 'Instagram'          => 'Instagram',
                                 'LastFM'             => 'LastFM',
                                 'Linkedin'           => 'Linkedin',
                                 'Mastodon'           => 'Mastodon',
                                 'Mix'                => 'Mix',
                                 'Mobile'             => 'Mobile',
                                 'Patreon'            => 'Patreon',
                                 'Paypal'             => 'Paypal',
                                 'Pinterest'          => 'Pinterest',
                                 'Reddit'             => 'Reddit',
                                 'RSS'                => 'RSS',
                                 'Skype'              => 'Skype',
                                 'Snapchat'           => 'Snapchat',
                                 'StackOverflow'      => 'StackOverflow',
                                 'Tumblr'             => 'Tumblr',
                                 'Twitter'            => 'Twitter',
                                 'Vimeo'              => 'Vimeo',
                                 'Vine'               => 'Vine',
                                 'Xing'               => 'Xing',
                                 'YouTube'            => 'YouTube')
    );
    $template_config[] = array(
        'var'           => 'social_icon' . $i . 'url',
        'name'          => SOCIAL_ICON_URL . ' #' .($i+1),
        'type'          => 'string',
        'default'       => '#',
    );
    $socialicons[] = array(
        'service'           => isset($template_loaded_config['social_icon' . $i . 'service']) ? $template_loaded_config['social_icon' . $i . 'service'] : null,
        'url'               => isset($template_loaded_config['social_icon' . $i . 'url']) ? $template_loaded_config['social_icon' . $i . 'url'] : null,
    );
}
$serendipity['smarty']->assignByRef('socialicons', $socialicons);

function getIcon($service) {
   $icons = array('Amazon' => 'fab fa-amazon', 'Amazon Pay' => 'fab fa-amazon-pay', 'Apple Pay' => 'fab fa-apple-pay', 'Comment' => 'fas fa-comment', 'Diaspora' => 'fab fa-diaspora', 'Digg' => 'fab fa-digg', 'Dribbble' => 'fab fa-dribbble', 'Dropbox' => 'fab fa-dropbox', 'Email' => 'far fa-envelope', 'Etsy' => 'fab fa-etsy', 'Facebook' => 'fab fa-facebook', 'Facebook-Messenger' => 'fab fa-facebook-messenger', 'Flickr' => 'fab fa-flickr', 'Github' => 'fab fa-github', 'Google' => 'fab fa-google', 'Home' => 'fas fa-home', 'Instagram' => 'fab fa-instagram', 'LastFM' => 'fab fa-lastfm', 'Linkedin' => 'fab fa-linkedin', 'Mastodon' => 'fab fa-mastodon', 'Mix' => 'fab fa-mix', 'Mobile' => 'fas fa-mobile-alt', 'Patreon' => 'fab fa-patreon', 'Paypal' => 'fab fa-paypal', 'Pinterest' => 'fab fa-pinterest', 'Reddit' => 'fab fa-reddit', 'RSS' => 'fas fa-rss', 'Skype' => 'fab fa-skype', 'Snapchat' => 'fab fa-snapchat', 'StackOverflow' => 'fab fa-stack-overflow', 'Tumblr' => 'fab fa-tumblr', 'Twitter' => 'fab fa-twitter', 'Vimeo' => 'fab fa-vimeo', 'Vine' => 'fab fa-vine', 'Xing' => 'fab fa-xing', 'YouTube' => 'fab fa-youtube',);
   return $icons[$service] ?? null;
}

$serendipity['smarty']->registerPlugin('function', 'service_icon', 'getServiceIcon');

function getServiceIcon ($params, $template) {
    return getIcon($params['from_service']);
}

$navlinks_collapse = array( 'use_corenav', 'amount');
for ($i = 0; $i < $template_loaded_config['amount']; $i++) {
    array_push($navlinks_collapse, 'navlink' . $i . 'text' ,'navlink' . $i . 'url');
}

$sociallinks_collapse = array('social_icons_amount');
for ($i = 0; $i < $template_loaded_config['social_icons_amount']; $i++) {
    array_push($sociallinks_collapse, 'social_icon' . $i . 'service' ,'social_icon' . $i . 'url');
}

$template_config_groups = array(
    THEME_README        => array('theme_instructions'),
    THEME_IDENTITY      => array('sidebars', 'header_img', 'subheader_img', 'use_variation', 'colorset', 'skinset', 'copyright'),
    THEME_PAGE_OPTIONS  => array('use_googlefonts', 'date_format', 'comment_time_format', 'display_as_timeline', 'months_on_timeline', 'months_on_timeline_format', 'categories_on_archive', 'category_rss_archive', 'tags_on_archive'),
    THEME_NAVIGATION    => $navlinks_collapse,
    THEME_SOCIAL_LINKS  => $sociallinks_collapse
);

// Save custom field variables within the serendipity "Edit/Create Entry" backend.
//                Any custom variables can later be queried inside the .tpl files through
//                  {if $entry.properties.key_value == 'true'}...{/if}
if (!function_exists('entry_option_get_value')) {
    // Function to get the content of a non-boolean entry variable
    function entry_option_get_value($property_key, &$eventData) {
        global $serendipity;

        if (isset($eventData['properties'][$property_key])) {
            return $eventData['properties'][$property_key];
        }
        if (isset($serendipity['POST']['properties'][$property_key])) {
            return $serendipity['POST']['properties'][$property_key];
        }

        return false;
    }
}

if (!function_exists('entry_option_store')) {
    // Function to store form values into the serendipity database, so that they will be retrieved later.
    function entry_option_store($property_key, $property_val, &$eventData) {
        global $serendipity;

        // prep key/val params for webp image and path
        $property_key_webp = $property_key .'_webp';
        $rpath = serendipity_generate_webpPathURI($property_val);
        $property_val_webp = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                ? $rpath
                                : $property_val; // file exist needs full path to check
        // prep key/val params for avif image and path
        $property_key_avif = $property_key .'_avif';
        $rpath = serendipity_generate_webpPathURI($property_val, 'avif');
        $property_val_avif = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                ? $rpath
                                : $property_val; // file exist needs full path to check

        if ($property_val_webp !== null) {
            $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property = '" . serendipity_db_escape_string($property_key_webp) . "'";
            serendipity_db_query($q);
        }
        if ($property_val_avif !== null) {
            $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property = '" . serendipity_db_escape_string($property_key_avif) . "'";
            serendipity_db_query($q);
        }
        $q = "DELETE FROM {$serendipity['dbPrefix']}entryproperties WHERE entryid = " . (int)$eventData['id'] . " AND property = '" . serendipity_db_escape_string($property_key) . "'";
        serendipity_db_query($q);

        if (!empty($property_val)) {
            if ($property_val_webp !== null) {
                $q = "INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$eventData['id'] . ", '" . serendipity_db_escape_string($property_key_webp) . "', '" . serendipity_db_escape_string($property_val_webp) . "')";
                serendipity_db_query($q);
            }
            if ($property_val_avif !== null) {
                $q = "INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$eventData['id'] . ", '" . serendipity_db_escape_string($property_key_avif) . "', '" . serendipity_db_escape_string($property_val_avif) . "')";
                serendipity_db_query($q);
            }
            $q = "INSERT INTO {$serendipity['dbPrefix']}entryproperties (entryid, property, value) VALUES (" . (int)$eventData['id'] . ", '" . serendipity_db_escape_string($property_key) . "', '" . serendipity_db_escape_string($property_val) . "')";
            serendipity_db_query($q);
        }
    }
}

if (!function_exists('serendipity_plugin_api_pre_event_hook')) {
    function serendipity_plugin_api_pre_event_hook($event, &$bag, &$eventData, &$addData) {
        global $serendipity;

        // Check what Event is coming in, only react to those we want.
        switch($event) {

            // Displaying the Backend entry section
            case 'backend_display':
                // INFO: The whole 'entryproperties' injection is easiest to store any data you want. The entryproperties plugin
                // should actually not even be required to do this, as serendipity loads all properties regardless of the installed plugin

                // The name of the variable
                $timeline_image_key = 'timeline_image';

                // Check what our special key is set to (checks both POST data as well as the actual data)
                $is_timeline_image = entry_option_get_value($timeline_image_key, $eventData);

                // prep [ webp | avif ]image and path
                if (false !== $is_timeline_image) {
                    $rpath = serendipity_generate_webpPathURI($is_timeline_image, 'avif');
                    $is_timeline_image_avif = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                                ? $rpath
                                                : $is_timeline_image; // file exist needs full path to check
                    if ($rpath != $is_timeline_image_avif) {
                        $rpath = serendipity_generate_webpPathURI($is_timeline_image);
                        $is_timeline_image_webp = file_exists(str_replace($serendipity['serendipityHTTPPath'], '', $serendipity['serendipityPath']) . $rpath)
                                                    ? $rpath
                                                    : $is_timeline_image; // file exist needs full path to check
                    }
                }

                // This is the actual HTML output on the Backend screen.
                //DEBUG: echo '<pre>' . print_r($eventData, true) . '</pre>';
                echo '
                    <div class="entryproperties">
                      <input type="hidden" value="true" name="serendipity[propertyform]">
                          <div class="entryproperties_customfields adv_opts_box">
                              <h4>' . THEME_CUSTOM_FIELD_HEADING . '</h4>
                              <span class="msg_hint msg-0">' . THEME_CUSTOM_FIELD_DEFINITION . '</span>
                              <div class="serendipity_customfields clearfix">
                                  <div class="clearfix form_area media_choose" id="ep_column_' . $timeline_image_key . '">
                                      <label for="' . $timeline_image_key . '">' . THEME_ENTRY_IMAGE. '</label>
                                      <textarea data-configitem="' . $timeline_image_key . '" name="serendipity[properties][' . $timeline_image_key . ']" class="change_preview" id="prop' . $timeline_image_key . '">' . $is_timeline_image . '</textarea>
                                      <button title="' . MEDIA . '" name="insImage" type="button" class="customfieldMedia"><span class="icon-picture" aria-hidden="true"></span><span class="visuallyhidden">' . MEDIA . '</span></button>
                                      <figure id="' . $timeline_image_key . '_preview">
                                          <figcaption>' . PREVIEW . '</figcaption>
                                          <picture>
                                              <source type="image/avif" srcset="' . ($is_timeline_image_avif ?? null) . '">
                                              <source type="image/webp" srcset="' . ($is_timeline_image_webp ?? null) . '">
                                              <img src="' . $is_timeline_image . '" class="ml_preview_img" alt="">
                                          </picture>
                                      </figure>
                                  </div>
                              </div>
                          </div>
                     </div>
                '.PHP_EOL;
                break;

            // To store the value of our entryproperties
            case 'backend_publish':
            case 'backend_save':
                // Call the helper function with all custom variables here.
                entry_option_store('timeline_image', $serendipity['POST']['properties']['timeline_image'], $eventData);
                break;

            case 'css':
                $tfile = dirname(__FILE__) . '/' . $serendipity['template_loaded_config']['skinset'] . '_skin.css';
                if ($tfile) {
                    $tfilecontent = str_replace('img/', 'templates/' . $serendipity['template'] . '/img/', @file_get_contents($tfile));
                }
                if (!empty($tfilecontent)) {
                    $eventData .= "/* Skinset styles loaded via theme config */ \n\n" . $tfilecontent . "\n\n";
                    $tfilecontent = ''; // so as not to have content in next condition since reusing var.
                }
                $tfile = dirname(__FILE__) . '/' . $serendipity['template_loaded_config']['colorset'] . '_style.css';
                if ($tfile) {
                    $tfilecontent = str_replace('img/', 'templates/' . $serendipity['template'] . '/img/', @file_get_contents($tfile));
                }
                if (!empty($tfilecontent)) {
                    $eventData .= "/* Colorset styles loaded via theme config */ \n\n" . $tfilecontent . "\n\n";
                }
                break;
        }
    }
}
