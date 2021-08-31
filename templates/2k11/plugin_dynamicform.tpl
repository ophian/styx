<article class="serendipity_entry staticpage_plugin_contactform">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="clearfix content serendipity_preface">
    {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}

    <p class="serendipity_msg_notice">{$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}

    <p class="serendipity_msg_important">{$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}

    <p class="serendipity_msg_important">{$message}</p>
    {/foreach}
    {/if}

    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
            <div>
                <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
                <input type="hidden" name="serendipity[commentform]" value="true">
            </div>
            {foreach $commentform_dynamicfields AS $field}
                {if $field.type == "checkbox"}

                    <fieldset class="form_field">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <div class="form_check{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} text_alert{/if}">
                            <label class="form_check_label">
                                <input type="checkbox" name="{$field.id}" id="{$field.id}" {$field.default|default:''} class="form_check_label">
                                {$field.message|default:''}
                            </label>
                        </div>
                    </fieldset>
                {elseif $field.type == "radio"}

                    <fieldset class="form_field">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <div class="form_radio form_radio_inline">
                            {foreach $field.options AS $option}

                                <label class="form_check_label">
                                    <input type="radio" class="form_check_input" name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>
                                    {$option.name}
                                </label>
                            {/foreach}

                        </div>
                    </fieldset>
                {elseif $field.type == "select"}

                    <fieldset class="form_field{if NOT empty($is_contactform_error) AND $field.required AND $selectset != 'true'} field_alert{/if}">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <select name="{$field.id}" class="form_select form_control">
                            {foreach $field.options AS $option}

                                <option name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>{$option.name}</option>
                            {/foreach}

                        </select>
                    </fieldset>
                {elseif $field.type == "password"}

                    <fieldset class="form_field{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} field_alert{/if}">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input type="password" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form_control">
                    </fieldset>
                {elseif $field.type == "textarea"}

                    <fieldset class="form_tarea{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} field_alert{/if}">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <textarea id="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}serendipity_commentform_comment{else}serendipity_contactform_{$field.id}{/if}" class="form_control" name="serendipity[{$field.id}]" rows="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}10{else}4{/if}" placeholder="{$field.name}">{$field.default}</textarea>
                        {* If you do NOT need AND run the emoticonchooser plugin, you can as well just use serendipity_contactform_{$field.id} here! *}

                    </fieldset>
                {elseif $field.type == "email"}

                    <fieldset class="form_field{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} field_alert{/if}">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input id="{$field.id}" class="form_control" name="serendipity[{$field.id}]" type="email" value="{$field.default}" placeholder="mail@example.org">
                    </fieldset>
                {else}

                    {if $field.type != "hidden"}

                    <fieldset class="form_field{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} field_alert{/if}">
                        <legend>{$field.name}{if $field.required} <span title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input type="text" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form_control" placeholder="{$field.name}">
                    </fieldset>
                    {else}

                    <div>
                        <input type="hidden" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}">
                    </div>
                    {/if}
                {/if}
            {/foreach}

            <div class="form_field">
                {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            </div>
            <div class="form_button">
                <input id="serendipity_submit" name="serendipity[submit]" type="submit" value="{$CONST.TWOK11_SEND_MAIL}">
            </div>
        </form>
    </div>
{/if}

</article>