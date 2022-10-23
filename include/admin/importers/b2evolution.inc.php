<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *  b2evolution  Importer,   by Garvin Hicking *
 * ****************************************************************/

class Serendipity_Import_b2evolution extends Serendipity_Import
{
    var $info        = array('software' => 'b2Evolution 4.1 +');
    var $data        = array();
    var $inputFields = array();
    var $categories  = array();

    function getImportNotes()
    {
        return 'UH AHHHH! - <strong>b2Evolution</strong> has extremely changed over time! Be careful! This Importer was originally developed with b2Evolution 0.9.11 and some very early Serendipity version, loong ago. As one can imagine, things have changed over time. This new lookup requires at least b2Evolution 4.1 up to current v7.2.5-stable now and a running Styx instance up from latest v.3 Series. If you wish to give it a try, backup both database implementations and better do this in a testing environment first to see if you catch some breaking flaws. This new lookup has just been ported, NOT been tested! It does not capture and import an exact copy, just some main things like from authors, entries, comments and categories, but NOT image references and/or the physically stored files for example (and so forth for other stored configurations, granular controls over access privileges, etc). This and the relations finetuning is "handmade" User stuff - left up to YOU - later on! Now go and ride this horse. File an GitHub <a href="https://github.com/ophian/styx/issues" target="_blank">issue</a> or start a <a href="https://github.com/ophian/styx/discussions" target="_blank">discussion</a> for help!';
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
                                         'default' => 'evo_'),

                                   array('text'    => CHARSET,
                                         'type'    => 'list',
                                         'name'    => 'charset',
                                         'value'   => 'UTF-8',
                                         'default' => $this->getCharsets(true)),

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

        $users = array();
        $entries = array();
        $ul = array();
        $ulist = array();

        if (!extension_loaded('mysqli')) {
            return MYSQL_REQUIRED;
        }

        try {
            $b2db = mysqli_connect($this->data['host'], $this->data['user'], $this->data['pass']);
        } catch (\Throwable $t) {
            $b2db = false;
        }

        if (!$b2db || mysqli_connect_error()) {
            return sprintf(COULDNT_CONNECT, serendipity_specialchars($this->data['host']));
        }

        if (!@mysqli_select_db($b2db, $this->data['name'])) {
            return sprintf(COULDNT_SELECT_DB, mysqli_error($b2db));
        }

        /* Users */
        foreach (serendipity_fetchUsers() AS $uname) $ul[] = $uname['username'];

        $res = @$this->nativeQuery("SELECT
                                    user_ID    AS ID,
                                    user_login AS user_login,
                                    user_pass  AS user_pass,
                                    user_email AS user_email,
                                    user_level AS user_level,
                                    user_url   AS user_url
                               FROM {$this->data['prefix']}users", $b2db);
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, mysqli_error($b2db));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $users[$x] = mysqli_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('right_publish' => ($users[$x]['user_level'] >= 2) ? 1 : 0,
                          'realname'      => $users[$x]['user_realname'] ?? $users[$x]['user_login'],
                          'username'      => in_array('b2e_' . $users[$x]['user_login'], $ul) ? 'b2e_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'b2e_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'email'         => $users[$x]['user_email'] ?? '',
                          'password'      => serendipity_hash($npwd)); // Create a new Styx password and keep it in an array to inform imported users later per email (if available)

            if ($users[$x]['user_level'] <= 2) {
                $data['userlevel'] = USERLEVEL_EDITOR;
            } elseif ($users[$x]['user_level'] <= 9) {
                $data['userlevel'] = USERLEVEL_CHIEF;
            } else {
                $data['userlevel'] = USERLEVEL_ADMIN;
            }

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

            if ($debug) echo '<span class="msg_success">Imported users.</span>';

