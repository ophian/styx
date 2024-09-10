{if $init === false}

<script>
    let styxPlugs  = typeof(styxConcatenatedToolbarPlugins) !== 'undefined' ? styxConcatenatedToolbarPlugins : '';
    let editorLang = '{$lang}';
</script>
<script src="{$serendipityHTTPPath}templates/_assets/prism/prism.js" data-manual></script>
<script src="{$serendipityHTTPPath}templates/default/admin/js/commonEditor.js"></script>
<script src="{$serendipityHTTPPath}templates/_assets/tinymce6/js/tinymce/tinymce.min.js"></script>
{/if}

<script>
    // wait for plugin nuggets, since often set/thrown before
    document.addEventListener("DOMContentLoaded", (event) => {
      // A pre load content filter for old db stored entry data
      var currentEditor = document.getElementById("{$item}");
      var html = currentEditor.defaultValue;
      if (null !== html) {
        html = html.replaceAll(/<\/p>\s*<br[/ ]*>/ig, '</p>'); // replace </p><br> with </p>
        // now we want to nuke empty elements of p div or pre - preferable at the very ends of textarea content html
        html = html.replaceAll(/<(p|div|pre)[^>]*>\s*<\/\1>/ig, ''); // remove empty tags !!! alle oder gar nicht

        currentEditor.defaultValue = html;
      }

      tinymce.init({
        selector: '#{$item}',
        setup: (editor) => {},
          ...commonConfig
      });
    });

    $(window).on('load', function () { $('html, body').animate({ scrollTop: 0 }, 'smooth'); });
</script>
