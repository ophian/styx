<header id="dashboard_header" class="clearfix">
    <h2>{$CONST.WELCOME_BACK} {$username|escape}</h2>
    <a href="#s9y_links" class="button_link toggle_links"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.FURTHER_LINKS}</span></a>
</header>

<div id="dashboard">
{if isset($published)}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ENTRY_PUBLISHED|sprintf:$published|escape}</span>
    <hr class="separator">
{/if}
{if isset($error_publish)}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.PUBLISH_ERROR}: {$error_publish}</span>
    <hr class="separator">
{/if}
{if isset($updateCheck) AND ($updateCheck == "stable" OR $updateCheck == "beta")}
{if $curVersion == -1}
    <section id="dashboard_update" class="clearfix dashboard_widget">
        <h3>{$CONST.UPDATE_NOTIFICATION}{if $isCustom}: Custom release file!{/if}</h3>

        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.UPDATE_FAILMSG|sprintf:$releaseFUrl}</span>
        <form id="updateCheckDisable" method="POST">
            <input type="hidden" name="serendipity[adminAction]" value="updateCheckDisable">
            {$token}
            <button type="submit">{$CONST.UPDATE_FAILACTION}</button>
        </form>
    </section>
    <hr class="separator">
{else if $update}
    <section id="dashboard_update" class="clearfix dashboard_widget">
        <h3>{$CONST.UPDATE_NOTIFICATION}{if $isCustom}: Custom release file!{/if}</h3>

        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.NEW_VERSION_AVAILABLE|replace:'Serendipity':$curVersName} {$curVersion}</span>
        {$updateButton}
    </section>
    <hr class="separator">
{/if}
{/if}

{if $no_create !== true}
    <section id="dashboard_ticker" class="clearfix dashboard_widget{if NOT isset($shortcuts)} expand blend{/if}">
        <h3>{$CONST.DASHBOARD_INFO_HEADER}</h3>

        <div class="{if NOT isset($shortcuts)}msg_{/if}notice">
{if isset($shortcuts)}{* $CONST.DASHBOARD_INFO_CONTENT}: *}{else}
            <span class="icon-info-circled" aria-hidden="true"></span> <em>{$CONST.DASHBOARD_INFO_EMPTY}</em>
{/if}
{if isset($shortcuts)}
            <ul class="plainList">
{if $comments['pending']['count'] > 0}
                <li><a href="{$comments['pending']['link']}">{$CONST.COMMENTS_PENDING}</a> [<span class="hl">{$comments['pending']['count']}</span>]</li>
{/if}
{if $entries['futures']['count'] > 0}
                <li><a href="{$entries['futures']['link']}">{$CONST.FUTURES_AVAILABLE}</a> [<span class="hl">{$entries['futures']['count']}</span>]</li>
{/if}
{if $entries['drafts']['count'] > 0}
                <li><a href="{$entries['drafts']['link']}">{$CONST.DRAFTS_AVAILABLE}</a> [<span class="hl">{$entries['drafts']['count']}</span>]</li>
{/if}
            </ul>
{/if}
        </div>
    </section>
{if NOT empty($sysinfo)}

    <section id="dashboard_sysinfo" class="clearfix dashboard_widget">
        <h3>{$CONST.DASHBOARD_SYSTEM_TICKER}</h3>

        <div>{$CONST.DASHBOARD_SYSINFO_GREETING}</div>

        <form id="sysinfo_notifications" name="serendipity[sysinfo]" method="post" target="_self">
            <ul class="plainList">
{foreach $sysinfo AS $info}
                <li>
                    <div class="msg_notice">
                        <header>{$CONST.PUBLISHED} {$CONST.ON} <time datetime="{$info.ts|escape|formatTime:"%Y-%m-%dT%H:%M:%SZ"}" pubdate>{$info.ts|escape|formatTime:$template_option.date_format}</time></header>
                        <span class="sysinfo_title">{$info.title|escape|truncate:46:"&hellip;":true}</span><span class="sysinfo_priority sysinfo_priority-{$info.priority}">{"🔹"|str_repeat:$info.priority}</span>
                        <span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden">{$info.title|escape}</span> {$info.msg|escape}
                        <div class="sysinfo_item">
                            <input type="hidden" name="serendipity[sysinfo][checked][hash][{$info@key}][0]" value="0">
                            <input type="checkbox" name="serendipity[sysinfo][checked][hash][{$info@key}][1]" value="{$info.hash|escape}">
                            <label for="serendipity[sysinfo][checked][hash]">
                                {$CONST.DASHBOARD_SYSINFO_HIDE_NOTE}
                            </label>
                        </div>
                    </div>
                </li>
{/foreach}

            </ul>
            <input name="serendipity[sysinfo][go]" type="submit" value="{$CONST.HIDE}">
        </form>
    </section>
{/if}
{serendipity_hookPlugin hook="backend_dashboard" hookAll="true"}

