<?php /*
    plugin_guestbook_entries.tpl for v.3.53 - 2014-12-29
*/ ?>

<?php if ($GLOBALS['tpl']['plugin_guestbook_articleformat']): ?>
  <div class="serendipity_Entry_Date serendipity_guestbook">
    <?php if ($GLOBALS['tpl']['staticpage_pagetitle']): ?><h2 class="serendipity_title"><?= $GLOBALS['tpl']['staticpage_headline'] ?></h2><?php endif; ?>

    <div class="serendipity_entry">
      <div class="serendipity_entry_body">
<?php endif; ?>

        <div id="guestbook_wrapper">

          <div class="clearfix">

            <div class="entry-info">
              <?php if (!$GLOBALS['tpl']['plugin_guestbook_articleformat']): ?><h2 class="page-title"><?= $GLOBALS['tpl']['staticpage_headline'] ?></h1><?php endif; ?>
              <?php if (!$GLOBALS['tpl']['is_contactform_sent'] AND $GLOBALS['tpl']['plugin_guestbook_intro']): ?>

              <div id="preface" class="preface guestbook_intro"><?= $GLOBALS['tpl']['plugin_guestbook_intro'] ?></div>
              <?php endif; ?>

            </div>

            <?php if ($GLOBALS['tpl']['staticpage_formorder'] == 'top'): ?><?= $GLOBALS['tpl']['GUESTBOOK_FORM'] ?><?php endif; ?>

            <div class="entry-body">
            <?php if ($GLOBALS['tpl']['is_guestbook_message']): ?>
              <p class="serendipity_center serendipity_msg_important guestbook_errorbundled"><?= $GLOBALS['tpl']['error_occured'] ?></p>
              <?php if ($GLOBALS['tpl']['guestbook_messages']): ?>
                <ul>
                <?php foreach ($GLOBALS['tpl']['guestbook_messages'] AS $messages):?>
                    <li class="guestbook_errors"><?= $messages ?></li>
                <?php endforeach; ?>
                </ul>
              <?php endif; ?>
            <?php endif; ?>

            <?php if ($GLOBALS['tpl']['guestbook_entry_paging']): ?><div id="guestbook_entrypaging"><?= $GLOBALS['tpl']['guestbook_paging'] ?></div><?php endif; ?>

            <?php if ($GLOBALS['tpl']['guestbook_entries']): ?>
              <?php foreach ($GLOBALS['tpl']['guestbook_entries'] AS $entry):?>

                <div id="guestbook_entrybundle">
                  <div class="guestbook_entrytop">
                    <dl class="guestbook_entries">
                      <dt><a href="mailto:<?= $entry['email'] ?>"><?= $entry['name'] ?></a>
                           <?= PLUGIN_GUESTBOOK_USERSDATE_OF_ENTRY ?> <img src="<?= $entry['pluginpath'] ?>img/shorttime.gif" width="14" height="17" onfocus="this.blur();" align="absmiddle" alt="<?= TEXT_IMG_LASTMODIFIED ?>" title="<?= TEXT_IMG_LASTMODIFIED ?>">&nbsp;
                           <?= $entry['timestamp'] ?>
                      </dt>
                      <?php if ($GLOBALS['tpl']['entry']['homepage']): ?>
                      <dt><?= TEXT_USERS_HOMEPAGE ?>: <a href="<?= $entry['homepage'] ?>" target="_blank"><?= substr($entry['homepage'], 0, 24) ?>&hellip;</a></dt>
                      <?php endif; ?>

                    </dl>

                    <dl class="guestbook_entrybottom">
                        <dd><?= str_replace('&amp;quot;', '&quot;', $entry['body']) ?></dd>
                    </dl>
                  </div> <!-- //- class:guestbook_entrytop end -->
                </div> <!-- //- id:guestbook_entrybundle end -->

                <div class="guestbook_splitentries">&#160;</div>
              <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($GLOBALS['tpl']['guestbook_entry_paging']): ?><div id="guestbook_entrypaging"><?= $GLOBALS['tpl']['guestbook_paging'] ?></div><?php endif; ?>

            </div><!-- //- class:entry-body end -->

            <?php if ($GLOBALS['tpl']['staticpage_formorder'] == 'bottom'): ?><?= $GLOBALS['tpl']['GUESTBOOK_FORM'] ?><?php endif; ?>

          </div> <!-- //- class:clearfix end -->

        </div> <!-- //- id:guestbook_wrapper end -->

<?php if ($GLOBALS['tpl']['plugin_guestbook_articleformat']): ?>
      </div>  <!-- //- class:serendipity_entry_body end -->
    </div> <!-- //- class:serendipity_entry end -->
  </div> <!-- //- class:serendipity_Entry_Date end -->

<?php endif; ?>
