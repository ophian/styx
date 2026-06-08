const userCssUrl = new URL('templates/_assets/sctc-user.css', window.location.href).href; // optional custom user stylesheet file for the RichText content area
const encodedImport = 'data:text/css;charset=UTF-8,%40import%20url(%22' + encodeURIComponent(userCssUrl) + '%22);';
const magiclineHelp = { en: { title: 'Magic Line Help',
    html: '<h1 style="color: mediumpurple">A visual helper enhancement for the RichText editor</h1>\n' +
      '\n' +
      '<p><strong>Purpose</strong>: Adds an interactive red "insert paragraph" line that appears on hover or touch to help you insert new paragraphs at the correct structural level—jumping out of nested containers, like linked images, galleries, or floats, without breaking your HTML hierarchy.</p>\n' +
      '\n' +
      '<p>It allows to <strong>break-out</strong>, up or down the currently highlighted (nested) element container, to set or fill the next element in root level.</p>\n' +
      '\n' +
      '<h1 style="color: mediumorchid">Magic Line Instructions</h1>\n' +
      '\n' +
      '<p>When hovering or touching such block element container this block turns into a hit area layer with a dashed line around it. At its top or bottom edge—creating a red line, pointing up- or downwards to insert new content blocks outside the current nesting level on click, without breaking simplicity of your HTML hierarchy.</p>\n' +
      '\n' +
      '<p>The hit-area and red line <strong>only</strong> appears when actually needed to avoid deep nested containers. If the adjacent element can handle correct placement insertion via cursor (+ ENTER) into the entries root level, the Magic Line helper is not shown. This also is the case when a red line event already has been triggered.</p>\n' +
      '\n' +
      '<p><strong>Why to use it</strong>: It ensures proper document structure and prevents invalid nesting that can cause display issues now and later on.</p>\n' +
      '\n' +
      '<p><strong>When it appears</strong>: Only when needed to exit the current container. For content within blocks, use normal editing and/or the toolbar options.</p>\n' +
      '\n' +
      '<p><strong style="text-shadow: 1px 2px 3px #1c1a1a; color: var(--color-scale-orange-4)">Important Issue Handling</strong>:</p>\n' +
      '\n' +
      '<ul style="margin-left: -1em; list-style-type: \'- \'">\n' +
      '  <li><p style="color: currentColor">Some Magic Line paragraph insertions cannot be undone with the ↩ toolbar button (especially between image containers). Instead, use:</p>\n' +
      '    <ul style="list-style-type: circle; font-size: .875rem">\n' +
      '      <li>Source view to manually correct HTML, which is the recommended <strong>best</strong> way to ensure correct structures.</li>\n' +
      '      <li>Keyboard delete/backspace with caution. These may delete hidden HTML tags and destroy unintended structures that won\'t auto-repair</li>\n' +
      '    </ul>\n' +
      '  </li>\n' +
      '  <li><p style="color: currentColor">Scrolling longer iframe content is disabled when the Magic Line hit area is active: </p>\n' +
      '    <ul style="list-style-type: circle; font-size: .875rem">\n' +
      '      <li>Allow the highlighted box to auto-dismiss (without moving the mouse in the meanwhile) and then scroll, or move your mouse out of the dashed container area to regain scrolling ability for the deeper entry level.</li>\n' +
      '    </ul>\n' +
      '  </li>\n' +
      '</ul>\n' +
      '\n\n' +
      '<div style="border: 6px double #585858;font-size: .875rem;padding: .5em;background-color: var(--color-bg-backdrop);">\n' +
      '<h3>Plugin Key Features:</h3>\n' +
      '<ul>\n' +
      '<li>Smart detection of block elements (images, galleries, floats, etc.)</li>\n' +
      '<li>Robust handling for various content types</li>\n' +
      '<li>Touch device support with device-specific UI hints</li>\n' +
      '<li>Accessibility features built-in</li>\n' +
      '<li>Smart overlay system for visual feedback</li>\n' +
      '</ul>\n' +
      '\n' +
      '<h3>Plugin Metadata:</h3>\n' +
      '<ul>\n' +
      '<li>Plugin version: 2.2.1</li>\n' +
      '<li>Last modified: June 08, 2026</li>\n' +
      '<li>Created for: Serendipity Styx blog edition</li>\n' +
      '<li>Author: Ian Styx</li>\n' +
      '<li>Status: active</li>\n' +
      '</ul>\n' +
      '</div>\n',
  },
  de: { title: 'Magic Line Hilfe',
    html: '<h1 style="color: mediumpurple">Ein visueller Helfer für den RichText-Editor</h1>\n' +
      '\n' +
      '<p><strong>Zweck</strong>: Fügt eine interaktive rote „Absatz einfügen"-Linie hinzu, die auf bestimmten Blockelementen, wie verlinkten Bildern, Galerien oder Floats, im Editor-Fenster erscheint und Ihnen ermöglicht, schnell neue Absätze oder Blöcke einzufügen, ohne solche ineinander zu verschachteln.</p>\n' +
      '\n' +
      '<p>Sie können damit aus dem aktuell hervorgehobenen (verschachtelten) Element nach <strong>oben</strong> oder nach <strong>unten</strong> herausspringen um das nächste Element zu setzen bzw zu befüllen.</p>\n' +
      '\n' +
      '<h1 style="color: mediumorchid">Magic Line Anweisungen</h1>\n' +
      '\n' +
      '<p>Je nach Gerät wird beim <em style="font-style: italic">Überfahren</em> oder <em style="font-style: italic">Touch</em> dieses Element zu einer umrandeten Click-Area und eine rote Linie am oberen oder unteren Rand eingeblendet, die Ihnen dabei helfen soll, neue Absätze oder Blöcke auf der richtigen strukturellen Ebene des Eintrages einzufügen, ohne Ihre HTML-Hierarchie zu beschädigen.</p>\n' +
      '\n' +
      '<p>Der Übersicht und Einfachheit halber ist der Hit-Layer und die rote Linie <strong>nicht</strong> verfügbar, wenn das angrenzende Nachbar-HTML-Element einen eigenen Sprung auf die unterste Ebene ermöglicht, so Sie den Cursor platzieren und ENTER drücken. Diese Paarung ist auch dann der Fall, wenn das rote Linien-Ereignis für das Container-Element auf diesen "Nachbarn" bereits angewendet wurde.</p>\n' +
      '\n' +
      '<p><strong>Warum man es verwenden sollte</strong>: Es gewährleistet eine korrekte Dokumentstruktur und verhindert das Speichern von unerwünschter Verschachtelung, die zu Darstellungsproblemen führen kann; sei im Editorfenster selbst oder später in ihrem Blogeintrag.</p>\n' +
      '\n' +
      '<p><strong>Wann es erscheint</strong>: Nur dann, wenn nötig, um den aktuellen Container zu verlassen und es keine Möglichkeit gibt dies vom angrenzenden Element aus zu bewerkstelligen. Verwenden Sie für Inhalte in Blöcken ansonsten die normalen Bearbeitungsmethoden.</p>\n' +
      '\n' +
      '<p><strong style="text-shadow: 1px 2px 3px #1c1a1a; color: var(--color-scale-orange-4)">Wichtige Fehlerbehandlung</strong>:</p>\n' +
      '\n' +
      '<ul style="margin-left: -1em; list-style-type: \'- \'">\n' +
      '  <li><p style="color: currentColor">Einige Magic Line Absatz-Einfügungen können nicht mit der ↩ Schaltfläche in der Symbolleiste rückgängig gemacht werden (besonders zwischen Bildcontainern). Verwenden Sie stattdessen:</p>\n' +
      '    <ul style="list-style-type: circle; font-size: .875rem">\n' +
      '      <li>Die Quellansicht, um das HTML manuell zu korrigieren – dies ist die empfohlene und <strong>beste</strong> Methode, um korrekte Strukturen zu gewährleisten.</li>\n' +
      '      <li>Tastatur DEL/Backspace -Aktionen mit Vorsicht. Diese können versteckte HTML-Tags löschen und so unbeabsichtigt Strukturen zerstören, die sich nicht automatisch reparieren lassen</li>\n' +
      '    </ul>\n' +
      '  </li>\n' +
      '  <li><p style="color: currentColor">Das Scrollen längerer Eintrags-Inhalte im iFrame ist unterbunden, wenn die Trefferfläche des Magic Line-Blocks aktiv ist. In diesem Fall:</p>\n' +
      '    <ul style="list-style-type: circle; font-size: .875rem">\n' +
      '      <li>Lassen Sie das hervorgehobene Feld automatisch verschwinden (ohne die Maus zwischenzeitlich zu bewegen) und scrollen Sie dann, <strong>oder</strong> bewegen Sie Ihre Maus aus dem Bereich des gestrichelten Containers heraus, um die Scrollfähigkeit auf der tieferen Ebene wiederzuerlangen.</li>\n' +
      '    </ul>\n' +
      '  </li>\n' +
      '</ul>\n' +
      '\n' +
      '<div style="border: 6px double #585858;font-size: .875rem;padding: .5em;background-color: var(--color-bg-backdrop);">\n' +
      '\n' +
      '<h3>Hauptfunktionen des Plug-ins:</h3>\n' +
      '\n' +
      '<ul>\n' +
      '<li>Intelligente Erkennung von Blockelementen (Bilder, Galerien, Floats usw.)</li>\n' +
      '<li>Robuste Handhabung verschiedener Inhaltstypen</li>\n' +
      '<li>Unterstützung für Touch-Geräte mit gerätespezifischen UI-Hinweisen</li>\n' +
      '<li>Integrierte Barrierefreiheitsfunktionen</li>\n' +
      '<li>Intelligentes Overlay-System für visuelles Feedback</li>\n' +
      '</ul>\n' +
      '\n' +
      '<h3>Plug-in-Metadaten:</h3>\n' +
      '<ul>\n' +
      '<li>Plug-in-Version: 2.2.1</li>\n' +
      '<li>Zuletzt geändert: Juni 08, 2026</li>\n' +
      '<li>Erstellt für: Serendipity Styx Blog-Edition</li>\n' +
      '<li>Autor: Ian Styx</li>\n' +
      '<li>Status: aktiv</li>\n' +
      '</ul>\n' +
      '\n' +
      '</div>\n',
  }
};

