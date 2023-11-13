{if isset($messages)}
{foreach $messages AS $message}
    {$message}
{/foreach}{/if}

{if $case_doSync}
    {if !$perm_adminImagesSync}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED}</span>
    {else}
        {if empty($convertThumbs) AND empty($buildVariation) AND empty($purgeVariation)}
        <h2>{$CONST.SYNCING}</h2>

        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$print_SYNC_DONE}</span>
        {/if}
        {if NOT empty($convertThumbs)}
        <h2>{$CONST.RESIZING}</h2>

        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$print_RESIZE_DONE|default:$CONST.NOTHING_TODO}</span>
        {/if}
        {if NOT empty($buildVariation)}
        <h2>{$CONST.SYNC_BUILD_VARIATIONS}</h2>

        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$print_VARIATIONBUILDS_DONE}</span>
        {/if}
    {/if}
{/if}
{if NOT empty($purgedVariations)}
    <h2>{$CONST.SYNC_PURGED_VARIATIONS}</h2>

    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$print_VARIATIONPURGE_DONE}</span>
{/if}
{if $case_delete}
    <h2>{$CONST.MEDIA_DELETE}</h2>

    <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.ABOUT_TO_DELETE_FILE|sprintf:"$file"}</span>

    <form id="delete_image" method="get">
        <div class="form_buttons">
            <a class="button_link state_cancel icon_link" href="{$abortLoc}">{$CONST.BACK}</a>
            <a class="button_link state_submit icon_link" href="{$newLoc}">{$CONST.DUMP_IT}</a>
        </div>
    </form>
{/if}
{if $case_multidelete}
    <form id="delete_image" method="get">
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.ABOUT_TO_DELETE_FILES}</span>
    {foreach $rip_image AS $ripimg}
        <span class="msg_hint"><span class="icon-help-circled" aria-hidden="true"></span> {$ripimg}</span>
    {/foreach}
        <div class="form_buttons">
            <a class="button_link state_cancel icon_link" href="{$abortLoc}">{$CONST.BACK}</a>
            <a class="button_link state_submit icon_link" href="{$newLoc}">{$CONST.DUMP_IT}</a>
        </div>
    </form>
{/if}
{if $case_do_multidelete OR $case_do_delete}
    {if isset($showML)}{$showML}{/if}
{/if}
{* A $case_rename can not respond to reload page while in JS - serendipity.rename() ajax will reload and set message events by script *}
{if $case_add OR $case_changeProp}
    {if isset($showML)}{$showML}{/if}
{/if}
{if $case_directoryDoDelete}
    {if isset($print_DIRECTORY_WRITE_ERROR)}<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$print_DIRECTORY_WRITE_ERROR}</span>{/if}
    {if isset($ob_serendipity_killPath)}{$ob_serendipity_killPath}{/if}
    {if NOT empty($print_ERROR_NO_DIRECTORY)}<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$print_ERROR_NO_DIRECTORY}</span>{/if}
{/if}
{if $case_directoryEdit}
    {if !empty($smarty.post.serendipity.save) AND isset($savedirtime)}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.SETTINGS_SAVED_AT|sprintf:$savedirtime}</span>
    {/if}
    <h2>{$CONST.MANAGE_DIRECTORIES}</h2>

    <form id="image_directory_edit_form" method="POST" action="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryEdit&amp;serendipity[dir]={$dir|escape}">
        {$formtoken}
        <input name="serendipity[oldDir]" type="hidden" value="{$use_dir}">

        <div class="form_field">
            <label for="diredit_new">{$CONST.NAME}</label>
            <input id="diredit_new" name="serendipity[newDir]" type="text" value="{$use_dir}">
        </div>

        <h3 class="toggle_headline">
            <button class="show_config_option icon_link{if $closed} show_config_option_hide{/if}" type="button" data-href="#directory_permissions" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$CONST.PERMISSIONS}</button>
        </h3>

        <div id="directory_permissions" class="clearfix additional_info">
            <div class="form_multiselect">
                <label for="read_authors">{$CONST.PERM_READ}</label>
                <select id="read_authors" name="serendipity[read_authors][]" multiple size="6">
                    <option value="0"{if $rgroups} selected{/if}>{$CONST.ALL_AUTHORS}</option>
                {foreach $groups AS $group}
                    <option value="{$group.confkey}"{if isset($read_groups.{$group.confkey})} selected{/if}>{$group.confvalue|escape}</option>
                {/foreach}
                </select>
            </div>

            <div class="form_multiselect">
                <label for="write_authors">{$CONST.PERM_WRITE}</label>
                <select id="write_authors" name="serendipity[write_authors][]" multiple size="6">
                    <option value="0"{if $wgroups} selected{/if}>{$CONST.ALL_AUTHORS}</option>
                {foreach $groups AS $group}
                    <option value="{$group.confkey}"{if isset($write_groups.{$group.confkey})} selected{/if}>{$group.confvalue|escape}</option>
                {/foreach}
                </select>
            </div>

            <div class="form_check">
                <input id="setchild" name="serendipity[update_children]" type="checkbox" value="true"{if !empty($smarty.post.update_children) == 'on'} checked="checked"{/if}><label for="setchild">{$CONST.PERM_SET_CHILD}</label>
            </div>

            <div class="form_field">
                <span class="wrap_legend"><legend>{$CONST.PERMISSIONS} <a class="toggle_info button_link" href="#acl_rw_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></a></legend></span>

                <span id="acl_rw_info" class="field_info additional_info">{$CONST.PERMISSION_READ_WRITE_ACL_DESC}</span>
            </div>
        </div>

        <div class="form_buttons">
            <a class="button_link" href="serendipity_admin.php?serendipity[adminModule]=media&amp;serendipity[adminAction]=directorySelect">{$CONST.BACK}</a>
            <input name="serendipity[save]" type="submit" value="{$CONST.SAVE}">
        </div>
    </form>
{/if}
{if $case_directoryDelete}
    <h2>{$CONST.DELETE_DIRECTORY}</h2>

    <p>{$CONST.DELETE_DIRECTORY_DESC}</p>

    <form id="image_directory_delete_form" method="POST" action="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryDoDelete&amp;serendipity[dir]={$dir|escape}">
        {$formtoken}
        <div class="form_check">
            <input id="diredit_delete" name="serendipity[nuke]" type="checkbox" value="true">
            <label for="diredit_delete"><b>{$basename_dir}</b> - {$CONST.FORCE_DELETE}</label>
        </div>

        {* I think this is redundant: <p>{$CONST.CONFIRM_DELETE_DIRECTORY|sprintf:$dir|escape}</p> *}
        <div class="form_buttons">
            <input class="state_cancel" name="SAVE" type="submit" value="{$CONST.DELETE_DIRECTORY}">
        </div>
    </form>
{/if}
{if $case_directoryDoCreate}
    {if $print_DIRECTORY_CREATED}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$print_DIRECTORY_CREATED}</span>
    {/if}
    {if isset($print_DIRECTORY_WRITE_ERROR)}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$print_DIRECTORY_WRITE_ERROR}</span>
    {/if}
    <a class="button_link state_submit" href="serendipity_admin.php?serendipity[adminModule]=media&amp;serendipity[adminAction]=directorySelect">{$CONST.BACK}</a>
{/if}
{if $case_directoryCreate}
    <h2>{$CONST.CREATE_DIRECTORY}</h2>

    <p>{$CONST.CREATE_DIRECTORY_DESC}</p>

    <form id="image_directory_create_form" method="POST" action="?serendipity[step]=directoryDoCreate&amp;serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryDoCreate">
        {$formtoken}
        <div class="form_field">
            <label for="dircreate_name">{$CONST.NAME}</label>
            <input id="dircreate_name" name="serendipity[name]" type="text" value="" required>
        </div>

        <div class="form_select">
            <label for="dircreate_parent">{$CONST.PARENT_DIRECTORY}</label>
            <select id="dircreate_parent" name="serendipity[parent]">
                <option value="">{$CONST.BASE_DIRECTORY}</option>
{foreach $folders AS $folder}
                <option{if $folder.relpath == $get.only_path OR $folder.relpath == $dir} selected{/if} value="{$folder.relpath}">{'&nbsp;'|str_repeat:($folder.depth*2)}{$folder.name}</option>
{/foreach}
            </select>
        </div>
        {serendipity_hookPlugin hookAll=true hook="backend_directory_createoptions" addData=$folders}
        <div class="form_buttons">
            <a class="button_link" href="?serendipity[adminModule]=media&amp;serendipity[adminAction]=directorySelect">{$CONST.BACK}</a>
            <input name="SAVE" type="submit" value="{$CONST.CREATE_DIRECTORY}">
        </div>
    </form>
{/if}
{if $case_directorySelect}
    <h2>{$CONST.MANAGE_DIRECTORIES}</h2>

    <div class="mediabase_file_action"><a class="media_show_info button_link" href="#media_directory_info" title="{$CONST.DIRECTORY_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DIRECTORY_INFO}</span></a></div>
    <header id="media_directory_info" class="media_directory_info additional_info">
        <span class="msg_hint focused">{$CONST.DIRECTORY_INFO_DESC}</span>
    </header>

    <ul id="serendipity_image_folders" class="option_list{if !$threadedDirs} slist{/if}">
    {if !empty($folders) || isset($pathitems[''])}
        <li>
            <div class="clearfix odd">
                <div class="directory_data">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-folder-minus" viewBox="0 0 16 16">
                      <title>{$CONST.BASE_DIRECTORY}</title>
                      <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z"/>
                      <path d="M11 11.5a.5.5 0 0 1 .5-.5h4a.5.5 0 1 1 0 1h-4a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>

                <ul class="plainList clearfix edit_actions">
                    <li>
                        <span class="media_directory_entries imgctlabel" title="{$CONST.IN} {$CONST.BASE_DIRECTORY}"><em>{if isset($pathitems[''])}{$pathitems['']}{else}<span class="emptydim imgctlabel">0</span>{/if} {$CONST.PLUGIN_GROUP_IMAGES}</em></span>
                    </li>
                </ul>
            </div>
        </li>
    {/if}
    {foreach $folders AS $folder}
        {if ! $folder@first}
            {if $folder.depth > $priorDepth}

            <ul>
            {/if}

            {if $folder.depth < $priorDepth}

        </li>

            {for $i=($folder.depth+1) to $priorDepth}

            </ul>
         </li>
            {/for}
            {/if}

            {if $folder.depth == $priorDepth}

        </li>
            {/if}
        {/if}

        {$priorDepth=$folder.depth}

        <li>
            <div class="clearfix {cycle values="odd,even"}">
                <span class="folder_name"><span class="icon-folder-open" aria-hidden="true"></span> {$folder.name}</span>

                <ul class="plainList clearfix edit_actions">
                    <li>{if isset($pathitems[$folder.relpath])}<span class="imgctlabel">{$pathitems[$folder.relpath]} {$CONST.MEDIA}</span>{else}<span class="emptydim imgctlabel">0 {$CONST.MEDIA}</span>{/if}</li>
                    <li><a class="button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryEdit&amp;serendipity[dir]={$folder.relpath|escape}" title="{$CONST.EDIT} {$folder.name}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.EDIT}</span></a></li>
                    <li><a class="button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryCreateSub&amp;serendipity[dir]={$folder.relpath|escape}" title="{$CONST.CREATE_DIRECTORY}"><span class="icon-plus" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.CREATE_DIRECTORY}</span></a></li>
                    <li><a class="button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryDelete&amp;serendipity[dir]={$folder.relpath|escape}" title="{$CONST.DELETE} {$folder.name}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.DELETE}</span></a></li>
                </ul>
            </div>
    {/foreach}

