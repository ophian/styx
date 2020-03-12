<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}
@define('NOTHING_TODO', 'Nothing to do');

/**
 * Check if an uploaded file is "evil"
 *
 * @access public
 * @param   string  Input filename
 * @return boolean
 */
function serendipity_isActiveFile($file) {
    if (preg_match('@^\.@', $file)) {
        return true;
    }

    $core = preg_match('@\.(php.*|[psj]html?|pht|aspx?|cgi|jsp|py|pl)$@i', $file);
    if ($core) {
        return true;
    }

    $eventData = false;
    serendipity_plugin_api::hook_event('backend_media_check', $eventData, $file);
    return $eventData;
}

/**
 * Gets a list of media items from our media database
 *
 * LONG
 *
 * @access public
 * @param   int     The offset to start fetching media files
 * @param   int     How many items to fetch
 * @param   int     The number (referenced variable) of fetched items
 * @param   mixed   The "ORDER BY" SQL part when fetching items. As a simple 0-key array, eg. array('x, y') for matching database field orders
 * @param   string  Order by DESC or ASC
 * @param   string  Only fetch files from a specific directory
 * @param   string  Only fetch specific filenames (including check for realname match) - deprecated, since now in filters
 * @param   string  Only fetch media with specific keyword
 * @param   array   An array of restricting filter sets
 * @param   boolean Apply strict directory checks, or include subdirectories?
 * @return  array   Result-set of images
 */
function serendipity_fetchImagesFromDatabase($start=0, $limit=0, &$total=null, $order = false, $ordermode = false, $directory = '', $filename = '', $keywords = '', $filter = array(), $hideSubdirFiles = false) {
    global $serendipity;

    $cond = array(
        'joinparts' => array(),
        'parts'     => array(),
    );

    if ($limit != 0) {
        $limitsql = serendipity_db_limit_sql(serendipity_db_limit($start, $limit));
    } else {
        $limitsql = '';
    }

    $orderfields = serendipity_getImageFields();

    if (empty($order) || (!is_array($order)) && !isset($orderfields[$order])) {
        $order = 'i.date';
    }

    if (empty($ordermode) || ($ordermode != 'DESC' && $ordermode != 'ASC')) {
        $ordermode = 'DESC';
    }

    if (is_array($order)) {
        $order = $order[0];
    }

    if ($order == 'name') {
        $order = 'realname ' . $ordermode . ', name';
    }

    if (!is_array($filter)) {
        $filter = array();
    }

    if ($hideSubdirFiles) {
        $hotlinked = ($directory == '') ? " OR i.hotlink = 1" : '';
        $cond['parts']['directory'] = " AND (i.path = '" . serendipity_db_escape_string($directory) . "'$hotlinked)\n";
    } elseif (!empty($directory)) {
        $cond['parts']['directory'] = " AND i.path LIKE '" . serendipity_db_escape_string($directory) . "%'\n";
    }

    if (!empty($filename)) {
        $cond['parts']['filename'] = " AND (i.name LIKE '%" . serendipity_db_escape_string($filename) . "%' OR
                  i.realname LIKE '%" . serendipity_db_escape_string($filename) . "%')\n";
    }

    if (!is_array($keywords)) {
        if (!empty($keywords)) {
            $keywords = explode(';', $keywords);
        } else {
            $keywords = array();
        }
    }

    if (!empty($filter) && !isset($cond['parts']['filter'])) {
        $cond['parts']['filter'] = '';
    }

    if (count($keywords) > 0) {
        $cond['parts']['keywords'] = " AND (mk.property IN ('" . serendipity_db_implode("', '", $keywords, 'string') . "'))\n";
        $cond['joinparts']['keywords'] = true;
    }
    foreach($filter AS $f => $fval) {
        if (! (isset($orderfields[$f]) || $f == 'fileCategory') || empty($fval)) {
            continue;
        }

        if (is_array($fval)) {
            if (empty($fval['from']) || empty($fval['to'])) {
                continue;
            }

            if ($orderfields[$f]['type'] == 'date') {
                $fval['from'] = serendipity_convertToTimestamp(trim($fval['from']));
                $fval['to']   = serendipity_convertToTimestamp(trim($fval['to']));
            }

            if (substr($f, 0, 3) === 'bp.') {
                $realf = substr($f, 3);
                $cond['parts']['filter'] .= " AND (bp2.property = '$realf' AND bp2.value >= " . (int)$fval['from'] . ' AND bp2.value <= ' . (int)$fval['to'] . ")\n";
            } else {
                $cond['parts']['filter'] .= " AND ($f >= " . (int)$fval['from'] . " AND $f <= " . (int)$fval['to'] . ")\n";
            }
        } elseif ($f == 'i.authorid') {
            $cond['parts']['filter'] .= " AND (
                                    (hp.property = 'authorid' AND hp.value = " . (int)$fval . ")
                                    OR
                                    (i.authorid = " . (int)$fval . ")
                                )\n";
            $cond['joinparts']['hiddenproperties'] = true;
        } elseif (isset($orderfields[$f]) && isset($orderfields[$f]['type']) && $orderfields[$f]['type'] == 'int') {
            if (substr($f, 0, 3) === 'bp.') {
                $realf = substr($f, 3);
                $cond['parts']['filter'] .= " AND (bp2.property = '$realf' AND bp2.value = '" . serendipity_db_escape_string(trim($fval)) . "')\n";
            } else {
                $cond['parts']['filter'] .= " AND ($f = '" . serendipity_db_escape_string(trim($fval)) . "')\n";
            }
        } elseif ($f == 'fileCategory') {
            switch ($fval) {
                case 'image':
                    $cond['parts']['filter'] .= " AND (i.mime LIKE 'image/%')\n";
                    break;
                case 'video':
                    $cond['parts']['filter'] .= " AND (i.mime LIKE 'video/%')\n";
                    break;
            }
        } else {
            if (substr($f, 0, 3) === 'bp.') {
                $realf = substr($f, 3);
                $cond['parts']['filter'] .= " AND (bp2.property = '$realf' AND bp2.value LIKE '%" . serendipity_db_escape_string(trim($fval)) . "%')\n";
            } else {
                $cond['parts']['filter'] .= " AND ($f LIKE '%" . serendipity_db_escape_string(trim($fval)) . "%')\n";
            }
        }
        $cond['joinparts']['filterproperties'] = true;
    }

    // Ahem.., having to say: 'adminImagesViewOthers' is just fake, since that would need a real authorid to condition the images list by self and others! Todo: re-enable authorid.
    if (isset($serendipity['authorid']) && !serendipity_checkPermission('adminImagesViewOthers')) {
        if (!isset($cond['parts']['authorid'])) $cond['parts']['authorid'] = '';
        $cond['parts']['authorid'] .= ' AND (i.authorid = 0 OR i.authorid = ' . (int)$serendipity['authorid'] . ")\n";
    }

    $cond['and']  = 'WHERE 1=1 ' . implode("\n", $cond['parts']);
    $cond['args'] = func_get_args();
    serendipity_plugin_api::hook_event('fetch_images_sql', $cond);
    if (!isset($cond['joins'])) {
        $cond['joins'] = '';
    }
    serendipity_ACL_SQL($cond, false, 'directory');

    if (isset($cond['joinparts']['keywords']) && $cond['joinparts']['keywords']) {
        $cond['joins'] .= "\n LEFT OUTER JOIN {$serendipity['dbPrefix']}mediaproperties AS mk
                                        ON (mk.mediaid = i.id AND mk.property_group = 'base_keyword')\n";
    }

    if (substr($order, 0, 3) === 'bp.') {
        $cond['orderproperty'] = substr($order, 3);
        $cond['orderkey']   = 'bp.value';
        $order              = 'bp.value';
        $cond['joinparts']['properties'] = true;
    } else {
        $cond['orderkey'] = "''";
    }

    if (isset($cond['joinparts']['properties']) && $cond['joinparts']['properties']) {
        $cond['joins'] .= "\n LEFT OUTER JOIN {$serendipity['dbPrefix']}mediaproperties AS bp
                                        ON (bp.mediaid = i.id AND bp.property_group = 'base_property' AND bp.property = '{$cond['orderproperty']}')\n";
    }

    if (isset($cond['joinparts']['filterproperties']) && $cond['joinparts']['filterproperties']) {
        $cond['joins'] .= "\n LEFT OUTER JOIN {$serendipity['dbPrefix']}mediaproperties AS bp2
                                        ON (bp2.mediaid = i.id AND bp2.property_group = 'base_property')\n";
    }

    if (isset($cond['joinparts']['hiddenproperties']) && $cond['joinparts']['hiddenproperties']) {
        $cond['joins'] .= "\n LEFT OUTER JOIN {$serendipity['dbPrefix']}mediaproperties AS hp
                                        ON (hp.mediaid = i.id AND hp.property_group = 'base_hidden')\n";
    }

    if ($serendipity['dbType'] == 'postgres' ||
        $serendipity['dbType'] == 'pdo-postgres') {
        $cond['group']    = '';
        $cond['distinct'] = 'DISTINCT';
    } else {
        $cond['group']    = 'GROUP BY i.id';
        $cond['distinct'] = '';
    }

    $basequery = "FROM {$serendipity['dbPrefix']}images AS i
       LEFT OUTER JOIN {$serendipity['dbPrefix']}authors AS a
                    ON i.authorid = a.authorid
                       {$cond['joins']}

                       {$cond['and']}";

    $query = "SELECT {$cond['distinct']} i.id, {$cond['orderkey']} AS orderkey, i.name, i.extension, i.mime, i.size, i.dimensions_width, i.dimensions_height, i.date, i.thumbnail_name, i.authorid, i.path, i.hotlink, i.realname,
                     a.realname AS authorname
                     $basequery
                     {$cond['group']}
            ORDER BY $order $ordermode $limitsql";

    $rs = serendipity_db_query($query, false, 'assoc');

    if (!is_array($rs) && $rs !== true && $rs !== 1) {
        echo '<div>' . $rs . '</div>';
        return array();
    } elseif (!is_array($rs)) {
        return array();
    }

    $total_query = "SELECT count(i.id)
                           $basequery
                           GROUP BY i.id";
    $total_rs = serendipity_db_query($total_query, false, 'num');
    if (is_array($total_rs)) {
        $total = count($total_rs);
    }

    return $rs;
}

/**
 * Fetch a specific media item from the media database
 *
 * @access public
 * @param   int     The ID of an media item
 * @return  array   The media info data
 */
function serendipity_fetchImageFromDatabase($id, $mode = 'read') {
    global $serendipity;

    if (is_array($id)) {
        // int casting in serendipity_db_implode()
        $cond = array(
            'and' => 'WHERE i.id IN (' . serendipity_db_implode(',', $id) . ')'
        );
        $single   = false;
        $assocKey = 'id';
        $assocVal = false;
    } else {
        $cond = array(
            'and' => 'WHERE i.id = ' . (int)$id
        );
        $single   = true;
        $assocKey = false;
        $assocVal = false;
    }

    if ($serendipity['dbType'] == 'postgres' ||
        $serendipity['dbType'] == 'pdo-postgres') {
        $cond['group']    = '';
        $cond['distinct'] = 'DISTINCT';
    } else {
        $cond['group']    = 'GROUP BY i.id';
        $cond['distinct'] = '';
    }

    $cond['joins'] = ''; // init for serendipity_ACL_SQL conditionals joins

    if ($mode != 'discard') {
        serendipity_ACL_SQL($cond, false, 'directory', $mode);
    }

    $rs = serendipity_db_query("SELECT {$cond['distinct']} i.id, i.name, i.extension, i.mime, i.size, i.dimensions_width, i.dimensions_height, i.date, i.thumbnail_name, i.authorid, i.path, i.hotlink, i.realname
                                  FROM {$serendipity['dbPrefix']}images AS i
                                       {$cond['joins']}
                                       {$cond['and']}
                                       {$cond['group']}", $single, 'assoc', false, $assocKey, $assocVal);
    return $rs;
}

/**
 * Update a media item
 *
 * @access public
 * @param   array       An array of columns to update
 * @param   int         The ID of an media item to update
 * @return  boolean
 */
function serendipity_updateImageInDatabase($updates, $id) {
    global $serendipity;

    $admin = '';
    if (!serendipity_checkPermission('adminImagesAdd')) {
        $admin = ' AND (authorid = ' . $serendipity['authorid'] . ' OR authorid = 0)';
    }

    $i = 0;
    if (sizeof($updates) > 0) {
        foreach($updates AS $k => $v) {
            $q[] = $k ." = '" . serendipity_db_escape_string($v) . "'";
        }
        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}images SET ". implode($q, ',') .' WHERE id = ' . (int)$id . " $admin");
        $i++;
    }
    return $i;
}

/**
 * Delete a media item
 *
 * @access public
 * @param   int     The ID of a media item to delete
 * @return
 */
function serendipity_deleteImage($id) {
    global $serendipity;

    $dThumb   = array();
    $messages = '';
    $file = serendipity_fetchImageFromDatabase($id);

    if (!is_array($file)) {
        $messages .= sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . FILE_NOT_FOUND . "</span>\n", $id);
        //return false;
    } else {

        $dFile  = $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

        $dThumb = array(array(
            'fthumb' => $file['thumbnail_name']
        ));

        if (!serendipity_checkPermission('adminImagesDelete')) {
            return;
        }

        if (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid']) {
            // A non-admin user SHALL NOT be able to delete private files from other users.
            return;
        }

        if (!$file['hotlink']) {
            if (file_exists($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dFile)) {
                if (@unlink($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dFile)) {
                    $messages .= sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DELETE_FILE . "</span>\n", $dFile);
                } else {
                    $messages .= sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . DELETE_FILE_FAIL . "</span>\n", $dFile);
                }

                serendipity_plugin_api::hook_event('backend_media_delete', $dThumb);
                foreach($dThumb AS $thumb) {
                    $dfnThumb = $file['path'] . $file['name'] . (!empty($thumb['fthumb']) ? '.' . $thumb['fthumb'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
                    $dfThumb  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dfnThumb;

                    if (@unlink($dfThumb)) {
                        $messages .= sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DELETE_THUMBNAIL . "</span>\n", $dfnThumb);
                    }
                }
            } else {
                $messages .= sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . FILE_NOT_FOUND . "</span>\n", $dFile);
            }
        } else {
            $messages .= sprintf('<span class="msg_hint"><span class="icon-help-circled" aria-hidden="true"></span> ' . DELETE_HOTLINK_FILE . "</span>\n", $file['name']);
        }

        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}images WHERE id = ". (int)$id);
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}mediaproperties WHERE mediaid = ". (int)$id);
    }

    return $messages;
}

/**
 * Open a directory and fetch all existing media items
 *
 * @access public
 * @param   boolean     Reverse the exclude type listing
 * @param   array       Array list of found items
 * @param   string      sub-directory to investigate [recursive use]
 * @return  array       List of media items without Thumbs
 */
function serendipity_fetchImages($reverse = false, $images = '', $odir = '') {
    global $serendipity;

    // Open directory
    $basedir = $serendipity['serendipityPath'] . $serendipity['uploadPath'];
    $images = array();

    if (empty($serendipity['uniqueThumbSuffixes'])) {
        $usedSuffixes    = serendipity_db_query("SELECT DISTINCT(thumbnail_name) AS thumbSuffix FROM {$serendipity['dbPrefix']}images", false, 'num');
        $thumbSuffixes   = is_array($usedSuffixes) ? call_user_func_array('array_merge', $usedSuffixes) : array();
        $thumbSuffixes[] = $serendipity['thumbSuffix']; // might be set to 'styxThumb' for new version
        $thumbSuffixes[] = 'serendipityThumb'; // might be the old suffix name - which should usually be inside usedSuffixes, but if not, hardcode it here to make sure!
        $thumbSuffixes[] = '.quickblog'; // an out-of-range imageselectorplus created thumb
        $serendipity['uniqueThumbSuffixes'] = array_values(array_unique($thumbSuffixes)); // only use unique strpos() search values
        if ($reverse) {
            $serendipity['uniqueThumbSuffixes'] = array_diff($serendipity['uniqueThumbSuffixes'], array($serendipity['thumbSuffix'], '.quickblog'));
        }
    }

    if ($dir = @opendir($basedir . $odir)) {
        $aTempArray = array();
        while (($file = @readdir($dir)) !== false) {
            if ($file == '.svn' || $file == 'CVS' || $file == '.htaccess' || strtolower($file) == 'thumbs.db' || $file == '.' || $file == '..') {
                continue;
            }
            array_push($aTempArray, $file);
        }
        @closedir($dir);
        sort($aTempArray);
        foreach($aTempArray AS $f) {
            if (!$reverse) {
                if (serendipity_contains($f, $serendipity['uniqueThumbSuffixes'])) {
                    // This is a sized serendipity thumbnail or something similar ranged "~outside" ML (see imageselectorplus event plugin), skip it!
                    continue;
                }
            }

            $cdir = ($odir != '') ? $odir . '/' : '';
            if (is_dir($basedir . $odir . '/' . $f)) {
                $temp = serendipity_fetchImages($reverse, $images, $cdir . $f);
                foreach($temp AS $tkey => $tval) {
                    if ($reverse) {
                        if (serendipity_contains($tval, $serendipity['uniqueThumbSuffixes'])) {
                            array_push($images, $tval);
                        }
                    } else {
                        array_push($images, $tval);
                    }
                }
            } else {
                if ($reverse) {
                    if (serendipity_contains($f, $serendipity['uniqueThumbSuffixes'])) {
                        array_push($images, $cdir . $f);
                    }
                } else {
                    array_push($images, $cdir . $f);
                }
            }
        }
    }
    if (!$reverse) {
        natsort($images);
    }

    /* BC */
    $serendipity['imageList'] = $images;
    return $images;
}

/**
 * Inserts a hotlinked media file
 *
 * hotlinks are files that are only linked in your database, and not really stored on your server
 *
 * @access public
 * @param   string      The filename to hotlink
 * @param   string      The URL to hotlink with
 * @param   int         The owner of the hotlinked media item
 * @param   int         The timestamp of insertion (UNIX second)
 * @param   string      A temporary filename for fetching the file to investigate it
 * @return  int         The ID of the inserted media item
 */
function serendipity_insertHotlinkedImageInDatabase($filename, $url, $authorid = 0, $time = NULL, $tempfile = NULL) {
    global $serendipity;

    if (is_null($time)) {
        $time = time();
    }

    list($filebase, $extension) = serendipity_parseFileName($filename);

    if ($tempfile && file_exists($tempfile)) {
        $filesize = @filesize($tempfile);
        $fdim     = @serendipity_getimagesize($tempfile, '', $extension);
        $width    = $fdim[0];
        $height   = $fdim[1];
        $mime     = $fdim['mime'];
        @unlink($tempfile);
    }

    $query = sprintf(
      "INSERT INTO {$serendipity['dbPrefix']}images (
                    name,
                    date,
                    authorid,
                    extension,
                    mime,
                    size,
                    dimensions_width,
                    dimensions_height,
                    path,
                    hotlink,
                    realname
                   ) VALUES (
                    '%s',
                    %s,
                    %s,
                    '%s',
                    '%s',
                    %s,
                    %s,
                    %s,
                    '%s',
                    1,
                    '%s'
                   )",
      serendipity_db_escape_string($filebase),
      (int)$time,
      (int)$authorid,
      serendipity_db_escape_string($extension),
      serendipity_db_escape_string($mime),
      (int)$filesize,
      (int)$width,
      (int)$height,
      serendipity_db_escape_string($url),
      serendipity_db_escape_string($filename)
    );

    $sql = serendipity_db_query($query);
    if (is_string($sql)) {
        echo '<span class="block_level">' . $query . "</span>\n";
        echo '<span class="block_level">' . $sql . "</span>\n";
    }

    $image_id = serendipity_db_insert_id('images', 'id');
    if ($image_id > 0) {
        return $image_id;
    }

    return 0;
}

/**
 * Insert a media item in the database
 *
 * @access public
 * @param   string      The filename of the media item
 * @param   string      The path to the media item
 * @param   int         The owner author of the item
 * @param   int         The timestamp of when the media item was inserted
 * @return  int         The new media ID
 */
function serendipity_insertImageInDatabase($filename, $directory, $authorid = 0, $time = NULL, $realname = NULL) {
    global $serendipity;

    if (is_null($time)) {
        $time = time();
    }

    if (is_null($realname)) {
        $realname = $filename;
    }

    $filepath = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $directory . $filename;
    $filesize = @filesize($filepath);

    list($filebase, $extension) = serendipity_parseFileName($filename);

    $thumbpath = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $directory . $filebase . '.'. $serendipity['thumbSuffix'] . (empty($extension) ? '' : '.' . $extension);
    $thumbnail = (file_exists($thumbpath) ? $serendipity['thumbSuffix'] : '');

    $fdim   = @serendipity_getimagesize($filepath, '', $extension);
    $width  = $fdim[0];
    $height = $fdim[1];
    $mime   = $fdim['mime'];

    $query = sprintf(
      "INSERT INTO {$serendipity['dbPrefix']}images (
                    name,
                    extension,
                    mime,
                    size,
                    dimensions_width,
                    dimensions_height,
                    thumbnail_name,
                    date,
                    authorid,
                    path,
                    realname
                   ) VALUES (
                    '%s',
                    '%s',
                    '%s',
                    %s,
                    %s,
                    %s,
                    '%s',
                    %s,
                    %s,
                    '%s',
                    '%s'
                   )",
      serendipity_db_escape_string($filebase),
      serendipity_db_escape_string($extension),
      serendipity_db_escape_string($mime),
      (int)$filesize,
      (int)$width,
      (int)$height,
      serendipity_db_escape_string($thumbnail),
      (int)$time,
      (int)$authorid,
      serendipity_db_escape_string($directory),
      serendipity_db_escape_string($realname)
    );

    $sql = serendipity_db_query($query);
    if (is_string($sql)) {
        echo '<span class="block_level">' . $query . "</span>\n";
        echo '<span class="block_level">' . $sql . "</span>\n";
    }

    $image_id = serendipity_db_insert_id('images', 'id');
    if ($image_id > 0) {
        return $image_id;
    }

    return 0;
}

/**
 * Create a thumbnail for an image
 *
 * LONG
 *
 * @access public
 * @param   string      The input image filename
 * @param   string      The directory to the image file
 * @param   string      The target size of the thumbnail (2-dimensional array width,height)
 * @param   string      Name of the thumbnail
 * @param   bool        Store thumbnail in temporary place?
 * @param   bool        Force enlarging of small images?
 * @return  array       The result size of the thumbnail
 */
