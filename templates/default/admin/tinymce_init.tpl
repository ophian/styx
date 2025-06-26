{if $init === false}

<script>
    function prepare_tbarArr(a) {
        let plugItems = ['buttons', 'extras'];
        a.forEach((item) => {
            plugItems.push(item);
        });
        return plugItems;
      }

    function pluginArrToStr(p) {
        p.forEach((item) => {
          if (styxPlugs.includes(item) === false) {
            styxPlugs += '\'' + item + '\', ';
          }
        });
        return styxPlugs;
      }

    // init custom button arrays
    let styxpluginbuttons = [];
    let styxpluginnames = [];

    let styxcustomplugins = '';
    let styxButtonHooks = '';

    let styxPlugs  = typeof(styxcustomplugins) !== 'undefined' ? styxcustomplugins : '';
    let editorLang = '{$lang}';
</script>
<script src="{$serendipityHTTPPath}templates/_assets/prism/prism.js" data-manual></script>
<script src="{$serendipityHTTPPath}templates/default/admin/js/commonEditor.js"></script>
<script src="{$serendipityHTTPPath}templates/_assets/tinymce6/js/tinymce/tinymce.min.js"></script>
{/if}

<script>
    // reset both for 2cd (next) run
    styxpluginnames = [];
    styxpluginbuttons = [];

    styxpluginbuttons.push('myCustomToolbarButton');

{foreach $buttons AS $button}
{if NOT empty($button.css) AND $run == 1}{$styxpluginbuttonstyles[{$button@key}] = "{$button.css}"}{/if}
        tinymce.PluginManager.add('{$button.id}', (editor, url) => {
          editor.ui.registry.addIcon('open{$button.id}', '{$button.svg}');
          /* Add a button that opens a window */
          editor.ui.registry.addButton('{$button.id}_ToolbarButton', {
            icon: 'open{$button.id}',
            tooltip: '{$button.name}',
            onAction: () => {
              ( {$button.javascript} () )
            },
          });
          /* Adds a menu item, which can then be included in any menu via the menu/menubar configuration */
          editor.ui.registry.addMenuItem('{$button.name}_MenuItem', {
            icon: 'open{$button.id}',
            text: '{$button.name}',
            onAction: () => {
              ( {$button.javascript} () )
            }
          });
          /* Return the metadata for the styxImage plugin */
          return {
            getMetadata: () => ({
              name: 'Add Styx {$button.name}',
              url: url
            })
          };
        });

        styxpluginnames.push('{$button.id}');
        styxpluginbuttons.push('{$button.id}_ToolbarButton');

{/foreach}

    // ES6 has a native object Set to store unique values. The constructor of Set takes an iterable object, like an Array, and the spread operator ... transform the set back into an Array. 
    styxpluginnames = [... new Set(styxpluginnames)];
    styxpluginbuttons = [... new Set(styxpluginbuttons)]; // turning an array into a set and then back into an array: is like array_unique !

    styxButtonHooks = prepare_tbarArr(styxpluginbuttons);

    // NOTE: code === source !!
    // the run loop number makes our setting independent for multiple textarea configurations ! last change: move split group behind medias group container ... was behind link previously
    let commonToolbar{$run} = [ { name: 'history', items: [ 'undo' ] }, { name: 'format', items: [ 'bold', 'italic' ] }, { name: 'link', items: [ 'link', 'blockquote' ] }, { name: 'images', items: [ 'styxImage', 'styxGallery' ] }, { name: 'medias', items: [ 'media', 'emoticons' ] }, { name: 'split', items: [ 'hr' ] }, { name: 'code', items: [ 'codesample', 'charmap' ] }, { name: 'views', items: [ 'code', 'fullscreen' ] }, { name: 'visuals', items: [ 'preview', 'visualblocks' ] }, { name: 'help', items: [ 'help' ] }, { name: 'extras', items: [ 'styles', 'fontsize', 'table', 'accordion' ] }, { name: 'hooks', items: styxButtonHooks } ];

    styxPlugs = pluginArrToStr(styxpluginnames);

    // keep in once, to not loose features - names are case sensitive ! - see independent note on commonToolbar
    let commonPlugins{$run} = 'preview autoresize lists code fullscreen image link media codesample table charmap styxImage styxGallery visualblocks styxDiv styxPrg help emoticons accordion magicline ' + styxPlugs.replace(/["|'|,]/g, "").slice(0, -1);

    styxPlugs = ''; // reset after usage for next 2cd run

    // wait for plugin nuggets, since often set/thrown before
    document.addEventListener("DOMContentLoaded", (event) => {
      // A pre load content filter for old db stored entry data
      var currentEditor = document.getElementById("{$item}");
      var html = currentEditor.defaultValue;
      if (null !== html) {
        html = html.replaceAll(/<\/p>\s*<br[/ ]*>/ig, '</p>'); // replace </p><br> with </p>
        // now we want to nuke empty elements of p div or pre - preferable at the very ends of textarea content html only
        html = html.replaceAll(/<(p|div|pre)[^>]*>\s*<\/\1>/ig, ''); // remove empty tags - ToDo: run this selective

        currentEditor.defaultValue = html;
      }

      tinymce.init({
        selector: '#{$item}',
        plugins: commonPlugins{$run},
        toolbar: commonToolbar{$run},
        language: '{$lang}',
          ...commonConfig
      });
    });

    // the tinymce auto_focus behaves erratic based on focusable content, last edit and/or having to 2 textareas and so forth... - so better force an independent page re-focus here.
    var h = location.hash ; null;
    if (!h) {
        $(window).on('load', function () { $('html, body').animate({ scrollTop: 0 }, 'smooth'); });
    }
</script>
{if $run == 1 AND isset($styxpluginbuttonstyles)}

<style>
/* additional plugin button styles for RichText editor */
{foreach $styxpluginbuttonstyles AS $style}
    {$style}
{/foreach}
</style>
{/if}
