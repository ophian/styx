        <div id="serendipityCommentFormC" class="serendipity_commentForm">
            <div id="serendipity_replyform_0"></div>
            <a id="serendipity_CommentForm"></a>
        {if $is_moderate_comments}

            <p class="msg_important">{$CONST.COMMENTS_WILL_BE_MODERATED}</p>
        {/if}

            <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
                <input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}">

                <div class="form_field">
                    <label for="serendipity_commentform_name">{$CONST.NAME}{if NOT empty($required_fields.name)}&#8727;{/if}</label>
                    <input id="serendipity_commentform_name" type="text" name="serendipity[name]" value="{$commentform_name}"{if NOT empty($required_fields.name)} required{/if}>
                </div>

                <div class="form_field">
                    <label for="serendipity_commentform_email">{$CONST.EMAIL}{if NOT empty($required_fields.email)}&#8727;{/if}</label>
                    <input id="serendipity_commentform_email" type="email" name="serendipity[email]" value="{$commentform_email}"{if NOT empty($required_fields.email)} required{/if}>
                </div>

                <div class="form_field">
                    <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}{if NOT empty($required_fields.url)}&#8727;{/if}</label>
                    <input id="serendipity_commentform_url" type="url" name="serendipity[url]" value="{$commentform_url}"{if NOT empty($required_fields.url)} required{/if}>
                </div>

                <div class="form_select">
                    <label for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
                    {$commentform_replyTo}
                </div>

                <div class="form_tarea">
                    <label for="serendipity_commentform_comment">{$CONST.COMMENT}{if NOT empty($required_fields.comment)}&#8727;{/if}</label>
                    <textarea id="serendipity_commentform_comment" rows="10" name="serendipity[comment]"{if NOT empty($required_fields.comment)} required{/if}>{$commentform_data}</textarea>
                </div>

                {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            {if $is_commentform_showToolbar}

                <div class="form_check">
                    <input id="checkbox_remember" type="checkbox" name="serendipity[remember]"{$commentform_remember}> <label for="checkbox_remember">{$CONST.REMEMBER_INFO}</label>
                </div>
                {if $is_allowSubscriptions}

                <div class="form_check">
                    <input id="checkbox_subscribe" type="checkbox" name="serendipity[subscribe]"{$commentform_subscribe}> <label for="checkbox_subscribe">{$CONST.SUBSCRIBE_TO_THIS_ENTRY}</label>
                </div>
                {/if}
            {/if}

                <div class="form_buttons">
                    <input id="serendipity_preview" type="submit" name="serendipity[preview]" value="{$CONST.PREVIEW}">
                    <input id="serendipity_submit" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
                </div>
            </form>
        </div>
