<?php

/**
 *  @version 1.0
 *  @author Ian
 */

@define('PLUGIN_CLEANSPAM_NAME', 'Maintenance-Cleanup of Spam-Logs');
@define('PLUGIN_CLEANSPAM_INFO', 'INFO');
@define('PLUGIN_CLEANSPAM_INFO_DESC', 'Cleans database logentries, which apply to certain criteria. This should be done perodically (once or twice a year), since spammers continuously blow up your spamblog logs and reduce the speed of your blog. Please attent to approved all comments first, which have beeen set to status MODERATE and which you want to allow. According to experience, depending on the settings of the Spamblog plugins, these two types are mainly equipped with Spam.');
@define('PLUGIN_CLEANSPAM_MAINTAIN', 'Cleanup of Spam-Logs');

@define('PLUGIN_CLEANSPAM_ALL_BUTTON', 'Cleanup all');
@define('PLUGIN_CLEANSPAM_ALL_DESC', 'Cleanup <b>all</b> log-entries of type: \'REJECTED\' and \'MODERATE\' in database.table "spamblocklog". Currently ("%d") items are available.');
@define('PLUGIN_CLEANSPAM_MULTI_BUTTON', 'Cleanup items');
@define('PLUGIN_CLEANSPAM_YEARS_BUTTON', 'Cleanup years');
@define('PLUGIN_CLEANSPAM_MULTI_DESC', 'Cleanup individually by <b>multi</b> selected or <b>single</b> from table field "reason" LIKE "items". Applies for the types: \'REJECTED\' und \'MODERATE\'!');
@define('PLUGIN_CLEANSPAM_MSG_DONE', 'Cleanup successfuly done!');
@define('PLUGIN_CLEANSPAM_SELECT', 'Single select by criteria');
@define('PLUGIN_CLEANSPAM_VISITORS', 'Clean visitors log table by years');
@define('PLUGIN_CLEANSPAM_VISITORS_DESC', 'Multi-select <b>year items</b> to clean from vistors log table. <u>ATTENTION:</u><br>This influences the statistics history of the statistic event plugin.');

