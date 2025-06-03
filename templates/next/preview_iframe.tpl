<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
{if $template_option.webfonts == 'osans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'ssans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:400italic,700italic,400,700" type="text/css">
{elseif $template_option.webfonts == 'rsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'lsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'mserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Merriweather:400,400italic,700,700italic" type="text/css">
{elseif $template_option.webfonts == 'dserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic" type="text/css">
{/if}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}" type="text/css">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css" type="text/css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}" type="text/css">

{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/scripts/modernizr/modernizr.js"></script>
{/if}

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

    {if $mode == 'save'}{* overwrite Next style.css conflicts or set *}
    <style>
        html { padding: 0; background-color: #fcfcfc; }
        body { margin: 0; padding: .5em 0; border: 0px none; width: 100%; }
        #primary { padding: 0; }
        .save_updertH { margin-left: .5em; }
    </style>
    {/if}
    {if $mode == 'preview'}{* overwrite Next style.css conflicts *}
    <style>
        html { padding: 0; }
        .serendipity_entry, .post { max-width: 98%; margin: 0 1%; }
    </style>
    {/if}
</head>

<body class="{$mode}_preview_body{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} {$template_option.webfonts}{/if}">
    <div id="main" class="clearfix {$mode}_preview_container">
        <main id="primary" class="{$mode}_preview_content">
            <div class="clearfix">
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
<!-- Filed by theme "{$template}" -->

{if $mode == 'preview'}
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/scripts/master.js"></script>
{/if}

</body>
</html>
