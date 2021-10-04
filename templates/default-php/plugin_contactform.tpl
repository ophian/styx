<div id="plugin_contact" class="clearfix serendipity_staticpage staticpage_plugin_contactform">
  <?php if ($GLOBALS['tpl']['plugin_contactform_articleformat']): ?>
  <div class="serendipity_Entry_Date">
  <?php if (!$GLOBALS['tpl']['plugin_contactform_pagetitle']): ?>  <h3 class="serendipity_date"><?= $GLOBALS['tpl']['plugin_contactform_name'] ?></h3><?php endif; ?>
    <div class="serendipity_entry">
      <div class="serendipity_entry_body">
  <?php endif; ?>

        <div class="clearfix">
          <div class="entry-info">
            <h1 class="page-title" class="entry-title"><?= $GLOBALS['tpl']['plugin_contactform_pagetitle'] ?></h1>
            <?php if (!isset($GLOBALS['tpl']['is_contactform_sent'])): ?>
            <div id="preface" class="preface"><?= $GLOBALS['tpl']['plugin_contactform_preface'] ?></div>
            <?php endif; ?>
          </div>

          <div class="entry-body">
            <?php if (isset($GLOBALS['tpl']['is_contactform_sent'])): ?>
            <a name="feedback"></a><p class="serendipity_center serendipity_msg_success"><?= $GLOBALS['tpl']['plugin_contactform_sent'] ?></p>
            <?php else: ?>
            <?php if (isset($GLOBALS['tpl']['is_contactform_error'])): ?>
            <p class="serendipity_center serendipity_msg_important"><?= $GLOBALS['tpl']['plugin_contactform_error'] ?></p>

            <!-- Needed for Captchas -->
            <?php foreach ((array)$GLOBALS['tpl']['comments_messagestack'] AS $message):?>
            <p class="serendipity_center serendipity_msg_important"><?= $GLOBALS['tpl']['message'] ?></p>
            <?php endforeach; ?>
            <?php endif; ?>

            <div id="serendipity_comment" class="serendipity_commentForm">
              <a id="serendipity_CommentForm"></a>

              <form id="serendipity_comment" action="<?= $GLOBALS['tpl']['commentform_action'] ?>#feedback" method="post">

                <div>
                  <input type="hidden" name="serendipity[subpage]" value="<?= $GLOBALS['tpl']['commentform_sname'] ?>">
                  <input type="hidden" name="serendipity[commentform]" value="true">
                </div>

                <div class="input-text">
                  <label for="serendipity_commentform_name"><?= NAME ?> &lowast;</label>
                  <input type="text" size="30" value="<?= $GLOBALS['tpl']['commentform_name'] ?>" name="serendipity[name]" id="serendipity_commentform_name" required>
                </div>

                <div class="input-text">
                  <label for="serendipity_commentform_email"><?= EMAIL ?> &lowast;</label>
                  <input type="text" size="30" value="<?= $GLOBALS['tpl']['commentform_email'] ?>" name="serendipity[email]" id="serendipity_commentform_email" required>
                </div>

                <div class="input-text">
                  <label for="serendipity_commentform_url"><?= HOMEPAGE ?></label>
                  <input type="text" size="30" value="<?= $GLOBALS['tpl']['commentform_url'] ?>" name="serendipity[url]" id="serendipity_commentform_url">
                </div>

                <div class="input-textarea">
                  <label for="serendipity_commentform_comment"><?= $GLOBALS['tpl']['plugin_contactform_message'] ?> &lowast;</label>
                  <textarea name="serendipity[comment]" id="serendipity_commentform_comment" cols="40" rows="10" required><?= $GLOBALS['tpl']['commentform_data'] ?></textarea>
                </div>

                <div id="directions">
                  <?php serendipity_plugin_api::hook_event('frontend_comment', $GLOBALS['tpl']['commentform_entry']); ?>
                </div>

                <div class="input-buttons">
                  <input type="submit" value="<?= SUBMIT_COMMENT ?>" name="serendipity[submit]">
                </div>

              </form>
            </div>
            <?php endif; ?>

          </div>
        </div>
  <?php if ($GLOBALS['tpl']['plugin_contactform_articleformat']): ?>

      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
