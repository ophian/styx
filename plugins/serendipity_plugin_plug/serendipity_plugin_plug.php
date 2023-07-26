<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

class serendipity_plugin_plug extends serendipity_plugin
{
    var $title = POWERED_BY;

    function introspect(&$propbag)
    {
        $propbag->add('name',          POWERED_BY);
        $propbag->add('description',   ADVERTISES_BLAHBLAH);
        $propbag->add('stackable',     true);
        $propbag->add('author',        'Serendipity Team');
        $propbag->add('version',       '1.5.1');
        $propbag->add('configuration', array(
                                        'image',
                                        'image2',
                                        'text'));
        $propbag->add('groups',        array('FRONTEND_VIEWS'));
    }

    function introspect_config_item($name, &$propbag)
    {
        switch($name) {
            case 'image':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        sprintf(POWERED_BY_SHOW_IMAGE, 'Serendipity Styx') . ' 1');
                $propbag->add('description', POWERED_BY_SHOW_IMAGE_DESC);
                $propbag->add('default',     'true');
                break;

            case 'image2':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        sprintf(POWERED_BY_SHOW_IMAGE, 'Serendipity Styx') . ' 2');
                $propbag->add('description', str_replace(' Styx', '', POWERED_BY_SHOW_IMAGE_DESC) . ' (deprecated)');
                $propbag->add('default',     'false');
                break;

            case 'text':
                $propbag->add('type',        'boolean');
                $propbag->add('name',        sprintf(POWERED_BY_SHOW_TEXT, 'Serendipity Styx'));
                $propbag->add('description', POWERED_BY_SHOW_TEXT_DESC);
                $propbag->add('default',     'true');
                break;

            default:
                return false;
        }
        return true;
    }

    function example()
    {
        global $serendipity;

        $s = '';
        $s .= "\n";
        $s = '<div class="configuration_group odd plug">';
        $s .= sprintf(POWERED_BY_SHOW_IMAGE, 'Serendipity Styx');
        $s .= '1: <picture>
                    <source type="image/webp" srcset="' . $serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 'styx_logo_150.webp">
                    <img src="' . $serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 'styx_logo_150.png' . '" alt="Serendipity Styx PHP Weblog" title="' . POWERED_BY . ' Serendipity Styx">
                   </picture> ';
        $s .= "</div>\n";
        $s .= '<div class="configuration_group even plug">';
        $s .= sprintf(POWERED_BY_SHOW_IMAGE, 'Serendipity Styx');
        $s .= '2: <img src="' . $serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 's9y_banner_small.png" alt="Serendipity Styx PHP Weblog" title="' . POWERED_BY . ' Serendipity Styx (img is deprecated)">';
        $s .= "<div>\n";
        return $s;
    }

    function generate_content(&$title)
    {
        global $serendipity;

        $title = $this->title;
        $url = 'https://ophian.github.io/';
        $edition = 'Serendipity Styx';
?>
<div class="serendipityPlug">
<?php if (serendipity_db_bool($this->get_config('image', 'true'))) { ?>
    <a title="<?php echo $title ?> <?=$edition?>" href="<?=$url?>">
        <picture>
            <source type="image/webp" srcset="<?=$serendipity['serendipityHTTPPath'] . $serendipity['templatePath']?>styx_logo_150.webp">
            <img src="<?php echo $serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 'styx_logo_150.png'; ?>" title="&copy; Serendipity Styx Edition" alt="<?=$edition?> PHP Weblog">
        </picture>
    </a>
<?php } ?>
<?php if (serendipity_db_bool($this->get_config('image2', 'false'))) { ?>
    <a title="<?php echo $title ?> <?=$edition?>" href="<?=$url?>">
        <img src="<?php echo $serendipity['serendipityHTTPPath'] . $serendipity['templatePath'] . 's9y_banner_small.png'; ?>" alt="<?=$edition?> PHP Weblog" />
    </a>
<?php } ?>
<?php if (serendipity_db_bool($this->get_config('text', 'true'))) { ?>
    <div>
        <a title="<?php echo $title ?> Serendipity Styx" href="<?=$url?>"><?=$edition?> PHP Weblog</a>
    </div>
<?php } ?>
</div>
<?php
    }

}

?>