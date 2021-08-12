<?php
/**
 * PLEASE NOTE:
 *  Since PHP5 it's no longer required to pass objects as reference. References should be removed from Smarty 2 plugins, filter etc.
 *  Starting with Smarty 3.1.28 we can register closures as filter. For that reason the filter functions are now called as callback
 *  with call_user_func() which does not pass the reference.
 *  Smarty 3 does pass the template object instead of the Smarty object to plugins and filter.
 *
 *  It was originally not intended by Smarty, that you can call fetch() and display() on a template object with parameters to get output from another template.
 *  The correct code of your function should then be in this case:
 *      function smarty_function_test($params, Smarty_Internal_Template $template)
 *      {
 *          return $template->smarty->fetch('string:testing');
 *      }
 *
 *  You may use the template object, eg by assign to a current template:
 *      $template->assign($params['bar'], $foo);
 *  OR by assign to a parent template (or the smarty instance):
 *      $template->parent->assign($params['bar'], $foo);
 *  OR by assign to the smarty instance:
 *      $template->smarty->assign($params['bar'], $foo);
 *  OR to load a smarty plugin depended functionality
 *      $template->smarty->loadPlugin('another_smarty_plugin_dependency');
 *
 *  As a general rule, the current evaluated template instanceOf Smarty_Internal_Template object is always passed to the plugins as the last parameter,
 *  with two exceptions:
 *      - modifiers do not get passed the Smarty_Internal_Template object at all
 *      - blocks get passed $repeat after the Smarty_Internal_Template object, to keep backwards compatibility to older versions of Smarty.
 *
 *  ATTENTION: Since having reworked the default-php and default-xml theme this type declaration (Smarty_Internal_Template) of the $template object
 *             has been removed here from all functions that do not need it. You would otherwise need to clone them to the template_api.inc.php file
 *             without the Smarty_Internal_Template type declaration. There are two very special cased functions left:
 *             serendipity_smarty_showCommentForm() and serendipity_smarty_getImageSize(), which both don't need to be used or have better origin methods.
 *
 *  Plugin functions naming convention is "smarty_type_name()" and plugin files must be named as follows: "type.name.php".
 *  Where type is one of these plugin types: [function, modifier, block, compiler, prefilter, postfilter, outputfilter, resource, insert].
 *
 */

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

/**
 * Fetch a list of trackbacks for an entry
 *
 * @access public
 * @param   int     The ID of the entry
 * @param   string  How many trackbacks to show
 * @param   boolean If true, also non-approved trackbacks will be shown
 * @return
 */
function &serendipity_fetchTrackbacks($id, $limit = null, $showAll = false) {
    global $serendipity;

    if (!$showAll) {
        $and = "AND status = 'approved'";
    }

    $query = "SELECT * FROM {$serendipity['dbPrefix']}comments WHERE entry_id = '". (int)$id ."' AND (type = 'TRACKBACK' OR type = 'PINGBACK') $and ORDER BY id";
    if (isset($limit)) {
        $limit  = serendipity_db_limit_sql($limit);
        $query .= " $limit";
    }

    $comments = serendipity_db_query($query);
    if (!is_array($comments)) {
        $r = array(); // avoid Notice: Only variable references should be returned by reference
        return $r;
    }

    return $comments;
}

/**
 * Show trackbacks for an entry
 *
 * LONG
 *
 * @access public
 * @param   array       The superarray of trackbacks to display
 * @return
 */
function &serendipity_printTrackbacks(&$trackbacks) {
    global $serendipity;

    $serendipity['smarty']->assignByRef('trackbacks', $trackbacks);

    return serendipity_smarty_fetch('TRACKBACKS', 'trackbacks.tpl');
}

/**
 * Formats a HTML5 timestamp; our serendipity_formatTime handler uses strftime() which does not have this shortcut
 *
 * @access public
 * @param  int  The unix timestamp to format
 * @return timestamp in ISO-format
 */
function serendipity_smarty_html5time($timestamp) {
    return date('c', $timestamp);
}


/**
 * Smarty: Fetch a smarty block and pass it on to Serendipity Templates - use with Smarty > 3.0 only
 *
 * @access public
 * @param   string      The name of the block to parse data into ("COMMENTS" - virtual variable like {$COMMENTS})
 * @param   string      The name of the template file to fetch. Only filename, the path is auto-detected
 * @param   boolean     If true, the output of the smarty parser will be echoed instead of invisibly treated
 * @return  string      The parsed HTML code
 */
function &serendipity_smarty_fetch($block, $file, $echo = false) {
    global $serendipity;

    $output = $serendipity['smarty']->fetch('file:'. serendipity_getTemplateFile($file, 'serendipityPath'), null, null, null, ($echo === true && $serendipity['smarty_raw_mode']));
    $output = $block == 'CONTENT' ? ltrim($output) : $output;

    $serendipity['smarty']->assignByRef($block, $output);

    return $output;
}

/**
 * Smarty Modifier: Check if a string is not empty and prepend a prefix in that case. Else, leave empty
 *
 * @access public
 * @param   string  The input string to check
 * @param   string  The prefix to prepend, if $string is not empty
 * @return  string  The return string
 */
function serendipity_emptyPrefix($string, $prefix = ': ') {
    return (!empty($string) ? $prefix . serendipity_specialchars($string) : '');
}

/**
 * Smarty Modifier: Replace unwanted chars and return just letters, numbers, underscores, hyphens - ie for broken button info "popup" cases
 *
 * @access public
 * @param   string  Template string content
 * @param   object  Smarty template object
 * @return
 */
function serendipity_cleanChars($string) {
   $string = str_replace(array(' ', '/'), array('-', '_'), $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9_\-]+/', '', $string); // Removes special chars, except the mentioned.
}

/**
 * Smarty Modifier: Return a remembered variable
 *
 * @access public
 * @param   string  The variable name
 * @param   string  The wanted value
 * @param   boolean Force default?
 * @param   string  Returned attribute
 * @return  string  The return string
 * @see serendipity_event_imageselectorplus as example
 */
function serendipity_ifRemember($name, $value, $isDefault = false, $att = 'checked') {
    global $serendipity;

    if (!is_array($serendipity['COOKIE']) && !$isDefault) {
        return false;
    }

    if ((!is_array($serendipity['COOKIE']) && $isDefault) ||
        (!isset($serendipity['COOKIE']['serendipity_' . $name]) && $isDefault) ||
        (isset($serendipity['COOKIE']['serendipity_' . $name]) && $serendipity['COOKIE']['serendipity_' . $name] == $value)) {

        return " $att=\"$att\" ";
    }
}

