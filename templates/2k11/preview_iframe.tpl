<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{if isset($template_option.webfonts)}
{if $template_option.webfonts == 'droid'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Sans:400,700" type="text/css">
{elseif $template_option.webfonts == 'ptsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'osans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'cabin'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Cabin:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'ubuntu'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'dserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic" type="text/css">
{/if}
{/if}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css" type="text/css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}" type="text/css">
{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}

    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <script src="{serendipity_getFile file="js/modernizr-2.7.1.min.js"}"></script>
{/if}
{* very long entry previews still have an (end) overlap of height ~70px *}
    <script type="text/javascript">
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

<body class="{$mode}_preview_body{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} {$template_option.webfonts}{/if}">
    <div id="page" class="clearfix container {$mode}_preview_container">
        <div class="clearfix{if isset($leftSidebarElements) AND $leftSidebarElements > 0 AND $rightSidebarElements > 0} col3{elseif  isset($leftSidebarElements) AND $leftSidebarElements > 0 AND $rightSidebarElements == 0} col2l{else} col2r{/if}">
            <main id="content" class="{$mode}_preview_content">
            {if $mode == 'preview'}
                <div class="clearfix">
                {$preview}
            {elseif $mode == 'save'}
                <div class="clearfix">
                    <div class="{$mode}_preview_sizing"></div>
                    {$updertHooks}
                {if $res}
                    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
                {else}
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
            </main>
        </div>
    </div>
<!-- Filed by theme "{$template}" -->
</body>
</html>
