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
{if $autosaved_iframe}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (window.parent.indexedDB) {
                    window.parent.serendipity.eraseEntryEditorCache();
                }
            });
        </script>
{/if}
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
{if $mode == 'preview' && $darkmode == true}

    <script>
    (function () {
      const IMAGE_EXTENSIONS = /\.(avif|webp|png|jpe?g|gif|bmp)(\?.*)?$/i;
      let overlay = null;
      let overlayImg = null;
      let previousFocus = null;

      function isImageLink(anchor) {
        if (!anchor || !anchor.href) return false;
        try {
          const url = new URL(anchor.href, window.location.href);
          return IMAGE_EXTENSIONS.test(url.pathname);
        } catch {
          return IMAGE_EXTENSIONS.test(anchor.getAttribute("href") || "");
        }
      }

      function ensureOverlay() {
        if (overlay) return;

        const style = document.createElement("style");
        style.id = "preview-image-viewer-style";
        style.textContent = `
          .preview-img-backdrop {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            background: rgba(12, 14, 18, 0.92);
            backdrop-filter: blur(4px);
          }
          .preview-img-backdrop[hidden] {
            display: none;
          }
          .preview-img-box {
            position: relative;
            max-width: 95vw;
            max-height: 92vh;
            display: flex;
            align-items: center;
            justify-content: center;
          }
          .preview-img-box img {
            max-width: 95vw;
            max-height: 92vh;
            width: auto;
            height: auto;
            object-fit: contain;
            background: transparent;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.65);
            border-radius: 4px;
          }
          .preview-img-close {
            position: fixed;
            top: 1rem;
            right: 1rem;
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 999px;
            background: rgba(24, 28, 36, 0.9);
            color: #f3f4f6;
            font-size: 1.25rem;
            line-height: 1;
            cursor: pointer;
          }
          .preview-img-close:hover {
            background: rgba(55, 65, 81, 0.95);
          }
        `;

        overlay = document.createElement("div");
        overlay.className = "preview-img-backdrop";
        overlay.hidden = true;
        overlay.setAttribute("role", "dialog");
        overlay.setAttribute("aria-modal", "true");
        overlay.setAttribute("aria-label", "Imagepreview");

        const closeBtn = document.createElement("button");
        closeBtn.type = "button";
        closeBtn.className = "preview-img-close";
        closeBtn.setAttribute("aria-label", "Close Preview");
        closeBtn.textContent = "×";

        const box = document.createElement("div");
        box.className = "preview-img-box";

        overlayImg = document.createElement("img");
        overlayImg.alt = "";

        box.appendChild(overlayImg);
        overlay.appendChild(closeBtn);
        overlay.appendChild(box);

        document.head.appendChild(style);
        document.body.appendChild(overlay);

        closeBtn.addEventListener("click", closeViewer);
        overlay.addEventListener("click", function (event) {
          if (event.target === overlay) {
            closeViewer();
          }
        });
        document.addEventListener("keydown", function (event) {
          if (event.key === "Escape" && !overlay.hidden) {
            closeViewer();
          }
        });
      }

      function openViewer(src, altText) {
        ensureOverlay();
        previousFocus = document.activeElement;
        overlayImg.src = src;
        overlayImg.alt = altText || "";
        overlay.hidden = false;
        const closeBtn = overlay.querySelector(".preview-img-close");
        if (closeBtn) closeBtn.focus();
      }

      function closeViewer() {
        if (!overlay || overlay.hidden) return;
        overlay.hidden = true;
        overlayImg.src = "";
        if (previousFocus && typeof previousFocus.focus === "function") {
          previousFocus.focus();
          previousFocus = null;
        }
      }

      document.addEventListener("click", function (event) {
        if (event.defaultPrevented || event.button !== 0) return;
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;

        const anchor = event.target.closest("a");
        if (!isImageLink(anchor)) return;

        event.preventDefault();
        const thumb = anchor.querySelector("img");
        const altText = thumb ? thumb.alt : "";
        openViewer(anchor.href, altText);
      });
    })();
    </script>
{/if}

</body>
</html>