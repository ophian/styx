<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (defined('S9Y_FRAMEWORK_FUNCTIONS')) {
    return;
}
@define('S9Y_FRAMEWORK_FUNCTIONS', true);

$serendipity['imageList'] = array();

if (IS_installed === true) {
    include_once(S9Y_INCLUDE_PATH . 'include/db/db.inc.php');
}
include_once(S9Y_INCLUDE_PATH . 'include/compat.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_config.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/plugin_api.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_images.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_installer.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_entries.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_comments.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_permalinks.inc.php');
include_once(S9Y_INCLUDE_PATH . 'include/functions_smarty.inc.php');

/**
 * Retrieve the raw request entity (body)
 *
 * @since 2.1
 * @return string
 */
function get_raw_data() {
    return file_get_contents( 'php://input' );
}

/**
 * Set a new PEAR Request object
 * Includes the required PHP5 PEAR Request2 class and
 * fixes failing CERT validation check for PHP versions below 5.6
 * Make new Request Object
 *
 * @since   2.1
 * @param   $url        string
 * @param   $method     string  Request method for send() (get,head,post,put,delete,trace,conn)
 *                      one of the methods defined in RFC 2616 (https://www.w3.org/Protocols/rfc2616/rfc2616-sec9.html)
 * @param   $options    array   Request parameter
 *
 * @return  object
 */
function serendipity_request_object($url = '', $method = 'get', $options = array()) {
    require_once S9Y_PEAR_PATH . 'HTTP/Request2.php';
    require_once S9Y_PEAR_PATH . 'HTTP/Request2/ConnectionException.php';

    switch($method) {
        case 'get':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_GET, $options);
            break;

        case 'head':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_HEAD, $options);
            break;

        case 'post':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_POST, $options);
            break;

        case 'put':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_PUT, $options);
            break;

        case 'delete':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_DELETE, $options);
            break;

        case 'trace':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_TRACE, $options);
            break;

        case 'conn':
        case 'connnect':
            $req = new HTTP_Request2($url, HTTP_Request2::METHOD_CONNECT, $options);
            break;

        default:
            return false;
    }

    return $req;
}

/**
 * Request the contents of an URL, API wrapper [A Serendipity successor of the Styx serendipity_request_object() wrapper]
 *
 * @param $uri string               The URL to fetch
 * @param $method string            HTTP method (GET/POST/PUT/OPTIONS...)
 * @param $contenttype string       optional HTTP content type
 * @param $contenttype mixed        optional extra data (i.e. POST body), can be an array
 * @param $extra_options array      Extra options for HTTP_Request $options array (can override defaults)
 * @param $addData string           possible extra event addData declaration for 'backend_http_request' hook
 * @param $auth array               Array with 'user' and 'pass' for HTTP Auth
 *
 * @return $content string          The URL contents
 */
function serendipity_request_url($uri, $method = 'GET', $contenttype = null, $data = null, $extra_options = null, $addData = null, $auth = null) {
    global $serendipity;

    require_once S9Y_PEAR_PATH . 'HTTP/Request2.php';
    require_once S9Y_PEAR_PATH . 'HTTP/Request2/ConnectionException.php';
    $options = array('follow_redirects' => true, 'max_redirects' => 5);

    if (is_array($extra_options)) {
        foreach($extra_options AS $okey => $oval) {
            $options[$okey] = $oval;
        }
    }
    serendipity_plugin_api::hook_event('backend_http_request', $options, $addData);
    serendipity_request_start();

    switch(strtoupper($method)) {
        case 'GET':
            $http_method = HTTP_Request2::METHOD_GET;
            break;
        case 'PUT':
            $http_method = HTTP_Request2::METHOD_PUT;
            break;
        case 'OPTIONS':
            $http_method = HTTP_Request2::METHOD_OPTIONS;
            break;
        case 'HEAD':
            $http_method = HTTP_Request2::METHOD_HEAD;
            break;
        case 'DELETE':
            $http_method = HTTP_Request2::METHOD_DELETE;
            break;
        case 'TRACE':
            $http_method = HTTP_Request2::METHOD_TRACE;
            break;
        case 'CONNECT':
            $http_method = HTTP_Request2::METHOD_CONNECT;
            break;
        default:
        case 'POST':
            $http_method = HTTP_Request2::METHOD_POST;
            break;
    }
    $req = new HTTP_Request2($uri, $http_method, $options);
    if (isset($contenttype) && $contenttype !== null) {
       $req->setHeader('Content-Type', $contenttype);
    }

    if (is_array($auth)) {
        $req->setAuth($auth['user'], $auth['pass']);
    }
    if (null !== $data) {
        if (is_array($data)) {
            $req->addPostParameter($data);
        } else {
            $req->setBody($data);
        }
    }
    try {
        $res = $req->send();
    } catch (HTTP_Request2_Exception $e) {
        serendipity_request_end();
        return false;
    }
    $fContent = $res->getBody();
    $serendipity['last_http_request'] = array(
        'responseCode' => $res->getStatus(),
        'effectiveUrl' => $res->getEffectiveUrl(),
        'reasonPhrase' => $res->getReasonPhrase(),
        'isRedirect'   => $res->isRedirect(),
        'cookies'      => $res->getCookies(),
        'version'      => $res->getVersion(),
        'header'       => $res->getHeader(),
        'object'       => $res // forward compatibility for possible other checks
    );

    serendipity_request_end();
    return $fContent;
}

/**
 * Serendipity strpos mapper to check flat arrays
 *
 * @access public
 * @param   string   The haystack
 * @param   array    The needle
 * @return  boolean
 */
function serendipity_contains($str, array $arr) {
    foreach($arr AS $a) {
        if (false !== @strpos($str, $a)) return true; // mute possible uninitialized items
    }
    return false;
}

/**
 * Serendipity strpos iteration mapper to also check needled arrays
 *
 * @access public
 * @param   string          The haystack
 * @param   string/array    The needle
 * @return
 */
function serendipity_strpos($haystack, $needles) {
    if (is_array($needles)) {
        foreach($needles AS $str) {
            // keep in mind if needle is not a string, it is converted to an integer and applied as the ordinal value of a character
            if (is_string($str)) {
                return strpos($haystack, $str);
            } else {
                serendipity_strpos($haystack, $str);
            }
        }
    } else {
        return strpos($haystack, $needles);
    }
}

/**
 * Check a multidimensional array for set values
 * Use array_filter() before, to already filter out empty values in the primary dimension.
 *
 * @access public
 * @param   array   An array of restricting filter sets
 * @return  bool    Result-set of empty values
 */
function serendipity_emptyArray($array) {
    $empty = true;
    if (is_array($array)) {
        foreach ($array AS $value) {
            if (!serendipity_emptyArray($value)) {
                $empty = false;
            }
        }
    }
    elseif (!empty($array)) {
        $empty = false;
    }
    return $empty;
}

/**
 * Return the HTTP protocol sent by the server.
 *
 * @access public
 * @return string The HTTP protocol. Default fallback HTTP/1.1 for 304.
 */
function serendipity_getServerProtocol(): string {
    $protocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
    if ( ! in_array( $protocol, [ 'HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3' ], true ) ) {
        $protocol = 'HTTP/1.1';
    }
    return $protocol;
}

/**
 * Return the 304 Not Modified header.
 *
 * @access public
 * @return void
 */
