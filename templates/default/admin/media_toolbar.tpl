<div class="has_toolbar">
{if $media.standardpane}

    <h2>{$CONST.MEDIA_LIBRARY}</h2>

    <script>$(document).ready(function() { var smcol = Cookies.get('serendipity[media_grid]'); if (smcol != 'undefined') { serendipity.changeMediaGrid(smcol) } });</script>
    <div id="grid-selector" class="media-grid-selector{if $media.nr_files < 3} poor{/if}">
        <div id="col-def-selector" class="mediaGrid" title="2-column grid" onclick="serendipity.changeMediaGrid('mlDefCol')">
          <div class="mediaGrid-cell tic"></div>
          <div class="mediaGrid-cell tac"></div>
        </div>
        <div id="col-mid-selector" class="mediaGrid" title="3-column grid" onclick="serendipity.changeMediaGrid('mlMidCol')">
          <div class="mediaGrid-cell tac"></div>
          <div class="mediaGrid-cell tic"></div>
          <div class="mediaGrid-cell tac"></div>
        </div>
        <div id="col-max-selector" class="mediaGrid" title="4-column grid" onclick="serendipity.changeMediaGrid('mlMaxCol')">
          <div class="mediaGrid-cell tic"></div>
          <div class="mediaGrid-cell tac"></div>
          <div class="mediaGrid-cell tic"></div>
          <div class="mediaGrid-cell tac"></div>
        </div>
    </div>
{else}{* GALLERY ITEM SELECTION *}

    <h2>{$CONST.MEDIA_LIBRARY} [galleries]</h2>
{/if}

    <form id="media_library_control" method="get" action="?">
        {$media.token}
        {if empty($media.form_hidden)}

        <input type="hidden" name="serendipity[adminModule]" value="media">
        <input type="hidden" name="serendipity[action]" value="">
        <input type="hidden" name="serendipity[adminAction]" value="">
        <input type="hidden" name="serendipity[only_path]" value="{$media.only_path}">
        {else}{$media.form_hidden}{/if}

        <ul class="filters_toolbar clearfix plainList">
            {if $media.standardpane}
            <li><a class="button_link" href="#media_pane_filter" title="Show filters"><span class="icon-filter" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FILTERS}</span></a></li>
            <li><a class="button_link" href="#media_pane_sort" title="{$CONST.SORT_ORDER}"><span class="icon-sort" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SORT_ORDER}</span></a></li>
            {/if}
            <li id="media_filter_path">
                <div class="form_select">
                    <label for="serendipity_only_path" class="visuallyhidden">{$CONST.FILTER_DIRECTORY}</label>
                    <select id="serendipity_only_path" name="serendipity[only_path]">
                        <option value="">{if NOT $media.limit_path}{if isset($media.toggle_dir) AND $media.toggle_dir == 'yes' OR $media.hideSubdirFiles == 'yes'}{$CONST.BASE_DIRECTORY}{else}{$CONST.ALL_DIRECTORIES}{/if}{else}{$media.blimit_path}{/if}</option>
                    {foreach $media.paths AS $folderHead}

                        <option{if ($media.only_path == $media.limit_path|cat:$folderHead.relpath)} selected{/if} value="{$folderHead.relpath}">{'&nbsp;'|str_repeat:($folderHead.depth*2)}{$folderHead.name}</option>{* * *}
                    {/foreach}
                    </select>

                    <input name="go" type="submit" value="{$CONST.GO}">
                    {if NOT $media.standardpane}
                    <button class="toggle_info button_link" type="button" data-href="#media_gallery_selection"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden">Gallery item selection</span></button>
                    <div id="media_gallery_selection" class="clearfix additional_info media_gallery_selection">
                        <span class="msg_hint focused">{$CONST.MEDIA_GALLERY_SELECTION}</span>
                    </div>
                    {/if}
                </div>
            </li>
            {if $media.standardpane}
            <li id="media_selector_bar">
                <fieldset>
                    <input id="serendipity[filter][fileCategory][All]" type="radio" name="serendipity[filter][fileCategory]"{if isset($media.filter.fileCategory) AND $media.filter.fileCategory == ""} checked{/if} value="all">
                    <label for="serendipity[filter][fileCategory][All]" class="media_selector button_link">{$CONST.COMMENTS_FILTER_ALL}</label>
                    <input id="serendipity[filter][fileCategory][Image]" type="radio" name="serendipity[filter][fileCategory]"{if isset($media.filter.fileCategory) AND $media.filter.fileCategory == "image"} checked{/if} value="image">
                    <label for="serendipity[filter][fileCategory][Image]" class="media_selector button_link">{$CONST.IMAGE}</label>
                    <input id="serendipity[filter][fileCategory][Video]" type="radio" name="serendipity[filter][fileCategory]"{if isset($media.filter.fileCategory) AND $media.filter.fileCategory == "video"} checked{/if} value="video">
                    <label for="serendipity[filter][fileCategory][Video]" class="media_selector button_link">{$CONST.VIDEO}</label>
                </fieldset>
            </li>
            {/if}
        {if isset($smarty.get.serendipity.showUpload) AND $smarty.get.serendipity.showUpload}
            <li class="popuplayer_showUpload"><a class="button_link" href="?serendipity[adminModule]=media&amp;serendipity[adminAction]=addSelect&amp;{$media.extraParems}">{$CONST.ADD_MEDIA}</a></li>
        {/if}
        </ul>

    {if $media.standardpane}
        <fieldset id="media_pane_filter" class="additional_info filter_pane">
            <legend class="visuallyhidden">{$CONST.FILTERS}</legend>
{* Keep in mind that $media.sort_order is different than $media.sortorder! The first is for building the key names; the second is the value that was set by POST! *}
            <div id="media_filter" class="clearfix">
            {foreach $media.sort_order AS $filtername => $filter}

                <div class="{cycle values="left,center,right"}{if $filter@iteration > 6} bp_filters{/if}">
                {if isset($filter.type) AND ($filter.type == 'date' OR $filter.type == 'intrange')}

                    <fieldset>
                        <span class="wrap_legend"><legend>{$filter.desc}</legend></span>
                {else}

                    <div class="form_{if isset($filter.type) AND $filter.type == 'authors'}select{else}field{/if}">
                        <label for="serendipity_filter_{$filter@key}">{$filter.desc}</label>
                {/if}
                {if isset($filter.type) AND $filter.type == 'date'}

                        <div class="form_field">
                            <label for="serendipity_filter_{$filter@key}_from" class="range-label hidden">{$CONST.RANGE_FROM|lower}</label>
                            <input id="serendipity_filter_{$filter@key}_from" name="serendipity[filter][{$filter@key}][from]" type="date" placeholder="2001-01-31" value="{$media.filter[$filter@key].from|escape|default:''}">
                            <label for="serendipity_filter_{$filter@key}_to" class="range-label"><span class="hidden">{$CONST.RANGE_TO|lower}</span><span class="icon-right-dir" title="{$CONST.RANGE_FROM|lower} - {$CONST.RANGE_TO|lower}"></span></label>
                            <input id="serendipity_filter_{$filter@key}_to" name="serendipity[filter][{$filter@key}][to]" type="date" placeholder="2005-12-31" value="{$media.filter[$filter@key].to|escape|default:''}">
                        </div>
                {elseif isset($filter.type) AND $filter.type == 'intrange'}

                        <div class="form_field">
                            <label for="serendipity_filter_{$filter@key}_from" class="range-label">{$CONST.RANGE_FROM|lower}</label>
                            <input id="serendipity_filter_{$filter@key}_from" name="serendipity[filter][{$filter@key}][from]" type="text" placeholder="{if $filtername == 'bp.RUN_LENGTH'}in{/if}" value="{$media.filter[$filter@key].from|escape|default:''}">
                            <label for="serendipity_filter_{$filter@key}_to" class="range-label">{$CONST.RANGE_TO|lower}</label>
                            <input id="serendipity_filter_{$filter@key}_to" name="serendipity[filter][{$filter@key}][to]" type="text" placeholder="{if $filtername == 'bp.RUN_LENGTH'}seconds{/if}" value="{$media.filter[$filter@key].to|escape|default:''}">
                        </div>
                {elseif isset($filter.type) AND $filter.type == 'authors'}

                        <select id="serendipity_filter_{$filter@key}" name="serendipity[filter][{$filter@key}]">
                            <option value="">{$CONST.ALL_AUTHORS}</option>
                            {foreach $media.authors AS $media_author}

                            <option value="{$media_author.authorid}"{if isset($media.filter[$filter@key]) AND $media.filter[$filter@key] == $media_author.authorid} selected{/if}>{$media_author.realname|escape}</option>
                            {/foreach}

                        </select>
                {else}{* this is of type string w/o being named *}
                        {* label is already set on loop start, when type is not date or intrange *}
                        <input id="serendipity_filter_{$filter@key}" name="serendipity[filter][{$filter@key}]" type="text" value="{$media.filter[$filter@key]|escape|default:''}">
                {/if}
                {if isset($filter.type) AND ($filter.type == 'date' OR $filter.type == 'intrange')}

                    </fieldset>
                {else}

                    </div>
                {/if}

                </div>
                {if $filter@last AND !$media.simpleFilters}

                <div class="right bp_filters">
                    <div class="form_field">
                        <label class="visuallyhidden">NOTE</label>
                        <div class="bp_note">
                            <span class="icon-info-circled" aria-hidden="true"></span> mediaproperties metadata fields
                        </div>
                    </div>
                </div>
                {/if}
            {/foreach}

                <div id="media_filter_keywords" class="form_field {if $media.simpleFilters}right{else}center{/if}">
                    <label for="keyword_input">{$CONST.MEDIA_KEYWORDS}</label>
                    <input id="keyword_input" name="serendipity[keywords]" type="text" value="{$media.keywords_selected|escape}">
                </div>

                <div id="keyword_list" class="clearfix {if $media.simpleFilters}keywords {/if}right">
                {foreach $media.keywords AS $keyword}

                    <a class="add_keyword" href="#keyword-input" data-keyword="{$keyword|escape}" title="{$keyword|escape}">{$keyword|escape|truncate:20:"&hellip;"}</a>
                {/foreach}

                </div>
            </div>{* media filter end *}
        </fieldset>

        <fieldset id="media_pane_sort" class="additional_info filter_pane">
            <legend class="visuallyhidden">{$CONST.SORT_ORDER}</legend>
            <div class="clearfix grouped">
                <div class="form_select">
                    <label for="serendipity_sortorder_order">{$CONST.SORT_BY}</label>
                    {* Keep in mind that $media.sort_order is different than $media.sortorder! *}
                    <select id="serendipity_sortorder_order" name="serendipity[sortorder][order]">
                    {foreach $media.sort_order AS $orderVal}
                        {* The first is for building the key names *}
                        <option value="{$orderVal@key}"{if $media.sortorder.order == $orderVal@key} selected{/if}>{$orderVal.desc}</option>
                    {/foreach}

                    </select>
                </div>

                <div class="form_select">
                    <label for="serendipity_sortorder_ordermode">{$CONST.SORT_ORDER}</label>
                    {* The second is the value that was set by POST or COOKIE! *}
                    <select id="serendipity_sortorder_ordermode" name="serendipity[sortorder][ordermode]">
                        <option value="DESC"{if $media.sortorder.ordermode == 'DESC'} selected{/if}>{$CONST.SORT_ORDER_DESC}</option>
                        <option value="ASC"{if $media.sortorder.ordermode == 'ASC'} selected{/if}>{$CONST.SORT_ORDER_ASC}</option>
                    </select>
                </div>

                <div class="form_select">
                    <label for="serendipity_sortorder_perpage">{$CONST.FILES_PER_PAGE}</label>

                    <select id="serendipity_sortorder_perpage" name="serendipity[sortorder][perpage]">
                    {foreach $media.sort_row_interval AS $perPageVal}

                        <option value="{$perPageVal}"{if $media.perPage == $perPageVal} selected{/if}>{$perPageVal}</option>
                    {/foreach}

                    </select>
                </div>
                {if !$media.simpleFilters}

                <div class="form_field">
                    <div class="clearfix">
                        <div class="form_radio">
                            <input id="radio_link_no" name="serendipity[hideSubdirFiles]" type="radio" value="no"{if $media.hideSubdirFiles == 'no'} checked="checked"{/if}>
                            <label for="radio_link_no">{$CONST.NO}</label>
                        </div>

                        <div class="form_radio">
                            <input id="radio_link_yes" name="serendipity[hideSubdirFiles]" type="radio" value="yes"{if $media.hideSubdirFiles == 'yes'} checked="checked"{/if}>
                            <label for="radio_link_yes">{$CONST.YES}</label>
                        </div>
                        <div class="hideSubDirLabel">{$CONST.HIDE_SUBDIR_FILES}</div>
                    </div>
                </div>
                {/if}

            </div>
            <div class="form_buttons">
                <input name="go" type="submit" value="{$CONST.GO}">
                <input class="reset_media_filters state_cancel" name="media_filters_reset" title="{$CONST.RESET_FILTERS}" type="submit" value="Reset">
            </div>
        </fieldset>
        <script>
            $(document).ready(function() {
                // write: is plain "foo", read: is "serendipity[foo]"!
            {foreach $media.sortParams AS $sortParam}

                serendipity.SetCookie("sortorder_{$sortParam}", "{$media.sortorder.{$sortParam}}");
            {/foreach}
            {if isset($filterParams)}
            {foreach $media.filterParams AS $filterParam}

                serendipity.SetCookie("{$filterParam}", "{$media.{$filterParam}}");
            {/foreach}
            {/if}

                serendipity.SetCookie("only_path", "{$media.only_path}");

                serendipity.SetCookie("hideSubdirFiles", "{$media.hideSubdirFiles}");
            {foreach $media.filter AS $k => $v}
                {if !is_array($media.filter[{$k}])}

                serendipity.SetCookie("[filter][{$k}]", "{$media.filter[{$k}]}");
                {else}
                    {foreach $media.filter[{$k}] AS $key => $val}

                serendipity.SetCookie("[filter][{$k}][{$key}]", "{$media.filter[{$k}][{$key}]}");
                    {/foreach}
                {/if}
            {/foreach}

                $('#media_pane_filter').find('.reset_media_filters').addClass('reset_filter');
                $('#media_pane_sort').find('.reset_media_filters').addClass('reset_sort');

                $('.reset_filter').click(function() {
                    $('#media_filter').find('input[type=text], input[type=date]').each(function() {
                        $(this).attr('value', '');
                    });
                });
                $('.reset_sort').click(function() {
                    $("#serendipity_sortorder_order option:selected").prop('selected', false);
                    $("#serendipity_sortorder_order option[value='i.date']").prop('selected', true);
                    $("#serendipity_sortorder_perpage option:selected").prop('selected', false);
                    $("#serendipity_sortorder_perpage option[value='8']").prop('selected', true);
                });
            });
        </script>
    {/if}
    </form>
</div>{* has toolbar end *}
