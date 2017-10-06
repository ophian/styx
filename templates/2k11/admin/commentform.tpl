{* DEV backend commentform file - anything more to change or to simplify here, since used in backend only? *}
<div id="serendipityCommentFormC" class="serendipityCommentForm">
    <div id="serendipity_replyform_0"></div>
    <a id="serendipity_CommentForm"></a>
    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <div><input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}"></div>
        <div class="form_field">
            <label for="serendipity_commentform_name">{$CONST.NAME}</label>
            <input id="serendipity_commentform_name" name="serendipity[name]" type="text" value="{$commentform_name}" placeholder="{$CONST.TWOK11_PLACE_NAME}">
        </div>
        <div class="form_field">
            <label for="serendipity_commentform_email">{$CONST.EMAIL}</label>
            <input id="serendipity_commentform_email" name="serendipity[email]" type="email" value="{$commentform_email}" placeholder="{$CONST.TWOK11_PLACE_MAIL}">
        </div>
        <div class="form_field">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
            <input id="serendipity_commentform_url" name="serendipity[url]" type="url" value="{$commentform_url}" placeholder="{$CONST.TWOK11_PLACE_URL}">
        </div>
        <div class="form_tarea">
            <label for="serendipity_backend_commentform_comment">{$CONST.COMMENT}</label>
            <textarea id="serendipity_backend_commentform_comment" data-tarea="serendipity_backend_commentform_comment" name="serendipity[comment]" rows="10" placeholder="{$CONST.TWOK11_PLACE_MESSAGE}">{$commentform_data}</textarea>
        </div>
        <div class="form_field">
            <label id="reply-to-hint" for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
            {$commentform_replyTo}
        </div>
        {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
        <div class="clearfix empty">&nbsp;</div>

        <div class="form_button">
            <input id="serendipity_preview" class="entry_preview" name="serendipity[preview]" type="submit" value="{$CONST.PREVIEW}">
            <input id="serendipity_submit" name="serendipity[submit]" type="submit" value="{$CONST.SUBMIT_COMMENT}">
        </div>
    </form>
</div>
