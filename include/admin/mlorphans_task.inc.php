<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('siteConfiguration')) {
    exit;
}

/**
 * Check and return s9ymdb:$id tagged img path
 * Non greedy for keeping things simple...
 */
function img_path($id, $fields) {
    $matches = array();
    $pattern = "@(<!-- s9ymdb:$id -->).*?<img[^>]* src=[\"']([^\"']+)[\"']@"; // either '<img[^>]+ ' or '<img[^>]* ' works
    foreach ($fields AS $field) {
        preg_match($pattern, $field, $matches);
    }
    return $matches;
}

/**
 * Strip out unwanted properties from match
 * DEVS: For later alerts testing purposes, do change an exiting entry s9ymdb tag only to an other existing db image ID number, so not a wild guess beyond scope!
 */
function strip_to_array($s, $p) {
    preg_match('/<!-- s9ymdb:(\d+) -->.*?<img[^>]* src=["\'](.*)["\']/', $s, $m); // should already be a simple: <!-- s9ymdb:ID -->**<img * src="*" *> by image_inuse regex and either '<img[^>]+ ' or '<img[^>]* ' works
#DEBUG if (!empty($m)) htmlspecialchars(print_r($m, true));
    return array('s9ymdb' => @$m[1], 'src' => str_replace($p, '', @$m[2]));
}

/**
 * Check and return images database entry by ID
 */
function check_by_image_db($id) {
    global $serendipity;
    $id = serendipity_db_escape_string($id);
    return serendipity_db_query("SELECT id, name, extension, thumbnail_name, path FROM {$serendipity['dbPrefix']}images WHERE id = $id", false, 'assoc');
}

/**
 * Auto fix found wrong set s9ymdb:$id tag (because of new indexed image db table, or alike)
 */
function autofix_thisimageid($entryid, $oid, $iid, $fname, $fvalue, $iname, $autofix = false) {
    global $serendipity;

    $im = [];
    $pattern = "@(<!-- s9ymdb:$oid -->).*?<img[^>]* src=[\"']([^\"']+)[\"']@"; // either '<img[^>]+ ' or '<img[^>]* ' works
    preg_match($pattern, $fvalue, $im);
    if (!empty($im[0])) {
        $iname = serendipity_db_escape_string($iname);
        $n = serendipity_db_query("SELECT id FROM {$serendipity['dbPrefix']}images WHERE name = '$iname' AND id != $iid", true, 'assoc');
        $nid = $n['id'];
        $str = (is_numeric($nid) && $oid !== $nid) ? str_replace(["<!-- s9ymdb:$oid -->"], ["<!-- s9ymdb:$nid -->"], $im[0]) : $im[0];
        $old = htmlspecialchars($im[0]); // for display only
        $new = htmlspecialchars($str); // for display only
        $table = false !== strpos('content', $fname) ? 'staticpages' : 'entries';
        if ($autofix !== true) {
            return "Autofix [submit] will UPDATE $table TABLE('$fname') field and exchange old string: <pre>$old</pre> with new: <pre>$new</pre>";
        } else {
            $newfvalue = $im[0] !== $str ? str_replace([$im[0]], $str, $fvalue) : $fvalue;
            if ($newfvalue !== $fvalue) {
                $res = serendipity_db_update($table, array('id' => $entryid), ["$fname" => $newfvalue]);
            } else $res = 'Nothing to update. String part is equal!';

            return $res;
        }
    }
    return htmlspecialchars(implode(", ", $im)); // fallback debug output
}

/**
 * Check all s9ymdb tagged and inserted images in entry which are in use
 * We need a bulletproof preg_match pattern regex syntax for
 * (<!-- itag:$iid -->) with possible following [<picture>, <source.*>, incl. whitespaces and linebreaks and ??] or [nothing or whitespace] in-between] and a following <img src=["\'](.*)["\'] string
 * [^>]* captures the content of a tag with attributes
 * We don't care about thumb appendix or not.
 */
