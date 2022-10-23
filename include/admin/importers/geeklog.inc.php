<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

/*****************************************************************
 *  geeklog  Importer,    by Garvin Hicking and Ian Styx         *
 * ***************************************************************/

class Serendipity_Import_geeklog extends Serendipity_Import
{
    var $info        = array('software' => 'Geeklog 1.4.1');
    var $data        = array();
    var $inputFields = array();
    var $categories  = array();

    function getImportNotes()
    {
        $update = 'This Importer was originally developed with Geeklog 1.3.11 and some very early Serendipity version, loong ago. As one can imagine, things have changed over time. This new lookup requires at least Geeklog 1.4.1 up to current v2.2.2 now and a running Styx instance up from latest v.3 Series. If you wish to give it a try, backup both database implementations and better do this in a testing environment first to see if you catch some breaking flaws. This new lookup has just been ported, NOT been tested! It does not capture and import an exact copy, just some main things like from authors, entries, comments and categories, but NOT image references and/or the physically stored files for example (and so forth for other stored configurations, etc). This and the relations finetuning is "handmade" User stuff - left up to YOU - later on! Now go and ride this horse. File an GitHub <a href="https://github.com/ophian/styx/issues" target="_blank">issue</a> or start a <a href="https://github.com/ophian/styx/discussions" target="_blank">discussion</a> for help!';
        return 'GeekLog has a granular control over access privileges which cannot be migrated to Serendipity. All Users will be migrated as Superusers, you may need to set them to EDITOR or CHIEF users manually after import. ' . $update;
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
                                         'default' => 'gl_'),

                                   array('text'    => CHARSET,
                                         'type'    => 'list',
                                         'name'    => 'charset',
                                         'value'   => 'native',
                                         'default' => $this->getCharsets()),

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
            $gdb = mysqli_connect($this->data['host'], $this->data['user'], $this->data['pass']);
        } catch (\Throwable $t) {
            $gdb = false;
        }

        if (!$gdb || mysqli_connect_error()) {
            return sprintf(COULDNT_CONNECT, serendipity_specialchars($this->data['host']));
        }

        if (!@mysqli_select_db($gdb, $this->data['name'])) {
            return sprintf(COULDNT_SELECT_DB, mysqli_error($gdb));
        }

        /* Users */
        $res = @$this->nativeQuery("SELECT uid AS ID,
                                    username   AS user_login,
                                    fullname   AS user_realname,
                                    passwd     AS user_pass,
                                    email      AS user_email,
                                    homepage   AS user_url
                               FROM {$this->data['prefix']}users", $gdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_USER_INFO, mysqli_error($gdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $users[$x] = mysqli_fetch_assoc($res);

            $npwd = serendipity_generate_password(20);
            $data = array('right_publish' => 1,
                          'realname'      => $users[$x]['user_realname'] ?? $users[$x]['user_login'],
                          'username'      => in_array('gkl_' . $users[$x]['user_login'], $ul) ? 'gkl_' . $users[$x]['user_login'].'-'.random_int(0, 0x3fff) : (in_array($users[$x]['user_login'], $ul) ? 'gkl_' . $users[$x]['user_login'] : $users[$x]['user_login']),
                          'email'         => $users[$x]['user_email'] ?? '',
                          'userlevel'     => USERLEVEL_ADMIN,
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

            if ($debug) echo '<span class="msg_success">Imported users.</span>';

            echo IMPORTER_USER_IMPORT_SUCCESS_TITLE;
            echo sprintf(IMPORTER_USER_IMPORT_SUCCESS_MSG, 'gkl');
            echo '<div class="import_full">';
            echo '<pre><code class="language-php">$added_users = ' . var_export($ulist, 1) . '</code></pre>';
            echo '</div>';
        }

        /* Categories */
        $res = @$this->nativeQuery("SELECT tid AS cat_ID, topic AS cat_name, topic AS category_description FROM {$this->data['prefix']}topics ORDER BY tid;", $gdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_CATEGORY_INFO, mysqli_error($gdb));
        }

        // Get all the info we need
        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $categories[] = mysqli_fetch_assoc($res);
        }

        // Insert all categories as top level (we need to know everyone's ID before we can represent the hierarchy).
        for ($x=0, $max_x = sizeof($categories) ; $x < $max_x ; $x++ ) {
            $cat = array('category_name'        => $categories[$x]['cat_name'],
                         'category_description' => $categories[$x]['category_description'],
                         'parentid'             => 0,
                         'category_left'        => 0,
                         'category_right'       => 0);

            serendipity_db_insert('category', $this->strtrRecursive($cat));
            $categories[$x]['categoryid'] = serendipity_db_insert_id('category', 'categoryid');
        }

        serendipity_rebuildCategoryTree();

        /* Entries */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}stories ORDER BY sid;", $gdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_ENTRY_INFO, mysqli_error($gdb));
        }

        for ($x=0, $max_x = mysqli_num_rows($res); $x < $max_x; $x++) {
            $entries[$x] = mysqli_fetch_assoc($res);

            $entry = array('title'          => $this->decode($entries[$x]['title']),
                           'isdraft'        => ($entries[$x]['draft_flag'] == '0') ? 'false' : 'true',
                           'allow_comments' => ($entries[$x]['comments'] == '1' ) ? 'true' : 'false',
                           'timestamp'      => strtotime($entries[$x]['date']),
                           'body'           => $this->strtr($entries[$x]['introtext']),
                           'extended'       => $this->strtr($entries[$x]['bodytext']),
                           );

            $entry['authorid'] = '';
            $entry['author']   = '';
            foreach($users AS $user) {
                if ($user['ID'] == $entries[$x]['uid']) {
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
                if ($category['cat_ID'] == $entries[$x]['tid'] ) {
                    $data = array('entryid'    => $entries[$x]['entryid'],
                                  'categoryid' => $category['categoryid']);
                    serendipity_db_insert('entrycat', $this->strtrRecursive($data));
                    break;
                }
            }
        }

        /* Comments */
        $res = @$this->nativeQuery("SELECT * FROM {$this->data['prefix']}comments;", $gdb);
        if (!$res) {
            return sprintf(COULDNT_SELECT_COMMENT_INFO, mysqli_error($gdb));
        }

        while ($a = mysqli_fetch_assoc($res)) {
            foreach($entries AS $entry) {
                if ($entry['sid'] == $a['sid'] ) {
                    $author   = '';
                    $mail     = '';
                    $url      = '';

                    foreach($users AS $user) {
                        if ($user['ID'] == $a['uid']) {
                            $author = $user['user_login'];
                            $mail = $user['user_email'];
                            $url  = $user['user_url'];
                            break;
                        }
                    }

                    $comment = array('entry_id ' => $entry['entryid'],
                                     'parent_id' => 0,
                                     'timestamp' => strtotime($a['date']),
                                     'author'    => $author,
                                     'email'     => $mail,
                                     'url'       => $url,
                                     'ip'        => $a['ip'],
                                     'status'    => 'approved',
                                     'body'      => $a['comment'],
                                     'subscribed'=> 'false',
                                     'type'      => 'NORMAL');

                    serendipity_db_insert('comments', $this->strtrRecursive($comment));
                    $cid = serendipity_db_insert_id('comments', 'id');
                    serendipity_approveComment($cid, $entry['entryid'], true);
                }
            }
        }

        $serendipity['noautodiscovery'] = $noautodiscovery;

        // That was fun.
        return true;
    }

}

return 'Serendipity_Import_geeklog';

/* vim: set sts=4 ts=4 expandtab : */
?>
