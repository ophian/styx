<?php
/*
 * The info.txt native charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset, analog to the non-UTF-8 lang constant files.
 **/

$info['theme_info_summary'] = 'The Styx Backend/Frontend Standard Theme';

$info['theme_info_desc'] = 'This theme defines the current standard, defined and used by the core Serendipity Styx blog system.
Regarding Serendipitys infinite possibilities, it is the currently best-concatenated and interlocked theme,
covering most of them as simple as possible in a wide frame, made easy to look through.
<br><br>
<u><b>Theme building</b></u><br>
Copy an existing theme or add a new and unique directory name to the "templates/" directory, eg. "example".
Add an <b>info.txt</b> file with the components of 2k11 to start with. Change the name, eg. to "my Example" and add the current date.<br>
Give it an "Engine&colon; 2k11" line if you want it to fall back to 2k11, if not covering all template files itself.
If not having this line, you either need all template files for yourself, or regard your theme to fall back to the "Serendipity default" theme templates.<br><br>
Set additional lines "Require Serendipity&colon; 2.0", "Backend&colon; Yes",
if having an extra backend and "Recommended&colon; Yes", if wanting it to apply to the recommended template section.<br><br>
Back in your template list, reload the list page and select the new created theme.';

$info['theme_info_backend'] = 'This theme accommodates the Styx core backend templates in the "2k11/admin" subdirectory.
The template files in that directory build and create the look of the complete admin interface.
They also contain some workflow and logic, as well as the javascript libraries.
If you want to use your own backend theme, copy the "admin" directory to a theme.
In there, change the "info.txt" file to add a "Backend&colon; Yes" line and select the new backend theme in the newly loaded theme list.
From now on, you can edit the files and styles of own backend theme and adapt it to your extending needs.<br>
<u><b>Please Note&colon;</b></u> This only is recommended for highly experienced users and completely off the update track!';
