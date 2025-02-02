{if $is_embedded != true}
<!DOCTYPE html>
<html lang="{$lang}">
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
    <link rel="stylesheet" type="text/css" href="{$head_link_stylesheet}" type="text/css">
    <link rel="alternate"  type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate"  type="application/x.atom+xml"  title="{$blogTitle} Atom feed"  href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
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
<div id="wrap" class="grid{if $leftSidebarElements > 0 AND $rightSidebarElements > 0}-col{/if}">

    <header id="serendipity_banner"><a id="topofpage"></a>
        <h1><a class="homelink1" href="{$serendipityBaseURL}">{$head_title|default:$blogTitle|truncate:80:" ..."}</a></h1>
        <h2><a class="homelink2" href="{$serendipityBaseURL}">{$head_subtitle|default:$blogDescription}</a></h2>
    </header>

    <main id="content">
        {$CONTENT}
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

<footer id="footer">
    <p><span lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> &amp; the <i>{$template}</i> theme.</span></p>
</footer>

</div>

{if ($view == 'entry' AND $wysiwyg_comment AND NOT (isset($smarty.get.serendipity.csuccess) AND $smarty.get.serendipity.csuccess == 'true') && (isset($entry) AND NOT $entry.allow_comments === false)) OR (($view == 'plugin' OR $view == 'start') AND $head_title == 'contactform')}

    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/ckeditor.js"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/ckebasic/config.js"></script>
    <script>
        window.onload = function() {
            var cfmco = document.getElementById('serendipity_commentform_comment');
            if (typeof(cfmco) != 'undefined' && cfmco != null) {
                CKEDITOR.replace( cfmco, { toolbar : [['Bold','Italic','Underline','-','NumberedList','BulletedList','Blockquote'],['CodeSnippet'],['EmojiPanel']], versionCheck: false });
            }
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
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}_assets/highlight/github.min.css" type="text/css">
    <script src="{$serendipityHTTPPath}{$templatePath}_assets/highlight/highlight.min.js"></script>
    <script>
        // launch the codesnippet highlight
        hljs.configure({
          tabReplace: '    ', // 4 spaces
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