function serendipity_makeThumbnail($file, $directory = '', $size = false, $thumbname = false, $is_temporary = false, $force_resize = false) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger

    if ($size === false) {
        $size = $serendipity['thumbSize'];
    }
    if ($size < 1) {
       return array(0,0);
    }

    if ($thumbname === false) {
        $thumbname = $serendipity['thumbSuffix'];
    }

    $t       = serendipity_parseFileName($file);
    $f       = $t[0];
    $suf     = $t[1];
    $infile  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $directory . $file;

    if ($debug) {
        $logtag = 'ML_MAKETHUMBNAIL:';
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START ML serendipity_makeThumbnail SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("$logtag From: $infile");
    }

    if ($is_temporary) {
        $temppath = dirname($thumbname);
        if (!is_dir($temppath)) {
            @mkdir($temppath);
        }
        $outfile = $thumbname;
    } else {
        $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $directory . $f . '.' . $thumbname . '.' . $suf;
    }

    $serendipity['last_outfile'] = $outfile;

    if ($debug) {
        $serendipity['logger']->debug("$logtag To: $outfile");
    }

    $fdim = @serendipity_getimagesize($infile, '', $suf);
    if (isset($fdim['noimage'])) {
        $r = array(0, 0);
    } else {
        if ($serendipity['magick'] !== true) {
            if (is_array($size)) {
                // The caller wants a thumbnail with a specific size
                $r = serendipity_resize_image_gd($infile, $outfile, $size['width'], $size['height']);
            } else {
                // The caller wants a thumbnail constrained in the dimension set by config
                $calc = serendipity_calculate_aspect_size($fdim[0], $fdim[1], $size, $serendipity['thumbConstraint']);
                $r    = serendipity_resize_image_gd($infile, $outfile, $calc[0], $calc[1]);
            }
        } else {
            if (is_array($size)) {
                if ($fdim[0] > $size['width'] && $fdim[1] > $size['height']) {
                    $r = array(0 => $size['width'], 'width' => $size['width'], 1 => $size['height'], 'height' => $size['height']);
                } else {
                    return array(0, 0); // do not create any thumb, if image is smaller than defined sizes
                }
            } else {
                $calc = serendipity_calculate_aspect_size($fdim[0], $fdim[1], $size, $serendipity['thumbConstraint']);
                $r    = array(0 => $calc[0], 'width' => $calc[0], 1 => $calc[1], 'height' => $calc[1]);
            }

            $newSize = $r['width'] . 'x' . $r['height'];
            // CMD - Be strict on order: (Normally a setting should come before bulk images and an image operator after the image filename [the later in special for IM 7 versions !!])
            // Since we have 1:1 file relations this can be set to: INFILE -setting(s) -operator(s) OUTFILE, @see
            //          http://magick.imagemagick.org/script/command-line-processing.php#setting
            // The here used -flatten and -scale are Sequence Operators, while -antialias is a Setting and -resize is an Operator.
            if ($fdim['mime'] == 'application/pdf') {
                $cmd = escapeshellcmd($serendipity['convert']) . ' '. serendipity_escapeshellarg($infile . '[0]') . ' -antialias -flatten -scale ' . serendipity_escapeshellarg($newSize) .' '. serendipity_escapeshellarg($outfile . '.png');
                $isPDF = true;
                if ($debug) { $serendipity['logger']->debug("PDF thumbnail creation: $cmd"); }
            } else {
                if (!$force_resize && serendipity_ini_bool(ini_get('safe_mode')) === false) {
                    $newSize .= '>'; // tell ImageMagick to not enlarge small images. This only works if safe_mode is off (safe_mode turns > in to \>)
                }

                $bang = '';
                if (empty($serendipity['imagemagick_nobang'])) {
                    // force the first run image geometry exactly to given sizes, if there were rounding differences (@see https://github.com/s9y/Serendipity/commit/94881ba and comments)
                    $bang = (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') ? '\!' : '!'; // escape by OS
                    // escapeshellarg() adds single quotes around a string and quotes/escapes any existing single quotes
                    // allowing you to pass a string directly to a shell function and having it be treated as a single safe argument.
                    // On Windows, escapeshellarg() instead replaces percent signs, exclamation marks (delayed variable substitution) and double quotes with spaces and adds double quotes around the string.
                    // see follow-on workaround for the bang on WIN OS.
                }
                $newSize .= $bang;

                $_itp = !empty($serendipity['imagemagick_thumb_parameters']) ? ' '. $serendipity['imagemagick_thumb_parameters'] : '';

                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    $cmd = escapeshellcmd($serendipity['convert']) . ' ' . serendipity_escapeshellarg($infile) . $_itp . ' -antialias -resize ' . str_replace('\ "', '!"', serendipity_escapeshellarg($newSize)) . ' ' . serendipity_escapeshellarg($outfile);
                } else {
                    $cmd = escapeshellcmd($serendipity['convert']) . ' ' . serendipity_escapeshellarg($infile) . $_itp . ' -antialias -resize ' . serendipity_escapeshellarg($newSize) . ' ' . serendipity_escapeshellarg($outfile);
                }
                $isPDF = false;
                if ($debug) { $serendipity['logger']->debug("Image thumbnail creation: $cmd"); }
            }

            exec($cmd, $output, $result);

            if ($result != 0) {
                if (!$isPDF) {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $cmd, @$output[0], $result) ."</span>\n";
                } else {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> PDF thumbnail creation using ImageMagick and Ghostscript failed!' . "</span>\n";
                }
                $r = false; // return failure
            } else {
                touch($outfile);
            }
            unset($output, $result);
        }
    }
    return $r;
}

/**
 * Scale an image
 *
 * LONG
 *
 * @access public
 * @param   int     The ID of an image
 * @param   int     The target width
 * @param   int     The target height
 * @return true
 */
function serendipity_scaleImg($id, $width, $height) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger

    $file = serendipity_fetchImageFromDatabase($id);
    if (!is_array($file)) {
        return false;
    }

    $admin = '';
    if (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid']) {
        // A non-admin user SHALL NOT change private files from other users.
        return;
    }

    $infile = $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

    if ($serendipity['magick'] !== true) {
        if (serendipity_resize_image_gd($infile, $outfile, $width, $height)) {
            $result = 0;
        }
    } else {
        $cmd = escapeshellcmd($serendipity['convert']) . ' ' . serendipity_escapeshellarg($infile) . ' -scale ' . serendipity_escapeshellarg($width . 'x' . $height) . ' ' . serendipity_escapeshellarg($outfile);
        if ($debug) { $serendipity['logger']->debug("Scale File command: $cmd"); }
        exec($cmd, $output, $result);
        if ($result != 0) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $cmd, $output[0], $result) ."</span>\n";
            return false;
        }
        unset($output);
    }

    if ($result == 0) {
        serendipity_updateImageInDatabase(array('dimensions_width' => $width, 'dimensions_height' => $height, 'size' => @filesize($outfile)), $id);
        return true;
    }
    return false;
}

/**
 * Rotate an image
 *
 * LONG
 *
 * @access public
 * @param   int     The ID of an image
 * @param   int     Number of degrees to rotate
 * @return boolean
 */
function serendipity_rotateImg($id, $degrees) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger

    $file = serendipity_fetchImageFromDatabase($id);
    if (!is_array($file)) {
        return false;
    }

    $admin = '';
    if (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid']) {
        // A non-admin user SHALL NOT change private files from other users.
        return false;
    }

    $infile = $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
    $infileThumb = $outfileThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);

    if ($serendipity['magick'] !== true) {
        serendipity_rotate_image_gd($infile, $outfile, $degrees);
        serendipity_rotate_image_gd($infileThumb, $outfileThumb, $degrees);
    } else {
        /* Why can't we just all agree on the rotation direction?
        -> Styx 2.5 disabled, since that seems to be a workaround for a very very old bug
        $degrees = (360 - $degrees); */

        /* Resize main image */
        $cmd = escapeshellcmd($serendipity['convert']) . ' ' . serendipity_escapeshellarg($infile) . ' -rotate ' . serendipity_escapeshellarg($degrees) . ' ' . serendipity_escapeshellarg($outfile);
        if ($debug) { $serendipity['logger']->debug("Resize main file command: $cmd"); }
        exec($cmd, $output, $result);
        if ($result != 0) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $cmd, $output[0], $result) ."</span>\n";
        }
        unset($output, $result);

        /* Resize thumbnail */
        $cmd = escapeshellcmd($serendipity['convert']) . ' ' . serendipity_escapeshellarg($infileThumb) . ' -rotate ' . serendipity_escapeshellarg($degrees) . ' ' . serendipity_escapeshellarg($outfileThumb);
        if ($debug) { $serendipity['logger']->debug("Resize thumb file command: $cmd"); }
        exec($cmd, $output, $result);
        if ($result != 0) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $cmd, $output[0], $result) ."</span>\n";
        }
        unset($output, $result);

    }

    $fdim = @getimagesize($outfile);

    serendipity_updateImageInDatabase(array('dimensions_width' => $fdim[0], 'dimensions_height' => $fdim[1]), $id);

    return true;
}

/**
 * Creates thumbnails for all images in the upload dir
 *
 * @access public
 * @return  int Number of created thumbnails
 */
