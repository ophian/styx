<div id="serendipityCommentFormC" class="serendipity_commentForm">
    <div id="serendipity_replyform_0"></div>
    <a id="serendipity_CommentForm"></a>
{if $is_moderate_comments}

    <p class="alert alert-warning" role="alert">{$CONST.COMMENTS_WILL_BE_MODERATED}</p>
{/if}

    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}">

        <div class="form-group">
            <label for="serendipity_commentform_name">{$CONST.NAME}{if NOT empty($required_fields.name)} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_name">{$CONST.REQUIRED_FIELD} name</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg>{/if}</label>
            <input id="serendipity_commentform_name" class="form-control" type="text" name="serendipity[name]" value="{$commentform_name}"{if NOT empty($required_fields.name)} required{/if}>
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_email">{$CONST.EMAIL}{if NOT empty($required_fields.email)} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_email">{$CONST.REQUIRED_FIELD} email</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg>{/if}</label>
            <input id="serendipity_commentform_email" class="form-control" type="email" name="serendipity[email]" value="{$commentform_email}"{if NOT empty($required_fields.email)} required{/if}>
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}{if NOT empty($required_fields.url)} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_url">{$CONST.REQUIRED_FIELD} url</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg>{/if}</label>
            <input id="serendipity_commentform_url" class="form-control" type="url" name="serendipity[url]" value="{$commentform_url}"{if NOT empty($required_fields.url)} required{/if}>
        </div>

        <div class="form-group">
            <label for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
            {$commentform_replyTo}
        </div>

        <div class="form-group">
            <label for="serendipity_commentform_comment">{$CONST.COMMENT}{if NOT empty($required_fields.comment)} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_comment">{$CONST.REQUIRED_FIELD} comment</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg>{/if}</label>
            <textarea id="serendipity_commentform_comment" class="form-control" rows="10" name="serendipity[comment]"{if NOT empty($required_fields.comment)} required{/if}>{$commentform_data}</textarea>
        </div>
        {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
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
            <input id="serendipity_preview" class="btn btn-outline-primary" type="submit" name="serendipity[preview]" value="{$CONST.PREVIEW}">
            <input id="serendipity_submit" class="btn btn-primary" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
        </div>
    </form>
</div>