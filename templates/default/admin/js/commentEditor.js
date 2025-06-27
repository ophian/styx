// A pre load content filter for old db stored comment data
const currentEditor = document.getElementById('serendipity_commentform_comment');
let html = currentEditor.defaultValue;
if (null !== html) {
    html = html.replaceAll(/<\/p>\s*<br[/ ]*>/ig, '</p>'); // replace </p><br> with </p>
    // now we want to nuke empty elements of p div or pre - preferable at the very ends of textarea content html only
    html = html.replaceAll(/<(p|div|pre)[^>]*>\s*<\/\1>/ig, ''); // remove empty tags - ToDo: run this selective

    currentEditor.defaultValue = html;
} // OK This works here for the comments and this because it is a single and placed right in workflow

const commentConfig = {
    skin: (typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'tinymce-5-dark' : 'tinymce-5',
    content_css: [ ((typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'templates/_assets/prism/dark/prism.css' : 'templates/_assets/prism/default/prism.css'),
                   'templates/_assets/sctc.min.css', ((typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'templates/_assets/sctc-dark.min.css' : '')
                 ], // custom mix styx_custom_tinymce_content.css w/ own colors, for STYX_DARKMODE case including dark mode additions to overwrite 'sctc.min.css'
    noneditable_class: 'mceNonEditable',
    // keep in once, to not loose features - names are case sensitive !
    plugins: 'preview autoresize lists code fullscreen image link media codesample table charmap styxDiv styxPrg help emoticons accordion magicline',
    contextmenu: 'link styxDiv styxPrg code',
    width: '100%',
    height: 300,
    autoresize_min_height: 300,
    init_instance_callback: function (inst) { inst.execCommand('mceAutoResize'); },
    // overwrite some default margin - 8px is a good compromise
    autoresize_bottom_margin: 8,
    menubar: false,
    toolbar_mode: 'sliding',
    // code === source !!
    toolbar: [
        { name: 'history', items: [ 'undo' ] },
        { name: 'format', items: [ 'bold', 'italic', 'underline', 'strikethrough' ] },
        { name: 'link', items: [ 'link', 'blockquote' ] },
        { name: 'split', items: [ 'hr' ] },
        { name: 'code', items: [ 'codesample', 'emoticons', 'charmap' ] },
        { name: 'views', items: [ 'code', 'fullscreen' ] },
        { name: 'help', items: [ 'help' ] }
    ],
    // Configure mobile behaviour
    mobile: {
        toolbar_mode: 'floating',
        contextmenu: 'styxDiv | styxPrg',
    },
    language: editorLang,
    object_resizing: false, // we don't want the image resize option
    highlight_on_focus: false, // ?? dito
    visual: false, // ?? dito
    entity_encoding: 'raw',
    extended_valid_elements: 'span[class],code[class],pre[class]',
    branding: false,
    promotion: false,
    //license_key: 'gpl',
    // convert image urls NOT to relative path, which is OK for the same domain, but not in other environments which are based on doc root paths
    relative_urls : false,
    // enables double click on hlgt code to re-open code editor for example
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
          newBlock.innerHTML = '<br data-mce-bogus=\"1\">';
          console.log(breakout);
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
      editor.addShortcut('meta+shift+40', 'Container break-out downwards', function () { _newBlock('down'); });
      editor.shortcuts.add('meta+shift+38', 'Container break-out upwards', function () { _newBlock('up'); });
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
      { start: '//indent', replacement: '<address style=\"padding-left: 40px;\">&nbsp;</address>' },
      { start: '//brb', replacement: 'Be Right Back' }
    ],
}
