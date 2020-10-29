{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <meta name="generator" content="Serendipity Styx Edition">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
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
    <link rel="stylesheet" href="{serendipity_getFile file="css/bootstrap.min.css"}">
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{if $entry_id}
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}
{serendipity_hookPlugin hook="frontend_header"}
</head>
<body>
{else}
{serendipity_hookPlugin hook="frontend_header"}
{/if}
{if $is_raw_mode != true}
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <div class="container">
  <a class="navbar-brand homelink1" href="{$serendipityBaseURL}" title="{$head_title|default:$blogDescription}">{$blogTitle|truncate:80:" ..."}</a>
{if $template_option.use_corenav}

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#corenav" aria-controls="corenav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="corenav">
    <ul class="navbar-nav mr-auto">
{foreach $navlinks AS $navlink}
    {if $navlink.title != '' AND $navlink.href != ''}

        <li class="nav-item{if $currpage == $navlink.href OR $currpage2 == $navlink.href} active{/if}{if ($navlink.href|smartySubstr:null:-1) == '#'} dropdown menu-item-{$navlink@index}{/if}">
        {if ($navlink.href|smartySubstr:null:-1) == '#'}

            <a id="navbarDropdown-{$navlink@index}" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{$navlink.title}</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown-{$navlink@index}">
                <h6 class="dropdown-header">1st group header title</h6>
                <a class="dropdown-item" href="/your/url/to/staticpage/">- First</a>
                <div class="dropdown-divider"></div>
                <h6 class="dropdown-header">2cd group header title</h6>
                <a class="dropdown-item" href="#">- Some</a>
                <a class="dropdown-item" href="#">- Thing</a>
                <a class="dropdown-item" href="#">- Title</a>
            </div>
        {else}

            <a class="nav-link" href="{$navlink.href}">{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href} <span class="sr-only">(current)</span>{/if}</a>
        </li>
        {/if}
    {/if}
{/foreach}
    </ul>
    <span class="nav-down">
      <a href="#to-sdb" title="jumpscroll to sidebar">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-square" xmlns="http://www.w3.org/2000/svg" fill="rgba(255,255,255,.5)">
          <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"></path>
        </svg>
      </a>
    </span>
    <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search" class="form-inline my-2 my-lg-0">
        <input type="hidden" name="serendipity[action]" value="search">
        <input type="hidden" name="serendipity[category]" value="5">
        <input id="serendipityQuickSearchTermField" class="form-control mr-sm-2" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.B45_PLACE_SEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
        <input id="searchsend" class="btn btn-outline-primary my-2 my-sm-0" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
    </form>
  </div>
{else}
  <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search" class="form-inline my-2 my-lg-0">
    <input type="hidden" name="serendipity[action]" value="search">
    <input type="hidden" name="serendipity[category]" value="5">
    <input id="serendipityQuickSearchTermField" class="form-control mr-sm-2" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.B45_PLACE_SEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
    <input id="searchsend" class="btn btn-outline-primary my-2 my-sm-0" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
  </form>
  <span class="nav-down">
    <a href="#to-sdb" title="jumpscroll to sidebar">
      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-square" xmlns="http://www.w3.org/2000/svg" fill="rgba(255,255,255,.5)">
        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"></path>
      </svg>
    </a>
  </span>
{/if}
    {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
  </div>
</nav>

<main role="main" class="container">
    <a id="to-top"></a>
    {if NOT empty($CONTENT)}

    {$CONTENT}
    {else if $view == '404'}

    <p class="alert alert-dark alert-empty" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}

</main><!-- /.container -->

<aside class="col-xs-12 col-lg-12">
    <a id="to-sdb"></a>
{if $template_option.bs_rss}
    <section class="serendipity_plugin_rsslinks mb-3">
        <h3>{$CONST.SYNDICATE_THIS_BLOG}</h3>

        <ul class="plainList">
            <li>
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-rss-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">XML</title>
                  <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm1.5 2.5a1 1 0 0 0 0 2 8 8 0 0 1 8 8 1 1 0 1 0 2 0c0-5.523-4.477-10-10-10zm0 4a1 1 0 0 0 0 2 4 4 0 0 1 4 4 1 1 0 1 0 2 0 6 6 0 0 0-6-6zm.5 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <a href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">{$CONST.ENTRIES} RSS</a>
            </li>
            <li>
                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-rss-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                  <title id="title">XML</title>
                  <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm1.5 2.5a1 1 0 0 0 0 2 8 8 0 0 1 8 8 1 1 0 1 0 2 0c0-5.523-4.477-10-10-10zm0 4a1 1 0 0 0 0 2 4 4 0 0 1 4 4 1 1 0 1 0 2 0 6 6 0 0 0-6-6zm.5 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
                </svg>
                <a href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/comments.rss2">{$CONST.COMMENTS} RSS</a>
            </li>
        </ul>
    </section>
{/if}
{if $leftSidebarElements > 0}{serendipity_printSidebar side="left"}{/if}
{if $rightSidebarElements > 0}{serendipity_printSidebar side="right"}{/if}
    <section class="serendipity_plugin_upscroll mb-3">
        <span class="sidebar-up">
          <a href="#to-top" title="jumpscroll to top">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-up-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
              <path fill-rule="evenodd" d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5z"/>
            </svg>
          </a>
        </span>
    </section>
</aside>

<footer id="footer" class="clearfix">
    <p lang="en">Powered by <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
</footer>

<script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
<script src="{serendipity_getFile file="js/bootstrap.min.js"}"></script>
<script src="{serendipity_getFile file="js/theme.js"}"></script>
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}

</body>
</html>
{/if}
