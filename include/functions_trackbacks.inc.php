<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (defined('S9Y_FRAMEWORK_TRACKBACKS')) {
    return;
}
@define('S9Y_FRAMEWORK_TRACKBACKS', true);

/**
 * Check a HTTP response if it is a valid XML trackback response
 *
 * Args:
 *      - HTTP Response string
 * Returns:
 *      - Boolean or error message
 * @access public
 */
function serendipity_trackback_is_success(string $resp) : bool|string {
    if (preg_match('@<error>(\d+)</error>@', $resp, $matches)) {
        if ((int) $matches[1] === 0) {
            return true;
        } else {
            if (preg_match('@<message>([^<]+)</message>@', $resp, $matches)) {
                return $matches[1];
            }
            else {
                return 'unknown error';
            }
        }
    }
    return true;
}

/**
 * Check a HTTP response if it is a valid XML pingback response
 *
 * Args:
 *      - HTTP Response string
 * Returns:
 *      - Boolean
 * @access public
 */
function serendipity_pingback_is_success(string $resp) : bool {
    // This is very rudimentary, but the fault is printed later, so what..
    if (preg_match('@<fault>@', $resp, $matches)) {
        return false;
    }
    return true;
}

/**
 * Perform a HTTP query for autodiscovering a pingback URL
 *
 * Args:
 *      - The URL to try autodiscovery
 *      - The HTML of the source URL
 *      - The URL of our blog article
 * Returns:
 *      - boolean
 * @access public
 */
function serendipity_pingback_autodiscover(string $loc, string $body, ?string $url = null) : bool {
    global $serendipity;

    // This is the old way, sending pingbacks, for downward compatibility.
    // But this is wrong, as it does link from the main blog URL instead of the article URL
    if (!isset($url)) {
        $url = $serendipity['baseURL'];
    }

    if (!empty($_SERVER['X-PINGBACK'])) {
        $pingback = $_SERVER['X-PINGBACK'];
    } elseif (preg_match('@<link rel="pingback" href="([^"]+)" ?/?>@i', $body, $matches)) {
        $pingback = $matches[1];
    } else {
        echo '<div>&#8226; ' . sprintf(PINGBACK_FAILED, PINGBACK_NOT_FOUND) . '</div>';
        return false;
    }

    // xml-rpc pingback call
    $query = "<?xml version=\"1.0\"?>
<methodCall>
  <methodName>pingback.ping</methodName>
  <params>
    <param>
      <value><string>$url</string></value>
    </param>
    <param>
      <value><string>$loc</string></value>
    </param>
  </params>
</methodCall>";

    echo '<div>&#8226; ' . sprintf(PINGBACK_SENDING, htmlspecialchars($pingback)) . '</div>';
    flush();

    $response =  serendipity_send($pingback, $query, 'text/html');
    $success  =  serendipity_pingback_is_success($response);
    if ($success == true) {
        echo '<div>&#8226; ' . PINGBACK_SENT .'</div>';
    } else {
        echo '<div>&#8226; ' . sprintf(PINGBACK_FAILED, $response) . '</div>';
    }
    return $success;
}

/**
 * Send a track/pingback ping
 *
 * Args:
 *      - The URL to send a trackback to
 *      - The XML data with the trackback contents
 * Returns:
 *      - Response string or false
 * @access public
 */
function serendipity_send(string $loc, string $data, ?string $contenttype = null) : string|false {
    #global $serendipity;

    $target = parse_url($loc);
    if (isset($target['query']) && $target['query'] != '') {
        $target['query'] = '?' . str_replace('&amp;', '&', $target['query']);
    } else {
        $target['query'] = '';
    }

    if ($target['scheme'] == 'https' && empty($target['port'])) {
       $uri = $target['scheme'] . '://' . $target['host'] . $target['path'] . $target['query'];
    } elseif (!isset($target['port']) || !is_numeric($target['port'])) {
       $target['port'] = 80;
       $uri = $target['scheme'] . '://' . $target['host'] . ':' . $target['port'] . $target['path'] . $target['query'];
    }

    $options = array('follow_redirects' => true, 'max_redirects' => 5);
    serendipity_plugin_api::hook_event('backend_http_request', $options, 'trackback_send');

    $fContent = serendipity_request_url($uri, 'POST', $contenttype, $data, $options, 'trackback_send');
    #echo '<pre>serendipity_send() '.print_r($serendipity['last_http_request'], true).'</pre>';

    return $fContent;
}

