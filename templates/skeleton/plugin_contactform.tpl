<article class="page serendipity_staticpage staticpage_plugin_contactform">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="page_content page_preface">

    {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}

    <p class="serendipity_msg_success">{$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}

    <p class="serendipity_msg_important">{$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}

    <p class="serendipity_msg_important">{$message}</p>
    {/foreach}
    {/if}

    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
            <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
            <input type="hidden" name="serendipity[commentform]" value="true">

             <div class="form_field">
                <label for="serendipity_commentform_name">{$CONST.NAME} &#8727;</label>
                <input id="serendipity_commentform_name" class="u-full-width" type="text" name="serendipity[name]" value="{$commentform_name}" required>
            </div>

            <div class="form_field">
                <label for="serendipity_commentform_email">{$CONST.EMAIL} &#8727;</label>
                <input id="serendipity_commentform_email" class="u-full-width" type="email" name="serendipity[email]" value="{$commentform_email}" required>
            </div>

            <div class="form_field">
                <label for="serendipity_commentform_url">{$CONST.HOMEPAGE} &#8727;</label>
                <input id="serendipity_commentform_url" class="u-full-width" type="url" name="serendipity[url]" value="{$commentform_url}">
            </div>

            <div class="form_tarea">
                <label for="serendipity_commentform_comment">{$CONST.COMMENT} &#8727;</label>
                <textarea id="serendipity_commentform_comment" class="u-full-width" rows="10" name="serendipity[comment]" required>{$commentform_data}</textarea>
            </div>
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            <div class="form_buttons">
                <input id="serendipity_submit" class="button-primary" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
            </div>
        </form>
    </div>
{/if}

</article>
