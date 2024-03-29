{if NOT empty($post_save)}
{if NOT empty($new)}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.CATEGORY_SAVED}</span>

{/if}
{if NOT empty($edit)}
{if isset($editPermission) AND $editPermission === false}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED} {$failedperm}</span>

{else}
{if NOT empty($subcat)}
    {$subcat}
{else}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.CATEGORY_SAVED}</span>

{/if}
{/if}
{/if}
{if isset($error_name)}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CATEGORY_ALREADY_EXIST|sprintf:$category_name|escape}</span>

{/if}
{/if}
{if NOT empty($doDelete)}
{if NOT empty($deleteSuccess)}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {if isset($remainingCat)}{$CONST.CATEGORY_DELETED_ARTICLES_MOVED|sprintf:$remainingCat:$cid}{else}{$cid|string_format:"{$CONST.CATEGORY_DELETED}"}{/if}</span>

{else}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.INVALID_CATEGORY}</span>

{/if}
{/if}
{if NOT empty($delete)}
{if $deletePermission === true}
    <h2>{$categoryName|escape}</h2>

    <form method="POST" name="serendipityCategory" action="?serendipity[adminModule]=category&amp;serendipity[adminAction]=doDelete&amp;serendipity[cid]={$cid}">
        {$formToken}

        <div class="form_select">
            <label for="remaining_cat">{$CONST.CATEGORY_REMAINING}:</label>
            <select id="remaining_cat" name="serendipity[cat][remaining_catid]">
                <option value="0">{$CONST.NO_CATEGORY}</option>
{foreach $cats AS $cat_data}
                <option value="{$cat_data.categoryid}">{$cat_data.category_name|escape}</option>
{/foreach}
            </select>
        </div>

        <input class="state_cancel" name="REMOVE" type="submit" value="{$CONST.DELETE}">
    </form>
{/if}
{/if}
{if empty($post_save) AND (NOT empty($edit) OR NOT empty($new))}
{if NOT empty($edit)}
    <h2>{$category_name|escape|string_format:"{$CONST.EDIT_THIS_CAT}"}</h2>
{/if}

    <form id="serendipity_category" method="POST" name="serendipityCategory">
        {$formToken}

        <div id="category_basics" class="clearfix">
            <div class="form_field">
                <label for="category_name">{$CONST.NAME}</label>
                <input id="category_name" pattern="{if NOT empty($new)}^(?!({foreach $categories AS $cat}{$cat.category_name|escape}|{/foreach})$).*{else}^(?!({foreach $categories AS $cat}{if $this_cat.category_name != $cat.category_name}{$cat.category_name|escape}{/if}|{/foreach})$).*{/if}" name="serendipity[cat][name]" type="text" value="{$this_cat.category_name|default:""|escape}" title="{$CONST.CATEGORY}">
            </div>

            <div class="form_field">
                <label for="category_description">{$CONST.DESCRIPTION}</label>
                <input id="category_description" name="serendipity[cat][description]" type="text" value="{$this_cat.category_description|default:""|escape}">
            </div>
        </div>

        <h3 class="toggle_headline">
            <button class="show_config_option icon_link{if NOT empty($newSub)} show_config_option_hide{/if}" type="button" data-href="#category_subcats" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.PARENT_CATEGORY}</button>
        </h3>

        <div id="category_subcats" class="clearfix additional_info">
            <div class="form_select">
                <label for="parent_cat">{$CONST.PARENT_CATEGORY}</label>
                <select id="parent_cat" name="serendipity[cat][parent_cat]">
                    <option value="0"{if $cid == 0} selected{/if}>{$CONST.NO_CATEGORY}</option>
{foreach $categories AS $cat}
{if $cat.categoryid == $cid}{continue}{/if}
                    <option value="{$cat.categoryid}"{if isset($this_cat.parentid) AND $this_cat.parentid == $cat.categoryid} selected{/if}>{for $i=1 to $cat.depth}&nbsp{/for} {$cat.category_name|escape}</option>
{/foreach}
                </select>
            </div>

            <fieldset class="clearfix inputs">
                <span class="wrap_legend"><legend>{$CONST.CATEGORY_HIDE_SUB} <a class="toggle_info button_link" href="#hide_subcats_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></a></legend></span>

                <span id="hide_subcats_info" class="field_info additional_info">{$CONST.CATEGORY_HIDE_SUB_DESC}</span>

                <div class="clearfix grouped">
                    <div class="form_radio">
                        <input id="hide_sub_yes" name="serendipity[cat][hide_sub]" type="radio" value="1"{if isset($this_cat.hide_sub) AND $this_cat.hide_sub == 1} checked="checked"{/if}>
                        <label for="hide_sub_yes">{$CONST.YES}</label>
                    </div>

                    <div class="form_radio">
                        <input id="hide_sub_no" name="serendipity[cat][hide_sub]" type="radio" value="0"{if isset($this_cat.hide_sub) AND $this_cat.hide_sub == 0} checked="checked"{/if}>
                        <label for="hide_sub_no">{$CONST.NO}</label>
                    </div>
                </div>
            </fieldset>
        </div>

        <h3 class="toggle_headline">
            <button class="show_config_option icon_link{if $closed} show_config_option_hide{/if}" type="button" data-href="#category_permissions" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.PERMISSIONS}</button>
        </h3>

        <div id="category_permissions" class="clearfix additional_info">
            <span class="wrap_legend"><legend>{$CONST.PERMISSIONS} <a class="toggle_info button_link" href="#acl_rw_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></a></legend></span>

            <span id="acl_rw_info" class="field_info additional_info">{$CONST.PERMISSION_READ_WRITE_ACL_DESC}</span>

            <div class="form_multiselect">
                <label for="read_authors">{$CONST.PERM_READ}</label>
                <select id="read_authors" size="6" multiple name="serendipity[cat][read_authors][]">
                    <option value="0"{if $selectAllReadAuthors} selected{/if}>{$CONST.ALL_AUTHORS}</option>
{foreach $groups AS $group}
                    <option value="{$group.confkey}"{if isset($read_groups.{$group.confkey})} selected{/if}>{$group.confvalue|escape}</option>
{/foreach}
                </select>
            </div>

            <div class="form_multiselect">
                <label for="write_authors">{$CONST.PERM_WRITE}</label>
                <select id="write_authors" size="6" multiple name="serendipity[cat][write_authors][]">
                    <option value="0"{if $selectAllWriteAuthors} selected{/if}>{$CONST.ALL_AUTHORS}</option>
{foreach $groups AS $group}
                    <option value="{$group.confkey}"{if isset($write_groups.{$group.confkey})} selected{/if}>{$group.confvalue|escape}</option>
{/foreach}
                </select>
            </div>
        </div>

        <h3 class="toggle_headline">
            <button class="show_config_option icon_link{if $closed} show_config_option_hide{/if}" type="button" data-href="#category_preview" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.CATEGORY} {$CONST.IMAGE}</button>
        </h3>

        <div id="category_preview" class="clearfix additional_info">
            <div class="form_field">
                <label for="category_icon">{$CONST.CATEGORY} {$CONST.IMAGE}</label>
                <input id="category_icon" class="change_preview" name="serendipity[cat][icon]" type="text" data-configitem="category_icon" value="{$this_cat.category_icon|default:""|escape}">
                <button class="choose_media" type="button" name="insImage" title="{$CONST.MEDIA_LIBRARY}"><span class="icon-picture" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_LIBRARY}</span></button>
            </div>
{if !empty($this_cat.category_icon)}
            <figure id="category_icon_preview">
                <figcaption>{$CONST.PREVIEW}</figcaption>
                <picture>
                  <source type="image/avif" srcset="{$this_cat.category_icon_avif|default:""|escape}">
                  <source type="image/webp" srcset="{$this_cat.category_icon_webp|default:""|escape}">
                  <img src="{$this_cat.category_icon|default:""|escape}" class="ml_preview_img" alt="">
                </picture>
            </figure>
{/if}
        </div>
        {$category_showForm|default:''}
        <a class="button_link" href="?serendipity[adminModule]=category&serendipity[adminAction]=view">{$CONST.BACK}</a>
        <input class="standalone" name="SAVE" type="submit" value="{$save}">
    </form>
{/if}
{if isset($view) AND $view}
<h2>{$CONST.CATEGORIES}</h2>

