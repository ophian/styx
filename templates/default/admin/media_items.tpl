{foreach $media.files AS $file}
    {if NOT $media.manage}
        {* ML got called for inserting media *}
        {if $file.is_image AND !empty($file.full_path_thumb)}
            {if NOT empty($media.textarea) OR NOT empty($media.htmltarget)}
            {$link="?serendipity[adminModule]=images&amp;serendipity[adminAction]=choose&amp;serendipity[fid]={$file.id}&amp;serendipity[textarea]={$media.textarea}&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;serendipity[noFooter]=true&amp;serendipity[filename_only]={$media.filename_only}&amp;serendipity[htmltarget]={$media.htmltarget}"}
            {else}
                {if $file.url}
                    {$link="{$file.url}&amp;serendipity[image]={$file.id}"}
                {/if}
            {/if}

            {$img_src_avif="{$file.full_thumb_avif|default:''}"}
            {$img_src_webp="{$file.full_thumb_webp|default:''}"}
            {$img_src="{$file.full_thumb}"}
            {$img_title="{$file.path}{$file.name}"}
            {$img_alt="{$file.realname}"}

        {elseif $file.is_image AND $file.hotlink}
            {if NOT empty($media.textarea)}
                {$link="?serendipity[adminModule]=images&amp;serendipity[adminAction]=choose&amp;serendipity[fid]={$file.id}&amp;serendipity[textarea]={$media.textarea}&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;serendipity[noFooter]=true&amp;serendipity[filename_only]={$media.filename_only}&amp;serendipity[htmltarget]={$media.htmltarget}"}
            {else}
                {if $file.url}
                    {$link="{$file.url}&amp;serendipity[image]={$file.id}"}
                {/if}
            {/if}

            {* $link_webp="{$file.full_file_webp|default:''}" NOT actually a NEED here, isn't it .. DITTO for avif *}
            {* $img_src_webp="{$file.full_thumb_webp|default:''}" NOT actually a NEED here, isn't it .. DITTO for avif *}
            {$img_src="{$file.path}"}
            {$img_title="{$file.path}"}
            {$img_alt="{$file.realname}"}
        {else}
            {if NOT empty($media.textarea)}
                {$link="?serendipity[adminModule]=images&amp;serendipity[adminAction]=choose&amp;serendipity[fid]={$file.id}&amp;serendipity[textarea]={$media.textarea}&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;serendipity[noFooter]=true&amp;serendipity[filename_only]={$media.filename_only}&amp;serendipity[htmltarget]={$media.htmltarget}"}
            {else}
                {if $file.url}
                    {$link="{$file.url}&amp;serendipity[image]={$file.id}"}
                {/if}
            {/if}

            {$img_src="{$file.mimeicon}"}
            {$img_title="{$file.path}{$file.name}({$file.mime})"}
            {$img_alt="{$file.mime}"}
        {/if}
    {else}
        {if $file.is_image AND !empty($file.full_path_thumb)}
            {$link="{if $file.hotlink}{$file.path}{else}{$file.full_file}{/if}"}
            {$link_avif="{$file.full_file_avif|default:''}"}
            {$link_webp="{$file.full_file_webp|default:''}"}
            {$img_src="{$file.show_thumb}"}
            {$img_src_avif="{$file.full_thumb_avif|default:''}"}
            {$img_src_webp="{$file.full_thumb_webp|default:''}"}
            {$img_title="{$file.path}{$file.name}"}
            {$img_alt="{$file.realname}"}
        {elseif $file.is_image AND $file.hotlink}
            {$link="{if $file.hotlink}{$file.path}{else}{$file.full_file}{/if}"}
            {$link_avif="{$file.full_file_avif|default:''}"}
            {$link_webp="{$file.full_file_webp|default:''}"}
            {$img_src="{$file.path}"}
            {$img_src_avif=""}{* YES, empty! Else it uses the predefined item *}
            {$img_src_webp=""}{* YES, empty! Else it uses the predefined item *}
            {$img_title="{$file.path}"}
            {$img_alt="{$file.realname}"}
        {else}
            {$link="{if $file.hotlink}{$file.path}{else}{$file.full_file}{/if}"}
            {$link_avif="{$file.full_file_avif|default:''}"}
            {$link_webp="{$file.full_file_webp|default:''}"}
            {$img_src="{$file.mimeicon}"}
            {$img_src_avif="{$file.full_thumb_avif|default:''}"}
            {$img_src_webp="{$file.full_thumb_webp|default:''}"}
            {$img_title="{$file.path}{$file.name}({$file.mime})"}
            {$img_alt="{$file.mime}"}
        {/if}
    {/if}
    {* builds a ML objects link for step 1, to pass to media_choose.tpl file section: passthrough media.filename_only scripts - do not use "empty($link) AND" here, since that would require a reset before! Strictly build this link for media to textarea cases only. *}
    {if (NOT $file.is_image OR $file.is_image == 0) AND $file.mediatype != 'image' AND $file.realfile AND NOT empty($media.textarea) AND NOT empty($media.htmltarget)}
        {$link="?serendipity[adminModule]=images&amp;serendipity[adminAction]=choose&amp;serendipity[noBanner]=true&amp;serendipity[noSidebar]=true&amp;serendipity[noFooter]=true&amp;serendipity[fid]={$file.id}&amp;serendipity[filename_only]={$media.filename_only}&amp;serendipity[textarea]={$media.textarea}&amp;serendipity[htmltarget]={$media.htmltarget}"}
    {/if}
    {* check empty cases like pdf thumbs to not fillup with last generated img_src_webp string *}
    {* AVIF *}
    {if empty($file.full_thumb_avif)}
        {$img_src_avif=""}
    {/if}
    {if NOT isset($link_avif)}{$link_avif=null}{/if}
    {if NOT isset($file.nice_size_avif)}{$file.nice_size_avif=null}{/if}
    {* WebP *}
    {if empty($file.full_thumb_webp)}
        {$img_src_webp=""}
    {/if}
    {if NOT isset($link_webp)}{$link_webp=null}{/if}
    {if NOT isset($file.nice_size_webp)}{$file.nice_size_webp=null}{/if}

            <article id="media_{$file.id}" class="media_file mlDefCol{if NOT empty($smarty.get.serendipity.adminAction) AND $smarty.get.serendipity.adminAction == 'properties'} mfile_prop{/if} {if $media.manage AND $media.multiperm}manage {/if}{cycle values="odd,even"}">
                <header class="clearfix">
                    {if $media.manage AND $media.multiperm}

                    <div class="form_check">
                        <input id="multicheck_image{$file.id}" class="multicheck" name="serendipity[multiCheck][]" type="checkbox" value="{$file.id}" data-multixid="media_{$file.id}">
                        <label for="multicheck_image{$file.id}" class="visuallyhidden">{$CONST.TOGGLE_SELECT}</label>
                    </div>
                    {/if}

                    <h3 title="{$file.diskname}">{if $media.manage}{$file.diskname|truncate:38:"&hellip;":true}{else}{$file.diskname}{/if}{if NOT empty($file.orderkey)}: {$file.orderkey|escape}{/if}
                    {if $file.hotlink}<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-share-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="title">External hotlink</title><path fill-rule="evenodd" d="M11 2.5a2.5 2.5 0 1 1 .603 1.628l-6.718 3.12a2.499 2.499 0 0 1 0 1.504l6.718 3.12a2.5 2.5 0 1 1-.488.876l-6.718-3.12a2.5 2.5 0 1 1 0-3.256l6.718-3.12A2.5 2.5 0 0 1 11 2.5z"/>
                    </svg>{/if}</h3>
                    {if $file.authorid != 0}<span class="author block_level">{$file.authorname}</span>{/if}

                </header>

                <div class="clearfix equal_heights media_file_wrap">
                    <div class="media_file_preview">
                {if isset($link)}
                    {if isset($file.mimeicon) AND in_array($file.mediatype, ['audio', 'video', 'binary']) AND in_array($file.extension, ['mp3', 'm4a', 'wav', 'ogg', 'aif', 'aiff', 'flac', 'au', 'mp4', 'webm', 'ogv'])}

                        {if NOT $media.manage AND $media.viewperm}<span class="media_player"><a href="{$link}"{else}<div{/if} class="media_mime_player_thumb">
                            <img src="{$img_src}" title="media object: {$img_title} as player" alt="{$img_alt}"><!-- media/manage -->{if NOT $media.manage AND $media.viewperm}</a></span>
                        <span class="media_or">- OR -</span>
                        <span class="media_link media_mime_player_thumb"><a href="{$link}&amp;mediaobject[link]=1" title="{$file.diskname}"><img src="{$img_src}" title="media object: {$img_title} as link" alt="{$img_alt}"><!-- media/manage --></a></span>
                        {else}</div>{/if}
                    {else}

                        <div class="pulsator" style="display: none"><div></div><div></div></div>
                        <a{if $media.manage AND $media.viewperm} class="media_fullsize"{/if} href="{if isset($file.sizeAVIF) AND $file.sizeAVIF > 252 AND $file.sizeAVIF != 34165 AND $file.sizeAVIF != 3389 AND isset($file.sizeWebp) AND $file.sizeAVIF < $file.sizeWebp}{$link_avif|default:$link}{else if isset($file.sizeWebp) AND $file.sizeWebp > 0}{$link_webp|default:$link}{else}{$link}{/if}" data-fallback="{$link}" title="{$CONST.MEDIA_FULLSIZE}: {$file.diskname}{if isset($file.sizeAVIF) AND $file.sizeAVIF > 252 AND isset($file.sizeWebp) AND $file.sizeAVIF < $file.sizeWebp}{if !empty($img_src_avif)} (AVIF){/if}{else}{if !empty($img_src_webp) AND isset($file.sizeWebp) AND $file.sizeWebp > 0} (WepP){/if}{/if}" data-pwidth="{$file.popupWidth}" data-pheight="{$file.popupHeight}">
                            <picture>{if isset($file.thumbSizeAVIF) AND $file.thumbSizeAVIF > 252 AND $file.thumbSizeAVIF != 34165 AND $file.thumbSizeAVIF != 3389 AND ( $file.thumbSizeWebp == 0 OR $file.thumbSizeAVIF < $file.thumbSizeWebp )}

                                <source type="image/avif" srcset="{$img_src_avif|default:''}">
                            {/if}

                                <source type="image/webp" srcset="{if NOT isset($file.thumbSizeWebp) OR $file.thumbSizeWebp > 0}{$img_src_webp|default:''}{/if}">
                                <img src="{$img_src}" class="ml_preview_img" title="{$img_title}" alt="{$img_alt}"><!-- media/manage -->
                            </picture>
                        </a>
                    {/if}
                    {if in_array($file.mediatype, ['video', 'binary']) AND in_array($file.extension, ['mp4', 'webm', 'ogv'])}

                        <div class="media_controls">
                            <video controls>
                                <source src="{$file.full_file}" type="video/{$file.extension}"><!-- media/properties video -->
                            </video>
                        </div>
                    {/if}
                    {if in_array($file.mediatype, ['audio', 'binary']) AND in_array($file.extension, ['mp3', 'm4a', 'wav', 'ogg', 'aif', 'aiff', 'flac', 'au'])}

                        <div class="media_controls">
                            <audio src="{$file.full_file}" type="audio/{$file.extension}" controls></audio>
                        </div>
                    {/if}
                {else}
                    {if $file.is_image}{if NOT $media.enclose}

                        <div id="waitingspin" class="pulsator format_image" style="display: none"><div></div><div></div></div>{/if}

                        <picture>{if isset($file.thumbSizeAVIF) AND $file.thumbSizeAVIF > 252 AND $file.thumbSizeAVIF != 34165 AND $file.thumbSizeAVIF != 3389 AND ($file.thumbSizeWebp == 0 OR $file.thumbSizeAVIF < $file.thumbSizeWebp)}

                            <source type="image/avif" srcset="{$img_src_avif|default:''}">{/if}

                            <source type="image/webp" srcset="{if NOT isset($file.thumbSizeWebp) OR $file.thumbSizeWebp > 0}{$img_src_webp|default:''}{/if}">
                            <img src="{$img_src}" class="ml_preview_img" title="{if NOT $media.enclose}{$CONST.THUMBNAIL_SHORT}: {/if}{$img_title}" alt="{$img_alt}"><!-- media/properties -->
                        </picture>
                        {if $file.mime|truncate:6:'' == 'image/' AND ($file.extension|count_characters > $CONST.PATHINFO_EXTENSION)}

                        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.ERROR_SOMETHING}
                            <p>{$CONST.MEDIA_EXTENSION_FAILURE|sprintf:$file.realname:$file.mime:$file.extension:($file.extension|count_characters):$CONST.PATHINFO_EXTENSION}</p>
                            {$CONST.MEDIA_EXTENSION_FAILURE_REPAIR}
                        </span>
                        {/if}
                    {else}
                        {if in_array($file.mediatype, ['video', 'binary']) AND in_array($file.extension, ['mp4', 'webm', 'ogv'])}

                        <div class="media_controls">
                            <video controls>
                                <source src="{$file.full_file}" type="video/{$file.extension}"><!-- media/properties video -->
                            </video>
                        </div>
                        {elseif in_array($file.mediatype, ['audio', 'binary']) AND in_array($file.extension, ['mp3', 'm4a', 'wav', 'ogg', 'aif', 'aiff', 'flac', 'au'])}

                        <div class="media_controls">
                            <audio src="{$file.full_file}" type="audio/{$file.extension}" controls></audio>
                        </div>
                        {else}

                        <img src="{$img_src}" title="{$img_title}" alt="{$img_alt}"><!-- media/properties non image file -->
                        {/if}
                    {/if}{* is image end *}
                {/if}{* is link end *}


                        <footer id="media_file_meta_{$file.id}" class="media_file_meta additional_info">
                            <ul class="plainList">
                            {if $file.hotlink}

                                <li><b>{$CONST.MEDIA_HOTLINKED}:</b> {$file.realfile}</li>
                            {else}
                                {if $file.realname != $file.diskname}

                                <li title="{$file.realname}"><b>Origin:</b> {$file.realname|truncate:38:"&hellip;"}</li>
                                {/if}
                                {if $file.mime}

                                <li><b>MIME-{$CONST.TYPE}:</b> {$file.mime}</li>
                                {/if}
                                {if $file.is_image}

                                <li><b>{$CONST.ORIGINAL_SHORT}:</b> {$file.dimensions_width}x{$file.dimensions_height}</li>
                                <li><b>{$CONST.THUMBNAIL_SHORT}:</b> {$file.dim.0|default:0}x{$file.dim.1|default:0}</li>
                                {/if}

                                <li class="halflinespacer">&nbsp;</li>
                                <li><b>{if $file.is_image}{$CONST.FILE_SIZE}{else}{$CONST.SORT_ORDER_SIZE}{/if}:</b> {$file.nice_size} KB</li>
                                {if isset($file.nice_thumbsize) AND NOT $file.hotlink}

                                <li><b>{$CONST.THUMBFILE_SIZE}:</b> {$file.nice_thumbsize} KB</li>
                                {/if}
                                {* AVIF *}
                                {if NOT empty($file.nice_size_avif) AND NOT $file.hotlink}

                                <li><b>AVIF-{$CONST.FILE_SIZE}:</b> {if $file.sizeAVIF == 34165 OR $file.sizeAVIF == 3389} (broken){else}{$file.nice_size_avif} KB{/if}</li>
                                {/if}
                                {if $file.is_image AND NOT empty($file.nice_thumbsize_avif)}

                                <li><b>AVIF-{$CONST.THUMBFILE_SIZE}:</b> {if $file.thumbSizeAVIF == 34165 OR $file.thumbSizeAVIF == 3389} (broken){else}{$file.nice_thumbsize_avif} KB{/if}</li>
                                {/if}
                                {* WebP *}
                                {if NOT empty($file.nice_size_webp) AND NOT $file.hotlink}

                                <li><b>WebP-{$CONST.FILE_SIZE}:</b> {$file.nice_size_webp} KB</li>
                                {/if}
                                {if $file.is_image AND NOT empty($file.nice_thumbsize_webp)}

                                <li><b>WebP-{$CONST.THUMBFILE_SIZE}:</b> {$file.nice_thumbsize_webp} KB</li>
                                {/if}

                                <li class="halflinespacer">&nbsp;</li>
                                <li><b>{$CONST.PATH}:</b> "{$file.path}"</li>
                                <li><b>{$CONST.DATE}:</b> {$file.date|formatTime:DATE_FORMAT_SHORT}</li>
                            {/if}

                            </ul>
                        </footer>
                    </div>
                </div>
            {if ($media.manage OR {serendipity_getConfigVar key='showMediaToolbar'}) AND $media.metaActionBar}

                <ul class="media_file_actions actions plainList clearfix">
                    <li><a class="media_show_info button_link" href="#media_file_meta_{$file.id}" title="{$CONST.SHOW_METADATA}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.SHOW_METADATA}</span></a></li>
                {if $file.is_editable}
                    {if NOT $file.hotlink AND $media.resetperm}

                    <li><button class="media_rename button_link" type="button" title="{$CONST.MEDIA_RENAME}" data-fileid="{$file.id}" data-filename="{$file.name|escape:'javascript'}"><span class="icon-edit" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_RENAME}</span></button></li>
                    {/if}
                    {if $file.is_image AND NOT $file.hotlink AND $media.multiperm}

                    <li><a class="media_resize button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=scaleSelect&amp;serendipity[fid]={$file.id}{if isset($smarty.get.serendipity.page)}&amp;serendipity[page]={$smarty.get.serendipity.page}{/if}" title="{$CONST.IMAGE_RESIZE}"><span class="icon-resize-full" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.IMAGE_RESIZE}</span></a></li>
                    {/if}
                    {if $file.is_image AND NOT $file.hotlink AND $media.multiperm}

                    <li><a class="media_rotate_left button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=rotateCCW&amp;serendipity[fid]={$file.id}" title="{$CONST.IMAGE_ROTATE_LEFT}"><span class="icon-ccw" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.IMAGE_ROTATE_LEFT}</span></a></li>
                    {/if}
                    {if $file.is_image AND NOT $file.hotlink AND $media.multiperm}

                    <li><a class="media_rotate_right button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=rotateCW&amp;serendipity[fid]={$file.id}" title="{$CONST.IMAGE_ROTATE_RIGHT}"><span class="icon-cw" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.IMAGE_ROTATE_RIGHT}</span></a></li>
                    {/if}
                    {if $media.manage AND $media.multiperm}

                    <li><a class="media_prop button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=properties&amp;serendipity[fid]={$file.id}{if isset($smarty.get.serendipity.page)}&amp;serendipity[page]={$smarty.get.serendipity.page}{/if}" title="{$CONST.MEDIA_PROP}"><span class="icon-picture" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_PROP}</span></a></li>
                    {/if}
                    {if $media.multiperm OR 'adminImagesDelete'|checkPermission}

                    <li><a class="media_delete button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=delete&amp;serendipity[fid]={$file.id}" title="{$CONST.MEDIA_DELETE}" data-fileid="{$file.id}" data-filename="{$file.name|escape:'javascript'}"><span class="icon-trash" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_DELETE}</span></a></li>
                    {/if}
                    {if NOT empty($imagesNoSync)}
                    {foreach $imagesNoSync AS $special}
                    {if $file.name == $special.pfilename}{* Check out erroneous build AVIF files by filesize and switch special case variation link on AVIF true *}
                    {if $special.extension == 'avif'}{if $special.filesize == null OR $special.filesize <= 252 OR $special.filesize == 34165 OR $special.filesize == 3389 OR (isset($special.linknext) AND $special.linknext === true)}{continue}{else}{assign var="isavif" value=true}{/if}{/if}
                    {if $special.extension == 'webp' AND isset($isavif) AND $isavif === true}{* reset loop assignment *}{assign var="isavif" value=false}{continue}{* do not show special webp *}{/if}

                    <li class="special"><a class="media_fullsize media_prop button_link" href="{$special.url}" title="{if $special.extension == 'webp' OR $special.extension == 'avif'}{$CONST.VARIATION}{else}{$CONST.PUBLISHED}{/if}: {$special.basename}, {$special.width}x{$special.height}px" data-pwidth="{$special.width}" data-pheight="{$special.height}"><span class="icon-image-of" aria-hidden="true">&#x22b7;</span><span class="visuallyhidden"> Image Of</span></a></li>
                    {/if}
                    {/foreach}
                    {else if NOT empty($file.sizeAVIF) && NOT empty($file.sizeWebp)}

                    <li class="special"><a class="media_fullsize media_prop button_link" href="{if $file.sizeAVIF < $file.sizeWebp}{$file.full_file_avif}{else}{$file.full_file_webp}{/if}" title="{$CONST.VARIATION}: {$file.full_file_avif|regex_replace:"~{$serendipityHTTPPath}uploads/{$file.path}.v/~":''}, {$file.dim_orig.0}x{$file.dim_orig.1}px" data-pwidth="{$file.dim_orig.0}" data-pheight="{$file.dim_orig.1}"><span class="icon-image-of" aria-hidden="true">&#x22b7;</span><span class="visuallyhidden"> Image Of</span></a></li>
                    {else if NOT empty($file.sizeWebp) && $file.sizeWebp < $file.size}

                    <li class="special"><a class="media_fullsize media_prop button_link" href="{$file.full_file_webp}" title="{$CONST.VARIATION}: {$file.full_file_webp|regex_replace:"~{$serendipityHTTPPath}uploads/{$file.path}.v/~":''}, {$file.dim_orig.0}x{$file.dim_orig.1}px" data-pwidth="{$file.dim_orig.0}" data-pheight="{$file.dim_orig.1}"><span class="icon-image-of" aria-hidden="true">&#x22b7;</span><span class="visuallyhidden"> Image Of</span></a></li>
                    {else if $file.is_image AND NOT $file.hotlink AND in_array($file.extension, ['jpg', 'jpeg', 'png']) AND (empty($file.full_file_webp) OR empty($file.full_thumb_webp))}

                    <li><a class="media_addvar button_link" href="?serendipity[adminModule]=images&amp;serendipity[adminAction]=variations&amp;serendipity[fid]={$file.id}{if isset($smarty.get.serendipity.page)}&amp;serendipity[page]={$smarty.get.serendipity.page}{/if}" title="{$CONST.MEDIA_CREATEVARS}"><span class="icon-plus" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MEDIA_CREATEVARS}</span></a></li>
                    {/if}
                {/if}

                </ul>
            {/if}

            </article>
{* singular media property page only *}
        {if NOT $media.enclose}

            <article class="media_file{if NOT empty($smarty.get.serendipity.adminAction) AND $smarty.get.serendipity.adminAction == 'properties'} mfile_prop{/if} media_enclose_no">
                <header>
                    <h3>{$file.realname}</h3>
                    <div>
                        <span class="block_level"><b>MIME-{$CONST.TYPE}:</b> {$file.mime}{if $file.realname != $file.diskname}, {$file.diskname}{/if}</span>
                        <span class="block_level"><b>{$CONST.SORT_ORDER_EXTENSION}:</b> {$file.extension}</span>
                        <ul class="media_file_meta dimensions plainList">
                            <li><b>{$CONST.SORT_ORDER_DATE}:</b> {if $file.authorid != 0}{$CONST.POSTED_BY} {$file.authorname} {/if}<span class="icon-clock" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.ON} </span> {$file.date|formatTime:DATE_FORMAT_SHORT}</li>
                        {if $file.hotlink}

                            <li><b>{$CONST.MEDIA_HOTLINKED}:</b> {$file.realfile}</li>
                        {elseif $file.is_image}

                            <li><b>{$CONST.IMAGE_SIZE}:</b> {$file.dimensions_width}x{$file.dimensions_height} px</li>
                            <li><b>{$CONST.THUMBNAIL_SIZE}:</b> {$file.dim.0|default:0}x{$file.dim.1|default:0} px</li>
                        {/if}

                        </ul>

                        <ul class="media_file_meta filesizes plainList">
                            <li><b>{$CONST.FILE_SIZE}:</b> {$file.nice_size} KB</li>
                        {if isset($file.nice_thumbsize) AND NOT $file.hotlink}

                            <li><b>{$CONST.THUMBFILE_SIZE}:</b> {$file.nice_thumbsize} KB</li>
                        {/if}
                        {* AVIF *}
                        {if NOT empty($file.nice_size_avif) AND NOT $file.hotlink}

                            <li><b>AVIF-{$CONST.FILE_SIZE}:</b> {$file.nice_size_avif} KB</li>
                        {/if}
                        {if $file.is_image AND NOT empty($file.nice_thumbsize_avif)}

                            <li><b>AVIF-{$CONST.THUMBFILE_SIZE|truncate:15}:</b> {$file.nice_thumbsize_avif} KB</li>
                        {/if}
                        {* WebP *}
                        {if NOT empty($file.nice_size_webp) AND NOT $file.hotlink}

                            <li><b>WebP-{$CONST.FILE_SIZE}:</b> {$file.nice_size_webp} KB</li>
                        {/if}
                        {if $file.is_image AND NOT empty($file.nice_thumbsize_webp)}

                            <li><b>WebP-{$CONST.THUMBFILE_SIZE|truncate:15}:</b> {$file.nice_thumbsize_webp} KB</li>
                        {/if}

                        </ul>
                    </div>
                </header>

                <input type="hidden" name="serendipity[mediaProperties][0][image_id]" value="{$file.image_id}">

                <section class="media_file_props">
                    <h4>{$CONST.MEDIA_PROP} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#dc3545" class="bi bi-pin-angle-fill" viewBox="0 0 16 16"><title id="title">Pinned</title><path d="M9.828.722a.5.5 0 0 1 .354.146l4.95 4.95a.5.5 0 0 1 0 .707c-.48.48-1.072.588-1.503.588-.177 0-.335-.018-.46-.039l-3.134 3.134a5.927 5.927 0 0 1 .16 1.013c.046.702-.032 1.687-.72 2.375a.5.5 0 0 1-.707 0l-2.829-2.828-3.182 3.182c-.195.195-1.219.902-1.414.707-.195-.195.512-1.22.707-1.414l3.182-3.182-2.828-2.829a.5.5 0 0 1 0-.707c.688-.688 1.673-.767 2.375-.72a5.92 5.92 0 0 1 1.013.16l3.134-3.133a2.772 2.772 0 0 1-.04-.461c0-.43.108-1.022.589-1.503a.5.5 0 0 1 .353-.146z"/></svg></h4>
                    {if $file.property_saved === false}<span class="msg_notice"><span class="icon-info-circled"></span> {$CONST.MEDIA_PROP_STATUS}</span>{/if}

                {foreach $file.base_property AS $prop_content}

                    <div class="form_{if $prop_content.type == 'textarea'}area{else}field{/if}">
                        <label for="mediaProperty{$prop_content@key}">{$prop_content.label}</label>
                    {if $prop_content.type == 'textarea'}

                        <textarea id="mediaProperty{$prop_content@key}" name="serendipity[mediaProperties][0][{$prop_content.title}]" rows="5">{$prop_content.val|escape}</textarea>
                    {elseif $prop_content.type == 'readonly'}
                        {$prop_content.val|escape}
                    {elseif $prop_content.type == 'input'}

                        <input id="mediaProperty{$prop_content@key}" name="serendipity[mediaProperties][0][{$prop_content.title}]" type="text" value="{$prop_content.val|escape}">
                    {/if}

                    </div>
                {/foreach}
                {if NOT $file.hotlink}

                    <fieldset class="media_properties_selects">
                      <legend> {$CONST.XOR} &nbsp;<span class="media_file_properties actions"><a class="media_show_info button_link" href="#media_select_props" title="Media properties select actions"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> Media properties selections info</span></a></span> </legend>
                      <div class="form_select">
                        <label for="newDir">{$CONST.FILTER_DIRECTORY}</label>
                        <input type="hidden" name="serendipity[mediaDirectory][0][oldDir]" value="{$file.path|escape}">
                        <select id="newDir" name="serendipity[mediaDirectory][0][newDir]">
                            <option{if empty($file.path)} selected="selected"{/if} value="">{$CONST.BASE_DIRECTORY}</option>
                        {foreach $media.paths AS $folder}

                            <option{if ($file.path == $folder.relpath)} selected="selected"{/if} value="{$folder.relpath}">{'&nbsp;'|str_repeat:($folder.depth*2)}{$folder.name}</option>{* * *}
                        {/foreach}

                        </select>
                      </div>
                    {if $file.is_image AND $media.resetperm}

                      <div class="form_select">
                        <label for="newFormat">{$CONST.FORMATS}</label>
                        <input type="hidden" name="serendipity[mediaFormat][0][oldMime]" value="{$file.mime}">
                        <select id="newFormat" name="serendipity[mediaFormat][0][newMime]">
                        {foreach $media.formats AS $format}{if $format.mime == 'image/webp' OR $format.mime == 'image/avif'}{assign "isvariation" true}{/if}

                            <option{if ($file.mime == $format.mime)} selected="selected"{/if} value="{$format.mime}">{$format.extension}</option>
                        {/foreach}

                        </select>
                      </div>
                    {/if}

                      <div id="media_select_props" class="media_select_props additional_info"><span class="msg_hint"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.MEDIA_PROPERTIES_SELECT_INFO_DESC}{if NOT empty($isvariation)}<br>{$CONST.MEDIA_PROPERTIES_FORMAT_VARIATIONS}{/if}</span></div>
                    </fieldset>
                {/if}

                </section>

                <section class="media_file_keywords">
                    <h4>{$CONST.MEDIA_KEYWORDS}</h4>

                    <ul class="clearfix plainList">
                    {foreach $file.base_keywords AS $keyword_cells}
                        {foreach $keyword_cells AS $keyword}
                        {if NOT empty($keyword.name)}

                        <li>
                            <input id="mediaKeyword{$keyword.name}" name="serendipity[mediaKeywords][0][{$keyword.name}]" type="checkbox" value="true"{if $keyword.selected} checked="checked"{/if}>
                            <label for="mediaKeyword{$keyword.name}">{$keyword.name|truncate:20:"&hellip;"}</label>
                        </li>
                        {/if}
                        {/foreach}
                    {/foreach}

                    </ul>
                </section>

                <section class="media_file_metadata clearfix">
                    <h4>EXIF/IPTC/XMP</h4>
                {foreach $file.metadata AS $meta_data}

                    <h5>{$meta_data@key}</h5>
                    {if is_array($meta_data)}

                    <dl class="clearfix">
                        {foreach $meta_data AS $meta_value}

                        <dt>{$meta_value@key|escape}</dt>
                        <dd>{if is_array($meta_value)}{$meta_value|print_r}{else}{$meta_value|formatTime:DATE_FORMAT_SHORT:false:$meta_value@key|escape|default:'&nbsp;'}{/if}</dd>
                        {/foreach}

                    </dl>
                    {else}

                    <p>{$meta_data|formatTime:DATE_FORMAT_SHORT:false:$meta_data@key}</p>
                    {/if}
                {/foreach}

                </section>
            {if $file.references}
                <section class="media_file_referer">

                    <h4>{$CONST.REFERER}</h4>

                    <ul>
                    {foreach $file.references AS $ref}

                        <li>({$ref.name|escape}) <a rel="nofollow" href="{$ref.link|escape}">{$ref.link|default:$CONST.NONE|escape}</a></li>
                    {/foreach}

                    </ul>
                </section>
            {/if}

            </article>
        {/if}
{/foreach}
