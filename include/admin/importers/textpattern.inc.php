<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *  textpattern  Importer,   by Garvin Hicking and Ian Styx      *
 *  Due to the nature of Textpattern’s evolution, upgrading a Textpattern instance older than version 4.2.0 (released 17 September 2009)
 *  requires a two-stage upgrade process to avoid loss of functionality and availability issues. Upgrade to version 4.2.0 first, ensuring
 *  all admin functionality is working as expected, and then upgrade to the latest stable release.
 *  For this major step the Textpattern Serendipity importer now requires at least a version up from 4.2.0 and the old importer’s implementation
 *  up from 'Textpattern 1.0rc1' is abandoned.
 * ****************************************************************/

class Serendipity_Import_textpattern extends Serendipity_Import
{
    var $info        = array('software' => 'Textpattern 4.2.0');
    var $data        = array();
    var $inputFields = array();
    var $categories  = array();

    function getImportNotes()
    {
        return 'This Importer was originally developed with Textpattern 1.0rc1 and some very early Serendipity version, loong ago. As one can imagine, things have changed over time. This new lookup requires at least Textpattern v.4.2.0 up to current v.4.8.8 now and a running Styx instance up from latest v.3 Series. If you wish to give it a try, backup both database implementations and better do this in a testing environment first to see if you catch some breaking flaws. This new lookup has just been ported, NOT been tested! It does not capture and import an exact copy, just some main things like from authors, entries and categories, but NOT image references and/or the physically stored files for example (and so forth for other stored configurations, etc). This and the relations finetuning is "handmade" User stuff - left up to YOU - later on! Now go and ride this horse. File an GitHub <a href="https://github.com/ophian/styx/issues" target="_blank">issue</a> or start a <a href="https://github.com/ophian/styx/discussions" target="_blank">discussion</a> for help!';
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
                                         'default' => ''),

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
        $entries = array();
        $ul = array();
        $ulist = array();

        if (!extension_loaded('mysqli')) {
            return MYSQL_REQUIRED;
        }

        try {
            $txpdb = mysqli_connect($this->data['host'], $this->data['user'], $this->data['pass']);
        } catch (\Throwable $t) {
            $txpdb = false;
        }

        if (!$txpdb || mysqli_connect_error()) {
            return sprintf(COULDNT_CONNECT, serendipity_specialchars($this->data['host']));
        }

        if (!@mysqli_select_db($txpdb, $this->data['name'])) {
            return sprintf(COULDNT_SELECT_DB, mysqli_error($txpdb));
        }
        // https://docs.textpattern.com/development/database-schema-reference#txp_users
        /* Users */
        foreach (serendipity_fetchUsers() AS $uname) $ul[] = $uname['username'];

        $res = @$this->nativeQuery("SELECT user_id  AS ID,
                                           name     AS user_login,
                                           RealName AS user_realname,
                                           pass     AS user_pass,
                                           email    AS user_email,
                                           privs    AS user_level
                               FROM {$this->data['prefix']}txp_users", $txpdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, mysqli_error($txpdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $users[$x] = mysqli_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('right_publish' => ($users[$x]['user_level'] <= 4) ? 1 : 0,
                          'realname'      => $users[$x]['user_realname'] ?? $users[$x]['user_login'],
                          'username'      => in_array('txp_' . $users[$x]['user_login'], $ul) ? 'txp_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'txp_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'email'         => $data['email'] = $users[$x]['user_email'] ?? '',
                          'password'      => serendipity_hash($npwd)); // Create a new Styx password and keep it in an array to inform imported users later per email (if available)

