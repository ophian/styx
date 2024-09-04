<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_authors extends serendipity_plugin
{
    public $title = AUTHORS;
    private const XML_IMAGE_AVAILABLE = " Available pure theme defaults: 'img/xml.gif' (orange), 'img/xml12.png' (lightblue 12px), 'img/xml16.png' (lightblue 16px), 'icons/rss.svg' (colored by CSS)";

    function introspect(&$propbag)
    {
        global $serendipity;

        $propbag->add('name',        AUTHORS);
        $propbag->add('description', AUTHOR_PLUGIN_DESC);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team, Ian Styx');
        $propbag->add('version',       '2.6.0');
        $propbag->add('configuration', array('image', 'allow_select', 'title', 'showartcount', 'mincount'));
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {

            case 'title':
                $propbag->add('type',          'string');
                $propbag->add('name',          TITLE);
                $propbag->add('default',       AUTHORS);
                break;

            case 'allow_select':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         AUTHORS_ALLOW_SELECT);
                $propbag->add('description',  AUTHORS_ALLOW_SELECT_DESC);
                $propbag->add('default',      'true');
                break;

            case 'image':
                $propbag->add('type',         'string');
                $propbag->add('name',         XML_IMAGE_TO_DISPLAY);
                $propbag->add('description',  XML_IMAGE_TO_DISPLAY_DESC) . self::XML_IMAGE_AVAILABLE;
                $propbag->add('default',      'img/xml.gif');
                break;

            case 'showartcount':
                $propbag->add('type',         'boolean');
                $propbag->add('name',         AUTHORS_SHOW_ARTICLE_COUNT);
                $propbag->add('description',  AUTHORS_SHOW_ARTICLE_COUNT_DESC);
                $propbag->add('default',      'false');
                break;

            case 'mincount':
                $propbag->add('type',         'string');
                $propbag->add('name',         PLUGIN_AUTHORS_MINCOUNT);
                $propbag->add('description',  '');
                $propbag->add('default',      '');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->get_config('title', $this->title);

        $sort = $this->get_config('sort_order');
        if ($sort == 'none') {
            $sort  = '';
        } else {
            $sort .= ' ' . $this->get_config('sort_method');
        }
        $is_form   = serendipity_db_bool($this->get_config('allow_select', 'true'));
        $is_count  = serendipity_db_bool($this->get_config('showartcount', 'false'));
        $mincount  = (int)$this->get_config('mincount');
        $authors   = serendipity_fetchUsers(null, 'hidden', $is_count);
        $html      = '';

        if ($is_form) {
            $html .= '<form action="' . $serendipity['baseURL'] . $serendipity['indexFile'] . '?frontpage" method="post">' . "\n";
        }

        $iconURL = $this->get_config('image', 'img/xml.gif');
        if (!(str_contains($iconURL, 'none'))) {
            $image = serendipity_getTemplateFile($iconURL);
        }
        // on update, reset potential existing config sets
        if (false === $image) {
            $image = $iconURL; // old had passed serendipity_getTemplateFile() already
            // reset the config var to the last relative path part, eg. img/xml.gif
            $iconURL = str_replace($serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . $serendipity['template'] .'/', '', $iconURL);
            $this->set_config('image', $iconURL); // set and done for next run
        }
        $usesvg = is_null($image) ? false : pathinfo($image, PATHINFO_EXTENSION) === 'svg';

        $html .= '                        <ul id="serendipity_authors_list" class="plainList' . ($usesvg ? ' xmlsvg' : '') .'">' . "\n";

        if (is_array($authors) && count($authors)) {
            foreach ($authors AS $auth) {

                if ($is_count) {
                    if ($auth['artcount'] < $mincount) {
                        continue;
                    }
                    $entrycount = " ({$auth['artcount']})";
                } else {
                    $entrycount = "";
                }

                $html .= "                            <li>\n";

                if ($is_form) {
                    $html .= '                                <input style="width: 15px" type="checkbox" name="serendipity[multiAuth][]" value="' . $auth['authorid'] . '">' . "\n";
                }

                if (!empty($image)) {
                    $html .= '                                <a class="serendipity_xml_icon" href="'. serendipity_feedAuthorURL($auth, 'serendipityHTTPPath') .'" title="' . htmlspecialchars($auth['realname']) . ' ' . AUTHOR . ' feed"><img src="'. $image .'" alt="XML"></a> ' . "\n";
                }
                $html .= '                                <a href="'. serendipity_authorURL($auth, 'serendipityHTTPPath') .'" title="'. htmlspecialchars($auth['realname']) . ' ' . ENTRIES . '">'. htmlspecialchars($auth['realname']) . $entrycount . '</a>' . "\n";
                $html .= "                            </li>\n";
            }
        }

        $html .= '                        </ul>' . "\n";

        if ($is_form) {
            $html .= '                        <div><input type="submit" name="serendipity[isMultiAuth]" value="' . GO . '"></div>'."\n";
        }

        $html .= sprintf(
            '                        <div><a href="%s" title="%s">%s</a></div>'."\n",
            $serendipity['serendipityHTTPPath'] . $serendipity['indexFile'],
            ALL_AUTHORS,
            ALL_AUTHORS
        );

        if ($is_form) {
            $html .= "                    </form>\n";
        }
        print $html;
    }

}

?>