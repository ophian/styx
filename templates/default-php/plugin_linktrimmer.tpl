<?php if ($GLOBALS['tpl']['linktrimmer_external']): ?>
<!doctype html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
<head>
    <meta charset="<?= LANG_CHARSET ?>">
    <title><?= PLUGIN_LINKTRIMMER_NAME ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>serendipity.css.php?serendipity[css_mode]=serendipity_admin.css">
<!--[if lte IE 8]>
    <link rel="stylesheet" href="<?= serendipity_getTemplateFile('admin/oldie.css', 'serendipityHTTPPath') ?>">
<![endif]-->
    <script src="<?= serendipity_getTemplateFile('admin/js/modernizr.min.js', 'serendipityHTTPPath') ?>"></script>

    <style>{* popup only classes *}
        .serendipity_linktrimmer_page .linktrimmer {
            display: block;
            margin: 1em auto auto;
        }
        #main_linktrimmer {
            border: 1px solid #BBB;
            background: none repeat scroll 0% 0% #EEE;
            padding: 0.75em;
            margin: 0px 0px 1em;
        }
        #main_linktrimmer legend {
            border: 1px solid #72878A;
            background: none repeat scroll 0% 0% #DDD;
            padding: 2px 5px;
        }
        #linktrimmer_url.input_textbox { width: inherit; }
    </style>
</head>
<body id="serendipity_admin_page" class="serendipity_linktrimmer_page">
    <main id="workspace" class="clearfix">
        <div id="content" class="clearfix">
<?php endif; ?>

<?php if ($GLOBALS['tpl']['linktrimmer_external']): ?>
<div class="linktrimmer">
<?php else: ?>
<section id="dashboard_linktrimmer" class="quick_list dashboard_widget">
    <h3><?= PLUGIN_LINKTRIMMER_NAME ?></h3>
<?php endif; ?>
    <form action="" method="post">
        <input type="hidden" name="txtarea" value="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_txtarea']) ?>:'url'">
        <fieldset id="main_linktrimmer" class="">
        <?php if ($GLOBALS['tpl']['linktrimmer_external']): ?>
            <legend><?= PLUGIN_LINKTRIMMER_NAME ?></legend>
        <?php endif; ?>

        <?php if ($GLOBALS['tpl']['linktrimmer_error']): ?>
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <?= PLUGIN_LINKTRIMMER_ERROR ?></span>
        <?php endif; ?>

            <div class="form_field">
                <label for="linktrimmer_url"><?= PLUGIN_LINKTRIMMER_ENTER ?></label>
                <input id="linktrimmer_url" class="input_textbox" type="text" onfocus="this.blur();" value="" name="linktrimmer_url" placeholder="https://www.s9y.org">
    <?php if ($GLOBALS['tpl']['linktrimmer_external'] === false): ?>
            </div>

            <div class="form_field">
    <?php endif; ?>
                <label for="linktrimmer_hash"><?= PLUGIN_LINKTRIMMER_HASH ?></label>
                <input id="linktrimmer_hash" class="input_textbox" type="text" onfocus="this.blur();" value="" name="linktrimmer_hash" size="14">

                <input type="submit" name="submit" value="<?= GO ?>" class="input_button">
            </div>

    <?php if ($GLOBALS['tpl']['linktrimmer_url'] != '' && $GLOBALS['tpl']['linktrimmer_external']): ?>
            <script>
        <?php if (!$GLOBALS['tpl']['linktrimmer_ispopup']): ?>
                window.parent.parent.serendipity.serendipity_imageSelector_addToBody('<a href="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_url']) ?>" title="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_origurl']) ?>"><?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_origurl']) ?></a>', '<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_txtarea']) ?>');
                window.parent.parent.$.magnificPopup.close();
        <?php else: ?>
                self.opener.serendipity_imageSelector_addToBody('<a href="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_url']) ?>" title="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_origurl']) ?>"><?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_origurl']) ?></a>', '<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_txtarea']) ?>');
                self.close();
        <?php endif; ?>
            </script>
    <?php elseif ($GLOBALS['tpl']['linktrimmer_url'] != ''): ?>
            <div class="form_field">
                <label for="linktrimmer_result"><?= PLUGIN_LINKTRIMMER_RESULT ?></label>
                <input id="linktrimmer_result" class="input_textbox" type="text" value="<?= serendipity_specialchars($GLOBALS['tpl']['linktrimmer_url']) ?>" name="linktrimmer_result">
                <script>
                    document.getElementById('linktrimmer_result').select();
                    document.getElementById('linktrimmer_result').focus();
                </script>
            </div>
    <?php else: ?>
            <script>
                document.getElementById('linktrimmer_url').select();
                document.getElementById('linktrimmer_url').focus();
            </script>
    <?php endif; ?>
        </fieldset>
    </form>
<?php if ($GLOBALS['tpl']['linktrimmer_external']): ?>
</div>
<?php else: ?>
</section>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['linktrimmer_external']): ?>
        </div>
    </main>
</body>
</html>
<?php endif; ?>
