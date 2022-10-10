<?php
# $Id: voodoopad.inc.php 1 2005-04-16 06:39:31Z timputnam $
# Copyright (c) 2003-2005, Tim Putnam

/*****************************************************************
 *                VoodooPad Importer, by Tim Putnam
 *               http://deepbluesea.fracsoft.com  *
 *****************************************************************/

// These are used by the XML parser
class element
{
   var $name = '';
   var $attributes = array();
   var $data = '';
   var $depth = 0;
}

$elements = $stack = array();
$count = $depth = 0;

// Language, language...
switch ($serendipity['lang']) {
    case 'en':
    default:
        @define('IMPORTER_VOODOO_FILEPROMPT', 'VoodooPad XML file');
        @define('IMPORTER_VOODOO_CREATEINTRALINKSPROMPT', 'Recreate intra-links?');
        @define('IMPORTER_VOODOO_WIKINAMEPROMPT','Wiki name');
        @define('IMPORTER_VOODOO_KEYPREFIXPROMPT','Prefix for static page DB key');
        @define('IMPORTER_VOODOO_UPDATEEXISTINGPROMPT','Update existing entries?');
        @define('IMPORTER_VOODOO_CREATINGPAGE','Creating page');
        @define('IMPORTER_VOODOO_UPDATINGPAGE','Updating page');
        @define('IMPORTER_VOODOO_NOTUPDATING','Not updating');
        @define('IMPORTER_VOODOO_RECORDURL','Recording link URL');
        @define('IMPORTER_VOODOO_WRITEINTRALINKS','Writing intra-links..');
        @define('IMPORTER_VOODOO_REQUIREMENTFAIL', 'This importer requires the Static Pages plugin to be installed. All static pages are currently scanned for a match.');
        break;
}

class Serendipity_Import_VoodooPad extends Serendipity_Import
{
    var $info        = array('software' => 'VoodooPad');
    var $data        = array();
    var $inputFields = array();
    var $force_recode = false;

    function __construct($data)
    {
        $this->data = $data;
        $this->inputFields = array(
                                array('text'      => IMPORTER_VOODOO_FILEPROMPT,
                                      'type'      => 'file',
                                      'name'      => 'voodooPadXML'),
                                array('text'      => IMPORTER_VOODOO_CREATEINTRALINKSPROMPT,
                                      'type'      => 'bool',
                                      'name'      => 'shouldWriteLinks',
                                      'default'   => 'true'),
                                array('text'      => IMPORTER_VOODOO_WIKINAMEPROMPT,
                                      'type'      => 'input',
                                      'name'      => 'wikiName',
                                      'default'   => ''),
                                array('text'      => IMPORTER_VOODOO_KEYPREFIXPROMPT,
                                      'type'      => 'input',
                                      'name'      => 'keyPrefix',
                                      'default'   => '' ),
                                array('text'      => IMPORTER_VOODOO_UPDATEEXISTINGPROMPT,
                                      'type'      => 'bool',
                                      'name'      => 'updateExisting',
                                      'default'   => 'true' ) );
    }

    function getImportNotes()
    {
        return IMPORTER_VOODOO_REQUIREMENTFAIL;
    }

    function validateData()
    {
       return (isset($_FILES['serendipity']) && is_array($_FILES['serendipity']['tmp_name']['import']['voodooPadXML']) ? sizeof($_FILES['serendipity']['tmp_name']['import']['voodooPadXML']) : false);
    }

    function getInputFields()
    {
        return $this->inputFields;
    }

