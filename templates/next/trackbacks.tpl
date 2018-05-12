{foreach $trackbacks AS $trackback}
    <article id="c{$trackback.id}" class="{$trackback.type|lower} {cycle values="tb-odd,tb-even"}">
        <h4><cite>{$trackback.author|default:$CONST.ANONYMOUS}</cite> {$CONST.ON} <time datetime="{$trackback.timestamp|serendipity_html5time}">{$trackback.timestamp|formatTime:$template_option.date_format}</time>: <a href="{$trackback.url|strip_tags}">{$trackback.title}</a></h4>
    {* This regex removes a possible avatar image automatically added by the serendipity_event_gravatar plugin *}
    {if {$trackback.body|regex_replace:"/^<img.*>$/":''} == ''}
        {if $trackback.type == 'TRACKBACK'}<p class="msg-notice no-content"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_ENTRIES_TO_PRINT}</p>{/if}
    {else}
        <details>
            <summary>{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$trackback.title}</summary>
            <div class="clearfix">
            {$trackback.body|strip_tags|escape:'htmlall'} [&hellip;]
            </div>
        </details>
    {/if}
    </article>
{foreachelse}
    <p class="msg-notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NO_TRACKBACKS}</p>
{/foreach}