            echo IMPORTER_USER_IMPORT_SUCCESS_TITLE;
            echo sprintf(IMPORTER_USER_IMPORT_SUCCESS_MSG, 'b2e');
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories */
        if (!$this->importCategories($b2db, null, 0)) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($b2db));
        }
        serendipity_rebuildCategoryTree();

        /* Entries */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}items__item ORDER BY post_ID;", $b2db);
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, mysqli_error($b2db));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $entries[$x] = mysqli_fetch_assoc($res);

            $entry = array('title'          => $this->decode($entries[$x]['post_title']),
                           'isdraft'        => ($entries[$x]['post_status'] == 'published') ? 'false' : 'true',
                           'allow_comments' => ($entries[$x]['post_comment_status'] == 'open' ) ? 'true' : 'false',
                           'timestamp'      => strtotime(($entries[$x]['post_datecreated'] ?? $entries[$x]['post_datestart'])),
                           'body'           => $this->strtr($entries[$x]['post_content']));

            $entry['authorid'] = '';
            $entry['author']   = '';
            foreach($users AS $user) {
                if ($user['post_ID'] == $entries[$x]['post_creator_user_ID']) {
                    $entry['authorid'] = $user['authorid'];
                    $entry['author']   = $user['user_login'];
                    break;
                }
            }

            if (!is_int($entries[$x]['entryid'] = serendipity_updertEntry($entry))) {
                return $entries[$x]['entryid'];
            }

            /* Entry/category */
            foreach($this->categories AS $category) {
                if ($category['cat_ID'] == $entries[$x]['post_main_cat_ID'] ) {
                    $data = array('entryid'    => $entries[$x]['entryid'],
                                  'categoryid' => $category['categoryid']);
                    serendipity_db_insert('entrycat', $this->strtrRecursive($data));
                    break;
                }
            }
        }

        /* Even more category stuff */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}postcats;", $b2db);
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($b2db));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $entrycat = mysqli_fetch_assoc($res);

            $entryid = 0;
            $categoryid = 0;
            foreach($entries AS $entry) {
                if ($entry['ID'] == $entrycat['postcat_post_ID']) {
                    $entryid = $entry['entryid'];
                    break;
                }
            }

            foreach($this->categories AS $category) {
                if ($category['cat_ID'] == $entrycat['postcat_cat_ID']) {
                    $categoryid = $category['categoryid'];
                }
            }

            if ($entryid > 0 && $categoryid > 0) {
                $data = array('entryid'    => $entryid,
                              'categoryid' => $categoryid);
                serendipity_db_insert('entrycat', $this->strtrRecursive($data));
            }
        }

        /* Comments */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}comments;", $b2db);
        if (!$res) {
            return sprintf(COULDNT_SELECT_COMMENT_INFO, mysqli_error($b2db));
        }

        while ($a = mysqli_fetch_assoc($res)) {
            foreach($entries AS $entry) {
                if ($entry['ID'] == $a['comment_post_ID'] ) {
                    $author = '';
                    $mail     = '';
                    $url      = '';
                    if (!empty($a['comment_author_ID'])) {
                        foreach($users AS $user) {
                            if ($user['ID'] == $a['comment_author_ID']) {
                                $author = $user['user_login'];
                                $mail = $user['user_email'];
                                $url  = $user['user_url'];
                                break;
                            }
                        }
                    }

                    if (empty($author) && empty($mail)) {
                        $author = $a['comment_author'];
                        $mail = $a['comment_author_email'];
                        $url = $a['comment_author_url'];
                    }

                    $comment = array('entry_id'   => $entry['entryid'],
                                     'parent_id'  => 0,
                                     'timestamp'  => strtotime($a['comment_date']),
                                     'author'     => $author,
                                     'email'      => $mail,
                                     'url'        => $url,
                                     'ip'         => $a['comment_author_IP'],
                                     'status'     => ($a['comment_status'] == 'published' ? 'approved' : 'pending'),
                                     'body'       => $a['comment_content'],
                                     'subscribed' => 'false',
                                     'type'       => 'NORMAL');

                    serendipity_db_insert('comments', $this->strtrRecursive($comment));
                    if ($a['comment_status'] == 'published') {
                        $cid = serendipity_db_insert_id('comments', 'id');
                        serendipity_approveComment($cid, $entry['entryid'], true);
                    }
                }
            }
        }

        $serendipity['noautodiscovery'] = $noautodiscovery;

        // That was fun.
        return true;
    }

    function importCategories($b2db, $parentid = 0, $new_parentid = 0)
    {
        if (is_null($parentid)) {
            $where = 'WHERE ISNULL(cat_parent_ID)';
        } else {
            $where = "WHERE cat_parent_ID = '" . mysqli_escape_string($parentid) . "'";
        }

        $res = $this->nativeQuery("SELECT * FROM {$this->data['prefix']}categories
                                     " . $where, $b2db);
        if (!$res) {
            echo mysqli_error();
            return false;
        }

        // Get all the info we need
        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $row = mysqli_fetch_assoc($res);
            $cat = array('category_name'        => $row['cat_name'],
                         'category_description' => $row['cat_description'],
                         'parentid'             => (int)$new_parentid,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $row['categoryid']  = serendipity_db_insert_id('category', 'categoryid');
            $this->categories[] = $row;
            $this->importCategories($b2db, $row['cat_ID'], $row['categoryid']);
        }

        return true;
    }

}

return 'Serendipity_Import_b2evolution';

/* vim: set sts=4 ts=4 expandtab : */
