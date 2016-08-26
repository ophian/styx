<?xml version="1.0" encoding="utf-8" ?>

<rdf:RDF <?= $GLOBALS['tpl']['namespace_display_dat'] ?>
   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
   xmlns:admin="http://webns.net/mvcb/"
   xmlns:content="http://purl.org/rss/1.0/modules/content/"
   xmlns:dc="http://purl.org/dc/elements/1.1/"
   xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
   xmlns:wfw="http://wellformedweb.org/CommentAPI/"
   xmlns="http://my.netscape.com/rdf/simple/0.9/">
<channel>
    <title><?= $GLOBALS['tpl']['metadata']['title'] ?></title>
    <link><?= $GLOBALS['tpl']['metadata']['link'] ?></link>
    <description><?= $GLOBALS['tpl']['metadata']['description'] ?></description>
    <dc:language><?= $GLOBALS['tpl']['metadata']['language'] ?></dc:language>
<?php if ($GLOBALS['tpl']['metadata']['showMail']): ?>
    <admin:errorReportsTo rdf:resource="mailto:<?= $GLOBALS['tpl']['metadata']['email'] ?>" />
<?php endif; ?>

    <?= $GLOBALS['tpl']['metadata']['additional_fields']['image_rss10_channel'] ?>

    <items>
      <rdf:Seq>
<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
        <rdf:li resource="{serendipity_rss_getguid entry=$entry is_comments=$is_comments ?>" />
<?php foreachend; ?>
      </rdf:Seq>
    </items>
</channel>

<?= $GLOBALS['tpl']['metadata']['additional_fields']['image_rss10_rdf'] ?>
<?= $GLOBALS['tpl']['once_display_dat'] ?>

<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
<item rdf:about="<?= $entry['feed_guid'] ?>">
    <title><?= $entry['feed_title'] ?></title>
    <link><?= $entry['feed_entryLink'] ?></link>
<?php if (!empty($entry['body'])): ?>
    <description>
    <?= serendipity_specialchars($entry['feed_body']) ?> <?= serendipity_specialchars($entry['feed_ext']) ?>
    </description>
<?php endif; ?>

    <dc:publisher><?= $entry['feed_blogTitle'] ?></dc:publisher>
    <dc:creator><?= $entry['feed_email'] ?> (<?= $entry['feed_author'] ?>)</dc:creator>
    <dc:subject>
    <?php foreach ($entry['categories'] AS $cat): ?><?= $cat['feed_category_name'] ?>, <?php foreachend; ?></dc:subject>
    <dc:date><?= $entry['feed_timestamp'] ?></dc:date>
    <wfw:comment><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>wfwcomment.php?cid=<?= $entry['feed_id'] ?></wfw:comment>
<?php if (!$is_comments): ?>
        <slash:comments><?= $entry['comments'] ?></slash:comments>
        <wfw:commentRss><?= $GLOBALS['tpl']['serendipityBaseURL'] ?>rss.php?version=<?= $GLOBALS['tpl']['metadata']['version'] ?>&amp;type=comments&amp;cid=<?= $entry['feed_id'] ?></wfw:commentRss>
<?php endif; ?>
    <?= $entry['per_entry_display_dat'] ?>
</item>
<?php foreachend; ?>

</rdf:RDF>