function image_inuse($iid, $eid, $im, $entry, $field, $path, $name) {
    global $serendipity;
    static $_loop = null;

    //---------------------------------.*? matches all, inclusive \s in-between
    $matches = img_path($iid, [$entry[$field]]);

    // Only care about the full s9ymdb ID plus the image src string and check if the path matches to the blog (to avoid possible others from same machine)
    if (!empty($matches[0]) && str_contains($matches[0], $path)) {
        $o = strip_to_array($matches[0], $path);
        $o['dbiname'] = $name;
        $o['bsename'] = strtok(basename($o['src']), '.');
        #$o['ext'] = pathinfo($o['src'], PATHINFO_EXTENSION);
        // Until now this task did not have taken care about false set s9ymdb:IDs, which might exist on elder blogs, due to historic issues/bugs, or other path issues.
        // Now (check the ID to match the image DB media ID OR having an unmatching src path against the db path/name value) OR
        //     (check the image name against the basename source name AND to comply with the blogs path (i.e. in cases you have a bunch/block of image(s) with a different path copied by another (local) blog))
        $_image = check_by_image_db($o['s9ymdb']);
        if ((!empty($o['s9ymdb']) && $iid != $o['s9ymdb'] || !str_contains($o['src'], $_image[0]['path'].$_image[0]['name'])) || (!empty($o['src']) && $name != $o['bsename'] && str_contains($o['src'], $path))) {
            if (!isset($_loop)) {
                echo '<span class="msg_hint"><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_MAIN_PATTERN_NAME_WARNING . "</span>\n";
            }
            $ret = autofix_thisimageid($eid, $iid, $_image[0]['id'], $field, $entry[$field], $o['bsename'], (isset($serendipity['POST']['mlopFormAutoFix']) && $serendipity['POST']['mlopFormAutoFix'] = 'fixentries'));
            if (is_string($ret)) echo $ret; // output the possible replacement
            if (is_bool($ret)) {
                $table = false !== strpos('content', $field) ? 'staticpages' : 'entries';
                $o['return'] = $ret;
                $o['autofix'] = "Good news! The problematic media ID: \"{$o['s9ymdb']}\" for \"{$o['src']}\" was automatically fixed in your database: \"$table\" entry field: \"$field\" in entry ID: \"$eid\".";
            }
            if (!isset($ret) || is_string($ret)) {
                $o['error'] = sprintf(MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR, $eid, $field, $o['s9ymdb'], $o['src'], $o['bsename']);
                echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <strong>' . ERROR . '</strong>: ' . $o['error'] . "</span>\n";
            }
            $_loop = 1;
        }
        $im[][$iid][$eid][$field][] = $o;
    }
    // Remember, images without s9ymdb ID tag are not m-/catched!
    return $im;
}

function unlink_orphaned_images($id, $path, $name, $thumb, $ext) {
    // better delete real files in uploads/ we need filename, full path, db stored extension and thumbPrefix and also delete all available .v/ path webp file variations too.
    if (file_exists($path . $name . '.' . $ext)) {
        unlink($path . $name . '.' . $ext);
    }
    if (file_exists($path . $name . '.' . $thumb . '.' . $ext)) {
        unlink($path . $name . '.' . $thumb . '.' . $ext);
    }
    if (file_exists($path . '.v/' . $name . '.webp')) {
        unlink($path . '.v/' . $name . '.webp');
    }
    if (file_exists($path . '.v/' . $name . '.' . $thumb . '.webp')) {
        unlink($path . '.v/' . $name . '.' . $thumb . '.webp');
    }
    if (file_exists($path . '.v/' . $name . '.avif')) {
        unlink($path . '.v/' . $name . '.avif');
    }
    if (file_exists($path . '.v/' . $name . '.' . $thumb . '.avif')) {
        unlink($path . '.v/' . $name . '.' . $thumb . '.avif');
    }
    return $id.', ';
}

