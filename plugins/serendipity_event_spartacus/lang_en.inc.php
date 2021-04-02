<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPARTACUS_NAME', 'Spartacus');
@define('PLUGIN_EVENT_SPARTACUS_DESC', '[S]erendipity [P]lugin [A]ccess [R]epository [T]ool [A]nd [C]ustomization/[U]nification [S]ystem - Allows you to download plugins from our online repository');
@define('PLUGIN_EVENT_SPARTACUS_FETCH', 'Click here to fetch a new %s from the Serendipity Online Repository');
@define('PLUGIN_EVENT_SPARTACUS_FETCHERROR', 'The URL %s could not be opened. Maybe the remote Spartacus Server is down - we are sorry, you need to try again later. Try to reload (F5) the page first.');
@define('PLUGIN_EVENT_SPARTACUS_FETCHING', 'Trying to open URL %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_URL', 'Fetched %s bytes from the URL above. Saving file as %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_CACHE', 'Fetched %s bytes from already existing file on your server. Saving file as %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_DONE', 'Data successfully fetched.');
@define('PLUGIN_EVENT_SPARTACUS_REPOSITORY_ERROR', '(The repository returned error code %s.)');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHCHECK', 'Unable to retrieve data from SPARTACUS repository. Checking for repository availability on %s.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHERROR', 'The SPARTACUS health site returned an error (HTTP code %s). This indicates that the SPARTACUS health site is down. Please try again later.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHLINK', '<a target="_blank" rel="noopener" href="%s">Click here to view the SPARTACUS health site</a> and determine if it is responding.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHBLOCKED', 'SPARTACUS attempted to connect to Google and failed (error %d: %s).<br>Your server is blocking outgoing connections. SPARTACUS will not function because it cannot contact the SPARTACUS repository. <b>Please contact your website provider and ask them to allow outgoing connections to web sites.</b> Plugins can still be installed from your local directories. Simply download the plugin from <a href="http://spartacus.s9y.org">the origin SPARTACUS web repository</a>, unzip it, and upload the files to your Serendipity plugin directory. For Styx link to the <a href="https://ophian.github.io/plugins/">Styx Spartacus website</a>.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHDOWN', 'SPARTACUS can contact Google, but cannot contact the SPARTACUS repository. It is possible that your server is blocking some outgoing connections, or that the SPARTACUS health site is down. Please contact your website provider to ensure that outgoing connections are allowed. <b>You will not be able to use SPARTACUS unless your server can contact the SPARTACUS repository.</b>');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_XML', 'File/Mirror location (XML metadata)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_FILES', 'File/Mirror location (files)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_DESC', 'Choose a download location. Do not change this value unless you know what you are doing and if servers get outdated. This option is available mainly for forward compatibility.');

@define('PLUGIN_EVENT_SPARTACUS_CHOWN', 'Owner of downloaded files');
@define('PLUGIN_EVENT_SPARTACUS_CHOWN_DESC', 'Here you can enter the (FTP/Shell) owner (like "nobody") of files downloaded by Spartacus. If empty, no changes are made to the ownership.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD', 'Permissions downloaded files');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DESC', 'Here you can enter the octal mode (like "0777") of the file permissions for files (FTP/Shell) downloaded by Spartacus. If empty, the default permission mask of the system are used. Note that not all servers allow changing/setting permissions. Pay attention that the applied permissions allow reading and writing for the webserver user. Else spartacus/Serendipity cannot overwrite existing files.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR', 'Permissions downloaded directories');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR_DESC', 'Here you can enter the octal mode (like "0777") of the directory permissions for directories (FTP/Shell) downloaded by Spartacus. If empty, the default permission mask of the system are used. Note that not all servers allow changing/setting permissions. Pay attention that the applied permissions allow reading and writing for the webserver user. Else Spartacus/Serendipity cannot overwrite existing directories.');

@define('PLUGIN_EVENT_SPARTACUS_CHECK_SIDEBAR', 'Update sidebar plugins');
@define('PLUGIN_EVENT_SPARTACUS_CHECK_EVENT', 'Update event plugins');
@define('PLUGIN_EVENT_SPARTACUS_CHECK_HINT', 'You can upgrade multiple plugins at once by opening the update-link in a new tab (middle mouse button)');

