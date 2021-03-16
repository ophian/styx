<?php

/************
  TODO:

  - Perform Serendipity version checks to only install plugins available for version

 ***********/

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_spartacus extends serendipity_event
{
    var $title = PLUGIN_EVENT_SPARTACUS_NAME;
    var $purgeCache = false;

    function introspect(&$propbag)
    {
        $propbag->add('name',          PLUGIN_EVENT_SPARTACUS_NAME);
        $propbag->add('description',   PLUGIN_EVENT_SPARTACUS_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Garvin Hicking, Ian Styx');
        $propbag->add('version',       '3.12');
        $propbag->add('requirements',  array(
            'serendipity' => '3.1',
            'php'         => '7.3'
        ));
        $propbag->add('event_hooks',    array(
            'backend_plugins_fetchlist'         => true,
            'backend_plugins_fetchplugin'       => true,
            'backend_templates_fetchlist'       => true,
            'backend_templates_fetchtemplate'   => true,
            'backend_pluginlisting_header'      => true,
            'external_plugin'                   => true,
            'backend_directory_create'          => true,
            'cronjob'                           => true
        ));
        $propbag->add('groups', array('BACKEND_FEATURES'));
        $propbag->add('configuration',  array(
            'enable_plugins', 'enable_themes', 'enable_remote', 'remote_url', 'cronjob', 'mirror_xml', 'mirror_files', 'custommirror',
            'chown', 'chmod_files', 'chmod_dir', 'use_ftp', 'ftp_server', 'ftp_username', 'ftp_password', 'ftp_basedir')
        );
        $propbag->add('legal',    array(
            'services' => array(
/*                'spartacus' => array(
                    'url'  => 'http://spartacus.s9y.org',
                    'desc' => 'Package server for theme/plugin downloads'
                ),*/
                'github.com' => array(
                    'url'  => 'https://www.github.com',
                    'desc' => 'Package server for plugin downloads'
                )/*,
                's9y.org' => array(
                    'url'  => 'http://www.s9y.org',
                    'desc' => 'Package server for plugin downloads'
                ),
                'sourceforge.net' => array(
                    'url'  => 'http://www.sourceforget.net',
                    'desc' => 'Package server for plugin downloads'
                )*/
            ),
            'frontend' => array(
            ),
            'backend' => array(
                'Allows to download plugins from configured remote sources from the webserver, may also connect via FTP to a configured server.'
            ),
            'cookies' => array(
            ),
            'stores_user_input'     => false,
            'stores_ip'             => false,
            'uses_ip'               => false,
            'transmits_user_input'  => false
        ));
    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

    function cleanup()
    {
        global $serendipity;

        // Purge DB cache
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}pluginlist");
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}plugincategories");

        // Purge cached XML files.
        $files = serendipity_traversePath($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE, '', false, '/package_.+\.xml$/');

        if (!is_array($files)) {
            return false;
        }

        foreach($files AS $file) {
            $this->outputMSG('notice', sprintf(DELETING_FILE . '<br />', $file['name']));
            @unlink($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/' . $file['name']);
        }
    }

    function &getMirrors($type = 'xml', $loc = false)
    {
        static $mirror = array(
            'xml' => array(
                'github.com'
            ),

            'files' => array(
                'github.com'
            )
        );

        static $http = array(
            'xml' => array(
                'https://raw.githubusercontent.com/ophian/additional_plugins/master/'
            ),

            'files' => array(
                'https://raw.githubusercontent.com/ophian/'
            ),

            'files_health' => array(
                'https://raw.githubusercontent.com/'
            )
        );

        if ($loc) {
            return $http[$type];
        } else {
            return $mirror[$type];
        }
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {
            case 'cronjob':
                if (class_exists('serendipity_event_cronjob')) {
                    $propbag->add('type',        'select');
                    $propbag->add('name',        sprintf(PLUGIN_EVENT_SPARTACUS_CRONJOB_WHEN, $serendipity['blogMail']));
                    $propbag->add('description', '');
                    $propbag->add('default',     'none');
                    $propbag->add('select_values', serendipity_event_cronjob::getValues());
                } else {
                    $propbag->add('type',   'content');
                    $propbag->add('default', PLUGIN_EVENT_SPARTACUS_CRONJOB);
                }
                break;

            case 'enable_plugins':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_ENABLE_PLUGINS);
                $propbag->add('description', '');
                $propbag->add('default',     'true');
                break;

            case 'enable_themes':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_ENABLE_THEMES);
                $propbag->add('description', '');
                $propbag->add('default',     'false');
                break;

            case 'enable_remote':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE);
                $propbag->add('description', sprintf(PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_DESC, $serendipity['baseURL'] . $serendipity['indexFile'] . '?/plugin/' . $this->get_config('remote_url')));
                $propbag->add('default',     'false');
                break;

            case 'remote_url':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL_DESC . sprintf(PLUGIN_EVENT_SPARTACUS_CSPRNG, 'spartacus_', str_replace(array('/', '+', '='), '', base64_encode(random_bytes(16)))));
                $propbag->add('default',     'spartacus_remote');
                break;

            case 'chmod_files':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_CHMOD);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_CHMOD_DESC);
                $propbag->add('default',     '');
                break;

            case 'chmod_dir':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_CHMOD_DIR);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_CHMOD_DIR_DESC);
                $propbag->add('default',     '');
                break;

            case 'chown':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_CHOWN);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_CHOWN_DESC);
                $propbag->add('default',     '');
                break;

            case 'custommirror':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC . ' --------------------------------------------------------------------- ' . PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC_ADD);
                $propbag->add('default',     '');
                break;

            case 'mirror_xml':
                $propbag->add('type',        'select');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_MIRROR_XML);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_MIRROR_DESC);
                $propbag->add('select_values', $this->getMirrors('xml'));
                $propbag->add('default',     0);
                break;

            case 'mirror_files':
                $propbag->add('type',        'select');
                $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_MIRROR_FILES);
                $propbag->add('description', PLUGIN_EVENT_SPARTACUS_MIRROR_DESC);
                $propbag->add('select_values', $this->getMirrors('files'));
                $propbag->add('default',     0);
                break;

            case 'use_ftp':
                if (function_exists('ftp_connect')) {
                    $propbag->add('type',        'boolean');
                    $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_FTP_USE);
                    $propbag->add('description', PLUGIN_EVENT_SPARTACUS_FTP_USE_DESC);
                    if (@ini_get('safe_mode')) {
                        $propbag->add('default', 'true');
                    } else {
                        $propbag->add('default', 'false');
                    }
                }
                break;

            case 'ftp_server':
                if (function_exists('ftp_connect')) {
                    $propbag->add('type',        'string');
                    $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_FTP_SERVER);
                    $propbag->add('description', '');
                    $propbag->add('default',     '');
                }
                break;

            case 'ftp_username':
                if (function_exists('ftp_connect')) {
                    $propbag->add('type',        'string');
                    $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_FTP_USERNAME);
                    $propbag->add('description', '');
                    $propbag->add('default',     '');
                }
                break;

            case 'ftp_password':
                if (function_exists('ftp_connect')) {
                    $propbag->add('type',        'string');
                    $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_FTP_PASS);
                    $propbag->add('description', '');
                    $propbag->add('default',     '');
                }
                break;

            case 'ftp_basedir':
                if (function_exists('ftp_connect')) {
                    $propbag->add('type',        'string');
                    $propbag->add('name',        PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR);
                    $propbag->add('description', PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR_DESC);
                    $propbag->add('default',     $serendipity['serendipityHTTPPath']);
                }
                break;

            default:
                return false;
        }
        return true;
    }

    function GetChildren(&$vals, &$i)
    {
        $children = array();
        $cnt = sizeof($vals);

        while (++$i < $cnt) {
            // compare type
            switch ($vals[$i]['type']) {
                case 'cdata':
                    $children[] = $vals[$i]['value'];
                    break;

                case 'complete':
                    $children[] = array(
                        'tag'        => $vals[$i]['tag'],
                        'attributes' => isset($vals[$i]['attributes']) ? $vals[$i]['attributes'] : '',
                        'value'      => isset($vals[$i]['value']) ? $vals[$i]['value'] : ''
                    );
                    break;

                case 'open':
                    $children[] = array(
                        'tag'        => $vals[$i]['tag'],
                        'attributes' => isset($vals[$i]['attributes']) ? $vals[$i]['attributes'] : '',
                        'value'      => isset($vals[$i]['value']) ? $vals[$i]['value'] : '',
                        'children'   => $this->GetChildren($vals, $i)
                    );
                    break;

                case 'close':
                    return $children;
            }
        }
    }

    // remove double slashes without breaking URL
    protected function fixUrl($s)
    {
        return preg_replace('%([^:])([/]{2,})%', '\\1/', $s);
    }

    // Create recursive directories; begins at serendipity plugin root folder level
    function rmkdir($dir, $sub = 'plugins')
    {
        global $serendipity;

        if (serendipity_db_bool($this->get_config('use_ftp')) && $this->get_config('ftp_password') != '') {
            return $this->make_dir_via_ftp($dir);
        }

        $spaths = explode('/', $serendipity['serendipityPath'] . $sub . '/');
        $paths  = explode('/', $dir);

        $stack  = '';
        foreach($paths AS $pathid => $path) {
            $stack .= $path . '/';
            if ((empty($path) || empty($spaths[$pathid])) && @$spaths[$pathid] == $path) {
                continue;
            }
            // avoid possible open_basedir restrictions and better only check/make directories underneath sub (see method title)
            if (!in_array($path, $spaths) && !is_dir($stack) && !mkdir($stack)) {
                return false;
            } else {
                $this->fileperm($stack, true);
            }
        }

        return true;
    }

    // Apply file permission settings.
    function fileperm($stack, $is_dir)
    {
        $chmod_dir   = intval($this->get_config('chmod_dir'), 8);
        $chmod_files = intval($this->get_config('chmod_files'), 8);
        $chown       = $this->get_config('chown');

        if ($is_dir && !empty($chmod_dir) && function_exists('chmod')) {
            @chmod($stack, $chmod_dir); // Always ensure directory traversal.
        }

        if (!$is_dir && !empty($chmod_files) && function_exists('chmod')) {
            @chmod($stack, $chmod_files); // Always ensure directory traversal.
        }

        if (!empty($chown) && function_exists('chown')) {
            $own = explode('.', $chown);
            if (isset($own[1])) {
                @chgrp($stack, $own[1]);
            }
            @chown($stack, $own[0]);
        }


        return true;
    }

    function outputMSG($status, $msg)
    {
        global $serendipity;

        switch($status) {
            case 'notice':
                echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> '. $msg .'</span>' . "\n";
                break;

            case 'error':
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> '. $msg .'</span>' . "\n";
                if ($serendipity['ajax']) {
                    // we need to set an actual error header so the ajax request can react to the error state
                    header('HTTP/1.1 400');
                }
                break;

            case 'success':
            default:
                echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> '. $msg .'</span>' . "\n";
                break;
        }
    }

    function &fetchfile($url, $target, $cacheTimeout = 0, $decode_utf8 = false, $sub = 'plugins')
    {
        global $serendipity;
        static $error = false;
        static $debug = false; // ad hoc, case-by-case debugging

        $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger
        if ($debug) {
            $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START Spartacus::fetchfile SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        }

        // Fix double URL strings. (Keeps protocol secured)
        $url = preg_replace('@http(s)?:/@i', 'http\1://', str_replace('//', '/', $url));

        // --JAM: Get the URL's IP in the most error-free way possible
        $url_parts = @parse_url($url);
        $url_hostname = 'localhost';

        if (is_array($url_parts)) {
            $url_hostname = $url_parts['host'];
        }

        $url_ip = gethostbyname($url_hostname);

        if ($debug) {
            $serendipity['logger']->debug(sprintf(mb_convert_encoding(PLUGIN_EVENT_SPARTACUS_FETCHING, 'UTF-8', LANG_CHARSET), $url));
        }

        if (file_exists($target) && filesize($target) > 0 && filemtime($target) >= (time()-$cacheTimeout)) {
            $data = file_get_contents($target);
            if ($debug) {
                $serendipity['logger']->debug(sprintf(mb_convert_encoding(PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_CACHE, 'UTF-8', LANG_CHARSET), strlen($data), $target));
            }
        } else {
            $options = array('follow_redirects' => true, 'max_redirects' => 5);
            serendipity_plugin_api::hook_event('backend_http_request', $options, 'spartacus');
            // ping for main xml check
            $fContent = serendipity_request_url($url, 'GET', null, null, $options);

            try {
                if ($serendipity['last_http_request']['responseCode'] != '200') {
                    throw new HTTP_Request2_Exception('Statuscode not 200, Akismet HTTP verification request failed.');
                }
            } catch (HTTP_Request2_Exception $e) {
                $resolved_url = $url . ' (IP ' . $url_ip . ')';
                $this->outputMSG('error', sprintf(PLUGIN_EVENT_SPARTACUS_FETCHERROR, $resolved_url));
                //--JAM: START FIREWALL DETECTION
                if ($serendipity['last_http_request']['responseCode']) {
                    $this->outputMSG('error', sprintf(PLUGIN_EVENT_SPARTACUS_REPOSITORY_ERROR, $serendipity['last_http_request']['responseCode']));
                }
                $check_health = true;
                if (function_exists('curl_init')) {
                    $this->outputMSG('notice', PLUGIN_EVENT_SPARTACUS_TRYCURL);
                    $curl_handle = curl_init();
                    curl_setopt($curl_handle, CURLOPT_URL, $url);
                    curl_setopt($curl_handle, CURLOPT_HEADER, 0);
                    $curl_result = curl_exec($curl_handle);
                    curl_close($curl_handle);
                    if ($curl_result) {
                        $check_health = false;
                    } else {
                        $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_CURLFAIL . "\n");
                    }
                }
            }

            if (isset($check_health) && $check_health) {
                /*--JAM: Useful for later, when we have a health monitor for SPARTACUS
                $propbag = new serendipity_property_bag;
                $this->introspect($propbag);
                $health_url = 'http://spartacus.s9y.org/spartacus_health.php?version=' . $propbag->get('version');
                */
                // Garvin: Temporary health. Better than nothing, eh?
                $health_url = $url;
                $matches = array();
                preg_match('#http(s)?://[^/]*/#', $url, $matches);
                if ($matches[0]) {
                    $health_url = $matches[0];
                }

                $mirrors = $this->getMirrors('files_health', true);
                $health_url = $mirrors[$health_url];
                if ($health_url == null) {
                    $health_url = $mirrors[0];
                }
                $this->outputMSG('notice', sprintf(PLUGIN_EVENT_SPARTACUS_HEALTHCHECK, $health_url));

                $health_options = $options;
                serendipity_plugin_api::hook_event('backend_http_request', $health_options, 'spartacus_health');
                // ping for health
                $fContent = serendipity_request_url($health_url, 'GET', null, null, $health_options);

                try {
                    if ($serendipity['last_http_request']['responseCode'] != '200') {
                        $this->outputMSG('error', sprintf(PLUGIN_EVENT_SPARTACUS_HEALTHERROR, $serendipity['last_http_request']['responseCode']));
                        $this->outputMSG('notice', sprintf(PLUGIN_EVENT_SPARTACUS_HEALTHLINK, $health_url));
                    } else {
                        $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_HEALTHFIREWALLED);
                    }
                } catch (HTTP_Request2_Exception $e) {
                    $fp = @fsockopen('www.google.com', 80, $errno, $errstr);
                    if (!$fp) {
                        $this->outputMSG('error', sprintf(PLUGIN_EVENT_SPARTACUS_HEALTHBLOCKED, $errno, $errstr));
                    } else {
                        $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_HEALTHDOWN);
                        $this->outputMSG('notice', sprintf(PLUGIN_EVENT_SPARTACUS_HEALTHLINK, $health_url));
                        fclose($fp);
                    }
                }
                //--JAM: END FIREWALL DETECTION
                if (file_exists($target) && filesize($target) > 0) {
                    $data = file_get_contents($target);
                    $this->outputMSG('success', sprintf(PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_CACHE, strlen($data), $target));
                }
            } else {
                // Fetch file
                if (empty($data)) {
                    $data = $fContent;
                }

                if ($debug) {
                    $serendipity['logger']->debug(sprintf(mb_convert_encoding(PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_URL, 'UTF-8', LANG_CHARSET), strlen($data), $target));
                }

                $tdir = dirname($target);

                if (!is_dir($tdir) && !$this->rmkdir($tdir, $sub)) {
                    $this->outputMSG('error', sprintf(FILE_WRITE_ERROR, $tdir));
                    return $error;
                }

                $fp = @fopen($target, 'w');

                if (!$fp) {
                    $this->outputMSG('error', sprintf(FILE_WRITE_ERROR, $target));
                    return $error;
                }

                if ($decode_utf8) {
                    $data = str_replace('<?xml version="1.0" encoding="UTF-8" ?>', '<?xml version="1.0" encoding="' . LANG_CHARSET . '" ?>', $data);
                    $this->decode($data, true);
                }

                fwrite($fp, $data);
                fclose($fp);

                $this->fileperm($target, false);

                $this->purgeCache = true;
            }
        }

        return $data;
    }

    function decode(&$data, $force = false)
    {
        // xml_parser_* functions to recoding from ISO-8859-1/UTF-8
        if ($force === false && (LANG_CHARSET == 'ISO-8859-1' || LANG_CHARSET == 'UTF-8')) {
            return true;
        }

        switch (strtolower(LANG_CHARSET)) {
            case 'utf-8':
                // The XML file is UTF-8 format. No changes needed.
                break;

            case 'iso-8859-1':
                $data = utf8_decode($data);
                break;

            default:
                if (function_exists('iconv')) {
                    $data = iconv('UTF-8', LANG_CHARSET, $data);
                } elseif (function_exists('recode')) {
                    $data = recode('utf-8..' . LANG_CHARSET, $data);
                }
                break;
        }
    }

    function &fetchOnline($type, $no_cache = false)
    {
        global $serendipity;

        switch($type) {
            // Sanitize to not fetch other URLs
            default:
            case 'event':
                $url_type = 'event';
                $i18n     = true;
                break;

            case 'sidebar':
                $url_type = 'sidebar';
                $i18n     = true;
                break;

            case 'template':
                $url_type = 'template';
                $i18n     = false;
                break;
        }

        if (!$i18n) {
            $lang = '';
        } elseif (isset($serendipity['languages'][$serendipity['lang']])) {
            $lang = '_' . $serendipity['lang'];
        } else {
            $lang = '_en';
        }

        $mirrors = $this->getMirrors('xml', true);
        $custom  = $this->get_config('custommirror');

        if (strlen($custom) > 2 && $custom != 'none') {
            $servers = explode('|', $custom);
            $cacheTimeout = 60*60*12; // XML file is cached for half a day
            $valid = false;
            foreach($servers AS $server) {
                if ($valid) continue;

                $url    = $server . '/package_' . $url_type .  $lang . '.xml';
                $target = $serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/package_' . $url_type . $lang . '.xml';
                $serendipity['spartacus_cachedXMLfile'] = $target; // keep the cache file target path to nuke the file within possible 'backend_plugins_update' to config redirects/breaks.
                $serendipity['spartacus_rawPluginPath'] = $custom; // add a global var for plugin upgrade remote path documentaries usage

                $xml = $this->fetchfile($url, $target, $cacheTimeout, true);
                if (strlen($xml) > 0) {
                    $valid = true;
                }
            }

        } else {
            $mirror = $mirrors[$this->get_config('mirror_xml', 0)];
            if ($mirror == null) {
                $mirror = $mirrors[0];
            }
            $cacheTimeout = 60*60*12; // XML file is cached for half a day
            $url    = $mirror . '/package_' . $url_type .  $lang . '.xml';
            $target = $serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/package_' . $url_type . $lang . '.xml';
            $serendipity['spartacus_cachedXMLfile'] = $target; // keep the cache file target path to nuke the file within possible 'backend_plugins_update' to config redirects/breaks.
            $serendipity['spartacus_rawPluginPath'] = $mirror; // add a global var for plugin upgrade remote path documentaries usage

            $xml = $this->fetchfile($url, $target, $cacheTimeout, true);
        }

        $new_crc  = md5($xml);
        $last_crc = $this->get_config('last_crc_' . $url_type);

        if (!$no_cache && !$this->purgeCache && $last_crc == $new_crc) {
            $out = 'cached';
            return $out;
        }

        // XML functions
        $xml_string = '<?xml version="1.0" encoding="UTF-8" ?>';
        if (preg_match('@(<\?xml.+\?>)@imsU', $xml, $xml_head)) {
            $xml_string = $xml_head[1];
        }

        $encoding = 'UTF-8';
        if (preg_match('@encoding="([^"]+)"@', $xml_string, $xml_encoding)) {
            $encoding = $xml_encoding[1];
        }

        preg_match_all('@(<package version="[^"]+">.*</package>)@imsU', $xml, $xml_matches);
        if (!is_array($xml_matches)) {
            $out = 'cached';
            return $out;
        }

        $i = 0;
        $tree = array();
        $tree[$i] = array(
            'tag'        => 'packages',
            'attributes' => '',
            'value'      => '',
            'children'   => array()
        );

        // Check for xml_parser_create()
        if (!function_exists('xml_parser_create')) {
            echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . sprintf(CANT_EXECUTE_EXTENSION, 'php-xml (PHP)') . "</span>\n";
        }

        foreach($xml_matches[0] AS $xml_index => $xml_package) {
            $i = 0;

            switch(strtolower($encoding)) {
                case 'iso-8859-1':
                case 'utf-8':
                    $p = xml_parser_create($encoding);
                    break;

                default:
                    $p = xml_parser_create('');
            }

            xml_parser_set_option($p, XML_OPTION_CASE_FOLDING, 0);
             // Fixup PHP 8 Uncaught TypeError: xml_parser_set_option(): Argument #1 ($parser) must be of type XmlParser, null given.
             // With 8.0.0 parser expects an XMLParser instance now; previously, a resource was expected.
            xml_parser_set_option(($this->parser ?? $p), XML_OPTION_TARGET_ENCODING, LANG_CHARSET);
            $xml_package = $xml_string . "\n" . $xml_package;
            xml_parse_into_struct($p, $xml_package, $vals);
            xml_parser_free($p);
            $tree[0]['children'][] = array(
                'tag'        => $vals[$i]['tag'],
                'attributes' => $vals[$i]['attributes'],
                'value'      => $vals[$i]['value'],
                'children'   => $this->GetChildren($vals, $i)
            );
            unset($vals);
        }

        $this->set_config('last_crc_' . $url_type, $new_crc);

        return $tree;
    }

    function &getCachedPlugins(&$plugins, $type)
    {
        global $serendipity;
        static $pluginlist = null;

        if ($pluginlist === null) {
            $pluginlist = array();
            $data = serendipity_db_query("SELECT p.*,
                                                 pc.category
                                            FROM {$serendipity['dbPrefix']}pluginlist AS p
                                 LEFT OUTER JOIN {$serendipity['dbPrefix']}plugincategories AS pc
                                              ON pc.class_name = p.class_name
                                           WHERE p.pluginlocation = 'Spartacus' AND
                                                 p.plugintype     = '" . serendipity_db_escape_string($type) . "'");

            if (is_array($data)) {
                foreach($data AS $p) {
                    $p['stackable']    = serendipity_db_bool($p['stackable']);
                    $p['requirements'] = unserialize($p['requirements']);
                    $this->checkPlugin($p, $plugins, $p['plugintype']);

                    if (!isset($pluginlist[$p['plugin_file']])) {
                        $pluginlist[$p['plugin_file']] = $p;
                    }

                    $pluginlist[$p['plugin_file']]['groups'][] = $p['category'];
                }
            }
        }

        return $pluginlist;
    }

    function checkPlugin(&$data, &$plugins, $type)
    {
        $installable = true;
        $upgradeable = false; // default init defines
        $upgradeLink = '';

        if (in_array($data['class_name'], $plugins)) {
            $infoplugin =& serendipity_plugin_api::load_plugin($data['class_name']);
            if (is_object($infoplugin)) {
                $bag    = new serendipity_property_bag;
                $infoplugin->introspect($bag);
                if ($bag->get('version') == $data['version']) {
                    $installable = false;
                    $upgradeable = false;
                } elseif (version_compare($bag->get('version'), $data['version'], '<')) {
                    $upgradeable             = true; // place to data is set down below!
                    $data['upgrade_version'] = $data['version'];
                    $data['version']         = $bag->get('version');
                    $upgradeLink             = '&amp;serendipity[spartacus_upgrade]=true';
                }
            }
        }

        $data['installable']     = $installable;
        $data['upgradeable']     = $upgradeable;
        $data['pluginPath']      = 'online_repository';
        $data['pluginlocation']  = 'Spartacus';
        $data['plugintype']      = $type;
        $data['customURI']       = '&amp;serendipity[spartacus_fetch]=' . $type . $upgradeLink;

        return true;
    }

    function &buildList(&$tree, $type)
    {
        $plugins = serendipity_plugin_api::get_installed_plugins();

        if ($tree === 'cached') {
            return $this->getCachedPlugins($plugins, $type);
        }

        $pluginstack = array();
        $i = 0;

        $this->checkArray($tree);

        foreach($tree[0]['children'] AS $idx => $subtree) {
            if (is_array($subtree) && $subtree['tag'] == 'package') {
                $i++;

                foreach($subtree['children'] AS $child => $childtree) {
                    if (is_array($childtree) && isset($childtree['tag'])) {
                        switch($childtree['tag']) {
                            case 'name':
                                $pluginstack[$i]['plugin_class'] =
                                $pluginstack[$i]['plugin_file']  =
                                $pluginstack[$i]['class_name']   =
                                $pluginstack[$i]['true_name']    = $childtree['value'];
                                break;

                            case 'summary':
                                $pluginstack[$i]['name']         = $childtree['value'];
                                break;

                            case 'website':
                                $pluginstack[$i]['website']      = $childtree['value'];
                                break;

                            case 'changelog':
                                $pluginstack[$i]['changelog']    = $childtree['value'];
                                break;

                            case 'groups':
                                $pluginstack[$i]['groups']       = explode(',', $childtree['value']);
                                break;

                            case 'description':
                                $pluginstack[$i]['description']  = $childtree['value'];
                                break;

                            case 'release':
                                $pluginstack[$i]['version']      = $childtree['children'][0]['value'];

                                $pluginstack[$i]['requirements'] = array(
                                    'serendipity' => '',
                                    'php'         => '',
                                    'smarty'      => ''
                                );

                                foreach((array)$childtree['children'] AS $relInfo) {
                                    if (isset($relInfo['tag'])) {
                                        if ($relInfo['tag'] == 'requirements:s9yVersion') {
                                            $pluginstack[$i]['requirements']['serendipity'] = $relInfo['value'];
                                        }
                                        if ($relInfo['tag'] == 'requirements:smyVersion') {
                                            $pluginstack[$i]['requirements']['smarty'] = $relInfo['value'];
                                        }
                                        if ($relInfo['tag'] == 'requirements:phpVersion') {
                                            $pluginstack[$i]['requirements']['php'] = $relInfo['value'];
                                        }
                                    }
                                }
                                break;

                            case 'maintainers':
                                $pluginstack[$i]['author']       = $childtree['children'][0]['children'][0]['value']; // I dig my PHP arrays ;-)
                                break;
                        }
                    }
                }

                $this->checkPlugin($pluginstack[$i], $plugins, $type);

                serendipity_plugin_api::setPluginInfo($pluginstack[$i], $pluginstack[$i]['plugin_file'], $i, $i, 'Spartacus', $type);
                // Remove the temporary $i reference, as the array should be associative
                $plugname = $pluginstack[$i]['true_name'];
                $pluginstack[$plugname] = $pluginstack[$i];
                unset($pluginstack[$i]);
            }
        }

        return $pluginstack;
    }

    function checkArray(&$tree)
    {
        if (!is_array($tree) || !is_array($tree[0]['children'])) {
            $msg = "DEBUG: The XML file could not be successfully parsed. Download or caching error. " .
            "Please try again later or switch your XML/File mirror location. ".
            "You can also try to go to the plugin configuration of the Spartacus Plugin and simply click on 'Save' - this will purge all cached XML files and try to download it again.\n".
            '<div style="display: none">' . print_r($tree, true) . "</div>\n";
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> '. $msg .'</span>' . "\n";
        }
    }

    function &buildTemplateList(&$tree)
    {
        global $serendipity;

        $pluginstack = array();
        $i = 0;
        $gitloc = '';

        $mirrors = $this->getMirrors('files', true);
        $mirror  = $mirrors[$this->get_config('mirror_files', 0)];
        if ($mirror == null) {
            $mirror = $mirrors[0];
        }

        $custom  = $this->get_config('custommirror');
        $custom  = $custom != 'none' ? $custom : '';
        if (strlen($custom) > 2) {
            $servers = explode('|', $custom);
            $mirror = $servers[0];
        }

        if (stristr($mirror, 'githubusercontent.com')) {
            $gitloc = 'master/';
        }

        $this->checkArray($tree);

        uksort($tree, "natcasesort");

        if (! file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache')) {
            mkdir($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache');
        }

        foreach($tree[0]['children'] AS $idx => $subtree) {
            if ($subtree['tag'] == 'package') {
                $i++;

                foreach($subtree['children'] AS $child => $childtree) {
                    if (is_array($childtree) && isset($childtree['tag'])) {
                        switch($childtree['tag']) {
                            case 'release':
                                $pluginstack[$i]['version'] = $childtree['children'][0]['value'];

                                $pluginstack[$i]['requirements'] = array(
                                    'serendipity' => '',
                                    'php'         => '',
                                    'smarty'      => ''
                                );

                                foreach((array)$childtree['children'] AS $relInfo) {
                                    if (isset($relInfo['tag'])) {
                                        if ($relInfo['tag'] == 'requirements:s9yVersion') {
                                            $pluginstack[$i]['requirements']['serendipity'] = $relInfo['value'];
                                        }
                                        if ($relInfo['tag'] == 'date') {
                                            $pluginstack[$i]['date'] = $relInfo['value'];
                                        }
                                    }
                                }

                                $pluginstack[$i]['require serendipity'] = $pluginstack[$i]['requirements']['serendipity'];
                                break;

                            case 'maintainers':
                                $pluginstack[$i]['author'] = $childtree['children'][0]['children'][0]['value'];
                                break;

                            default:
                                # Catches name, summary, template, description and recommended. Also a way to extend this later on
                                $pluginstack[$i][$childtree['tag']]  = $childtree['value'];
                                break;
                        }
                    }
                }

                $plugname = $pluginstack[$i]['template'];
                #$pluginstack[$i]['demoURL'] = 'http://blog.s9y.org?user_template=additional_themes/' . $plugname;
                $pluginstack[$i]['previewURL'] = $this->fixUrl($mirror . '/additional_themes/' . $gitloc . $plugname . '/preview.png');

                $preview_previewURL  = $this->fixUrl($mirror . '/additional_themes/' . $gitloc . $plugname . '/preview.png');
                $prvwebp_previewURL  = $this->fixUrl($mirror . '/additional_themes/' . $gitloc . $plugname . '/preview.webp');
                $preview_fullsizeURL = $this->fixUrl($mirror . '/additional_themes/' . $gitloc . $plugname . '/preview_fullsize.jpg');
                $prvwebp_fullsizeURL = $this->fixUrl($mirror . '/additional_themes/' . $gitloc . $plugname . '/preview_fullsize.webp');

                if (file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'.jpg')
                ||  file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'.webp')
                ) {
                    $pluginstack[$i]['preview_fullsizeURL'] = $preview_fullsizeURL;
                } else {
                    if ( ! file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname)) {
                        $file = @fopen($preview_fullsizeURL, 'r');
                        if ($file) {
                            file_put_contents($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'.jpg', $file);
                            $pluginstack[$i]['preview_fullsizeURL'] = $preview_fullsizeURL;

                            $webp = @fopen($prvwebp_fullsizeURL, 'r');
                            if ($webp) {
                                file_put_contents($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'.webp', $webp);
                            }

                            $ppf = @fopen($preview_previewURL, 'r');
                            if ($ppf) {
                                file_put_contents($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'_preview.png', $ppf);
                            }

                            $wpf = @fopen($prvwebp_previewURL, 'r');
                            if ($wpf) {
                                file_put_contents($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname .'_preview.webp', $wpf);
                            }

                        } else {
                            // place an empty file, so we don't have to check the server on every load
                            file_put_contents($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/template_cache/'. $plugname, $file);
                        }
                    }
                }

                $pluginstack[$i]['customURI']  = '&amp;serendipity[spartacus_fetch]=' . $plugname;
                $pluginstack[$i]['customIcon'] = '_spartacus';

                // Remove the temporary $i reference, as the array should be associative
                $pluginstack[$plugname] = $pluginstack[$i];
                unset($pluginstack[$i]);
            }
        }

        return $pluginstack;
    }

    function download(&$tree, $plugin_to_install, $sub = 'plugins')
    {
        global $serendipity;

        $gitloc  = '';
        $cvshack = '';//?revision=1.9999';

        switch($sub) {
            case 'plugins':
            default:
                $sfloc = 'additional_plugins';
                break;

            case 'templates':
                $sfloc = 'additional_themes';
                break;
        }

        $pdir = $this->fixUrl($serendipity['serendipityPath'] . $sub . '/');
        if (!is_writable($pdir)) {
            $this->outputMSG('error', sprintf(DIRECTORY_WRITE_ERROR, $pdir));
            return false;
        }

        $files = array();
        $found = false;

        $this->checkArray($tree);

        foreach($tree[0]['children'] AS $subtree) {
            if ($subtree['tag'] != 'package') {
                continue;
            }

            foreach($subtree['children'] AS $childtree) {
                if (!is_array($childtree) || !isset($childtree['tag'])) {
                    continue;
                }
                if ($sub == 'templates' && $childtree['tag'] == 'template' && $childtree['value'] == $plugin_to_install) {
                    $found = true;
                } elseif ($sub == 'plugins' && $childtree['tag'] == 'name' && $childtree['value'] == $plugin_to_install) {
                    $found = true;
                }

                if (!$found || $childtree['tag'] != 'release') {
                    continue;
                }

                foreach($childtree['children'] AS $childtree2) {
                    if (!is_array($childtree2) || !isset($childtree2['tag'])) {
                        continue;
                    }
                    if ($childtree2['tag'] != 'serendipityFilelist') {
                        continue;
                    }

                    foreach($childtree2['children'] AS $_files) {
                        if (!is_array($_files) || !isset($_files['tag'])) {
                            continue;
                        }
                        if ($_files['tag'] == 'file' && !empty($_files['value'])) {
                            $files[] = $_files['value'];
                        }
                    }
                }

                $found = false;
            }
        }

        if (count($files) == 0) {
            $msg = "DEBUG: ERROR: XML tree did not contain requested plugin:\n<div>" . print_r($tree, true) . "</div>\n";
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> '. $msg .'</span>' . "\n";
        }

        $mirrors = $this->getMirrors('files', true);
        $mirror  = $mirrors[$this->get_config('mirror_files', 0)];
        if ($mirror == null) {
            $mirror = $mirrors[0];
        }

        $custom = $this->get_config('custommirror', '');
        if (strlen($custom) > 2 && $custom != 'none') {
            $servers = explode('|', $custom);
            $mirror = $servers[0];
        }

        if (stristr($mirror, 'githubusercontent.com')) {
            $gitloc = 'master/';
            $cvshack = '';
        }

        // fixes for custom mirror - currently custom mirror(s) for plugins only!
        if ((strlen($custom) > 2 && $custom != 'none') && $sub != 'templates') {
            $sfloc  = '';
            $gitloc = '';
        }
        foreach($files AS $file) {
            $url    = $this->fixUrl($mirror . '/' . $sfloc . '/' . $gitloc . $file . $cvshack);
            $target = $this->fixUrl($pdir . $file);
            $this->rmkdir($pdir . $plugin_to_install, $sub);
            $this->fileperm($pdir . $plugin_to_install, true);
            $this->fetchfile($url, $target);
            if (!isset($baseDir)) {
                $baseDirs = explode('/', $file);
                $baseDir  = $baseDirs[0];
            }
        }

        if (isset($baseDir)) {
            $this->outputMSG('success', PLUGIN_EVENT_SPARTACUS_FETCHED_DONE);
            return $baseDir;
        }
    }

    function make_dir_via_ftp($dir)
    {
        global $serendipity;

        if (!serendipity_db_bool($this->get_config('use_ftp'))) {
            return false;
        }

        $ftp_server    = $this->get_config('ftp_server');
        $ftp_user_name = $this->get_config('ftp_username');
        $ftp_user_pass = $this->get_config('ftp_password');
        $basedir       = $this->get_config('ftp_basedir');
        $dir_rules     = intval($this->get_config('chmod_dir'), 8);

        if (empty($ftp_server) || empty($ftp_user_name)) {
            return false;
        }

        $dir = str_replace($serendipity['serendipityPath'],"",$dir);

        // set up basic connection and log in with username and password
        $conn_id       = ftp_connect($ftp_server);
        $login_result  = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);

        // check connection
        if ((!$conn_id) || (!$login_result)) {
            $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CONNECT);
            return false;
        } else {
            $paths  = preg_split('@/@', $basedir.$dir, -1, PREG_SPLIT_NO_EMPTY);
            foreach($paths AS $path) {
                // trying to change directory, if not successful, it means
                // the directory does not exist and we must create it
                if (!ftp_chdir($conn_id, $path)) {
                    if (!ftp_mkdir($conn_id, $path)) {
                        $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_FTP_ERROR_MKDIR);
                        return false;
                    }
                    if (!ftp_chmod($conn_id,$dir_rules,$path)) {
                        $this->outputMSG('error', PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CHMOD);
                        return false;
                    }
                    if (!ftp_chdir($conn_id, $path)) {
                        return false;
                    }
                      $this->outputMSG('success', sprintf(PLUGIN_EVENT_SPARTACUS_FTP_SUCCESS, $path));
                }
            }
            ftp_close($conn_id);
            return true;
        }
    }

    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            switch($event) {

                case 'cronjob':
                    if ($this->get_config('cronjob') == $eventData) {
                        serendipity_event_cronjob::log('Spartacus', 'plugin');

                        $avail   = array();
                        $install = array();
                        $meth    = array('event', 'sidebar');
                        $active  = serendipity_plugin_api::get_installed_plugins();

                        $avail['event']   = $this->buildList($this->fetchOnline('event'), 'event');
                        $avail['sidebar'] = $this->buildList($this->fetchOnline('sidebar'), 'sidebar');
                        #echo "XAVAIL:<pre>" . print_r($avail, true) . "</pre>";

                        $install['event']   = serendipity_plugin_api::enum_plugin_classes(true);
                        $install['sidebar'] = serendipity_plugin_api::enum_plugin_classes(false);
                        #echo "XINSTALL:<pre>" . print_r($install, true) . "</pre>";

                        $mailtext = '';
                        foreach($meth AS $method) {
                            foreach($install[$method] AS $class_data) {
                                #echo "Probe " . $class_data['name']. "<br />\n"; // DEBUG
                                $pluginFile = serendipity_plugin_api::probePlugin($class_data['name'], $class_data['classname'], $class_data['pluginPath']);
                                $plugin     = serendipity_plugin_api::getPluginInfo($pluginFile, $class_data, $method);

                                if (is_object($plugin)) {
                                    #echo "Non cached<br />\n";
                                    #echo "<pre>" . print_r($avail[$method][$class_data['name']], true) . "</pre>";
                                    // Object is returned when a plugin could not be cached.
                                    $bag = new serendipity_property_bag;
                                    $plugin->introspect($bag);

                                    // If a foreign plugin is upgradeable, keep the new version number.
                                    if (isset($avail[$method][$class_data['name']])) {
                                        $class_data['upgrade_version'] = $avail[$method][$class_data['name']]['upgrade_version'];
                                    }
                                    $props = serendipity_plugin_api::setPluginInfo($plugin, $pluginFile, $bag, $class_data, 'local', $method);
                                    #echo "<pre>" . print_r($props, true) . "</pre>";
                                } elseif (is_array($plugin)) {
                                    // Array is returned if a plugin could be fetched from info cache
                                    $props = $plugin;
                                    #echo "Cached<br />\n";
                                } else {
                                    $props = false;
                                    #echo "Error<br />\n";
                                }

                                if (is_array($props)) {
                                    #echo "<pre>" . print_r($props, true) . "</pre>\n";
                                    if (version_compare($props['version'], $props['upgrade_version'], '<')) {
                                        $mailtext .= ' * ' . $class_data['name'] . " NEW VERSION: " . $props['upgrade_version'] . " - CURRENT VERSION: " . $props['version'] . "\n";
                                    }
                                } else {
                                    $mailtext .= " X ERROR: " . $class_data['true_name'] . "\n";
                                }
                            }
                        }

                        if (!empty($mailtext)) {
                            serendipity_sendMail($serendipity['blogMail'], 'Spartacus update report ' . $serendipity['baseURL'], $mailtext, $serendipity['blogMail']);
                            echo nl2br($mailtext);
                        }
                    }
                    break;

                case 'external_plugin':
                    if (!serendipity_db_bool($this->get_config('enable_remote', 'false'))) {
                        return false;
                    }
                    $details = ($eventData == 'spartacus_remote') ? true : false;

                    if ($eventData == $this->get_config('remote_url')) {
                        header('Content-Type: text/plain');
                        $avail   = array();
                        $install = array();
                        $meth    = array('event', 'sidebar');
                        $active  = serendipity_plugin_api::get_installed_plugins();

                        $avail['event']   = $this->buildList($this->fetchOnline('event'), 'event');
                        $avail['sidebar'] = $this->buildList($this->fetchOnline('sidebar'), 'sidebar');

                        $install['event']   = serendipity_plugin_api::enum_plugin_classes(true);
                        $install['sidebar'] = serendipity_plugin_api::enum_plugin_classes(false);

                        foreach($meth AS $method) {
                            echo "LISTING: $method\n-------------------\n";
                            foreach($install[$method] AS $class_data) {
                                $pluginFile = serendipity_plugin_api::probePlugin($class_data['name'], $class_data['classname'], $class_data['pluginPath']);
                                $plugin     = serendipity_plugin_api::getPluginInfo($pluginFile, $class_data, $method);

                                if (is_object($plugin)) {
                                    // Object is returned when a plugin could not be cached.
                                    $bag = new serendipity_property_bag;
                                    $plugin->introspect($bag);

                                    // If a foreign plugin is upgradeable, keep the new version number.
                                    if (isset($avail[$method][$class_data['name']])) {
                                        $class_data['upgrade_version'] = $avail[$method][$class_data['name']]['upgrade_version'];
                                    }
                                    $props = serendipity_plugin_api::setPluginInfo($plugin, $pluginFile, $bag, $class_data, 'local', $method);

                                } elseif (is_array($plugin)) {
                                    // Array is returned if a plugin could be fetched from info cache
                                    $props = $plugin;
                                } else {
                                    $props = false;
                                }

                                if (is_array($props)) {
                                    #print_r($props);
                                    if (version_compare($props['version'], $props['upgrade_version'], '<')) {
                                        // in case of obfuscated hidden remote url, we need to set the Upgrade notice for each, but not any details
                                        echo "UPGRADE: " . ($details ? $class_data['name'] . " -- " . $props['upgrade_version'] : substr($class_data['name'], 0, 18)) . "\n";
                                    } else {
                                        if ($details) echo "OK: " . $class_data['name'] . " -- " . $props['version'] . "\n";
                                    }
                                } else {
                                    if ($details) echo "ERROR: " . $class_data['true_name'] . "\n";
                                }
                            }
                        }
                    }
                    break;

                case 'backend_pluginlisting_header':
                    if (serendipity_db_bool($this->get_config('enable_plugins', 'true'))) {
?>

        <div id="upgrade_notice" class="clearfix">
            <a id="upgrade_plugins" class="button_link" href="?serendipity[adminModule]=plugins&amp;serendipity[adminAction]=addnew&amp;serendipity[only_group]=UPGRADE"><?php echo PLUGIN_EVENT_SPARTACUS_CHECK ?></a>
        </div>

<?php
                    }
                    break;

                case 'backend_templates_fetchlist':
                    if (serendipity_db_bool($this->get_config('enable_themes', 'false'))) {
                        $eventData = $this->buildTemplateList($this->fetchOnline('template', true), 'template');
                    }
                    break;

                case 'backend_templates_fetchtemplate':
                    if (serendipity_db_bool($this->get_config('enable_themes', 'false'))) {
                        if (!empty($eventData['GET']['spartacus_fetch'])) {
                            $this->download(
                                $this->fetchOnline('template', true),
                                $eventData['GET']['theme'],
                                'templates'
                            );
                        }
                    }
                    break;

                case 'backend_plugins_fetchlist':
                    if (serendipity_db_bool($this->get_config('enable_plugins', 'true'))) {
                        $type = (isset($serendipity['GET']['type']) && !empty($serendipity['GET']['type'])) ? $serendipity['GET']['type'] : 'sidebar';

                        $eventData = array(
                           'pluginstack' => $this->buildList($this->fetchOnline($type), $type),
                           'errorstack'  => array(),
                           'upgradeURI'  => '&amp;serendipity[spartacus_upgrade]=true',
                           'baseURI'     => '&amp;serendipity[spartacus_fetch]=' . $type
                        );
                        // remove here deprecated plugins from list by option?
                        #echo '<pre>' . print_r($eventData, true) . '</pre>';
                    }
                    break;

                case 'backend_plugins_fetchplugin':
                    if (serendipity_db_bool($this->get_config('enable_plugins', 'true'))) {
                        if (!empty($eventData['GET']['spartacus_fetch'])) {
                            $baseDir = $this->download(
                                $this->fetchOnline($eventData['GET']['spartacus_fetch'], true),
                                $eventData['GET']['install_plugin']
                            );

                            if ($baseDir === false) {
                                $eventData['install'] = false;
                            } elseif (!empty($baseDir)) {
                                $eventData['GET']['pluginPath'] = $baseDir;
                            } else {
                                $eventData['GET']['pluginPath'] = $eventData['GET']['install_plugin'];
                            }

                            if (isset($eventData['GET']['spartacus_upgrade']) && $eventData['GET']['spartacus_upgrade']) {
                                $eventData['install'] = false;
                            }
                        }
                    }
                    break;

                case 'backend_directory_create':
                    if (serendipity_db_bool($this->get_config('use_ftp')) && (!is_dir($eventData))) {
                        return $this->make_dir_via_ftp($eventData);
                    }
                    break;

                default:
                    return false;

            }
            return true;
        } else {
            return false;
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>