{if isset($adminAction) AND $adminAction == 'install'}

    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$install_template|string_format:"{$CONST.TEMPLATE_SET}"}</span>
{/if}
{if isset($deprecated) AND $deprecated}

    <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.WARNING_TEMPLATE_DEPRECATED}</span>
{/if}

{if isset($adminAction) AND ($adminAction == 'configure' OR $adminAction == 'editConfiguration')}

    <section id="template_options">
        <h2>{$CONST.STYLE_OPTIONS} ({$cur_template})</h2>
    {if NOT empty($has_config)}

        {if $adminAction == 'configure'}

        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}: {$save_time}</span>
        {/if}

        <form class="theme_options option_list" method="post" action="serendipity_admin.php">
            <input name="serendipity[adminModule]" type="hidden" value="templates">
            <input name="serendipity[adminAction]" type="hidden" value="configure">
            {$form_token}
            {$configuration}
        </form>
    {else}

        <p>{$CONST.STYLE_OPTIONS_NONE}</p>
        <a class="button_link" href="?serendipity[adminModule]=templates" title="{$CONST.BACK}">{$CONST.BACK}</a>
    {/if}

    </section>
{else}

    <script>$(document).ready(function() { var stcol = Cookies.get('serendipity[theme_grid]'); if (stcol != 'undefined') { serendipity.changeThemeGrid(stcol) } });</script>
    <section id="template_select" class="clearfix">
        <h2>{$CONST.CURRENT_TEMPLATE}{* since the #template_select container is already flexed, there is no other way than having the grid-selector float in the h2 format. Current frontend and backend templates are 2 items only so they don't need to switch the grid. *}

            <div id="grid-selector" class="theme-grid-selector">
                <div id="col-def-selector" class="mediaGrid" title="2-column grid" onclick="serendipity.changeThemeGrid('tmDefCol')">
                  <div class="mediaGrid-cell tic"></div>
                  <div class="mediaGrid-cell tac"></div>
                </div>
                <div id="col-mid-selector" class="mediaGrid" title="3-column grid" onclick="serendipity.changeThemeGrid('tmMidCol')">
                  <div class="mediaGrid-cell tac"></div>
                  <div class="mediaGrid-cell tic"></div>
                  <div class="mediaGrid-cell tac"></div>
                </div>
                <div id="col-max-selector" class="mediaGrid" title="4-column grid" onclick="serendipity.changeThemeGrid('tmMaxCol')">
                  <div class="mediaGrid-cell tic"></div>
                  <div class="mediaGrid-cell tac"></div>
                  <div class="mediaGrid-cell tic"></div>
                  <div class="mediaGrid-cell tac"></div>
                </div>
            </div>
        </h2>

        <article class="clearfix current_template">
            <h3 title="{$cur_tpl.info.name}">{$CONST.FRONTEND}: {$cur_tpl.info.name|truncate:25:"&hellip;"}</h3>

            <div class="clearfix equal_heights template_wrap">
                <div class="template_preview">
            {if NOT empty($cur_tpl.fullsize_preview) OR NOT empty($cur_tpl.preview)}
                {if NOT empty($cur_tpl.fullsize_preview)}

                    <a class="media_fullsize" href="{$cur_tpl.fullsize_preview_webp|default:$cur_tpl.fullsize_preview}" data-fallback="{$cur_tpl.fullsize_preview}" title="{$CONST.MEDIA_FULLSIZE}: {$cur_tpl.info.name}">
                        <picture>
                          <source type="image/webp" srcset="{$cur_tpl.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                          <img src="{$cur_tpl.preview|default:$cur_tpl.fullsize_preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                        </picture>
                    </a>
                {else}

                    <picture>
                      <source type="image/webp" srcset="{$cur_tpl.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                      <img src="{$cur_tpl.preview|default:$cur_tpl.fullsize_preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                    </picture>
                {/if}
             {else}

                    <svg class="svg-placeholder-img svg-placeholder-img-lg" width="431" height="266" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder {$cur_tpl.info.name}</title><rect x="0px" y="0px" width="100%" height="100%" fill="#1c2128"/><text x="50%" y="42%" fill="#eceeef" dy=".3em">No preview</text><text x="50%" y="58%" fill="#e5534b" dy=".3em">{$cur_tpl.info.name}</text></svg>
             {/if}

                    <footer id="template_info_cur" class="template_info additional_info">
                        <dl class="clearfix">
                            <dt class="template_author">{$CONST.AUTHOR}:</dt>
                            <dd>{$cur_tpl.info.author}</dd>
                            <dt class="template_date">{$CONST.LAST_UPDATED}:</dt>
                            <dd>{$cur_tpl.info.date}</dd>
                            <dt class="template_config">{$CONST.CUSTOM_CONFIG}:</dt>
                            <dd>{$cur_tpl.info.custom_config|default:$CONST.NO}</dd>
                            {if isset($cur_tpl.info.custom_admin_interface)}<dt class="template_admin">{$CONST.CUSTOM_ADMIN_INTERFACE}:</dt>
                            <dd>{$cur_tpl.info.custom_admin_interface}</dd>
                            {/if}{if isset($cur_tpl.info.engine)}<dt class="template_description">Engine:</dt>
                            <dd>{$cur_tpl.info.engine}</dd>{/if}

                            <dt class="template_responsive">{$CONST.RESPONSIVE}:</dt>
                            <dd>{if !empty($cur_tpl.info.responsive)}{$cur_tpl.info.responsive}{else}{$CONST.NOT_AVAILABLE}{/if}</dd>
                            <dt class="template_mobile">{$CONST.MOBILE|default:'Mobile'}:</dt>
                            <dd>{if !empty($cur_tpl.info.mobile)}{$cur_tpl.info.mobile}{else}{$CONST.NOT_AVAILABLE}{/if}</dd>
                            <dt>&nbsp;</dt>

                            {if !empty($cur_tpl.info.summary)}<dt class="template_summary">{$CONST.SUMMARY}:</dt>
                            <dd>{$cur_tpl.info.summary}</dd>
                            {/if}{if !empty($cur_tpl.info.description)}<dt class="template_description">{$CONST.DESCRIPTION}:</dt>
                            <dd>{$cur_tpl.info.description}</dd>
                            {/if}

                        </dl>
                        {if empty($cur_tpl.info.custom_config)}<p>{$CONST.STYLE_OPTIONS_NONE}</p>{/if}

                    </footer>
                </div>
            </div>

            <button class="template_show_info button_link" type="button" data-href="#template_info_cur" title="{$CONST.TEMPLATE_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TEMPLATE_INFO}</span></button>
            {if !empty($cur_tpl.info.custom_config)}<a class="button_link" href="?serendipity[adminModule]=templates&amp;serendipity[adminAction]=editConfiguration&amp;{$urltoken}" title="{$CONST.CONFIGURATION}">{$CONST.CONFIGURATION}</a>{/if}
            {if isset($cur_tpl.info.custom_admin_interface) AND $cur_tpl.info.custom_admin_interface == $CONST.YES AND $cur_tpl.info.name != $cur_tpl_backend.info.name}<a class="button_link" href="?serendipity[adminModule]=templates&amp;serendipity[adminAction]=install-backend&amp;serendipity[theme]={$template}{if isset($cur_tpl.info.customURI)}{$cur_tpl.info.customURI|default:''}{/if}&amp;{$urltoken}" title="{$CONST.SET_AS_TEMPLATE}">{$CONST.INSTALL}: {$CONST.BACKEND}</a>{/if}

        </article>

    {if $cur_template_backend}
        <article class="clearfix current_backend_template">
            <h3 title="{$cur_tpl_backend.info.name}">{$CONST.BACKEND}: {$cur_tpl_backend.info.name|truncate:27:"&hellip;"}</h3>

            <div class="clearfix equal_heights template_wrap">
                <div class="template_preview">
            {if NOT empty($cur_tpl_backend.fullsize_backend_preview) OR NOT empty($cur_tpl_backend.preview_backend)}
                {if $cur_tpl_backend.fullsize_backend_preview}

                    <a class="media_fullsize" href="{$cur_tpl_backend.fullsize_backend_preview_webp|default:$cur_tpl_backend.fullsize_backend_preview}" data-fallback="{$cur_tpl_backend.fullsize_backend_preview}" title="{$CONST.MEDIA_FULLSIZE}: {$cur_tpl_backend.info.name}">
                        <picture>
                          <source type="image/webp" srcset="{$cur_tpl_backend.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                          <img src="{$cur_tpl_backend.preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                        </picture>
                    </a>
                {else}

                    <picture>
                      <source type="image/webp" srcset="{$cur_tpl_backend.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                      <img src="{$cur_tpl_backend.preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                    </picture>
                {/if}
            {/if}

                    <footer id="template_info_cur_backend" class="template_info additional_info">
                        <dl class="clearfix">
                            <dt class="template_author">{$CONST.AUTHOR}:</dt>
                            <dd>{$cur_tpl_backend.info.author}</dd>
                            <dt class="template_date">{$CONST.LAST_UPDATED}:</dt>
                            <dd>{$cur_tpl_backend.info.date}</dd>
                            <dt class="template_bdesc">{$CONST.DESCRIPTION}:</dt>
                            <dd>{$cur_tpl_backend.info.backenddesc}</dd>
                        </dl>
                    </footer>
                </div>
            </div>

            <button class="template_show_info button_link" type="button" data-href="#template_info_cur_backend" title="{$CONST.TEMPLATE_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TEMPLATE_INFO}</span></button>
        </article>
    {/if}

        {function name=templateBlock}
            <li class="theme_file tmDefCol">
                <article class="clearfix">
                    <h3 title="{$template.info.name}">{$template.info.name|truncate:27:"&hellip;"}</h3>
                    <div class="clearfix equal_heights template_wrap">
                        <div class="template_preview">
                    {if NOT empty($template.fullsize_preview) OR NOT empty($template.preview)}
                        {if NOT empty($template.fullsize_preview)}

                            <a class="media_fullsize" href="{$template.fullsize_preview_webp|default:$template.fullsize_preview}" data-fallback="{$template.fullsize_preview}" title="{$CONST.MEDIA_FULLSIZE}: {$template.info.name}">
                                <picture>
                                  <source type="image/webp" srcset="{$template.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                                  <img src="{$template.preview|default:$template.fullsize_preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                                </picture>
                            </a>
                        {else}

                            <picture>
                              <source type="image/webp" srcset="{$template.preview_webp|default:''}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                              <img src="{$template.preview|default:$template.fullsize_preview}" class="template_preview_img" alt="{$CONST.PREVIEW}">
                            </picture>
                        {/if}
                    {else}

                            <svg class="svg-placeholder-img svg-placeholder-img-lg" width="431" height="266" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder {$template.info.name}</title><rect x="0px" y="0px" width="100%" height="100%" fill="#1c2128"/><text x="50%" y="42%" fill="#eceeef" dy=".3em">No preview</text><text x="50%" y="58%" fill="#e5534b" dy=".3em">{$template.info.name}</text></svg>
                    {/if}

                            <footer id="template_info_{$key}" class="template_info additional_info">
                                <dl class="clearfix">
                                    <dt class="template_author">{$CONST.AUTHOR}:</dt>
                                    <dd>{$template.info.author|default:''}</dd>
                                    <dt class="template_date">{$CONST.LAST_UPDATED}:</dt>
                                    <dd>{$template.info.date}</dd>
                                    <dt class="template_config">{$CONST.CUSTOM_CONFIG}:</dt>
                                    <dd>{$template.info.custom_config|default:$CONST.NO}</dd>
                                    {if isset($template.info.custom_admin_interface)}<dt class="template_admin">{$CONST.CUSTOM_ADMIN_INTERFACE}:</dt>
                                    <dd>{if $template.info.custom_admin_interface}{$template.info.custom_admin_interface}{else}{$CONST.NO}{/if}</dd>
                                    {/if}{if isset($template.info.engine)}<dt class="template_description">Engine:</dt>
                                    <dd>{$template.info.engine}</dd>{/if}

                                    <dt class="template_responsive">{$CONST.RESPONSIVE}:</dt>
                                    <dd>{if !empty($template.info.responsive)}{$template.info.responsive}{else}{$CONST.NOT_AVAILABLE}{/if}</dd>
                                    <dt class="template_mobile">{$CONST.MOBILE|default:'Mobile'}:</dt>
                                    <dd>{if !empty($template.info.mobile)}{$template.info.mobile}{else}{$CONST.NOT_AVAILABLE}{/if}</dd>
                                    <dt>&nbsp;</dt>

                                    {if !empty($template.info.summary)}<dt class="template_summary">{$CONST.SUMMARY}:</dt>
                                    <dd>{$template.info.summary}</dd>
                                    {/if}{if !empty($template.info.description)}<dt class="template_description">{$CONST.DESCRIPTION}:</dt>
                                    <dd>{$template.info.description}</dd>
                                    {/if}

                                </dl>
                            </footer>
                        </div>
                    </div>

                    <button class="template_show_info button_link" type="button" data-href="#template_info_{$key}" title="{$CONST.TEMPLATE_INFO}"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TEMPLATE_INFO}</span></button>
                    {if !empty($template.demoURL)}<a class="demo_link button_link" href="{$template.demoURL}" title="{$CONST.THEMES_PREVIEW_BLOG}" target="_blank" rel="noopener"><span class="icon-search" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.THEMES_PREVIEW_BLOG}</span></a>{/if}
                {if !isset($template.unmetRequirements)}
                    {if isset($template.info.custom_admin_interface) AND $template.info.custom_admin_interface == $CONST.YES AND $cur_tpl_backend.info.name != $template.info.name}

                    {if empty($template.info.custom_admin_only_interface)}<a class="button_link" href="?serendipity[adminModule]=templates&amp;serendipity[adminAction]=install-frontend&amp;serendipity[theme]={$key}&amp;{$urltoken}" title="{$CONST.SET_AS_TEMPLATE}">{$CONST.FRONTEND}</a>{/if}
                    <a class="button_link" href="?serendipity[adminModule]=templates&amp;serendipity[adminAction]=install-backend&amp;serendipity[theme]={$key}&amp;{$urltoken}" title="{$CONST.SET_AS_TEMPLATE}">{$CONST.BACKEND}</a>
                    {else}

                    {if empty($template.info.custom_admin_only_interface)}<a class="button_link" href="?serendipity[adminModule]=templates&amp;serendipity[adminAction]=install&amp;serendipity[theme]={$key}&amp;{$urltoken}" title="{$CONST.SET_AS_TEMPLATE}">{$CONST.INSTALL}: {$CONST.FRONTEND}</a>{/if}
                    {/if}
                {else}

                    <span class="unmet_requirements msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$template.unmetRequirements}</span>
                {/if}

                </article>
            </li>
        {/function}

        <h2>{$CONST.CORE_THEMES}</h2>
        <ul class="plainList">
        {foreach $core_templates AS $template}
            {if $template@key == $cur_template_backend AND isset($cur_tpl_backend.info.modul) AND $cur_tpl_backend.info.modul|lower == 'backend'}{continue}{/if}
            {templateBlock template=$template key=$template@key}
        {/foreach}
        </ul>

        <h2>{$CONST.AVAILABLE_TEMPLATES}</h2>

        <ul class="plainList">
        {foreach $templates AS $template}
            {templateBlock template=$template key=$template@key}
        {/foreach}
        </ul>
    </section>
    {* change the link url for old browsers not supporting WebP images *}
    <script>
        Modernizr.on('webp', function(result) {
          if (!result) { $('.media_fullsize').on( "mouseenter mouseleave", function() { $(this).attr('href', $(this).data('fallback')); }); }
        });
    </script>
{/if}
