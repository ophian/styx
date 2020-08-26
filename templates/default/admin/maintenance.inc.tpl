<h2>{$CONST.MENU_MAINTENANCE}</h2>

{if isset($action) AND $action == "integrity" AND isset($badsums)}
    <h3 class="visuallyhidden">{$CONST.INTEGRITY}</h3>
    {if $badsums|count > 0}
    {assign "cfiles" $badsums|count}
    <ul class="plainList">
        {foreach $badsums AS $rpath => $calcsum}
        <li class="msg_error_list"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CHECKSUM_FAILED|sprintf:$rpath}</li>
        {/foreach}
    </ul>
    {/if}
{/if}

<div id="maintenance" class="maintenance_container">

{if 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission}
    <section id="maintenance_integrity" class="quick_list">
        <h3>{$CONST.INTEGRITY}</h3>

{if isset($action) AND $action == "integrity"}
    {if isset($noChecksum) AND $noChecksum == true}
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.CHECKSUMS_NOT_FOUND}</span>
    {/if}
    {if isset($badsums) AND $badsums|count == 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.CHECKSUMS_PASS}</span>
    {/if}
    {if isset($cfiles) AND $cfiles > 0}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CHECKSUM_FAILED|sprintf:$cfiles}</span>
    {/if}
{else}
        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=integrity" title="{$CONST.INTEGRITY}"><span>{$CONST.INTEGRITY}</span></a>
{/if}
    </section>
{/if}

{if 'adminImport'|checkPermission}
    <section id="maintenance_import" class="quick_list">
        <h3>{$CONST.IMPORT_ENTRIES}</h3>

        {$importMenu}
    </section>
{/if}

{if 'adminImagesSync'|checkPermission}
    <section id="maintenance_thumbs" class="quick_list">
        <h3>{$CONST.MEDIA_LIBRARY}: {$CONST.CREATE_THUMBS}</h3>

        <form method="POST" action="serendipity_admin.php?serendipity[adminModule]=media&amp;serendipity[adminAction]=doSync">
            <fieldset>
                <span class="wrap_legend"><legend>{$CONST.SYNC_OPTION_LEGEND}</legend></span>
                <button class="toggle_info button_link" type="button" data-href="#isync_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>

                <div class="clearfix">
                    <div class="form_radio">
                        <input id="keepthumbs" name="serendipity[deleteThumbs]" type="radio" value="no" checked="checked">
                        <label for="keepthumbs">{$CONST.SYNC_OPTION_KEEPTHUMBS}</label>
                    </div>

                    <div class="form_radio">
                        <input id="sizecheckthumbs" name="serendipity[deleteThumbs]" type="radio" value="check">
                        <label for="sizecheckthumbs">{$CONST.SYNC_OPTION_SIZECHECKTHUMBS}</label>
                    </div>

                    <div class="form_radio">
                        <input id="deletethumbs" name="serendipity[deleteThumbs]" type="radio" value="yes">
                        <label for="deletethumbs">{$CONST.SYNC_OPTION_DELETETHUMBS|sprintf:$thumbsuffix}</label>
                    </div>

                    {if $suffixTask}
                    <div class="form_radio">
                        <input id="convertthumbs" name="serendipity[deleteThumbs]" type="radio" value="convert"{if !$suffixTask} disabled="disabled"{/if}>
                        <label for="convertthumbs">{$CONST.SYNC_OPTION_CONVERTTHUMBS}</label>
                        <button class="toggle_info button_link" type="button" data-href="#iconvert_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
                    </div>
                    {/if}

                    {if $variationTask}
                    <div class="form_radio">
                        <input id="makeVariations" name="serendipity[deleteThumbs]" type="radio" value="build"{if !$variationTask} disabled="disabled"{/if}>
                        <label for="makeVariations">{$CONST.SYNC_OPTION_BUILDVARIATIONS}</label>
                    </div>
                    {else}
                    <div class="form_radio">
                        <input id="makeVariations" name="serendipity[deleteThumbs]" type="radio" value="cleanup">
                        <label for="makeVariations">{$CONST.SYNC_OPTION_PURGEVARIATIONS}</label>
                    </div>
                    {/if}

                    <div id="iconvert_info" class="comment_status additional_info">
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.SYNC_OPTION_CONVERTTHUMBS_INFO|sprintf:$thumbsuffix}
                    {if $dbnotmysql AND NOT empty($CONST.MEDIA_THUMBURL_REPLACE_ENTRY)}<br><br>{* remove this part and constant, when non-mysql database LIKE replacements found being proofed guilty *}
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.MEDIA_THUMBURL_REPLACE_ENTRY}
                    {else}<br><br>
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.PLUGIN_MODEMAINTAIN_HINT_MAINTENANCE_MODE}
                    {/if}
                    </div>

                    <div id="isync_info" class="comment_status additional_info">
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.IMAGESYNC_WARNING}
                    </div>
                </div>
            </fieldset>

            <div class="form_buttons">
                <input name="doSync" type="submit" value="{$CONST.CREATE_THUMBS}">
            </div>
        </form>
    </section>
{/if}

