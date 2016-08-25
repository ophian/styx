<?php if (!empty($GLOBALS['tpl']['staticpage_custom']['relTags'])): ?>

        <h3>Entries by related tags:</h3>
        <div class="serendipity_freeTag">
             <p>(<?= str_replace(';',', ', $GLOBALS['tpl']['staticpage_custom']['relTags']) ?>)</p>
        </div>

    <?php if (!empty($GLOBALS['tpl']['entries'])): ?>
        <ul>
    <?php foreach ($GLOBALS['tpl']['entries'] AS $dategroup): ?>
        <?php foreach ($dategroup['entries'] AS $entry): ?>

            <li class="static-entries">
                (<?= serendipity_formatTime("%d.%m.%Y", $dategroup['date']); ?>) <a href="<?= $entry['link'] ?>"><?= (!empty($entry['title']) ? $entry['title'] : $entry['id']) ?></a>
            </li>
        <?php endforeach; ?>
    <?php endforeach; ?>

        </ul>
    <?php endif; ?>

<?php endif; ?>
