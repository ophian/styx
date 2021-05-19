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
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
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
<div class="container">
  <header class="blog-header py-3">
    <div class="row flex-nowrap justify-content-between align-items-center">
      <div class="col-4 pt-1">
        <a class="link-secondary" href="#">Subscribe</a>
      </div>
      <div class="col-4 text-center">
        <h1><a class="blog-header-logo text-dark" href="{$serendipityBaseURL}">{$blogTitle}</a></h1>
        <p>{$blogDescription}</p>
      </div>
      <div class="col-4 d-flex justify-content-end align-items-center">
        <a class="link-secondary" href="#" data-bs-toggle="modal" data-bs-target="#quicksearch" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>
        <a class="btn btn-sm btn-outline-secondary" href="#">Sign up</a>
      </div>
    </div>
  </header>
{if $template_option.use_corenav}

  <div class="nav-scroller py-1 mb-2">
    <nav class="nav d-flex justify-content-between">
      {foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}{/if}{/foreach}
    </nav>
  </div>
{/if}

  <main class="container mb-4">
  {* FEATURED BLOG POST container *}
    <div class="p-4 p-md-5 mb-4 text-white rounded bg-dark">
      <div class="col-md-6 px-0">
        <h1 class="display-4 fst-italic">Title of a longer featured blog post</h1>
        <p class="lead my-3">Multiple lines of text that form the lede, informing new readers quickly and efficiently about what’s most interesting in this post’s contents.</p>
        <p class="lead mb-0"><a href="#{* Point to a blog post *}" class="text-white fw-bold">Continue reading...</a></p>
      </div>
    </div>
{* GRID CARD POSTS container *}
    <div class="row mb-2">
{* LEFT GRID CARD *}
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary">World</strong>
            <h3 class="mb-0">Featured post</h3>
            <div class="mb-1 text-muted">Nov 12</div>
            <p class="card-text mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
            <a href="#{* Point to a blog post *}" class="stretched-link">Continue reading</a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
          </div>
        </div>
      </div>
{* RIGHT GRID CARD *}
      <div class="col-md-6">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success">Design</strong>
            <h3 class="mb-0">Post title</h3>
            <div class="mb-1 text-muted">Nov 11</div>
            <p class="mb-auto">This is a wider card with supporting text below as a natural lead-in to additional content.</p>
            <a href="#{* Point to a blog post *}" class="stretched-link">Continue reading</a>
          </div>
          <div class="col-auto d-none d-lg-block">
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-5">
      <div class="col-md-8">
{* BLOG POSTS TITLE headline *}
        <h3 class="pb-4 mb-4 fst-italic border-bottom">
          From the Styx Firehose
        </h3>

        {$CONTENT}

      </div>

      <div class="col-md-4">
        <div class="position-sticky" style="top: 2rem;">
{* SIDEBAR ABOUT BOX container *}
          <div class="p-4 mb-3 bg-light rounded">
            <h4 class="fst-italic">About</h4>
            <p class="mb-0">Customize this section to tell your visitors a little bit about your publication, writers, content, or something else entirely. Totally up to you.</p>
          </div>

          {serendipity_printSidebar side="left"}
          {serendipity_printSidebar side="right"}

        </div>
      </div>
    </div>

  </main>

  <footer>
    <p lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
  </footer>

{if $view == 'archive'}
  <script src="{serendipity_getFile file="b5/js/bootstrap.min.js"}"></script>{* bootstrap 5 does not need jquery lib any more *}
{/if}
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
</div>

</body>
</html>
{/if}