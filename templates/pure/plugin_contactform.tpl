<article class="page">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="page_content page_preface">
    {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}
    <p class="msg_notice">{$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}
    <p class="msg_important">{$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}
    <p class="msg_important">{$message}</p>
    {/foreach}
    {/if}
    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
            <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
            <input type="hidden" name="serendipity[commentform]" value="true">

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

            <div class="form_area">
                <label for="serendipity_commentform_comment">{$CONST.COMMENT}{if NOT empty($required_fields.comment)}&#8727;{/if}</label>
                <textarea id="serendipity_commentform_comment" rows="10" name="serendipity[comment]"{if NOT empty($required_fields.comment)} required{/if}>{$commentform_data}</textarea>
            </div>
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            <div class="form_buttons">
                <input id="serendipity_submit" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
            </div>
        </form>
    </div>
{/if}
</article>
