<!-- CONTENT START -->
{if NOT empty($content_message)}

        <section id="section_content_alert">
  {if !empty($searchresult_tooShort)}

          <div id="search_results_nav" class="serendipity_Entry_Date results_navigation">
            <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
            <div class="serendipity_search serendipity_search_tooshort">{$content_message}</div>
          </div>
  {elseif !empty($searchresult_error)}

          <div id="search_results_nav" class="serendipity_Entry_Date results_navigation">
            <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
            <div class="serendipity_search serendipity_search_error">{$content_message}</div>
          </div>
  {elseif !empty($searchresult_noEntries)}

          <div id="search_results_nav" class="serendipity_Entry_Date results_navigation">
            <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
            <div class="serendipity_search serendipity_search_noentries">{$content_message|replace:'"':''}</div>
          </div>
  {elseif !empty($searchresult_results)}

          <div id="search_results_nav" class="serendipity_Entry_Date results_navigation">
            <h3 class="serendipity_date">{$CONST.QUICKSEARCH}</h3>
            <div class="serendipity_search serendipity_search_results">{$content_message|replace:'"':''}</div>
          </div>
  {elseif !empty($subscribe_confirm_error)}

          <div class="serendipity_Entry_Date">
            <h3 class="serendipity_date">{$CONST.ERROR}</h3>
            <div class="serendipity_msg_important comment_subscribe_error">{$content_message}</div>
          </div>
  {elseif !empty($subscribe_confirm_success)}

          <div class="serendipity_Entry_Date">
            <h3 class="serendipity_date">{$CONST.SUCCESS}</h3>
            <div class="serendipity_msg_notice comment_subscribe_success">{$content_message}</div>
          </div>
  {else}

          <div class="serendipity_Entry_Date">
            <div class="serendipity_content_message serendipity_msg_notice"><em>{$content_message}</em></div>
          </div>
  {/if}

        </section><!-- // id:#section_content_alert end -->
{/if}

{$ENTRIES}{* pre parsed and may also be $COMMENTS *}
{$ARCHIVES}

        <!-- CONTENT END -->
