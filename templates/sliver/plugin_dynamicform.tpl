<div id="plugin_contact" class="clearfix serendipity_staticpage staticpage_plugin_contactform">
{if $plugin_contactform_articleformat}

    <div class="serendipity_Entry_Date">
        <h3 class="serendipity_date">{$plugin_contactform_name}</h3>
        <div class="serendipity_entry">
            <div class="serendipity_entry_body">
{/if}

{if NOT empty($is_contactform_error)}

    <div class="serendipity_center serendipity_msg_important">{$plugin_contactform_error}</div>
    <!-- Needed for Captchas -->
    {foreach $comments_messagestack AS $message}

        <div class="serendipity_center serendipity_msg_important">{$message}</div>
    {/foreach}
{/if}
{if empty($is_contactform_sent)}

    <div>{$plugin_contactform_preface}</div>
{/if}
{if NOT empty($is_contactform_sent)}

    <div class="serendipity_center serendipity_msg_notice">{$plugin_contactform_sent}</div>
{else}

<!-- This whole commentform style, including field names is needed -->
<!-- for Captchas. The spamblock plugin relies on the field names  -->
<!-- [name], [email], [url], [comment]!                            -->

<div class="serendipity_commentForm">
    <a id="serendipity_CommentForm"></a>
    <form id="serendipity_comment_CommentForm" action="{$commentform_action}#feedback" method="post">
        <div>
            <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
            <input type="hidden" name="serendipity[commentform]" value="true">
            {foreach $commentform_dynamicfields AS $field}
                {if $field.type == "hidden"}

                    <input type="hidden" name="serendipity[{$field.id}]" value="{$field.default}">
              {/if}
            {/foreach}

        </div>

        <fieldset>
            <legend>{$plugin_contactform_pagetitle}</legend>
            <dl>
                {foreach $commentform_dynamicfields AS $field}
                    {if $field.type != "hidden"}

                        <dt class="serendipity_commentsLabel">
                           {if $field.required}<sup>&#8727;</sup>{/if}<label for="serendipity_contactform_{$field.id}">{$field.name}</label>
                        </dt>
                        <dd class="serendipity_commentsValue">
                            {if $field.type == "checkbox"}

                                <input class="frm_check" type="checkbox" name="{$field.id}" id="{$field.id}" {$field.default}><label class="frm_check_label" for="{$field.id}">{$field.message}</label>
                            {elseif $field.type == "radio"}
                                {foreach $field.options AS $option}

                                    <input class="frm_radio" type="radio" name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default}><label class="frm_radio_label" for="{$field.id}.{$option.id}">{$option.name}</label>
                                {/foreach}
                            {elseif $field.type == "select"}

                                <select name="{$field.id}">
                                    {foreach $field.options AS $option}

                                        <option name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default} >{$option.name}</option>
                                    {/foreach}

                                </select>
                            {elseif $field.type == "password"}

                                <input class="frm" type="password" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" size="30">
                            {elseif $field.type == "textarea"}

                                <textarea class="frm" rows="10" cols="40" id="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}serendipity_commentform_comment{else}serendipity_contactform_{$field.id}{/if}" name="serendipity[{$field.id}]">{$field.default}</textarea>
                            {else}

                                <input class="frm" type="text" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" size="30">
                            {/if}

                        </dd>
                    {/if}
                {/foreach}

                <dt>&#160;</dt>
                <dd>
<!-- This is where the spamblock/Captcha plugin is called -->
                    {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}

                </dd>
                <dt>&#160;</dt>
                <dd>
                    <input class="frm" type="submit" name="serendipity[submit]" value="{$CONST.SUBMIT_COMMENT}">
                </dd>
            </dl>
        </fieldset>
    </form>
</div>
{/if}
{if $plugin_contactform_articleformat}

            </div>
        </div>
    </div>
{/if}

</div>
