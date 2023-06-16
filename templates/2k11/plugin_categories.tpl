{if $is_form}
<form id="serendipity_category_form" action="{$form_url}" method="post">
{/if}
    <ul class="plainList">
    {foreach $categories AS $plugin_category}
        <li id="category_{$plugin_category.categoryid}" class="category_depth{$plugin_category.catdepth}">
        {if $is_form}
            <input name="serendipity[multiCat][]" type="checkbox" value="{$plugin_category.categoryid}"{if isset($plugin_category.checkcat)} checked=checked{/if}>
        {/if}
        {if NOT empty($category_image)}
            <a class="serendipity_xml_icon" href="{$plugin_category.feedCategoryURL}"><img src="{$category_image}" alt="XML"></a>
        {/if}
            <a href="{$plugin_category.categoryURL}" title="{$plugin_category.category_description|escape}">{$plugin_category.category_name|escape}</a>
        </li>
    {/foreach}
    </ul>
{if $is_form}
    <input class="category_submit" name="serendipity[isMultiCat]" type="submit" value="{if NOT empty($smarty.get.serendipity.category)}{$CONST.RESET_FILTERS}{else}{$CONST.GO}{/if}">
{/if}
{if $show_all}
    <a class="category_link_all" href="{$form_url}?frontpage">{$CONST.ALL_CATEGORIES}</a>
{/if}
{if $is_form}
</form>
{/if}
