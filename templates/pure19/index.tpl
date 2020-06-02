{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
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
{if in_array($view, ['start', 'entries'])}{* 'archives' *}
    <link rel="canonical" href="{$serendipityBaseURL}">
{/if}
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{if $entry_id}
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}
{serendipity_hookPlugin hook="frontend_header"}
</head>
<body class="grid-{if $leftSidebarElements > 0 AND $rightSidebarElements > 0}col{elseif $leftSidebarElements > 0 OR $rightSidebarElements > 0}flex{else}block{/if}">
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}
{if $is_raw_mode != true}

    <header id="serendipity_banner"><a id="topofpage"></a>
        <h1><a class="homelink1" href="{$serendipityBaseURL}">{$head_title|default:$blogTitle|truncate:80:" ..."}</a></h1>
        <h2><a class="homelink2" href="{$serendipityBaseURL}">{$head_subtitle|default:$blogDescription}</a></h2>
{if $template_option.use_corenav === true}

        <nav id="navbar">
            <ul>{foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}<li{if $navlink@last} class="last"{/if}>{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}</li>{/if}{/foreach}
                <li class="navsearch">
                    <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
                    <div>
                        <input type="hidden" name="serendipity[action]" value="search">
                        <label for="serendipityQuickSearchTermField">{$CONST.QUICKSEARCH}</label>
                        <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.TWOK11_PLACE_SEARCH}" value="">
                        <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
                    </div>
                    </form>
                    {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
                </li>
            </ul>
        </nav>
{else}

        <nav id="headsearch">
            <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
            <div>
                <input type="hidden" name="serendipity[action]" value="search">
                <label for="serendipityQuickSearchTermField">{$CONST.QUICKSEARCH}</label>
                <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.TWOK11_PLACE_SEARCH}" value="">
                <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
            </div>
            </form>
            {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
        </nav>
{/if}
    </header>
{if $leftSidebarElements > 0}

    <aside id="serendipityLeftSideBar">
        {serendipity_printSidebar side="left"}
    </aside>
{/if}

    <main id="content">
        {$CONTENT}
    </main>
{if $rightSidebarElements > 0}

    <aside id="serendipityRightSideBar">
        {serendipity_printSidebar side="right"}
    </aside>
{/if}

    <footer id="footer">
        <p lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </footer>

{if $template_option.use_corenav === true}

    <footer id="menubar_mobile">
        <nav id="menu" >
            <button class="c-menu c-menu--htx" aria-live="polite">
                <span id="buttonname">Show Navigation</span>
            </button>
            <span id="menutxt" aria-hidden="true">{$CONST.NAV_MENU}</span>
        </nav>
    </footer>
{/if}

    <script src="{serendipity_getFile file="pure.js"}"></script>
{if $view == 'entry' AND $wysiwyg_comment AND NOT (isset($smarty.get.serendipity.csuccess) AND $smarty.get.serendipity.csuccess == 'true') && (isset($entry) AND NOT $entry.allow_comments === false)}
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/ckeditor.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/config.js"></script>
    <script>
        window.onload = function() {
            CKEDITOR.replace( 'serendipity_commentform_comment', { toolbar : [['Bold','Italic','Underline','-','NumberedList','BulletedList','Blockquote'],['CodeSnippet'],['EmojiPanel']] });
        }
    </script>
{/if}

{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
