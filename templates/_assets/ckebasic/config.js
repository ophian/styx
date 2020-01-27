/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// The toolbar groups arrangement, optimized for a single toolbar row.
	config.toolbarGroups = [
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
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

	// The default plugins included in the basic setup define some buttons that
	// are not needed in a basic editor. They are removed here.
	config.removeButtons = 'Cut,Copy,Paste,Undo,Redo,Anchor,Strike,Subscript,Superscript';

	// Dialog windows are also simplified.
	config.removeDialogTabs = 'link:advanced';

    // Backend Only Area
    if ($('#serendipityEntry').length > 0) {
        // Add Styx specific styles
        config.contentsCss = [ 'templates/_assets/ckebasic/contents.css', 'templates/_assets/wysiwyg-style.css' ];

        // Plugin: Autogrow textareas configuration for Styx
        config.autoGrow_minHeight = 120;
        config.autoGrow_maxHeight = 420;
        config.autoGrow_bottomSpace = 50;
        config.autoGrow_onStartup = true;

        /** SECTION: Extra Allowed Content
            Set placeholder tag cases - elements [attributes]{styles}(classes) to protect ACF suspensions.
              - Allowed <mediainsert>, <gallery>, <media> tags (imageselectorplus galleries) - which tells ACF to not touch the code!
              - Allowed <picture> element and the <source> tag for viewport client access - which tells ACF to not touch the code!
              - Allowed <figure> styles and classes, <figcaption> classes for image comments
              - Allowed <div> is a need for Media Library inserts - which tells ACF to not touch the code!
              - Allowed <p> custom classes - to easier style certain paragraphs!
              - Allowed <ul> listing for styles and classes and <span> to make life a bit easier!
              - Allowed <a> link tag attributes and classes for having to add data-* attributes (see picture element) - which tells ACF to not touch the code!
              - Allow <img> [attributes]{styles}(classes) Media Library image inserts to protect ACF suspension - which tells ACF to not touch the code!
              - Allow <code(*classes)>, <pre[*attributes](*classes)> for custom attributes/classes in code blocks - which tells ACF to not touch the code!
        */
        // protect
        config.extraAllowedContent = 'mediainsert[*]{*}(*);gallery[*]{*}(*);media[*]{*}(*);audio[*]{*}(*);div[*]{*}(*);p(*);ul{*}(*);a[*](*);span[*]{*}(*);figure{*}(*);figcaption(*);picture;source[*]{*}(*);img[*]{*}(*);code(*);pre[*](*);';
        // Do not use auto paragraphs, added to these allowed tags (only!). Please regard that this was marked deprecated by CKE 4.4.5, but is a need for (our use of) extraAllowedContent - check this again by future versions!
        config.autoParagraph = false; // defaults(true)
    }
};