function serendipity_setNotModifiedHeader() {
    //
    // Fetch output buffer containing the CSS output and create eTag header
    //
    $ob_buffer = ob_get_contents();

    if ($ob_buffer) {

        // Calculate hash for eTag and get request header. The hash value
        // changes with every modification to any part of the CSS styles.
        // This includes the installation of a plugin that adds plugin
        // specific styles.

        // Send ETag header using the hash value of the CSS code
        $hashValue = hash('xxh3', $ob_buffer);
        header('ETag: "' . $hashValue . '"');

        // Compare value of If-None-Match header (if available) to hash value
        if (!empty($_SERVER['HTTP_IF_NONE_MATCH'])) {
            // Get request header value and chop off optional quotes
            $reqHeader = trim($_SERVER['HTTP_IF_NONE_MATCH'], '"');

            if ($hashValue === $reqHeader) {
                // Tell client to use the cached version and destroy output buffer
                header(serendipity_getServerProtocol() . ' 304 Not Modified', true, 304); // force
                header('Status: 304 Not Modified'); // overwrite Status 200
                header('Vary: Accept-Encoding');
                ob_clean();
            }
        }
    }

    ob_end_flush();
}

/**
 * Get the Referrer calling function name for the current HTTP Request
 *
 * @access public
 * @return string parent level function name
 */
function serendipity_debugCallerId() {
    $trace = debug_backtrace();
    $level = count($trace)-1;
    return $trace[$level]['function'];
}

/**
 * Truncate a string to a specific length, multibyte aware. Appends '...' if successfully truncated
 *
 * @access public
 * @param   string  Input string
 * @param   int     Length the final string should have
 * @return  string  Truncated string
 */
function serendipity_truncateString($s, $len) {
    if (strlen($s) > ($len+3)) {
        $s = serendipity_mb('substr', $s, 0, $len) . '...';
    }
    return $s;
}

/**
 * Optionally turn on GZip Compression, if configured
 *
 * @access public
 */
function serendipity_gzCompression() {
    global $serendipity;

    if (isset($serendipity['useGzip']) && serendipity_db_bool($serendipity['useGzip'])
        && function_exists('ob_gzhandler') && extension_loaded('zlib')
        && serendipity_ini_bool(ini_get('zlib.output_compression')) == false
        && serendipity_ini_bool(ini_get('session.use_trans_sid')) == false) {
        ob_start('ob_gzhandler');
    }
}

/**
 * Validate input entry dates only
 *
 * @access public
 * @param   string  Input string
 * @return  bool
 */
function serendipity_validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date; // be strict
}

/**
 * Convert input entry strftime() dates to use DateTime Interface successor.
 * strftime is deprecated with PHP 8.1 + and will be removed by PHP 9.
 * This function is for converting old strftime date data only. Please use the DateTime successor for newer approaches.
 *
 * Locale-formatted strftime using \IntlDateFormatter (PHP 8.1 ++ compatible)
 * This provides a cross-platform alternative to strftime() for when it will be removed from PHP.
 * Note that output can be slightly different between libc sprintf and this function as it is using ICU.
 *
 * @access public
 * @param  string $format Date format
 * @param  integer|string|DateTime $timestamp Timestamp
 * @return string
 *
 * Origin Usage:
 * use function \PHP81_BC\strftime;
 * echo strftime('%A %e %B %Y %X', new \DateTime('2021-09-28 00:00:00'), 'fr_FR');
 *
 * @author BohwaZ <https://bohwaz.net/>
 * @see https://gist.github.com/bohwaz/42fc223031e2b2dd2585aab159a20f30
 */
function serendipity_toDateTimeMapper(string $format, $timestamp = null, ?string $locale = null): string {

    if (null === $timestamp) {
        $timestamp = new \DateTime;
    }
    elseif (is_numeric($timestamp)) {
        $timestamp = date_create('@' . $timestamp);

        if ($timestamp) {
            $timestamp->setTimezone(new \DateTimezone(date_default_timezone_get()));
        }
    }
    elseif (is_string($timestamp)) {
        $timestamp = date_create($timestamp);
    }

    if (!($timestamp instanceof \DateTimeInterface)) {
        throw new \InvalidArgumentException('The given $timestamp argument is neither a valid UNIX timestamp, a valid date-time string nor a DateTime object.');
    }

    $locale = substr((string) $locale, 0, 5);

    $intl_formats = [
        '%a' => 'ccc',    // An abbreviated textual representation of the day Sun through Sat
        '%A' => 'EEEE',   // A full textual representation of the day Sunday through Saturday
        '%b' => 'LLL',    // Abbreviated month name, based on the locale Jan through Dec
        '%B' => 'MMMM',   // Full month name, based on the locale January through December
        '%h' => 'MMM',    // Abbreviated month name, based on the locale (an alias of %b) Jan through Dec
    ];

    $intl_formatter = function (\DateTimeInterface $timestamp, string $format) use ($intl_formats, $locale) {
        $tz = $timestamp->getTimezone();
        $date_type = \IntlDateFormatter::FULL;
        $time_type = \IntlDateFormatter::FULL;
        $pattern = '';

        // %c = Preferred date and time stamp based on locale
        // Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
        if ($format == '%c') {
            $date_type = \IntlDateFormatter::LONG;
            $time_type = \IntlDateFormatter::SHORT;
        }
        // %x = Preferred date representation based on locale, without the time
        // Example: 02/05/09 for February 5, 2009
        elseif ($format == '%x') {
            $date_type = \IntlDateFormatter::SHORT;
            $time_type = \IntlDateFormatter::NONE;
        }
        // Localized time format
        elseif ($format == '%X') {
            $date_type = \IntlDateFormatter::NONE;
            $time_type = \IntlDateFormatter::MEDIUM;
        }
        else {
            $pattern = $intl_formats[$format];
        }

        return (new \IntlDateFormatter($locale, $date_type, $time_type, $tz, null, $pattern))->format($timestamp);
    };

    // Same order as https://www.php.net/manual/en/function.strftime.php
    $translation_table = [
        // Day (Later on remove the dot from short abbreviations eg for DE language Mo. Di. etc. in calendar, and comments sidebar plugins)
        '%a' => $intl_formatter,
        '%A' => $intl_formatter,
        '%d' => 'd',
        '%e' => function ($timestamp) {
            return sprintf('% 2u', $timestamp->format('j'));
        },
        '%j' => function ($timestamp) {
            // Day number in year, 001 to 366
            return sprintf('%03d', $timestamp->format('z')+1);
        },
        '%u' => 'N',
        '%w' => 'w',

        // Week
        '%U' => function ($timestamp) {
            // Number of weeks between date and first Sunday of year
            $day = new \DateTime(sprintf('%d-01 Sunday', $timestamp->format('Y')));
            return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
        },
        '%V' => 'W',
        '%W' => function ($timestamp) {
            // Number of weeks between date and first Monday of year
            $day = new \DateTime(sprintf('%d-01 Monday', $timestamp->format('Y')));
            return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
        },

        // Month
        '%b' => $intl_formatter,
        '%B' => $intl_formatter,
        '%h' => $intl_formatter,
        '%m' => 'm',

        // Year
        '%C' => function ($timestamp) {
            // Century (-1): 19 for 20th century
            return floor($timestamp->format('Y') / 100);
        },
        '%g' => function ($timestamp) {
            return substr($timestamp->format('o'), -2);
        },
        '%G' => 'o',
        '%y' => 'y',
        '%Y' => 'Y',

        // Time
        '%H' => 'H',
        '%k' => function ($timestamp) {
            return sprintf('% 2u', $timestamp->format('G'));
        },
        '%I' => 'h',
        '%l' => function ($timestamp) {
            return sprintf('% 2u', $timestamp->format('g'));
        },
        '%M' => 'i',
        '%p' => 'A', // AM PM (this is reversed on purpose!)
        '%P' => 'a', // am pm
        '%r' => 'h:i:s A', // %I:%M:%S %p
        '%R' => 'H:i', // %H:%M
        '%S' => 's',
        '%T' => 'H:i:s', // %H:%M:%S
        '%X' => $intl_formatter, // Preferred time representation based on locale, without the date

        // Timezone
        '%z' => 'O',
        '%Z' => 'T',

        // Time and Date Stamps
        '%c' => $intl_formatter,
        '%D' => 'm/d/Y',
        '%F' => 'Y-m-d',
        '%s' => 'U',
        '%x' => $intl_formatter,
    ];

    $out = preg_replace_callback('/(?<!%)(%[a-zA-Z])/', function ($match) use ($translation_table, $timestamp) {
        if ($match[1] == '%n') {
            return "\n";
        }
        elseif ($match[1] == '%t') {
            return "\t";
        }

        if (!isset($translation_table[$match[1]])) {
            throw new \InvalidArgumentException(sprintf('Format "%s" is unknown in time format', $match[1]));
        }

        $replace = $translation_table[$match[1]];

        if (is_string($replace)) {
            return $timestamp->format($replace);
        }
        else {
            return $replace($timestamp, $match[1]);
        }
    }, $format);

    $out = str_replace('%%', '%', $out);

    return $out;
}

