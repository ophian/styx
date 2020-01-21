<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_plugin_history extends serendipity_plugin
{
    var $title = PLUGIN_HISTORY_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $this->title = $this->get_config('title', $this->title);

        $propbag->add('name',          PLUGIN_HISTORY_NAME);
        $propbag->add('description',   PLUGIN_HISTORY_DESC);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Jannis Hermanns, Ian Styx');
        $propbag->add('version',       '1.19');
        $propbag->add('requirements',  array(
            'serendipity' => '1.6',
            'smarty'      => '2.6.7',
            'php'         => '4.1.0'
        ));
        $propbag->add('groups', array('FRONTEND_VIEWS'));
        $propbag->add('configuration', array('title',
                                             'intro',
                                             'outro',
                                             'maxlength',
                                             'specialage',
                                             'min_age',
                                             'max_age',
                                             'multiyears',
                                             'max_entries',
                                             'full',
                                             'amount',
                                             'displaydate',
                                             'displayauthor',
                                             'dateformat'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {

            case 'title':
                $propbag->add('type', 'string');
                $propbag->add('name', TITLE);
                $propbag->add('description', '');
                $propbag->add('default', PLUGIN_HISTORY_NAME);
                break;

            case 'intro':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_INTRO);
                $propbag->add('description', PLUGIN_HISTORY_INTRO_DESC);
                $propbag->add('default', '');
                break;

            case 'outro':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_OUTRO);
                $propbag->add('description', PLUGIN_HISTORY_OUTRO_DESC);
                $propbag->add('default', '');
                break;

            case 'maxlength':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MAXLENGTH);
                $propbag->add('description', PLUGIN_HISTORY_MAXLENGTH_DESC);
                $propbag->add('default', 30);
                break;

            case 'specialage':
                $propbag->add('type', 'select');
                $propbag->add('name', PLUGIN_HISTORY_SPECIALAGE);
                $propbag->add('description', PLUGIN_HISTORY_SPECIALAGE_DESC);
                $propbag->add('default', 'year');
                $propbag->add('select_values', array('year'=>PLUGIN_HISTORY_OYA,'custom'=>PLUGIN_HISTORY_MYSELF));
                break;

            case 'min_age':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MIN_AGE);
                $propbag->add('description', PLUGIN_HISTORY_MIN_AGE_DESC);
                $propbag->add('default', 365);
                break;

            case 'max_age':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MAX_AGE);
                $propbag->add('description', PLUGIN_HISTORY_MAX_AGE_DESC);
                $propbag->add('default', 365);
                break;

            case 'multiyears':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MULTIYEARS);
                $propbag->add('description', PLUGIN_HISTORY_MULTIYEARS_DESC);
                $propbag->add('default', '1');
                break;

            case 'max_entries':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MAX_ENTRIES);
                $propbag->add('description', PLUGIN_HISTORY_MAX_ENTRIES_DESC);
                $propbag->add('default', 5);
                break;

            case 'dateformat':
                $propbag->add('type', 'string');
                $propbag->add('name', GENERAL_PLUGIN_DATEFORMAT);
                $propbag->add('description', sprintf(GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH, '%a, %d.%m.%Y %H:%M'));
                $propbag->add('default', '%a, %d.%m.%Y %H:%M');
                break;

           case 'full':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         PLUGIN_HISTORY_SHOWFULL);
                $propbag->add('description',  PLUGIN_HISTORY_SHOWFULL_DESC);
                $propbag->add('default',     'false');
                break;

            case 'displaydate':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         PLUGIN_HISTORY_DISPLAYDATE);
                $propbag->add('description',  PLUGIN_HISTORY_DISPLAYDATE_DESC);
                $propbag->add('default',      'true');
                break;

            case 'displayauthor':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         PLUGIN_HISTORY_DISPLAYAUTHOR);
                $propbag->add('description',  '');
                $propbag->add('default',      'false');
                break;

            default:
                return false;
        }
        return true;
    }

    function getHistoryEntries($maxts, $mints, $max_age, $min_age, $full, $max_entries, $maxlength, $intro, $outro, $displaydate, $dateformat, $displayauthor)
    {
        global $serendipity;

        $oldLim = $serendipity['fetchLimit'];

        if (!is_array($max_age) && $min_age !== null) {
            // this is a fetch for the default range of TODAY; ie. you want to fetch one full year of entries, remove this "-($min_age*86400)" from second array item part or better install the plugin another time and set new min_/max_age days
            $e = serendipity_fetchEntries(array(($mints-($max_age*86400)),
                                                ($maxts-($min_age*86400))), $full, $max_entries);
        } else {
            // this is looped years
            foreach ($max_age AS $xt) {
                $range[] = [($mints-($xt*86400)),($maxts-($xt*86400))];
            }
            $hyr = array(0 => 'hyears', 1 => $range);

            $and = " WHERE (";
            foreach ($hyr[1] AS $trex) {
                $startts = serendipity_serverOffsetHour((int)$trex[0], true);
                $endts   = serendipity_serverOffsetHour((int)$trex[1], true);
                $and    .= " OR ( e.timestamp >= $startts AND e.timestamp <= $endts )";
            }
            $and = str_replace('WHERE ( OR', '', $and); // WHERE and () is done in function _fetchEntries for consistency and clarification

            $e = serendipity_fetchEntries(array(0 => 'hyears', 1 => $and), $full, $max_entries);
        }

        $serendipity['fetchLimit'] = $oldLim;

        if (!is_array($e)) {
            return;
        }

        if (($e_c = count($e)) == 0) {
            return;
        }

        echo empty($intro) ? '' : '<div class="serendipity_history_intro">' . $intro . "</div>\n";

        for($x=0; $x < $e_c; $x++) {
            $url = serendipity_archiveURL($e[$x]['id'],
                                          $e[$x]['title'],
                                          'serendipityHTTPPath',
                                          true,
                                          array('timestamp' => $e[$x]['timestamp'])
            );

            $date   = !$displaydate ? '' : serendipity_strftime($dateformat, $e[$x]['timestamp']);
            $author = $displayauthor ? $e[$x]['author'] . ': ' : '';

            echo '<div class="serendipity_history_info">'."\n";

            if ($displayauthor) {
                echo '    <span class="serendipity_history_author">' . $author . "</span> ";
            }
            if ($displaydate) {
                echo '    <span class="serendipity_history_date">' . $date . "</span> ";
            }
            $t = ($maxlength==0 || (strlen($e[$x]['title']) <= $maxlength))
                    ? $e[$x]['title']
                    : trim(serendipity_mb('substr', $e[$x]['title'], 0, $maxlength-3)).' [...]';
            echo '    <a href="' . $url . '" title="' . str_replace("'", "`", serendipity_specialchars($e[$x]['title'])) . '">' . serendipity_specialchars($t) . "</a>\n";
            echo "</div>\n";
            if ($full) {
                echo '<div class="serendipity_history_body">' . strip_tags($e[$x]['body']) . "</div>\n";
            }
        }

        echo empty($outro) ? '' : '<div class="serendipity_history_outro">' . $outro . "</div>\n";
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $cachefile   = $serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/history_daylist.dat';
        $title       = $this->get_config('title', $this->title);
        $intro       = $this->get_config('intro');
        $outro       = $this->get_config('outro');
        $maxlength   = $this->get_config('maxlength');
        $max_entries = $this->get_config('max_entries');
        $min_age     = $this->get_config('min_age');
        $max_age     = $this->get_config('max_age');
        $specialage  = $this->get_config('specialage');
        $xyears      = $this->get_config('multiyears', '1');
        $displaydate = serendipity_db_bool($this->get_config('displaydate', 'true'));
        $dateformat  = $this->get_config('dateformat');
        $full        = serendipity_db_bool($this->get_config('full', 'false'));
        $displayauthor = serendipity_db_bool($this->get_config('displayauthor', 'false'));

        $nowts = serendipity_serverOffsetHour();
        $maxts = mktime(23, 59, 59,  date('m', $nowts), date('d', $nowts), date('Y', $nowts)); // this is todays timestamp at last minute of day
        $mints = mktime(0, 0, 0, date('m', $nowts), date('d', $nowts), date('Y', $nowts)); // this is todays timestamp at start of day

        if (!is_numeric($min_age) || $min_age < 0 || $specialage == 'year') {
            $min_age = 365 + date('L', serendipity_serverOffsetHour());
        }

        if (!is_numeric($max_age) || $max_age < 1 || $specialage == 'year') {
            $max_age = 365 + date('L', serendipity_serverOffsetHour());
        }

        if (!is_numeric($max_entries) || $max_entries < 1) {
            $max_entries = 5;
        }

        if (!is_numeric($maxlength) || $maxlength < 0) {
            $maxlength = 30;
        }

        if (strlen($dateformat) < 1) {
            $dateformat = '%a, %d.%m.%Y %H:%M';
        }

        if ((int)$xyears > 1 && $specialage == 'year') {
            // get, read and echo possible cache file
            if (file_exists($cachefile) && ($maxts > $nowts)) {

                $history = unserialize(file_get_contents($cachefile));
                echo '<!-- cached f -->';
                echo $history;

            } else {

                $multiage = array();
                ob_start();
                if (!empty($intro)) {
                    echo '<div class="serendipity_history_intro">' . $intro . "</div>\n";
                }
                // y start by 0 adds current day, else start is last year
                for($y=0; $y < $xyears; $y++) {
                    $age = ($min_age > 365) ? (365 * $y) : $min_age;
                    $n   = ($y/4);
                    // for start with 0
                    if (preg_match('/^[0-9]+$/', $n)) {
                    // for start with 1
                    #if (ctype_digit("$n")) {
                        $age = $age + $n;
                    } else {
                        $age = $age + floor($n); // round fractions down
                    }
                    $multiage[] = $age;
                }
                $this->getHistoryEntries($maxts, $mints, $multiage, null, $full, $max_entries, $maxlength, null, null, $displaydate, $dateformat, $displayauthor);
                if (!empty($outro)) {
                    echo '<div class="serendipity_history_outro">' . $outro . "</div>\n";
                }
                $history_daylist = ob_get_contents();
                ob_end_clean();

                @unlink($cachefile);
                if (!empty($history_daylist)) {
                    if ($serendipity['view'] != 'categories') {
                        // write to cache
                        $fp = fopen($cachefile, 'w');
                        fwrite($fp, serialize($history_daylist));
                        fclose($fp);
                    }

                    // echo on run
                    echo "<!-- cached s ${serendipity['view']} -->";
                    echo $history_daylist;
                }
            }
        } else {
            $this->getHistoryEntries($maxts, $mints, $max_age, $min_age, $full, $max_entries, $maxlength, $intro, $outro, $displaydate, $dateformat, $displayauthor);
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>