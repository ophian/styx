<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}">
{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/scripts/modernizr/modernizr.js"></script>
{/if}
{serendipity_hookPlugin hook="backend_header" hookAll="true"}
    <script>
        window.onload = function() {ldelim}
            var isChrome = !!window.chrome || !!window.opera || /*@cc_on!@*/false || !!document.documentMode;{* LAST 2 IE 6-11 *}

            if (isChrome) {
                var frameheight = document.querySelector('html').offsetHeight{if $mode == 'preview'}-24{/if};
            } else {
                var frameheight = document.querySelector('html').offsetHeight;
            }
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
            parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
        {rdelim}
    </script>
</head>
<body>
    <div class="container{if $template_option.bs_fluid}-fluid{/if}">
        <div class="row">
        {if $mode == 'preview'}
            <main class="col-xs-12 col-lg-8">
        {elseif $mode == 'save'}
            <main class="col-xs-12 col-lg-8">
                <div style="float: left; height: 75px"></div>
                {$updertHooks}
            {if $res}
                <div class="serendipity_msg_important">{$CONST.ERROR}: <b>{$res}</b></div>
            {else}
                {* PLEASE NOTE: This is for case new entry first save only! *}
                {if isset($lastSavedEntry) AND (int)$lastSavedEntry}

                <script>
                    window.onload = function() {ldelim}
                        parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                    {rdelim};
                </script>
                {/if}
                <span class="msg_success"><span class="icon-ok-circled"></span> {$CONST.ENTRY_SAVED}</span>
                <a href="{$entrylink}" target="_blank">{$CONST.VIEW}</a>
            {/if}
        {/if}
            {$preview}
            </main>
        </div>
    </div>

    <script src="{serendipity_getFile file="scripts/theme.js"}"></script>
</body>
</html>