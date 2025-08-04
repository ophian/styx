<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_archives extends serendipity_plugin
{
    public $title = ARCHIVES;

    function introspect(&$propbag)
    {
        $propbag->add('name',          ARCHIVES);
        $propbag->add('description',   BROWSE_ARCHIVES);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team, Ian Styx');
        $propbag->add('version',       '2.0.0');
        $propbag->add('configuration', array('title', 'frequency', 'count', 'show_count', 'hide_zero_count'));
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {

            case 'title':
                $propbag->add('type',       'string');
                $propbag->add('name',       TITLE);
                $propbag->add('description', TITLE_FOR_NUGGET);
                $propbag->add('default',    ARCHIVES);
                break;

            case 'count' :
                $propbag->add('type',       'string');
                $propbag->add('name',        ARCHIVE_COUNT);
                $propbag->add('description', ARCHIVE_COUNT_DESC);
                $propbag->add('default',     3);
                break;

            case 'frequency' :
                $propbag->add('type',       'select');
                $propbag->add('name',       ARCHIVE_FREQUENCY);
                $propbag->add('select_values', array('months' => MONTHS, 'weeks' => WEEKS, 'days' => DAYS));
                $propbag->add('description', ARCHIVE_FREQUENCY_DESC);
                $propbag->add('default',    'months');
                break;

            case 'show_count':
                $propbag->add('type',       'boolean');
                $propbag->add('name',       CATEGORY_PLUGIN_SHOWCOUNT);
                $propbag->add('description', '');
                $propbag->add('default',    'false');
                break;

            case 'hide_zero_count':
                $propbag->add('type',       'boolean');
                $propbag->add('name',        CATEGORY_PLUGIN_HIDEZEROCOUNT);
                $propbag->add('description', '');
                $propbag->add('default',    'false');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->get_config('title', $this->title);
        $ts = mktime(0, 0, 0, (int) date('m'), 1);
        $add_query = '';

        if (class_exists('serendipity_event_categorytemplates')) {
            $uri = $_SERVER['REQUEST_URI'];
            $args = serendipity_getUriArguments($uri);
        }

        $category_set = !empty($serendipity['GET']['category']);
        if ($category_set) {
            $base_query = 'C' . (int)$serendipity['GET']['category'];
            $add_query = '/' . $base_query;
            $title .= ' ' . $base_query . '';
        }

        $max_x = $this->get_config('count', 3);
        $show_count = serendipity_db_bool($this->get_config('show_count', 'false'));
        $hide_zero_count = serendipity_db_bool($this->get_config('hide_zero_count', 'false'));
        $freq = $this->get_config('frequency', 'months');

        echo '<ul class="plainList">' . "\n";

        if ($serendipity['dbType'] == 'sqlite' || $serendipity['dbType'] == 'sqlite3' || $serendipity['dbType'] == 'sqlite3oo' || $serendipity['dbType'] == 'pdo-sqlite') {
            $dist_sql = 'count(e.id) AS orderkey';
        } else {
            $dist_sql = 'count(DISTINCT e.id) AS orderkey';
        }

        for($x = 0; $x < $max_x; $x++) {
            $current_ts = $ts;
            switch($freq) {
                case 'months' :
                    switch($serendipity['calendar']) {
                        default:
                        case 'gregorian':
                            $linkStamp = date('Y/m', $ts);
                            $ts_title = serendipity_formatTime("%B %Y", $ts, false);
                            $ts = mktime(0, 0, 0, (int) date('m', $ts)-1, 1, (int) date('Y', $ts)); // Must be last in 'case' statement
                            break;
                        case 'persian-utf8':
                            require_once S9Y_INCLUDE_PATH . 'include/functions_calendars.inc.php';
                            $linkStamp = persian_date_utf('Y/m', $ts);
                            $ts_title = serendipity_formatTime("%B %Y", $ts, false);
                            $ts = persian_mktime(0, 0, 0, persian_date_utf('m', $ts)-1, 1, persian_date_utf('Y', $ts)); // Must be last in 'case' statement
                            break;
                    }
                    break;
                case 'weeks' :
                    switch($serendipity['calendar']) {
                        default:
                        case 'gregorian':
                            $linkStamp = date('Y/\WW', $ts);
                            $ts_title = WEEK . ' '. date('W, Y', $ts);
                            $ts = mktime(0, 0, 0, (int) date('m', $ts), (int) date('d', $ts)-7, (int) date('Y', $ts));
                            break;
                        case 'persian-utf8':
                            require_once S9Y_INCLUDE_PATH . 'include/functions_calendars.inc.php';
                            $linkStamp = persian_date_utf('Y/\WW', $ts);
                            $ts_title = WEEK . ' '. persian_date_utf('W، Y', $ts);
                            $ts = persian_mktime(0, 0, 0, persian_date_utf('m', $ts), persian_date_utf('d', $ts)-7, persian_date_utf('Y', $ts));
                            break;
                    }
                    break;
                case 'days' :
                    switch($serendipity['calendar']) {
                        default:
                        case 'gregorian':
                            $linkStamp = date('Y/m/d', $ts);
                            $ts_title = serendipity_formatTime("%B %e. %Y", $ts, false);
                            $ts = mktime(0, 0, 0, (int) date('m', $ts), (int) date('d', $ts)-1, (int) date('Y', $ts)); // Must be last in 'case' statement
                            break;
                        case 'persian-utf8':
                            require_once S9Y_INCLUDE_PATH . 'include/functions_calendars.inc.php';
                            $linkStamp = persian_date_utf('Y/m/d', $ts);
                            $ts_title = serendipity_formatTime("%e %B %Y", $ts, false);
                            $ts = persian_mktime(0, 0, 0, persian_date_utf('m', $ts), persian_date_utf('d', $ts)-1, persian_date_utf('Y', $ts)); // Must be last in 'case' statement
                            break;
                    }
                    break;
            }
            $link = serendipity_rewriteURL(PATH_ARCHIVES . '/' . $linkStamp . $add_query . '.html', 'serendipityHTTPPath');

            $html_count = '';
            $hidden_by_zero_count = false;
            if ($show_count) {
                switch($freq) {
                    case 'months':
                        $end_ts = $current_ts + (date('t', $current_ts) * 24 * 60 * 60) - 1;
                        break;
                    case 'weeks':
                        $end_ts = $current_ts + (7 * 24 * 60 * 60) - 1;
                        break;
                    case 'days':
                        $end_ts = $current_ts + (24 * 60 * 60) - 1;
                        break;
                }

                $ec = serendipity_fetchEntries(
                    array($current_ts, $end_ts),
                    false,
                    '',
                    false,
                    false,
                    null,
                    '',
                    false,
                    true,
                    $dist_sql,
                    '',
                    'single',
                    false, $category_set // the joins used
                );

                if (is_array($ec)) {
                    if (empty($ec['orderkey'])) {
                        $ec['orderkey'] = '0';
                    }
                    $hidden_by_zero_count = $hide_zero_count && ( $ec['orderkey'] == '0');
                    $html_count .= ' (' . $ec['orderkey'] . ')';
                }
            }

            if (!$hidden_by_zero_count) {
                echo '                        <li><a href="' . $link . '" title="' . $ts_title . '">' . $ts_title . $html_count . "</a></li>\n";
            }
        }
        // Category views are either "archives/C%ID%.html" OR "categories/%ID%-name"
        // find category archive
        if (isset($args[0]) && $args[0] == PATH_ARCHIVE && isset($args[1][0]) && $args[1][0] == 'C' && isset($serendipity['GET']['subpage'])) {
            echo '                        <li><a href="' . str_replace(PATH_ARCHIVE, PATH_ARCHIVES, $serendipity['GET']['subpage']) . '.html">' . RECENT. "</a></li>\n";
        } else
            // find categories path
            if (isset($args[0]) && $args[0] == PATH_CATEGORIES && isset($args[1])) {
                echo '                        <li><a href="' . ($serendipity['GET']['subpage'] ?? '') . '">' . RECENT. "</a></li>\n";
        } else
            // find category startpage
            if (isset($args[1]) && isset($serendipity['GET']['subpage']) && $serendipity['GET']['subpage'] == $serendipity['serendipityHTTPPath'] . PATH_ARCHIVES . '/' . $args[1] . '.html') {
                echo '                        <li><a href="' . $serendipity['GET']['subpage'] . '">' . RECENT. "</a></li>\n";
        } else
            // find category sub (month) view
            if (!empty($args) && isset($base_query) && $base_query == $args[count($args)-1]) {
                echo '                        <li><a href="' . serendipity_rewriteURL(PATH_ARCHIVES . '/' . $base_query . '.html') . '">' . RECENT. "</a></li>\n";
        } else {
            // set blogs frontpage
            echo '                        <li><a href="'. $serendipity['serendipityHTTPPath'] . $serendipity['indexFile'] . '?frontpage">' . RECENT . "</a></li>\n";
        }
        echo '                        <li><a href="'. serendipity_rewriteURL(PATH_ARCHIVE . $add_query) .'">' . OLDER . "</a></li>\n";
        echo "                    </ul>\n";
    }

}

?>