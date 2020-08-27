<?php

@define('PLUGIN_MODEMAINTAIN_TITLE', 'Maintenance Mode');
@define('PLUGIN_MODEMAINTAIN_TITLE_DESC', 'Allows to set your public open blog - the frontend - into a "503 - Service Temporarily Unavailable" mode.');

@define('PLUGIN_MODEMAINTAIN_MAINTAIN', 'Service Maintenance Mode');
@define('PLUGIN_DASHBOARD_MAINTENANCE_MODE_ACTIVE', '...active maintenance...');
@define('PLUGIN_MODEMAINTAIN_INFOALERT', 'Attention: Read the description first!');
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_MODE_DESC', "ATTENTION:<br>\nDo <b>not</b> log-off, close your browser or tab, or submit the Configuration options form, until reset to false again!");
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_EXWARNING_DESC', "WARNING:<br>\nCurrently there <em>may</em> be a page loading (Session based) issue not switching the mode button here immediately after page reload . You then need to access the setmode and/or the undo button twice, with a ~1 second delay inbetween, OR better click somewhere else and return, to see the button change (which has to get: green to red, or vice versa).");
@define('PLUGIN_MODEMAINTAIN_DASHBOARD_EMERGENCY_DESC', "EMERGENCY CASE:<br>\nIf you ever logged yourself out without resetting the 503 Maintenance Mode, or your login cookie got destroyed, you need to set the &dollar;serendipity['maintenance'] variable to 'false' in your serendipity_config_local.inc.php file to get public access to your blog again!");

@define('PLUGIN_MODEMAINTAIN_MAINTAIN_NOTE', 'Public Maintenance Mode Text');
@define('PLUGIN_MODEMAINTAIN_MAINTAIN_TEXT', 'This site &#187;%s&#171; is currently undergoing some maintenance work and therefore is temporarily unavailable. Please visit us later.');
@define('PLUGIN_MODEMAINTAIN_MAINTAIN_USELOGO', 'Show the Serendipity logo?');

@define('PLUGIN_MODEMAINTAIN_BUTTON', 'Set 503 Maintenance Mode');
@define('PLUGIN_MODEMAINTAIN_FREEBUTTON', 'Unset 503 Maintenance Mode');
@define('PLUGIN_MODEMAINTAIN_RETURN', 'The Blog is now set to %s mode. <a href="%s">Return</a> to the backend and do your work.');

@define('PLUGIN_MODEMAINTAIN_TITLE_AUTOLOGIN', 'To use and administrate the maintenance mode you need to log-off and log-in again with the "auto remember me" option set true.');

@define('PLUGIN_MODEMAINTAIN_WARNLOGOFF', 'Hey, you are in <span class="fivezerothree">503</span>-Maintenance-Mode! - <b>Free</b> this Mode <a href="%s">here</a>, <b>before</b> Log-Off!');
@define('PLUGIN_MODEMAINTAIN_WARNGLOBALCONFIGFORM', 'Hey, you are in <span class="fivezerothree">503</span>-Maintenance-Mode! - <b>Free</b> this Mode <a href="%s">here</a>, <b>before</b> you change and/or post the global configuration form!');

@define('PLUGIN_MODEMAINTAIN_HINT_MAINTENANCE_MODE', 'Since long, or having possible frontend effects, this could be a valid task to use the Maintenance-Mode!');