/**
 * Returns a timestamp formatted according to the current Server timezone offset
 *
 * @access public
 * @param  int      The timestamp you want to convert into the current server timezone. Defaults to "now".
 * @param  boolean  A toggle to indicate, if the timezone offset should be ADDED or SUBSTRACTED from the timezone. Subtracting is required to restore original time when posting an entry.
 * @return int      The final timestamp
 */
function serendipity_serverOffsetHour($timestamp = null, $negative = false): int {
    global $serendipity;

    if ($timestamp === null) {
        $timestamp = time();
    }

    if (empty($serendipity['serverOffsetHours']) || !is_numeric($serendipity['serverOffsetHours']) || $serendipity['serverOffsetHours'] == 0) {
        return (int) $timestamp;
    } else {
        return (int) $timestamp + (($negative ? -$serendipity['serverOffsetHours'] : $serendipity['serverOffsetHours']) * 60 * 60);
    }
}

/**
 * Converts a date string (DD.MM.YYYY, YYYY-MM-DD, MM/DD/YYYY) into a UNIX timestamp
 *
 * @access public
 * @param  string  The input date
 * @return int     The output UNIX timestamp
 */
function &serendipity_convertToTimestamp($in) {
    if (preg_match('@([0-9]+)([/\.-])([0-9]+)([/\.-])([0-9]+)@', $in, $m)) {
        if ($m[2] != $m[4]) {
            return $in;
        }

        switch($m[2]) {
            case '.':
                return mktime(0, 0, 0, /* month */ $m[3], /* day */ $m[1], /* year */ $m[5]);
                break;

            case '/':
                return mktime(0, 0, 0, /* month */ $m[1], /* day */ $m[3], /* year */ $m[5]);
                break;

            case '-':
                return mktime(0, 0, 0, /* month */ $m[3], /* day */ $m[5], /* year */ $m[1]);
                break;
        }

        return $in;
    }

    return $in;
}

/**
 * Format a timestamp
 *
 * This function can convert an input timestamp into specific PHP strftime() outputs, including applying necessary timezone calculations.
 *
 * @access public
 * @param   string      Output format for the timestamp
 * @param   int         Timestamp to use for displaying
 * @param   boolean     Indicates, if timezone calculations shall be used.
 * @param   boolean     Whether to use strftime or simply date
 * @return  string      The formatted timestamp
 */
function serendipity_strftime($format, $timestamp = null, $useOffset = true, $useDate = false) {
    global $serendipity;

    if ($useDate) {
        $out = date($format, $timestamp);
    } else {
        switch($serendipity['calendar']) {
            default:
            case 'gregorian':
                if ($timestamp == null) {
                    $timestamp = serendipity_serverOffsetHour();
                } elseif ($useOffset) {
                    $timestamp = serendipity_serverOffsetHour($timestamp);
                }
                /*
                  strftime is infected by thread unsafe locales, which is plenty of reason to deprecate it, with additional pro reasons for doing so being its disparate functionality among different os-es and libc's.
                  Deprecation also doesn't mean removal, which won't happen until PHP 9, giving developers plenty of time to move to a saner threadsafe locale API based on intl/icu.
                  cheers Derick
                  done!
                */
                $out = serendipity_toDateTimeMapper($format, $timestamp, WYSIWYG_LANG);
                break;

            case 'persian-utf8':
                if ($timestamp == null) {
                    $timestamp = serendipity_serverOffsetHour();
                } elseif ($useOffset) {
                    $timestamp = serendipity_serverOffsetHour($timestamp);
                }

                require_once S9Y_INCLUDE_PATH . 'include/functions_calendars.inc.php';
                $out = persian_strftime_utf($format, $timestamp);
                break;
        }
    }

    return $out;
}

/**
 * A wrapper function call for formatting Timestamps.
 *
 * Utilizes serendipity_strftime() and prepares the output timestamp with a few tweaks, and applies automatic uppercasing of the return.
 *
 * @see serendipity_strftime()
 * @param   string      Output format for the timestamp
 * @param   int         Timestamp to use for displaying
 * @param   boolean     Indicates, if timezone calculations shall be used.
 * @param   boolean     Whether to use strftime or simply date
 * @return  string      The formatted timestamp
 */
function serendipity_formatTime($format, $time, $useOffset = true, $useDate = false) {
    static $cache;
    if (!isset($cache)) {
        $cache = array();
    }

    if (!isset($cache[$format])) {
        $cache[$format] = $format;
        if (str_starts_with(strtoupper(PHP_OS), 'WIN')) {
            $cache[$format] = str_replace('%e', '%d', $cache[$format]);
        }
    }

    return serendipity_mb('ucfirst', serendipity_strftime($cache[$format], (int)$time, $useOffset, $useDate));
}

/**
 * Fetches the list of available templates/themes/styles.
 *
 * @access public
 * @param   string  Directory to search for a template [recursive use]
 * @return  array   Sorted array of available template names
 */
function serendipity_fetchTemplates($dir = '') {
    global $serendipity;

    $cdir = @opendir($serendipity['serendipityPath'] . $serendipity['templatePath'] . $dir);
    $rv   = array();
    if (!$cdir) {
        return $rv;
    }
    while (($file = readdir($cdir)) !== false) {
        if (is_dir($serendipity['serendipityPath'] . $serendipity['templatePath'] . $dir . $file)
        && !preg_match('@^(\.|CVS)@i', $file)
        && !file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $dir . $file . '/inactive.txt')) {
            if (file_exists($serendipity['serendipityPath'] . $serendipity['templatePath'] . $dir . $file . '/info.txt')) {
                $key = strtolower($file);
                if (isset($rv[$key])) {
                    $key = $dir . $key;
                }
                $rv[$key] = $dir . $file;
            } else {
                $temp = serendipity_fetchTemplates($dir . $file . '/');
                if (count($temp) > 0) {
                    $rv = array_merge($rv, $temp);
                }
            }
        }
    }
    closedir($cdir);
    ksort($rv);
    return $rv;
}

/**
 * Get information about a specific theme/template/style
 *
 * @access public
 * @param   string  Directory name of a theme
 * @param   string  Absolute path to the templates [for use on CVS mounted directories]
 * @return  array   Associative array if template information
 */