/**
 * Smarty Function: Fetch and print a single or multiple entries
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                      [FETCHING]
 *                          category:        (int)     The category ID (separate multiple with ";") to fetch entries from
 *                          viewAuthor:      (int)     The author ID (separate multiple with ";") to fetch entries from
 *                          page:            (int)     The number of the page for paginating entries
 *                          id:              (int)     The ID of an entry. If given, only a single entry will be fetched. If left empty, multiple entries are fetched.
 *                          range:           (mixed)   Restricts fetching entries to a specific timespan. Behaves differently depending on the type:
 *                                           Numeric:
 *                                            YYYYMMDD - Shows all entries from YYYY-MM-DD.
 *                                            If DD is "00", it will show all entries from that month.
 *                                            If DD is any other number, it will show entries of that specific day.
 *                                           2-Dimensional Array:
 *                                            Key #0   - Specifies the start timestamp (unix seconds)
 *                                            Key #1   - Specifies the end timestamp (unix seconds)
 *                                           Other (null, 3-dimensional Array, ...):
 *                                            Entries newer than $modified_since will be fetched
 *                          full             (boolean) Indicates if the full entry will be fetched (body+extended: TRUE), or only the body (FALSE).
 *                          limit            (string)  Holds a "Y" or "X, Y" string that tells which entries to fetch. X is the first entry offset, Y is number of entries. If not set, the global fetchLimit will be applied (15 entries by default)
 *                          fetchDrafts      (boolean) Indicates whether drafts should be fetched (TRUE) or not
 *                          modified_since   (int)     Holds a unix timestamp to be used in conjunction with $range, to fetch all entries newer than this timestamp
 *                          orderby          (string)  Holds the SQL "ORDER BY" statement.
 *                          filter_sql       (string)  Can contain any SQL code to inject into the central SQL statement for fetching the entry
 *                          noCache          (boolean) If set to TRUE, all entries will be fetched from scratch and any caching is ignored
 *                          noSticky         (boolean) If set to TRUE, all sticky entries will NOT be fetched.
 *                          select_key       (string)  Can contain a SQL statement on which keys to select. Plugins can also set this, pay attention!
 *                          group_by         (string)  Can contain a SQL statement on how to group the query. Plugins can also set this, pay attention!
 *                          returncode       (string)  If set to "array", the array of entries will be returned. "flat-array" will only return the articles without their entryproperties. "single" will only return a 1-dimensional array. "assign" will assign this particular entry data to your Smarty template (needs the id parameter). "query" will only return the used SQL.
 *                          joinauthors      (bool)    Should an SQL-join be made to the AUTHORS DB table?
 *                          joincategories   (bool)    Should an SQL-join be made to the CATEGORIES DB table?
 *                          joinown          (string)  SQL-Parts to add to the "JOIN" query
 *                          entryprops       (string)  Condition list of commaseparated entryproperties that an entry must have to be displayed (example: "ep_CustomField='customVal',ep_CustomField2='customVal2'")
 *
 *                      [PRINTING]
 *                          template:          (string)  Name of the template file to print entries with
 *                          preview:           (boolean) Indicates if this is a preview
 *                          block              (string   The name of the SMARTY block that this gets parsed into
 *                          use_hooks          (boolean  Indicates whether to apply footer/header event hooks
 *                          use_footer         (boolean  Indicates whether the pagination footer should be displayed
 *                          groupmode          (string   Indicates whether the input $entries array is already grouped in preparation for the smarty $entries output array [TRUE], or if it shall be grouped by date [FALSE]
 *                          skip_smarty_hooks  (boolean) If TRUE, no plugins will be executed at all
 *                          skip_smarty_hook   (mixed)   Can be set to an array of plugin hooks to NOT execute
 *                          prevent_reset      (boolean) If set to TRUE, the smarty $entries array will NOT be cleared. (to prevent possible duplicate output of entries)
 * @param   object      Smarty template object
 * @return  string      The Smarty HTML response
 */
