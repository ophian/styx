{* NOTE: Replace src: |default:"http://lorempixel.com/600/400/{$design.0}/?{($key+$design.1)}" (placeholder dummy image generator) with your own *}
{function name=gridcards}
                        <li class="grid__item">
                            <a href="{$card.link|default:'#'}" class="card">
                              <div class="card__image">
                                <picture>
                                  <source srcset="{$card.image.webp|default:''}" type="image/webp">
                                  <img src="{$card.image.src|default:"http://lorempixel.com/600/400/{$design.0}/?{($key+$design.1)}"}" alt="">
                                </picture>
                              </div>
                              <article class="card__content">
                                <h2 class="card__title">{$card.title|default:"Card Title"}</h2>
                                <div class="card__body">
                                  {$card.body|default:"<p>No Content</p>"}
                                </div>
                                <div class="card__footer">
                                  in <span class="tag">Pure</span>, <span class="tag">Styx</span>
                                </div>
                              </article>
                            </a>
                        </li>
{/function}
{if $template_option.pure_welcome AND $view == "start" && NOT isset($staticpage_pagetitle)}{assign var="welcome" value=true scope="root"}{/if}
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
<body class="{if isset($welcome)}welcome {/if}grid-{if $leftSidebarElements > 0 AND $rightSidebarElements > 0}col{elseif $leftSidebarElements > 0 OR $rightSidebarElements > 0}flex{else}block{/if}">
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

{if NOT isset($welcome) AND $leftSidebarElements > 0 AND $rightSidebarElements > 0}

    <section class="grid">
{/if}

        <main id="content"{if isset($welcome)} class="welcome"{/if}>
{if $template_option.pure_welcome === true AND $view == "start" && NOT isset($staticpage_pagetitle)}{* this an entries list startview - NOT view=start of staticpage startpage !! *}
    {if NOT empty($template_option.home_welcome_title) OR $template_option.home_welcome_title != 'none'}

            <section class="post post_home">

    {/if}
    {if NOT empty($template_option.home_welcome_content)}

                <div class="home_content">
                    <ul class="home">
                        {* this 1st card, overspans 2 columns *}
                        <li class="home__item">

                            <div class="card">
                              <div class="card__image">
                                <picture>
                                  <source srcset="{* link to static MediaLibrary webp variation file *}" type="image/webp">
                                  <img src="{* link to static MediaLibrary image file *}http://lorempixel.com/600/400/{$design.0}/?1" alt="">
                                </picture>
                              </div>
                              <article class="card__content">
                                <h2 class="card__title">{$template_option.home_posts_title}</h2>
                                <div class="card__body">
                                  <ol class="card__news plainList">
                                    {serendipity_fetchPrintEntries short_archives=true full=false noSticky=true use_footer=false limit="0,5"}{* Add category="1;2" if you want *}
                                  </ol>
                                </div>
                                <div class="card__footer">
                                  in <span class="tag">Pure</span>, <span class="tag">Styx</span>
                                </div>
                              </article>
                            </div>

                        </li>
                        {* this 2cd right card, is the about card linking to your blog *}
                        <li class="grid__item">

                            <a href="{$serendipityBaseURL}?frontpage" class="card" title="{$template_option.home_blog_link}">
                              <div class="card__image">
                                <picture>
                                  <source srcset="{* link to static MediaLibrary webp variation file *}" type="image/webp">
                                  <img src="{* link to static MediaLibrary image file *}http://lorempixel.com/600/400/{$design.0}/?2" alt="">
                                </picture>
                              </div>
                              <article class="card__content">
                                {if $template_option.home_welcome_title != 'none'}<h2 class="card__title">{$template_option.home_welcome_title}</h2>{/if}
                                <div class="card__body">
                                  {$template_option.home_welcome_content}
                                </div>
                                <div class="card__footer">
                                  in <span class="tag">Pure</span>, <span class="tag">Styx</span>
                                </div>
                              </article>
                            </a>

                        </li>
                {if isset($addcards) AND is_array($addcards)}
                    {foreach $addcards AS $addcard}
                       {gridcards card=$addcard key=$addcard@key}
                    {/foreach}
                {/if}
                    </ul>
                </div>
    {/if}
    {if NOT empty($template_option.home_welcome_title) OR $template_option.home_welcome_title != 'none'}

            </section>
    {/if}
{else}
        {if NOT empty($CONTENT)}

            {$CONTENT}
        {else if $view == '404'}

            <p class="msg_notice"><span class="ico icon-info" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>
        {/if}
{/if}

        </main>
    {if NOT isset($welcome) AND $leftSidebarElements > 0}

        <aside id="serendipityLeftSideBar">
            {serendipity_printSidebar side="left"}
        </aside>
    {/if}
    {if NOT isset($welcome) AND $rightSidebarElements > 0}

        <aside id="serendipityRightSideBar">
            {serendipity_printSidebar side="right"}
        </aside>
    {/if}
{if NOT isset($welcome) AND $leftSidebarElements > 0 AND $rightSidebarElements > 0}

    </section>
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

    <script src="{serendipity_getFile file="pure.js"}"></script>
{if $view == 'entry' AND $wysiwyg_comment AND NOT (isset($smarty.get.serendipity.csuccess) AND $smarty.get.serendipity.csuccess == 'true') && (isset($entry) AND NOT $entry.allow_comments === false)}
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/ckeditor.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/config.js"></script>
    <script>
        window.onload = function() {
            CKEDITOR.replace( 'serendipity_commentform_comment', { toolbar : [['Bold','Italic','Underline','-','NumberedList','BulletedList','Blockquote'],['CodeSnippet'],['EmojiPanel']] });
        }
    </script>
    {assign var="hljsload" value=true}
{/if}
{if (in_array($view, ['start', 'entries', 'entry', 'categories']) AND $template_option.use_highlight === true) OR isset($hljsload) && $hljsload === true}
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}_assets/highlight/github.min.css">
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/highlight/highlight.min.js"></script>
    <script>
        // launch the codesnippet highlight
        hljs.configure({
          tabReplace: '    ', // 4 spaces
        });
        hljs.initHighlightingOnLoad();
    </script>
{/if}

{/if}
{$raw_data}
{serendipity_hookPlugin hook="frontend_footer"}
{if $is_embedded != true}
</body>
</html>
{/if}
