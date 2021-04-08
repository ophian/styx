<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{* BOOTSTRAP CORE CSS *}
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
{* S9Y CSS *}
{if $head_link_stylesheet_frontend}{* >= s9y 2.0.2 *}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css">
{/if}
{* CUSTOM FONTS *}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
{if $template_option.use_googlefonts}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400" rel="stylesheet" type="text/css">
{/if}
{* ADDTIONAL COLORSET & SKIN STYLESHEETS - INCLUDED SETS ARE LOADED VIA CONFIG *}
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
    <main id="maincontent" class="container content" role="main" style="margin: 0 auto;">
        <div class="row">
            <div class="col-md-9">
                {if $mode == 'preview'}
                    {$preview}
                {elseif $mode == 'save'}
{if isset($lastSavedEntry) && (int)$lastSavedEntry}

                <script type="text/javascript">
                    window.onload = function() {ldelim}
                        parent.document.forms['serendipityEntry']['serendipity[id]'].value = "{$lastSavedEntry}";
                    {rdelim};
                </script>
{/if}
                    {$updertHooks}
                    {if $res}
                        <span class="alert alert-danger"><span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
                    {else}
                        <span class="alert alert-success"><span class="fa-stack text-success" aria-hidden="true"><i class="far fa-smile fa-2x"></i></span> {$CONST.ENTRY_SAVED}. &nbsp;&nbsp; <a class="btn btn-md btn-default btn-theme" href="{$entrylink}" target="_blank">{$CONST.VIEW_ENTRY}</a></span>
                    {/if}
                {/if}
            </div>
        </div>
        <!-- Filed by theme "{$template}" -->
    </main>

{if $mode == 'preview'}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/timeline.js"></script>

<!--[if lt IE 9]>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/respond.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/html5shiv.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/placeholder-IE-fixes.js"></script>
<![endif]-->
{/if}

</body>
</html>
