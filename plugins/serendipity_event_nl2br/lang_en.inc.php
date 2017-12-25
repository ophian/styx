<?php

/**
 *  @version
 *  @author Translator Name <yourmail@example.com>
 *  EN-Revision: Revision of lang_en.inc.php
 */

@define('PLUGIN_EVENT_NL2BR_NAME', 'Markup: NL2BR');
@define('PLUGIN_EVENT_NL2BR_DESC', 'Convert newlines to BR tags');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP', 'Check other markup plugins?');
@define('PLUGIN_EVENT_NL2BR_CHECK_MARKUP_DESC', 'Automatically check existing markup plugins to disable the use of NL2BR plugin. This is true, when WYSIWYG or specific markup plugins are detected.');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS', 'Exceptions to this rule');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS_DESC', 'A list of HTML-tags where no breaks shall be converted, if using p-tags. Suggestion: "code,pre,geshi,textarea". Seperate multiple tags with a comma. Hint: The entered tags are evaluated as regular expressions.');
@define('PLUGIN_EVENT_NL2BR_PTAGS', 'Use p-tags');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC', 'Insert p-tags instead of br.');
@define('PLUGIN_EVENT_NL2BR_PTAGS_DESC2', 'This may break with some non-simple markup cases!');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG', 'ISOBR isolations-default BR setting');
@define('PLUGIN_EVENT_NL2BR_ISOBR_TAG_DESC', 'With this unoperational NON-HTML-Tag <nl> </nl>, as a NL2BR Isolations-Default setting, you can use the NL2BR function now by shutting down the text parsing inside this tag. You can use it multiple times inside your entry, but not nested! Example: <nl>do not parse newline to br inside inside this textblock</nl>');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS', 'Use BR-Clean-Tags as fallback, when ISOBR false');
@define('PLUGIN_EVENT_NL2BR_CLEANTAGS_DESC', 'If using <HTML-Tags> in you entries, which can\'t be solved satisfiable with the ISOBR Config-Option, remove nl2br after <tag>. This applies to all <tags> ending with > or >\n! Default (table|thead|tbody|tfoot|th|tr|td|caption|colgroup|col|ol|ul|li|dl|dt|dd)');
@define('PLUGIN_EVENT_NL2BR_CONFIG_ERROR', 'Config missmatch alert! The Option: "%s" is set back to false, while  \'%s\' is active! Just use one of them, please.');

@define('PLUGIN_EVENT_NL2BR_ABOUT_TITLE', 'PLEASE NOTE the implications of this markup plugin:');
@define('PLUGIN_EVENT_NL2BR_ABOUT_DESC', '<p>This plugin transfers linebreaks to HTML-linebreaks, so that they show up in your blog entry.</p>
<p>In two cases this can raise problematic issues for you:</p>
<ul>
    <li>previously, if you used a <strong>WYSIWYG editor</strong> to write your entries. In that case, the WYSIWYG editor already placed proper HTML linebreaks, so the nl2br plugin would have actually doubled those linebreaks. Since <strong>Serendipity 2.0</strong> you don\'t need to take care about this any more, in blog entries and static pages, since nl2br parsing is automatically disabled.</li>
    <li>if you use any other markup plugins in conjunction with this plugin that already translate linebreaks. The <strong>TEXTILE</strong> and <strong>MARKDOWN</strong> plugins are examples for plugins like these.</li>
</ul>
<p>To prevent problems, you should disable the nl2br plugin on entries globally or per entry within the "Extended properties" section of an entry, if you have the entryproperties plugin installed.</p>
<p><u>Generally advice:</u> The nl2br plugin only makes sense if you</p>
<ul>
    <li>do not use other markup plugins - or</li>
    <li>you do not use the WYSIWYG editor - or</li>
    <li>you only want to apply linebreak transformations on comments to your blog entries, and do not allow any possible markup of other plugins that you only use for blog entries.</li>
</ul>
<p>NL2BR is a short form word. Read as: Funktion NL to BR, <b>not</b> NL two BR!</p>');