/**
 * Autodiscover a trackback location URL
 *
 * Args:
 *      - The HTML of the source URL
 *      - The source URL
 *      - The URL of our blog
 *      - The author of our entry
 *      - The title of our entry
 *      - The text of our entry
 *      - A comparison URL
 * Returns:
 *      - Response string OR boolean
 * @access public
 */
function serendipity_trackback_autodiscover(string $res, string $loc, string $url, string $author, string $title,string  $text, string $loc2 = '') : string|bool {
    $is_wp    = false;
    $wp_check = false;

    // the new detection method via rel=trackback should have priority
    if (preg_match('@link\s*rel=["\']trackback["\'].*href=["\'](https?:[^"\']+)["\']@i', $res, $matches)) {
        $trackURI = trim($matches[1]);
    } else {
        if (preg_match('@((' . preg_quote($loc, '@') . '|' . preg_quote($loc2, '@') . ')/?trackback/)@i', $res, $wp_loc)) {
            // We found a WP-blog that may not advertise RDF-Tags!
            $is_wp = true;
        }

        if (!preg_match('@trackback:ping(\s*rdf:resource)?\s*=\s*["\'](https?:[^"\']+)["\']@i', $res, $matches)) {
            $matches = array();
            serendipity_plugin_api::hook_event('backend_trackback_check', $matches, $loc);

            // Plugins may say that a URI is valid, in situations where a blog has no valid RDF metadata
            if (empty($matches[2])) {
                if ($is_wp) {
                    $wp_check = true;
                } else {
                    echo '<div>&#8226; ' . sprintf(TRACKBACK_FAILED, TRACKBACK_NOT_FOUND) . "</div>\n";
                    return false;
                }
            }
        }

        $trackURI = trim($matches[2]);

        if (preg_match('@dc:identifier\s*=\s*["\'](https?:[^\'"]+)["\']@i', $res, $test)) {
            if ($loc != $test[1] && $loc2 != $test[1]) {
                if ($is_wp) {
                    $wp_check = true;
                } else {
                    echo '<div>&#8226; ' . sprintf(TRACKBACK_FAILED, TRACKBACK_URI_MISMATCH) . "</div>\n";
                    return false;
                }
            }
        }

        // If $wp_check is set it means no RDF metadata was found, and we simply try the /trackback/ url.
        if ($wp_check) {
            $trackURI = $wp_loc[0];
        }
    }

    $data = 'url='        . rawurlencode($url)
          . '&title='     . rawurlencode($title)
          . '&blog_name=' . rawurlencode($author)
          . '&excerpt='   . rawurlencode(strip_tags($text));

    printf(TRACKBACK_SENDING, htmlspecialchars($trackURI));
    flush();

    $response = serendipity_trackback_is_success(serendipity_send($trackURI, $data));

    if ($response === true) {
        echo '<div>&#8226; ' . TRACKBACK_SENT . "</div>\n";
    } else {
        echo '<div>&#8226; ' . sprintf(TRACKBACK_FAILED, $response) . "</div>\n";
    }

    return $response;
}

/**
 * Open a URL and autodetect contained ping/trackback locations
 *
 * Args:
 *      - The URL to autodetect/try
 *      - The URL to our blog
 *      - The author of our entry
 *      - The title of our entry
 *      - The body of our entry
 * Returns:
 *      - void
 * @access public
 */
