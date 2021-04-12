<h4>{$CONST.STATICPAGE_NEW_HEADLINES|capitalize}</h4>
{if NOT empty($entries)}
    <dl>
        {foreach $entries AS $dategroup}
            {foreach $dategroup.entries AS $entry}
            <dt class="serendipity_recententries_entrylink"><a href="{$entry.link}" title="{$entry.title|default:$entry.id}">{$entry.title|default:$entry.id}</a></dt>
            <dd class="text-muted serendipity_recententries_entrydate"><small>{$dategroup.date|formatTime:($template_option.date_format|default:$CONST.DATE_FORMAT_ENTRY)}</small></dd>
            {/foreach}
        {/foreach}
    </dl>
    {if $staticpage_related_category_id >0}
        <a class="btn btn-secondary btn-md btn-theme" href="{$serendipityBaseURL}{$serendipityRewritePrefix}{getCategoryLinkByID cid=$staticpage_related_category_id}">{$CONST.STATICPAGE_ARTICLE_OVERVIEW|capitalize} <i class="fa fa-arrow-right" aria-hidden="true"></i></a><br />
    {else}
        <a class="btn btn-secondary btn-md btn-theme" href="{$serendipityArchiveURL}/P1.html">{$CONST.STATICPAGE_ARTICLE_OVERVIEW|capitalize} <i class="fa fa-arrow-right" aria-hidden="true"></i></a><br />
    {/if}
{else}
    <p class="alert alert-info noentries"><span class="fa-stack"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-info fa-stack-1x"></i></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
{/if}