@define('PLUGIN_EVENT_SPARTACUS_TRYCURL', 'Trying to use cURL library as fallback...');
@define('PLUGIN_EVENT_SPARTACUS_CURLFAIL', 'cURL library returned a failure, too.');
@define('PLUGIN_EVENT_SPARTACUS_HEALTHFIREWALLED', 'It was not possible to download the required files from the Spartacus repository, but the health of our repository was retrievable. This means your provider uses a content-based firewall and does not allow to fetch PHP code over the web by using mod_security or other reverse proxies. You either need to ask your provider to turn this off, or you cannot use the Spartacus plugin and need to download files manually.');

@define('PLUGIN_EVENT_SPARTACUS_ENABLE_PLUGINS', 'Enable the use of Spartacus for fetching plugins?');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_THEMES', 'Enable the use of Spartacus for fetching themes?');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_THEMES_DESC', 'Since the additional themes preview image files are getting cached on first run, this fetch can take a while (2-3 min) when hitting the sidebars themes link the first time, after having set this option to Yes (enabled). Wait for the page to finish its background work. If it fails with a max execution fatal error, please do just reload the page to finish that run.');

@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE', 'Enable remote plugin version information');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_DESC', 'If enabled, visitors to "%s" can see the version information of all installed plugins. You might want to protect this URL through custom .htaccess rules for unprivileged access.');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL', 'Secret key to Remote plugin version information');
@define('PLUGIN_EVENT_SPARTACUS_ENABLE_REMOTE_URL_DESC', 'Enter a special URI component that people need to know to access your remote management version information output.');

@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CONNECT', 'FTP Error: Unable to connect to FTP.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_MKDIR', 'FTP Error: Unable to create directory (%s).');
@define('PLUGIN_EVENT_SPARTACUS_FTP_ERROR_CHMOD', 'FTP Error: Unable to change privileges of directory (%s).');
@define('PLUGIN_EVENT_SPARTACUS_FTP_SUCCESS', 'FTP: Directory (%s) successfully created.');

@define('PLUGIN_EVENT_SPARTACUS_FTP_USE', 'Use directory creating using FTP in safe_mode?');
@define('PLUGIN_EVENT_SPARTACUS_FTP_USE_DESC', 'If you are running PHP in safe_mode, some restrictions are applied. And these restrictions have the result that if you create a directory using an ordinary way, you cannot upload in this directory. But if you create a directory using FTP, you can. So if you have safe_mode = on, this is the only way how to use SPARTACUS and Media uploads successfully.');
@define('PLUGIN_EVENT_SPARTACUS_FTP_SERVER', 'FTP server address');
@define('PLUGIN_EVENT_SPARTACUS_FTP_USERNAME', 'FTP username');
@define('PLUGIN_EVENT_SPARTACUS_FTP_PASS', 'FTP password');
@define('PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR', 'FTP serendipity directory');
@define('PLUGIN_EVENT_SPARTACUS_FTP_BASEDIR_DESC', 'When you connect to the FTP, you do not necessarily enter into the serendipity directory. So here it is necessary to write the path from the FTP login place to the serendipity directory.');

@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR', 'Custom location for mirror');
@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC', 'This option (normally) is for advanced users only. When the selectable mirror(s) are down or malfunctioning, you can enter your own server name (like https://mirror.org/styx/). The server needs to maintain the XML files at URL level, and have subdirectories like "additional_plugins" and "additional_themes". Only enter mirrors that you fully trust to be safe and which are a full duplicate of the files hosted in the Serendipity repository. You can enter multiple mirrors, separated by "|".');
@define('PLUGIN_EVENT_SPARTACUS_CUSTOMMIRROR_DESC_ADD', 'PLEASE NOTE: Styx orders items from the above set (Styx) GitHub repository location. If you ever need to switch back and to only use the selectable mirror(s) again, you have to remove the custom set URL on each submit of this configuration page, or add "none" instead (w/o quotes).');

@define('PLUGIN_EVENT_SPARTACUS_CRONJOB', 'This plugin supports the Serendipity Cronjob plugin. Go and install it if you want scheduled execution.');
@define('PLUGIN_EVENT_SPARTACUS_CRONJOB_WHEN', 'Execute regular cronjob to check for plugin updates, and mail those to the configured blog\'s mail address (%s)?');

@define('PLUGIN_EVENT_SPARTACUS_CSPRNG', ' (A random copy-ready string by loading this option page: "%s%s". Please take this "obfuscated" hash-string (w/o quotes) to use it remotely with the serendipity_event_plugup Plugin-Update notification plugin and allow the remote option above. Such page does not provide any details like name or version, like the origin "spartacus_remote" string and you don\'t need to care about further security.)');

@define('PLUGIN_EVENT_SPARTACUS_CHECK', 'Update plugins');

