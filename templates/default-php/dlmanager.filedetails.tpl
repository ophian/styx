<?php /* dlmanager.filedetails.tpl last modified 2016-07-06 */ ?>
<div id="downloadmanager" class="serendipity_Entry_Date">
<!-- dlmanager.filedetails.tpl start -->
    <h3 class="serendipity_date"><?= $GLOBALS['tpl']['pagetitle'] ?></h3>
    <h4 class="serendipity_title"><?= $GLOBALS['tpl']['headline'] ?></h4>
    <?php if (!empty($GLOBALS['tpl']['dlm_intro'])): ?><div class="dlm_intro"><?= $GLOBALS['tpl']['dlm_intro'] ?></div><?php endif; ?>

    <?php if ($GLOBALS['tpl']['dlm_is_registered'] == false || $GLOBALS['tpl']['is_logged_in']) : ?>
        <?php if ($GLOBALS['tpl']['showfile']) : ?>

        <ul class="plainList">
            <li><strong><?= PLUGIN_DOWNLOADMANAGER_CATEGORY ?>: <?= $GLOBALS['tpl']['catname'] ?></strong> [<a href="<?= $GLOBALS['tpl']['basepage'] ?>?serendipity[subpage]=<?= $GLOBALS['tpl']['pageurl'] ?>&amp;thiscat=<?= $GLOBALS['tpl']['catid'] ?>"><?= PLUGIN_DOWNLOADMANAGER_BACK ?>&hellip;</a>]</li>
            <li><strong><?= PLUGIN_DOWNLOADMANAGER_SUBCATEGORIES ?>:</strong> <?= $GLOBALS['tpl']['num_subcats'] ?></li>
            <li><strong><?= PLUGIN_DOWNLOADMANAGER_DLS_IN_THIS_CAT ?>:</strong> <?= $GLOBALS['tpl']['num_files'] ?></li>
        </ul>

        <h5><?= PLUGIN_DOWNLOADMANAGER_THIS_FILE ?>:</h5>
        <?php /* $thisfile is a single array without index - no need for loops */ ?>
        <?php if (is_array($GLOBALS['tpl']['thisfile'])): ?>

        <dl>
            <dt><img src="<?= $GLOBALS['tpl']['thisfile']['iconfile'] ?>" width="<?= $GLOBALS['tpl']['thisfile']['iconwidth'] ?>" height="<?= $GLOBALS['tpl']['thisfile']['iconheight'] ?>" alt="<?= $GLOBALS['tpl']['thisfile']['icontype'] ?>" title="<?= $GLOBALS['tpl']['thisfile']['icontype'] ?>" /> <?= $GLOBALS['tpl']['thisfile']['filename'] ?></dt>
            <dd><strong><?= PLUGIN_DOWNLOADMANAGER_EDIT_FILE_DESC ?>:</strong> <?= strip_tags($GLOBALS['tpl']['thisfile']['description']) ?></dd>
            <dd><strong><?= PLUGIN_DOWNLOADMANAGER_NUM_DOWNLOADS_BLAH ?>:</strong> <?= $GLOBALS['tpl']['thisfile']['dlcount'] ?></dd>
            <dd><strong><?= $GLOBALS['tpl']['thisfile']['filesize_field'] ?>:</strong> <?= $GLOBALS['tpl']['thisfile']['filesize'] ?></dd>
            <dd><strong><?= $GLOBALS['tpl']['thisfile']['filedate_field'] ?>:</strong> <?= $GLOBALS['tpl']['thisfile']['filedate'] ?></dd>
        </dl>

        <h5><?= PLUGIN_DOWNLOADMANAGER_DOWNLOAD_FILE ?></h5>

        <div id="dlm_button"><a href="<?= $GLOBALS['tpl']['thisfile']['dlurl'] ?>"><img src="<?= $GLOBALS['tpl']['httppath'] ?>/img/download.png" alt="Download" /></a></div>
        <?php endif; ?>
        <?php endif; ?>
    <?php else: ?>

        <div class="dlm_info"><?= PLUGIN_DOWNLOADMANAGER_REGISTERED_ONLY_ERROR ?></div>
    <?php endif; ?>

<!-- dlmanager.filedetails.tpl end -->
</div>
