{if $is_xhtml}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{else}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
{/if}
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
    <head>
        <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
        <meta http-equiv="Content-Type" content="text/html; charset={$head_charset}" />
        <meta name="generator" content="Serendipity v.{$serendipityVersion}" />
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
        {if $mode == 'save'}}

        <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
        {/if}

        <script type="text/javascript">
           window.onload = function() {ldelim}
             parent.document.getElementById('serendipity_iframe').style.height = document.getElementById('content').offsetHeight
                                                                               + parseInt(document.getElementById('content').style.marginTop)
                                                                               + parseInt(document.getElementById('content').style.marginBottom)
                                                                               + 'px';
             parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
             parent.document.getElementById('serendipity_iframe').style.border = 0;
           {rdelim}
        </script>
    </head>

  <body id="preview_iframe_body"{if $template_option.webfonts != 'none'} class="{$template_option.webfonts}"{/if}>
    <div id="wrapper" style="border: 0 none; max-width: 100%; min-width: 100%; margin: 0px;">
        <div id="content" style="margin: 0px; padding: 1em 0.5em; width: 98.75%;">
        {if $mode == 'preview'}
            <div class="clearfix">
        {elseif $mode == 'save'}
            <div class="clearfix">
                <div style="float: left; height: 75px"></div>
                {$updertHooks}
            {if $res}
                <div class="serendipity_msg_error">{$CONST.ERROR}: <b>{$res}</b></div>
            {else}
                {if isset($lastSavedEntry) && (int)$lastSavedEntry}

                    <script type="text/javascript">
                        window.onload = function() {ldelim}
                            parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                        {rdelim};
                    </script>
                {/if}

                <div class="serendipity_msg_notice"> {$CONST.ENTRY_SAVED}</div>
                <a href="{$entrylink}" target="_blank">{$CONST.VIEW}</a>
            {/if}
        {/if}
            {$preview}
            </div>
        </div>
    </div>

  </body>
</html>
