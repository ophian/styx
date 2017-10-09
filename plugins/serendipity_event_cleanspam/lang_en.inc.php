<?php

/**
 *  @version 1.0
 *  @author Ian
 */

@define('PLUGIN_CLEANSPAM_NAME', 'Maintenance-Cleanup of Spam-Logs');
@define('PLUGIN_CLEANSPAM_DESC', 'Cleans database logentries, which apply to certain criteria. This should be done perodically (once or twice a year), since spammers continuously blow up your spamblog logs and reduce the speed of your blog.');
@define('PLUGIN_CLEANSPAM_MAINTAIN', 'Cleanup of Spam-Logs');
@define('PLUGIN_CLEANSPAM_ALL_BUTTON', 'Cleanup all');
@define('PLUGIN_CLEANSPAM_ALL_DESC', 'Cleanup <b>all</b> log-entries of type: \'REJECTED\' in database.table "spamblocklog". Currently ("%d") items available.');
@define('PLUGIN_CLEANSPAM_MULTI_BUTTON', 'Cleanup items');
@define('PLUGIN_CLEANSPAM_YEARS_BUTTON', 'Cleanup years');
@define('PLUGIN_CLEANSPAM_MULTI_DESC', 'Cleanup individually by <b>multi</b> selected or <b>single</b> from table field "reason" LIKE "items"');
@define('PLUGIN_CLEANSPAM_MSG_DONE', 'Cleanup successfuly done!');
@define('PLUGIN_CLEANSPAM_SELECT', 'Single select by criteria');
@define('PLUGIN_CLEANSPAM_VISITORS', 'Clean visitors log table by years');
@define('PLUGIN_CLEANSPAM_VISITORS_DESC', 'Multi-select <b>year items</b> to clean from vistors log table. <u>ATTENTION:</u><br>This influences the statistics history of the statistic event plugin.');