function serendipity_smarty_fetchPrintEntries($params, $template) {
    global $serendipity;
    static $entrycount = 0;
    static $restore_var_GET_keys = array('category', 'viewAuthor', 'page', 'hide_category');

    // A counter variable to not assign template files multiple times
    $entrycount++;

    // Default values for function calls
    if (empty($params['template'])) {
        $params['template'] = 'entries.tpl';
    }

    if (empty($params['range'])) {
        $params['range'] = null;
    }

    if (empty($params['full'])) {
        $params['full'] = true;
    }

    if (empty($params['fetchDrafts'])) {
        $params['fetchDrafts'] = false;
    }

    if (!empty($params['entryprops'])) {
        if (preg_match_all('@(.*)(!)?=[\'"]*([^\'"]+)[\'"]*(,|$)@imsU', $params['entryprops'], $m)) {
            foreach($m[0] AS $idx => $p) {
                $params['joinown'] .= "\n JOIN {$serendipity['dbPrefix']}entryproperties
                                          AS ep" . $idx . "
                                          ON (ep" . $idx . ".entryid = e.id AND
                                              ep" . $idx . ".property = '" . serendipity_db_escape_string($m[1][$idx]) . "' AND
                                              ep" . $idx . ".value " . $m[2][$idx] . "= '" . serendipity_db_escape_string($m[3][$idx]) . "') \n";
            }
        }
    }

    if (empty($params['modified_since'])) {
        $params['modified_since'] = false;
    }

    if (empty($params['orderby'])) {
        $params['orderby'] = 'timestamp DESC';
    }

    if (empty($params['noCache'])) {
        $params['noCache'] = false;
    }

    if (empty($params['noSticky'])) {
        $params['noSticky'] = false;
    }

    if (empty($params['preview'])) {
        $params['preview'] = false;
    }

    if (empty($params['block'])) {
        $params['block'] = 'smarty_entries_' . $entrycount;
    }

    if (empty($params['use_hooks'])) {
        $params['use_hooks'] = false;
    }

    if (empty($params['use_footer'])) {
        $params['use_footer'] = false;
    }

    if (empty($params['groupmode'])) {
        $params['groupmode'] = 'date';
    }

    if (empty($params['skip_smarty_hooks'])) {
        $params['skip_smarty_hooks'] = true;
    }

    if (empty($params['skip_smarty_hook'])) {
        $params['skip_smarty_hook'] = array();
    }

    if (empty($params['prevent_reset'])) {
        $params['prevent_reset'] = false;
    }

    if (empty($params['select_key'])) {
        $params['select_key'] = null;
    }

    if (empty($params['group_by'])) {
        $params['group_by'] = null;
    }

    if (empty($params['returncode'])) {
        $params['returncode'] = 'array';
    }

    if (empty($params['joinauthors'])) {
        $params['joinauthors'] = true;
    }

    if (empty($params['joincategories'])) {
        $params['joincategories'] = true;
    }

    if (empty($params['joinown'])) {
        $params['joinown'] = null;
    }

    if (empty($params['filter_sql'])) {
        $params['filter_sql'] = null;
    }

    // Some functions deal with the $serendipity array. To modify them, we need to store
    // their original contents.
    $old_var = array();
    if (!empty($params['short_archives'])) {
        $old_var['short_archives']     = $serendipity['short_archives'] ?? null;
        $serendipity['short_archives'] = $params['short_archives'];
        // If no parameters for block and template were sent and the 'short_archives=true' param was set,
        // this assumingly is a call for assigning entries_summary content by categories to the 'ENTRIES' smarty block.
        if ($params['short_archives'] == true && $params['template'] == 'entries.tpl' && ($params['block'] == 'smarty_entries_' . $entrycount || $params['block'] == 'ENTRIES')) {
            $params['block']    = 'ENTRIES';
            $params['template'] = 'smarty_entries_short_archives.tpl'; // placed in default as an example and default preset,
            // and as a users theme copy template for a {serendipity_fetchPrintEntries} self added " template="my_very_own_cats_at_startup.tpl" parameter.
        }
    }

    $old_var['skip_smarty_hooks']     = $serendipity['skip_smarty_hooks'] ?? null;
    $serendipity['skip_smarty_hooks'] = $params['skip_smarty_hooks'];

    $old_var['skip_smarty_hook']      = $serendipity['skip_smarty_hook'] ?? null;
    $serendipity['skip_smarty_hook']  = $params['skip_smarty_hook'];

    foreach($restore_var_GET_keys AS $key) {
        if (!empty($params[$key])) {
            $old_var['GET'][$key]     = $serendipity['GET'][$key] ?? null;
            $serendipity['GET'][$key] = $params[$key];
        }
    }

    if (!empty($params['id'])) {
        $entry = serendipity_fetchEntry(
            'id',
            (int)$params['id'],
            $params['full'],
            $params['fetchDrafts']);
    } else {
        $entry = serendipity_fetchEntries(
            $params['range'],
            $params['full'],
            $params['limit'],
            $params['fetchDrafts'],
            $params['modified_since'],
            $params['orderby'],
            $params['filter_sql'],
            $params['noCache'],
            $params['noSticky'],
            $params['select_key'],
            $params['group_by'],
            $params['returncode'],
            $params['joinauthors'],
            $params['joincategories'],
            $params['joinown']
        );

        // Check whether the returned entries shall be grouped specifically
        switch ($params['groupmode']) {
            case 'date':
                // No regrouping required, printEntries() does it for us.
                break;

            case 'category':
                // Regroup by primary category

                $groupdata = array();
                foreach($entry AS $k => $_entry) {

                    if (is_array($entry['categories'])) {
                        $groupkey = $entry['categories'][0];
                    } else {
                        $groupkey = 'none';
                    }
                    $groupdata[$groupkey]['entries'] =& $_entry;
                }
                $entry =& $groupdata;
                break;
        }
    }

    if (!empty($params['id']) && $params['returncode'] == 'assign') {
        $serendipity['smarty']->assignByRef('entry', $entry);
        return;
    }

    if ($params['returncode'] == 'query') {
        return print_r($entry, true);
    }

    serendipity_printEntries(
        $entry,                                 // Entry data
        (!empty($params['id']) ? true : false), // Extended data?
        $params['preview'],                     // Entry preview?
        'ENTRIES',
        false,                                  // Prevent Smarty parsing
        $params['use_hooks'],
        $params['use_footer'],
        ($params['groupmode'] == 'date' ? false : true) // Grouping of $entry
    );

    // Restore the $serendipity array after our modifications.
    if (isset($old_var['short_archives'])) {
        $serendipity['short_archives'] = $old_var['short_archives'];
    }

    if (isset($old_var['GET']) && is_array($old_var['GET'])) {
        foreach($old_var['GET'] AS $key => $val) {
            $serendipity['GET'][$key] = $val;
        }
    }

    // watch out for the upper, conditionally changed 'short_archives' and 'block' parameters to allow serving what the specs intend with 'short_archives'
    $out = serendipity_smarty_fetch($params['block'], $params['template']);

    // Reset array list, because we might be in a nested code call.
    if ($params['prevent_reset'] == false) {
        $serendipity['smarty']->assign('entries', array());
    }
    $serendipity['skip_smarty_hook']  = $old_var['skip_smarty_hook'];
    $serendipity['skip_smarty_hooks'] = $old_var['skip_smarty_hooks'];

    return $out;
}

/**
 * Smarty Function: Shows a commentform
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          id:                 An entryid to show the commentform for
 *                          url:                An optional HTML target link for the form
 *                          comments:           Optional array of containing comments
 *                          data:               Possible pre-submitted values to the input values
 *                          showToolbar:        Toggle whether to show extended options of the comment form
 *                          moderate_comments:  Toggle whether comments to this entry are allowed
 * @param   object      Smarty template object
 * @return  void
 */
