<div id="plugin_contact" class="clearfix serendipity_staticpage staticpage_plugin_contactform">
{if $plugin_contactform_articleformat}

    <div class="serendipity_Entry_Date">
    {if NOT empty($plugin_contactform_pagetitle)}  <h3 class="serendipity_date">{$plugin_contactform_name}</h3>{/if}
        <div class="serendipity_entry">
            <div class="serendipity_entry_body">
{/if}

    <div class="clearfix">
        <div class="entry-info">
            <h1 class="page-title" class="entry-title">{$plugin_contactform_pagetitle}</h1>
            {if empty($is_contactform_sent)}

            <div id="preface" class="preface">{$plugin_contactform_preface}</div>
            {/if}

        </div>

        <div class="entry-body">
        {if NOT empty($is_contactform_sent)}

            <a name="feedback"></a><p class="serendipity_center serendipity_msg_notice">{$plugin_contactform_sent}</p>
        {else}
            {if NOT empty($is_contactform_error)}

            <p class="serendipity_center serendipity_msg_important">{$plugin_contactform_error}</p>

            <!-- Needed for Captchas -->
        {foreach $comments_messagestack AS $message}

            <p class="serendipity_center serendipity_msg_important">{$message}</p>
        {/foreach}
        {/if}

            <div id="serendipity_comment" class="serendipity_commentForm">
                <a id="serendipity_CommentForm"></a>

                <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">

                    <div>
                        <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
                        <input type="hidden" name="serendipity[commentform]" value="true">
                    </div>

                    <div class="input-text">
                        <label for="serendipity_commentform_name">{$CONST.NAME} &lowast;</label>
                        <input type="text" size="30" value="{$commentform_name}" name="serendipity[name]" id="serendipity_commentform_name" required>
                    </div>

                    <div class="input-text">
                        <label for="serendipity_commentform_email">{$CONST.EMAIL} &lowast;</label>
                        <input type="text" size="30" value="{$commentform_email}" name="serendipity[email]" id="serendipity_commentform_email" required>
                    </div>

                    <div class="input-text">
                        <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
                        <input type="text" size="30" value="{$commentform_url}" name="serendipity[url]" id="serendipity_commentform_url">
                    </div>

                    <div class="input-textarea">
                        <label for="serendipity_commentform_comment">{$plugin_contactform_message} &lowast;</label>
                        <textarea name="serendipity[comment]" id="serendipity_commentform_comment" cols="40" rows="10" required>{$commentform_data}</textarea>
                    </div>

                    <div id="directions">
                        {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
                    </div>

                    <div class="input-buttons">
                        <input type="submit" value="{$CONST.SUBMIT_COMMENT}" name="serendipity[submit]">
                    </div>

                </form>
            </div>
        {/if}

        </div>
    </div>
{if $plugin_contactform_articleformat}

            </div>
        </div>
    </div>
{/if}

</div>
