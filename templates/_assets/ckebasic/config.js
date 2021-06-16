/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

    // The toolbar groups arrangement, optimized for a single toolbar row.
    config.toolbarGroups = [
        { name: 'document',    groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
        { name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'forms' },
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'links' },
        { name: 'mediaembed' },
        { name: 'insert' },
        { name: 'styles' },
        { name: 'colors' },
        { name: 'tools' },
        { name: 'others' },
        { name: 'about' }
    ];

    // Allow dark mode
    if (typeof STYX_DARKMODE === 'undefined' || STYX_DARKMODE === null) STYX_DARKMODE = false;
    config.skin = (STYX_DARKMODE === true ? 'moono-dark' : 'moono-lisa');

    // The default plugins included in the basic setup define some buttons that
    // are not needed in a basic editor. They are removed here.
    config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Strike,Subscript,Superscript';

    // Dialog windows are also simplified.
    config.removeDialogTabs = 'link:advanced';

    // BACKEND Only Area - check blog entries, staticpages and other backend related normal form area nuggets (ie. comment forms have different init need),
    // like contactform, commentspice, downloadmanager, FAQ, DSGVO / GDPR, guestbook, html nugget, quicknotes, and more.
    if (document.getElementById('serendipityEntry') != null || document.getElementById('sp_main_data') != null || document.getElementById('backend_sp_simple') != null || document.getElementById('serendipity_admin_page .form_area') != null || document.getElementById('nuggets3')) {
        //console.log('STYX fired WYSIWYG: backend entries, staticpages or spawned nuggets');
        // Add Styx specific styles
        if (STYX_DARKMODE === true) {
            config.contentsCss = [ 'templates/_assets/ckebasic/dark-contents.css', 'templates/_assets/wysiwyg-style.css' ];
        } else {
            config.contentsCss = [ 'templates/_assets/ckebasic/contents.css', 'templates/_assets/wysiwyg-style.css' ];
        }

        config.entities = false; // defaults(true)
        config.htmlEncodeOutput = false; // defaults(true)

        // Plugin: Autogrow textarea default configuration for Styx
        config.autoGrow_minHeight = 120;
        config.autoGrow_maxHeight = 420;
        config.autoGrow_bottomSpace = 50;
        config.autoGrow_onStartup = true;

        // Default theme of CKEDITOR 'codesnippet' plugin - else use 'default' or 'monokai_sublime' or 'pojoaque' or any of those described at https://highlightjs.org/static/test.html
        config.codeSnippet_theme = 'github'; // write as exists, since can be case sensitive when loading!

        /** SECTION: Extra Allowed Content - which tells ACF to not touch the code!
            Set placeholder tag cases to protect ACF suspensions:
              - Allowed <mediainsert>, <gallery>, <media> tags (imageselectorplus galleries)
            Normal ACF suspension tag protects:
              - Allowed <picture> element and the <source> tag for viewport client access
              - Allowed <figure> styles and classes, <figcaption> classes for image comments
              - Allowed <div> is a need for Media Library inserts
              - Allowed manually, by source added header (2,3,4) formats
              - Allowed <p> custom classes - to easier style certain paragraphs!
              - Allowed <ul> listing for styles and classes, <hr> and <span> to make life a bit easier!
              - Allowed <a> link tag attributes and classes for having to add data-* attributes (see picture element)
              - Allowed <img> [attributes]{styles}(classes) Media Library image inserts to protect ACF suspension
              - Allowed <code(*classes)>, <pre[*attributes](*classes)> for custom attributes/classes in code blocks
              - Allowed (pseudo) [lang] attribute in p and ul elements, see @https://www.w3.org/International/questions/qa-css-lang.en
        */
        // protect - elements [attributes]{styles}(classes) - only use the asterix!
        config.extraAllowedContent = 'mediainsert[*]{*}(*);gallery[*]{*}(*);media[*]{*}(*);audio[*]{*}(*);video[*];div[*]{*}(*);h2;h3;h4;p[lang](*);ul[lang]{*}(*);a[*](*);span[*]{*}(*);figure{*}(*);figcaption(*);picture;source[*]{*}(*);img[*]{*}(*);code(*);hr;pre[*](*);';
        // Do not use auto paragraphs, added to these allowed tags (only!). Please regard that this was marked deprecated by CKE 4.4.5, but is a need for (our use of) extraAllowedContent - check this again by future versions!
        config.autoParagraph = false; // defaults(true)
    }
};
