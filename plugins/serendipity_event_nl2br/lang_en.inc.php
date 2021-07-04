<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_NL2BR_NAME', 'Markup: NL2BR');
@define('PLUGIN_EVENT_NL2BR_DESC', 'Convert newlines to BR tags');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP', 'Check other markup plugins?');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP', 'Check other markup plugins?');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP_DESC', 'Automatically check existing markup plugins to disable the use of NL2BR plugin. This is true, when WYSIWYG or specific markup plugins are detected.');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS', 'Exceptions for all following rules');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS_DESC', 'A list of user defined HTML-tags where no breaks shall be converted. Suggestion: "nl,pre,geshi,textarea". Separate multiple tags with a comma. Hint: The entered tags are evaluated as regular expressions.');
@define('PLUGIN_EVENT_NL2BR_PTAGS', 'Use p-tags');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC', 'Insert p-tags instead of <br>.');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC2', 'This may break with some non-simple markup cases!');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG', 'ISOBR isolations-default BR setting');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG_DESC', 'With this NONE-HTML-Tag <nl> </nl>, as the NL2BR Isolations-Default setting, you can use the NL2BR function now by shutting down the text parsing inside this tag. You can use it multiple times inside your entry, but not nested! Example: <nl>do not parse newline to br inside this multiline-textblock</nl>');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS', 'Use BR-Clean-Tags as fallback, when ISOBR false');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS_DESC', 'If using <HTML-Tags> in you entries, which can\'t be solved satisfiable with the ISOBR Config-Option, remove nl2br after <tag>. This applies to all <tags> ending with > or >\n! Default (table|thead|tbody|tfoot|th|tr|td|caption|colgroup|col|ol|ul|li|dl|dt|dd)');
@define('PLUGIN_EVENT_NL2BR_CONFIG_ERROR', 'Config mismatch alert! The Option: "%s" is set back to false, while  \'%s\' is active! Just use one of them, please.');

@define('PLUGIN_EVENT_NL2BR_ABOUT_TITLE', 'PLEASE NOTE the implications of this markup plugin:');
@define('PLUGIN_EVENT_NL2BR_ABOUT_DESC', '<p>This plugin transfers linebreaks to HTML-linebreaks, so that they show up in your blog entry.</p>
<p><b><u>Preliminary note</u>:</b> The Serendipity standard delivery has always used no other markup plugins by default. We call this text form PLAIN (TEXT) EDITOR here. Text is plain text and line breaks inserted by ENTER or structurally are stored coded in the database and converted to HTML by this plugin only at runtime output.</p>
<p><b>PLAIN EDITOR</b>s basic functionality: simply converts newlines to &lt;br&gt; tags (Extended by ISOBR option).<br>
<b>PLAIN EDITOR</b>s extended P-functionality: parse the text into &lt;p&gt; tags in regard of the HTML syntax where they are allowed and automatically ignore for preformatted text inside &lt;pre&gt; tags or inside &lt;style&gt; or &lt;svg&gt; tags.</p>
<p>This can lead to problems for you especially if you change the markup plugin during the operation of your blog, i.e. content with different requirements can be found in the entry tables afterwards:</p>
<ul>
    <li>The built-in <strong>WYSIWYG editor</strong> and the <strong>CKEditor Plus</strong> plugin already save correct HTML - ready for output - and automatically disable the NL2BR plugin for output. (Otherwise there would be a duplication of all coded line breaks and would at least change or even destroy the output layout).</li>
    <li>If you use other markup plugins in conjunction with this plugin that already translate line breaks. The <strong>TEXTILE</strong> and <strong>MARKDOWN</strong> plugins are examples of such plugins. (There are corresponding provisions for disabling NL2BR for these two as well).</li>
</ul>
<p>This "<em>problem</em>" applies to a large extent, however, only if you have very old entries from the early days of Serendipity, where the markup state or the NL2BR requirement were not stored accordingly.</p>
<p>To prevent any further problems, you should either disable the nl2br plugin on entries globally or per entry within the "Extended properties" section of an entry, if you have the entryproperties plugin installed.</p>
<p><u>Generally advice:</u> The nl2br plugin only makes sense if you</p>
<ul>
    <li>do not use other markup plugins - or</li>
    <li>you do not use the WYSIWYG editor - or</li>
    <li>you only want to apply linebreak transformations on comments to your blog entries, and do not allow any possible markup of other plugins that you only use for blog entries.</li>
</ul>
<p>NL2BR is a short form word. Read as: Function NL to BR, <b>not</b> NL two BR!</p>');

