{if isset($searchresult_tooShort) OR isset($searchresult_noEntries)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        <div id="search-response" class="panel panel-warning">
            <div class="panel-heading">
                <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span><span class="sr-only">{$CONST.CLOSE}</span></button>
                <h3 class="panel-title">{$CONST.SEARCH}</h3>
            </div>
            <div class="panel-body">
                <p><span class="fa-stack text-warning" aria-hidden="true"><i class="far fa-frown fa-2x"></i></span> {$content_message}</p>
                <div class="input-group" style="width:100%; margin-bottom: 20px;">{* REMOVE INLINE STYLES *}
                    <form id="searchform" class="input-group" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
                        <input type="hidden" name="serendipity[action]" value="search" />
                        <label for="serendipityQuickSearchTermFieldBox" class="sr-only">{$CONST.QUICKSEARCH}</label>
                        <input id="serendipityQuickSearchTermFieldBox" class="form-control mr-3" alt="{$CONST.SEARCH_SITE}" type="text" name="serendipity[searchTerm]" value="{$CONST.SEARCH}..." onfocus="if(this.value=='{$CONST.SEARCH}...')value=''" onblur="if(this.value=='')value='{$CONST.SEARCH}...';" />
                        <span class="input-group-btn">
                            <input class="btn btn-secondary btn-sm btn-theme quicksearch_submit" type="submit" value="{$CONST.GO}" alt="{$CONST.SEARCH_SITE}" name="serendipity[searchButton]" title="{$CONST.SEARCH}" />
                        </span>
                        <div id="LSResult" style="display: none;"><div id="LSShadow"></div></div>
                    </form>
                </div>
                {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
            </div>
        </div>
    </div>
</div>
{if empty($searchresult_results) AND NOT empty($comment_searchresults) AND NOT empty($comment_results)}{$comment_search_result}{/if}

{elseif isset($searchresult_error)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        <div id="search-response" class="panel panel-danger">
            <div class="panel-heading">
                <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span><span class="sr-only">{$CONST.CLOSE}</span></button>
                <h3 class="panel-title">{$CONST.SEARCH}</h3>
            </div>
            <div class="panel-body">
                <p><span class="fa-stack text-danger" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$content_message}</p>
                <div class="input-group" style="width:100%; margin-bottom: 20px;">{* REMOVE INLINE STYLES *}
                    <form id="searchform" class="input-group" action="{$serendipityHTTPPath}{$serendipityIndexFile}" method="get">
                        <input type="hidden" name="serendipity[action]" value="search" />
                        <label for="serendipityQuickSearchTermFieldBox" class="sr-only">{$CONST.QUICKSEARCH}</label>
                        <input id="serendipityQuickSearchTermFieldBox" class="form-control mr-3" alt="{$CONST.SEARCH_SITE}" type="text" name="serendipity[searchTerm]" value="{$CONST.SEARCH}..." onfocus="if(this.value=='{$CONST.SEARCH}...')value=''" onblur="if(this.value=='')value='{$CONST.SEARCH}...';" />
                        <span class="input-group-btn">
                            <input class="btn btn-secondary btn-sm btn-theme quicksearch_submit" type="submit" value="{$CONST.GO}" alt="{$CONST.SEARCH_SITE}" name="serendipity[searchButton]" title="{$CONST.SEARCH}" />
                        </span>
                        <div id="LSResult" style="display: none;"><div id="LSShadow"></div></div>
                    </form>
                </div>
                {serendipity_hookPlugin hook="quicksearch_plugin" hookAll="true"}
            </div>
        </div>
    </div>
</div>

{elseif isset($searchresult_results)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        <div id="search-response" class="panel panel-success">
            <div class="panel-heading">
                <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span><span class="sr-only">{$CONST.CLOSE}</span></button>
                <h3 class="panel-title">{$CONST.SEARCH}</h3>
            </div>
            <div class="panel-body">
                <span class="fa-stack text-success" aria-hidden="true"><i class="far fa-smile fa-2x"></i></span> {$content_message}
            </div>
        </div>
    </div>
</div>

{elseif isset($subscribe_confirm_error)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        <div id="search-response" class="panel panel-danger">
            <div class="panel-heading">
                <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span><span class="sr-only">{$CONST.ERROR}</span></button>
                <h3 class="panel-title">{$CONST.ERROR}</h3>
            </div>
            <div class="panel-body">
                <span class="fa-stack text-danger" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$content_message}
            </div>
        </div>
    </div>
</div>

{elseif isset($subscribe_confirm_success)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        <div id="search-response" class="panel panel-success">
            <div class="panel-heading">
                <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span><span class="sr-only">{$CONST.CLOSE}</span></button>
                <h3 class="panel-title">{$CONST.SUCCESS}</h3>
            </div>
            <div class="panel-body">
                <span class="fa-stack text-success" aria-hidden="true"><i class="far fa-smile fa-2x"></i></span> {$content_message}
            </div>
        </div>
    </div>
</div>
{elseif isset($content_message)}
<div id="search-block" class="row">
    <div class="col-md-8 col-md-offset-2">
        {if $content_message|strip == $content_message}<div class="alert alert-info alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-target="#search-block" data-dismiss="alert" aria-label="Close" title="{$CONST.CLOSE}"><span aria-hidden="true">&times;</span></button>
            <span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-info fa-stack-1x"></i></span></span> {$content_message}
        </div>{else}{$content_message}{/if}
    </div>
</div>
{/if}

{$ENTRIES}
{$ARCHIVES}