<!doctype html>
<!--[if IE 8 ]>    <html class="no-js lt-ie9" lang="{$lang}"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="{$lang}"> <!--<![endif]-->
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
{if $template_option.webfonts == 'osans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'ssans'}
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400italic,700italic,400,700">
{elseif $template_option.webfonts == 'rsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'lsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'mserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Merriweather:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'dserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic">
{/if}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">                                
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
<!--[if lte IE 8]>
    <link rel="stylesheet" href="{serendipity_getFile file="oldie.css"}">
<![endif]-->
{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}

    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/scripts/modernizr/modernizr.js"></script>
{/if}

    <script type="text/javascript">
        window.onload = function() {ldelim}
            parent.document.getElementById('serendipity_iframe').style.height = document.getElementById('main').offsetHeight
                                                                              + parseInt(document.getElementById('main').style.marginTop)
                                                                              + parseInt(document.getElementById('main').style.marginBottom)
                                                                              + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
        {rdelim}
    </script>
    {if $mode == 'save'}{* overwrite next style.css conflicts *}
    <style>
        html { padding:0; background-color: #fcfcfc; }
        body { margin: 0px; padding: 0.5em 0px; border: 0px none; width: 100%; }
        #primary { padding:0; }
    </style>
    {/if}
</head>

<body{if $template_option.webfonts != 'none'} class="{$template_option.webfonts}"{/if}>
    <div id="main" class="clearfix" style="padding: 0; margin: 5px auto; width: 98%;">
        <main id="primary">
        {if $mode == 'save'}

            <div style="float: left; height: 75px"></div>
            <div class="clearfix">
                {$updertHooks}
            {if $res}

            <span class="msg-error"><span class="icon-attention-circled"></span> {$CONST.ERROR}: <b>{$res}</b></span>
            {else}
                {* PLEASE NOTE: This is for case new entry first save only! *}
                {if isset($lastSavedEntry) && (int)$lastSavedEntry}

            <script type="text/javascript">
                window.onload = function() {ldelim}
                    parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                {rdelim};
            </script>
                {/if}

            <span class="msg-success"><span class="icon-ok-circled"></span> {$CONST.ENTRY_SAVED}</span>
            <a href="{$entrylink}" target="_blank">{$CONST.VIEW}</a>
            {/if}

            </div>
        {/if}
                {$preview}

        </main>
    </div>

{if $mode == 'preview'}
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/scripts/master.js"></script>
{/if}

</body>
</html>
