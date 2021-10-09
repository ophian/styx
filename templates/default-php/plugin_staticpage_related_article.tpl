<?php /* frontend plugin_staticpage_related_article.tpl file v. 1.05, 2015-02-01 */ ?>
<article id="staticpage_<?= serendipity_makeFilename($GLOBALS['tpl']['staticpage_pagetitle']); ?>" class="clearfix serendipity_staticpage<?php if ($GLOBALS['tpl']['staticpage_articleformat']): ?> serendipity_entry<?php endif; ?>">
    <header>
        <h2><?php if ($GLOBALS['tpl']['staticpage_articleformat']): ?><?php if ($GLOBALS['tpl']['staticpage_articleformattitle']): ?><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_articleformattitle']) ?><?php else: ?><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_pagetitle']) ?><?php endif; ?><?php else: ?><?php if ($GLOBALS['tpl']['staticpage_headline']): ?><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_headline']) ?><?php else: ?><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_pagetitle']) ?><?php endif; ?><?php endif; ?></h2>
    <?php if (is_array($GLOBALS['tpl']['staticpage_navigation']) && ($GLOBALS['tpl']['staticpage_shownavi'] || $GLOBALS['tpl']['staticpage_show_breadcrumb'])): ?>
        <div id="staticpage_nav">
        <?php if ($GLOBALS['tpl']['staticpage_shownavi']): ?>
            <ul class="staticpage_navigation">
                <li class="staticpage_navigation_left"><?php if (!empty($GLOBALS['tpl']['staticpage_navigation']['prev']['link)'])): ?><a href="<?= $GLOBALS['tpl']['staticpage_navigation']['prev']['link'] ?>" title="prev"><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_navigation']['prev']['name']) ?></a><?php else: ?><span class="staticpage_navigation_dummy"><?= PREVIOUS ?></span><?php endif; ?></li>
                <li class="staticpage_navigation_center"><?php if ($GLOBALS['tpl']['staticpage_navigation']['top']['new']): ?><?php if (!empty($GLOBALS['tpl']['staticpage_navigation']['top']['topp_name'])): ?><a href="<?= $GLOBALS['tpl']['staticpage_navigation']['top']['topp_link'] ?>" title="top"><?= $GLOBALS['tpl']['staticpage_navigation']['top']['topp_name'] ?></a> | <?php endif; ?>&#171 <?= $GLOBALS['tpl']['staticpage_navigation']['top']['curr_name'] ?> &#187; <?php if (!empty($GLOBALS['tpl']['staticpage_navigation']['top']['exit_name'])): ?>| <a href="<?= $GLOBALS['tpl']['staticpage_navigation']['top']['exit_link'] ?>" title="exit"><?= $GLOBALS['tpl']['staticpage_navigation']['top']['exit_name'] ?></a><?php endif; ?><?php else: ?><a href="<?= $GLOBALS['tpl']['staticpage_navigation']['top']['link'] ?>" title="current page"><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_navigation']['top']['name']) ?></a><?php endif; ?></li>
                <li class="staticpage_navigation_right"><?php if (!empty($GLOBALS['tpl']['staticpage_navigation']['next']['link)'])): ?><a href="<?= $GLOBALS['tpl']['staticpage_navigation']['next']['link'] ?>" title="next"><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_navigation']['next']['name']) ?></a><?php else: ?><span class="staticpage_navigation_dummy"><?= NEXT ?></span><?php endif; ?></li>
            </ul>
        <?php endif; ?>
        <?php if ($GLOBALS['tpl']['staticpage_show_breadcrumb']): ?>
            <div class="staticpage_navigation_breadcrumb">
                <a href="<?= $GLOBALS['tpl']['serendipityBaseURL'] ?>"><?= HOMEPAGE ?></a> &#187;
            <?php $i=0; ?>
            <?php foreach ($GLOBALS['tpl']['staticpage_navigation']['crumbs'] AS $crumb):?>
                <?php if (!$i == 0): ?>&#187;<?php endif; ?><?php if ($crumb['id'] != $GLOBALS['tpl']['staticpage_pid']): ?><a href="<?= $crumb['link'] ?>"><?= serendipity_specialchars($crumb['name']) ?></a><?php else: ?><?= serendipity_specialchars($crumb['name']) ?><?php endif; ?>
            <?php $i++; ?>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
        </div>
    <?php endif; ?>
    </header>
<?php if ($GLOBALS['tpl']['staticpage_pass'] && $GLOBALS['tpl']['staticpage_form_pass'] != $GLOBALS['tpl']['staticpage_pass']): ?>
    <form class="staticpage_password_form" action="<?= $GLOBALS['tpl']['staticpage_form_url'] ?>" method="post">
    <fieldset>
        <legend><?= STATICPAGE_PASSWORD_NOTICE ?></legend>
        <input name="serendipity[pass]" type="password" value="">
        <input name="submit" type="submit" value="<?= GO ?>" >
    </fieldset>
    </form>
