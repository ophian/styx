{if $drawList}
<div class="has_toolbar">
    <h2>{$CONST.EDIT_ENTRIES}{if (NOT empty($smarty.get.serendipity.filter.author) OR NOT empty($smarty.get.serendipity.filter.category) OR NOT empty($smarty.get.serendipity.filter.body) OR (isset($smarty.get.serendipity.filter.isdraft) AND $smarty.get.serendipity.filter.isdraft != 'all')) AND empty($smarty.get.entry_filters_reset)} <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-filter-circle-fill" fill="#3e5f81" xmlns="http://www.w3.org/2000/svg"><title id="title">{$CONST.FILTERS}</title><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM3.5 5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zM5 8.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm2 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z"/></svg>{/if}</h2>

    <form action="?" method="get">
        <input name="serendipity[action]" type="hidden" value="admin">
        <input name="serendipity[adminModule]" type="hidden" value="entries">
        <input name="serendipity[adminAction]" type="hidden" value="editSelect">

        <ul class="filters_toolbar filter_entries plainList">
            <li><a class="button_link" href="#filter_entries" title="{$CONST.FILTERS}"><span class="icon-filter" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FILTERS}</span></a></li>
            <li><a class="button_link" href="#sort_entries" title="{$CONST.SORT_ORDER}"><span class="icon-sort" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SORT_ORDER}</span></a></li>
            {if NOT $simpleFilters}
                <li><a class="button_link" href="#entry_skip" title="{$CONST.EDIT_ENTRY} #"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT_ENTRY} #</span></a></li>
            {/if}
        </ul>

        <fieldset id="filter_entries" class="additional_info filter_pane">
            <legend class="visuallyhidden">{$CONST.FILTERS}</legend>

            <div class="clearfix">
                <div class="form_select">
                    <label for="filter_author">{$CONST.AUTHOR}</label>
                    <select id="filter_author" name="serendipity[filter][author]">
                        <option value="">-</option>
                {if is_array($users)}
                    {foreach $users AS $user}
                        {if isset($user.artcount) AND $user.artcount < 1}{continue}{/if}

                        <option value="{$user.authorid}"{(isset($get.filter.author) AND $get.filter.author == $user.authorid) ? ' selected' : ''}>{$user.realname|escape}</option>
                    {/foreach}
                {/if}

                    </select>
                </div>

                <div class="form_select">
                    <label for="filter_draft">{$CONST.ENTRY_STATUS}</label>
                    <select id="filter_draft" name="serendipity[filter][isdraft]">
                        <option value="all">{$CONST.COMMENTS_FILTER_ALL}</option>
                        <option value="draft"{(isset($get.filter.isdraft) AND ($get.filter.isdraft == 'draft') ? ' selected' : '')}>{$CONST.DRAFT}</option>
                        <option value="publish"{(isset($get.filter.isdraft) AND ($get.filter.isdraft == 'publish') ? ' selected' : '')}>{$CONST.PUBLISH}</option>
                    </select>
                </div>

                <div class="form_select">
                    <label for="filter_category">{$CONST.CATEGORY}</label>
                    <select id="filter_category" name="serendipity[filter][category]">
                        <option value="">-</option>
                        <option value="nocat">{$CONST.NO_CATEGORY}</option>
                    {foreach $categories AS $cat}
                        <option value="{$cat.categoryid}"{(isset($get.filter.category) AND $get.filter.category == $cat.categoryid) ? ' selected' : ''}>{'&nbsp;'|str_repeat:$cat.depth} {$cat.category_name|escape}</option>
                    {/foreach}
                    </select>
                </div>

                <div class="form_field">
                    <label for="filter_content">{$CONST.CONTENT}</label>
                    <input id="filter_content" name="serendipity[filter][body]" type="text" value="{if NOT empty($get.filter)}{$get.filter.body|escape|default:''}{/if}">
                </div>
            </div>
        </fieldset>

        <fieldset id="sort_entries" class="additional_info filter_pane">
            <legend class="visuallyhidden">{$CONST.SORT_ORDER}</legend>

            <div class="clearfix">
                <div class="form_select">
                    <label for="sort_order">{$CONST.SORT_BY}</label>
                    <select id="sort_order" name="serendipity[sort][order]">
                    {foreach $sort_order AS $so_key => $so_val}
                        <option value="{$so_key}"{(isset($get.sort.order) AND ($get.sort.order == $so_key) ? ' selected': '')}>{$so_val}</option>
                    {/foreach}
                    </select>
                </div>

                <div class="form_select">
                    <label for="sort_ordermode">{$CONST.SORT_ORDER}</label>
                    <select id="sort_ordermode" name="serendipity[sort][ordermode]">
                        <option value="DESC"{(isset($get.sort.ordermode) AND ($get.sort.ordermode == 'DESC') ? ' selected' : '')}>{$CONST.SORT_ORDER_DESC}</option>
                        <option value="ASC"{(isset($get.sort.ordermode) AND ($get.sort.ordermode == 'ASC') ? ' selected' : '')}>{$CONST.SORT_ORDER_ASC}</option>
                    </select>
                </div>

                <div class="form_select">
                    <label for="sort_perpage">{$CONST.ENTRIES_PER_PAGE}</label>
                    <select id="sort_perpage" name="serendipity[sort][perPage]">
                    {foreach $per_page AS $per_page_nr}
                        <option value="{$per_page_nr}"{((isset($get.sort.perPage) AND ($get.sort.perPage == $per_page_nr)) ? ' selected' : '')}> {$per_page_nr}</option>
                    {/foreach}
                    </select>
                </div>
            </div>

            <div class="form_buttons">
                <input name="go" type="submit" value="{$CONST.GO}"> <input class="reset_entry_filters state_cancel" name="entry_filters_reset" title="{$CONST.RESET_FILTERS}" type="submit" value="Reset">
            </div>
        </fieldset>
{if NOT $simpleFilters}

        <div id="entry_skip" class="clearfix additional_info filter_pane">
            <div class="form_field">
                <label for="skipto_entry">{$CONST.EDIT_ENTRY} #</label>
                <input id="skipto_entry" name="serendipity[id]" type="text" size="3">
                <input name="serendipity[editSubmit]" type="submit" value="{$CONST.GO}">
            </div>
        </div>
{/if}

    </form>

    <script>
        $(document).ready(function() {
        {if isset($filter_import) AND is_array($filter_import)}
        {foreach $filter_import AS $f_import}
            {if $f_import == 'isdraft' AND isset($smarty.get.dashboard.filter.noset)}{continue}{/if}
            {if NOT empty($get_filter_{$f_import})}
            serendipity.SetCookie("entrylist_filter_{$f_import}", "{$get_filter_{$f_import}}");
            {/if}
        {/foreach}
        {/if}
        {if isset($sort_import) AND is_array($sort_import)}
        {foreach $sort_import AS $s_import}
            {if NOT empty($get_sort_{$s_import})}
            serendipity.SetCookie("entrylist_sort_{$s_import}", "{$get_sort_{$s_import}}");
            {/if}
        {/foreach}
        {/if}

            $('#filter_entries').find('.reset_entry_filters').addClass('reset_filter');
            $('#sort_entries').find('.reset_entry_filters').addClass('reset_sort');

            $('.reset_filter').click(function() {
                $('#filter_author option:selected').prop('selected', false);
                $('#filter_draft option:selected').prop('selected', false);
                $('#filter_category option:selected').prop('selected', false);
                $('#filter_content').attr('value', '');
            });
            $('.reset_sort').click(function() {
                $('#sort_order option:selected').prop('selected', false);
                $('#sort_ordermode option:selected').prop('selected', false);
                $('#sort_perpage option:selected').prop('selected', false);
            });
        });
    </script>

