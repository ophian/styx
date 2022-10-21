<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *  SMF  Importer,     by Garvin Hicking *
 * ****************************************************************/

class Serendipity_Import_smf extends Serendipity_Import
{
    var $info        = array('software' => 'SMF 2.1.x');
    var $data        = array();
    var $inputFields = array();
    var $categories  = array();

    function getImportNotes()
    {
        return 'This (Simple Machines Forum) Importer was originally developed with some early SMF ~v.1 and Serendipity version, in 2008. As one can imagine, things have changed over time. This new lookup requires at least SMF v.2.1.0 up to current v.2.1.2 now and a running Styx instance up from latest v.3 Series. If you wish to give it a try, backup both database implementations and better do this in a testing environment first to see if you catch some breaking flaws. This new lookup has just been ported, NOT been tested! It does not capture and import an exact copy, just some main things like from authors, entries, comments and categories, even tags if you have, but NO other, more detailed configurations. This and the relations finetuning is "handmade" User stuff - left up to YOU - later on! Now go and ride this horse. File an GitHub <a href="https://github.com/ophian/styx/issues" target="_blank">issue</a> or start a <a href="https://github.com/ophian/styx/discussions" target="_blank">discussion</a> for help!';
    }

    function __construct($data)
    {
        $this->data = $data;
        $this->inputFields = array(array('text' => INSTALL_DBHOST,
                                         'type' => 'input',
                                         'name' => 'host'),

                                   array('text' => INSTALL_DBUSER,
                                         'type' => 'input',
                                         'name' => 'user'),

                                   array('text' => INSTALL_DBPASS,
                                         'type' => 'protected',
                                         'name' => 'pass'),

                                   array('text' => INSTALL_DBNAME,
                                         'type' => 'input',
                                         'name' => 'name'),

                                   array('text' => INSTALL_DBPREFIX,
                                         'type' => 'input',
                                         'name' => 'prefix',
                                         'default' => 'smf_'),

                                   array('text'    => CHARSET,
                                         'type'    => 'list',
                                         'name'    => 'charset',
                                         'value'   => 'native',
                                         'default' => $this->getCharsets(false)),

                                   array('text'    => CONVERT_HTMLENTITIES,
                                         'type'    => 'bool',
                                         'name'    => 'use_strtr',
                                         'default' => 'true'),

                                   array('text'    => ACTIVATE_AUTODISCOVERY,
                                         'type'    => 'bool',
                                         'name'    => 'autodiscovery',
                                         'default' => 'false')
                            );
    }

    function validateData()
    {
        return (is_array($this->data) ? sizeof($this->data) : false);
    }

    function getInputFields()
    {
        return $this->inputFields;
    }

