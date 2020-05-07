<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_HISTORY_NAME', 'History');
@define('PLUGIN_HISTORY_DESC', 'Displays ancient entries of an adjustable age.');
@define('PLUGIN_HISTORY_MIN_AGE', 'Min age');
@define('PLUGIN_HISTORY_MIN_AGE_DESC', 'Minimum age of entries (in days).');
@define('PLUGIN_HISTORY_MAX_AGE', 'Max age');
@define('PLUGIN_HISTORY_MAX_AGE_DESC','Maximum age of entries (in days).');
@define('PLUGIN_HISTORY_MAX_ENTRIES', 'Maximum entries');
@define('PLUGIN_HISTORY_MAX_ENTRIES_DESC', 'Number of entries to display. In case of a defined time range with "Number of looped years" greater 1, this really limits the total maximum of Entries to display.');
@define('PLUGIN_HISTORY_SHOWFULL', 'Full entries');
@define('PLUGIN_HISTORY_SHOWFULL_DESC', 'Display full entries instead of linked headlines.');
@define('PLUGIN_HISTORY_INTRO', 'Intro');
@define('PLUGIN_HISTORY_INTRO_DESC', 'A short intro like \'One year ago I said:\'.');
@define('PLUGIN_HISTORY_OUTRO', 'Outro');
@define('PLUGIN_HISTORY_OUTRO_DESC', 'A short outro like \'Nice, eh?\'.');
@define('PLUGIN_HISTORY_DISPLAYDATE', 'Display date');
@define('PLUGIN_HISTORY_DISPLAYDATE_DESC', 'Display the date of each entry?');
@define('PLUGIN_HISTORY_MAXLENGTH', 'Title-Length');
@define('PLUGIN_HISTORY_MAXLENGTH_DESC', 'After how many characters to cut the titles (0 for full titles)?');
@define('PLUGIN_HISTORY_SPECIALAGE', 'Ready-made age?');
@define('PLUGIN_HISTORY_SPECIALAGE_DESC', 'If you want to define your own time range instead of a ready-made, select \'I\'ll define one\' here and adjust the two settings below.');
@define('PLUGIN_HISTORY_SPECIALAGE_YEAR', 'Display items of exactly one year ago');
@define('PLUGIN_HISTORY_CUSTOMAGE', 'Let me define the age');
@define('PLUGIN_HISTORY_OYA', 'One year ago');
@define('PLUGIN_HISTORY_MYSELF', 'I\'ll define one');
@define('PLUGIN_HISTORY_DISPLAYAUTHOR', 'Show author\'s name');

@define('PLUGIN_HISTORY_MULTIYEARS', 'Number of looped years');
@define('PLUGIN_HISTORY_MULTIYEARS_DESC', 'Set a new number of years to loop through, when having selected the time range "one year ago". Default is 1 (the current year). Set upper "Min age" and "Max age" entries to exact 365 days. In case of multi years, all history sidebar output - inclusive possible intro/outro - will be cached for performance reasons inside a "templates_c/history_daylist.dat" file for the rest of the day.');

