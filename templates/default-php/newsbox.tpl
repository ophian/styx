<!-- NEWSBOX ENTRIES START -->
    <fieldset class="newsbox"><legend class="newsbox_title"><?= $GLOBALS['tpl']['newsbox_data']['title']; ?></legend>
    <?php if (is_array($GLOBALS['tpl']['entries'])):
    foreach($GLOBALS['tpl']['entries'] AS $dategroup):?>
        <div class="serendipity_Entry_Date">
            <?php if ($dategroup['is_sticky']): ?>
            <h4 class="serendipity_date"><?= STICKY_POSTINGS ?></h4>
            <?php endif; ?>

            <?php foreach($dategroup['entries'] AS $entry):?>
            <div class="shadow">
              <div class="serendipity_entry serendipity_entry_author_{$entry.author|makeFilename}{if NOT empty($entry.is_entry_owner)} serendipity_entry_author_self{/if} drop newsbox_entry">
                <h3 class="serendipity_title"><a href="<?= $entry['link'] ?>"><?= $entry['title'] ?></a></h3>
                <h4 class="serendipity_date"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $dategroup['date']); ?></h4>

                <div class="serendipity_entry_body">
                    <?= $entry['body'] ?>
                </div>

                <?php if ($entry['has_extended'] && empty($GLOBALS['tpl']['is_single_entry']) && !$entry['is_extended']): ?>
                <p><a href="<?= $entry['link'] ?>#extended"><?php printf(VIEW_EXTENDED_ENTRY, $entry['title']) ?></a></p>
                <?php endif; ?>

              </div>
            </div>
            <!--
            <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                     xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/"
                     xmlns:dc="http://purl.org/dc/elements/1.1/">
            <rdf:Description
                     rdf:about="<?= $entry['link_rdf'] ?>"
                     trackback:ping="<?= $entry['link_trackback'] ?>"
                     dc:title="<?php if ($entry['title_rdf']): ?><?= $entry['title_rdf']; ?><?php else: ?><?= $entry['title'] ?><?php endif; ?>"
                     dc:identifier="<?= $entry['rdf_ident'] ?>">
            </rdf:RDF>
            -->
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if ((is_array($GLOBALS['tpl']['entries']) && count($GLOBALS['tpl']['entries']) < 1) && !$GLOBALS['tpl']['plugin_clean_page'] && $GLOBALS['tpl']['view'] != '404'): ?>
    <div class="serendipity_overview_noentries">
        <?= NO_ENTRIES_TO_PRINT ?>
    </div>
    <?php endif; ?>
<?php if ($GLOBALS['tpl']['is_nbpagination']): ?>

        <div class="serendipity_entries_footer">
            <form style="display:inline;" action="<?= $GLOBALS['tpl']['newsbox_data']['multicat_action']; ?>" method="post">
            <?php foreach($GLOBALS['tpl']['newsbox_data']['cats'] AS $cat_id):?>
                <input type="hidden" name="serendipity[multiCat][]" value="<?= $cat_id; ?>">
            <?php endforeach; ?>
                <input type="submit" name="serendipity[isMultiCat]" value="<?= MORE; ?> <?= $GLOBALS['tpl']['newsbox_data']['title']; ?>">
            </form>
        </div>
<?php endif; ?>

    </fieldset>
<!-- NEWBOX ENTRIES END -->
