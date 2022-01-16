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
    <link rel="stylesheet" href="{serendipity_getFile file="b5/css/bootstrap.min.css"}">
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml" title="{$blogTitle} Atom feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{* serendipity_hookPlugin hook="frontend_header" *}{* ENABLE TO USE any plugin hooked assets - see footer *}
</head>
<body class="grid-block">
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
<header>
  <div class="collapse bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
          <h4 class="text-white">About</h4>
          <p class="text-muted">Add some information about the album below, the author, or any other background context. Make it a few sentences long so folks can pick up some informative tidbits. Then, link them off to some social networking sites or contact information.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4 class="text-white">Contact</h4>
          <ul class="list-unstyled">
            <li><a href="#" class="text-white">Follow on Twitter</a></li>
            <li><a href="#" class="text-white">Like on Facebook</a></li>
            <li><a href="#" class="text-white">Email me</a></li>
{if $template_option.use_corenav}
    {foreach $navlinks AS $navlink}
        {if $navlink.title != "" AND $navlink.href != ""}
            <li class="nav-item{if $currpage == $navlink.href OR $currpage2 == $navlink.href} active{/if}">
                <a class="text-white" href="{$navlink.href}">{$navlink.title}{if $currpage == $navlink.href OR $currpage2 == $navlink.href} <span class="sr-only">(current)</span>{/if}</a>
            </li>
        {/if}
    {/foreach}
{/if}
            <li class="link-secondary" href="#" data-bs-toggle="modal" data-bs-target="#quicksearch" aria-label="Search">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="px-3" role="img" viewBox="0 0 24 24"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a href="#" class="navbar-brand d-flex align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        <strong>Album</strong>
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
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

  <footer class="text-muted py-5">
    <div class="container">
      <p class="float-end mb-1">
        <a href="#">Back to top</a>
      </p>
      <p class="mb-1" lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </div>
  </footer>

</div><!-- //container-fluid end -->

<script src="{serendipity_getFile file="b5/js/bootstrap.min.js"}"></script>
<script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
<script src="{serendipity_getFile file="theme.js"}"></script>

{/if}
{if isset($raw_data)}{$raw_data|default:''}{/if}
{* serendipity_hookPlugin hook="frontend_footer" *}{* ENABLE TO USE any plugin hooked assets which often need an active jQuery lib *}
{if $is_embedded != true}
</body>
</html>
{/if}
