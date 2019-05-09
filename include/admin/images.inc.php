<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('adminImages')) {
    return;
}

$data = array();

if (!is_object($serendipity['smarty'])) {
    serendipity_smarty_init();
}

// PLEASE: No echo output here, before the switch, since that matters renaming alerts!

// unset adminAction type to default, if an image was bulkmoved and the origin page reloaded
if (!is_array($serendipity['POST']) && $serendipity['GET']['adminAction'] == 'multicheck') {
    unset($serendipity['GET']['adminAction']);
}
// Listens on GET hideSubdirFiles non simple filters to list items per directory, or include all sub directory items
if (empty($serendipity['GET']['hideSubdirFiles']) && empty($serendipity['COOKIE']['hideSubdirFiles'])) {
    $serendipity['GET']['hideSubdirFiles'] = 'no'; // default
}
if (!empty($serendipity['COOKIE']['hideSubdirFiles'])) {
    serendipity_restoreVar($serendipity['COOKIE']['hideSubdirFiles'], $serendipity['GET']['hideSubdirFiles']);
}
// don't do on null
if (isset($serendipity['GET']['fid'])) {
    $serendipity['GET']['fid'] = (int)$serendipity['GET']['fid'];
}

// init all boolean Smarty variables to false
$data['case_doSync'] = $data['case_do_delete'] = false;
$data['case_do_multidelete'] = $data['case_delete'] = false;
$data['case_multidelete'] = $data['case_confirm_deletion'] = false;
$data['case_properties'] = $data['case_add'] = false;
$data['case_directoryDoDelete'] = $data['case_directoryEdit'] = false;
$data['case_directoryDelete'] = $data['case_directoryDoCreate'] = false;
$data['case_directoryCreate'] = $data['case_directorySelect'] = false;
$data['case_rotateCW'] = $data['case_rotateCCW'] = false;
$data['case_scale'] = $data['case_scaleSelect'] = false;
$data['showMLbutton'] = $data['case_default'] = false;
$data['closed'] = false;

