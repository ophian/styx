<!DOCTYPE html>
<html{if $admin_vars.darkmode} data-color-mode="dark"{else} data-color-mode="light"{/if}{if !empty($smarty.get.serendipity.popupContent)} class="is-modal-view"{/if} dir="ltr" lang="{$lang}">
<head>
    <meta charset="{$CONST.LANG_CHARSET}">
{if !$admin_vars.backendBlogtitleFirst}
    <title>{if $admin_vars.title}{$admin_vars.title} | {/if}{if $admin_vars.is_logged_in}{$CONST.SERENDIPITY_ADMIN_SUITE} | {/if}{$blogTitle}</title>
{else}
    <title>{$blogTitle} | {if $admin_vars.title}{$admin_vars.title} | {/if}{if $admin_vars.is_logged_in}{$CONST.SERENDIPITY_ADMIN_SUITE}{/if}</title>
{/if}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
{if $admin_vars.darkmode}
    <link rel="stylesheet" href="{serendipity_getFile file='admin/styx_dark.min.css'}" type="text/css">
{else}
{if NOT isset($forceLightMode)}
    <script>
      if ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) || localStorage.getItem('data-login-color-mode') === 'dark') {
        document.currentScript.insertAdjacentHTML('beforebegin', '<link rel="stylesheet" href="{serendipity_getFile file='admin/styx_dark.min.css'}" type="text/css">');
      }
    </script>
{/if}
{/if}
{if $admin_vars.is_logged_in}
{if $admin_vars.admin_installed}{serendipity_hookPlugin hook="backend_header" hookAll="true"}{/if}
    <script>
{if NOT isset($forceLightMode)}
    {if $admin_vars.darkmode}
        window.STYX_DARKMODE = true;
        if (localStorage.getItem('data-login-color-mode') === null) {
            localStorage.setItem('data-login-color-mode', 'dark');
        }
    {else}
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-color-mode', 'dark');
            window.STYX_DARKMODE = true;
        } else {
            window.STYX_DARKMODE = false;
        }

        const dark_mode = localStorage.getItem('data-login-color-mode');
        if (window.STYX_DARKMODE === true && dark_mode === null) {
            localStorage.setItem('data-login-color-mode', 'dark');
        }
    {/if}
{else}
        window.STYX_DARKMODE = false;
{/if}

        /* Set HTML5 Theme Attribute for TinyMCE & CSS */
        document.documentElement.dataset.theme = window.STYX_DARKMODE ? 'dark' : 'light';

        /* Dynamic Favicon handling */
        const iconName = window.STYX_DARKMODE ? 'sty.xd.png' : 'sty.x.png';
        const iconId = window.STYX_DARKMODE ? 'dark-scheme-icon' : 'light-scheme-icon';
        const iconUrl = '{$serendipityBaseURL}{$templatePath}styx/' + iconName;

        document.head.insertAdjacentHTML('beforeend', `<link id="${ iconId }" rel="shortcut icon" href="${ iconUrl }" type="image/x-icon">`);
    </script>
{/if}
</head>
<body id="serendipity_admin_page">
{if NOT $admin_vars.no_banner && empty($is_embedded)}

    <header id="top">
        <div id="banner{if NOT $admin_vars.is_logged_in}_install{/if}" class="clearfix">
{if $admin_vars.is_logged_in}
            <a id="nav-toggle" class="button_link" href="#main_menu" aria-label="{$CONST.NAVIGATION}"><span class="icon-menu" aria-hidden="true"></span></a>
{/if}
{if $admin_vars.admin_installed}
            <h1><a href="serendipity_admin.php" aria-label="{$CONST.SERENDIPITY_ADMIN_SUITE|replace:' Styx':''}">{$CONST.ADMIN}: <span class="chop-title">{$blogTitle}</span></a></h1>
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

        <script>
{if NOT isset($forceLightMode)}
          if ((window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) || localStorage.getItem('data-login-color-mode') === 'dark') {
            document.documentElement.setAttribute('data-login-color-mode', 'dark');
            document.head.insertAdjacentHTML('beforeend', '<link id="dark-scheme-icon" rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}styx/sty.xd.png" type="image/x-icon">')
          } else {
            document.head.insertAdjacentHTML('beforeend', '<link id="light-scheme-icon" rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}styx/sty.x.png" type="image/x-icon">')
          }
{else}
          document.head.insertAdjacentHTML('beforeend', '<link id="light-scheme-icon" rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}styx/sty.x.png" type="image/x-icon">')
{/if}
        </script>
{else}
{if NOT $admin_vars.no_sidebar}

        <nav id="main_menu">
            <h2 class="sr-only">{$CONST.MAIN_MENU}</h2>

            <ul class="clearfix">
                <li id="user_menu"><h3>{$admin_vars.self_info}</h3>
                    <ul class="clearfix">
                        <li><a class="button_link{$admin_vars.permlevel}" href="serendipity_admin.php" title="{$CONST.MENU_DASHBOARD}" aria-label="{$CONST.MENU_DASHBOARD}"><span class="icon-home" aria-hidden="true"></span></a></li>
{if 'personalConfiguration'|checkPermission}
                        <li><a class="button_link{$admin_vars.permlevel}" href="serendipity_admin.php?serendipity[adminModule]=personal" title="{$CONST.PERSONAL_SETTINGS}" aria-label="{$CONST.PERSONAL_SETTINGS}"><span class="icon-cog-alt" aria-hidden="true"></span></a></li>
{/if}
                        <li><a class="button_link" href="{$serendipityBaseURL}" title="{$CONST.BACK_TO_BLOG}" aria-label="{$CONST.BACK_TO_BLOG}"><span class="icon-globe" aria-hidden="true"></span></a></li>
                        <li><a class="button_link" href="serendipity_admin.php?serendipity[adminModule]=logout" title="{$CONST.LOGOUT}" aria-label="{$CONST.LOGOUT}"><span class="icon-logout" aria-hidden="true"></span></a></li>
                    </ul>
                </li>
{if 'adminEntries'|checkPermission OR 'adminEntriesPlugins'|checkPermission}

                <li>
                    <h3>{$CONST.CONTENT}</h3>
                    <ul id="entry_hooks">
{if 'adminEntries'|checkPermission}
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=entries&amp;serendipity[adminAction]=new">{$CONST.NEW_ENTRY}</a></li>
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=entries&amp;serendipity[adminAction]=editSelect&amp;serendipity[pinned_entries]={$pin_entries|default:''}">{$CONST.EDIT_ENTRIES}</a></li>
{/if}
{if 'adminCategories'|checkPermission}
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=category&amp;serendipity[adminAction]=view">{$CONST.CATEGORIES}</a></li>
{/if}
{if 'adminEntries'|checkPermission OR 'adminEntriesPlugins'|checkPermission}{if $admin_vars.no_create !== true}
                        <li class="expandable-group">
                            <div class="flex-inside ex">
                                <button type="button" class="toggle_info button_link btn-expander" aria-expanded="false">
                                  <span class="open">
                                    <svg aria-label="Expand" role="img" height="16" viewBox="0 0 16 16" fill="#768390" version="1.1" width="16" data-view-component="true" class="expandable expandable-unfold"><title>{$CONST.OPEN}</title>
                                      <path d="m8.177.677 2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25a.75.75 0 0 1-1.5 0V4H5.104a.25.25 0 0 1-.177-.427L7.823.677a.25.25 0 0 1 .354 0ZM7.25 10.75a.75.75 0 0 1 1.5 0V12h2.146a.25.25 0 0 1 .177.427l-2.896 2.896a.25.25 0 0 1-.354 0l-2.896-2.896A.25.25 0 0 1 5.104 12H7.25v-1.25Zm-5-2a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                  <span class="hide">
                                    <svg aria-label="Collapse" role="img" height="16" viewBox="0 0 16 16" version="1.1" width="16" fill="#a0a0a0" data-view-component="true" class="expandable expandable-fold"><title>{$CONST.CLOSE}</title>
                                      <path d="M10.896 2H8.75V.75a.75.75 0 0 0-1.5 0V2H5.104a.25.25 0 0 0-.177.427l2.896 2.896a.25.25 0 0 0 .354 0l2.896-2.896A.25.25 0 0 0 10.896 2ZM8.75 15.25a.75.75 0 0 1-1.5 0V14H5.104a.25.25 0 0 1-.177-.427l2.896-2.896a.25.25 0 0 1 .354 0l2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25Zm-6.5-6.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                </button>
                            </div>
                        </li>
{serendipity_hookPlugin hook="backend_sidebar_entries" hookAll="true"}
{/if}{/if}
                    </ul>
                </li>
{/if}
{if 'adminImages'|checkPermission}

                <li>
                    <h3>{$CONST.MEDIA}</h3>
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
{if $admin_vars.no_create !== true}{serendipity_hookPlugin hook="backend_sidebar_entries_images" hookAll="true"}{/if}
                    </ul>
                </li>
{/if}

                <li>
                    <h3>{$CONST.MENU_ACTIVITY}</h3>
                    <ul id="activity_hooks">
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=comments">{$CONST.COMMENTS}</a></li>
{if 'adminPlugins'|checkPermission AND $admin_vars.no_create !== true}
                        <li class="expandable-group">
                            <div class="flex-inside ex">
                                <button type="button" class="toggle_info button_link btn-expander" aria-expanded="false">
                                  <span class="open">
                                    <svg aria-label="Expand" role="img" height="16" viewBox="0 0 16 16" fill="#768390" version="1.1" width="16" data-view-component="true" class="expandable expandable-unfold"><title>{$CONST.OPEN}</title>
                                      <path d="m8.177.677 2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25a.75.75 0 0 1-1.5 0V4H5.104a.25.25 0 0 1-.177-.427L7.823.677a.25.25 0 0 1 .354 0ZM7.25 10.75a.75.75 0 0 1 1.5 0V12h2.146a.25.25 0 0 1 .177.427l-2.896 2.896a.25.25 0 0 1-.354 0l-2.896-2.896A.25.25 0 0 1 5.104 12H7.25v-1.25Zm-5-2a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                  <span class="hide">
                                    <svg aria-label="Collapse" role="img" height="16" viewBox="0 0 16 16" version="1.1" width="16" fill="#a0a0a0" data-view-component="true" class="expandable expandable-fold"><title>{$CONST.CLOSE}</title>
                                      <path d="M10.896 2H8.75V.75a.75.75 0 0 0-1.5 0V2H5.104a.25.25 0 0 0-.177.427l2.896 2.896a.25.25 0 0 0 .354 0l2.896-2.896A.25.25 0 0 0 10.896 2ZM8.75 15.25a.75.75 0 0 1-1.5 0V14H5.104a.25.25 0 0 1-.177-.427l2.896-2.896a.25.25 0 0 1 .354 0l2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25Zm-6.5-6.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                </button>
                            </div>
                        </li>
{serendipity_hookPlugin hook="backend_sidebar_admin_appearance" hookAll="true"}{/if}
                    </ul>
                </li>
{if 'adminImport'|checkPermission OR 'siteConfiguration'|checkPermission OR 'blogConfiguration'|checkPermission OR 'adminTemplates'|checkPermission OR 'adminPlugins'|checkPermission}

                <li>
                    <h3>{$CONST.MENU_SETTINGS}</h3>
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
{if 'siteConfiguration'|checkPermission OR 'siteAutoUpgrades'|checkPermission}
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=maintenance">{$CONST.MENU_MAINTENANCE}</a></li>
{/if}
{if $admin_vars.no_create !== true}{serendipity_hookPlugin hook="backend_sidebar_admin" hookAll="true"}{/if}
                    </ul>
                </li>
{/if}

                <li>
                    <h3>{$CONST.MANAGE_USERS}</h3>
                    <ul id="user_hooks"{if $admin_vars.permlevel === 'editor'} data-editor-check="true"{/if}>
{if 'adminUsersGroups'|checkPermission OR 'adminUsers'|checkPermission}
{if 'adminUsers'|checkPermission}
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=users">{$CONST.MENU_USERS}</a></li>
{/if}
{if 'adminUsersGroups'|checkPermission}
                        <li><a href="serendipity_admin.php?serendipity[adminModule]=groups">{$CONST.MENU_GROUPS}</a></li>
{/if}
{if $admin_vars.no_create !== true}{serendipity_hookPlugin hook="backend_sidebar_users" hookAll="true"}{/if}
{else}
{if $admin_vars.no_create !== true}{serendipity_hookPlugin hook="backend_sidebar_users" hookAll="true"}{/if}
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
                        <li class="expandable-group">
                            <div class="flex-inside ex">
                                <button type="button" class="toggle_info button_link btn-expander" aria-expanded="false">
                                  <span class="open">
                                    <svg aria-label="Expand" role="img" height="16" viewBox="0 0 16 16" fill="#768390" version="1.1" width="16" data-view-component="true" class="expandable expandable-unfold"><title>{$CONST.OPEN}</title>
                                      <path d="m8.177.677 2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25a.75.75 0 0 1-1.5 0V4H5.104a.25.25 0 0 1-.177-.427L7.823.677a.25.25 0 0 1 .354 0ZM7.25 10.75a.75.75 0 0 1 1.5 0V12h2.146a.25.25 0 0 1 .177.427l-2.896 2.896a.25.25 0 0 1-.354 0l-2.896-2.896A.25.25 0 0 1 5.104 12H7.25v-1.25Zm-5-2a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                  <span class="hide">
                                    <svg aria-label="Collapse" role="img" height="16" viewBox="0 0 16 16" version="1.1" width="16" fill="#a0a0a0" data-view-component="true" class="expandable expandable-fold"><title>{$CONST.CLOSE}</title>
                                      <path d="M10.896 2H8.75V.75a.75.75 0 0 0-1.5 0V2H5.104a.25.25 0 0 0-.177.427l2.896 2.896a.25.25 0 0 0 .354 0l2.896-2.896A.25.25 0 0 0 10.896 2ZM8.75 15.25a.75.75 0 0 1-1.5 0V14H5.104a.25.25 0 0 1-.177-.427l2.896-2.896a.25.25 0 0 1 .354 0l2.896 2.896a.25.25 0 0 1-.177.427H8.75v1.25Zm-6.5-6.5a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM6 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 6 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5ZM12 8a.75.75 0 0 1-.75.75h-.5a.75.75 0 0 1 0-1.5h.5A.75.75 0 0 1 12 8Zm2.25.75a.75.75 0 0 0 0-1.5h-.5a.75.75 0 0 0 0 1.5h.5Z"></path>
                                    </svg>
                                  </span>
                                </button>
                            </div>
                        </li>
{serendipity_hookPlugin hook="backend_sidebar_useralert" hookAll="true"}{/if}
{/if}
                    </ul>
                </li>

            </ul>
        </nav>
{/if}

        <div id="content" class="clearfix">

