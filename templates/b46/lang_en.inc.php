<?php
// Theme options
@define('B46_USE_CORENAV', 'Use global navigation?');
@define('B46_USE_SEARCH', 'Use quicksearch in the navigation head bar?');
@define('B46_JUMPSCROLL', 'Use scroll button jumper?');
@define('B46_HUGO', 'Article [text] as toggle summary teaser with length');
@define('B46_HUGO_TTT', 'When over, click mouse or toggle by keyboards space-bar when set active');
@define('B46_HUGO_TITLE_ELSE', 'Open entry');
@define('B46_TEASE', '0 means: Not set!');
@define('B46_TEASE_COND', ' - Integers only & choose type either/or!'); // start with space!
@define('B46_CARD', 'Article [text] as a Grid-Card teaser with length');
@define('B46_CARD_META', ' The card subhead "user & date" 1-line meta data may get too long and then masked out hidden truncated. Please check up your personal length need by using short usernames and/or by this configuration upper dateformat option.'); // start with space!
@define('B46_CARD_TITLE_ELSE', 'No entry summary text available');
@define('B46_LEAD', 'By teaser type, add a top featured article');
@define('B46_LEAD_DESC', ' Or use this "URI" alike plain text string "array" representation, to fill in the text (with unchanged keys) - "title" and "text" keys are mandatory: '); // start with space!
@define('B46_NAV_ONELINE', 'Navigation as independent line?');

// If used within template files, add previously parent theme defines here, since the lang CONSTANT engine:fallback will work only for the config.inc file up from Styx 3.3.1!
@define('BS_REPLYORIGIN', 'Origin');

// Lang constants non-translate
@define('B46_PLACE_SEARCH', 'Search term(s)');
@define('B46_SEND_MAIL', 'Send email');

@define('B46_INSTR', '<details><summary role="button" aria-expanded="false">B46: Click me to open extended helper information Readme</summary>
<br><b>NOTE:</b> "b46" is an upgrade Engine:Template of the "bootstrap4" theme.
<ul>
    <li>For custom theme styling of entries freetags, enable the "Extended Smarty" option in the event freetag plugin.</li>
    <li>If you leave the "#" for the URL of a navigation-link, a popover submenu can be created manually in the index.tpl main template file. Example inside.</li>
</ul>
<b>Featured Article Pro Tip:</b>
<ul>
    <li>Create a new category with the name "feature".<br>Explain yourself in the description (for later) what it means, e.g. "A special category for temporary featured articles (b46)". Save the new category.</li>
    <li>Then install the plugin "Properties/Templates of categories (serendipity_event_categorytemplates)" Event Plugin and configure it with the default properties ( <em>No, timestamp DESC, No, Empty </em>).</li>
    <li>Go back to the category list and call the newly created category "feature" for editing. You now have a new options section at the bottom called "'.sprintf(ADDITIONAL_PROPERTIES_BY_PLUGIN, "Properties/Templates of categories").'". Activate the final option in it to exclude the category from entry lists and RSS feeds in the future. ( <em>You may notice in the further that a duplicate setting will be activated in the following at the entryform as well. But <u>this</u> plugin setting would additionally help with other lists/links like for the entrypaging plugin. Have your own experiments with it if in need at all.</em> )</li>
    <li>Then create an entry that will be linked as a featured article here in these theme options in "'.B46_LEAD.'". Make sure to assign this entry only to the category "feature" and to check "Do not show in article overview" and (optionally) "Hide entry content in RSS feed" in the "Extended properties for entries" section of the entry form.</li>
    <li>Simply use the article preview to copy the link to the article (right-click the title with mouse and "Save link"). Insert the copied link where "#" lives as "url=#" in the following shortened example array: "<em>image=/uploads/features/fa_1.webp&height=350px&title=My first longer featured blog post with ID 1&text=Summary feature of my post contents.&url=#&link=Continue reading...</em>" (see a full functional demo example in the info text of option "'.B46_LEAD.'" down below).</li>
</ul>
<p>Et voila ! Your promoted "featured article" will now link to the featured article as a single full article. Otherwise, it will only be link-accessible via the archives/category link in the blog. If at some point this article is back demoted for another/new one as a normal article, you have to only change the category in the article itself and uncheck the two ckeckboxes in the "Extended properties for entries" (as described above). As you can see, you now are able to enable a different way of dealing with "sticky articles" with this theme option, which are otherwise of course part of Serendipity\'s standard repertoire in the "Advanced article properties".</p>
</details>');