            // Privilege level (0 = none, 1 = publisher, 2 = managing editor, 3 = copy editor, 4 = staff writer, 5 = freelancer, 6 = designer). 
            // https://docs.textpattern.com/administration/user-roles-and-privileges
            if (!empty($users[$x]['user_level'])) {
                if (isset($users[$x]['user_level']) && $users[$x]['user_level'] >= 3) {
                    $data['userlevel'] = USERLEVEL_EDITOR;
                } elseif (isset($users[$x]['user_level']) && $users[$x]['user_level'] > 1) {
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
            $data['hashtype'] = 2;

            $ulist[$x] = $udata = $this->strtrRecursive($data);
            serendipity_db_insert('authors', $udata);
            $users[$x]['authorid'] = serendipity_db_insert_id('authors', 'authorid');

            // Add to mentoring
            $ulist[$x] = array_merge($ulist[$x], [ 'authorid' => $users[$x]['authorid'], 'new_plain_password' => $npwd ]);

            if ($debug) echo '<span class="msg_success">Imported users.</span>';

            echo IMPORTER_USER_IMPORT_SUCCESS_TITLE;
            echo sprintf(IMPORTER_USER_IMPORT_SUCCESS_MSG, 'txp');
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories */
        if (!$this->importCategories($txpdb, 'root', 0)) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($txpdb));
        }
        serendipity_rebuildCategoryTree();

        /* Entries */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}textpattern ORDER BY Posted;", $txpdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, mysqli_error($txpdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $entries[$x] = mysqli_fetch_assoc($res);

            $entry = array('title'          => $this->decode($entries[$x]['Title']),
                           'isdraft'        => ($entries[$x]['Status'] == '4') ? 'false' : 'true',
                           'allow_comments' => ($entries[$x]['Annotate'] == '1' ) ? 'true' : 'false',
                           'timestamp'      => strtotime($entries[$x]['Posted']),
                           'extended'       => $this->strtr($entries[$x]['Body_html']),
                           'body'           => $this->strtr($entries[$x]['Excerpt']));

            $entry['authorid'] = '';
            $entry['author']   = '';
            foreach($users AS $user) {
                if ($user['user_login'] == $entries[$x]['AuthorID']) {
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
                if ($category['name'] == $entries[$x]['Category1'] || $category['name'] == $entries[$x]['Category2']) {
                    $data = array('entryid'    => $entries[$x]['entryid'],
                                  'categoryid' => $category['categoryid']);
                    serendipity_db_insert('entrycat', $this->strtrRecursive($data));
                    break;
                }
            }
        }

        /* Comments */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}txp_discuss;", $txpdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_COMMENT_INFO, mysqli_error($txpdb));
        }

        while ($a = mysqli_fetch_assoc($res)) {
            foreach($entries AS $entry) {
                if ($entry['ID'] == $a['parentid'] ) {
                    $author   = $a['name'];
                    $mail     = $a['email'];
                    $url      = $a['web'];

                    $comment = array('entry_id ' => $entry['entryid'],
                                     'parent_id' => 0,
                                     'timestamp' => strtotime($a['posted']),
                                     'author'    => $author,
                                     'email'     => $mail,
                                     'url'       => $url,
                                     'ip'        => $a['ip'],
                                     'status'    => ($a['visible'] == '1' ? 'approved' : 'pending'),
                                     'body'      => $a['message'],
                                     'subscribed'=> 'false',
                                     'type'      => 'NORMAL');

                    serendipity_db_insert('comments', $this->strtrRecursive($comment));
                    if ($a['visible'] == '1') {
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

    function importCategories($txpdb, $parentname = 'root', $parentid = 0)
    {
        $res = $this->nativeQuery("SELECT * FROM {$this->data['prefix']}txp_category
                                     WHERE parent = '" . mysqli_escape_string($parentname) . "' AND type = 'article'", $txpdb);
        if (!$res) {
            echo mysqli_error();
            return false;
        }

        // Get all the info we need
        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $row = mysqli_fetch_assoc($res);
            $cat = array('category_name'        => $row['name'],
                         'category_description' => $row['name'],
                         'parentid'             => $parentid,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $row['categoryid']  = serendipity_db_insert_id('category', 'categoryid');
            $this->categories[] = $row;
            $this->importCategories($txpdb, $row['name'], $row['categoryid']);
        }

        return true;
    }

}

return 'Serendipity_Import_textpattern';

/* vim: set sts=4 ts=4 expandtab : */
?>