{if 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission}
    <section id="maintenance_pluginmanager" class="quick_list">
        <h3>{$CONST.PLUGINMANAGER}</h3>
    {if NOT empty($pluginmanager_error)}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$pluginmanager_error}</span>
    {else if $zombP}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.PLUGINMANAGER_ZOMB_OK}</span>
    {else if isset($select_localplugins_total) AND $select_localplugins_total == 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> <em>{$CONST.NOTHING_TODO}</em></span>
    {else if NOT isset($local_plugins) OR !is_array($local_plugins)}
        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=checkplug" title="{$CONST.PLUGINMANAGER_LOCALPLUGINS|lower}"><span>{$CONST.PLUGINMANAGER_LOCALPLUGINS}</span></a>
    {else}

        <form id="maintenance_clearplug_multi" enctype="multipart/form-data"  method="POST" action="serendipity_admin.php">
            <input type="hidden" name="serendipity[adminModule]" value="maintenance">
            <input type="hidden" name="serendipity[adminAction]" value="clearplug">
            {$formtoken}

            <div class="form_select">
                <select id="clearplug_access_multi_plugins" class="" name="serendipity[clearplug][multi_plugins][]" multiple="multiple" size="{$select_localplugins_total}">
                {foreach $local_plugins AS $plugins}
                    <option value="{$plugins@key}">{$plugins}</option>
                {/foreach}
                </select>
            </div>

            <div class="form_buttons">
                <input class="state_submit" name="clearplug_multi" value="{$CONST.PLUGINMANAGER_SUBMIT}" type="submit">
                <button class="toggle_info button_link" type="button" data-href="#zomplug_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
                <span id="zomplug_info" class="comment_status additional_info">{$CONST.PLUGINMANAGER_INFO}</span>
            </div>
        </form>

    {/if}
    </section>
{/if}

{if 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission}
    <section id="maintenance_thememanager" class="quick_list">
        <h3>{$CONST.THEMEMANAGER}</h3>
    {if NOT empty($thememanager_error)}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$thememanager_error}</span>
    {else if $zombT}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.THEMEMANAGER_ZOMB_OK}</span>
    {else if isset($select_localthemes_total) AND $select_localthemes_total == 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> <em>{$CONST.NOTHING_TODO}</em></span>
    {else if NOT isset($local_themes) OR !is_array($local_themes)}
        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=checktemp" title="{$CONST.THEMEMANAGER_LOCALTHEMES|lower}"><span>{$CONST.THEMEMANAGER_LOCALTHEMES}</span></a>
    {else}

        <form id="maintenance_cleartemp_multi" enctype="multipart/form-data"  method="POST" action="serendipity_admin.php">
            <input type="hidden" name="serendipity[adminModule]" value="maintenance">
            <input type="hidden" name="serendipity[adminAction]" value="cleartemp">
            {$formtoken}

            <div class="form_select">
                <select id="cleartemp_access_multi_themes" class="" name="serendipity[cleartemp][multi_themes][]" multiple="multiple" size="{$select_localthemes_total}">
                {foreach $local_themes AS $themes}
                    <option value="{$themes}">{$themes}</option>
                {/foreach}
                </select>
            </div>

            <div class="form_buttons">
                <input class="state_submit" name="cleartemp_multi" value="{$CONST.THEMEMANAGER_SUBMIT}" type="submit">
                <button class="toggle_info button_link" type="button" data-href="#thema_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
                <span id="thema_info" class="comment_status additional_info">{$CONST.THEMEMANAGER_INFO}</span>
            </div>
        </form>

    {/if}
    </section>
{/if}

{if 'adminTemplates'|checkPermission}
    <section id="maintenance_cleanup" class="quick_list breakme">
        <h3>{$CONST.CLEANCOMPILE_TITLE}</h3>

{if isset($cleanup_finish)}
    {if $cleanup_finish > 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}! <span class="perm_name">{$CONST.CLEANCOMPILE_PASS|sprintf:$cleanup_template}</span></span>
    {/if}
    {if $cleanup_finish === 0}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CLEANCOMPILE_FAIL}</span>
    {/if}
{else}
        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=clearcomp" title="{$CONST.CLEANCOMPILE_TITLE}"><span>{$CONST.CLEANCOMPILE_TITLE}</span></a>
        <button class="toggle_info button_link" type="button" data-href="#cleanup_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
        <span id="cleanup_info" class="comment_status additional_info">{$CONST.CLEANCOMPILE_INFO}</span>
{/if}
    </section>
{/if}

