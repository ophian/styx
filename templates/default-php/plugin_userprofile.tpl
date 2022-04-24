<?php if (! empty($GLOBALS['tpl']['userProfile']['realname'])): ?>
<div class="serendipityAuthorProfile">
<strong><?= $GLOBALS['tpl']['userProfileTitle'] ?> - <?= $GLOBALS['tpl']['userProfile']['realname'] ?></strong>
<br />

<dl>
<?php if ($GLOBALS['tpl']['userProfile']['show_email'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileLocalProperties']['email']['desc'] ?></dt>
    <dd><a href="mailto:<?= filter_var(trim($GLOBALS['tpl']['userProfile']['email']), FILTER_SANITIZE_EMAIL) ?>|escape:"hex"}"><?= filter_var(trim($GLOBALS['tpl']['userProfile']['email']), FILTER_SANITIZE_EMAIL) ?></a></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['birthday'] && isset($GLOBALS['tpl']['userProfile']['show_birthday']) && $GLOBALS['tpl']['userProfile']['show_birthday'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['birthday']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['birthday|formatTime:DATE_FORMAT_ENTRY'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['url'] && $GLOBALS['tpl']['userProfile']['show_url'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['url']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['url'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['city'] && $GLOBALS['tpl']['userProfile']['show_city'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['city']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['city'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['country'] && $GLOBALS['tpl']['userProfile']['show_country'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['country']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['country'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['occupation'] && isset($GLOBALS['tpl']['userProfile']['show_occupation']) && $GLOBALS['tpl']['userProfile']['show_occupation'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['occupation']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['occupation'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['hobbies'] && $GLOBALS['tpl']['userProfile']['show_hobbies'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['hobbies']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['hobbies'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['yahoo'] && $GLOBALS['tpl']['userProfile']['show_yahoo'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['yahoo']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['yahoo'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['aim'] && $GLOBALS['tpl']['userProfile']['show_aim'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['aim']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['aim'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['jabber'] && $GLOBALS['tpl']['userProfile']['show_jabber'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['jabber']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['jabber'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['icq'] && $GLOBALS['tpl']['userProfile']['show_icq'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['icq']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['icq'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['msn'] && $GLOBALS['tpl']['userProfile']['show_msn'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['msn']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['msn'] ?></dd>
<?php
 endif;
 if ($GLOBALS['tpl']['userProfile']['skype'] && $GLOBALS['tpl']['userProfile']['show_skype'] == "true"): ?>
    <dt><?= $GLOBALS['tpl']['userProfileProperties']['skype']['desc'] ?></dt>
    <dd><?= $GLOBALS['tpl']['userProfile']['skype'] ?></dd>
<?php endif; ?>
</dl>
</div>
<?php endif; ?>