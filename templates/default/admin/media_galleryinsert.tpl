
{if isset($perm_denied) AND $perm_denied}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED}</span>
{else}
    <!-- MEDIA GALLERY SELECTION FINISHER -->
    {if $media.fast_select AND is_array($media.files) AND isset($jsmedia)}
    <script>
    {if $media.supportsWebP}
        mediaPictureSubmit();{* Is odd, but better than nothing, since it can't distinguish between both gallery submit buttons .. I need to figure out how to get that the second submit button only was clicked! *}
    {/if}
        serendipity.serendipity_imageGallerySelector_done('{$media.mediaTextarea|escape}', {$jsmedia});
    </script>
    {/if}
{/if}

