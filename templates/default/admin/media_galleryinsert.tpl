
{if isset($perm_denied) AND $perm_denied}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED}</span>
{else}
    <!-- MEDIA GALLERY SELECTION FINISHER -->
    {if $media.fast_select AND is_array($media.files) AND isset($jsmedia)}
    <script>
    {if $media.supportsWebP AND $media.addMediaPictureSubmitFnc}
        serendipity.mediaPictureSubmit();
        {* console.log('mediaSubmitter true'); *}
    {/if}
        serendipity.serendipity_imageGallerySelector_done('{$media.mediaTextarea|escape}', {$jsmedia});
    </script>
    {/if}
{/if}

