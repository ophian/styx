<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_syndication extends serendipity_plugin
{
    public $title = SYNDICATION;
    private const SYNDICATION_PLUGIN_OUTDATED_SERVICES = 'Historic external services';
    private const SYNDICATION_PLUGIN_OUTDATED_SERVICES_DESC = "In the old days Googles Feedburner Service was the central collector for feeds, a provider for managing web feeds. Long ago Google moved this to the “Google graveyard of dead projects”, so said, deprecated the API and shut off certain inbound services like AdSense for Feeds, but left it alive for registered users. Lets say: you don't need it nowadays. The other, Subtome project, was a project to gather feeds all in-one, in short a subscribing application, but didn't made it to become very famous. One of the key goals of the button was to hide “RSS” altogether. Its relevance for feeds is near to nothing today. On the longer run both services will get nuked from the syndication plugin. There are so many valid subscribing tools and feedreaders that providing the xml buttons and feeds is the purest implementation we should give.";
    private const XML_IMAGE_AVAILABLE = " Available [ pure ] theme defaults: 'img/xml.gif' (orange), 'img/xml12.png' (lightblue 12px), 'img/xml16.png' (lightblue 16px), 'icons/rss.svg' (colored by CSS)";

    function introspect(&$propbag)
    {
        $propbag->add('name',          SYNDICATION);
        $propbag->add('description',   SHOWS_RSS_BLAHBLAH);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team, Ian Styx');
        $propbag->add('version',       '2.19');
        $propbag->add('configuration', array(
                                        'title',
                                        'feed_format',
                                        'show_comment_feed',
                                        'separator',
                                        'iconURL',
                                        'feed_name',
                                        'comment_name',
                                        'custom_url',
                                        'separator2',
                                        'config_outdated',
                                        'subToMe',
                                        'big_img',
                                        'fb_id'
                                       )
        );
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
        $propbag->add('legal',         array(
            'services' => array(
                'subtome' => array(
                    'url'  => 'https://www.subtome.com',
                    'desc' => 'Enables visitors to easily subscribe to RSS feeds. The visitor loads a JavaScript from their servers, thus the IP address will be known to the service.'
                ),
                'feedburner.com' => array(
                    'url'  => 'https://www.feedburner.com',
                    'desc' => 'Feedburner can be used to track your feed subscription statistics. If used, a tracking pixel is loaded from FeedBurner.com servers and the IP address of the visitor will be known to the service.'
                ),
            ),
            'frontend' => array(
                'To allow easy subscription to feeds and optional tracking statistics, the subtome or feedburner services can be used.',
            ),
            'backend' => array(
            ),
            'cookies' => array(
            ),
            'stores_user_input'     => false,
            'stores_ip'             => false,
            'uses_ip'               => true,
            'transmits_user_input'  => true
        ));
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
            case 'separator2':
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

            case 'config_outdated':
                $propbag->add('type',    'content');
                $propbag->add('name',    'Slight outdated services');
                $propbag->add('default', '<h3>' . self::SYNDICATION_PLUGIN_OUTDATED_SERVICES . '</h3><em>' . self::SYNDICATION_PLUGIN_OUTDATED_SERVICES_DESC . '</em>');
                break;

            case 'subToMe':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        SYNDICATION_PLUGIN_SUBTOME);
                $propbag->add('description', SYNDICATION_PLUGIN_SUBTOME_DESC);
                $propbag->add('default',     'false');
                break;

            case 'big_img':
                $propbag->add('type',        'string');
                $propbag->add('name',        SYNDICATION_PLUGIN_FEEDICON);
                $propbag->add('description', SYNDICATION_PLUGIN_FEEDICON_DESC);
                $propbag->add('default',     'img/subtome.png');
                break;

            case 'fb_id':
                $propbag->add('type',        'string');
                $propbag->add('name',        SYNDICATION_PLUGIN_FEEDBURNERID);
                $propbag->add('description', SYNDICATION_PLUGIN_FEEDBURNERID_DESC);
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
            $small_icon = serendipity_getTemplateFile($iconURL);
        }
        // on update, reset potential existing config sets
        if (false === $small_icon) {
            $small_icon = $iconURL; // old had passed serendipity_getTemplateFile() already
            // reset the config var to the last relative path part, eg. img/xml.gif
            $iconURL = str_replace($serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . $serendipity['template'] .'/', '', $iconURL);
            $this->set_config('iconURL', $iconURL); // set and done for next run
        }
        $usesvg = is_null($small_icon) ? false : pathinfo($small_icon, PATHINFO_EXTENSION) === 'svg';

        $custom_feed = trim($this->get_config('feed_name', ''));
        $custom_comm = trim($this->get_config('comment_name', ''));
        $custom_img  = trim($this->get_config('big_img', 'img/subtome.png'));
        if ($custom_img != 'none' && $custom_img != 'feedburner') {
            $custom_img = serendipity_getTemplateFile($custom_img);
        }
        $subtome     = serendipity_db_bool($this->get_config('subToMe', 'false'));
        $fbid        = $this->get_config('fb_id', '');
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

        #$img = 'https://feeds.feedburner.com/~fc/'.$this->get_config('fb_id').'?bg=99CCFF&amp;fg=444444&amp;anim=0';

        $icon = $small_icon;
        if (!empty($custom_img) && $custom_img != 'default' && $custom_img != 'none' && $custom_img != 'empty') {
            $icon = $custom_img;
            if ($fbid != '' && $custom_img == 'feedburner') {
                $icon = "https://feeds.feedburner.com/~fc/$fbid?bg=99CCFF&amp;fg=444444&amp;anim=0";
            }
            if ($fbid == '' && $custom_img == 'feedburner') {
                $icon = serendipity_getTemplateFile('img/subtome.png');
            }
        }

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
            if ($fbid != '') {
                $mainFeed = 'https://feeds.feedburner.com/' . $fbid;
            } else {
                if ($useAtom && !$useRss) {
                    $mainFeed = serendipity_rewriteURL(PATH_FEEDS .'/atom10.xml');
                }
            }
        }

        $onclick = '';
        if ($subtome) {
            $onclick = $this->getOnclick($mainFeed);
        }

        echo '<ul id="serendipity_syndication_list" class="plainList' . ($usesvg ? ' xmlsvg' : '') .'">'."\n";
        // case main entries feed either/or
        echo $this->generateFeedButton( $mainFeed,
                                        ($useRss ? "RSS $FEED" : "Atom $FEED"),
                                        $onclick,
                                        $icon,
                                        ($icon === $small_icon));

        // case entries feed atom to add
        if ($useBoth) {
            echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/atom10.xml'),
                                            "Atom $FEED",
                                            ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/atom10.xml')) : ''),
                                            $icon,
                                            ($icon === $small_icon));
            if ($feed_format == 'rssatomxsl') {
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/index.xsl'),
                                            "XSLT $FEED",
                                            ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/index.xsl')) : ''),
                                            $icon,
                                            ($icon === $small_icon));
            }
        }

        if (serendipity_db_bool($this->get_config('show_2.0c', 'false')) || serendipity_db_bool($this->get_config('show_comment_feed', 'false'))) {
            echo "<hr/>\n"; // a separator between entry feeds and comment feeds
            // case comments feed both
            if ($useBoth) {
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2'),
                                                $COMMENTS . ' (RSS)',
                                                ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2')) : ''),
                                                $icon,
                                                ($icon === $small_icon));
                echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10'),
                                                $COMMENTS . ' (Atom)',
                                                ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10')) : ''),
                                                $icon,
                                                ($icon === $small_icon));
            } else {
                // case comments feed rss2 only
                if ($useRss) {
                    $_GET['version'] = '2.0';
                    echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2'),
                                                    $COMMENTS . ' (RSS)',
                                                    ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.rss2')) : ''),
                                                    $icon,
                                                    ($icon === $small_icon));
                }
                // case comments feed atom10 only
                if ($useAtom) {
                    $_GET['version'] = 'atom1.0';
                    echo $this->generateFeedButton( serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10'),
                                                    $COMMENTS . ' (Atom)',
                                                    ($subtome ? $this->getOnclick(serendipity_rewriteURL(PATH_FEEDS .'/comments/comments.atom10')) : ''),
                                                    $icon,
                                                    ($icon === $small_icon));
                }
            }
        }
        echo "                     </ul>\n";
    }

    function generateFeedButton($feed, $label, $onclick, $icon, $small = false)
    {
        $link = 'href="'.$feed.'"'. $onclick;
        $output = "                        <li>\n";
        $class = '';
        if ($onclick != '') {   # this might be not a good solution, but right now works to add the subtome-class only when subtome is on
            $class = "subtome";
        }
        if ($small) {
            $class .= (!empty($class) ? ' ' : '') . 'serendipity_xml_icon';
        }
        if ($icon) {
            $output .= '                            <a class="'. $class .'" ' . $link . '><img src="' . $icon . '" alt="XML"></a>'."\n";
        }
        if (!empty($label)) {
            $output .= "                            <a $link>$label</a>\n";
        }
        return $output .= "                        </li>\n";
    }

    function getOnclick($url)
    {
        return " onclick=\"document.subtomeBtn=this;document.subtomeBtn.dataset['subtomeFeeds']='". urlencode($url). "';var s=document.createElement('script');s.src='https://www.subtome.com/load.js';document.body.appendChild(s);return false;\"";
    }

}

?>