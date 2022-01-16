{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{if isset($media.file.props.base_property.ALL.TITLE)}{$media.file.props.base_property.ALL.TITLE|default:$media.file.realname}{else}{$media.file.realname}{/if}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="{$serendipityBaseURL}">
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

    <button id="blink" class="navbar-shader btn float" onclick="dark()" title="Theme: Dark (Browser preferences|Session override)">
        <i id="dark-mode-icon" class="bi bi-moon-fill"></i>
        <img id="daynight" src="{$serendipityHTTPPath}{$templatePath}{$template}/icons/sun-fill.svg" width="30" height="30" alt="">
    </button>

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
                        <input id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" type="search" placeholder="{$CONST.PURE_PLACE_SEARCH}" value="">
                        <input id="searchsend" name="serendipity[searchButton]" type="submit" value="{$CONST.GO}">
                    </div>
                    </form>
                    {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
                </li>
            </ul>
        </nav>
{else}

{* ENABLE TO USE any plugin hooked assets - see footer
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
*}
{/if}

    </header>

    <main id="content" class="mediaItemMain">

        <div class="serendipity_Entry_Date">
            <h3 class="serendipity_date">{$media.file.realname}</h3>
            <h4 class="serendipity_title"><a href="#">{if isset($media.file.props.base_property.ALL.TITLE)}{$media.file.props.base_property.ALL.TITLE|default:''}{/if}</a></h4>

            <div class="serendipity_entry">
                <div class="serendipity_entry_body">
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
            </div>
        </div>

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

    <footer id="footer" class="mediaItemBlend">
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

    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script> const themePath = '{$serendipityHTTPPath}{$templatePath}{$template}'; </script>
    <script src="{serendipity_getFile file="pure.js"}"></script>

{/if}
{if isset($raw_data)}{$raw_data|default:''}{/if}
{* serendipity_hookPlugin hook="frontend_footer" *}{* ENABLE TO USE any plugin hooked assets which often need an active jQuery lib *}
{if $is_embedded != true}
</body>
</html>
{/if}
