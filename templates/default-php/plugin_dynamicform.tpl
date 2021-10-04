<div id="plugin_contact" class="clearfix serendipity_staticpage staticpage_plugin_contactform">
<?php if ($GLOBALS['tpl']['plugin_contactform_articleformat']): ?>
   <div class="serendipity_Entry_Date">
       <h3 class="serendipity_date"><?= $GLOBALS['tpl']['plugin_contactform_name'] ?></h3>
       <div class="serendipity_entry">
           <div class="serendipity_entry_body">
<?php endif; ?>

<?php if (isset($GLOBALS['tpl']['is_contactform_error'])): ?>
   <div class="serendipity_center serendipity_msg_important"><?= $GLOBALS['tpl']['plugin_contactform_error'] ?></div>
   <!-- Needed for Captchas -->
   <?php foreach ($GLOBALS['tpl']['comments_messagestack'] AS $messages):?>
   <div class="serendipity_center serendipity_msg_important"<?= $messages ?></div>
   <?php endforeach; ?>
<?php endif; ?>

<?php if (empty($GLOBALS['tpl']['is_contactform_sent'])): ?>
   <div><?= $GLOBALS['tpl']['plugin_contactform_preface'] ?></div>
<?php endif; ?>

<?php if (isset($GLOBALS['tpl']['is_contactform_sent'])): ?>
    <div class="serendipity_center serendipity_msg_success"><?= $GLOBALS['tpl']['plugin_contactform_sent'] ?></div>
<?php else: ?>

<!-- This whole commentform style, including field names is needed -->
<!-- for Captchas. The spamblock plugin relies on the field names  -->
<!-- [name], [email], [url], [comment]!                            -->

<div class="serendipity_commentForm">
    <a id="serendipity_CommentForm"></a>
    <form id="serendipity_comment_CommentForm" action="<?= $GLOBALS['tpl']['commentform_action'] ?>#feedback" method="post">
        <div>
            <input type="hidden" name="serendipity[subpage]" value="<?= $GLOBALS['tpl']['commentform_sname'] ?>">
            <input type="hidden" name="serendipity[commentform]" value="true">
            <?php foreach ($GLOBALS['tpl']['commentform_dynamicfields'] AS $field):?>
                <?php if ($field['type'] == "hidden"): ?>
                    <input type="hidden" name="serendipity[<?= $field['id'] ?>]" value="<?= $field['default'] ?>">
                <?php endif; ?>
            <?php endforeach; ?>
       </div>

       <fieldset>
           <legend><?= $GLOBALS['tpl']['plugin_contactform_pagetitle'] ?></legend>
           <dl>
               <?php foreach ($GLOBALS['tpl']['commentform_dynamicfields'] AS $field):?>
                   <?php if ($field['type'] != "hidden"): ?>
                       <dt class="serendipity_commentsLabel">
                           <?php if ($field['required']): ?><sup>&#8727;</sup><?php endif; ?><label for="serendipity_contactform_<?= $field['id'] ?>"><?= $field['name'] ?></label>
                       </dt>
                       <dd class="serendipity_commentsValue">
                           <?php if ($field['type'] == "checkbox"): ?>
                               <input class="frm_check" type="checkbox" name="<?= $field['id'] ?>" id="<?= $field['id'] ?>" <?= $field['default'] ?>><label class="frm_check_label" for="<?= $field['id'] ?>"><?= $field['message'] ?></label>
                           <?php elseif ($field['type'] == "radio"): ?>
                               <?php foreach ($field['options'] AS $option):?>
                                   <input class="frm_radio" type="radio" name="<?= $field['id'] ?>" id="<?= $field['id'] ?>['<?= $option['id'] ?>" value="<?= $option['value'] ?>" <?= $option['default'] ?>><label class="frm_radio_label" for="<?= $field['id'] ?>.<?= $option['id'] ?>"><?= $option['name'] ?></label>
                              <?php endforeach; ?>
                           <?php elseif ($field['type'] == "select"): ?>
                               <select name="<?= $field['id'] ?>">
                                   <?php foreach ($field['options'] AS $option):?>
                                       <option name="<?= $field['id'] ?>" id="<?= $field['id'] ?>['<?= $option['id'] ?>" value="<?= $option['value'] ?>" <?= $option['default'] ?> ><?= $option['name'] ?></option>
                                  <?php endforeach; ?>
                               </select>
                           <?php elseif ($field['type'] == "password"): ?>
                               <input class="frm" type="password" id="serendipity_contactform_<?= $field['id'] ?>" name="serendipity[<?= $field['id'] ?>]" value="<?= $field['default'] ?>" size="30">
                           <?php elseif ($field['type'] == "textarea"): ?>
                               <textarea class="frm" rows="10" cols="40" id="<?php if ($field['name'] == PLUGIN_CONTACTFORM_MESSAGE): ?>serendipity_commentform_comment<?php else: ?>serendipity_contactform_<?= $field['id'] ?><?php endif; ?>" name="serendipity[<?= $field['id'] ?>]"><?= $field['default'] ?></textarea>
                               <?php /* If you do NOT need AND run the emoticonchooser plugin, you can as well just use serendipity_contactform_{$field.id} here! */ ?>
                           <?php else: ?>
                               <input class="frm" type="text" id="serendipity_contactform_<?= $field['id'] ?>" name="serendipity[<?= $field['id'] ?>]" value="<?= $field['default'] ?>" size="30">
                           <?php endif; ?>
                       </dd>
                   <?php endif; ?>
               <?php endforeach; ?>
               <dt>&#160;</dt>
               <dd>
<!-- This is where the spamblock/Captcha plugin is called -->
                   <?php serendipity_plugin_api::hook_event('frontend_comment', $GLOBALS['tpl']['commentform_entry']); ?>
               </dd>
               <dt>&#160;</dt>
               <dd>
                   <input class="frm" type="submit" name="serendipity[submit]" value="<?= SUBMIT_COMMENT ?>">
               </dd>
           </dl>
       </fieldset>
    </form>
</div>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['plugin_contactform_articleformat']): ?>
            </div>
        </div>
    </div>
<?php endif; ?>

</div>
