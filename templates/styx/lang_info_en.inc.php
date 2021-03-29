<?php
/*
 * The info.txt native charset (translation) file as an array.
 * Save as lang_info_xx.inc.php and replace 'xx' with your short lang term, defined and set in $serendipity['lang'].
 * Convert or save as ANSI (ISO-8859-1) or your native charset, analogue to the non-UTF-8 lang constant files.
 **/

$info['theme_info_summary'] = 'The Styx Backend (example) default Theme';

$info['theme_info_desc'] = '<u>Please Note&colon;</u> This theme has no existing frontend!<br>
This example backend theme relegates to the Styx core backend templates in the "default/admin" subdirectory.
As an example it proves the ability that you can simply add a more or less template-empty backend theme, which uses the fallback to the standard "default" backend template files.
With this in mind you are able to just change and add those files only, which are necessary to fill your needs.';

$info['theme_info_backend'] = 'This example backend theme relegates to the Styx core backend templates in the "default/default" subdirectory.
As an example it proves the ability that you can simply add a more or less empty backend theme, which uses the fallback to the "default" backend template files.
With this in mind you are able to just change and add those files only, which are necessary to fill your needs.
This example may change in future to hold some more real files for the backend generation.
For the moment it solely includes an index template file, which removes relevant informations and assets when not logged in.
And it has its own styles markup, equivalent to the "default/admin/styles.css" file, which makes the following continuously evolving enhancements to the backend:
<pre>
* 2017-08-21 - Newly fixes a UI breaking issue with the rtl (right-to-left) direction attribute in the &lt;html&gt; element.
* 2019-09-08 - Removed the IE8/9 workarounds.
* 2020-03-25 - Sign out Styx message styles with a "marked" left border.
* 2020-06-06 - Smaller font-size of entry_info styles of entries list.
* 2020-08-31 - Improved login page and update fullsize preview.
* 2020-10-31 - Add SVG icon to the plugin added fieldset legends of entryform.
* 2020-12-06 - Allow iPhone 5/SE with 320px sizes for media filter datetime pick-fields.
* 2021-02 / 2021-03 - Small Improvements to the CSS styles.
* 2021-03-26 - Entries/Comments filter toolbars are shifted to the right up from 360px screen width.
* 2021-03-29 - Extended with a right floated toolbar group for Plugins/Themes/Personal-Preferences configurations, including at least a "toggle all" (containers) button.
</pre>

THE FOLLOWING IS THE DESCRIPTION OF THE RELEGATED BACKEND&colon;<br>
The template files in that directory build and create the look of the complete admin interface.
They also contain some workflow and logic, as well as the javascript libraries.
If you want to use your own backend theme, copy the "admin" directory to a theme.
In there, change the "info.txt" file to add a "Backend&colon; Yes" line and select the new backend theme in the newly loaded theme list.
From now on, you can edit the files and styles of own backend theme and adapt it to your extending needs.<br>
<u><b>Please Note&colon;</b></u> This only is recommended for highly experienced users and completely off the update track!';
