{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}" data-bs-theme="auto">
<head>
    <meta charset="{$head_charset}">
    <title>{if isset($media.file.props.base_property.ALL.TITLE)}{$media.file.props.base_property.ALL.TITLE|default:$media.file.realname}{else}{$media.file.realname}{/if}</title>
    <meta name="theme-color" content="#7952b3">
    <script src="{serendipity_getFile file="js/color-modes.js"}"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{$serendipityBaseURL}">
    <link rel="stylesheet" href="{serendipity_getFile file="css/bootstrap.min.css"}">
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{* serendipity_hookPlugin hook="frontend_header" *}{* ENABLE TO USE any plugin hooked assets - see footer *}
</head>
<body class="grid-block">
    <div class="skippy visually-hidden-focusable overflow-hidden">
      <div class="container-xl">
        <a class="d-inline-flex p-2 m-1" href="#content">Skip to main content</a>
      </div>
    </div>
{else}
{* serendipity_hookPlugin hook="frontend_header" *}{* ENABLE TO USE any plugin hooked assets - see footer *}
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
<header class="navbar navbar-expand-lg bd-navbar py-2 mb-3 sticky-top">
  <nav class="container-xxl flex-wrap flex-lg-nowrap" aria-label="Main navigation">
    <div class="d-lg-none" style="width: 1.5rem;"></div>

    <a class="navbar-brand p-0 me-0 me-lg-2" href="{$serendipityHTTPPath}" aria-label="B53 Home">
      <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block my-1" viewBox="0 0 118 94" role="img"><title>{$blogTitle} - {$blogDescription}</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg>
    </a>

    <h1 style="display: none;"><a class="blog-header-logo text-dark" href="{$serendipityBaseURL}">{$blogTitle}</a></h1>
{if NOT $template_option.use_corenav}

    <div class="col-auto d-block my-1 text-truncate">
      <span>{$blogDescription}</span>
    </div>
{/if}

    <div class="d-flex">

      <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-label="Toggle navigation">
        <svg class="bi" aria-hidden="true"><use xlink:href="#three-dots"></use></svg>
      </button>
    </div>

    <div class="offcanvas-lg offcanvas-end flex-grow-1" tabindex="-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel" data-bs-scroll="true">
      <div class="offcanvas-header px-4 pb-0">
        <h5 class="offcanvas-title text-white" id="bdNavbarOffcanvasLabel">{$blogTitle}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#bdNavbar"></button>
      </div>

      <div class="offcanvas-body p-4 pt-0 p-lg-0">
        <hr class="d-lg-none text-white-50">
        <ul class="navbar-nav flex-row flex-wrap bd-navbar-nav nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
{if $template_option.use_corenav}
    {foreach $navlinks AS $navlink}
        {if $navlink.title != "" AND $navlink.href != ""}

            <li class="nav-item col-6 col-lg-auto{if $currpage == $navlink.href OR $currpage2 == $navlink.href} active{/if}">
                <a class="nav-link py-2 px-0 px-lg-2" href="{$navlink.href}">{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href} <span class="sr-only">(current)</span>{/if}</a>
            </li>
        {/if}
    {/foreach}
{/if}

        </ul>

        <hr class="d-lg-none text-white-50">

        <form class="col-12 col-lg-auto mt-1 mb-3 mb-lg-0 me-lg-3" method="get" role="search">
          <input type="hidden" name="serendipity[action]" value="search">
          <input type="search" class="form-control" name="serendipity[searchTerm]" placeholder="{$CONST.BS_PLACEHOLDER_QUICKSEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
        </form>

        <ul class="navbar-nav flex-row flex-wrap ms-md-auto">
          <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
            <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
            <hr class="d-lg-none my-2 text-white-50">
          </li>

          <li class="nav-item dropdown">
            <button id="bd-theme" class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle mode (dark)">
              <svg class="bi my-1 theme-icon-active"><use href="#moon-stars-fill"></use></svg>
              <span class="d-lg-none ms-2" id="bd-theme-text">Toggle mode</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#sun-fill"></use></svg>
                  Light
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="dark" aria-pressed="true">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#moon-stars-fill"></use></svg>
                  Dark
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#circle-half"></use></svg>
                  Auto
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="container-fluid content">

  <div class="container bg-light">
    <main class="col-md-6">

        <section id="entries_dategroup" class="serendipity_Entry_Date">
            <article class="post post_single">
                <header>
                    <h2><a href="#">{$media.file.realname}</a></h2>

                    <p class="post_byline"><a href="#">{if isset($media.file.props.base_property.ALL.TITLE)}{$media.file.props.base_property.ALL.TITLE|default:''}{/if}</a></p>
                </header>

                <div class="post_content">
                {if NOT empty($perm_denied)}
                    {$CONST.PERM_DENIED}
                {else}
                    {if $media.file.is_image}

                    <div>
                        <!-- s9ymdb:{$media.file.id} -->
                        <picture>
{if isset($media.file.full_file_avif)}                            <source srcset="{$media.file.full_file_avif}" type="image/avif" />{/if}
                            <source srcset="{$media.file.full_file_webp|default:''}" type="image/webp" />
                            <img alt="" class="serendipity_image_center" loading="lazy" src="{$media.file.full_file}" width="{$media.file.dimensions_width}" />
                        </picture>
                    </div>
                    {else}

                    <div class="serendipity_center">
                        <a href="{$media.file.full_file}">{$media.file.realname} ({$media.file.displaymime})</a>
                    </div>
                    {/if}

                    <div class="serendipity_entryFooter mediaItemFooter">
                        <a href="{$media.from|escape}" title="{$CONST.BACK_TO_BLOG}">{$CONST.BACK_TO_BLOG}</a>
                    </div>
                {/if}

                </div>
            </article>
        </section>

        <div class="mediaItemProperties">
        {if $media.file.base_property}

            <div class="mediaItem mediaItemProp">
                <h3 class="mediaItemTitle">{$CONST.MEDIA_PROP}</h3>

                <div class="mediaItemContent">
                    <dl>
                    {foreach $media.file.base_property AS $prop_fieldname => $prop_content}
                    {if isset($prop_content.val)}

                        <dt>{$prop_content.label}</dt>
                        <dd>{$prop_content.val|escape}</dd>
                    {/if}
                    {/foreach}

                    </dl>
                </div>
            </div>
        {/if}

        {if NOT empty($media.file.props.base_keyword)}

            <div class="mediaItem mediaItemKeyword">
                <h3 class="mediaItemTitle">{$CONST.MEDIA_KEYWORDS}</h3>

                <div class="mediaItemContent">

                {foreach $media.file.props.base_keyword AS $prop_fieldname => $prop_content}

                    {$prop_fieldname|escape}&nbsp;
                {/foreach}

                </div>
            </div>
        {/if}

        {if $media.file.props.base_metadata}

            <div class="mediaItem mediaItemMeta">
                <h3 class="mediaItemTitle">EXIF/IPTC/XMP</h3>

                <div class="mediaItemContent">
                    <dl>
                    {foreach $media.file.props.base_metadata AS $meta_type => $meta_data}

                        <dt><strong>{$meta_type}</strong></dt>
                        <dd>
                        {if is_array($meta_data)}

                        <table>
                        {foreach $meta_data AS $meta_name => $meta_value}

                            <tr>
                                <td><em>{$meta_name}!</em></th>
                                <td>{if is_array($meta_value)}<pre>{$meta_value|print_r}</pre>{else}{$meta_value|formatTime:DATE_FORMAT_SHORT:false:$meta_name}{/if}</td>
                            </tr>
                        {/foreach}

                        </table>
                        {else}

                        {$meta_data|formatTime:DATE_FORMAT_SHORT:false:$meta_type}
                        {/if}

                        </dd>
                    {/foreach}

                    </dl>
                </div>
            </div>
        {/if}

        {if $media.file.references}

            <div class="mediaItem mediaItemRef">
                <h3 class="mediaItemTitle">{$CONST.REFERER}</h3>

                <div class="mediaItemContent">
                    <ul class="plainList">
                    {foreach $media.file.references AS $ref}

                        <li>({$ref.name|escape}) <a rel="nofollow" href="{$ref.link|escape}">{$ref.link|default:$CONST.NONE|escape}</a></li>
                    {/foreach}

                    </ul>
                </div>
            </div>
        {/if}

        </div>
    </main>

  </div><!-- //container bg-light end -->

  <footer class="text-body-secondary py-5">
    <div class="container">
      <p class="float-end mb-1">
        <a href="#"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block my-1" viewBox="0 0 118 94" role="img"><title>Back to top</title><path fill-rule="evenodd" clip-rule="evenodd" d="M24.509 0c-6.733 0-11.715 5.893-11.492 12.284.214 6.14-.064 14.092-2.066 20.577C8.943 39.365 5.547 43.485 0 44.014v5.972c5.547.529 8.943 4.649 10.951 11.153 2.002 6.485 2.28 14.437 2.066 20.577C12.794 88.106 17.776 94 24.51 94H93.5c6.733 0 11.714-5.893 11.491-12.284-.214-6.14.064-14.092 2.066-20.577 2.009-6.504 5.396-10.624 10.943-11.153v-5.972c-5.547-.529-8.934-4.649-10.943-11.153-2.002-6.484-2.28-14.437-2.066-20.577C105.214 5.894 100.233 0 93.5 0H24.508zM80 57.863C80 66.663 73.436 72 62.543 72H44a2 2 0 01-2-2V24a2 2 0 012-2h18.437c9.083 0 15.044 4.92 15.044 12.474 0 5.302-4.01 10.049-9.119 10.88v.277C75.317 46.394 80 51.21 80 57.863zM60.521 28.34H49.948v14.934h8.905c6.884 0 10.68-2.772 10.68-7.727 0-4.643-3.264-7.207-9.012-7.207zM49.948 49.2v16.458H60.91c7.167 0 10.964-2.876 10.964-8.281 0-5.406-3.903-8.178-11.425-8.178H49.948z" fill="currentColor"></path></svg></a>
      </p>
      <p class="mb-1" lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </div>
  </footer>

</div><!-- //container-fluid end -->

<script src="{serendipity_getFile file="js/bootstrap.bundle.min.js"}"></script>
<script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
<script> const themePath = '{$serendipityHTTPPath}{$templatePath}{$template}'; </script>
<script src="{serendipity_getFile file="theme.js"}"></script>

{/if}
{if isset($raw_data)}{$raw_data|default:''}{/if}
{* serendipity_hookPlugin hook="frontend_footer" *}{* ENABLE TO USE any plugin hooked assets which often need an active jQuery lib *}
{if $is_embedded != true}

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="circle-half" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
  </symbol>
  <symbol id="moon-stars-fill" viewBox="0 0 16 16">
    <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
    <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
  </symbol>
  <symbol id="sun-fill" viewBox="0 0 16 16">
    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
  </symbol>
  <symbol id="three-dots" viewBox="0 0 16 16">
    <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
  </symbol>
{* Bootstrap alert icons 1: success, 2: info, 3: error/warning *}
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
  <symbol id="required-field-asterisk" fill="red" viewBox="0 0 16 16">
     <path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/>
  </symbol>
</svg>

</body>
</html>
{/if}