function serendipity_fetchTemplateInfo($theme, $abspath = null) {
    global $serendipity;

    if ($abspath === null) {
        $abspath = $serendipity['serendipityPath'] . $serendipity['templatePath'];
    }

    $lines = @file($abspath . $theme . '/info.txt');
    if (!$lines) {
        return array();
    }
    // init default
    $data['summary'] = $data['description'] = $data['backenddesc'] = $data['backend'] = null; // needs a null init, while the following for loop used [] operator is not supported for strings.
    // This usage files only one regression for later checked string method strtolower($data['backend']) : "Passing null to parameter #1 ($string) of type string is deprecated", which is better easily checked by an isset ternary

    for($x=0; $x < count($lines); $x++) {
        $j = preg_split('/([^\:]+)\:/', $lines[$x], -1, PREG_SPLIT_DELIM_CAPTURE);
        if (!empty($j[2])) {
            $currSec = $j[1];
            $data[strtolower($currSec)][] = trim($j[2]);
        } else {
            $data[strtolower($currSec)][] = trim($j[0]);
        }
    }

    foreach($data AS $k => $v) {
        if (!is_null($v)) {
            $data[$k] = @trim(implode("\n", $v));
        }
    }

    // Split language charset path for themes
    $charsetpath  = (LANG_CHARSET == 'UTF-8') ? '/UTF-8' : '';
    $info_default = S9Y_INCLUDE_PATH . $serendipity['templatePath'] . $theme . '/lang_info_en.inc.php';
    if ((isset($data['summary']) || isset($data['description']) || isset($data['backenddesc'])) && @is_file($info_default)) {
        if (file_exists(S9Y_INCLUDE_PATH . $serendipity['templatePath'] . $theme . $charsetpath. '/lang_info_'.$serendipity['lang'].'.inc.php')) {
            include(S9Y_INCLUDE_PATH . $serendipity['templatePath'] . $theme . $charsetpath. '/lang_info_'.$serendipity['lang'].'.inc.php'); // holds potential $info array
        }
        if (!empty($info)) {
            $data['summary']     = $info['theme_info_summary'] ?? $data['summary'];
            $data['description'] = $info['theme_info_desc']    ?? $data['description'];
            $data['backenddesc'] = $info['theme_info_backend'] ?? $data['backenddesc'];
        } else {
            if (file_exists(S9Y_INCLUDE_PATH . $serendipity['templatePath'] . $theme . '/lang_info_'.$serendipity['lang'].'.inc.php')) {
                include(S9Y_INCLUDE_PATH . $serendipity['templatePath'] . $theme . '/lang_info_'.$serendipity['lang'].'.inc.php'); // holds potential [en] $info array
            }
            if (empty($info)) {
                include($info_default);
            }
            $data['summary']     = $info['theme_info_summary'] ?? $data['summary'];
            $data['description'] = $info['theme_info_desc']    ?? $data['description'];
            $data['backenddesc'] = $info['theme_info_backend'] ?? $data['backenddesc'];
        }
    }

    if (@is_file($serendipity['templatePath'] . $theme . '/config.inc.php')) {
        $data['custom_config'] = YES;
        $data['custom_config_engine'] = $theme;
    }

    // Templates can depend on a possible "Engine" (i.e. "Engine: 2k11").
    // We support the fallback chain also of a template's configuration, so let's check each engine for a config file.
    // No case sensitivity here, since is_file() is safe with all appearances. Take realpath if not!
    if (!empty($data['engine'])) {
        $engines = explode(',', $data['engine']);
        foreach($engines AS $engine) {
            $engine = trim($engine);
            if (empty($engine)) continue;

            if (@is_file($serendipity['templatePath'] . $engine . '/config.inc.php')) {
                $data['custom_config'] = YES;
                $data['custom_config_engine'] = $engine;
            }
        }
    }

    if ($theme != 'default-rtl'
                && @is_dir($serendipity['templatePath'] . $theme . '/admin')
                && strtolower(($data['backend'] ?? '')) == 'yes') {
        $data['custom_admin_interface'] = YES;
    } else {
        $data['custom_admin_interface'] = NO;
    }

    // Templates can depend on a modul only setting (i.e. "Modul: backend"). This might get extended in future...
    if (!empty($data['modul'])) {
        $modul = explode(',', $data['modul']);
        if (strtolower($modul[0]) == 'backend') {
            $data['custom_admin_only_interface'] = true;
            $data['custom_config'] = NO;
        }
    }

    return $data;
}

/**
 * Recursively walks an 1-dimensional array to map parent IDs and depths, depending on the nested array set.
 *
 * Used for sorting a list of comments, for example. The list of comment is iterated, and the nesting level is calculated, and the array will be sorted to represent the amount of nesting.
 *
 * @access public
 * @param   array   Input array to investigate [consecutively sliced for recursive calls]
 * @param   string  Array index name to indicate the ID value of an array index
 * @param   string  Array index name to indicate the PARENT ID value of an array index, matched against the $child_name value
 * @param   int     The parent id to check an element against for recursive nesting
 * @param   int     The current depth of the cycled array
 * @return  array   The sorted and shiny polished result array
 */
function serendipity_walkRecursive($ary, $child_name = 'id', $parent_name = 'parent_id', $parentid = 0, $depth = 0) {
    static $_resArray;
    static $_remain;

    if (!is_array($ary) || sizeof($ary) == 0) {
        return array();
    }

    if ($parentid === VIEWMODE_THREADED) {
        $parentid = 0;
    }

    if ($depth == 0) {
        $_resArray = array();
        $_remain   = $ary;
    }

    foreach($ary AS $key => $data) {
        if ($parentid === VIEWMODE_LINEAR || !isset($data[$parent_name]) || $data[$parent_name] == $parentid) {
            $data['depth'] = $depth;
            $_resArray[]   = $data;
            unset($_remain[$key]);
            if ($data[$child_name] && $parentid !== VIEWMODE_LINEAR ) {
                serendipity_walkRecursive($ary, $child_name, $parent_name, $data[$child_name], ($depth+1));
            }
        }
    }

    /* We are inside a recursive child, and we need to break out */
    if ($depth !== 0) {
        return true;
    }

    if (count($_remain) > 0) {
        // Remaining items need to be appended
        foreach($_remain AS $key => $data) {
            $data['depth'] = 0;
            $_resArray[]   = $data;
        }
    }

    return $_resArray;
}


/**
 * Same Same But Different.
 * Check a users array USERLEVEL privileges to maintain same or others which uses a different operator for sorting out.
 * Both - the old USERLEVEL and the newer GROUP membership based - privileges checks are not totally perfect on their own.
 * In simple and clear group structures this isn't a problem, but not in multi user cases with intertwined (chained) permission sets.
 * Together they can build the correct permission chain.
 *
 * @access public
 * @param   array   A (super)array of serendipity_fetchUsers() authors.
 * @return  array   Array sorted out by USERLEVEL or by matching ID
 */
function serendipity_chainByLevel($users) {
    global $serendipity;

    $soop = serendipity_checkPermission('adminUsersMaintainOthers'); // check privileges same (<) OR others (<=)
    if ($soop) {
        foreach($users AS $user => $userdata) {
            if ($userdata['userlevel'] <= $serendipity['serendipityUserlevel'] || $userdata['authorid'] == $serendipity['authorid'] || $serendipity['serendipityUserlevel'] >= USERLEVEL_ADMIN) {
                $_users[] = $userdata;
            }
        }
    } else {
        foreach($users AS $user => $userdata) {
            if ($userdata['userlevel'] < $serendipity['serendipityUserlevel'] || $userdata['authorid'] == $serendipity['authorid'] || $serendipity['serendipityUserlevel'] >= USERLEVEL_ADMIN) {
                $_users[] = $userdata;
            }
        }
    }

    return $_users;
}


/**
 * Fetch the list of Serendipity Authors
 *
 * @access public
 * @param   int     Fetch only a specific User
 * @param   array   Can contain an array of group IDs you only want to fetch authors of.
 * @param   boolean If set to TRUE, the amount of entries per author will also be returned
 * @return  array   Result of the SQL query
 */
