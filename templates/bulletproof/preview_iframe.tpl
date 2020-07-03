{if $is_xhtml}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{else}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
{/if}
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
    <head>
        <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
        <meta http-equiv="Content-Type" content="text/html; charset={$head_charset}" />
        <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}" />
        <link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}{$templatePath}{$template}/base.css" />
        {if $head_link_stylesheet_frontend}
        <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
        {else}
        <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
        {/if}

        <!--[if IE 6]>
        <link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}{$templatePath}{$template}/ie6.css" />
        <![endif]-->
        <!--[if IE 7]>
        <link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}{$templatePath}{$template}/ie7.css" />
        <![endif]-->
        <!-- additional colorset stylesheet -->
        <link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}{$templatePath}{$template}/{$template_option.colorset}_style.css" />

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

  <body id="preview_iframe_body" class="{$mode}_preview_body{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} {$template_option.webfonts}{/if}">
    <div id="wrapper" class="{$mode}_preview_container">
        <div id="content" class="{$mode}_preview_content">
            <div class="clearfix">
        {if $mode == 'preview'}
            {$preview}
        {elseif $mode == 'save'}
                <div class="{$mode}_preview_sizing"></div>
                {$updertHooks}
            {if $res}
                <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
            {else}
                {if isset($lastSavedEntry) && (int)$lastSavedEntry}

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
        </div>
    </div>
    <!-- Filed by theme "{$template}" -->

  </body>
</html>
