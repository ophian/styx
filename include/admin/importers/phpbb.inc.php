<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *  phpbb  Importer,     by Garvin Hicking *
 *  Tried to up-port to phpBB 3.x .... Not easy! Be really careful!
 * ****************************************************************/

class Serendipity_Import_phpbb extends Serendipity_Import
{
    var $info        = array('software' => 'phpBB 3.x');
    var $data        = array();
    var $inputFields = array();
    var $categories  = array();

    function getImportNotes()
    {
        return 'This Importer was originally developed with phpBB in an early 2.0.x state and some early Serendipity version too, loong ago. As one can imagine, things have changed over time. This new lookup requires at least phpBB 3..x up to current v3.3.8 (<em>Dunno!</em>) now and a running Styx instance up from latest v.3 Series. If you wish to give it a try, backup both database implementations and better do this in a testing environment first to see if you catch some breaking flaws. This new lookup has just been ported (<em>with shaky results</em>), and NOT been tested! It does not capture and import an exact copy, just some main things like from users, posts/topics and forums/categories, but NOT any other stored configurations, files, etc). This and the relations finetuning is "handmade" User stuff - left up to YOU - later on! Now go and ride this horse. File an GitHub <a href="https://github.com/ophian/styx/issues" target="_blank">issue</a> or start a <a href="https://github.com/ophian/styx/discussions" target="_blank">discussion</a> for help!';
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
                                         'default' => 'phpbb_'),

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
            $phbbdb = mysqli_connect($this->data['host'], $this->data['user'], $this->data['pass']);
        } catch (\Throwable $t) {
            $phbbdb = false;
        }

        if (!$phbbdb || mysqli_connect_error()) {
            return sprintf(COULDNT_CONNECT, serendipity_specialchars($this->data['host']));
        }

        if (!@mysqli_select_db($phbbdb, $this->data['name'])) {
            return sprintf(COULDNT_SELECT_DB, mysqli_error($phbbdb));
        }

        /* Users */
        foreach (serendipity_fetchUsers() AS $uname) $ul[] = $uname['username'];

        $res = @$this->nativeQuery("SELECT
                                     user_id       AS ID,
                                     group_id      AS user_level,
                                     username      AS user_login,
                                     user_email    AS user_email,
                                     user_website  AS user_url
                               FROM {$this->data['prefix']}users
                              WHERE user_type = 1", $phbbdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, mysqli_error($phbbdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $users[$x] = mysqli_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('right_publish' => 1,
                          'realname'      => $users[$x]['user_login'],
                          'username'      => in_array('pbb_' . $users[$x]['user_login'], $ul) ? 'pbb_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'pbb_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'username'      => $users[$x]['user_login'],
                          'email'         => $users[$x]['user_email'] ?? '',
                          'userlevel'     => ($users[$x]['user_level'] == 0 ? USERLEVEL_EDITOR : USERLEVEL_ADMIN),
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
            echo sprintf(IMPORTER_USER_IMPORT_SUCCESS_MSG, 'pbb');
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories OLD OLD OLD - Remove this after further tests! Please! */
        $res = @$this->nativeQuery("SELECT cat_id AS cat_ID,
                                    cat_title AS cat_name
                               FROM {$this->data['prefix']}categories", $phbbdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($phbbdb));
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

        /* Categories */
        $res = @$this->nativeQuery("SELECT
                                    forum_id   AS cat_ID,
                                    parent_id  AS parent_cat_id,
                                    forum_name AS cat_name,
                                    forum_desc AS category_description
                               FROM {$this->data['prefix']}forums ORDER BY forum_last_post_id;", $phbbdb); // was ORDER BY forum_order in the old days. Is that the right up-port replacement?
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($phbbdb));
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
        $res = @$this->nativeQuery("SELECT t.topic_title,
                                    t.topic_poster,
                                    t.forum_id,
                                    p.post_time,
                                    pt.post_subject,
                                    pt.post_text,
                                    count(p.topic_id) AS ccount,
                                    p.topic_id,
                                    MIN(p.post_id) AS post_id
                               FROM {$this->data['prefix']}topics AS t
                    LEFT OUTER JOIN {$this->data['prefix']}posts  AS p
                                 ON t.topic_id = p.topic_id
                    LEFT OUTER JOIN {$this->data['prefix']}posts  AS pt
                                 ON pt.post_id = p.post_id
                           GROUP BY p.topic_id
                           ", $phbbdb); // pt. was posts_text table which now does not exists any more. So is this right with a doubled outer join on self?
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, mysqli_error($phbbdb));
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

            /* Comments */
            $topic_id = $entries[$x]['topic_id'];
            $c_res = @$this->nativeQuery("SELECT t.topic_title,
                                        t.topic_poster,
                                        p.poster_id,
                                        t.forum_id,
                                        p.post_time,
                                        pt.post_subject,
                                        pt.post_text,
                                        pt.post_id
                                   FROM {$this->data['prefix']}topics AS t
                        LEFT OUTER JOIN {$this->data['prefix']}posts  AS p
                                     ON t.topic_id = p.topic_id
                        LEFT OUTER JOIN {$this->data['prefix']}posts  AS pt
                                     ON pt.post_id = p.post_id
                                  WHERE p.topic_id = {$topic_id}
                               ", $phbbdb); // pt. was posts_text table which now does not exists any more. So is this right with a doubled outer join on self?
            if (!$c_res) {
                return sprintf(COULDNT_SELECT_COMMENT_INFO, mysqli_error($phbbdb));
            }

            while ($a = mysqli_fetch_assoc($c_res)) {
                if ($a['post_id'] == $entries[$x]['post_id']) {
                    continue;
                }
                $author   = '';
                $mail     = '';
                $url      = '';

                foreach($users AS $user) {
                    if ($user['ID'] == $a['poster_id']) {
                        $author = $user['user_login'];
                        $mail   = $user['user_email'];
                        $url    = $user['user_url'];
                        break;
                    }
                }

                $comment = array('entry_id'   => $entries[$x]['entryid'],
                                 'parent_id'  => 0,
                                 'timestamp'  => $a['post_time'],
                                 'author'     => $author,
                                 'email'      => $mail,
                                 'url'        => $url,
                                 'ip'         => '',
                                 'status'     => 'approved',
                                 'body'       => $a['post_text'],
                                 'subscribed' => 'false',
                                 'type'       => 'NORMAL');

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

return 'Serendipity_Import_phpbb';

/* vim: set sts=4 ts=4 expandtab : */
?>