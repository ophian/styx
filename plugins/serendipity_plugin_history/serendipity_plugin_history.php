<?php

declare(strict_types=1);

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
        $propbag->add('version',       '1.51');
        $propbag->add('requirements',  array(
            'serendipity' => '5.0',
            'smarty'      => '4.1',
            'php'         => '8.2'
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
                                             'isempty',
                                             'full',
                                             'entrycut',
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

            case 'isempty':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MULTIYEARS_EMPTY);
                $propbag->add('description', '');
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

            case 'entrycut':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_HISTORY_MAXFULLLENGTH);
                $propbag->add('description', PLUGIN_HISTORY_MAXFULLLENGTH_DESC);
                $propbag->add('default', 106);
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

    function getHistoryEntries($maxts, $mints, $max_age, $nowts, $min_age = null, $intro = null, $outro = null)
    {
        global $serendipity;

        $max_entries = $this->get_config('max_entries');
        if (!is_numeric($max_entries) || $max_entries < 1) {
            $max_entries = 5;
        }
        $full   = serendipity_db_bool($this->get_config('full', 'false'));
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
                $and    .= " OR ( e.timestamp >= $startts AND e.timestamp <= $endts )\n                           ";
            }
            $and = str_replace('WHERE ( OR', '', $and); // WHERE and () is done in function _fetchEntries for consistency and clarification

            $e = serendipity_fetchEntries(array(0 => 'hyears', 1 => $and), $full, $max_entries);
        }

        $serendipity['fetchLimit'] = $oldLim;

        if (!is_array($e)) {
            return false;
        }

        $ect = count($e);
        if ($ect == 0) {
            return false;
        }

        $maxlength   = $this->get_config('maxlength');
        $displaydate = serendipity_db_bool($this->get_config('displaydate', 'true'));
        $dateformat  = $this->get_config('dateformat');
        $entrycut    = $this->get_config('entrycut', 106);
        $displayauthor = serendipity_db_bool($this->get_config('displayauthor', 'false'));
        if (!is_numeric($maxlength) || $maxlength < 0) {
            $maxlength = 30;
        }
        if (strlen($dateformat) < 1) {
            $dateformat = '%a, %d.%m.%Y %H:%M';
        }

        $elday = date('md', $nowts) == '0229'; // (bool) is the current day the explicit leap day ?
        $fmrch = date('md', $nowts) == '0301'; // (bool) is the current day the explicit 1st of March day ?

        echo empty($intro) ? '' : '<div class="serendipity_history_intro">' . $intro . "</div>\n";
        if (!$full) {
            echo '<ul class="plainList">'."\n";
        }

        for($x=0; $x < $ect; $x++) {
            // in leap years on leap day exclude both possible sibling days, in normal years exclude explicit leap day entries (by the counter days)
            if (($elday && (date('md', $e[$x]['timestamp']) == '0228' || date('md', $e[$x]['timestamp']) == '0301'))
             || ($fmrch && date('md', $e[$x]['timestamp']) == '0229')) continue; // do not show

            $url = serendipity_archiveURL($e[$x]['id'],
                                          $e[$x]['title'],
                                          'serendipityHTTPPath',
                                          true,
                                          array('timestamp' => $e[$x]['timestamp'])
            );
            $e[$x]['title'] = !empty($e[$x]['title']) ? $e[$x]['title'] : 'unknown'; // fixes empty titles for the link

            $date   = !$displaydate ? '' : serendipity_strftime($dateformat, (int) $e[$x]['timestamp']);
            $author = $displayauthor ? $e[$x]['author'] . ': ' : '';

            if ($full) {
                echo '<div class="serendipity_history_info">'."\n";
            }
            if ($displayauthor) {
                echo '    <span class="serendipity_history_author">' . $author . "</span>\n";
            }
            if (!$full) {
                echo "  <li>\n";
            }
            if ($displaydate) {
                echo '    <span class="serendipity_history_date">' . $date . "</span>\n";
            }
            $t = ($maxlength == 0 || (strlen($e[$x]['title']) <= $maxlength))
                    ? $e[$x]['title']
                    : trim(serendipity_mb('substr', $e[$x]['title'], 0, $maxlength-3)).' [...]';
            echo '    <a href="' . $url . '" title="' . str_replace("'", "`", htmlspecialchars($e[$x]['title'])) . '">' . htmlspecialchars($t) . "</a>\n";
            if ($full) {
                echo "</div>\n";
                $body = preg_replace("{2,}", ' ', str_replace(["\r\n", "\r", "\n", "\t"], [' '], strip_tags($e[$x]['body'])));
                echo '<div class="serendipity_history_body">' . ($entrycut > 0 ? mb_substr($body, 0, $entrycut, LANG_CHARSET)."&hellip;" : $body) . "</div>\n";
            } else {
                echo "  </li>\n";
            }
        }
        if (!$full) {
            echo "</ul>\n";
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
        $min_age     = $this->get_config('min_age');
        $max_age     = $this->get_config('max_age');
        $specialage  = $this->get_config('specialage');
        $xyears      = $this->get_config('multiyears', '1');
        $xyempty     = $this->get_config('isempty');
        $empty_ct    = $this->get_config('empty_ct', 0);

        $nowts = serendipity_serverOffsetHour();
        $maxts = mktime(23, 59, 59,  (int) date('m', $nowts), (int) date('d', $nowts), (int) date('Y', $nowts)); // this is todays timestamp at last minute of day
        $mints = mktime(0, 0, 0, (int) date('m', $nowts), (int) date('d', $nowts), (int) date('Y', $nowts)); // this is todays timestamp at start of day

        if (!is_numeric($min_age) || $min_age < 0 || $specialage == 'year') {
            $min_age = 365 + date('L', serendipity_serverOffsetHour());
        }
        if (!is_numeric($max_age) || $max_age < 1 || $specialage == 'year') {
            $max_age = 365 + date('L', serendipity_serverOffsetHour());
        }

        if ((int)$xyears > 1 && $specialage == 'year' && (empty($serendipity['calendar']) || $serendipity['calendar'] == 'gregorian')) {
            $timeout = ($maxts - $nowts); // the rest of the day
            $cached  = (($timeout >= 0) && ($maxts > $nowts));
            $date    = file_exists($cachefile) ? date('d-m-Y', $nowts) == date('d-m-Y', serendipity_serverOffsetHour(filemtime($cachefile))) : false; // filemtime is Servers timezone or UTC/GMT

            // get, read and echo possible cache file
            if ($date && $cached) {

                $history = unserialize(file_get_contents($cachefile));
                echo "<!-- cached f $timeout $cached -->";
                echo $history;

            } else {

                // avoid possible haunt failings
                #if ($serendipity['view'] == 'plugin') {
                #    return false;
                #}

                $sc = (date('L', $nowts) == '1') ? date('md', $nowts) > '0228' : false; // if to add the leap day to counter
                if (false === $sc) {
                    $xyears += 1; // adds one additional loop year into the array - because we are counting year days backward, starting by key 1
                }
                $cy = date('Y', $nowts);
                $sy = ($cy-$xyears);

                $age = 0;
                $leap = []; // incrementing array to use as leap true check
                $multiage = [];

                // create leap years array sibling for xyears looped
                for($i = $cy; $i > $sy; $i--) {
                    #$leap[] = !in_array($i, [1900, 2100]) ? date('L', strtotime("$i-01-01")) : false; // ;-)
                    $leap[] = date('L', strtotime("$i-01-01"));
                }
                // Loops xyears backward days by leap year (cases)
                $skey = $sc ? 0 : 1; // Set the startkey depending on being [the leap day and + in LY] OR not
                // The interwoven conditional leap year array checkup makes it necessary that,
                // if the current year is a leap year and the (current year) counted day is greater February 28th,
                // the start key must start with 0, otherwise 1, so that the leap year days age counter matches the back-looped year.
                for($y=$skey; $y < $xyears; $y++) {
                    $days       = (isset($leap[$y]) && $leap[$y] == 1) ? 366 : 365; // days is the counter for building the back years
                    $age       += $days; // increment, since we need all years added days for the decrementing backward day age counter
                    $multiage[] = $age; // When finalized we can sort out entries on special day February 29th and so. See getHistoryEntries().
                }

                ob_start();
                    $fallback = false;
                    if (!empty($intro)) {
                        echo '<div class="serendipity_history_intro">' . $intro . "</div>\n";
                    }
                    if (false === $this->getHistoryEntries($maxts, $mints, $multiage, $nowts)) {
                        $fallback = true;
                    }
                    if (!empty($outro)) {
                        echo '<div class="serendipity_history_outro">' . $outro . "</div>\n";
                    }
                    if ($fallback === false) {
                        $history_daylist = ob_get_contents();
                    }
                ob_end_clean();

                if (!empty($history_daylist)) {
                    // write to cache
                    $fp = fopen($cachefile, 'w');
                    fwrite($fp, serialize($history_daylist));
                    fclose($fp);
                    @touch($cachefile); // 'w' mode will NOT update the modification time (filemtime)
                    $this->set_config('empty_ct', 0); // reset the counter
                    // echo on run
                    echo $history_daylist;
                } else {
                    $xytxt = '<div class="serendipity_history_outro history_empty">' . $xyempty . "</div>\n";
                    if ($empty_ct < 8) {
                        $this->set_config('empty_ct', $empty_ct+1);
                        echo '<!-- ' . $empty_ct . date(' H:i:s', $nowts) . ' -->' . $xytxt;
                    } else {
                        $this->set_config('empty_ct', 0);
                        // write to cache
                        $fp = fopen($cachefile, 'w');
                        fwrite($fp, serialize($xytxt));
                        fclose($fp);
                        @touch($cachefile); // 'w' mode will NOT update the modification time (filemtime)
                        echo '<!-- 1st empty cached fallback -->' . $xytxt;
                    }
                }
            }
        } else {
            if (empty($this->getHistoryEntries($maxts, $mints, $max_age, $nowts, $min_age, $intro, $outro))) {
                if (!empty($xyempty)) {
                    echo '<div class="serendipity_history_outro history_empty">' . $xyempty . "</div>\n";
                }
            }
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>