<?php
/*
 * The info.txt native charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset, analogue to the non-UTF-8 lang constant files.
 **/

$info['theme_info_summary'] = 'The Serendipity Styx base template.';

$info['theme_info_desc'] = 'A frontend theme, newly revisited for Styx to build it a fully responsive (3-2-1) HTML5 theme,
without changing too much of the "old school" html(4) markup.<br>
It works as a full styled fallback for the PHP and XML Engine and as a base file fallback pool for several other themes.<br>
<br>
In difference to the Serendipity <b>standard</b> template(s) (earlier "bulletproof", later on "2k11", now "Pure &lsaquo; 2020 &rsaquo;") this theme is the ground system basement,
unless special instructions (*) or internal reasons force differently, i.e. when something was searched for compile and not in your usual
theme or the normal fallback theme cascade.<br>
<br>
<span class="footnote">[*] In mean, when no "Engine" (in info.txt) and no Serendipity stylesheet-file (style.css) is set, like for the "default-php" or "default-xml" themes.
for the backend templates and a backup and error fallback theme.</span>';

$info['theme_info_backend'] = 'This theme accommodates the Styx core backend templates in the "default/admin" subdirectory.
The template files in that directory build and create the look of the complete admin interface.
They also contain some workflow and logic, as well as the javascript libraries.
If you want to use your own backend theme, copy the "admin" directory to a theme.
In there, change the "info.txt" file to add a "Backend&colon; Yes" line and select the new backend theme in the newly loaded theme list.
From now on, you can edit the files and styles of own backend theme and adapt it to your extending needs.<br>
<u><b>Please Note&colon;</b></u> This only is recommended for highly experienced users and completely off the update track!';
