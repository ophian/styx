<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

if (!serendipity_checkPermission('siteConfiguration')) {
    exit;
}

/**
 * Strip out unwanted properties from match
 * DEVS: For later alerts testing purposes, do change an exiting entry s9ymdb tag only to an other existing db image ID number, so not a wild guess beyond scope!
 */
function strip_to_array($s, $p) {
    preg_match('/<!-- s9ymdb:(\d+) -->.*?<img[^>]* src=["\'](.*)["\']/', $s, $m); // should already be a simple: <!-- s9ymdb:ID -->**<img * src="*" *> by image_inuse regex and either '<img[^>]+ ' or '<img[^>]* ' works
#DEBUG    if (!empty($m)) print_r($m);
    return array('s9ymdb' => @$m[1], 'src' => str_replace($p, '', @$m[2]));
}

/**
 * Check and return database entry by ID
 */
function check_by_image_db($id) {
    global $serendipity;
    $id = serendipity_db_escape_string($id);
    return serendipity_db_query("SELECT id, name, extension, thumbnail_name, path FROM {$serendipity['dbPrefix']}images WHERE id = $id", false, 'assoc');
}

/**
 * Check all s9ymdb tagged and inserted images in entry which are in use
 * We need a bulletproof preg_match pattern regex syntax for
 * (<!-- itag:$iid -->) with possible following [<picture>, <source.*>, incl. whitespaces and linebreaks and ??] or [nothing or whitespace] in-between] and a following <img src=["\'](.*)["\'] string
 * [^>]* captures the content of a tag with attributes
 */
function image_inuse($iid, $eid, $im, $entry, $field, $path, $name) {
    $matches = array();
    $pattern = "@(<!-- s9ymdb:$iid -->).*?<img[^>]* src=[\"']([^\"']+)[\"']@"; // either '<img[^>]+ ' or '<img[^>]* ' works
    //---------------------------------.*? matches all, inclusive \s in-between
    preg_match($pattern, $entry[$field], $matches);
#DEBUG    if (!empty($matches)) print_r($matches);

    // Only care about the full s9ymdb ID plus the image src string and check if the path matches to the blog (to avoid possible others from same machine)
    if (!empty($matches[0]) && false !== strpos($matches[0], $path)) {
        $o = strip_to_array($matches[0], $path);
        $o['dbiname'] = $name;
        $o['bsename'] = strtok(basename($o['src']), '.');
        // Until now this task did not have taken care about false set s9ymdb:IDs, which might exist on elder blogs, due to historic issues/bugs, or other path issues.
        // Now (check the ID to match the image DB media ID OR having an unmatching src path against the db path/name value) OR
        //     (check the image name against the basename source name AND to comply with the blogs path (i.e. in cases you have a bunch/block of image(s) with a different path copied by another (local) blog))
        $_image = check_by_image_db($o['s9ymdb']);
        if ((!empty($o['s9ymdb']) && $iid != $o['s9ymdb'] || false === strpos($o['src'], $_image[0]['path'].$_image[0]['name'])) || (!empty($o['src']) && $name != $o['bsename'] && false !== strpos($o['src'], $path))) {
            $o['error'] = sprintf(MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR, $eid, $field, $o['s9ymdb'], $o['src'], $o['bsename']);
            echo '<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <strong>' . ERROR . '</strong>: ' . $o['error'] . "</span>\n";
        }
        $im[$iid][$eid][$field][] = $o;
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
#DEBUG    echo '<pre>'.print_r($images,1).'</pre>';
#DEBUG    echo '<pre>'.print_r($im,1).'</pre>';
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
foreach ($im AS $imk => $imv) {
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
$rkeys = array_keys(array_flip($dkeys));
#DEBUG echo '<pre>'.print_r($rkeys,1).'</pre>';

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
// A paranoid re-check against entries and staticpages
foreach($_images AS $go) {
    // exclude certain entry images which don't match
    if (!empty($go['id'])) {
        $e = serendipity_db_query("SELECT id, title FROM `{$serendipity['dbPrefix']}entries` WHERE body LIKE '%<!-- s9ymdb:{$go['id']} -->%' OR extended LIKE '%<!-- s9ymdb:{$go['id']} -->%'", false, 'assoc');
        if (!empty($e[0])) {
            $alerts[] = '<b>'.$go['name'] .'</b> ('.$go['id'].') in Entry ID: ' . $e[0]['id'].' - '. $e[0]['title'];
            $pckeys[] = $go['id'];
            $exclude .= $go['id'].', ';
        }
        // watch out for imageselectorplus plugin added quickblog entries, eg. '<!--quickblog:[none|plugin|js|_blank]|/var/www/example/htdocs/serendipity/uploads/image.jpeg-->'
        $f = $serendipity['serendipityPath'] . $serendipity['uploadPath'] . $go['path'] . $go['name'] . '.' . $go['extension'];
        $q = serendipity_db_query("SELECT id, title FROM `{$serendipity['dbPrefix']}entries` WHERE body LIKE '%<!--quickblog:%|" . serendipity_db_escape_string($f) . "-->%'", false, 'assoc');
        if (!empty($q[0])) {
            $alerts[] = '<b>'.$go['name'] .'</b> ('.$go['id'].'<em>, special: Imageselectorplus Quickblog image</em>) in Entry ID: ' . $q[0]['id'].' - '. $q[0]['title'];
            $pckeys[] = $go['id'];
            $exclude .= $go['id'].', ';
        }
        $s = serendipity_db_query("SELECT id, pagetitle FROM `{$serendipity['dbPrefix']}staticpages` WHERE content LIKE '%<!-- s9ymdb:{$go['id']} -->%' OR pre_content LIKE '%<!-- s9ymdb:{$go['id']} -->%'", false, 'assoc');
        if (!empty($s[0])) {
            $alerts[] = '<span style="color:blue"><b>'.$go['name'] .'</b> ('.$go['id'].') in Staticpage ID: ' . $s[0]['id'].' - '. $s[0]['pagetitle'].'</span>';
            $pckeys[] = $go['id'];
            $exclude .= $go['id'].', ';
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
        echo '<ul class="plainList">'."\n";
        foreach ($alerts AS $alert) {
            echo '<li>' . $alert."</li>\n";
        }
        echo "</ul>\n</div>\n";
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . sprintf(MLORPHAN_MTASK_PNCASE_EXCLUDING, substr($exclude, 0, -2)) . "</span>\n";
    } else {
        echo '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . MLORPHAN_MTASK_PNCASE_OK . "</span>\n";
    }
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
            <form id="#formMultiSelect" name="formMultiSelect" action="?" method="POST">
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
        <pre id="orphaned_array" class="orphaned_status additional_info">' . print_r($orphaned,1) . '</pre>
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

    // Inverts a selection of checkboxes - overwrites a serendipity_editor.js function
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
#DEBUG echo '<pre>'.print_r($orphaned,1).'</pre>'; // better show this to the user only to not get confused about the keys
#DEBUG echo '<pre>'.print_r($_images,1).'</pre>';

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