<!DOCTYPE html>
<html class="no-js" dir="{$CONST.LANG_DIRECTION}" lang="{$lang}">
<head>
    <meta charset="{$CONST.LANG_CHARSET}">
{if !$admin_vars.backendBlogtitleFirst}
    <title>{if $admin_vars.title}{$admin_vars.title} | {/if}{if $admin_vars.is_logged_in}{$CONST.SERENDIPITY_ADMIN_SUITE} | {/if}{$blogTitle}</title>
{else}
    <title>{$blogTitle} | {if $admin_vars.title}{$admin_vars.title} | {/if}{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
{/if}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
    <script src="{serendipity_getFile file='admin/js/modernizr.min.js'}"></script>
{if $admin_vars.admin_installed}{serendipity_hookPlugin hook="backend_header" hookAll="true"}{/if}
    <script src="{serendipity_getFile file='admin/js/plugins.js'}"></script>
    <script src="{serendipity_getFile file='admin/serendipity_styx.js'}"></script>
    <script src="{$head_link_script}"></script>
</head>
<body id="serendipity_admin_page">
{if NOT $admin_vars.no_banner}
    <header id="top">
        <div id="banner{if NOT $admin_vars.is_logged_in}_install{/if}" class="clearfix">
        {if $admin_vars.is_logged_in}
            <a id="nav-toggle" class="button_link" href="#main_menu"><span class="icon-menu" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.NAVIGATION}</span></a>
        {/if}
        {if $admin_vars.admin_installed}
            <h1><a href="serendipity_admin.php"><span class="visuallyhidden">{$CONST.SERENDIPITY_ADMIN_SUITE}: </span>{$CONST.ADMIN}: <span class="chop-title">{$blogTitle}</span></a></h1>
        {else}
            <h1>{$CONST.SERENDIPITY_INSTALLATION}</h1>
        {/if}
        </div>
    </header>
{/if}

