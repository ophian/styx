<?php /* dlmanager.catlist.tpl last modified 2016-07-06 */ ?>
<div id="downloadmanager" class="serendipity_Entry_Date">
<!-- dlmanager.catlist.tpl start -->
    <h3 class="serendipity_date"><?= $GLOBALS['tpl']['pagetitle'] ?></h3>
    <h4 class="serendipity_title"><?= $GLOBALS['tpl']['headline'] ?></h4>
    <?php if (!empty($GLOBALS['tpl']['dlm_intro'])): ?><div class="dlm_intro"><?= $GLOBALS['tpl']['dlm_intro'] ?></div><?php endif; ?>

    <?php if ($GLOBALS['tpl']['dlm_is_registered'] == false || $GLOBALS['tpl']['is_logged_in']) : ?>
        <?php if ($GLOBALS['tpl']['categories_found']): ?>

        <table id="catlist" cellspacing="0">
        <thead>
            <tr>
                <th><?= PLUGIN_DOWNLOADMANAGER_CATEGORIES ?></th>
                <th class="last_column"><?= PLUGIN_DOWNLOADMANAGER_NUMBER_OF_DOWNLOADS ?></th>
            </tr>
        </thead>
        <tbody>
        <?php $i=0; foreach ($GLOBALS['tpl']['catlist'] AS $cat): ?><?php /* <pre><?= print_r($cat) ?></pre> */ ?>

            <tr class="dlm_subcat <?= (($i % 2) ? "even" : "odd") ?>">
                <td><?php foreach ($cat['imgname'] AS $imagename):?><img src="<?= $GLOBALS['tpl']['httppath'] ?>img/<?= $imagename ?>.gif" width="20" height="20" alt="" /><?php endforeach; ?><img src="<?= $GLOBALS['tpl']['httppath'] ?>img/f.png" width="20" height="20" alt="<?= CATEGORY ?>" /> <a href="<?= $cat['path'] ?>"><?= $cat['cat']['payload'] ?><?php /* $cat['catname'] */ ?></a></td>
                <td class="last_column"><?= $cat['filenum'] ?></td>
            </tr>
        <?php $i++; endforeach; ?>
        </tbody>
        </table>
        <?php else: ?>
        <div class="error"><?= PLUGIN_DOWNLOADMANAGER_NO_CATS_FOUND ?></div>
        <?php endif; ?>
    <?php else: ?>
        <div class="dlm_info"><?= PLUGIN_DOWNLOADMANAGER_REGISTERED_ONLY_ERROR ?></div>
    <?php endif; ?>
<!-- dlmanager.catlist.tpl end -->
</div>