function serendipity_fetchUsers($user = '', $group = null, $is_count = false) {
    global $serendipity;

    $where = '';
    if (!empty($user)) {
        $where = "WHERE a.authorid = '" . (int)$user ."'";
    }

    $query_select   = '';
    $query_join     = '';
    $query_group    = '';
    $query_distinct = '';
    if ($is_count) {
        $query_select = ', count(e.authorid) AS artcount';
        $query_join   = "LEFT OUTER JOIN {$serendipity['dbPrefix']}entries AS e
                                      ON (a.authorid = e.authorid AND e.isdraft = 'false')";
    }

    if ($is_count || $group != null) {
        if ($serendipity['dbType'] == 'postgres' ||
            $serendipity['dbType'] == 'pdo-postgres') {
            // Why does PostgreSQL keep doing this to us? :-)
            $query_group    = 'GROUP BY a.authorid, a.realname, a.username, a.password, a.hashtype, a.mail_comments, a.mail_trackbacks, a.email, a.userlevel, a.right_publish';
            $query_distinct = 'DISTINCT';
        } else {
            $query_group    = 'GROUP BY a.authorid';
            $query_distinct = '';
        }
    }

    if ($group === null) {
        $querystring = "SELECT $query_distinct
                               a.authorid,
                               a.realname,
                               a.username,
                               a.password,
                               a.hashtype,
                               a.mail_comments,
                               a.mail_trackbacks,
                               a.email,
                               a.userlevel,
                               a.right_publish
                               $query_select
                          FROM {$serendipity['dbPrefix']}authors AS a
                               $query_join
                               $where
                               $query_group
                      ORDER BY a.userlevel DESC, a.realname ASC";
    } else {

        if ($group === 'hidden') {
            $query_join .= "
               LEFT OUTER JOIN {$serendipity['dbPrefix']}groupconfig AS gc
                            ON (gc.property = 'hiddenGroup' AND gc.id = ag.groupid AND gc.value = 'true')";
            $where .= ' AND gc.id IS NULL ';
        } elseif (is_array($group)) {
            foreach($group AS $g) {
                $in_groups[] = (int)($g['id'] ?? $g); // be nice to debug sessions
            }
            $group_sql = implode(', ', $in_groups);
        } else {
            $group_sql = (int)$group;
        }

        $querystring = "SELECT $query_distinct
                               a.authorid,
                               a.realname,
                               a.username,
                               a.password,
                               a.hashtype,
                               a.mail_comments,
                               a.mail_trackbacks,
                               a.email,
                               a.userlevel,
                               a.right_publish
                               $query_select
                          FROM {$serendipity['dbPrefix']}authors AS a
               LEFT OUTER JOIN {$serendipity['dbPrefix']}authorgroups AS ag
                            ON a.authorid = ag.authorid
               LEFT OUTER JOIN {$serendipity['dbPrefix']}groups AS g
                            ON ag.groupid  = g.id
                               $query_join
                         WHERE " . (isset($group_sql) ? "g.id IN ($group_sql)" : '1=1') . "
                               $where
                               $query_group
                      ORDER BY a.userlevel DESC, a.realname ASC";
    }

    return serendipity_db_query($querystring);
}


/**
 * Sends a Mail with Serendipity formatting
 *
 * @access public
 * @param   string  The recipient address of the mail
 * @param   string  The subject of the mail
 * @param   string  The body of the mail
 * @param   string  The sender mail address of the mail
 * @param   array   additional headers to pass to the E-Mail
 * @param   string  The name of the sender
 * @return  int     Return code of the PHP mail() function
 */
function serendipity_sendMail($to, $subject, $message, $fromMail, $headers = NULL, $fromName = NULL) {
    global $serendipity;

    if (!is_null($headers) && !is_array($headers)) {
        trigger_error(__FUNCTION__ . ': $headers must be either an array or null', E_USER_ERROR);
    }

    if (is_null($fromName) || empty($fromName)) {
        $fromName = $serendipity['blogTitle'];
    }

    if (is_null($fromMail) || empty($fromMail)) {
        $fromMail = $to;
    }

    if (is_null($headers)) {
        $headers = array();
    }

    // Fix special characters
    $fromName = str_replace(array('"', "\r", "\n"), array("'", '', ''), $fromName);
    $fromMail = str_replace(array("\r","\n"), array('', ''), $fromMail);

    // Prefix all mail with weblog title
    $subject = '['. $serendipity['blogTitle'] . '] '.  $subject;

    // Append signature to every mail
    $message .= "\n" . sprintf(SIGNATURE, $serendipity['blogTitle'], 'Serendipity Styx', 'https://ophian.github.io/');

    $maildata = array(
        'to'       => &$to,
        'subject'  => &$subject,
        'fromName' => &$fromName,
        'fromMail' => &$fromMail,
        'blogMail' => $serendipity['blogMail'],
        'version'  => 'Serendipity' . ($serendipity['expose_s9y'] ? '/' . $serendipity['version'] : ''),
        'legacy'   => true,
        'headers'  => &$headers,
        'message'  => &$message
    );

    serendipity_plugin_api::hook_event('backend_sendmail', $maildata, LANG_CHARSET);

    // This routine can be overridden by a plugin.
    if ($maildata['legacy']) {
        // Check for mb_* function, and use it to encode headers etc. */
        if (function_exists('mb_encode_mimeheader')) {
            // mb_encode_mimeheader function inserts linebreaks after 74 chars.
            // Usually this is according to spec, but for us it caused more trouble than
            // it prevented.
            // Regards to Mark Kronsbein for finding this issue!
            $maildata['subject'] = str_replace(array("\n", "\r"), array('', ''), mb_encode_mimeheader($maildata['subject'], LANG_CHARSET));
            $maildata['fromName'] = str_replace(array("\n", "\r"), array('', ''), mb_encode_mimeheader($maildata['fromName'], LANG_CHARSET));
        }


        // Always add these headers
        if (!empty($maildata['blogMail'])) {
            $maildata['headers'][] = 'From: "'. $maildata['fromName'] .'" <'. $maildata['blogMail'] .'>';
        }
        $maildata['headers'][] = 'Reply-To: "'. $maildata['fromName'] .'" <'. $maildata['fromMail'] .'>';
        if ($serendipity['expose_s9y']) {
            $maildata['headers'][] = 'X-Mailer: ' . $maildata['version'];
            $maildata['headers'][] = 'X-Engine: PHP/'. PHP_VERSION;
        }
        $maildata['headers'][] = 'Message-ID: <'. bin2hex(random_bytes(16)) .'@'. $_SERVER['HTTP_HOST'] .'>';
        $maildata['headers'][] = 'MIME-Version: 1.0';
        $maildata['headers'][] = 'Precedence: bulk';
        $maildata['headers'][] = 'Content-Type: text/plain; charset=' . LANG_CHARSET;
        $maildata['headers'][] = 'Auto-Submitted: auto-generated';

        if (LANG_CHARSET == 'UTF-8') {
            if (function_exists('imap_8bit') && !$serendipity['forceBase64']) {
                $maildata['headers'][] = 'Content-Transfer-Encoding: quoted-printable';
                $maildata['message']   = str_starts_with(strtoupper(PHP_OS), 'WIN') ? imap_8bit($maildata['message']) : str_replace("\r\n", "\n", imap_8bit($maildata['message']));
            } else {
                $maildata['headers'][] = 'Content-Transfer-Encoding: base64';
                $maildata['message']   = chunk_split(base64_encode($maildata['message']));
            }
        }
    }

    if (isset($serendipity['dumpMail']) && $serendipity['dumpMail']) {
        $fp = fopen($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/mail.log', 'a');
        fwrite($fp, date('Y-m-d H:i') . "\n" . print_r($maildata, true));
        fclose($fp);
    }

    if (!isset($maildata['skip_native']) && !empty($maildata['to'])) {
        return @mail($maildata['to'], $maildata['subject'], $maildata['message'], implode("\n", $maildata['headers']));
    }
}

/**
 * Fetch all references (links) from a given entry ID
 *
 * @access public
 * @param   int     The entry ID
 * @return  array   The SQL result containing the references/links of an entry
 */
function serendipity_fetchReferences($id) {
    global $serendipity;

    $query = "SELECT name,link FROM {$serendipity['dbPrefix']}references WHERE entry_id = '" . (int)$id . "' AND (type = '' OR type IS NULL)";

    return serendipity_db_query($query);
}


/**
 * Encode a string to UTF-8, if not already in UTF-8 format.
 *
 * @access public
 * @param   string  The input string
 * @return  string  The output string
 */
function serendipity_utf8_encode($string) {
    if (is_null($string)) return;
    if (strtolower(LANG_CHARSET) != 'utf-8') {
        if (function_exists('iconv')) {
            $new = iconv(LANG_CHARSET, 'UTF-8', $string);
            if ($new !== false) {
                return $new;
            } else {
                return mb_convert_encoding($string, 'UTF-8', LANG_CHARSET); // string, to, from
            }
        } else {
            return mb_convert_encoding($string, 'UTF-8', LANG_CHARSET); // string, to, from
        }
    } else {
        return $string;
    }
}

/**
 * Create a link that can be used within a RSS feed to indicate a permalink for an entry or comment
 *
 * @access public
 * @param   array       The input entry array
 * @param   boolean     Toggle whether the link will be for a COMMENT [true] or an ENTRY [false]
 * @return  string      A permalink for the given entry
 */
function serendipity_rss_getguid($entry, $comments = false) {
    global $serendipity;

    $id = (isset($entry['entryid']) && $entry['entryid'] != '' ? $entry['entryid'] : $entry['id']);

    // When using %id%, we can make the GUID shorter and independent from the title.
    // If not using %id%, the entryid needs to be used for uniqueness.
    if (stristr($serendipity['permalinkStructure'], '%id%') !== FALSE) {
        $title = 'guid';
    } else {
        $title = $id;
    }

    $guid = serendipity_archiveURL(
        $id,
        $title,
        'baseURL',
        true,
        array('timestamp' => $entry['timestamp'])
    );

    if ($comments == true) {
        $guid .= '#c' . $entry['commentid'];
    }

    return $guid;
}

/**
 * Perform some replacement calls to make valid XHTML content
 *
 * Starter function to clean up XHTML for ATOM feeds.
 *
 * @access public
 * @param   string  Input HTML code
 * @return  string  Cleaned HTML code
 */
function xhtml_cleanup($html) {
    static $p = array(
        '/\&([\s\<])/',                 // ampersand followed by whitespace or tag
        '/\&$/',                        // ampersand at end of body
        '/<(br|hr|img)(.*?)\/?>/i',     // commonly used unclosed single tags - attributes included
        '/\&nbsp;/'                     // Protect whitespace
    );

    static $r = array(
        '&amp;\1',
        '&amp;',
        '<\1\2/>',
        '&#160;'
    );

    return preg_replace($p, $r, $html);
}

/**
 * Fetch user data for a specific Serendipity author
 *
 * @access public
 * @param   int     The requested author id
 * @return  array   The SQL result array
 */
function serendipity_fetchAuthor($author) {
    global $serendipity;

    return serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}authors WHERE " . (is_numeric($author) ? "authorid={$author};" : "username='" . serendipity_db_escape_string($author) . "';"));
}

