{if $categories}
    <h3>{$CONST.CATEGORIES}</h3>
    <ul class="plainList category-list">
        {foreach $categories AS $plugin_category}
            {if $plugin_category@first}{assign var="prevdepth" value=$plugin_category.catdepth}{/if}
            {if ($plugin_category.catdepth == $prevdepth) AND NOT $plugin_category@first}
                </li>
            {elseif $plugin_category.catdepth < $prevdepth}
                {for $i=1 to $prevdepth-$plugin_category.catdepth}
                    </li>
                    </ul>
                {/for}
                </li>
            {elseif $plugin_category.catdepth > $prevdepth}
                <ul class="category-children">
            {/if}
            <li id="category_{$plugin_category.categoryid}" class="category_depth{$plugin_category.catdepth} archive-category-list-item">
                {if $template_option.category_rss_archive == true}<a class="btn btn-secondary btn-sm btn-theme serendipity_xml_icon" href="{$plugin_category.feedCategoryURL}" title="{$plugin_category.category_name|escape} rss"><i class="fas fa-rss"></i></a>{/if}
                <a class="btn btn-secondary btn-sm btn-theme" href="{$plugin_category.categoryURL}" title="{$plugin_category.category_description|escape}">{$plugin_category.category_name|escape}</a>
            {if $plugin_category@last}
                {if $plugin_category.catdepth>0}
                    {for $i=1 to $plugin_category.catdepth}
                        </li>
                        </ul>
                    {/for}
                {/if}
                </li>
            {/if}
            {assign var="prevdepth" value=$plugin_category.catdepth}
        {/foreach}
    </ul>
{else}
    <p class="alert alert-warning"><span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$CONST.CATEGORIES_ON_ARCHIVE_DESC}</p>
{/if}