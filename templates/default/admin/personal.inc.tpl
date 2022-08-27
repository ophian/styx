    <h2>{$CONST.PERSONAL_SETTINGS}</h2>
{if isset($adminAction) AND $adminAction == 'save'}
    {if isset($not_authorized) AND $not_authorized}
    <span class="msg_error ppconf_msg"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.CREATE_NOT_AUTHORIZED_USERLEVEL}</span>
    {elseif isset($empty_username) AND $empty_username}
    <span class="msg_error ppconf_msg"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.USERCONF_CHECK_USERNAME_ERROR}</span>
    {elseif isset($password_check_fail) AND $password_check_fail}
    <span class="msg_error ppconf_msg"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.USERCONF_CHECK_PASSWORD_ERROR}</span>
    {else}
    <span class="msg_success ppconf_msg"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.MODIFIED_USER|sprintf:"{$realname|escape}"}</span>
    {/if}
{/if}
<form action="?serendipity[adminModule]=personal&amp;serendipity[adminAction]=save" method="post">
    {$formToken}
    {$CONFIG}
    <div class="form_buttons">
        <input name="SAVE" type="submit" value="{$CONST.SAVE}">
    </div>
</form>
