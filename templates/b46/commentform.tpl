<div id="serendipityCommentFormC" class="serendipity_commentForm">
    <div id="serendipity_replyform_0"></div>
    <a id="serendipity_CommentForm"></a>
{if $is_moderate_comments}

    <p class="alert alert-secondary" role="alert">{$CONST.COMMENTS_WILL_BE_MODERATED}</p>
{/if}

    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}">

        <div class="form-group">
            <label for="serendipity_commentform_name">{$CONST.NAME}{if NOT empty($required_fields.name)} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-asterisk" viewBox="0 0 16 16"><title id="title_required_name">{$CONST.REQUIRED_FIELD} name</title><path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/></svg>{/if}</label>
            <input id="serendipity_commentform_name" class="form-control" type="text" name="serendipity[name]" value="{$commentform_name}"{if NOT empty($required_fields.name)} required{/if}>
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_email">{$CONST.EMAIL}{if NOT empty($required_fields.email)} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-asterisk" viewBox="0 0 16 16"><title id="title_required_email">{$CONST.REQUIRED_FIELD} email</title><path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/></svg>{/if}</label>
            <input id="serendipity_commentform_email" class="form-control" type="email" name="serendipity[email]" value="{$commentform_email}"{if NOT empty($required_fields.email)} required{/if}>
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}{if NOT empty($required_fields.url)} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-asterisk" viewBox="0 0 16 16"><title id="title_required_url">{$CONST.REQUIRED_FIELD} url</title><path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/></svg>{/if}</label>
            <input id="serendipity_commentform_url" class="form-control" type="url" name="serendipity[url]" value="{$commentform_url}"{if NOT empty($required_fields.url)} required{/if}>
        </div>

        <div class="form-group form-select">
            <label for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
            {$commentform_replyTo}
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_comment">{$CONST.COMMENT}{if NOT empty($required_fields.comment)} <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-asterisk" viewBox="0 0 16 16"><title id="title_required_comment">{$CONST.REQUIRED_FIELD} comment</title><path d="M8 0a1 1 0 0 1 1 1v5.268l4.562-2.634a1 1 0 1 1 1 1.732L10 8l4.562 2.634a1 1 0 1 1-1 1.732L9 9.732V15a1 1 0 1 1-2 0V9.732l-4.562 2.634a1 1 0 1 1-1-1.732L6 8 1.438 5.366a1 1 0 0 1 1-1.732L7 6.268V1a1 1 0 0 1 1-1z"/></svg>{/if}</label>
            <textarea id="serendipity_commentform_comment" class="form-control" rows="10" name="serendipity[comment]"{if NOT empty($required_fields.comment)} required{/if}>{$commentform_data}</textarea>
        </div>

        <div class="form-group form-info alert alert-secondary">
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
        </div>
    {if $is_commentform_showToolbar}

        <div class="form-check">
            <input id="checkbox_remember" class="form-check-input" type="checkbox" name="serendipity[remember]"{$commentform_remember}> <label for="checkbox_remember" class="form-check-label">{$CONST.REMEMBER_INFO}</label>
        </div>
        {if $is_allowSubscriptions}

        <div class="form-check">
            <input id="checkbox_subscribe" class="form-check-input" type="checkbox" name="serendipity[subscribe]"{$commentform_subscribe}> <label for="checkbox_subscribe" class="form-check-label">{$CONST.SUBSCRIBE_TO_THIS_ENTRY}</label>
        </div>
        {/if}
    {/if}

        <div class="form_buttons my-3">
            <input id="serendipity_preview" class="btn btn-secondary btn-sm" type="submit" name="serendipity[preview]" value="{$CONST.PREVIEW}">
            <input id="serendipity_submit" class="btn btn-dark btn-sm" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
        </div>
    </form>
</div>