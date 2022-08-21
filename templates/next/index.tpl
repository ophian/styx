{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
    <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
    <link rel="dns-prefetch" href="//ajax.googleapis.com">
{if $template_option.webfonts == 'osans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'ssans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:400italic,700italic,400,700">
{elseif $template_option.webfonts == 'rsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'lsans'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'mserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Merriweather:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'dserif'}
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Serif:400,400italic,700,700italic">
{/if}
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <script src="{serendipity_getFile file="scripts/modernizr/modernizr.js"}"></script>
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{if $entry_id}
    <link rel="trackback" type="application/x-www-form-urlencoded" href="{$serendipityBaseURL}comment.php?type=trackback&amp;entry_id={$entry_id}">
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}
{serendipity_hookPlugin hook="frontend_header"}
</head>
<body class="columns-{if $leftSidebarElements > 0 AND $rightSidebarElements > 0}3{else}2{/if}{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} {$template_option.webfonts}{/if}">
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}
{if $is_raw_mode != true}
    <header id="banner" class="clearfix">
        <a id="identity" href="{$serendipityBaseURL}">
            <h1>{$blogTitle}</h1>
            <span>{$blogDescription}</span>
        </a>

        <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search">
            <input type="hidden" name="serendipity[action]" value="search">
            <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.NEXT_PLACE_SEARCH}" value="">
            <label for="serendipityQuickSearchTermField"><span class="icon-search" aria-hidden="true"></span><span class="fallback-text">{$CONST.QUICKSEARCH}</span></label>
            <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
        </form>
        {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
    </header>
    {if $template_option.header_img}
    <div id="logo">
        <img src="{$template_option.header_img|escape}" alt="">
    </div>
    {/if}
    <div id="navbar">
    {if $template_option.use_corenav}
        <a id="open-nav" class="nav-toggle" href="#site-nav"><span class="icon-menu" aria-hidden="true"></span><span class="fallback-text">{$CONST.NEXT_NAVTEXT}</span></a>

        <nav id="site-nav" class="nav-collapse">
            <ul>{foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}<li>{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}</li>{/if}{/foreach}</ul>
        </nav>
    {/if}
    </div>

    <main id="primary">
    {$CONTENT}
    </main>
{if $leftSidebarElements > 0}
    <aside id="secondary" class="clearfix">
    {serendipity_printSidebar side="left"}
    </aside>
{/if}
{if $rightSidebarElements > 0}
    <aside id="{if $leftSidebarElements > 0}tertiary{else}secondary{/if}" class="clearfix">
    {serendipity_printSidebar side="right"}
    </aside>
{/if}

    <footer id="colophon" class="clearfix">
        <p lang="en">Powered by <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </footer>

    <script src="{serendipity_getFile file="scripts/master.js"}"></script>
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
