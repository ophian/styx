
            <div class="clearfix pages_found">
                <h3>{$CONST.STATICPAGE_SEARCHRESULTS|sprintf:$staticpage_searchresults}</h3>
{if $staticpage_results}

                <dl>
{foreach $staticpage_results AS $result}
                    <dt><a href="{$result.permalink|escape}" title="{$result.pagetitle|escape} ({$result.realname})">{if NOT empty($result.headline)}{$result.headline}{else}{$result.pagetitle|upper|escape}-{$CONST.PAGE|lower}{/if}</a></dt>
                    <dd>{$result.content|strip_tags|truncate:200:"..."}</dd>
{/foreach}
                </dl>
{else}

                <p class="serendipity_msg_notice">{$CONST.NO_ENTRIES_TO_PRINT}</p>
{/if}
            </div>