function serendipity_smarty_showCommentForm($params, Smarty_Internal_Template $template) {
    global $serendipity;

    if (empty($params['id']) || empty($params['entry'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'id' or 'entry' parameter", E_USER_WARNING);
        return;
    }

    if (empty($params['url'])) {
        $params['url'] = $serendipity['serendipityHTTPPath'] . $serendipity['indexFile'] . '?url=' . $params['entry']['commURL'];
    }

    if (empty($params['comments'])) {
        $params['comments'] = NULL;
    }

    if (empty($params['data'])) {
        $params['data'] = $serendipity['POST'];
    }

    if (empty($params['showToolbar'])) {
        $params['showToolbar'] = true;
    }

    if (empty($params['moderate_comments'])) {
        $params['moderate_comments'] = serendipity_db_bool($params['entry']['moderate_comments']);
    }

    $comment_add_data = array(
        'comments_messagestack' => (array) ($serendipity['messagestack']['comments'] ?? array()),
        'is_comment_added'      => ( (isset($serendipity['GET']['csuccess']) && $serendipity['GET']['csuccess'] == 'true') ? true: false),
        'is_comment_moderate'   => ( (isset($serendipity['GET']['csuccess']) && $serendipity['GET']['csuccess'] == 'moderate') ? true: false)
    );

    $template->assign($comment_add_data);

    serendipity_displayCommentForm(
        $params['id'],
        $params['url'],
        $params['comments'],
        $params['data'],
        $params['showToolbar'],
        $params['moderate_comments'],
        $params['entry']
    );

    return true;
}

/**
 * Smarty Function: Be able to include the output of a sidebar plugin within a Smarty template
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          class:  The classname of the plugin to show
 *                          id:     An ID of a plugin to show
 *                          side:   The side of a plugin to show (left|right|hide|and|other|user-defined|sidebars)
 *                          negate: Revert previous filters
 * @param   object      Smarty template object
 * @return  string      The Smarty HTML response
 */
function serendipity_smarty_showPlugin($params, $template) {
    global $serendipity;

    if (empty($params['class'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'class' parameter", E_USER_WARNING);
        return;
    }

    if (empty($params['id'])) {
        $params['id'] = null;
    }

    if (empty($params['side'])) {
        $params['side'] = '*';
    }
    if ($params['side'] === 'hidden') {
        $params['side'] = 'hide'; //compat, since being announced false in the docs for a long time
    }

    if (empty($params['negate']) || $params['negate'] === 'null') {
        $params['negate'] = null;
    }
    if ($params['negate'] === 'true' || $params['negate'] === 'false') {
        $params['negate'] = serendipity_db_bool($params['negate']);
    }
    if (empty($params['template'])) {
        $params['template'] = 'sidebar.tpl';
    }

    $out = serendipity_plugin_api::generate_plugins($params['side'], $params['negate'], $params['class'], $params['id'], $params['template']);

    if (empty($out) && !empty($params['empty'])) {
        return $params['empty'];
    }

    return $out;
}

/**
 * Smarty Function: Get total count for specific objects
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                      what: The type of count to show: "entries", "trackbacks", "comments"
 * @param   object      Smarty template object
 * @return  string      The Smarty HTML response
 */
function serendipity_smarty_getTotalCount($params, $template) {
    if (empty($params['what'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'what' parameter", E_USER_WARNING);
        return;
    }

    return serendipity_getTotalCount($params['what']);
}

/**
 * Smarty Function: Be able to execute the hook of an event plugin and return its output
 *
 * Listens to specific serendipity global variables:
 *  $serendipity['skip_smarty_hooks'] - If TRUE, no plugins will be executed at all
 *  $serendipity['skip_smarty_hook']  - Can be set to an array of plugin hooks to NOT execute
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          hook:       The name of the event hook
 *                          hookAll:    (boolean) Whether unknown hooks shall be executed
 *                          data:       The $eventData to an event plugin
 *                          addData:    The $addData to an event plugin
 * @param   object      Smarty template object
 * @return null
 */
function serendipity_smarty_hookPlugin($params, $template) {
    global $serendipity;
    static $hookable = array('frontend_header',
                             'entries_header',
                             'entries_footer',
                             'frontend_comment',
                             'frontend_footer');
    if (empty($params['hook'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'hook' parameter", E_USER_WARNING);
        return;
    }

    if (!in_array($params['hook'], $hookable) && $params['hookAll'] != 'true') {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": illegal hook '". $params['hook'] ."' (" . $params['hookAll'] . ")", E_USER_WARNING);
        return;
    }

    // Smarty hooks can be bypassed via an internal variable (temporarily)
    if (isset($serendipity['skip_smarty_hooks']) && $serendipity['skip_smarty_hooks']) {
        return;
    }

    // A specific hook can also be bypassed by creating an associative array like this:
    // $serendipity['skip_smarty_hook'] = array('entries_header');
    // That would only skip the entries_header event hook, but allow all others.
    // Of course it cannot be used in conjunction with the all-blocking skip_smarty_hooks.
    if (isset($serendipity['skip_smarty_hook']) && is_array($serendipity['skip_smarty_hook']) && isset($serendipity['skip_smarty_hook'][$params['hook']])) {
        return;
    }

    if (empty($params['data'])) {
        $params['data'] = &$serendipity;//RQ: ???? really ALL ???, but this should only be eventData something or null shouldn't it ??? (but null gives nothing at certain places)
        #see for example 'backend_header' with echo '<pre>ref-Serendipity: ';print_r($eventData);echo '</pre>'; outputs all and the plugins have a global $serendipity and still need this as params['data'] referenced. WHY???
        #echo '<pre>ref-Serendipity: ';print_r($params['data']);echo '</pre>';
    }

    if (empty($params['addData'])) {
        $params['addData'] = null;
    }

    serendipity_plugin_api::hook_event($params['hook'], $params['data'], $params['addData']);
}

/**
 * Smarty Modifier: Be able to execute the hook of an event plugin and return its output, uses a REFERENCED variable.
 *
 * Listens to specific serendipity global variables:
 *  $serendipity['skip_smarty_hooks'] - If TRUE, no plugins will be executed at all
 *  $serendipity['skip_smarty_hook']  - Can be set to an array of plugin hooks to NOT execute
 *
 * @access public
 * @param   mixed       EventData (referenced)
 * @param   string      Event hook name
 * @param   mixed       Additional data
 * @return null
 */
function serendipity_smarty_refhookPlugin(&$eventData, $hook, $addData = null) {
    global $serendipity;

    if (empty($hook)) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'hook' parameter", E_USER_WARNING);
        return;
    }

    // Smarty hooks can be bypassed via an internal variable (temporarily)
    if (isset($serendipity['skip_smarty_hooks']) && $serendipity['skip_smarty_hooks']) {
        return;
    }

    // A specific hook can also be bypassed by creating an associative array like this:
    // $serendipity['skip_smarty_hook'] = array('entries_header');
    // That would only skip the entries_header event hook, but allow all others.
    // Of course it cannot be used in conjunction with the all-blocking skip_smarty_hooks.
    if (isset($serendipity['skip_smarty_hook']) && is_array($serendipity['skip_smarty_hook']) && isset($serendipity['skip_smarty_hook'][$hook])) {
        return;
    }

    serendipity_plugin_api::hook_event($hook, $eventData, $addData);
}

/**
 * Smarty Function: Prints a list of sidebar plugins
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          side: The plugin side to show (left|right|hide)
 * @param   object      Smarty template object
 * @return string       The HTML code of a plugin's output
 */
function serendipity_smarty_printSidebar($params, $template) {
    if (empty($params['side'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'side' parameter", E_USER_WARNING);
        return;
    }

    if (!empty($params['template'])) {
        return serendipity_plugin_api::generate_plugins($params['side'], false, null, null, $params['template']);
    } else {
        return serendipity_plugin_api::generate_plugins($params['side']);
    }
}

/**
 * Smarty Function: Get the full path to a template file
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          file: The filename you want to include (any file within your template directory or the 'default' template if not found)
 * @param   object      Smarty template object
 * @return  string      The requested filename with full path
 */
function serendipity_smarty_getFile($params, $template) {
    if (empty($params['file'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'file' parameter", E_USER_WARNING);
        return;
    }
    return serendipity_getTemplateFile($params['file']);
}

/**
 * Smarty Function: Get a plugins config value by key
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          key: The plugin config value
 * @param   object      Smarty template object
 * @return  string      The requested configutation value
 */
function serendipity_smarty_getConfigVar($params, $template) {
    if (empty($params['key'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'key' parameter", E_USER_WARNING);
        return;
    }
    return serendipity_get_config_var($params['key']);
}

/**
 * Smarty Function: Set the valid form token by type or default
 *
 * @access public
 * @param  array       Smarty parameter input array:
 *                          type: The form token by a given type
 * @param  object      Smarty template object
 * @return string      The requested valid form token
 */
function serendipity_smarty_setFormToken($params, $template) {
    if (!empty($params['type'])) {
        return serendipity_setFormToken($params['type']);
    }
    return serendipity_setFormToken();
}

/**
 * Smarty Function: Picks a specified key from an array and returns it
 *
 * @access public
 * @param  array       Smarty parameter input array:
 *                          array: The array you want to check
 *                          key: The keyname
 *                          default: What (string) to return when array does not contain the key.
 * @param  object      Smarty template object
 * @return string      The requested filename with full path
 */
function serendipity_smarty_pickKey($params, $template) {
    if (empty($params['array'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'array' parameter", E_USER_WARNING);
        return;
    }

    if (empty($params['key'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'key' parameter", E_USER_WARNING);
        return;
    }

    return serendipity_pickKey($params['array'], $params['key'], $params['default']);
}


/**
 * Smarty Function: Get a permalink for an entry
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          entry: $entry data to pull title etc. out of
 *                          is_comments: Whether a permalink for a comment feed should be embedded
 * @param   object      Smarty template object
 * @return string       The permalink
 */
function serendipity_smarty_rss_getguid($params, $template) {
    if (empty($params['entry'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'entry' parameter", E_USER_WARNING);
        return;
    }
    if (empty($params['is_comments']) && !is_bool($params['is_comments'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'is_comments' parameter", E_USER_WARNING);
        return;
    }

    return serendipity_rss_getguid($params['entry'], $params['is_comments']);
}

/**
 * Smarty Modifier: Format a timestamp
 *
 * @access public
 * @param   int     The timestamp to format (unix seconds)
 * @param   string  The strftime() format options on how to format this string
 * @param   boolean Shall timezone conversions be applied?
 * @param   boolean Try to detect a valid timestamp?
 * @param   boolean Use strftime or date?
 * @return
 */
function serendipity_smarty_formatTime($timestamp, $format, $useOffset = true, $detectTimestamp = false, $useDate = false) {
    if ($detectTimestamp !== false && stristr($detectTimestamp, 'date') === false) {
        return $timestamp;
    }

    if (defined($format)) {
        return serendipity_formatTime(constant($format), $timestamp, $useOffset, $useDate);
    } else {
        return serendipity_formatTime($format, $timestamp, $useOffset, $useDate);
    }
}

/**
 * Smarty Function: Show comments to an entry
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          entry: The $entry data
 *                          mode: The default viewmode (threaded/linear)
 * @param   object      Smarty template object
 * @return  string      The HTML code with the comments
 */
function &serendipity_smarty_printComments($params, $template) {
    global $serendipity;

    if (empty($params['entry'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'entry' parameter", E_USER_WARNING);
        return;
    }

    if (empty($params['mode'])) {
        $params['mode'] = VIEWMODE_THREADED;
    }

    if (isset($params['order']) && $params['order'] != 'DESC') {
        $params['order'] = 'ASC';
    }

    $params['limit'] = isset($params['limit']) ? (int)$params['limit'] : null;
    $params['order'] = $params['order'] ?? '';

    $comments = serendipity_fetchComments($params['entry'], $params['limit'], 'co.id ' . $params['order']);

    if (!empty($serendipity['POST']['preview'])) {
        $comments[] =
            array(
                    'email'     => $serendipity['POST']['email'],
                    'author'    => $serendipity['POST']['name'],
                    'body'      => $serendipity['POST']['comment'],
                    'url'       => $serendipity['POST']['url'],
                    'parent_id' => $serendipity['POST']['replyTo'],
                    'timestamp' => time()
            );
    }

    if (empty($params['depth'])) {
        $params['depth'] = 0;
    }

    if (empty($params['trace'])) {
        $params['trace'] = null;
    }

    if (empty($params['block'])) {
        $params['block'] = 'COMMENTS';
    }

    if (empty($params['template'])) {
        $params['template'] = 'comments.tpl';
    }

    $out = serendipity_printComments($comments, $params['mode'], $params['depth'], $params['trace'], $params['block'], $params['template']);
    return $out;
}

/**
 * Smarty Function: Show Trackbacks
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                          entry: The $entry data for the trackbacks
 * @param   object      Smarty template object
 * @return
 */
function &serendipity_smarty_printTrackbacks($params, $template) {
    if (empty($params['entry'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'entry' parameter", E_USER_WARNING);
        return;
    }

    $trackbacks =& serendipity_fetchTrackbacks($params['entry']);

    if (empty($params['depth'])) {
        $params['depth'] = 0;
    }

    if (empty($params['trace'])) {
        $params['trace'] = null;
    }

    if (empty($params['block'])) {
        $params['block'] = 'TRACKBACKS';
    }

    if (empty($params['template'])) {
        $params['template'] = 'trackbacks.tpl';
    }

    $out = serendipity_printComments($trackbacks, VIEWMODE_LINEAR, $params['depth'], $params['trace'], $params['block'], $params['template']);
    return $out;
}

/**
 * Get the Serendipity dimensions for an image
 *
 * @access public
 * @param   array       Smarty parameter input array:
 *                      file: The image file to get image data for
 *                      assign: The variable to assign the image data array to
 * @param   object      Smarty template object
 * @return  string      Empty
 */
function serendipity_smarty_getImageSize($params, Smarty_Internal_Template $template) {

    if (empty($params['file'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'file' parameter", E_USER_WARNING);
        return;
    }
    if (empty($params['assign'])) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ": missing 'assign' parameter", E_USER_WARNING);
        return;
    }

    // Is it a correct filesystem absolute path?
    $file = $params['file'];
    // Most likely the user specified an HTTP path
    if (!file_exists($file)) {
        $file = $_SERVER['DOCUMENT_ROOT'] . $file;
    }
    // Maybe wants a template file (returns filesystem path)
    if (!file_exists($file)) {
        $file = serendipity_getTemplateFile($params['file'], 'serendipityPath', true);
    }

    // If no file, trigger an error
    if (!file_exists($file)) {
        trigger_error('Smarty Error: ' . __FUNCTION__ . ': file "' . $params['file'] . '" ' . strtolower(NOT_FOUND) . ' ', E_USER_WARNING);
        return;
    }
    $template->assign($params['assign'], @serendipity_getImageSize($file));
}

/**
 * Smarty Prefilter: Replace constants to direct $smarty.const. access
 *
 * @access public
 * @param   string  Template source content
 * @param   object  Smarty template object
 * @return
 */
function serendipity_replaceSmartyVars($source, $template) {
     return str_replace('$CONST.', '$smarty.const.', $source);
}

/**
 * Initialize the Smarty framework for use in Serendipity
 *
 * @access public
 * @return null
 */
function serendipity_smarty_init($vars = array()) {
    global $serendipity, $template_config, $template_global_config, $template_config_groups;

    if (empty($serendipity['smarty'])) {

        $template_dir = $serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template'];

        if (!defined('IN_serendipity_admin') && file_exists($template_dir . '/template.inc.php')) {
            // If this file exists, a custom template engine will be loaded.
            // Beware: Smarty is used in the Administration Backend, despite of this.
            // Special case "preview_iframe(d) Backend previews" are configured directly in the serendipity_iframe() function, since being a different "scope"
            include_once $template_dir . '/template.inc.php';
        } else {

            // Backend template overwritten here (NOT earlier due to Frontend specific check)
            if (defined('IN_serendipity_admin')) {
                $template_dir = $serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template_backend'];
            }

            // Set a session variable if Smarty fails:
            $prev_smarty = $_SESSION['no_smarty'] ?? null;
            $_SESSION['no_smarty'] = true;

            if (LANG_CHARSET != 'UTF-8') {
                @define('SMARTY_MBSTRING', false);
                @define('SMARTY_RESOURCE_CHAR_SET', LANG_CHARSET);
            }

            // define cache resources to load with Smarty - see Smarty cache readme - needs enabled cache, which does not work the way we use Smarty!
            #@define('APC_EXTENSION_LOADED', extension_loaded('apc') && ini_get('apc.enabled'));
            #@define('MEMCACHE_EXTENSION_LOADED', (class_exists('Memcached',false) || class_exists('Memcache',false)) && (extension_loaded("memcached") || extension_loaded("memcache")));

            // Default Smarty Engine will be used
            @define('SMARTY_DIR', S9Y_PEAR_PATH . 'Smarty/libs/');
            if (!class_exists('Smarty')) {
                include_once SMARTY_DIR . 'Smarty.class.php';
            }

            if (!class_exists('Smarty')) {
                return false;
            }

            // include the serendipity smarty constructor
            if (!class_exists('Serendipity_Smarty')) {
                include_once S9Y_INCLUDE_PATH . 'include/serendipity_smarty_class.inc.php';
            }

            if (!class_exists('Serendipity_Smarty')) {
                return false;
            }

            // set smarty instance
            #$serendipity['smarty'] = new \Serendipity_Smarty;
            // initialize smarty object by instance
            $serendipity['smarty'] = Serendipity_Smarty::getInstance();
            // debug moved to class

            // Hooray for Smarty:
            $_SESSION['no_smarty'] = $prev_smarty;

            // enable security policy by instance of the Smarty_Security class
            $serendipity['smarty']->enableSecurity('Serendipity_Smarty_Security_Policy');

            // debugging...
            #echo '<pre>';print_r($serendipity['smarty']);echo '</pre>';#exit;
            #$serendipity['smarty']->testInstall();exit;
            // extreme debugging with undocumented internal flag which enables a trace output from the parser during debugging
            #$serendipity['smarty']->_parserdebug = true; // be careful!

            /**
             * ToDo: Check for possible API changes in Smarty 3.2 [smarty_modifier_foobar, --> [smarty_modifier_foobar, smarty_function_foobar, smarty_block_foobar] (in class)]
             * smarty_modifier_foobar(Smarty $smarty, $string, ...) vs. smarty_modifier_foobar($string, ...)
             **/
            $serendipity['smarty']->registerPlugin('modifier', 'makeFilename', 'serendipity_makeFilename');
            $serendipity['smarty']->registerPlugin('modifier', 'xhtml_target', 'serendipity_xhtml_target');
            $serendipity['smarty']->registerPlugin('modifier', 'emptyPrefix', 'serendipity_emptyPrefix');
            $serendipity['smarty']->registerPlugin('modifier', 'formatTime', 'serendipity_smarty_formatTime');
            $serendipity['smarty']->registerPlugin('modifier', 'serendipity_utf8_encode', 'serendipity_utf8_encode');
            $serendipity['smarty']->registerPlugin('modifier', 'ifRemember', 'serendipity_ifRemember');
            $serendipity['smarty']->registerPlugin('modifier', 'checkPermission', 'serendipity_checkPermission');
            $serendipity['smarty']->registerPlugin('modifier', 'serendipity_refhookPlugin', 'serendipity_smarty_refhookPlugin');
            $serendipity['smarty']->registerPlugin('modifier', 'serendipity_html5time', 'serendipity_smarty_html5time');
            $serendipity['smarty']->registerPlugin('modifier', 'rewriteURL', 'serendipity_rewriteURL');
            $serendipity['smarty']->registerPlugin('modifier', 'cleanChars', 'serendipity_cleanChars');

            $serendipity['smarty']->registerPlugin('function', 'serendipity_printSidebar', 'serendipity_smarty_printSidebar');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_hookPlugin', 'serendipity_smarty_hookPlugin');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_showPlugin', 'serendipity_smarty_showPlugin');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_getFile', 'serendipity_smarty_getFile');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_printComments', 'serendipity_smarty_printComments');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_printTrackbacks', 'serendipity_smarty_printTrackbacks');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_rss_getguid', 'serendipity_smarty_rss_getguid');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_fetchPrintEntries', 'serendipity_smarty_fetchPrintEntries');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_getTotalCount', 'serendipity_smarty_getTotalCount');
            $serendipity['smarty']->registerPlugin('function', 'pickKey', 'serendipity_smarty_pickKey');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_showCommentForm', 'serendipity_smarty_showCommentForm');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_getImageSize', 'serendipity_smarty_getImageSize');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_getConfigVar', 'serendipity_smarty_getConfigVar');
            $serendipity['smarty']->registerPlugin('function', 'serendipity_setFormToken', 'serendipity_smarty_setFormToken');

            $serendipity['smarty']->registerFilter('pre', 'serendipity_replaceSmartyVars'); // see the DOC Note

        }

        if (empty($serendipity['smarty_file'])) {
            $serendipity['smarty_file'] = 'index.tpl';
        }

        $category      = false;
        $category_info = array();
        if (isset($serendipity['GET']['category'])) {
            $category = (int)$serendipity['GET']['category'];
            if (isset($GLOBALS['cInfo'])) {
                $category_info = $GLOBALS['cInfo'];
            } else {
                $category_info = serendipity_fetchCategoryInfo($category);
            }
        }

        if (empty($serendipity['smarty_vars']['head_link_stylesheet'])) {
            $serendipity['smarty_vars']['head_link_stylesheet_frontend'] = serendipity_rewriteURL('serendipity.css');

            if (defined('IN_serendipity_admin') && IN_serendipity_admin === true) {
                $serendipity['smarty_vars']['head_link_stylesheet'] = serendipity_rewriteURL('serendipity_admin.css');
            } else {
                $serendipity['smarty_vars']['head_link_stylesheet'] = serendipity_rewriteURL('serendipity.css');
            }

            // When templates are switched, append a specific version string to make sure the browser does not cache the CSS
            if (strstr($serendipity['smarty_vars']['head_link_stylesheet'], '?')) {
                $serendipity['smarty_vars']['head_link_stylesheet'] .= '&amp;v=' . ($serendipity['last_template_change'] ?? time());
                $serendipity['smarty_vars']['head_link_stylesheet_frontend'] .= '&amp;v=' . ($serendipity['last_template_change'] ?? time());
            } else {
                $serendipity['smarty_vars']['head_link_stylesheet'] .= '?v=' . ($serendipity['last_template_change'] ?? time());
                $serendipity['smarty_vars']['head_link_stylesheet_frontend'] .= '?v=' . ($serendipity['last_template_change'] ?? time());
            }
        }

        if (empty($serendipity['smarty_vars']['head_link_script'])) {
            if (defined('IN_serendipity_admin') && IN_serendipity_admin === true) {
                $serendipity['smarty_vars']['head_link_script'] = serendipity_rewriteURL('serendipity_admin.js');
            } else {
                $serendipity['smarty_vars']['head_link_script'] = serendipity_rewriteURL('serendipity.js');
            }

            if (strstr($serendipity['smarty_vars']['head_link_script'], '?')) {
                $serendipity['smarty_vars']['head_link_script'] .= '&amp;v=' . ($serendipity['last_template_change'] ?? time());
            } else {
                $serendipity['smarty_vars']['head_link_script'] .= '?v=' . ($serendipity['last_template_change'] ?? time());
            }
        }

        $_force_backendpopups = explode(',', ($serendipity['enableBackendPopupGranular'] ?? 'links')); // 'links' container is the only one in need to be non mpf layered per default install for the quicktip doc
        $force_backendpopups  = array();
        foreach($_force_backendpopups AS $fbp_key => $fbp_val) {
            $fbp_val = trim($fbp_val);
            if (empty($fbp_val)) continue;
            $force_backendpopups[$fbp_val] = $fbp_val;
        }

        /* Variable 'is_xhtml' deprecated with 2.1, keep for compatibility only, since this was configurable in old Serendipity versions and used in some themes (eg. bulletproof) */
        /* Variable 'head_version' deprecated before 1.1-alpha, @see https://github.com/ophian/styx/commit/529dca9 as last history and only kept for old themes */
        $serendipity['smarty']->assign(
            array(
                'head_charset'              => LANG_CHARSET,
                'head_version'              => $serendipity['version'],
                'head_title'                => $serendipity['head_title'],
                'head_subtitle'             => $serendipity['head_subtitle'],
                'head_link_stylesheet'      => $serendipity['smarty_vars']['head_link_stylesheet'],
                'head_link_script'          => $serendipity['smarty_vars']['head_link_script'],
                'head_link_stylesheet_frontend' => $serendipity['smarty_vars']['head_link_stylesheet_frontend'] ?? null,

                'is_xhtml'                  => true,
                'use_popups'                => $serendipity['enablePopup'] ?? false,
                'use_backendpopups'         => $serendipity['enableBackendPopup'] ?? false,
                'force_backendpopups'       => $force_backendpopups,
                'is_embedded'               => (empty($serendipity['embed']) || $serendipity['embed'] === 'false' || $serendipity['embed'] === false) ? false : true,
                'is_raw_mode'               => $serendipity['smarty_raw_mode'],
                'is_logged_in'              => serendipity_userLoggedIn(),

                'entry_id'                  => (isset($serendipity['GET']['id']) && is_numeric($serendipity['GET']['id'])) ? $serendipity['GET']['id'] : false,
                'is_single_entry'           => (isset($serendipity['GET']['id']) && is_numeric($serendipity['GET']['id'])),

                'blogTitle'                 => $serendipity['blogTitle'] ?? '',
                'blogSubTitle'              => (!empty($serendipity['blogSubTitle']) ? $serendipity['blogSubTitle'] : ''),
                'blogDescription'           => $serendipity['blogDescription'] ?? '',

                'serendipityHTTPPath'       => $serendipity['serendipityHTTPPath'] ?? '',
                'serendipityDefaultBaseURL' => $serendipity['defaultBaseURL'] ?? '',
                'serendipityBaseURL'        => $serendipity['baseURL'] ?? '',
                'serendipityRewritePrefix'  => $serendipity['rewrite'] == 'none' ? $serendipity['indexFile'] . '?/' : '',
                'serendipityIndexFile'      => $serendipity['indexFile'],
                'serendipityVersion'        => $serendipity['expose_s9y'] ? $serendipity['version'] : '',

                'lang'                      => $serendipity['lang'],
                'category'                  => $category,
                'category_info'             => $category_info,
                'template'                  => $serendipity['template'],
                'templatePath'              => $serendipity['templatePath'],
                'template_backend'          => $serendipity['template_backend'],
                'wysiwyg_comment'           => $serendipity['allowHtmlComment'] ?? false,
                'use_autosave'              => (isset($serendipity['use_autosave']) && serendipity_db_bool($serendipity['use_autosave'])) ? 'true' : 'false',

                'dateRange'                 => (!empty($serendipity['range']) ? $serendipity['range'] : array())
            )
        );

        if (count($vars) > 0) {
            $serendipity['smarty']->assign($vars);
        }

        // For advanced usage, we allow template authors to create a file 'config.inc.php' where they can
        // setup custom Smarty variables, modifiers etc. to use in their templates.

        // If a template engine is defined we need that config.inc.php file as well. The template's actual file is loaded after that to be able to overwrite config.
        if (isset($serendipity['template_engine']) && $serendipity['template_engine'] != null) {
            $multi_engine_config = [];
            $p = explode(',', $serendipity['template_engine']);
            // Check multi configuration files
            $c = (count($p) > 1) ? true : false;
            // AN ENGINE ORDER set must always be: Parent, Grand - which is equal to the $template_dirs (serendipity_smarty_class) array ORDER, eg "b46", => "bootstrap4" [ => Standard => Backend => Plugin dir => Default ]
            if ($c) {
                // BUT for configuration variable merges we have to change the ORDER to overwrite duplicates with the last set !
                $p = array_reverse($p);
            }
            foreach($p AS $te) {
                $config = $serendipity['serendipityPath'] . $serendipity['templatePath'] . trim($te) . '/config.inc.php';

                if (file_exists($config)) {
                    include_once $config;
                    if ($c && isset($template_loaded_config) && is_array($template_loaded_config)) {
                        $multi_engine_config[] = $template_loaded_config;
                    }
                }
            }
        }

        // FIRST: Load config of the currently configured FRONTEND template. We might actually need this in the Backend (sidebar configuration, IPTC options, some others).
        // SECOND: Load config of the currently set template, which can also be the BACKEND template, or be the same as before. include_once takes care of only including the file once.
        $config = $serendipity['serendipityPath'] . $serendipity['templatePath'] . $serendipity['template'] . '/config.inc.php';
        if (file_exists($config)) {
            include_once $config;
            if (!empty($multi_engine_config)) {
                $multi_engine_config[] = $template_loaded_config;
            }
        }

        $config = $serendipity['smarty']->getConfigDir(0) . '/config.inc.php';
        if (file_exists($config)) {
            include_once $config;
            if (!empty($multi_engine_config)) {
                $multi_engine_config[] = $template_loaded_config;
            }
        }

        if (isset($template_loaded_config) && is_array($template_loaded_config)) {
            if (!empty($multi_engine_config)) {
                // merge multi engine theme configuration together uniquely
                $template_loaded_config = call_user_func_array('array_merge', $multi_engine_config);
            }
            $template_vars =& $template_loaded_config;
            $serendipity['smarty']->assignByRef('template_option', $template_vars);
        } elseif (is_array($template_config)) {
            $template_vars =& serendipity_loadThemeOptions($template_config, $serendipity['smarty_vars']['template_option']);
            $serendipity['smarty']->assignByRef('template_option', $template_vars);
        } else {
            // themes without a config
            $template_vars = array('date_format' => null);
            $serendipity['smarty']->assign('template_option', $template_vars);
        }
    }

    return true;
}

/**
 * Purge compiled Smarty Templates completely
 *
 * @access public
 * @return null
 */
function serendipity_smarty_purge() {
    global $serendipity;

    if (!is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }
    $_cdir = new RecursiveDirectoryIterator($serendipity['smarty']->getCompileDir());
    $_dirs = new RecursiveIteratorIterator($_cdir);
    $files = new RegexIterator($_dirs, '@.*\.tpl\.php$@', RegexIterator::GET_MATCH);
    foreach($files AS $file) {
        if (is_writable($file[0])) {
            unlink($file[0]);
        } else {
            if (is_object($serendipity['logger'])) $serendipity['logger']->warning('Could not delete ' . $file[0]);
        }
    }
}

/**
 * Shut down the Smarty Framework, output all parsed data
 *
 * This function is meant to be used in embedded installations, like in Gallery.
 * Function can be called from foreign applications. ob_start() needs to
 * have been called before, and will be parsed into Smarty here
 *
 * @access public
 * @param  string  The path to Serendipity
 * @return null
 */
function serendipity_smarty_shutdown($serendipity_directory = '') {
    global $serendipity;

    #$cwd = getcwd();
    chdir($serendipity_directory);
    $raw_data = ob_get_contents();
    ob_end_clean();
    $serendipity['smarty']->assignByRef('content_message', $raw_data);

    serendipity_smarty_fetch('CONTENT', 'content.tpl');
    $serendipity['smarty']->assign('ENTRIES', '');
    if (empty($serendipity['smarty_file'])) {
        $serendipity['smarty_file'] = '404.tpl';
    }
    $serendipity['smarty']->display(serendipity_getTemplateFile($serendipity['smarty_file'], 'serendipityPath'));
}

/**
 * Render a smarty-template
 *
 * @access public
 * @param  string  $tplfile: path to the template-file
 * @param  array   $data: map with the variables to assign
 * @param  string  $debugtype: If set, debug string is preceded. Can be set to HTML or JS.
 * @param  string  $debug: Possible debug string that is preceded to output
 *
 * @return string  compiled file
 */
function serendipity_smarty_showTemplate($tplfile, $data = null, $debugtype = null, $debug = null) {
    global $serendipity;

    if (!isset($serendipity['smarty']) || !is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }

    if ($data !== null) {
        // An iframed preview meeds to remove the loading="lazy" attribute to get the correct height !!
        if ($tplfile == 'preview_iframe.tpl' && isset($data['preview'])) {
            $data['preview'] = str_replace(' loading="lazy"', '', $data['preview']);
        }
        $serendipity['smarty']->assign($data);
    }

    $tfile = ($tplfile == 'preview_iframe.tpl')
                ? serendipity_getTemplateFile($tplfile, 'serendipityPath', true)
                : serendipity_getTemplateFile($tplfile, 'serendipityPath');

    if ($debug !== null) {
        if ($debugtype == 'HTML') {
            $debug = '<!-- Dynamically fetched ' . serendipity_specialchars(str_replace($serendipity['serendipityPath'], '', $tfile)) . ' on ' . date('Y-m-d H:i') . ', called from: ' . $debug . " -->\n";
        } else {
            $debug = '/* Dynamically fetched ' . serendipity_specialchars(str_replace($serendipity['serendipityPath'], '', $tfile)) . ' on ' . date('Y-m-d H:i') . ', called from: ' . $debug . " */\n";
        }
    }

    return $debug . $serendipity['smarty']->fetch('file:'. $tfile);
}
