{if !isset($staticpage_custom.show_author)}{$staticpage_custom.show_author = null}{/if}
{if !isset($staticpage_custom.show_date)}{$staticpage_custom.show_date = null}{/if}
{if isset($searchresult_tooShort) || isset($searchresult_noEntries)}
    <div class="alert alert-info"><h4>{$CONST.SEARCH}</h4><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-info fa-stack-1x"></i></span> {$content_message}</div>
    {if empty($searchresult_results) AND NOT empty($comment_searchresults) AND NOT empty($comment_results)}{$comment_search_result}{/if}
{elseif isset($searchresult_error)}
    <div class="alert alert-danger"><h4>{$CONST.SEARCH}</h4><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-exclamation fa-stack-1x"></i></span> {$content_message}</div>
{elseif isset($searchresult_results)}
    <div class="alert alert-success"><h4>{$CONST.SEARCH}</h4><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-check fa-stack-1x"></i></span> {$content_message}</div>
{elseif isset($subscribe_confirm_error)}
    <div class="alert alert-danger"><h4>{$CONST.ERROR}</h4><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-exclamation fa-stack-1x"></i></span> {$content_message}</div>
{elseif isset($subscribe_confirm_success)}
    <div class="alert alert-success"><h4>{$CONST.SUCCESS}</h4><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-check fa-stack-1x"></i></span> {$content_message}</div>
{elseif isset($content_message)}
    {if $content_message|strip == $content_message}<div class="alert alert-info"><span class="fa-stack" aria-hidden="true"><i class="fa fa-circle-thin fa-stack-2x"></i><i class="fa fa-info fa-stack-1x"></i></span>  {$content_message}</div>{else}{$content_message}{/if}
{/if}

{$ENTRIES|default:''}
{$ARCHIVES}
