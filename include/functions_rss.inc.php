<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (defined('S9Y_FRAMEWORK_RSS')) {
    return;
}
@define('S9Y_FRAMEWORK_RSS', true);

/**
 * Parses entries to display them for RSS/Atom feeds to be passed on to generic Smarty templates
 *
 * This function searches for existing RSS feed template customizations. As long as a template
 * with the same name as the $version variable exists, it will be emitted.
 *
 * @access public
 * @see serendipity_fetchEntries(), rss.php
 * @param   array       A superarray of entries to output
 * @param   string      The version/type of a RSS/Atom feed to display (atom1.0, rss2.0 etc.)
 * @param   boolean     If true, this is a comments feed. If false, it's an Entry feed.
 * @param   boolean     Indicates if this feed is a fulltext feed (true) or only excerpt (false)
 * @param   boolean     Indicates if E-Mail addresses should be shown (true) or hidden (false)
 * @return
 */
function serendipity_printEntries_rss(&$entries, $version, $comments = false, $fullFeed = false, $showMail = true) {
    global $serendipity;

    $options = array(
        'version'  => $version,
        'comments' => $comments,
        'fullFeed' => $fullFeed,
        'showMail' => $showMail
    );
    serendipity_plugin_api::hook_event('frontend_entries_rss', $entries, $options);

    if (is_array($entries)) {
        foreach($entries AS $key => $_entry) {
            $entry = &$entries[$key];

            if (isset($entry['entrytimestamp'])) {
                $e_ts = $entry['entrytimestamp'];
            } else {
                $e_ts = $entry['timestamp'];
            }

            $entry['feed_id'] = (isset($entry['entryid']) && !empty($entry['entryid']) ? $entry['entryid'] : $entry['id']);

            // set feed guid only, if not already defined externally
            if (empty($entry['feed_guid']))
                $entry['feed_guid'] = serendipity_rss_getguid($entry, $options['comments']);

            $entry['feed_entryLink'] = serendipity_archiveURL($entry['feed_id'], $entry['title'], 'baseURL', true, array('timestamp' => $e_ts));
            if ($options['comments'] === true) {
                // Display username as part of the title for easier feed-readability
                if ($entry['type'] == 'TRACKBACK' && !empty($entry['ctitle'])) {
                    $entry['author'] .= ' - ' . ($options['version'] == 'atom1.0' ? serendipity_specialchars($entry['ctitle'], ENT_XHTML, LANG_CHARSET, false) : $entry['ctitle']);
                }
                if ($options['version'] == 'atom1.0') {
                    $entry['title'] = str_replace(['&nbsp;', '&#160;', '  '], [' '], $entry['title']);
                    $entry['title'] = str_replace(' & ', ' + ', $entry['title']); // this weird hotfix is necessary to avoid broken entities (?wherever?) breaking the xml! An entry title is not stored by htmlspecialchars().
                }
                $entry['title'] = (!empty($entry['author']) ? $entry['author'] : ANONYMOUS) . ': ' . ($options['version'] == 'atom1.0' ? serendipity_specialchars($entry['title'], ENT_XHTML, LANG_CHARSET, false) : $entry['title']);

                if ($options['version'] == 'atom1.0') {
                    $entry['body'] = serendipity_specialchars($entry['body'], ENT_XHTML, LANG_CHARSET, false); // NO NEED to strip for atom, but make sure we don't do double encoding !!
                } else{
                    // [old] RSS2 only - No HTML allowed here:
                    $entry['body'] = strip_tags($entry['body']); // see c580fa35d3ab51cb79d41a2a00863ed52aa0a83c
                }
            }

            // Embed a link to extended entry, if existing
            if ($options['fullFeed']) {
                $entry['body'] .= "\n" . ($options['version'] == 'atom1.0' ? serendipity_specialchars($entry['extended'], ENT_XHTML, LANG_CHARSET, false) : $entry['extended']);
                $ext = '';
            } elseif (isset($entry['exflag']) && $entry['exflag']) {
                $ext = '<a class="block_level" href="' . $entry['feed_entryLink'] . '#extended">' . sprintf(VIEW_EXTENDED_ENTRY, ($options['version'] == 'atom1.0' ? serendipity_specialchars($entry['title'], ENT_XHTML, LANG_CHARSET, false) : $entry['title'])) . '</a>';
            } else {
                $ext = '';
            }

            $addData = array('from' => 'functions_entries:printEntries_rss', 'rss_options' => $options);
            serendipity_plugin_api::hook_event('frontend_display', $entry, $addData);

            // Do some relative -> absolute URI replacing magic. Replaces all HREF/SRC/SRCSET (<a>, <img>, ...) references to only the serendipity path with the full baseURL URI
            // garvin: Could impose some problems. Closely watch this one.
            // onli: Did impose some problems, when having //-links to stuff. following pattern-selection tries to workaround that
            if ($serendipity['serendipityHTTPPath'] == '/') {
                $pattern = '@(href|src|srcset)=(["\'])/([^/][^"\']*)@imsU';
            } else {
                $pattern = '@(href|src|srcset)=(["\'])' . preg_quote($serendipity['serendipityHTTPPath']) . '([^"\']*)@imsU';
            }
            $entry['body'] = preg_replace($pattern, '\1=\2' . $serendipity['baseURL'] . '\3', $entry['body']);
            //$entry['body'] = preg_replace('@(href|src|srcset)=("|\')(' . preg_quote($serendipity['serendipityHTTPPath']) . ')(.*)("|\')(.*)>@imsU', '\1=\2' . $serendipity['baseURL'] . '\4\2\6>', $entry['body']);

            // clean up body for XML compliance and doubled whitespace between (img) attributes as best we can.
            $entry['body'] = str_replace('"  ', '" ', xhtml_cleanup($entry['body']));
            if ($options['comments'] === true && $version == 'atom1.0') {
                // Remember: A comment body is DB stored by using htmlspecialchars() !!
                $entry['body'] = str_replace(['&nbsp;', '&#160;', '  '], [' '], $entry['body']); // allowed to do, since stripped
                $entry['body'] = serendipity_entity_decode($entry['body'], ENT_COMPAT | ENT_HTML401, LANG_CHARSET);
                $entry['body'] = str_replace('&', '+', $entry['body']); // fix serendipity_entity_decode
            }

            // extract author information
            if ((isset($entry['no_email']) && $entry['no_email']) || $options['showMail'] === FALSE) {
                $entry['email'] = 'nospam@example.com'; // RSS Feeds need an E-Mail address!
            } elseif (empty($entry['email'])) {
                $query = "SELECT email FROM {$serendipity['dbPrefix']}authors WHERE authorid = '". serendipity_db_escape_string($entry['authorid']) ."'";
                $results = serendipity_db_query($query);
                $entry['email'] = $results[0]['email'];
            }
            // these silenced non-defined categories is case comments feeds only where no cats are available!
            if (!isset($entry['categories']) || !is_array($entry['categories'])) {
                $entry['categories'] = array(0 => array(
                    'category_name'      => $entry['category_name'] ?? '',
                    'feed_category_name' => serendipity_utf8_encode(serendipity_specialchars(($entry['category_name'] ?? ''))),
                    'categoryURL'        => serendipity_categoryURL($entry, 'baseURL')
                ));
            } else {
                foreach($entry['categories'] AS $cid => $_cat) {
                    $cat = &$entry['categories'][$cid];
                    $cat['categoryURL']        = serendipity_categoryURL($cat, 'baseURL');
                    $cat['feed_category_name'] = serendipity_utf8_encode(serendipity_specialchars($cat['category_name']));
                }
            }

            // Prepare variables
            // 1. UTF8 encoding + serendipity_specialchars.
            $entry['feed_title']     = serendipity_utf8_encode(serendipity_specialchars($entry['title']));
            $entry['feed_blogTitle'] = serendipity_utf8_encode(serendipity_specialchars($serendipity['blogTitle']));
            $entry['feed_title']     = serendipity_utf8_encode(serendipity_specialchars($entry['title']));
            $entry['feed_author']    = serendipity_utf8_encode(serendipity_specialchars($entry['author']));
            $entry['feed_email']     = serendipity_utf8_encode(serendipity_specialchars($entry['email']));

            // 2. gmdate
            $entry['feed_timestamp']     = gmdate('Y-m-d\TH:i:s\Z', serendipity_serverOffsetHour($entry['timestamp']));
            $entry['feed_last_modified'] = gmdate('Y-m-d\TH:i:s\Z', serendipity_serverOffsetHour(@$entry['last_modified'])); // mute possible uninitialized item
            $entry['feed_timestamp_r']   = date('r', $entry['timestamp']);

            // 3. UTF8 encoding
            $entry['feed_body'] = serendipity_utf8_encode($entry['body']);
            $entry['feed_ext']  = serendipity_utf8_encode($ext);

            $entry_hook = 'frontend_display:unknown:per-entry';
            switch($version) {
                case 'opml1.0':
                    $entry_hook = 'frontend_display:opml-1.0:per_entry';
                    break;

                case '0.91':
                    $entry_hook = 'frontend_display:rss-0.91:per_entry';
                    break;

                case '1.0':
                    $entry_hook = 'frontend_display:rss-1.0:per_entry';
                    break;

                case '2.0':
                    $entry_hook = 'frontend_display:rss-2.0:per_entry';
                    break;

                case 'atom0.3':
                    $entry_hook = 'frontend_display:atom-0.3:per_entry';
                    break;

                case 'atom1.0':
                    $entry_hook = 'frontend_display:atom-1.0:per_entry';
                    break;
            }

            serendipity_plugin_api::hook_event($entry_hook, $entry);
            $entry['per_entry_display_dat'] = !empty($entry['display_dat']) ? $entry['display_dat'] : '';
        }
    }

}
