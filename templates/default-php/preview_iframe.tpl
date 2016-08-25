<!doctype html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
    <head>
        <title><?= SERENDIPITY_ADMIN_SUITE ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $GLOBALS['tpl']['head_charset'] ?>" />
        <meta name="Powered-By" content="Serendipity v.<?= $GLOBALS['tpl']['head_version'] ?>" />
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet'] ?>" />
    <?php if ($GLOBALS['tpl']['head_link_stylesheet_frontend']): ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['head_link_stylesheet_frontend'] ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?><?= $GLOBALS['tpl']['serendipityRewritePrefix'] ?>serendipity.css">
    <?php endif; ?>
    <?php if ($GLOBALS['tpl']['mode'] == 'save'): /* we need this for modernizr.indexDB cleaning up autosave entry modifications */ ?>
        <script src="<?= serendipity_getTemplateFile("admin/js/modernizr.min.js"); ?>"></script>
    <?php endif; ?>

        <script type="text/javascript">
            window.onload = function() {
                parent.document.getElementById('serendipity_iframe').style.height = document.getElementById('mainpane').offsetHeight
                                                                                  + parseInt(document.getElementById('mainpane').style.marginTop)
                                                                                  + parseInt(document.getElementById('mainpane').style.marginBottom)
                                                                                  + 'px';
                parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
                parent.document.getElementById('serendipity_iframe').style.border = 0;
            }
        </script>
    </head>

    <body style="padding: 0px; margin: 0px;">
        <div id="mainpaine" style="border: 0 none; max-width: 100%; min-width: 100%; margin: 0px;">
            <div id="content" style="margin: 0px; padding: 1em 0.5em; width: 98.75%;">
        <?php if ($GLOBALS['tpl']['mode'] == 'save'): ?>
                <div style="float: left; height: 75px"></div>
                <?= $GLOBALS['tpl']['updertHooks'] ?>
            <?php if ($GLOBALS['tpl']['res']):  ?>
                <div class="serendipity_msg_error"><?= $GLOBALS['tpl']['ERROR'] ?>: <b><?= $GLOBALS['tpl']['res'] ?></b></div>
            <?php else: ?>
                <?php if (isset($GLOBALS['tpl']['lastSavedEntry']) && (int)$GLOBALS['tpl']['lastSavedEntry']): ?>

                    <script type="text/javascript">
                        window.onload = function() {
                            parent.document.forms['serendipityEntry']['serendipity[id]'].value = "<?= $GLOBALS['tpl']['lastSavedEntry'] ?>";
                        };
                    </script>
                <?php endif; ?>

                <div class="serendipity_msg_notice"> <?= $GLOBALS['tpl']['ENTRY_SAVED'] ?></div>
                <a href="<?= $GLOBALS['tpl']['entrylink'] ?>" target="_blank"><?= $GLOBALS['tpl']['VIEW'] ?></a>
            <?php endif; ?>
        <?php endif; ?>
            <?= $GLOBALS['tpl']['preview'] ?>
            </div>
        </div>

    </body>
</html>