/**
 * Split a filename into basename and extension parts
 *
 * @access public
 * @param   string  Filename
 * @return  array   Return array containing the basename and file extension
 */
function serendipity_parseFileName($file) {
    $x = explode('.', $file);
    if (is_array($x) && count($x) > 1) {
        $suf = array_pop($x);
        $f   = @implode('.', $x);
        return array($f, $suf);
    }
    else {
        return array($file, '');
    }
}

/**
 * Track the referer to a specific Entry ID
 *
 * @access public
 * @param   int     Entry ID
 * @return  null
 */
function serendipity_track_referrer($entry = 0) {
    global $serendipity;

    // Tracking disabled.
    if ($serendipity['trackReferrer'] === false) {
        return;
    }

    if (isset($_SERVER['HTTP_REFERER'])) {
        if (stristr($_SERVER['HTTP_REFERER'], $serendipity['baseURL']) !== false) {
            return;
        }

        if (!isset($serendipity['_blockReferer']) || !is_array($serendipity['_blockReferer'])) {
            // Only generate an array once per call
            $serendipity['_blockReferer'] = array();
            $serendipity['_blockReferer'] = explode(';', $serendipity['blockReferer'] ?? '');
        }

        $url_parts  = parse_url($_SERVER['HTTP_REFERER']);
        $host_parts = explode('.', $url_parts['host']);
        if (!$url_parts['host'] ||
            str_contains($url_parts['host'], $_SERVER['SERVER_NAME'])) {
            return;
        }

        foreach($serendipity['_blockReferer'] AS $idx => $hostname) {
            if (@str_contains($url_parts['host'], $hostname)) {
                return;
            }
        }

        if (rand(0, 100) < 1) {
            serendipity_track_referrer_gc();
        }

        $ts       = serendipity_db_get_interval('ts');
        $interval = serendipity_db_get_interval('interval', 900);

        $url_parts['query'] = substr($url_parts['query'], 0, 255);

        $suppressq = "SELECT count(1)
                      FROM {$serendipity['dbPrefix']}suppress
                      WHERE ip = '" . serendipity_db_escape_string($_SERVER['REMOTE_ADDR']) . "'
                      AND scheme = '" . serendipity_db_escape_string($url_parts['scheme']) . "'
                      AND port = '" . serendipity_db_escape_string($url_parts['port']) . "'
                      AND host = '" . serendipity_db_escape_string($url_parts['host']) . "'
                      AND path = '" . serendipity_db_escape_string($url_parts['path']) . "'
                      AND query = '" . serendipity_db_escape_string($url_parts['query']) . "'
                      AND last > $ts - $interval";

        $suppressp = "DELETE FROM {$serendipity['dbPrefix']}suppress
                      WHERE ip = '" . serendipity_db_escape_string($_SERVER['REMOTE_ADDR']) . "'
                      AND scheme = '" . serendipity_db_escape_string($url_parts['scheme']) . "'
                      AND host = '" . serendipity_db_escape_string($url_parts['host']) . "'
                      AND port = '" . serendipity_db_escape_string($url_parts['port']) . "'
                      AND query = '" . serendipity_db_escape_string($url_parts['query']) . "'
                      AND path = '" . serendipity_db_escape_string($url_parts['path']) . "'";

        $suppressu = "INSERT INTO {$serendipity['dbPrefix']}suppress
                      (ip, last, scheme, host, port, path, query)
                      VALUES (
                      '" . serendipity_db_escape_string($_SERVER['REMOTE_ADDR']) . "',
                      $ts,
                      '" . serendipity_db_escape_string($url_parts['scheme']) . "',
                      '" . serendipity_db_escape_string($url_parts['host']) . "',
                      '" . serendipity_db_escape_string($url_parts['port']) . "',
                      '" . serendipity_db_escape_string($url_parts['path']) . "',
                      '" . serendipity_db_escape_string($url_parts['query']) . "'
                      )";

        $count = serendipity_db_query($suppressq, true);

        if ($count[0] == 0) {
            serendipity_db_query($suppressu);
            return;
        }

        serendipity_db_query($suppressp);
        serendipity_db_query($suppressu);

        serendipity_track_url('referrers', $_SERVER['HTTP_REFERER'], $entry);
    }
}

/**
 * Garbage Collection for suppressed referrers
 *
 * "Bad" referrers, that only occurred once to your entry are put within a
 * SUPPRESS database table. Entries contained there will be cleaned up eventually.
 *
 * @access public
 * @return null
 */
