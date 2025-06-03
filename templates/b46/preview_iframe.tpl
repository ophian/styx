<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
{if $mode == 'preview'}
    <link rel="stylesheet" href="{serendipity_getFile file="b4/css/bootstrap.min.css"}" type="text/css">
{/if}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}" type="text/css">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css" type="text/css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}" type="text/css">
{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
    <style>.container { max-width: 100%; } .container a { padding-left: .5rem; } main { width: 100%; line-height: 1.8; padding: .25rem 0; } .save_preview_sizing { visibility: hidden; display: none; } .msg_error { margin: 0; }</style>
    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <style>.container { max-width: 100%; } .mb-4, .my-4 { margin-bottom: 0 !important; } .post_content { display: initial; } figure > .serendipity_imageComment_img { border: 0 none; } .serendipity_entrypaging.mobile { display: none; visibility: hidden; }</style>
{/if}
{serendipity_hookPlugin hook="backend_header" hookAll="true"}
    <script>
        window.onload = function() {ldelim}
            var thisFrame = parent.document.getElementById('serendipity_iframe');
            if (typeof thisFrame !== 'undefined' && thisFrame !== null) {ldelim}
                var frameheight = document.querySelector('html').offsetHeight;
                thisFrame.style.height = frameheight + 'px';
                thisFrame.scrolling    = 'no';
                thisFrame.style.border = 0;
                thisFrame.style.overflow = 'hidden';
            {rdelim}
        {rdelim}
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
            <main class="col-xs-12 col-lg-12">
        {if $mode == 'preview'}

                {$preview}
        {elseif $mode == 'save'}

                <div class="{$mode}_preview_sizing"></div>
                {if NOT empty($updertHooks)}<div class="{$mode}_updertH">{$updertHooks}</div>{/if}
            {if $res}
                <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
            {else}
                {* PLEASE NOTE: This is for case new entry first save only! *}
                {if isset($lastSavedEntry) AND (int)$lastSavedEntry}

                <script>
                    window.onload = function() {ldelim}
                        parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                    {rdelim};
                </script>
                {/if}
                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_SAVED}</span>
                <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
            {/if}
        {/if}

            </main>
        </div>
    </div>

{if $mode == 'preview'}
    <script src="{serendipity_getFile file="js/theme.js"}"></script>
{/if}
</body>
</html>