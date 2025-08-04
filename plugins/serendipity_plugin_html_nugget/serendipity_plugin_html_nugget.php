<?php

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_html_nugget extends serendipity_plugin
{
    public $title = HTML_NUGGET;

    function introspect(&$propbag)
    {
        $this->title = $this->get_config('title', $this->title);
        $subtitle    = $this->get_config('backend_title', '');
        if (!empty($subtitle)) {
            $desc = '(' . $subtitle . ') ' . HOLDS_A_BLAHBLAH;
        } else {
            $desc = HOLDS_A_BLAHBLAH;
        }

        $propbag->add('name',          HTML_NUGGET);
        $propbag->add('description',   $desc);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team, Ian Styx');
        $propbag->add('version',       '1.8');
        $propbag->add('configuration', array(
                                        'title',
                                        'backend_title',
                                        'content',
                                        'markup',
                                        'show_where'
                                       )
        );
        $propbag->add('groups',        array('FRONTEND_VIEWS'));

        $this->protected = TRUE; // If set to TRUE, only allows the owner of the plugin to modify its configuration
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {
            case 'title':
                $propbag->add('type',        'string');
                $propbag->add('name',        TITLE);
                $propbag->add('description', TITLE_FOR_NUGGET);
                $propbag->add('default',     '');
                break;

            case 'backend_title':
                $propbag->add('type',        'string');
                $propbag->add('name',        BACKEND_TITLE);
                $propbag->add('description', BACKEND_TITLE_FOR_NUGGET);
                $propbag->add('default',     '');
                break;

            case 'content':
                $propbag->add('type',        'html');
                $propbag->add('name',        ($serendipity['wysiwyg'] ? '' : CONTENT));
                $propbag->add('description', ($serendipity['wysiwyg'] ? '' : THE_NUGGET));
                $propbag->add('default',     '');
                break;

            case 'markup':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        DO_MARKUP);
                $propbag->add('description', DO_MARKUP_DESCRIPTION);
                $propbag->add('default',     ((isset($serendipity['wysiwyg']) && $serendipity['wysiwyg']) ? 'false' : 'true')); // 'markup' option also excessed on frontend where wysiwyg for unlogged users/visitors might not exist
                break;

            case 'show_where':
                $select = array('extended' => PLUGIN_ITEM_DISPLAY_EXTENDED, 'overview' => PLUGIN_ITEM_DISPLAY_OVERVIEW, 'both' => PLUGIN_ITEM_DISPLAY_BOTH);
                if (defined('STATICPAGE_TITLE')) $select['nostart'] =  PLUGIN_ITEM_DISPLAY_BOTH . ', not ' . STATICPAGE_TITLE . ' ' . ADMIN_FRONTPAGE;
                $propbag->add('type',        'select');
                $propbag->add('select_values', $select);
                $propbag->add('name',        PLUGIN_ITEM_DISPLAY);
                $propbag->add('description', '');
                $propbag->add('default',     'both');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->get_config('title');
        $show_where = $this->get_config('show_where', 'both');

        if ($show_where == 'extended' && (!isset($serendipity['GET']['id']) || !is_numeric($serendipity['GET']['id']))) {
            return false;
        } else if ($show_where == 'overview' && isset($serendipity['GET']['id']) && is_numeric($serendipity['GET']['id'])) {
            return false;
        } else if ($show_where == 'nostart' && $serendipity['view'] == 'start' && !str_contains($_SERVER['REQUEST_URI'], '?frontpage')) {
            return false;
        }

        if (serendipity_db_bool($this->get_config('markup', 'true'))) {
            $entry = array('html_nugget' => $this->get_config('content'));
            serendipity_plugin_api::hook_event('frontend_display', $entry, array('from' => 'serendipity_plugin_html_nugget:generate_content'));
            echo $entry['html_nugget'];
        } else {
            echo $this->get_config('content');
        }
    }

}

?>