function serendipity_reference_autodiscover(string $loc, string $url, string $author, string $title, string $text) : void {
    #global $serendipity;

    $timeout = 30;
    $u = parse_url($loc);
    $u['scheme'] = $u['scheme'] ?? '';

    if ($u['scheme'] != 'http' && $u['scheme'] != 'https') {
        return;
    } elseif ($u['scheme'] == 'https' && !extension_loaded('openssl')) {
        return; // Trackbacks to HTTPS URLs can only be performed with openssl activated
    }

    if (empty($u['port'])) {
        $u['port'] = 80;
        $port = '';
    } else {
        $port = ':' . $u['port'];
    }

    if (empty($u['path'])) {
        echo '<div>&#8226; ' . TRACKBACK_NO_DATA . '</div>';
        return;
    }

    if (!empty($u['query'])) {
        $u['path'] .= '?' . $u['query'];
    }

    $parsed_loc = $u['scheme'] . '://' . $u['host'] . $port . $u['path'];

    if (preg_match('@\.(jpe?g|aiff?|gif|png|webp|avifs?|pdf|doc|rtf|wave?|mp2|mp4|mpe?g3|mpe?g4|divx|xvid|bz2|mpe?g|avi|mp3|xl?|ppt|pps|xslt?|xsd|zip|tar|t?gz|swf|rm|ram?|exe|phar|mov|qt|midi?|qcp|emf|wmf|snd|pmg|w?bmp|gcd|mms|ogg|ogm|rv|wmv|wma|jad|3g?|jar)$@i', $u['path'])) {
        // echo '<div>&#8226; ' . TRACKBACK_NO_DATA . '</div>';
        return;
    }

    echo '<div>&#8226; '. str_replace(array('Trackbacks', 'trackbacks'), array('Track-/Pingbacks', 'track-/pingbacks'), sprintf(TRACKBACK_CHECKING, $loc)) .'</div>'; // this current constant is trackback only - it should be both. Return better for all those, which are [de, en] and are not already translated to other languages.
    flush();

    $options = array('follow_redirects' => true, 'max_redirects' => 5);
    serendipity_plugin_api::hook_event('backend_http_request', $options, 'trackback_detect');

    $fContent = serendipity_request_url($parsed_loc, extra_options: $options, addData: 'trackback_detect');
    #echo '<pre>serendipity_reference_autodiscover() '.print_r($serendipity['last_http_request'], true).'</pre>';

    if (false === $fContent) {
        echo '<div>&#8226; ' . sprintf(TRACKBACK_COULD_NOT_CONNECT, $u['host'], $u['port']) .'</div>';
        return;
    }

    if (strlen($fContent) != 0) {
        $trackback_result = serendipity_trackback_autodiscover($fContent, $parsed_loc, $url, $author, $title, $text, $loc);
        if ($trackback_result == false) {
            serendipity_pingback_autodiscover($parsed_loc, $fContent, $url);
        }
    } else {
        echo '<div>&#8226; ' . TRACKBACK_NO_DATA . '</div>';
    }
    echo '<hr noshade="noshade" />';
}

/**
 * Receive a trackback
 *
 * Args:
 *      - The ID of our entry
 *      - The title of the foreign blog
 *      - The URL of the foreign blog
 *      - The name of the foreign blog
 *      - The excerpt text of the foreign blog
 * Returns:
 *      - boolean
 * @access public
 */
function add_trackback(int $id, string $title, string $url, string $name, string $excerpt) : bool {
    log_trackback('[' . date('d.m.Y H:i') . '] add_trackback:' . print_r(func_get_args(), true));

    // We can't accept a trackback if we don't get any URL
    // This is a protocol rule.
    if (empty($url)) {
        log_trackback('[' . date('d.m.Y H:i') . '] Empty URL.');
        return false;
    }

    // If title is not provided, the value for url will be set as the title
    // This is a protocol rule.
    if (empty($title)) {
        $title = $url;
    }

    // Decode HTML Entities
    $excerpt = trackback_body_strip($excerpt);

    log_trackback('[' . date('d.m.Y H:i') . '] Trackback body: ' . $excerpt);

    $comment = array(
        'title'   => $title,
        'url'     => $url,
        'name'    => $name,
        'comment' => $excerpt
    );

    $is_utf8 = strtolower(LANG_CHARSET) == 'utf-8';

    log_trackback('[' . date('d.m.Y H:i') . '] TRACKBACK TRANSCODING CHECK');

    foreach($comment AS $idx => $field) {
        if (is_utf8($field)) {
            // Trackback is in UTF-8. Check if our blog also is UTF-8.
            if (!$is_utf8) {
                log_trackback('[' . date('d.m.Y H:i') . '] Transcoding ' . $idx . ' from UTF-8 to ISO');
                $comment[$idx] = mb_convert_encoding($field, 'ISO-8859-1', 'UTF-8'); // string, to, from
            }
        } else {
            // Trackback is in some native format. We assume ISO-8859-1. Check if our blog is also ISO.
            if ($is_utf8) {
                log_trackback('[' . date('d.m.Y H:i') . '] Transcoding ' . $idx . ' from ISO to UTF-8');
                $comment[$idx] = mb_convert_encoding($field, 'UTF-8', 'ISO-8859-1'); // string, to, from
            }
        }
    }

    log_trackback('[' . date('d.m.Y H:i') . '] TRACKBACK DATA: ' . print_r($comment, true) . '...');
    log_trackback('[' . date('d.m.Y H:i') . '] TRACKBACK STORING...');

    if ($id > 0) {
        // first check, if we already have this trackback
        $comments = serendipity_fetchComments($id, '1', 'co.id', true, 'TRACKBACK', " AND co.url='" . serendipity_db_escape_string($url) . "'");
        if (is_array($comments) && sizeof($comments) == 1) {
            log_trackback('We already have that TRACKBACK!');
            return false; // We already have it!
        }
        // We don't have it, so save the pingback
        serendipity_saveComment($id, $comment, 'TRACKBACK');
        return true;
    } else {
        return false;
    }
}

