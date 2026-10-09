<!DOCTYPE html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
    <head>
        <meta charset="<?= $GLOBALS['tpl']['head_charset'] ?>">
        <title><?= SERENDIPITY_ADMIN_SUITE ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet'] ?>">
    <?php if ($GLOBALS['tpl']['head_link_stylesheet_frontend']): ?>
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet_frontend'] ?>">
    <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['serendipityHTTPPath'] ?><?= $GLOBALS['tpl']['serendipityRewritePrefix'] ?>serendipity.css">
    <?php endif; ?>
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['iconizr'] ?>">
        <style> #content { width: 99%; background-color: #fcfcfc; padding: 5px; } .save_preview_content .msg_success { margin: 0; } </style>
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

                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const entryIdInput = parent?.document?.forms?.['serendipityEntry']?.['serendipity[id]'];
                        if (entryIdInput) {
                            entryIdInput.value = "{$lastSavedEntry}";
                        }
                    });
                </script>
                <?php endif; ?>

                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> <?= ENTRY_SAVED ?>
                <a href="<?= $GLOBALS['tpl']['entrylink'] ?>" target="_blank" rel="noopener"><?= VIEW ?></a></span>
            <?php endif; ?>
        <?php endif; ?>
            </main>
        </div>
        <!-- Filed by theme "default-php" -->
    </body>
</html>
