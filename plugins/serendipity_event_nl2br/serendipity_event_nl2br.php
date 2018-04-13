<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_nl2br extends serendipity_event
{
    var $title = PLUGIN_EVENT_NL2BR_NAME;

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',          PLUGIN_EVENT_NL2BR_NAME);
        $propbag->add('description',   PLUGIN_EVENT_NL2BR_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Serendipity Team');
        $propbag->add('version',       '2.30');
        $propbag->add('requirements',  array(
            'serendipity' => '2.0',
            'smarty'      => '3.1.0',
            'php'         => '5.2.0'
        ));
        $propbag->add('cachable_events', array('frontend_display' => true));

        $propbag->add('event_hooks',     array('frontend_display'  => true,
                                               'backend_configure' => true,
                                               'css_backend'       => true,
                                               'css'               => true
                     ));
        $propbag->add('groups', array('MARKUP'));

        $this->markup_elements = array(
            array(
              'name'     => 'ENTRY_BODY',
              'element'  => 'body',
            ),
            array(
              'name'     => 'EXTENDED_BODY',
              'element'  => 'extended',
            ),
            array(
              'name'     => 'COMMENT',
              'element'  => 'comment',
            ),
            array(
              'name'     => 'HTML_NUGGET',
              'element'  => 'html_nugget',
            )
        );

        $conf_array = array('check_markup');
        foreach($this->markup_elements AS $element) {
            $conf_array[] = $element['name'];
        }
        $conf_array[] = 'separator';
        $conf_array[] = 'isolate';
        $conf_array = array_merge($conf_array, array('separator1', 'isobr', 'clean_tags', 'separator2', 'p_tags'));
        $propbag->add('configuration', $conf_array);
    }

    function cleanup()
    {
        global $serendipity;

        /* check possible config mismatch setting in combination with ISOBR */
        if ( serendipity_db_bool($this->get_config('isobr', 'true')) === true ) {
            if ( serendipity_db_bool($this->get_config('clean_tags', 'false')) === true ) {
                $this->set_config('clean_tags', 'false');
                echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . sprintf(PLUGIN_EVENT_NL2BR_CONFIG_ERROR, 'clean_tags', 'ISOBR') . "</span>\n";
                return false;
            }
            if ( serendipity_db_bool($this->get_config('p_tags', 'false')) === true ) {
                $this->set_config('p_tags', 'false');
                echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . sprintf(PLUGIN_EVENT_NL2BR_CONFIG_ERROR, 'p_tags', 'ISOBR') . "</span>\n";
                return false;
            }
        }
        /* check possible config mismatch setting in combination with P_TAGS */
        if ( serendipity_db_bool($this->get_config('p_tags', 'false')) === true && serendipity_db_bool($this->get_config('clean_tags', 'false')) === true ) {
            $this->set_config('clean_tags', 'false');
                echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . sprintf(PLUGIN_EVENT_NL2BR_CONFIG_ERROR, 'clean_tags', 'P_TAGS') . "</span>\n";
            return false;
        }
        return true;
    }

    function example()
    {
        return '<h3>' . PLUGIN_EVENT_NL2BR_ABOUT_TITLE . '</h3>' .
        '<span class="msg_notice">' . PLUGIN_EVENT_NL2BR_ABOUT_DESC ."</span>\n";
    }

    function install()
    {
        serendipity_plugin_api::hook_event('backend_cache_entries', $this->title);
    }

    function uninstall(&$propbag)
    {
        serendipity_plugin_api::hook_event('backend_cache_purge', $this->title);
        serendipity_plugin_api::hook_event('backend_cache_entries', $this->title);
    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'check_markup':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_NL2BR_CHECK_MARKUP);
                $propbag->add('description', PLUGIN_EVENT_NL2BR_CHECK_MARKUP_DESC);
                $propbag->add('default',     'true');
                break;

            case 'separator2':
            case 'separator1':
            case 'separator':
                $propbag->add('type',        'separator');
                break;

            case 'isolate':
                $propbag->add('type',        'string');
                $propbag->add('name',        PLUGIN_EVENT_NL2BR_ISOLATE_TAGS);
                $propbag->add('description', PLUGIN_EVENT_NL2BR_ISOLATE_TAGS_DESC);
                $propbag->add('default',     'pre');
                break;

            case 'p_tags':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_NL2BR_PTAGS);
                $propbag->add('description', PLUGIN_EVENT_NL2BR_PTAGS_DESC . ' ' . PLUGIN_EVENT_NL2BR_PTAGS_DESC2);
                $propbag->add('default',     'false');
                break;

            case 'isobr':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_NL2BR_ISOBR_TAG);
                $propbag->add('description', PLUGIN_EVENT_NL2BR_ISOBR_TAG_DESC);
                $propbag->add('default',     'true');
                break;

            case 'clean_tags':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        PLUGIN_EVENT_NL2BR_CLEANTAGS);
                $propbag->add('description', PLUGIN_EVENT_NL2BR_CLEANTAGS_DESC);
                $propbag->add('default',     'false');
                break;

            default:
                $propbag->add('type',        'boolean');
                $propbag->add('name',        constant($name));
                $propbag->add('description', sprintf(APPLY_MARKUP_TO, constant($name)));
                $propbag->add('default',     'true');
                break;
        }
        return true;
    }

    function isolate($src, $regexp = NULL)
    {
        if ($regexp) {
            return preg_replace_callback($regexp, array($this, 'isolate'), $src);
        }
        global $_buf;
        $_buf[] = $src[0];
        return "\001" . (count($_buf) - 1);
    }

    function restore_callback($matches)
    {
        global $_buf;
        return $_buf[$matches[1]];
    }

    function restore($text)
    {
        return preg_replace_callback('!\001(\d+)!', array($this, 'restore_callback'), $text); // works?!
    }

    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;
        static $markup  = null;
        static $isolate = null;
        static $p_tags  = null;
        static $isobr   = null;
        static $clean_tags  = null;
        global $_buf;

        $hooks = &$bag->get('event_hooks');

        if ($markup === null) {
            $markup = serendipity_db_bool($this->get_config('check_markup'));
        }

        if ($p_tags === null) {
            $p_tags = serendipity_db_bool($this->get_config('p_tags'));
        }

        if ($isobr === null) {
            $isobr = serendipity_db_bool($this->get_config('isobr'));
        }

        if ($clean_tags === null) {
            $clean_tags = serendipity_db_bool($this->get_config('clean_tags'));
        }

        if (isset($hooks[$event])) {

            switch($event) {

                case 'frontend_display':

                    // check single entry for temporary disabled markups
                    if ( !$eventData['properties']['ep_disable_markup_' . $this->instance] &&
                         @!in_array($this->instance, $serendipity['POST']['properties']['disable_markups']) &&
                         !$eventData['properties']['ep_no_textile'] && !isset($serendipity['POST']['properties']['ep_no_textile']) &&
                         !$eventData['properties']['ep_no_markdown'] && !isset($serendipity['POST']['properties']['ep_no_markdown'])) {
                        // yes, this markup shall be applied
                        $serendipity['nl2br']['entry_disabled_markup'] = false;
                    } else {
                        // no, do not apply markup
                        $serendipity['nl2br']['entry_disabled_markup'] = true;
                    }

/* PLEASE NOTE:
    $serendipity['POST']['properties']['disable_markups'] = array(false);
    is the only workable solution for (sidebar?) plugins (see sidebar plugins: guestbook, multilingual),
    to explicitly allow to apply nl2br to markup (if we want to)
*/
                    // don't run, if the textile, or markdown plugin already took care about markup
                    if ($markup && $serendipity['nl2br']['entry_disabled_markup'] === false && (class_exists('serendipity_event_textile') || class_exists('serendipity_event_markdown'))) {
                        break;
                    }
                    // NOTE: the wysiwyg-editor needs to send its own ['properties']['ep_no_nl2br'] to disable the nl2br() parser!

                    // check for users isolation tags
                    if ($isolate === null) {
                        $isolate = $this->get_config('isolate');
                        $tags    = (array)explode(',', $isolate);
                        $isolate = array();
                        foreach($tags AS $tag) {
                            $tag = trim($tag);
                            if (!empty($tag)) {
                                $isolate[] = $tag;
                            }
                        }
                        if (count($isolate) < 1) {
                            $isolate = false;
                        }
                    }

                    foreach ($this->markup_elements AS $temp) {
                        if (serendipity_db_bool($this->get_config($temp['name'], 'true')) && isset($eventData[$temp['element']]) &&
                                !$eventData['properties']['ep_disable_markup_' . $this->instance] &&
                                @!in_array($this->instance, $serendipity['POST']['properties']['disable_markups']) &&
                                !$eventData['properties']['ep_no_nl2br'] &&
                                !isset($serendipity['POST']['properties']['ep_no_nl2br'])) {

                            $element = $temp['element'];
                            if ($p_tags) {
                                // NL2P OPERATION
                                $this->isolationtags = $isolate;

                                $text = $eventData[$element];
                                if (!empty($text)) {
                                    // Standard Unix line endings
                                    $text = str_replace(array("\r\n", "\r"), "\n", $text);
                                    // move newlines from body to extended
                                    if ($element == 'body' && isset($eventData['extended'])) {
                                        $eventData['extended'] = str_repeat("\n", strspn($text, "\n", -1)) . $eventData['extended'];
                                        $text = rtrim($text,"\n");
                                    }
                                    $eventData[$element] = $this->nl2p($text);
                                }
                                // NL2BR OPERATION
                            } else if ($isolate) {
                                $eventData[$element] = $this->isolate($eventData[$element], '~[<\[](' . implode('|', $isolate) . ').*?[>\]].*?[<\[]/\1[>\]]~si');
                                $eventData[$element] = nl2br($eventData[$element]);
                                $eventData[$element] = $this->restore($eventData[$element]);
                            } else {
                                if ($isobr) {
                                    $eventData[$element] = $this->isolate($eventData[$element], '~[<\[](nl).*?[>\]].*?[<\[]/\1[>\]]~si');
                                    $eventData[$element] = nl2br($eventData[$element]);
                                    $eventData[$element] = $this->restore($eventData[$element]);
                                    // unset nl tagline, if is
                                    $eventData[$element] = str_replace(array("<nl>", "</nl><br />", "</nl><br/>", "</nl>"), "", $eventData[$element]);
                                } else {
                                    $eventData[$element] = nl2br($eventData[$element]);
                                }
                            }
                            /* this is an option if not using new isobr default config setting */
                            if (!$p_tags && $isobr === false && $clean_tags === true) {
                                // convert line endings to Unix style, if not already done
                                $eventData[$element] = str_replace(array("\r\n", "\r"), "\n", $eventData[$element]);
                                // clean special tags from nl2br
                                $eventData[$element] = $this->clean_nl2brtags($eventData[$element]);
                            }
                        }
                    }
                    break;

                case 'backend_configure':

                    // check single entry for temporary disabled markups
                    if ($isobr) {
                        $serendipity['nl2br']['iso2br'] = true; // include to global since also used by staticpages now

                        if (!is_object($serendipity['smarty'])) {
                            serendipity_smarty_init(); // if not set to avoid member function assign() on a non-object error, start Smarty templating
                        }

                        // hook into default/admin/entries.tpl somehow via the Heart Of Gold = serendipity_printEntryForm() before! it is loaded
                        $serendipity['smarty']->assign('iso2br', true);
                    }
                    break;

                case 'css_backend':
                    $eventData .= '

/* nl2br plugin start */

#clean_tags_info {
    width: 100%;
    margin-bottom: 2.25em;
    word-break: break-all; /*since using a non breakable too long word*/
}

/* nl2br plugin end */

';
                    break;

                case 'css':
                    $eventData .= '

/* nl2br plugin start */

p.whiteline, /* keep whiteline for compat */
p.wl_bottom {
    margin-top: 0em;
    margin-bottom: 1em;
}
p.wl_top {
    margin-top: 1em;
    margin-bottom: 0em;
}
p.wl_top_bottom {
    margin-top: 1em;
    margin-bottom: 1em;
}
p.break {
    margin-top: 0em;
    margin-bottom: 0em;
}

/* nl2br plugin end */

';
                    break;

                default:
                    return false;

            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * clean nl2br from markup where it is invalid and/or breaks html output
     * @param  string entrytext
     * @return string
     */
    function clean_nl2brtags(&$entry)
    {
        $allTags = explode('|', 'table|thead|tbody|tfoot|th|tr|td|caption|colgroup|col|ol|ul|li|dl|dt|dd');

        $br2nl = array();

        foreach($allTags AS $tag){
            /* for \\1 ( start with : < followed by any number of white spaces : \s* optionally a slash : /? and the tag itself )
             * for \\2 ( anything with spaces and characters following until )
             * for \\3 ( finally the > )
             * for \\4 ( <br followed by any number of spaces, the optional slash and ending with > )
             * regex modifier : i - using a case-insensitive match, as upper <TAGS> are valid in HTML
             * regex modifier : s - using the dot metacharacter in the pattern to match all characters, including newlines */
            $br2nl[] = "%(<\s*/?$tag)(.*?)([^>]*>)(<br\s*/?>)%is";
        }

        if (sizeof($br2nl)) $entry = preg_replace($br2nl, '\\1\\2\\3', $entry);

        return $entry;
    }

    /** ====================================
     *         NL2P OPERATION
     *  ====================================
     */
    // following w3.org, these elements close p elements automatically:
    var $block_elements = array('table',
                                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                                'menu', 'section',
                                'address', 'article', 'aside', 'fieldset', 'footer',
                                'form', 'header', 'hgroup', 'hr', 'main', 'nav', 'p');
    var $nested_block_elements = array('div','table','blockquote','ul','ol','dl');

    var $singleton_block_elements = array('hr');

    var $isolation_block_elements = array('pre','textarea');

    var $isolation_inline_elements = array('svg');

    var $ignored_elements = array('area', 'br', 'col', 'command', 'embed',
                                'img', 'input', 'keygen', 'link', 'param', 'source',
                                'track', 'wbr', '!--', 'iframe',
                                'li','tr','th','col','colgroup',
                                'thead', 'tbody', 'tfoot', 'caption', 'ins','del',
                                'video','audio','title','desc','path','circle',
                                'ellipse', 'rect', 'line', 'polyline', 'polygon', 'text',
                                'image', 'g', 'defs'); //includes svg tags
    // paragraphs aren't allowed in these inline elements -> p closes these elements:
    var $inline_elements = array('b', 'big', 'i', 'small', 'tt', 'abbr',
                                'acronym', 'cite', 'code', 'dfn', 'em', 'kbd', 'strong',
                                'samp', 'var', 'a', 'bdo', 'bdi', 'map', 'object',
                                'q', 'script', 'span', 'sub', 'sup', 'button',
                                'label', 'select', 'textarea', 's');
    var $allowed_p_parents = array('blockquote', 'td', 'div', 'article', 'aside', 'dd',
                                'details', 'dl', 'dt', 'footer', 'header', 'summary');
    const P_TOP = '<p class="wl_top">';
    const P_BOTTOM = '<p class="wl_bottom">';
    const P_TOP_BOTTOM = '<p class="wl_top_bottom">';
    const P_BREAK = '<p class="break">';
    const P_END = '</p>';

    /**
     * Prepare text tags to p-tags
     *
     * @param string text
     * @return string
     */
    function nl2p($text)
    {
        // homogenize tags
        $text = $this->tag_clean($text);

        // delete isolation tags from other arrays
        if ($this->isolationtags) {
            $this->block_elements           = array_diff($this->block_elements,$this->isolationtags);
            $this->allowed_p_parents        = array_diff($this->allowed_p_parents,$this->isolationtags);
            $this->nested_block_elements    = array_diff($this->nested_block_elements,$this->isolationtags);
            $this->inline_elements          = array_diff($this->inline_elements,$this->isolationtags);
            $this->singleton_block_elements = array_diff($this->singleton_block_elements,$this->isolationtags);
            $this->ignored_elements         = array_diff($this->ignored_elements,$this->isolationtags);
            $this->isolation_block_elements = array_merge($this->isolationtags,$this->isolation_block_elements);
            $this->isolationtags            = array();
        }
        if (empty($text)) {
            return '';
        }
        return $this->blocktag_nl2p($text);
    }

    /**
     * Make sure that all the tags are in lowercase
     * purge all \n from inside tags
     * remove spaces in endtags
     *
     * @param string text
     * @return text
     */
    function tag_clean($textstring)
    {
        $text       = str_split($textstring);
        $tagstart   = false;
        $tagstart_b = false;
        $tagdef     = false;
        $endtag     = false;
        $tagstyle   = false;
        for ($i=0; $i<count($text); $i++) {
            if ($text[$i] == '<' && !strpos($textstring,'>',$i+1)) {
                $text[$i] = '&lt;';
            } else
            if ($text[$i] == '>' && !($tagstart !== false || $tagdef || $tagstyle)) {
                $text[$i] = '&gt;';
            } else
            if ($text[$i] == '<') {
                $tagstart = $i;
            } else
            if ($text[$i] == ' ' && $tagstart !== false) {
                $text[$tagstart] = '&lt;';
                $tagstart = false;
            } else
            if ($text[$i] == '>' && $tagstart !== false) {
                $text[$tagstart] = '&lt;';
                $text[$i] = '&gt;';
            } else
            if (($text[$i] == ' ' || $text[$i] == '>') && $tagdef) {
                // check if it is a real tag
                $tag = substr($textstring, $tagdef, $i-$tagdef);

                if ( !(in_array($tag,$this->block_elements)
                    || in_array($tag,$this->singleton_block_elements)
                    || in_array($tag,$this->inline_elements)
                    || in_array($tag,$this->allowed_p_parents)
                    || in_array($tag,$this->isolation_block_elements)
                    || in_array($tag,$this->isolation_inline_elements)
                    || in_array($tag,$this->nested_block_elements)
                    || in_array($tag,$this->ignored_elements) ))
                {
                    $text[$tagstart_b] = '&lt;';
                    $text[strpos($textstring,'>',$i)] = '&gt;';
                } else {
                    $tagstyle = true;
                    $tagdef   = false;
                }
                if ($text[$i] == '>') {
                    $tagstart = false;
                    $tagdef   = false;
                    $tagstyle = false;
                    $endtag   = false;
                }
            } else
            if ($text[$i] == '/' && $tagstart !== false) {
                $endtag = true;
            } else
            if ($text[$i] == ' ' && $endtag) {
                $text[$i] = '';
            } else
            if (($tagstart !== false || $tagdef || $tagstyle) && $text[$i] == "\n") {
                $text[$i] = '';
            } else
            if ($text[$i] == '>' && ($tagdef || $tagstyle)) {
                $tagstart = false;
                $tagdef   = false;
                $tagstyle = false;
                $endtag   = false;
            } else
            if ($tagstart !== false) {
                $tagdef = $i;
                $tagstart_b = $tagstart;
                $tagstart = false;
                $text[$i] = strtolower($text[$i]);
            } else
            if ($tagdef) {
                $text[$i] = strtolower($text[$i]);
            }
        }
        return implode($text);
    }

    /*
     * Sophisticated nl to p - blocktag stage
     * handles content with blocktags, apply nl2p to the block elements if tag allows it
     * works also for ommitted closing tags and singleton tags
     *
     * @param: text
     * return string
     */
    function blocktag_nl2p($text)
    {
        // explode string into array of tags and contents
        $textarray = $this->explode_along_tags($text);
        $content = "";
        $start = 0;
        $tagstack = array();
        $isolation_flag = false;
        for ($i=0; $i<count($textarray); $i++) {
            // get tag, or false if none
            $tag = $this->extract_tag($textarray[$i]);
            // new blocktag - e.g. <table>
            if ($tag && $this->is_starttag($textarray[$i])
                && (in_array($tag, $this->block_elements) || in_array($tag, $this->nested_block_elements) ))
            {
                // merge previous content, apply nl2p if needed and concatenate
                if (!$isolation_flag && ( empty($tagstack) || in_array($tagstack[0], $this->allowed_p_parents))) {
                    $content .= $this->nl2pblock(implode(array_slice($textarray, $start, $i-$start)));
                } else {
                    $content .= implode(array_slice($textarray, $start, $i-$start));
                }
                // clear stack of block elements and insert
                if (in_array($tag, $this->block_elements)) {
                    $tagstack = array_diff($tagstack, $this->block_elements);
                }
                // concatenate tag
                $content .= $textarray[$i];

                if (!in_array($tag, $this->singleton_block_elements)) {
                    array_unshift($tagstack, $tag);
                }
                $start = $i+1;
            }
            // new tag which can contain paragraphs and can be inside a blocktag - e.g. <td>
            elseif ($tag && $this->is_starttag($textarray[$i]) && in_array($tag, $this->allowed_p_parents)) {
                // merge previous content, apply nl2p if needed and concatenate
                if (!$isolation_flag && ( empty($tagstack) || in_array($tagstack[0], $this->allowed_p_parents))) {
                    $content .= $this->nl2pblock(implode(array_slice($textarray, $start, $i-$start)));
                } else {
                    $content .= implode(array_slice($textarray, $start, $i-$start));
                }
                // insert tag into the stack and concatenate
                array_unshift($tagstack, $tag);
                $content .= $textarray[$i];
                $start = $i+1;
            }
            // isolation tag
            elseif($tag && !$isolation_flag && $this->is_starttag($textarray[$i]) && in_array($tag, $this->isolation_block_elements)) {
                // merge previous content, apply nl2p if needed and concatenate
                if (empty($tagstack)) {
                    $content .= $this->nl2pblock(implode(array_slice($textarray, $start, $i-$start)));
                } else
                if (in_array($tagstack[0], $this->allowed_p_parents)) {
                    $content .= $textarray[$start]
                             . $this->nl2pblock(implode(array_slice($textarray, $start+1, $i-$start-1)));
                } else {
                    $content .= implode(array_slice($textarray, $start, $i-$start));
                }
                $isolation_flag = $tag;    // isolation has to be started and ended with the same tag
                $start = $i+1;
            }
            // closing isolation tag
            elseif($tag && !$this->is_starttag($textarray[$i]) && $tag == $isolation_flag) {
                //content, no nl2p
                $content .= implode(array_slice($textarray,$start,$i-$start));
                $isolation_flag = false;
                $start = $i+1;
            }
            // closing blocktag or p parent - e.g. </table> or </td>
            elseif($tag && !$this->is_starttag($textarray[$i]) && !empty($tagstack) && $tag == $tagstack[0]) {
                // content, apply nl2p if needed
                if ($i != $start) {
                    if (!$isolation_flag && in_array($tagstack[0], $this->allowed_p_parents)) {
                        $content .= $this->nl2pblock(implode(array_slice($textarray, $start, $i-$start)));
                    } else {
                        $content .= implode(array_slice($textarray, $start, $i-$start));
                    }
                }
                // closing tag
                $content .= $textarray[$i];
                $start = $i+1;
                array_shift($tagstack);
            }
        }
        // merge remainder
        if (!$isolation_flag && ( empty($tagstack) || in_array($tagstack[0], $this->allowed_p_parents))) {
            $content .= $this->nl2pblock(implode(array_slice($textarray,$start,$i-$start)));
        } else {
            $content .= implode(array_slice($textarray,$start,$i-$start));
        }
        return $content;
    }

    /*
     * Sophisticated nl to p for content which is already
     * purged from block elements by blocktag_nl2p
     * explode content along \n
     * check for following \n
     * explode along (inline) tags, get active tags across newlines
     * build every paragraph: p class | reopen active tags | content ... | new open tags | closing p tag
     * for content which is not isolated by inline isolation tags like svg
     * Insert P_BOTTOM class at paragraphs ending with two newlines
     * Insert P_BREAK class at paragraphs ending with one newline
     * Insert P_TOP class at the first paragraph if starting with a nl
     * Insert P_TOP_BOTTOM class if the first paragraph is ending with two newlines
     *
     * @param string text
     * @return string
    */
    function nl2pblock($textstring)
    {
        // check for empty content
        if (empty(trim($textstring))) {
            return $textstring;
        }

        // check for start/end newlines
        $startnl    = strspn($textstring,"\n") ? true : false;
        $endnl      = strspn($textstring,"\n", -1) ? true : false;
        $whiteline  = false;
        $textstring = trim($textstring, "\n");

        if (empty($textstring)) {
            return '';
        }

        // explode in paragraphs
        $textline      = explode("\n", $textstring);
        $tagstack      = array();
        $tagstack_prev = array();
        $tagexplode    = array();
        $content       = '';
        $isolation_tag = false;

        for ($i=0; $i<count($textline); $i++) {
            // explode in tags and content
            $tagexplode = $this->explode_along_tags($textline[$i]);
            // save active tags
            $tagstack_prev = $tagstack;
            // iterate through the tags in the paragraph
            for ($j=0; $j<count($tagexplode); $j++) {
                // get tag, or false if none
                $tag = $this->extract_tag($tagexplode[$j]);
                // put, or remove tag from stack
                if (isset($tag) && $this->is_starttag($tagexplode[$j]) && !in_array($tag, $this->isolation_inline_elements)) {
                    $isolation_tag = $tag;
                }
                elseif (isset($tag) && !$this->is_starttag($tagexplode[$j]) && $tag == $isolation_tag) {
                    $isolation_tag = false;
                }
                elseif (isset($tag) && !$isolation_tag && $this->is_starttag($tagexplode[$j]) && in_array($tag,$this->inline_elements)) {
                    array_unshift($tagstack, $tagexplode[$j]);
                }
                elseif (isset($tag) && !$this->is_starttag($tagexplode[$j]) && !empty($tagstack) && $tag == $this->extract_tag($tagstack[0])) {
                    array_shift($tagstack);
                }
            }

            // concatenate if lines are isolated
            if ($isolation_tag && $i < count($textline)-1) {
                $textline[$i+1] = $textline[$i] . "\n" . $textline[$i+1];
                continue;
            }
            elseif ($isolation_tag && $i == count($textline)-1) {
                $textline[$i] .= $this->html_end_tag($this->extract_tag($isolation_tag));
            }
            // check for whiteline
            if ($i < count($textline) - 1 && empty($textline[$i+1])) {
                $whiteline = true;
            }
            elseif (empty($textline[$i])) {
                continue;
            }

            // build content
            // paragraph class
            if ($i == 0 && $startnl && ( $whiteline || ($i == count($textline)-1 && $endnl))) {
                $content .=  self::P_TOP_BOTTOM;
            } elseif ($i == 0 && $startnl) {
                $content .= self::P_TOP;
            } elseif ($whiteline || ($i == count($textline)-1 && $endnl)) {
                $content .= self::P_BOTTOM;
            } else {
                $content .= self::P_BREAK;
            }
            // reopen active tags
            foreach($tagstack_prev AS $ins_tag) {
                $content .= $ins_tag;
            }
            // content paragraph
            $content .= $textline[$i];
            // close open tags
            foreach($tagstack AS $ins_tag) {
                $content .= $this->html_end_tag($this->extract_tag($ins_tag));
            }
            // paragraph closing tag
            $content .= self::P_END . "\n";
            $whiteline = false;
        }
        return $content;
    }

    /**
     * Explode textstring into array of substrings
     * array element can be tag or content
     *
     * @param text
     * $return array of tags and contents
     */
    function explode_along_tags($text)
    {
        $startpos = 0;
        $endpos   = 0;
        $textarray = array();
        do {
            // find tag start
            $endpos = strpos($text, '<', $startpos);
            if ($endpos === false) {
                // no more tags, copy remainder to array
                $endpos = strlen($text);
                if ($endpos - $startpos > 0) {
                    $textarray[] = substr($text, $startpos, $endpos - $startpos);
                }
                return $textarray;
            } elseif (($endpos - $startpos) > 0) {
                // copy preliminary text to array
                $textarray[] = substr($text, $startpos, $endpos - $startpos);
            }
            $startpos = $endpos;
            // find tag end
            $endpos = strpos($text, '>', $startpos);
            if ($endpos === false) {
                return false;
            } else
            if (($endpos - $startpos) > 1) {
                // copy tag to array
                $textarray[] = substr($text, $startpos, $endpos - $startpos + 1);
                $startpos = $endpos + 1;
            } else {
                return false;
            }
        } while (1);
        return false;
    }

    /**
     * p-tag helper extract tag
     */
    function extract_tag($text)
    {
        if ($text[0] != '<') {
            return false;
        }
        $n = strcspn($text, ' >');
        return ltrim(substr($text, 0, $n), '</');
    }

    /**
     * p-tag helper add endtag
     */
    function html_end_tag($text)
    {
        return !empty($text) ? '</' . $text . '>' : '';
    }

    /**
     * p-tag helper add starttag
     */
    function html_start_tag($text)
    {
        return !empty($text) ? '<' . $text . '>' : '';
    }

    /**
     * p-tag helper check starttag
     */
    function is_starttag($text)
    {
        return $text[1] == "/" ? false : true;
    }

}

/* vim: set sts=4 ts=4 expandtab : */
?>