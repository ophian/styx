<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

include('serendipity_config.inc.php');

if ($_REQUEST['cid'] != '' && $HTTP_RAW_POST_DATA != '') {
    $comment = array();

    if (!preg_match('@<author[^>]*>(.*)</author[^>]*>@i', $HTTP_RAW_POST_DATA, $name)) {
        preg_match('@<dc:creator[^>]*>(.*)</dc:creator[^>]*>@i', $HTTP_RAW_POST_DATA, $name);
    }

    // this means API comments, which probably are trackbacks, are meant to be ISO-8859-1 7bit only ??

    if (isset($name[1]) && !empty($name[1])) {
        if (preg_match('@^(.*)\((.*)\)@i', $name[1], $names)) {
            $comment['name'] = mb_convert_encoding($names[2], 'ISO-8859-1', 'UTF-8'); // string, to, from
            $comment['name'] = mb_convert_encoding($names[1], 'ISO-8859-1', 'UTF-8'); // string, to, from
        } else {
            $comment['name'] = mb_convert_encoding($name[1], 'ISO-8859-1', 'UTF-8'); // string, to, from
        }
    }

    if (preg_match('@<link[^>]*>(.*)</link[^>]*>@i', $HTTP_RAW_POST_DATA, $link)) {
        $comment['url'] = mb_convert_encoding($link[1], 'ISO-8859-1', 'UTF-8'); // string, to, from
    }

    if (preg_match('@<description[^>]*>(.*)</description[^>]*>@ims', $HTTP_RAW_POST_DATA, $description)) {
        if (preg_match('@^<!\[CDATA\[(.*)\]\]>@ims', $description[1], $cdata)) {
            $comment['comment'] = mb_convert_encoding($cdata[1], 'ISO-8859-1', 'UTF-8'); // string, to, from
        } else {
            $comment['comment'] = mb_convert_encoding($description[1], 'ISO-8859-1', 'UTF-8'); // string, to, from
        }

        if (!empty($comment['comment'])) {
            serendipity_saveComment($_REQUEST['cid'], $comment, 'NORMAL', 'API');
        }
    }
}

?>