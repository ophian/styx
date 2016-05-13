<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

include_once(S9Y_INCLUDE_PATH . "include/functions_trackbacks.inc.php");


/**
 * Prints the form for editing/creating new blog entries
 *
 * This is the core file where your edit form appears. The Heart Of Gold, so to say.
 *
 * @access public
 * @param   string      The URL where the entry form is submitted to
 * @param   array       An array of hidden input fields that should be passed on to the HTML FORM
 * @param   array       The entry superarray with your entry's contents
 * @param   string      Any error messages that might have occured on the last run
 * @return null
 */
function serendipity_printEntryForm($targetURL, $hiddens = array(), $entry = array(), $errMsg = "") {
    global $serendipity;

    $draftD = '';
    $draftP = '';
    $categoryselector_expanded = false;

    $template_vars = array();

    serendipity_plugin_api::hook_event('backend_entryform', $entry);

    if ( (isset($entry['isdraft']) && serendipity_db_bool($entry['isdraft'])) ||
         (!isset($entry['isdraft']) && $serendipity['publishDefault'] == 'draft') ) {
        $draftD = ' selected="selected"';
        $template_vars['draft_mode'] = 'draft';
    } else {
        $draftP = ' selected="selected"';
        $template_vars['draft_mode'] = 'publish';
    }

    if (isset($entry['moderate_comments']) && (serendipity_db_bool($entry['moderate_comments']))) {
        $template_vars['moderate_comments'] = true;
        $moderate_comments = ' checked="checked"';
    } elseif (!isset($entry['moderate_comments']) && ($serendipity['moderateCommentsDefault'] == 'true' || $serendipity['moderateCommentsDefault'] === true)) {
        // This is the default on creation of a new entry and depends on the "moderateCommentsDefault" variable of the configuration.
        $moderate_comments = ' checked="checked"';
        $template_vars['moderate_comments'] = true;
    } else {
        $moderate_comments = '';
        $template_vars['moderate_comments'] = false;
    }

    if (isset($entry['allow_comments']) && (serendipity_db_bool($entry['allow_comments']))) {
        $template_vars['allow_comments'] = true;
        $allow_comments = ' checked="checked"';
    } elseif ((!isset($entry['allow_comments']) || $entry['allow_comments'] !== 'false') && (!isset($serendipity['allowCommentsDefault']) || $serendipity['allowCommentsDefault'] == 'true' || $serendipity['allowCommentsDefault'] === true)) {
        // This is the default on creation of a new entry and depends on the "allowCommentsDefault" variable of the configuration.
        $template_vars['allow_comments'] = true;
        $allow_comments = ' checked="checked"';
    } else {
        $template_vars['allow_comments'] = false;
        $allow_comments = '';
    }

    // Fix category list. If the entryForm is displayed after a POST request, the additional category information is lost.
    if (is_array($entry['categories']) && !is_array($entry['categories'][0])) {
        $categories = (array)$entry['categories'];
        $entry['categories'] = array();
        foreach ($categories as $catid) {
            $entry['categories'][] = serendipity_fetchCategoryInfo($catid);
        }
    }

    $selected = array();
    if (is_array($entry['categories'])) {
        if (count($entry['categories']) > 1) {
            $categoryselector_expanded = true;
        }

        foreach ($entry['categories'] as $cat) {
            $selected[] = $cat['categoryid'];
        }
    }

    if (count($selected) > 1 ||
          (isset($serendipity['POST']['categories']) && is_array($serendipity['POST']['categories']) && sizeof($serendipity['POST']['categories']) > 1)) {
        $categoryselector_expanded = true;
    }

    if (is_array($cats = serendipity_fetchCategories())) {
        $cats = serendipity_walkRecursive($cats, 'categoryid', 'parentid', VIEWMODE_THREADED);
        foreach ($cats as $cat) {

            if (in_array($cat['categoryid'], $selected)) {
                $cat['is_selected'] = true;
            }

            $cat['depth_pad'] = str_repeat('&nbsp;', $cat['depth']);

            $template_vars['category_options'][] = $cat;
        }
    }

    if (!empty($serendipity['GET']['title'])) {
        $entry['title'] = utf8_decode(urldecode($serendipity['GET']['title']));
    }

    if (!empty($serendipity['GET']['body'])) {
        $entry['body'] = utf8_decode(urldecode($serendipity['GET']['body']));
    }

    if (!empty($serendipity['GET']['url'])) {
        $entry['body'] .= "\n" . '<a class="block_level" href="' . serendipity_specialchars(utf8_decode(urldecode($serendipity['GET']['url']))) . '">' . $entry['title'] . '</a>';
    }

    $template_vars['formToken'] = serendipity_setFormToken();

    if (isset($serendipity['allowDateManipulation']) && $serendipity['allowDateManipulation']) {
        $template_vars['allowDateManipulation'] = true;
    }

    if ((!empty($entry['extended']) || !empty($serendipity['COOKIE']['toggle_extended'])) && !$serendipity['wysiwyg']) {
        $template_vars['show_wysiwyg'] = true;
    }

    $template_vars['wysiwyg_advanced'] = true;

    $template_vars['timestamp']               =  serendipity_serverOffsetHour(isset($entry['timestamp']) && $entry['timestamp'] > 0 ? $entry['timestamp'] : time());
    $template_vars['reset_timestamp']         =  serendipity_serverOffsetHour(time());
    $template_vars['hiddens']                 =  $hiddens;
    $template_vars['errMsg']                  =  $errMsg;
    $template_vars['entry']                   =& $entry;
    $template_vars['targetURL']               =  $targetURL;
    $template_vars['cat_count']               =  count($cats)+1;
    $template_vars['wysiwyg']                 =  $serendipity['wysiwyg'];
    $template_vars['serendipityRightPublish'] =  $_SESSION['serendipityRightPublish'];
    $template_vars['wysiwyg_blocks']          =  array(
                                                    'body'      => 'serendipity[body]',
                                                    'extended'  => 'serendipity[extended]'
                                                  );

    $template_vars['entry_template'] = serendipity_getTemplateFile('admin/entries.tpl', 'serendipityPath');

    if (!is_object($serendipity['smarty'])) {
        serendipity_smarty_init();
    }
    $serendipity['smarty']->registerPlugin('modifier', 'emit_htmlarea_code', 'serendipity_emit_htmlarea_code');
    $serendipity['smarty']->assign('admin_view', 'entryform');
    serendipity_plugin_api::hook_event('backend_entryform_smarty', $template_vars);
    $serendipity['smarty']->assignByRef('entry_vars', $template_vars);
    return serendipity_smarty_showTemplate($template_vars['entry_template']);
}