$im = array();
$images  = serendipity_db_query("SELECT id, name, extension, thumbnail_name, path FROM {$serendipity['dbPrefix']}images WHERE mime LIKE 'image/%' AND hotlink IS NULL ORDER BY id", false, 'assoc');
$entries = serendipity_db_query("SELECT id, body, extended FROM {$serendipity['dbPrefix']}entries WHERE body LIKE '%<!-- s9ymdb:% -->%' OR extended LIKE '%<!-- s9ymdb:% -->%'", false, 'assoc');
$spages  = array();
if (class_exists('serendipity_event_staticpage')) {
    $spages  = serendipity_db_query("SELECT id, content, pre_content FROM {$serendipity['dbPrefix']}staticpages WHERE content LIKE '%<!-- s9ymdb:% -->%' OR pre_content LIKE '%<!-- s9ymdb:% -->%'", false, 'assoc');
}

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    echo '<h3>' . sprintf(MLORPHAN_MTASK_ML_REAL_IMAGES, count($images)) . "</h3>\n";
}

if (is_array($images) && !empty($images)) {
    foreach($images AS $image) {
        if (is_array($entries) && !empty($entries)) {
            foreach($entries AS $entry) {
                $im = image_inuse($image['id'], $entry['id'], $im, $entry, 'body', $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $image['name']);
                $im = image_inuse($image['id'], $entry['id'], $im, $entry, 'extended', $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $image['name']);
            }
        }
        if (is_array($spages) && !empty($spages)) {
            foreach($spages AS $spage) {
                $im = image_inuse($image['id'], $spage['id'], $im, $spage, 'content', $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $image['name']);
                $im = image_inuse($image['id'], $spage['id'], $im, $spage, 'pre_content', $serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $image['name']);
            }
        }
    }
}

// count matches
$count = 0;
foreach (array_values($im) AS $it) {
    $count += count(array_keys($it));
}

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MLORPHAN_MTASK_MAIN_PATTERN_MATCHES, $count) . "</span>\n";
#DEBUG    echo '<pre>'.print_r($images,true).'</pre>';
#DEBUG    echo '<pre>'.print_r($im,true).'</pre>';
}

function exclude_image_matches(&$_images, $keys) {
    foreach ($_images AS $ikey => $i) {
        foreach ($keys AS $k) {
            if (isset($i) && $i['id'] == $k) {
                unset($_images[$ikey]);
            }
        }
    }
}

// flatten and unify $im array to only hold the item IDs in use by entries/staticpages
$dkeys = array();
foreach ($im AS $key) {
    foreach ($key AS $imk => $imv) {
        foreach ($imv AS $item) {
            if (!empty($item['body'][0]['s9ymdb'])) {
                $dkeys[] = $item['body'][0]['s9ymdb'];
            }
            if (!empty($item['extended'][0]['s9ymdb'])) {
                $dkeys[] = $item['extended'][0]['s9ymdb'];
            }
            if (!empty($item['content'][0]['s9ymdb'])) {
                $dkeys[] = $item['content'][0]['s9ymdb'];
            }
            if (!empty($item['pre_content'][0]['s9ymdb'])) {
                $dkeys[] = $item['pre_content'][0]['s9ymdb'];
            }
        }
    }
}
$rkeys = array_keys(array_flip($dkeys));
#DEBUG echo '<pre>'.print_r($rkeys,true).'</pre>';

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    echo MLORPHAN_MTASK_MAIN_PATTERN_RESULTCHECK_ACTION . "\n";
}

reset($images);
$_images = $images;
// exclude found s9ymd:ID tag images referenced from later delete (orphaned) array
exclude_image_matches($_images, $rkeys);

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . DONE . "</span>\n";

    echo '<span>' . MLORPHAN_MTASK_PNCASE_REVERSECHECK_ACTION . "</span>\n";
}

