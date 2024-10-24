<?php

@define('PLUGIN_EVENT_MLORPHANS_NAME', 'Manage orphans');
@define('PLUGIN_EVENT_MLORPHANS_DESC', 'Enables to research the MediaLibrary for image orphans against entries, when having at least 100 images. In Plugin-List place before other maintenance plugins (spamblock, modemaintain, changelog).');
@define('PLUGIN_EVENT_MLORPHANS_SUBMIT', 'Search image orphans');

@define('MLORPHAN_MTASK_ML_REAL_IMAGES', 'The MediaLibrary contains %d real local image items.');
@define('MLORPHAN_MTASK_MAIN_PATTERN_MATCHES', 'Found: <b>%d</b> ID flagged img src items in entries and staticpages as ($im) array items (<em>doubles are possible</em>).');
@define('MLORPHAN_MTASK_MAIN_PATTERN_ID_ERROR', 'Please fix: Entry: %d  [<em>%s</em> textarea field] for a <em>probably</em> <b>wrong</b> used "<code>&lt;-- s9ymdb: %d --&gt;</code>" tag for an image src "<em>%s</em>" path first and then run this script again. To do this, check and search the correct image ID in your MediaLibrary, i.e. by last part in GET String ( &serendipity[fid]=XX ) of the mentioned file media properties URL, and/or down below inside the collapsible orphaned "view array" box by "<em>%s</em>" for the [id].');
@define('MLORPHAN_MTASK_MAIN_PATTERN_RESULTCHECK_ACTION', '<em>Check against false set image s9ymdb:IDs and/or wrong image name(s)... and remove found images from array...</em>');
@define('MLORPHAN_MTASK_PNCASE_REVERSECHECK_ACTION', '<em>Last paranoid reverse check against database content... and possible quickblog image tags...</em>');
@define('MLORPHAN_MTASK_PNCASE_TITLE', 'Paranoid re-check of found orphaned image IDs');
@define('MLORPHAN_MTASK_PNCASE_NOTE', '<em>that may still reside in entries or staticpages. This probably are some unknown regex pattern match-, or non-blog path cases.</em><br>If you want, check these images in your entries and run this script again.<br>To do this, copy the "s9ymdb" image ID, e.g. "49", and then search for an "s9ymdb:49" tag in the two textareas (source) of the named entry ID (e.g. 8). Take a close look at the paths. If they do point to your blog, check the IMG ID 49 in your MediaLibrary for the corresponding image name. Then you will know if something is incorrectly wired or assigned in your blog content pages that needs to be renewed.');
@define('MLORPHAN_MTASK_PNCASE_EXCLUDING', 'Excluding these image IDs: (<b>%s</b>) from orphan array.');
@define('MLORPHAN_MTASK_PNCASE_OK', 'Looks all good to me...');
@define('MLORPHAN_MTASK_FOUND_IMAGE_ORPHANS', 'Found: <b>%d</b> remaining and probably unused image orphan array items left.');
@define('MLORPHAN_MTASK_REMOVE_IMAGE_ORPHANS', 'You are now able to remove %d orphaned items from images!');
@define('MLORPHAN_MTASK_LAST_ACTION_NOTE', '<b>Take care:</b> <span>There <b>may</b> still be some of these images used in your entries <b>without</b> a <code>&lt;-- s9ymdb:N --&gt;</code> MediaLibrary tag. If being unsure about that, do not delete here or better re-/add the correct tag IDs in these entries and run this script again. (Recommended!)</span><br>
    <span>This orphan manager task also does <b>not</b> care about entryproperties (plugin) database cached entries. If you had to correct existing, but false set image s9ymdb:IDs (see possible error messages on top) in articles manually, this also applies to entryproperties stored cache entries. If you need and use that (but this shouldn\'t be necessary these days), run the maintenance entryproperties build cache again afterwards, which may lead to other issues such as NL2BR and line breaks in very old articles.</span>');
@define('MLORPHAN_MTASK_PURGED_SUCCESS', 'Successfully deleted images by ID: (<b>%s</b>) from MediaLibrary filesystem and database.');
@define('MLORPHAN_MTASK_RESPECTIVELY', 'respectively');
@define('MLORPHANS_MTASK_EXPATH', 'With an external image path: "%s"');
@define('MLORPHANS_MTASK_REASONS', 'Of these, (<b>%d</b>) occurrences were found with "external" IMG paths and another (<b>%d</b>) with unknown reasons. The latter with manual verification required in principle! See following:');
@define('MLORPHAN_MTASK_MAIN_PATTERN_NAME_WARNING', 'ATTENTION: Images are not allowed to have a dot in the name! Previous Serendipity Versions did not check this on upload! Avoid such occurrences in your stocks, otherwise MediaLibrary actions such as moves / renames / etc. can lead to consequences that are difficult to repair!');

@define('MLORPHAN_MTASK_MCHECKED_OK', '<strong>Attention</strong>: When having verified the possible replacements marked underneath the red outlined issue boxes and found them trustable, please manually check the yellow list marked entries in the notice-box before this text, so these only are media DB items to real exclude from the "im" array. If you have made changes manually, this page must be updated. Then you can proceed using this auto fix submit. Better make a last database backup right before so you have a real last backup state in case of issues.');
@define('MLORPHAN_MTASK_POST_PREAUTOFIX_HEAD', 'Automatic helper task in case of red marked errors on top of page...');
@define('MLORPHAN_MTASK_AUTOFIX', 'Automatic correction');
@define('MLORPHAN_MTASK_POST_AUTOFIX', 'After the automated correction, you should call up this page again¹ to check that the red marked issues are gone. (¹ Via the maintenance page. Do NOT simply reload!)');
@define('MLORPHAN_MTASK_POST_AUTOFIX_THXHEAD', 'Automated correction. Please read the following:');
@define('MLORPHAN_MTASK_POST_AUTOFIX_THX', 'No capture \'return\' key in "im" array left. Thanks for "automated correction" usage.');
@define('MLORPHAN_MTASK_MULTIPOST_AUTOFIX', 'For cases where matches¹ and fixes were not being greedy enough, run this all from the beginning again (and then again) as long there are thrown red errors with possible replacement string parts, please. You will notice that errors and replacements are slowly getting less (reading upwards) until at the end they all are captured. Depending on the initial situation in your entries, this repetition may be necessary many times.<br>[¹ <em>on purpose !</em> ]');

