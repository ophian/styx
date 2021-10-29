
{if isset($perm_denied) AND $perm_denied}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED}</span>
{else}
    <!-- MEDIA SELECTION START -->
    {if isset($media.external)}{$media.external}{/if}

    <div class="clearfix">
{if isset($media.file) && is_array($media.file)}
    {if $media.file.is_image}
        {serendipity_hookPlugin hook="frontend_image_selector" data=$media.file hookAll=true}
        <h1 id="media_selection_title">{$CONST.YOU_CHOSE|sprintf:$media.file.realname}</h1>

        <picture>
            {if isset($media.file.thumbSizeAVIF) AND $media.file.thumbSizeAVIF > 252 AND $media.file.thumbSizeAVIF <= $media.file.thumbSizeWebp}<source type="image/avif" srcset="{$media.file.full_thumb_avif|default:''}">{/if}
            <source type="image/webp" srcset="{$media.file.full_thumb_webp|default:''}">
            <img src="{$media.file.imgsrc}" class="ml_preview_img" alt="{$media.file.name}">
        </picture>

        <form id="imageForm" name="serendipity[selForm]" action="#" method="GET">
            {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_hiddenfields' data=$media.file}
            <input name="imgThumbWidth" type="hidden" value="{$media.file.thumbWidth}">
            <input name="imgThumbHeight" type="hidden" value="{$media.file.thumbHeight}">
            <input name="imgWidth" type="hidden" value="{$media.file.dimensions_width}">
            <input name="imgHeight" type="hidden" value="{$media.file.dimensions_height}">
            <input name="imgID" type="hidden" value="{$media.file.id}">
            <input name="baseURL" type="hidden" value="{$serendipityBaseURL}">
            <input name="indexFile" type="hidden" value="{$serendipityIndexFile}">
            <input name="imgName" type="hidden" value="{$media.file.full_file}">
            <input name="thumbName" type="hidden" value="{$media.file.show_thumb}">
            <input name="avifThumbName" type="hidden" value="{$media.file.full_thumb_avif|default:''}">
            <input name="webPthumbName" type="hidden" value="{$media.file.full_thumb_webp|default:''}">
            <input name="avifFileName" type="hidden" value="{$media.file.full_file_avif|default:''}">
            <input name="webPfileName" type="hidden" value="{$media.file.full_file_webp|default:''}">
            <input name="srcAvifBestFormatSize" type="hidden" value="{if isset($media.file.sizeAVIF) AND $media.file.sizeAVIF > 252 AND $media.file.sizeAVIF <= $media.file.sizeWebp}true{else}false{/if}">
            <input name="hotlink" type="hidden" value="{$media.file.hotlink}">
        {if $media.htmltarget}
            <input name="serendipity[htmltarget]" type="hidden" value="{$media.htmltarget|escape}">
        {/if}
        {if $media.filename_only}
            <input name="serendipity[filename_only]" type="hidden" value="{$media.filename_only|escape}">
        {/if}
        {if isset($media.file.fast_select) AND $media.file.fast_select}
            <script>
                {serendipity_hookPlugin hookAll=true hook='frontend_image_add_filenameonly' data=$media.file}
                serendipity.serendipity_imageSelector_done('{$media.textarea|escape}');
            </script>
        {else}
            <fieldset id="image_size">
                <span class="wrap_legend"><legend>{$CONST.IMAGE_SIZE}</legend></span>

                <div class="clearfix">
                    <div class="form_radio">
                        <input id="radio_link_no" name="serendipity[linkThumbnail]" type="radio" value="no" {'linkThumbnail'|ifRemember:'no':true}>
                        <label for="radio_link_no">{$CONST.I_WANT_THUMB}</label>
                    </div>

                    <div class="form_radio">
                        <input id="radio_link_yes" name="serendipity[linkThumbnail]" type="radio" value="yes" {'linkThumbnail'|ifRemember:'yes'}>
                        <label for="radio_link_yes">{$CONST.I_WANT_BIG_IMAGE}</label>
                    </div>
                </div>
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_imagesize' data=$media.file}
            </fieldset>
            {if NOT $media.filename_only}
            <fieldset id="image_alignment">
                <span class="wrap_legend"><legend>{$CONST.IMAGE_ALIGNMENT}</legend></span>

                <div class="clearfix">
                    <div class="form_radio">
                        <input id="image_align_top" name="serendipity[align]" {'align'|ifRemember:''} type="radio" value="">
                        <label for="image_align_top"><img src="{serendipity_getFile file='img/img_align_top.png'}" alt="{$CONST.ALIGN_TOP}"></label>
                    </div>

                    <div class="form_radio">
                        <input id="image_align_left" name="serendipity[align]" {'align'|ifRemember:'left':true} type="radio" value="left">
                        <label for="image_align_left"><img src="{serendipity_getFile file='img/img_align_left.png'}" alt="{$CONST.ALIGN_LEFT}"></label>
                    </div>

                    <div class="form_radio">
                        <input id="image_align_right" name="serendipity[align]" {'align'|ifRemember:'right'} type="radio" value="right">
                        <label for="image_align_right"><img src="{serendipity_getFile file='img/img_align_right.png'}" alt="{$CONST.ALIGN_RIGHT}"></label>
                    </div>
                </div>
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_imagealign' data=$media.file}
            </fieldset>

            <fieldset id="image_as_link">
                <span class="wrap_legend"><legend>{$CONST.IMAGE_AS_A_LINK}</legend></span>

                <div class="clearfix">
                    <div class="form_radio">
                        <input id="radio_islink_no" name="serendipity[isLink]" type="radio" value="no" {'isLink'|ifRemember:'no':true}>
                        <label for="radio_islink_no">{$CONST.I_WANT_NO_LINK}</label>
                    </div>

                    <div class="form_radio">
                        <input id="radio_islink_yes" name="serendipity[isLink]" type="radio" value="yes" {'isLink'|ifRemember:'yes'}>
                        <label for="radio_islink_yes">{$CONST.I_WANT_IT_TO_LINK}</label>

                        <div class="form_field">
                        {* Could use input[type=url], but does that handle local URLs as well? Hm. *}
                            <label for="media_file_path" class="visuallyhidden">{$CONST.FILTER_DIRECTORY}</label>
                        {if $media.file.hotlink}
                            <input id="media_file_path" name="serendipity[url]" type="text" value="{$media.file.path}">
                        {else}
                            {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_link_url' data=$media.file.links}
                            <input id="media_file_path" name="serendipity[url]" type="text" value="{$media.file.links.imagelinkurl}">
                        {/if}
                        </div>
                    </div>
                </div>

                <div class="form_select">
                    <select id="select_image_target" name="serendipity[target]">
                        <option value="none"   {'target'|ifRemember:'none':false:'selected'}>{$CONST.NONE}</option>
                        <option value="js"     {'target'|ifRemember:'js':false:'selected'}>{$CONST.MEDIA_TARGET_JS}</option>
                        <option value="plugin" {'target'|ifRemember:'plugin':false:'selected'}>{$CONST.MEDIA_ENTRY}</option>
                        <option value="_blank" {'target'|ifRemember:'_blank':false:'selected'}>{$CONST.MEDIA_TARGET_BLANK}</option>
                    </select>
                    {* Currently all 'frontend_image_selector_xxx' hooks are just dummies (used in the past) for the imageselectorplus event plugin. *}
                    {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_imagelink' data=$media.file}
                    <label for="select_image_target">{$CONST.MEDIA_TARGET}</label>
                </div>
            </fieldset>

            <div id="image_comment" class="form_area">
                <label for="serendipity_imagecomment">{$CONST.COMMENT}</label>
                <textarea id="serendipity_imagecomment" name="serendipity[imagecomment]" rows="5">{if isset($media.file.props.base_property)}{$media.file.props.base_property.ALL.COMMENT1|escape|default:''}{/if}</textarea>
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_imagecomment' data=$media.file}
            </div>

            <div id="image_alttext" class="form_field">
                <label for="serendipity_alt">{$CONST.MEDIA_ALT}</label>
                <input id="serendipity_alt" name="serendipity[alt]" type="text" value="{if isset($media.file.props.base_property)}{$media.file.props.base_property.ALL.ALT|escape|default:''}{/if}">
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_alt' data=$media.file}
            </div>

            <div id="image_title" class="form_field">
                <label for="serendipity_title">{$CONST.MEDIA_TITLE}</label>
                <input id="serendipity_title" name="serendipity[title]" type="text" value="{if isset($media.file.props.base_property)}{$media.file.props.base_property.ALL.TITLE|escape|default:''}{/if}">
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_title' data=$media.file}
            </div>
            {/if}
            {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_more' data=$media.file}
            <div class="form_buttons">
                <input class="button_link go_back" type="button" value="{$CONST.BACK}">
                <input class="input_button state_submit" type="submit" value="{$CONST.ADD_MEDIA}" onclick="serendipity.rememberMediaOptions(); {$media.file.origfinishJSFunction}">
            {if $media.supportsWebP AND NOT empty($media.file.full_thumb_webp)}
                <input id="picSubmit" class="input_button state_submit" type="submit" value="{$CONST.ADD_MEDIA_PICTELEMENT}" data-submit="enhanced" onclick="serendipity.rememberMediaOptions(); serendipity.mediaPictureSubmit(); {$media.file.origfinishJSFunction}">
            {/if}
                {serendipity_hookPlugin hookAll=true hook='frontend_image_selector_submit' data=$media.file}
                <a class="toggle_info button_link" href="#media_serve_info" title="{$CONST.MEDIA_SERVE_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_SERVE_INFO}</span></a>
            </div>

            <div id="media_serve_info" class="msg_hint msg-0 msg-sm media_serve_info additional_info">
                <span class="icon-info-circled"></span> <em>{$CONST.PICTURE_FORMAT_BUTTON_DESC}</em>
            </div>
        {/if}{* else fast_select end *}
        </form>
    {else}{* if $media.file.is_image end *}
        {if $media.filename_only}
        <script>
            {serendipity_hookPlugin hookAll=true hook='frontend_image_add_filenameonly' data=$media}
            if (parent.self.opener == undefined) {
                // in iframes, there is no opener, and the magnific popup is wrapped
                parent.self = window.parent.parent.$.magnificPopup;
                parent.self.opener = window.parent.parent;
            }
            parent.self.opener.serendipity.serendipity_imageSelector_addToElement('{$media.file.full_file|escape}', '{$media.htmltarget|escape}');
            parent.self.close();
        </script>
        {else}
        <script>
        {if in_array($media.file.mediatype, ['video', 'binary']) AND in_array($media.file.extension, ['mp4', 'webm', 'ogv']) AND NOT isset($smarty.get.mediaobject.link)}
            block = '<div class="media_object_container"><!-- s9ymdb:{$media.file.id} --><video controls="" src="{$media.file.full_file}" title="Play: {$media.file.realname|escape}" type="video/{$media.file.extension}"></video></div>';
        {elseif in_array($media.file.mediatype, ['audio', 'binary']) AND in_array($media.file.extension, ['mp3', 'm4a', 'wav', 'ogg', 'aif', 'aiff', 'flac', 'au']) AND NOT isset($smarty.get.mediaobject.link)}
            block = '<div class="media_object_container"><!-- s9ymdb:{$media.file.id} --><audio controls="" preload="auto" src="{$media.file.full_file}" title="Play: {$media.file.realname|escape}" type="audio/{$media.file.extension}"></audio></div>';
        {else}
            block = '<span class="media_object_container_link"><!-- s9ymdb:{$media.file.id} --><a class="block_level opens_window" href="{$media.file.full_file}" title="{$media.file.realname|escape}">{$media.file.realname|escape}</a></span>';
        {/if}
            {serendipity_hookPlugin hookAll=true hook='frontend_image_add_unknown' data=$media}
            if (parent.self.opener == undefined) {
                // in iframes, there is no opener, and the magnific popup is wrapped
                parent.self = window.parent.parent.$.magnificPopup;
                parent.self.opener = window.parent.parent;
            }
            if (parent.self.opener.editorref) {
                parent.self.opener.editorref.surroundHTML(block, '');
            } else {
                parent.self.opener.serendipity.serendipity_imageSelector_addToBody(block, '{$media.textarea}');
            }
            parent.self.close();
        </script>
        {/if}
    {/if}{* if $media.file.is_image is something else end *}
{/if}
    </div>
{/if}{* if $perm_denied else end *}

