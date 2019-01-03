{if $is_raw_mode}
<div id="serendipity{$pluginside}SideBar">
{/if}
{foreach $plugindata AS $item}
   {if empty($item.content) OR $item.class == "serendipity_plugin_quicksearch" AND $template_option.sitenav_quicksearch == 'true' AND ($template_option.sitenavpos == 'above' OR $template_option.sitenavpos == 'below')}
<!-- do nothing thereby suppressing quicksearch in the sidebar when enabled in  -->
<!-- navigation menu bar and ONLY when navigation bar is above or below header -->
   {else}
      <div class="serendipitySideBarItem container_{$item.class}">
         {if $item.title != ""}
            <h3 class="serendipitySideBarTitle {$item.class}">{$item.title}</h3>
         {else}
            <div class="serendipitySideBarTitleEmpty"></div>
         {/if}
         <div class="serendipitySideBarContent">{$item.content}</div>
         <div class="serendipitySideBarFooter"></div>
      </div>
   {/if}
{/foreach}
{if $is_raw_mode}
</div>
{/if}
