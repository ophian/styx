{$MEDIA_TOOLBAR}

<div class="media_library_pane">
{if $media.nr_files < 1}

    <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_IMAGES_FOUND}</span>
{else}
    {if $media.manage AND $media.multiperm}

    <form id="formMultiSelect" name="formMultiSelect" action="?" method="post">
        {$media.token}
        <input name="serendipity[action]" type="hidden" value="admin">
        <input name="serendipity[adminModule]" type="hidden" value="media">
        <input name="serendipity[adminAction]" type="hidden" value="multicheck">
    {/if}

        <div class="media_pane" data-thumbmaxwidth="{$media.thumbSize}">
            {$MEDIA_ITEMS}

        {if ($media.page != 1 AND $media.page <= $media.pages) OR $media.page != $media.pages}

            <nav class="pagination">
                <h3>{$CONST.PAGE_BROWSE_ENTRIES|sprintf:$media.page:$media.pages:$media.totalImages}</h3>

                <ul class="clearfix">
                    <li class="first">{if $media.page > 1}<a class="button_link" href="{$media.linkFirst}" title="{$CONST.FIRST_PAGE}"><span class="visuallyhidden">{$CONST.FIRST_PAGE} </span><span class="icon-to-start" aria-hidden="true"></span></a>{/if}</li>
                    <li class="prev">{if $media.page != 1 AND $media.page <= $media.pages}<a class="button_link" href="{$media.linkPrevious}" title="{$CONST.PREVIOUS}"><span class="icon-left-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIOUS}</span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                    {* Looks weird, but last will be at end by the CSS float:right *}
                    <li class="last">{if $media.page < $media.pages}<a class="button_link" href="{$media.linkLast}" title="{$CONST.LAST_PAGE}"><span class="visuallyhidden">{$CONST.LAST_PAGE} </span><span class="icon-to-end" aria-hidden="true"></span></a>{/if}</li>
                    <li class="next">{if $media.page != $media.pages}<a class="button_link" href="{$media.linkNext}" title="{$CONST.NEXT}"><span class="visuallyhidden">{$CONST.NEXT} </span><span class="icon-right-dir" aria-hidden="true"></span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                </ul>
            </nav>
        {/if}

        </div>{* media pane end *}

    {if $media.manage AND $media.multiperm}

        <div class="form_buttons">
            <input class="invert_selection" name="toggle" type="button" value="{$CONST.INVERT_SELECTIONS}">
            <input class="state_cancel" name="toggle_delete" type="submit" value="{$CONST.DELETE}">
        </div>
        <hr>
        <div class="form_select">
            <label for="newDir">{$CONST.FILTER_DIRECTORY}</label>
            <input type="hidden" name="serendipity[oldDir]" value="">
            <select id="newDir" name="serendipity[newDir]">
                <option value=""></option>
                <option value="uploadRoot">{$CONST.BASE_DIRECTORY}</option>
            {foreach $media.paths AS $folderFoot}

                <option value="{$folderFoot.relpath}">{'&nbsp;'|str_repeat:($folderFoot.depth*2)}{$folderFoot.name}</option>{* * *}
            {/foreach}

            </select>
        </div>
        <div class="form_buttons">
            <input class="state_submit" name="toggle_move" type="submit" value="{$CONST.MOVE}">
            <span class="media_file_footer actions "><a class="media_show_info button_link" href="#media_file_bulkmove" title="{$CONST.BULKMOVE_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.BULKMOVE_INFO}</span></a></span>
        </div>

        <footer id="media_file_bulkmove" class="media_file_bulkmove additional_info">
            <span class="msg_hint focused">{$CONST.BULKMOVE_INFO_DESC}</span>
        </footer>

    </form>

    {/if}
{/if}

</div>{* MediaLibrary pane end *}