{if is_array($viewCats)}
<ul id="categories" class="option_list{if NOT $threadedCat} slist{/if}">
{if NOT empty($entriesnocat)}
    <li>
        <div class="clearfix x-odd">
            <div class="category_data">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left" viewBox="0 0 16 16">
                  <title>{$CONST.NO_CATEGORY}</title>
                  <path d="M10 12.796V3.204L4.519 8 10 12.796zm-.659.753-5.48-4.796a1 1 0 0 1 0-1.506l5.48-4.796A1 1 0 0 1 11 3.204v9.592a1 1 0 0 1-1.659.753z"/>
                </svg>
            </div>

            <ul class="plainList clearfix edit_actions">
                <li>{if $entriesnocat.0 > 0}<a class="button_link" href="?serendipity[adminModule]=entries&serendipity[adminAction]=editSelect&serendipity[filter][category]=nocat&serendipity[catref]=1" title="{$CONST.ENTRIES} {$CONST.NO_CATEGORY}"><span class="catctlabel">{$entriesnocat.0}{else}<span class="emptydim catctlabel">0{/if} {$CONST.ENTRIES}</span></a> <span class="catctlabel groupbyauthor">({$entriesbyauthor})</span></li>
            </ul>
        </div>
    </li><!-- close root item no cat -->
{/if}
{foreach $viewCategories AS $category}
{if NOT $category@first}
{if $category.depth > $priorDepth}

        <ul><!-- start a subcat of "{$category.category_name|escape}" as depth {$category.depth} -->
{/if}
{if $category.depth < $priorDepth}
    </li><!-- close last main list item -->
{for $i=$category.depth+1 to $priorDepth}
        </ul></li><!-- close the last opened subcat (indent) and its parent list item -->
{/for}
{/if}
{if $category.depth == $priorDepth}
    </li><!-- close single or last main category list item -->
{/if}
{/if}
{$priorDepth=$category.depth}
    <li>{if $category.depth == 0}<!-- open next main list item -->{/if}

        <div class="clearfix {cycle values="odd,even"}">
            <details class="category_data">
                <summary>{$category.category_name|escape}</summary>

                <div class="category_info clearfix">
{if $category.category_description != ''}
                    <span class="category_desc">{$category.category_description|escape}</span>
{/if}
                    (<span>{if $category.authorid == 0}{$CONST.ALL_AUTHORS}{else}{$category.realname|escape}{/if}</span>)
                </div>
            </details>

            <ul class="plainList clearfix edit_actions">
                <li><em>{if isset($catentries[{$category.categoryid}])}<a class="button_link" href="?serendipity[adminModule]=entries&serendipity[adminAction]=editSelect&serendipity[filter][category]={$category.categoryid}&serendipity[catref]=1" title="{$CONST.ENTRIES} {$CONST.CATEGORY} {$category.category_name|escape}"><span class="catctlabel">{$catentries[{$category.categoryid}]} {$CONST.ENTRIES}</span></a>{else}<span class="emptydim catctlabel">0 {$CONST.ENTRIES}</span>{/if}</em></li>
                <li><a class="button_link" href="?serendipity[adminModule]=category&amp;serendipity[adminAction]=edit&amp;serendipity[cid]={$category.categoryid}" title="{$CONST.EDIT}: #{$category.categoryid} - {$category.category_name|escape}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
                <li><a class="button_link" href="?serendipity[adminModule]=category&amp;serendipity[adminAction]=newSub&amp;serendipity[cid]={$category.categoryid}" title="{$CONST.CREATE_NEW_CAT}"><span class="icon-plus" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.CREATE_NEW_CAT}</span></a></li>
                <li><a class="button_link" href="?serendipity[adminModule]=category&amp;serendipity[adminAction]=delete&amp;serendipity[cid]={$category.categoryid}" title="{$CONST.DELETE} {$category.category_name|escape}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a></li>
            </ul>
        </div>
{/foreach}

    </li>
{for $i=1 to $priorDepth}

    </ul></li><!-- close the real last subcat(s) (indent) and its parent list item -->
{/for}
</ul>
{else}

<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_CATEGORIES}</span>
{/if}

<a class="button_link" href="?serendipity[adminModule]=category&serendipity[adminAction]=new">{$CONST.CREATE_NEW_CAT}</a>
{/if}
