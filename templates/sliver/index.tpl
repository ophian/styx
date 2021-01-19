{* Sliver v4 template: last modified 2019-08-13 v.4.60 - view README.md *}{if $is_embedded != true}
<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
  <head>
    <meta charset="{$head_charset}">
    <meta name="generator" content="Serendipity Styx Edition">
    <meta name="viewport" content="width=device-width, initial-scale=1">
{if (isset($view) AND in_array($view, ['start', 'entries', 'entry', 'feed', 'plugin'])) OR NOT empty($staticpage_pagetitle) OR (isset($robots_index) AND $robots_index == 'index')}

    <meta name="robots" content="index,follow">
{else}

    <meta name="robots" content="noindex,follow">
{/if}
{if NOT empty($staticpage_custom.meta_description)}

    <meta name="description" content="{$staticpage_custom.meta_description|escape}">
{/if}
{if NOT empty($staticpage_custom.meta_keywords)}

    <meta name="keywords" content="{$staticpage_custom.meta_keywords|escape}">
{/if}
{if $is_single_entry AND isset($entry.body)}

    <meta property="og:description" content="{$entry.body|strip_tags:false|strip|truncate:160:'...'}">
{/if}
{if NOT empty($staticpage_custom.title_element)}

    <title>{$staticpage_custom.title_element|escape}</title>
{else}

    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
{/if}
{if $template_option.webfonts == 'droid'}

    <link  rel="stylesheet" href="//fonts.googleapis.com/css?family=Droid+Sans:400,700">
{elseif $template_option.webfonts == 'ptsans'}

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=PT+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'osans'}

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'cabin'}

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Cabin:400,400italic,700,700italic">
{elseif $template_option.webfonts == 'ubuntu'}

    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Ubuntu:400,400italic,700,700italic">
{/if}
{if isset($view) AND $view == 'entry' AND isset($entry.rdf_ident)}

    <link rel="canonical" href="{$entry.rdf_ident}">
{/if}

    <link rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}{$template}/favicon.ico">
    <link rel="alternate" type="application/rss+xml" title="{$blogTitle} RSS feed" href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/index.rss2">
    <link rel="alternate" type="application/x.atom+xml"  title="{$blogTitle} Atom feed"  href="{$serendipityBaseURL}{$serendipityRewritePrefix}feeds/atom.xml">
{if $entry_id}

    <link rel="trackback" type="application/x-www-form-urlencoded" href="{$serendipityBaseURL}comment.php?type=trackback&amp;entry_id={$entry_id}">
    <link rel="pingback" href="{$serendipityBaseURL}comment.php?type=pingback&amp;entry_id={$entry_id}">
{/if}
{if isset($view) AND in_array($view, ['start', 'entries'])}

    <link rel="canonical" href="{$serendipityBaseURL}">
{/if}

    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}{$template}/css/normalize.css">
    <link rel="stylesheet" href="{$head_link_stylesheet}">
    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}{$template}/css/endandprint.css">
{if $template_option.use_slivers_codeprettifier}

    <link rel="stylesheet" href="{$serendipityHTTPPath}{$templatePath}{$template}/css/prettify.css">
{/if}

    <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/modernizr-3.6.0.min.js"></script>

{serendipity_hookPlugin hook="frontend_header"}

    <script src="{$head_link_script}"></script>
  </head>
  <body id="top"{if isset($template_option.webfonts) AND $template_option.webfonts != 'none'} class="{$template_option.webfonts}"{/if}>
{else}
    {serendipity_hookPlugin hook="frontend_header"}
{/if}

