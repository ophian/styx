<?php if ($GLOBALS['tpl']['is_form']): ?>
<form id="serendipity_category_form" action="<?= $GLOBALS['tpl']['form_url'] ?>" method="post">
    <div id="serendipity_category_form_content">
<?php endif; ?>

    <ul id="serendipity_categories_list" class="plainList">
<?php if (is_array($GLOBALS['tpl']['categories'])):
    foreach ($GLOBALS['tpl']['categories'] AS $plugin_category):?>
        <li>
        <?php if ($GLOBALS['tpl']['is_form']): ?>
            <input type="checkbox" name="serendipity[multiCat][]" value="<?= $plugin_category['categoryid'] ?>">
        <?php endif; ?>

        <?php if (!empty($GLOBALS['tpl']['category_image'])): ?>
            <a class="serendipity_xml_icon" href="<?= $plugin_category['feedCategoryURL'] ?>"><img src="<?= $GLOBALS['tpl']['category_image'] ?>" alt="XML" /></a>
        <?php endif; ?>

            <a href="<?= $plugin_category['categoryURL'] ?>" title="<?= serendipity_specialchars($plugin_category['category_description']); ?>" style="padding-left: <?= $plugin_category['paddingPx'] ?>px"><?= serendipity_specialchars($plugin_category['category_name']); ?></a>
        </li>
<?php endforeach; ?>
<?php endif; ?>
    </ul>

<?php if ($GLOBALS['tpl']['is_form']): ?>
    <div class="category_submit"><input type="submit" name="serendipity[isMultiCat]" value="<?php if (isset($_GET['serendipity']['category'])): ?><?= RESET_FILTERS ?><?php else: ?><?= GO ?><?php endif; ?>"></div>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['show_all']): ?>
    <div class="category_link_all"><a href="<?= $GLOBALS['tpl']['form_url'] ?>" title="<?= ALL_CATEGORIES ?>"><?= ALL_CATEGORIES ?></a></div>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['is_form']): ?>
    </div>
</form>
<?php endif; ?>