    function import()
    {
        global $serendipity;
        global $elements;

        // Dependency on static pages
        if (!class_exists('serendipity_event_staticpage')) {
            die('<span class="msg_error">' . IMPORTER_VOODOO_REQUIREMENTFAIL . '</span>');
        }

          // The selected file
        $file =  $_FILES['serendipity']['tmp_name']['import']['voodooPadXML'];

        // Check for xml_parser_create()
        if (!function_exists('xml_parser_create')) {
            echo '<span class="msg_error"><span class="icon-attention-circled"></span> ' . sprintf(CANT_EXECUTE_EXTENSION, 'php-xml (PHP)') . "</span>\n";
        }

        // Create a parser and set it up with the callbacks
        $xml_parser = xml_parser_create('');
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_element_handler($xml_parser, "start_element_handler", "end_element_handler");
        xml_set_character_data_handler($xml_parser, "character_data_handler");

        // Feed the contents of the file into the parser
        if (!file_exists($file)) {
            die(sprintf(DOCUMENT_NOT_FOUND, serendipity_specialchars($file)));
        }

        if (!($handle = fopen($file, "r"))) {
            die(sprintf(SKIPPING_FILE_UNREADABLE, serendipity_specialchars($file)));
        }

        while ($contents = fread($handle, 4096)) {
            xml_parse($xml_parser, $contents, feof($handle));
        }

        fclose($handle);
        xml_parser_free($xml_parser);

        // Maintain a list of the aliases and their links
        $aliases = array();

        // Now have a list of elements referenceable by id
        // so loop through building and/or updating page objects
        while (list($key_a) = current($elements)) {
            $name = $elements[$key_a]->name;

            switch ($name) {
                case 'data': // <data> indicates the start of the VoodooPad entry, so create page object
                    $thispage = array();
                    break;

                case 'key': // This is the unique identifier of the page
                    $mykey = serendipity_makeFilename($elements[$key_a]->data);
                    $mykey = basename($this->data['keyPrefix']) . $mykey;

                    // Pluck out the existing one if its there
                    $page = serendipity_db_query("SELECT *
                                                    FROM {$serendipity['dbPrefix']}staticpages
                                                    WHERE filename = '" . serendipity_db_escape_string($mykey.'.htm') . "'
                                                    LIMIT 1", true, 'assoc');
                    if (is_array($page)) {
                        $thispage =& $page;
                        if (empty($thispage['timestamp'])) {
                              $thispage['timestamp'] = time();
                        }
                    }

                    $thispage['filename']  = $mykey.'.htm';
                    // Thanks for pointing this out to me and not just fixing it, I'm learning.
                    $thispage['permalink'] = $serendipity['serendipityHTTPPath'] . 'index.php?serendipity[subpage]=' . $mykey;
                    break;

                case 'alias': // The title and the string used to match links
                    $thispage['articleformattitle'] = $this->data['wikiName'];
                    $thispage['pagetitle'] = $mykey;
                    $thispage['headline'] = $elements[$key_a]->data;
                    break;

                case 'content': // The content of a voodoopad entry
                case 'path': // The path of a url string
                    $thispage['content'] = $elements[$key_a]->data;

                    // If its a content link list it for referencing with the page permalink
                    if ($name == 'content') {
                        $aliases[$thispage['headline']] = $thispage['permalink'];

                        // Either replace or insert depending on previous existence
                        if (!isset($thispage['id'])) {
                            echo '<span class="block_level">'.IMPORTER_VOODOO_CREATINGPAGE.': '. $mykey.'</span>';
                            serendipity_db_insert('staticpages', $thispage);
                            $serendipity["POST"]["staticpage"] = serendipity_db_insert_id("staticpages", 'id');
                        } elseif ($this->data['updateExisting'] == 'true') {
                            echo '<span class="block_level">'.IMPORTER_VOODOO_UPDATINGPAGE.': '. $mykey.'</span>';
                            serendipity_db_update("staticpages", array("id" => $thispage["id"]), $thispage);
                        } else {
                            echo '<span class="block_level">'.IMPORTER_VOODOO_NOTUPDATING.': '. $mykey.'</span>';
                        }
                    } else {
                        // If its a url, the content is the link instead
                        echo '<span class="block_level">'.IMPORTER_VOODOO_RECORDURL.': '.$thispage['headline'].'</span>';
                        $aliases[$thispage['headline']] = $thispage['content'];
                    }
                    break;
            }
            next($elements);
        }

        // Now rewrite the permalinks
        if ($this->data['shouldWriteLinks'] == 'true') {
            Serendipity_Import_VoodooPad::write_links($aliases);
        }
        return true;
    }

    function write_links($aliases)
    {
        // Here we run through the static pages database and put in cross links
        // around the keywords in the text
        global $serendipity;

        // **TODO** Change this to pull out only entries for the current wiki
        echo '<p>'.IMPORTER_VOODOO_WRITEINTRALINKS.'</p>';

        $pages= &serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}staticpages  ORDER BY pagetitle DESC");

        foreach($pages AS $thispage) {
            // Parse the content string
            foreach($aliases AS $alias => $permalink) {
                $thispage['content'] = Serendipity_Import_VoodooPad::wikify($alias, $permalink, $thispage['content']);
            }

            for ($counter = 0; $counter <= 12; $counter+=1) {
                unset ($thispage[$counter]);
            }

            // Write back to the database
            serendipity_db_update("staticpages", array("id" => $thispage["id"]), $thispage);
        }

        echo '<span class="msg_success">' . DONE . '</span>';
    }

    // Search and replace avoiding content of links
    // **TODO** Fix this to avoid short links screwing up longer links
    function wikify($alias, $link, $txt)
    {
        $r = preg_split('((>)|(<))', $txt, -1, PREG_SPLIT_DELIM_CAPTURE);
        $ns = '';
        for ($i = 0; $i < count($r); $i++) {
            if ($r[$i] == "<") {
                $i+=2;
                continue;
            }
            $r[$i] = eregi_replace(sql_regcase($alias), '<a href="'.$link.'">'.$alias.'</a>', $r[$i]);
        }

        return join("", $r);
    }

}

// XML Parser callbacks
function start_element_handler($parser, $name, $attribs) {
    global $elements, $stack, $count, $depth;

    $id = $count;
    $element = new element;
    $elements[$id] = $element;
    $elements[$id]->name = $name;

    foreach(attribs AS $key => $value) {
        $elements[$id]->attributes[$key] = $value;
    }

   $elements[$id]->depth = $depth;
   array_push($stack, $id);

   $count++;
   $depth++;
}

function end_element_handler($parser, $name) {
   global $stack, $depth;

   array_pop($stack);

   $depth--;
}

function character_data_handler($parser, $data) {
   global $elements, $stack;

   $elements[$stack[count($stack)-1]]->data .= $data;
}

return 'Serendipity_Import_VoodooPad';

?>