{* It is only possible to send the item selected IDs (simple like this, which is ok since we can do that later) *}
{foreach $media.files AS $file}

            <article id="media_{$file.id}" class="media_file media_gal {if $media.multiperm}manage {/if}{cycle values="odd,even"}">
                <header class="clearfix">

                    <div class="form_check">
                        <input id="multicheck_image{$file.id}" class="multicheck" name="serendipity[multiSelect][]" type="checkbox" value="{$file.id}" data-multixid="media_{$file.id}">
                        <label for="multicheck_image{$file.id}" class="visuallyhidden">{$CONST.TOGGLE_SELECT}</label>
                    </div>

                    <h3 title="{$file.diskname}">{$file.diskname|truncate:38:"&hellip;":true}{if $file.orderkey != ''}: {$file.orderkey|escape}{/if}</h3>
                    {if $file.authorid != 0}<span class="author block_level">{$file.authorname}</span>{/if}

                </header>

                <div class="clearfix media_file_wrap">
                    <div class="media_file_preview media_file_pregal">
                    {if $file.hotlink}

                        <img src="{$file.path}" title="{$file.name}" alt="{$file.realname}">
                    {elseif empty($file.full_thumb)}

                        <picture>
                            {if isset($file.sizeAVIF) && $file.sizeAVIF > 1000 && $file.sizeAVIF <= $file.sizeWebp}<source type="image/avif" srcset="{$file.full_file_avif|default:''}" class="ml_preview_img" alt="{$file.diskname}">{/if}
                            <source type="image/webp" srcset="{if $file.sizeWebp <= $file.size}{$file.full_file_webp|default:''}{/if}" class="ml_preview_img" alt="{$file.diskname}">
                            <img src="{$file.full_file}" title="{$file.name}" alt="{$file.diskname}">
                        </picture>
                    {else}

                        <picture>
                            {if isset($file.thumbSizeAVIF) && $file.thumbSizeAVIF > 1000 && $file.thumbSizeAVIF <= $file.thumbSizeWebp}<source type="image/avif" srcset="{$file.full_thumb_avif|default:''}" class="ml_preview_img" alt="{$file.diskname}">{/if}
                            <source type="image/webp" srcset="{$file.full_thumb_webp|default:''}" class="ml_preview_img" alt="{$file.diskname}">
                            <img src="{$file.full_thumb}" title="{$file.name}" alt="{$file.diskname}">
                        </picture>
                    {/if}

                    </div>
                </div>
            </article>

{/foreach}
