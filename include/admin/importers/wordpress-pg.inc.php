<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *      WordPress PostgreSQL Importer, by Devrim GUNDUZ          *
 *****************************************************************/

class Serendipity_Import_WordPress_PG extends Serendipity_Import
{
    var $info        = array('software' => 'WordPress PostgreSQL');
    var $data        = array();
    var $inputFields = array();

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
                                         'name' => 'password'),

                                   array('text' => INSTALL_DBPORT,
                                         'type' => 'input',
                                         'name' => 'port'),

                                   array('text' => INSTALL_DBNAME,
                                         'type' => 'input',
                                         'name' => 'name'),

                                   array('text' => INSTALL_DBPREFIX,
                                         'type' => 'input',
                                         'name' => 'prefix'),

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

        $this->data['prefix'] = serendipity_db_escape_string($this->data['prefix']);
        $users = array();
        $categories = array();
        $entries = array();
        $ul = array();
        $ulist = array();

        if (!extension_loaded('pgsql')) {
            return PGSQL_REQUIRED;
        }

        try { // As of PHP 8.1.0, using the default connection is deprecated. 8.1.0 	Returns an PgSql\Connection instance now; previously, a resource was returned. 
            $wpdb = pg_connect("{$this->data['host']}, {$this->data['port']}, {$this->data['user']}, {$this->data['pass']}, {$this->data['name']}");
        } catch (\Throwable $t) {
            $wpdb = false;
        }
        if (!$wpdb) {
            return sprintf(PGSQL_COULDNT_CONNECT, serendipity_specialchars($this->data['pass']));
        }

        /* Users */
        foreach (serendipity_fetchUsers() AS $uname) $ul[] = $uname['username'];

        $sql = "SELECT u.*,
                       m.meta_value AS wp_user_level
                  FROM {$this->data['prefix']}users AS u
             LEFT JOIN {$this->data['prefix']}usermeta m
                    ON m.user_id = u.ID
                 WHERE m.meta_key = 'wp_user_level'
                    ";
        $res = @pg_query($wpdb, $sql);
        if (!$res) {
            $res = @pg_query($wpdb, "SELECT * FROM {$this->data['prefix']}users;"); // old WP representation fallback
        }
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, pg_last_error($wpdb));
        }

        for ($x=0; $x < pg_num_rows($res); $x++) {
            $users[$x] = pg_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('realname'      => $users[$x]['display_name'] ?? $users[$x]['user_nicename'] ?? $users[$x]['user_login'],
                          'username'      => in_array('wp_' . $users[$x]['user_login'], $ul) ? 'wp_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'wp_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'password'      => serendipity_hash($npwd)); // WP uses MD5 or a salted MD5. So we have to create a new Styx password and keep it in an array to inform imported users later per email (if available)

            if (!empty($users[$x]['wp_user_level'])) {
                if (isset($users[$x]['wp_user_level']) && $users[$x]['wp_user_level'] <= 7) {
                    $data['userlevel'] = USERLEVEL_EDITOR;
                } elseif (isset($users[$x]['wp_user_level']) && $users[$x]['wp_user_level'] < 10) {
                    $data['userlevel'] = USERLEVEL_CHIEF;
                } else {
                    $data['userlevel'] = USERLEVEL_ADMIN;
                }
            } else {
                $data['userlevel'] = USERLEVEL_EDITOR; // reset to a simple Styx EDITOR role -  A manual ACL finetune set may follow later
            }

            if ($serendipity['serendipityUserlevel'] < $data['userlevel']) {
                $data['userlevel'] = $serendipity['serendipityUserlevel'];
            }
            $data['mail_comments'] = 0;
            $data['mail_trackbacks'] = 0;
            $data['email'] = $users[$x]['user_email'] ?? '';
            $data['right_publish'] = 1; // simplified to publish true, since real user level metadata roles live in wp_usermeta (see upper ACL note) and the wp_users.user_level field does not correspond (and maybe never did, as used for something different)
            $data['hashtype'] = 2;

            $ulist[$x] = $udata = $this->strtrRecursive($data);
            serendipity_db_insert('authors', $udata);
            $users[$x]['authorid'] = serendipity_db_insert_id('authors', 'authorid');

            // Add to mentoring
            $ulist[$x] = array_merge($ulist[$x], [ 'authorid' => $users[$x]['authorid'], 'new_plain_password' => $npwd ]);

            if ($debug) echo '<span class="msg_success">Imported users.</span>';

            echo '<h3>PHP COPY-array to mentor credential changes for partial email information or secured backup</h3>';
            echo '<div class="msg_notice"><strong>PLEASE NOTE</strong>: The NEW user password(s) are now encrypted <em>("$2y$10$&hellip;")</em> in the database. So <strong>this</strong> following array is the <strong><u>one & only</u></strong> copy of used <strong>new_plain_password</strong> value to log in. Also, if a username for login was already taken, it was given a "wp_" prefix with a -number addition for uniqueness!</div>';
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories */
        $res = @pg_query($wpdb, "SELECT cat_ID, cat_name, category_description, category_parent FROM {$this->data['prefix']}categories ORDER BY category_parent, cat_ID;");
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, pg_last_error($wpdb));
        }

        // Get all the info we need
        for ($x=0; $x < pg_num_rows($res); $x++)
            $categories[] = pg_fetch_assoc($res);

        // Insert all categories as top level (we need to know everyone's ID before we can represent the hierarchy).
        for ($x=0; $x < sizeof($categories); $x++) {
            $cat = array('category_name'        => $categories[$x]['cat_name'],
                         'category_description' => $categories[$x]['category_description'],
                         'parentid'             => 0,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $categories[$x]['categoryid'] = serendipity_db_insert_id('category', 'categoryid');
        }

        // There has to be a more efficient way of doing this...
        foreach($categories AS $cat) {
            if ($cat['category_parent'] != 0) {
                // Find the parent
                $par_id = 0;
                foreach($categories AS $possible_par) {
                    if ($possible_par['cat_ID'] == $cat['category_parent']) {
                        $par_id = $possible_par['categoryid'];
                        break;
                    }
                }

                if ($par_id != 0) {
                  serendipity_db_query("UPDATE {$serendipity['dbPrefix']}category SET parentid={$par_id} WHERE categoryid={$cat['categoryid']};");
                } // else { echo "D'oh! " . random_string_of_profanity(); }
            }
        }

        serendipity_rebuildCategoryTree();

        /* Entries */
        $res = @pg_query($wpdb, "SELECT * FROM {$this->data['prefix']}posts ORDER BY post_date;");
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, pg_last_error($wpdb));
        }

        for ($x=0; $x < pg_num_rows($res); $x++) {
            $entries[$x] = pg_fetch_assoc($res);

            $entry = array('title'          => $this->decode($entries[$x]['post_title']), // htmlentities() is called later, so we can leave this.
                           'isdraft'        => ($entries[$x]['post_status'] == 'publish') ? 'false' : 'true',
                           'allow_comments' => ($entries[$x]['comment_status'] == 'open' ) ? 'true' : 'false',
                           'timestamp'      => strtotime($entries[$x]['post_date']),
                           'body'           => $this->strtr($entries[$x]['post_content']));

            foreach($users AS $user) {
                if ($user['ID'] == $entries[$x]['post_author']) {
                    $entry['authorid'] = $user['authorid'];
                    break;
                }
            }

            if (!is_int($entries[$x]['entryid'] = serendipity_updertEntry($entry))) {
                return $entries[$x]['entryid'];
            }
        }

        /* Entry/category */
        $res = @pg_query($wpdb, "SELECT * FROM {$this->data['prefix']}post2cat;");
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, pg_last_error($wpdb));
        }

        while ($a = pg_fetch_assoc($res)) {
            foreach($categories AS $category) {
                if ($category['cat_ID'] == $a['category_id']) {
                    foreach( $entries AS $entry) {
                        if ($a['post_id'] == $entry['ID']) {
                            $data = array('entryid' => $entry['entryid'],
                                          'categoryid' => $category['categoryid']);
                            serendipity_db_insert('entrycat', $this->strtrRecursive($data));
                            break;
                        }
                    }
                    break;
                }
            }
        }

        /* Comments */
        $res = @pg_query($wpdb, "SELECT * FROM {$this->data['prefix']}comments;");
        if (!$res) {
            return sprintf(COULDNT_SELECT_COMMENT_INFO, pg_last_error($wpdb));
        }

        while ($a = pg_fetch_assoc($res)) {
            foreach($entries AS $entry) {
                if ($entry['ID'] == $a['comment_post_ID']) {
                    $comment = array('entry_id ' => $entry['entryid'],
                                     'parent_id' => 0,
                                     'timestamp' => strtotime($a['comment_date']),
                                     'author'    => $a['comment_author'],
                                     'email'     => $a['comment_author_email'],
                                     'url'       => $a['comment_author_url'],
                                     'ip'        => $a['comment_author_IP'],
                                     'status'    => (empty($a['comment_approved']) || $a['comment_approved'] == '1') ? 'approved' : 'pending',
                                     'subscribed'=> 'false',
                                     'body'      => $a['comment_content'],
                                     'type'      => 'NORMAL');

                    serendipity_db_insert('comments', $this->strtrRecursive($comment));
                    if ($comment['status'] == 'approved') {
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

}

return 'Serendipity_Import_WordPress_PG';

/* vim: set sts=4 ts=4 expandtab : */
?>