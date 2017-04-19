<?xml version="1.0" encoding="utf-8" ?>
<?xml-stylesheet href="<?= serendipity_getTemplateFile('atom.css'); ?>" type="text/css" ?>

<feed version="0.3" <?= $GLOBALS['tpl']['namespace_display_dat'] ?>
   xmlns="http://purl.org/atom/ns#"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
   xmlns:wfw="http://wellformedweb.org/CommentAPI/">
    <link href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=atom0.3" rel="service.feed" title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="application/x.atom+xml" />
    <link href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>"                        rel="alternate"    title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="text/html" />
    <link href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=2.0"     rel="alternate"    title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="application/rss+xml" />
    <title mode="escaped" type="text/html"><?= $GLOBALS['tpl']['metadata']['title'] ?></title>
    <tagline mode="escaped" type="text/html"><?= $GLOBALS['tpl']['metadata']['description'] ?></tagline>
    <id><?= $GLOBALS['tpl']['metadata']['link'] ?></id>
    <modified><?= $GLOBALS['tpl']['last_modified'] ?></modified>
    <generator url="https://www.s9y.org/" version="<?= $GLOBALS['tpl']['serendipityVersion'] ?>">Serendipity <?= $GLOBALS['tpl']['serendipityVersion'] ?> - https://www.s9y.org/</generator>
    <dc:language><?= $GLOBALS['tpl']['metadata']['language'] ?></dc:language>
<?php if ($GLOBALS['tpl']['metadata']['showMail']): ?>
    <admin:errorReportsTo rdf:resource="mailto:<?= $GLOBALS['tpl']['metadata']['email'] ?>" />
<?php endif; ?>
    <info mode="xml" type="text/html">
        <div xmlns="http://www.w3.org/1999/xhtml">You are viewing an ATOM formatted XML site feed. Usually this file is inteded to be viewed in an aggregator or syndication software. If you want to know more about ATOM, please visist <a href="http://atomenabled.org/">Atomenabled.org</a></div>
    </info>

<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
    <entry>
        <link href="<?= $entry['feed_entryLink'] ?>" rel="alternate" title="<?= $entry['feed_title'] ?>" type="text/html" />
        <author>
            <name><?= $entry['feed_author'] ?></name>
            <email><?= $entry['feed_email'] ?></email>
        </author>

        <issued><?= $entry['feed_timestamp'] ?></issued>
        <created><?= $entry['feed_timestamp'] ?></created>
        <modified><?= $entry['feed_last_modified'] ?></modified>
        <wfw:comment><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>wfwcomment.php?cid=<?= $entry['feed_id'] ?></wfw:comment>
<?php if (!$GLOBALS['tpl']['is_comments']): ?>
        <slash:comments><?= $entry['comments'] ?></slash:comments>
        <wfw:commentRss><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=<?= $GLOBALS['tpl']['metadata']['version'] ?>&amp;type=comments&amp;cid=<?= $entry['feed_id'] ?></wfw:commentRss>
<?php endif; ?>
        <id><?= $entry['feed_guid'] ?></id>
        <title mode="escaped" type="text/html"><?= $entry['feed_title'] ?></title>
<?php if (!empty($entry['body'])): ?>
        <content type="application/xhtml+xml" xml:base="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>">
            <div xmlns="http://www.w3.org/1999/xhtml">
                <?= $entry['feed_body'] ?> <?= $entry['feed_ext'] ?>
            </div>
        </content>
<?php endif; ?>

        <?= $entry['per_entry_display_dat'] ?>
    </entry>
<?php endforeach; ?>
</feed>