{if $is_raw_mode != true}
  <div id="wrapper">
    {*
       +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
       // header section
       +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      *}

      {if $template_option.sitenavpos == 'above'}
      {* #sitenav: this holds a list of navigational links which can be customized in the theme configurator *}
      <nav id="{if $template_option.sitenavstyle != 'slim'}site{/if}nav{if $template_option.sitenavstyle == 'ex'}-extended{/if}" class="snabove">
        <ul>
        {foreach $navlinks AS $navlink}
          <li class="{if $currpage==$navlink.href OR $currpage2==$navlink.href}currentpage{/if}{if $navlink@first} navlink_first{/if}{if $navlink@last} navlink_last{/if}"><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>
        {/foreach}
        </ul>
        {* quicksearch option in the navigational link menu bar only when navbar is above or below the banner *}
        {if $template_option.sitenav_quicksearch}
        <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
          <input type="hidden" name="serendipity[action]" value="search">
          <input alt="{$CONST.QUICKSEARCH}" type="text" id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" value="{$CONST.QUICKSEARCH}..." onfocus="if(this.value=='{$CONST.QUICKSEARCH}...')value=''" onblur="if(this.value=='')value='{$CONST.QUICKSEARCH}...';">
          <div id="LSResult" style="display: none;"><div id="LSShadow"></div></div>
        </form>
        {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
        {/if}
      </nav>
      {/if}
    <header id="header" class="clearfix column{if $template_option.sitenavpos != 'below'} spacer{/if}">
      {* #serendipity_banner: this is the header area. it holds the blog title and description headlines *}
      <hgroup id="serendipity_banner">
        <h1><span class="{if NOT $template_option.firbtitle}in{/if}visible"><a class="homelink1" href="{$serendipityBaseURL}">{$head_title|default:$blogTitle|truncate:80:"&hellip;"}</a></span></h1>
        <h2><span class="{if NOT $template_option.firbdescr}in{/if}visible"><a class="homelink2" href="{$serendipityBaseURL}">{if $view == 'plugin'}{$blogDescription}{else}{$head_subtitle|default:$blogDescription}{/if}</a></span></h2>
      </hgroup>
    </header>
      {if $template_option.sitenavpos == 'below'}
      {* #sitenav: this holds a list of navigational links which can be customized in the theme configurator *}
        <a id="open-nav" class="nav-toggle" href="#nav"><span class="icon-menu" aria-hidden="true"></span><span class="fallback-text">{$CONST.NEXT_NAVTEXT}</span></a>

        <nav id="{if $template_option.sitenavstyle != 'slim'}site{/if}nav{if $template_option.sitenavstyle == 'ex'}-extended{/if}" class="snbelow nav-collapse">
        <ul>
        {foreach $navlinks AS $navlink}
          <li class="{if $currpage==$navlink.href OR $currpage2==$navlink.href}currentpage{/if}{if $navlink@first} navlink_first{/if}{if $navlink@last} navlink_last{/if}"><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>
        {/foreach}
        </ul>
        </nav>
        {* quicksearch option in the navigational link menu bar only when navbar is above or below the banner *}
        {if $template_option.sitenav_quicksearch}
        <form id="searchform" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
          <input type="hidden" name="serendipity[action]" value="search">
          <input alt="{$CONST.QUICKSEARCH}" type="text" id="serendipityQuickSearchTermField" name="serendipity[searchTerm]" value="{$CONST.QUICKSEARCH}..." onfocus="if(this.value=='{$CONST.QUICKSEARCH}...')value=''" onblur="if(this.value=='')value='{$CONST.QUICKSEARCH}...';">
          <div id="LSResult" style="display: none;"><div id="LSShadow"></div></div>
        </form>
        {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
        {/if}
      {/if}


    {*
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
       // include the top sidebar, if set in admin panels plugin section
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      *}
    {if $is_single_entry !== true AND (isset($view) AND in_array($view, ['entry', 'start', 'archives']))}
    {if $topSidebarElements > 0}
    <nav id="sidebar_top" class="clearfix column">
      {serendipity_printSidebar side="top"}
    </nav><!-- // "id:#sidebar_top" end -->
    {/if}
    {/if}
    {if $template_option.layouttype != '1col' AND $middleSidebarElements < 1}

    <div id="ham"><input class="burger-check" id="burger-check" type="checkbox" aria-controls="sidebar_left"><label for="burger-check" class="burger"></label></div>
    {/if}

    {*
       +++++++++++++++++++++++++++++++++++++++++++++
       // include the theme option type sidebar left
       +++++++++++++++++++++++++++++++++++++++++++++
      *}
    {if $template_option.layouttype == '2sb'}

    <section id="maingrid" class="clearfix">

    <!-- case 1: 1-2 columns, left sidebar(s) only -->

    {* left sidebar stuff in here *}
    <aside id="sidebar_left" class="clearfix column{if ($middleSidebarElements > 0)} twoside{else} oneside{/if} layout2sb_left">

      {if $template_option.sitenavpos == 'left' OR $template_option.sitenavpos == 'right'}
      {* #sbsitenav: like #sitenav, but placed within the sidebar *}
      <div id="sbsitenav" class="serendipitySideBarItem">
        <h3 class="serendipitySideBarTitle">{$template_option.sitenav_sidebar_title|escape}</h3>
        <div class="serendipitySideBarContent">
          {* the line below must remain as a single uninterrupted line to display correctly in ie6 *}
          <ul>{foreach $navlinks AS $navlink}<li class="{if $currpage==$navlink.href OR $currpage2==$navlink.href}currentpage{/if}{if $navlink@first} sbnavlink_first{/if}{if $navlink@last} sbnavlink_last{/if}"><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>{/foreach}</ul>
        </div>
        <div class="serendipitySideBarFooter"></div>
      </div>
      {/if}
      {if $leftSidebarElements > 0}{serendipity_printSidebar side="left"}{/if}
      {if $rightSidebarElements > 0}{serendipity_printSidebar side="right"}{/if}

    </aside>

    {* middle sidebar stuff in here *}
    {if $middleSidebarElements > 0}
    <aside id="sidebar_middle" class="clearfix column sbm_left">
      {serendipity_printSidebar side="middle"}
     </aside><!-- // "id:#sidebar_middle" end --> 
    {/if}

    {* blog content stuff in here *}
    <section id="blog" class="clearfix column {if $middleSidebarElements > 0}twobar-left{else}onebar-left{/if}">
      <section id="content" class="twomain layout2sb_content hfeed">
        {$CONTENT}
      </section><!-- // "section id:#content" end -->
    </section><!-- // "section id:#blog" end -->

    </section><!-- #maingrid 2 sidebars left end -->

    {/if}
    {*
       +++++++++++++++++++++++++++++++++++++++++++++
       // include the theme option type sidebar right
       +++++++++++++++++++++++++++++++++++++++++++++
      *}
    {if $template_option.layouttype == '2bs'}

    <section id="maingrid" class="clearfix">

    <!-- case 2: 1-2 columns, right sidebar(s) only -->

    {* blog content stuff in here *}
    <section id="blog" class="clearfix column{if $middleSidebarElements > 0} twobar-right{else} onebar-right{/if}">
      <section id="content" class="twomain layout2bs_content hfeed">
        {$CONTENT}
      </section><!-- // "section id:#content" end -->
    </section><!-- // "section id:#blog" end -->

    {* middle sidebar stuff in here *}
    {if $middleSidebarElements > 0}

    <aside id="sidebar_middle" class="clearfix column sbm_right">
      {serendipity_printSidebar side="middle"}
    </aside><!-- // "id:#sidebar_middle" end --> 
    {/if}

    {* right sidebar stuff in here *}
    <aside id="sidebar_right" class="clearfix column{if ($middleSidebarElements > 0)} twoside{else} oneside{/if} layout2bs_right">

      {if $template_option.sitenavpos == 'left' OR $template_option.sitenavpos == 'right'}
      {* #sbsitenav: like #sitenav, but placed within the sidebar *}
      <div id="sbsitenav" class="serendipitySideBarItem">
        <h3 class="serendipitySideBarTitle">{$template_option.sitenav_sidebar_title|escape}</h3>
        <div class="serendipitySideBarContent">
          {* the line below must remain as a single uninterrupted line to display correctly in ie6 *}
          <ul>{foreach $navlinks AS $navlink}<li class="{if $currpage==$navlink.href}currentpage{/if}{if $navlink@first} sbnavlink_first{/if}{if $navlink@last} sbnavlink_last{/if}"><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>{/foreach}</ul>
        </div>
        <div class="serendipitySideBarFooter"></div>
      </div>
      {/if}
      {if $rightSidebarElements > 0}{serendipity_printSidebar side="right"}{/if}
      {if $leftSidebarElements > 0}{serendipity_printSidebar side="left"}{/if}

    </aside>

    </section><!-- #maingrid 2 sidebars right end -->

    {/if}
    {*
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
       // include the theme option type no horizontal sidebars
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      *}
    {if $template_option.layouttype == '1col'}

    <section id="maingrid" class="clearfix">

    <!-- case 3: 1 column, sidebar(s) below -->

    {* blog content stuff in here *}
    <section id="blogone" class="clearfix">
      <section id="content" class="onemain layout1col_content hfeed">
        {$CONTENT}
      </section><!-- // "section id:#content" end -->
    </section><!-- // "section id:#blogone" end -->

    </section><!-- #maingrid blogone end -->

    {* onefull sidebar stuff in here *}
    <aside id="sidebar_footer" class="onefull column layout1col_right_full">

      {if $leftSidebarElements > 0}{serendipity_printSidebar side="left"}{/if}
      {if $middleSidebarElements > 0}{serendipity_printSidebar side="middle"}{/if}
      {if $rightSidebarElements > 0}{serendipity_printSidebar side="right"}{/if}
      {if $footerSidebarElements > 0}{serendipity_printSidebar side="footer"}{/if}

      {if ($template_option.sitenavpos != 'none' and $template_option.sitenav_footer)}
      <div class="clearfix footer_sitenav">
        <ul>
        {foreach $navlinks AS $navlink}
          <li{if $currpage == $navlink.href} class="currentpage"{/if}><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>
        {/foreach}
        </ul>
      </div>
      {/if}

    </aside>

    {else}

    {*
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
       // include the footer sidebar, if set in admin panels plugin section
       ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      *}

    <nav id="sidebar_footer" class="clearfix column">
      {if $footerSidebarElements > 0}{serendipity_printSidebar side="footer"}{/if}
      {if ($template_option.sitenavpos != 'none' and $template_option.sitenav_footer)}
      <div class="clearfix footer_sitenav">
        <ul>
        {foreach $navlinks AS $navlink}
          <li{if $currpage == $navlink.href} class="currentpage"{/if}><a href="{$navlink.href}" title="{$navlink.title}">{$navlink.title}</a></li>
        {/foreach}
        </ul>
      </div>
      {/if}

    </nav><!-- // "id:#sidebar_footer" end --> 

    {/if}

    {*
       +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
       // footer section
       +++++++++++++++++++++++++++++++++++++++++++++++++++++++++
      *}

    <footer id="footer" class="clearfix column">
      <div id="serendipity_credit_line">&#160;<em>{$sliver_credit}</em>&#160;</div>
    </footer>

  </div><!-- // "id:#wrapper" end -->

{if $template_option.use_slivers_jQueryMin}
  <script>window.jQuery || document.write('<script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/jquery-3.3.1.min.js"><\/script>')</script>
{/if}
{/if}

{$raw_data}

{serendipity_hookPlugin hook="frontend_footer"}

{if $is_embedded != true}
  {* JavaScript at the bottom for fast page loading *}

{if $template_option.use_slivers_codeprettifier}
  <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/prettify.js"></script>
{/if}
{if $template_option.use_slivers_codeprettifier}
{literal}
  <script>
    jQuery(function($) {
        prettyPrint();
    });
  </script>
{/literal}
{/if}

  <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/plugins.js"></script>
  <script src="{$serendipityHTTPPath}{$templatePath}{$template}/js/main.js"></script>

{if $template_option.use_google_analytics}
  {* See config: Asynchronous Google Analytics snippet. Include using the anonymous version, deleting the last 8 Bit of the IP-Address - else delete: ,['_gat._anonymizeIp'] *}

  <script>
    var _gaq=[['_setAccount','{$template_option.google_id}'],['_gat._anonymizeIp'],['_trackPageview'],['_trackPageLoadTime']];
    (function(d,t){ldelim}var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
    g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
    s.parentNode.insertBefore(g,s){rdelim}(document,'script'));
  </script>
{/if}

{if isset($tagcanvasrotate) AND $tagcanvasrotate === true}

  <script>
    // using rotating tags canvas
    $(document).ready(function() { 
        $("#bytags .container_serendipity_plugin_freetag").css({ "background-color":"transparent", "color":"inherit" });
        $("#bytags .serendipitySideBarContent").css({ "flex-grow":"inherit", "column-count":"inherit", "column-gap":"inherit" });
        $("#bytags .serendipitySideBarContent").addClass('dyn_rotacloud');
        // better check for canvas in to here too, when using both clouds! In this case rota cloud in event, 2D-canvas in sidebarplus!
        $('#sidebar_top canvas').css({ "display":"none", "visibility":"hidden", "position":"inherit", "z-index":"inherit", "width":"inherit", "height":"inherit" });
        // on id #sidebar_top we need to use important for display: inline - see user css
    });
  </script>
{/if}
{if isset($tagcanvascloud) AND $tagcanvascloud === true}

  <script>
    // using awesome tags canvas wordcloud
    $(document).ready(function() { 
        $("#bytags .container_serendipity_plugin_freetag").css({ "background-color":"transparent", "color":"inherit" });
        $(".serendipity_freetag_taglist").css({ "font-size":"inherit", "background-image":"inherit", "overflow":"auto", "background-color":"inherit", "background":"inherit", "height":"100%" });
        $('#sidebar_top canvas').css({ "display":"none", "visibility":"hidden", "position":"inherit", "z-index":"inherit", "width":"inherit", "height":"inherit" });
    });
  </script>
{/if}

  </body>

</html>
{/if}