<?php else: ?>
    <?php if ($GLOBALS['tpl']['staticpage_precontent']): ?>
    <div class="clearfix content serendipity_preface">
    <?= $GLOBALS['tpl']['staticpage_precontent'] ?>
    </div>
    <?php endif; ?>
    <?php if (is_array($GLOBALS['tpl']['staticpage_childpages'])): ?>
    <div class="clearfix content staticpage_childpages">
        <ul id="staticpage_childpages">
            <?php foreach ($GLOBALS['tpl']['staticpage_childpages'] AS $childpage):?>
            <li><a href="<?= serendipity_specialchars($childpage['permalink']) ?>" title="<?= serendipity_specialchars($childpage['pagetitle']) ?>"><?= serendipity_specialchars($childpage['pagetitle']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
<?php
/* Staticpage related article by freetags.
   Better use a theme unique name, eg. mytheme_related_articles.tpl*/

function default_php_staticpage_show_tags($params) {
    $o = serendipity_specialchars($GLOBALS['serendipity']['GET']['tag']);
    $GLOBALS['serendipity']['GET']['tag'] = $params['tag'];
    $e = serendipity_smarty_fetchPrintEntries($params, template);
    echo $e;
    if (!empty($o)) {
        $GLOBALS['serendipity']['GET']['tag'] = $o;
    } else {
        unset($GLOBALS['serendipity']['GET']['tag']);
    }
}

?>
    <div class="clearfix content serendipity_preface staticpage_related_article_list">
    <?php $params = array('tag' => $GLOBALS['tpl']['staticpage_custom']['relTags'], 'template' => 'default-php_related_articles.tpl', 'limit' => $GLOBALS['tpl']['staticpage_custom']['relNumb'], 'noSticky' => 'true'); ?>
        <?= default_php_staticpage_show_tags($params); ?>
    </div>

    <?php if ($GLOBALS['tpl']['staticpage_content']): ?>
    <div class="clearfix content <?php if ($GLOBALS['tpl']['staticpage_articleformat']): ?>serendipity_entry_body<?php else: ?>staticpage_content<?php endif; ?>">
    <?= $GLOBALS['tpl']['staticpage_content'] ?>
    </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($GLOBALS['tpl']['staticpage_author'] OR $GLOBALS['tpl']['staticpage_lastchange'] OR $GLOBALS['tpl']['staticpage_adminlink']): ?>
    <footer class="staticpage_metainfo">
        <p>
        <?php if ($GLOBALS['tpl']['staticpage_author']): ?>
            <span class="single_user"><span class="visuallyhidden"><?= POSTED_BY ?> </span><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_author']) ?>
        <?php endif; ?>
        <?php if ($GLOBALS['tpl']['staticpage_author'] && $GLOBALS['tpl']['staticpage_lastchange']): ?> | </span><?php endif; ?>
        <?php if ($GLOBALS['tpl']['staticpage_lastchange']): ?>
            <span class="visuallyhidden"><?= ON ?> </span>
            <?php if ($GLOBALS['tpl']['staticpage_use_lmdate']): ?>
            <time datetime="<?= date("c", $GLOBALS['tpl']['staticpage_lastchange']) ?>"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $GLOBALS['tpl']['staticpage_lastchange']); ?></time>
            <?php if ($GLOBALS['tpl']['staticpage_adminlink'] && $GLOBALS['tpl']['staticpage_adminlink']['page_user']): ?> (<?= strtolower(CREATED_ON) ?>: <?= serendipity_strftime("%Y-%m-%d", $GLOBALS['tpl']['staticpage_created_on'], false); ?>)<?php endif; ?>
            <?php else: ?>
            <time datetime="<?= date("c", $GLOBALS['tpl']['staticpage_created_on']) ?>"><?= serendipity_formatTime(DATE_FORMAT_ENTRY, $GLOBALS['tpl']['staticpage_created_on']); ?></time>
            <?php if ($GLOBALS['tpl']['staticpage_adminlink'] && $GLOBALS['tpl']['staticpage_adminlink']['page_user']): ?> (<?= strtolower(LAST_UPDATED) ?>: <?= serendipity_strftime("%Y-%m-%d", $GLOBALS['tpl']['staticpage_lastchange'], false); ?>)<?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($GLOBALS['tpl']['staticpage_adminlink'] && $GLOBALS['tpl']['staticpage_adminlink']['page_user']): ?>
            | <a href="<?= $GLOBALS['tpl']['staticpage_adminlink']['link_edit'] ?>"><?= serendipity_specialchars($GLOBALS['tpl']['staticpage_adminlink']['link_name']) ?></a>
        <?php endif; ?>
        </p>
    </footer>
<?php endif; ?>
</article>
<?php /*  we dont have ['template_option']['date_format']  */ ?>
