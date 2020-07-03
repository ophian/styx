<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{* BOOTSTRAP CORE CSS *}
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
{* S9Y CSS *}
{if $head_link_stylesheet_frontend}{* >= s9y 2.0.2 *}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}">
{* CUSTOM FONTS *}
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
{if $template_option.use_googlefonts}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600,800|Lora:400,400italic" rel="stylesheet" type="text/css">
{/if}
{if $mode == 'save'}{* we need this for modernizr.indexDB cleaning up autosave entry modifications *}

    <script src="{serendipity_getFile file="admin/js/modernizr.min.js"}"></script>
{else}
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
{/if}
    <script>
        window.onload = function() {ldelim}
            parent.document.getElementById('serendipity_iframe').style.height = document.getElementById('maincontent').offsetHeight
                                                                              + parseInt(document.getElementById('maincontent').style.marginTop)
                                                                              + parseInt(document.getElementById('maincontent').style.marginBottom)
                                                                              + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
            parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
        {rdelim}
    </script>
</head>
<body>

    <main id="maincontent" class="container" role="main" style="margin: 0 auto;{if $mode == 'preview'} width: 100%; padding: .5em;{/if}">
        <div class="row">
            <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                {if $mode == 'preview'}
                    {$preview}
                {elseif $mode == 'save'}
                    {$updertHooks}
                    {if $res}
                        <span class="alert alert-danger"><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-exclamation fa-stack-1x"></i></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
                    {else}
                    {if isset($lastSavedEntry) && (int)$lastSavedEntry}

                        <script>
                            window.onload = function() {ldelim}
                                parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                            {rdelim};
                        </script>
                    {/if}

                        <span class="alert alert-success"><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-check fa-stack-1x"></i></span> {$CONST.ENTRY_SAVED}</span>
                        <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
                    {/if}
                {/if}
            </div>
        </div>
    </main>
    <!-- Filed by theme "{$template}" -->

{if $mode == 'preview'}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/clean-blog.js"></script>
{/if}

</body>
</html>