$exclude = '';
$pckeys  = array();
$alerts  = array();
$c = 0; // count external path items
// A paranoid re-check against entries and staticpages
foreach($_images AS $go) {
    // exclude certain entry images which don't match
    if (!empty($go['id'])) {
        $e = serendipity_db_query("SELECT id, title, body, extended FROM `{$serendipity['dbPrefix']}entries` WHERE body LIKE '%<!-- s9ymdb:{$go['id']} -->%' OR extended LIKE '%<!-- s9ymdb:{$go['id']} -->%'", false, 'assoc');
        if (!empty($e[0])) {
            $expath = img_path($go['id'], [$e[0]['body'], $e[0]['extended']]);
            if (!empty($expath[2]) && !str_contains($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $expath[2])) {
                $alerts[] = '<b>'.$go['name'] .'</b> '.MLORPHAN_MTASK_RESPECTIVELY.' ( <b>s9ymdb: '.$go['id'].'</b> ) in Entry ID: ' . $e[0]['id'].' - "<em>'. $e[0]['title'] . '</em>". ' . sprintf(MLORPHANS_MTASK_EXPATH, $expath[2]);
                $pckeys[] = $go['id'];
                $exclude .= $go['id'].', ';
                $c++;
            } else {
                $alerts[] = '<b>'.$go['name'] .'</b> '.MLORPHAN_MTASK_RESPECTIVELY.' ( <b>s9ymdb: '.$go['id'].'</b> ) in Entry ID: ' . $e[0]['id'].' - "<em>'. $e[0]['title'] . '</em>"';
                $pckeys[] = $go['id'];
                $exclude .= $go['id'].', ';
            }
        }
        // watch out for imageselectorplus plugin added quickblog entries, eg. '<!--quickblog:[none|plugin|js|_blank]|/var/www/example/htdocs/serendipity/uploads/image.jpeg-->'
        $f = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $go['path'] . $go['name'] . '.' . $go['extension'];
        $q = serendipity_db_query("SELECT id, title FROM `{$serendipity['dbPrefix']}entries` WHERE body LIKE '%<!--quickblog:%|" . serendipity_db_escape_string($f) . "-->%'", false, 'assoc');
        if (!empty($q[0])) {
            $alerts[] = '<b>'.$go['name'] .'</b> ( s9ymdb: '.$go['id'].'<em>, special: Imageselectorplus Quickblog image</em> ) in Entry ID: ' . $q[0]['id'].' - '. $q[0]['title'];
            $pckeys[] = $go['id'];
            $exclude .= $go['id'].', ';
        }
        $s = serendipity_db_query("SELECT id, pagetitle, content, pre_content FROM `{$serendipity['dbPrefix']}staticpages` WHERE content LIKE '%<!-- s9ymdb:{$go['id']} -->%' OR pre_content LIKE '%<!-- s9ymdb:{$go['id']} -->%'", false, 'assoc');
        if (!empty($s[0])) {
            $expath = img_path($go['id'], [$s[0]['content'], $s[0]['pre_content']]);
            if (!empty($expath[2]) && !str_contains($serendipity['serendipityHTTPPath'] . $serendipity['uploadHTTPPath'], $expath[2])) {
                $alerts[] = '<span style="color:blue;color:var(--color-alert-info-text)"><b>'.$go['name'] .'</b> ( s9ymdb: '.$go['id'].' ) in Staticpage ID: ' . $s[0]['id'].' - "<em>'. $s[0]['pagetitle'] . '</em>". ' . sprintf(MLORPHANS_MTASK_EXPATH, $expath[2]).'</span>';
                $pckeys[] = $go['id'];
                $exclude .= $go['id'].', ';
                $c++;
            } else {
                $alerts[] = '<span style="color:blue;color:var(--color-alert-info-text)"><b>'.$go['name'] .'</b> ( s9ymdb: '.$go['id'].' ) in Staticpage ID: ' . $s[0]['id'].' - "<em>'. $s[0]['pagetitle'].'</em>"</span>';
                $pckeys[] = $go['id'];
                $exclude .= $go['id'].', ';
            }
        }
    }
}
// exclude found paranoid case keys referenced from later delete (orphaned) array
exclude_image_matches($_images, $pckeys);

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    if (!empty($alerts)) {
        echo '<div class="msg_notice orphan">'."\n";
        echo '<h3>' . MLORPHAN_MTASK_PNCASE_TITLE . "</h3>\n";
        echo '<span>' . MLORPHAN_MTASK_PNCASE_NOTE . "</span>\n";
        if ($c > 0) {
            echo '<p>' . sprintf(MLORPHANS_MTASK_REASONS, $c, count($alerts)-$c) . "</p>\n";
        }
        echo '<ul class="xplainList">'."\n";
        foreach ($alerts AS $alert) {
            echo '<li>' . $alert."</li>\n";
        }
        echo "</ul>
        </div>\n";
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MLORPHAN_MTASK_PNCASE_EXCLUDING, substr($exclude, 0, -2)) . "</span>\n";
    } else {
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_PNCASE_OK . "</span>\n";
    }
    // Automatic correction box
    echo '<div class="msg_notice orphan autofix">';
    if (isset($serendipity['POST']['mlopFormAutoFix']) && $serendipity['POST']['mlopFormAutoFix'] = 'fixentries') {
        $reloadmsg = false;
        foreach ($im AS $fixed) {
            if (array_key_exists("return", $fixed)) {
                $f = array();
                foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($fixed)) AS $k => $v){
                    $f[$k] = $v;
                }
                if (isset($f['return']) && $f['return'] === true) {
                    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . $f['autofix'] . "</span>\n";
                    $reloadmsg = true;
                }
            }
        }
        if ($reloadmsg) {
            echo '<span><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_POST_AUTOFIX . '</span>';
        } else {
            if (!array_key_exists("return", $im)) {
                echo '<h3> ' . MLORPHAN_MTASK_POST_AUTOFIX_THXHEAD . '</h3>';
                echo '<span class="msg_success" style="margin:0 auto 1em"><span class="icon-ok-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_POST_AUTOFIX_THX . '</span>';
                echo '<span><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_MULTIPOST_AUTOFIX . '</span>';
            }
        }
    } else {
        echo '<h3> ' . MLORPHAN_MTASK_POST_PREAUTOFIX_HEAD . '</h3>';
        echo '<form id="formAutoFix" name=mlopFormAutoFix" action="?" method="POST">
                '.serendipity_setFormToken().'
                <input type="hidden" name="serendipity[adminModule]" value="maintenance">
                <input type="hidden" name="serendipity[adminAction]" value="imageorphans">
                <span><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_MCHECKED_OK . '</span>
                <div class="form_buttons">
                    <button type="submit" class="btn btn-info light" name="serendipity[mlopFormAutoFix]" value="fixentries"> ' . MLORPHAN_MTASK_AUTOFIX . ' </button>
                </div>
            </form>
            <span><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_POST_AUTOFIX . '</span>
';
    }
    echo '</div>';
}

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {

    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MLORPHAN_MTASK_FOUND_IMAGE_ORPHANS, count($_images)) . "</span>\n";
    echo '