function serendipity_emit_htmlarea_code($item, $jsname, $spawnMulti = false) {
    # init == true when editor was already initialized
    static $init = false;
    global $serendipity;

    if ($init && $spawnMulti) {
        return;
    }

    if (isset($serendipity['wysiwyg']) && $serendipity['wysiwyg']) {

        $eventData = array(
            'init'    => &$init,
            'item'    => &$item,
            'jsname'  => &$jsname,
            'skip'    => false,
            'buttons' => array(),
        );

        serendipity_plugin_api::hook_event('backend_wysiwyg', $eventData);

        if ($eventData['skip']) {
            return;
        }

        $data = array('init' => $init, 'spawnMulti' => $spawnMulti, 'jsname' => $jsname, 'item' => $item, 'buttons' => $eventData['buttons']);
        echo serendipity_smarty_showTemplate('admin/wysiwyg_init.tpl', $data);
    }
    $init = true;

}

/**
 * Sanitize 'private use area' font symbols to unicode / html entities before saving to database
 * Thanks to http://stackoverflow.com/questions/8240030/how-to-convert-symbol-font-to-standard-utf8-html-entity
 *
 * @see     https://github.com/s9y/Serendipity/issues/394
 * @param   string  $string ($entry[body] && entry[extended])
 * @return  string  $string ($entry[body] && entry[extended])
 */
function symbol_sanitize_string($string) {
    // replace font symbols
    $string =  preg_replace_callback('/&#(61\d+?);/i', 'symbol_alone2utf8', $string);
    return $string;
}

function symbol_alone2utf8( $match ){
    return symbol2utf8( $match[1] );
}