/**
 * Receive a pingback
 *
 * Args:
 *      - The entry id to receive a pingback for
 *      - The foreign postdata to add
 * Returns:
 *      - boolean
 * @access public
 */
function add_pingback(int $id, string $postdata) : bool {
    log_pingback("Reached add_pingback. ID:[$id]");

    // XML-RPC Method call without named parameter. This seems to be the default way using XML-RPC
    if (preg_match('@<methodCall>\s*<methodName>\s*pingback.ping\s*</methodName>\s*<params>\s*<param>\s*<value>\s*<string>([^<]*)</string>\s*</value>\s*</param>\s*<param>\s*<value>\s*<string>([^<]*)</string>\s*</value>\s*</param>\s*</params>\s*</methodCall>@is', $postdata, $matches)) {
        log_pingback('Pingback wp structure.');
        $remote             = $matches[1];
        $local              = $matches[2];
        log_pingback("remote=$remote, local=$local");
        $path = parse_url($remote);
        $comment['title']   = 'PingBack';
        $comment['url']     = $remote;
        $comment['comment'] = '';
        $comment['name']    = $path['host'];
        fetchPingbackData($comment);

        // if no ID parameter was given, try to get one from targetURI
        if (!isset($id) || $id == 0) {
            log_pingback('ID not found');
            $id = evaluateIdByLocalUrl($local);
            log_pingback("ID set to $id");
        }

        if ($id > 0) {
            // first check, if we already have this pingback
            $comments = serendipity_fetchComments($id, '1', 'co.id', true, 'PINGBACK', " AND co.url='" . serendipity_db_escape_string($remote) . "'");
            if (is_array($comments) && sizeof($comments) == 1) {
                log_pingback('We already have that PINGBACK!');
                return false; // We already have it!
            }
            // We don't have it, so save the pingback
            serendipity_saveComment($id, $comment, 'PINGBACK');
            return true;
        } else {
            return false;
        }
    }

    // XML-RPC Method call with named parameter. I'm not sure, if XML-RPC supports this, but just to be sure
    $sourceURI = getPingbackParam('sourceURI', $postdata);
    $targetURI = getPingbackParam('targetURI', $postdata);
    if (isset($sourceURI) && isset($targetURI)) {
        log_pingback('Pingback spec structure.');
        $path = parse_url($sourceURI);
        $local              = $targetURI;
        $comment['title']   = 'PingBack';
        $comment['url']     = $sourceURI;
        $comment['comment'] = '';
        $comment['name']    = $path['host'];
        fetchPingbackData($comment);

        // if no ID parameter was given, try to get one from targetURI
        if (!isset($id) || $id == 0) {
            log_pingback('ID not found');
            $id = evaluateIdByLocalUrl($local);
            log_pingback("ID set to $id");
        }
        if ($id > 0) {
            serendipity_saveComment($id, $comment, 'PINGBACK');
            return true;
        } else {
            return false;
        }
    }

    return false;
}

/**
 *
 * Args:
 *      - The localUrl string
 * Returns:
 *      - integer matching
 * @access public
 */
