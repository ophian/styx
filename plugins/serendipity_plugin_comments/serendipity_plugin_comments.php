<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_plugin_comments extends serendipity_plugin
{
    var $title = COMMENTS;

    function introspect(&$propbag)
    {
        global $serendipity;

        $this->title = $this->get_config('title', $this->title);

        $propbag->add('name',          COMMENTS);
        $propbag->add('description',   PLUGIN_COMMENTS_BLAHBLAH);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Garvin Hicking, Tadashi Jokagi, Judebert, G. Brockhaus, Ian Styx');
        $propbag->add('version',       '1.19');
        $propbag->add('requirements',  array(
            'serendipity' => '1.6',
            'smarty'      => '2.6.7',
            'php'         => '5.3.4'
        ));
        $propbag->add('groups', array('FRONTEND_VIEWS'));
        $propbag->add('configuration', array(
                                             'title',
                                             'cssbreak',
                                             'wordwrap',
                                             'max_chars',
                                             'max_entries',
                                             'dateformat',
                                             'viewmode',
                                             'showurls',
                                             'authorid'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {

            case 'authorid':
                $authors = array('all' => ALL_AUTHORS, 'login' => CURRENT_AUTHOR);
                /*
                $row_authors = serendipity_db_query("SELECT realname, authorid FROM {$serendipity['dbPrefix']}authors");
                if (is_array($row_authors)) {
                    foreach($row_authors as $row) {
                        $authors[$row['authorid']] = $row['realname'];
                    }
                }
                */

                $propbag->add('type',         'select');
                $propbag->add('name',         CATEGORIES_TO_FETCH);
                $propbag->add('description',  CATEGORIES_TO_FETCH_DESC);
                $propbag->add('select_values', $authors);
                $propbag->add('default',     'all');
                break;

            case 'showurls':
                $urltypes = array(
                    'none'       => NONE,
                    'comments'   => COMMENTS,
                    'trackbacks' => TRACKBACKS,
                    'all'        => COMMENTS . ' + ' . TRACKBACKS
                );
                $propbag->add('type',        'select');
                $propbag->add('name',        PLUGIN_COMMENTS_ADDURL);
                $propbag->add('description', '');
                $propbag->add('select_values', $urltypes);
                $propbag->add('default',     'trackbacks');
                break;

            case 'viewmode':
                $types = array(
                    'comments'   => COMMENTS,
                    'trackbacks' => TRACKBACKS,
                    'all'        => COMMENTS . ' + ' . TRACKBACKS
                );
                $propbag->add('type',        'select');
                $propbag->add('name',        TYPE);
                $propbag->add('description', '');
                $propbag->add('select_values', $types);
                $propbag->add('default',     'all');
                break;

            case 'title':
                $propbag->add('type',        'string');
                $propbag->add('name',        TITLE);
                $propbag->add('description', '');
                $propbag->add('default',     COMMENTS);
                break;

            case 'cssbreak':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_COMMENTS_CSSONLY);
                $propbag->add('description', 'Example style: ".plugin_comment_body { word-wrap: break-word; }"');
                $propbag->add('default',     'true');
                break;

            case 'wordwrap':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_COMMENTS_WORDWRAP);
                $propbag->add('description', PLUGIN_COMMENTS_WORDWRAP_BLAHBLAH);
                $propbag->add('default', 30);
                break;

            case 'max_chars':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_COMMENTS_MAXCHARS);
                $propbag->add('description', PLUGIN_COMMENTS_MAXCHARS_BLAHBLAH);
                $propbag->add('default', 120);
                break;

            case 'max_entries':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_COMMENTS_MAXENTRIES);
                $propbag->add('description', PLUGIN_COMMENTS_MAXENTRIES_BLAHBLAH);
                $propbag->add('default', 15);
                break;

            case 'dateformat':
                $propbag->add('type', 'string');
                $propbag->add('name', GENERAL_PLUGIN_DATEFORMAT);
                $propbag->add('description', sprintf(GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH, '%a, %d.%m.%Y %H:%M'));
                $propbag->add('default', '%a, %d.%m.%Y %H:%M');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;
        $title       = $this->get_config('title', $this->title);
        $max_entries = $this->get_config('max_entries');
        $max_chars   = $this->get_config('max_chars');
        $cssbreak    = serendipity_db_bool($this->get_config('cssbreak', 'true'));
        $wordwrap    = $this->get_config('wordwrap');
        $dateformat  = $this->get_config('dateformat');
        $showurls    = $this->get_config('showurls','trackbacks');

        if (!$max_entries || !is_numeric($max_entries) || $max_entries < 1) {
            $max_entries = 15;
        }

        if ($max_chars || !is_numeric($max_chars) || $max_chars < 1) {
            $max_chars = 120;
        }

        if (!$cssbreak && (!$wordwrap || !is_numeric($wordwrap) || $wordwrap < 1)) {
            $wordwrap = 30;
        }

        if (!$dateformat || strlen($dateformat) < 1) {
            $dateformat = '%a, %d.%m.%Y %H:%M';
        }

        $viewtype = '';
        if ($this->get_config('viewmode') == 'comments') {
            $viewtype .= ' AND co.type = \'NORMAL\'';
        } elseif ($this->get_config('viewmode') == 'trackbacks') {
            $viewtype .= ' AND (co.type = \'TRACKBACK\' OR co.type = \'PINGBACK\')';
        }

        $cond = array();
        $cond['and'] = ' AND e.isdraft = \'false\' ';
        if ($this->get_config('authorid') == 'login') {
            serendipity_ACL_SQL($cond, true);
            serendipity_plugin_api::hook_event('frontend_fetchentries', $cond, array('source' => 'entries'));
        }
        if (!isset($cond['joins'])) $cond['joins'] = '';

        $q = "SELECT    co.body              AS comment,
                        co.timestamp         AS stamp,
                        co.author            AS user,
                        e.title              AS subject,
                        e.timestamp          AS entrystamp,
                        e.id                 AS entry_id,
                        co.id                AS comment_id,
                        co.type              AS comment_type,
                        co.url               AS comment_url,
                        co.title             AS comment_title,
                        co.email             AS comment_email
                FROM    {$serendipity['dbPrefix']}comments AS co,
                        {$serendipity['dbPrefix']}entries  AS e
                        {$cond['joins']}
               WHERE    e.id = co.entry_id
                 AND    NOT (co.type = 'TRACKBACK' AND co.author = '" . serendipity_db_escape_string($serendipity['blogTitle']) . "' AND co.title != '')
                 AND    co.status = 'approved'
                        $viewtype
                        {$cond['and']}
            ORDER BY    co.timestamp DESC
               LIMIT    $max_entries";
        $sql = serendipity_db_query($q);
        // echo $q;

        if ($sql && is_array($sql)) {
            // search for trackbacks with duplicate values for 'comment' body and 'comment_title'; BBC/SVG mark them as clone
            foreach ($sql AS $current_key => &$current_array) {
                foreach ($sql AS $search_key => $search_array) {
                    if ($search_array['comment_type'] == 'TRACKBACK' && $search_array['comment_title'] == $current_array['comment_title'] && $search_array['comment'] == $current_array['comment']) {
                        if ($search_key != $current_key) {
                            $current_array['clone']   = '[clone]';
                            $current_array['cloneof'] = $search_array['subject'];
                            $current_array['comment'] = ''; // reset
                        }
                    }
                }
            }
            foreach ($sql AS $key => $row) {
                // Strip any HTML tags from comment. But we want a space where previously was a tag following a tagged newline like for "<p>xxx</p>\n<p>xxx</p>".
                $comment = str_replace(array("\r\n","\n\r","\n","\r",'  '), ' ', trim(strip_tags(str_replace('<', ' <', $row['comment']))));
                if (function_exists('mb_strimwidth')) {
                    $comment = mb_strimwidth($comment, 0, $max_chars, " [&hellip;]", LANG_CHARSET);
                } else {
                    $comments = wordwrap($comment, $max_chars, '@@@', 1);
                    $aComment = explode('@@@', $comments);
                    $comment  = $aComment[0];
                    if (count($aComment) > 1) {
                        $comment .= ' [&hellip;]';
                    }
                }
                $isTrackBack = ($row['comment_type'] == 'TRACKBACK' || $row['comment_type'] == 'PINGBACK');

                if ($row['comment_url'] != '' && ( ($isTrackBack && ($showurls == 'trackbacks' || $showurls == 'all') || !$isTrackBack && ($showurls == 'comments' || $showurls == 'all')) ) ) {

                    /* Fix invalid cases in protocol part */
                    $row['comment_url'] = preg_replace('@^http://@i','http://', $row['comment_url']);
                    $row['comment_url'] = preg_replace('@^https://@i','https://', $row['comment_url']);

                    if (substr($row['comment_url'], 0, 7) != 'http://' &&
                        substr($row['comment_url'], 0, 8) != 'https://') {
                        $row['comment_url'] = 'http://' . $row['comment_url'];
                    }
                    $user = '<a class="highlight" href="' . serendipity_specialchars(strip_tags($row['comment_url'])) . '" title="' . serendipity_specialchars(strip_tags($row['comment_title'])) . '">' . serendipity_specialchars(strip_tags($row['user'])) . '</a>';
                } else {
                    $user = serendipity_specialchars(strip_tags($row['user']));
                }

                $user = trim($user);
                if (empty($user)) {
                    $user = PLUGIN_COMMENTS_ANONYMOUS;
                }

                if (!$cssbreak) {
                    if (function_exists('mb_strimwidth')) {
                        $pos = 0;
                        $parts = array();
                        $enc = LANG_CHARSET;
                        $comment_len = mb_strlen($comment, $enc);
                        while ($pos < $comment_len) {
                            $part = mb_strimwidth($comment, $pos, $wordwrap, '', $enc);
                            $pos += mb_strlen($part, $enc);
                            $parts[] = $part;
                        }
                        $comment = implode("\n", $parts);
                    } else {
                        $comment = wordwrap($comment, $wordwrap, "\n", 1);
                    }
                }

                if (!isset($row['clone'])) $row['clone'] = '';
                if (!isset($row['cloneof'])) $row['cloneof'] = '';

                $entry = array('comment' => $comment,
                               'email'   => $row['comment_email'],
                               'url'     => $row['comment_url'],
                               'author'  => $row['user'],
                               'id'      => $row['comment_id']);

                // Let's help the BBCOde plugin a bit:
                if (class_exists('serendipity_event_bbcode')) {
                    $entry['comment'] = preg_replace('@((\[.*)[\n\r]+(.*\]))+@imsU', '\2\3', $entry['comment']);
                    $entry['comment'] = preg_replace('@((\[.+\].*)[\r\n]+(.*\[/.+\]))+@imsU', '\2\3', $entry['comment']);
                }
                $addData = array('from' => 'serendipity_plugin_comments:generate_content');
                serendipity_plugin_api::hook_event('frontend_display', $entry, $addData);

                printf('<div class="plugin_comment_wrap">' . "\n" . PLUGIN_COMMENTS_ABOUT . "</div>\n\n",
                    '<div class="plugin_comment_subject"><span class="plugin_comment_author">' . $user . '</span>',
                    ' <a class="highlight" href="' . serendipity_archiveURL($row['entry_id'], $row['subject'], 'baseURL', true, array('timestamp' => $row['entrystamp'])) .'#c' . $row['comment_id'] . '" title="' . serendipity_specialchars($row['subject']) . '">'
                      . serendipity_specialchars($row['subject'])
                      . "</a></div>\n"
                      . '<div class="plugin_comment_date">' . serendipity_specialchars(serendipity_strftime($dateformat, $row['stamp'])) . str_replace('[clone]', ' <span class="trackback_clone" title="Duplicate trackback summary of [@'.serendipity_specialchars($row['cloneof']).']"><svg aria-hidden="true" focusable="false" data-icon="clone" role="img" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 512 512" class="svg-inline fa-clone"><path fill="currentColor" d="M464 0H144c-26.51 0-48 21.49-48 48v48H48c-26.51 0-48 21.49-48 48v320c0 26.51 21.49 48 48 48h320c26.51 0 48-21.49 48-48v-48h48c26.51 0 48-21.49 48-48V48c0-26.51-21.49-48-48-48zm-80 464c0 8.82-7.18 16-16 16H48c-8.82 0-16-7.18-16-16V144c0-8.82 7.18-16 16-16h48v240c0 26.51 21.49 48 48 48h240v48zm96-96c0 8.82-7.18 16-16 16H144c-8.82 0-16-7.18-16-16V48c0-8.82 7.18-16 16-16h320c8.82 0 16 7.18 16 16v320z" class=""></path></svg></span>', $row['clone']) . "</div>\n"
                      . '<div class="plugin_comment_body">' . strip_tags($entry['comment'], '<img>') . '</div>' . "\n"
                );
            }
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>