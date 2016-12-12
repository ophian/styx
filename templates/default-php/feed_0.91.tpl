<?xml version="1.0" encoding="utf-8" ?>

<rss version="0.91" <?= $GLOBALS['tpl']['namespace_display_dat'] ?>>
<channel>
<title><?= $GLOBALS['tpl']['metadata']['title'] ?></title>
<link><?= $GLOBALS['tpl']['metadata']['link'] ?></link>
<description><?= $GLOBALS['tpl']['metadata']['description'] ?></description>
<language><?= $GLOBALS['tpl']['metadata']['language'] ?></language>
<?= $GLOBALS['tpl']['metadata']['additional_fields']['image'] ?>

<?php foreach ($GLOBALS['tpl']['entries'] AS $entry): ?>
<item>
    <title><?= $entry['feed_title'] ?></title>
    <link><?= $entry['feed_entryLink'] ?></link>

<?php if (!empty($entry['body'])): ?>
    <description>
        <?= serendipity_specialchars($entry['feed_body']) ?> <?= serendipity_specialchars($entry['feed_ext']) ?>
    </description>
<?php endif; ?>
</item>
<?php endforeach; ?>

</channel>
</rss>

