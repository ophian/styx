<div id="comments" class="serendipity_comments serendipity_section_comments">
    <a id="serendipity_CommentForm"></a>

    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <div>
            <input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}">
            <input type="hidden" name="serendipity[replyTo]" value="0">
        </div>

        <div class="input-text">
            <label for="serendipity_commentform_name">{$CONST.NAME}</label>
            <input type="text" size="30" value="{$commentform_name}" name="serendipity[name]" id="serendipity_commentform_name">
        </div>

        <div class="input-text">
            <label for="serendipity_commentform_email">{$CONST.EMAIL}</label>
            <input type="text" size="30" value="{$commentform_email}" name="serendipity[email]" id="serendipity_commentform_email">
        </div>

        <div class="input-text">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
            <input type="text" size="30" value="{$commentform_url}" name="serendipity[url]" id="serendipity_commentform_url">
        </div>

        <div class="input-select">
            <label id="reply-to-hint" for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
            {$commentform_replyTo}
        </div>

        <div class="input-textarea">
            <label for="serendipity_commentform_comment">{$CONST.COMMENT}</label>
            <textarea name="serendipity[comment]" id="serendipity_commentform_comment" cols="40" rows="10">{$commentform_data}</textarea>
        </div>

        <div id="directions">
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}

        {if $is_commentform_showToolbar}
            <div class="input-checkbox">
                <input id="checkbox_remember" type="checkbox" name="serendipity[remember]"{$commentform_remember}><label for="checkbox_remember">{$CONST.REMEMBER_INFO}</label>
            </div>
            {if $is_allowSubscriptions}
            <div class="input-checkbox">
                <input id="checkbox_subscribe" type="checkbox" name="serendipity[subscribe]"{$commentform_subscribe}><label for="checkbox_subscribe">{$CONST.SUBSCRIBE_TO_THIS_ENTRY}</label>
            </div>
            {/if}
        {/if}

        {if $is_moderate_comments}
          <p class="serendipity_msg_important">{$CONST.COMMENTS_WILL_BE_MODERATED}</p>
        {/if}

        </div>

        <div class="input-buttons">
            <input type="submit" id="serendipity_csubmit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
            <input type="submit" id="serendipity_preview" name="serendipity[preview]" value="{$CONST.PREVIEW}">
        </div>

    </form>

</div>
