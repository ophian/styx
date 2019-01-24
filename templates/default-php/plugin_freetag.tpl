<div class="serendipity_Entry_Date freetag_cloud">
    <h2 class="serendipity_date"><?= sprintf(PLUGIN_EVENT_FREETAG_USING, $GLOBALS['tpl']['freetag_tagTitle']); ?></h2>

    <?php if (empty($GLOBALS['tpl']['freetag_isList'])): ?>

    <div class="serendipity_freetag_taglist">
        <p class="serendipity_freetag_taglist_related"><?= PLUGIN_EVENT_FREETAG_RELATED_TAGS; ?></p>

        <?php if ($GLOBALS['tpl']['freetag_hasTags']): ?>
            <?= $GLOBALS['tpl']['freetag_displayTags']; ?>
        <?php else: ?>
            <span class="ftr-empty"><?= PLUGIN_EVENT_FREETAG_NO_RELATED; ?></span>
        <?php endif; ?>

    </div>
    <?php endif; ?>

</div>
