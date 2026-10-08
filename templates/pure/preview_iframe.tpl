<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Anti-Flash Theme Script (Executes before rendering body to prevent flickering) -->
    <script>
        (function() {
            const forceLightMode = {$forceLightMode|default:'false'};
            const darkModeSetting = sessionStorage.getItem('dark_mode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (darkModeSetting === 'dark' || (darkModeSetting === null && !forceLightMode && prefersDark)) {
                document.documentElement.setAttribute('data-dark-theme', 'dark');
            } else {
                document.documentElement.removeAttribute('data-dark-theme');
            }
        })();
    </script>
{if $head_link_stylesheet_frontend}
    <link rel="stylesheet" href="{$head_link_stylesheet_frontend}" type="text/css">
{else}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$serendipityRewritePrefix}serendipity.css" type="text/css">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/preview_iconizr.css'}" type="text/css">
</head>
<body class="{$mode}_preview_body">
    <main id="content" class="{$mode}_preview_content">
{if $mode == 'preview'}
        <div class="preview_entry">
{$preview}
        </div>
{elseif $mode == 'save'}
        <div class="{$mode}_preview_sizing"></div>
{if !empty($updertHooks)}
        <div class="{$mode}_updertH">{$updertHooks}</div>
{/if}
{if $res}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> <b>{$CONST.ERROR}:</b><br> {$res}</span>
{else}
{* PLEASE NOTE: This is for case new entry first save only! *}
{if isset($lastSavedEntry) AND (int)$lastSavedEntry}

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const entryIdInput = parent?.document?.forms?.['serendipityEntry']?.['serendipity[id]'];
                if (entryIdInput) {
                    entryIdInput.value = "{$lastSavedEntry}";
                }
            });
        </script>
{/if}

        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_SAVED}</span>
        <a href="{$entrylink}" target="_blank" rel="noopener">{$CONST.VIEW}</a>
{/if}
{/if}
    </main>

</body>
</html>