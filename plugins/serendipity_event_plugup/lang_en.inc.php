<?php

@define('PLUGIN_EVENT_PLUGUP_TITLE', 'Plugin-Update Notifier');
@define('PLUGIN_EVENT_PLUGUP_TITLE_DESC', 'The Backends startpage Plugin-Update Notifier');
@define('PLUGIN_EVENT_PLUGUP_SHOW_UPDATE_NOTIFIER', 'Show available Plugin updates?');
@define('PLUGIN_EVENT_PLUGUP_SHOW_UPDATE_NOTIFIER_DESC', 'Will newly check every 6 hours');
@define('PLUGIN_EVENT_PLUGUP_SPARTACUS_URL', 'Spartacus remote fetch URL');
@define('PLUGIN_EVENT_PLUGUP_SPARTACUS_URL_DESC', 'Please enable the remote plugin version information in SPARTACUS options and add here the "secret" key to the remote plugin version information! This is only readable for admin users in the Dashboard. Move the Plugup-plugin beneath the Spartacus-plugin in list.');
@define('PLUGIN_DASHBOARD_PLUGUP_BOX_TITLE', 'Plugin Updates');
@define('PLUGIN_DASHBOARD_PLUGUP_UP_TO_DATE', 'All Plugins are up to date!');
@define('PLUGIN_DASHBOARD_PLUGUP_UP_AVAILABLE', '%s Plugin(s) are waiting for upgrade!');
@define('PLUGIN_DASHBOARD_PLUGUP_RELOAD', 'Please reload page to get a button!');

//Remember: plugin updates are checked by version and timestamp! So, if having touched the local file after sync time, you need a new xml file.
