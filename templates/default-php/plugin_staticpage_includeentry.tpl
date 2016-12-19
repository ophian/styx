{* A "default fallback" frontend "plugin_staticpage_includeentry.tpl" file v. 1.00, 2015-08-13 - in case it was set in a template, OR an entry, switching to a new theme without this file! *}
<article id="staticpage_<?= serendipity_makeFilename($GLOBALS['tpl']['staticpage_pagetitle']); ?>" class="clearfix serendipity_staticpage_includeentry<?php if ($GLOBALS['tpl']['staticpage_articleformat']) ?> serendipity_entry<?php endif; ?>">
    <?php if ($GLOBALS['tpl']['staticpage_precontent']) ?>
    <div class="clearfix content serendipity_preface">
    <?= $GLOBALS['tpl']['staticpage_precontent'] ?>
    </div>
    <?php endif; ?>
    <?php if (is_array($GLOBALS['tpl']['staticpage_childpages'])) ?>
    <div class="clearfix content staticpage_childpages">
        <ul id="staticpage_childpages">
            <?php foreach ($GLOBALS['tpl']['staticpage_childpages'] AS $childpage):?>
            <li><a href="<?= $childpage['permalink'] ?>" title="<?= $childpage['pagetitle']) ?>"><?= $childpage['pagetitle']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <?php if ($GLOBALS['tpl']['staticpage_content']) ?>
    <div class="clearfix content <?php if ($GLOBALS['tpl']['staticpage_articleformat']) ?>serendipity_entry_body<?php else: ?>staticpage_content<?php endif; ?>">
    <?= $GLOBALS['tpl']['staticpage_content'] ?>
    </div>
    <?php endif; ?>
</article>
