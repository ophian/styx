// default
if (typeof STYX_DARKMODE === 'undefined' || STYX_DARKMODE === null) STYX_DARKMODE = false;
// NOT pure theme + derivates
if (typeof MATCH_SESSIONSTORAGE === 'undefined' || MATCH_SESSIONSTORAGE === null) MATCH_SESSIONSTORAGE = false;
// NOT b53 theme + derivates
if (typeof MATCH_LOCALSTORAGE === 'undefined' || MATCH_LOCALSTORAGE === null) MATCH_LOCALSTORAGE = false;
let sisdark = localStorage.getItem('theme');
    sisdark = sisdark ? sisdark : sessionStorage.getItem('dark_mode');
if (MATCH_LOCALSTORAGE === true) {
    localStorage.setItem('theme', 'auto');
    sisdark = 'dark';
} else {
    if (MATCH_SESSIONSTORAGE === false){
        sisdark = null;
    }
}
if (STYX_DARKMODE !== true && document.getElementById('serendipity_commentform_comment') !== null
&& ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches && (typeof sisdark !== 'undefined' && sisdark !== null && sisdark !== 'light'))
|| (sisdark === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches)
||  sisdark === 'dark')) {
    var STYX_DARKMODE = true;
}
let basicConfig = {
    skin: (typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? 'tinymce-5-dark' : 'tinymce-5',
    content_css: (typeof(STYX_DARKMODE) !== 'undefined' && STYX_DARKMODE === true) ? styxPath + 'templates/_assets/prism/dark/prism.css' : styxPath + 'templates/_assets/prism/default/prism.css',
    plugins: 'autoresize lists codesample charmap emoticons',
    contextmenu: false,
    width: '100%',
    height: 300,
    autoresize_min_height: 300,
    init_instance_callback: function (inst) { inst.execCommand('mceAutoResize'); },
    // overwrite some default margin - 8px is a good compromise
    autoresize_bottom_margin: 8,
    menubar: false,
    toolbar_mode: 'sliding',
    toolbar: [
        { name: 'history', items: [ 'undo' ] },
        { name: 'format', items: [ 'bold', 'italic', 'underline', 'strikethrough' ] },
        { name: 'link', items: [ 'blockquote' ] },
        { name: 'code', items: [ 'codesample', 'emoticons', 'charmap' ] },
    ],
    // Configure mobile behaviour
    mobile: {
        toolbar_mode: 'floating',
        contextmenu: false,
    },
    language: 'en',
    entity_encoding: 'raw',
    extended_valid_elements: 'code[class],pre[class]',
    branding: false,
    promotion: false,
    //license_key: 'gpl',
    // convert image urls NOT to relative path, which is OK for the same domain, but not in other environments which are based on doc root paths
    relative_urls : false,
    auto_focus: true,
    help_tabs: false,
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
    // TinyMCE front end editor position breaks structure: Remove "<div class="tox tox-silver-sink tox-tinymce-aux" style="width: 1490px; position: relative;"></div>" in body end context.
    // The inline styled "position: relative" cannot be unset by global (fallback) CSS and is somehow designed for tinymce "dialog open" tasks, which we don't need here!
    setup: (editor) => {
      editor.on('PostRender', () => {
        var container = editor.getContainer();
        var uiContainer = document.querySelector('.tox.tox-tinymce-aux');
        if (uiContainer === null) return;
        container?.parentNode?.appendChild(uiContainer);
      });
    }
}