{if 'adminImport'|checkPermission}
    <section id="maintenance_export" class="quick_list">
        <h3>{$CONST.EXPORT_ENTRIES}</h3>

        <a class="button_link" href="{$serendipityBaseURL}rss.php?version=2.0&amp;all=1"><span class="icon-rss" aria-hidden="true"></span> {$CONST.EXPORT_FEED}</a>
    </section>
{/if}

{if 'siteConfiguration'|checkPermission AND !$dbnotmysql}
    <section id="maintenance_utf8mb4" class="quick_list{if NOT $dbUtf8mb4_converted AND $dbUtf8mb4_migrate AND $dbUtf8mb4_ready AND NOT empty($dbUtf8mb4_migrate.sql)} mtask_long{/if}">
        <h3>{$CONST.UTF8MB4_MIGRATION_TITLE}</h3>

        {if isset($dbUtf8mb4_error) AND $dbUtf8mb4_error}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.UTF8MB4_MIGRATION_ERROR|sprintf:$dbUtf8mb4_error}</span></span>
        {/if}

        {if isset($dbUtf8mb4_migrate) AND $dbUtf8mb4_migrate}
            <p>{$CONST.UTF8MB4_MIGRATION_TASK_RETURN}</p>
            <ul>
            {foreach $dbUtf8mb4_migrate.errors AS $error}
                <li><span class="msg_error_list">{$error}</span></li>
            {/foreach}
            </ul>

            <ul>
            {foreach $dbUtf8mb4_migrate.warnings AS $warning}
                <li>{$warning}</li>
            {/foreach}
            </ul>

            {if $dbUtf8mb4_executed}
            <p>{$CONST.UTF8MB4_MIGRATION_TASK_HAVE}</p>
            {else}
            <p>{$CONST.UTF8MB4_MIGRATION_TASK_CAN}</p>
            {/if}

            <ul>
            {foreach $dbUtf8mb4_migrate.sql AS $query}
                <li>{$query};</li>
            {/foreach}
            </ul>

        {/if}

    {if $dbUtf8mb4_converted === true AND $dbUtf8mb4_ready === true}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true" title="{$CONST.UTF8MB4_MIGRATION_TASK_DONE}"></span> <span title="{$CONST.UTF8MB4_MIGRATION_TASK_DONE}"> {$CONST.UTF8MB4_MIGRATION_TASK_DONE_SHORT}</span></span>
    {else}
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.UTF8MB4_MIGRATION_INFO}</span>
        {if $dbUtf8mb4_ready}
        <form method="POST" action="serendipity_admin.php">
            <input type="hidden" name="serendipity[adminModule]" value="maintenance">
            <input type="hidden" name="serendipity[adminAction]" value="utf8mb4">
            {$formtoken}

            <div class="form_buttons">
                <input name="serendipity[adminOption][check]" type="submit" value="{$CONST.UTF8MB4_MIGRATION_BUTTON_CHECK}">
            {if $dbUtf8mb4_simulated}
                <input name="serendipity[adminOption][execute]" type="submit" value="{$CONST.UTF8MB4_MIGRATION_BUTTON_EXECUTE}">
            {/if}
                <button class="toggle_info button_link" type="button" data-href="#utf8migrate_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
            </div>
        </form>
        <div id="utf8migrate_info" class="comment_status additional_info">
        {$CONST.UTF8MB4_MIGRATION_INFO_DESC}
        </div>
        {else}
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.UTF8MB4_MIGRATION_FAIL}</span>
        {/if}
    {/if}
    </section>
{/if}

{serendipity_hookPlugin hook="backend_maintenance" hookAll="true"}

</div>