    function import()
    {
        global $serendipity;

        // Save this so we can return it to its original value at the end of this method.
        $noautodiscovery = $serendipity['noautodiscovery'] ?? false;

        if ($this->data['autodiscovery'] == 'false') {
            $serendipity['noautodiscovery'] = 1;
        }

        $this->getTransTable();

        $this->data['prefix'] = serendipity_db_escape_string($this->data['prefix']);
        $users = array();
        $entries = array();
        $ul = array();
        $ulist = array();

        if (!extension_loaded('mysqli')) {
            return MYSQL_REQUIRED;
        }

        try {
            $smfdb = mysqli_connect($this->data['host'], $this->data['user'], $this->data['pass']);
        } catch (\Throwable $t) {
            $smfdb = false;
        }

        if (!$smfdb || mysqli_connect_error()) {
            return sprintf(COULDNT_CONNECT, serendipity_specialchars($this->data['host']));
        }

        if (!@mysqli_select_db($smfdb, $this->data['name'])) {
            return sprintf(COULDNT_SELECT_DB, mysqli_error($smfdb));
        }
        
        /* Users */
        foreach (serendipity_fetchUsers() AS $uname) $ul[] = $uname['username'];

        $res = @$this->nativeQuery("SELECT id_member  AS ID,
                                        member_name   AS user_login,
                                        real_name     AS user_realname,
                                        passwd        AS user_pass,
                                        email_address AS user_email,
                                        id_group      AS user_level
                                     FROM {$this->data['prefix']}members
                                    WHERE is_activated = 1", $smfdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, mysqli_error($smfdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $users[$x] = mysqli_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('right_publish' => 1,
                          'realname'      => $users[$x]['user_realname'] ?? $users[$x]['user_login'],
                          'username'      => in_array('smf_' . $users[$x]['user_login'], $ul) ? 'smf_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'smf_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'email'         => $users[$x]['user_email'] ?? '',
                          'userlevel'     => ($users[$x]['user_level'] == 1 ? USERLEVEL_ADMIN : USERLEVEL_EDITOR),
                          'password'      => serendipity_hash($npwd)); // Create a new Styx password and keep it in an array to inform imported users later per email (if available)

            if ($serendipity['serendipityUserlevel'] < $data['userlevel']) {
                $data['userlevel'] = $serendipity['serendipityUserlevel'];
            }
            $data['mail_comments'] = 0;
            $data['mail_trackbacks'] = 0;
            $data['hashtype'] = 2;

            $ulist[$x] = $udata = $this->strtrRecursive($data);
            serendipity_db_insert('authors', $udata);
            #echo mysqli_error();
            $users[$x]['authorid'] = serendipity_db_insert_id('authors', 'authorid');

            // Add to mentoring
            $ulist[$x] = array_merge($ulist[$x], [ 'authorid' => $users[$x]['authorid'], 'new_plain_password' => $npwd ]);

            echo IMPORTER_USER_IMPORT_SUCCESS_TITLE;
            echo sprintf(IMPORTER_USER_IMPORT_SUCCESS_MSG, 'smf');
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories */
        $res = @$this->nativeQuery("SELECT id_cat AS cat_ID,
                                             name AS cat_name
                               FROM {$this->data['prefix']}categories", $smfdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($smfdb));
        }

        // Get all the info we need
        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $parent_categories[] = mysqli_fetch_assoc($res);
        }

        for ($x=0, $max_x = sizeof($parent_categories); $x < $max_x; $x++) {
            $cat = array('category_name'        => $parent_categories[$x]['cat_name'],
                         'category_description' => '',
                         'parentid'             => 0,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $parent_categories[$x]['categoryid'] = serendipity_db_insert_id('category', 'categoryid');
        }

        /* Board Categories */
        $res = @$this->nativeQuery("SELECT id_board    AS cat_ID,
                                           id_cat      AS parent_cat_id,
                                           name        AS cat_name,
                                           description AS category_description
                               FROM {$this->data['prefix']}boards ORDER BY board_order;", $smfdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($smfdb));
        }

        // Get all the info we need
        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $categories[] = mysqli_fetch_assoc($res);
        }

