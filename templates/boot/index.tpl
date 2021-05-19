{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition">
{if in_array($view, ['start', 'entries', 'entry', 'feed', 'plugin']) OR NOT empty($staticpage_pagetitle) OR (isset($robots_index) AND $robots_index == 'index')}
    <meta name="robots" content="index,follow">
{else}
    <meta name="robots" content="noindex,follow">
{/if}
    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
{if $view == 'entry' AND isset($entry)}
    <link rel="canonical" href="{$entry.rdf_ident}">
{/if}
{if in_array($view, ['start', 'entries'])}
    <link rel="canonical" href="{$serendipityBaseURL}">
{/if}
    <link rel="stylesheet" href="{serendipity_getFile file="b5/css/bootstrap.min.css"}">
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
    <!-- Modal -->
    <div class="modal fade" id="quicksearch" tabindex="-1" aria-labelledby="{$CONST.QUICKSEARCH}" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <form class="navbar-form" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search">
              <input type="hidden" name="serendipity[action]" value="search">
              <div class="mb-3">
                <h5 class="modal-title col-form-label">Styx {$CONST.QUICKSEARCH}</h5>
                <input id="styxQuickSearchTermField" class="form-control" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.BS_PLACEHOLDER_QUICKSEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <header>
        <h1><a href="{$serendipityBaseURL}">{$blogTitle}</a></h1>

        <p>{$blogDescription}</p>
    </header>
{if $template_option.use_corenav}

    <nav id="navigator" class="nav d-flex justify-content-between">
        <ul>{foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}<li>{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}</li>{/if}{/foreach}
        <li class="link-secondary" href="#" data-bs-toggle="modal" data-bs-target="#quicksearch" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </li>
        </ul>
    </nav>
{/if}

    <main id="content" class="container">
        {$CONTENT}
    </main>
{if $leftSidebarElements > 0}

    <aside id="serendipityLeftSideBar" class="clearfix">
    {serendipity_printSidebar side="left"}
    </aside>
{/if}
{if $rightSidebarElements > 0}

    <aside id="{if $leftSidebarElements > 0}serendipityRightSideBar{else}serendipityLeftSideBar{/if}" class="clearfix">
    {serendipity_printSidebar side="right"}
    </aside>
{/if}

    <footer>
        <p lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </footer>

    <script src="{serendipity_getFile file="b5/js/bootstrap.min.js"}"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script src="{serendipity_getFile file="theme.js"}"></script>
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}

{if $view == 'entry' AND $wysiwyg_comment AND NOT (isset($smarty.get.serendipity.csuccess) AND $smarty.get.serendipity.csuccess == 'true') && (isset($entry) AND NOT $entry.allow_comments === false)}

    <script src="{serendipity_getFile file="ckebasic/ckeditor.js"}"></script>
    <script src="{serendipity_getFile file="ckebasic/config.js"}"></script>
    <script>
        window.onload = function() {
            var cfmco = document.getElementById('serendipity_commentform_comment');
            if (typeof(cfmco) != 'undefined' && cfmco != null) {
                CKEDITOR.replace( cfmco, { toolbar : [['Bold','Italic','Underline','-','NumberedList','BulletedList','Blockquote'],['CodeSnippet'],['EmojiPanel']] });
            }
        }
    </script>
    {assign var="hljsload" value=true}
{/if}
{if (in_array($view, ['start', 'entries', 'entry', 'comments', 'categories']) AND $wysiwyg_comment) OR isset($hljsload) && $hljsload === true}

    <link rel="stylesheet" href="{serendipity_getFile file="highlight/github.min.css"}">
    <script src="{serendipity_getFile file="highlight/highlight.min.js"}"></script>
    <script>
        // launch the codesnippet highlight
        hljs.configure({
          tabReplace: '    ', // 4 spaces
        });
        hljs.initHighlightingOnLoad();
    </script>
{/if}

</body>
</html>
{/if}
