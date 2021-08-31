<article class="page col-xs-12 col-lg-12 staticpage_plugin_contactform">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="page_content page_preface">

    {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}

    <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}

    <p class="alert alert-danger" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}

    <p class="alert alert-secondary" role="alert">{$message}</p>
    {/foreach}
    {/if}

    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
            <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
            <input type="hidden" name="serendipity[commentform]" value="true">

             <div class="form-group">
                <label for="serendipity_commentform_name">{$CONST.NAME} &#8727;</label>
                <input id="serendipity_commentform_name" class="form-control" type="text" name="serendipity[name]" value="{$commentform_name}" required>
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_email">{$CONST.EMAIL} &#8727;</label>
                <input id="serendipity_commentform_email" class="form-control" type="email" name="serendipity[email]" value="{$commentform_email}" required>
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
                <input id="serendipity_commentform_url" class="form-control" type="url" name="serendipity[url]" value="{$commentform_url}">
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_comment">{$CONST.COMMENT} &#8727;</label>
                <textarea id="serendipity_commentform_comment" class="form-control" rows="10" name="serendipity[comment]" required>{$commentform_data}</textarea>
            </div>
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            <div class="form_buttons my-3">
                <input id="serendipity_submit" class="btn btn-dark btn-sm" type="submit" name="serendipity[submit]" value="{$CONST.B46_SEND_MAIL}">
            </div>
        </form>
    </div>
{/if}

</article>