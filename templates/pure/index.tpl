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
{if in_array($view, ['start', 'entries'])}
    <link rel="canonical" href="{$serendipityBaseURL}">
{/if}
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{if $entry_id}
    <link rel="trackback" type="application/x-www-form-urlencoded" href="{$serendipityBaseURL}comment.php?type=trackback&amp;entry_id={$entry_id}">
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}
{serendipity_hookPlugin hook="frontend_header"}
</head>
<body class="grid-{if $leftSidebarElements > 0 AND $rightSidebarElements > 0}col{elseif $leftSidebarElements > 0 OR $rightSidebarElements > 0}flex{else}block{/if}">
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}
{if $is_raw_mode != true}

    <button id="blink" class="navbar-shader btn float" onclick="dark()" title="Theme: Dark (Browser preferences|Session override)">
        <img id="daynight" src="{$serendipityHTTPPath}{$templatePath}{$template}/icons/moon-fill.svg" width="30" height="30" alt="">
    </button>

    <header id="serendipity_banner"><a id="topofpage"></a>
        <h1><a class="homelink1" href="{$serendipityBaseURL}">{$head_title|default:$blogTitle|truncate:80:" ..."}</a></h1>
        <h2><a class="homelink2" href="{$serendipityBaseURL}">{$head_subtitle|default:$blogDescription}</a></h2>
{if $template_option.use_corenav === true}

        <nav id="navbar">
            <ul>
{foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}
                <li{if $navlink@last} class="last"{/if}>{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}</li>
{/if}{/foreach}
                <li class="navsearch">
                    <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
                    <div>
                        <input type="hidden" name="serendipity[action]" value="search">
                        <label for="serendipityQuickSearchTermField">{$CONST.QUICKSEARCH}</label>
                        <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.PURE_PLACE_SEARCH}" value="">
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
                <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.PURE_PLACE_SEARCH}" value="">
                <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
            </div>
            </form>
{serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
        </nav>
{/if}

    </header>
{*
 NOTE: serendipity_printSidebar content spawned for tripleViewIndents per default
 CONTENT aligned left by default
*}
{if $tripleViewIndent}

    <section class="grid">

        <main id="content">
{if NOT empty($CONTENT)}

            {$CONTENT}
{else if $view == '404'}

            <p class="msg_notice"><span class="ico icon-info" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
{/if}

        </main>
{if $leftSidebarElements > 0}

        <aside id="serendipityLeftSideBar">
{serendipity_printSidebar side="left"}
        </aside>
{/if}
{if $rightSidebarElements > 0}

        <aside id="serendipityRightSideBar">
{serendipity_printSidebar side="right"}
        </aside>
{/if}

    </section>
{else}

    <main id="content">
{if NOT empty($CONTENT)}

        {$CONTENT}
{else if $view == '404'}

        <p class="msg_notice"><span class="ico icon-info" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
{/if}

    </main>
{if $leftSidebarElements > 0}

    <aside id="serendipityLeftSideBar">
        {serendipity_printSidebar side="left"}
    </aside>
{/if}
{if $rightSidebarElements > 0}
    <aside id="serendipityRightSideBar">
        {serendipity_printSidebar side="right"}
    </aside>
{/if}
{/if}

    <footer id="footer">
        <p lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </footer>
{if $template_option.use_corenav === true || $template_option.use_corenav == ''}

    <footer id="menubar_mobile">
        <nav id="menu" >
            <button class="c-menu c-menu--htx" aria-live="polite">
                <span id="buttonname">Show Navigation</span>
            </button>
            <span id="menutxt" aria-hidden="true">{$CONST.NAV_MENU}</span>
        </nav>
    </footer>
{/if}

    <script> const themePath = '{$serendipityHTTPPath}{$templatePath}{$template}'; </script>
    <script src="{serendipity_getFile file="pure.js"}"></script>
{if ($view == 'entry' AND $wysiwyg_comment AND NOT (isset($smarty.get.serendipity.csuccess) AND $smarty.get.serendipity.csuccess == 'true') && (isset($entry) AND NOT ($entry.allow_comments === false))) OR (($view == 'plugin' OR $view == 'start') AND $head_title == 'contactform')}

    <script> const styxPath = '{$serendipityHTTPPath}'; </script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/prism/prism.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/tinymce6/basicEditor.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/tinymce6/js/tinymce/tinymce.min.js"></script>
    <script>
        window.onload = function() {
            var coco = document.getElementById('serendipity_commentform_comment');
            if (typeof(coco) != 'undefined' && coco != null) {
                tinymce.init({
                    selector: '#serendipity_commentform_comment',
                    setup: (editor) => {},
                      ...basicConfig
                });
            }
        }

        // the tinymce auto_focus behaves erratic based on focusable content, last edit and/or having to 2 textareas and so forth... - so better force an independent page re-focus here.
        var h = location.hash ; null;
        if (!h) {
            $(window).on('load', function () { $('html, body').animate({ scrollTop: 0 }, 'smooth'); });
        }
    </script>
{assign var="hljsload" value=true}
{/if}
{if (in_array($view, ['start', 'entries', 'entry', 'comments', 'categories', 'search', 'archives' ]) AND $template_option.use_highlight === true) OR isset($hljsload) && $hljsload === true}
    <script>
      const elements = document.querySelectorAll("pre");
      elements.forEach(item => {
        // Replace matching unknown enabled highlight class names
        item.classList.replace("language-smarty", "language-perl"); /* perl better than php else you may get unescaped HTM errors from highlightjs */
        item.classList.replace("language-log", "language-yaml"); /* -bash is good also */
        item.classList.replace("language-markup", "language-plaintext");
      })
    </script>
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}_assets/highlight/github-pure.min.css" type="text/css">
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/highlight/highlight.min.js"></script>
    <script>
        // launch the code snippets highlight
        hljs.configure({
          tabReplace: '    ', // 4 spaces
          ignoreUnescapedHTML: true, // We already have it escaped!
        });
        hljs.highlightAll();
    </script>
{/if}
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
