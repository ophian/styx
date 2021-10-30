<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_SPAMBLOCK_TITLE', 'Spam Protector');
@define('PLUGIN_EVENT_SPAMBLOCK_DESC', 'A variety of methods to prevent comment Spam. This is the cores backbone of Anti-Spam measures. Do not remove!');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY', 'Spam Prevention: Invalid message.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_IP', 'Spam Prevention: You cannot post a comment so soon after submitting another one.');

@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH', 'This blog is in "Emergency Comment Blockage Mode", please come back another time');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE', 'Do not allow duplicate comments');
@define('PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC', 'Do not allow users to submit a comment which contains the same body as an already submitted comment');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH', 'Emergency comment shutdown');
@define('PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC', 'Temporarily disable comments for all entries. Useful if your Blog is under Spam attack.');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD', 'IP block interval');
@define('PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC', 'Only allow an IP to submit a comment every n minutes. Useful to prevent comment floods.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS', 'Enable Captchas');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC', 'Will force the user to input a random string displayed in a specially crafted image. This will disallow automated submits to your Blog. Please remember that people with decreased vision may find it hard to read those Captchas. To avoid having to use Captchas at all, try out the extending Serendipity Spamblog Bee plugin.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC', 'To prevent automated Bots from comment-spamming, please enter the string you see in the image below in the appropriate input box. Your comment will only be submitted if the strings match. Please ensure that your browser supports and accepts cookies, or your comment cannot be verified correctly.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2', 'Enter the string you see here in the input box!');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3', 'Enter the string from the Spam-prevention image above: ');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS', 'You did not enter the correct string displayed in the Spam-prevention image box. Please look at the image and enter the values displayed there.');
@define('PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF', 'Captchas disabled on your server. You need GDLib and freetype libraries compiled to PHP, and need the .TTF files residing in your directory.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL', 'Force Captchas after X days');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC', 'Captchas can be enforced depending on the age of your articles. Enter the amount of days after which entering a correct Captcha is necessary. If set to 0, Captchas will always be used.');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION', 'Force comment moderation after X days');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC', 'You can automatically set all incoming comments for entries to state "moderation". Enter the age of an entry in days, after which it should be set "auto-moderated". 0 means no "auto-moderation".');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE', 'How many links before a comment gets moderated');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC', 'When a comment reaches a certain amount of links, that comment can be set to be moderated. 0 means that no link-checking is done.');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT', 'How many links before a comment gets rejected');
@define('PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC', 'When a comment reaches a certain amount of links, that comment can be set to be rejected. 0 means that no link-checking is done.');

@define('PLUGIN_EVENT_SPAMBLOCK_NOTICE_MODERATION', 'Because of some conditions, your comment has been marked to require moderation by the owner of this Blog.');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR', 'Background color of the Captcha');
@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC', 'Enter RGB values: 0,255,255');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE', 'Logfile location');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC', 'Information about rejected/moderated posts can be written to a logfile. Set this to an empty string if you want to disable logging.');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH', 'Emergency Comment Blockage');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE', 'Duplicate comment');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD', 'IP-block');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS', 'Invalid Captcha (Entered: %s, Expected: %s)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION', 'Auto-moderation after X days');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT', 'Too many hyperlinks');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE', 'Too many hyperlinks');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL', 'Hide E-Mail addresses of commenting users');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC', 'Will show no E-Mail addresses of commenting users');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE', 'E-Mail addresses will not be displayed and will only be used for E-Mail notifications.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE', 'Choose logging method');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC', 'Logging of rejected comments can be done in Database or to a plaintext file');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE', 'File (see "logfile" option below)');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB', 'Database');
@define('PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE', 'No Logging');