function evaluateIdByLocalUrl(string $localUrl) : int {
    global $serendipity;

    // Build an ID searchpattern in configured permaling structure:
    $permalink_article = $serendipity['permalinkStructure'];
    log_pingback("perma: $permalink_article");
    $permalink_article = str_replace('.','\.', $permalink_article);
    $permalink_article = str_replace('+','\+', $permalink_article);
    $permalink_article = str_replace('?','\?', $permalink_article);
    $permalink_article = str_replace('%id%','(\d+)', $permalink_article);
    $permalink_article = str_replace('%title%','[^/]*', $permalink_article);
    $permalink_article_regex = '@' . $permalink_article . '$@';
    log_pingback("regex: $permalink_article_regex");

    if (preg_match($permalink_article_regex, $localUrl, $matches)) {
        return (int)$matches[1];
    } else {
        return 0;
    }
}

/**
 * Gets a XML-RPC pingback.ping value by given parameter name
 *
 * Args:
 *      - Name of the parameter
 *      - Buffer containing the pingback XML
 * Returns:
 *      - parameter match string OR NULL
 * @access private
 */
function getPingbackParam(string $paramName, string $data) : ?string {
    $pattern = "<methodCall>.*?<methodName>\s*pingback.ping\s*</methodName>.*?<params>.*?<param>\s*((<name>\s*$paramName\s*</name>\s*<value>\s*<string>([^<]*)</string>\s*</value>)|(<value>\s*<string>([^<]*)</string>\s*</value>\s*<name>\s*$paramName\s*</name>))\s*</param>.*?</params>.*?</methodCall>";
    if (preg_match('@' . $pattern .'@is', $data, $matches)) {
        return $matches[3];
    } else {
        return null;
    }
}

/**
 * Fetches additional comment data from the page that sent the pingback
 *
 * Args:
 *      - Comment array to be filled
 * Returns:
 *      - void
 * @access private
 */
function fetchPingbackData(iterable &$comment) : void {
    global $serendipity;

    // Don't fetch remote page, if not explicitly allowed in serendipity_config_local.php:
    if (empty($serendipity['pingbackFetchPage'])) {
        return;
    }

    // If we don't have a comment or a commentors url, stop it.
    if (!isset($comment) || !is_array($comment) || !isset($comment['url'])) {
        return;
    }

    // Max amount of characters fetched from the page doing a pingback:
    $fetchPageMaxLength = 200;
    if (isset($serendipity['pingbackFetchPageMaxLength'])) {
        $fetchPageMaxLength = $serendipity['pingbackFetchPageMaxLength'];
    }
    $url = $comment['url'];

    // Request the page
    $options = array('follow_redirects' => true, 'max_redirects' => 5, 'timeout' => 20);

    $fContent = serendipity_request_url($url, extra_options: $options);
    #echo '<pre>fetchPingbackData() '.print_r($serendipity['last_http_request'], true).'</pre>';

    // Get a title
    if (preg_match('@<head[^>]*>.*?<title[^>]*>(.*?)</title>.*?</head>@is', $fContent, $matches)) {
        $comment['title'] = html_entity_decode(strip_tags($matches[1]), ENT_COMPAT, LANG_CHARSET);
    }

    // Try to get content from first <p> tag on:
    if (preg_match('@<p[^>]*>(.*?)</body>@is', $fContent, $matches)) {
        $body = $matches[1];
    }
    if (empty($body) && preg_match('@<body[^>]*>(.*?)</body>@is', $fContent, $matches)) {
        $body = $matches[1];
    }
    // Get a part of the article
    if (!empty($body)) {
        $body = trackback_body_strip($body);

        // truncate the text to 200 chars
        $arr = str_split($body, $fetchPageMaxLength);
        $body = $arr[0];

        $comment['comment'] = $body . '[..]';
    }

    if (false === $fContent) {
        if (is_object($serendipity['logger'])) $serendipity['logger']->debug("Request url: $url failed in: " . __FUNCTION__ . ' with response Code: ' . $serendipity['last_http_request']['responseCode']);
        // do what? Don't touch $comments
        return;
    }
}

/**
 * Strips any unneeded code from trackback / pingback bodies returning pure (UTF8) text.
 *
 * Args:
 *      - The text string
 * Returns:
 *      - filtered text string
 * @access private
 */
function trackback_body_strip(string $body) : string {
    // replace non breakable space with normal space:
    $body = str_replace('&nbsp;', ' ', $body);

    // strip html entities and tags.
    $body = html_entity_decode(strip_tags($body), ENT_COMPAT, LANG_CHARSET);

    // replace whitespace with single space
    $body = preg_replace('@\s+@s', ' ', $body);

    return $body;
}

