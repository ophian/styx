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

    $core = preg_match('@.(php.*|[psj]html?|pht|aspx?|cgi|jsp|py|pl)$@i', $file);
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

    // Normally in MediaLibrary views only!
    // Set filters have to avoid using hideSubDirFiles - so we need to check for something unique in filters and check every values state to find out - otherwise be consistent for category
    if (!empty($serendipity['GET']['filter'])) {
        $sfilters = array_filter($serendipity['GET']['filter']);
        // reset for empty value iteration
        if (!empty($sfilters['fileCategory'])) {
            if ($sfilters['fileCategory'] == 'all') {
                $sfilters['fileCategory'] = '';
            } else {
                $fCategory = true;
            }
        }
    }
    $sfilter  = isset($sfilters) ? serendipity_emptyArray($sfilters) : true;
    $nofilter = $fCategory ?? $sfilter;

    if ($hideSubdirFiles && $nofilter) {
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
        if (! (isset($orderfields[$f]) || $f == 'fileCategory' || $f == 'by.extension') || empty($fval)) {
            continue;
        }
        if (is_array($fval)) {
            // A serendipity_generateVariations() filter intrusion to help getting same result sets for the part range runs
            if (!empty($filter['by.extension'])) {
                $cond['parts']['filter'] = " AND (i.extension IN ('" . serendipity_db_implode("', '", $fval, 'string') . "'))\n";
            }

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
    $cond['joins'] = $cond['joins'] ?? '';
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
        serendipity_db_query("UPDATE {$serendipity['dbPrefix']}images SET ". implode(',', $q) .' WHERE id = ' . (int)$id . " $admin");
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

    $_file = serendipity_fetchImageFromDatabase($id);

    if ($serendipity['useWebPFormat'] || $serendipity['useAvifFormat']) {
        // get a possible image variations id (should only be if that was development or somethings has went wrong)
        $vfile = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}images AS i WHERE path = '.v/' AND name = '{$_file['name']}' AND (extension = 'webp' OR extension = 'avif')", true, 'assoc');
        $files = is_array($vfile) ? [ $_file, $vfile ] : [ $_file ];
    } else {
        $files = [ $_file ];
    }

    if (!is_array($files)) {
        $messages .= sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . FILE_NOT_FOUND . "</span>\n", $id);
        //return false;
    } else {
        foreach ($files AS $file) {
            $dFile = $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

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
                    if (unlink($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dFile)) {
                        // Silently delete an already generated .v/origin.[webp|avif] variation file too
                        serendipity_syncUnlinkVariation($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dFile);
                        $messages .= sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DELETE_FILE . "</span>\n", $dFile);
                    } else {
                        $messages .= sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . DELETE_FILE_FAIL . "</span>\n", $dFile);
                    }

                    serendipity_plugin_api::hook_event('backend_media_delete', $dThumb);
                    foreach($dThumb AS $thumb) {
                        $dfnThumb = $file['path'] . $file['name'] . (!empty($thumb['fthumb']) ? '.' . $thumb['fthumb'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
                        $dfThumb  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $dfnThumb;

                        if (file_exists($dfThumb) && unlink($dfThumb)) {
                            // Silently delete an already generated .v/originthumb.[webp|avif] variation file too
                            serendipity_syncUnlinkVariation($dfThumb);
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
        $usedSuffixes    = serendipity_db_query("SELECT DISTINCT(thumbnail_name) AS thumbSuffix FROM {$serendipity['dbPrefix']}images WHERE thumbnail_name != ''", false, 'num');
        $thumbSuffixes   = is_array($usedSuffixes) ? call_user_func_array('array_merge', $usedSuffixes) : array();
        $thumbSuffixes[] = $serendipity['thumbSuffix']; // might be set to 'styxThumb' for new version
        $thumbSuffixes[] = 'serendipityThumb'; // might be the old suffix name - which should usually be inside usedSuffixes, but if not, hardcode it here to make sure!
        $thumbSuffixes[] = '.quickblog'; // an out-of-range imageselectorplus created thumb
        $serendipity['uniqueThumbSuffixes'] = array_values(array_unique($thumbSuffixes)); // only use unique strpos() search values
        if ($reverse) {
            $serendipity['uniqueThumbSuffixes'] = array_diff($serendipity['uniqueThumbSuffixes'], array($serendipity['thumbSuffix'], '.quickblog'));
        }
        $serendipity['uniqueThumbSuffixes'] = array_values($serendipity['uniqueThumbSuffixes']);
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
 * Checks the image database by image name
 * OR count by wildcard for possible "upcounted" naming doubles e.g. "name3.png" to avoid having non-singularities
 *
 * @param  string   The files name - in 2cd case push 'name%' wildcard
 * @param  boolean  Use sum counter
 * @return mixed    Result-set of images or Sum
 */
function serendipity_fetchImagesByName($file, $sum = false) {
    global $serendipity;

    $field = $sum ? 'count(*)' : '*';
    $rtype = $sum ? 'num' : 'assoc';
    $query = "SELECT $field FROM {$serendipity['dbPrefix']}images WHERE name LIKE '" . serendipity_db_escape_String($file) . "' ORDER BY name ASC";
    $res = serendipity_db_query($query, true, $rtype);

    return $res;
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
        $fdim     = @serendipity_getImageSize($tempfile, '', $extension);
        $width    = $fdim[0];
        $height   = $fdim[1];
        $mime     = $fdim['mime'];
        unlink($tempfile);
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

    $fdim   = @serendipity_getImageSize($filepath, '', $extension);
    $width  = $fdim[0] ?? 0; // this check is temporary getimagesize() hotfix related since uploaded avif images have no sizes yet by default
    $height = $fdim[1] ?? 0; // ditto
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
 * Helper function for creating WebP formatted images with the PHP GD library
 * @see serendipity_imageGDWebPConversion()
 *
 * @param string $infile   The fullpath file format from string
 * @return typed image
 */
function serendipity_imageCreateFromAny($filepath) {
    if (function_exists("exif_imagetype")) {
        $type = exif_imagetype($filepath);
    } else {
        $type = getImageSize($filepath)[2];
    }
    // default fallback so that $type is defined
    if (!is_int($type)) {
        $type = IMAGETYPE_JPEG;
    }

#        1,  // [] gif/* [muted for GD "Fatal error: Paletter image not supported by webp"] */
    $allowedTypes = array(
        2,  // [] jpg
        3,  // [] png
        6   // [] bmp
        );
    if (!in_array($type, $allowedTypes)) {
        return false;
    }
    switch ($type) {
        case 1:
            $im = imagecreatefromgif($filepath);
            break;
        case 2:
            $im = imagecreatefromjpeg($filepath);
            break;
        case 3:
            $im = imagecreatefrompng($filepath);
            break;
        case 6:
            $im = imagecreatefrombmp($filepath);
            break;
    }
    return $im;
}

/**
 * Convert supported images to a new file image format
 * and link to/run all relevant follow-up actions, which are:
 *  1. real file change,
 *  2. real file thumb change,
 *  3. database entry changes,
 *  4. database ep cache changes,
 *  5. database staticpage changes
 *
 * @param array  $file      The files current database properties
 * @param string $oldMime   The files current mime format
 * @param string $newMime   The files new mime format
 * @return bool
 */
function serendipity_convertImageFormat($file, $oldMime, $newMime) {
    switch ($newMime) {
        case 'image/jpg':
        case 'image/jpeg':
            $new['extension'] = image_type_to_extension(IMAGETYPE_JPEG, false); // this and the others are without the dot and returned lowercased
            break;
        case 'image/png':
            $new['extension'] = image_type_to_extension(IMAGETYPE_PNG, false);
            break;
        case 'image/gif':
            $new['extension'] = image_type_to_extension(IMAGETYPE_GIF, false);
            break;
        case 'image/webp':
            $new['extension'] = image_type_to_extension(IMAGETYPE_WEBP, false);
            break;
        case 'image/avif':
            $new['extension'] = image_type_to_extension(IMAGETYPE_AVIF, false);
            break;
        default:
            return false;
            break;
    }

    // pass over old file and new file, relative to serendipity['uploadsDir']
    $oldfile = $file['path'] . $file['name'] . '.' . $file['extension'];
    $newfile = $file['path'] . $file['name'] . '.' . $new['extension']; // pass over with extensions DOT!

    return serendipity_formatRealFile($oldfile, $newfile, $new['extension'], $file['id'], $file);

}

/**
 * Convert JPG, PNG, GIF, BMP formatted images to the WebP image format with PHP build-in GD image library
 *
 * @param string $infile    The fullpath file format from string
 * @param string $outfile   The fullpath file format to string
 * @param int    $quality   The quality of sizing/formatting
 * @return mixed            bool false or string converted outfile
 */
function serendipity_imageGDWebPConversion($infile, $outfile, $quality = 75) {
    $im = serendipity_imageCreateFromAny($infile);
    if (!$im) {
        return false;
    }
    @ini_set('memory_limit', '1024M');
    try {
        imagewebp($im, $outfile, $quality);
    } catch (Throwable $t) {
        echo 'Could not create WebP image with GD: ',  $t->getMessage(), "\n";
        imagedestroy($im);
        return false;
    }
    imagedestroy($im);

    return $outfile;
}

/**
 * Convert JPG, PNG, GIF, BMP formatted images to the AVIF image format with PHP build-in GD image library
 * Copy of serendipity_imageGDWebPConversion()
 * AVIF encoding takes around 1GB of memory (!)
 *
 * @param string $infile    The fullpath file format from string
 * @param string $outfile   The fullpath file format to string
 * @param int    $quality   The quality of sizing/formatting. A quality -1 is not 100%. It is better! It is the optimized default!
 * @return mixed            bool false or string converted outfile
 */
function serendipity_imageGDAvifConversion($infile, $outfile, $quality = -1) {
    $im = serendipity_imageCreateFromAny($infile);
    if (!$im) {
        return false;
    }
    $mlimit = round(filesize($infile)/1024, 0); // Image example 14210367 b filesize = 13877 KB
    if ($mlimit > 3596) {
        @ini_set('max_execution_time', 240); // 4 min MAX
        $maxMem = round(($mlimit/1024)+1024).'M'; // + 1GB (1024MB) encoding memory
        @ini_set('memory_limit', $maxMem);
    }

    @imageavif($im, $outfile, $quality);

    imagedestroy($im);

    return $outfile;
}

/**
 * Convert an uploaded thumb or single file to the WebP image VARIATION image format with ImageMagick
 * Create CMD string settings and pass to serendipity_passToCMD()
 *
 * NOTE: An image upload source is the origin file object. Thumb prefixed previews AND media sized "previews" are origin sub-variations.
 *       A WebP image is an extra origin variant of the source and is "on top" the variation(s). We STORE them in a (preserved key) current dir/.v directory!
 *
 * WHY USING A HIDDEN DIRECTORY for storage of image variations:
 *       Hidden files offer a convenient mechanism for associating arbitrary metadata with a directory location while remaining largely independent of file system or OS mechanics.
 *       Hidden files are just hidden enough to discourage most users from accidentally invalidating that metadata by moving or removing them while remaining standard enough
 *       to be universally available and flexible enough to support a wide range of use cases. In this directory-case excellent for storing additional image variations that are used for output only.
 *       Styx handlers:
 *          Fetching a sub variations shall not get the WebP formatted origin variation.
 *          ML fetching of $images shall only get the origin, the WebP origin variation and the thumbnail sub variation. Others shall not be included.
 *          Media scaled images for the source-sets live in ML since they are variations, but NOT in the database NOR in the display images build list.
 *          So we have core files: Origin, Thumb and WebP and possible other sub variations of origin, which are media scaled images and special plugin images, eg. quickblog.
 *
 * @param string $source    Source file fullpath
 * @param string $outpath   Target file path
 * @param string $outfile   Target file name
 * @param string $mime      Output of mime_content_type($target)
 * @param bool   $mute      To message OR not. Is default false for a single request, true for bulk like synchronization traversals
 * @param int    $quality   Held for ImageMagick purposes, quality ranges from 0 to 100, while -1 is auto default (known as best working for compression and quality)
 * @return mixed
 */
function serendipity_convertToWebPFormat($infile, $outpath, $outfile, $mime, $mute = false, $quality = -1) {
    global $serendipity;

    if (in_array(strtoupper(explode('/', $mime)[1]), serendipity_getSupportedFormats())) {

        $_tmppath = dirname($outpath . '/.v/' . $outfile);
        if (!is_dir($_tmppath)) {
            @mkdir($_tmppath);
        }
        $thumb = (false !== strpos($outfile, $serendipity['thumbSuffix'])) ? "{$serendipity['thumbSuffix']} " : ' ';
        $_outfile = $_tmppath . '/' . $outfile; // store in a ("preserved key .v") current dir/.v directory!
        if (!file_exists($_outfile)) {
            // make a distinction switch between IM / GD libraries
            if ($serendipity['magick'] !== true) {
                $out = serendipity_imageGDWebPConversion($infile, $_outfile);
                if ($out === false && $mute === false) {
                    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> Trying to store a WebP GD image format ' . $thumb . 'variation in: ' . $_tmppath  . " directory.</span>\n";
                }
                return ((false !== $out) ? array(0, $out, 'with GD') : array(1, 'false', 'with GD'));
            } else {
                $pass = [ $serendipity['convert'], [], [], [], $quality, -1 ]; // Best result format conversion settings with ImageMagick CLI convert is empty/nothing (-1), which is some kind of auto true! Do not handle with lossless!!
                $out  = serendipity_passToCMD('format-webp', $infile, $_outfile, $pass);
                if ($out === false && $mute === false) {
                    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> Trying to store a WebP IM image format ' . $thumb . 'variation in: ' . $_tmppath  . " directory.</span>\n";
                }
                return $out;
            }
        }
    }
    return false;
}

/**
 * Convert an uploaded thumb or single file to the AVIF image VARIATION image format with ImageMagick
 * Create CMD string settings and pass to serendipity_passToCMD()
 * Copy of serendipity_convertToWebPFormat()
 *
 * NOTE: An image upload source is the origin file object. Thumb prefixed previews AND media sized "previews" are origin sub-variations.
 *       A AVIF image is an extra origin variant of the source and is "on top" the variation(s). We STORE them in a (preserved key) current dir/.v directory!
 *
 * WHY USING A HIDDEN DIRECTORY for storage of image variations:
 *       Hidden files offer a convenient mechanism for associating arbitrary metadata with a directory location while remaining largely independent of file system or OS mechanics.
 *       Hidden files are just hidden enough to discourage most users from accidentally invalidating that metadata by moving or removing them while remaining standard enough
 *       to be universally available and flexible enough to support a wide range of use cases. In this directory-case excellent for storing additional image variations that are used for output only.
 *       Styx handlers:
 *          Fetching a sub variations shall not get the AVIF formatted origin variation.
 *          ML fetching of $images shall only get the origin, the AVIF origin variation and the thumbnail sub variation. Others shall not be included.
 *          Media scaled images for the source-sets live in ML since they are variations, but NOT in the database NOR in the display images build list.
 *          So we have core files: Origin, Thumb and AVIF and possible other sub variations of origin, which are media scaled images and special plugin images, eg. quickblog.
 *
 * @param string $source    Source file fullpath
 * @param string $outpath   Target file path
 * @param string $outfile   Target file name
 * @param string $mime      Output of mime_content_type($target)
 * @param bool   $mute      To message OR not. Is default false for a single request, true for bulk like synchronization traversals
 * @param int    $quality   Held for future purposes, ranges from 0 to 100. -1 is the internal optimized default, which is better than 100% !
 * @return mixed
 */
function serendipity_convertToAvifFormat($infile, $outpath, $outfile, $mime, $mute = false, $quality = -1) {
    global $serendipity;

    if (in_array(strtoupper(explode('/', $mime)[1]), serendipity_getSupportedFormats())) {

        $_tmppath = dirname($outpath . '/.v/' . $outfile);
        if (!is_dir($_tmppath)) {
            @mkdir($_tmppath);
        }
        $thumb = (false !== strpos($outfile, $serendipity['thumbSuffix'])) ? "{$serendipity['thumbSuffix']} " : ' ';
        $_outfile = $_tmppath . '/' . $outfile; // store in a ("preserved key .v") current dir/.v directory!
        if (!file_exists($_outfile)) {
            // make a distinction switch between IM / GD libraries
            if ($serendipity['magick'] !== true) {
                $out = serendipity_imageGDAvifConversion($infile, $_outfile);
                if ($out === false && $mute === false) {
                    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> Trying to store a AVIF GD image format ' . $thumb . 'variation in: ' . $_tmppath  . " directory.</span>\n";
                }
                return ((false !== $out) ? array(0, $out, 'with GD') : array(1, 'false', 'with GD'));
            } else {
                $pass = [ $serendipity['convert'], [], [], [], $quality, -1 ]; // Best result format conversion settings with ImageMagick CLI convert is empty/nothing, which is some kind of auto true! Do not handle with lossless!!
                $out  = serendipity_passToCMD('format-avif', $infile, $_outfile, $pass);
                if ($out === false && $mute === false) {
                    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> Trying to store a AVIF IM image format ' . $thumb . 'variation in: ' . $_tmppath  . " directory.</span>\n";
                }
                return $out;
            }
        }
    }
    return false;
}

/**
 * Get valid source image formats
 *
 * @return array
 */
function serendipity_getSupportedFormats($extend = false) {
    if ($extend) {
        return ['BMP', 'PNG', 'JPG', 'JPEG', 'GIF', 'WEBP', 'AVIF'];
    }
    return ['PNG', 'JPG', 'JPEG', 'GIF'];
}

/**
 * Make image variation storage path for targets. Split the origin file to path and replace the file basename with the new image format name.
 *
 * @param string $file  origin fullpath file
 * @param string $ext   new extension
 * @return array
 */
function serendipity_makeImageVariationPath($orgfile, $ext) {
    $newfile = [];
    [ 'basename' => $basename, 'dirname' => $dirname, 'extension' => $extension ] = pathinfo($orgfile);
    $newname = str_replace($extension, $ext, $basename);
    $newfile['filepath'] = $dirname;
    $newfile['filename'] = $newname;

    return $newfile;
}

/**
 * Build image variation storage path for src targets in themes/plugins.
 * Originally build for WebP variations. Now also used for additional AVIF extension or other futures.
 *
 * @param string $image  origin image file relative path
 * @param string $ext    by extension
 * @return array
 */
function serendipity_generate_webpPathURI($image, $ext = 'webp') {
    $bname  = basename($image); // to get base file name w/ ext
    $vpath  = str_replace($bname, '', $image); // get file path
    $fname  = pathinfo($image, PATHINFO_FILENAME); // get file name w/o extension
    $rpath  = $vpath . '.v/' . $fname . '.' . $ext; // the relative document root image filepath

    return $rpath;
}

/**
 * Pass ImageMagick variables to command-CLI-interface [cmd] and process the image resize for a single image file
 *
 * @param string $type      Mime/string type name the image shall be formatted to
 * @param string $source    Source file fullpath
 * @param string $target    Target file fullpath
 * @param array  $args      [0] ImageMagick executor command (remember, "magick" shall be used for IM 7 only, but always a copy named "convert" is created too, so we can stick to convert until this is reverted.)
 *                          [1],[2],[3] Convert setting/operator commands [-antialias, -sharp, -unsharp, -flatten, -scale, -resize, -crop, size adjustments, etc]
 *                          [4] Quality of image operation (normally 100, 75 for thumb downsizing)
 *                          [5] The same color image displayed on two different workstations may look different due to differences in the display monitor.
 *                              Use gamma correction to adjust for this color difference. Reasonable values extend from 0.8 to 2.3.
 *                              Gamma less than 1.0 darkens the image and gamma greater than 1.0 lightens it. Gamma argument of image operation: -1 is disabled. 2 use defaults.
 *                              Large adjustments to image gamma may result in the loss of some image information if the pixel quantum size is only eight bits (quantum range 0 to 255).
 *                              Gamma adjusts the image's channel values pixel-by-pixel according to a power law, namely, pow(pixel,1/gamma) or pixel^(1/gamma), where pixel is the
 *                              normalized or 0 to 1 color value.
 * @return mixed            boolean on fail, else array with result and $cmd string (for debug)
 */
function serendipity_passToCMD($type = null, $source = '', $target = '', $args = array()) {

    if ($type === null
    || (!in_array($type, ['pdfthumb', 'mkthumb', 'format-jpg', 'format-jpeg', 'format-png', 'format-gif', 'format-webp', 'format-avif']) && !in_array(strtoupper(explode('/', $type)[1]), serendipity_getSupportedFormats(true)))
    || !serendipity_checkPermission('adminImagesAdd')) {
        return false;
    }

    $cmd = null;
    $out = array();
    $res = 0;
    $args[0] = $args[0] ?? '/usr/local/bin/convert';
    $args[1] = $args[1] ?? array();
    $args[2] = $args[2] ?? array();
    $args[3] = $args[3] ?? array();
    $args[4] = $args[4] ?? -1;
    $args[5] = $args[5] ?? -1;
    if (count($args[2]) > 0) {
        $do = implode(' ', $args[1]) . ' ' . implode(' ', $args[2]) . ' | "' .
            $args[0] . '" ' . implode(' ', $args[3]); // this is a fully operational string containing infile, settings/operators and the outfile; while [1] is just some kind of preset in this case.
    } else {
        $do = implode(' ', $args[1]) . ' ' . implode(' ', $args[3]); // else [2] is just an arguments (sizing) string for settings/operators
    }

    $do = str_replace(array('  '), array(' '), $do);

    $quality = ($args[4] != -1) ? "-quality {$args[4]}" : '';

    // Do resizing images right:
    // @see https://www.imagemagick.org/Usage/resize/#resize_gamma, for 16bit (Q16 binary) workspace and optional gamma correction.
    // Images are typically stored using a non-linear "sRGB" colorspace, or with gamma correction.
    // But "resize" (like most other image processing operators) is a mathematically linear processor, that assumes that image values directly represent a linear color brightness.
    // The colorspace "sRGB" basically contains a gamma correction of roughly 2.2. As of version 6.7.5 ImageMagick follows the standard convention and defines the default colorspace of
    // images (at least for most image file formats) to be sRGB. This means we simply need to use the "-gamma/-level" (colorspace) to transform the image to a linear space before doing the resize. 
    $gamma['linear'] = $gamma['standard'] = '';
    if ($args[5] != -1) {
        $gamma['linear']   = '-gamma 0.454545'; // (0.45455 is 1/2.2 POW)
        $gamma['standard'] = '-gamma 2.2';
        // For example, using a value of gamma=2 is the same as taking the square root of the image.
    }

    // variations - with type being a command parameter
    if ($type == 'pdfthumb') {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} {$do} ${gamma['standard']} " .
                "-depth 8 -strip \"$target\"";

    } else if ($type == 'mkthumb') {
        $cmd =  "\"{$args[0]}\" \"$source\" {$do} " .
                "-depth 8 $quality -strip \"$target\"";

    } else if ($type == 'format-webp') {
        $cmd =  "\"{$args[0]}\" \"$source\" {$do} " .
                "$quality -strip \"$target\"";

    } else if ($type == 'format-avif') {
        $cmd =  "\"{$args[0]}\" \"$source\" {$do} " .
                "-strip \"$target\"";

    } else if (in_array($type, ['format-jpg', 'format-jpeg', 'format-png', 'format-gif'])) {
        $cmd =  "\"{$args[0]}\" \"$source\" {$do} " .
                "\"$target\"";
    }

    // main file scaling (scale, resize, rotate, ...) - with type being a mime string parameter, while we have it already
    if (image_type_to_mime_type(IMAGETYPE_JPEG) == $type) {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} -filter Lanczos {$do} ${gamma['standard']} " .
                "-depth 8 $quality -sampling-factor 1x1 -strip \"$target\"";

    } else if (image_type_to_mime_type(IMAGETYPE_PNG) == $type) {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} {$do} ${gamma['standard']} " .
                "-depth 8 -strip \"$target\"";

    } else if (image_type_to_mime_type(IMAGETYPE_GIF) == $type) {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} {$do} ${gamma['standard']} " .
                "-depth 8 -strip \"$target\"";

    } else if (image_type_to_mime_type(IMAGETYPE_WEBP) == $type) {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} {$do} ${gamma['standard']} " .
                "-depth 8 -strip \"$target\"";

    } else if (defined('IMAGETYPE_AVIF') && image_type_to_mime_type(IMAGETYPE_AVIF) == $type) {
        $cmd =  "\"{$args[0]}\" \"$source\" -depth 16 ${gamma['linear']} {$do} ${gamma['standard']} " .
                "-depth 8 -strip \"$target\""; // ToDO: or depth 8, 10, 12, etc for other args
    }

    if (is_null($cmd)) {
        return false;
    } else {
        $cmd = str_replace(array('  '), array(' '), $cmd);
        if ($type == 'format-avif' || (defined('IMAGETYPE_AVIF') && image_type_to_mime_type(IMAGETYPE_AVIF) == $type)) {
            // yeah AVIF takes it all - yammi, gimme more! ;-) 2 Gigs plus the filesize at least
            $mlimit = round(filesize($source)/1024, 0); // in KB
            if ($mlimit > 3596) {
                $max = round(($mlimit/1000) + 2048); // 3.6M + 2048M
                @ini_set('max_execution_time', 240); // 4 min MAX
                @ini_set('memory_limit', $max.'M');
            }
        } else {
            @ini_set('max_execution_time', 120);
        }
        @exec($cmd, $out, $res);
    }

    // a failure would be $res[0] == 1
    return array($res, $out, $cmd);
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
 * @param   bool        Suppress serendipity_convertToWebPFormat() & serendipity_convertToAvifFormat() message, if it is a bulk (synchronization) traversal request
 * @return  array       The result size of the thumbnail
 */
function serendipity_makeThumbnail($file, $directory = '', $size = false, $thumbname = false, $is_temporary = false, $force_resize = false, $mute = false) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    if ($size === false) {
        $size = $serendipity['thumbSize'];
    }
    if ($size < 1) {
       return array(0,0);
    }

    if ($thumbname === false) {
        $thumbname = $serendipity['thumbSuffix'];
    }

    $t      = serendipity_parseFileName($file);
    $f      = $t[0];
    $suf    = $t[1];
    $infile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $directory . $file;

    if ($debug) {
        $logtag = 'ML_MAKETHUMBNAIL::';
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START ML serendipity_makeThumbnail SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag From: $infile");
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

    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag To: $outfile"); }

    $fdim = @serendipity_getImageSize($infile, '', $suf);
    if (isset($fdim['noimage'])) {
        $r = array(0, 0);
    } else {
        $fdim[0] = $fdim[0] ?? 0; // this check is temporary getimagesize() hotfix related since uploaded avif images have no sizes yet by default
        $fdim[1] = $fdim[1] ?? 0; // ditto
        // GD
        if ($serendipity['magick'] !== true) {
            if (is_array($size)) {
                // The caller wants a thumbnail with a specific size
                $r = serendipity_resizeImageGD($infile, $outfile, $size['width'], $size['height']);
                // Create a copy in WebP image format
                if (file_exists($outfile) && $serendipity['useWebPFormat']) {
                    // The WebP GD part in 3 steps: 1. makeVariationPath(), 2. convertToWebPFormat(), 3. resizeImageGD()
                    $newgdfile = serendipity_makeImageVariationPath($outfile, 'webp');
                    // first we create it!
                    $result = serendipity_convertToWebPFormat($infile, $newgdfile['filepath'], $newgdfile['filename'], mime_content_type($outfile), $mute);
                    if (is_array($result) && $result[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: Image WebP format creation success ${result[2]} " . DONE); }
                        // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                        // split it up like done here: 1. $outfile->convert to WebP and then 2. $webpthbGD->resize to thumb, which overwrites the first.
                        $webpthbGD = $newgdfile['filepath'] . '/.v/' . $newgdfile['filename'];
                        $newsize   = serendipity_resizeImageGD($webpthbGD, $webpthbGD, $size['width'], $size['height']);
                        if (false !== $newsize && is_array($newsize)) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format resize success with GD lib " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format resize failed! Perhaps a wrong path: '$webpthbGD' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format creation failed OR already exists."); }
                    }
                }
                // Create a copy in AVIF image format
                if (file_exists($outfile) && $serendipity['useAvifFormat']) {
                    // The AVIF GD part in 3 steps: 1. makeVariationPath(), 2. convertToAvifFormat(), 3. resizeImageGD()
                    $newgdfile = serendipity_makeImageVariationPath($outfile, 'avif');
                    // first we create it!
                    $result = serendipity_convertToAvifFormat($infile, $newgdfile['filepath'], $newgdfile['filename'], mime_content_type($outfile), $mute);
                    if (is_array($result) && $result[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: Image AVIF format creation success ${result[2]} " . DONE); }
                        // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                        // split it up like done here: 1. $outfile->convert to AVIF and then 2. $avifthbGD->resize to thumb, which overwrites the first.
                        $avifthbGD = $newgdfile['filepath'] . '/.v/' . $newgdfile['filename'];
                        $newsize   = serendipity_resizeImageGD($avifthbGD, $avifthbGD, $size['width'], $size['height']);
                        if (false !== $newsize && is_array($newsize)) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format resize success with GD lib " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format resize failed! Perhaps a wrong path: '$avifthbGD' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format creation failed OR already exists."); }
                    }
                }
            } else {
                // The caller wants a thumbnail constrained in the dimension set by config
                $calc = serendipity_calculateAspectSize($fdim[0], $fdim[1], $size, $serendipity['thumbConstraint']);
                $r    = serendipity_resizeImageGD($infile, $outfile, $calc[0], $calc[1]);
                // Create a copy in WebP image format
                if (file_exists($outfile) && $serendipity['useWebPFormat']) {
                    // The WebP GD part in 3 steps: 1. makeVariationPath(), 2. convertToWebPFormat(), 3. resizeImageGD()
                    $newgdfile = serendipity_makeImageVariationPath($outfile, 'webp');
                    // first we create it!
                    $result = serendipity_convertToWebPFormat($infile, $newgdfile['filepath'], $newgdfile['filename'], mime_content_type($outfile), $mute);
                    if (is_array($result) && $result[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: Image WebP format creation success ${result[2]} " . DONE); }
                        // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                        // split it up like done here: 1. $outfile->convert to WebP and then 2. $webpthbGD->resize to thumb, which overwrites the first.
                        $webpthbGD = $newgdfile['filepath'] . '/.v/' . $newgdfile['filename'];
                        $newsize   = serendipity_resizeImageGD($webpthbGD, $webpthbGD, $calc[0], $calc[1]);
                        if (false !== $newsize && is_array($newsize)) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format resize success with GD lib " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format resize failed! Perhaps a wrong path: '$webpthbGD' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image WebP format creation failed OR already exists."); }
                    }
                }
                // Create a copy in AVIF image format
                if (file_exists($outfile) && $serendipity['useAvifFormat']) {
                    // The AVIF GD part in 3 steps: 1. makeVariationPath(), 2. convertToAvifFormat(), 3. resizeImageGD()
                    $newgdfile = serendipity_makeImageVariationPath($outfile, 'avif');
                    // first we create it!
                    $result = serendipity_convertToAvifFormat($infile, $newgdfile['filepath'], $newgdfile['filename'], mime_content_type($outfile), $mute);
                    if (is_array($result) && $result[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: Image AVIF format creation success ${result[2]} " . DONE); }
                        // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                        // split it up like done here: 1. $outfile->convert to AVIF and then 2. $avifthbGD->resize to thumb, which overwrites the first.
                        $avifthbGD = $newgdfile['filepath'] . '/.v/' . $newgdfile['filename'];
                        $newsize   = serendipity_resizeImageGD($avifthbGD, $avifthbGD, $calc[0], $calc[1]);
                        if (false !== $newsize && is_array($newsize)) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format resize success with GD lib " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format resize failed! Perhaps a wrong path: '$avifthbGD' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: GD Image AVIF format creation failed OR already exists."); }
                    }
                }
            }
        }
        // IM
        else {
            if (is_array($size)) {
                if ($fdim[0] > $size['width'] && $fdim[1] > $size['height']) {
                    $r = array(0 => $size['width'], 'width' => $size['width'], 1 => $size['height'], 'height' => $size['height']);
                } else {
                    return array(0, 0); // do not create any thumb, if image is smaller than defined sizes
                }
            } else {
                $calc = serendipity_calculateAspectSize($fdim[0], $fdim[1], $size, $serendipity['thumbConstraint']);
                $r    = array(0 => $calc[0], 'width' => $calc[0], 1 => $calc[1], 'height' => $calc[1]);
            }

            $newSize = $r['width'] . 'x' . $r['height'];
            // CMD - Be strict on order: (Normally a setting should come before bulk images and an image operator after the image filename [the later in special for IM 7 versions !!])
            // Since we have 1:1 file relations this can be set to: INFILE -setting(s) -operator(s) OUTFILE, @see
            //          http://magick.imagemagick.org/script/command-line-processing.php#setting
            // The here used -flatten and -scale are Sequence Operators, while -antialias is a Setting and -resize is an Operator.
            if ($fdim['mime'] == 'application/pdf') {
                $isPDF = true;
                $pass = [ $serendipity['convert'], ['-antialias -flatten -scale'], [], ['"'.$newSize.'"'], 75, 2 ];
                $result = serendipity_passToCMD('pdfthumb', $infile[0], $outfile . '.png', $pass);
                // The [0] after the pdf path is used to choose which page we want to convert, starting from 0.

                if ($debug) { $serendipity['logger']->debug("ImageMagick CLI PDF thumbnail creation: ${result[2]}"); }

            } else {
                $isPDF = false;
                if (!$force_resize && serendipity_ini_bool(ini_get('safe_mode')) === false) {
                    $newSize .= '>'; // tell ImageMagick to not enlarge small images. This only works if safe_mode is off (safe_mode turns > in to \>)
                }

                // force the first run image geometry exactly to given sizes, if there were rounding differences (@see https://github.com/s9y/Serendipity/commit/94881ba and comments)
                $bang = empty($serendipity['imagemagick_nobang']) ? '!' : '';
                $newSize .= $bang;

                $_imtp = !empty($serendipity['imagemagick_thumb_parameters']) ? ' '. $serendipity['imagemagick_thumb_parameters'] : '';

                // check a special case for the fullpath WebP file to thumbnail resizing
                if (false !== strpos($outfile, '.' . $serendipity['thumbSuffix'] . '.webp')) {
                    $fdim['mime'] = 'image/webp';
                }
                // check a special case for the fullpath AVIF file to thumbnail resizing
                if (false !== strpos($outfile, '.' . $serendipity['thumbSuffix'] . '.avif')) {
                    $fdim['mime'] = 'image/avif';
                }

                // avoid the file resizing loop in special case; which is example.serendipityThumb.ext (png,jpg,webp,avif..)
                if (!file_exists($outfile)) {
                    $pass = [ $serendipity['convert'], ["-antialias -resize $_imtp"], [], ['"'.$newSize.'"'], 75, -1 ];
                    $result = serendipity_passToCMD($fdim['mime'], $infile, $outfile, $pass);
                    if ($result === false) {
                        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> Image thumb variation failed. Please contact the sites Administrator!' . "</span>\n";
                        $logres = 'Thumb variation failed - serendipity_passToCMD L.:' . (__LINE__ - 3) . ' returned false!';
                    } else {
                        $logres = $result[2];
                    }

                    if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image thumbnail creation: $logres"); }
                }

                // Create a copy of the thumb in WebP image format
                if (file_exists($outfile) && $serendipity['useWebPFormat']) {
                    $newfile = serendipity_makeImageVariationPath($outfile, 'webp');
                    // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                    // split it up like done here: 1. $outfile->convert to WebP and then 2. $webpthb->resize to thumb, which overwrites the first.
                    $webpthb = $newfile['filepath'] . '/.v/' . $newfile['filename'];
                    $reswebp = serendipity_convertToWebPFormat($infile, $newfile['filepath'], $newfile['filename'], mime_content_type($outfile), $mute); // WebP thumbnail uses full quality by auto default
                    if (is_array($reswebp) && $reswebp[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image WebP format creation success ${reswebp[2]} " . DONE); }
                        unset($reswebp);
                        // The resizing to same name(!)
                        $pass = $pass ?? [ $serendipity['convert'], ["-antialias -resize $_imtp"], [], ['"'.$newSize.'"'], 75, -1 ]; // no upload (FTP like case)
                        $reswebp = serendipity_passToCMD('image/webp', $webpthb, $webpthb, $pass);
                        if (is_array($reswebp) && $reswebp[0] == 0) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image WebP format resize success ${reswebp[2]} " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image WebP format resize failed! Perhaps a wrong path: '$webpthb' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image WebP format creation failed OR already exists."); }
                    }
                }
                // Create a copy of the thumb in AVIF image format
                if (file_exists($outfile) && $serendipity['useAvifFormat']) {
                    $newfile = serendipity_makeImageVariationPath($outfile, 'avif');
                    // The $outfile variable is not being the resized $outfile yet! We could either fetch it first, .. or
                    // split it up like done here: 1. $outfile->convert to AVIF and then 2. $avifthb->resize to thumb, which overwrites the first.
                    $avifthb = $newfile['filepath'] . '/.v/' . $newfile['filename'];
                    $resavif = serendipity_convertToAvifFormat($infile, $newfile['filepath'], $newfile['filename'], mime_content_type($outfile), $mute);
                    if (is_array($resavif) && $resavif[0] == 0) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image AVIF format creation success ${resavif[2]} " . DONE); }
                        unset($resavif);
                        // The resizing to same name(!)
                        $pass = $pass ?? []; // (FTP like case) pass args 1,2,4,5 are currently unused with AVIF
                        $resavif = serendipity_passToCMD('image/avif', $avifthb, $avifthb, $pass);
                        if (is_array($resavif) && $resavif[0] == 0) {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image AVIF format resize success ${resavif[2]} " . DONE); }
                        } else {
                            if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image AVIF format resize failed! Perhaps a wrong path: '$avifthb' ?"); }
                        }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATETHUMBVARIATION: ImageMagick CLI Image AVIF format creation failed OR already exists."); }
                    }
                }
            }

            if (isset($result) && is_array($result) && $result[0] != 0) {
                if (!$isPDF) {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $result[2], @$result[1][0], $result[0]) ."</span>\n";
                } else {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> PDF thumbnail creation using ImageMagick and Ghostscript failed!' . "</span>\n";
                }
                $r = false; // return failure
            } else {
                touch($outfile); // since we may have touched existing files. GD does it in serendipity_resizeImageGD().
            }
            unset($result);
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
 * @param   bool    A special set to resize the WepP-Format Thumb variation
 * @return true
 */