        // Insert all categories as top level (we need to know everyone's ID before we can represent the hierarchy).
        for ($x=0, $max_x = sizeof($categories); $x < $max_x; $x++) {
            $pcatid = 0;
            foreach($parent_categories AS $pcat) {
                if ($pcat['cat_ID'] == $categories[$x]['parent_cat_id']) {
                    $pcatid = $pcat['cat_ID'];
                    break;
                }
            }

            $cat = array('category_name'        => $categories[$x]['cat_name'],
                         'category_description' => $categories[$x]['category_description'],
                         'parentid'             => $pcatid,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $categories[$x]['categoryid'] = serendipity_db_insert_id('category', 'categoryid');
        }

        serendipity_rebuildCategoryTree();

        /* Entries */
        $res = @$this->nativeQuery("SELECT
                                        tm.subject          AS post_subject,
                                        t.id_member_started AS topic_poster,
                                        t.id_board          AS forum_id,
                                        tm.poster_time      AS post_time,
                                        tm.body             AS post_text,
                                        t.id_topic          AS topic_id,
                                        t.id_first_msg      AS post_id,
                                        t.num_replies       AS ccount
                                FROM {$this->data['prefix']}topics AS t
                                JOIN {$this->data['prefix']}messages AS tm
                                  ON tm.id_msg = t.id_first_msg
                            GROUP BY t.id_topic", $smfdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, mysqli_error($smfdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $entries[$x] = mysqli_fetch_assoc($res);

            $entry = array('title'          => $this->decode($entries[$x]['post_subject']),
                           'isdraft'        => 'false',
                           'allow_comments' => 'true',
                           'timestamp'      => $entries[$x]['post_time'],
                           'body'           => $this->strtr($entries[$x]['post_text']),
                           'extended'       => ''
                           );

            $entry['authorid'] = '';
            $entry['author']   = '';
            foreach($users AS $user) {
                if ($user['ID'] == $entries[$x]['topic_poster']) {
                    $entry['authorid'] = $user['authorid'];
                    $entry['author']   = $user['user_login'];
                    break;
                }
            }

            if (!is_int($entries[$x]['entryid'] = serendipity_updertEntry($entry))) {
                return $entries[$x]['entryid'];
            }

            /* Entry/category */
            foreach($categories AS $category) {
                if ($category['cat_ID'] == $entries[$x]['forum_id'] ) {
                    $data = array('entryid'    => $entries[$x]['entryid'],
                                  'categoryid' => $category['categoryid']);
                    serendipity_db_insert('entrycat', $this->strtrRecursive($data));
                    break;
                }
            }
            
            $topic_id = $entries[$x]['topic_id'];

            // Store original ID, we might need it at some point.
            serendipity_db_insert('entryproperties', array('entryid' => $entries[$x]['entryid'], 'property' => 'foreign_import_id', 'value' => $entries[$x]['topic_id']));

            // Convert SMF tags - do they exist ? in Series 2 ? Is it a plugin ?
            $t_res = @$this->nativeQuery("SELECT t.tag
                                            FROM {$this->data['prefix']}tags_log AS tl
                                            JOIN {$this->data['prefix']}tags AS t
                                              ON tl.id_tag = t.id_tag
                                           WHERE tl.id_topic = {$topic_id}
                                             AND t.approved = 1", $smfdb);
            if (mysqli_num_rows($t_res) > 0) {
                while ($a = mysqli_fetch_assoc($t_res)) {
                    try { serendipity_db_insert('entrytags', array('entryid' => $entries[$x]['entryid'], 'tag' => $t_res['tag'])); } catch (\Throwable $t) {} // might not exist!
                }
            }

            /* Comments */
            $c_res = @$this->nativeQuery("SELECT
                                            tm.subject      AS post_subject,
                                            tm.body         AS post_text,
                                            tm.id_msg       AS post_id,
                                            tm.poster_time  AS post_time,
                                            tm.id_board     AS forum_id,
                                            tm.poster_name  AS poster_name,
                                            tm.poster_email AS poster_email

                                        FROM {$this->data['prefix']}topics AS t
                                        JOIN {$this->data['prefix']}messages AS tm
                                          ON tm.id_topic = t.id_topic
                                       WHERE t.id_topic = {$topic_id}
                                    ", $smfdb);

            if (!$c_res) {
                return sprintf(COULDNT_SELECT_COMMENT_INFO, mysqli_error($smfdb));
            }

            while ($a = mysqli_fetch_assoc($c_res)) {
                if ($a['post_id'] == $entries[$x]['post_id']) {
                    continue;
                }
                $author   = $a['poster_name'];
                $mail     = $a['poster_email'];
                $url      = '';

                foreach($users AS $user) {
                    if ($user['ID'] == $a['poster_id']) {
                        $author = $user['user_login'];
                        $mail   = $user['user_email'];
                        $url    = $user['user_url'];
                        break;
                    }
                }
                $a['post_text'] = serendipity_entity_decode($a['post_text']);

                $comment = array('entry_id ' => $entries[$x]['entryid'],
                                 'parent_id' => 0,
                                 'timestamp' => $a['post_time'],
                                 'author'    => $author,
                                 'email'     => $mail,
                                 'url'       => $url,
                                 'ip'        => '',
                                 'status'    => 'approved',
                                 'body'      => $a['post_text'],
                                 'subscribed'=> 'false',
                                 'type'      => 'NORMAL');

                serendipity_db_insert('comments', $this->strtrRecursive($comment));
                $cid = serendipity_db_insert_id('comments', 'id');
                serendipity_approveComment($cid, $entries[$x]['entryid'], true);
            }
        }

        $serendipity['noautodiscovery'] = $noautodiscovery;

        // That was fun.
        return true;
    }

}

return 'Serendipity_Import_smf';

/* vim: set sts=4 ts=4 expandtab : */
?>