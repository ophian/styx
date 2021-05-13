{if $entries}
<div class="page_content mb-3">
    <h3>{$CONST.STATICPAGE_NEW_HEADLINES}</h3>

    <div class="mb-3 bg-light rounded">
{foreach $entries AS $dategroup}
    {foreach $dategroup.entries AS $entry}
      <div class="d-flex">
        <div class="p-1 w-100 bd-highlight">
          <a href="{$entry.link}">{$entry.title|default:$entry.id}</a>
        </div>
        <div class="p-1 flex-shrink-1">
          ({$dategroup.date|date_format:"%d.%m.%Y"})
        </div>
      </div>
    {/foreach}
{/foreach}
    </div>

    {*  for normal static pages  *}
    <p>&raquo; <a href="{$serendipityBaseURL}{getCategoryLinkByID cid=$staticpage_related_category_id}">{$CONST.STATICPAGE_ARTICLE_OVERVIEW}</a></p>

    {* for a staticpage as startpage  *}
    {* <p>&raquo; <a href="{$serendipityArchiveURL}/P1.html">{$CONST.STATICPAGE_ARTICLE_OVERVIEW}</a></p>  *}
</div>
{/if}