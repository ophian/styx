<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_plugin_history extends serendipity_plugin
{
    public $title = PLUGIN_HISTORY_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $this->title = $this->get_config('title', $this->title);

        $propbag->add('name',          PLUGIN_HISTORY_NAME);
        $propbag->add('description',   PLUGIN_HISTORY_DESC);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Jannis Hermanns, Ian Styx');
        $propbag->add('version',       '2.0.0');
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

    /**
     * Check if multi-range timestamps happen to be in leap years and range before FEBRUARY 29th without an added leap day OR behind with 1 day plus
     *
     * Args:
     *      - The multiage array lopped timestamps startday - endday. (Could also use the SQL BETWEEN operator since that is inclusive: begin and end values are included.)
     *      - The current year - is a leap year $cy_isaly
     *      - The current year - is directly following a leap year $cy_pox - is lyear plus one AND its timestamp day is >= Feb 28 (normal years(3) first year and x days)
     * Returns:
     *      - return conditional prepared $timestamp
     */
    private function rangeLeapYearAddDay(int $timestamp, bool $cy_isaly, bool $cy_pox) : int
    {
        // check looped timestamp year for being a leap year
        if (date('L', $timestamp)) {
            $year = (int) date('Y', $timestamp);
            $leapDayTimestamp = mktime(0, 0, 0, 2, 29, $year); // check against start of leap day
            // case 1: lower added leap day
            if ($timestamp < $leapDayTimestamp) {
                return $timestamp; // do nothing "before the added leap day"; i.e. TNOW = 06-01-2024 12:12:00
            } else {
            // case 2: greater or equal added leap day
                if (date('md', $timestamp) > '0229') {
                    // current year is a normal year
                    if (!$cy_isaly) {
                        // and for exception under x days only condition is directly following a leap year
                        if ($cy_pox) {
                            return $timestamp; // "after the added leap day" in non-leap-start-years; i.e. TNOW = 02-03-2025 12:12:00
                        } else {
                            return $timestamp+86400; // "after the added leap day" in non-leap-start-years; i.e. TNOW = 02-03-2023 12:12:00
                        }
                    } else {
                        return $timestamp; // "after the added leap day" in leap-start-years; i.e. TNOW = 02-03-2024 12:12:00
                    }
                } else {
                    // current year is a leap year
                    if ($cy_isaly) {
                        return $timestamp; // i.e. TNOW = 01-02-2024 12:12:00
                    } else {
                        // 2cd and 3rd normal year cases
                        if (!$cy_pox) {
                            return $timestamp+86400; // i.e. TNOW = 01-02-2022 12:12:00 OR TNOW = 01-02-2023 12:12:00
                        } else {
                            // The ONE and ONLY exception; [ Current start year IS a leap year + 1 and its date is NOT below February 29]  AND the range timestamp IS the 28th of February;
                            return $timestamp-86400; // i.e. TNOW = 28-02-2025 12:12:00
                        }
                    }
                }
            }
        }
        // normal years in-between leap years - except $cy_pox case ly + 1 >= Feb 28th
        return $timestamp;
    }

    /**
     * Main function to prepare (multi-aged) timestamps to fetch historic entries off by year(s)
     *
     * Args:
     *      - Option setting: Max timestamp (regularly 365 days)
     *      - Option setting: Min timestamp (regularly 365 days)
     *      - Multi age prepared range in days
     *      - The current timestamp
     *      - Current year is a leap year and its day is > 0228 - NULL on not use
     *      - Current year is directly following a leap year and its day is >= 0228 - NULL on not use
     *      - Option setting: Min age (optional)
     *      - The output intro string (optional)
     *      - The output outro string (optional)
     * Returns:
     *      - Boolean state
     */
    function getHistoryEntries(int $maxts, int $mints, iterable $max_age, int $nowts, ?bool $cyisly, ?bool $cypox, ?int $min_age = null, ?string $intro = null, ?string $outro = null) : ?false
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
                $range[] = [$this->rangeLeapYearAddDay($mints-($xt*86400), $cyisly, $cypox), $this->rangeLeapYearAddDay($maxts-($xt*86400), $cyisly, $cypox)];
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

        $elday = date('md', $nowts) == '0229'; // (bool) Is the current day the explicit leap day ?

        echo empty($intro) ? '' : '<div class="serendipity_history_intro">' . $intro . "</div>\n";
        if (!$full) {
            echo '<ul class="plainList">'."\n";
        }

        for($x=0; $x < $ect; $x++) {
            // only exception to exclude wrongly fetched 28th days when on leap day Feb 29th
            if ($elday && (date('md', $e[$x]['timestamp']) == '0228')) continue; // do not show

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
                    : trim(mb_substr($e[$x]['title'], 0, $maxlength-3)).' [...]';
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

        return null;
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

        // $testts = mktime(00, 12, 00, 2, 27, 2025); // test timestamp cases. Do for normal year, leap year and leap year + 1 year cases with multi diff month/day cases. In special, multi-variants of Feb 28th, 29th in ly and 1st of march cases!
        // ----
        $nowts = serendipity_serverOffsetHour(); // test NOW TS $testts on certain year date calculations (see above)
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
            // set $date = false for non cache debug cases

            // get, read and echo possible cache file
            if ($date && $cached) {

                $history = unserialize(file_get_contents($cachefile));
                echo "<!-- cached f $timeout $cached -->";
                echo $history;

            } else {

                $sc = (date('L', $nowts) == '1') ? (date('md', $nowts) > '0228') : false; // if to add the leap day to counter

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

                // Internal arrow function (short closure) to check a special case when current year is following a leap year, but NOT before Feb 28th !! Scope is parent.
                $hasPreviousLeapYear = fn($nowts): bool => (date('L', strtotime((date('Y', $nowts) - 1)."-01-01")) == 1 && date('md', $nowts) >= '0228'); // Restriction important
                $cpx = $hasPreviousLeapYear($nowts); // Boolean = current (year) plus one + x days

                if ($skey == 1 && $cpx === true) $skey = 0; // special case reset - i.e. 16th of March 2025

                // The interwoven conditional leap year array checkup makes it necessary that,
                // if the current year is a leap year and the (current year) counted day is greater February 28th,
                // the start key must start with 0, otherwise 1, so that the leap year days age counter matches the back-looped year.
                for($y=$skey; $y < $xyears; $y++) {
                    $days       = (isset($leap[$y]) && $leap[$y] == '1') ? 366 : 365; // days is the counter for building the back years
                    $age       += $days; // Increment, since we need all years added days for the decrementing backward day age counter
                    $multiage[] = $age; // When finalized we can sort out entries on special day February 29th and so. See getHistoryEntries().
                }

                ob_start();
                    $fallback = false;
                    if (!empty($intro)) {
                        echo '<div class="serendipity_history_intro">' . $intro . "</div>\n";
                    }
                    if (false === $this->getHistoryEntries($maxts, $mints, $multiage, $nowts, $sc, $cpx)) {
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
            if (empty($this->getHistoryEntries($maxts, $mints, $max_age, $nowts, null, null, $min_age, $intro, $outro))) {
                if (!empty($xyempty)) {
                    echo '<div class="serendipity_history_outro history_empty">' . $xyempty . "</div>\n";
                }
            }
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>