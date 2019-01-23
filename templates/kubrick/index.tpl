{if $is_embedded != true}
{if $is_xhtml}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
           "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
{else}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
           "http://www.w3.org/TR/html4/loose.dtd">
{/if}

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
    <title>{$head_title|default:$blogTitle} {if $head_subtitle} - {$head_subtitle}{/if}</title>
    <meta http-equiv="Content-Type" content="text/html; charset={$head_charset}" />
    <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
{if in_array($view, ['start', 'entries', 'entry', 'feed', 'plugin']) OR NOT empty($staticpage_pagetitle) OR (isset($robots_index) AND $robots_index == 'index')}
    <meta name="robots" content="index,follow" />
{else}
    <meta name="robots" content="noindex,follow" />
{/if}
{if $view == 'entry' AND isset($entry)}
    <link rel="canonical" href="{$entry.rdf_ident}" />
{/if}
{if in_array($view, ['start', 'entries'])}
    <link rel="canonical" href="{$serendipityBaseURL}" />
{/if}
    <link rel="stylesheet" type="text/css" href="{$head_link_stylesheet}" />
    <link rel="alternate"  type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2" />
    <link rel="alternate"  type="application/x.atom+xml"  title="{$blogTitle} Atom feed"  href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml" />
{if $entry_id}
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}" />
{/if}

{serendipity_hookPlugin hook="frontend_header"}
</head>

<body>
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}
<div id="page">
<div id="header" onclick="location.href='{$serendipityBaseURL}';" style="cursor: pointer;">
    <div id="headerimg">
        <h1>{$head_title|default:$blogTitle}</h1>
        <div class="description">{if $view == 'plugin'}{$blogDescription}{else}{$head_subtitle|default:$blogDescription}{/if}</div>
    </div>
</div>
<hr />

<div id="content" class="narrowcolumn">
    {$CONTENT}
</div>

{if $rightSidebarElements > 0}
    <div id="sidebar">
    {serendipity_printSidebar side="right"}
    {serendipity_printSidebar side="left"}
    </div>
{/if}

<hr />
<div id="footer">
    <p>
    {$CONST.PROUDLY_POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a>.<br />
    Design is <a href="http://binarybonsai.com/kubrick/">Kubrick</a>, by Michael Heilemann, ported by <a href="http://blog.dreamcoder.dk">Tom Sommer</a>.
    </p>
</div>

</div>
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