{if $admin_vars.is_logged_in}
    <div id="idx_waitingspin" class="pulsator busy_update" style="display: none"><div></div><div></div></div>
{/if}
    <main id="workspace" class="clearfix">
    {if NOT $admin_vars.is_logged_in}
        {$admin_vars.out|serendipity_refhookPlugin:'backend_login_page'}
            {if isset($admin_vars.out.header)}{$admin_vars.out.header|default:''}{/if}
        {if $admin_vars.post_action != '' AND NOT $admin_vars.is_logged_in}
            <span class="msg_error">{$CONST.WRONG_USERNAME_OR_PASSWORD}</span>
        {/if}
            <form id="login" class="clearfix" action="serendipity_admin.php" method="post">
                <input type="hidden" name="serendipity[action]" value="admin">
                <fieldset>
                    <span class="wrap_legend"><legend>{$CONST.PLEASE_ENTER_CREDENTIALS}</legend></span>

                    <div class="form_field">
                        <label for="login_uid">{$CONST.USERNAME}</label>
                        <input id="login_uid" name="serendipity[user]" autocomplete="name" type="text" autofocus>
                    </div>

                    <div class="form_field">
                        <label for="login_pwd">{$CONST.PASSWORD}</label>
                        <input id="login_pwd" name="serendipity[pass]" autocomplete="current-password" type="password">
                    </div>

                    <div class="form_check">
                        <input id="login_auto" name="serendipity[auto]" type="checkbox"><label for="login_auto">{$CONST.AUTOMATIC_LOGIN}</label>
                    </div>

                    <div class="form_buttons">
                        <input id="login_send" name="submit" type="submit" value="{$CONST.LOGIN}">
                        <a class="button_link" href="{$serendipityBaseURL}">{$CONST.BACK_TO_BLOG}</a>
                    </div>
                </fieldset>
                {if isset($admin_vars.out.table)}{$admin_vars.out.table|default:''}{/if}
            </form>
            {if isset($admin_vars.out.footer)}{$admin_vars.out.footer|default:''}{/if}
    {else}
        {if NOT $admin_vars.no_sidebar}

        <nav id="main_menu">
            <h2 class="visuallyhidden">{$CONST.MAIN_MENU}</h2>

            <ul class="clearfix">
                <li id="user_menu"><h3>{$admin_vars.self_info}</h3>
                    <ul class="clearfix">
                        <li><a class="button_link" href="serendipity_admin.php" title="{$CONST.MENU_DASHBOARD}"><span class="icon-home" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MENU_DASHBOARD}</span></a></li>
                    {if 'personalConfiguration'|checkPermission}

                        <li><a class="button_link" href="serendipity_admin.php?serendipity[adminModule]=personal" title="{$CONST.PERSONAL_SETTINGS}"><span class="icon-cog-alt" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.PERSONAL_SETTINGS}</span></a></li>
                    {/if}

                        <li><a class="button_link" href="{$serendipityBaseURL}" title="{$CONST.BACK_TO_BLOG}"><span class="icon-globe" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.BACK_TO_BLOG}</span></a></li>
                        <li><a class="button_link" href="serendipity_admin.php?serendipity[adminModule]=logout" title="{$CONST.LOGOUT}"><span class="icon-logout" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.LOGOUT}</span></a></li>
                    </ul>
                </li>
                {if 'adminEntries'|checkPermission OR 'adminEntriesPlugins'|checkPermission}

                <li><h3>{$CONST.CONTENT}</h3>
                    <ul>
                    {if 'adminEntries'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=entries&amp;serendipity[adminAction]=new">{$CONST.NEW_ENTRY}</a></li>
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=entries&amp;serendipity[adminAction]=editSelect&amp;serendipity[pinned_entries]={$pin_entries|default:''}">{$CONST.EDIT_ENTRIES}</a></li>
                    {/if}
                    {if 'adminCategories'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=category&amp;serendipity[adminAction]=view">{$CONST.CATEGORIES}</a></li>
                    {/if}
                    {if 'adminEntries'|checkPermission OR 'adminEntriesPlugins'|checkPermission}
                        {if $admin_vars.no_create !== true}
                        {serendipity_hookPlugin hook="backend_sidebar_entries" hookAll="true"}
                        {/if}
                    {/if}
                    </ul>
                </li>
                {/if}
                {if 'adminImages'|checkPermission}

                <li><h3>{$CONST.MEDIA}</h3>
                    <ul>
                    {if 'adminImagesAdd'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=media&amp;serendipity[adminAction]=addSelect">{$CONST.ADD_MEDIA}</a></li>
                    {/if}
                    {if 'adminImagesView'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=media">{$CONST.MEDIA_LIBRARY}</a></li>
                    {/if}
                    {if 'adminImagesDirectories'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=media&amp;serendipity[adminAction]=directorySelect">{$CONST.MANAGE_DIRECTORIES}</a></li>
                    {/if}
                    {if $admin_vars.no_create !== true}
                        {serendipity_hookPlugin hook="backend_sidebar_entries_images" hookAll="true"}
                    {/if}
                    </ul>
                </li>
                {/if}

                <li><h3>{$CONST.MENU_ACTIVITY}</h3>
                    <ul>
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=comments">{$CONST.COMMENTS}</a></li>
                    {if 'adminPlugins'|checkPermission AND $admin_vars.no_create !== true}
                        {serendipity_hookPlugin hook="backend_sidebar_admin_appearance" hookAll="true"}
                    {/if}
                    {if 'siteConfiguration'|checkPermission OR 'siteAutoUpgrades'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=maintenance">{$CONST.MENU_MAINTENANCE}</a></li>
                    {/if}

                    </ul>
                </li>
                {if 'adminImport'|checkPermission OR 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission OR 'adminTemplates'|checkPermission OR 'adminPlugins'|checkPermission}

                <li><h3>{$CONST.MENU_SETTINGS}</h3>
                    <ul>
                    {if 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=configuration">{$CONST.CONFIGURATION}</a></li>
                    {/if}
                    {if 'adminTemplates'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=templates">{$CONST.MENU_TEMPLATES}</a></li>
                    {/if}
                    {if 'adminPlugins'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=plugins">{$CONST.MENU_PLUGINS}</a></li>
                    {/if}
                    {if $admin_vars.no_create !== true}

                        {serendipity_hookPlugin hook="backend_sidebar_admin" hookAll="true"}
                    {/if}

                    </ul>
                </li>
                {/if}

                <li><h3>{$CONST.MANAGE_USERS}</h3>
                    <ul>
                {if 'adminUsersGroups'|checkPermission OR 'adminUsers'|checkPermission}

                    {if 'adminUsers'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=users">{$CONST.MENU_USERS}</a></li>
                    {/if}
                    {if 'adminUsersGroups'|checkPermission}

                        <li><a href="serendipity_admin.php?serendipity[adminModule]=groups">{$CONST.MENU_GROUPS}</a></li>
                    {/if}
                    {if $admin_vars.no_create !== true}

                        {serendipity_hookPlugin hook="backend_sidebar_users" hookAll="true"}

                    {/if}
                {else}
                    {if $admin_vars.no_create !== true}

                        {serendipity_hookPlugin hook="backend_sidebar_users" hookAll="true"}

                    {/if}

                    {if $admin_vars.right_publish !== true AND $admin_vars.no_create !== true}

                    <li>
                        <span class="msg_hint">{$CONST.USER_ALERT} "<b>{$CONST.PERMISSIONS|upper}</b>"<br>&laquo;&nbsp;<em>{$CONST.ENTRY_STATUS}: {$CONST.DRAFT}</em>&nbsp;&raquo;</span>
                        <span class="msg_notice hyphenate"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.USER_PERMISSION_NOTIFIER_DRAFT_MODE}</span>
                        <span class="msg_notice hyphenate"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.USER_PERMISSION_NOTIFIER_RESET}</span>
                    </li>
                    {/if}
                    {if $admin_vars.no_create === true}

                    <li>
                        <span class="msg_hint">{$CONST.USER_ALERT} "<b>{$CONST.PERMISSIONS|upper}</b>"<br>&laquo;&nbsp;<em>{$CONST.PERMISSIONS}: {$CONST.NONE}</em>&nbsp;&raquo;</span>
                        <span class="msg_notice hyphenate"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.USER_PERMISSION_NOTIFIER_WRITE_MODE}</span>
                        <span class="msg_notice hyphenate"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.USER_PERMISSION_NOTIFIER_RESET}</span>
                    </li>
                    {/if}
                    {if $admin_vars.no_create !== true}

                        {serendipity_hookPlugin hook="backend_sidebar_useralert" hookAll="true"}

                    {/if}
                {/if}

                    </ul>
                </li>

            </ul>
        </nav>
        {/if}

        <div id="content" class="clearfix">

{$admin_vars.main_content}

        </div>
    {/if}

    </main>
{if NOT $admin_vars.no_footer}

    <footer id="meta">
        <p>{$admin_vars.version_info}</p>
    </footer>
{/if}
{if $admin_vars.admin_installed}{serendipity_hookPlugin hook="backend_footer" hookAll="true"}{/if}

</body>
</html>
