<!-- ENTRIES START -->
    <?php serendipity_plugin_api::hook_event('entries_header', $GLOBALS['tpl'], @$GLOBALS['tpl']['entry_id']); ?>
<?php /* NOTE: in case of staticpages it either needs to check is_array() or cast foreach($GLOBALS as (array) */ ?>
<?php if (! empty($GLOBALS['tpl']['entries'])):
    foreach($GLOBALS['tpl']['entries'] AS $dategroup): ?>
    <div class="serendipity_Entry_Date">
        <?php if ($dategroup['is_sticky']): ?>
        <h3 class="serendipity_date"><?= STICKY_POSTINGS ?></h3>
        <?php else: ?>
        <h3 class="serendipity_date"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $dategroup['date']); ?></h3>
        <?php endif; ?>

        <?php foreach($dategroup['entries'] AS $entry): ?>
        <?php $GLOBALS['tpl']['entry'] = $entry; ?>
        <h2 class="serendipity_title"><a href="<?= $entry['link'] ?>"><?= $entry['title'] ?></a></h2>

        <div class="serendipity_entry serendipity_entry_author_<?= serendipity_makeFilename($entry['author']); ?> <?php if (!empty($entry['is_entry_owner'])): ?>serendipity_entry_author_self<?php endif; ?>">
            <?php if (!empty($entry['categories'])): ?>
            <span class="serendipity_entryIcon">
            <?php foreach($entry['categories'] AS $entry_category):?>
                <?php if ($entry_category['category_icon']): ?>
                    <a href="<?= $entry_category['category_link'] ?>"><img class="serendipity_entryIcon" title="<?= serendipity_specialchars($entry_category['category_name']) ?> <?= $entry_category['category_description'] ?>" alt="<?= serendipity_specialchars($entry_category['category_name']) ?>" src="<?= serendipity_specialchars($entry_category['category_icon']) ?>"></a>
                <?php endif; ?>
            <?php endforeach; ?>
            </span>
            <?php endif; ?>

            <div class="serendipity_entry_body">
                <?= $entry['body'] ?>
            </div>

            <?php if ($entry['is_extended']): ?>
            <div class="serendipity_entry_extended">
                <a id="extended"></a>
                <?= $entry['extended'] ?>
            </div>
            <?php endif; ?>

            <?php if ($entry['has_extended'] && empty($GLOBALS['tpl']['is_single_entry']) && !$entry['is_extended']): ?>
            <p><a href="<?= $entry['link'] ?>#extended"><?php printf(VIEW_EXTENDED_ENTRY, $entry['title']) ?></a></p>
            <?php endif; ?>

            <?php if (!$GLOBALS['tpl']['is_preview']): ?>
            <div class="serendipity_entryFooter">
                <?= POSTED_BY ?> <a href="<?= $entry['link_author'] ?>"><?= $entry['author'] ?></a>
                <?php if (!empty($entry['categories'])): ?>
                   <?= IN ?> <?php foreach($entry['categories'] AS $entry_category):?><a href="<?= $entry_category['category_link'] ?>"><?= serendipity_specialchars($entry_category['category_name']); ?></a>, <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($dategroup['is_sticky']): ?>
                    <?= ON ?>
                <?php else: ?>
                    <?= AT ?>
                <?php endif; ?> <a href="<?= $entry['link'] ?>"><?php if ($dategroup['is_sticky']): ?><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $entry['timestamp']); ?> <?php endif; ?><?= serendipity_formatTime('%H:%M', $entry['timestamp']); ?></a>

                <?php if ($entry['has_comments'] && empty($GLOBALS['tpl']['is_single_entry'])): ?>
                    <?php if ($GLOBALS['tpl']['use_popups']): ?>
                        | <a href="<?= $entry['link_popup_comments'] ?>" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;"><?= ($entry['label_comments'] ?? '') ?> (<?= $entry['comments'] ?>)</a>
                    <?php else: ?>
                        | <a href="<?= $entry['link'] ?>#comments"><?= $entry['label_comments'] ?> (<?= $entry['comments'] ?>)</a>
                    <?php endif; ?>
                <?php elseif (null !== ($entry['label_comments'] ?? null)): ?>
                        | <a href="<?= $entry['link'] ?>#comments"><?= $entry['label_comments'] ?> (<?= $entry['comments'] ?>)</a>
                <?php endif; ?>

                <?php if ($entry['has_trackbacks'] && empty($GLOBALS['tpl']['is_single_entry'])): ?>
                    <?php if ($GLOBALS['tpl']['use_popups']): ?>
                        | <a href="<?= $entry['link_popup_trackbacks'] ?>" onclick="window.open(this.href, 'comments', 'width=480,height=480,scrollbars=yes'); return false;"><?= ($entry['label_trackbacks'] ?? '') ?> (<?= $entry['trackbacks'] ?>)</a>
                    <?php else: ?>
                        | <a href="<?= $entry['link'] ?>#trackbacks"><?= $entry['label_trackbacks'] ?> (<?= $entry['trackbacks'] ?>)</a>
                    <?php endif; ?>
                <?php elseif (null !== ($entry['label_trackbacks'] ?? null)): ?>
                        | <a href="<?= $entry['link'] ?>#trackbacks"><?= $entry['label_trackbacks'] ?> (<?= $entry['trackbacks'] ?>)</a>
                <?php endif; ?>

                <?php if (!empty($entry['is_entry_owner'])): ?>
                        | <a href="<?= $entry['link_edit'] ?>"><?= EDIT_ENTRY; ?></a>
                <?php endif; ?>

                <?= $entry['add_footer'] ?>
            </div>
            <?php endif; ?>
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

        <?= $entry['plugin_display_dat'] ?>

        <?php if ($GLOBALS['tpl']['is_single_entry'] && !$GLOBALS['tpl']['is_preview']): ?>
            <?php if (defined(DATA_UNSUBSCRIBED)): ?>
                <div class="serendipity_center serendipity_msg_success"><?= sprintf(DATA_UNSUBSCRIBED, UNSUBSCRIBE_OK) ?></div>
            <?php endif; ?>

            <?php if (defined(DATA_TRACKBACK_DELETED)): ?>
                <div class="serendipity_center serendipity_msg_success"><?= sprintf(DATA_TRACKBACK_DELETED, TRACKBACK_DELETED) ?></div>
            <?php endif; ?>

            <?php if (defined(DATA_TRACKBACK_APPROVED)): ?>
                <div class="serendipity_center serendipity_msg_success"><?= sprintf(DATA_TRACKBACK_APPROVED, TRACKBACK_APPROVED) ?></div>
            <?php endif; ?>

            <?php if (defined(DATA_COMMENT_DELETED)): ?>
                <div class="serendipity_center serendipity_msg_success"><?= sprintf(DATA_COMMENT_DELETED, COMMENT_DELETED) ?></div>
            <?php endif; ?>

            <?php if (defined(DATA_COMMENT_APPROVED)): ?>
                <div class="serendipity_center serendipity_msg_success"><?= sprintf(DATA_COMMENT_APPROVED, COMMENT_APPROVED) ?></div>
            <?php endif; ?>

            <div class="serendipity_comments serendipity_section_trackbacks">
                <a id="trackbacks"></a>
                <div class="serendipity_commentsTitle"><?= TRACKBACKS ?></div>
                <div class="serendipity_center">
                    <a rel="nofollow" href="<?= $entry['link_trackback'] ?>" onclick="alert('<?= serendipity_specialchars(TRACKBACK_SPECIFIC_ON_CLICK) ?> &raquo;<?= serendipity_specialchars($entry['rdf_ident']) ?>&laquo;'); return false;" title="<?= serendipity_specialchars(TRACKBACK_SPECIFIC_ON_CLICK) ?> &raquo;<?= serendipity_specialchars($entry['rdf_ident']) ?>&laquo;"><?= TRACKBACK_SPECIFIC; ?></a>
                </div>
                <div id="serendipity_trackbacklist">
                    <?php echo serendipity_printTrackbacks(serendipity_fetchTrackbacks($entry['id'])); ?>
                </div>
            </div>

            <div class="serendipity_comments serendipity_section_comments">
                <a id="comments"></a>
                <div class="serendipity_commentsTitle"><?= COMMENTS; ?></div>
                <div class="serendipity_center"><?= DISPLAY_COMMENTS_AS; ?>
                <?php if ($entry['viewmode'] == VIEWMODE_LINEAR): ?>
                    (<?= COMMENTS_VIEWMODE_LINEAR; ?> | <a href="<?= $entry['link_viewmode_threaded'] ?>#comments" rel="nofollow"><?= COMMENTS_VIEWMODE_THREADED; ?></a>)
                <?php else: ?>
                    (<a rel="nofollow" href="<?= $entry['link_viewmode_linear'] ?>#comments"><?= COMMENTS_VIEWMODE_LINEAR; ?></a> | <?= COMMENTS_VIEWMODE_THREADED; ?>)
                <?php endif; ?>
                </div>
                <div id="serendipity_commentlist">
                <?= $GLOBALS['template']->call('printComments', array('entry' => $entry['id'], 'mode' => $entry['viewmode'])); ?>
                </div>

                <?php if (!empty($entry['is_entry_owner'])): ?>
                    <?php if ($entry['allow_comments']): ?>
                    <div class="serendipity_center">(<a href="<?= $entry['link_deny_comments'] ?>"><?= COMMENTS_DISABLE ?></a>)</div>
                    <?php else: ?>
                    <div class="serendipity_center">(<a href="<?= $entry['link_allow_comments'] ?>"><?= COMMENTS_ENABLE ?></a>)</div>
                    <?php endif; ?>
                <?php endif; ?>
                <a id="feedback"></a>

                <?php if ( !empty($GLOBALS['tpl']['comments_messagestack'])): ?>
                <?php foreach($GLOBALS['tpl']['comments_messagestack'] AS $message): ?>
                <div class="serendipity_center serendipity_msg_important"><?= $message ?></div>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if ($GLOBALS['tpl']['is_comment_moderate']): ?>

                <div class="serendipity_center serendipity_msg_success"><?= COMMENT_ADDED; ?><br><?= THIS_COMMENT_NEEDS_REVIEW ?></div>

                <?php elseif ($GLOBALS['tpl']['is_comment_added']): ?>

                <div class="serendipity_center serendipity_msg_success"><?= COMMENT_ADDED; ?></div>

                <?php elseif (!$entry['allow_comments']): ?>

                <div class="serendipity_center serendipity_msg_important"><?= COMMENTS_CLOSED; ?></div>

                <?php else: ?>

                <div class="serendipity_section_commentform">
                    <div class="serendipity_commentsTitle"><?= ADD_COMMENT; ?></div>
                    <?= $GLOBALS['tpl']['COMMENTFORM']; ?>
                </div>

                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?= $entry['backend_preview'] ?>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
