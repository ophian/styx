{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{if isset($media.file.props.base_property.ALL.TITLE)}{$media.file.props.base_property.ALL.TITLE|default:$media.file.realname}{else}{$media.file.realname}{/if}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{$serendipityBaseURL}">
    <link rel="stylesheet" href="{serendipity_getFile file="b4/css/bootstrap.min.css"}">
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{* serendipity_hookPlugin hook="frontend_header" *}{* ENABLE TO USE any plugin hooked assets - see footer *}
</head>
<body>
{else}
{* serendipity_hookPlugin hook="frontend_header" *}{* ENABLE TO USE any plugin hooked assets - see footer *}
{/if}
{if $is_raw_mode != true}
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <div class="container">
  <a class="navbar-brand homelink1" href="{$serendipityBaseURL}" title="{$head_title|default:$blogDescription}">{$blogTitle|truncate:80:" ..."}</a>
{if $template_option.use_corenav OR $template_option.lineup}

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#corenav" aria-controls="corenav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse b46-lineup-nav" id="corenav">
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
    {if $template_option.navsearch}
    <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search" class="form-inline my-2 my-lg-0">
        <input type="hidden" name="serendipity[action]" value="search">
        <input id="serendipityQuickSearchTermField" class="form-control mr-sm-2" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.B46_PLACE_SEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
        <input id="searchsend" class="btn btn-outline-primary my-2 my-sm-0" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
    </form>
    {/if}
    {if $template_option.scrollbtn}
    <span class="nav-down">
      <a href="#to-sdb" title="jumpscroll to sidebar">
        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-square" xmlns="http://www.w3.org/2000/svg" fill="rgba(255,255,255,.5)">
          <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
          <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"></path>
        </svg>
      </a>
    </span>
    {/if}
  </div>
{else}
{if $template_option.navsearch}
  <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get" role="search" class="form-inline my-2 my-lg-0">
    <input type="hidden" name="serendipity[action]" value="search">
    <input id="serendipityQuickSearchTermField" class="form-control mr-sm-2" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.B46_PLACE_SEARCH}" value="" aria-label="{$CONST.QUICKSEARCH}">
    <input id="searchsend" class="btn btn-outline-primary my-2 my-sm-0" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
  </form>
{/if}
{if $template_option.scrollbtn}
  <span class="nav-down">
    <a href="#to-sdb" title="jumpscroll to sidebar">
      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-arrow-down-square" xmlns="http://www.w3.org/2000/svg" fill="rgba(255,255,255,.5)">
        <path fill-rule="evenodd" d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"></path>
        <path fill-rule="evenodd" d="M8 4a.5.5 0 0 1 .5.5v5.793l2.146-2.147a.5.5 0 0 1 .708.708l-3 3a.5.5 0 0 1-.708 0l-3-3a.5.5 0 1 1 .708-.708L7.5 10.293V4.5A.5.5 0 0 1 8 4z"></path>
      </svg>
    </a>
  </span>
{/if}
{/if}
    {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
  </div>
</nav>
{if $template_option.lineup}

<nav class="container navbar navbar-expand-md">
  <div class="collapse navbar-collapse" id="corenav">
    <ul class="nav d-flex justify-content-between b46-expand">
{foreach $navlinks AS $navlink}
    {if $navlink.title != '' AND $navlink.href != ''}

        <li class="nav-item p-2{if $currpage == $navlink.href OR $currpage2 == $navlink.href} active{/if}{if ($navlink.href|smartySubstr:null:-1) == '#'} dropdown menu-item-{$navlink@index}{/if}">
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
  </div>
</nav>
{/if}

<main role="main" class="container">
    <a id="to-top"></a>

    <div class="row mb-2">
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

<footer id="footer" class="clearfix">
    <p lang="en">Powered by <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
</footer>

<script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
<script src="{serendipity_getFile file="b4/js/bootstrap.min.js"}"></script>
<script src="{serendipity_getFile file="theme.js"}"></script>

{/if}
{if isset($raw_data)}{$raw_data|default:''}{/if}
{* serendipity_hookPlugin hook="frontend_footer" *}{* ENABLE TO USE any plugin hooked assets which often need an active jQuery lib *}
{if $is_embedded != true}
</body>
</html>
{/if}
