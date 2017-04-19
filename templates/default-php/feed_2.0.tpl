<?xml version="1.0" encoding="utf-8" ?>

<rss version="2.0"
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
   xmlns:wfw="http://wellformedweb.org/CommentAPI/"
   xmlns:content="http://purl.org/rss/1.0/modules/content/"
   <?= $GLOBALS['tpl']['namespace_display_dat'] ?>>
<channel>
    <title><?= $GLOBALS['tpl']['metadata']['title'] ?></title>
    <link><?= $GLOBALS['tpl']['metadata']['link'] ?></link>
    <description><?= $GLOBALS['tpl']['metadata']['description'] ?></description>
    <dc:language><?= $GLOBALS['tpl']['metadata']['language'] ?></dc:language>
<?php if ($GLOBALS['tpl']['metadata']['showMail']): ?>
    <admin:errorReportsTo rdf:resource="mailto:<?= $GLOBALS['tpl']['metadata']['email'] ?>" />
<?php endif; ?>
    <generator>Serendipity <?= $GLOBALS['tpl']['serendipityVersion'] ?> - https://www.s9y.org/</generator>
    <?= $GLOBALS['tpl']['metadata']['additional_fields']['channel'] ?>
    <?= $GLOBALS['tpl']['metadata']['additional_fields']['image'] ?>

<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
<item>
    <title><?= $entry['feed_title'] ?></title>
    <link><?= $entry['feed_entryLink'] ?></link>
    <?php foreach ($entry['categories'] AS $cat): ?>
        <category><?= $cat['feed_category_name'] ?></category>
    <?php endforeach; ?>

    <comments><?= $entry['feed_entryLink'] ?>#comments</comments>
    <wfw:comment><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>wfwcomment.php?cid=<?= $entry['feed_id'] ?></wfw:comment>

<?php if (!$GLOBALS['tpl']['is_comments']): ?>
    <slash:comments><?= $entry['comments'] ?></slash:comments>
    <wfw:commentRss><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=<?= $GLOBALS['tpl']['metadata']['version'] ?>&amp;type=comments&amp;cid=<?= $entry['feed_id'] ?></wfw:commentRss>
<?php endif; ?>

    <author><?= $entry['feed_email'] ?> (<?= $entry['feed_author'] ?>)</author>
<?php if (!empty($entry['body'])): ?>
    <content:encoded>
    <?= serendipity_specialchars($entry['feed_body']) ?> <?= serendipity_specialchars($entry['feed_ext']) ?>
    </content:encoded>
<?php endif; ?>

    <pubDate><?= $entry['feed_timestamp_r'] ?></pubDate>
    <guid isPermaLink="false"><?= $entry['feed_guid'] ?></guid>
    <?= $entry['per_entry_display_dat'] ?>
</item>
<?php endforeach; ?>

</channel>
</rss>