function serendipity_track_referrer_gc() {
    global $serendipity;

    $ts       = serendipity_db_get_interval('ts');
    $interval = serendipity_db_get_interval('interval', 900);
    $gc = "DELETE FROM {$serendipity['dbPrefix']}suppress WHERE last <= $ts - $interval";
    serendipity_db_query($gc);
}

/**
 * Track a URL used in your Blog (Exit-Tracking)
 *
 * @access public
 * @param  string   Name of the DB table where to store the link (exits|referrers)
 * @param  string   The URL to track
 * @param  int      The Entry ID to relate the track to
 * @return null
 */
function serendipity_track_url($list, $url, $entry_id = 0) {
    global $serendipity;

    $url_parts = parse_url($url);
    $url_parts['query'] = substr($url_parts['query'], 0, 255);

    serendipity_db_query(
      @sprintf(
        "UPDATE %s%s
            SET count = count + 1
          WHERE scheme = '%s'
            AND host   = '%s'
            AND port   = '%s'
            AND path   = '%s'
            AND query  = '%s'
            AND day    = '%s'
            %s",

        $serendipity['dbPrefix'],
        $list,
        serendipity_db_escape_string($url_parts['scheme']),
        serendipity_db_escape_string($url_parts['host']),
        serendipity_db_escape_string($url_parts['port']),
        serendipity_db_escape_string($url_parts['path']),
        serendipity_db_escape_string($url_parts['query']),
        date('Y-m-d'),
        ($entry_id != 0) ? "AND entry_id = '". (int)$entry_id ."'" : ''
      )
    );

    if (serendipity_db_affected_rows() == 0) {
        serendipity_db_query(
          sprintf(
            "INSERT INTO %s%s
                    (entry_id, day, count, scheme, host, port, path, query)
             VALUES (%d, '%s', 1, '%s', '%s', '%s', '%s', '%s')",

            $serendipity['dbPrefix'],
            $list,
            (int)$entry_id,
            date('Y-m-d'),
            serendipity_db_escape_string($url_parts['scheme']),
            serendipity_db_escape_string($url_parts['host']),
            serendipity_db_escape_string($url_parts['port']),
            serendipity_db_escape_string($url_parts['path']),
            serendipity_db_escape_string($url_parts['query'])
          )
        );
    }
}

/**
 * Display the list of top referrers
 *
 * @access public
 * @see serendipity_displayTopUrlList()
 * @param  int      Number of referrers to show
 * @param  boolean  Whether to use HTML links for URLs
 * @param  int      Interval for which the top referrers are aggregated
 * @return string   List of Top referrers
 */
function serendipity_displayTopReferrers($limit = 10, $use_links = true, $interval = 7) {
    return serendipity_displayTopUrlList('referrers', $limit, $use_links, $interval);
}

/**
 * Display the list of top exits
 *
 * @access public
 * @see serendipity_displayTopUrlList()
 * @param  int      Number of exits to show
 * @param  boolean  Whether to use HTML links for URLs
 * @param  int      Interval for which the top exits are aggregated
 * @return string   List of Top exits
 */
function serendipity_displayTopExits($limit = 10, $use_links = true, $interval = 7) {
    return serendipity_displayTopUrlList('exits', $limit, $use_links, $interval);
}

/**
 * Display HTML output data of a Exit/Referrer list
 *
 * @access public
 * @see serendipity_displayTopExits()
 * @see serendipity_displayTopReferrers()
 * @param   string      Name of the DB table to show data from (exits|referrers)
 * @param  boolean  Whether to use HTML links for URLs
 * @param  int      Interval for which the top exits are aggregated
 * @return
 */
function serendipity_displayTopUrlList($list, $limit, $use_links = true, $interval = 7) {
    global $serendipity;

    if ($limit) {
        $limit = serendipity_db_limit_sql($limit);
    }

    if ($serendipity['dbType'] == 'mysqli') {
        /* Non portable SQL due to MySQL date functions,
         * but produces rolling 7 day totals, which is more
         * interesting - if has
         */
        $query = "SELECT scheme, host, SUM(count) AS total
                    FROM {$serendipity['dbPrefix']}$list
                   WHERE day > date_sub(current_date, interval " . (int)$interval . " day) OR 1
                GROUP BY host
                ORDER BY total DESC, host
                  $limit";
    } else {
        /* Portable version of the same query */
        $query = "SELECT scheme, host, SUM(count) AS total
                    FROM {$serendipity['dbPrefix']}$list
                GROUP BY scheme, host
                ORDER BY total DESC, host
                  $limit";
    }

    $rows = serendipity_db_query($query);
    $output = '<span class="serendipityReferer">';
    if (is_array($rows)) {
        foreach($rows AS $row) {
            if ($use_links) {
                $output .= sprintf(
                    '<span class="block_level"><a href="%1$s://%2$s" title="%2$s" >%2$s</a> (%3$s) </span>',
                    htmlspecialchars($row['scheme']),
                    htmlspecialchars($row['host']),
                    htmlspecialchars($row['total'])
                );
            } else {
                $output .= sprintf(
                    '<span class="block_level">%1$s (%2$s) </span>',
                    htmlspecialchars($row['host']),
                    htmlspecialchars($row['total'])
                );
            }
        }
    }
    $output .= "</span>\n";
    return $output;
}

/**
 * Return either HTML or XHTML code for an '<a target...> attribute.
 *
 * @access public
 * @param   string  The target to use (_blank, _parent, ...)
 * @return  string  HTML string containing the valid markup for the target attribute.
 */
function serendipity_xhtml_target($target) {
    global $serendipity;

    if ($serendipity['enablePopup'] != true)
        return '';

    return ' onclick="window.open(this.href, \'target' . time() . '\'); return false;" ';
}

/**
 * Parse a URI portion to return which RSS Feed version was requested
 *
 * @access public
 * @param  string  Name of the core URI part
 * @param  string  File extension name of the URI
 * @return string  RSS feed type/version
 */
function serendipity_discover_rss($name, $ext) {
    static $default = '2.0';

    /* Detect type */
    if ($name == 'comments') {
        $type = 'comments';
    } elseif ($name == 'comments_and_trackbacks') {
        $type = 'comments_and_trackbacks';
    } elseif ($name == 'trackbacks') {
        $type = 'trackbacks';
    } else {
        $type = 'content';
    }

    /* Detect version */
    if ($name == 'atom' || $name == 'atom10' || $ext == 'atom') {
        $ver = 'atom1.0';
    } elseif ($name == 'opml' || $ext == 'opml') {
        $ver = 'opml1.0';
    } elseif ($name == 'rdf') {
        $ver = 'rdf';
    } elseif ($ext == 'rss1') {
        $ver = '1.0';
    } else {
        $ver = $default;
    }

    return array($ver, $type);
}

/**
 * Check whether an input string contains "evil" characters used for HTTP Response Splitting
 *
 * @access public
 * @param   string      String to check for evil characters
 * @return  boolean     Return true on success, false on failure
 */
function serendipity_isResponseClean($d) {
    return (strpos($d, "\r") === false && strpos($d, "\n") === false && stripos($d, "%0A") === false && stripos($d, "%0D") === false);
}

/**
 * Create a new Category
 *
 * @access public
 * @param   string  The new category name
 * @param   string  The new category description
 * @param   int     The category owner
 * @param   string  An icon representing the category
 * @param   int     A possible parentid to a category
 * @return  int     The new category's ID
 */
