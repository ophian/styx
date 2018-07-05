<div id="maintenance">
    <h2>{$CONST.MENU_MAINTENANCE}</h2>

{if isset($action) AND $action == "integrity"}
    <h3 class="visuallyhidden">{$CONST.INTEGRITY}</h3>
    {if isset($noChecksum) AND $noChecksum == true}
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.CHECKSUMS_NOT_FOUND}</span>
    {else}
        {if $badsums|count == 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.CHECKSUMS_PASS}</span>
        {else}
        <ul class="plainList">
            {foreach $badsums AS $rpath => $calcsum}
            <li class="msg_error_list"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CHECKSUM_FAILED|sprintf:$rpath}</li>
            {/foreach}
        </ul>
        {/if}
    {/if}
{/if}

{if isset($cleanup_finish) AND $cleanup_finish > 0}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}! <span class="perm_name">{$CONST.CLEANCOMPILE_PASS|sprintf:$cleanup_template}</span></span>
{/if}
{if isset($cleanup_finish) AND $cleanup_finish === 0}
        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CLEANCOMPILE_FAIL}</span>
{/if}

{if 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission}
    <section id="maintenance_integrity" class="quick_list">
        <h3>{$CONST.INTEGRITY}</h3>

        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=integrity" title="{$CONST.INTEGRITY}"><span>{$CONST.INTEGRITY}</span></a>
    </section>
{/if}

{if 'adminTemplates'|checkPermission}
    <section id="maintenance_cleanup" class="quick_list">
        <h3>{$CONST.CLEANCOMPILE_TITLE}</h3>

        <a class="button_link" href="?serendipity[action]=admin&amp;serendipity[adminModule]=maintenance&amp;serendipity[adminAction]=runcleanup" title="{$CONST.CLEANCOMPILE_TITLE}"><span>{$CONST.CLEANCOMPILE_TITLE}</span></a>
        <button class="toggle_info button_link" type="button" data-href="#cleanup_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
        <span id="cleanup_info" class="comment_status additional_info">{$CONST.CLEANCOMPILE_INFO}</span>
    </section>
{/if}

{if 'adminImport'|checkPermission}
    <section id="maintenance_export" class="quick_list">
        <h3>{$CONST.EXPORT_ENTRIES}</h3>

        <a class="button_link" href="{$serendipityBaseURL}rss.php?version=2.0&amp;all=1"><span class="icon-rss" aria-hidden="true"></span> {$CONST.EXPORT_FEED}</a>
    </section>

    <section id="maintenance_import" class="quick_list">
        <h3>{$CONST.IMPORT_ENTRIES}</h3>

        {$importMenu}
    </section>
{/if}

{if 'adminImagesSync'|checkPermission}
    <section id="maintenance_thumbs" class="quick_list">
        <h3>{$CONST.CREATE_THUMBS}</h3>

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

                    <div class="form_radio">
                        <input id="convertthumbs" name="serendipity[deleteThumbs]" type="radio" value="convert"{if !$suffixTask} disabled="disabled"{/if}>
                        <label for="convertthumbs">{$CONST.SYNC_OPTION_CONVERTTHUMBS}</label>
                        <button class="toggle_info button_link" type="button" data-href="#iconvert_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
                    </div>

                    <div id="iconvert_info" class="comment_status additional_info">
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.SYNC_OPTION_CONVERTTHUMBS_INFO|sprintf:$thumbsuffix}
                    {if $dbnotmysql}<br><br>
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.MEDIA_THUMBURL_REPLACE_ENTRY}
                    {else}<br><br>
                        <span class="icon-info-circled" aria-hidden="true"></span> {$CONST.PLUGIN_MODEMAINTAIN_HINT_MAINTENANCE_MODE|default:'Recommended: Install modemaintain event plugin!'}
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

{serendipity_hookPlugin hook="backend_maintenance" hookAll="true"}

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

    {if $dbUtf8mb4_converted}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.UTF8MB4_MIGRATION_TASK_DONE}</span>
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

</div>