function serendipity_scaleImg($id, $width, $height, $scaleThumbVariation=false) {
    global $serendipity;
    static $debug = true; // ad hoc, case-by-case debugging

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    $file = serendipity_fetchImageFromDatabase($id);
    if (!is_array($file)) {
        return false;
    }

    if (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid']) {
        // A non-admin user SHALL NOT change private files from other users.
        return;
    }

    $infile  = $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
    /* WebP case */
    $owebp   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.webp';
    $owebpTH = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.' . $file['thumbnail_name'] . '.webp';
    /* AVIF case */
    $oavif   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.avif';
    $oavifTH = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.' . $file['thumbnail_name'] . '.avif';

    // check for AVIF image file errors before to prevent image scaling AT ALL
    // - this is a workaround to prevent serendipity_resizeImageGD() or serendipity_passToCMD IM -scale errors on broken images and should also work when getimagesize() will be "GOOGLE" fixed for AVIF in future
    if (file_exists($oavif)) {
        list($width, $height, $type, $attr) = @getimagesize($oavif); // to grasp the nettle this currently is the default for AVIF in the moment, excluding known broken filesizes
        if (($width == 0 && $height == 0 && $type = 19) || in_array(filesize($oavif), [3389, 34165])) {
            return 'Sorry! This function is temporary disabled because the AVIF Variation file is erroneous!';
        }
    }

    if ($serendipity['magick'] !== true) {
        $r = serendipity_resizeImageGD($infile, $outfile, $width, $height);
        if (false !== $r && is_array($r)) {
            $result[0] = 0; // ! define success and keep for ending serendipity_updateImageInDatabase check. GD returns slightly different than IM!
            if ($debug) { $serendipity['logger']->debug("GD Library Scale File command: ${outfile}, with ${r[0]}x${r[1]} via serendipity_resizeImageGD()."); }
            // do on SAME FILE for the WebP-Format variation
            if (file_exists($owebp)) {
                $rws = serendipity_resizeImageGD($owebp, $owebp, $width, $height);
                if (false !== $rws && is_array($rws)) {
                    if ($debug) { $serendipity['logger']->debug("GD Library Scale WebP File command: ${owebp}, with ${rws[0]}x${rws[1]} via serendipity_resizeImageGD()."); }
                    if ($scaleThumbVariation && file_exists($owebpTH)) {
                        // if particularly wished, (silently) force scale Thumb Variation too
                        $nz = serendipity_calculateAspectSize($width, $height, $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                        $rs = serendipity_resizeImageGD($owebpTH, $owebpTH, $nz[0], $nz[1]);
                        if (false !== $rs && is_array($rs) && $debug) {
                            $serendipity['logger']->debug("GD Library Scale WebP Thumb File command: ${owebpTH}, with ${rs[0]}x${rs[1]} via serendipity_resizeImageGD().");
                        }
                    }
                } else {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, 'serendipity_resizeImageGD()', "Creating WebP ${owebp} image", 'failed') ."</span>\n";
                }
            }
            // do on SAME FILE for the AVIF-Format variation
            if (file_exists($oavif)) {
                $rws = serendipity_resizeImageGD($oavif, $oavif, $width, $height);
                if (false !== $rws && is_array($rws)) {
                    if ($debug) { $serendipity['logger']->debug("GD Library Scale AVIF File command: ${oavif}, with ${rws[0]}x${rws[1]} via serendipity_resizeImageGD()."); }
                    if ($scaleThumbVariation && file_exists($oavifTH)) {
                        // if particularly wished, (silently) force scale Thumb Variation too
                        $nz = serendipity_calculateAspectSize($width, $height, $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                        $rs = serendipity_resizeImageGD($oavifTH, $oavifTH, $nz[0], $nz[1]);
                        if (false !== $rs && is_array($rs) && $debug) {
                            $serendipity['logger']->debug("GD Library Scale AVIF Thumb File command: ${oavifTH}, with ${rs[0]}x${rs[1]} via serendipity_resizeImageGD().");
                        }
                    }
                } else {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, 'serendipity_resizeImageGD()', "Creating AVIF ${oavif} image", 'failed') ."</span>\n";
                }
            }
        } else {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, 'serendipity_resizeImageGD()', "Creating ${outfile} image", 'failed') ."</span>\n";
        }
    } else {
        $pass   = [ $serendipity['convert'], ['-scale'], [], ["\"{$width}x{$height}\""], 100, 2 ];
        $result = serendipity_passToCMD($file['mime'], $infile, $outfile, $pass);
        if ($result[0] != 0) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $result[2], $result[1][0], $result[0]) ."</span>\n";
            return false;
        } else {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Scale File command: ${result[2]}, with {$width}x{$height}."); }
            // do on SAME FILE for the WebP-Format variation
            if (file_exists($owebp)) {
                $reswebp = serendipity_passToCMD('image/webp', $owebp, $owebp, $pass);
                if (is_array($reswebp) && $reswebp[0] != 0) {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $reswebp[2], $reswebp[1][0], $reswebp[0]) ."</span>\n";
                } else {
                    if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Scale WebP File command: ${reswebp[2]}, with {$width}x{$height}."); }
                    if ($scaleThumbVariation && file_exists($owebpTH)) {
                        // if particularly wished, (silently) force scale Thumb Variation too
                        $nz    = serendipity_calculateAspectSize($width, $height, $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                        $pass  = [ $serendipity['convert'], ['-scale'], [], ["\"{$nz[0]}x{$nz[1]}\""], 75, -1 ];
                        $resTH = serendipity_passToCMD('image/webp', $owebpTH, $owebpTH, $pass);
                        if (is_array($resTH) && $resTH[0] == 0 && $debug) {
                            $serendipity['logger']->debug("ImageMagick CLI Scale WebP Thumb File command: ${owebpTH}, with {$width}x{$height}.");
                        }
                    }
                }
            }
            // do on SAME FILE for the AVIF-Format variation
            if (file_exists($oavif)) {
                $resavif = serendipity_passToCMD('image/avif', $oavif, $oavif, $pass);
                if (is_array($resavif) && $resavif[0] != 0) {
                    echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $resavif[2], $resavif[1][0], $resavif[0]) ."</span>\n";
                } else {
                    if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Scale AVIF File command: ${resavif[2]}, with {$width}x{$height}."); }
                    if ($scaleThumbVariation && file_exists($oavifTH)) {
                        // if particularly wished, (silently) force scale Thumb Variation too
                        $nz    = serendipity_calculateAspectSize($width, $height, $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                        $pass  = [ $serendipity['convert'], ['-scale'], [], ["\"{$nz[0]}x{$nz[1]}\""], 75, -1 ];
                        $resTH = serendipity_passToCMD('image/avif', $oavifTH, $oavifTH, $pass);
                        if (is_array($resTH) && $resTH[0] == 0 && $debug) {
                            $serendipity['logger']->debug("ImageMagick CLI Scale AVIF Thumb File command: ${oavifTH}, with {$width}x{$height}.");
                        }
                    }
                }
            }
        }
        unset($result[1][0], $reswebp);
        unset($result[1][0], $resavif);
    }

    if (isset($result[0]) && $result[0] == 0) {
        serendipity_updateImageInDatabase(array('dimensions_width' => (int)$width, 'dimensions_height' => (int)$height, 'size' => (int)@filesize($outfile)), $id);
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

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    $file = serendipity_fetchImageFromDatabase($id);
    if (!is_array($file)) {
        return false;
    }

    if (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid']) {
        // A non-admin user SHALL NOT change private files from other users.
        return false;
    }

    $infile = $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
    $infileThumb = $outfileThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
    // WebP case
    $infile_webp = $outfile_webp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.webp';
    $infile_webpThumb = $outfile_webpThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.webp';
    // AVIF case
    $infile_avif = $outfile_avif = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.avif';
    $infile_avifThumb = $outfile_avifThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.avif';

    $turn = (preg_match('@-@', $degrees)) ? '<-' : '->';

    // check for AVIF image file errors before to prevent image rotating AT ALL
    // - this is a workaround to prevent serendipity_rotateImageGD() or serendipity_passToCMD IM -rotate errors on broken images and should also work when getimagesize() will be "GOOGLE" fixed for AVIF in future
    if (file_exists($infile_avif)) {
        list($width, $height, $type, $attr) = @getimagesize($infile_avif); // to grasp the nettle this currently is the default for AVIF in the moment, excluding known broken filesizes
        if (($width == 0 && $height == 0 && $type = 19) || in_array(filesize($infile_avif), [3389, 34165])) {
            return true; // silently return, else we will need {if !isset($rotate_img_done) OR $rotate_img_done} in templates\default\admin\images.inc.tpl. Also see possible thrown errors in the XHR request by serendipity_rotateImageGD() with unsilenced imagecreatefromavif and imagerotate for example
        }
    }

    if ($serendipity['magick'] !== true) {
        if (serendipity_rotateImageGD($infile, $outfile, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfile}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfile}."); }
        }
        if (serendipity_rotateImageGD($infileThumb, $outfileThumb, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfileThumb}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfileThumb}."); }
        }
        if (serendipity_rotateImageGD($infile_webp, $outfile_webp, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfile_webp}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_webp}."); }
        }
        if (serendipity_rotateImageGD($infile_webpThumb, $outfile_webpThumb, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfile_webpThumb}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_webpThumb}."); }
        }
        if (serendipity_rotateImageGD($infile_avif, $outfile_avif, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfile_avif}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_avif}."); }
        }
        if (serendipity_rotateImageGD($infile_avifThumb, $outfile_avifThumb, $degrees)) {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate main file command: Rotate $turn ${degrees} degrees, file: ${outfile_avifThumb}"); }
        } else {
            if ($debug) { $serendipity['logger']->debug("GD Library Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_avifThumb}."); }
        }
    } else {
        /* Why can't we just all agree on the rotation direction?
        -> Styx 2.5 disabled, since that seems to be a workaround for a very very old bug
        $degrees = (360 - $degrees); */

        $pass = [ $serendipity['convert'], ['-rotate'], [], ['"'.$degrees.'"'], 100, 2 ];

        /* Resize main image */
        $result = serendipity_passToCMD($file['mime'], $infile, $outfile, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate main file command: Rotate ${turn} ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfile}."); }
        }
        unset($result);

        /* Resize thumbnail */
        $result = serendipity_passToCMD($file['mime'], $infileThumb, $outfileThumb, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate thumb file command: Rotate $turn ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfileThumb}."); }
        }
        unset($result);

        /* Resize main WebP image */
        $result = serendipity_passToCMD($file['mime'], $infile_webp, $outfile_webp, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate main WebP file command: Rotate $turn ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_webp}."); }
        }
        unset($result);

        /* Resize WebP thumbnail */
        $result = serendipity_passToCMD($file['mime'], $infile_webpThumb, $outfile_webpThumb, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate WebP thumb file command: Rotate $turn ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_webpThumb}."); }
        }
        unset($result);

        /* Resize main AVIF image */
        $result = serendipity_passToCMD($file['mime'], $infile_avif, $outfile_avif, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate main AVIF file command: Rotate $turn ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_avif}."); }
        }
        unset($result);

        /* Resize AVIF thumbnail */
        $result = serendipity_passToCMD($file['mime'], $infile_avifThumb, $outfile_avifThumb, $pass);
        if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate AVIF thumb file command: Rotate $turn ${degrees} degrees, file: ${result[2]}"); }
        if ($result[0] != 0) {
            if ($debug) { $serendipity['logger']->debug("ImageMagick CLI Rotate failed: ${turn} ${degrees} degrees, file: ${outfile_avifThumb}."); }
        }
        unset($result);

    }

    $fdim = @getimagesize($outfile);

    serendipity_updateImageInDatabase(array('dimensions_width' => (int)$fdim[0], 'dimensions_height' => (int)$fdim[1]), $id);

    return true;
}

