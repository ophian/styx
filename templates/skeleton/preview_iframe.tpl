<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{if $template_option.use_googlefonts}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,300,600">
{/if}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}">

{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{/if}
    <script>window.onload = function() {ldelim}
        parent.document.getElementById('serendipity_iframe').style.height = document.querySelector('html').offsetHeight + 'px';
        parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
        parent.document.getElementById('serendipity_iframe').style.border = 0;
        parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
    {rdelim}
    </script>
    <style> .save_preview_content, .preview_preview_content { margin: .5em; } </style>
</head>
<body class="{$mode}_preview_body">
    <main class="{$mode}_preview_content" role="main">
        <div class="clearfix">
    {if $mode == 'preview'}
        {$preview}
    {elseif $mode == 'save'}
            <div class="{$mode}_preview_sizing"></div>
            {$updertHooks}
        {if $res}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
        {else}
            {* PLEASE NOTE: This is for case new entry first save only! *}
            {if isset($lastSavedEntry) AND (int)$lastSavedEntry}

            <script type="text/javascript">
                window.onload = function() {ldelim}
                    parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                {rdelim};
            </script>
            {/if}

            <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_SAVED}</span>
            <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
        {/if}
    {/if}

        </div>
        <!-- Filed by theme "{$template}" -->
    </main>
</body>
</html>
