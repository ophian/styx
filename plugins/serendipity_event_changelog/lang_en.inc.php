<?php

@define('PLUGIN_CHANGELOG_TITLE', 'Serendipity-log Reader');
@define('PLUGIN_CHANGELOG_DESC', 'Be able to easily read the Serendipity ChangeLog and optionally active set Error/Debug-Log files.');
@define('PLUGIN_CHANGELOG_TITLE_DESC', 'Please carefully read the Serendipity ChangeLog for the current version: "%s", to assure yourself that there is nothing to do for you within custom themes or plugins...');

@define('PLUGIN_CHANGELOG_MAINTAIN', 'View Serendipity Logfiles');
@define('PLUGIN_CHANGELOG_BUTTON', 'Open Changelog');
@define('PLUGIN_CHANGELOG_DELETEOLDLOGS', 'Delete old logfiles');

@define('PLUGIN_CHANGELOG_LOGGER_BUTTON', 'Open Logfile');
@define('PLUGIN_CHANGELOG_LOGGER_DESC', 'Opens the latest Logfile for debugging purposes set in general config. Delete nukes all old logfiles.');
@define('PLUGIN_CHANGELOG_LOGGER_HAS_LOGS', 'DIRECTORY "templates_c/logs/" contains %d text logger log-files.');
@define('PLUGIN_CHANGELOG_LOGGER_NUKE_WARNING', "PLEASE NOTE:\nRemember! These log files are plain text files.\nYou should better nuke them quickly, using the changelog-section button on your maintenance page,\nif not in need for a current debugging session, since this is a directory with \"dilated\" privileges.\nThis is relevant, even when there are VIEW restrictions set by Serendipity for the \"templates_c\" directory itself and\nauthorised VIEW only restrictions for this URL by the changelog event plugin in special!");

@define('PLUGIN_CHANGELOG_LOGGER_BACKBLAH', 'PLEASE USE BROWSER BACK BUTTON TO RETURN TO MAINTENANCE PAGE.');

