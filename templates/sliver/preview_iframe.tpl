<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>

{if $template_option.webfonts == 'droid'}
    <link  rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Sans:400,700">
{elseif $template_option.webfonts == 'ptsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'osans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'cabin'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Cabin:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'ubuntu'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:400,400italic,700,700italic">
{/if}

    <link rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}{$template}/favicon.ico">
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}{$template}/css/normalize.css">
    {* this is the default fallback and additional plugin stylesheet generated into serendipity.css - DO NOT USE href="{$head_link_stylesheet}" here, since we want this templates styles, not 2k11s!! *}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
    {* this is the end of boilerplate style and mixed print styles *}
{if $mode == 'preview'}

    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}{$template}/css/endandprint.css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}">
{if $mode == 'save'}

    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{/if}

	<script type="text/javascript">
        window.onload = function() {ldelim}
            var frameheight = document.querySelector('html').offsetHeight{* if $mode == 'preview'}-14{/if *};
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
        {rdelim}
    </script>
  </head>
  <body id="admin_preview_iframe_body"{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} class="{$mode}_preview_body {$template_option.webfonts}"{/if}>
    <div id="admin_preview_iframe_wrapper" class="{$mode}_preview_container">
        <div id="content" class="clearfix admin_preview_iframe_content {$mode}_preview_content">{* we have to leave the inline style part here in order to the upper ajax working *}
            <div class="clearfix">
        {if $mode == 'preview'}
            {$preview}
        {elseif $mode == 'save'}
                <div class="{$mode}_preview_sizing"></div>
                {$updertHooks}
            {if $res}
                <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
            {else}
                {if isset($lastSavedEntry) AND (int)$lastSavedEntry}

                    <script type="text/javascript">
                        window.onload = function() {ldelim}
                            //window.parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                            parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                        {rdelim};
                    </script>
                {/if}

                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_SAVED}</span>
                <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
            {/if}
        {/if}
            </div>
        </div>
    </div>
{if $mode == 'preview'}

    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/ckeditor_highlight.pack.js"></script>
{/if}

  </body>
</html>