function serendipity_addCategory($name, $desc, $authorid, $icon, $parentid) {
    global $serendipity;

    $query = "INSERT INTO {$serendipity['dbPrefix']}category
                    (category_name, category_description, authorid, category_icon, parentid, category_left, category_right)
                  VALUES
                    ('". serendipity_db_escape_string($name) ."',
                     '". serendipity_db_escape_string($desc) ."',
                      ". (int)$authorid .",
                     '". serendipity_db_escape_string($icon) ."',
                      ". (int)$parentid .",
                       0,
                       0)";

    serendipity_db_query($query);
    $cid = serendipity_db_insert_id('category', 'categoryid');
    serendipity_plugin_api::hook_event('backend_category_addNew', $cid);

    $data = array(
        'categoryid'           => $cid,
        'category_name'        => $name,
        'category_description' => $desc
    );

    serendipity_insertPermalink($data, 'category');
    return $cid;
}

/**
 * Update an existing category
 *
 * @access public
 * @param   int     Category ID to update
 * @param   string  The new category name
 * @param   string  The new category description
 * @param   int     The new category owner
 * @param   string  The new category icon
 * @param   int     The new category parent ID
 * @param   int     The new category sort order
 * @param   int     The new category subcat hiding
 * @return null
 */
function serendipity_updateCategory($cid, $name, $desc, $authorid, $icon, $parentid, $sort_order = 0, $hide_sub = 0, $admin_category = '') {
    global $serendipity;

    $query = "UPDATE {$serendipity['dbPrefix']}category
                    SET category_name = '". serendipity_db_escape_string($name) ."',
                        category_description = '". serendipity_db_escape_string($desc) ."',
                        authorid = ". (int)$authorid .",
                        category_icon = '". serendipity_db_escape_string($icon) ."',
                        parentid = ". (int)$parentid .",
                        sort_order = ". (int)$sort_order . ",
                        hide_sub = ". (int)$hide_sub . "
                    WHERE categoryid = ". (int)$cid ."
                        $admin_category";
    serendipity_db_query($query);
    serendipity_plugin_api::hook_event('backend_category_update', $cid);

    $data = array(
        'id'                   => $cid,
        'categoryid'           => $cid,
        'category_name'        => $name,
        'category_description' => $desc
    );

    serendipity_updatePermalink($data, 'category');
}

/**
 * Ends a session, so that while a file requests happens, Serendipity can work on in that session
 */
function serendipity_request_start() {
    @session_write_close();
    return true;
}

/**
 * Continues a session after a file request
 */
function serendipity_request_end() {
    if (!headers_sent()) {
        session_start();
        return true;
    } else {
        return false;
    }
}

if (!function_exists('microtime_float')) {
    /**
     * Get current timestamp as microseconds
     *
     * @access public
     * @return float    the time
     */
    function microtime_float() {
        list($usec, $sec) = explode(' ', microtime());
        return ((float)$usec + (float)$sec);
    }
}

/**
 * Returns variable message items for sprintf parameter data for spot msg highlights
 *
 * @access public
 * @param   string  The string variable
 * @return  string  The highlight span'ed string
 */
function serendipity_spotify(string $var): string {
    return "<span class=\"msg-spot\">$var</span>";
}

/**
 * Converts Array data to be used as a GET string
 *
 * @access public
 * @param   array   The input array
 * @param   string  An array prefix
 * @param   string  How to join the array
 * @return  string  The HTTP query string
 */
function serendipity_build_query(&$array, $array_prefix = null, $comb_char = '&amp;') {
    $ret = array();
    if (!is_array($array)) {
        return '';
    }

    foreach($array AS $k => $v) {
        $newkey = urlencode($k);
        if ($array_prefix) {
            $newkey = $array_prefix . '[' . $newkey . ']';
        }
        if (is_array($v)) {
            $ret[] = serendipity_build_query($v, $newkey, $comb_char);
        } else {
            $ret[] = $newkey . '=' . urlencode($v);
        }
    }

    return implode($comb_char, $ret);
}

/**
 * Picks a specified key from an array and returns it
 *
 * @access public
 * @param   array   The input array
 * @param   string  The key to search for
 * @param   string  The default value to return when not found
 * @return null
 */
function &serendipity_pickKey(&$array, $key, $default) {
    if (!is_array($array)) {
        return $default;
    }

    // array_key_exists() copies array, so is much slower.
    if (in_array($key, array_keys($array))) {
        if (isset($array[$key])) {
            return $array[$key];
        }
    }
    foreach($array AS $child) {
        if (is_array($child) && isset($child[$key]) && !empty($child[$key])) {
            return $child[$key];
        }
    }

    return $default;
}

/**
 * Retrieves the current timestamp but only deals with minutes to optimize Database caching
 * @access public
 * @return timestamp
 * @author Matthew Groeninger
 */
function serendipity_db_time() {
    static $ts    = null;
    static $cache = 300; // Seconds to cache

    if ($ts === null) {
        $now = time();
        $ts = $now - ($now % $cache) + $cache;
    }

    return $ts;
}

/**
 * Inits the logger.
 * @return null
 */
function serendipity_initLog() {
    global $serendipity;

    if (isset($serendipity['logLevel']) && $serendipity['logLevel'] !== 'Off') {
        if ($serendipity['logLevel'] == 'debug') {
            $log_level = Psr\Log\LogLevel::DEBUG;
        } else {
            $log_level = Psr\Log\LogLevel::ERROR;
        }
        $serendipity['logger'] = new Katzgrau\KLogger\Logger($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/logs', $log_level);
    }
}

/**
 * Check whether a given URL is valid to be locally requested
 * @return boolean
 */
function serendipity_url_allowed($url) {
    global $serendipity;

    if ($serendipity['allowLocalURL']) {
        return true;
    }

    $parts = @parse_url($url);
    if (!is_array($parts) || empty($parts['host'])) {
        return false;
    }

    $host = trim($parts['host'], '.');
    if (preg_match('@^(([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)\.){3}([1-9]?\d|1\d\d|25[0-5]|2[0-4]\d)$@imsU', $host)) {
        $ip = $host;
    } else {
        $ip = gethostbyname($host);
        if ($ip === $host) {
            $ip = false;
        }
    }

    if ($ip) {
        $ipparts = array_map('intval', explode('.', $ip));
        if (127 === $ipparts[0] || 10 === $ipparts[0] || 0 === $ipparts[0] ||
           (172 === $ipparts[0] && 16 <= $ipparts[1] && 31 >= $ipparts[1]) ||
           (192 === $ipparts[0] && 168 === $ipparts[1])
        ) {
            return false;
        }
    }

    return true;
}

use voku\cache\Cache;
// Configure voku/simple-cache to use templates_c as directory for the opcache files, the fallback
// when Memcached and Redis are not used. Returns the configured cache object. Used internally by
// the other cache functions, you most likely never need to call this.
function serendipity_setupCache() {
    $cacheManager = new \voku\cache\CacheAdapterAutoManager();

    $cacheManager->addAdapter(
        \voku\cache\AdapterOpCache::class,
        static function () {
            global $serendipity;
            $cacheDir = $serendipity['serendipityPath'] . '/templates_c/simple_cache';

            return $cacheDir;
        }
    );

    $cacheManager->addAdapter(
        \voku\cache\AdapterArray::class
    );

    $cache = new Cache(
        null,
        null,
        false,
        true,
        false,
        false,
        false,
        false,
        '',
        $cacheManager,
        false
    );
    return $cache;
}

function serendipity_cleanCache() {
    $cache = serendipity_setupCache();
    return $cache->removeAll();
}

function serendipity_cacheItem($key, $item, $ttl = 3600) {
    $cache = serendipity_setupCache();
    return $cache->setItem($key, $item, $ttl);
}

function serendipity_getCacheItem($key) {
    $cache = serendipity_setupCache();
    return $cache->getItem($key);
}

define('serendipity_FUNCTIONS_LOADED', true);
/* vim: set sts=4 ts=4 expandtab : */
