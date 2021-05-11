{if $is_form}
<form id="serendipity_category_form" action="{$form_url}" method="post">
{/if}
    <ul class="plainList">
{foreach $categories AS $plugin_category}
        <li class="category_depth{$plugin_category.catdepth}">
{if $is_form}
            <input type="checkbox" name="serendipity[multiCat][]" value="{$plugin_category.categoryid}">
{/if}
{if NOT empty($category_image)}
            <a class="serendipity_xml_icon" href="{$plugin_category.feedCategoryURL}">
              <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-rss-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                <title id="title">XML</title>
                <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm1.5 2.5a1 1 0 0 0 0 2 8 8 0 0 1 8 8 1 1 0 1 0 2 0c0-5.523-4.477-10-10-10zm0 4a1 1 0 0 0 0 2 4 4 0 0 1 4 4 1 1 0 1 0 2 0 6 6 0 0 0-6-6zm.5 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
              </svg>
            </a>
{/if}
            <a href="{$plugin_category.categoryURL}" title="{$plugin_category.category_description|escape}">{$plugin_category.category_name|escape}</a>
        </li>
{/foreach}
    </ul>
{if $is_form}
    <input id="category_submit" type="submit" name="serendipity[isMultiCat]" value="{if isset($smarty.get.serendipity.category)}{$CONST.RESET_FILTERS}{else}{$CONST.GO}{/if}">
{/if}
{if $show_all}
    <a class="category_link_all" href="{$form_url}?frontpage">{$CONST.ALL_CATEGORIES}</a>
{/if}
{if $is_form}
</form>
{/if}