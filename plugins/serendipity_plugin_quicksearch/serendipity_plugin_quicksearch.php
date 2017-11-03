<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_quicksearch extends serendipity_plugin
{
    var $title = QUICKSEARCH;

    function introspect(&$propbag)
    {
        $propbag->add('name',          QUICKSEARCH);
        $propbag->add('description',   SEARCH_FOR_ENTRY);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Serendipity Team');
        $propbag->add('version',       '1.3');
        $propbag->add('configuration', array('fullentry', 'smartify'));
        $propbag->add('groups',        array('FRONTEND_ENTRY_RELATED'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'fullentry':
                $propbag->add('type',       'boolean');
                $propbag->add('name',        SEARCH_FULLENTRY);
                $propbag->add('description', '');
                $propbag->add('default',     'true');
                break;

            case 'smartify':
                $propbag->add('type',       'boolean');
                $propbag->add('name',        CATEGORY_PLUGIN_TEMPLATE);
                $propbag->add('description', 'plugin_quicksearch.tpl');
                $propbag->add('default',     'false');
                break;

            default:
                return false;
        }
        return true;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->title;
        $data['fullentry'] = $this->get_config('fullentry', 'true');
        $fullentry  = serendipity_db_bool($data['fullentry']);
        $use_smarty = serendipity_db_bool($this->get_config('smartify', 'false'));
        if ($use_smarty) {
            $serendipity['smarty']->assign($data);
            $tfile = serendipity_getTemplateFile('plugin_quicksearch.tpl', 'serendipityPath');
            if (!$tfile || $tfile == 'quickblog.tpl') {
                $tfile = dirname(__FILE__) . '/plugin_quicksearch.tpl';
            }
            $content = $this->parseTemplate($tfile);
            echo $content;
        } else {
?>
<form id="searchform" action="<?php echo $serendipity['serendipityHTTPPath'] . $serendipity['indexFile']; ?>" method="get">
    <div>
        <input type="hidden" name="serendipity[action]" value="search" />
        <input type="hidden" name="serendipity[fullentry]" value="<?php echo $fullentry ?>" />
        <input type="text" id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" size="13" />
        <input class="quicksearch_submit" type="submit" value="&gt;" name="serendipity[searchButton]" title="<?php echo GO; ?>" style="width: 2em;" />
    </div>
    <div id="LSResult" style="display: none;"><div id="LSShadow"></div></div>
</form>
<?php
        serendipity_plugin_api::hook_event('quicksearch_plugin', $serendipity);
        }
    }

}

?>