/* Only static configuration content here which is equal in multi ordered textareas */
const commonConfig = {
    skin: (typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'tinymce-5-dark' : 'tinymce-5',
    content_css: [ ((typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'templates/_assets/prism/dark/prism.css' : 'templates/_assets/prism/default/prism.css'),
                   'templates/_assets/sctc.min.css', ((typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'templates/_assets/sctc-dark.min.css' : ''),
                   encodedImport
    ].filter(Boolean), // custom mix styx_custom_tinymce_content.css w/ own colors, for STYX_DARKMODE case including dark mode additions to overwrite 'sctc.min.css'. Filter removes empty strings
    noneditable_class: 'mceNonEditable',
    // plugins and toolbar and lang sets may contain dynamic sets so better place in init directly
    contextmenu: 'link styxImage styxGallery styxDiv styxPrg visualblocks code',
    width: '100%',
    height: 300,
    autoresize_min_height: 300,
    init_instance_callback: function (inst) { inst.execCommand('mceAutoResize'); },
    // overwrite some default margin - 8 is a good compromise
    autoresize_bottom_margin: 8,
    menubar: false,
    toolbar_mode: 'sliding',
    // Configure mobile behaviour
    mobile: {
        toolbar_mode: 'floating',
        contextmenu: 'styxDiv | styxPrg',
    },
    // TODO Create a tinymce v.6 scriptlet to configure plugin and toolbar items per user ...?
    object_resizing: false, // we don't want the image resize option
    highlight_on_focus: false, // ?? ditto - [ This feature is only available for TinyMCE 6.4 and later. In TinyMCE 7.0, the default setting for highlight_on_focus was changed from false to true. Any editors using this highlight_on_focus: true option in TinyMCE , can remove this option from their TinyMCE init configuration when upgrading to TinyMCE 7.0. ]
    visual: false, // ?? ditto
    entity_encoding: "raw",
    allow_script_urls: true, // this also allows the isolated page image link option with onclick="javascript:this.href =.., otherwise simple a[onclick] allows only the POPup JS option onclick="F1 = window.open..
    //When adding a new attribute by specifying an existing element rule (e.g. img, a), the entire rule for that element is over-ridden so be sure to include all valid attributes not just the one you wish to add. 
    //https://www.tiny.cloud/docs/tinymce/latest/content-filtering/#extended_valid_elements
    //https://www.tiny.cloud/docs/tinymce/latest/content-filtering/#valid_elements
    //https://www.tiny.cloud/docs/tinymce/latest/content-filtering/#invalid_elements
    // ToDo extend add styles * and classes * like having done for CKE
    extended_valid_elements: 'mediainsert[*],gallery[*],media[*],audio[*],video[*],div[*],p[lang],q[lang],ul[lang],a[href|rel|target|class|id|style|onclick|title],span[*],figure[*],figcaption[*],picture,source[*],img[*],code[*],hr,pre[*],ref[name]',
    branding: false,
    promotion: false,
    // convert image URLs NOT to relative path, which is OK for the same domain, but not in other environments which are based on doc root paths
    relative_urls : false,
    auto_focus: 'editable',
    // While the editors auto_focus is making trouble with multi textarea views of backend entry forms (always landing in the second textarea field), or scrolling a backend page with a single textarea too much,
    // ensure the editor is not focused after init, since there is no documented false set available
    init_instance_callback: function (editor) {
      // Clean up empty class attributes from previously stored content on editor initialization
      cleanupEmptyClassAttributes(editor);

      setTimeout(function () {
        const body = editor.getBody();
        if (body && typeof body.blur === 'function') {
          body.blur();
        }
        // fallback: blur whatever DOM element is currently focused
        if (document.activeElement && typeof document.activeElement.blur === 'function') {
          document.activeElement.blur();
        }
      }, 0);
    },
    help_tabs: [ 'shortcuts', 'keyboardnav',
        //additional custom
        {
          name: 'magicline',
          title: magiclineHelp[editorLang == 'de' ? editorLang : 'en'].title,
          items: [{ type: 'htmlpanel', html: magiclineHelp[editorLang == 'de' ? editorLang : 'en'].html }],
        }
    ],
    // Configure on setup
    setup: function (editor) {
      // Styx helper fnc to break-out containers - see help plugin for manual keyboard commands and language additions
      const _newBlock = (breakout) => {
          let editor = tinyMCE.activeEditor
          const dom = editor.dom
          const parentBlock = tinyMCE.activeEditor.selection.getSelectedBlocks()[0]
          const containerBlock = parentBlock.parentNode.nodeName == 'BODY' ? dom.getParent(parentBlock, dom.isBlock) : dom.getParent(parentBlock.parentNode, dom.isBlock)
          let newBlock = tinyMCE.activeEditor.dom.create('p')
          newBlock.innerHTML = '<br data-mce-bogus="1">';
          if (breakout == 'up') {
            editor.getBody().insertBefore(newBlock, containerBlock);
          } else {
            dom.insertAfter(newBlock, containerBlock)
          }
          let rng = dom.createRng();
          newBlock.normalize();
          rng.setStart(newBlock, 0);
          rng.setEnd(newBlock, 0);
          editor.selection.setRng(rng);
      };
      editor.addShortcut("meta+shift+40", "Container break-out downwards", function () { _newBlock('down'); });
      editor.shortcuts.add("meta+shift+38", "Container break-out upwards", function () { _newBlock('up'); });
      // Intercept when source view is about to open and clean content first
      editor.on('BeforeExecCommand', function(e) {
        if (e.command === 'mceCodeEditor') {
          const body = editor.getBody();
          if (body) {
            // Clean up before opening code view
            body.querySelectorAll('[class=""]').forEach(el => {
              el.removeAttribute('class');
            });
            body.querySelectorAll('[class*="magic-line"]').forEach(el => {
              el.classList.remove('magic-line-highlight', 'magic-line-fading');
              if (el.className === '') {
                el.removeAttribute('class');
              }
            });
          }
        }
      });
      // refocus to the place where have been added...
      editor.on('ExecCommand', function (e) {
        if (e.command === 'mceInsertContent') {
          editor._pendingScrollRestore = true;
        }
        if (e.command === 'mceFocus' && editor._pendingScrollRestore) {
          editor._pendingScrollRestore = false;
          requestAnimationFrame(() => {
            const cursorNode = editor.selection.getNode();
            if (cursorNode) {
              cursorNode.scrollIntoView({
                block: 'nearest',
                behavior: 'instant'
              });
            }
          });
        }
      });
      // Just to make sure! Clean up empty class attributes when saving content
      editor.on('SaveContent', function(e) {
        e.content = e.content.replace(/\s*class=""/g, '');
        // Remove magic-line classes
        if (e.content) {
          e.content = e.content.replace(/\s*class="magic-line-highlight"/g, '');
          e.content = e.content.replace(/\s*class="magic-line-fading"/g, '');
        }
      });
    },
    // define codesample language markers
    codesample_languages: [
      { text: 'HTML/XML', value: 'markup' },
      { text: 'JavaScript', value: 'javascript' },
      { text: 'CSS', value: 'css' },
      { text: 'PHP', value: 'php' },
      { text: 'SQL', value: 'sql' },
      { text: 'Ruby', value: 'ruby' },
      { text: 'Python', value: 'python' },
      { text: 'Java', value: 'java' },
      { text: 'Log', value: 'log' },
      { text: 'C', value: 'c' },
      { text: 'C#', value: 'csharp' },
      { text: 'C++', value: 'cpp' },
      { text: 'Go', value: 'go' },
      { text: 'Shell', value: 'shell' },
      { text: 'Smarty', value: 'smarty' },
      { text: 'Diff', value: 'diff' },
      { text: 'Rust', value: 'rust' },
      { text: 'YAML', value: 'yaml' }
    ],
    // highlight with prism for the editor currently. Using highlight.js was found difficult, though this stays our default highlight code alignment tool.
    codesample_global_prismjs: true,
    // testing some text replace patterns
    text_patterns: [
      { start: '//---', replacement: '<hr/>' },
      { start: '//--', replacement: '—' },
      { start: '//(c)', replacement: '©' },
      { start: '//indent', replacement: '<address style="padding-left: 40px;">&nbsp;</address>' },
      { start: '//brb', replacement: 'Be Right Back' },
      { start: '//heading', replacement: '<h3>Heading here</h3> <h4>Author: Name here</h4> <p><em>Date: 01/01/2000</em></p> <hr />' }
    ],
}

/* Helper function to remove empty class="" attributes from editor content */
function cleanupEmptyClassAttributes(editor) {
  const body = editor.getBody();
  if (!body) return;

  // Remove all empty class="" attributes from the DOM
  const elementsWithEmptyClass = body.querySelectorAll('[class=""]');
  elementsWithEmptyClass.forEach(el => {
    el.removeAttribute('class');
  });
}
