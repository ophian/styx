<!doctype html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
    <head>
        <title><?= SERENDIPITY_ADMIN_SUITE ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= $GLOBALS['tpl']['head_charset'] ?>" />
        <meta name="Powered-By" content="Serendipity v.<?= $GLOBALS['tpl']['head_version'] ?>" />
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet'] ?>" />
    <?php if ($GLOBALS['tpl']['head_link_stylesheet_frontend']): ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['head_link_stylesheet_frontend'] ?>" />
    <?php else: ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?><?= $GLOBALS['tpl']['serendipityRewritePrefix'] ?>serendipity.css" />
    <?php endif; ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['iconizr'] ?>" />
        <style> #content { width: 99%; background-color: #fcfcfc; } </style>
    <?php if ($GLOBALS['tpl']['mode'] == 'save'): /* we need this for modernizr.indexDB cleaning up autosave entry modifications */ ?>
        <script src="<?= $GLOBALS['tpl']['modernizr'] ?>"></script>
    <?php endif; ?>

        <script type="text/javascript">
        window.onload = function() {
            var frameheight = document.querySelector('html').offsetHeight;
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
        }
        </script>
    </head>

    <body class="<?= $GLOBALS['tpl']['mode'] ?>_preview_body">
        <div id="mainpaine" class="<?= $GLOBALS['tpl']['mode'] ?>_preview_container">
            <div id="content" class="<?= $GLOBALS['tpl']['mode'] ?>_preview_content">
        <?php if ($GLOBALS['tpl']['mode'] == 'save'): ?>
                <div class="<?= $GLOBALS['tpl']['mode'] ?>_preview_sizing"></div>
                <?= $GLOBALS['tpl']['updertHooks'] ?>
            <?php if ($GLOBALS['tpl']['res']):  ?>
                <div class="serendipity_msg_error"><?= ERROR ?>: <b><?= $GLOBALS['tpl']['res'] ?></b></div>
            <?php else: ?>
                <?php if (isset($GLOBALS['tpl']['lastSavedEntry']) && (int)$GLOBALS['tpl']['lastSavedEntry']): ?>

                    <script type="text/javascript">
                        window.onload = function() {
                            parent.document.forms['serendipityEntry']['serendipity[id]'].value = "<?= $GLOBALS['tpl']['lastSavedEntry'] ?>";
                        };
                    </script>
                <?php endif; ?>

                <span class="msg_success"><span class="icon-ok-circled"></span> <?= ENTRY_SAVED ?></span>
                <a href="<?= $GLOBALS['tpl']['entrylink'] ?>" target="_blank"><?= VIEW ?></a>
            <?php endif; ?>
        <?php endif; ?>
            <?= $GLOBALS['tpl']['preview'] ?>
            </div>
        </div>

    </body>
</html>
