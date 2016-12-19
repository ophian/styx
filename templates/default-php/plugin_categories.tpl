<?php if ($GLOBALS['tpl']['is_form']: ?>
<form id="serendipity_category_form" action="<?= $GLOBALS['tpl']['form_url'] ?>" method="post">
    <div id="serendipity_category_form_content">
<?php endif; ?>

    <ul id="serendipity_categories_list" style="list-style: none; margin: 0px; padding: 0px">
<?php if is_array($categories)):
    foreach ($categories AS $plugin_category):?>
        <li style="display: block;">
        <?php if ($GLOBALS['tpl']['is_form']: ?>
            <input style="width: 15px" type="checkbox" name="serendipity[multiCat][]" value="<?= $plugin_category['categoryid'] ?>" />
        <?php endif; ?>

        <?php if !empty($GLOBALS['tpl']['category_image']): ?>
            <a class="serendipity_xml_icon" href="<?= $plugin_category['feedCategoryURL'] ?>"><img src="<?= $GLOBALS['tpl']['category_image'] ?>" alt="XML" style="border: 0px" /></a>
        <?php endif; ?>

            <a href="<?= $plugin_category['categoryURL'] ?>" title="<?= serendipity_specialchars($plugin_category['category_description']); ?>" style="padding-left: <?= $plugin_category['paddingPx'] ?>px"><?= serendipity_specialchars($plugin_category['category_name']); ?></a>
        </li>
<?php endforeach; ?>
<?php endif; ?>
    </ul>

<?php if ($GLOBALS['tpl']['is_form']: ?>
    <div class="category_submit"><input type="submit" name="serendipity[isMultiCat]" value="<?= GO ?>" /></div>
<?php endif; ?>

    <div class="category_link_all"><a href="<?= $GLOBALS['tpl']['form_url'] ?>" title="<?= ALL_CATEGORIES ?>"><?= ALL_CATEGORIES ?></a></div>

<?php if ($GLOBALS['tpl']['is_form']: ?>
    </div>
</form>
<?php endif; ?>