/**
 * Force an image AVIF/WebP Variation file format conversion on all supported files by range
 *
 * @return int num $items converted
 */
function serendipity_generateVariations() {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    if (empty($serendipity['useWebPFormat'])) {// also used for avif - bind to
        return;
    }
    if ($debug) {
        $logtag = 'MAINTENANCE ML IMAGE-SYNC OPT #4 - PART RUN ::';
        $trace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START MS serendipity_generateVariations() SEPARATOR" . str_repeat(" >>> ", 10) . "\n");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    $count = serendipity_db_query("SELECT count(*) FROM {$serendipity['dbPrefix']}images WHERE extension IN ('jpg', 'jpeg', 'png')", true, 'num');
    $i = 0;
    $iteration = 1;
    echo "<ul class=\"plainList\">\n";
    $parts = ($count[0] > 25) ? range(0, $count[0], 25) : array(0);
    foreach($parts AS $part) {
        echo "<li>" . sprintf(SYNC_IMAGE_LIST_ITERATION_RANGE_PART, $iteration, ($count[0]-$part)) . "</li>\n";
        // we use and set a filter extension for webp to get same results for the part range
        $files = serendipity_fetchImagesFromDatabase($part, 25, $total, array('path, name'), 'ASC', '', '', '', array('by.extension' => array(0 => 'jpg', 1 => 'jpeg', 2 => 'png')));
        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag FETCH PART $iteration DB FILES: ".print_r($files,1)); }
        if (is_array($files) && !empty($files)) {
            foreach($files AS $f => $file) {
                $resWebP = $resAVIF = false; // init
                if (!in_array(strtolower($file['extension']), ['jpg', 'jpeg', 'png']) || $file['hotlink'] == 1) {
                    continue; // next
                }
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag EACH FILE AFTER: ".print_r($file,1)); }
                $infile    = $outfile   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
                $infileTH  = $outfileTH = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['thumbnail_name']) ? '' : '.' . $file['thumbnail_name']) . (empty($file['extension']) ? '' : '.' . $file['extension']);

                // WebP case
                if ($serendipity['useWebPFormat']) {
                    $newfile   = serendipity_makeImageVariationPath($outfile, 'webp');
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag NEW FILE WEBP: ".print_r($newfile,1)); }
                    $newfileTH = serendipity_makeImageVariationPath($outfileTH, 'webp');
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag NEW FILETHUMB WEBP: ".print_r($newfileTH,1)); }
                    if (in_array(strtoupper(explode('/', mime_content_type($outfile))[1]), serendipity_getSupportedFormats())) {
                        $odim = filesize($infile);
                        $webpIMQ = -1;
                        #   1024 B x            3.6 MB         6 MB           9 MB           12 MB
                        $dimensions = [0 => -1, 3686400 => 90, 6144000 => 85, 9216000 => 80, 12288000 => 75];
                        foreach ($dimensions AS $dk => $dv) {
                            if ($odim > $dk) {
                                $webpIMQ = $dv;
                            }
                        }
                    } else {
                        $webpIMQ = -1;
                    }
                    $result    = serendipity_convertToWebPFormat($infile, $newfile['filepath'], $newfile['filename'], mime_content_type($outfile), true, $webpIMQ); // Origins WebP copy variation only in case it is big, else we might get bigger webp lossless expression than the origin
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag CONVERT TO WEBP: ".print_r($result,1)); }
                    if ($result !== false && is_array($result) && $result[0] == 0) {
                        serendipity_convertToWebPFormat($infileTH, $newfileTH['filepath'], $newfileTH['filename'], mime_content_type($outfileTH), true); // WebP thumbnail uses full quality by auto default
                        $resWebP = true;
                    }
                }
                // AVIF case
                if ($serendipity['useAvifFormat']) {
                    $newfile   = serendipity_makeImageVariationPath($outfile, 'avif');
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag NEW FILE AVIF: ".print_r($newfile,1)); }
                    $newfileTH = serendipity_makeImageVariationPath($outfileTH, 'avif');
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag NEW FILETHUMB AVIF: ".print_r($newfileTH,1)); }

                    $result    = serendipity_convertToAvifFormat($infile, $newfile['filepath'], $newfile['filename'], mime_content_type($outfile), true);
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag CONVERT TO AVIF: ".print_r($result,1)); }
                    if ($result !== false && is_array($result) && $result[0] == 0) {
                        serendipity_convertToAvifFormat($infileTH, $newfileTH['filepath'], $newfileTH['filename'], mime_content_type($outfileTH), true);
                        $resAVIF = true;
                    }
                }

                if ($resWebP || $resAVIF) {
                    ++$i; // iterate
                }
            }
        }
        echo '<li>' , sprintf(SYNC_IMAGE_LIST_ITERATION_RANGE_DONE, $iteration, DONE, $i) , "<br>\n</li>\n";
        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag ".sprintf(SYNC_IMAGE_LIST_ITERATION_RANGE_DONE, $iteration, DONE, $i)); }
        ++$iteration;
        flush();
    }
    $serendipity['upgrade_variation_done'] = true;
    serendipity_set_config_var('upgrade_variation_done', 'true', 0);
    echo "</ul>\n";

    return $i;
}

/**
 * Delete all image AVIF/WebP Variation files in the physical Media Library
 *
 * @return int num $i(tems) to purge/purged
 */