{/if}{* no create end *}
    <section id="s9y_links" class="clearfix mfp-hide dashboard_widget">
        <h3>{$CONST.FURTHER_LINKS}</h3>

        <ul class="plainList">
            <li><a target="_blank" rel="noopener" href="https://ophian.github.io/">{$CONST.FURTHER_LINKS_S9Y} (Styx)</a></li>
            <li><a target="_blank" rel="noopener" href="https://ophian.github.io/hc/en/">{$CONST.FURTHER_LINKS_S9Y_DOCS} (Styx)</a></li>
            <li><a target="_blank" rel="noopener" href="https://ophian.github.io/book/">Serendipity Styx Edition Book [de]</a></li>
            <li><a target="_blank" rel="noopener" href="https://ophian.github.io/blog">{$CONST.FURTHER_LINKS_S9Y_BLOG} (Styx)</a></li>
            <li><a target="_blank" rel="noopener" href="https://ophian.github.io/plugins/">{$CONST.FURTHER_LINKS_S9Y_SPARTACUS} (Styx) Web</a></li>
            <li><a target="_blank" rel="noopener" href="https://github.com/ophian/styx">GitHub Serendipity Styx Edition</a></li>
            <li>&nbsp;</li>
            <li><a class="s9y_bookmarklet" href="{$bookmarklet}" title="{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET_DESC}">{$CONST.FURTHER_LINKS_S9Y_BOOKMARKLET}</a></li>
        </ul>
    </section>

    <section id="s9y_quicktip" class="clearfix mfp-hide quick_list dashboard_widget">

        {include "./doc/quicktip_{$lang}.tpl" caching}

    </section>

{$backend_frontpage_display}

</div><!-- dashboard end -->

<script type="text/javascript">
    $(document).ready(function() {
        if (typeof(serendipity) != 'object' || typeof(serendipity.spawn) != 'function') {
            $('#dashboard_header').after("<span class=\"msg_error\"><span class=\"icon-attention-circled\"></span> {$CONST.JS_FAILURE|sprintf:$js_failure_file|escape:'javascript'}</span>");
        }

        if ($("#dashboard_ticker").hasClass('blend')) {
            if (Cookies.get('styx_tickerBlend')) {
                $("#dashboard_ticker").hide();
            } else {
                $("#dashboard_ticker").delay(5000).fadeOut( 2500, 'linear' );
                Cookies.set('styx_tickerBlend', true, { path: '{$serendipityHTTPPath}', sameSite: 'lax' });
            }
        }

        if ($("#dashboard_plugup").hasClass('blend')) {
            if (Cookies.get('styx_plugupBlend')) {
                $("#dashboard_plugup").hide();
            } else {
                $("#dashboard_plugup").delay(5000).fadeOut( 2500, 'linear' );
                Cookies.set('styx_plugupBlend', true, { path: '{$serendipityHTTPPath}', sameSite: 'lax' });
            }
        }
    });
</script>
