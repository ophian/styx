<?xml version="1.0" encoding="utf-8" ?>
<?xml-stylesheet href="<?= serendipity_getTemplateFile('atom.css'); ?>" type="text/css" ?>

<feed <?= $GLOBALS['tpl']['namespace_display_dat'] ?>
   xmlns="http://www.w3.org/2005/Atom"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
   xmlns:wfw="http://wellformedweb.org/CommentAPI/">
    <link href="<?= $GLOBALS['tpl']['self_url'] ?>" rel="self" title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="application/atom+xml" />
    <link href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>"                        rel="alternate"    title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="text/html" />
    <link href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=2.0"     rel="alternate"    title="<?= $GLOBALS['tpl']['metadata']['title'] ?>" type="application/rss+xml" />
    <title type="html"><?= $GLOBALS['tpl']['metadata']['title'] ?></title>
    <subtitle type="html"><?= $GLOBALS['tpl']['metadata']['description'] ?></subtitle>
    <?= $GLOBALS['tpl']['metadata']['additional_fields']['image_atom10'] ?>
    <id><?= $GLOBALS['tpl']['metadata']['link'] ?></id>
    <updated><?= $GLOBALS['tpl']['last_modified'] ?></updated>
    <generator uri="http://www.s9y.org/" version="<?= $GLOBALS['tpl']['serendipityVersion'] ?>">Serendipity <?= $GLOBALS['tpl']['serendipityVersion'] ?> - http://www.s9y.org/</generator>
    <dc:language><?= $GLOBALS['tpl']['metadata']['language'] ?></dc:language>
<?php if ($GLOBALS['tpl']['metadata']['showMail']): ?>
    <admin:errorReportsTo rdf:resource="mailto:<?= $GLOBALS['tpl']['metadata']['email'] ?>" />
<?php endif; ?>

<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
    <entry>
        <link href="<?= $entry['feed_entryLink'] ?>" rel="alternate" title="<?= $entry['feed_title'] ?>" />
        <author>
            <name><?= $entry['feed_author'] ?></name>
            <email><?= $entry['feed_email'] ?></email>
        </author>

        <published><?= $entry['feed_timestamp'] ?></published>
        <updated><?= $entry['feed_last_modified'] ?></updated>
        <wfw:comment><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>wfwcomment.php?cid=<?= $entry['feed_id'] ?></wfw:comment>

    <?php if (!$GLOBALS['tpl']['is_comments']): ?>
        <slash:comments><?= $entry['comments'] ?></slash:comments>
        <wfw:commentRss><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=<?= $GLOBALS['tpl']['metadata']['version'] ?>&amp;type=comments&amp;cid=<?= $entry['feed_id'] ?></wfw:commentRss>
    <?php endif; ?>

    <?php foreach ($entry['categories'] AS $cat): ?>
        <category scheme="<?= $cat['categoryURL'] ?>" label="<?= $cat['feed_category_name'] ?>" term="<?= $cat['feed_category_name'] ?>" />
    <?php endforeach; ?>

        <id><?= $entry['feed_guid'] ?></id>
        <title type="html"><?= $entry['feed_title'] ?></title>
    <?php if (!empty($entry['body'])): ?>
        <content type="xhtml" xml:base="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>">
            <div xmlns="http://www.w3.org/1999/xhtml">
                <?= $entry['feed_body'] ?> <?= $entry['feed_ext'] ?>
            </div>
        </content>
    <?php endif; ?>
        <?= $entry['per_entry_display_dat'] ?>
    </entry>
<?php endforeach; ?>

</feed>