/**
 * Create an excerpt for a trackback to send
 *
 * Args:
 *      - Input text
 * Returns:
 *      - Output text
 * @access public
 */
function serendipity_trackback_excerpt(string $text) : string {
    return mb_substr(strip_tags($text), 0, 255);
}

/**
 * Report success of a trackback
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function report_trackback_success() : void {
print '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
print <<<SUCCESS
<response>
    <error>0</error>
</response>
SUCCESS;
}

/**
 * Report failure of a trackback
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function report_trackback_failure() : void {
print '<?xml version="1.0" encoding="iso-8859-1"?>' . "\n";
print <<<FAILURE
<response>
    <error>1</error>
    <message>Danger Will Robinson, trackback failed.</message>
</response>
FAILURE;
}

/**
 * Return success of a pingback
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function report_pingback_success() : void {
print '<?xml version="1.0"?>' . "\n";
print <<<SUCCESS
<methodResponse>
   <params>
      <param>
         <value><string>success</string></value>
      </param>
   </params>
</methodResponse>
SUCCESS;
}

/**
 * Return failure of a pingback
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function report_pingback_failure() : void {
print '<?xml version="1.0"?>' . "\n";
print <<<FAILURE
<methodResponse>
    <fault>
    <value><i4>0</i4></value>
    </fault>
</methodResponse>
FAILURE;
}

/**
 * Search through link body, and automatically send a trackback ping.
 *
 * This is the trackback starter function that searches your text
 * and sees if any trackback URLs are in there
 *
 * Args:
 *      - The ID of our entry
 *      - The author of our entry
 *      - The title of our entry
 *      - The text of our entry
 *      - Dry-Run, without performing trackbacks?
 * Returns:
 *      - void
 * @access public
 */
