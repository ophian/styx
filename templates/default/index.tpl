{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <title>{$head_title|default:$blogTitle} {if $head_subtitle} - {$head_subtitle}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset={$head_charset}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}">
{if in_array($view, ['start', 'entries', 'entry', 'feed', 'plugin']) OR NOT empty($staticpage_pagetitle) OR (isset($robots_index) AND $robots_index == 'index')}
    <meta name="robots" content="index,follow">
{else}
    <meta name="robots" content="noindex,follow">
{/if}
{if $view == 'entry' AND isset($entry)}
    <link rel="canonical" href="{$entry.rdf_ident}">
{/if}
{if in_array($view, ['start', 'entries'])}
    <link rel="canonical" href="{$serendipityBaseURL}">
{/if}
    <link rel="stylesheet" type="text/css" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">

{if $entry_id}
    <link rel="trackback" type="application/x-www-form-urlencoded" href="{$serendipityBaseURL}comment.php?type=trackback&amp;entry_id={$entry_id}">
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}

{serendipity_hookPlugin hook="frontend_header"}
</head>

<body>
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}

{if $is_raw_mode != true}
<div id="serendipity_banner">
    <h1><a class="homelink1" href="{$serendipityBaseURL}">{$head_title|default:$blogTitle}</a></h1>
    <h2><a class="homelink2" href="{$serendipityBaseURL}">{if $view == 'plugin'}{$blogDescription}{else}{$head_subtitle|default:$blogDescription}{/if}</a></h2>
</div>

<div id="mainpane" class="{if $leftSidebarElements == 0 AND $rightSidebarElements == 0}full{else}side-{/if}{if $leftSidebarElements > 0 AND $rightSidebarElements == 0}left{else if $rightSidebarElements > 0 AND $leftSidebarElements == 0}right{else}both{/if}">

    <div id="content">
        {$CONTENT}
    </div>
{if $leftSidebarElements > 0}

    <div id="serendipityLeftSideBar">
        {serendipity_printSidebar side="left"}
    </div>
{/if}
{if $rightSidebarElements > 0}

    <div id="serendipityRightSideBar">
        {serendipity_printSidebar side="right"}
    </div>
{/if}

</div>
{/if}

{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}

{if $is_embedded != true}

    <script type="text/javascript" src="{serendipity_getFile file="default.js"}"></script>
</body>
</html>
{/if}
