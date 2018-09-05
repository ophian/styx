<?php
/*
 * The info.txt native charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset, analogue to the non-UTF-8 lang constant files.
 **/

$info['theme_info_summary'] = 'The Styx default template. Previously named Serendipity v2.3 (w/o relation to core version number).';

$info['theme_info_desc'] = 'New revisited for Styx 2.1 to make it fully responsive (3-2-1),
without changing much of the "old school" html. Works additionally as a full styled fallback
for the PHP and XML Engine and as a file fallback pool for several other themes.
This theme previously wasn\'t meant to be a theme out of hundrets.
Since "bulletproof" and later on "2k11" became the Serendipity <b>standard</b> template(s),
this theme was used as the default backend, a backup and error fallback theme, when something was searched for compile
and not in your usual theme or the fallback theme cascade. It also is chosen
if no engine and no style is set, like for the default-php and/or default-xml themes.';

$info['theme_info_backend'] = 'This theme accommodates the Styx core backend templates in the "default/admin" subdirectory.
The template files in that directory build and create the look of the complete admin interface.
They also contain some workflow and logic, as well as the javascript libraries.
If you want to use your own backend theme, copy the "admin" directory to a theme.
In there, change the "info.txt" file to add a "Backend&colon; Yes" line and select the new backend theme in the newly loaded theme list.
From now on, you can edit the files and styles of own backend theme and adapt it to your extending needs.<br>
<u><b>Please Note&colon;</b></u> This only is recommended for highly experienced users and completely off the update track!';