function serendipity_handle_references(int $id, string $author, string $title, string $text, bool $dry_run = false) : void {
    global $serendipity;
    static $old_references = [];
    static $saved_references = [];
    static $saved_urls = [];
    static $debug = true;

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc, case-by-case debugging

    if ($debug) { $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START serendipity_handle_references SEPARATOR" . str_repeat(" <<< ", 10) . "\n"); }

    if ($dry_run) {
        $runtype = 'DRYRUN';
        // Store the current list of references. We might need to restore them for later usage.
        $old_references = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}references WHERE (type = '' OR type IS NULL) AND entry_id = " . $id, false, 'assoc');

        if (is_string($old_references)) {
            if ($debug) $serendipity['logger']->debug("$runtype old_references SELECT: " . $old_references); // error case
        }

        if (is_array($old_references) && count($old_references) > 0) {
            $current_references = array();
            foreach($old_references AS $idx => $old_reference) {
                // We need the current reference ID to restore it later.
                $saved_references[$old_reference['link'] . $old_reference['name']] = $current_references[$old_reference['link'] . $old_reference['name']] = $old_reference;
                $saved_urls[$old_reference['link']] = true;
            }
            if ($debug) $serendipity['logger']->debug("$runtype - Got references: " . print_r($current_references, true)); // don't mind the double [link . name] in key. Probably to ensure we have a proper key in case one is empty!
        }
    } else {
        $runtype = 'FINAL';
        // A dry-run was called previously and restorable references are found. Restore them now.
        $del = serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}references WHERE (type = '' OR type IS NULL) AND entry_id = " . $id);
        if (is_string($del)) {
            if ($debug) $serendipity['logger']->debug("$runtype - $del"); // error case
        }
        if ($debug) $serendipity['logger']->debug("$runtype - Deleted references");

        if (is_array($old_references) && count($old_references) > 0) {
            $current_references = array();
            foreach($old_references AS $idx => $old_reference) {
                // We need the current reference ID to restore it later.
                $current_references[rtrim($old_reference['link'] . $old_reference['name'])] = $old_reference;
                $q = serendipity_db_insert('references', $old_reference, 'show');
                $cr = serendipity_db_query($q);
                if (is_string($cr)) {
                    if ($debug) $serendipity['logger']->debug("$runtype - $cr"); // error case
                }
            }
            if ($debug) $serendipity['logger']->debug("$runtype - Got references: " . print_r($current_references, true)); // don't mind the double [link . name] in key. Probably to ensure we have a proper key in case one is empty!
        }
    }

    if (!preg_match_all('@<a[^>]+?(data-fallback|href)\s*=\s*["\']?([^\'" >]+?)[ \'"][^>]*>(.+?)</a>@i', $text, $matches)) {
        $matches = array(0 => array(), 1 => array());
    } else {
        // remove full matches
        array_shift($matches);
        // remove the ugly data-fallback|href attribute matches, which came in to avoid rel tags for lightboxes for example,.. since I hate regex(!) and this was the only way I got matching fallback or href links
        array_shift($matches);
        // matches now contains the URL values of data-fallback OR href
    }

    // Make trackback URL
    $url = serendipity_archiveURL($id, $title, 'baseURL', true, array('timestamp' => time()));
    // Make sure that the trackback-URL does not point to https
    $url = str_replace('https://', 'http://', $url);

    // Add URL references
    $locations = $matches[0];
    $names     = $matches[1];

    $serendipity['trackback_debug_data'] = $debug; // hook into trackback plugin debugging

    $checked_locations = array();
    serendipity_plugin_api::hook_event('backend_trackbacks', $locations);
    for ($i = 0, $j = count($locations); $i < $j; ++$i) {
        if ($debug) $serendipity['logger']->debug("$runtype - Checking {$locations[$i]}...");
        if ($locations[$i][0] == '/') {
            $locations[$i] = 'http' . (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $locations[$i];
        }

        if (isset($checked_locations[$locations[$i]])) {
            if ($debug) $serendipity['logger']->debug("$runtype - Already checked");
            continue;
        }

        if (isset($names[$i]) && preg_match_all('@<img[^>]+?alt=["\']?([^\'">]+?)[\'"][^>]+?>@i', $names[$i], $img_alt)) {
            if (is_array($img_alt) && is_array($img_alt[0])) {
                foreach($img_alt[0] AS $alt_idx => $alt_img) {
                    // Replace all <img>s within a link with their respective ALT tag, so that references
                    // can be stored with a title.
                    $names[$i] = str_replace($alt_img, $img_alt[1][$alt_idx], $names[$i]);
                }
            }
        }

        $query = "SELECT COUNT(id) FROM {$serendipity['dbPrefix']}references
                                  WHERE entry_id = ". $id ."
                                    AND link = '" . serendipity_db_escape_string($locations[$i]) . "'
                                    AND (type = '' OR type IS NULL)";

        $row = serendipity_db_query($query, true, 'num');
        if (is_string($row)) {
            if ($debug) $serendipity['logger']->debug("$runtype - $row"); // error case
        }

        $names[$i] = isset($names[$i]) ? rtrim(strip_tags($names[$i])) : array();
        if (empty($names[$i])) {
            if ($debug) $serendipity['logger']->debug("$runtype - Found reference $locations[$i] w/o name. Adding location as name");
            $names[$i] = $locations[$i];
        }

        if (!isset($serendipity['skip_trackback_check']) || !$serendipity['skip_trackback_check']) {
            if ($row[0] > 0 && isset($saved_references[$locations[$i] . $names[$i]])) {
                if ($debug) $serendipity['logger']->debug("$runtype - Found references for [$id], skipping rest");
                continue;
            }
        }

        if (!isset($serendipity['noautodiscovery']) || !$serendipity['noautodiscovery']) {
            if (!$dry_run) {
                if (!isset($saved_urls[$locations[$i]]) || (isset($serendipity['skip_trackback_check']) && $serendipity['skip_trackback_check'])) {
                    if ($debug) $serendipity['logger']->debug($runtype .' - Enabling autodiscovery - send params: (' . "'{$locations[$i]}', '$url', '$author', '$title', '".serendipity_trackback_excerpt($text)."')");
                    serendipity_reference_autodiscover($locations[$i], $url, $author, $title, serendipity_trackback_excerpt($text));
                } else {
                    if ($debug) $serendipity['logger']->debug("$runtype - This reference was already used before in [$id] and therefore will not be trackbacked again");
                }
            } else {
                if ($debug) $serendipity['logger']->debug("$runtype - Skipping autodiscovery");
            }
            $checked_locations[$locations[$i]] = true; // Store trackbacked link so that no further trackbacks will be sent to the same link
        } else {
            if ($debug) $serendipity['logger']->debug("$runtype - Skipping full autodiscovery");
        }
    }
    $del = serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}references WHERE entry_id=" . $id . " AND (type = '' OR type IS NULL)");
    if (is_string($del)) {
        if ($debug) $serendipity['logger']->debug("$runtype - $del"); // error case
    }
    if ($debug) $serendipity['logger']->debug("$runtype - Deleted references again");

    if (!is_array($old_references)) {
        $old_references = array();
    }

    $duplicate_check = array();
    for ($i = 0; $i < $j; ++$i) {
        if (!isset($names[$i])) {
            continue; // skip keys that don't exist
        }
        $i_link     = serendipity_db_escape_string(rtrim(strip_tags($names[$i])));
        $i_location = serendipity_db_escape_string(rtrim($locations[$i]));

        // No link with same description AND same text should be inserted.
        if (isset($duplicate_check[$i_location . $i_link])) {
            continue;
        }

        if (isset($current_references[$locations[$i] . $names[$i]])) {
            $query = "INSERT INTO {$serendipity['dbPrefix']}references (id, entry_id, link, name)
                           VALUES(" . (int)$current_references[$locations[$i] . $names[$i]]['id'] . ', ' . $id . ", '$i_location', '$i_link')";
            $ins = serendipity_db_query($query);
            if (is_string($ins)) {
                if ($debug) $serendipity['logger']->debug("$runtype - $ins"); // error case
            }
            $duplicate_check[$locations[$i] . $names[$i]] = true;
        } else {
            $query = "INSERT INTO {$serendipity['dbPrefix']}references (entry_id, link, name) VALUES(";
            $query .= $id . ", '" . $i_location . "', '" . $i_link . "')";
            $ins = serendipity_db_query($query);
            if (is_string($ins)) {
                if ($debug) $serendipity['logger']->debug("$runtype - $ins"); // error case
            }

            $old_references[] = array(
                'id'       => serendipity_db_insert_id('references', 'id'),
                'entry_id' => $id,
                'link'     => $i_location,
                'name'     => $i_link
            );
            $duplicate_check[$i_location . $i_link] = true;
        }

        if ($debug && isset($locations[$i]) && isset($names[$i]) && isset($current_references[$locations[$i] . $names[$i]])) {
            $serendipity['logger']->debug("$runtype - Current lookup for {$locations[$i]} {$names[$i]} is " . print_r($current_references[$locations[$i] . $names[$i]], true)) . "\n";
            $serendipity['logger']->debug("$runtype - $query");
        }
    }

    if ($debug) $serendipity['logger']->debug("$runtype - Old references " . print_r($old_references, true));

    // Add citations
    preg_match_all('@<cite[^>]*>([^<]+)</cite>@i', $text, $matches);

    foreach($matches[1] AS $citation) {
        $query  = "INSERT INTO {$serendipity['dbPrefix']}references (entry_id, name) VALUES(";
        $query .= $id . ", '" . serendipity_db_escape_string($citation) . "')";

        $cite = serendipity_db_query($query);
        if (is_string($cite)) {
            if ($debug) $serendipity['logger']->debug("$runtype - $cite"); // error case
        }
    }

    if ($debug) $serendipity['logger']->debug("$runtype - END ******************************************************* ");
}

/**
 * Check if a string is in UTF-8 format.
 *
 * Args:
 *      - The string to check
 * Returns:
 *      - preg_match return codes
 * @access public
 */
function is_utf8(string $string) : int|bool {
   // From http://w3.org/International/questions/qa-forms-utf-8.html
   return preg_match('%^(?:'
         . '[\x09\x0A\x0D\x20-\x7E]'             # ASCII
         . '|[\xC2-\xDF][\x80-\xBF]'             # non-overlong 2-byte
         . '|\xE0[\xA0-\xBF][\x80-\xBF]'         # excluding overlongs
         . '|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'  # straight 3-byte
         . '|\xED[\x80-\x9F][\x80-\xBF]'         # excluding surrogates
         . '|\xF0[\x90-\xBF][\x80-\xBF]{2}'      # planes 1-3
         . '|[\xF1-\xF3][\x80-\xBF]{3}'          # planes 4-15
         . '|\xF4[\x80-\x8F][\x80-\xBF]{2}'      # plane 16
         . ')*$%xs', $string);
}
