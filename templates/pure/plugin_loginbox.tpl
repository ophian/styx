<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$head_charset}">
    <title>{$head_title|default:$blogTitle}{if $head_subtitle} | {$head_subtitle}{/if}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="Serendipity Styx Edition v.{$serendipityVersion}">
    <link rel="stylesheet" type="text/css" href="{$head_link_stylesheet}">
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
</head>

<body id="serendipity_loginform" class="grid-col">

    <button id="blink" class="navbar-shader btn float" onclick="dark()" title="Theme: Dark (Browser preferences|Session override)">
        <img id="daynight" src="{$serendipityHTTPPath}{$templatePath}{$template}/icons/moon-fill.svg" width="30" height="30" alt="">
    </button>

{if $close_window}
    <script type="text/javascript">
    if (window && window.opener && window.opener.focus)
        window.opener.focus();
    {if $is_logged_in}
        alert('{$CONST.USER_SELF_INFO|sprintf:$loginform_user:$loginform_mail}');
    {/if}
        self.close();
    </script>

{elseif $is_logged_in}
    <main id="content">

        <div id="login_instructions">
            <p>{$CONST.USER_SELF_INFO|sprintf:$loginform_user:$loginform_mail}</p>
        </div>

        <form id="loginbox" class="clearfix" action="{$loginform_url}" method="post">
            <input type="hidden" name="serendipity[action]" value="logout">
            <input type="hidden" name="serendipity[logout]" value="logout">
            <fieldset class="form-group">
                <div class="form_buttons">
                    <input id="login_send" name="submit" type="submit" value="{$CONST.LOGOUT} &gt;">
                    <a class="button_link" href="{$serendipityBaseURL}">{$CONST.BACK_TO_BLOG}</a>
                </div>
            </fieldset>
        </form>
    </main>

{else}
    <main id="content">

        <div id="login_instructions">
            <p>{$CONST.PLEASE_ENTER_CREDENTIALS}</p>
            {$loginform_add.header}
        </div>

{if $is_error}
        <div class="login_error">
            <p>{$CONST.WRONG_USERNAME_OR_PASSWORD}</p>
        </div>

{/if}
        <form id="loginbox" class="clearfix" action="{$loginform_url}" method="post">
            <input type="hidden" name="serendipity[action]" value="login">
            <fieldset class="form-group">
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
                    <input id="login_send" name="submit" type="submit" value="{$CONST.LOGIN} &gt;">
                    <a class="button_link" href="{$serendipityBaseURL}">{$CONST.BACK_TO_BLOG}</a>
                </div>
                {$loginform_add.table}
            </fieldset>
        </form>
    </main>
{/if}

    <footer id="footer">
        <p lang="en">{$CONST.POWERED_BY} <a href="https://ophian.github.io/">Serendipity Styx Edition</a> <abbr title="and">&amp;</abbr> the <i>{$template}</i> theme.</p>
    </footer>

    <script> const themePath = '{$serendipityHTTPPath}{$templatePath}{$template}'; </script>
    <script src="{serendipity_getFile file="pure.js"}"></script>

</body>
</html>