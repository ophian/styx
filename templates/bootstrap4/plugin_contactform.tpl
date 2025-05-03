<article class="page staticpage_plugin_contactform">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="page_content page_preface">

        {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}

    <p class="alert alert-success" role="alert">{$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}

    <p class="alert alert-warning" role="alert">{$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}

    <p class="alert alert-warning" role="alert">{$message}</p>
    {/foreach}
    {/if}

    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
            <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
            <input type="hidden" name="serendipity[commentform]" value="true">

             <div class="form-group">
                <label for="serendipity_commentform_name">{$CONST.NAME} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_name">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg></label>
                <input id="serendipity_commentform_name" class="form-control" type="text" name="serendipity[name]" value="{$commentform_name}" required>
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_email">{$CONST.EMAIL} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_email">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg></label>
                <input id="serendipity_commentform_email" class="form-control" type="email" name="serendipity[email]" value="{$commentform_email}" required>
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
                <input id="serendipity_commentform_url" class="form-control" type="url" name="serendipity[url]" value="{$commentform_url}">
            </div>

            <div class="form-group">
                <label for="serendipity_commentform_comment">{$CONST.COMMENT} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_comment">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="{$serendipityHTTPPath}{$templatePath}{$template}/img/icons.svg#required-field-asterisk"/></svg></label>
                <textarea id="serendipity_commentform_comment" class="form-control" rows="10" name="serendipity[comment]" required>{$commentform_data}</textarea>
            </div>
            {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            <div class="form_buttons my-3">
                <input id="serendipity_submit" class="btn btn-primary" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
            </div>
        </form>
    </div>
{/if}

</article>