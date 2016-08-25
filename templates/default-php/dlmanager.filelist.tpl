<?php /* dlmanager.filelist.tpl last modified 2016-07-14 */ ?>
<div id="downloadmanager" class="serendipity_Entry_Date">
<!-- dlmanager.filelist.tpl start -->
    <h3 class="serendipity_date"><?= $GLOBALS['tpl']['pagetitle'] ?></h3>
    <h4 class="serendipity_title"><?= $GLOBALS['tpl']['headline'] ?></h4>
    <?php if (!empty($GLOBALS['tpl']['dlm_intro'])): ?><div class="dlm_intro"><?= $GLOBALS['tpl']['dlm_intro'] ?></div><?php endif; ?>

    <?php if ($GLOBALS['tpl']['dlm_is_registered'] == false || $GLOBALS['tpl']['is_logged_in']): ?>

        <ul class="plainList">
            <li><strong><?= PLUGIN_DOWNLOADMANAGER_CATEGORY ?>: <?= $GLOBALS['tpl']['catname'] ?></strong> [<a href="<?= $GLOBALS['tpl']['basepage'] ?>?serendipity[subpage]=<?= $GLOBALS['tpl']['pageurl'] ?>"><?= PLUGIN_DOWNLOADMANAGER_BACK ?>&hellip;</a>]</li>
            <li><?= PLUGIN_DOWNLOADMANAGER_SUBCATEGORIES ?>: <?= $GLOBALS['tpl']['numsubcats'] ?></li>
            <li><?= PLUGIN_DOWNLOADMANAGER_DLS_IN_THIS_CAT ?>: <?= $GLOBALS['tpl']['numdls'] ?></li>
        </ul>
        <?php if ($GLOBALS['tpl']['has_subcats']) : ?>

        <table id="subcatlist" cellspacing="0">
        <thead>
            <tr>
                <th><?= PLUGIN_DOWNLOADMANAGER_SUBCATEGORIES ?></th>
                <th><?= PLUGIN_DOWNLOADMANAGER_SUBCATEGORIES ?></th>
                <th class="last_column"><?= PLUGIN_DOWNLOADMANAGER_NUMBER_OF_DOWNLOADS ?></th>
            </tr>
        </thead>
        <tbody>
            <?php $i=0; foreach ($GLOBALS['tpl']['sclist'] AS $sclist): ?><?php /* <pre><?= '$sclist='.print_r($sclist, true) ?></pre> */ ?>

            <tr class="dlm_subcat <?= (($i % 2) ? "even" : "odd") ?>">
                <td><img src="<?= $GLOBALS['tpl']['httppath'] ?>img/f.png" width="20" height="20" alt=""/> <a href="<?= $sclist['node']['path'] ?>"><?= $sclist['subcat']['payload'] ?></a></td>
                <td><?= $sclist['subcat']['subcats'] ?></td>
                <td class="last_column"><?= $sclist['num'] ?></td>
            </tr>
            <?php $i++; endforeach; ?>

        </tbody>
        </table>
        <?php endif; ?>

        <table id="filelist" cellspacing="0">
        <thead>
            <tr>
            <?php if ($GLOBALS['tpl']['filename_field']) : ?>

                <th class="dlm_filelist_name"><?= $GLOBALS['tpl']['filename_field'] ?></th>
            <?php endif; ?>
            <?php if ($GLOBALS['tpl']['dls_field']) : ?>

                <th class="dlm_filelist_dls"><?= $GLOBALS['tpl']['dls_field'] ?></th>
            <?php endif; ?>
            <?php if ($GLOBALS['tpl']['filesize_field']) : ?>

                <th class="dlm_filelist_size"> <?= $GLOBALS['tpl']['filesize_field'] ?></th>
            <?php endif; ?>
            <?php if ($GLOBALS['tpl']['filedate_field']) : ?>

                <th class="dlm_filelist_date"> <?= $GLOBALS['tpl']['filedate_field'] ?></th>
            <?php endif; ?>

            </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach ($GLOBALS['tpl']['fltable'] AS $fltable): ?><?php /* <pre><?= '$fltable='.print_r($fltable, true) ?></pre> */ ?>

            <tr class="dlm_file <?= (($i % 2) ? "even" : "odd") ?>">
                <td class="dlm_filename">
                    <a href="<?= $fltable['info']['iconurl'] ?>" class="dlm_fileicon"><img src="<?= $fltable['info']['iconfile'] ?>" width="<?= $fltable['info']['iconwidth'] ?>" height="<?= $fltable['info']['iconheight'] ?>" alt="<?= $fltable['info']['icontype'] ?>" title="<?= $fltable['info']['icontype'] ?>" /></a><?php if ($fltable['is']['showfilename']): ?> <a href="<?= $fltable['info']['nameurl'] ?>" class="dlm_filename" title="<?= $fltable['file']['realfilename'] ?>"><?= $fltable['file']['realfilename'] ?></a><?php endif; ?><?php if ($fltable['is']['showdesc_inlist'] && $fltable['info']['file_desc']): ?> <span class="dlm_filedesc"><?= $fltable['info']['file_desc'] ?><?php endif; ?>

                </td>
                <?php if ($fltable['is']['showdownloads']) : ?>

                <td class="dlm_filedlds"><?= $fltable['file']['dlcount'] ?></td>
                <?php endif; ?>
                <?php if ($fltable['is']['showfilesize']) : ?>

                <td class="dlm_filesize"><?= $fltable['info']['filesize'] ?></td>
                <?php endif; ?>
                <?php if ($fltable['is']['showdate']) : ?>

                <td class="dlm_filedate"><?= $fltable['info']['filedate'] ?></td>
                <?php endif; ?>

            </tr>
        <?php $i++; endforeach; ?>

        </tbody>
        </table>
    <?php else: ?>

        <div class="dlm_info"><?= PLUGIN_DOWNLOADMANAGER_REGISTERED_ONLY_ERROR ?></div>
    <?php endif; ?>

<!-- dlmanager.filelist.tpl end -->
</div>
