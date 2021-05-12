{if $entries}
<div class="page_content mb-3">
    <h3>{$CONST.STATICPAGE_NEW_HEADLINES}</h3>

    <ul>
    {foreach $entries AS $dategroup}
        {foreach $dategroup.entries AS $entry}
        <li class="static-entries">
            ({$dategroup.date|date_format:"%d.%m.%Y"}) <a href="{$entry.link}">{$entry.title|default:$entry.id}</a>
        </li>
        {/foreach}
    {/foreach}
    </ul>

    {*  for normal static pages  *}
    <p>&raquo; <a href="{$serendipityBaseURL}{getCategoryLinkByID cid=$staticpage_related_category_id}">{$CONST.STATICPAGE_ARTICLE_OVERVIEW}</a></p>

    {* for a staticpage as startpage  *}
    {* <p>&raquo; <a href="{$serendipityArchiveURL}/P1.html">{$CONST.STATICPAGE_ARTICLE_OVERVIEW}</a></p>  *}
</div>
{/if}