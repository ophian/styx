<?php if ($GLOBALS['tpl']['is_embedded'] != true): ?>
<!doctype html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
<head>
    <title><?= $GLOBALS['template']->getdefault('head_title', 'blogTitle'); ?> - <?= $GLOBALS['template']->getdefault('head_subtitle', 'blogDescription'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= $GLOBALS['tpl']['head_charset']; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="generator" content="Serendipity v.<?= $GLOBALS['tpl']['serendipityVersion']; ?>" />
    <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet']; ?>" />
    <link rel="alternate"  type="application/rss+xml" title="<?= $GLOBALS['tpl']['blogTitle']; ?> RSS feed" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?><?= $GLOBALS['tpl']['serendipityRewritePrefix']; ?>feeds/index.rss2" />
    <link rel="alternate"  type="application/x.atom+xml"  title="<?= $GLOBALS['tpl']['blogTitle']; ?> Atom feed"  href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?><?= $GLOBALS['tpl']['serendipityRewritePrefix']; ?>feeds/atom.xml" />
<?php if ($GLOBALS['tpl']['entry_id']): ?>
    <link rel="pingback" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>comment.php?type=pingback&amp;entry_id=<?= $GLOBALS['tpl']['entry_id']; ?>" />
<?php endif; ?>

<?php serendipity_plugin_api::hook_event('frontend_header', $GLOBALS['template']); ?>
</head>

<body>
<?php else: ?>
<?php serendipity_plugin_api::hook_event('frontend_header', $GLOBALS['template']); ?>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['is_raw_mode'] != true): ?>
<div id="serendipity_banner">
    <h1><a class="homelink1" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>"><?= $GLOBALS['template']->getdefault('head_title', 'blogTitle'); ?></a></h1>
    <h2><a class="homelink2" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>"><?= $GLOBALS['template']->getdefault('head_subtitle', 'blogDescription'); ?></a></h2>
</div>

<div id="mainpane">
    <div id="content" valign="top"><?= $GLOBALS['tpl']['CONTENT']; ?></div>
<?php if ($GLOBALS['tpl']['leftSidebarElements'] > 0): ?>
    <div id="serendipityLeftSideBar" valign="top"><?php echo serendipity_plugin_api::generate_plugins('left'); ?></div>
<?php endif; ?>
<?php if ($GLOBALS['tpl']['rightSidebarElements'] > 0): ?>
    <div id="serendipityRightSideBar" valign="top"><?php echo serendipity_plugin_api::generate_plugins('right'); ?></div>
<?php endif; ?>
</div>
<?php endif; ?>

<?= $GLOBALS['tpl']['raw_data']; ?>
<?php serendipity_plugin_api::hook_event('frontend_footer', $GLOBALS['template']); ?>

<script type="text/javascript">
/* toggle content/left sidebar markup nodes for responsiveness */
(function ($) {
    if ($(window).width() < 980) {
        $("#serendipityLeftSideBar").before($("#content"));
    }
    else {
        $("#content").before($("#serendipityLeftSideBar"));
    }
    $(window).resize(function() {
        if ($(window).width() < 980) {
            $("#serendipityLeftSideBar").before($("#content"));
            $("#serendipityRightSideBar").before($("#serendipityLeftSideBar"));
        }
        else {
            $("#content").before($("#serendipityLeftSideBar"));
        }
    });
})(jQuery);
</script>

<?php if ($GLOBALS['tpl']['is_embedded'] != true): ?>
</body>
</html>
<?php endif; ?>
