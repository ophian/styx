<!doctype html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
    <head>
        <meta charset="<?= LANG_CHARSET ?>">
        <title><?= PLUGIN_EVENT_AMAZONCHOOSER_MEDIA_BUTTON ?></title>
        <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['plugin_amazonchooser_css'] ?>">
        <script type="text/javascript" src="<?= $GLOBALS['tpl']['plugin_amazonchooser_js'] ?>"></script>
    </head>

    <body id="serendipity_admin_page">
        <div id="serendipityAdminMainpane">
            <h2><?= PLUGIN_EVENT_AMAZONCHOOSER_MEDIA_BUTTON ?></h2>
            <div class="serendipityAdminContent">
                <div class="serendipity_amazonchr_body_list">
            <?php if ($GLOBALS['tpl']['plugin_amazonchooser_page'] == 'Search'): ?>
              <?php if ($GLOBALS['tpl']['plugin_amazonchooser_item_count'] > 0 && $GLOBALS['tpl']['plugin_amazonchooser_return_count'] > 0): ?>
                    <input type="button" class="serendipityPrettyButton input_button"  value="<?= BACK ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_search_url'] ?>">
                    <div class="serendipity_amazonchr_body_count">
                        <span class="serendipity_amazonchr_pagecount"><?= PLUGIN_EVENT_AMAZONCHOOSER_DISPLAYING ?> <?= PLUGIN_EVENT_AMAZONCHOOSER_PAGE ?> <?= $GLOBALS['tpl']['plugin_amazonchooser_currentpage'] ?> <?= PLUGIN_EVENT_AMAZONCHOOSER_OF ?> <?= $GLOBALS['tpl']['plugin_amazonchooser_totalpages'] ?> <?= PLUGIN_EVENT_AMAZONCHOOSER_PAGES ?> (<?= PLUGIN_EVENT_AMAZONCHOOSER_PAGELIMIT ?>).</span>
                    </div>
                    <div class="serendipity_amazonchr_page_buttons">
                       <?php if (isset($GLOBALS['tpl']['plugin_amazonchooser_previouspage'])): ?>
                       <span class="serendipity_amazonchr_nextbutton"><input type="button" class="serendipityPrettyButton input_button"  value="<?= PREVIOUS ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_this_url'] ?><?= $GLOBALS['tpl']['plugin_amazonchooser_previouspage'] ?>"></span>
                       <?php endif; ?>
                       <?php if (isset($GLOBALS['tpl']['plugin_amazonchooser_nextpage'])): ?>
                       <span class="serendipity_amazonchr_previousbutton"><input type="button" class="serendipityPrettyButton input_button"  value="<?= NEXT ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_this_url'] ?><?= $GLOBALS['tpl']['plugin_amazonchooser_nextpage'] ?>"></span>
                       <?php endif; ?>
                    </div>
                 <?php foreach ($GLOBALS['tpl']['plugin_amazonchooser_items'] AS $thingy):?>
                    <?= serendipity_getTemplateFile($GLOBALS['tpl']['plugin_amazonchooser_displaytemplate']); ?>
                 <?php endforeach; ?>
                    <div class="serendipity_amazonchr_page_buttons">
                       <?php if (isset($GLOBALS['tpl']['plugin_amazonchooser_previouspage'])): ?>
                       <span class="serendipity_amazonchr_nextbutton"><input type="button" class="serendipityPrettyButton input_button"  value="<?= PREVIOUS ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_this_url'] ?><?= $GLOBALS['tpl']['plugin_amazonchooser_previouspage'] ?>"></span>
                       <?php endif; ?>
                       <?php if (isset($GLOBALS['tpl']['plugin_amazonchooser_nextpage'])): ?>
                       <span class="serendipity_amazonchr_previousbutton"><input type="button" class="serendipityPrettyButton input_button"  value="<?= NEXT ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_this_url'] ?><?= $GLOBALS['tpl']['plugin_amazonchooser_nextpage'] ?>"></span>
                       <?php endif; ?>
                    </div>
              <?php else: ?>
                    <br>
                    <br>
                    <div>
                        <span><?= $GLOBALS['tpl']['plugin_amazonchooser_error_message'] ?></span>
                        <br>
                        <span><?= $GLOBALS['tpl']['plugin_amazonchooser_error_result'] ?></span>
                    </div>
                    <br>
              <?php endif; ?>
                <div class="serendipity_amazonchr_body_list">
                    <input type="button" class="serendipityPrettyButton input_button"  value="<?= BACK ?>" onclick=window.location.href="<?= $GLOBALS['tpl']['plugin_amazonchooser_search_url'] ?>">
                </div>
            <?php elseif ($GLOBALS['tpl']['plugin_amazonchooser_page'] == 'Lookup'): ?>
              <?php if ($GLOBALS['tpl']['plugin_amazonchooser_item_count'] == 1 && $GLOBALS['tpl']['plugin_amazonchooser_return_count'] == 1): ?>
                <h3><?= PLUGIN_EVENT_AMAZONCHOOSER_CHOSE ?> - <?= $GLOBALS['tpl']['thingy']['strings']['title'] ?></h3>
                <?= serendipity_getTemplateFile($GLOBALS['tpl']['plugin_amazonchooser_displaytemplate']); ?>
                <form action="#" method="get" name="serendipity[selForm]">
                    <div>
                        <input type="hidden" name="asin" value="<?= $GLOBALS['tpl']['thingy']['strings']['ASIN'] ?>">
                        <input type="hidden" name="searchmode" value="<?= $GLOBALS['tpl']['plugin_amazonchooser_searchmode'] ?>">
                    </div>

                    <div class="form_field">
                        <input type="button" class="serendipityPrettyButton input_button"  value="<?= BACK ?>" onclick="history.go(-1);">
                    <?php if ($GLOBALS['tpl']['plugin_amazonchooser_simple'] == '1'): ?>
                        <input type="button" class="serendipityPrettyButton input_button"  value="<?= DONE ?>" onclick="serendipity_amazonSelector_simpledone('<?= $GLOBALS['tpl']['plugin_amazonchooser_txtarea'] ?>')">
                    <?php else: ?>
                        <input type="button" class="serendipityPrettyButton input_button"  value="<?= DONE ?>" onclick="serendipity_amazonSelector_done('<?= $GLOBALS['tpl']['plugin_amazonchooser_txtarea'] ?>')">
                    <?php endif; ?>
                    </div>
                </form>
              <?php else: ?>
                <h3><<?= PLUGIN_EVENT_AMAZONCHOOSER_CHOSE ?></h3>
                <br>
                <br>
                <div>
                    <span><?= $GLOBALS['tpl']['plugin_amazonchooser_error_message'] ?></span>
                    <br>
                    <span><?= $GLOBALS['tpl']['plugin_amazonchooser_error_result'] ?></span>
                </div>
                <input type="button" class="serendipityPrettyButton input_button"  value="<?= BACK ?>" onclick="history.go(-1);">
                <br>
              <?php endif; ?>

            <?php else: ?>
                    <?= PLUGIN_EVENT_AMAZONCHOOSER_SEARCH_DESC ?>
                    <div>
                        <form name="serendipity[selForm]" onsubmit="serendipity_amazonSelector_next(); return false;">
                            <div>
                                <input type="hidden" name="step" value="1">
                                <input type="hidden" name="url" value="<?= $GLOBALS['tpl']['plugin_amazonchooser_link'] ?>">
                                <input type="hidden" name="txtarea" value="<?= $GLOBALS['tpl']['plugin_amazonchooser_txtarea'] ?>">
                                <input type="hidden" name="simple" value="<?= $GLOBALS['tpl']['plugin_amazonchooser_simple'] ?>">
                            </div>
                            <select name="mode">
<?php foreach ($GLOBALS['tpl']['plugin_amazonchooser_mode'] AS $type => $mode_names):?>
    <?php if ($GLOBALS['tpl']['plugin_amazonchooser_defaultmode'] == $type): ?>
                            <option value="<?= $type ?>" selected="selected"><?= $mode_names ?></option>
    <?php else: ?>
                            <option value="<?= $type ?>"><?= $mode_names ?></option>
    <?php endif; ?>
<?php endforeach; ?>
                            </select>

                            <div class="form_field">
                                <input class="input_textbox" type="text" name="keyword" value="<?= $GLOBALS['tpl']['plugin_amazonchooser_keyword'] ?>"/>
                                <br>
                                <input type="button" class="serendipityPrettyButton input_button"  value="<?= PLUGIN_EVENT_AMAZONCHOOSER_SEARCH ?>" onclick="serendipity_amazonSelector_next()">
                            </div>
                        </form>
                    </div>
            <?php endif; ?>

                </div><!--//serendipity_amazonchr_body_list end-->
            </div><!--//serendipityAdminContent end-->
        </div><!--//serendipityAdminMainpane end-->
    </body>

</html>