<section id="maintenance_orphaned_images" class="quick_list" role="region" style="">
    <fieldset class="orphans">
        <span class="wrap_legend"><legend>' . sprintf(MLORPHAN_MTASK_REMOVE_IMAGE_ORPHANS, count($_images)) . "</legend></span>\n";
}

// cleanup the $images keys
foreach ($_images AS $ckey) {
    $orphaned[] = $ckey;
}

if (empty($serendipity['POST']['multiCheck']) && empty($serendipity['POST']['orphaned'])) {
    echo '<span class="msg_notice no-margin"><span class="icon-attention-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_LAST_ACTION_NOTE . "</span>\n";

    echo '  <div id="serendipity_orphaned_images">
            <form id="formMultiSelect" name="formMultiSelect" action="?" method="POST">
                '.serendipity_setFormToken().'
                <input type="hidden" name="serendipity[adminModule]" value="maintenance">
                <input type="hidden" name="serendipity[adminAction]" value="imageorphans">
                <div class="checkbyid">'."\n";
                foreach ($orphaned AS $key => $orphan) {
                    echo '<div class="form-check compact">'."\n";
                    echo '    <input id="serendipity_orphan_' . $key . '" class="multicheck" name="serendipity[multiCheck][]" type="checkbox" value="' . $orphan['id'] . '" data-multixid="media_' . $orphan['id'] . '">' . "\n";
                    echo '    <label for="serendipity_orphan_' . $key . '" title="' . $orphan['path'] . $orphan['name'] . '.' . $orphan['extension'] . '">' . $orphan['id'] . '</label>'."\n";
                    echo "</div>\n";
                }
    echo '          </div>
                <div class="form_buttons">
                    <label> ' . DELETE . ': </label>
                    <input type="submit" class="input_button state_submit" name="serendipity[orphaned]" value="all">
                    <label> or </label>
                    <button type="submit" class="btn btn-info light"> by image ID </button>
                    <input class="invert_selection" name="toggle" type="button" value="' . INVERT_SELECTIONS . '">
                    <button class="toggle_info button_link toggle_button" type="button" data-href="#orphaned_array"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> ' . MORE . '</span> view array</button>
                </div>
            </form>
        </div>
        <pre id="orphaned_array" class="orphaned_status additional_info">' . print_r($orphaned,true) . '</pre>
    </fieldset>
</section>'."\n";

    echo "
<script>
    // Inverts a selection of checkboxes
    function changeCheckboxes(list, value) {
        for(var i = (list.length-1); i >= 0; i--) {
            list[i].checked = (typeof value === 'boolean') ? value : !list[i].checked;
       }
    }

    // Inverts a selection of checkboxes - overwrites a serendipity_styx.js function
    serendipity.invertSelection = function() {
        // image orphans for example
        var inputs = document.getElementsByClassName('multicheck');
        var allCheckboxes = [];
        for (var j = (inputs.length-1); j >= 0; j--) {
            if (inputs[j].type === 'checkbox') {
                allCheckboxes.push(inputs[j]);
            }
        }
        changeCheckboxes(allCheckboxes);
    }
</script>

";

    // check images that are not in use by entries/staticpages
#DEBUG echo '<pre>'.print_r($orphaned,true).'</pre>'; // better show this to the user only to not get confused about the keys
#DEBUG echo '<pre>'.print_r($_images,true).'</pre>';

}

