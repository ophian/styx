{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
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
{if $template_option.use_googlefonts}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,300,600">
{/if}
    <link rel="stylesheet" href="{$head_link_stylesheet}">
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
    <header class="page_header" role="banner">
        <div class="container">
            <div class="row">
                <h1 class="twelve columns"><a href="{$serendipityBaseURL}">{$blogTitle}</a></h1>
            </div>
        </div>
    </header>
    {if $template_option.use_corenav}
    <nav class="primary-nav" role="navigation">
        <div class="container">
            <div class="row">
                <ul class="twelve columns plainList">{foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}<li>{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}</li>{/if}{/foreach}</ul>
            </div>
        </div>
    </nav>
    {/if}
    <div class="container">
        <div class="row">
            <main class="{if $view == 'entry' AND $entry_id}twelfe{else}eight{/if} columns" role="main">
            {$CONTENT}
            </main>

            <aside class="{if $view == 'entry' AND $entry_id}twelfe{else}four{/if} columns" role="complementary">
                <section class="sidebar_widget sidebar_search">
                    <h3><label for="serendipityQuickSearchTermField">{$CONST.QUICKSEARCH}</label></h3>

                    <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search">
                        <input type="hidden" name="serendipity[action]" value="search">
                        <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.SKELETON_PLACE_SEARCH}" value="">
                        <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
                    </form>
                    {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
                </section>
            {if $leftSidebarElements > 0}{serendipity_printSidebar side="left"}{/if}
            {if $rightSidebarElements > 0}{serendipity_printSidebar side="right"}{/if}
            </aside>
        </div>
    </div>

    <footer class="page-footer" role="contentinfo">
        <div class="container">
            <div class="row">
                <p class="twelve columns credit">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a></p>
            </div>
        </div>
    </footer>
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