{$admin_vars.main_content}{* starts from root 0 indent *}

        </div><!-- #content end -->

{/if}{* endif of logged-in *}

    </main>
{if NOT $admin_vars.no_footer}

    <footer id="meta">
        <p>{if $admin_vars.is_logged_in}{$admin_vars.version_info|replace:"Serendipity":"Serendipity Styx"}{else}{$blogTitle|default:''}{/if}</p>
    </footer>
{/if}
{if $admin_vars.admin_installed}

    <script src="{$serendipityHTTPPath}templates/jquery.js"></script>
    <script>
        window.serendipity = Object.assign(window.serendipity || {}, {
            // Backend Settings & Server Variables for StyxConfig.get()
            serendipityHTTPPath: '{$serendipityHTTPPath}',
            entryId: {if $entry_id}{$entry_id}{else}false{/if},
            autosave: {if $use_autosave}true{else}false{/if},
            useBackendPopups: {if $use_backendpopups}true{else}false{/if},
            forceBackendPopups: {if !empty($force_backendpopups)}{$force_backendpopups|json_encode}{else}[]{/if},
            uploadResize: {if {serendipity_getConfigVar key='uploadResize'}}true{else}false{/if},
            maxImgWidth: {if {serendipity_getConfigVar key='maxImgWidth'}}{serendipity_getConfigVar key='maxImgWidth'}{else}0{/if},
            maxImgHeight: {if {serendipity_getConfigVar key='maxImgHeight'}}{serendipity_getConfigVar key='maxImgHeight'}{else}0{/if},
            maxImgWidthPortrait: {if {serendipity_getConfigVar key='maxImgWidthPortrait'}}{serendipity_getConfigVar key='maxImgWidthPortrait'}{else}0{/if},

            // Language-Constants (i18n / Localisation) for StyxLang.get()
            lang: {
                abortNow: '{$CONST.ABORT_NOW|escape:"javascript"}',
                back: '{$CONST.BACK|escape:"javascript"}',
                currentTab: '{$CONST.CURRENT_TAB|escape:"javascript"}',
                deleteFileContinue: '{$CONST.DIALOG_DELETE_FILE_CONTINUE|escape:"javascript"}',
                deleteVariationsPerItem: '{$CONST.DIALOG_DELETE_VARIATIONS_PERITEM|escape:"javascript"}',
                dialogDeleteVariations: '{$CONST.DIALOG_DELETE_VARIATIONS|escape:"javascript"}',
                done: '{$CONST.DONE|escape:"javascript"}!',
                editorNoTags: '{$CONST.EDITOR_NO_TAGS|escape:"javascript"}',
                enterNewName: '{$CONST.ENTER_NEW_NAME|escape:"javascript"}',
                entryStatus: '{$CONST.ENTRY_STATUS|escape:"javascript"}',
                go: '{$CONST.GO|escape:"javascript"}',
                hide: '{$CONST.HIDE|escape:"javascript"}',
                mediaDeletes: '{$CONST.MEDIA_DELETE|escape:"javascript"}',
                mediaCreateVars: '{$CONST.MEDIA_CREATEVARS|escape:"javascript"}',
                mediaLibrary: '{$CONST.MEDIA_LIBRARY|escape:"javascript"}',
                mediaRename: '{$CONST.MEDIA_RENAME|escape:"javascript"}',
                no: '{$CONST.NO|escape:"javascript"}',
                noCategories: '{$CONST.NO_CATEGORIES|escape:"javascript"}',
                reallyScaleImage: '{$CONST.REALLY_SCALE_IMAGE|escape:"javascript"}',
                renameErrorReload: '{$CONST.MEDIA_RENAME_ERROR_RELOAD|escape:"javascript"}',
                timestampReset: '{$CONST.TIMESTAMP_RESET|escape:"javascript"}',
                toggleAll: '{$CONST.TOGGLE_ALL|escape:"javascript"}',
                unknownUpload: '{$CONST.ERROR_UNKNOWN_NOUPLOAD|escape:"javascript"}',
                yes: '{$CONST.YES|escape:"javascript"}',
            }
        });
    </script>
    <script src="{serendipity_getFile file='admin/serendipity_styx.min.js'}"></script>
    <script src="{$backend_hook_script}"></script>

{serendipity_hookPlugin hook="backend_footer" hookAll="true"}
{/if}

</body>
</html>
