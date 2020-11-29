{if isset($plugin_to_conf) AND $plugin_to_conf}
    {if isset($save_errors) AND is_array($save_errors)}
    <div class="msg_error">
        <h2><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.ERROR}:</h2>

        <ul class="plainList">
        {foreach $save_errors AS $save_error}
            <li>{$save_error}</li>
        {/foreach}
        </ul>
    </div>
    {elseif isset($saveconf) AND $saveconf}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}: {$CONST.SETTINGS_SAVED_AT|sprintf:"$timestamp"}</span>
    {/if}
    {if $has_config_groups > 0}
    <section id="plugin_options">

    {/if}
    {if $is_stackable}{if $no_stack}<h2 title="{$CONST.STACKABLE_PLUGIN|escape}">{$name} ({$class})<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-layers" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M3.188 8L.264 9.559a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882L12.813 8l-1.063.567L14.438 10 8 13.433 1.562 10 4.25 8.567 3.187 8z"/>
  <path fill-rule="evenodd" d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4zM1.563 6L8 9.433 14.438 6 8 2.567 1.562 6z"/>
</svg></h2>{elseif $multi_stack}<h2 title="{$CONST.MULTISTACK_PLUGIN|escape}">{$name} ({$class})<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-layers-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4z"/>
  <path fill-rule="evenodd" d="M2.125 8.567l-1.86.992a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882l-1.86-.992-5.17 2.756a1.5 1.5 0 0 1-1.41 0l-5.17-2.756z"/>
</svg></h2>{elseif $has_stack}<h2 title="{$CONST.STACKED_PLUGIN|escape}">{$name} ({$class})<svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-layers-half" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
  <path fill-rule="evenodd" d="M3.188 8L.264 9.559a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882L12.813 8l-4.578 2.441a.5.5 0 0 1-.47 0L3.188 8z"/>
  <path fill-rule="evenodd" d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4zM1.563 6L8 9.433 14.438 6 8 2.567 1.562 6z"/>