@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS', 'How to treat comments made via APIs');
@define('PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC', 'This affects the moderation of comments made via API calls (trackbacks, pingbacks, WFW:commentAPI comments). If set to "moderate", all those comments always need to be approved first. If set to "reject", the are completely disallowed. If set to "none", the comments will be treated as usual comments.');
@define('PLUGIN_EVENT_SPAMBLOCK_API_MODERATE', 'moderate');
@define('PLUGIN_EVENT_SPAMBLOCK_API_REJECT', 'reject');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_API', 'No API-created comments allowed');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_DATE', 'No trackbacks allowed outside of time frame');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE', 'Activate wordfilter');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC', 'Searches comments for certain strings and marks them as Spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS', 'Wordfilter for URLs');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC', 'Regular Expressions allowed, separate strings by semicolons (;). You have to escape the @-sign with \\@.');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS', 'Wordfilter for author names');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS', 'Wordfilter for comment body');

@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS', 'Wordfilter for comment E-mail');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL', 'Invalid e-mail address');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL', 'Check e-mail addresses?');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS', 'Required comment fields');
@define('PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC', 'Enter a list of required fields that need to be filled when a user comments. Separate multiple fields with a ",". Available keys are: name, email, url, replyTo, comment');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD', 'You did not specify the %s field!');

@define('PLUGIN_EVENT_SPAMBLOCK_CONFIG', 'Configure Anti-Spam methods');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR', 'Block this author via Spamblock plugin');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_URL', 'Block this URL via Spamblock plugin');
@define('PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL', 'Block this E-mail via Spamblock plugin');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR', 'Unblock this author via Spamblock plugin');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL', 'Unblock this URL via Spamblock plugin');
@define('PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL', 'Unblock this E-mail via Spamblock plugin');

@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE', 'Entry title equals comment');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE', 'Reject comments which only contain known text');
@define('PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE_DESC', 'Comments, which only hold the entries title, or a combination of the blogs- and the entry title, often used by Bots.');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL', 'Check trackback/pingback URLs');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC', 'Only allow trackbacks/pingbacks, when its URL contains a link to your Blog');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL', 'Trackback/Pingback URL invalid.');

@define('PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_SCRAMBLE', 'Scrambled Captchas');

@define('PLUGIN_EVENT_SPAMBLOCK_HIDE', 'Disable spamblock for Authors');
@define('PLUGIN_EVENT_SPAMBLOCK_HIDE_DESC', 'You can allow authors in the following usergroups to post comments without them being checked by the spamblock plugin.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET', 'Akismet API Key');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_DESC', 'Akismet is an anti-spam and blacklisting protocol. It can analyze your incoming comments and check if that comment has been listed as Spam. Two servers are supported: the original Akismet server, and the TypePad Antispam (TPAS) Open Source server. To use the Akismet server, you must register for an account at http://www.wordpress.com/. To use the TPAS server, you must obtain a free key from http://antispam.typepad.com/. If you leave the key blank, Akismet will not be used to check spam.');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_FILTER', 'How to treat Akismet-reported Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST', 'Filtered by Akismet.com Blacklist');

@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_TREAT', 'Comments classification after being auto-moderated?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT', 'Classification after being auto-moderated?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT_DESC', 'What to do with trackbacks/pingbacks when being auto-moderated?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT', 'Force API comment moderation after X days');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_DESC', 'You can automatically set all incoming trackbacks/pingbacks for entries to state "moderation". Enter the age of an entry in days, after which it should be set "auto-moderated". 0 means no "auto-moderation".');

@define('PLUGIN_EVENT_SPAMBLOCK_CSRF', 'Forbid direct comments (XSRF protection)');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_DESC', 'If enabled, visitors are not allowed to submit a comment when visiting your articles directly. This can block spambots, but also people who are commenting from their RSS readers or who have cookies disabled. This protection is implemented by setting a special hash field, which will only exist when a valid session was already started. This will also protect you from XSRF attacks that could trick you into submitting comments under false pretenses.');
@define('PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON', 'Your comment did not contain a Session-Hash. Comments can only be made on this Blog when having cookies enabled and visiting at least one URL before commenting!');

