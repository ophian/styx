{if $is_form}
<form id="serendipity_category_form" action="{$form_url}" method="post">
    <div id="serendipity_category_form_content">
{/if}

    <ul id="serendipity_categories_list">
{foreach $categories AS $plugin_category}
        <li class="category_depth{$plugin_category.catdepth} category_{$plugin_category.categoryid}">
        {if $is_form}
            <input type="checkbox" name="serendipity[multiCat][]" value="{$plugin_category.categoryid}" />
        {/if}

        {if NOT empty($category_image)}
            <a class="serendipity_xml_icon" href="{$plugin_category.feedCategoryURL}"><img src="{$category_image}" alt="XML" /></a>
        {/if}

            <a href="{$plugin_category.categoryURL}" title="{$plugin_category.category_description|escape}" style="padding-left: {$plugin_category.paddingPx}px">{$plugin_category.category_name|escape}</a>
        </li>
{/foreach}
    </ul>

{if $is_form}
    <div class="category_submit"><input type="submit" name="serendipity[isMultiCat]" value="{if isset($smarty.get.serendipity.category)}{$CONST.RESET_FILTERS}{else}{$CONST.GO}{/if}" /></div>
{/if}

{if $show_all}
    <div class="category_link_all"><a href="{$form_url}?frontpage" title="{$CONST.ALL_CATEGORIES}">{$CONST.ALL_CATEGORIES}</a></div>
{/if}

{if $is_form}
    </div>
</form>
{/if}