function serendipity_generateThumbs() {
    global $serendipity;

    $i = 0;
    $serendipity['imageList'] = serendipity_fetchImagesFromDatabase(0, 0, $total, array('path, name'), 'ASC');
    $_list = '';

    echo '<section class="media_rebuild_thumbs">' . "\n";
    printf('    <header><h2>' . sprintf(RESIZE_BLAHBLAH, THUMBNAIL_SHORT) . "</h2></header>\n");

    foreach($serendipity['imageList'] AS $k => $file) {
        $is_image = serendipity_isImage($file);

        if ($is_image && !$file['hotlink']) {
            $update   = false;
            $filename = $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
            $ffull    = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $filename;

            if (!file_exists($ffull)) {
                serendipity_deleteImage($file['id']); // no message output?
                continue;
            }

            if (empty($file['thumbnail_name'])) {
                $file['thumbnail_name'] = $serendipity['thumbSuffix'];
            }

            $oldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . '.' . $file['thumbnail_name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
            $newThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . '.' . $serendipity['thumbSuffix'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
            $sThumb   = $file['path'] . $file['name'] . '.' . $serendipity['thumbSuffix'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
            $fdim     = @getimagesize($ffull);

            // create a sized thumbnail
            if (!file_exists($oldThumb) && !file_exists($newThumb) && ($fdim[0] > $serendipity['thumbSize'] || $fdim[1] > $serendipity['thumbSize'])) {
                $returnsize = serendipity_makeThumbnail($file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']), $file['path']);
                if ($returnsize !== false && is_array($returnsize)) {
                    $_list .= '<li>' . sprintf(RESIZE_BLAHBLAH, '<b>' . $sThumb . '</b>') . ': ' . $returnsize[0] . 'x' . $returnsize[1] . "</li>\n";
                    if (!file_exists($newThumb)) {
                        $_list .= sprintf('<li>' . THUMBNAIL_FAILED_COPY . "</li>\n", '<b>' . $sThumb . '</b>');
                    } else {
                        $update = true;
                    }
                }
            // copy the too small origin $ffull image to a copy with the name $newThumb, since we need a thumbnail explicitly
            } elseif (!file_exists($oldThumb) && !file_exists($newThumb) && $fdim[0] <= $serendipity['thumbSize'] && $fdim[1] <= $serendipity['thumbSize']) {
                $res = @copy($ffull, $newThumb);
                if (@$res === true) {
                    $_list .= sprintf('<li>' . THUMBNAIL_USING_OWN . "</li>\n", '<b>' . $filename . '</b>');
                    $update = true;
                } else {
                    $_list .= sprintf('<li>' . THUMBNAIL_FAILED_COPY . "</li>\n", '<b>' . $sThumb . '</b>');
                }
            }

            if ($update) {
                $i++;
                $updates = array('thumbnail_name' => $serendipity['thumbSuffix']);
                serendipity_updateImageInDatabase($updates, $file['id']);
            }
        } else {
            // Currently, non-image files have no thumbnail.
        }
    }

    // Close the list, if it was created
    if (!empty($_list)) {
         echo '<ul class="plainList">' . "\n";
         echo $_list;
         echo "</ul>\n";
    } else {
        echo '    <span class="msg_success"><span class="icon-ok-circled"></span> ' . DONE . ' (' . NOTHING_TODO . ')</span>' . "\n";
    }
    echo "</section>\n";

    return $i;
}

/**
 * Guess the MIME type of a file
 *
 * @access public
 * @param   string  Filename extension
 * @return  string  Mimetype
 */
function serendipity_guessMime($extension) {
    $mime = '';
    switch (strtolower($extension)) {
        case 'jpg':
        case 'jpeg':
            $mime = 'image/jpeg';
        break;

        case 'aiff':
        case 'aif':
            $mime = 'audio/x-aiff';
            break;

        case 'gif':
            $mime = 'image/gif';
        break;

        case 'png':
            $mime = 'image/png';
        break;

        case 'pdf':
            $mime = 'application/pdf';
            break;

        case 'doc':
            $mime = 'application/msword';
            break;

        case 'rtf':
            $mime = 'application/rtf';
            break;

        case 'wav':
        case 'wave':
            $mime = 'audio/x-wav';
            break;

        case 'mp2':
        case 'mpg':
        case 'mpeg':
            $mime = 'video/x-mpeg';
            break;

        case 'avi':
            $mime = 'video/x-msvideo';
            break;

        case 'mp3':
            $mime = 'audio/x-mpeg3';
            break;

        case 'xlm':
        case 'xlb':
        case 'xll':
        case 'xla':
        case 'xlw':
        case 'xlc':
        case 'xls':
        case 'xlt':
            $mime = 'application/vnd.ms-excel';
            break;

        case 'ppt':
        case 'pps':
            $mime = 'application/vnd.ms-powerpoint';
            break;

        case 'html':
        case 'htm':
            $mime = 'text/html';
            break;

        case 'xsl':
        case 'xslt':
        case 'xml':
        case 'wsdl':
        case 'xsd':
            $mime = 'text/xml';
            break;

        case 'zip':
            $mime = 'application/zip';
            break;

        case 'tar':
            $mime = 'application/x-tar';
            break;

        case 'tgz':
        case 'gz':
            $mime = 'application/x-gzip';
            break;

        case 'swf':
            $mime = 'application/x-shockwave-flash';
            break;

        case 'rm':
        case 'ra':
        case 'ram':
            $mime = 'application/vnd.rn-realaudio';
            break;

        case 'exe':
            $mime = 'application/octet-stream';
            break;

        case 'mov':
        case 'mp4':
        case 'qt':
            $mime = 'video/x-quicktime';
            break;

        case 'midi':
        case 'mid':
            $mime = 'audio/x-midi';
            break;

        case 'txt':
            $mime = 'text/plain';
            break;

        case 'qcp':
            $mime = 'audio/vnd.qcelp';
            break;

        case 'emf':
            $mime = 'image/x-emf';
            break;

        case 'wmf':
            $mime = 'image/x-wmf';
            break;

        case 'snd':
            $mime = 'audio/basic';
            break;

        case 'pmd':
            $mime = 'application/x-pmd';
            break;

        case 'wbmp':
            $mime = 'image/vnd.wap.wbmp';
            break;

        case 'gcd':
            $mime = 'text/x-pcs-gcd';
            break;

        case 'mms':
            $mime = 'application/vnd.wap.mms-message';
            break;

        case 'ogg':
        case 'ogm':
            $mime = 'application/ogg';
            break;

        case 'rv':
            $mime = 'video/vnd.rn-realvideo';
            break;

        case 'wmv':
            $mime = 'video/x-ms-wmv';
            break;

        case 'wma':
            $mime = 'audio/x-ms-wma';
            break;

        case 'qcp':
            $mime = 'audio/vnd.qcelp';
            break;

        case 'jad':
            $mime = 'text/vnd.sun.j2me.app-descriptor';
            break;

        case '3g2':
        case '3gp':
            $mime = 'video/3gpp';
            break;

        case 'jar':
            $mime = 'application/java-archive';
            break;

        case 'ico':
            $mime = 'image/x-icon';
            break;

        default:
            $mime = 'application/octet-stream';
            break;
    }

    return $mime;
}

/**
 * Convert existing thumbnails using an old naming-scheme, which are not like current thumbSuffix
 *
 * Now, in a total independent task:
 *       1. rename in filesystem
 *       2. rename in db tables
 *          1. images,
 *          2. entries,
 *          3. entryproperties
 *          4. staticpages,
 *
 * @access public
 * @return  int     Number of updated thumbnails
 */
function serendipity_convertThumbs() {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger

    if ($debug) {
        $logtag = 'MAINTENANCE IMAGE-SYNC Opt4:';
        $trace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START MS serendipity_convertThumbs() SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    // fetch all excluded files from list in $files relative to /uploads directory (make sure it is synced before)
    $ofiles = serendipity_fetchImages(true);
    $nfiles = array();
    $i = $e = $s = 0;

    if ($debug) {
        $serendipity['logger']->debug("$logtag UniqueThumbSuffixes: ".print_r($serendipity['uniqueThumbSuffixes'],1));
        $serendipity['logger']->debug("$logtag REVERSE THUMB FILES: ".print_r($ofiles,1));
    }

    if (empty($ofiles)) return $i;

    echo "<span class=\"msg_notice\">\n<ul>\n";

    // Open directory
    $basedir = $serendipity['serendipityPath'] . $serendipity['uploadPath'];
    // rename in filepath
    foreach($ofiles AS $oldthumbnail) {
        foreach($serendipity['uniqueThumbSuffixes'] AS $othumb) {
            $newThumbnail = str_replace($othumb, $serendipity['thumbSuffix'], $oldthumbnail);
            $nfiles[] = $newThumbnail;
            // RENAME in file system
            rename($basedir.$oldthumbnail, $basedir.$newThumbnail);
            if ($debug) { $serendipity['logger']->debug("\n\n$logtag FILE RENAMES FROM::TO:\n".$basedir.$oldthumbnail.",\n".$basedir.$newThumbnail . DONE); }
            // update in image database
            $q = "UPDATE {$serendipity['dbPrefix']}images
                     SET thumbnail_name = '" . serendipity_db_escape_string($serendipity['thumbSuffix']) . "'
                   WHERE thumbnail_name = '" . serendipity_db_escape_string($othumb) . "'";
            if ($debug) { $serendipity['logger']->debug("$logtag UPDATE images DB::images:\n$q"); }
            serendipity_db_query($q);
            if ($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') {
                // SELECT-ing the entries by $oldthumbnail singularly
                $eq = "SELECT id, body, extended
                         FROM {$serendipity['dbPrefix']}entries
                        WHERE body     REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'
                           OR extended REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'";
            } else {
                $eq = "SELECT id, body, extended
                        FROM {$serendipity['dbPrefix']}entries
                       WHERE (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')
                          OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')";
            }
            if ($debug) { $serendipity['logger']->debug("$logtag SELECT entries DB::entries:\n$eq"); }
            $entries = serendipity_db_query($eq, false, 'assoc');
            if (is_array($entries)) {
                foreach($entries AS $entry) {
                    $id = serendipity_db_escape_string($entry['id']);
                    $entry['body']     = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['body']);
                    $entry['extended'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['extended']);
                    $uq = "UPDATE {$serendipity['dbPrefix']}entries
                              SET body = '" . serendipity_db_escape_string($entry['body']) . "' ,
                                  extended = '" . serendipity_db_escape_string($entry['extended']) . "'
                            WHERE id = $id";
                    if ($debug) { $serendipity['logger']->debug("$logtag UPDATE entries DB::entries:\nID:{$entry['id']} {$serendipity['dbPrefix']}entries::[body|extended] update " .DONE); }
                    serendipity_db_query($uq);
                    // count the entries changed
                    if ($_tmpEntryID != $entry['id']) $e++;
                    $_tmpEntryID = $entry['id'];

                    // SAME FOR ENTRYPROPERTIES CACHE for ep_cache_body
                    $epq1 = "SELECT entryid, value
                               FROM {$serendipity['dbPrefix']}entryproperties
                              WHERE entryid = $id AND property = 'ep_cache_body'";
                    if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_body):ID:{$entry['id']}\n$epq1"); }
                    $eps1 = serendipity_db_query($epq1, false, 'assoc');
                    if (is_array($eps1)) {
                        $eps1['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $eps1['value']);
                        $uepq1 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                     SET value = '" . serendipity_db_escape_string($eps1['value']) . "'
                                   WHERE entryid =  " . (int)$eps1['entryid'] . "
                                     AND property = 'ep_cache_body'";
                        if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT-UPDATE entryproperties DB:\nENTRY_ID:{$eps1['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_body) SUB-UPDATE " .DONE); }
                        serendipity_db_query($uepq1);
                    }
                    // SAME FOR ENTRYPROPERTIES CACHE for ep_cache_extended
                    $epq2 = "SELECT entryid, value
                               FROM {$serendipity['dbPrefix']}entryproperties
                              WHERE entryid = $id AND property = 'ep_cache_extended'";
                    if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_extended):ID:{$entry['id']}\n$epq2"); }
                    $eps2 = serendipity_db_query($epq2, false, 'assoc');
                    if (is_array($eps2)) {
                        $eps2['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $eps2['value']);
                        $uepq2 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                     SET value = '" . serendipity_db_escape_string($eps2['value']) . "'
                                   WHERE entryid =  " . (int)$eps2['entryid'] . "
                                     AND property = 'ep_cache_extended'";
                        if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT-UPDATE entryproperties DB:\nENTRY_ID:{$eps2['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_extended) SUB-UPDATE " .DONE); }
                        serendipity_db_query($uepq2);
                    }
                }
            }

                // SAME FOR STATICPAGES
            if ($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') {
                $sq = "SELECT id, content, pre_content
                         FROM {$serendipity['dbPrefix']}staticpages
                        WHERE content     REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'
                           OR pre_content REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'";
            } else {
                $sq = "SELECT id, content, pre_content
                         FROM {$serendipity['dbPrefix']}staticpages
                       WHERE (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')
                          OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')";
            }
            if ($debug) { $serendipity['logger']->debug("$logtag ADDITIONAL-SELECT staticpages DB::sp:\n$sq"); }
            $spages = serendipity_db_query($sq, false, 'assoc');
            if (is_array($spages)) {
                foreach($spages AS $spage) {
                    $spage['content']     = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['content']);
                    $spage['pre_content'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['pre_content']);
                    $pq = "UPDATE {$serendipity['dbPrefix']}staticpages
                              SET content = '" . serendipity_db_escape_string($spage['content']) . "' ,
                                  pre_content = '" . serendipity_db_escape_string($spage['pre_content']) . "'
                            WHERE id =  " . serendipity_db_escape_string($spage['id']);
                    if ($debug) { $serendipity['logger']->debug("$logtag ADDITIONAL-UPDATE staticpages DB:\nID:{$spage['id']} {$serendipity['dbPrefix']}staticpages::[content|pre_content] UPDATE " .DONE); }
                    serendipity_db_query($pq);
                    // count the staticpage entries changed
                    if ($_tmpStaticpID != $spage['id']) $s++;
                    $_tmpStaticpID = $spage['id'];
                }
            }
        }
        $i++;
        echo "<li>$oldthumbnail <b>converted</b> to {$serendipity['thumbSuffix']}</li>\n";
        flush();
    }
    echo "</ul>\n</span>\n";

    if ($e > 0) {
        $msg = sprintf(MEDIA_FILE_RENAME_ENTRY, $e);
        echo "<span class=\"msg_success\"><span class=\"icon-ok-circled\"></span> $msg</span>\n";
    }
    if ($s > 0) {
        $msg = str_replace('.', '', sprintf(MEDIA_FILE_RENAME_ENTRY, $s));
        echo "<span class=\"msg_success\"><span class=\"icon-ok-circled\"></span> $msg (staticpages).</span>\n";
    }
    return $i;
}

/**
 * Check all existing thumbnails if they are the right size, insert missing thumbnails
 *
 * LONG
 *
 * @access public
 * @return  int     Number of updated thumbnails
 */
function serendipity_syncThumbs($deleteThumbs = false) {
    global $serendipity;

    $i = 0;

    $files  = serendipity_fetchImages();
    $fcount = count($files);
    $_list  = '';
    $_br    = '';

    echo "\n";
    echo '<section class="media_sync_thumbs">' . "\n";
    echo '    <header><h2>' . sprintf(SYNC_OPTION_DELETETHUMBS, '') . "</h2></header>\n";

    for ($x = 0; $x < $fcount; $x++) {
        $update = array();
        $f      = serendipity_parseFileName($files[$x]);
        if (empty($f[1]) || $f[1] == $files[$x]) {
            // No extension means bad file most probably. Skip it.
            printf('    <div class="media_sync_list">' . SKIPPING_FILE_EXTENSION . "</div>\n", $files[$x]);
            continue;
        }

        $ffull   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $files[$x];
        $fthumb  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $f[0] . '.' . $serendipity['thumbSuffix'] . '.' . $f[1];
        $sThumb  = $f[0] . '.' . $serendipity['thumbSuffix'] . '.' . $f[1];
        $fbase   = basename($f[0]);
        $fdir    = dirname($f[0]) . '/';
        if ($fdir == './') {
            $fdir = '';
        }

        if (!is_readable($ffull) || filesize($ffull) == 0) {
            printf('    <div class="media_sync_list">' . SKIPPING_FILE_UNREADABLE . "</div>\n", $files[$x]);
            continue;
        }

        $ft_mime = serendipity_guessMime($f[1]);
        $fdim    = @serendipity_getimagesize($ffull, $ft_mime);

        if (!empty($_list)) {
            $_list .= '<div class="media_sync_list">' . "\n";
        }
        // If we're supposed to delete thumbs, this is the easiest place. Leave messages plain unstiled.
        if (is_readable($fthumb)) {
            if ($deleteThumbs === true) {
                if (@unlink($fthumb)) {
                    $_list .= sprintf(DELETE_THUMBNAIL, $sThumb);
                    $_br = "<br>\n";
                    $i++;
                }
            } else if ($deleteThumbs == 'checksize') {
                // Find existing thumbnail dimensions - does look redundant, but IS necessary!
                $tdim = @serendipity_getimagesize($fthumb);
                if (isset($tdim['noimage'])) {
                    // Delete it so it can be regenerated
                    if (@unlink($fthumb)) {
                        $_list .= sprintf(DELETE_THUMBNAIL, $sThumb);
                        $_br = "<br>\n";
                        $i++;
                    }
                } else {
                    // Calculate correct thumbnail size from original image
                    $expect = serendipity_calculate_aspect_size($fdim[0], $fdim[1], $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                    // Check actual thumbnail size
                    if ($tdim[0] != $expect[0] || $tdim[1] != $expect[1]) {
                        // This thumbnail is incorrect; delete it so
                        // it can be regenerated
                        if (@unlink($fthumb)) {
                            $_list .= sprintf(DELETE_THUMBNAIL, $sThumb);
                            $_br = "<br>\n";
                            $i++;
                        }
                    }
                }
            }
            // else the option is to keep all existing thumbs; do nothing.
        } // end if thumb exists

        $cond = array(
            'and' => "WHERE name = '" . serendipity_db_escape_string($fbase) . "'
                            AND path = '" . serendipity_db_escape_string($fdir) . "'
                            AND mime = '" . serendipity_db_escape_string($fdim['mime']) . "'
                            AND extension = '" . serendipity_db_escape_string($f[1]) . "'"
        );

        $cond['joins'] = ''; // init for serendipity_ACL_SQL conditionals joins
        serendipity_ACL_SQL($cond, false, 'directory');

        $rs = serendipity_db_query("SELECT *
                                      FROM {$serendipity['dbPrefix']}images AS i
                                           {$cond['joins']}
                                           {$cond['and']}", true, 'assoc');
        // Leave messages plain unstiled
        if (is_array($rs)) {

            // This image is in the database. Check our calculated data against the database data.
            $update = array();
            // Is the width correct?
            if (isset($fdim[0]) && $rs['dimensions_width'] != $fdim[0]) {
                $update['dimensions_width'] = $fdim[0];
            }

            // Is the height correct?
            if (isset($fdim[1]) && $rs['dimensions_height'] != $fdim[1]) {
                $update['dimensions_height'] = $fdim[1];
            }

            // Is the image size correct?
            if ($rs['size'] != filesize($ffull)) {
                $update['size'] = filesize($ffull);
            }

            // Does it exist and is an image and has the thumbnail suffix changed?
            $checkfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $rs['path'] . $rs['name'] . '.' . $rs['thumbnail_name'] . (empty($rs['extension']) ? '' : '.' . $rs['extension']);
            if (!file_exists($checkfile) && empty($fdim['noimage']) && file_exists($fthumb)) {
                $update['thumbnail_name'] = $serendipity['thumbSuffix'];
            }

            // Do the database update, if needed
            if (sizeof($update) != 0) {
                $_list .= $_br . sprintf(FOUND_FILE . " (<em>Update in database</em>)", $files[$x]);
                serendipity_updateImageInDatabase($update, $rs['id']);
                $i++;
            }

        } else {
            $_list .= $_br . sprintf(FOUND_FILE . " (<em>Insert in Database</em>)", $files[$x]);
            serendipity_insertImageInDatabase($fbase . '.' . $f[1], $fdir, 0, filemtime($ffull));
            $i++;
        }
        if (!empty($_list)) {
            $_list .= "\n</div>\n"; // This is the first (x=1) closing div for the last loop $x case (the first displayed item) AS WELL AS looped by (x=2; etc) all filled messages in is_readable($fthumb) added by case FOUND FILE (do database action)
        }
    }
    if (!empty($_list)) {
        echo '<div class="media_sync_list">' . "\n"; // <!-- $x --> this line is for the last looped $x item only, which then is the first matching (excluded the skipped) item in the list
        echo $_list; // this are all listed messages in is_readable($fthumb) added by case FOUND FILE (do database action)
    } else {
        echo '    <span class="msg_success"><span class="icon-ok-circled"></span> ' . DONE . ' (' . NOTHING_TODO . ').</span>' . "\n";
    }
    echo "</section>\n\n";

    return $i;
}

/**
 * Wrapper for GDLib functions
 *
 * @access public
 * @param   string      Filename to operate on
 * @return string       Name of GD function to execute
 */
function serendipity_functions_gd($infilename) {
    if (!function_exists('imagecopyresampled')) {
        return false;
    }

    $func = array();
    $inf  = pathinfo(strtolower($infilename));
    switch ($inf['extension']) {
    case 'gif':
        $func['load'] = 'imagecreatefromgif';
        $func['save'] = 'imagegif';
        $func['qual'] = 100;
        break;

    case 'jpeg':
    case 'jpg':
    case 'jfif':
        $func['load'] = 'imagecreatefromjpeg';
        $func['save'] = 'imagejpeg';
        $func['qual'] = 100;
        break;

    case 'png':
        $func['load'] = 'imagecreatefrompng';
        $func['save'] = 'imagepng';
        $func['qual'] = 9;
        break;

    default:
        return false;
    }

    /* If our loader does not exist, we are doomed */
    if (!function_exists($func['load'])) {
        return false;
    }

    /* If the save function does not exist (i.e. read-only GIF), we want to output it as PNG */
    if (!function_exists($func['save'])) {
        if (function_exists('imagepng')) {
            $func['save'] = 'imagepng';
        } else {
            return false;
        }
    }

    return $func;
}

/**
 * Rotate an image (GDlib)
 *
 * @access public
 * @param   string      Source Filename to rotate
 * @param   string      Target file
 * @param   int         Degrees to rotate
 * @return  array       New width/height of the image
 */
function serendipity_rotate_image_gd($infilename, $outfilename, $degrees)
{
    $func = serendipity_functions_gd($infilename);
    if (!is_array($func)) {
        return false;
    }

    $in        = $func['load']($infilename);

    $out       = imagerotate($in, $degrees, 0);
    $func['save']($out, $outfilename, $func['qual']);

    $newwidth  = imagesx($out);
    $newheight = imagesy($out);

    $out       = null;
    $in        = null;

    return array($newwidth, $newheight);
}

/**
 * Resize an image (GDLib)
 *
 * @access public
 * @param   string      Source Filename to resize
 * @param   string      Target file
 * @param   int         New width
 * @return  int         New height (can be autodetected)
 * @return  array       New image size
 */
function serendipity_resize_image_gd($infilename, $outfilename, $newwidth, $newheight=null)
{
    $func = serendipity_functions_gd($infilename);
    if (!is_array($func)) {
        return false;
    }

    try {
        // if an image exist that can not be loaded (invalid GIF for example), the page shall still be rendered
        $in = $func['load']($infilename);
    } catch (Throwable $t) {
        // Executed only in PHP 7, will not match in PHP 5.x
        echo 'Could not create thumbnail: ',  $t->getMessage(), "\n";
        return false;
    } catch (Exception $e) {
        // Executed only in PHP 5.x, will not be reached in PHP 7
        echo 'Could not create thumbnail: ',  $e->getMessage(), "\n";
        return false;
    }
    $width  = imagesx($in);
    $height = imagesy($in);

    if (is_null($newheight)) {
        $newsizes  = serendipity_calculate_aspect_size($width, $height, $newwidth, 'width');
        $newwidth  = $newsizes[0];
        $newheight = $newsizes[1];
    }

    if (is_null($newwidth)) {
        $newsizes  = serendipity_calculate_aspect_size($width, $height, $newheight, 'height');
        $newwidth  = $newsizes[0];
        $newheight = $newsizes[1];
    }

    $out = imagecreatetruecolor($newwidth, $newheight);

    /* Attempt to copy transparency information, this really only works for PNG */
    if (function_exists('imagesavealpha')) {
        imagealphablending($out, false);
        imagesavealpha($out, true);
    }

    imagecopyresampled($out, $in, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    @umask(0000);
    touch($outfilename); // safe_mode requirement
    $func['save']($out, $outfilename, $func['qual']);
    @chmod($outfilename, 0664);
    $out = null;
    $in  = null;

    return array($newwidth, $newheight);
}

/**
 * Calculate new size for an image, considering aspect ratio and constraint
 *
 * @access public
 * @param   int     Image width
 * @param   int     Image height
 * @param   int     Target dimension size
 * @param   string  Dimension to constrain ('width', 'height', 'largest',
 *                  'smallest'; defaults to original behavior, 'largest')
 * @return  array   An array with the scaled width and height
 */
function serendipity_calculate_aspect_size($width, $height, $size, $constraint = null) {

    // Allow for future constraints (idea: 'percent')
    $known_constraints = array('width', 'height', 'largest', 'smallest');

    // Re-arrange params for calls from old imageselectorplus plugin
    if ($size == null) {
      $size       = $constraint;
      $constraint = 'smallest';
    }

    // Normalize relative constraint types
    if ($constraint == 'largest' || !in_array($constraint, $known_constraints)) {
        // Original default behavior, included for backwards compatibility
        // Constrains largest dimension
        if ($width >= $height) {
            $constraint = 'width';
        } else {
            $constraint = 'height';
        }
    } else if ($constraint == 'smallest') {
        // Only ever called from imageselectorplus plugin, included for
        // backwards compatibility with its older versions
        if ($width >= $height) {
            $constraint = 'height';
        } else {
            $constraint = 'width';
        }
    }

    // Constraint is now definitely one of the known absolute types,
    // either 'width' or 'height'
    if ($constraint == 'height') {
        // Is the image big enough to resize?
        if ($height > $size) {
            // Calculate new size
            $ratio    = $width / $height;
            $newwidth = round($size * $ratio);
            // Limit calculated dimension to at least 1px
            if ($newwidth <= 0) {
                $newwidth = 1;
            }
            $newsize = array($newwidth, $size);
        } else {
            // Image is too small to be resized; use original dimensions
            $newsize = array($width, $height);
        }
    } else {
        // Default constraint is width
        if ($width > $size) {
            // Image is big enough to resize
            $ratio = $height / $width;
            $newheight = round($size * $ratio);
            // Limit calculated dimension to at least 1px
            if ($newheight <= 0) {
                $newheight = 1;
            }
            $newsize = array($size, $newheight);
        } else {
            // Do not scale small images
            $newsize = array($width, $height);
        }
    }

    return $newsize;
}

/**
 * Display the list of images in our database
 *
 * @access public
 * @param   int     The current page number
 * @param   string  The HTML linebreak to use after a row of images
 * @param   boolean Is this the ML-Version for managing everything (true), or is it about selecting one image for the editor? (false)
 * @param   string  The URL to use for pagination
 * @param   boolean Show the "upload media item" feature?
 * @param   boolean Restrict viewing images to a specific directory
 * @param   array   Map of Smarty vars transported into all following templates
 * @return  string  Generated HTML
 */
function serendipity_displayImageList($page = 0, $lineBreak = NULL, $manage = false, $url = NULL, $show_upload = false, $limit_path = NULL, $smarty_vars = array()) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger
    if ($debug) {
        $logtag = 'ML-LIST:';
        $trace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START ML serendipity_displayImageList SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    $extraParems     = serendipity_generateImageSelectorParems();
    $hideSubdirFiles = (isset($serendipity['GET']['hideSubdirFiles']) && $serendipity['GET']['hideSubdirFiles'] == 'yes') ? true : false; // default
    $userPerms       = array('delete' => serendipity_checkPermission('adminImagesDelete'));

    $displayGallery  = (isset($serendipity['GET']['showGallery']) && !$show_upload && $serendipity['GET']['showGallery'] == 'true') ? true : false;
    // displayGallery uses hideSubdirFiles (a directory items only list), without cookie remembrance!
    if ($displayGallery) {
        $serendipity['GET']['sortorder']['perpage'] = 48; // Set to 6 items per row x 8 rows as a hardcoded maximum per directory view
        $serendipity['GET']['hideSubdirFiles'] = 'yes'; // Definitely YES! 'The site maintainer has get to know that it is better to split up media directories with more than 48 items
    }

    $serendipity['GET']['only_path'] = serendipity_uploadSecure($limit_path . $serendipity['GET']['only_path'], true);
    if (isset($serendipity['GET']['filter']['i.name'])) {
        $serendipity['GET']['filter']['i.name'] = serendipity_specialchars(str_replace(array('*', '?'), array('%', '_'), $serendipity['GET']['filter']['i.name']));
    }

    $perPage = (!empty($serendipity['GET']['sortorder']['perpage']) ? (int)$serendipity['GET']['sortorder']['perpage'] : 8);
    while ($perPage % $lineBreak !== 0) {
        $perPage++;
    }
    $start = ($page-1) * $perPage;

    if ($manage && $limit_path == NULL) {
        ## SYNC START ##
        $aExclude = array('CVS' => true, '.svn' => true, '_vti_cnf' => true); // _vti_cnf to exclude possible added servers frontpage extensions
        serendipity_plugin_api::hook_event('backend_media_path_exclude_directories', $aExclude);
        $paths        = array();
        $aFilesOnDisk = array();
        $aFilesNoSync = array();
        $aResultSet   = serendipity_traversePath(
            $serendipity['serendipityPath'] . $serendipity['uploadPath']. $limit_path,
            '',
            false,
            NULL,
            1,
            NULL,
            FALSE,
            $aExclude
        );
        foreach($aResultSet AS $sKey => $sFile) {
            if ($sFile['directory']) {
                if ($debug) { $serendipity['logger']->debug("$logtag {$sFile['relpath']} is a directory."); }
                array_push($paths, $sFile);
            } else {
                if ($debug) { $serendipity['logger']->debug("$logtag {$sFile['relpath']} is a file."); }
                if ($sFile['relpath'] == '.empty' || false !== strpos($sFile['relpath'], '.quickblog.')) {
                    if ($sFile['relpath'] != '.empty' && @!in_array($sFile['relpath'], (array)$serendipity['aFilesNoSync'])) {
                        if ($debug) { $serendipity['logger']->debug("$logtag Found aFilesNoSync = {$sFile['relpath']}."); }
                        $path_parts = pathinfo($sFile['relpath']);
                        $fdim = @serendipity_getimagesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sFile['relpath'], '', $path_parts['extension']);
                        $aFilesNoSync[$sFile['relpath']] = array(
                            'dirname'   => $path_parts['dirname'],
                            'basename'  => $path_parts['basename'],
                            'filename'  => $path_parts['filename'],
                            'pfilename' => str_replace('.quickblog', '', $path_parts['filename']),
                            'extension' => $path_parts['extension'],
                            'filesize'  => @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sFile['relpath']),
                            'url'       => $serendipity['baseURL'] . $serendipity['uploadPath'] . $sFile['relpath'],
                            'fdim'      => $fdim,
                            'width'     => $fdim[0],
                            'height'    => $fdim[1],
                            'mime'      => $fdim['mime'],
                        ); // store this in a cache file to use later (we use $serendipity['aFilesNoSync'] for this currently)
                    }
                    // This is a sized serendipity thumbnail or ranged "~outside" ML (see imageselectorplus event plugin), skip it!
                    continue;
                }
                // Store the file in our array, remove any ending slashes
                $aFilesOnDisk[$sFile['relpath']] = 1;
            }
            unset($aResultSet[$sKey]);
        }

        usort($paths, 'serendipity_sortPath');

        if ($debug) { $serendipity['logger']->debug("$logtag Got real disc files: " . print_r($aFilesOnDisk, 1)); }
        $serendipity['current_image_hash'] = md5(serialize($aFilesOnDisk));

        // ML Cleanup START - is part of SYNC
        // MTG 21/01/06: request all images from the database, delete any which don't exist
        // on the filesystem, and mark off files from the file list which are already
        // in the database

        $nTimeStart = microtime_float();
        $nCount = 0;

        if ($debug) { $serendipity['logger']->debug("$logtag Image-Sync has perm: " . serendipity_checkPermission('adminImagesSync') . ", Onthefly Sync: {$serendipity['onTheFlySynch']}, Hash: " . ($serendipity['current_image_hash']!=$serendipity['last_image_hash']?"uneven, cleanup":"even, skip cleanup")); }

        if ($serendipity['onTheFlySynch'] && serendipity_checkPermission('adminImagesSync')
        && isset($serendipity['last_image_hash']) && $serendipity['current_image_hash'] != $serendipity['last_image_hash']) {
            $aResultSet = serendipity_db_query("SELECT id, name, extension, thumbnail_name, path, hotlink
                                                  FROM {$serendipity['dbPrefix']}images", false, 'assoc');

            if ($debug) { $serendipity['logger']->debug("$logtag Got images: " . print_r($aResultSet, 1)); }

            if (is_array($aResultSet)) {
                $msgdelfile = array();
                foreach($aResultSet AS $sKey => $sFile) {
                    serendipity_plugin_api::hook_event('backend_thumbnail_filename_select', $sFile); // unknown usage anywhere -> $sFile['thumbnail_filename']
                    $sThumbNailFile = '';
                    if (isset($sFile['thumbnail_filename'])) {
                        $sThumbNailFile = $sFile['thumbnail_filename'];
                    } else {
                        // avoid non existing thumbs, eg. pdf files without ImageMagick/ghostscript captured thumb preview
                        if (!empty($sFile['thumbnail_name'])) {
                            $sThumbNailFile = $sFile['path'] . $sFile['name'] . '.' . $sFile['thumbnail_name'] . (empty($sFile['extension']) ? '' : '.' . $sFile['extension']);
                        }
                    }

                    if ($sFile['hotlink']) {
                        $sFileName = $sFile['path'];
                        $sThumbNailFile = '';
                    } else {
                        $sFileName = $sFile['path'] . $sFile['name'] . (empty($sFile['extension']) ? '' : '.' . $sFile['extension']);
                    }

                    if ($debug) { $serendipity['logger']->debug("$logtag File name is $sFileName, thumbnail is $sThumbNailFile"); }

                    unset($aResultSet[$sKey]);

                    // check existing realFiles against remaining files without any reference to cleanup
                    if (isset($aFilesOnDisk[$sFileName])) {
                        unset($aFilesOnDisk[$sFileName]);
                    } else {
                        if (!$sFile['hotlink']) {
                            if ($debug) { $serendipity['logger']->debug("$logtag Deleting Image {$sFile['id']}"); }

                            $msgdelfile[] = serendipity_deleteImage($sFile['id']);
                            ++$nCount;
                        }
                    }
                    unset($aFilesOnDisk[$sThumbNailFile]);
                }
                if (count($msgdelfile) > 0) {
                    echo "<h3>MediaLibrary Cleanup:</h3>";
                    echo '<ul class="plainList">'."\n";
                    foreach ($msgdelfile AS $f) { echo "<li>$f</li>\n"; }
                    echo "</ul>\n";
                }
            }

            if ($nCount > 0) {
                if ($debug) { $serendipity['logger']->debug("$logtag Cleaned up $nCount database entries"); }
            }

            serendipity_set_config_var('last_image_hash', $serendipity['current_image_hash'], 0);
            $aUnmatchedOnDisk = array_keys($aFilesOnDisk);

            if ($debug) { $serendipity['logger']->debug("$logtag Got unmatched files: " . print_r($aUnmatchedOnDisk, 1)); }

            $nCount = 0;
            foreach($aUnmatchedOnDisk AS $sFile) {
                if (preg_match('@\.' . $serendipity['thumbSuffix'] . '\.@', $sFile)) {
                    if ($debug) { $serendipity['logger']->debug("$logtag Skipping thumbnailed file $sFile"); }
                    continue;
                } else {
                    if ($debug) { $serendipity['logger']->debug("$logtag Checking $sFile"); }
                }

                // MTG: 21/01/06: put files which have just 'turned up' into the database
                $aImageData = serendipity_getImageData($sFile);
                if (serendipity_isImage($aImageData, false, '(image)|(video)|(audio)/')) {
                    $nPos = strrpos($sFile, '/');
                    if (is_bool($nPos) && !$nPos) {
                       $sFileName  = $sFile;
                       $sDirectory = '';
                    } else {
                       ++$nPos;
                       $sFileName  = substr($sFile, $nPos);
                       $sDirectory = substr($sFile, 0, $nPos);
                    }
                    if ($debug) { $serendipity['logger']->debug("$logtag Inserting image $sFileName from $sDirectory" . print_r($aImageData, 1) . "\ninto database"); }
                    # TODO: Check if the thumbnail generation goes fine with Marty's code
                    serendipity_makeThumbnail($sFileName, $sDirectory);
                    serendipity_insertImageInDatabase($sFileName, $sDirectory);
                    ++$nCount;
                }
            }

            if ($nCount > 0) {
                if ($debug) { $serendipity['logger']->debug("$logtag Inserted $nCount images into the database"); }
            }
        } else {
            if ($debug) { $serendipity['logger']->debug("$logtag Media Gallery database is up to date"); }
        }

         /*
         $nTimeEnd = microtime_float ( );
         $nDifference = $nTimeEnd - $nTimeStart;
         echo "<p> total time taken was $nDifference </p>\n";
        */
        ## SYNC FINISHED ##
    }

    // Out of Sync Files
    if (!isset($aFilesNoSync)) $aFilesNoSync = array();
    if ($debug) { $serendipity['logger']->debug("$logtag ".print_r($aFilesNoSync,1)); }
    $serendipity['aFilesNoSync'] = $aFilesNoSync;
    $serendipity['smarty']->assign('imagesNoSync', $aFilesNoSync);

    ## Apply ACL afterwards:
    serendipity_directoryACL($paths, 'read');

    // set filters (first part of serendipity_showMedia() remember filter settings for SetCookie ~1450)
    // set remember filter settings for SetCookie
    if (!isset($serendipity['GET']['filter'])) {
        serendipity_restoreVar($serendipity['COOKIE']['filter'], $serendipity['GET']['filter']);
    }

    // If NO filters are required, we still have an empty filter 'i.name' set, (*)
    // which forces serendipity_fetchImagesFromDatabase() to run some extra SQL query parts that are not in need!
    // Since that helps a lot, we just additionally check "simple" (white field) i.filters (image media files, which is 'date', 'size', 'dimensions' and 'name')
    // and the default complex array of file category + full i.filters + simple default bp.filter (media base-properties metadata fields)
    // that both can appear during ML paging.
    // [ NOTE: KEEP after the Cookie restoring! ]
    //  [*] This and other filled array behaviour is probably ported by one of these lazy GET/COOKIE/GET remember requests, which, potentially doubled, is a mix of old and current session filter data.
    if ($serendipity['GET']['filter'] == array('i.name' => '')
        || $serendipity['GET']['filter'] == array('i.date' => array('from' => '', 'to' => '',), 'i.size' => array('from' => '', 'to' => '',), 'i.dimensions_width' => array('from' => '', 'to' => '',), 'i.dimensions_height' => array('from' => '', 'to' => '',), 'i.name' => '',)
        || $serendipity['GET']['filter'] == array('fileCategory' => '', 'i.date' => array('from' => '', 'to' => '',), 'i.name' => '', 'i.authorid' => '', 'i.extension' => '', 'i.size' => array('from' => '', 'to' => '',), 'i.dimensions_width' => array('from' => '', 'to' => '',), 'i.dimensions_height' => array('from' => '', 'to' => '',), 'bp.DPI' => '', 'bp.COPYRIGHT' => '', 'bp.TITLE' => '', 'bp.COMMENT1' => '', 'bp.COMMENT2' => '',)
    ) {
        unset($serendipity['GET']['filter']); // sets NIL to any filters, which is the default and ML startover, before done any user filtering or ML All/IMAGE/VIDEO structure requests.
    }

    if ($displayGallery) {
        // don't touch cookie and normal settings, but hard set in case of gallery usage
        $serendipity['GET']['filter']['fileCategory'] = 'image'; // filter restrict to mime part 'image/%' only!
        $hideSubdirFiles = true; // Definitely YES!
    }
    $serendipity['imageList'] = serendipity_fetchImagesFromDatabase(
                                  $start,
                                  $perPage,
                                  $totalImages, // Passed by ref
                                  (isset($serendipity['GET']['sortorder']['order']) ? $serendipity['GET']['sortorder']['order'] : false),
                                  (isset($serendipity['GET']['sortorder']['ordermode']) ? $serendipity['GET']['sortorder']['ordermode'] : false),
                                  (isset($serendipity['GET']['only_path']) ? $serendipity['GET']['only_path'] : ''),
                                  null,
                                  (isset($serendipity['GET']['keywords']) ? $serendipity['GET']['keywords'] : ''),
                                  (isset($serendipity['GET']['filter']) ? $serendipity['GET']['filter'] : null),
                                  $hideSubdirFiles
    );

    $pages         = ceil($totalImages / $perPage);
    $linkPrevious  = '?' . $extraParems . '&amp;serendipity[page]=' . ($page-1);
    $linkNext      = '?' . $extraParems . '&amp;serendipity[page]=' . ($page+1);
    // Keep the inner to be build first. Now add first and last. Has to do with adding $param to $extraParems.
    $linkFirst     = '?' . $extraParems . '&amp;serendipity[page]=' . 1;
    $linkLast      = '?' . $extraParems . '&amp;serendipity[page]=' . $pages;
    if (is_null($lineBreak)) {
        $lineBreak = floor(750 / ($serendipity['thumbSize'] + 20));
    }

    $dprops = $keywords = array();
    if (isset($serendipity['parseMediaOverview']) && $serendipity['parseMediaOverview']) { // $serendipity['parseMediaOverview'] is either an undocumented user feature, or a development leftover since prior to 2006, or an unknown and unofficial plugin feature
        $ids = array();
        foreach($serendipity['imageList'] AS $k => $file) {
            $ids[] = $file['id'];
        }
        $allprops =& serendipity_fetchMediaProperties($ids);
    }

    if (count($serendipity['imageList']) > 0) {
        foreach($serendipity['imageList'] AS $k => $file) {
            if (!($serendipity['authorid'] == $file['authorid'] || $file['authorid'] == '0' || serendipity_checkPermission('adminImagesViewOthers'))) {
                // This is a fail-safe continue. Basically a non-matching file should already be filtered in SQL.
                // Ahem.., the word-of-day is "should" here..., see L 169 which conditions the SQL query
                continue;
            }

            serendipity_prepareMedia($serendipity['imageList'][$k], $url);

            if (isset($serendipity['parseMediaOverview']) && $serendipity['parseMediaOverview']) { // see above note
                $serendipity['imageList'][$k]['props'] =& $allprops[$file['id']];
                if (!is_array($serendipity['imageList'][$k]['props']['base_metadata'])) {
                    $serendipity['imageList'][$k]['metadata'] =& serendipity_getMetaData($serendipity['imageList'][$k]['realfile'], $serendipity['imageList'][$k]['header']);
                } else {
                    $serendipity['imageList'][$k]['metadata'] = $serendipity['imageList'][$k]['props']['base_metadata'];
                    serendipity_plugin_api::hook_event('media_getproperties_cached', $serendipity['imageList'][$k]['metadata'], $serendipity['imageList'][$k]['realfile']);
                }
                serendipity_parseMediaProperties($dprops, $keywords, $serendipity['imageList'][$k], $serendipity['imageList'][$k]['props'], 3, false);
            }
        }
    }

    $smarty_vars = array_merge($smarty_vars, array(
        'limit_path'    => $limit_path,
        'perPage'       => $perPage,
        'show_upload'   => $show_upload,
        'perms'         => $userPerms,
        'page'          => $page,
        'pages'         => $pages,
        'linkFirst'     => $linkFirst,
        'linkNext'      => $linkNext,
        'linkPrevious'  => $linkPrevious,
        'linkLast'      => $linkLast,
        'extraParems'   => $extraParems,
        'totalImages'   => $totalImages
    ));

    return serendipity_showMedia(
        $serendipity['imageList'],
        $paths,
        $url,
        $manage,
        $lineBreak,
        true,
        $smarty_vars
    );
} // End serendipity_displayImageList()

/**
 * Gather the URL-parameters needed when generating the ML to select an image to add to the editor,
 * to store the relevant options (eg. like, which textarea to add it to)
 *
 * @param   string  URL or Form format
 */
function serendipity_generateImageSelectorParems($format = 'url') {
    global $serendipity;

    $sortParams   = array('perpage', 'order', 'ordermode');
    $importParams = array('adminModule', 'htmltarget', 'filename_only', 'textarea', 'subpage',  'keywords', 'noBanner', 'noSidebar', 'noFooter', 'showUpload','showMediaToolbar');
    $extraParems  = '';
    $filterParams = isset($serendipity['GET']['filter']) ? $serendipity['GET']['filter'] : array();

    $standaloneFilterParams = array('only_path');
    $parems = array();

    foreach($importParams AS $importParam) {
        if (isset($serendipity['GET'][$importParam])) {
            $parems['serendipity[' . $importParam . ']'] = $serendipity['GET'][$importParam];
        }
    }

    foreach($sortParams AS $sortParam) {
        serendipity_restoreVar($serendipity['COOKIE']['sortorder_' . $sortParam], $serendipity['GET']['sortorder'][$sortParam]);
        $parems['serendipity[sortorder]['. $sortParam .']'] = $serendipity['GET']['sortorder'][$sortParam];
    }

    foreach($standaloneFilterParams AS $filterParam) {
        serendipity_restoreVar($serendipity['COOKIE'][$filterParam], $serendipity['GET'][$filterParam]);
        if (!empty($serendipity['GET'][$filterParam]) && $serendipity['GET'][$filterParam] != 'undefined') {
            $parems['serendipity[' . $filterParam . ']'] = $serendipity['GET'][$filterParam];
        }
    }

    foreach($filterParams AS $filterParam => $filterValue) {
        serendipity_restoreVar($serendipity['COOKIE']['filter'][$filterParam], $serendipity['GET']['filter'][$filterParam]);
        if (!empty($serendipity['GET']['filter'][$filterParam]) && $serendipity['GET']['filter'][$filterParam] != 'undefined') {
            if (is_array($filterValue)) {
                foreach($filterValue AS $key => $value) {
                    $parems['serendipity[filter][' . $filterParam . '][' . $key . ']'] = $value;
                }
            } else {
                $parems['serendipity[filter][' . $filterParam . ']'] = $filterValue;
            }
        }
    }

    foreach($parems AS $param => $value) {
        if (empty(trim($value))) continue;
        if ($format == 'form') {
            $extraParems .= '<input type="hidden" name="'. $param .'" value="'. serendipity_specialchars($value) .'">'."\n";
        } else {
            $extraParems .= $param.'='. serendipity_specialchars($value) .'&amp;';
        }
    }

    return preg_replace("/&amp;$/", '', $extraParems);
}

/**
 * Check if a media item is an image
 *
 * @access public
 * @param   array       File information
 * @param   boolean     Use a strict check that does not list PDFs as an image?
 * @return  boolean     True if the file is an image
 */
function serendipity_isImage(&$file, $strict = false, $allowed = 'image/') {
    global $serendipity;

    $file['displaymime'] = $file['mime'];

    // Strip HTTP path out of imgsrc
    $file['location'] = !$file['hotlink'] ? $serendipity['serendipityPath'] . preg_replace('@^(' . preg_quote($serendipity['serendipityHTTPPath']) . ')@i', '', @$file['imgsrc']) : '';

    // File is PDF -> Thumb is PNG
    // Detect PDF thumbs
    if ($file['mime'] == 'application/pdf' && file_exists($file['location'] . '.png') && $strict == false) {
        $file['imgsrc']     .= '.png';
        $file['displaymime'] = 'image/png';
    }

    return preg_match('@' . $allowed . '@i', $file['displaymime']);
}

/**
 * Recursively delete a directory tree
 *
 * @access public
 * @param   string      The originating directory
 * @param   string      The subdirectory
 * @param   boolean     Force deleting an directory even if there are files left in it?
 * @return true
 */
function serendipity_killPath($basedir, $directory = '', $forceDelete = false) {
    static $serious = true;

    if ($handle = @opendir($basedir . $directory)) {
        while (false !== ($file = @readdir($handle))) {
            if ($file != '.' && $file != '..') {
                if (is_dir($basedir . $directory . $file)) {
                    serendipity_killPath($basedir, $directory . $file . '/', $forceDelete);
                } else {
                    $filestack[$file] = $directory . $file;
                }
            }
        }
        @closedir($handle);

        echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . sprintf(CHECKING_DIRECTORY, $directory) . "</span>\n";

        // No, we just don't kill files the easy way. We sort them out properly from the database
        // and preserve files not entered therein.
        $files = serendipity_fetchImagesFromDatabase(0, 0, $total, false, false, $directory);
        if (is_array($files)) {
            echo '<ul class="plainList">'."\n";
            foreach($files AS $f => $file) {
                echo "<li>\n";
                if ($serious) {
                    echo serendipity_deleteImage($file['id']);
                } else {
                    echo $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
                }
                echo "</li>\n";

                unset($filestack[$file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension'])]);
                unset($filestack[$file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension'])]);
            }
            echo "</ul>\n";
        }

        if (count($filestack) > 0) {
            if ($forceDelete) {
                echo '<ul class="plainList">'."\n";
                foreach($filestack AS $f => $file) {
                    if ($serious && @unlink($basedir . $file)) {
                        printf('<li><span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DELETING_FILE . ' ' . DONE . "</span></li>\n", $file);
                    } else {
                        printf('<li><span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . DELETING_FILE . ' ' . ERROR . "</span></li>\n", $file);
                    }
                }
                echo "</ul>\n";
            } else {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_DIRECTORY_NOT_EMPTY . "</span>\n";
                echo "<ul>\n";
                foreach($filestack AS $f => $file) {
                    echo "<li>$file</li>\n";
                }
                echo "</ul>\n";
            }
        }

        if ($serious && !empty($directory) && !preg_match('@^.?/?$@', $directory) && @rmdir($basedir . $directory)) {
            echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(DIRECTORY_DELETE_SUCCESS, $directory) . "</span>\n";
        } else {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(DIRECTORY_DELETE_FAILED, $directory) . "</span>\n";
        }
    }

    return true;
}

/**
 * Recursively walk a directory tree
 *
 *
 * @access public
 * @param   string      The core directory
 * @param   string      The subdirectory
 * @param   boolean     Only return directories instead of files as well?
 * @param   string      A regexp pattern to include files
 * @param   int         Level of nesting (recursive use)
 * @param   int         The maximum level of nesting (recursive use)
 * @param   mixed       Toggle whether to apply serendipity_directoryACL (false / 'read' / 'write')
 * @param   array       An array of directories to skip [passed by plugins, for example]
 * @return  array       Array of files/directories
 */
function serendipity_traversePath($basedir, $dir='', $onlyDirs = true, $pattern = NULL, $depth = 1, $max_depth = NULL, $apply_ACL = false, $aExcludeDirs = NULL) {

    if ($aExcludeDirs === null) {
        // add _vti_cnf to exclude possible added servers frontpage extensions - deprecated and remove in future  since that is OLD!
        // add CKEditors .thumb dir to exclude, since no hook
        $aExcludeDirs = array('CVS' => true, '.svn' => true, '.thumbs' => true, '_vti_cnf' => true, '.git' => true);
    }

    $odir = serendipity_dirSlash('end', $basedir) . serendipity_dirSlash('end', $dir);
    $dh = @opendir($odir);
    if (!$dh) {
        return array();
    }

    $files = array();
    while (($file = @readdir($dh)) !== false) {
        if ($file != '.' && $file != '..') {
            $bPatternMatch = (is_null($pattern) || preg_match($pattern, $file));
            $sFullPath     = $odir . $file;
            $bIsDir        = is_dir($sFullPath);
            if ($onlyDirs === false || $bIsDir) {
                if ($bPatternMatch &&
                    (!$bIsDir || $aExcludeDirs == null || !isset($aExcludeDirs[$file]))) {
                    $files[] = array(
                        'name'      => $file,
                        'depth'     => $depth,
                        'relpath'   => ltrim(str_replace('\\', '/', serendipity_dirSlash('end', $dir)) . basename($file) . ($bIsDir ? '/' : ''), '/'),
                        'directory' => $bIsDir
                    );
                }
            }

            if ($bIsDir &&
                    ($max_depth === null || $depth < $max_depth) &&
                    ($aExcludeDirs == null || !isset($aExcludeDirs[$file]))) {
                $next_dir = serendipity_dirSlash('end', $dir) . basename($file);
                $files    = array_merge($files, serendipity_traversePath($basedir, $next_dir, $onlyDirs, $pattern, ($depth+1), $max_depth, $apply_ACL, $aExcludeDirs));
            }
        }
    }

    @closedir($dh);

    if ($depth == 1 && $apply_ACL !== FALSE) {
        serendipity_directoryACL($files, $apply_ACL);
    }

    return $files;
}

/**
 * Custom usort() function that properly sorts a path
 *
 * @access public
 * @param   array      First array
 * @param   array      Second array
 * @return
 */
function serendipity_sortPath($a, $b) {
    return strcasecmp($a['relpath'], $b['relpath']);
}

/**
 * Delete a directory with all its files
 *
 * @access public
 * @param   string      The directory to delete
 * @return
 */
function serendipity_deletePath($dir) {
    $d = dir($dir);
    if ($d) {
        while ($f = $d->read()) {
            if ($f != '.' && $f != '..') {
                if (is_dir($dir . $f)) {
                    serendipity_deletePath($dir . $f . '/');
                    rmdir($dir . $f);
                }

                if (is_file($dir . $f)) {
                    unlink($dir . $f);
                }
            }
        }

        $d->close();
    }
}

/**
 * Transform a filename into a valid upload path
 *
 * @access public
 * @param   string      The input filename
 * @param   boolean     Shall all paths be stripped?
 * @param   boolean     Shall a trailing slash be appended?
 * @return  string      The valid filename
 */
function serendipity_uploadSecure($var, $strip_paths = true, $append_slash = false) {

    $var = str_replace(' ', '_', $var);
    $var = preg_replace('@[^0-9a-z\._/-]@i', '', $var);
    if ($strip_paths) {
        $var = preg_replace('@(\.+[/\\\\]+)@', '/', $var);
    }

    $var = preg_replace('@^(/+)@', '', $var);

    if ($append_slash) {
        if (!empty($var) && substr($var, -1, 1) != '/') {
            $var .= '/';
        }
    }

    return $var;
}

/**
 * Get the image size for a file
 *
 * @access public
 * @param   string      The filename of the image
 * @param   string      The mimetype of an image (can be autodetected)
 * @param   string      The file extension of an image
 * @return  array       The width/height of the file
 */
function serendipity_getimagesize($file, $ft_mime = '', $suf = '') {
    if (empty($ft_mime) && !empty($suf)) {
        $ft_mime = serendipity_guessMime($suf);
    }

    if ($ft_mime == 'application/pdf') {
        $fdim = array(1000,1000,24, '', 'bits'=> 24, 'channels' => '3', 'mime' => 'application/pdf');
    } else {
        $fdim = @getimagesize($file);
    }

    if (is_array($fdim)) {
        if (empty($fdim['mime'])) {
            $fdim['mime'] = $ft_mime;
        }

        if ($fdim['mime'] == 'image/vnd.wap.wbmp' && $ft_mime == 'video/x-quicktime') {
            // PHP Versions prior to 4.3.9 reported .mov files wrongly as WAP. Fix this and mark the file as 'non-image' with 0x0 dimensions
            $fdim['mime'] = $ft_mime;
        }
    } else {
        // The file is no image. Return a fake array so that files are inserted (but without a thumb)
        $fdim = array(
            0         => 0,
            1         => 0,
            'mime'    => $ft_mime,
            'noimage' => true
        );
    }

    return $fdim;
}

/**
 * Get the available fields of the media database
 *
 * @access public
 * @return array    Array with available, sortable fields
 */
function serendipity_getImageFields() {
    global $serendipity;

    if (isset($serendipity['simpleFilters']) && $serendipity['simpleFilters'] !== false) {
        $x = array(
            'i.date'              => array('desc' => SORT_ORDER_DATE,
                                         'type' => 'date'
                                   ),

            'i.name'              => array('desc' => SORT_ORDER_NAME
                                   ),

        );

    } else {
        $x = array(
            'i.date'              => array('desc' => SORT_ORDER_DATE,
                                         'type' => 'date'
                                   ),

            'i.name'              => array('desc' => SORT_ORDER_NAME
                                   ),

            'i.authorid'          => array('desc' => AUTHOR,
                                         'type' => 'authors'
                                   ),

            'i.extension'         => array('desc' => SORT_ORDER_EXTENSION
                                   ),

            'i.size'              => array('desc' => SORT_ORDER_SIZE,
                                         'type' => 'intrange'
                                   ),

            'i.dimensions_width'  => array('desc' => SORT_ORDER_WIDTH,
                                         'type' => 'intrange'
                                   ),

            'i.dimensions_height' => array('desc' => SORT_ORDER_HEIGHT,
                                         'type' => 'intrange'
                                   )
        );

        $addProp = explode(';', $serendipity['mediaProperties']);
        foreach($addProp AS $prop) {
            $parts = explode(':', $prop);
            $name  = $parts[0];
            $x['bp.' . $name] = array('desc' => (defined('MEDIA_PROPERTY_' . $name) ? constant('MEDIA_PROPERTY_' . $name) : serendipity_specialchars($name)));
            if (preg_match('@date@i', $name)) {
                $x['bp.' . $name]['type'] = 'date';
            }
            if (preg_match('@length@i', $name)) {
                $x['bp.' . $name]['type'] = 'intrange';
            }
            if (preg_match('@dpi@i', $name)) {
                $x['bp.' . $name]['type'] = 'int';
            }
        }
    }

    return $x;
}

/**
 * Escape a shell argument for ImageMagick use
 *
 * @access public
 * @param   string  Input argument
 * @return  string  Output argument
 */
function serendipity_escapeshellarg($string) {
    return escapeshellarg(str_replace('%', '', $string));
}

/**
 * Makes sure a directory begins with or ends with a "/"
 *
 * @access public
 * @param   string  Type of where to append/prepend slash ('end', 'start', 'both')
 * @param   string  Directory name
 * @return  string  Output argument
 */
function serendipity_dirSlash($type, $dir) {

    if ($dir == '') {
        return $dir;
    }

    if ($type == 'start' || $type == 'both') {
        if (substr($dir, 0, 1) != '/') {
            $dir = '/' . $dir;
        }
    }

    if ($type == 'end' || $type == 'both') {
        if (substr($dir, -1) != '/') {
            $dir .= '/';
        }
    }

    return $dir;
}

/**
 * Cycle a serendipity_traversePath result-set and apply read/write ACLs.
 *
 * @access public
 * @param   array   serendipity_traversePath result array
 * @param   string  ACL type ('read', 'write')
 */
function serendipity_directoryACL(&$paths, $type = 'read') {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger
    if ($debug) {
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START serendipity_directoryACL SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("Applying ACL for mode '$type'.");
    }

    if (!is_array($paths)) {
        return true;
    }

    $startCount = count($paths);
    if (!isset($serendipity['enableACL']) || $serendipity['enableACL'] == true) {
        // Check if we are a cool superuser. Bail out if we are.
        $logged_in = serendipity_userLoggedIn();
        if ($logged_in && serendipity_checkPermission('adminImagesMaintainOthers') && serendipity_checkPermission('adminImagesDirectories')) {
            if (!$debug) {
                return true;
            }
        }

        // Get list of all ACLs for directories.
        $q = "SELECT a.artifact_index AS directory,
                     a.groupid
                FROM {$serendipity['dbPrefix']}access AS a
               WHERE a.artifact_type = 'directory'
                 AND a.artifact_mode = '" . serendipity_db_escape_string($type) . "'";
        $allowed = serendipity_db_query($q);
        if (!is_array($allowed)) {
            return true;
        }

        // Get a list of all the groups for this user. Pipe it into a usable array.
        if ($logged_in) {
            $my_groups =& serendipity_getGroups($serendipity['authorid']);
            $acl_allowed_groups = array();
            foreach($my_groups AS $my_group) {
                $acl_allowed_groups[$my_group['id']] = true;
            }
        } else {
            // Only the 'ALL AUTHORS' group is valid for non-logged in authors.
            $acl_allowed_groups = array(0 => true);
        }

        // Iterate every ACL and check if we are allowed to use it. Parse that data into a workable array.
        $acl_allowed = array();
        foreach($allowed AS $row) {
            $acl_allowed[$row['directory']][$row['groupid']] = true;
        }

        // Iterate the input path array and check it against ACL.
        foreach($paths AS $idx => $info) {
            if (!isset($acl_allowed[$info['relpath']])) {
                // ACL for directory not set. Assume we are allowed to access.
                continue;
            }

            $granted = false;
            foreach($acl_allowed[$info['relpath']] AS $groupid => $set) {
                if ($groupid === 0 || isset($acl_allowed_groups[$groupid])) {
                    // We are allowed to access this element
                    $granted = true;
                    break;
                }
            }

            if ($granted === false) {
                // We are not allowed to access this element
                if ($debug) {
                    $serendipity['logger']->debug("ACL for {$info['relpath']} DENIED.");
                }
                unset($paths[$idx]);
            } else {
                if ($debug) {
                    $serendipity['logger']->debug("ACL for {$info['relpath']} granted.");
                }
            }
        }

        if (count($paths) < $startCount) {
            if ($debug) {
                $serendipity['logger']->debug("ACL denied all.");
            }
            return false;
        }
    }

    return true;
}

/**
 * Build the name of a thumbnail image file.
 *
 * @author MTG
 * @param  string   Relative Path
 * @param  string   File name
 * @param  string   File extension
 * @param  string   Thumbnail suffix
 * @return array    Thumbnail path
 *
 */
function serendipity_getThumbNailPath($sRelativePath, $sName, $sExtension, $sThumbName) {
    $aTempArray = array('path'      => $sRelativePath,
                        'name'      => $sName,
                        'extension' => $sExtension);
    serendipity_plugin_api::hook_event('backend_thumbnail_filename_select', $aTempArray);

    if (isset($aTempArray['thumbnail_filename'])) {
        $sThumbNailPath = $aTempArray['thumbnail_filename'];
    } else {
        if ($sExtension) {
            $sThumbNailPath = $sRelativePath . $sName . (!empty($sThumbName) ? '.' . $sThumbName : '') . '.' . $sExtension;
        } else {
            $sThumbNailPath = $sRelativePath . $sName . (!empty($sThumbName) ? '.' . $sThumbName : '');
        }
    }

    return $sThumbNailPath;
}

 /**
 * Given a relative path to an image, construct an array containing all
 * relevant information about that image in the file structure.
 *
 * @author MTG
 * @param  string   Relative Path
 * @return array    Data about image
 *
 */
function &serendipity_getImageData($sRelativePath) {
    global $serendipity;

    // First, peel off the file name from the path
    $nPos = strrpos($sRelativePath, '/');
    if (is_bool($nPos) && !$nPos) {
        $sFileName  = $sRelativePath;
        $sDirectory = '';
    } else {
        $nLastSlashPos = 1 + $nPos;
        $sFileName     = substr($sRelativePath, $nLastSlashPos);
        $sDirectory    = substr($sRelativePath, 0, $nLastSlashPos);
    }

    list($sName, $sExtension) = serendipity_parseFileName($sFileName);

    $sImagePath = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sRelativePath;

    $aSizeData = @serendipity_getimagesize($sImagePath , '', $sExtension);
    $nWidth    = $aSizeData[0];
    $nHeight   = $aSizeData[1];
    $sMime     = $aSizeData['mime'];
    $nFileSize = @filesize($sImagePath);

    $array = array(
        'name'              => $sName,
        'extension'         => $sExtension,
        'mime'              => $sMime,
        'size'              => $nFileSize,
        'dimensions_width'  => $nWidth,
        'dimensions_height' => $nHeight,
        'path'              => $sDirectory,
        'authorid'          => 0,
        'hotlink'           => 0,
        'id'                => $sRelativePath,
        'realname'          => $sFileName
    );

    return $array;
}

/**
 * Shows the HTML form to add/edit properties of uploaded media items
 *
 * @param  array    Associative array holding an array('image_id', 'target', 'created_thumbnail') that points to the uploaded media
 * @param  int      How many keyword checkboxes to display next to each other?
 * @param  boolean  Can existing data be modified?
 * @return string   Generated HTML
 *
 */
function serendipity_showPropertyForm(&$new_media, $keywordsPerBlock = 3, $is_edit = true) {
    global $serendipity;

    if (!is_array($new_media) || count($new_media) < 1) {
        return true;
    }

    $mirror = array();
    serendipity_checkPropertyAccess($new_media, $mirror, 'read');

    $editform_hidden = '';
    if (isset($GLOBALS['image_selector_addvars']) && is_array($GLOBALS['image_selector_addvars'])) {
        // These variables may come from serendipity_admin_image_selector.php to show embedded upload form
        foreach($GLOBALS['image_selector_addvars'] AS $imgsel_key => $imgsel_val) {
            $editform_hidden .= '          <input type="hidden" name="serendipity[' . serendipity_specialchars($imgsel_key) . ']" value="' . serendipity_specialchars($imgsel_val) . '">' . "\n";
        }
    }

    $dprops   = explode(';', $serendipity['mediaProperties']);
    $keywords = explode(';', $serendipity['mediaKeywords']);

    $show = array();
    foreach($new_media AS $idx => $media) {
        $props =& serendipity_fetchMediaProperties($media['image_id']);

        $show[$idx] =& $media['internal'];
        $show[$idx]['image_id'] = $media['image_id'];

        serendipity_prepareMedia($show[$idx]);
        if (!isset($props['base_metadata']) || !is_array($props['base_metadata'])) {
            $show[$idx]['metadata'] =& serendipity_getMetaData($show[$idx]['realfile'], $show[$idx]['header']);
        } else {
            $show[$idx]['metadata'] = $props['base_metadata'];
            serendipity_plugin_api::hook_event('media_getproperties_cached', $show[$idx]['metadata'], $show[$idx]['realfile']);
        }

        serendipity_parseMediaProperties($dprops, $keywords, $show[$idx], $props, $keywordsPerBlock, $is_edit);
    }
    $smarty_vars = array(
        'is_edit'           => $is_edit,
        'editform_hidden'   => $editform_hidden,
        'keywordsPerBlock'  => $keywordsPerBlock,
        'keywords'          => $keywords,
        'dprops'            => $dprops,
        'case_add'          => (isset($new_media[0]['created_thumbnail']) && is_array($new_media[0]['created_thumbnail']))     // created_thumbnail is only set when viewing properties after adding an image
    );

    return serendipity_showMedia(
        $show,
        $mirror,
        '',
        false,
        1,
        false,
        $smarty_vars);
}

/**
 * Parse/Convert properties
 *
 * @param  array    Holds the property key array
 * @param  array    Holds the keyword key array
 * @param  int      Holds the media metadata
 * @param  int      Holds the media properties
 * @param  int      How many keyword checkboxes to display next to each other?
 * @param  boolean  Can existing data be modified?
 * @return boolean
 *
 */
function serendipity_parseMediaProperties(&$dprops, &$keywords, &$media, &$props, $keywordsPerBlock, $is_edit) {
    global $serendipity;

    if (!is_array($dprops)) {
        $dprops   = explode(';', $serendipity['mediaProperties']);
    }
    if (!is_array($keywords)) {
        $keywords = explode(';', $serendipity['mediaKeywords']);
    }
    // type 'media' only usage @see serendipity_admin_image_selector case $serendipity['GET']['step'] = 'showItem',
    // eg. /serendipity_admin_image_selector.php?serendipity[step]=showItem&serendipity[image]=42
    $media['references'] = serendipity_db_query("SELECT link, name
                            FROM {$serendipity['dbPrefix']}references
                           WHERE entry_id = " . $media['id'] . "
                             AND type = 'media'
                        ORDER BY name DESC
                           LIMIT 15", false, 'assoc');
    if (!is_array($media['references'])) {
        $media['references'] = false;
    }

    foreach($dprops AS $idx => $prop) {
        $type = 'input';
        $parts = explode(':', trim($prop));

        if (in_array('MULTI', $parts)) {
            $type = 'textarea';
        }

        if (preg_match('@(AUDIO|VIDEO|DOCUMENT|IMAGE|ARCHIVE|BINARY)@i', $prop)) {
            $show_item = false;
            if ($media['mediatype'] == 'video' && in_array('VIDEO', $parts)) {
                $show_item = true;
            }

            if ($media['mediatype'] == 'audio'  && in_array('AUDIO', $parts)) {
                $show_item = true;
            }

            if ($media['mediatype'] == 'image'  && in_array('IMAGE', $parts)) {
                $show_item = true;
            }

            if ($media['mediatype'] == 'document' && in_array('DOCUMENT', $parts)) {
                $show_item = true;
            }

            if ($media['mediatype'] == 'archive' && in_array('ARCHIVE', $parts)) {
                $show_item = true;
            }

            if ($media['mediatype'] == 'binary' && in_array('BINARY', $parts)) {
                $show_item = true;
            }

            if (!$show_item) {
                continue;
            }
        }

        if (!$is_edit) {
            $type = 'readonly';
        }
        $val = serendipity_mediaTypeCast($parts[0], $props['base_property']['ALL'][$parts[0]], true);

        $propkey = serendipity_specialchars($parts[0]) . $idx; // Well, this was added in S9y history for securing key uniqueness and fixed by Styx 32ada49c. Although we don't have possible duplicates.

        $media['base_property'][$propkey] = array(
            'label' => serendipity_specialchars((defined('MEDIA_PROPERTY_' . strtoupper($parts[0])) ? constant('MEDIA_PROPERTY_' . strtoupper($parts[0])) : $parts[0])),
            'type'  => $type,
            'val'   => $val,
            'title' => serendipity_specialchars($parts[0])
        );

        if (!isset($GLOBALS['IPTC']) || !is_array($GLOBALS['IPTC'])) {
            // Your templates config.inc.php or any of the language files can declare this variable,
            // if you want to use other default settings for this. No interface ability to declare this
            // yet, sorry.
            $GLOBALS['IPTC'] = array(
                'DATE'          => array('DateCreated'),
                'RUN_LENGTH'    => array('RunLength'),
                'DPI'           => array('XResolution'),
                'COPYRIGHT'     => array('Creator'),
                'TITLE'         => array('Title', 'ObjectName'),
                'COMMENT1'      => array('Description'),
                'ALT'           => array('Title', 'ObjectName'),
                'COMMENT2'      => array('Keywords', 'PhotoLocation')
            );
        }

        $default_iptc_val = null;
        if (empty($val)) {
            switch($parts[0]) {
                case 'DATE':
                    $default_iptc_val = serendipity_serverOffsetHour();

                case 'RUN_LENGTH':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = '00:00:00.00';
                    }

                case 'DPI':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = '72';
                    }

                case 'COPYRIGHT':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = isset($serendipity['serendipityUser']) ? $serendipity['serendipityUser'] : null;
                    }

                case 'TITLE':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = $media['realname'];
                    }

                case 'ALT':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = '';
                    }

                case 'COMMENT1':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = '';
                    }

                case 'COMMENT2':
                    if (!isset($default_iptc_val)) {
                        $default_iptc_val = '';
                    }

                    $media['base_property'][$propkey]['val'] = serendipity_pickKey($media['metadata'], 'Keywords', '');

                    $new_iptc_val     = false;
                    foreach($GLOBALS['IPTC'][$parts[0]] AS $iptc_key) {
                        if (empty($new_iptc_val)) {
                            $new_iptc_val = serendipity_pickKey($media['metadata'], $iptc_key, '');
                        }
                    }

                    if (empty($new_iptc_val)) {
                        $new_iptc_val = $default_iptc_val;
                    }

                    if ($parts[0] == 'DATE') {
                        $media['base_property'][$propkey]['val'] = serendipity_strftime(DATE_FORMAT_SHORT, $new_iptc_val);
                    } else {
                        $media['base_property'][$propkey]['val'] = $new_iptc_val;
                    }

                    break;

                default:
                    serendipity_plugin_api::hook_event('media_showproperties', $media, $propkey);
                    break;
            }
        }
    }

    if ($keywordsPerBlock > 0) {
        $rows  = ceil(count($keywords) / $keywordsPerBlock);
        for($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $keywordsPerBlock; $j++) {
                $kidx = ($i*$keywordsPerBlock) + $j;
                if (isset($keywords[$kidx])) {
                    $media['base_keywords'][$i][$j] = array(
                        'name'      => serendipity_specialchars($keywords[$kidx]),
                        'selected'  => isset($props['base_keyword'][$keywords[$kidx]]) ? true : false
                    );
                } else {
                    $media['base_keywords'][$i][$j] = array();
                }
            }
        }
    }
}

/**
 * Tries to auto-convert specific fields into DB-storable values
 *
 * @param  string   The keyname
 * @param  string   The value
 * @param  string   Invert?
 * @return array    array('image_id') holding the last created thumbnail for immediate processing
 *
 */
function serendipity_mediaTypeCast($key, $val, $invert = false) {
    if (stristr($key, 'date') !== FALSE) {
        if ($invert && is_numeric($val)) {
            return serendipity_strftime(DATE_FORMAT_SHORT, $val, false);
        } elseif ($invert === false) {
            $tmp = strtotime($val);
            if ($tmp !== FALSE && $tmp > 1) {
                return $tmp;
            }
        }
    } elseif ($invert && stristr($key, 'length') !== FALSE) {
        $tmp = '';

        $hours    = intval(intval($val) / 3600);
        $minutes  = intval(($val / 60) % 60);
        $seconds  = intval($val % 60);
        $mseconds = intval((($val - $seconds) * 100) % 100);

        $tmp .= str_pad($hours, 2, '0', STR_PAD_LEFT) . ':';
        $tmp .= str_pad($minutes, 2, '0', STR_PAD_LEFT). ':';
        $tmp .= str_pad($seconds, 2, '0', STR_PAD_LEFT) . '.';
        $tmp .= str_pad($mseconds, 2, '0', STR_PAD_LEFT);

        return $tmp;
    } elseif ($invert === false && preg_match('@^([0-9]+):([0-9]+):([0-9]+).([0-9]+)$@i', $val, $m)) {
        $tmp = ($m[1] * 3600)
             + ($m[2] * 60)
             + ($m[3])
             + ($m[4] / 100);
        return $tmp;
    }

    return $val;
}

/**
 * Inserts media properties
 *
 * @param   string  Property_group
 *
 */
function serendipity_insertMediaProperty($property_group, $property_subgroup, $image_id, &$media, $use_cast = true) {
    global $serendipity;

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}mediaproperties
                                WHERE mediaid = " . (int)$image_id . "
                                  " . ($property_subgroup != 'ALL' ? "AND property_subgroup = '" . serendipity_db_escape_string($property_subgroup) . "'" : '') . "
                                  AND property_group = '" . serendipity_db_escape_string($property_group) . "'");

    if (is_array($media)) {
        foreach($media AS $key => $val) {
            if ($key == 'image_id') continue;

            if (is_array($val)) {
                $use_property_subgroup = $key;
                $use_val = $val;
            } else {
                $use_property_subgroup = $property_subgroup;
                $use_val = array($key => $val);
            }

            foreach($use_val AS $insert_key => $insert_val) {
                if ($use_cast) {
                    $insert_val = serendipity_mediaTypeCast($insert_key, $insert_val);
                }
                $q = sprintf("INSERT INTO {$serendipity['dbPrefix']}mediaproperties
                                          (mediaid, property_group, property_subgroup, property, value)
                                   VALUES (%d, '%s', '%s', '%s', '%s')",
                             $image_id,
                             serendipity_db_escape_string($property_group),
                             serendipity_db_escape_string($use_property_subgroup),
                             serendipity_db_escape_string($insert_key),
                             serendipity_db_escape_string($insert_val));
                serendipity_db_query($q);
            }
        }
    }
}

/**
 * Inserts the submitted properties of uploaded media items
 *
 * @return array    array('image_id') holding the last created thumbnail for immediate processing
 *
 */
function serendipity_parsePropertyForm() {
    global $serendipity;

    if (!is_array($serendipity['POST']['mediaProperties'])) {
        return false;
    }

    serendipity_checkPropertyAccess($serendipity['POST']['mediaProperties'], $serendipity['POST']['mediaKeywords'], 'write');

    foreach($serendipity['POST']['mediaProperties'] AS $id => $media) {
        serendipity_insertMediaProperty('base_property', 'ALL', $media['image_id'], $media);

        $s9y_img = $media['internal'];
        $s9y_img['image_id'] = $media['image_id'];
        serendipity_prepareMedia($s9y_img);
        $s9y_img['metadata'] =& serendipity_getMetaData($s9y_img['realfile'], $s9y_img['header']);
        serendipity_insertMediaProperty('base_metadata', 'ALL', $media['image_id'], $s9y_img['metadata']);
        $s9y_img['hidden'] = array(
            'author'   => $serendipity['serendipityUser'],
            'authorid' => $serendipity['authorid']
        );
        serendipity_insertMediaProperty('base_hidden', '', $media['image_id'], $s9y_img['hidden']);

        if (isset($serendipity['POST']['oldDir'][$id]) && $serendipity['POST']['oldDir'][$id] != $serendipity['POST']['newDir'][$id]) {
            serendipity_moveMediaDirectory(
                serendipity_uploadSecure($serendipity['POST']['oldDir'][$id]),
                serendipity_uploadSecure($serendipity['POST']['newDir'][$id]),
                'filedir',
                $media['image_id']);
        }
    }

    if (is_array($serendipity['POST']['mediaKeywords'])) {
        foreach($serendipity['POST']['mediaKeywords'] AS $id => $keywords) {
            serendipity_insertMediaProperty('base_keyword', '', $serendipity['POST']['mediaProperties'][$id]['image_id'], $keywords);
        }
    }

    $array = array(
        'image_id' => $serendipity['POST']['mediaProperties'][0]['image_id'],
    );

    return $array;
}

/**
 * Fetches existing Media Properties for images
 *
 * @param  int      The media item id
 * @return array    Array of image metadata
 *
 */
function &serendipity_fetchMediaProperties($id) {
    global $serendipity;

    $sql = "SELECT mediaid, property, property_group, property_subgroup, value
              FROM {$serendipity['dbPrefix']}mediaproperties
             WHERE mediaid IN (" . (is_array($id) ? serendipity_db_implode(',', $id) : (int)$id) . ")";
    $rows  = serendipity_db_query($sql, false, 'assoc');
    $props = array();
    if (is_array($rows)) {
        foreach($rows AS $row) {
            if (empty($row['property_subgroup'])) {
                if (is_array($id)) {
                    $props[$row['mediaid']][$row['property_group']][$row['property']] = $row['value'];
                } else {
                    $props[$row['property_group']][$row['property']] = $row['value'];
                }
            } else {
                if (is_array($id)) {
                    $props[$row['mediaid']][$row['property_group']][$row['property_subgroup']][$row['property']] = $row['value'];
                } else {
                    $props[$row['property_group']][$row['property_subgroup']][$row['property']] = $row['value'];
                }
            }
        }
    }
    return $props;
}

/**
 * Checks if properties to a specific image are allowed to be fetched
 *
 * @param  array    Array of image metadata
 * @param  array    Array of additional image metadata
 * @param  string   ACL toggle type ('read', 'write')
 * @return array    Stripped Array of image metadata
 *
 */
function serendipity_checkPropertyAccess(&$new_media, &$additional, $mode = 'read') {
    global $serendipity;

    // Strip out images we don't have access to
    $ids = array();
    foreach($new_media AS $id => $item) {
        $ids[] = $item['image_id'];
    }

    $valid_images = serendipity_fetchImageFromDatabase($ids, $mode);
    foreach($new_media AS $id => $media) {
        if (!isset($valid_images[$media['image_id']])) {
            unset($new_media[$id]);
            unset($additional[$id]);
        } else {
            $new_media[$id]['internal'] = $valid_images[$media['image_id']];
        }
    }

    return true;
}

/**
 * Prepare a media item for showing
 *
 * @param  array    Array of image metadata
 * @param  string   URL for maintenance tasks, set when using the ML for inserting images
 * @return bool
 *
 */
function serendipity_prepareMedia(&$file, $url = '') {
    global $serendipity;
    static $full_perm = null;

    if ($full_perm === null) {
        $full_perm = serendipity_checkPermission('adminImagesMaintainOthers');
    }

    $sThumbSource = serendipity_getThumbNailPath($file['path'], $file['name'], $file['extension'], $file['thumbnail_name']);
    if (! $file['hotlink']) {
        $file['full_thumb']     = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource;
        $file['full_thumbHTTP'] = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $sThumbSource;
    }

    $file['url'] = $url;

    if ($file['hotlink']) {
        $file['full_file']  = $file['path'];
        $file['show_thumb'] = $file['path'];
        if (!isset($file['imgsrc'])) {
            $file['imgsrc'] = $file['show_thumb'];
        }
    } else {
        $file['full_file']  = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $file['full_path_file'] = $serendipity['serendipityPath'] . $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $file['show_thumb'] = $file['full_thumbHTTP'];
        if (!isset($file['imgsrc'])) {
            $file['imgsrc'] = $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        }
    }

    // Detect PDF thumbs
    if (isset($file['full_thumb']) && file_exists($file['full_thumb'] . '.png')) {
        $file['full_thumb']     .= '.png';
        $file['full_thumbHTTP'] .= '.png';
        $file['show_thumb']     .= '.png';
        $sThumbSource           .= '.png';
    }

    if (empty($file['realname'])) {
        $file['realname'] = $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
    }
    $file['diskname'] = $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

    $file['links'] = array('imagelinkurl' => $file['full_file']);

    $file['is_image']  = serendipity_isImage($file);
    $file['dim']       = $file['is_image'] ? @getimagesize($file['full_thumb'], $file['thumb_header']) : null;
    $file['dim_orig']  = $file['is_image'] ? @getimagesize($file['full_path_file'], $file['header']) : null;

    if ($file['is_image']) {
        $file['mediatype'] = 'image';
    } elseif (0 === strpos(strtolower($file['displaymime']), 'video/') || 0 === strpos(strtolower($file['displaymime']), 'application/x-shockwave')) {
        $file['mediatype'] = 'video';
    } elseif (0 === strpos(strtolower($file['displaymime']), 'audio/') || 0 === strpos(strtolower($file['displaymime']), 'application/vnd.rn-') || 0 === strpos(strtolower($file['displaymime']), 'application/ogg')) {
        $file['mediatype'] = 'audio';
    } elseif (0 === strpos(strtolower($file['displaymime']), 'text/')) {
        $file['mediatype'] = 'document';
    } elseif (preg_match('@application/(pdf|rtf|msword|msexcel|excel|x-excel|mspowerpoint|postscript|vnd\.ms*|powerpoint)@i', $file['displaymime'])) {
        $file['mediatype'] = 'document';
    } elseif (preg_match('@application/(java-archive|zip|gzip|arj|x-bzip|x-bzip2|x-compressed|x-gzip|x-stuffit)@i', $file['displaymime'])) {
        $file['mediatype'] = 'archive';
    } else {
        $file['mediatype'] = 'binary';
    }

    $file['realfile'] = ($file['hotlink'])
                        ? $file['path']
                        : $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

    if ($full_perm || (isset($serendipity['authorid']) && $serendipity['authorid'] == $file['authorid']) || $file['authorid'] == '0' || serendipity_checkPermission('adminImagesDelete')) {
        $file['is_editable'] = true;
    } else {
        $file['is_editable'] = false;
    }

    /* If it is an image, and the thumbnail exists */
    if ($file['is_image'] && isset($file['full_thumb']) && file_exists($file['full_thumb'])) {
        $file['thumbWidth']  = $file['dim'][0];
        $file['thumbHeight'] = $file['dim'][1];
        $file['thumbSize']   = @filesize($file['full_thumb']);
    } elseif ($file['is_image'] && $file['hotlink']) {
        $sizes = serendipity_calculate_aspect_size($file['dimensions_width'], $file['dimensions_height'], $serendipity['thumbSize'], $serendipity['thumbConstraint']);
        $file['thumbWidth']  = $sizes[0];
        $file['thumbHeight'] = $sizes[1];
        $file['thumbSize']   = 0;
    /* If it's not an image, or the thumbnail does not exist */
    } else {
        $mimeicon = serendipity_getTemplateFile('admin/img/mime_' . preg_replace('@[^a-z0-9\-\_]@i', '-', $file['mime']) . '.png');
        if (!$mimeicon) {
            $mimeicon = serendipity_getTemplateFile('admin/img/mime_unknown.png');
        }
        $file['mimeicon'] = $mimeicon;
    }

    $_iplus = ((isset($serendipity['enableBackendPopupGranular']) && false !== stripos($serendipity['enableBackendPopupGranular'], 'images')) || (isset($serendipity['enableBackendPopup']) && $serendipity['enableBackendPopup'])) ? 20 : 0;
    $file['popupWidth']   = ($file['is_image'] ? ($file['dimensions_width']  + $_iplus) : 600);
    $file['popupHeight']  = ($file['is_image'] ? ($file['dimensions_height'] + $_iplus) : 500);
    if ($file['hotlink']) {
        $file['nice_hotlink'] = wordwrap($file['path'], 45, '<br />', 1);
    }
    $file['nice_size']    = number_format(round($file['size']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    if (isset($file['thumbSize'])) {
        $file['nice_thumbsize'] = number_format(round($file['thumbSize']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }

    return true;
}

/**
 * Prints a media item
 *
 * @param  array    Array of image metadata
 * @param  array    Array of additional image metadata like mediaKeywords or paths
 * @param  string   URL for maintenance tasks
 * @param  boolean  Whether to show maintenance task items
 * @param  int      how many media items to display per row
 * @param  boolean  Enclose within a table cell?
 * @param  array    Additional Smarty variables
 * @return string   Generated HTML
 *
 */
function serendipity_showMedia(&$file, &$paths, $url = '', $manage = false, $lineBreak = 3, $enclose = true, $smarty_vars = array()) {
    global $serendipity;

    $form_hidden = '';
    // do not add, if not for the default media list form
    if (($serendipity['GET']['adminAction'] == 'default' || empty($serendipity['GET']['adminAction'])) && !isset($serendipity['GET']['fid'])) {
        foreach($serendipity['GET'] AS $g_key => $g_val) {
            // do not add token, since this is assigned separately to properties and list forms
            if (!is_array($g_val) && $g_key != 'page' && $g_key != 'token') {
                $form_hidden .= '        <input type="hidden" name="serendipity[' . $g_key . ']" value="' . serendipity_specialchars($g_val) . '">'."\n";
            }
        }
    }

    $displayGallery  = (isset($serendipity['GET']['showGallery']) && !isset($serendipity['GET']['showUpload']) && $serendipity['GET']['showGallery'] == 'true') ? true : false;

    if (!is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }
    $order_fields = serendipity_getImageFields();

    $media = array(
        'standardpane'      => $displayGallery ? false : true,
        'manage'            => $manage,
        'multiperm'         => serendipity_checkPermission('adminImagesDirectories'),
        'resetperm'         => (serendipity_checkPermission('adminImagesDelete') && serendipity_checkPermission('adminImagesMaintainOthers')),
        'viewperm'          => (serendipity_checkPermission('adminImagesView') && $serendipity['GET']['adminAction'] != 'choose'),
        'lineBreak'         => $lineBreak,
        'lineBreakP'        => round(1/$lineBreak*100),
        'url'               => $url,
        'enclose'           => $enclose,
        'token'             => serendipity_setFormToken(),
        'form_hidden'       => $form_hidden,
        'blimit_path'       => empty($smarty_vars['limit_path']) ? '' : basename($smarty_vars['limit_path']),
        'only_path'         => isset($serendipity['GET']['only_path']) ? $serendipity['GET']['only_path'] : '',
        'sortorder'         => isset($serendipity['GET']['sortorder']) ? $serendipity['GET']['sortorder'] : '',
        'keywords_selected' => isset($serendipity['GET']['keywords']) ? $serendipity['GET']['keywords'] : '',
        'filter'            => isset($serendipity['GET']['filter']) ? $serendipity['GET']['filter'] : null,/* NIL or array() ?? (media_toolbar.tpl) */
        'sort_order'        => $order_fields,
        'simpleFilters'     => $displayGallery ? false : (isset($serendipity['simpleFilters']) ? $serendipity['simpleFilters'] : true),
        'metaActionBar'     => ($serendipity['GET']['adminAction'] != 'properties' && empty($serendipity['GET']['fid'])),
        'hideSubdirFiles'   => empty($serendipity['GET']['hideSubdirFiles']) ? 'yes' : $serendipity['GET']['hideSubdirFiles'],
        'authors'           => serendipity_fetchUsers(),
        'sort_row_interval' => array(8, 16, 50, 100),
        'nr_files'          => count($file),
        'keywords'          => explode(';', $serendipity['mediaKeywords']),
        'thumbSize'         => $serendipity['thumbSize'],
        'sortParams'        => array('perpage', 'order', 'ordermode')
    );

    $media = array_merge($media, $smarty_vars);
    $media['files'] =& $file;

    if (is_array($paths) && count($paths) > 0) {
        $media['paths'] =& $paths;
    } else {
        $media['paths'] =& serendipity_getMediaPaths();
    }

    $serendipity['smarty']->assignByRef('media', $media);

    if ($enclose) {
        serendipity_smarty_fetch('MEDIA_TOOLBAR', 'admin/media_toolbar.tpl');
        if ($displayGallery) {
            serendipity_smarty_fetch('MEDIA_ITEMS', 'admin/media_galleryitems.tpl');
            return serendipity_smarty_showTemplate(serendipity_getTemplateFile('admin/media_gallery.tpl', 'serendipityPath'));
        } else {
            serendipity_smarty_fetch('MEDIA_ITEMS', 'admin/media_items.tpl');
            return serendipity_smarty_showTemplate(serendipity_getTemplateFile('admin/media_pane.tpl', 'serendipityPath'));
        }
    } else {
        serendipity_smarty_fetch('MEDIA_ITEMS', 'admin/media_items.tpl');
        return serendipity_smarty_showTemplate(serendipity_getTemplateFile('admin/media_properties.tpl', 'serendipityPath'));
    }
}

/**
 * Convert a IPTC/EXIF/XMP item
 *
 * @param  string   The content
 * @param  string   The type of the content
 * @return string   The converted content
 *
 */
function serendipity_metaFieldConvert(&$item, $type) {
    switch($type) {
        case 'math':
            $parts = explode('/', $item);
            return ($parts[1] > 0) ? ($parts[0] / $parts[1]) : 0;
            break;

        case 'or':
            if ($item == '1') {
                return 'Landscape';
            } else {
                return 'Portrait';
            }

        case 'date':
            return strtotime($item);
            break;

        case 'date2':
            $parts = preg_split('&[ :]&', $item);
            return mktime($parts[3], $parts[4], $parts[5], $parts[1], $parts[2], $parts[0]);
            break;

        case 'IPTCdate':
            preg_match('@(\d{4})(\d{2})(\d{2})@',$item,$parts);
            return mktime(0, 0, 0, intval($parts[2]), intval($parts[3]), intval($parts[1]));
            break;

        case 'IPTCtime':
            preg_match('@(\d{2})(\d{2})(\d{2})([\+-])(\d{2})(\d{2})@',$item,$parts);
            $time = serendipity_strftime('%H:%M', mktime(intval($parts[1]), intval($parts[2]), intval($parts[3]), 0, 0, 0));
            $timezone = serendipity_strftime('%H:%M', mktime(intval($parts[5]), intval($parts[6]), 0, 0, 0, 0));
            return $time . ' GMT' . $parts[4] . $timezone;
            break;

        case 'rdf':
            if (preg_match('@<rdf:li[^>]*>(.*)</rdf:li>@i', $item, $ret)) {
                return $ret[1];
            }
            break;

        case 'text':
        default:
            return trim($item);
            break;
    }

    return '';
}

/**
 * Get the RAW media header data (XMP)
 *
 * @param  string   Filename
 * @return array    The raw media header data
 *
 * Inspired, but rewritten,  by "PHP JPEG Metadata Toolkit" from http://electronics.ozhiker.com.
 * Code is GPL so sadly we couldn't bundle that GREAT library.
 */
function serendipity_getMediaRaw($filename) {
    $abort = false;

    $f = @fopen($filename, 'rb');
    $ret = array();
    if (!$f) {
        return $ret;
    }

    $filedata = fread($f, 2);

    if ($filedata != "\xFF\xD8") {
        fclose($f);
        return $ret;
    }

    $filedata = fread($f, 2);

    if ($filedata[0] != "\xFF") {
        fclose($f);
        return $ret;
    }

    while (!$abort && !feof($f) && $filedata[1] != "\xD9") {
        if ((ord($filedata[1]) < 0xD0) || (ord($filedata[1]) > 0xD7)) {
            $ordret   = fread($f, 2);
            $ordstart = ftell($f);
            $int      = unpack('nsize', $ordret);

            if (ord($filedata[1]) == 225) {
                $content  = fread($f, $int['size'] - 2);

                if (substr($content, 0, 24) == 'http://ns.adobe.com/xap/') {
                    $ret[] = array(
                        'ord'      => ord($filedata[1]),
                        'ordstart' => $ordstart,
                        'int'      => $int,
                        'content'  => $content
                    );
                }
            } else {
                fseek($f, $int['size'] - 2, SEEK_CUR);
            }
        }

        if ($filedata[1] == "\xDA") {
            $abort = true;
        } else {
            $filedata = fread($f, 2);
            if ($filedata[0] != "\xFF") {
                fclose($f);
                return $ret;
            }
        }
    }

    fclose($f);

    return $ret;
}

/**
 * Get the IPTC/EXIF/XMP media metadata
 *
 * @param  string   Filename
 * @return array    The raw media header data
 *
 */
function &serendipity_getMetaData($file, &$info) {
    global $serendipity;

    # Fields taken from: http://demo.imagefolio.com/demo/ImageFolio31_files/skins/cool_blue/images/iptc.html
    static $IPTC_Fields = array(
    '2#005' => 'ObjectName',
    '2#025' => 'Keywords',
    '2#026' => 'LocationCode',
    '2#027' => 'LocationName',
    '2#030' => 'ReleaseDate',
    '2#035' => 'ReleaseTime',
    '2#037' => 'ExpirationDate',
    '2#038' => 'ExpirationTime',
    '2#055' => 'IPTCDateCreated',
    '2#060' => 'IPTCTimeCreated',
    '2#062' => 'DigitalDateCreated',
    '2#063' => 'DigitalTimeCreated',
    '2#065' => 'Software',
    '2#070' => 'SoftwareVersion',
    '2#080' => 'Photographer',
    '2#085' => 'Photographer Name',
    '2#090' => 'PhotoLocation',
    '2#092' => 'PhotoLocation2',
    '2#095' => 'PhotoState',
    '2#100' => 'PhotoCountryCode',
    '2#101' => 'PhotoCountry',
    '2#105' => 'Title',
    '2#110' => 'Credits',
    '2#115' => 'Source',
    '2#116' => 'Creator',
    '2#118' => 'Contact',
    '2#120' => 'Description',
    '2#131' => 'Orientation',
    '2#150' => 'AudioType',
    '2#151' => 'AudioSamplingRate',
    '2#152' => 'AudioSamplingResolution',
    '2#153' => 'AudioDuration'
    );

    static $ExifFields = array(
        'IFD0' => array(
            'Make'         => array('type' => 'text',  'name' => 'CameraMaker'),
            'Model'        => array('type' => 'text',  'name' => 'CameraModel'),
            'Orientation'  => array('type' => 'or',    'name' => 'Orientation'),
            'XResolution'  => array('type' => 'math',  'name' => 'XResolution'),
            'YResolution'  => array('type' => 'math',  'name' => 'YResolution'),
            'Software'     => array('type' => 'text',  'name' => 'Software'),
            'DateTime'     => array('type' => 'date2', 'name' => 'DateCreated'),
            'Artist'       => array('type' => 'text',  'name' => 'Creator'),
        ),

        'EXIF' => array(
            'ExposureTime'          => array('type' => 'math',  'name' => 'ExposureTime'),
            'ApertureValue'         => array('type' => 'math',  'name' => 'ApertureValue'),
            'MaxApertureValue'      => array('type' => 'math',  'name' => 'MaxApertureValue'),
            'ISOSpeedRatings'       => array('type' => 'text',  'name' => 'ISOSpeedRatings'),
            'DateTimeOriginal'      => array('type' => 'date2', 'name' => 'DateCreated'),
            'MeteringMode'          => array('type' => 'text',  'name' => 'MeteringMode'),
            'FNumber'               => array('type' => 'math',  'name' => 'FNumber'),
            'ExposureProgram'       => array('type' => 'text',  'name' => 'ExposureProgram'),
            'FocalLength'           => array('type' => 'math',  'name' => 'FocalLength'),
            'WhiteBalance'          => array('type' => 'text',  'name' => 'WhiteBalance'),
            'DigitalZoomRatio'      => array('type' => 'math',  'name' => 'DigitalZoomRatio'),
            'FocalLengthIn35mmFilm' => array('type' => 'text',  'name' => 'FocalLengthIn35mmFilm'),
            'Flash'                 => array('type' => 'text',  'name' => 'Flash'),
            'Fired'                 => array('type' => 'text',  'name' => 'FlashFired'),
            'RedEyeMode'            => array('type' => 'text',  'name' => 'RedEyeMode'),
        )
    );

    static $xmpPatterns = array(
        'tiff:Orientation'           => array('type' => 'or',   'name' => 'Orientation'),
        'tiff:XResolution'           => array('type' => 'math', 'name' => 'XResolution'),
        'tiff:YResolution'           => array('type' => 'math', 'name' => 'YResolution'),
        'tiff:Make'                  => array('type' => 'text', 'name' => 'CameraMaker'),
        'tiff:Model'                 => array('type' => 'text', 'name' => 'CameraModel'),
        'xap:ModifyDate'             => array('type' => 'date', 'name' => 'DateModified'),
        'xap:CreatorTool'            => array('type' => 'text', 'name' => 'Software'),
        'xap:CreateDate'             => array('type' => 'date', 'name' => 'DateCreated'),
        'xap:MetadataDate'           => array('type' => 'date', 'name' => 'DateMetadata'),

        'exif:ExposureTime'          => array('type' => 'math',  'name' => 'ExposureTime'),
        'exif:ApertureValue'         => array('type' => 'math',  'name' => 'ApertureValue'),
        'exif:MaxApertureValue'      => array('type' => 'math',  'name' => 'MaxApertureValue'),
        'exif:ISOSpeedRatings'       => array('type' => 'text',  'name' => 'ISOSpeedRatings'),
        'exif:DateTimeOriginal'      => array('type' => 'date',  'name' => 'DateCreated'),
        'exif:MeteringMode'          => array('type' => 'text',  'name' => 'MeteringMode'),
        'exif:FNumber'               => array('type' => 'math',  'name' => 'FNumber'),
        'exif:ExposureProgram'       => array('type' => 'text',  'name' => 'ExposureProgram'),
        'exif:FocalLength'           => array('type' => 'math',  'name' => 'FocalLength'),
        'exif:WhiteBalance'          => array('type' => 'text',  'name' => 'WhiteBalance'),
        'exif:DigitalZoomRatio'      => array('type' => 'math',  'name' => 'DigitalZoomRatio'),
        'exif:FocalLengthIn35mmFilm' => array('type' => 'text',  'name' => 'FocalLengthIn35mmFilm'),
        'exif:Fired'                 => array('type' => 'text',  'name' => 'FlashFired'),
        'exif:RedEyeMode'            => array('type' => 'text',  'name' => 'RedEyeMode'),

        'dc:title'                   => array('type' => 'rdf',   'name' => 'Title'),
        'dc:creator'                 => array('type' => 'rdf',   'name' => 'Creator'),
    );

    $ret = array();

    if (!$serendipity['mediaExif']) {
        return $ret;
    }

    if (!file_exists($file)) {
        return $ret;
    }

    if (function_exists('iptcparse') && is_array($info) && isset($info['APP13'])) {
        $iptc = iptcparse($info['APP13']);
        foreach($IPTC_Fields AS $field => $desc) {
            if (isset($iptc[$field])) {
                if (is_array($iptc[$field])) {
                    $ret['IPTC'][$desc] = trim(implode(';', $iptc[$field]));
                } else {
                    $ret['IPTC'][$desc] = trim($iptc[$field]);
                }

                switch ($desc) {
                    case 'IPTCDateCreated':
                        $ret['IPTC'][$desc] = serendipity_metaFieldConvert($ret['IPTC'][$desc],'IPTCdate');
                        break;
                    case 'IPTCTimeCreated':
                        $ret['IPTC'][$desc] = serendipity_metaFieldConvert($ret['IPTC'][$desc],'IPTCtime');
                        break;
                }
            }
        }
    }

    if (function_exists('exif_read_data') && is_array($info)) {
        $exif = @exif_read_data($file, 'FILE,COMPUTED,ANY_TAG,IFD0,COMMENT,EXIF', true, false);
        if (is_array($exif)) {
            foreach($ExifFields AS $Exifgroup => $ExifField) {
                foreach($ExifField AS $ExifName => $ExifItem) {
                    if (!isset($exif[$Exifgroup][$ExifName])) {
                        continue;
                    }
                    $ret['EXIF'][$ExifItem['name']] = serendipity_metaFieldConvert($exif[$Exifgroup][$ExifName], $ExifItem['type']);
                    if (isset($item) && $ret['EXIF'][$item['name']] == $ret['IPTC'][$item['name']]) {
                        unset($ret['IPTC'][$item['name']]);
                    }
                }
            }
        }
    }

    $xmp = serendipity_getMediaRaw($file);
    foreach($xmp AS $xmp_data) {
        if (empty($xmp_data['content'])) {
            continue;
        }
        foreach($xmpPatterns AS $lookup => $item) {
            if (preg_match('@<' . $lookup . '>(.*)</' . $lookup . '>@', $xmp_data['content'], $match)) {
                $ret['XMP'][$item['name']] = serendipity_metaFieldConvert($match[1], $item['type']);
                if (isset($ret['EXIF'][$item['name']]) && $ret['EXIF'][$item['name']] == $ret['XMP'][$item['name']]) {
                    unset($ret['EXIF'][$item['name']]);
                }
            }
        }
    }

    serendipity_plugin_api::hook_event('media_getproperties', $ret, $file);

    return $ret;
}

/**
 * Parses an existing filename and increases the filecount.
 *
 * @param  string   The (duplicate) filename
 * @param  string   The full path to the (duplicate) filename
 * @param  string   The directory of the (duplicate) filename
 * @param  boolean  Show new filename?
 * @return string   The new filename
 *
 */
function serendipity_imageAppend(&$tfile, &$target, $dir, $echo = true) {
    static $safe_bail = 20;

    $realname = $tfile;
    list($filebase, $extension) = serendipity_parseFileName($tfile);

    $cnum = 1;
    if (preg_match('@^(.*)([0-9]+)$@', $filebase, $match)) {
        $cnum     = $match[2];
        $filebase = $match[1];
    }

    $i = 0;
    while ($i <= $safe_bail && file_exists($dir . $filebase . $cnum . (empty($extension) ? '' : '.' . $extension))) {
        $cnum++;
    }

    // Check if the file STILL exists and append a MD5 if that's the case. That should be unique enough.
    if (file_exists($dir . $filebase . $cnum . (empty($extension) ? '' : '.' . $extension))) {
        $cnum = md5(time() . $filebase);
    }

    // Those variables are passed by reference!
    $tfile  = $filebase . $cnum . (empty($extension) ? '' : '.' . $extension);
    $target = $dir . $tfile;

    if ($echo) {
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> <b>' .
        sprintf(FILENAME_REASSIGNED . "<br>\n", serendipity_specialchars($tfile)) . "</b></span>\n";
    }
    return $realname;
}

/**
 * Checks if an uploaded media item hits any configured limits.
 *
 * @param  string   The filename
 * @return boolean  TRUE when file is okay, FALSE when it is beyond limits
 *
 */
function serendipity_checkMediaSize($file) {
    global $serendipity;

    if (!empty($serendipity['maxFileSize'])) {
        if (filesize($file) > $serendipity['maxFileSize']) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
            sprintf(MEDIA_UPLOAD_SIZEERROR . "<br>\n", (int)$serendipity['maxFileSize']) . "</span>\n";
            return false;
        }
    }

    if (!empty($serendipity['maxImgWidth']) || !empty($serendipity['maxImgHeight'])) {
        $dim = serendipity_getimagesize($file);
        if (!is_array($dim) || !isset($dim[0])) {
            return true;
        }

        if (!empty($serendipity['maxImgWidth'])) {
            if ($dim[0] > $serendipity['maxImgWidth']) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
                sprintf(MEDIA_UPLOAD_DIMERROR . "<br>\n", (int)$serendipity['maxImgWidth'], (int)$serendipity['maxImgHeight']) . "</span>\n";
                return false;
            }
        }

        if (!empty($serendipity['maxImgHeight'])) {
            if ($dim[1] > $serendipity['maxImgHeight']) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
                sprintf(MEDIA_UPLOAD_DIMERROR . "<br>\n", (int)$serendipity['maxImgWidth'], (int)$serendipity['maxImgHeight']) . "</span>\n";
                return false;
            }
        }
    }

    return true;
}

/**
 * RENAME a media directory, update the path and apply ACL restrictions in the database and forward to staticpages.
 * ACL are Access Control Lists. In this case they indicate which read/write permissions a directory has for a specific usergroup.
 *
 * Accessed for existing directory edits by 'directoryEdit'
 *
 * @see SPLIT serendipity_moveMediaDirectory() part 1
 *
 * @access public
 * @param   string  Old directory name or empty
 * @param   string  New directory name with trailing slash or empty
 * @param   boolean Ad hoc debugging, set in wrapper serendipity_moveMediaDirectory()
 */
function serendipity_renameDirAccess($oldDir, $newDir, $debug=false) {
    global $serendipity;

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $serendipity['logger']->debug("IN serendipity_renameDirAccess");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_moveMediaDirectory') {
        echo 'Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    $real_oldDir = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . rtrim($oldDir, '/');
    $real_newDir = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . rtrim($newDir, '/');

    if (!is_dir($real_oldDir)) {
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_NOT_EXISTS . "</span>\n"; // const has no arg for  , rtrim($oldDir, '/')
        return false;
    }

    if (is_dir($real_newDir)) {
        if ($serendipity['GET']['adminAction'] == 'directoryEdit') {
            // void, since it already exists and this is just a change of properties, otherwise it is new and created and the move actions are proceeded.
            //echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(DIRECTORY_CREATED, rtrim($newDir, '/')) . "</span>\n";
        } else {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_EXISTS . "</span>\n"; // const has no arg for , rtrim($newDir, '/')
        }
        return false;
    }

    // Move the origin file in file system
    try {
        rename($real_oldDir, $real_newDir);
    } catch (Throwable $t) {
        // Executed only in PHP 7, will not match in PHP 5.x
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVE_ERROR, $newDir) . "</span>\n";
        #echo ': '.$t->getMessage();
        return false;
    } catch (Exception $e) {
        // Executed only in PHP 5.x, will not be reached in PHP 7
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVE_ERROR, $newDir) . "</span>\n";
        #echo ': '.$e->getMessage();
        return false;
    }

    // make it easy and just fetch the items in real need
    $dirs = serendipity_db_query("SELECT id, path
                                    FROM {$serendipity['dbPrefix']}images
                                   WHERE path LIKE '" . serendipity_db_escape_string($oldDir) . "%'", false, 'assoc');
    if (is_array($dirs)) {
        foreach($dirs AS $dir) {
            $old = $dir['path'];
            $new = preg_replace('@^(' . preg_quote($oldDir) . ')@i', $newDir, $old);
            // do we bother the authorid? It should be 0 (ALL) since ages.
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}images
                                     SET path = '" . serendipity_db_escape_string($new) . "'
                                   WHERE id = {$dir['id']}");
        }
    }

    $afdirs = serendipity_db_query("SELECT groupid, artifact_id, artifact_type, artifact_mode, artifact_index
                                      FROM {$serendipity['dbPrefix']}access
                                     WHERE artifact_type = 'directory'
                                       AND artifact_index LIKE '" . serendipity_db_escape_string($oldDir) . "%'", false, 'assoc');
    if (is_array($afdirs)) {
        foreach($afdirs AS $afdir) {
            $old = $afdir['artifact_index'];
            $new = preg_replace('@^(' . preg_quote($oldDir) . ')@i', $newDir, $old);
            serendipity_db_query("UPDATE {$serendipity['dbPrefix']}access
                                     SET artifact_index = '" . serendipity_db_escape_string($new) . "'
                                   WHERE groupid        = '" . serendipity_db_escape_string($afdir['groupid']) . "'
                                     AND artifact_id    = '" . serendipity_db_escape_string($afdir['artifact_id']) . "'
                                     AND artifact_type  = '" . serendipity_db_escape_string($afdir['artifact_type']) . "'
                                     AND artifact_mode  = '" . serendipity_db_escape_string($afdir['artifact_mode']) . "'
                                     AND artifact_index = '" . serendipity_db_escape_string($afdir['artifact_index']) . "'");
        }
    }

    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVED, $newDir) . "</span>\n";

    // hook into staticpage for the renaming regex replacements (no need to special care about thumb name, since this is simple dir renaming!)
    $renameValues = array(array(
        'from'    => null,
        'to'      => null,
        'thumb'   => $serendipity['thumbSuffix'],
        'fthumb'  => null,
        'oldDir'  => $oldDir,
        'newDir'  => $newDir,
        'type'    => 'dir',
        'item_id' => null,
        'file'    => null
    ));
    // Changing a ML directory via directoryEdit needs to run through staticpage entries too!
    serendipity_plugin_api::hook_event('backend_media_rename', $renameValues);

    if ($debug) {
        $serendipity['logger']->debug(print_r($renameValues,1));
    }
    return true;
}

/**
 * RENAME a real media file name [not a hotlinked, which is DB referenced only] and forward to staticpages.
 * @see SPLIT serendipity_moveMediaDirectory() part 2
 *
 * @access public
 * @param   string  Old directory name or empty
 * @param   string  New directory name with a trailing slash or empty
 * @param   string  The type of what to remove (file)
 * @param   string  An item id of a file
 * @param   array   Result of serendipity_fetchImageFromDatabase($id) for the previous file properties
 * @param   boolean Ad hoc debugging, set in wrapper serendipity_moveMediaDirectory()
 * @return  boolean
 */
function serendipity_renameRealFileName($oldDir, $newDir, $type, $item_id, $file, $debug=false) {
    global $serendipity;

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $logtag = 'renameRealFileName:';
        $serendipity['logger']->debug("IN serendipity_renameRealFileName");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_moveMediaDirectory') {
        echo 'Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    $parts = pathinfo($newDir);

    // build or rename: "new", "thumb" and "old file" names, relative to Serendipity "uploads/" root path, eg. "a/b/c/"

    // case rename only
    if ($oldDir === null && $newDir != 'uploadRoot/') {

        // case single file re-name event (newDir = newName is passed without path!)
        $newName = rtrim($newDir, '/'); // for better readability and removes the trailing slash in the filename

        // We don't need to care about $parts['extension'], since you can't change the EXT by the JS file rename event
        $file_new = $file['path'] . $newName;
        $file_old = $file['path'] . $file['name'];

        // build full thumb file names
        $_file_newthumb = $file['path'] . $newName . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $_file_oldthumb = $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $newThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_newthumb;
        $oldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_oldthumb;

    } else {
        if ($debug) { $serendipity['logger']->debug("$logtag 1 newDir=$newDir"); }
        // case bulkmove event (newDir is passed inclusive the path! and normally w/o the filename, but we better check this though)
        $newDir = ($newDir == 'uploadRoot/') ? '' : $newDir; // Take care: remove temporary 'uploadRoot/' string, in case of moving a subdir file into "uploads/" root directory by bulkmove
        if ($debug) { $serendipity['logger']->debug("$logtag 2 newDir=$newDir"); }
        $_newDir = str_replace($file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']), '', $newDir);
        if ($debug) { $serendipity['logger']->debug("$logtag 3_newDir=$_newDir"); }

        // We don't need to care about $parts['extension'], since you can't change the EXT via the bulkmove event
        $file_new = $_newDir . $file['name'];
        $file_old = $file['path'] . $file['name'];

    }

    // build full origin and new file path names for both events
    $newfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file_new . (empty($file['extension']) ? '' : '.' . $file['extension']);
    $oldfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file_old . (empty($file['extension']) ? '' : '.' . $file['extension']);

    if ($debug) {
        $serendipity['logger']->debug("$logtag oldfile=$oldfile");
        $serendipity['logger']->debug("$logtag newfile=$newfile");
    }

    // check files existence for both events
    if (file_exists($oldfile) && !file_exists($newfile)) {

        // for the paranoid, securely check these build filenames again, since we really need a real file set to continue!
        $newparts = pathinfo($newfile);
        if ($newparts['dirname'] == '.' || (!empty($file['extension']) && empty($newparts['extension'])) || empty($newparts['filename'])) {
            // error new file build mismatch
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . $newfile . ' ' . ERROR_SOMETHING . " (1)</span>\n";
            return false;
        }

        // we need to KEEP the old files thumbnail_name, for the case the global serendipity thumbSuffix has changed! A general conversion need to be done somewhere else.
        $fileThumbSuffix = !empty($file['thumbnail_name']) ? $file['thumbnail_name'] : $serendipity['thumbSuffix'];

        // Case re-name event, keeping a possible moved directory name for a single file
        if ($oldDir === null) {
            // Move the origin file
            @rename($oldfile, $newfile);
            // do not re-name again, if an item has no thumb name (eg. *.zip object file case) and old thumb eventually exists (possible missing PDF preview image on WinOS with IM)
            if (($newThumb != $newfile) && file_exists($oldThumb)) {
                // the thumb file
                @rename($oldThumb, $newThumb); // Keep both rename() errors disabled, since we have to avoid any output in renaming cases
            }

            // hook into staticpage for the renaming regex replacements
            $renameValues = array(array(
                'from'    => $oldfile,
                'to'      => $newfile,
                'thumb'   => $fileThumbSuffix,
                'fthumb'  => $file['thumbnail_name'],
                'oldDir'  => $oldDir,
                'newDir'  => $newDir,
                'type'    => $type,
                'item_id' => $item_id,
                'file'    => $file
            ));
            serendipity_plugin_api::hook_event('backend_media_rename', $renameValues);

            // renaming filenames has to update mediaproperties if set
            $q = "UPDATE {$serendipity['dbPrefix']}mediaproperties
                     SET value = '" . serendipity_db_escape_string($newName . (empty($file['extension']) ? '' : '.' . $file['extension'])) . "'
                   WHERE mediaid = " . (int)$item_id . ' AND property = "realname" AND value = "' . $file['realname'] . '"';
            serendipity_db_query($q);
            $q = "UPDATE {$serendipity['dbPrefix']}mediaproperties
                     SET value = '" . serendipity_db_escape_string($newName) . "'
                   WHERE mediaid = " . (int)$item_id . ' AND property = "name" AND value = "' . $file['name'] .'"';
            serendipity_db_query($q);
            $q = "UPDATE {$serendipity['dbPrefix']}mediaproperties
                     SET value = '" . serendipity_db_escape_string($newName . (empty($file['extension']) ? '' : '.' . $file['extension'])) . "'
                   WHERE mediaid = " . (int)$item_id . ' AND property = "TITLE" AND value = "' . $file['realname'] .'"';
            serendipity_db_query($q);

            serendipity_updateImageInDatabase(array('thumbnail_name' => $renameValues[0]['thumb'], 'realname' => $newName . (empty($file['extension']) ? '' : '.' . $file['extension']), 'name' => $newName), $item_id);

            // Forward user to overview (we don't want the user's back button to rename things again) ?? What does this do? Check!!!
        }

        // Case Move or Bulkmove event
        // newDir can now be used for the "uploads/" directory root path too
        // Do not allow an empty string OR NOT set newDir for the build call so we do not conflict with rename calls, which are single files only and is done above
        // BULKMOVE vars oldfile and newfile are fullpath based w/o EXT, see above
        elseif (!empty($newfile)) {

            // hook into staticpage for the renaming regex replacements
            $renameValues = array(array(
                'from'    => $oldfile,
                'to'      => $newfile,
                'thumb'   => $fileThumbSuffix,
                'fthumb'  => $file['thumbnail_name'],
                'oldDir'  => $oldDir,
                'newDir'  => $newDir,
                'type'    => $type,
                'item_id' => $item_id,
                'file'    => $file
            ));
            serendipity_plugin_api::hook_event('backend_media_rename', $renameValues); // eg. for staticpage entries path regex replacements

            // Move the origin file
            try {
                rename($oldfile, $newfile);
            } catch (Throwable $t) {
                // Executed only in PHP 7, will not match in PHP 5.x
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (2)</span>\n";
            } catch (Exception $e) {
                // Executed only in PHP 5.x, will not be reached in PHP 7
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$e->getMessage() . " (2)</span>\n";
            }

            // do we still need this? YES, it is definitely false, so we would not need the ternary - should already be done, maybe just paranoid :g
            // Rename newDir + file name in case it is called by the Bulk Move and not by rename
            $newDirFile = (false === strpos($newDir, $file['name'])) ? $newDir . $file['name'] : $newDir;

            foreach($renameValues AS $renameData) {
                // build full thumb file names
                $thisOldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['oldDir'] . $file['name'] . (!empty($renameData['fthumb']) ? '.' . $renameData['fthumb'] : '.' . $serendipity['thumbSuffix']) . (empty($file['extension']) ? '' : '.' . $file['extension']);
                $thisNewThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDirFile . (!empty($file['thumbnail_name']) ? '.' . $renameData['thumb'] : '.' . $serendipity['thumbSuffix']) . (empty($file['extension']) ? '' : '.' . $file['extension']);
                // Check for existent old thumb files first, to not need to disable rename by @rename(), then move the thumb file and catch any wrong renaming
                if (($thisNewThumb != $newfile) && file_exists($thisOldThumb)) {
                    // the thumb file and catch any wrong renaming
                    try {
                        rename($thisOldThumb, $thisNewThumb);
                    } catch (Throwable $t) {
                        // Executed only in PHP 7, will not match in PHP 5.x
                        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (3)</span>\n";
                    } catch (Exception $e) {
                        // Executed only in PHP 5.x, will not be reached in PHP 7
                        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$e->getMessage() . " (3)</span>\n";
                    }
                }
            }

            serendipity_updateImageInDatabase(array('thumbnail_name' => $renameValues[0]['thumb'], 'path' => $newDir, 'realname' => $file['realname'], 'name' => $file['name']), $item_id);
            // Forward user to overview (we don't want the user's back button to rename things again)
        } else {
            //void
        }
    } else {
        if (!file_exists($oldfile)) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_NOT_EXISTS . "</span>\n";
        } elseif (file_exists($newfile)) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_EXISTS . "</span>\n";
        } else {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . " (4)</span>\n";
        }
        return false;
    }
    return true;
}

/**
 * RENAME a real media dirfilename [not a hotlinked, which is DB referenced only] and forward to staticpages.
 * Used solely by serendipity_parsePropertyForm() base_properties, when changing the file selected path via mediaproperties form.
 *
 * @see SPLIT serendipity_moveMediaDirectory() part 3
 *
 * @access public
 * @param   string  Old directory name or empty
 * @param   string  New directory name with a trailing slash or empty
 * @param   string  The type of what to remove (filedir)
 * @param   string  An item id of a file
 * @param   boolean Ad hoc debugging, set in wrapper serendipity_moveMediaDirectory()
 * @return
 */
function serendipity_renameRealFileDir($oldDir, $newDir, $type, $item_id, $debug=false) {
    global $serendipity;

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $serendipity['logger']->debug("IN serendipity_renameRealFileDir");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_moveMediaDirectory') {
        echo 'Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    if ($oldDir != $newDir) {
        serendipity_updateImageInDatabase(array('path' => $newDir), $item_id);
    } else {
        return false;
    }

    // pick up the file array properties with the newly updated path for against checks
    $_file = serendipity_db_query("SELECT * FROM  {$serendipity['dbPrefix']}images
                                    WHERE id = " . (int)$item_id, true, 'assoc');

    // Move thumbs - Rebuild full origin and new file path names by the new picked file array
    $oldfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . $_file['name'] . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
    $newfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . $_file['name'] . (empty($_file['extension']) ? '' : '.' . $_file['extension']);

    // we need to KEEP the old files thumbnail_name (for the staticpage hook only in this case), for the case the global serendipity thumbSuffix has changed! A general conversion need to be done somewhere else.
    $fileThumbSuffix = !empty($_file['thumbnail_name']) ? $_file['thumbnail_name'] : $serendipity['thumbSuffix'];

    // hook into staticpage for the renaming regex replacements
    $renameValues = array(array(
        'from'    => $oldfile,
        'to'      => $newfile,
        'thumb'   => $fileThumbSuffix,
        'fthumb'  => $_file['thumbnail_name'],
        'oldDir'  => $oldDir,
        'newDir'  => $newDir,
        'type'    => $type,
        'item_id' => $item_id,
        'file'    => $_file,
        'name'    => $_file['name']
    ));
    serendipity_plugin_api::hook_event('backend_media_rename', $renameValues);

    // Move the origin file
    try {
        rename($oldfile, $newfile);
    } catch (Throwable $t) {
        // Executed only in PHP 7, will not match in PHP 5.x
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (5)</span>\n";
    } catch (Exception $e) {
        // Executed only in PHP 5.x, will not be reached in PHP 7
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$e->getMessage() . " (5)</span>\n";
    }

    foreach($renameValues AS $renameData) {
        $thisOldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . $_file['name'] . (!empty($renameData['fthumb']) ? '.' . $renameData['fthumb'] : '') . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
        $thisNewThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . $_file['name'] . (!empty($_file['thumbnail_name']) ? '.' . $_file['thumbnail_name'] : '') . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
        // Check for existent old thumb files first, to not need to disable rename by @rename(), then move the thumb file and catch any wrong renaming
        if (($thisNewThumb != $newfile) && file_exists($thisOldThumb)) {
            // Move the thumb file and catch any wrong renaming
            try {
                rename($thisOldThumb, $thisNewThumb);
            } catch (Throwable $t) {
                // Executed only in PHP 7, will not match in PHP 5.x
                // reset already updated image table
                serendipity_updateImageInDatabase(array('path' => $oldDir), $item_id);
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (6)</span>\n";
            } catch (Exception $e) {
                // Executed only in PHP 5.x, will not be reached in PHP 7
                // reset already updated image table
                serendipity_updateImageInDatabase(array('path' => $oldDir), $item_id);
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$e->getMessage() . " (6)</span>\n";
            }
        }
    }
    // ???? Forward user to overview (we don't want the user's back button to rename things again)

    // prepare for message
    $thisnew = (empty($newDir) ? $serendipity['uploadPath'] : '') . $newDir . $_file['name'];
    $thisExt = isset($_file['extension']) ? '.'.$_file['extension'] : '';

    if (file_exists($newfile)) {
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVED, $thisnew . $thisExt) . "</span>\n";
    }
    return $_file;
}

/**
 * RENAME a MEDIA dir or filename in existing entries
 * @see SPLIT serendipity_moveMediaDirectory() part 4
 *
 * @access public
 * @param   string  Old directory name or empty
 * @param   string  New directory name with a trailing slash or empty
 * @param   string  The type of what to remove (dir|file|filedir)
 * @param   array   Properties result of new updated query,
 *                      @see serendipity_renameRealFileDir() and serendipity_moveMediaDirectory()
 * @param   array   Result of origin (old) serendipity_fetchImageFromDatabase($id)
 * @param   boolean Ad hoc debugging, set in wrapper serendipity_moveMediaDirectory()
 * @return
 */
function serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $pick=null, $file, $debug=false) {
    global $serendipity;

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $logtag = 'moveMediaInEntriesDB:';
        $serendipity['logger']->debug("IN serendipity_moveMediaInEntriesDB");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_moveMediaDirectory') {
        echo 'Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    // type file path param by rename is the filename w/o EXT only
    // type file path param by bulkmove is the relative dir/+filename w/o EXT
    // type filedir path param via media mediaproperties form as a relative dir/
    // type dir path param by DirectoryEdit is a relative dir/

    // get the correct $file properties, which is either array or null by type, and are the origin or already updated properties (which then is $pick in case of filedir typed directory renaming actions)
    $_file = ($type == 'filedir' && $pick !== null) ? $pick : $file;

    // Prepare the SELECT query for filetypes
    if ($type == 'filedir' || $type == 'file') {

        // strictly replace FILE+EXT check the oldDir in bulkmove case only,
        $oldDir = ($type == 'file' && !is_null($oldDir)) ? str_replace($_file['name'].'.'.$_file['extension'], '', $oldDir) : $oldDir;
        // since $oldDir is the path structure only, relative down below "uploads", eg. "a/b/c/"

        // Path patterns to SELECT en detail with EXT, to not pick entries path parts in a loop
        if ($oldDir === null) {// care for a file renaming with oldpath
            $oldDirFile  = $_file['path'] . $_file['name'] . (!empty($_file['extension']) ? '.'.$_file['extension'] : '');
            $oldDirThumb = $_file['path'] . $_file['name'] . '.' . $_file['thumbnail_name'] . (!empty($_file['extension']) ? '.'.$_file['extension'] : '');
        } else {
            $oldDirFile  = $oldDir . $_file['name'] . (!empty($_file['extension']) ? '.'.$_file['extension'] : '');
            $oldDirThumb = $oldDir . $_file['name'] . '.' . $_file['thumbnail_name'] . (!empty($_file['extension']) ? '.'.$_file['extension'] : '');
        }

        $ispOldFile = $serendipity['serendipityPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile;
        if ($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') {
            $joinThumbs = "|" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb);
        } else {
            // works w/ or w/o the braces! (see follow-up sql queries)
            $entry_joinThumbs = " OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%') OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%')";
            $stapa_joinThumbs = " OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%') OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%')";
        }

    } elseif ($type == 'dir') {
        // since this is case 'dir', we do not have a filename and have to rename replacement File vars to oldDir and newDir values for the update preg_replace match
        $oldDirFile = $oldDir;
        $ispOldFile = $serendipity['serendipityPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile . (($_file['extension']) ? '.'.$_file['extension'] : '');
        $joinThumbs = ''; // we don't need to join Thumbs in special, since this is the 'dir' type case only! (Fixes matching and the counter!)
    }

    // Please note: IMAGESELECTORPLUS plugin quickblog option is either quickblog:FullPath or quickblog:|?(none|plugin|js|_blank)|FullPath
    // SELECTing the entries uses a more detailed approach to be as precise as possible, thus we need to reset these vars for the preg_replace later on in some cases
    if ($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') {
        $q = "SELECT id, body, extended
                FROM {$serendipity['dbPrefix']}entries
               WHERE body     REGEXP '(src=|href=|window.open.|<!--quickblog:)(\'|\"|\\\|?(plugin|none|js|_blank)?\\\|?)(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . "|" . serendipity_db_escape_String($ispOldFile) . ")'
                  OR extended REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . ")'";
    } else {
        $q = "SELECT id, body, extended
                FROM {$serendipity['dbPrefix']}entries
               WHERE body LIKE '%<!--quickblog:%" . serendipity_db_escape_String($ispOldFile) . "-->%'
                  OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')
                  OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')" . $entry_joinThumbs . "";
    }
    $entries = serendipity_db_query($q, false, 'assoc');

    if ($debug) {
        $serendipity['logger']->debug("$logtag SQL QUERY: \n$q");
        $did = array(); // init for NULL cases
        if (is_array($entries)) {
            foreach($entries AS $d) { $did[] = $d['id']; }
            reset($entries);
        }
        if (is_string($entries) && !empty($entries)) {
            $serendipity['logger']->debug("$logtag DB ERROR! Entries serendipity_db_query returned: $entries");
        }
        if (!empty($did)) {
            $serendipity['logger']->debug("$logtag FOUND Entry ID: " . implode(', ', $did));
        } else {
            $serendipity['logger']->debug("$logtag Found NO ENTRIES to change");
        }
        $serendipity['logger']->debug("$logtag Change IMAGESELECTORPLUS ispOldFile=$ispOldFile");
    }

    if ($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') {
        $sq = "SELECT id, content, pre_content
                 FROM {$serendipity['dbPrefix']}staticpages
                WHERE content     REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . ")'
                   OR pre_content REGEXP '(src=|href=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . ")'";
    } else {
        $sq = "SELECT id, content, pre_content
                 FROM {$serendipity['dbPrefix']}staticpages
                WHERE (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')
                   OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')" . $stapa_joinThumbs . "";
    }
    $spages = serendipity_db_query($sq, false, 'assoc');

    if ($debug) { $serendipity['logger']->debug("$logtag ADDITIONAL-SELECT staticpages DB::sp:\n$sq"); }

    // prepare preg/replace variables for both entry and/or staticpage cases
    if ((is_array($entries) && !empty($entries)) || (is_array($spages) && !empty($spages))) {

        // Prepare the REPLACE $newDirFile string for filetypes
        if ($type == 'filedir' || $type == 'file') {
            // newDir + file name in case it is a 'filedir' path OR a file called by the Bulk Move, BUT NOT by rename
            if ($type == 'filedir' || ($type == 'file' && $oldDir !== null)) {
                $newDirFile = (false === strpos($newDir, $_file['name'])) ? $newDir . $_file['name'] : $newDir;
            }
            if (isset($newDirFile)) $newDirFile = ($newDirFile == 'uploadRoot/'.$_file['name']) ? str_replace('uploadRoot/', '', $newDirFile) : $newDirFile;
            if ($type == 'file' && $oldDir === null) {
                $newDirFile = $newDir;
            }
        } elseif ($type == 'dir') {
            $newDirFile = $newDir;
        } else {
            // paranoid fallback case
            $newDirFile = rtrim($newDir, '/');
        }
        if ($debug) { $serendipity['logger']->debug("$logtag newDirFile=$newDirFile"); }
        if ($debug) { $serendipity['logger']->debug("$logtag ISP newDir=$newDir"); }
        // for thumbs only - Rebuild full origin and new file path names by the newly "$pick"ed file array
        $oldfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . $_file['name'] . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
        $newfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . $_file['name'] . (empty($_file['extension']) ? '' : '.' . $_file['extension']);

        // here we need to match THUMBS too, so we do not want the extension, see detailed SELECT regex note
        if ($type == 'file' && $oldDir === null) {
            $_ispOldFile = $oldfile; // this is more exact in every case [YES!]
            $_ispNewFile = $serendipity['serendipityPath'] . $serendipity['uploadHTTPPath'] . $newDirFile . (($_file['extension']) ? '.'.$_file['extension'] : '');
            $newDirFile = $_file['path'] . $newDirFile; // newDirFile is missing a possible subdir path for the preg_replace (w/o EXT!)
            if ($debug) { $serendipity['logger']->debug("$logtag REPLACE IMAGESELECTORPLUS[type=$type] _ispNewFile=$_ispNewFile"); }
        } else {
            $_ispOldFile = $ispOldFile;
            $_ispNewFile = $serendipity['serendipityPath'] . $serendipity['uploadHTTPPath'] . $newDirFile . (($_file['extension']) ? '.'.$_file['extension'] : '');
            if ($debug) { $serendipity['logger']->debug("$logtag REPLACE IMAGESELECTORPLUS[type=$type(2)] _ispNewFile=$_ispNewFile"); }
        }
        // LAST paranoid check - WHILE FIXING WRONG ENTRIES LATER ON IS A HELL! :)
        // Get to know the length of file EXT
        $lex = strlen($_file['extension']);
        //  to check the oldDirFile string backwards with a flexible substr offset ending with a "dot extension"
        if ($type == 'file') {
            $_oldDirFile = ('.'.substr($oldDirFile, -$lex) != '.'.$_file['extension']) ? $oldDirFile : $_file['path'] . $_file['name'];
        } else { // cases 'filedir' and 'dir'
            $_oldDirFile = (FALSE !== strrpos($oldDirFile, '.'.$_file['extension'], -($lex+1))) ? str_replace('.'.$_file['extension'], '', $oldDirFile) : $oldDirFile;
        }
        if ($debug) {
            $serendipity['logger']->debug("$logtag REPLACE IMAGESELECTORPLUS _ispOldFile=$_ispOldFile to _ispNewFile=$_ispNewFile");
            $serendipity['logger']->debug("$logtag REPLACE _oldDirFile=$_oldDirFile");
            $serendipity['logger']->debug("$logtag REPLACE  newDirFile=$newDirFile");
        }

        // Check for special cased media object links
        $oldLink = $_file['name'] . '.'.$_file['extension']; // basename of oldlink with extension
        $newLink = str_replace($_oldDirFile, $newDirFile, $oldLink);
        $newLinkHTTPPath = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_file['path'] . $newLink;
        $link_pattern = '<a class="block_level opens_window" href="' . $newLinkHTTPPath . '" title="' . $oldLink . '"><!-- s9ymdb:' . $_file['id'] . ' -->' . $oldLink . '</a>';
        $link_replace = '<a class="block_level opens_window" href="' . $newLinkHTTPPath . '" title="' . $newLink . '"><!-- s9ymdb:' . $_file['id'] . ' -->' . $newLink . '</a>';

        if (is_array($entries) && !empty($entries)) {

            // What we really need here, is oldDirFile w/o EXT to newDirFile w/o EXT, while in need to match the media FILE and the media THUMB
            // and the full ispOldFile path to the full ispNewFile path for IMAGESELECTORPLUS inserts.
            foreach($entries AS $entry) {
                $id = serendipity_db_escape_string($entry['id']);
                $entry['body']     = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $entry['body']);
                $entry['body']     = preg_replace('@(<!--quickblog:)(\\|?(plugin|none|js|_blank)?\\|?)(' . preg_quote($_ispOldFile) . ')@', '\1\2' . $_ispNewFile, $entry['body']);
                $entry['extended'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $entry['extended']);
                $entry['body']     = str_replace($link_pattern, $link_replace, $entry['body']);
                $entry['extended'] = str_replace($link_pattern, $link_replace, $entry['extended']);

                $uq = "UPDATE {$serendipity['dbPrefix']}entries
                          SET body = '" . serendipity_db_escape_string($entry['body']) . "' ,
                          extended = '" . serendipity_db_escape_string($entry['extended']) . "'
                        WHERE   id = $id";
                serendipity_db_query($uq);

                // SAME FOR ENTRIES ENTRYPROPERTIES CACHE for ep_cache_body (we do not need to care about SELECTing ISP items, since that is already a result of $entries array)
                $epq1 = "SELECT entryid, value
                           FROM {$serendipity['dbPrefix']}entryproperties
                          WHERE entryid = $id AND property = 'ep_cache_body'";
                if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_body):ID:$id\n$epq1"); }
                $eps1 = serendipity_db_query($epq1, false, 'assoc');
                if (is_array($eps1)) {
                    $eps1['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $eps1['value']);
                    $eps1['value'] = preg_replace('@(<!--quickblog:)(\\|?(plugin|none|js|_blank)?\\|?)(' . preg_quote($_ispOldFile) . ')@', '\1\2' . $_ispNewFile, $eps1['value']);
                    $eps1['value'] = str_replace($link_pattern, $link_replace, $eps1['value']);
                    $uepq1 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                 SET value = '" . serendipity_db_escape_string($eps1['value']) . "'
                               WHERE entryid =  " . serendipity_db_escape_string($eps1['entryid']) . "
                                 AND property = 'ep_cache_body'";
                    if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT-UPDATE entryproperties DB: ENTRY_ID:{$eps1['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_body) SUB-UPDATE " .DONE); }
                    serendipity_db_query($uepq1);
                } // no need for else thrown mysql/i error message
                // SAME FOR ENTRIES ENTRYPROPERTIES CACHE for ep_cache_extended
                $epq2 = "SELECT entryid, value
                           FROM {$serendipity['dbPrefix']}entryproperties
                          WHERE entryid = $id AND property = 'ep_cache_extended'";
                if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_extended):ID:$id\n$epq2"); }
                $eps2 = serendipity_db_query($epq2, false, 'assoc');
                if (is_array($eps2)) {
                    $eps2['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $eps2['value']);
                    $eps2['value'] = str_replace($link_pattern, $link_replace, $eps2['value']);
                    $uepq2 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                 SET value = '" . serendipity_db_escape_string($eps2['value']) . "'
                               WHERE entryid =  " . serendipity_db_escape_string($eps2['entryid']) . "
                               AND property = 'ep_cache_extended'";
                    if ($debug) { $serendipity['logger']->debug("$logtag SUB-SELECT-UPDATE entryproperties DB: ENTRY_ID:{$eps2['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_extended) SUB-UPDATE " .DONE); }
                    serendipity_db_query($uepq2);
                } // no need for else thrown mysql/i error message
            }

            if ($debug) {
                $serendipity['logger']->debug("$logtag transported file " . print_r($_file, 1));
                $serendipity['logger']->debug("$logtag AFTER regexed entry BODY $oldLink = $newLink");
                $serendipity['logger']->debug("$logtag AFTER regexed entry BODY newLinkHTTPPath = $newLinkHTTPPath");
                $serendipity['logger']->debug("$logtag AFTER regexed entry BODY linkpattern = $link_pattern");
                $serendipity['logger']->debug("$logtag AFTER regexed entry BODY linkreplace = $link_replace");
                $serendipity['logger']->debug("$logtag THE NEW regexed entry BODY = {$entry['body']}");
            }
        }

        // SAME FOR STATICPAGES (w/o isp) - down here for case there were no entries items done before
        if (is_array($spages) && !empty($spages)) {
            if ($debug) {
                $serendipity['logger']->debug("$logtag STATICPAGE REPLACE _oldDirFile=$_oldDirFile");
                $serendipity['logger']->debug("$logtag STATICPAGE REPLACE  newDirFile=$newDirFile");
            }
            $spmdbitems = 0;
            foreach($spages AS $spage) {
                $spage['content']     = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $spage['content']);
                $spage['pre_content'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $spage['pre_content']);
                $spage['content']     = str_replace($link_pattern, $link_replace, $spage['content']);
                $spage['pre_content'] = str_replace($link_pattern, $link_replace, $spage['pre_content']);

                $pq = "UPDATE {$serendipity['dbPrefix']}staticpages
                          SET content = '" . serendipity_db_escape_string($spage['content']) . "' ,
                              pre_content = '" . serendipity_db_escape_string($spage['pre_content']) . "'
                        WHERE id =  " . serendipity_db_escape_string($spage['id']);

                if ($debug) { $serendipity['logger']->debug("$logtag ADDITIONAL-UPDATE staticpages DB: ID:{$spage['id']} {$serendipity['dbPrefix']}staticpages::[content|pre_content] UPDATE " .DONE); }
                serendipity_db_query($pq);
                // count the staticpage entry media items changed
                $spmdbitems++;
            }
            if ($debug) { $serendipity['logger']->debug("$logtag ADDITIONAL-UPDATE staticpages DB: ID:{$spage['id']} UPDATE renamed $spmdbitems items "); }
        }

        // spawn the messages
        if ($oldDir !== null) {
            echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVE_ENTRIES, count($entries)) . "</span>\n";
        } else {
            // This first is renaming file event - pushes message to layer - (not really true, but better than nothing, or adding another constant just for this case)
            if ($oldDir === null) {
                echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DONE . '! ' . sprintf(FILE_UPLOADED, $_file['name'], $newDir) . "</span>\n";
            }
            if (is_array($entries) && !empty($entries) && count($entries) > 0) {
                echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_FILE_RENAME_ENTRY, count($entries)) . "</span>\n";
            }
            if (is_array($spages) && !empty($spages) && count($spages) > 0 && $spmdbitems > 0) {
                echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_FILE_RENAME_ENTRY, count($spages) . ' (staticpages)') . "</span>\n";
            }
        }
    } // entries OR staticpages end
    else {
        if (($serendipity['dbType'] == 'mysqli' || $serendipity['dbType'] == 'mysql') && $serendipity['production'] && (is_string($entries) || is_string($spages))) {
            // NOTE: keep "error" somewhere in echoed string since that is the matching JS condition
            echo '<span class="msg_error"><span class="icon-info-attention" aria-hidden="true"></span> DB ERROR: ' . (!empty($entries) ? $entries : $spages) . "</span>\n";
        }
    }
}

/**
 * Moves a media directory and or file. A wrapper for
 *
 * 1. case type 'dir' via 'directoryEdit':
 *              serendipity_renameDirAccess($oldDir, $newDir)
 * 2. case type 'file' as a single file id via (looped bulkmove) 'multicheck':
 *                     as a single file id via 'rename':
 *              serendipity_renameRealFileName($oldDir, $newDir, $type, $item_id, $file)
 * 3. case type 'filedir' via this API serendipity_parsePropertyForm() as base_properties only, when changing the file selected path within mediaproperties form:
 *              serendipity_renameRealFileDir($oldDir, $newDir, $type, $item_id)
 *
 * and LASTLY to update the entries in the database
 *              serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $pick, $file)
 *
 * @param  string   The old directory.
 *                  This can be NULL or (an empty / a) STRING for re-name/multiCheck move comparison events
 * @param  string   The new directory
 * @param  string   The type of what to remove (dir|file|filedir)
 * @param  string   An item id of a file
 * @param  array    Result of serendipity_fetchImageFromDatabase($id)
 * @return boolean
 *
 */
function serendipity_moveMediaDirectory($oldDir, $newDir, $type = 'dir', $item_id = null, $file = null) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging
    $pick = null;

    // Since being a wrapper function, this enables logging of all sub functions
    $debug = is_object(@$serendipity['logger']) && $debug; // ad hoc debug + enabled logger

    // paranoid case for updating an old image id entry - else we have a new entry incrementation
    if (is_null($item_id) && isset($file['id']) && $file['id'] > 0) $item_id = $file['id'];

    if (!$item_id || $item_id < 1) {
        // only print message if not posting a case_directoryEdit submit
        if (empty($serendipity['POST']['save'])) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(ERROR_FILE_NOT_EXISTS, $item_id) . "</span>\n";
            return false;
        }
    }
    // Prepare data for the database, any hooks and the real file move, by case AREA:
    //   DIR     = Media directory form edit,
    //   FILE    = File rename or File bulk move,
    //   FILEDIR = Media properties form edit

    if ($debug) { $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START moveMediaDirectory SEPARATOR" . str_repeat(" <<< ", 10) . "\n"); }

    // case 'dir' via images.inc case 'directoryEdit', which is ML Directories form, via ML case 'directorySelect'
    if ($type == 'dir') {

        //   MEDIADIRFORM existing directory renames need a trailing slash in here and later on in serendipity_moveMediaInEntriesDB()
        $newDir = (!empty($newDir) && $newDir != '/') ? rtrim($newDir, '/') . '/' : $newDir;

        // rename in database
        if (false === serendipity_renameDirAccess($oldDir, $newDir, $debug)) {
            return false;
        }

    // case 'rename' OR 'multicheck' (bulk multimove)
    } else if ($type == 'file') {

        // active in mean of eval or executable
        if (serendipity_isActiveFile(basename($newDir))) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(ERROR_FILE_FORBIDDEN, serendipity_specialchars($newDir)) . "</span>\n";
            return false;
        }

        if (!empty($file['hotlink'])) {

            $newHotlinkFile = (false === strpos($newDir, $file['extension'])) ? $newDir . (empty($file['extension']) ? '' : '.' . $file['extension']) : $newDir;
            serendipity_updateImageInDatabase(array('realname' => $newHotlinkFile, 'name' => $newDir), $item_id);

        } else {
            // check return!
            if (false === serendipity_renameRealFileName($oldDir, $newDir, $type, $item_id, $file, $debug)) {
                return false;
            }
        }

    // Used solely by this API serendipity_parsePropertyForm() base_properties only, when changing the file selected path within mediaproperties form
    } elseif ($type == 'filedir') {

        $pick = serendipity_renameRealFileDir($oldDir, $newDir, $type, $item_id, $debug);
        if ($pick === false) {
            return false;
        }

    } // case dir, file, filedir end

    // Entry REPLACEMENT AREA

    // Only MySQL supported, since I don't know how to use REGEXPs differently.
    // Ian: Whoever wrote this; We should improve this to all!
    //      Remove completely, when new LIKE solution found working overall!
    #if (!in_array($serendipity['dbType'], ['mysql', 'mysqli', 'sqlite3', 'sqlite3oo', 'pdo-sqlite'])) {
    #    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . MEDIA_DIRECTORY_MOVE_ENTRY . "</span>\n";
    #    return true;
    #}

    if (false === serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $pick, $file, $debug)) {
        return false;
    }

    return true;
}

/**
 * Show the Media Library
 *
 * @access  public
 * @param   bool    default false
 * @param   array   $smarty_vars
 * @return  string  Image list
 */
function showMediaLibrary($addvar_check = false, $smarty_vars = array()) {
    global $serendipity;

    if (!serendipity_checkPermission('adminImagesView')) {
        return;
    }
    $output = '';

    // After upload, do not show the list to be able to proceed to
    // media selection.
    if ($addvar_check && !empty($GLOBALS['image_selector_addvars'])) {
        return true;
    }

    if (!isset($serendipity['thumbPerPage'])) {
        $serendipity['thumbPerPage'] = 2;
    }
    $smarty_vars = array(
        'textarea' => isset($serendipity['GET']['textarea']) ? $serendipity['GET']['textarea'] : false,
        'htmltarget' => isset($serendipity['GET']['htmltarget']) ? $serendipity['GET']['htmltarget'] : '',
        'filename_only' => isset($serendipity['GET']['filename_only']) ? $serendipity['GET']['filename_only'] : false,
    );

    $output .= serendipity_displayImageList(
        isset($serendipity['GET']['page']) ? $serendipity['GET']['page'] : 1,
        $serendipity['thumbPerPage'],
        isset($serendipity['GET']['showMediaToolbar']) ? serendipity_db_bool($serendipity['GET']['showMediaToolbar']) : true,
        NULL,
        isset($serendipity['GET']['showUpload']) ? $serendipity['GET']['showUpload'] : false,
        NULL,
        $smarty_vars
    );

    return $output;
}

/**
 * Gets all available media directories
 *
 * @return array
 *
 */
function &serendipity_getMediaPaths() {
    global $serendipity;

    $aExclude = array('CVS' => true, '.svn' => true, '_vti_cnf' => true); // add _vti_cnf to exclude possible added servers frontpage extensions
    serendipity_plugin_api::hook_event('backend_media_path_exclude_directories', $aExclude);

    $paths        = array();
    $aResultSet   = serendipity_traversePath(
        $serendipity['serendipityPath'] . $serendipity['uploadPath'],
        '',
        false,
        NULL,
        1,
        NULL,
        FALSE,
        $aExclude
    );

    foreach($aResultSet AS $sKey => $sFile) {
        if ($sFile['directory']) {
            array_push($paths, $sFile);
        }
        unset($aResultSet[$sKey]);
    }
    serendipity_directoryACL($paths, 'read');

    usort($paths, 'serendipity_sortPath');

    return $paths;
}

/**
 * Checks whether a user has access to write into a directory
 *
 * @access public
 * @param   string Directory to check
 * @return  boolean
 */
function serendipity_checkDirUpload($dir) {
    global $serendipity;

    /*
    if (serendipity_checkPermission('adminImagesMaintainOthers')) {
        return true;
    }
    */

    $allowed  = serendipity_ACLGet(0, 'directory', 'write', $dir);
    $mygroups = serendipity_checkPermission(null, null, true);

    // Usergroup "0" always means that access is granted. If no array exists, no ACL restrictions have been set and all is fine.
    if (!is_array($allowed) || isset($allowed[0])) {
        return true;
    }

    if (!is_array($mygroups)) {
        return true;
    }

    foreach($mygroups AS $grpid => $grp) {
        if (isset($allowed[$grpid])) {
            return true;
            break;
        }
    }

    return false;
}
