<?php

@define('PLUGIN_EVENT_MLORPHANS_NAME', 'Manage orphans');
@define('PLUGIN_EVENT_MLORPHANS_DESC', 'Enables to research the MediaLibrary for image orphans against entries, when having at least 100 images. In Plugin-List place before other maintenance plugins (spamblock, modemaintain, changelog).');
@define('PLUGIN_EVENT_MLORPHANS_SUBMIT', 'Search image orphans');

@define('MLORPHAN_MTASK_ML_REAL_IMAGES', 'The MediaLibrary contains %d real local image items.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_MATCHES', 'Found: <b>%d</b> ID flagged img src items in entries and staticpage as ($im) array items (<em>doubles are possible</em>).');
@define('MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR', 'Please fix: Entry: %d  [<em>%s</em> textarea field] for a <em>probably</em> <b>wrong</b> used "<code>&lt;-- s9ymdb: %d --&gt;</code>" tag for an image src "<em>%s</em>" path first and then run this script again. To do this, check and search the correct image ID in your MediaLibrary, i.e. by last part in GET String ( &serendipity[fid]=XX ) of the mentioned file media properties URL, and/or down below inside the collapsible orphaned "view array" box by "<em>%s</em>" for the [id].');
@define('MLORPHAN_MTASK_MAIN_PATTERN_RESULTCHECK_ACTION', '<em>Check against false set image s9ymdb:IDs and/or wrong image name(s)... and remove found images from array...</em>');
@define('MLORPHAN_MTASK_PNCASE_REVERSECHECK_ACTION', '<em>Last paranoid reverse check against database content... and possible quickblog image tags...</em>');
@define('MLORPHAN_MTASK_PNCASE_TITLE', 'Paranoid re-check of found orphaned image IDs');
@define('MLORPHAN_MTASK_PNCASE_NOTE', '<em>that may still reside in entries or staticpages. This probably are some unknown regex pattern match-, or non-blog path cases.</em><br>If you want, check these images in your entries and run this script again.');
@define('MLORPHAN_MTASK_PNCASE_EXCLUDING', 'Excluding these image IDs: (<b>%s</b>) from orphan array.');
@define('MLORPHAN_MTASK_PNCASE_OK', 'Looks all good to me...');
@define('MLORPHAN_MTASK_FOUND_IMAGE_ORPHANS', 'Found: <b>%d</b> remaining and probably unused image orphan array items left.');
@define('MLORPHAN_MTASK_REMOVE_IMAGE_ORPHANS', 'You are now able to remove %d orphaned items from images!');
@define('MLORPHAN_MTASK_LAST_ACTION_NOTE', '<b>Take care:</b> <span>There <b>may</b> still be some of these images used in your entries <b>without</b> a <code>&lt;-- s9ymdb:N --&gt;</code> MediaLibrary tag. If being unsure about that, do not delete here or better re-/add the correct tag IDs in these entries and run this script again. (Recommended!)</span><br>
    <span>This orphan manager task also does <b>not</b> care about entryproperties (plugin) database cached entries. If you had to correct existing, but false set image s9ymdb:IDs (see possible error messages on top) in articles manually, this also applies to entryproperties stored cache entries. If you need and use that (but this shouldn\'t be necessary these days), run the maintenance entryproperties build cache again afterwards, which may lead to other issues such as NL2BR and line breaks in very old articles.</span>');
@define('MLORPHAN_MTASK_PURGED_SUCCESS', 'Successfully deleted images by ID: (<b>%s</b>) from MediaLibrary filesystem and database.');
@define('MLORPHAN_MTASK_RESPECTIVELY', 'respectively');
@define('MLORPHAN_MTASK_MAIN_PATTERN_NAME_WARNING', 'ATTENTION: Images are not allowed to have a dot in the name! Previous Serendipity Versions did not check this on upload! Avoid such occurrences in your stocks, otherwise MediaLibrary actions such as moves / renames / etc. can lead to consequences that are difficult to repair!');