switch ($serendipity['GET']['adminAction']) {

    case 'doSync':
        $data['case_doSync'] = true;
        $data['perm_adminImagesSync'] = true;
        // I don't know how it could've changed, but let's be safe.
        if (!serendipity_checkPermission('adminImagesSync')) {
            $data['perm_adminImagesSync'] = false;
            break;
        }

        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }
        @ignore_user_abort();

        $deleteThumbs = false;
        if (isset($serendipity['POST']['deleteThumbs'])) {
            switch ($serendipity['POST']['deleteThumbs']) {
                case 'yes':
                    $deleteThumbs = true;
                    break;
                case 'check':
                    $deleteThumbs = 'checksize';
                    break;
                case 'convert':
                    $deleteThumbs = 'convert';
                    break;
           }
        }

        // guard clause - and force a strict check, since we want (bool)true to not touch this part!
        if ($deleteThumbs === 'convert') {
            $i = serendipity_convertThumbs();
            $data['print_SYNC_DONE'] = sprintf(SYNC_DONE, $i);
            $data['convertThumbs'] = true;
            flush();
            break; // stop here
        }

        // this is: Maintenance ML cleanup for sync
        $i = serendipity_syncThumbs($deleteThumbs);
        $data['print_SYNC_DONE'] = sprintf(SYNC_DONE, $i);
        flush();

        // this is: Maintenance ML re-generate for sync
        $i = serendipity_generateThumbs();
        $data['print_RESIZE_DONE'] = sprintf(RESIZE_DONE, $i);
        flush();
        break;

    case 'doDelete':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDelete')) {
            break;
        }

        $messages = array();
        $data['case_do_delete'] = true;
        $messages[] = serendipity_deleteImage($serendipity['GET']['fid']);
        $messages[] = sprintf('<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . RIP_ENTRY . "</span>\n", $serendipity['GET']['fid']);

        $data['messages'] = $messages;
        unset($messages);
        break;

    case 'doMultiDelete':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDelete')) {
            break;
        }

        $messages = array();
        $parts = explode(',', $serendipity['GET']['id']);
        $data['case_do_multidelete'] = true;
        foreach($parts AS $id) {
            $id = (int)$id;
            if ($id > 0) {
                $image = serendipity_fetchImageFromDatabase($id);
                $messages[] = serendipity_deleteImage((int)$id);
                $messages[] = sprintf('<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . RIP_ENTRY . "</span>\n", $image['id'] . ' - ' . serendipity_specialchars($image['realname']));
            }
        }
        $data['showML'] = showMediaLibrary();
        $data['messages'] = $messages;
        unset($messages);
        break;

    case 'delete':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);

        if (!is_array($file) || !serendipity_checkPermission('adminImagesDelete') || (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }

        $data['case_delete'] = true;
        if (!isset($serendipity['adminFile'])) {
            $serendipity['adminFile'] = 'serendipity_admin.php';
        }
        $abortLoc = $serendipity['serendipityHTTPPath'] . $serendipity['adminFile'] . '?serendipity[adminModule]=images';
        $newLoc   = $abortLoc . '&serendipity[adminAction]=doDelete&serendipity[fid]=' . (int)$serendipity['GET']['fid'] . '&' . serendipity_setFormToken('url');
        $data['file']     = $file['name'] . '.' . $file['extension'];
        $data['abortLoc'] = $abortLoc;
        $data['newLoc']   = $newLoc;
        break;

    case 'multiselect':
        if (!serendipity_checkFormToken()) {
            return; // blank content page, but default token check parameter is presenting a XSRF message when false
        }
        if ((empty($serendipity['POST']['multiSelect']) || !is_array($serendipity['POST']['multiSelect'])) && isset($_POST['gallery_insert'])) {
            echo '<div class="msg_notice"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MULTICHECK_NO_ITEM, serendipity_specialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES | ENT_HTML401)) . '</div>'."\n";
            return; // blank content page exit
        }
        $_multiSelectImages = $serendipity['POST']['multiSelect'];
        unset($serendipity['POST']['multiSelect']);
        foreach($_multiSelectImages AS $media_id) {
            $file = serendipity_fetchImageFromDatabase((int)$media_id);
            serendipity_prepareMedia($file);
            $file['props'] =& serendipity_fetchMediaProperties((int)$media_id);
            serendipity_plugin_api::hook_event('media_getproperties_cached', $file['props']['base_metadata'], $file['realfile']);
            $file['prop_imagecomment'] = serendipity_specialchars((isset($file['props']['base_property']['COMMENT1']) ? $file['props']['base_property']['COMMENT1'] : ''));
            $file['prop_alt']          = serendipity_specialchars((isset($file['props']['base_property']['ALT'])      ? $file['props']['base_property']['ALT']      : ''));
            $file['prop_title']        = serendipity_specialchars((isset($file['props']['base_property']['TITLE'])    ? $file['props']['base_property']['TITLE']    : ''));
            unset($file['props']); // we don't need this bloat, except the three above
            unset($file['thumb_header']); // img (encoded) header data will make json_encode() fail and return nothing
            unset($file['header']);
            $files[] = &$file;
            unset($file); // keep this to prevail overwrite!!!!
        }
        $media['files'] = $files;
        unset($files);

        $media = array_merge($serendipity['POST'], $media);
        $jsmedia = json_encode($media); // image header(s) let the encoder fail to return nothing (see above)

        $media['fast_select'] = true;

        $media = array_merge($serendipity['GET'], $media);
        $serendipity['smarty']->assignByRef('media', $media);
        $serendipity['smarty']->assignByRef('jsmedia', $jsmedia);
        echo $serendipity['smarty']->display('admin/media_galleryinsert.tpl', $data); // no need for a compile file
        #echo serendipity_smarty_showTemplate('admin/media_galleryinsert.tpl', $data);
        break;

    case 'multicheck':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDirectories')) {
            return; // blank content page, but default token check parameter is presenting a XSRF message when false
        }
        if (!is_array($serendipity['POST']['multiCheck']) && (isset($_POST['toggle_move']) || isset($_POST['toggle_delete']))) {
            echo '<div class="msg_notice"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MULTICHECK_NO_ITEM, serendipity_specialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES | ENT_HTML401)) . '</div>'."\n";
            return; // blank content page exit
        }
        if (is_array($serendipity['POST']['multiCheck']) && isset($serendipity['POST']['oldDir']) && empty($serendipity['POST']['newDir']) && isset($_POST['toggle_move'])) {
            echo '<div class="msg_notice"><span class="icon-attention-circled" aria-hidden="true"></span> ' . sprintf(MULTICHECK_NO_DIR, serendipity_specialchars($_SERVER['HTTP_REFERER'], ENT_QUOTES | ENT_HTML401)) . '</div>'."\n";
            return; // blank content page exit
        }
        // case bulk multimove (leave the faked oldDir being send as an empty dir string)
        if (isset($serendipity['POST']['oldDir']) && !empty($serendipity['POST']['newDir'])) {
            $messages = array();
            $multiMoveImages = $serendipity['POST']['multiCheck'];
            unset($serendipity['POST']['multiCheck']); // since used later for delete

            // oldDir is relative to Uploads/, since we can not specify a directory of a ML bulk move directly
            $nDir = serendipity_specialchars((string)str_replace('//', '/', $serendipity['POST']['newDir'])); // relative to Uploads/
            // set and check for a given trailing slash! (see media directory renames)
            $nDir = (!empty($nDir) && $nDir != '/') ? rtrim($nDir, '/') . '/' : $nDir;
            // $nDir "set empty" check for the fake-named "uploadRoot" directory is done via functions_images.inc, since we need it for comparison checks before conversion

            if ($serendipity['POST']['oldDir'] != $nDir) {
                $i = 0;
                foreach($multiMoveImages AS $mkey => $move_id) {
                    $file = serendipity_fetchImageFromDatabase((int)$move_id);
                    $oDir = $file['path']; // this now is the exact oldDir path of this ID
                    $mMDr = serendipity_moveMediaDirectory($oDir, $nDir, 'file', (int)$move_id, $file);
                    ++$i;
                }
                $rDir = '"'.(($nDir == 'uploadRoot/') ? $serendipity['uploadHTTPPath'].'"' : $serendipity['uploadHTTPPath'] . $nDir).'"';
                if ($mMDr) {
                    $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . $i . ' ' . MEDIA_DIRECTORY_MOVED . "</span>\n", $rDir);
                } else {
                    $messages[] = sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . MEDIA_DIRECTORY_MOVE_ERROR . "</span>\n", $rDir);
                }
            }
            $data['messages'] = $messages;
            unset($messages);
            // remember to return to last selected media library directory
            serendipity_restoreVar($serendipity['COOKIE']['serendipity_only_path'], $serendipity['GET']['only_path']);
            // fall back
            $data['case_default'] = true;
            $data['showML'] = showMediaLibrary(true); // the true drives us back to selected directory :)
            break;
        }

        // case bulk multicheck delete
        $ids = '';
        $data['rip_image']        = array();
        $data['case_multidelete'] = true;
        foreach($serendipity['POST']['multiCheck'] AS $idx => $id) {
            $ids .= (int)$id . ',';
            $image = serendipity_fetchImageFromDatabase($id);
            $data['rip_image'][] = sprintf(DELETE_SURE, $image['id'] . ' - ' . serendipity_specialchars($image['realname']));
        }
        if (!isset($serendipity['adminFile'])) {
            $serendipity['adminFile'] = 'serendipity_admin.php';
        }
        $abortLoc = $serendipity['serendipityHTTPPath'] . $serendipity['adminFile'] . '?serendipity[adminModule]=images';
        $newLoc   = $serendipity['serendipityHTTPPath'] . $serendipity['adminFile'] . '?' . serendipity_setFormToken('url') . '&amp;serendipity[action]=admin&amp;serendipity[adminModule]=images&amp;serendipity[adminAction]=doMultiDelete&amp;serendipity[id]=' . $ids;
        $data['case_confirm_deletion'] = true;
        $data['abortLoc'] = $abortLoc;
        $data['newLoc']   = $newLoc;
        break;

    case 'rename':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);

        if (LANG_CHARSET == 'UTF-8') {
             // yeah, turn on content to be a real utf-8 string, which it isn't at this point! Else serendipity_makeFilename() can not work!
             $serendipity['GET']['newname'] = utf8_encode($serendipity['GET']['newname']);
        }
        $serendipity['GET']['newname'] = str_replace(' ', '_', $serendipity['GET']['newname']); // keep serendipity_uploadSecure(URL) whitespace convert behaviour, when using serendipity_makeFilename()
        $serendipity['GET']['newname'] = serendipity_uploadSecure(serendipity_makeFilename($serendipity['GET']['newname']), true);

        if (!is_array($file) || !serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDelete') ||
           (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }
        // since this is a javascript action only, all event success/error action messages have moved into js
        serendipity_moveMediaDirectory(null, $serendipity['GET']['newname'], 'file', $serendipity['GET']['fid'], $file);
        break;

    case 'properties':
        $data['case_properties'] = true;
        $new_media = array(array('image_id' => $serendipity['GET']['fid']));
        echo serendipity_showPropertyForm($new_media);
        break;

    case 'add':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesAdd')) {
            return;
        }
        $data['case_add'] = true;
        $messages = array();
        if (isset($serendipity['POST']['adminSubAction']) && $serendipity['POST']['adminSubAction'] == 'properties') {
            serendipity_restoreVar($serendipity['COOKIE']['serendipity_only_path'], $serendipity['GET']['only_path']); // restore last set directory path, see true parameter
            $properties        = serendipity_parsePropertyForm();
            $image_id          = $properties['image_id'];
            $created_thumbnail = true; //??
            $data['showML']    = showMediaLibrary(true); // in this case we do not need the location.href (removed)
            $propdone          = sprintf(MEDIA_PROPERTIES_DONE, $image_id);
            $data['messages']  = '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> '.DONE.'! ' . $propdone . "</span>\n";
            break;
        }

        $messages[] = '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . ADDING_IMAGE . "</span>\n";

        $authorid = 0; // Only use access-control based on media directories, not images themselves

        $new_media = array();

        $_imageurl = isset($serendipity['POST']['imageurl']) ? serendipity_specialchars($serendipity['POST']['imageurl']) : '';

        // First find out whether to fetch a download hotlink or accept an upload file
        $pattern = '~^(?:ht|f)tps?://[a-z0-9.-_\/](?:(?!.{3}+\?|#|\+).)+\.(?:jpe?g|png|gif)~Ui'; // each protocol, a negative look behind to not match malicious URIs and the 4 most common img extensions
        if ($_imageurl != '' && $_imageurl != 'http://' && preg_match($pattern, $_imageurl)) {
            // case DOWNLOAD file
            if (!empty($serendipity['POST']['target_filename'][2])) {
                // Faked hidden form 2 when submitting with JavaScript
                $tfile   = $serendipity['POST']['target_filename'][2];
                $tindex  = 2;
            } elseif (!empty($serendipity['POST']['target_filename'][1])) {
                // Fallback key when not using JavaScript
                $tfile   = $serendipity['POST']['target_filename'][1];
                $tindex  = 1;
            } else {
                $tfile   = $_imageurl;
                $tindex  = 1;
            }

            $tfile = str_replace(' ', '_', basename($tfile)); // keep serendipity_uploadSecure(URL) whitespace convert behaviour, when using serendipity_makeFilename()
            $tfile = serendipity_uploadSecure(serendipity_makeFilename($tfile));

            if (serendipity_isActiveFile($tfile)) {
                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_FORBIDDEN . " $tfile </span>\n";
                break;
            }

            $serendipity['POST']['target_directory'][$tindex] = serendipity_uploadSecure($serendipity['POST']['target_directory'][$tindex], true, true);
            $target = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $serendipity['POST']['target_directory'][$tindex] . $tfile;

            if (!serendipity_checkDirUpload($serendipity['POST']['target_directory'][$tindex])) {
                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . PERM_DENIED . "</span>\n";
                return;
            }

            $realname = $tfile;
            if (file_exists($target)) {
                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . $target . ' - ' . ERROR_FILE_EXISTS_ALREADY . "</span>\n";
                $realname = serendipity_imageAppend($tfile, $target, $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $serendipity['POST']['target_directory'][$tindex]);
            }

            if (!serendipity_url_allowed($_imageurl)) {
                $messages[] = sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . REMOTE_FILE_INVALID . "</span>\n", $_imageurl);
            } else {
                // Try to get the URL
                try {
                    $fContent = serendipity_request_url($_imageurl, 'GET', null, null, null, 'image');
                    if (!isset($serendipity['last_http_request']) || $serendipity['last_http_request']['responseCode'] != '200') {
                        throw new Exception("Something wrong with responseCode: {$serendipity['last_http_request']['responseCode']}?");
                    } else {
                        if ($serendipity['POST']['imageimporttype'] == 'hotlink') {
                            $tempfile = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . 'hotlink_' . time();
                            $fp = fopen($tempfile, 'w');
                            fwrite($fp, $fContent);
                            fclose($fp);

                            $image_id = @serendipity_insertHotlinkedImageInDatabase($tfile, $_imageurl, $authorid, null, $tempfile);
                            if ($image_id > 0) {
                                $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . HOTLINK_DONE . "</span>\n", $_imageurl, $tfile);
                                serendipity_plugin_api::hook_event('backend_image_addHotlink', $_imageurl);
                            } else {
                                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> HOTLINK '.$_imageurl . "($tfile) failed.</span>\n";
                            }
                        } else {
                            $fp = fopen($target, 'w');
                            fwrite($fp, $fContent);
                            fclose($fp);

                            $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . FILE_FETCHED . "</span>\n", $_imageurl , $tfile . '');

                            if (serendipity_checkMediaSize($target)) {
                                $thumbs = array(array(
                                    'thumbSize' => $serendipity['thumbSize'],
                                    'thumb'     => $serendipity['thumbSuffix']
                                ));
                                serendipity_plugin_api::hook_event('backend_media_makethumb', $thumbs, $target);

                                foreach($thumbs AS $thumb) {
                                    // Create thumbnail
                                    if ($created_thumbnail = serendipity_makeThumbnail($tfile, $serendipity['POST']['target_directory'][$tindex], $thumb['thumbSize'], $thumb['thumb'])) {
                                        $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . THUMB_CREATED_DONE . "</span>\n", "<b>{$thumb['thumb']}</b> &#8660; <b>$uploadfile</b>");
                                    }
                                }

                                // Insert into database
                                $image_id = serendipity_insertImageInDatabase($tfile, $serendipity['POST']['target_directory'][$tindex], $authorid, null, $realname);
                                serendipity_plugin_api::hook_event('backend_image_add', $target);
                                $new_media[] = array(
                                    'image_id'          => $image_id,
                                    'target'            => $target,
                                    'created_thumbnail' => $created_thumbnail
                                );
                            }
                        }
                    }
                } catch (Throwable $t) {
                    // Executed only in PHP 7, will not match in PHP 5.x
                    $messages[] = sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . REMOTE_FILE_NOT_FOUND . ". Status returned is: \"{$serendipity['last_http_request']['responseCode']}\".</span>\n", $_imageurl);
                } catch (Exception $e) {
                    // Executed only in PHP 5.x, will not be reached in PHP 7
                    $messages[] = sprintf('<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . REMOTE_FILE_NOT_FOUND . ". Status returned is: \"{$serendipity['last_http_request']['responseCode']}\".</span>\n", $_imageurl);
                }
            }
        } else {
            if (!is_array($_FILES['serendipity']['name']['userfile']) || empty($_FILES['serendipity']['name']['userfile'][1])) {
                $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . "</span>\n";
                $data['messages'] = $messages;
                unset($messages);
                break;
            }

            foreach($_FILES['serendipity']['name']['userfile'] AS $idx => $uploadfiles) {
                if (! is_array($uploadfiles)) {
                    $uploadfiles = array($uploadfiles);
                }
                $uploadFileCounter=-1;
                foreach($uploadfiles AS $uploadfile) {
                    $uploadFileCounter++;
                    $target_filename = isset($serendipity['POST']['target_filename'][$idx]) ? $serendipity['POST']['target_filename'][$idx] : null;
                    $uploadtmp  = $_FILES['serendipity']['tmp_name']['userfile'][$idx];
                    if (is_array($uploadtmp)) {
                        $uploadtmp = $uploadtmp[$uploadFileCounter];
                    }
                    if (!empty($target_filename)) {
                        $tfile = $target_filename;
                    } elseif (!empty($uploadfile)) {
                        $tfile = $uploadfile;
                    } else {
                        // skip empty array
                        continue;
                    }

                    $tfile = str_replace(' ', '_', basename($tfile)); // keep serendipity_uploadSecure(URL) whitespace convert behaviour, when using serendipity_makeFilename()
                    $tfile = serendipity_specialchars($tfile); // needed to prevent ability for uploader to inject javascript https://github.com/s9y/Serendipity/commit/f295a3b123bd7840ae65ccb2050ee93e5fbbcd93#diff-96c5729a7a3cb8af240c8d9fee9f023fR
                    $tfile = serendipity_uploadSecure(serendipity_makeFilename($tfile));

                    if (serendipity_isActiveFile($tfile)) {
                        $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_FILE_FORBIDDEN . " $tfile </span>\n";
                        continue;
                    }

                    // avoid uploading images files without extensions, which quite often is done on Macs, since that is bad for the MediaLibrary Management
                    $tmpfileinfo = @serendipity_getimagesize($uploadtmp);
                    if (empty(strtolower(pathinfo($tfile, PATHINFO_EXTENSION)))
                    && $tmpfileinfo[0] > 0 && $tmpfileinfo[1] > 0 /* check width and height */
                    && in_array($tmpfileinfo[2], [IMAGETYPE_BMP, IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_TIFF_II, IMAGETYPE_TIFF_MM, IMAGETYPE_WEBP])) {
                        $ext = explode('/', $tmpfileinfo['mime']);
                        $tfile = $tfile . '.' . $ext[1];
                    }

                    $serendipity['POST']['target_directory'][$idx] = isset($serendipity['POST']['target_directory'][$idx])
                                                                        ? serendipity_uploadSecure($serendipity['POST']['target_directory'][$idx], true, true)
                                                                        : null;

                    if (!serendipity_checkDirUpload($serendipity['POST']['target_directory'][$idx])) {
                        $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . PERM_DENIED . "</span>\n";
                        continue;
                    }

                    // last chance to lower the upload file extension part
                    $info   = pathinfo($tfile);
                    $tfile  = $info['filename'] . '.' . strtolower($info['extension']);

                    $target = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $serendipity['POST']['target_directory'][$idx] . $tfile;

                    $realname = $tfile;
                    if (file_exists($target)) {
                        $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . $target . ' - ' . ERROR_FILE_EXISTS_ALREADY . "</span>\n";
                        $realname   = serendipity_imageAppend($tfile, $target, $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $serendipity['POST']['target_directory'][$idx]);
                    }

                    // Accept file
                    if (is_uploaded_file($uploadtmp) && serendipity_checkMediaSize($uploadtmp) && move_uploaded_file($uploadtmp, $target)) {
                        $uploadfile = serendipity_specialchars($uploadfile); //see $tfile L 439 - and we  want the origin name here!
                        $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . FILE_UPLOADED . "</span>\n", "<b>$uploadfile</b>", $target);
                        @umask(0000);
                        @chmod($target, 0664);

                        $thumbs = array(array(
                            'thumbSize' => $serendipity['thumbSize'],
                            'thumb'     => $serendipity['thumbSuffix']
                        ));
                        serendipity_plugin_api::hook_event('backend_media_makethumb', $thumbs, $target);

                        foreach($thumbs AS $thumb) {
                            // Create thumbnail
                            if ($created_thumbnail = serendipity_makeThumbnail($tfile, $serendipity['POST']['target_directory'][$idx], $thumb['thumbSize'], $thumb['thumb'])) {
                                $messages[] = sprintf('<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . THUMB_CREATED_DONE . "</span>\n", "<b>{$thumb['thumb']}</b> &#8660; <b>$uploadfile</b>");
                            }
                        }

                        // Insert into database
                        $image_id = serendipity_insertImageInDatabase($tfile, $serendipity['POST']['target_directory'][$idx], $authorid, null, $realname);
                        serendipity_plugin_api::hook_event('backend_image_add', $target, $created_thumbnail);
                        $new_media[] = array(
                            'image_id'          => $image_id,
                            'target'            => $target,
                            'created_thumbnail' => $created_thumbnail
                        );
                    } else {
                        // necessary for the ajax-uploader to show upload errors
                        header("Internal Server Error", true, 500);
                        $messages[] = '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_UNKNOWN_NOUPLOAD . "</span>\n";
                    }
                }
            }
        }

        if (isset($_REQUEST['go_properties'])) {
            echo serendipity_showPropertyForm($new_media);
        } else {
            $hidden = array(
                'author'   => $serendipity['serendipityUser'],
                'authorid' => $serendipity['authorid']
            );

            foreach($new_media AS $nm) {
                serendipity_insertMediaProperty('base_hidden', '', $nm['image_id'], $hidden);
            }
            $data['showML'] = showMediaLibrary(true);
        }
        $data['messages'] = $messages;
        unset($messages);
        break;

    case 'directoryDoDelete':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }

        $data['case_directoryDoDelete'] = true;
        $new_dir = serendipity_uploadSecure($serendipity['GET']['dir'], true);
        if (is_dir($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $new_dir)) {
            if (!is_writable($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $new_dir)) {
                $data['print_DIRECTORY_WRITE_ERROR'] = sprintf(DIRECTORY_WRITE_ERROR, $new_dir);
            } else {
                ob_start();
                // Directory exists and is writable. Now dive within subdirectories and kill 'em all.
                serendipity_killPath($serendipity['serendipityPath'] . $serendipity['uploadPath'], $new_dir, (isset($serendipity['POST']['nuke']) ? true : false));
                $data['ob_serendipity_killPath'] = ob_get_contents();
                ob_end_clean();
           }
        } else {
            $data['print_ERROR_NO_DIRECTORY'] = sprintf(ERROR_NO_DIRECTORY, $new_dir);
        }

        serendipity_plugin_api::hook_event('backend_directory_delete', $new_dir);
        break;

    case 'directoryEdit':
        if (!serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }

        $data['case_directoryEdit'] = true;
        $data['closed'] = true;

        $use_dir   = serendipity_uploadSecure($serendipity['GET']['dir']);
        $checkpath = array(
            array(
                'relpath' => $use_dir
            )
        );

        if (!serendipity_directoryACL($checkpath, 'write')) {
            return;
        }

        if (!empty($serendipity['POST']['save'])) {
            // preserve moving subdir directories to serendipity_makeFilename(), preserves dir/subdir/ for example
            $_newDir = $serendipity['POST']['newDir'];
            $newfile = serendipity_makeFilename(basename($_newDir));
            $newDir  = (dirname($_newDir) != '.') ? dirname($_newDir) . '/' . $newfile : $newfile;
            $oldDir  = serendipity_uploadSecure($serendipity['POST']['oldDir']);

            if ($oldDir != $newDir) {
                //is this possible? Ian: YES! Change an already set directory.
                ob_start();
                serendipity_moveMediaDirectory($oldDir, $newDir);
                $data['messages'] = ob_get_contents();
                ob_end_clean();
                $use_dir = (!empty($newDir) && $newDir != '/') ? rtrim($newDir, '/') . '/' : $newDir;
            }

            serendipity_ACLGrant(0, 'directory', 'read', $serendipity['POST']['read_authors'], $use_dir);
            serendipity_ACLGrant(0, 'directory', 'write', $serendipity['POST']['write_authors'], $use_dir);
            $data['savedirtime'] = serendipity_strftime('%H:%M:%S');
        }

        $groups = serendipity_getAllGroups();
        $read_groups  = serendipity_ACLGet(0, 'directory', 'read', $use_dir);
        $write_groups = serendipity_ACLGet(0, 'directory', 'write', $use_dir);

        if (!empty($serendipity['POST']['update_children'])) {
            $dir_list = serendipity_traversePath($serendipity['serendipityPath'] . $serendipity['uploadPath'], $use_dir, true, NULL, 1, NULL, 'write', NULL);
            foreach($dir_list AS $f => $dir) {
                // Apply parent ACL to children.
                serendipity_ACLGrant(0, 'directory', 'read', $serendipity['POST']['read_authors'], $dir['relpath']);
                serendipity_ACLGrant(0, 'directory', 'write', $serendipity['POST']['write_authors'], $dir['relpath']);
            }
        }
        $data['groups']       = $groups;
        $data['use_dir']      = $use_dir;
        $data['formtoken']    = serendipity_setFormToken();
        $data['dir']          = serendipity_specialchars($serendipity['GET']['dir']);
        $data['rgroups']      = (isset($read_groups[0]) ? true : false);
        $data['wgroups']      = (isset($write_groups[0]) ? true : false);
        $data['read_groups']  = $read_groups;
        $data['write_groups'] = $write_groups;
        break;

    case 'directoryDelete':
        if (!serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }
        $data['case_directoryDelete'] = true;
        $data['dir']          = serendipity_specialchars($serendipity['GET']['dir']);
        $data['formtoken']    = serendipity_setFormToken();
        $data['basename_dir'] = basename(serendipity_specialchars($serendipity['GET']['dir']));
        break;

    case 'directoryDoCreate':
        if (!serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }

        $data['case_directoryDoCreate'] = true;

        $new_dir = serendipity_uploadSecure($serendipity['POST']['parent'] . '/' . serendipity_makeFilename($serendipity['POST']['name']), true);
        $new_dir = str_replace(array('..', '//'), array('', '/'), $new_dir);

        $nd      = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $new_dir;
        serendipity_plugin_api::hook_event('backend_directory_create', $nd);

        /* TODO: check if directory already exist */
        if (is_dir($nd) || @mkdir($nd)) {
            $data['print_DIRECTORY_CREATED'] = sprintf(DIRECTORY_CREATED, serendipity_specialchars($new_dir));
            @umask(0000);
            @chmod($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $new_dir, 0777);

            // Apply parent ACL to new child.
            $array_parent_read  = serendipity_ACLGet(0, 'directory', 'read',  $serendipity['POST']['parent']);
            $array_parent_write = serendipity_ACLGet(0, 'directory', 'write', $serendipity['POST']['parent']);
            if (!is_array($array_parent_read) || count($array_parent_read) < 1) {
                $parent_read = array(0);
            } else {
                $parent_read = array_keys($array_parent_read);
            }
            if (!is_array($array_parent_write) || count($array_parent_write) < 1) {
                $parent_write = array(0);
            } else {
                $parent_write = array_keys($array_parent_write);
            }

            serendipity_ACLGrant(0, 'directory', 'read', $parent_read, $new_dir . '/');
            serendipity_ACLGrant(0, 'directory', 'write', $parent_write, $new_dir . '/');
        } else {
            $data['print_DIRECTORY_WRITE_ERROR'] = sprintf(DIRECTORY_WRITE_ERROR, $new_dir);
        }

        break;

    case 'directoryCreate':
    case 'directoryCreateSub':
        if (!serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }

        $folders = serendipity_traversePath(
            $serendipity['serendipityPath'] . $serendipity['uploadPath'],
            '',
            true,
            NULL,
            1,
            NULL,
            'write'
        );
        usort($folders, 'serendipity_sortPath');
        $data['case_directoryCreate'] = true;
        $data['formtoken'] = serendipity_setFormToken();
        $data['folders']   = $folders;
        $data['dir'] = isset($serendipity['GET']['dir']) ? $serendipity['GET']['dir'] : null;
        break;

    case 'directorySelect':
        if (!serendipity_checkPermission('adminImagesDirectories')) {
            return;
        }

        $folders = serendipity_traversePath(
            $serendipity['serendipityPath'] . $serendipity['uploadPath'],
            '',
            true,
            NULL,
            1,
            NULL,
            'write'
        );
        usort($folders, 'serendipity_sortPath');
        $data['case_directorySelect'] = true;
        $data['folders'] = $folders;
        $data['threadedDirs'] = in_array(1, array_column($folders, 'depth'));
        $data['pathitems'] = array_column(serendipity_getTotalCount('mediabypath'), 'num', 'cat');
        break;

    case 'addSelect':
        if (!serendipity_checkPermission('adminImagesAdd')) {
            return;
        }

        serendipity_restoreVar($serendipity['COOKIE']['addmedia_directory'], $serendipity['GET']['only_path']);
        $folders = serendipity_traversePath(
            $serendipity['serendipityPath'] . $serendipity['uploadPath'],
            '',
            true,
            NULL,
            1,
            NULL,
            'write'
        );
        usort($folders, 'serendipity_sortPath');

        $form_hidden = '';
        if (isset($image_selector_addvars) && is_array($image_selector_addvars)) {
            // These variables may come from serendipity_admin_image_selector.php to show embedded upload form
            foreach($image_selector_addvars AS $imgsel_key => $imgsel_val) {
                $form_hidden .= '          <input type="hidden" name="serendipity[' . serendipity_specialchars($imgsel_key) . ']" value="' . serendipity_specialchars($imgsel_val) . '" />' . "\n";
            }
        }

        $mediaFiles = array(
            'token'             => serendipity_setFormToken(),
            'form_hidden'       => $form_hidden,
            'folders'           => $folders,
            'only_path'         => $serendipity['GET']['only_path'],
            'max_file_size'     => $serendipity['maxFileSize'],
            'maxImgHeight'      => $serendipity['maxImgHeight'],
            'maxImgWidth'       => $serendipity['maxImgWidth'],
            'extraParems'       => serendipity_generateImageSelectorParems(),
            'manage'            => isset($serendipity['GET']['showMediaToolbar']) ? serendipity_db_bool($serendipity['GET']['showMediaToolbar']) : true,
            'multiperm'         => serendipity_checkPermission('adminImagesDirectories')
        );
        // ToDo later: merge $data and $media
        $serendipity['smarty']->assign('media', $mediaFiles);
        $serendipity['smarty']->display(serendipity_getTemplateFile('admin/media_upload.tpl', 'serendipityPath')); // no need for a compile file
        return;

    case 'rotateCW':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);
        if (!is_array($file) || !serendipity_checkPermission('adminImagesDelete') || (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }

        if (empty($serendipity['adminFile_redirect'])) {
            $serendipity['adminFile_redirect'] = serendipity_specialchars($_SERVER['HTTP_REFERER']);
        }

        $data['case_rotateCW'] = true;
        if (serendipity_rotateImg($serendipity['GET']['fid'], -90)) {
            $data['rotate_img_done']    = true;
            $data['adminFile_redirect'] = $serendipity['adminFile_redirect'];
        }
        break;

    case 'rotateCCW':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);
        if (!is_array($file) || !serendipity_checkPermission('adminImagesDelete') || (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }

        if (empty($serendipity['adminFile_redirect'])) {
            $serendipity['adminFile_redirect'] = serendipity_specialchars($_SERVER['HTTP_REFERER']);
        }

        $data['case_rotateCCW'] = true;
        if (serendipity_rotateImg($serendipity['GET']['fid'], 90)) {
            $data['rotate_img_done']    = true;
            $data['adminFile_redirect'] = $serendipity['adminFile_redirect'];
        }
        break;

    case 'scale':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);

        if (!is_array($file) || !serendipity_checkFormToken() || !serendipity_checkPermission('adminImagesDelete') || (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }

        $data['case_scale'] = true; // this allows to use the showML fallback too
        if ($serendipity['GET']['width'] == $file['dimensions_width'] && $serendipity['GET']['height'] == $file['dimensions_height']) {
            $data['messages'] = '<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> ' . MEDIA_RESIZE_EXISTS . '</span>';
        } else {
            $data['print_SCALING_IMAGE'] = sprintf(
                SCALING_IMAGE,
                $file['path'] . $file['name'] .'.'. $file['extension'],
                (int)$serendipity['GET']['width'],
                (int)$serendipity['GET']['height']
            );
            $scaleImg = serendipity_scaleImg($serendipity['GET']['fid'], (int)$serendipity['GET']['width'], (int)$serendipity['GET']['height']);
            if (!empty($scaleImg) && is_string($scaleImg)) {
                $data['scaleImgError'] = $scaleImg;
            }
            $data['is_done'] = true;
        }
        // fall back
        $serendipity['GET']['fid'] = null; // reset to nil after done, to be able to get the tools metaActionBar on showML via $media.metaActionBar
        $data['showML'] = showMediaLibrary();
        break;

    case 'scaleSelect':
        $file = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);

        if (!is_array($file) || !serendipity_checkPermission('adminImagesDelete') || (!serendipity_checkPermission('adminImagesMaintainOthers') && $file['authorid'] != '0' && $file['authorid'] != $serendipity['authorid'])) {
            return;
        }

        $data['case_scaleSelect'] = true;
        $s = getimagesize($serendipity['serendipityPath'] . $serendipity['uploadPath'] . $file['path'] . $file['name'] . ($file['extension'] ? '.'. $file['extension'] : ''));

        $data['scaleFileName']   = $file['name'];
        $data['scaleOriginSize'] = array('width' => $s[0], 'height' => $s[1]);
        $data['formtoken']       = serendipity_setFormToken();
        $data['file']            = $serendipity['uploadHTTPPath'] . $file['path'] . $file['name'] .($file['extension'] ? '.'. $file['extension'] : '');
        break;

    case 'choose':
        $file          = serendipity_fetchImageFromDatabase($serendipity['GET']['fid']);
        $media['file'] = &$file;
        if (!is_array($file)) {
            $media['perm_denied'] = true;
            break;
        }

        serendipity_prepareMedia($file);

        $media['file']['props'] =& serendipity_fetchMediaProperties((int)$serendipity['GET']['fid']);
        serendipity_plugin_api::hook_event('media_getproperties_cached', $media['file']['props']['base_metadata'], $media['file']['realfile']);

        if ($file['is_image']) {
            $file['finishJSFunction'] = $file['origfinishJSFunction'] = 'serendipity.serendipity_imageSelector_done(\'' . serendipity_specialchars($serendipity['GET']['textarea']) . '\')';

            if (!empty($serendipity['GET']['filename_only']) && $serendipity['GET']['filename_only'] !== 'true') {
                $file['fast_select'] = true;
            }
        }
        $media = array_merge($serendipity['GET'], $media);
        $serendipity['smarty']->assignByRef('media', $media);
        echo serendipity_smarty_showTemplate('admin/media_choose.tpl', $data);
        break;

    default:
        $data['case_default'] = true;
        $data['showML'] = showMediaLibrary();
        break;

}

if (! isset($data['showML'])) {
    if (isset($_REQUEST['go_properties'])) {
        $data['showMLbutton'] = true;
    } else {
        // always having the ML available is useful when switching the filter after adding an image, thus being in the add-case (hotlink/upload)
        if (isset($serendipity['POST']['imageurl'])) {
            $data['showML'] = showMediaLibrary();
        }
    }
}

$data['get']['fid']       = isset($serendipity['GET']['fid']) ? $serendipity['GET']['fid'] : null; // don't trust {$smarty.get.vars} if not proofed, as we often change GET vars via serendipity['GET'] by runtime
$data['get']['only_path'] = isset($serendipity['GET']['only_path']) ? $serendipity['GET']['only_path'] : '';

echo serendipity_smarty_showTemplate('admin/images.inc.tpl', $data);

/* vim: set sts=4 ts=4 expandtab : */
