{if $is_form}
<form id="serendipity_category_form" action="{$form_url}" method="post">
{/if}
    <ul class="plainList">
    {foreach $categories AS $plugin_category}
        <li class="category_depth{$plugin_category.catdepth}">
        {if $is_form}
            <input type="checkbox" name="serendipity[multiCat][]" value="{$plugin_category.categoryid}"{if isset($plugin_category.checkcat)} checked="checked"{/if}>
        {/if}
            <svg class="icon-rss" role="img" viewbox="0 0 1792 1792" width="1792" height="1792" aria-labelledby="title"><title id="title">XML</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#rss"></use></svg><a href="{$plugin_category.categoryURL}" title="{$plugin_category.category_description|escape}">{$plugin_category.category_name|escape}</a>
        </li>
    {/foreach}
    </ul>
{if $is_form}
    <input id="category_submit" type="submit" name="serendipity[isMultiCat]" value="{if NOT empty($smarty.get.serendipity.category)}{$CONST.RESET_FILTERS}{else}{$CONST.GO}{/if}">
{/if}
{if $show_all}
    <a class="category_link_all" href="{$form_url}?frontpage">{$CONST.ALL_CATEGORIES}</a>
{/if}
{if $is_form}
</form>
{/if}