<?php else: ?>

    <?php if (!$GLOBALS['tpl']['plugin_clean_page'] && $GLOBALS['tpl']['view'] != '404'): ?>
    <div class="serendipity_overview_noentries">
        <?= NO_ENTRIES_TO_PRINT ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php if (!isset($GLOBALS['tpl']['$is_single_entry']) && !$GLOBALS['tpl']['is_preview'] && !$GLOBALS['tpl']['plugin_clean_page'] && (!empty($GLOBALS['tpl']['footer_prev_page']) OR !empty($GLOBALS['tpl']['footer_next_page']))): ?>
    <div class="serendipity_entries_footer">
    <?php if ($GLOBALS['tpl']['footer_prev_page']): ?>
        <a href="<?= $GLOBALS['tpl']['footer_prev_page'] ?>">&laquo; <?= PREVIOUS_PAGE; ?></a>&#160;&#160;
    <?php endif; ?>

    <?php if (!empty($GLOBALS['tpl']['footer_info'])): ?>
        (<?= $GLOBALS['tpl']['footer_info'] ?>)
    <?php endif; ?>

    <?php if ($GLOBALS['tpl']['footer_next_page']): ?>
        <a href="<?= $GLOBALS['tpl']['footer_next_page'] ?>">&raquo; <?= NEXT_PAGE; ?></a>
    <?php endif; ?>
    </div>
<?php endif; ?>

    <?php serendipity_plugin_api::hook_event('entries_footer', $GLOBALS['tpl']); ?>

<!-- ENTRIES END -->
