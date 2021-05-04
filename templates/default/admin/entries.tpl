<h2>{if isset($entry_vars.entry.title)}{$CONST.EDIT_ENTRY}{else}{$CONST.NEW_ENTRY}{/if}</h2>
{if $entry_vars.errMsg}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$entry_vars.errMsg}</span>
{/if}
<form id="serendipityEntry" name="serendipityEntry"{if isset($entry_vars.entry.entry_form)} {$entry_vars.entry.entry_form}{/if} action="{$entry_vars.targetURL}" method="post">
{foreach $entry_vars.hiddens AS $key => $value}{if $key == 'serendipity[timestamp]' AND $entry_vars.timestamp == $value}{* avoid possible doublet *}{else}
    <input type="hidden" name="{$key}" value="{$value}">
{/if}{/foreach}
    <input type="hidden" name="serendipity[id]" value="{if isset($entry_vars.entry.id)}{$entry_vars.entry.id|escape|default:''}{/if}">
    <input type="hidden" name="serendipity[timestamp]" value="{$entry_vars.timestamp|escape}">
    <input type="hidden" name="serendipity[preview]" value="false">
    {$entry_vars.formToken}
    <div id="edit_entry_title" class="form_field">
        <label for="entryTitle">{$CONST.TITLE}</label>
        <input id="entryTitle" name="serendipity[title]" type="text" value="{if isset($entry_vars.entry.title)}{$entry_vars.entry.title|escape|default:''}{/if}">
    </div>

    <div id="cats_list" class="clearfix taxonomy">
        <h3>{$CONST.CATEGORIES}</h3>

        <ul class="plainList"></ul>
    </div>

    {if class_exists('serendipity_event_freetag')}
    <div id="tags_list" class="clearfix taxonomy">
        <h3>{$CONST.EDITOR_TAGS}</h3>

        <ul class="plainList"></ul>
    </div>
    {/if}
    {if !isset($entry_data.entry)}{$entry_data.entry = ''}{/if}

    <div class="form_area">
        <label for="serendipity[body]">{$CONST.ENTRY_BODY}</label>
    {if NOT $entry_vars.wysiwyg}
        <div id="tools_entry" class="editor_toolbar">
        {if isset($iso2br) AND $iso2br}
            <button class="wrap_selection lang-html" type="button" name="insX" data-tag-open="nl" data-tag-close="nl" data-tarea="serendipity[body]">noBR</button>
        {/if}
            <button class="hilite_i wrap_selection lang-html" type="button" name="insI" data-tag-open="em" data-tag-close="em" data-tarea="serendipity[body]">i</button>
            <button class="hilite_b wrap_selection lang-html" type="button" name="insB" data-tag-open="strong" data-tag-close="strong" data-tarea="serendipity[body]">b</button>
            <button class="hilite_u wrap_selection lang-html" type="button" name="insU" data-tag-open="u" data-tag-close="u" data-tarea="serendipity[body]">u</button>
            <button class="wrap_selection lang-html" type="button" name="insQ" data-tag-open="blockquote" data-tag-close="blockquote" data-tarea="serendipity[body]">{$CONST.QUOTE}</button>
            <button class="wrap_insimg" type="button" name="insJ" data-tarea="serendipity[body]">img</button>
            <button class="wrap_insgal" type="button" name="insG" title="Media Gallery" data-tarea="serendipity[body]"><span class="icon-gallery" aria-hidden="true"></span><span class="visuallyhidden"> Media Gallery</span></button>
            <button class="wrap_insmedia" type="button" name="insImage" title="{$CONST.MEDIA_LIBRARY}" data-tarea="serendipity[body]"><span class="icon-s9yml" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_LIBRARY}</span></button>
            <button class="wrap_insurl" type="button" name="insURL" data-tarea="serendipity[body]">URL</button>
            {serendipity_hookPlugin hook="backend_entry_toolbar_body" data=$entry_data.entry|default:'' hookAll="true"}
        </div>
    {else}
        <div id="tools_entry" class="editor_toolbar">
            {serendipity_hookPlugin hook="backend_entry_toolbar_body" data=$entry_data.entry|default:'' hookAll="true"}
        </div>
    {/if}
        <div id="teaser_entry_editor">
            <textarea id="serendipity[body]" name="serendipity[body]" rows="15">{if isset($entry_vars.entry.body)}{$entry_vars.entry.body|escape|default:''}{/if}</textarea>
        </div>
    </div>

     <div class="form_area">
        <label for="serendipity[extended]">{$CONST.EXTENDED_BODY}</label>
    {if NOT $entry_vars.wysiwyg}
        <div id="tools_extended" class="editor_toolbar">
        {if isset($iso2br) AND $iso2br}
            <button class="wrap_selection lang-html" type="button" name="insX" data-tag-open="nl" data-tag-close="nl" data-tarea="serendipity[extended]">noBR</button>
        {/if}
            <button class="hilite_i wrap_selection lang-html" type="button" name="insI" data-tag-open="em" data-tag-close="em" data-tarea="serendipity[extended]">i</button>
            <button class="hilite_b wrap_selection lang-html" type="button" name="insB" data-tag-open="strong" data-tag-close="strong" data-tarea="serendipity[extended]">b</button>
            <button class="hilite_u wrap_selection lang-html" type="button" name="insU" data-tag-open="u" data-tag-close="u" data-tarea="serendipity[extended]">u</button>
            <button class="wrap_selection lang-html" type="button" name="insQ" data-tag-open="blockquote" data-tag-close="blockquote" data-tarea="serendipity[extended]">{$CONST.QUOTE}</button>
            <button class="wrap_insimg" type="button" name="insJ" data-tarea="serendipity[extended]">img</button>
            <button class="wrap_insgal" type="button" name="insG" title="Media Gallery" data-tarea="serendipity[extended]"><span class="icon-gallery" aria-hidden="true"></span><span class="visuallyhidden"> Media Gallery</span></button>
            <button class="wrap_insmedia" type="button" name="insImage" title="{$CONST.MEDIA_LIBRARY}" data-tarea="serendipity[extended]"><span class="icon-s9yml" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_LIBRARY}</span></button>
            <button class="wrap_insurl" type="button" name="insURL" data-tarea="serendipity[extended]">URL</button>
            {serendipity_hookPlugin hook="backend_entry_toolbar_extended" data=$entry_data.entry|default:'' hookAll="true"}
        </div>
    {else}
        <div id="tools_extended" class="editor_toolbar">
            {serendipity_hookPlugin hook="backend_entry_toolbar_extended" data=$entry_data.entry|default:'' hookAll="true"}
        </div>
    {/if}
        <div id="extended_entry_editor">
            <textarea id="serendipity[extended]" name="serendipity[extended]" rows="15">{if isset($entry_vars.entry.extended)}{$entry_vars.entry.extended|escape|default:''}{/if}</textarea>
        </div>
    </div>

    <div id="edit_entry_submit">
        <button id="reset_timestamp" class="button_link" type="button" href="#serendipityNewTimestamp" data-currtime="{$entry_vars.reset_timestamp|formatTime:'Y-m-d\TH:i':true:false:true}" title="{$CONST.RESET_DATE_DESC}"><span class="icon-clock" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.RESET_DATE}</span></button>
        <a id="select_category" class="button_link icon_link" href="#edit_entry_category" title="{$CONST.CATEGORY}"><span class="icon-list-bullet" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.CATEGORIES}</span></a>
    {if class_exists('serendipity_event_freetag')}
        <a id="select_tags" class="button_link icon_link" href="#edit_entry_freetags" title="{$CONST.PLUGIN_EVENT_FREETAG_MANAGETAGS}"><span class="icon-tag" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.PLUGIN_EVENT_FREETAG_MANAGETAGS}</span></a>
    {/if}
        <button id="switch_entry_status" class="button_link" type="button" href="#edit_entry_status" title="{if $entry_vars.draft_mode == 'publish'}{$CONST.PUBLISH}{else}{$CONST.DRAFT}{/if}" data-title-alt="{if $entry_vars.draft_mode == 'publish'}{$CONST.DRAFT}{else}{$CONST.PUBLISH}{/if}">{if $entry_vars.draft_mode == 'publish'}<span class="icon-toggle-on" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PUBLISH}</span>{else}<span class="icon-toggle-off" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DRAFT}</span>{/if}</button>
        <input class="entry_preview" type="submit" value="{$CONST.PREVIEW}">
        <input type="submit" value="{$CONST.SAVE}">
    </div>

    <div id="edit_entry_metadata" class="clearfix">
        <button id="toggle_metadata" class="icon_link" type="button"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.ENTRY_METADATA}</button>

        <div id="meta_data" class="additional_info">
        {if $entry_vars.allowDateManipulation}
            <div id="edit_entry_timestamp" class="form_field">
                <input name="serendipity[chk_timestamp]" type="hidden" value="{$entry_vars.timestamp|escape}">

                <label for="serendipityNewTimestamp">{$CONST.DATE} <i class="icon-info-circled" aria-hidden="true" title="English format only, w/o T separator: YYYY-MM-DDTHH:MM "></i></label>
                <input id="serendipityNewTimestamp" name="serendipity[new_timestamp]" type="datetime-local" value="{$entry_vars.timestamp|formatTime:'Y-m-d\TH:i':true:false:true}">
            </div>
        {/if}
            <div id="edit_entry_status" class="form_select">
                <label for="entry_status">{$CONST.ENTRY_STATUS}</label>
                <select id="entry_status" name="serendipity[isdraft]">
                {if $entry_vars.serendipityRightPublish}
                    <option value="false"{if $entry_vars.draft_mode == 'publish'} selected{/if}>{$CONST.PUBLISH}</option>
                {/if}
                    <option value="true"{if $entry_vars.draft_mode == 'draft'} selected{/if}>{$CONST.DRAFT}</option>
                </select>
            </div>

            <div id="edit_entry_status_comments">
                <fieldset>
                    <span class="wrap_legend"><legend>{$CONST.COMMENTS}</legend></span>

                    <div class="form_check">
                        <input id="checkbox_allow_comments" name="serendipity[allow_comments]" type="checkbox" value="true"{if $entry_vars.allow_comments} checked="checked"{/if}><label for="checkbox_allow_comments">{$CONST.COMMENTS_ENABLE}</label>
                    </div>

                    <div class="form_check">
                        <input id="checkbox_moderate_comments" name="serendipity[moderate_comments]" type="checkbox" value="true"{if $entry_vars.moderate_comments} checked="checked"{/if}><label for="checkbox_moderate_comments">{$CONST.COMMENTS_MODERATE}</label>
                    </div>
                </fieldset>
            </div>

            <div id="edit_entry_category" class="clearfix">
                <fieldset>
                    <span class="wrap_legend"><legend>{$CONST.CATEGORY}</legend></span>

                    <div id="category_filter" class="form_field{if isset($entry_vars.category_compact) AND $entry_vars.category_compact} compact{/if}">
                        {if !isset($entry_vars.category_compact)}
                        <label for="categoryfilter" class="visuallyhidden">{$CONST.FILTERS}</label>
                        <input id="categoryfilter" type="text" placeholder="{$CONST.FILTERS}: {$CONST.CATEGORIES}">
                        <button class="reset_livefilter icon_link" type="button" data-target="categoryfilter" title="{$CONST.RESET_FILTERS}"><span class="icon-cancel" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.RESET_FILTERS}</span></button>
                        {if $use_backendpopups || (isset($force_backendpopups.categories) AND $force_backendpopups.categories)}<a href="#top" class="svg-button_link svg-button_up" title="{$CONST.UP}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333333" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">
                          <path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                        </a>{/if}
                        <button id="toggle_cat_view" class="icon_link" type="button" title="{$CONST.TOGGLE_VIEW}"><span class="icon-th" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.TOGGLE_VIEW}</span></button>
                        {else}
                        {if $use_backendpopups || (isset($force_backendpopups.categories) AND $force_backendpopups.categories)}<a href="#top" class="svg-button_link svg-button_up" title="{$CONST.UP}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#333333" class="bi bi-arrow-up-square-fill" viewBox="0 0 16 16">
                          <path d="M2 16a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2zm6.5-4.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                        </a>{/if}
                        {/if}
                    </div>

            {if NOT empty($entry_vars.category_options)}
                {foreach $entry_vars.category_options AS $entry_cat}
                    <div class="form_check{if isset($entry_vars.category_compact) AND $entry_vars.category_compact} compact{/if}">
                        <input type="hidden" name="serendipity[had_categories]" value="1">
                        <span class="cat_view_pad">{$entry_cat.depth_pad}</span>
                        <input id="serendipity_category_{$entry_cat.categoryid}" name="serendipity[categories][]" type="checkbox" value="{$entry_cat.categoryid}"{if isset($entry_cat.is_selected)} checked="checked"{/if}>

                        <label for="serendipity_category_{$entry_cat.categoryid}">{$entry_cat.category_name|escape}</label>
                    </div>
                {/foreach}
            {/if}

                </fieldset>
            </div>
        </div>
    </div>

    {capture name='advanced_options'}{$entry_vars.entry|serendipity_refhookPlugin:'backend_display'}{/capture}
    {if !empty($smarty.capture.advanced_options)}

    <div id="advanced_options">
        <button id="toggle_advanced" class="icon_link" type="button"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.ADVANCED_OPTIONS}</button>
        <div id="adv_opts" class="additional_info">
            {$smarty.capture.advanced_options}
        </div>
    </div>
    {/if}

</form>
{if $entry_vars.wysiwyg}
    {foreach $entry_vars.wysiwyg_blocks AS $wysiwyg_block_jsname => $wysiwyg_block_item}
        {$wysiwyg_block_item|emit_htmlarea_code:$wysiwyg_block_jsname}
    {/foreach}
    {$entry_vars.wysiwyg_blocks|serendipity_refhookPlugin:'backend_wysiwyg_finish'}
{/if}
