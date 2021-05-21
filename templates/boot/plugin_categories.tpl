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
              <svg class="me-1" width="16" height="16" role="img" aria-labelledby="title"> <title id="pctrss_{$plugin_category@key}">XML</title><use xlink:href="#rss-fill"/></svg>
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