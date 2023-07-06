<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
{* BOOTSTRAP CORE CSS *}
    <link rel="stylesheet" href="{serendipity_getFile file="b4/css/bootstrap.min.css"}" type="text/css">
{* S9Y CSS *}
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}" type="text/css">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css" type="text/css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}" type="text/css">
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
    <script type="text/javascript">
        window.onload = function() {
            var frameheight = document.querySelector('html').offsetHeight;
            parent.document.getElementById('serendipity_iframe').style.height = frameheight + 'px';
            parent.document.getElementById('serendipity_iframe').scrolling    = 'no';
            parent.document.getElementById('serendipity_iframe').style.border = 0;
            parent.document.getElementById('serendipity_iframe').style.overflow = 'hidden';
        }
    </script>
</head>
<body>

    <main id="maincontent" class="container" role="main" style="margin: 0 auto;{if $mode == 'preview'} width: 100%; max-width: fit-content; padding: .5em;{/if}">
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
    <script src="{serendipity_getFile file="b4/js/bootstrap.min.js"}"></script>
    <script src="{serendipity_getFile file="js/clean-blog.min.js"}"></script>
{/if}

</body>
</html>