{if isset($priorDepth)}
    {if $priorDepth > 1}
            </li><!-- Depth:{$priorDepth} -->
    {/if}
    {if $priorDepth > 0}
    {for $i=1 to $priorDepth}
        {if $i != $priorDepth}

            </ul>
        </li>
        {/if}
    {/for}
    {/if}
{/if}

    </ul>

    <a class="button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=directoryCreate">{$CONST.CREATE_NEW_DIRECTORY}</a>
{/if}

{* TODO: obsolete? *}
{if isset($case_addSelect) AND $case_addSelect}
    {** smarty display 'admin/media_upload.tpl' **}
{/if}
{* END *}

{if $case_rotateCW}
    {if $rotate_img_done}
    <script>location.href="{$adminFile_redirect}";</script>
    <noscript><a class="button_link icon_link standalone" href="{$adminFile_redirect}">{$CONST.DONE}</a></noscript>
    {/if}
{/if}
{if $case_rotateCCW}
    {if $rotate_img_done}
    <script>location.href="{$adminFile_redirect}";</script>
    <noscript><a class="button_link icon_link standalone" href="{$adminFile_redirect}">{$CONST.DONE}</a></noscript>
    {/if}
{/if}
{if $case_scale}
    {if isset($print_SCALING_IMAGE)}<span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$print_SCALING_IMAGE}</span>{/if}
    {if isset($scaleImgError)}<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$scaleImgError}</span>{/if}
    {if isset($is_done)}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}</span>
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.FORCE_RELOAD}</span>
    {/if}
    {if $showML}{$showML}{/if}
{/if}
{if $case_scaleSelect}
    {if isset($scaleFileName)}<h2>{$CONST.RESIZE_BLAHBLAH|sprintf:'<span class="scale_fname">%s</span>'|sprintf:$scaleFileName}</h2>{/if}
    {if $unscalable}<span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> Scale Format w/o thumbnail not available !! Don't do any scaling action here !!</span>{/if}

    <div id="waitingspin" class="pulsator scale_image" style="display: none"><div></div><div></div></div>
    {if isset($scaleOriginSize)}

    <span class="block_level standalone">
        {$CONST.ORIGINAL_SIZE|sprintf:$scaleOriginSize.width:$scaleOriginSize.height}
        <button class="toggle_info button_link" type="button" data-href="#media_scale_selection"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
        <span id="media_scale_selection" class="clearfix additional_info media_scale_selection">
            <span class="msg_hint image_resize_hint"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.HERE_YOU_CAN_ENTER_BLAHBLAH}</span>
        </span>
    </span>
    {/if}

    <div class="clearfix">
        <form id="serendipityScaleForm" name="serendipityScaleForm" action="?" method="GET">
            {$formtoken}
            <input name="serendipity[adminModule]" type="hidden" value="images">
            <input name="serendipity[adminAction]" type="hidden" value="scale">
            <input name="serendipity[fid]" type="hidden" value="{$get.fid}">
            {if isset($smarty.get.serendipity.page)}<input name="serendipity[page]" type="hidden" value="{$smarty.get.serendipity.page}">{/if}

            <fieldset>
                <span class="wrap_legend"><legend>{$CONST.NEWSIZE}</legend></span>

                <div class="form_field">
                    <label for="resize_width">{$CONST.INSTALL_THUMBDIM_WIDTH}</label>
                    <input id="resize_width" name="serendipity[width]" type="text" value="{$scaleOriginSize.width}">
                </div>

                <div class="form_field">
                    <label for="resize_height">{$CONST.INSTALL_THUMBDIM_HEIGHT}</label>
                    <input id="resize_height" name="serendipity[height]" type="text" value="{$scaleOriginSize.height}">
                </div>
            </fieldset>

            <div class="form_check">
                <input id="resize_keepprops" name="auto" type="checkbox" checked="checked">
                <label for="resize_keepprops">{$CONST.KEEP_PROPORTIONS}</label>
            </div>

            <div class="form_check">
                <input id="resize_scalethumbvariation" name="serendipity[scaleThumbVariation]" type="checkbox">
                <label for="resize_scalethumbvariation">{$CONST.SCALE_THUMB_VARIATION} <button class="toggle_info button_link" type="button" data-href="#media_scale_selection"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button></label>
            </div>

            <div class="form_buttons">
                <a class="button_link" href="?serendipity[adminModule]=media{if isset($smarty.get.serendipity.page)}&amp;serendipity[page]={$smarty.get.serendipity.page}{/if}">{$CONST.BACK}</a>
                <input class="image_scale state_submit" name="scale" type="submit" value="{$CONST.IMAGE_RESIZE}">
            </div>
        </form>

        <div id="serendipityScaleImg" data-imgwidth="{$scaleOriginSize.width}" data-imgheight="{$scaleOriginSize.height}" title="{$scaleFileName}, {$CONST.ORIGINAL_SIZE|sprintf:$scaleOriginSize.width:$scaleOriginSize.height|strip_tags}, scaled for browser preview">
            <picture>{if NOT empty($file_avif)}

                <source type="image/avif" srcset="{$file_avif|default:''}">{/if}{if NOT empty($file_webp)}

                <source type="image/webp" srcset="{$file_webp|default:''}">{/if}

                <img src="{$file}" class="ml_preview_img" name="serendipityScaleImg" alt="{$CONST.PREVIEW}">
            </picture>
        </div>
    </div>
{/if}
{if $case_default}
    {if $showML}{$showML}{/if}
{/if}
{if $showMLbutton}
    <a id="ml_link" class="button_link" href="?serendipity[adminModule]=media">{$CONST.MEDIA_LIBRARY}</a>
{/if}