</div>{* has toolbar end *}

    {if isset($is_entries) AND $is_entries}
    {if NOT $simpleFilters}

    <form id="formMultiSelect" name="formMultiSelect" action="?" method="post">
        {$formtoken}
        <input name="serendipity[action]" type="hidden" value="admin">
        <input name="serendipity[adminModule]" type="hidden" value="entries">
        <input name="serendipity[adminAction]" type="hidden" value="multidelete">
    {/if}

        <div class="entries_pane">
        {if isset($entries) AND is_array($entries)}

            <ul id="entries_list" class="plainList zebra_list">
            {foreach $entries AS $entry}
                {if ($entry@index >= $perPage)}{continue}{/if}

                <li id="entry_{$entry.id}" class="clearfix {cycle values="odd,even"}">
                    {if NOT $simpleFilters}

                        <div class="form_check">
                            <input id="multidelete_entry{$entry.id}" class="multicheck" name="serendipity[multiDelete][]" type="checkbox" value="{$entry.id}" data-multixid="entry_{$entry.id}"><label for="multidelete_entry{$entry.id}" class="visuallyhidden">{$CONST.TOGGLE_SELECT} (#{$entry_id})</label>
                        </div>
                    {/if}

                    <h3><a href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$entry.id}" title="#{$entry.id}: {$entry.title|escape:'html':$CONST.LANG_CHARSET:false}">{$entry.title|escape:'html':$CONST.LANG_CHARSET:false}</a></h3>

                    <ul class="plainList clearfix actions">
                    {if $entry.preview OR (!$showFutureEntries AND ($entry.timestamp >= $serverOffsetHour))}

                        <li><a class="button_link" href="{$entry.preview_link}" title="{$CONST.PREVIEW} #{$entry.id}"><span class="icon-search" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIEW}</span></a></li>
                    {else}

                        <li><a class="button_link" href="{$entry.archive_link}" title="{$CONST.VIEW} #{$entry.id}"><span class="icon-search" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.VIEW}</span></a></li>
                    {/if}

                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=edit&amp;serendipity[id]={$entry.id}" title="{$CONST.EDIT} #{$entry.id}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=delete&amp;serendipity[id]={$entry.id}&amp;{$urltoken}" title="{$CONST.DELETE} #{$entry.id}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a></li>
                    {if $entry.ep_is_sticky}

                        <li><a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=entries&amp;serendipity[adminAction]=editSelect&amp;serendipity[id]={$entry.id}&amp;serendipity[timestamp]={$entry.timestamp}&amp;serendipity[preview]=false&amp;{$urltoken}&amp;serendipity[properties][is_sticky]=false" title="{$CONST.RESET_STATUS}: {$CONST.PLUGIN_EVENT_ENTRYPROPERTIES_STICKYPOSTS}"><span class="icon-off" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.RESET_STATUS}</span></a></li>
                    {/if}

                    </ul>

                    <div class="entry_info clearfix">
                        <span class="status_timestamp">
                            {$entry.timestamp|formatTime:"{$CONST.DATE_FORMAT_SHORT}"}{if $entry.timestamp <= ($entry.last_modified - 1800)} <span class="icon-info-circled" aria-hidden="true" title="{$CONST.LAST_UPDATED}: {$entry.last_modified|formatTime:"{$CONST.DATE_FORMAT_SHORT}"}"></span><span class="visuallyhidden"> {$CONST.LAST_UPDATED}</span>{/if}

                        </span>
                        <span class="entry_meta"><span class="icon-edit" aria-hidden="true" title="{$CONST.POSTED_BY}"></span>: {$entry.author|escape}
                        {if count($entry.cats)} {$CONST.IN}
                          {foreach $entry.cats AS $cat}

                            <a href="{$cat.link}">{$cat.category_name|escape}</a>{if NOT empty($cat.grouped)}{foreach $cat.groupname AS $gshortname} <span class="icon-users {$gshortname}" aria-hidden="true" title="read and view restriction to group {$gshortname}"></span>{/foreach}{/if}{if (count($entry.cats) > 1) AND !$cat@last}, {/if}
                          {/foreach}
                        {/if}</span>
                    {if !$showFutureEntries AND ($entry.timestamp >= $serverOffsetHour)}

                        <span class="entry_status status_future">{$CONST.SCHEDULED}</span>
                    {/if}
                    {if $entry.ep_is_sticky}

                        <span class="entry_status status_sticky">{$CONST.STICKY_POSTINGS}</span>
                    {/if}
                    {if $entry.isdraft}

                        <span class="entry_status status_draft">{$CONST.DRAFT}</span>
                    {/if}
                    {if (isset($smarty.get.go) AND (NOT empty($smarty.get.serendipity.filter.body) OR (isset($smarty.post.serendipity.editSubmit) AND NOT empty($smarty.post.serendipity.id)))) OR isset($entry.is_pinned)}

                        <span class="entry_status status_pin"><input class="pinpoint" name="serendipity[entryQuickPin][]" type="checkbox" value="{$entry.id}"{assign "pinstr" "entrylist_pin_entry_{$entry.id}"}{if isset($smarty.cookies.serendipity.$pinstr)} checked="checked"{/if} onClick="serendipity.PinFilter({$entry.id})"> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-pin-angle-fill" viewBox="0 0 16 16"><title id="title">{$CONST.ENTRY_QUICKPIN}</title><path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/></svg></span>
                        <script> serendipity.GetPinExpireTime({$entry.id}) </script>
                    {else}
                        {if NOT empty($entry.info_more)}{$entry.info_more}{/if}{* Plugin eventData, ie. vgwort; No need in filter cases. *}
                    {/if}
                    {if isset($entry.lang) AND $entry.lang != 'all'}

                        <span class="entry_status status_lang"><span class="icon-plus" aria-hidden="true"></span> {$CONST.INSTALL_LANG}: [ {$entry.lang} ]</span>
                    {/if}

                    </div>
                </li>
            {/foreach}

            </ul>
        {/if}{* isset entries end *}
        {if ($offSet > 0) OR ($count > $perPage)}
            {math assign=totalPages equation="ceil(values/parts)" values=$totalEntries parts=$perPage}
            <nav class="pagination">
                <h3>{$CONST.PAGE_BROWSE_ENTRIES|sprintf:($page+1):$totalPages:$totalEntries}</h3>

                <ul class="clearfix">
                    <li class="first">{if $page > 0}<a class="button_link" href="{$linkFirst}" title="{$CONST.FIRST_PAGE}"><span class="visuallyhidden">{$CONST.FIRST_PAGE} </span><span class="icon-to-start" aria-hidden="true"></span></a>{/if}</li>
                    <li class="prev">{if $offSet > 0}<a class="button_link" href="{$linkPrevious}" title="{$CONST.PREVIOUS}"><span class="icon-left-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIOUS}</span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                    {* Looks weird, but .last will be placed to end by the CSS float:right *}
                    <li class="last">{if ($page+1) < $totalPages}<a class="button_link" href="{$linkLast}{$totalPages-1}" title="{$CONST.LAST_PAGE}"><span class="visuallyhidden">{$CONST.LAST_PAGE} </span><span class="icon-to-end" aria-hidden="true"></span></a>{/if}</li>
                    <li class="next">{if $count > $perPage}<a class="button_link" href="{$linkNext}" title="{$CONST.NEXT}"><span class="visuallyhidden">{$CONST.NEXT} </span><span class="icon-right-dir" aria-hidden="true"></span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                </ul>
            </nav>
        {/if}

        </div>
    {/if}
    {if NOT $simpleFilters AND isset($entries) AND is_array($entries)}

        <div id="multidelete_tools" class="form_buttons">
            {if isset($smarty.get.serendipity.catref)}<a class="button_link" href="?serendipity[adminModule]=category&serendipity[adminAction]=view">{$CONST.BACK}</a>{/if}

            <input class="invert_selection" name="toggle" type="button" value="{$CONST.INVERT_SELECTIONS}">
            <input class="state_cancel" name="toggle" type="submit" value="{$CONST.DELETE}">
        </div>
    </form>
    {/if}
{/if}
{if isset($no_entries)}
    {if empty($drawList)}<h2>{$CONST.FIND_ENTRIES}</h2>{/if}

    <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</span>
{/if}