function symbol2utf8( $decimal ) {
    $_Symbol = array(
        61472 => '020',
        61473 => '021',
        61474 => '022',
        61475 => '023',
        61476 => '024',
        61477 => '025',
        61478 => '026',
        61479 => '027',
        61480 => '028',
        61481 => '029',
        61482 => '02A',
        61483 => '02B',
        61484 => '02C',
        61485 => '02D',
        61486 => '02E',
        61487 => '02F',
        61488 => '030',
        61489 => '031',
        61490 => '032',
        61491 => '033',
        61492 => '034',
        61493 => '035',
        61494 => '036',
        61495 => '037',
        61496 => '038',
        61497 => '039',
        61498 => '03A',
        61499 => '03B',
        61500 => '03C',
        61501 => '03D',
        61502 => '03E',
        61503 => '03F',
        61504 => '040',
        61505 => '041',
        61506 => '042',
        61507 => '043',
        61508 => '044',
        61509 => '045',
        61510 => '046',
        61511 => '047',
        61512 => '048',
        61513 => '049',
        61514 => '04A',
        61515 => '04B',
        61516 => '04C',
        61517 => '04D',
        61518 => '04E',
        61519 => '04F',
        61520 => '050',
        61521 => '051',
        61522 => '052',
        61523 => '053',
        61524 => '054',
        61525 => '055',
        61526 => '056',
        61527 => '057',
        61528 => '058',
        61529 => '059',
        61530 => '05A',
        61531 => '05B',
        61532 => '05C',
        61533 => '05D',
        61534 => '05E',
        61535 => '05F',
        61536 => '060',
        61537 => '061',
        61538 => '062',
        61539 => '063',
        61540 => '064',
        61541 => '065',
        61542 => '066',
        61543 => '067',
        61544 => '068',
        61545 => '069',
        61546 => '06A',
        61547 => '06B',
        61548 => '06C',
        61549 => '06D',
        61550 => '06E',
        61551 => '06F',
        61552 => '070',
        61553 => '071',
        61554 => '072',
        61555 => '073',
        61556 => '074',
        61557 => '075',
        61558 => '076',
        61559 => '077',
        61560 => '078',
        61561 => '079',
        61562 => '07A',
        61563 => '07B',
        61564 => '07C',
        61565 => '07D',
        61566 => '07E',
        61601 => '0A1',
        61602 => '0A2',
        61603 => '0A3',
        61604 => '0A4',
        61605 => '0A5',
        61606 => '0A6',
        61607 => '0A7',
        61608 => '0A8',
        61609 => '0A9',
        61610 => '0AA',
        61611 => '0AB',
        61612 => '0AC',
        61613 => '0AD',
        61614 => '0AE',
        61615 => '0AF',
        61616 => '0B0',
        61617 => '0B1',
        61618 => '0B2',
        61619 => '0B3',
        61620 => '0B4',
        61621 => '0B5',
        61622 => '0B6',
        61623 => '0B7',
        61624 => '0B8',
        61625 => '0B9',
        61626 => '0BA',
        61627 => '0BB',
        61628 => '0BC',
        61629 => '0BD',
        61630 => '0BE',
        61631 => '0BF',
        61632 => '0C0',
        61633 => '0C1',
        61634 => '0C2',
        61635 => '0C3',
        61636 => '0C4',
        61637 => '0C5',
        61638 => '0C6',
        61639 => '0C7',
        61640 => '0C8',
        61641 => '0C9',
        61642 => '0CA',
        61643 => '0CB',
        61644 => '0CC',
        61645 => '0CD',
        61646 => '0CE',
        61647 => '0CF',
        61648 => '0D0',
        61649 => '0D1',
        61650 => '0D2',
        61651 => '0D3',
        61652 => '0D4',
        61653 => '0D5',
        61654 => '0D6',
        61655 => '0D7',
        61656 => '0D8',
        61657 => '0D9',
        61658 => '0DA',
        61659 => '0DB',
        61660 => '0DC',
        61661 => '0DD',
        61662 => '0DE',
        61663 => '0DF',
        61664 => '0E0',
        61665 => '0E1',
        61666 => '0E2',
        61667 => '0E3',
        61668 => '0E4',
        61669 => '0E5',
        61670 => '0E6',
        61671 => '0E7',
        61672 => '0E8',
        61673 => '0E9',
        61674 => '0EA',
        61675 => '0EB',
        61676 => '0EC',
        61677 => '0ED',
        61678 => '0EE',
        61679 => '0EF',
        61681 => '0F1',
        61682 => '0F2',
        61683 => '0F3',
        61684 => '0F4',
        61685 => '0F5',
        61686 => '0F6',
        61687 => '0F7',
        61688 => '0F8',
        61689 => '0F9',
        61690 => '0FA',
        61691 => '0FB',
        61692 => '0FC',
        61693 => '0FD',
        61694 => '0FE'
    );
    $key = $decimal;
    if ( array_key_exists( $key, $_Symbol ) ) {

        if( $key <= 61487 ) {
            $c = '0';
        } else {
            $c = 'f';
        }
        $char = json_decode( '"\u' . $c . $_Symbol[ $key ] . '"');

        return $char;
    } else {
        return "&#$decimal;";
    }
}
