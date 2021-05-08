<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
{serendipity_hookPlugin hook="backend_header" hookAll="true"}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}">

{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{/if}

    <script type="text/javascript">
        window.onload = function() {ldelim}
            var frameheight = document.querySelector('html').offsetHeight;
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
            parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
        {rdelim}
    </script>
</head>
<body class="{$mode}_preview_body">
    <div id="main" class="clearfix {$mode}_preview_container">
        <main id="content" class="{$mode}_preview_content">
        {if $mode == 'preview'}
            <div class="preview_entry">
                {$preview}
            </div>
        {elseif $mode == 'save'}
            <div class="{$mode}_preview_sizing"></div>
            {if !empty($updertHooks)}<div class="{$mode}_updertH">{$updertHooks}</div>{/if}
            {if $res}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
            {else}
                {* PLEASE NOTE: This is for case new entry first save only! *}
                {if isset($lastSavedEntry) AND (int)$lastSavedEntry}

            <script type="text/javascript">
                window.onload = function() {
                    parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                };
            </script>
                {/if}

            <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_SAVED}</span>
            <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
            {/if}
        {/if}
        </main>
    </div>

{if $mode == 'preview'}
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}theme.js"></script>
{/if}

</body>
</html>