function serendipity_purgeVariations($path = null, $doPurge = false) {
    global $serendipity;

    if (empty($serendipity['useWebPFormat']) || empty($path)) {// also used for avif - bind to
        return;
    }
    if ($doPurge && !serendipity_checkPermission('adminImagesDirectories')) {
        return;
    }
    $path     = rtrim($path, '/');
    $wpurges  = array();
    $iterator = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                                RecursiveIteratorIterator::CHILD_FIRST);
    echo "<section id=\"fileListing\">\n";
    echo "<h3>" . SYNC_VARIATION_ITERATION_LIST_TITLE . "</h3>\n";
    if (iterator_count($iterator) < 2) {
        echo '<em>' . NO_IMAGES_FOUND . '</em>';
    }

    echo "<ul class=\"plainList\">\n";
    foreach($iterator AS $dir) {
        if ($dir->isDir()) {
            if ($dir->getFilename() == '.v') {
                // this now are all .v/ directories
                $files = array();
                $files = new RecursiveIteratorIterator(
                                new RecursiveDirectoryIterator($dir),
                                        RecursiveIteratorIterator::SELF_FIRST);
                foreach($files AS $fileinfo) {
                    if ($fileinfo->getExtension() == 'webp' || $fileinfo->getExtension() == 'avif') {
                        $wpurges[] = $fileinfo->__toString();
                        if (!$doPurge) {
                            print('<li>' . $fileinfo->__toString() . '</li>' . PHP_EOL); // OK
                        } else {
                            unlink($fileinfo->__toString());
                        }
                    }
                }
            }
        } else {
            // void
        }
    }
    echo "</ul>\n";

    if (!empty($wpurges) && !$doPurge) {
        $token = serendipity_setFormToken('url');
        echo '<form id="purge_variation_images" method="get">
                <div class="form_buttons">
                  <a class="button_link icon_link" href="serendipity_admin.php?serendipity[adminModule]=maintenance">'.BACK.'</a>
                  <a class="button_link state_submit icon_link" href="serendipity_admin.php?'.$token.'&amp;serendipity[adminModule]=media&amp;serendipity[adminAction]=doSyncPurgeVariations">'.DUMP_IT.'</a>
                </div>
              </form>
';
    }

    echo "</section>\n";
    return count($wpurges);
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
            if (!file_exists($oldThumb) && !file_exists($newThumb) && is_array($fdim) && ($fdim[0] > $serendipity['thumbSize'] || $fdim[1] > $serendipity['thumbSize'])) {
                $returnsize = serendipity_makeThumbnail($file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']), $file['path'], false, false, false, false, true); // suppress "trying to webp" message
                if ($returnsize !== false && is_array($returnsize)) {
                    $_list .= '<li>' . sprintf(RESIZE_BLAHBLAH, '<b>' . $sThumb . '</b>') . ': ' . $returnsize[0] . 'x' . $returnsize[1] . "</li>\n";
                    if (!file_exists($newThumb)) {
                        $_list .= sprintf('<li>' . THUMBNAIL_FAILED_COPY . "</li>\n", '<b>' . $sThumb . '</b>');
                    } else {
                        $update = true;
                    }
                }
            // copy the too small origin $ffull image to a copy with the name $newThumb, since we need a thumbnail explicitly
            } elseif (!file_exists($oldThumb) && !file_exists($newThumb) && is_array($fdim) && $fdim[0] <= $serendipity['thumbSize'] && $fdim[1] <= $serendipity['thumbSize']) {
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
        case 'avif':
            $mime = 'image/avif';
            break;

        case 'avifs':
            $mime = 'image/avif-sequence';
            break;

        case 'jpg':
        case 'jpeg':
            $mime = 'image/jpeg';
            break;

        case 'jp2':
            $mime = 'image/jp2';
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

        case '7z':
            $mime = 'application/x-7z-compressed';
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

        case 'ics':
            $mime = 'text/calendar';
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

        case 'webp':
            $mime = 'image/webp';
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

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    if ($debug) {
        $logtag = 'MAINTENANCE IMAGE-SYNC Opt4::';
        $trace  = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
        $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START MS serendipity_convertThumbs() SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    // fetch all excluded files from list in $files relative to /uploads directory (make sure it is synced before)
    $ofiles = serendipity_fetchImages(true);
    $nfiles = array();
    $i = $e = $s = 0;

    if ($debug) {
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag UniqueThumbSuffixes: ".print_r($serendipity['uniqueThumbSuffixes'],1));
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag REVERSE THUMB FILES: ".print_r($ofiles,1));
    }

    if (empty($ofiles)) return $i;

    echo "<span class=\"msg_notice\">\n<ul>\n";

    // Open directory
    $basedir = $serendipity['serendipityPath'] . $serendipity['uploadPath'];
    $hook_sp = class_exists('serendipity_event_staticpage') ? true : false;

    // rename in filepath
    foreach($ofiles AS $oldthumbnail) {
        foreach($serendipity['uniqueThumbSuffixes'] AS $othumb) {
            $newThumbnail = str_replace($othumb, $serendipity['thumbSuffix'], $oldthumbnail);
            $nfiles[] = $newThumbnail;
            // avoid fatal errors - since being looped - this is rename(same, same) after done which fails
            if ($newThumbnail == $oldthumbnail) {
                continue;
            }
            // RENAME in file system
            rename($basedir.$oldthumbnail, $basedir.$newThumbnail);
            if ($debug) { $serendipity['logger']->debug("\n\n$logtag FILE RENAMES FROM::TO:\n".$basedir.$oldthumbnail.",\n".$basedir.$newThumbnail . DONE); }
            // update in image database
            $q = "UPDATE {$serendipity['dbPrefix']}images
                     SET thumbnail_name = '" . serendipity_db_escape_string($serendipity['thumbSuffix']) . "'
                   WHERE thumbnail_name = '" . serendipity_db_escape_string($othumb) . "'";
            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag UPDATE images DB::images:\n$q"); }
            serendipity_db_query($q);
            if ($serendipity['dbType'] == 'mysqli') {
                // SELECT-ing the entries by $oldthumbnail singularly
                $eq = "SELECT id, body, extended
                         FROM {$serendipity['dbPrefix']}entries
                        WHERE body     REGEXP '(src=|srcset=|href=|data-fallback=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'
                           OR extended REGEXP '(src=|srcset=|href=|data-fallback=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'";
            } else {
                $eq = "SELECT id, body, extended
                        FROM {$serendipity['dbPrefix']}entries
                       WHERE (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')
                          OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')";
            }
            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag SELECT entries DB::entries:\n$eq"); }
            $entries = serendipity_db_query($eq, false, 'assoc');
            if (is_array($entries)) {
                foreach($entries AS $entry) {
                    $id = serendipity_db_escape_string($entry['id']);
                    $entry['body']     = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['body']);
                    $entry['extended'] = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $entry['extended']);
                    $uq = "UPDATE {$serendipity['dbPrefix']}entries
                              SET body = '" . serendipity_db_escape_string($entry['body']) . "' ,
                                  extended = '" . serendipity_db_escape_string($entry['extended']) . "'
                            WHERE id = $id";
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag UPDATE entries DB::entries:\nID:{$entry['id']} {$serendipity['dbPrefix']}entries::[body|extended] update " .DONE); }
                    serendipity_db_query($uq);
                    // count the entries changed
                    if (isset($_tmpEntryID) && $_tmpEntryID != $entry['id']) $e++;
                    $_tmpEntryID = $entry['id'];

                    // SAME FOR ENTRYPROPERTIES CACHE for ep_cache_body
                    $epq1 = "SELECT entryid, value
                               FROM {$serendipity['dbPrefix']}entryproperties
                              WHERE entryid = $id AND property = 'ep_cache_body'";
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_body):ID:{$entry['id']}\n$epq1"); }
                    $eps1 = serendipity_db_query($epq1, false, 'assoc');
                    if (is_array($eps1)) {
                        $eps1['value'] = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $eps1['value']);
                        $uepq1 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                     SET value = '" . serendipity_db_escape_string($eps1['value']) . "'
                                   WHERE entryid =  " . (int)$eps1['entryid'] . "
                                     AND property = 'ep_cache_body'";
                        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT-UPDATE entryproperties DB:\nENTRY_ID:{$eps1['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_body) SUB-UPDATE " .DONE); }
                        serendipity_db_query($uepq1);
                    }
                    // SAME FOR ENTRYPROPERTIES CACHE for ep_cache_extended
                    $epq2 = "SELECT entryid, value
                               FROM {$serendipity['dbPrefix']}entryproperties
                              WHERE entryid = $id AND property = 'ep_cache_extended'";
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_extended):ID:{$entry['id']}\n$epq2"); }
                    $eps2 = serendipity_db_query($epq2, false, 'assoc');
                    if (is_array($eps2)) {
                        $eps2['value'] = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $eps2['value']);
                        $uepq2 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                     SET value = '" . serendipity_db_escape_string($eps2['value']) . "'
                                   WHERE entryid =  " . (int)$eps2['entryid'] . "
                                     AND property = 'ep_cache_extended'";
                        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT-UPDATE entryproperties DB:\nENTRY_ID:{$eps2['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_extended) SUB-UPDATE " .DONE); }
                        serendipity_db_query($uepq2);
                    }
                }
            }

            if ($hook_sp) {
                // SAME FOR STATICPAGES [non-hooked]
                if ($serendipity['dbType'] == 'mysqli') {
                    $sq = "SELECT id, content, pre_content
                             FROM {$serendipity['dbPrefix']}staticpages
                            WHERE content     REGEXP '(src=|srcset=|href=|data-fallback=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'
                               OR pre_content REGEXP '(src=|srcset=|href=|data-fallback=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ")'";
                } else {
                    $sq = "SELECT id, content, pre_content
                             FROM {$serendipity['dbPrefix']}staticpages
                           WHERE (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')
                              OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . "%')";
                }
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag ADDITIONAL-SELECT staticpages DB::sp:\n$sq"); }
                $spages = serendipity_db_query($sq, false, 'assoc');
                if (is_array($spages)) {
                    foreach($spages AS $spage) {
                        $spage['content']     = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $spage['content']);
                        $spage['pre_content'] = preg_replace('@(src=|srcset=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newThumbnail, $spage['pre_content']);
                        $pq = "UPDATE {$serendipity['dbPrefix']}staticpages
                                  SET content = '" . serendipity_db_escape_string($spage['content']) . "' ,
                                      pre_content = '" . serendipity_db_escape_string($spage['pre_content']) . "'
                                WHERE id =  " . serendipity_db_escape_string($spage['id']);
                        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag ADDITIONAL-UPDATE staticpages DB:\nID:{$spage['id']} {$serendipity['dbPrefix']}staticpages::[content|pre_content] UPDATE " .DONE); }
                        serendipity_db_query($pq);
                        // count the staticpage entries changed
                        if (isset($_tmpStaticpID) && $_tmpStaticpID != $spage['id']) $s++;
                        $_tmpStaticpID = $spage['id'];
                    }
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
 * Create ORIGIN TARGET full file Variations
 *
 * @access protected
 * @param   string      Source Filename to work on
 * @param   array       File pathinfo properties (ext)
 * @param   array       Port already indexed messages
 *
 * @return  array       $messages
 */
function serendipity_createFullFileVariations($target, $info, $messages) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

    // Create a target copy variation in WebP image format
    if (file_exists($target) && $serendipity['useWebPFormat'] && !in_array(strtolower($info['extension']), ['webp', 'avif'])) {
        $odim = filesize($target);
        $variat = serendipity_makeImageVariationPath($target, 'webp');
        $webpIMQ = -1;
        #   1024 B x            3.6 MB         6 MB           9 MB           12 MB
        $dimensions = [0 => -1, 3686400 => 90, 6144000 => 85, 9216000 => 80, 12288000 => 75];
        foreach ($dimensions AS $dk => $dv) {
            if ($odim > $dk) {
                $webpIMQ = $dv; // Origins WebP ImageMagick variation copy QUALITY only, in case it is big, else we might get bigger WebP lossless expression than the origin file
            }
        }
        $result = serendipity_convertToWebPFormat($target, $variat['filepath'], $variat['filename'], mime_content_type($target), false, $webpIMQ);
        if (is_array($result)) {
            $messages[] = '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> WebP image format variation(s) created!</span>'."\n";
            if (is_array($result) && $result[0] == 0) {
                if (is_string($result[1])) {
                    if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: Image WebP format creation success ${result[2]} from $target " . DONE); }
                } else {
                    if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: ImageMagick CLI Image WebP format creation success ${result[2]} from $target " . DONE); }
                }
            }
        } else {
            $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> WebP image format copy creation failed!</span>'."\n";
            if ($serendipity['magick'] !== true) {
                if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: GD Image WebP format creation failed"); }
            } else {
                if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: ImageMagick CLI Image WebP format creation failed"); }
            }
        }
    }
    // Create a target copy variation in AVIF image format
    if (file_exists($target) && $serendipity['useAvifFormat'] && !in_array(strtolower($info['extension']), ['webp', 'avif'])) {
        $restrictedBytes = 14680064; // 14MB
        if (filesize($target) > $restrictedBytes && $serendipity['magick'] === true) {
            //void
            $messages[] = '<span class="msg_notice"><span class="icon-attention-circled" aria-hidden="true"></span> No AVIF image format variation(s) with ImageMagick created, since Origin is too big '.filesize($target)."! Sorry! Limit is currently set at 14MB.</span>\n";
            if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: No AVIF image format created ${result[2]} from $target - Limit is currently at 14MB"); }
        } else {
            $variat = serendipity_makeImageVariationPath($target, 'avif');
            $result = serendipity_convertToAvifFormat($target, $variat['filepath'], $variat['filename'], mime_content_type($target), false);
            if (is_array($result)) {
                $messages[] = '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> AVIF image format variation(s) created!</span>'."\n";
                if (is_array($result) && $result[0] == 0) {
                    if (is_string($result[1])) {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: Image AVIF format creation success ${result[2]} from $target " . DONE); }
                    } else {
                        if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: ImageMagick CLI Image AVIF format creation success ${result[2]} from $target " . DONE); }
                    }
                }
            } else {
                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> AVIF image format copy creation failed!</span>'."\n";
                if ($serendipity['magick'] !== true) {
                    if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: GD Image AVIF format creation failed"); }
                } else {
                    if ($debug) { $serendipity['logger']->debug("ML_CREATEVARIATION: ImageMagick CLI Image AVIF format creation failed"); }
                }
            }
        }
    }
    return $messages;
}

/**
 * Unlink/Delete a (thumb) files variation for ML maintenance synchronization
 * and/or serendipity_deleteImage()
 *
 * @param  string $origin/Thumb/File The fullpath (thumb) file of the image object
 * @access private
 * @return
 */
function serendipity_syncUnlinkVariation($originThumbFile) {
    $variant = ['avif', 'webp'];
    $varfile = array();

    foreach ($variant AS $ext) {
        $varfile = serendipity_makeImageVariationPath($originThumbFile, $ext);
        if (file_exists($varfile['filepath'] . '/.v/' . $varfile['filename'])) {
            unlink($varfile['filepath'] . '/.v/' . $varfile['filename']);
        }
    }
    return true;
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
        $fdim    = @serendipity_getImageSize($ffull, $ft_mime);

        if (!empty($_list)) {
            $_list .= '<div class="media_sync_list">' . "\n";
        }
        // If we're supposed to delete thumbs, this is the easiest place. Leave messages plain unstiled.
        if (is_readable($fthumb)) {
            if ($deleteThumbs === true) {
                if (unlink($fthumb)) {
                    // Silently delete an already generated .v/fthumb.[webp|avif] variation file too
                    serendipity_syncUnlinkVariation($fthumb);
                    $_list .= sprintf(DELETE_THUMBNAIL, $sThumb);
                    $_br = "<br>\n";
                    $i++;
                }
            } else if ($deleteThumbs == 'checksize') {
                // Find existing thumbnail dimensions - does look redundant, but IS necessary!
                $tdim = @serendipity_getImageSize($fthumb);
                if (isset($tdim['noimage'])) {
                    // Delete it so it can be regenerated
                    if (unlink($fthumb)) {
                        // Silently delete an already generated .v/fthumb.[webp|avif] variation file too
                        serendipity_syncUnlinkVariation($fthumb);
                        $_list .= sprintf(DELETE_THUMBNAIL, $sThumb);
                        $_br = "<br>\n";
                        $i++;
                    }
                } else {
                    // Calculate correct thumbnail size from original image
                    $expect = serendipity_calculateAspectSize($fdim[0], $fdim[1], $serendipity['thumbSize'], $serendipity['thumbConstraint']);
                    // Check actual thumbnail size
                    if ($tdim[0] != $expect[0] || $tdim[1] != $expect[1]) {
                        // This thumbnail is incorrect; delete it so
                        // it can be regenerated
                        if (unlink($fthumb)) {
                            // Silently delete an already generated .v/fthumb.[webp|avif] variation file too
                            serendipity_syncUnlinkVariation($fthumb);
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
                $update['dimensions_width'] = (int)$fdim[0];
            }

            // Is the height correct?
            if (isset($fdim[1]) && $rs['dimensions_height'] != $fdim[1]) {
                $update['dimensions_height'] = (int)$fdim[1];
            }

            // Is the image size correct?
            if ($rs['size'] != filesize($ffull)) {
                $update['size'] = (int)@filesize($ffull);
            }

            // Does it exist and is an image and has the thumbnail suffix changed?
            $checkfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $rs['path'] . $rs['name'] . '.' . $rs['thumbnail_name'] . (empty($rs['extension']) ? '' : '.' . $rs['extension']);
            if (!file_exists($checkfile) && empty($fdim['noimage']) && file_exists($fthumb)) {
                $update['thumbnail_name'] = $serendipity['thumbSuffix'];
            }

            // Do the database update, if needed
            if (sizeof($update) != 0 && !preg_match('@/.v/@', $files[$x])) {
                $_list .= $_br . sprintf(FOUND_FILE . " (<em>Update in database</em>)", $files[$x]);
                serendipity_updateImageInDatabase($update, $rs['id']);
                $i++;
            }

        } else {
            if (!preg_match('@.v/@', $fdir)) {
                $_list .= $_br . sprintf(FOUND_FILE . " (<em>Insert in Database</em>)", $files[$x]);
                serendipity_insertImageInDatabase($fbase . '.' . $f[1], $fdir, 0, (int)@filemtime($ffull));
                $i++;
            }
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
 * @param  string       Filename to operate on
 * @param  int          Possible AVIF/WebP format Quality change
 * @return string       Name of GD function to execute
 */
function serendipity_functionsGD($infilename, $q = null) {
    if (!function_exists('imagecopyresampled')) {
        return false;
    }

    $qual = is_null($q) ? 75 : $q; // currently WebP only
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
            $func['qual'] = 9; // Compression levels: 0-9, so this equals 100%
            break;

        case 'webp':
            $func['load'] = 'imagecreatefromwebp';
            $func['save'] = 'imagewebp';
            $func['qual'] = $qual; // variations shall still use the default 75 quality level, since formats of ORIGINs is better with a Q 100, to not have a (increasing) loss.
            break;

        case 'avif':
            $func['load'] = 'imagecreatefromavif';
            $func['save'] = 'imageavif';
            $func['qual'] = -1; // keep a full optimized default quality of -1
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
 * Format an image (GDlib) to another image format
 *
 * @access public
 * @param   string      Source Filename to format
 * @param   string      Target file
 * @param   int         Extension to format to
 * @return  array       width/height of the new image
 */
function serendipity_formatImageGD($infilename, $outfilename, $format) {

    $ifunc = serendipity_functionsGD($infilename, 100);  // Currently this effects WebP formats only, and makes the format to WebP conversion inadvisable, where the infile has already been optimized for the Web.
    $ofunc = serendipity_functionsGD($outfilename, 100); // Ditto
    if (!is_array($ifunc) || !is_array($ofunc) || empty($format)) {
        return false;
    }

    $in = $ifunc['load']($infilename); // this is the resource

    switch($format) {
        case 'jpg':
        case 'jpeg':
            $out = imagejpeg($in, $outfilename); // these just give back booleans
            break;
        case 'png':
            $out = imagepng($in, $outfilename);
            break;
        case 'gif':
            $out = imagegif($in, $outfilename);
            break;
        case 'webp':
            $out = imagewebp($in, $outfilename);
            break;
        case 'avif':
            $out = imageavif($in, $outfilename);
            break;
        default:
            break;
    }

    $ofunc['save']($in, $outfilename, $ofunc['qual']);

    $newwidth  = imagesx($in);
    $newheight = imagesy($in);

    $out       = null;
    $in        = null;

    return array($newwidth, $newheight);
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
function serendipity_rotateImageGD($infilename, $outfilename, $degrees) {

    $func = serendipity_functionsGD($infilename);
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
function serendipity_resizeImageGD($infilename, $outfilename, $newwidth, $newheight = null) {

    $func = serendipity_functionsGD($infilename);
    if (!is_array($func)) {
        return false;
    }

    try {
        // If an image exist that can not be loaded (invalid GIF for example), the page shall still be rendered
        $in = @$func['load']($infilename);
    } catch (Throwable $t) {
        echo 'serendipity_resizeImageGD(): Could not create thumbnail resource: ',  $t->getMessage(), "\n";
        return false;
    }

    // imagecreatefromwebp() -> imagesx() expects parameter 1 to be resource, bool given when is animated gif for example
    if (is_bool($in)) {
        return false;
    }

    $width  = imagesx($in);
    $height = imagesy($in);

    if (is_null($newheight)) {
        $newsizes  = serendipity_calculateAspectSize($width, $height, $newwidth, 'width');
        $newwidth  = $newsizes[0];
        $newheight = $newsizes[1];
    }

    if (is_null($newwidth)) {
        $newsizes  = serendipity_calculateAspectSize($width, $height, $newheight, 'height');
        $newwidth  = $newsizes[0];
        $newheight = $newsizes[1];
    }

    $out = imagecreatetruecolor($newwidth, $newheight);

    /* Attempt to copy transparency information, this really only works for PNG */
    if (function_exists('imagesavealpha') && $func['save'] == 'imagepng') {
        imagealphablending($out, false);
        imagesavealpha($out, true);
    }

    imagecopyresampled($out, $in, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    @umask(0000);
    touch($outfilename); // safe_mode requirement
    @$func['save']($out, $outfilename, $func['qual']); // mute possible expectations, eg. animated gifs with GD
    @chmod($outfilename, 0664);
    $out = null;
    $in  = null;

    return array((float)$newwidth, $newheight);
}

/**
 * Deprecation compatibility wrapper for Plugins (imageselectorplus)
 *
 * @access public
 * @return  serendipity_calculateAspectSize()
 */
function serendipity_calculate_aspect_size($width, $height, $size, $constraint = null) {
    return serendipity_calculateAspectSize($width, $height, $size, $constraint);
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
function serendipity_calculateAspectSize($width, $height, $size, $constraint = null) {

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
 * @param   boolean Is this the ML-Version for managing everything (true), or is it about selecting one image for the editor? (false)
 * @param   string  The URL to use for pagination
 * @param   boolean Show the "upload media item" feature?
 * @param   boolean Restrict viewing images to a specific directory
 * @param   array   Map of Smarty vars transported into all following templates
 * @return  string  Generated HTML
 */
function serendipity_displayImageList($page = 0, $manage = false, $url = NULL, $show_upload = false, $limit_path = NULL, $smarty_vars = array()) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger
    if ($debug) {
        $logtag = 'ML-LIST::';
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
    $start = ($page-1) * $perPage;

    if ($manage && $limit_path === NULL) {
        ## SYNC START ##
        $aExclude = array('CVS' => true, '.svn' => true, '.git' => true); // removed ", '.v' => true", which allows to place an existing .v/ dir stored AVIF/Webp image variation in the aFilesNoSync array! See media_items.tpl special.pfilename button.
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
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag {$sFile['relpath']} is a directory."); }
                // remove the hidden .v/ directory from media.path select lists, since we need it for handlers but not for user directory select lists
                if (!preg_match('@.v/@', $sFile['relpath'])) {
                    array_push($paths, $sFile);
                }
            } else {
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag {$sFile['relpath']} is a file."); }
                if ($sFile['relpath'] == '.empty' || false !== strpos($sFile['relpath'], '.quickblog.') || ( preg_match('@.v/@', $sFile['relpath']) && preg_match('@[.webp|.avif]$@', $sFile['relpath']) )) {
                    if ($sFile['relpath'] != '.empty' && (!isset($serendipity['aFilesNoSync']) || !in_array($sFile['relpath'], (array)$serendipity['aFilesNoSync']))) {
                        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Found aFilesNoSync = {$sFile['relpath']}."); }
                        $path_parts = pathinfo($sFile['relpath']);
                        $fdim = @serendipity_getImageSize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sFile['relpath'], '', $path_parts['extension']);
                        $aFilesNoSync[$sFile['relpath']] = array(
                            'dirname'   => $path_parts['dirname'],
                            'basename'  => $path_parts['basename'],
                            'filename'  => $path_parts['filename'],
                            'pfilename' => str_replace('.quickblog', '', $path_parts['filename']),
                            'extension' => $path_parts['extension'],
                            'filesize'  => @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sFile['relpath']),
                            'url'       => $serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $sFile['relpath'],
                            'fdim'      => $fdim,
                            'width'     => $fdim[0] ?? 0,/*regression fix for temporary serendipity_getImageSize() getimagesize avif hotfix*/
                            'height'    => $fdim[1] ?? 0,/*regression fix for temporary serendipity_getImageSize() getimagesize avif hotfix*/
                            'mime'      => $fdim['mime'],
                        ); // store this in a cache file to use later (we use $serendipity['aFilesNoSync'] for this currently)
                    }
                    // This is a special sized serendipity thumbnail, OR an item ranged "~outside" ML (see imageselectorplus event plugin), OR a hidden .v/ dir stored AVIF/Webp image file variation; skip it!
                    continue;
                }
                // Store the file in our array, remove any ending slashes
                $aFilesOnDisk[$sFile['relpath']] = 1;
            }
            unset($aResultSet[$sKey]);
        }

        // Run a looped filesize comparison for AVIF vs WebP sizes to check this referenced variable for the special Variation URL link in media_items.tpl
        if (isset($aFilesNoSync) && is_array($aFilesNoSync)) {
            foreach ($aFilesNoSync AS $k => &$v) {
                if ($v['extension'] == 'avif') {
                    $v['linknext'] = false;
                    $checkAVIFSibling = str_replace('.avif', '.webp', $k);
                    // check the array again for the sibling
                    if (array_key_exists($checkAVIFSibling, $aFilesNoSync)) {
                        if ($v['filesize'] > $aFilesNoSync[$checkAVIFSibling]['filesize'] && $aFilesNoSync[$checkAVIFSibling]['filesize'] > 0) {
                            $v['linknext'] = true; // push to parent
                        }
                    }
                }
            }
        }

        usort($paths, 'serendipity_sortPath');

        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Got real disc files: " . print_r($aFilesOnDisk, 1)); }
        $serendipity['current_image_hash'] = md5(serialize($aFilesOnDisk));
        $serendipity['last_image_hash'] = $serendipity['last_image_hash'] ?? ''; // avoid a non-isset by a relatively new image database which had never run setting the $serendipity['last_image_hash'] before

        // ML Cleanup START - is part of SYNC
        // MTG 21/01/06: request all images from the database, delete any which don't exist
        // on the filesystem, and mark off files from the file list which are already
        // in the database

        $nTimeStart = microtime_float();
        $nCount = 0;

        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Image-Sync has perm: " . serendipity_checkPermission('adminImagesSync') . ", Onthefly Sync: {$serendipity['onTheFlySynch']}, Hash: " . ($serendipity['current_image_hash'] != $serendipity['last_image_hash'] ? "uneven, cleanup" : "even, skip cleanup")); }

        if ($serendipity['onTheFlySynch'] && serendipity_checkPermission('adminImagesSync')
        && $serendipity['current_image_hash'] != $serendipity['last_image_hash']) {
            $aResultSet = serendipity_db_query("SELECT id, name, extension, thumbnail_name, path, hotlink
                                                  FROM {$serendipity['dbPrefix']}images WHERE path != '.v/'", false, 'assoc'); // exclude possible variations (.v/ path should only be if that was development or somethings has went wrong)

            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Got images: " . print_r($aResultSet, 1)); }

            if (is_array($aResultSet)) {
                $msgdelfile = [];
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

                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag File name is $sFileName, thumbnail is $sThumbNailFile"); }

                    unset($aResultSet[$sKey]);

                    // check existing realFiles against remaining files without any reference to cleanup
                    if (isset($aFilesOnDisk[$sFileName])) {
                        unset($aFilesOnDisk[$sFileName]);
                    } else {
                        if (!$sFile['hotlink']) {
                            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Deleting Image {$sFile['id']}"); }

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
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Cleaned up $nCount database entries"); }
            }

            serendipity_set_config_var('last_image_hash', $serendipity['current_image_hash'], 0);
            $aUnmatchedOnDisk = array_keys($aFilesOnDisk);

            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Got unmatched files: " . print_r($aUnmatchedOnDisk, 1)); }

            $nCount = 0;
            foreach($aUnmatchedOnDisk AS $sFile) {
                if (preg_match('@.' . $serendipity['thumbSuffix'] . '.@', $sFile) || preg_match('@.v/@', $sFile)) {
                    // this means from now on these image variations are not added to the database any more!
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Skipping special cased hidden directory AND/OR thumbnail file $sFile"); }
                    continue;
                } else {
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Checking $sFile"); }
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
                    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Inserting image $sFileName from $sDirectory" . print_r($aImageData, 1) . "\ninto database"); }

                    // added block for accessing serendipity_createFullFileVariations()
                    $info = pathinfo($sFileName);
                    @umask(0000);
                    @chmod($sFileName, 0664);
                    $infile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sDirectory . $sFileName;

                    if ($debug) { $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START ML case MANUALLY ADDED file(s) CREATE VARIATIONS SEPARATOR" . str_repeat(" <<< ", 10) . "\n"); }

                    // Create ORIGIN TARGET full file VARIATIONS in case these were uploaded by hand!
                    $messages = serendipity_createFullFileVariations($infile, $info, []);

                    serendipity_makeThumbnail($sFileName, $sDirectory);
                    serendipity_insertImageInDatabase($sFileName, $sDirectory);
                    ++$nCount;
                }
            }

            if ($nCount > 0) {
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Inserted $nCount images into the database"); }
            }
        } else {
            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Media Gallery database is up to date"); }
        }

         /*
         $nTimeEnd = microtime_float();
         $nDifference = $nTimeEnd - $nTimeStart;
         echo "<p> total time taken was $nDifference </p>\n";
        */
        ## SYNC FINISHED ##
    }

    // Out of Sync Files
    if (!isset($aFilesNoSync)) $aFilesNoSync = array();
    if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag ".print_r($aFilesNoSync,1)); }
    $serendipity['aFilesNoSync'] = $aFilesNoSync;
    $serendipity['smarty']->assign('imagesNoSync', $aFilesNoSync);

    ## Apply ACL afterwards:
    serendipity_directoryACL($paths, 'read');

    // Set filters (FIRST PART of serendipity_showMedia() remember filter settings for SetCookie ~1450(?))
    // Set remember filter settings for SetCookie
    if (!isset($serendipity['GET']['filter'])) {
        serendipity_restoreVar($serendipity['COOKIE']['filter'], $serendipity['GET']['filter']);
    }

    // Check the restored or set ['GET']['filter']
    if (!empty($serendipity['GET']['filter'])) {
        $sfilters = array_filter($serendipity['GET']['filter']);
        // reset for empty value iteration
        if (isset($sfilters['fileCategory']) && $sfilters['fileCategory'] == 'all') {
            $sfilters['fileCategory'] = '';
        }
    }
    // REMEMBER: IMAGE/VIDEO are filters - ALL is not!

    // sFILTER is singularly used for media grid appearance
    $sfilter = isset($sfilters) ? serendipity_emptyArray($sfilters) : false;

    if ($displayGallery) {
        // don't touch cookie and normal settings, but hard set in case of gallery usage
        $serendipity['GET']['filter']['fileCategory'] = 'image'; // filter restrict to mime part 'image/%' only!
        $hideSubdirFiles = true; // Definitely YES!
    }
    $serendipity['imageList'] = serendipity_fetchImagesFromDatabase(
                                  $start,
                                  $perPage,
                                  $totalImages, // Passed by ref
                                  $serendipity['GET']['sortorder']['order'] ?? false,
                                  $serendipity['GET']['sortorder']['ordermode'] ?? false,
                                  $serendipity['GET']['only_path'] ?? '',
                                  null,
                                  $serendipity['GET']['keywords'] ?? '',
                                  $serendipity['GET']['filter'] ?? null,
                                  $hideSubdirFiles
    );

    $pages         = ceil($totalImages / $perPage);
    $linkPrevious  = '?' . $extraParems . '&amp;serendipity[page]=' . ($page-1);
    $linkNext      = '?' . $extraParems . '&amp;serendipity[page]=' . ($page+1);
    // Keep the inner to be build first. Now add first and last. Has to do with adding $param to $extraParems.
    $linkFirst     = '?' . $extraParems . '&amp;serendipity[page]=' . 1;
    $linkLast      = '?' . $extraParems . '&amp;serendipity[page]=' . $pages;

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
        'use_mediagrid' => $sfilter ? false : true,
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
        'totalImages'   => $totalImages,
        'supportsWebP'  => $serendipity['useWebPFormat'] ?? false,
        'supportsAVIF'  => $serendipity['useAvifFormat'] ?? false
    ));

    return serendipity_showMedia(
        $serendipity['imageList'],
        $paths,
        $url,
        $manage,
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
    $importParams = array('adminModule', 'htmltarget', 'filename_only', 'textarea', 'subpage',  'keywords', 'noBanner', 'noSidebar', 'noFooter', 'showUpload', 'showMediaToolbar');
    $extraParems  = '';
    $filterParams = $serendipity['GET']['filter'] ?? array();

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
        if (is_null($value) || empty(trim($value))) continue;
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
    $file['location'] = !$file['hotlink'] ? $serendipity['serendipityPath'] . preg_replace('@^(' . preg_quote($serendipity['serendipityHTTPPath']) . ')@i', '', ($file['imgsrc'] ?? '')) : '';

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
        $filestack = [];
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
                    if ($serious && unlink($basedir . $file)) {
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
 * Checks if a new dir in dirpath needs to be created first in moving situations - then rename()
 *
 * @param  string   The old path
 * @param  string   The new path
 * @return  mixed   rename result (bool/error string)
 */
function serendipity_makeDirRename($from, $to) {
    // check moving a dir to a new mkdir directory location
    if (file_exists($from) && !file_exists($to)) {
        $_tmppath = dirname($to);
        if (!is_dir($_tmppath)) {
            @mkdir($_tmppath);
        }
        return rename($from, $to);
    }
    return null;
}

/**
 * Recursively walk a directory tree
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
        // add possible historic CKEditors .thumb dir to exclude, since no hook
        // do not use as auto excludes for media directory restrictions .v/ case, since that disables ML AVIF/WebP cases
        $aExcludeDirs = array('CVS' => true, '.svn' => true, '.thumbs' => true, '.git' => true);
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
function serendipity_getImageSize($file, $ft_mime = '', $suf = '') {
    if (empty($ft_mime) && !empty($suf)) {
        $ft_mime = serendipity_guessMime($suf);
    }

    if ($ft_mime == 'application/pdf') {
        $fdim = array(1000,1000,24, '', 'bits'=> 24, 'channels' => '3', 'mime' => 'application/pdf');
    } else {
        if ($suf == 'avif') {// temp HOTFIX, see L~2807 aFilesNoSync - PHP 8.1 getimagesize() has no build-in AVIF size measures yet, but people at Google are currently working on implementation
            $_file = str_replace('.avif', '.webp', $file);
            $fdim = @getimagesize($_file);
            $fdim[2] = str_replace(18, 19, $fdim[2]);
            $fdim['mime'] = str_replace('image/webp', 'image/avif', $fdim['mime']);
            unset($fdim['bits']);
        } else {
            $fdim = @getimagesize($file);
            if ($fdim['mime'] == 'image/avif' && file_exists(str_replace('.avif', '.webp', $file))) { // check possible other non-suffix calls of AVIF files for the HOTFIX - probably serendipity_checkMediaSize() won't work, so upload sizes cannot be checked yet
                $fdim = serendipity_getImageSize($file, $ft_mime, 'avif');
            }
        }
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

    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger
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

    $aSizeData = @serendipity_getImageSize($sImagePath , '', $sExtension);
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
        $show[$idx]['property_saved'] = !(!isset($props['base_property']) || (empty($props['base_property']['ALL']['ALT']) && empty($props['base_property']['ALL']['COMMENT1']) && empty($props['base_property']['ALL']['COMMENT2']) && $props['base_property']['ALL']['TITLE'] === $props['base_property']['internal']['realname'])) ? true: false; // three possible cases: no subarray, equal or diff set

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
        $val = serendipity_mediaTypeCast($parts[0], ($props['base_property']['ALL'][$parts[0]] ?? 0), true);

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
                        $default_iptc_val = $serendipity['serendipityUser'] ?? null;
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
 * Update single media properties. Mainly used for renaming media filenames.
 *
 * @param   int     Media ID
 * @param   string  Property_fields to check for
 * @param   string  The SET value
 */
function serendipity_updateSingleMediaProperty($image_id, $property_fields, $setval) {
    global $serendipity;

    $AND = '';
    if (is_array($property_fields)) {
        foreach ($property_fields AS $field => $val) {
            $AND .= ' AND ' . $field . ' = "' . $val .'"';
        }
    } else {
        return;
    }
    $q = "UPDATE {$serendipity['dbPrefix']}mediaproperties
             SET value = '" . serendipity_db_escape_string($setval) . "'
           WHERE mediaid = " . (int)$image_id . $AND;
    serendipity_db_query($q);
}

/**
 * Inserts or updates media properties
 *
 * @param   string  Property_group
 * @param   string  Property_Subgroup
 * @param   int     Image ID
 * @param   array   Referenced Media properties
 * @param   bool    Media Type Cast
 * @param   string  Property_group
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
                    $insert_val = serendipity_mediaTypeCast($insert_key, ($insert_val ?? ''));
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
 * Consequently this should only be a single file submit without having to push an array key - but we just keep it prepared to provide a valid structure
 *
 * @return array    array('image_id') holding the last created/changed media (thumbnail) id for immediate processing
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

    }

    // check media properties form sub case changing the medias directory place. Having a key for all these is just for dummy structure purposes
    if (is_array($serendipity['POST']['mediaDirectory']) && $serendipity['POST']['mediaDirectory'][0]['oldDir'] != $serendipity['POST']['mediaDirectory'][0]['newDir']) {
        foreach($serendipity['POST']['mediaDirectory'] AS $id => $filedir) {
            serendipity_moveMediaDirectory(
                serendipity_uploadSecure($filedir['oldDir']),
                serendipity_uploadSecure($filedir['newDir']),
                'filedir',
                $serendipity['POST']['mediaProperties'][$id]['image_id']);
        }
    }

    // check media properties form sub case changing the media keywords
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
 */
function serendipity_prepareMedia(&$file, $url = '') {
    global $serendipity;
    static $full_perm = null;

    if ($full_perm === null) {
        $full_perm = serendipity_checkPermission('adminImagesMaintainOthers');
    }

    $file['hotlink'] = $file['hotlink'] ?? null;

    $sThumbSource      = serendipity_getThumbNailPath($file['path'], $file['name'], $file['extension'], $file['thumbnail_name']);
    $sThumbSource_webp = serendipity_getThumbNailPath($file['path'].'.v/', $file['name'], 'webp', $file['thumbnail_name']);
    $sThumbSource_avif = serendipity_getThumbNailPath($file['path'].'.v/', $file['name'], 'avif', $file['thumbnail_name']);

    if (! $file['hotlink']) {
        $file['full_path_thumb'] = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource;
        $file['full_thumb']      = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $sThumbSource;

        if (file_exists($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource_webp)) {
            $file['full_thumb_webp'] = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $sThumbSource_webp;
            $file['thumbSizeWebp']   = @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource_webp);
        }
        if (file_exists($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource_avif)) {
            $file['full_thumb_avif'] = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $sThumbSource_avif;
            $file['thumbSizeAVIF']   = @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $sThumbSource_avif);
        }
    }

    $file['url'] = $url;

    if ($file['hotlink']) {
        $file['full_file']  = $file['path'];
        $file['show_thumb'] = $file['path'];
        if (!isset($file['imgsrc'])) {
            $file['imgsrc'] = $file['show_thumb'];
        }
        $file['full_file_webp'] = $file['full_file_webp'] ?? null; // avoid template errors on hotlinked images
        $file['full_file_avif'] = $file['full_file_avif'] ?? null; // avoid template errors on hotlinked images
    } else {
        $file['full_file']      = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $file['full_path_file'] = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $file['show_thumb']     = $file['full_thumb'];

        if (file_exists($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.webp')) {
            $file['full_file_webp'] = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $file['path'] . '.v/' . $file['name'] . '.webp';
            $file['sizeWebp']       = @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.webp');
            if (file_exists($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.avif')) {
                $file['full_file_avif'] = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $file['path'] . '.v/' . $file['name'] . '.avif';
                $file['sizeAVIF']       = @filesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . '.v/' . $file['name'] . '.avif');
            }
        } else {
            $file['full_file_webp'] = null; // avoid template errors
            $file['full_file_avif'] = null; // ditto
        }
        if (!isset($file['imgsrc'])) {
            $file['imgsrc'] = $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        }
    }

    // Detect PDF thumbs
    if (isset($file['full_path_thumb']) && file_exists($file['full_path_thumb'] . '.png')) {
        $file['full_path_thumb'] .= '.png';
        $file['full_thumb']      .= '.png';
        $file['show_thumb']      .= '.png';
        $sThumbSource            .= '.png';
    }

    if (empty($file['realname'])) {
        $file['realname'] = $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);
    }
    $file['diskname'] = $file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']);

    $file['links'] = array('imagelinkurl' => $file['full_file']);

    $file['is_image']  = serendipity_isImage($file);
    $file['dim']       = ($file['is_image'] && isset($file['full_path_thumb'])) ? @getimagesize($file['full_path_thumb'], $file['thumb_header']) : null;
    $file['dim_orig']  = ($file['is_image'] && isset($file['full_path_file'])) ? @getimagesize($file['full_path_file'], $file['header']) : null;
    // check possible non-sets
    $file['dim'] = is_array($file['dim']) ? $file['dim'] : array(0 => null, 1 => null);
    $file['dim_orig'] = is_array($file['dim_orig']) ? $file['dim_orig'] : array(0 => null, 1 => null);

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
    if ($file['is_image'] && isset($file['full_path_thumb']) && file_exists($file['full_path_thumb'])) {
        $file['thumbWidth']  = $file['dim'][0] ?? null;
        $file['thumbHeight'] = $file['dim'][1] ?? null;
        $file['thumbSize']   = isset($file['full_path_thumb']) ? filesize($file['full_path_thumb']) : null;
    } elseif ($file['is_image'] && $file['hotlink']) {
        $sizes = serendipity_calculateAspectSize($file['dimensions_width'], $file['dimensions_height'], $serendipity['thumbSize'], $serendipity['thumbConstraint']);
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
    #if ($file['hotlink']) {//no need up from 2.0
    #    $file['nice_hotlink'] = wordwrap($file['path'], 45, '<br>', 1);
    #}
    $file['nice_size'] = number_format(round($file['size']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    if (isset($file['thumbSize'])) {
        $file['nice_thumbsize'] = number_format(round($file['thumbSize']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }
    if (isset($file['sizeWebp'])) {
        $file['nice_size_webp'] = number_format(round($file['sizeWebp']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }
    if (isset($file['thumbSizeWebp'])) {
        $file['nice_thumbsize_webp'] = number_format(round($file['thumbSizeWebp']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }
    if (isset($file['sizeAVIF'])) {
        $file['nice_size_avif'] = number_format(round($file['sizeAVIF']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }
    if (isset($file['thumbSizeAVIF'])) {
        $file['nice_thumbsize_avif'] = number_format(round($file['thumbSizeAVIF']/1024, 2), NUMBER_FORMAT_DECIMALS, NUMBER_FORMAT_DECPOINT, NUMBER_FORMAT_THOUSANDS);
    }

    // inits
    if (!isset($file['full_thumb_webp'])) $file['full_thumb_webp'] = null;
    if (!isset($file['full_thumb_avif'])) $file['full_thumb_avif'] = null;

    return true;
}

/**
 * Prints a media item
 *
 * @param  array    Array of image metadata
 * @param  array    Array of additional image metadata like mediaKeywords or paths
 * @param  string   URL for maintenance tasks
 * @param  boolean  Whether to show maintenance task items
 * @param  boolean  Enclose within a table cell?
 * @param  array    Additional Smarty variables
 * @return string   Generated HTML
 */
function serendipity_showMedia(&$file, &$paths, $url = '', $manage = false, $enclose = true, $smarty_vars = array()) {
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
    $smarty_vars['use_mediagrid'] = $displayGallery ? false : ($smarty_vars['use_mediagrid'] ?? null); // fixes case virgin system

    if (!is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }
    $order_fields = serendipity_getImageFields();

    $media = array(
        'standardpane'      => $displayGallery ? false : true,
        'grid'              => $smarty_vars['use_mediagrid'] ?? false,
        'manage'            => $manage,
        'multiperm'         => serendipity_checkPermission('adminImagesDirectories'),
        'resetperm'         => (serendipity_checkPermission('adminImagesDelete') && serendipity_checkPermission('adminImagesMaintainOthers')),
        'viewperm'          => (serendipity_checkPermission('adminImagesView') && $serendipity['GET']['adminAction'] != 'choose'),
        'url'               => $url,
        'enclose'           => $enclose,
        'token'             => serendipity_setFormToken(),
        'form_hidden'       => $form_hidden,
        'blimit_path'       => empty($smarty_vars['limit_path']) ? '' : basename($smarty_vars['limit_path']),
        'only_path'         => $serendipity['GET']['only_path'] ?? '',
        'sortorder'         => $serendipity['GET']['sortorder'] ?? '',
        'keywords_selected' => $serendipity['GET']['keywords'] ?? '',
        'filter'            => $serendipity['GET']['filter'] ?? null,/* NIL or array() (media_toolbar.tpl) */
        'sort_order'        => $order_fields,
        'simpleFilters'     => $displayGallery ? false : ($serendipity['simpleFilters'] ?? true),
        'metaActionBar'     => ($serendipity['GET']['adminAction'] != 'properties' && empty($serendipity['GET']['fid'])),
        'hideSubdirFiles'   => empty($serendipity['GET']['hideSubdirFiles']) ? 'yes' : $serendipity['GET']['hideSubdirFiles'],
        'authors'           => serendipity_fetchUsers(),
        'sort_row_interval' => array(8, 9, 16, 18, 36, 48, 50, 96, 100),
        'nr_files'          => count($file),
        'keywords'          => explode(';', $serendipity['mediaKeywords']),
        'thumbSize'         => $serendipity['thumbSize'],
        'sortParams'        => array('perpage', 'order', 'ordermode')
    );

    // temporary approach to push convenient supported images formats into the $media array for media properties page
    if (!$enclose && isset($file[0]) && $file[0]['is_image']) {
        $media['formats'] = [   0 => ['mime' => 'image/jpeg', 'extension' => 'jpeg'],
                                1 => ['mime' => 'image/png',  'extension' => 'png' ],
                                2 => ['mime' => 'image/gif',  'extension' => 'gif' ] ];
        if ($serendipity['useWebPFormat']) {
            $media['formats'] = array_merge($media['formats'], [ 3 => ['mime' => 'image/webp',  'extension' => 'webp'] ]);
        }
        if ($serendipity['useAvifFormat']) {
            $media['formats'] = array_merge($media['formats'], [ 4 => ['mime' => 'image/avif',  'extension' => 'avif'] ]);
        }
    }

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
 */
function serendipity_metaFieldConvert(&$item, $type) {
    switch($type) {
        case 'math':
            $parts = explode('/', $item);
            return (($parts[1] > 0) ? ($parts[0] / $parts[1]) : 0);
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
            return mktime((int)$parts[3], (int)$parts[4], (int)$parts[5], $parts[1], $parts[2], $parts[0]);
            break;

        case 'IPTCdate':
            preg_match('@(\d{4})(\d{2})(\d{2})@', $item, $parts);
            return mktime(0, 0, 0, intval($parts[2]), intval($parts[3]), intval($parts[1]));
            break;

        case 'IPTCtime':
            preg_match('@(\d{2})(\d{2})(\d{2})([\+-])(\d{2})(\d{2})@', $item, $parts);
            if (array_keys($parts)) {
                $time = serendipity_strftime('%H:%M', mktime(intval($parts[1]), intval($parts[2]), intval($parts[3]), 0, 0, 0));
                $timezone = serendipity_strftime('%H:%M', mktime(intval($parts[5]), intval($parts[6]), 0, 0, 0, 0));
                return $time . ' GMT' . $parts[4] . $timezone;
            }
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

    if (function_exists('exif_read_data') && is_array($info) && exif_imagetype($file) === IMAGETYPE_JPEG) {
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
        $dim = serendipity_getImageSize($file);
        if (!is_array($dim) || !isset($dim[0])) {
            return true;
        }

        if (!empty($serendipity['maxImgWidth']) && !empty($serendipity['maxImgHeight'])) {
            if ($dim[0] > $serendipity['maxImgWidth'] && $dim[1] > $serendipity['maxImgHeight']) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
                sprintf(MEDIA_UPLOAD_DIMERROR . "<br>\n", (int)$serendipity['maxImgWidth'], (int)$serendipity['maxImgHeight'], INSTALL_CAT_IMAGECONV, MEDIA_UPLOAD_RESIZE) . "</span>\n";
                return false;
            }
        }

        if (!empty($serendipity['maxImgWidth'])) {
            if ($dim[0] > $serendipity['maxImgWidth']) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
                sprintf(MEDIA_UPLOAD_DIMERROR . "<br>\n", (int)$serendipity['maxImgWidth'], "&hellip;", INSTALL_CAT_IMAGECONV, MEDIA_UPLOAD_RESIZE) . "</span>\n";
                return false;
            }
        }

        if (!empty($serendipity['maxImgHeight'])) {
            if ($dim[1] > $serendipity['maxImgHeight']) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' .
                sprintf(MEDIA_UPLOAD_DIMERROR . "<br>\n", "&hellip;", (int)$serendipity['maxImgHeight'], INSTALL_CAT_IMAGECONV, MEDIA_UPLOAD_RESIZE) . "</span>\n";
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
        echo 'P1: Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    $real_oldDir = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . rtrim($oldDir, '/');
    $real_newDir = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . rtrim($newDir, '/');
    // nothing to do for a possible web case!

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
        serendipity_makeDirRename($real_oldDir, $real_newDir);
    } catch (Throwable $t) {
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MEDIA_DIRECTORY_MOVE_ERROR, $newDir) . "</span>\n";
        #echo ': '.$t->getMessage();
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
    // we cannot give anything here which couldn't be done there too - about variations dir path settings
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
    serendipity_plugin_api::hook_event('backend_media_rename', $renameValues); // this is type dir, renamimg media directories

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
        $logtag = 'renameRealFileName::';
        $serendipity['logger']->debug("IN serendipity_renameRealFileName");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_moveMediaDirectory') {
        echo 'P2: Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    #$parts = pathinfo($newDir); // we don't need this here, since we don't care about extension, don't we?!

    // BUILD or RENAME: "new", "thumb" and "old file" names, relative to Serendipity "uploads/" root path, eg. "a/b/c/"

    // case rename only
    if ($oldDir === null && $newDir != 'uploadRoot/') {

        // case single file re-name event (newDir = newName is passed without path!)
        $newName = rtrim($newDir, '/'); // for better readability and removes the trailing slash in the filename

        // We don't need to care about $parts['extension'], since you can't change the EXT by the JS file rename event
        $file_new = $file['path'] . $newName;
        $file_old = $file['path'] . $file['name'];
        // AVIF/WebP case
        $file_new_variation = $file['path'] . '.v/' . $newName;
        $file_old_variation = $file['path'] . '.v/' . $file['name'];

        // build full thumb file names
        $_file_newthumb = $file['path'] . $newName . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        $_file_oldthumb = $file['path'] . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . (empty($file['extension']) ? '' : '.' . $file['extension']);
        // WebP case
        $_file_newthumbWebp = $file['path'] . '.v/' . $newName . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.webp';
        $_file_oldthumbWebp = $file['path'] . '.v/' . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.webp';
        // AVIF case
        $_file_newthumbAVIF = $file['path'] . '.v/' . $newName . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.avif';
        $_file_oldthumbAVIF = $file['path'] . '.v/' . $file['name'] . (!empty($file['thumbnail_name']) ? '.' . $file['thumbnail_name'] : '') . '.avif';
        // file case
        $newThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_newthumb;
        $oldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_oldthumb;
        // WebP case
        $newThumbWebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_newthumbWebp;
        $oldThumbWebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_oldthumbWebp;
        // AVIF case
        $newThumbAVIF = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_newthumbAVIF;
        $oldThumbAVIF = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file_oldthumbAVIF;

    } else {
        #if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag RTRIM             newDir=$newDir"); }
        // case bulkmove event (newDir is passed inclusive the path! and normally w/o the filename, but we better check this though)
        $newDir = ($newDir == 'uploadRoot/') ? '' : $newDir; // Take care: remove temporary 'uploadRoot/' string, in case of moving a subdir file into "uploads/" root directory by bulkmove
        #if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED BULKMOVE newDir=$newDir"); }
        $_newDir = str_replace($file['name'] . (empty($file['extension']) ? '' : '.' . $file['extension']), '', $newDir);
        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED         _newDir=$_newDir"); }

        // We don't need to care about $parts['extension'], since you can't change the EXT via the bulkmove event
        $file_new = $_newDir . $file['name'];
        $file_old = $file['path'] . $file['name'];
        // AVIF/WebP case
        $file_new_variation = $_newDir . '.v/' . $file['name'];
        $file_old_variation = $file['path'] . '.v/' . $file['name'];
    }

    // build full origin and new file path names for both events
    $newfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file_new . (empty($file['extension']) ? '' : '.' . $file['extension']);
    $oldfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file_old . (empty($file['extension']) ? '' : '.' . $file['extension']);
    // AVIF/WebP case and the valid(!) paranoid case to remove and re-add the extension
    $file_new_variation = pathinfo($file_new_variation, PATHINFO_FILENAME);
    #$file_old_variation = pathinfo($file_old_variation, PATHINFO_FILENAME); // disabled, for the case an "example.png.jpg" file was uploaded and now renamed to "example.jpg"
    $file_rel_path = ($newDir == $file_new_variation) ? $file['path'] : $newDir; // distinguish between rename and re-move actions. covering newDir variable changes

    // check if the hidden dir path part is not already applied
    if (!preg_match('@.v/@', $file_new_variation)) {
        $file_new_variation = $file_rel_path . '.v/' . $file_new_variation;
    }
    if (!preg_match('@.v/@', $file_old_variation)) {
        $file_old_variation = $file_rel_path . '.v/' . $file_old_variation;
    }
    $relnewfilevariation = $file_new_variation;
    $reloldfilevariation = $file_old_variation;
    // WebP case
    $newfilewebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $relnewfilevariation . '.webp';
    $oldfilewebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $reloldfilevariation . '.webp';
    // AVIF case
    $newfileavif = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $relnewfilevariation . '.avif';
    $oldfileavif = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $reloldfilevariation . '.avif';

    if ($debug) {
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED oldfile=    $oldfile");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED newfile=    $newfile");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED oldfilewebp=$oldfilewebp");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED newfilewebp=$newfilewebp");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED oldfileavif=$oldfileavif");
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED newfileavif=$newfileavif");
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

        // we need to KEEP the old files thumbnail_name, for the case the global serendipity thumbSuffix has changed! A general conversion needs to be done somewhere else.
        $fileThumbSuffix = !empty($file['thumbnail_name']) ? $file['thumbnail_name'] : $serendipity['thumbSuffix'];

        $is_bulkmove = false;

        // Case re-name event, keeping a possible moved directory name for a single file
        if ($oldDir === null) {
            // Rename/Move the origin file
            @rename($oldfile, $newfile);
            // Rename/Move the .v/ stored WebP file - avoid to rename on object files, eg zip, pdf, etc., w/o real image with variations
            if (file_exists($oldfilewebp)) {
                @rename($oldfilewebp, $newfilewebp);
            }
            // Rename/Move the .v/ stored AVIF file - avoid to rename on object files, eg zip, pdf, etc., w/o real image with variations
            if (file_exists($oldfileavif)) {
                @rename($oldfileavif, $newfileavif);
            }
            // do not re-name again, if an item has no thumb name (eg. *.zip object file case) and old thumb eventually exists (possible missing PDF preview image on WinOS with IM)
            if (($newThumb != $newfile) && file_exists($oldThumb)) {
                // the thumb file
                @rename($oldThumb, $newThumb); // Keep both rename() errors disabled, since we have to avoid any output in renaming cases
                // WebP thumb case
                if (($newThumbWebp != $newfilewebp) && file_exists($oldThumbWebp)) {
                    @rename($oldThumbWebp, $newThumbWebp);
                }
                // AVIF thumb case
                if (($newThumbAVIF != $newfileavif) && file_exists($oldThumbAVIF)) {
                    @rename($oldThumbAVIF, $newThumbAVIF);
                }
            }

            // Hook into staticpage for the renaming regex replacements
            // YES. We simply just assume the origins paths are the relative variations paths w/o the hidden dir!
            $renameValues  = array(array(
                'haswebp'  => (file_exists($newfilewebp) || file_exists($newfileavif)),/* staticpage ported synonym for both expressions - change later */
                'hasVar'   => (file_exists($newfilewebp) || file_exists($newfileavif)),/* the new key name - sets 'haswebp' deprecated */
                'fromVar'  => $reloldfilevariation,
                'toVar'    => $relnewfilevariation,
                'from'     => str_replace('.v/', '', $reloldfilevariation),
                'to'       => str_replace('.v/', '', $relnewfilevariation),
                'thumb'    => $fileThumbSuffix,
                'fthumb'   => $file['thumbnail_name'],
                'oldDir'   => $oldDir,
                'newDir'   => $newDir,
                'type'     => $type,
                'item_id'  => $item_id,
                'file'     => $file,
                'debug'    => $debug,
                'dbginfo'  => 'CASE IS RENAME of [$oldfilewebp | $oldfileavif] _renameRealFileName:: ~5117 ++ ditto for avif'
            ));
            serendipity_plugin_api::hook_event('backend_media_rename', $renameValues); // rename real file name type 'file'

            // renaming filenames has to update mediaproperties, if set (.. although no DB needs for the WebP variation files!)
            serendipity_updateSingleMediaProperty(  $item_id,
                    array('property' => 'realname', 'property_group' => 'base_property', 'property_subgroup' => 'internal', 'value' => $file['realname']),
                    $newName . (empty($file['extension']) ? '' : '.' . $file['extension']));
            serendipity_updateSingleMediaProperty(  $item_id,
                    array('property' => 'name', 'property_group' => 'base_property', 'property_subgroup' => 'internal', 'value' => $file['name']),
                    $newName);
            serendipity_updateSingleMediaProperty(  $item_id,
                    array('property' => 'TITLE', 'property_group' => 'base_property', 'value' => $file['realname']),
                    $newName . (empty($file['extension']) ? '' : '.' . $file['extension'])); // TITLE is either '', 'ALL', or 'internal'
                    // And keep in mind that field names are case-insensitive.. but in this case there should be no confusion, since the only other value I found is: 'Title' with a subgroup 'XMP' is in group 'base_metadata'.

            serendipity_updateImageInDatabase(array('thumbnail_name' => $renameValues[0]['thumb'], 'realname' => $newName . (empty($file['extension']) ? '' : '.' . $file['extension']), 'name' => $newName), $item_id);

            // Forward user to overview (we don't want the user's back button to rename things again)
        }

        // Case Move or Bulkmove event
        // newDir can now be used for the "uploads/" directory root path too
        // Do not allow an empty string OR NOT set newDir for the build call so we do not conflict with rename calls, which are single files only and is done above
        // BULKMOVE vars oldfile and newfile are fullpath based w/o EXT, see above
        elseif (!empty($newfile)) {
            $serendipity['ml_type_file_is_bulkmove_event'] = $is_bulkmove = true;

            // Hook into staticpage for the renaming regex replacements and include some more since also use below for rename action
            // YES. We simply just assume the origins paths are the relative variations paths w/o the hidden dir!
            $renameValues = array(array(
                'fromVar'  => $reloldfilevariation,
                'toVar'    => $relnewfilevariation,
                'from'     => str_replace('.v/', '', $reloldfilevariation),
                'to'       => str_replace('.v/', '', $relnewfilevariation),
                'oldDir'   => $oldDir,
                'newDir'   => $newDir,
                'thumb'    => $fileThumbSuffix,
                'fthumb'   => $file['thumbnail_name'],
                'type'     => $type,
                'item_id'  => $item_id,
                'haswebp'  => (file_exists($oldfilewebp) || file_exists($oldfileavif)),/* ported synonym for both expressions */
                'hasVar'   => (file_exists($oldfilewebp) || file_exists($oldfileavif)),/* the new key name - sets 'haswebp' deprecated */
                'file'     => $file,
                'debug'    => $debug,
                'dbginfo'  => "CASE IS BULKMOVE of [$oldfilewebp | $oldfileavif] _renameRealFileName:: ~5117 ++ ditto for avif"
            ));
            serendipity_plugin_api::hook_event('backend_media_rename', $renameValues); // eg. for staticpage entries path regex replacements

            // Move the origin file
            try {
                rename($oldfile, $newfile);
            } catch (Throwable $t) {
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (2)</span>\n";
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
                    // The thumb file and catch any wrong renaming
                    try {
                        rename($thisOldThumb, $thisNewThumb);
                    } catch (Throwable $t) {
                        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (3)</span>\n";
                    }
                    //  check the origin filedir has moved in bulkmove
                    if ($is_bulkmove && file_exists($newfile)) {
                        // build variations path
                        // WebP origin variation case
                        $varFromWebPOrigin = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['fromVar'] . '.webp';
                        $varToWebPOrigin   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['toVar'] . '.webp';
                        // AVIF origin variation case
                        $varFromAvifOrigin = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['fromVar'] . '.avif';
                        $varToAvifOrigin   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['toVar'] . '.avif';


                        // Move a variation dir to a new location mkdir directory
                        serendipity_makeDirRename($varFromWebPOrigin, $varToWebPOrigin);
                        serendipity_makeDirRename($varFromAvifOrigin, $varToAvifOrigin);

                        if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag BULKMOVE VARIATION ORIGIN $varFromWebPOrigin => $varToWebPOrigin"); }
                        // WebP thumb variation case
                        $varFromWebPThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['fromVar'] . (!empty($file['thumbnail_name']) ? '.' . $renameData['thumb'] : '.' . $serendipity['thumbSuffix']) . '.webp';
                        $varToWebPThumb   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['toVar'] . (!empty($file['thumbnail_name']) ? '.' . $renameData['thumb'] : '.' . $serendipity['thumbSuffix']) . '.webp';
                        // AVIF thumb variation case
                        $varFromAvifThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['fromVar'] . (!empty($file['thumbnail_name']) ? '.' . $renameData['thumb'] : '.' . $serendipity['thumbSuffix']) . '.avif';
                        $varToAvifThumb   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $renameData['toVar'] . (!empty($file['thumbnail_name']) ? '.' . $renameData['thumb'] : '.' . $serendipity['thumbSuffix']) . '.avif';

                        // Move a variation dir to a new location mkdir directory - WebP thumbs
                        serendipity_makeDirRename($varFromWebPThumb, $varToWebPThumb);
                        // Move a variation dir to a new location mkdir directory - AVIF thumbs
                        serendipity_makeDirRename($varFromAvifThumb, $varToAvifThumb);

                        if ($debug) {
                            $serendipity['logger']->debug("L_".__LINE__.":: $logtag BULKMOVE VARIATION THUMB  $varFromWebPThumb => $varToWebPThumb");
                            $serendipity['logger']->debug("L_".__LINE__.":: $logtag BULKMOVE VARIATION THUMB  $varFromAvifThumb => $varToAvifThumb");
                            $serendipity['logger']->debug(" - - - "); // spacer
                        }
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
 * Used solely by serendipity_parsePropertyForm() base_properties, when changing the file selected path via the mediaproperties form.
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
        echo 'P3: Please use the API workflow via serendipity_moveMediaDirectory()!';
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
    // WebP case
    $oldfilewebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . '.v/' . $_file['name'] . '.webp';
    $newfilewebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . '.v/' . $_file['name'] . '.webp';
    // AVIF case
    $oldfileavif = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . '.v/' . $_file['name'] . '.avif';
    $newfileavif = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . '.v/' . $_file['name'] . '.avif';

    // we need to KEEP the old files thumbnail_name (for the staticpage hook only in this case), for the case the global serendipity thumbSuffix has changed! A general conversion need to be done somewhere else.
    $fileThumbSuffix = !empty($_file['thumbnail_name']) ? $_file['thumbnail_name'] : $serendipity['thumbSuffix'];

    // hook into staticpage for the renaming regex replacements
    $renameValues = array(array(
        'from'    => $oldfile,
        'to'      => $newfile,
        'thumb'   => $fileThumbSuffix,
        'fthumb'  => $_file['thumbnail_name'],
        'haswebp' => (file_exists($oldfilewebp) || file_exists($oldfileavif)),/* ported synonym for both expressions */
        'hasVar'  => (file_exists($oldfilewebp) || file_exists($oldfileavif)),/* the new key name - sets 'haswebp' deprecated */
        'oldDir'  => $oldDir,
        'newDir'  => $newDir,
        'type'    => $type,
        'item_id' => $item_id,
        'file'    => $_file,
        'name'    => $_file['name'],
        'debug'   => $debug,
        'dbginfo' => 'CASE RENAME DIR:: '.__LINE__
    ));
    serendipity_plugin_api::hook_event('backend_media_rename', $renameValues); // this is via media properties moving image (path)

    $reserr = false;
    // Move the origin file
    try {
        serendipity_makeDirRename($oldfile, $newfile);
    } catch (Throwable $t) {
        echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (5)</span>\n";
        $reserr = true;
    }
    // AVIF/WebP case
    if (!$reserr) {
        serendipity_makeDirRename($oldfilewebp, $newfilewebp);
        serendipity_makeDirRename($oldfileavif, $newfileavif);
    }

    foreach($renameValues AS $renameData) {
        $reset = false;
        $thisOldThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . $_file['name'] . (!empty($renameData['fthumb']) ? '.' . $renameData['fthumb'] : '') . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
        $thisNewThumb = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . $_file['name'] . (!empty($_file['thumbnail_name']) ? '.' . $_file['thumbnail_name'] : '') . (empty($_file['extension']) ? '' : '.' . $_file['extension']);
        // WebP thumb case
        $thisOldThumbWebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . '.v/' . $_file['name'] . (!empty($renameData['fthumb']) ? '.' . $renameData['fthumb'] : '') . '.webp';;
        $thisNewThumbWebp = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . '.v/' . $_file['name'] . (!empty($_file['thumbnail_name']) ? '.' . $_file['thumbnail_name'] : '') . '.webp';;
        // AVIF thumb case
        $thisOldThumbAVIF = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir . '.v/' . $_file['name'] . (!empty($renameData['fthumb']) ? '.' . $renameData['fthumb'] : '') . '.avif';;
        $thisNewThumbAVIF = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir . '.v/' . $_file['name'] . (!empty($_file['thumbnail_name']) ? '.' . $_file['thumbnail_name'] : '') . '.avif';;

        // Check for existent old thumb files first, to not need to disable rename by @rename(), then move the thumb file and catch any wrong renaming
        if (($thisNewThumb != $newfile) && file_exists($thisOldThumb)) {
            // Move the thumb file and catch any wrong renaming
            try {
                serendipity_makeDirRename($thisOldThumb, $thisNewThumb);
            } catch (Throwable $t) {
                // Reset already updated image table
                serendipity_updateImageInDatabase(array('path' => $oldDir), $item_id);
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . ': '.$t->getMessage() . " (6)</span>\n";
                $reset = true;
            }
            if (!$reset) {
                serendipity_makeDirRename($thisOldThumbWebp, $thisNewThumbWebp);
                serendipity_makeDirRename($thisOldThumbAVIF, $thisNewThumbAVIF);
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
 * FORMAT a real media file to a convenient supported format and pass changes to related entries
 * NOTE: We use the $oldDir / $newDir notation here to keep some sort of usage consistency and since the dir / file distinction does not exist on Unix..
 *
 * @access public
 * @param   string  Old relative directory/file name
 * @param   string  New relative directory/file name
 * @param   string  The new extension format name
 * @param   string  An item ID of the current file
 * @param   array   Result of serendipity_fetchImageFromDatabase($id) for the current file properties
 * @return  boolean
 */
function serendipity_formatRealFile($oldDir, $newDir, $format, $item_id, $file) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $logtag = 'formatRealFile::';
        $serendipity['logger']->debug("IN serendipity_formatRealFile");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && $trace[1]['function'] != 'serendipity_convertImageFormat') {
        echo 'Please use the API workflow via serendipity_convertImageFormat()!';
        return false;
    }

    // format: "origin", "thumb" names, relative to Serendipity "uploads/" root path, eg. "a/b/c/"
    // We don't care about variations since they are done once and have no other relation to its parents than a small sized footprint - doing that again ist too much of a hassle

    $infile  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDir;
    $outfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDir;
    $infileThumb   = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . '.' . $file['thumbnail_name'] . '.' . $file['extension'];
    $outfileThumb  = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . '.' . $file['thumbnail_name'] . '.' . $format;
    $outfileRealName = str_replace($file['extension'], $format, $file['realname']);

    if (!file_exists($outfile)) {
        // pass to GD
        if ($serendipity['magick'] !== true) {
            $out  = serendipity_formatImageGD($infile, $outfile, $format);
            $call = 'serendipity_formatImageGD()';
            if (is_array($out)) {
                $result  = array(0, $out, 'with GD');
                if ($debug) { $serendipity['logger']->debug("ML_NEWORIGINFORMAT: New GD Image '${format}' format creation: \"${result[2]}\""); }
            }
        }
        // pass to IM
        else {
            $_format = "format-$format";
            $pass    = [ $serendipity['convert'], [], [], [], 100, -1 ]; // Best result format conversion settings with ImageMagick CLI convert is empty/nothing, which is some kind of auto true! Do not handle with lossless!!
            $result  = serendipity_passToCMD($_format, $infile, $outfile, $pass);
            $call    = 'serendipity_passToCMD()';
            if ($debug) { $serendipity['logger']->debug("ML_NEWORIGINFORMAT: ImageMagick CLI - New Image '${format}' format creation: '${result[2]}'"); }
        }

        if (!is_array($result) || $result[0] != 0) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(IMAGICK_EXEC_ERROR, $call, "Creating ${outfile} image", 'failed') ."</span>\n";
        }

        // GD
        if (is_array($result) && $result[0] == 0 && $serendipity['magick'] !== true) {
            if ($debug) { $serendipity['logger']->debug("ML_NEWORIGINFORMAT: New Image '${format}' format creation success '${result[2]}' " . DONE); }
            unset($result);
            unset($out);
            unlink($infile); // delete the old origin format
            // 2cd run: The thumb conversion to new format
            $out = serendipity_formatImageGD($infileThumb, $outfileThumb, $format);
            if (is_array($out)) {
                $result  = array(0, $out, 'with GD');
            }
            if (is_array($result) && $result[0] == 0) {
                if ($debug) { $serendipity['logger']->debug("ML_NEWTHUMBFORMAT: New Image '${format}' format success '${result[2]}' " . DONE); }
                unlink($infileThumb); // delete the old thumb format
            }
            unset($result);
            $uID = serendipity_updateImageInDatabase(array('extension' => $format, 'mime' => serendipity_guessMime($format), 'size' => (int)@filesize($outfile), 'date' => (int)@filemtime($outfile), 'realname' => $outfileRealName), $item_id);
        }
        // IM
        else if (is_array($result) && $result[0] == 0) {
            if ($debug) { $serendipity['logger']->debug("ML_NEWORIGINFORMAT: ImageMagick CLI - New Image '${format}' format creation success '${result[2]}' " . DONE); }
            unset($result);
            unlink($infile); // delete the old origin format
            // 2cd run: The thumb conversion to new format
            $result  = serendipity_passToCMD($_format, $infileThumb, $outfileThumb, $pass);
            if (is_array($result) && $result[0] == 0) {
                if ($debug) { $serendipity['logger']->debug("ML_NEWTHUMBFORMAT: ImageMagick CLI - New Image '${format}' format THUMB RESIZE success '${result[2]}' " . DONE); }
                unlink($infileThumb); // delete the old thumb format
            } else {
                if ($debug) { $serendipity['logger']->debug("ML_NEWTHUMBFORMAT: ImageMagick CLI - New Image '${format}' format RESIZE failed! Perhaps a wrong path: \"${outfileThumb}\" ?"); }
            }
            unset($result);
            $uID = serendipity_updateImageInDatabase(array('extension' => $format, 'mime' => serendipity_guessMime($format), 'size' => (int)@filesize($outfile), 'date' => (int)@filemtime($outfile), 'realname' => $outfileRealName), $item_id);
        }
        // FAILED
        else {
            if ($debug) { $serendipity['logger']->debug("ML_NEWORIGINFORMAT: New Image '${format}' format creation failed OR already exists."); }
        }

        // new formatRealFile:: WHAT have we done so far?
        /*
            1. Change the origin file format
            2. Change the thumb file format
            3. Delete both old origin files.
                    We did not touch WebP-Variation files since they have their own format, and which do not really care about changed parents!
                    The only thing we could outreach here, is a file size change, but since format changes have probably a LOSS (at least for the first time,
                    likewise jpg -> png) this should not be carried to the variations!
            4. Update in images database
        */

        // WHAT do we need to do is :
        /*
            1. pass this change to staticpage
            2. change mediaProperties database
            3. change file in entries via serendipity_moveMediaInEntriesDB()
            4. change file in ep cached entries via serendipity_moveMediaInEntriesDB()
        */

        // check if serendipity_updateImageInDatabase() has run with success
        if (isset($uID) && $uID > 0) {

            // Hook into STATICPAGE for the FORMAT renaming regex replacements, like samples in serendipity_renameRealFileName(), serendipity_renameRealFileDir()
            $renameValues   = array(array(
                'from'      => $oldDir,
                'to'        => $newDir,
                'fromThumb' => str_replace($serendipity['serendipityPath'] . $serendipity['uploadPath'], '', $infileThumb),
                'toThumb'   => str_replace($serendipity['serendipityPath'] . $serendipity['uploadPath'], '', $outfileThumb),
                'haswebp'   => false,/* ported synonym for both expressions */
                'hasVar'    => false,/* the new key name - sets 'haswebp' deprecated */
                'chgformat' => true,
                'oldDir'    => $oldDir,
                'newDir'    => $newDir,
                'format'    => $format,
                'type'      => 'filedir',
                'item_id'   => $item_id,
                'file'      => $file,
                'debug'     => $debug,
                'dbginfo'   => $trace[0]['function'] . ': Port format values to staticpage changes ~5521++.'
            ));/* Does not matter if filedir or file type case is used! */
            serendipity_plugin_api::hook_event('backend_media_rename', $renameValues);

            // renaming filenames has to update mediaproperties, if set
            serendipity_updateSingleMediaProperty(  $item_id,
                    array('property' => 'realname', 'property_group' => 'base_property', 'property_subgroup' => 'internal', 'value' => $file['realname']),
                    $file['realname'] . '.' . $format);
            serendipity_updateSingleMediaProperty(  $item_id,
                    array('property' => 'TITLE', 'property_group' => 'base_property', 'value' => $file['realname']),
                    $file['realname'] . '.' . $format); // TITLE is either '', 'ALL', or 'internal'
                    // And keep in mind that field names are case-insensitive.. but in this case there should be no confusion, since the only other value I found is: 'Title' with a subgroup 'XMP' is in group 'base_metadata'.

            $file['newformat'] = $format;
            // replace newfilename occurrences in entries oldDir is build inside that function
            if (false === serendipity_moveMediaInEntriesDB(null, $newDir, 'file', $file, null, $debug)) {
                return false;
            }
        }

    } else {
        if (file_exists($outfile)) {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_EXISTS . "</span>\n";
        } else {
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . "</span>\n";
        }
        return false;
    }
    return true;

}

/**
 * RENAME a MEDIA dir or filename in existing entries
 * @see SPLIT serendipity_moveMediaDirectory() part 4
 * @see Special case from outside via serendipity_convertImageFormat() -> serendipity_formatRealFile()
 *
 * @access public
 * @param   string  Old directory name or empty
 * @param   string  New directory name with a trailing slash or empty
 * @param   string  The type of what to remove (dir|file|filedir)
 * @param   array   Result of origin (old) serendipity_fetchImageFromDatabase($id)
 * @param   array   Properties result of new updated query,
 *                      @see serendipity_renameRealFileDir() and serendipity_moveMediaDirectory()
 * @param   boolean Ad hoc debugging, set in wrapper serendipity_moveMediaDirectory()
 * @return
 */
function serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $file, $pick=null, $debug=false) {
    global $serendipity;

    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
    if ($debug) {
        $logtag = 'moveMediaInEntriesDB::';
        $serendipity['logger']->debug("IN serendipity_moveMediaInEntriesDB");
        $serendipity['logger']->debug("TRACE: " . print_r($trace,1));
    }
    if (is_array($trace) && !in_array($trace[1]['function'], ['serendipity_moveMediaDirectory', 'serendipity_formatRealFile'])) {
        echo 'P4: Please use the API workflow via serendipity_moveMediaDirectory()!';
        return false;
    }

    // type file path param by rename is the filename w/o EXT only
    // type file path param by bulkmove is the relative dir/+filename w/o EXT
    // type filedir path param via media mediaproperties form as a relative dir/
    // type dir path param by DirectoryEdit is a relative dir/

    // get the correct $file properties, which is either array or null by type, and are the origin or already updated properties (which then is $pick in case of filedir typed directory renaming actions)
    $_file = ($type == 'filedir' && $pick !== null) ? $pick : $file;

    if ($debug) {
        $which = $type == 'filedir' ? 'NEW (\'filedir\')' : 'OLD (\'file\')';
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag TRANSPORTED $which type _file " . print_r($_file, 1));
    }

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

        $ispOldFile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDirFile;
        if ($serendipity['dbType'] == 'mysqli') {
            $joinThumbs = "|" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb);
        } else {
            // works w/ or w/o the braces! (see follow-up sql queries)
            $entry_joinThumbs = " OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%') OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%')";
            $stapa_joinThumbs = " OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%') OR (content || pre_content LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirThumb) . "%')";
        }

    } elseif ($type == 'dir') {
        // since this is case 'dir', we do not have a filename and have to rename replacement File vars to oldDir and newDir values for the update preg_replace match
        $oldDirFile = $oldDir;
        $ispOldFile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $oldDirFile . (($_file['extension']) ? '.'.$_file['extension'] : '');
        $joinThumbs = ''; // we don't need to join Thumbs in special, since this is the 'dir' type case only! (Fixes matching and the counter!)
    }

    // Please note: IMAGESELECTORPLUS plugin quickblog option is either quickblog:FullPath or quickblog:|?(none|plugin|js|_blank)|FullPath
    // SELECTing the entries uses a more detailed approach to be as precise as possible, thus we need to reset these vars for the preg_replace later on in some cases
    // We do not need to extra SELECT check for image variations sine they do exist only if the normal image strings exist
    if ($serendipity['dbType'] == 'mysqli') {
        $q = "SELECT id, body, extended
                FROM {$serendipity['dbPrefix']}entries
               WHERE body     REGEXP '(src=|href=|data-fallback=|window.open.|<!--quickblog:)(\'|\"|\\\|?(plugin|none|js|_blank)?\\\|?)(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . "|" . serendipity_db_escape_String($ispOldFile) . ")'
                  OR extended REGEXP '(src=|href=|data-fallback=|window.open.)(\'|\")(" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "|" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . $joinThumbs . ")'";
    } else {
        $q = "SELECT id, body, extended
                FROM {$serendipity['dbPrefix']}entries
               WHERE body LIKE '%<!--quickblog:%" . serendipity_db_escape_String($ispOldFile) . "-->%'
                  OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')
                  OR (body || extended LIKE '%" . serendipity_db_escape_String($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFile) . "%')" . $entry_joinThumbs . "";
    }
    $entries = serendipity_db_query($q, false, 'assoc');

    if ($debug) {
        $serendipity['logger']->debug("L_".__LINE__.":: $logtag ENTRIES SELECT SQL: \n              $q");
        $serendipity['logger']->debug(" - - - "); // spacer
        $did = array(); // init for NULL cases
        if (is_array($entries)) {
            foreach($entries AS $d) { $did[] = $d['id']; }
            reset($entries);
        }
        if (is_string($entries) && !empty($entries)) {
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag DB ERROR! Entries serendipity_db_query returned: $entries");
        }
        if (!empty($did)) {
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag FOUND Entry ID: " . implode(', ', $did));
            $serendipity['logger']->debug(" - - - "); // spacer
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag CHANGE IMAGESELECTORPLUS ispOldFile=$ispOldFile");
        } else {
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag Found NO ENTRIES to change");
            $serendipity['logger']->debug(" - - - "); // spacer
        }
    }

    // prepare preg/replace variables for entry related cases
    if (is_array($entries) && !empty($entries)) {

         // Take care and remove temporary 'uploadRoot/' string, in case of moving a subdir file into "uploads/" root directory by bulkmove
        $newDir = ($newDir == 'uploadRoot/') ? '' : $newDir;

        // Prepare the REPLACE $newDirFile string for filetypes
        if ($type == 'filedir' || $type == 'file') {
            // newDir + file name in case it is a 'filedir' path OR a file called by the Bulk Move, BUT NOT by rename
            if ($type == 'filedir' || ($type == 'file' && $oldDir !== null)) {
                $newDirFile = (false === strpos($newDir, $_file['name'])) ? $newDir . $_file['name'] : $newDir;
            }
            #if (isset($newDirFile)) $newDirFile = ($newDirFile == 'uploadRoot/'.$_file['name']) ? str_replace('uploadRoot/', '', $newDirFile) : $newDirFile; //@see better $newDir fix above
            if ($type == 'file' && $oldDir === null) {
                $newDirFile = $newDir;
            }
        } elseif ($type == 'dir') {
            $newDirFile = $newDir;
        } else {
            // paranoid fallback case
            $newDirFile = rtrim($newDir, '/');
        }
        if ($debug) {
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED IMAGESELECTORPLUS newDir=$newDir");
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag PREPARED ISP/+ENTRIES newDirFile= $newDirFile");
        }

        // here we need to match THUMBS too, so we do NOT want the extension, see detailed SELECT regex note
        if ($type == 'file' && $oldDir === null) {
            $_ispOldFile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file['path'] . $_file['name'] . (empty($_file['extension']) ? '' : '.' . $_file['extension']); // this is more exact in every case [YES!]
            // special case format change
            if ($trace[1]['function'] == 'serendipity_formatRealFile' && isset($_file['newformat'])) {
            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag FORMAT CHANGE by {$trace[1]['function']}: Set _file extension var to NIL for newformat= {$_file['newformat']}"); }
                $_temp = $_file['extension'];
                $_file['extension'] = null;
                // Normally we don't have to care about THUMBs since we don't care about EXTensions.
                // The special new FORMAT case constrains us to care about the complete thumb url string for the preg_replace. (Relative to uploads/, full in preg_replace.)
                $format_oldthumbnail = $_file['path'] . $_file['name'] . '.' . $_file['thumbnail_name'] . '.' . $_temp;
                $format_newthumbnail = $_file['path'] . $_file['name'] . '.' . $_file['thumbnail_name'] . '.' . $_file['newformat'];
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag FORMAT REPLACE THUMBNAIL build: format_oldthumbnail=$format_oldthumbnail to format_newthumbnail=$format_newthumbnail"); }
            }
            $_ispNewFile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $_file['path'] . $newDirFile . ($_file['extension'] ? '.' . $_file['extension'] : '');
            // [non-format] rename action
            if (!isset($_temp) && !isset($_file['newformat'])) {
                $newDirFile = $_file['path'] . $newDirFile; // newDirFile is missing a possible subdir path for the preg_replace (w/o EXT!)
            }
            if ($debug) {
                $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE IMAGESELECTORPLUS[type=$type] _ispNewFile=$_ispNewFile");
                $serendipity['logger']->debug(" - - - "); // spacer
            }
        } else {
            $_ispOldFile = $ispOldFile;
            // special case format change
            if ($trace[1]['function'] == 'serendipity_formatRealFile' && isset($_file['newformat'])) {
                $_temp = $_file['extension'];
                $_file['extension'] = null;
            }
            $_ispNewFile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $newDirFile . ($_file['extension'] ? '.' . $_file['extension'] : '');
            if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE IMAGESELECTORPLUS[type=$type(2)] _ispNewFile=$_ispNewFile"); }
        }

        if (isset($_temp) && isset($_file['newformat'])) {
            // port the new format into the file extension
            $_file['extension'] = $_file['newformat']; // also resets the null cases which were only applied for the $_isp... builds above
        }

        // LAST paranoid checks - WHILE FIXING WRONG ENTRIES LATER ON IS A HELL! :)
        // Get to know the length of file EXT
        $lex = strlen($_file['extension']);
        //  to check the oldDirFile string backwards with a flexible substr offset ending with a "dot extension"
        if ($type == 'file') {
            $_oldDirFile = ('.'.substr($oldDirFile, -$lex) != '.'.$_file['extension']) ? $oldDirFile : $_file['path'] . $_file['name'];
            $_oldDirFileVariation = $_file['path'] . '.v/' . $_file['name'];
            // DISTINGUISH if it is a single type 'file' case rename OR a type 'file' case re-move (which is more like a 'filedir' type case, isn't it?!)
            if (empty($serendipity['ml_type_file_is_bulkmove_event']) && !isset($file['newformat'])) {
                $_newDirFileVariation = $_file['path'] . '.v/' . $newDir; // YES, newDir is the new file name for the type 'file' case for rename! IS NOT in case bulkmove!!
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag RENAME case (1) RENAME VS BULKMOVE: newDir=$newDir is the new variation filename"); }
            } else if (!empty($serendipity['ml_type_file_is_bulkmove_event'])) {
                $_newDirFileVariation = $newDir . '.v/' . $_file['name']; // YES, this is a type 'file' case for re-move and so is newDir the new relative location directory path, while filename is not changed.
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag RE-MOVE case (2) BULKMOVE VS RENAME: newDir=$newDir is the new variation directory location == ${newDir}.v/${_file['name']}"); }
                unset($serendipity['ml_type_file_is_bulkmove_event']);
            } else if (empty($serendipity['ml_type_file_is_bulkmove_event']) && isset($file['newformat'])) {
                $_newDirFileVariation = $_file['path'] . '.v/' . $_file['name']; // Actually there is no need to set this variable, since not used when a format change applies! (Just done to clear things up!)
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag Format case (3): _newDirFileVariation=${_file['path']}.v/${_file['name']} w/o real file application!"); }
            } else {
                // unknown fallback cse
                echo '<span class="msg_error"><span class="icon-info-attention" aria-hidden="true"></span> Building _newDirFileVariation variable for Bulkmove vs Rename mismatch failed.</span>'."\n";
                if (isset($serendipity['ml_type_file_is_bulkmove_event'])) unset($serendipity['ml_type_file_is_bulkmove_event']);
                return false;
            }
        } else { // cases 'filedir' and 'dir'
            $_oldDirFile = (FALSE !== strrpos($oldDirFile, '.'.$_file['extension'], -($lex+1))) ? str_replace('.'.$_file['extension'], '', $oldDirFile) : $oldDirFile;
            if ($type == 'dir') {
                $_oldDirFileVariation = $_oldDirFile . '.v/' . $_file['name'];
            } else {
                $list_oldDirFile = pathinfo($_oldDirFile); // since old $file array may be empty
                $_oldDirFileVariation = ($list_oldDirFile['dirname'] != '.' ? $list_oldDirFile['dirname'] . '/.v/' : '.v/') . $list_oldDirFile['basename']; // checks relative path parts
            }
            $_newDirFileVariation = $newDir . '.v/' . $_file['name'];
        }
        if (!isset($file['newformat'])) {
            // Prepare variations for replace w/o thumb and extension to match all possible occurrences
            $oldDirFileVariation = $_oldDirFileVariation;
            $newDirFileVariation = $_newDirFileVariation;
        }

        if ($debug) {
            $serendipity['logger']->debug(" - - - "); // spacer
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE ACTION type=$type");
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE IMAGESELECTORPLUS _ispOldFile=$_ispOldFile to _ispNewFile=$_ispNewFile");
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE ENTRIES  _oldDirFile=    $_oldDirFile");
            $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE ENTRIES   newDirFile=    $newDirFile");
            if (!empty($newDirFileVariation)) {
                $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE VARIATION oldDirFileVariation=$oldDirFileVariation");
                $serendipity['logger']->debug("L_".__LINE__.":: $logtag REPLACE VARIATION newDirFileVariation=$newDirFileVariation");
            }
            $serendipity['logger']->debug(" - - - "); // spacer
        }

        if (isset($_temp) && isset($_file['newformat'])) {
            if ($_temp != $_file['extension']) {
                // port the old extension back for the special cased media object links
                $_file['extension'] = $_temp;
                unset($_temp);
            }
        }

        // Check for special cased media object links
        $oldLink = $_file['name'] . '.' . $_file['extension']; // basename of oldlink, including EXTension
        $newLink = str_replace($_oldDirFile, $newDirFile, $oldLink);
        $newLinkHTTPPath = $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_file['path'] . $newLink;
        $link_pattern = '<a class="block_level opens_window" href="' . $newLinkHTTPPath . '" title="' . $oldLink . '"><!-- s9ymdb:' . $_file['id'] . ' -->' . $oldLink . '</a>';
        $link_replace = '<a class="block_level opens_window" href="' . $newLinkHTTPPath . '" title="' . $newLink . '"><!-- s9ymdb:' . $_file['id'] . ' -->' . $newLink . '</a>';

        if (is_array($entries) && !empty($entries)) {

            // What we really need here, is oldDirFile w/o EXT to newDirFile w/o EXT, while in need to match the media FILE and the media THUMB,
            // match the special cased new Format (thumbnail) case
            // and the full ispOldFile path to the full ispNewFile path for IMAGESELECTORPLUS inserts.
            foreach($entries AS $entry) {
                $id = serendipity_db_escape_string($entry['id']);
                // body including ISP
                $entry['body']     = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $entry['body']);
                if (!isset($file['newformat'])) {
                    $entry['body'] = preg_replace('@(srcset=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFileVariation, $entry['body']);
                } else if (isset($format_oldthumbnail) && isset($format_newthumbnail)) {
                    $entry['body'] = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_newthumbnail, $entry['body']);
                }
                $entry['body']     = preg_replace('@(<!--quickblog:)(\\|?(plugin|none|js|_blank)?\\|?)(' . preg_quote($_ispOldFile) . ')@', '\1\2' . $_ispNewFile, $entry['body']);
                // extended
                $entry['extended']     = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $entry['extended']);
                if (!isset($file['newformat'])) {
                    $entry['extended'] = preg_replace('@(srcset=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFileVariation, $entry['extended']);
                } else if (isset($format_oldthumbnail) && isset($format_newthumbnail)) {
                    $entry['extended'] = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_newthumbnail, $entry['extended']);
                }
                // run for possible alt and title attributes
                $entry['body']     = preg_replace('@(alt=|title=)(\'|")(' . preg_quote(basename($_oldDirFile)) . ')@', '\1\2' . basename($newDirFile), $entry['body']);
                $entry['extended'] = preg_replace('@(alt=|title=|)(\'|")(' . preg_quote(basename($_oldDirFile)) . ')@', '\1\2' . basename($newDirFile), $entry['extended']);
                if ($debug) { $serendipity['logger']->debug("L_".__LINE__.":: $logtag (title|alt)" . basename($_oldDirFile) . ' ' . basename($newDirFile)); }
                // media objects
                $entry['body']     = str_replace($link_pattern, $link_replace, $entry['body']);
                $entry['extended'] = str_replace($link_pattern, $link_replace, $entry['extended']);

                $uq = "UPDATE {$serendipity['dbPrefix']}entries
                          SET body = '" . serendipity_db_escape_string($entry['body']) . "' ,
                          extended = '" . serendipity_db_escape_string($entry['extended']) . "'
                        WHERE   id = $id";
                if ($debug) {
                    #$serendipity['logger']->debug("L_".__LINE__.":: $logtag The NEW regexed ENTRIES entry BODY::ID:$id\n{$entry['body']}");
                    #$serendipity['logger']->debug("L_".__LINE__.":: $logtag The NEW regexed ENTRIES entry EXTENDED::ID:$id\n{$entry['extended']}");
                }
                serendipity_db_query($uq);

                // SAME FOR ENTRIES ENTRYPROPERTIES CACHE for ep_cache_body (we do not need to care about SELECTing ISP items, since that is already a result of $entries array)
                $epq1 = "SELECT entryid, value
                           FROM {$serendipity['dbPrefix']}entryproperties
                          WHERE entryid = $id AND property = 'ep_cache_body'";
                $eps1 = serendipity_db_query($epq1, false, 'assoc');
                if (is_array($eps1)) {
                    $eps1['value'] = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $eps1['value']);
                    if (!isset($file['newformat'])) {
                        $eps1['value'] = preg_replace('@(srcset=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFileVariation, $eps1['value']);
                    } else if (isset($format_oldthumbnail) && isset($format_newthumbnail)) {
                        $eps1['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_newthumbnail, $eps1['value']);
                    }
                    // run for possible imageselectorplus quickblock
                    $eps1['value'] = preg_replace('@(<!--quickblog:)(\\|?(plugin|none|js|_blank)?\\|?)(' . preg_quote($_ispOldFile) . ')@', '\1\2' . $_ispNewFile, $eps1['value']);
                    // run for possible alt and title attributes
                    $eps1['value'] = preg_replace('@(alt=|title=)(\'|")(' . preg_quote(basename($_ispOldFile)) . ')@', '\1\2' . basename($_ispNewFile), $eps1['value']);
                    // run for possible media objects
                    $eps1['value'] = str_replace($link_pattern, $link_replace, $eps1['value']);

                    $uepq1 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                 SET value = '" . serendipity_db_escape_string($eps1['value']) . "'
                               WHERE entryid =  " . serendipity_db_escape_string($eps1['entryid']) . "
                                 AND property = 'ep_cache_body'";
                    if ($debug) {
                        #$serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_body):ID:$id\n$epq1");
                        #$serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT-UPDATE entryproperties DB: ENTRY_ID:{$eps1['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_body) SUB-UPDATE " .DONE);
                    }
                    serendipity_db_query($uepq1);

                } // no need for else thrown mysql/i error message
                // SAME FOR ENTRIES ENTRYPROPERTIES CACHE for ep_cache_extended w/o ISP
                $epq2 = "SELECT entryid, value
                           FROM {$serendipity['dbPrefix']}entryproperties
                          WHERE entryid = $id AND property = 'ep_cache_extended'";
                $eps2 = serendipity_db_query($epq2, false, 'assoc');
                if (is_array($eps2)) {
                    $eps2['value'] = preg_replace('@(src=|href=|data-fallback=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $_oldDirFile) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFile, $eps2['value']);
                    if (!isset($file['newformat'])) {
                        $eps2['value'] = preg_replace('@(srcset=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $oldDirFileVariation) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $newDirFileVariation, $eps2['value']);
                    } else if (isset($format_oldthumbnail) && isset($format_newthumbnail)) {
                        $eps2['value'] = preg_replace('@(src=|href=|window.open.)(\'|")(' . preg_quote($serendipity['baseURL'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . '|' . preg_quote($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_oldthumbnail) . ')@', '\1\2' . $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'] . $format_newthumbnail, $eps2['value']);
                    }
                    // run for possible alt and title attributes
                    $eps2['value'] = preg_replace('@(alt=|title=)(\'|")(' . preg_quote(basename($_oldDirFile)) . ')@', '\1\2' . basename($newDirFile), $eps2['value']);
                    // run for possible media objects
                    $eps2['value'] = str_replace($link_pattern, $link_replace, $eps2['value']);

                    $uepq2 = "UPDATE {$serendipity['dbPrefix']}entryproperties
                                 SET value = '" . serendipity_db_escape_string($eps2['value']) . "'
                               WHERE entryid =  " . serendipity_db_escape_string($eps2['entryid']) . "
                               AND property = 'ep_cache_extended'";
                    if ($debug) {
                        #$serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT entryproperties DB::ep::value(ep_cache_extended):ID:$id\n$epq2");
                        #$serendipity['logger']->debug("L_".__LINE__.":: $logtag SUB-SELECT-UPDATE entryproperties DB: ENTRY_ID:{$eps2['entryid']} {$serendipity['dbPrefix']}entryproperties::value(ep_cache_extended) SUB-UPDATE " .DONE);
                    }
                    serendipity_db_query($uepq2);

                } // no need for else thrown mysql/i error message
            }

        } // if (is_array($entries) && !empty($entries)) end

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
        }
    } // entries end
    else {
        if ($serendipity['dbType'] == 'mysqli' && $serendipity['production'] && is_string($entries)) {
            // NOTE: keep "error" somewhere in echoed string since that is the matching JS condition
            echo '<span class="msg_error"><span class="icon-info-attention" aria-hidden="true"></span> DB ERROR: ' . @$entries . "</span>\n";
        }
    }
}

/**
 * Moves/handles a media directory and / or file. A wrapper for
 *
 * 1. case type 'dir' via 'directoryEdit':
 *              serendipity_renameDirAccess($oldDir, $newDir)
 * 2. case type 'file' as a single file ID via (looped bulkmove) 'multicheck':
 *                     as a single file ID via 'rename':
 *              serendipity_renameRealFileName($oldDir, $newDir, $type, $item_id, $file)
 * 3. case type 'filedir' via this API serendipity_parsePropertyForm() as base_properties only, when changing the file selected path within mediaproperties form:
 *              serendipity_renameRealFileDir($oldDir, $newDir, $type, $item_id)
 *
 * and LASTLY to update the entries in the database
 *              serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $file, $pick)
 *
 * @param  string   The old directory / file.
 *                  This can be NULL or (an empty / a) STRING for re-name/multiCheck move comparison events
 * @param  string   The new directory / file
 * @param  string   The type of what to remove/handle (dir|file|filedir)
 * @param  string   An item ID of a file
 * @param  array    Result of serendipity_fetchImageFromDatabase($id)
 * @return boolean
 */
function serendipity_moveMediaDirectory($oldDir, $newDir, $type = 'dir', $item_id = null, $file = null) {
    global $serendipity;
    static $debug = false; // ad hoc, case-by-case debugging
    $pick = null;

    // Since being a wrapper function, this enables logging of all sub functions
    $debug = (is_object($serendipity['logger']) && $debug); // ad hoc debug + enabled logger

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

    // [Manage directories] case 'dir' via images.inc case 'directoryEdit', which is ML Directories form, via ML case 'directorySelect'
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
            // check return! A single 'file' rename is oldDir === null
            if (false === serendipity_renameRealFileName($oldDir, $newDir, $type, $item_id, $file, $debug)) {
                return false;
            }
        }

    // Used solely by this APIs serendipity_parsePropertyForm() base_properties only, when changing the file selected path within mediaproperties form
    } elseif ($type == 'filedir') {

        $pick = serendipity_renameRealFileDir($oldDir, $newDir, $type, $item_id, $debug);
        if ($pick === false) {
            return false;
        }

    } // case dir, file, filedir end

    // Entry REPLACEMENT AREA

    // Only MySQL supported, since I don't know how to use REGEXPs differently.
    // Ian: Whoever wrote this; We should improve this to all!
    //      Remove completely, when new LIKE solution found working overall! @see https://github.com/ophian/styx/commit/f1431739a39f754261ece05dfb7722a1c2d79f61#diff-66ba985797ad4611ca378bfb1d373140
    #if (!in_array($serendipity['dbType'], ['mysqli', 'sqlite3', 'sqlite3oo', 'pdo-sqlite'])) {
    #    echo '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . MEDIA_DIRECTORY_MOVE_ENTRY . "</span>\n";
    #    return true;
    #}

    if (false === serendipity_moveMediaInEntriesDB($oldDir, $newDir, $type, $file, $pick, $debug)) {
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

    $smarty_vars = array(
        'textarea'      => $serendipity['GET']['textarea'] ?? false,
        'htmltarget'    => $serendipity['GET']['htmltarget'] ?? '',
        'filename_only' => $serendipity['GET']['filename_only'] ?? false,
    );

    $output .= serendipity_displayImageList(
        $serendipity['GET']['page'] ?? 1,
        serendipity_db_bool(($serendipity['GET']['showMediaToolbar'] ?? true)),
        NULL,
        $serendipity['GET']['showUpload'] ?? false,
        NULL,
        $smarty_vars
    );

    return $output;
}

/**
 * Gets all available media directories
 *
 * @return array
 */
function &serendipity_getMediaPaths() {
    global $serendipity;

    $aExclude = array('CVS' => true, '.svn' => true, '.git' => true, '.v' => true); // the last is about Variations
    serendipity_plugin_api::hook_event('backend_media_path_exclude_directories', $aExclude);

    $paths      = array();
    $aResultSet = serendipity_traversePath(
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
