{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}" data-bs-theme="auto">
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
    <link rel="stylesheet" href="{serendipity_getFile file="b5/css/bootstrap.min.css"}" type="text/css">
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
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
<!-- Theme switch -->
<div class="dropdown position-fixed top-2 end-0 me-3 bd-mode-toggle">
<button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)">
  <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
  <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
</button>
<ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
  <li>
    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
      <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
      Light
      <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
    </button>
  </li>
  <li>
    <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
      <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
      Dark
      <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
    </button>
  </li>
  <li>
    <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
      <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
      Auto
      <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
    </button>
  </li>
</ul>
</div>
<!-- Blog Container -->
<div class="container-fluid">
  <header class="container-xl blog-header lh-1 py-3 border-bottom">
    <div class="row flex-nowrap justify-content-start align-items-center">
      <div class="col-3 d-flex pt-1">
        <a class="link-secondary" href="{$serendipityBaseURL}feeds/index.rss2" title="{$CONST.SUBSCRIBE_TO_BLOG}"><svg class="bi m-0" width="36" height="36" role="img" aria-labelledby="title"><title id="sycrss">XML</title><use xlink:href="#rss-fill"></use></svg></a>
        <a class="link-home" href="{$serendipityBaseURL}" title="home"><svg class="bi ms-3" width="32" height="32" role="img" aria-labelledby="title"><title id="home">Home</title><use xlink:href="#start"></use></svg></a>
      </div>
      <div class="col-6 text-center text-truncate">
        <h1><a class="blog-header-logo text-dark" href="{$serendipityBaseURL}">{$blogTitle}</a></h1>
        <p>{$blogDescription}</p>
      </div>
      <div class="col-3 d-flex justify-content-end align-items-center">
        <a class="link-secondary" href="#" data-bs-toggle="modal" data-bs-target="#quicksearch" aria-label="Search">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="me-3" role="img" viewBox="0 0 24 24"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
        </a>
        <a class="btn btn-sm btn-outline-secondary" href="{$serendipityBaseURL}admin">{$CONST.LOGIN}</a>
      </div>
    </div>
  </header>
{if $template_option.use_corenav}

  <div class="container-xl nav-scroller py-1 mb-2 border-bottom">
    <nav class="nav nav-underline d-flex justify-content-between">
      {foreach $navlinks AS $navlink}{if $navlink.title != "" AND $navlink.href != ""}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}<span>{else}<a class="nav-item nav-link link-body-emphasis" href="{$navlink.href}">{/if}{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href}</span>{else}</a>{/if}{/if}{/foreach}

    </nav>
  </div>
{/if}

  <main class="container-lg mb-4">
{* FEATURED BLOG POST container *}
{if $template_option.featured != 0 AND in_array($view, ['start', 'entries'])}
    <div class="p-4 p-md-5 mb-4 rounded text-bg-dark">
      <div class="col-md-6 px-0">
        <h1 class="display-4 fst-italic">{$red.title|default:'Title of a longer featured blog post'}</h1>
        <p class="lead my-3">{$red.body|strip|strip_tags|truncate:280:'...'|default:'Multiple lines of text that form the lede, informing new readers quickly and efficiently about what’s most interesting in this post’s contents.'}</p>
        <p class="lead mb-0"><a href="{$serendipityArchiveURL}/{$red.id|default:'#'}-{$red.title|default:''}.html" class="text-white fw-bold">Continue reading...</a></p>
      </div>
    </div>
{/if}
{* GRID CARD POSTS container *}
    <div class="row mb-2">
{* LEFT GRID CARD *}
{if $template_option.cardone != 0 AND in_array($view, ['start', 'entries'])}
      <div class="col-md-6">
        <div class="card row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-primary capitalize">{if NOT isset($cal.categories.0)}World{else}{$cal.categories.0.category_name|default:'World'}{/if}</strong>
            <h3 class="mb-0">{$cal.title|truncate:30:''}</h3>
            <div class="mb-1 text-muted">{$cal.timestamp|formatTime:'%B %e'}</div>
            <p class="card-text mb-auto">{$cal.body|strip|strip_tags|truncate:80:'...'}</p>
            <a href="{$serendipityArchiveURL}/{$cal.id}-{$cal.title}.html" class="stretched-link">Continue reading</a>
          </div>
          <div class="col-auto d-none d-lg-block">
{if $template_option.cot == 0}
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
{else}
            <img class="bd-placeholder-img" width="200" height="250" src="{$template_option.cot|default:0}">
{/if}
          </div>
        </div><!-- inner row end -->
      </div><!-- card col end -->
{/if}
{* RIGHT GRID CARD *}
{if $template_option.cardtwo != 0 AND in_array($view, ['start', 'entries'])}
      <div class="col-md-6">
        <div class="card row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
          <div class="col p-4 d-flex flex-column position-static">
            <strong class="d-inline-block mb-2 text-success capitalize">{if NOT isset($car.categories.0)}Design{else}{$car.categories.0.category_name|default:'Design'}{/if}</strong>
            <h3 class="mb-0">{$car.title|truncate:30:''}</h3>
            <div class="mb-1 text-muted">{$car.timestamp|formatTime:'%B %e'}</div>
            <p class="mb-auto">{$car.body|strip_tags|strip|truncate:80:'...'}</p>
            <a href="{$serendipityArchiveURL}/{$car.id}-{$car.title}.html" class="stretched-link">Continue reading</a>
          </div>
          <div class="col-auto d-none d-lg-block">
{if $template_option.ctt == 0}
            <svg class="bd-placeholder-img" width="200" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"/><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>
{else}
            <img class="bd-placeholder-img" width="200" height="250" src="{$template_option.ctt|default:0}">
{/if}
          </div>
        </div><!-- inner row end -->
      </div><!-- card col end -->
{/if}
    </div><!-- card row end -->

    <div class="row gy-5 pe-0">
      <div class="col-md-8">
{* BLOG POSTS TITLE headline *}
{if $template_option.title != 0 AND in_array($view, ['start', 'entries'])}
        <h3 class="pb-4 mb-4 fst-italic border-bottom">
          {$template_option.title|default:'From the Styx Firehose'|escape}
        </h3>
{/if}

        {$CONTENT}

      </div><!-- col content end -->

      <div class="col-md-4 pe-0">
        <div class="position-flex">
{* SIDEBAR ABOUT BOX container *}
{if $template_option.about}
          <div class="p-4 mb-3 bg-body-tertiary rounded">
            <h4 class="fst-italic">{$template_option.abouttitle|default:''|escape}</h4>
            <p class="mb-0">{$template_option.abouttext|default:''|escape}</p>
          </div>
{/if}

          {serendipity_printSidebar side="left"}
          {serendipity_printSidebar side="right"}

        </div>
      </div><!-- col sidebar end -->
    </div><!-- row content end  -->

  </main>

  <footer>
    <div class="container">
      <p class="float-end mb-1"><a href="#" title="back to top"><svg class="bi ms-auto" width="1em" height="1em"><use href="#back-to-top"></use></svg></a></p>
      <p class="text-center" lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </div>
  </footer>

  <script src="{serendipity_getFile file="b5/js/bootstrap.bundle.min.js"}"></script>{* bootstrap 5 does not need jquery lib any more *}
  <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>{* The bad, others like lightbox etc do need it though ! *}

  <script> const themePath = '{$serendipityHTTPPath}{$templatePath}{$template}';</script>
  <script src="{serendipity_getFile file="theme.js"}"></script>
{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
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
  <link rel="stylesheet" href="{serendipity_getFile file="highlight/github-boot.min.css"}" type="text/css">
  <script src="{serendipity_getFile file="highlight/highlight.min.js"}"></script>
  <script>
    // launch the code snippet highlight
    hljs.configure({
      tabReplace: '    ', // 4 spaces
      ignoreUnescapedHTML: true, // We already have it escaped!
    });
    hljs.highlightAll();
  </script>
{/if}
  <script>
    /* allows to treat the theme mode SVGs placement top on height */
    $(function () {
      $(document).scroll(function () {
        var $nav = $(".top-2");
        $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
      });
    });
  </script>
</div>

{* Bootstrap [theme switch icons: circle*, moon*, sun*, check2]
           - [global icons: grip, info*, pencil*, rss*, *top]
           - [header icons: start]
           - [alert icons 1: success, 2: info, 3: error/warning]
           - [form icons: *asterisk, check-fill] *}
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="circle-half" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
  </symbol>
  <symbol id="moon-stars-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
    <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
  </symbol>
  <symbol id="sun-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
  </symbol>
  <symbol id="check2" viewBox="0 0 16 16">
    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
  </symbol>
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
  <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
  </symbol>
  <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </symbol>
  <symbol id="grip-horizontal" fill="currentColor" viewBox="0 0 16 16">
    <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
  </symbol>
  <symbol id="pencil-square" fill="currentColor" viewBox="0 0 16 16">
    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
  </symbol>
  <symbol id="rss-fill" fill="currentColor" viewBox="0 0 16 16">
    <path fill-rule="evenodd" d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm1.5 2.5a1 1 0 0 0 0 2 8 8 0 0 1 8 8 1 1 0 1 0 2 0c0-5.523-4.477-10-10-10zm0 4a1 1 0 0 0 0 2 4 4 0 0 1 4 4 1 1 0 1 0 2 0 6 6 0 0 0-6-6zm.5 7a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/>
  </symbol>
  <symbol id="start" fill="var(--bs-secondary-color)" viewBox="0 0 16 16">
    <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5z"/>
  </symbol>
  <symbol id="required-field-asterisk" fill="red" viewBox="0 0 16 16">
    <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
  </symbol>
  <symbol id="back-to-top" fill="currentColor" class="bi bi-arrow-up-square" viewBox="0 0 16 16">
    <path d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm8.5 9.5a.5.5 0 0 1-1 0V5.707L5.354 7.854a.5.5 0 1 1-.708-.708l3-3a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1-.708.708L8.5 5.707z"/>
  </symbol>
</svg>

</body>
</html>
{/if}