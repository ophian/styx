{serendipity_hookPlugin hook="entries_header"}

<div id="blog-archive">
  <h1>{$CONST.ARCHIVES}</h1>

  <p>{$CONST.ARCHIVE_TEXT_INTRO}</p>

  <p>{$CONST.ARCHIVE_TEXT_ADD|sprintf:$serendipityHTTPPath}</p>

  <div id="bycats" class="clearfix">
  {serendipity_showPlugin class="serendipity_plugin_categories"}
  </div>

  {capture name="archivetags"}{serendipity_showPlugin class="serendipity_plugin_freetag"}{/capture}
  {assign var="captags" value=$smarty.capture.archivetags|replace:'<h3 class="serendipitySideBarTitle serendipity_plugin_freetag">':'<h2 class="serendipitySideBarTitle serendipity_plugin_freetag">'}
  {assign var="captags" value=$captags|replace:'</h3>':'</h2>'}
  <div id="bytags" class="clearfix">
  {if $smarty.capture.archivetags != ''}{$captags}{/if}
  </div>

  <div id="bydate" class="clearfix">
    <h2>{$CONST.DATE}</h2>
    <p>{$CONST.ARCHIVE_TEXT_YEARMONTH}</p>

  {foreach $archives AS $archive}
    <div class="archive-year {cycle name="blah" values="left,center,right"}">
    <h3>{$archive.year}</h3>

    <dl>
    {foreach $archive.months AS $month}
       {if $month.entry_count > 0}
       <dt><a href="{$month.link}" title="{$CONST.VIEW_FULL}">{$month.date|formatTime:"%B"}</a>: </dt>
       <dd>{$month.entry_count} <a href="{$month.link_summary}" title="{$CONST.VIEW_TOPICS}"> {$CONST.ENTRIES}</a></dd>
       {/if}
    {/foreach}

    </dl>

    </div>
  {/foreach}

  </div>

</div>

{serendipity_hookPlugin hook="entries_footer"}

