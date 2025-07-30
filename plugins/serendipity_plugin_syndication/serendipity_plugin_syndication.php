<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_syndication extends serendipity_plugin
{
    public $title = SYNDICATION;
    private const XML_IMAGE_AVAILABLE = " Available [ pure ] theme defaults: 'img/xml.gif' (orange), 'img/xml12.png' (lightblue 12px), 'img/xml16.png' (lightblue 16px), 'icons/rss.svg' (colored by CSS)";

    function introspect(&$propbag)
    {
        $propbag->add('name',          SYNDICATION);
        $propbag->add('description',   SHOWS_RSS_BLAHBLAH);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team, Ian Styx');
        $propbag->add('version',       '2.20');
        $propbag->add('configuration', array(
                                        'title',
                                        'feed_format',
                                        'show_comment_feed',
                                        'separator',
                                        'iconURL',
                                        'feed_name',
                                        'comment_name',
                                        'custom_url',
                                       )
        );
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {
            case 'title':
                $propbag->add('type',        'string');
                $propbag->add('name',        TITLE);
                $propbag->add('description', TITLE_FOR_NUGGET);
                $propbag->add('default',     SUBSCRIBE_TO_BLOG);
                break;

            case 'feed_format':
                $propbag->add('type', 'radio');
                $propbag->add('name', SYNDICATION_PLUGIN_FEEDFORMAT);
                $propbag->add('description', SYNDICATION_PLUGIN_FEEDFORMAT_DESC);
                $propbag->add('default', 'rss');
                $propbag->add('radio', array(
                    'value' => array('rss', 'atom', 'rssatom', 'rssatomxsl'),
                    'desc'  => array(
                                    SYNDICATION_PLUGIN_20,
                                    sprintf(SYNDICATION_PLUGIN_GENERIC_FEED, 'Atom 1.0'),
                                    SYNDICATION_PLUGIN_20 .' + '. sprintf(SYNDICATION_PLUGIN_GENERIC_FEED, 'Atom 1.0'),
                                    SYNDICATION_PLUGIN_20 .' + '. sprintf(SYNDICATION_PLUGIN_GENERIC_FEED, 'Atom 1.0') . ' +  XSLT link feed')
                ));
                $propbag->add('radio_per_row', '4');
                break;

            case 'show_comment_feed':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        SYNDICATION_PLUGIN_COMMENTFEED);
                $propbag->add('description', SYNDICATION_PLUGIN_COMMENTFEED_DESC);
                $propbag->add('default',     'false');
                break;

            case 'separator':
                $propbag->add('type',        'separator');
                break;

            case 'iconURL':
                $propbag->add('type',        'string');
                $propbag->add('name',        XML_IMAGE_TO_DISPLAY);
                $propbag->add('description', SYNDICATION_PLUGIN_XML_DESC . self::XML_IMAGE_AVAILABLE);
                $propbag->add('default',     'img/xml.gif');
                break;

            case 'feed_name':
                $propbag->add('type',        'string');
                $propbag->add('name',        SYNDICATION_PLUGIN_FEEDNAME);
                $propbag->add('description', SYNDICATION_PLUGIN_FEEDNAME_DESC);
                $propbag->add('default',     '');
                break;

             case 'comment_name':
                $propbag->add('type',        'string');
                $propbag->add('name',        SYNDICATION_PLUGIN_COMMENTNAME);
                $propbag->add('description', SYNDICATION_PLUGIN_COMMENTNAME_DESC);
                $propbag->add('default',     '');
                break;

            case 'custom_url':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        SYNDICATION_PLUGIN_CUSTOMURL);
                $propbag->add('description', SYNDICATION_PLUGIN_CUSTOMURL_DESC);
                $propbag->add('default',     '');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->get_config('title', SUBSCRIBE_TO_BLOG);

        $iconURL = $this->get_config('iconURL', 'img/xml.gif');
        if (!(str_contains($iconURL, 'none'))) {
            $custom_icon = serendipity_getTemplateFile($iconURL);
        }
        // on update, reset potential existing config sets
        if (false === $custom_icon) {
            $custom_icon = $iconURL; // old had passed serendipity_getTemplateFile() already
            // reset the config var to the last relative path part, eg. img/xml.gif
            $iconURL = str_replace($serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . $serendipity['template'] .'/', '', $iconURL);
            $this->set_config('iconURL', $iconURL); // set and done for next run
        }
        $usesvg = is_null($custom_icon) ? false : pathinfo($custom_icon, PATHINFO_EXTENSION) === 'svg';

        $custom_feed = trim($this->get_config('feed_name', ''));
        $custom_comm = trim($this->get_config('comment_name', ''));
        $custom_url  = serendipity_db_bool($this->get_config('custom_url', 'false'));
        $feed_format = $this->get_config('feed_format', 'rss');

        $useRss = true;
        $useAtom = $useBoth = false;
        if ($feed_format == 'atom') {
            $useRss = false;
            $useAtom = true;
        } else if ($feed_format == 'rssatom' || $feed_format == 'rssatomxsl') {
            $useAtom = $useBoth = true;
        }

        $icon = $custom_icon;

        if (empty($custom_feed) || $custom_feed == 'default' || $custom_feed == 'none' || $custom_feed == 'empty') {
            $FEED = 'Feed';
        } else {
            $FEED = $custom_feed;
        }

        if (empty($custom_comm) || $custom_comm == 'default' || $custom_comm == 'none' || $custom_comm == 'empty') {
            $COMMENTS = COMMENTS;
        } else {
            $COMMENTS = $custom_comm;
        }

        if ($custom_url) {
            $mainFeed = serendipity_get_config_var('feedCustom');
        } else {
            $mainFeed = serendipity_rewriteURL(PATH_FEEDS .'/index.rss2');
            if ($useAtom && !$useRss) {
                $mainFeed = serendipity_rewriteURL(PATH_FEEDS .'/atom10.xml');
            }
        }

        echo '<ul id="serendipity_syndication_list" class="plainList' . ($usesvg ? ' xmlsvg' : '') .'">'."\n";
        // case main entries feed either/or
        echo $this->generateFeedButton( $mainFeed,
                                        ($useRss ? "RSS $FEED" : "Atom $FEED"),
                                        $icon);

        // case entries feed atom to add
        if ($useBoth) {
            echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/atom10.xml'),
                                            "Atom $FEED",
                                            $icon);
            if ($feed_format == 'rssatomxsl') {
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/index.xsl'),
                                            "XSLT $FEED",
                                            $icon);
            }
        }

        if (serendipity_db_bool($this->get_config('show_2.0c', 'false')) || serendipity_db_bool($this->get_config('show_comment_feed', 'false'))) {
            echo "<hr/>\n"; // a separator between entry feeds and comment feeds
            // case comments feed both
            if ($useBoth) {
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2'),
                                                $COMMENTS . ' (RSS)',
                                                $icon);
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10'),
                                                $COMMENTS . ' (Atom)',
                                                $icon);
            } else {
                // case comments feed rss2 only
                if ($useRss) {
                    $_GET['version'] = '2.0';
                    echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2'),
                                                    $COMMENTS . ' (RSS)',
                                                    $icon);
                }
                // case comments feed atom10 only
                if ($useAtom) {
                    $_GET['version'] = 'atom1.0';
                    echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10'),
                                                    $COMMENTS . ' (Atom)',
                                                    $icon);
                }
            }
        }
        echo "                     </ul>\n";
    }

    function generateFeedButton($feed, $label, $icon)
    {
        $link = 'href="'.$feed.'"';
        $output = "                        <li>\n";
        if ($icon) {
            $output .= '                            <a class="serendipity_xml_icon" ' . $link . '><img src="' . $icon . '" alt="XML"></a>'."\n";
        }
        if (!empty($label)) {
            $output .= "                            <a $link>$label</a>\n";
        }
        return $output .= "                        </li>\n";
    }

}

?>