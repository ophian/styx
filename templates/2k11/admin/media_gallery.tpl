{$MEDIA_TOOLBAR}

<div class="media_library_pane">
{if $media.nr_files < 1}

    <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_IMAGES_FOUND}</span>
{else}

    <form id="formMultiSelect" name="formMultiSelect" action="?" method="post">
        {$media.token}
        <input name="serendipity[action]" type="hidden" value="admin">
        <input name="serendipity[adminModule]" type="hidden" value="media">
        <input name="serendipity[adminAction]" type="hidden" value="multiselect">

        <div class="media_pane media_gallery" data-thumbmaxwidth="{$media.thumbSize}">
            {$MEDIA_ITEMS}

        {if ($media.page != 1 AND $media.page <= $media.pages) OR $media.page != $media.pages}

            <nav class="pagination">
                <h3>{$CONST.PAGE_BROWSE_ENTRIES|sprintf:$media.page:$media.pages:$media.totalImages}</h3>

                <ul class="clearfix">
                    <li class="first">{if $media.page > 1}<a class="button_link" href="{$media.linkFirst}&amp;serendipity[showGallery]=true" title="{$CONST.FIRST_PAGE}"><span class="visuallyhidden">{$CONST.FIRST_PAGE} </span><span class="icon-to-start" aria-hidden="true"></span></a>{/if}</li>
                    <li class="prev">{if $media.page != 1 AND $media.page <= $media.pages}<a class="button_link" href="{$media.linkPrevious}&amp;serendipity[showGallery]=true" title="{$CONST.PREVIOUS}"><span class="icon-left-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PREVIOUS}</span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                    {* Looks weird, but last will be at end by the CSS float:right *}
                    <li class="last">{if $media.page < $media.pages}<a class="button_link" href="{$media.linkLast}&amp;serendipity[showGallery]=true" title="{$CONST.LAST_PAGE}"><span class="visuallyhidden">{$CONST.LAST_PAGE} </span><span class="icon-to-end" aria-hidden="true"></span></a>{/if}</li>
                    <li class="next">{if $media.page != $media.pages}<a class="button_link" href="{$media.linkNext}&amp;serendipity[showGallery]=true" title="{$CONST.NEXT}"><span class="visuallyhidden">{$CONST.NEXT} </span><span class="icon-right-dir" aria-hidden="true"></span></a>{else}<span class="visuallyhidden">{$CONST.NO_ENTRIES_TO_PRINT}</span>{/if}</li>
                </ul>
            </nav>
        {/if}

        </div>{* media pane gallery edition end *}

        <fieldset id="image_as_link">
            <span class="wrap_legend"><legend>{$CONST.IMAGE_AS_A_LINK}</legend></span>

            <div class="clearfix">
                <div class="form_radio">
                    <input id="radio_islink_no" name="serendipity[isLink]" type="radio" value="no">
                    <label for="radio_islink_no">{$CONST.I_WANT_NO_LINK}</label>
                </div>

                <div class="form_radio">
                    <input id="radio_islink_yes" name="serendipity[isLink]" type="radio" value="yes" checked="checked">
                    <label for="radio_islink_yes">Link to the larger image</label>
                </div>
            </div>

        </fieldset>

        <fieldset id="image_alignment">
            <span class="wrap_legend"><legend>{$CONST.IMAGE_ALIGNMENT}</legend></span>

            <div class="clearfix">
                <div class="form_radio">
                    <input id="image_align_left" name="serendipity[align]" checked="checked" type="radio" value="left">
                    <label for="image_align_left"><img src="{serendipity_getFile file='img/img_align_left.png'}" alt="{$CONST.ALIGN_LEFT}"></label>
                </div>

                <div class="form_radio">
                    <input id="image_align_right" name="serendipity[align]" type="radio" value="right">
                    <label for="image_align_right"><img src="{serendipity_getFile file='img/img_align_right.png'}" alt="{$CONST.ALIGN_RIGHT}"></label>
                </div>
            </div>
        </fieldset>

        <div class="form_buttons">
            <input class="invert_selection" name="toggle" type="button" value="{$CONST.INVERT_SELECTIONS}">
            <input name="serendipity[mediaTextarea]" type="hidden" value="{$media.textarea}">
            <input class="state_submit" name="gallery_insert" type="submit" value="{$CONST.ADD_MEDIA}">
        </div>

    </form>
{/if}

</div>{* media library gallery pane end *}
