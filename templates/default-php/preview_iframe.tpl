<!DOCTYPE html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
    <head>
        <meta charset="<?= $GLOBALS['tpl']['head_charset'] ?>">
        <title><?= SERENDIPITY_ADMIN_SUITE ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet'] ?>">
    <?php if ($GLOBALS['tpl']['head_link_stylesheet_frontend']): ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['head_link_stylesheet_frontend'] ?>">
    <?php else: ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?><?= $GLOBALS['tpl']['serendipityRewritePrefix'] ?>serendipity.css">
    <?php endif; ?>
        <link rel="stylesheet" href="<?= $GLOBALS['tpl']['iconizr'] ?>">
        <style> #content { width: 99%; background-color: #fcfcfc; padding: 5px; } .save_preview_content .msg_success { margin: 0; } </style>
    <?php if ($GLOBALS['tpl']['mode'] == 'save'): /* we need this for modernizr.indexDB cleaning up autosave entry modifications */ ?>
        <script src="<?= $GLOBALS['tpl']['modernizr'] ?>"></script>
    <?php endif; ?>

        <script type="text/javascript">
        window.onload = function() {
            var frameheight = document.querySelector('html').offsetHeight<?php if ($GLOBALS['tpl']['mode'] == 'preview'): ?>-20<?php endif; ?>;
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
            parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
        }
        </script>
    </head>

    <body class="<?= $GLOBALS['tpl']['mode'] ?>_preview_body">
        <div id="mainpane" class="<?= $GLOBALS['tpl']['mode'] ?>_preview_container">
            <main id="content" class="<?= $GLOBALS['tpl']['mode'] ?>_preview_content">
        <?php if ($GLOBALS['tpl']['mode'] == 'preview'): ?>
                <div class="preview_entry">
                    <?= $GLOBALS['tpl']['preview'] ?>
                </div>
        <?php elseif ($GLOBALS['tpl']['mode'] == 'save'): ?>
                <div class="<?= $GLOBALS['tpl']['mode'] ?>_preview_sizing"></div>
                <?= $GLOBALS['tpl']['updertHooks'] ?>
            <?php if ($GLOBALS['tpl']['res']):  ?>
                <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b><?= ERROR ?>:</b><br> <?= $GLOBALS['tpl']['res'] ?></span>
            <?php else: ?>
                <?php if (isset($GLOBALS['tpl']['lastSavedEntry']) && (int)$GLOBALS['tpl']['lastSavedEntry']): ?>

                    <script type="text/javascript">
                        window.onload = function() {
                            parent.document.forms['serendipityEntry']['serendipity[id]'].value = "<?= $GLOBALS['tpl']['lastSavedEntry'] ?>";
                        };
                    </script>
                <?php endif; ?>

                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> <?= ENTRY_SAVED ?>
                <a href="<?= $GLOBALS['tpl']['entrylink'] ?>" target="_blank" rel="noopener"><?= VIEW ?></a></span>
            <?php endif; ?>
        <?php endif; ?>
            </main>
        </div>
        <!-- Filed by theme "<?= $GLOBALS['tpl']['template'] ?>" -->
    </body>
</html>
