<?php if ($GLOBALS['tpl']['is_embedded'] != true): ?>
<!DOCTYPE html>
<html lang="<?= $GLOBALS['tpl']['lang'] ?>">
<head>
    <meta charset="<?= $GLOBALS['tpl']['head_charset'] ?>">
    <title><?= $GLOBALS['template']->getdefault('head_title', 'blogTitle'); ?> - <?= $GLOBALS['template']->getdefault('head_subtitle', 'blogDescription'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition v.<?= $GLOBALS['tpl']['serendipityVersion']; ?>">
<?php if (in_array($GLOBALS['tpl']['view'], ['start', 'entries', 'entry', 'feed', 'plugin']) || !empty($GLOBALS['tpl']['staticpage_pagetitle']) || (isset($GLOBALS['tpl']['robots_index']) && $GLOBALS['tpl']['robots_index'] == 'index')): ?>
    <meta name="robots" content="index,follow">
<?php else: ?>
    <meta name="robots" content="noindex,follow">
<?php endif; ?>
<?php if ($GLOBALS['tpl']['view'] == 'entry' && isset($GLOBALS['tpl']['entry'])): ?>
    <link rel="canonical" href="{$entry.rdf_ident}">
<?php endif; ?>
<?php if (in_array($GLOBALS['tpl']['view'], ['start', 'entries'])): ?>
    <link rel="canonical" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>">
<?php endif; ?>
    <link rel="stylesheet" type="text/css" href="<?= $GLOBALS['tpl']['head_link_stylesheet']; ?>">
    <link rel="alternate"  type="application/rss+xml" title="<?= $GLOBALS['tpl']['blogTitle']; ?> RSS feed" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?><?= $GLOBALS['tpl']['serendipityRewritePrefix']; ?>feeds/index.rss2">
    <link rel="alternate"  type="application/x.atom+xml"  title="<?= $GLOBALS['tpl']['blogTitle']; ?> Atom feed"  href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?><?= $GLOBALS['tpl']['serendipityRewritePrefix']; ?>feeds/atom.xml">
<?php if ($GLOBALS['tpl']['entry_id']): ?>
    <link rel="trackback" type="application/x-www-form-urlencoded" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>comment.php?type=trackback&amp;entry_id=<?= $GLOBALS['tpl']['entry_id']; ?>">
    <link rel="pingback" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>comment.php?type=pingback&amp;entry_id=<?= $GLOBALS['tpl']['entry_id']; ?>">
<?php endif; ?>

<?php serendipity_plugin_api::hook_event('frontend_header', $GLOBALS['template']); ?>
</head>

<body>
<?php else: ?>
<?php serendipity_plugin_api::hook_event('frontend_header', $GLOBALS['template']); ?>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['is_raw_mode'] != true): ?>
    <header id="serendipity_banner">
        <h1><a class="homelink1" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>"><?= $GLOBALS['template']->getdefault('head_title', 'blogTitle'); ?></a></h1>
        <h2><a class="homelink2" href="<?= $GLOBALS['tpl']['serendipityBaseURL']; ?>"><?php if ($GLOBALS['tpl']['view'] == 'plugin'): ?><?= $GLOBALS['tpl']['blogDescription']; ?><?php else: ?><?= $GLOBALS['template']->getdefault('head_subtitle', 'blogDescription'); ?><?php endif; ?></a></h2>
    </header>

<div id="mainpane">
    <main id="content" valign="top">
        <?= $GLOBALS['tpl']['CONTENT']; ?>
    </main>
<?php if ($GLOBALS['tpl']['leftSidebarElements'] > 0): ?>
    <aside id="serendipityLeftSideBar" valign="top">
        <?php echo serendipity_plugin_api::generate_plugins('left'); ?>
    </aside>
<?php endif; ?>
<?php if ($GLOBALS['tpl']['rightSidebarElements'] > 0): ?>
    <aside id="serendipityRightSideBar" valign="top">
        <?php echo serendipity_plugin_api::generate_plugins('right'); ?>
    </aside>
<?php endif; ?>
</div>
<?php endif; ?>

<?= $GLOBALS['tpl']['raw_data']; ?>
<?php serendipity_plugin_api::hook_event('frontend_footer', $GLOBALS['template']); ?>

<script src="<?= serendipity_getTemplateFile('default.js') ?>"></script>

<?php if ($GLOBALS['tpl']['is_embedded'] != true): ?>
</body>
</html>
<?php endif; ?>
