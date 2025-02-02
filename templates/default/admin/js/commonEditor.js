/* Only static configuration content here which is equal in multi ordered textareas */
const commonConfig = {
    skin: (typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'tinymce-5-dark' : 'tinymce-5',
    content_css: [ ((typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'templates/_assets/prism/dark/prism.css' : 'templates/_assets/prism/default/prism.css'),
                   'templates/_assets/sctc.min.css'
                 ], // custom mix styx_custom_tinymce_content.css
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
    // Configure our magicline plugin
    magicline: {
        triggerOffset: 30,
        holdDistance: 0.5,
        color: '#ff0000',
        everywhere: true,
        tabuList: []
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
    // convert image urls NOT to relative path, which is OK for the same domain, but not in other environments which are based on doc root paths
    relative_urls : false,
    auto_focus: 'editable',
    help_tabs: [ 'shortcuts', 'keyboardnav' ],
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
