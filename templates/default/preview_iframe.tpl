{if $is_xhtml}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
           "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{else}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
           "http://www.w3.org/TR/html4/loose.dtd">
{/if}

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
    <head>
        <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
        <meta http-equiv="Content-Type" content="text/html; charset={$head_charset}" />
        <meta name="generator" content="Serendipity v.{$serendipityVersion}" />
    {if $head_link_stylesheet_frontend}
        <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">                                
    {else}
        <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
    {/if}
    {if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}
        <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
    {/if}

        <script type="text/javascript">
           window.onload = function() {ldelim}
             parent.document.getElementById('serendipity_iframe').style.height = document.getElementById('mainpane').offsetHeight
                                                                               + parseInt(document.getElementById('mainpane').style.marginTop)
                                                                               + parseInt(document.getElementById('mainpane').style.marginBottom)
                                                                               + 'px';
             parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
             parent.document.getElementById('serendipity_iframe').style.border = 0;
           {rdelim}
        </script>
    </head>

    <body style="padding: 0px; margin: 0px;">
        <div id="mainpaine" style="border: 0 none; max-width: 100%; min-width: 100%; margin: 0px;">
            <div id="content" style="margin: 0px; padding: 1em 0.5em; width: 98.75%;">
        {if $mode == 'save'}
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

    </body>
</html>
