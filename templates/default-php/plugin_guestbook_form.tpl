<?php /*
  plugin_guestbook_form.tpl v.3.28 - 2015-05-10 ian
 */ ?>

    <!-- Needed for Captchas -->
    <?php foreach ($GLOBALS['tpl']['guestbook_messages'] AS $messages):?>
    <p class="serendipity_center serendipity_msg_important"><?= $message ?></p>
    <?php endforeach; ?>

    <div id="comments" class="serendipity_comments serendipity_section_comments">
      <a id="serendipity_CommentForm"></a>
      <form id="serendipity_comment" action="<?= $GLOBALS['tpl']['is_guestbook_url'] ?>#feedback" method="post">
        <div>
            <input type="hidden" name="serendipity[subpage]" value="<?= $GLOBALS['tpl']['plugin_guestbook_sname'] ?>" />
            <input type="hidden" name="serendipity[guestbookform]" value="true" />
        </div>

        <div class="input-text">
            <label for="serendipity_commentform_name"><?= NAME ?></label>
            <input type="text" size="30" maxlength="39" name="serendipity[name]" value="<?= $GLOBALS['tpl']['plugin_guestbook_name'] ?>" id="serendipity_commentform_name" />
        </div>

       <?php if ($GLOBALS['tpl']['is_show_mail']): ?>
        <div class="input-text">
            <label for="serendipity_commentform_email"><?= EMAIL ?></label>
            <input type="text" size="30" maxlength="99" name="serendipity[email]" value="<?= $GLOBALS['tpl']['plugin_guestbook_email'] ?>" id="serendipity_commentform_email" />
            <div class="guestbook_emailprotect"><?= $GLOBALS['tpl']['plugin_guestbook_emailprotect'] ?></div>
        </div>
       <?php endif; ?>

       <?php if ($GLOBALS['tpl']['is_show_url']): ?>
        <div class="input-text">
            <label for="serendipity_commentform_url"><?= HOMEPAGE ?></label>
            <input type="text" size="30" maxlength="99" name="serendipity[url]" value="<?= $GLOBALS['tpl']['plugin_guestbook_url'] ?>" id="serendipity_commentform_url" />
        </div>
       <?php endif; ?>

        <div class="input-textarea">
            <label for="serendipity_commentform_comment"><?= BODY ?></label>
            <textarea cols="40" rows="10" name="serendipity[comment]" id="serendipity_commentform_comment"><?= $GLOBALS['tpl']['plugin_guestbook_comment'] ?></textarea>
            <?php serendipity_plugin_api::hook_event('frontend_comment', $GLOBALS['tpl']['plugin_guestbook_entry']); ?>
        </div>

        <div id="directions">
             <div class="serendipity_commentDirection"><?= $GLOBALS['tpl']['plugin_guestbook_captcha'] ?></div>
        </div>

        <div class="input-buttons">
             <input type="submit" name="serendipity[submit]" value="<?= SUBMIT ?>" />
        </div>

      </form>
    </div>