{if $switched_output}
    {if isset($get.adminAction) AND $get.adminAction == 'save' AND $dateval}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.DATE_INVALID}</span>
    {/if}
    {if isset($get.adminAction) AND $get.adminAction == 'save' AND $single_error}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PUBLISH_ERROR} {$is_empty}</span>
    {/if}
    {if isset($get.adminAction) AND $get.adminAction == 'save' AND $use_legacy}
        {if $is_draft AND ! $errors}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.IFRAME_SAVE_DRAFT}</span>
        {/if}
        {if $is_iframe === true}
        {if isset($smarty.post.serendipity.properties.lang_selected)}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.PLUGIN_EVENT_MULTILINGUAL_ENTRY_RELOADED|sprintf:{('' == $smarty.post.serendipity.properties.lang_selected) ? $lang : $smarty.post.serendipity.properties.lang_selected}}</span>
        {else}
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.IFRAME_SAVE}</span>
        {/if}
        {/if}
        {if isset($is_iframepreview) AND $is_iframepreview}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.IFRAME_PREVIEW}</span>
        {/if}
    {/if}
    {if $is_doDelete OR $is_doMultiDelete}
        {foreach $del_entry AS $delent}
        <span class="msg_hint"><span class="icon-help-circled" aria-hidden="true"></span> {$delent}</span>
        {/foreach}
    {/if}
    {if $is_delete OR $is_multidelete}
        {foreach $rip_entry AS $ripent}
        <span class="msg_hint"><span class="icon-help-circled" aria-hidden="true"></span> {$ripent}</span>
        {/foreach}
        <div class="form_buttons">
            <a class="button_link state_cancel icon_link" href="{$smarty.server.HTTP_REFERER|escape}">{$CONST.NOT_REALLY}</a>
            <a class="button_link state_submit icon_link" href="{$newLoc}">{$CONST.DUMP_IT}</a>
        </div>
    {/if}
{/if}
{if $iframe !== true}{$iframe}{/if}
{$entryForm}