</svg></h2>{/if}{else}<h2>{$name} ({$class})</h2>{/if}

    <div class="plugin_info">
        <p><b>{$CONST.DESCRIPTION}:</b> {$desc}</p>
    {if !empty($license)}
        <p><b>{$CONST.MEDIA_PROPERTY_COPYRIGHT}:</b> {$license}</p>
    {/if}
    {if isset($smarty.post.SAVECONF)}{assign var='point' value='new'}{else}{$point = null}{/if}
    {if !empty($documentation) OR (isset($changelog) AND $changelog) OR (isset($documentation_local) AND $documentation_local)}
        <ul class="plainList">
        {if !empty($documentation)}
            <li class="plugin_docu"><a target="_{$point|default:'self'}" href="{$documentation|escape}">{$CONST.PLUGIN_DOCUMENTATION}</a>{if isset($point)} <span class="icon-info-circled" aria-hidden="true" title="in new tab"></span><span class="visuallyhidden"> in new tab</span>{/if}</li>
        {/if}
        {if isset($changelog) AND $changelog}
            <li class="plugin_docu"><a target="_{$point|default:'self'}" href="plugins/{$plugin->act_pluginPath}/ChangeLog">{$CONST.PLUGIN_DOCUMENTATION_CHANGELOG}</a>{if isset($point)} <span class="icon-info-circled" aria-hidden="true" title="in new tab"></span><span class="visuallyhidden"> in new tab</span>{/if}</li>
        {/if}
        {if isset($documentation_local) AND $documentation_local}
            <li class="plugin_docu"><a target="_{$point|default:'self'}" href="plugins/{$plugin->act_pluginPath}{$documentation_local}">{$CONST.PLUGIN_DOCUMENTATION_LOCAL}</a>{if isset($point)} <span class="icon-info-circled" aria-hidden="true" title="in new tab"></span><span class="visuallyhidden"> in new tab</span>{/if}</li>
        {/if}
        </ul>
    {/if}
    </div>

    <form class="configure_plugin option_list" method="post" name="serendipityPluginConfigure">
        {$formToken}
        {$CONFIG}
    </form>
    {if $has_config_groups > 0}
    </section>

    {/if}
{elseif isset($adminAction) AND $adminAction == 'addnew'}
    <h2>{if $type == 'event'}{$CONST.EVENT_PLUGINS}{/if}{if $type == 'sidebar'}{$CONST.SIDEBAR_PLUGINS}{/if}{if $type == 'both'}{$CONST.MENU_PLUGINS}{/if}{if $only_group != 'UPGRADE'} <span class="plugins_available">({$CONST.PLUGIN_AVAILABLE_COUNT|sprintf:$count_pluginstack})</span>{/if}</h2>
    {foreach $errorstack AS $e_idx => $e_name}
    <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.ERROR}: {$e_name}</span>
    {/foreach}

    <form action="serendipity_admin.php" method="get">
        {$formToken}
        <input name="serendipity[adminModule]" type="hidden" value="plugins">
        <input name="serendipity[adminAction]" type="hidden" value="addnew">
        <input name="serendipity[type]" type="hidden" value="{$type|escape}">

        <div class="clearfix">
            {if $only_group != 'UPGRADE'}
                <div id="plugin_groups" class="update_group form_select">
                    <label for="only_group">{$CONST.GROUP}</label>
                    <select id="only_group" name="serendipity[only_group]">
                    {foreach $groupnames AS $available_group => $available_name}
                        <option value="{$available_group}"{if $only_group == $available_group} selected{/if}>{$available_name|default:$CONST.ALL_CATEGORIES}</option>
                    {/foreach}
                    </select>
                </div>

                <div id="plugin_filter" class="form_field">
                    <label for="pluginfilter">{$CONST.QUICKSEARCH}</label>
                    <input id="pluginfilter" type="text">
                    <button class="reset_livefilter icon_link" type="button" data-target="pluginfilter" title="{$CONST.RESET_FILTERS}"><span class="icon-cancel" aria-hidden="true"></span><span class="visuallyhidden">{$CONST.RESET_FILTERS}</span></button>
                </div>
                <div class="form_buttons">
                    <input type="submit" value="{$CONST.GO}">
                </div>
            {else}
                <a class="button_link" id="back" href="?serendipity[adminModule]=plugins">{$CONST.BACK}</a>
            {/if}
        </div>
    </form>
    {if $only_group == 'UPGRADE' AND $available_upgrades !== true}
        <span class="msg_notice"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.NO_UPDATES}</span>
    {else}
        {foreach $pluggroups AS $pluggroup => $groupstack}
            {if !empty($only_group) AND ($only_group AND $pluggroup != $only_group || empty($pluggroup))}{continue}{/if}
            <h3>{foreach $groupnames AS $available_group => $available_name}{if $pluggroup == $available_group}{$available_name}{/if}{/foreach}</h3>
            {if $only_group == 'UPGRADE' AND $pluggroups['UPGRADE']|count > 1}
                <button id="updateAll">{$CONST.UPDATE_ALL}</button>
            {/if}
            <ul class="plugins_installable plainList">
            {foreach $groupstack AS $plug}
                <li class="clearfix{if isset($plug.single_upgrade) AND $plug.single_upgrade} single_alert{/if}">
                    <div class="equal_heights">
                        <div class="plugin_features">
                            <h4>{$plug.name}</h4>
                        {if $plug@total > 1 AND isset($plug.single_upgrade) AND $plug.single_upgrade}{* for plugin UPGRADE page and CKEDITOR plugin only to only UPGRADE SINGULARLY! *}
                            <div class="single_hint"><span class="icon-attention-circled" aria-hidden="true"></span> <strong>UPGRADE</strong> singularly, <strong>NOT</strong> via "{$CONST.UPDATE_ALL}"!</div>
                        {/if}

                        {if $plug.description}{* for plugin UPGRADE page *}
                            <details class="plugin_data">
                                <summary><var class="perm_name">{$plug.class_name} <span class="icon-info-circled" aria-hidden="true"></span></var></summary>

                                <div class="plugin_desc clearfix">
                                {$plug.description}
                                </div>
                                {if !empty($plug.author)}
                                <div class="plugin_author"><b>{$CONST.AUTHOR}:</b> {$plug.author}</div>
                                {/if}
                            </details>
                        {else}
                            <div class="plugin_data">
                                <var class="perm_name">{$plug.class_name}</var>
                            </div>
                        {/if}
                        </div>

                        <ul class="plugin_info{if !empty($plug.upgrade_version) AND $plug.upgrade_version != $plug.version} plugup_from{/if} plainList">
                        {if !empty($plug.version)}
                            <li class="plugin_version"><b>{$CONST.VERSION}:</b> {$plug.version}</li>
                        {/if}
                        {if !empty($plug.website)}
                            <li class="plugin_web"><a href="{$plug.website|escape}"><span{if isset($plug.exdoc)} title="{$plug.exdoc|default:''}"{/if} class="icon-globe" aria-hidden="true"></span> {if isset($plug.exdoc)}{$plug.exdoc|default:''} {/if}{$CONST.PLUGIN_DOCUMENTATION}</a></li>
                        {/if}
                        {if !empty($plug.local_documentation)}
                            <li class="plugin_localdoc"><a href="{$plug.local_documentation|escape}">{$CONST.PLUGIN_DOCUMENTATION_LOCAL}</a></li>
                        {/if}
                        </ul>
                        <ul class="plugin_info{if !empty($plug.upgrade_version) AND $plug.upgrade_version != $plug.version} plugup_to{/if} plainList">
                        {if !empty($plug.upgrade_version) AND $plug.upgrade_version != $plug.version}
                            <li class="plugin_toversion">{$CONST.UPGRADE_TO_VERSION|sprintf:"{$plug.upgrade_version}"}{if !empty($plug.pluginlocation) AND $plug.pluginlocation != 'local'} ({$plug.pluginlocation|escape}){/if}</li>
                            {if !empty($plug.local_documentation)}{* we assume this is remotely still available and we want to stick to the language already chosen to show *}
                            <li class="plugin_web"><a href="{$plug.remote_path}{$plug.plugin_class}/{$plug.local_documentation_name}">{$CONST.PLUGIN_DOCUMENTATION}</a> <em>(raw)</em></li>
                            {/if}
                            {if !empty($plug.changelog)}
                            <li class="plugin_web"><a href="{$plug.changelog}">{$CONST.PLUGIN_DOCUMENTATION_CHANGELOG}</a></li>
                            {/if}
                        {/if}
                        </ul>
                    </div>

                    <div class="plugin_status{if (!$plug.installable AND !$plug['upgradeable']) OR (!$plug['upgradeable'] AND $plug.installable AND $plug.stackable)} installed{/if}">
                    {if isset($requirement_failures.{$plug.class_name})}
                        <span class="unmet_requirements msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.UNMET_REQUIREMENTS|sprintf:"{if (!empty($requirement_failures.{$plug.class_name}.styx))}Styx {$plug.requirements.serendipity},{/if}{if (!empty($requirement_failures.{$plug.class_name}.php))} PHP {$plug.requirements.php},{/if}{if (!empty($requirement_failures.{$plug.class_name}.smarty))} Smarty {$plug.requirements.smarty}{/if}"|replace:' ,':','|regex_replace:"/,$/":''}</span>
                    {elseif $plug['upgradeable']}
                        <a class="button_link state_update" href="?serendipity[adminModule]=plugins&amp;serendipity[pluginPath]={$plug.pluginPath}&amp;serendipity[install_plugin]={$plug.plugin_class}&amp;{$urltoken}{if isset($plug['customURI'])}{$plug.customURI}{/if}" title="{$CONST.PLUGIN_EVENT_SPARTACUS_CHECK_HINT}">{$CONST.UPGRADE}</a>
                    {elseif $plug.installable}
                        {if $plug.stackable}<span class="block_level stackable"><span class="icon-ok-circled" aria-hidden="true"></span>  {$CONST.ALREADY_INSTALLED} <span class="icon-plus" aria-hidden="true"></span> </span>{/if}
                        <a class="button_link" href="?serendipity[adminModule]=plugins&amp;serendipity[pluginPath]={$plug.pluginPath}&amp;serendipity[install_plugin]={$plug.plugin_class}&amp;{$urltoken}{if isset($plug.customURI)}{$plug.customURI}{/if}">{$CONST.INSTALL}</a>
                    {else}
                        <span class="block_level"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.ALREADY_INSTALLED}</span>
                    {/if}
                    </div>
                </li>
            {/foreach}
            </ul>
        {/foreach}
    {/if}
{elseif isset($adminAction) AND $adminAction == 'overlay'}
    <div id="progressWidget">
        <span id="updateMessage">{$CONST.START_UPDATE}</span>
        <div id="updateIndicator" class="animated-css"></div>
        <progress id="updateProgress" value="0"></progress>
    </div>
    <script src="{serendipity_getFile file='admin/js/progress-polyfill.min.js'}"></script>
{elseif isset($ajax_output)}
    {$ajax_output}
{else}
    {$backend_pluginlisting_header}
    <h2>{$CONST.CONFIGURE_PLUGINS}</h2>
    {if isset($save) AND $save}
    <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}: {$CONST.SETTINGS_SAVED_AT|sprintf:"$timestamp"}</span>
    {/if}
    {if isset($new_plugin_failed) AND $new_plugin_failed}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.ERROR}: {$CONST.PLUGIN_ALREADY_INSTALLED}</span>
    {/if}
    {if $updateAllMsg}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}: All Plugins updated</span>{* i18n *}
    {/if}
    <div id="pluginlist_tabs" class="tabs">
        <section id="pluginlist_sidebar" class="panel">
            <h3>{$CONST.SIDEBAR_PLUGINS}</h3>
            <a class="button_link" href="?serendipity[adminModule]=plugins&amp;serendipity[adminAction]=addnew" title='{$CONST.CLICK_HERE_TO_INSTALL_PLUGIN|sprintf:"{$CONST.SIDEBAR_PLUGIN}"}'>{$CONST.INSTALL_NEW_SIDEBAR_PLUGIN}</a>

            {$backend_plugins_sidebar_header}
            {$sidebar_plugins}
        </section>

        <section id="pluginlist_event" class="panel">
            <h3>{$CONST.EVENT_PLUGINS}</h3>
            <a class="button_link" href="?serendipity[adminModule]=plugins&amp;serendipity[adminAction]=addnew&amp;serendipity[type]=event" title='{$CONST.CLICK_HERE_TO_INSTALL_PLUGIN|sprintf:"{$CONST.EVENT_PLUGIN}"}'>{$CONST.INSTALL_NEW_EVENT_PLUGIN}</a>

            {$backend_plugins_event_header}
            {$event_plugins}
        </section>
    </div>
    {if isset($memsnaps) AND $memsnaps}
    <section>
        <h3>RAM</h3>

        <pre>{$memSnaps|print_r}</pre>
    </section>
    {/if}
{/if}
