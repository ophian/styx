
{if $perm_denied}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PERM_DENIED}</span>
{else}
    <!-- MEDIA GALLERY SELECTION FINISHER -->
    {if $media.fast_select && is_array($media.files) && isset($jsmedia)}
    <script>
        serendipity.serendipity_imageGallerySelector_done('{$media.mediaTextarea|escape}', {$jsmedia});
    </script>
    {/if}
{/if}