// $_images now should hold orphaned images only which are not used in entries/staticpages by normal usage (if the patterns match them all. I am still not absolutely sure... though.)
// TAKE CARE NOTE: Images without any s9ymdb tag '<!-- s9ymdb:% -->' which are used by your entries are still in!

if (!empty($serendipity['POST']['multiCheck']) && is_array($serendipity['POST']['multiCheck']) && serendipity_checkFormToken()) {
    $did = '';
    foreach ($serendipity['POST']['multiCheck'] AS $multicheck) {
        foreach ($orphaned AS $i) {
            if ($multicheck === $i['id']) {
                $did .= unlink_orphaned_images($i['id'], $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $i['path'], $i['name'], $i['thumbnail_name'], $i['extension']);
                serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}images WHERE id = '{$i['id']}'");
            }
        }
    }
}

if (isset($serendipity['POST']['orphaned']) && serendipity_checkFormToken() && $serendipity['POST']['orphaned'] == 'all') {
    $did = '';
    foreach ($orphaned AS $i) {
        $did .= unlink_orphaned_images($i['id'], $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $i['path'], $i['name'], $i['thumbnail_name'], $i['extension']);
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}images WHERE id = '{$i['id']}'");
    }
}

if (!empty($did)) {
    echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MLORPHAN_MTASK_PURGED_SUCCESS, substr($did, 0, -2)) . "</span>\n";
}

?>