@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS', 'Block bad IPs via HTaccess?');
@define('PLUGIN_EVENT_SPAMBLOCK_HTACCESS_DESC', 'Enabling this will add IPs that have sent Spam to your Blog to your .htaccess file. The .htaccess file will be regenerated regularly with the forbidden IPs of the last month.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOOK', 'This is how your Captcha images currently look like. If you changed and saved settings above and want to refresh the look of your Captcha, simply click on it to reload.');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION', 'Trackback/Pingback: ip validation');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_DESC', 'Should the IP of the sender match the IP of the host, a trackback/pingback is set to? (RECOMMENDED!)');
@define('PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION', 'IP validation: %s [%s] != sender ip [%s]'); // don't translate, since being a log entry which might get checked by another plugin!

@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_DESC', 'If disabled, no email checking will be performed. If set to "Yes", the commenting user must supply a valid e-mail address. If set to "Confirm always", the commenting user will need to approve his comments always via email (by clicking a mailed link). If set to "Confirm once", the user has to confirm his comment once and will then always be allowed to pass comment moderation.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ONCE', 'Confirm once');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ALWAYS', 'Confirm always');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL', 'You will now receive an email notification with which you can approve your comment.');
@define('PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_INFO', 'To leave a comment you must approve it via e-mail, which will be sent to your address after submission.');

@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER', 'Anti-spam server');
@define('PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER_DESC', 'Which server is the above key registered for? Anonymized means that all data submitted to the services is stripped of the usernames and mail addresses. This also reduces Spam detection rates, though.');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS', 'TypePad Antispam');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET', 'Original Akismet');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS_ANON', 'TypePad Antispam (anonymized)');
@define('PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET_ANON', 'Original Akismet (anonymized)');

@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE', 'Exclude URLs from IP Validation');
@define('PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE_DESC', 'URLs to be excluded from IP Validation. ' . PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);

@define('PLUGIN_EVENT_SPAMBLOCK_SPAM', 'Spam');
@define('PLUGIN_EVENT_SPAMBLOCK_NOT_SPAM', 'Not spam');

@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_TITLE', 'Spamblock maintenance');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN', 'Cleanup of database Spam-Logs');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN_DESC', 'Cleanups database logentries, which apply to certain criteria. This should be done periodically (once or twice a year), since SPAMmers continuously blow up your spamblock logs and reduce the speed of your Blog. According to experience, depending on the settings of the current Spamblog plugins, these two types (moderate, rejected) are mainly equipped with Spam.');

@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_BUTTON', 'Cleanup all');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_DESC', 'Cleanup <b>all</b> log-entries of type: \'REJECTED\'; And type: \'MODERATE\' in database.table "spamblocklog" which have an empty comment field. Currently %d items of these types are available.');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_BUTTON', 'Cleanup items');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_DESC', 'Cleanup individually by <b>multi</b> selected or <b>single</b> from table field "reason" LIKE "items". Applies for the types: \'REJECTED\' and \'MODERATE\'!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MSG_DONE', 'Cleanup successfully done!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SELECT', 'Single select by criteria');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_BUTTON', 'Debug Log-logging');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_DESC', 'Write all possible cleanup spamblocklog entries into the current debug log file. (See Maintenance ... Serendipity Logfiles.) Don\'t do this when not specifically in need, since this may get quite huge!');
@define('PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_LOGMSG_DONE', 'Written to Debug Logger output!');

@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC', 'Time frame for comments within X days');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_DESC', 'The comment (form) function of an article can be permitted generally for a limited period of X days starting from the article date, in order to avoid that old entry contributions are flooded with bogus comments. This might be a valid optional request for a Blog. The default value is "0" and allows (user) comments to any existing article without age limit (if not restricted by other options).');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT', 'Shall this time frame block later trackbacks too?');
@define('PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT_DESC', '"Yes" will block and reject potentially valid Trackbacks/Pingbacks, which income to entries after this time frame closed. (Other option actions may influence this behaviour.)');

@define('PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION', 'Main-Configuration');
@define('PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION_DESC', 'The naming is internally differentiated in (user) comments and (blog) trackbacks/pingbacks. In general, the word "comment(s)" is used for both manifestations here, unless a separate distinction (cf. "Trackbacks") or reference is made.');

@define('PLUGIN_EVENT_SPAMBLOCK_LOGFILE_VALIDATE', 'Only file extensions .log and .txt are allowed');

