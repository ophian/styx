<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_WRAPPER_NAME', 'Event-Output wrapper');
@define('PLUGIN_EVENT_WRAPPER_DESC', 'Displays gathered data by a certain event plugin, when it print outs something via the generate_content(&$title) method. This plugin is a curiosity, as it shortens the basic modularity and delimitation of event and sidebar plugins and uses an event plugin as a fast output medium for the frontend sidebar.');
@define('PLUGIN_EVENT_WRAPPER_PLUGIN', 'Source event plugin');
@define('PLUGIN_EVENT_WRAPPER_PLUGINDESC', 'Select the event plugin for which the output should be displayed. You should be aware that the plugin of choice offers this method with additional generated output content other than just the plugin title!');
@define('PLUGIN_EVENT_WRAPPER_TITLEDESC', 'Enter the title for this sidebar item (leave empty for inheritance by event plugin). In case you chose an event plugin with no title generated, the title output is "Sample" with a content of "This is a sample!".');

