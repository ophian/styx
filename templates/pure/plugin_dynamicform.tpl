<article class="page staticpage_plugin_contactform">
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
            <div>
                <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
                <input type="hidden" name="serendipity[commentform]" value="true">
            </div>
            {foreach $commentform_dynamicfields AS $field}
                {if $field.type == "checkbox"}

                    <fieldset class="form-group">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <div class="form-check{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} text-hint{/if}">
                            <label class="form-check-label">
                                <input type="checkbox" name="{$field.id}" id="{$field.id}" {$field.default|default:''} class="form-check-label">
                                {$field.message|default:''}
                            </label>
                        </div>
                    </fieldset>
                {elseif $field.type == "radio"}

                    <fieldset class="form-group">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <div class="form-radio">
                            {foreach $field.options AS $option}

                                <label class="form-check-label">
                                    <input type="radio" class="form-check-input" name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>
                                    {$option.name}
                                </label>
                            {/foreach}

                        </div>
                    </fieldset>
                {elseif $field.type == "select"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND $selectset != 'true'} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <select name="{$field.id}" class="form-select form-control">
                            {foreach $field.options AS $option}

                                <option name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>{$option.name}</option>
                            {/foreach}

                        </select>
                    </fieldset>
                {elseif $field.type == "password"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input type="password" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control">
                    </fieldset>
                {elseif $field.type == "textarea"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <textarea id="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}serendipity_commentform_comment{else}serendipity_contactform_{$field.id}{/if}" class="form-control" name="serendipity[{$field.id}]" rows="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}10{else}4{/if}" placeholder="{$field.name}">{$field.default}</textarea>
                        {* If you do NOT need AND run the emoticonchooser plugin, you can as well just use serendipity_contactform_{$field.id} here! *}

                    </fieldset>
                {elseif $field.type == "email"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input id="{$field.id}" class="form-control" name="serendipity[{$field.id}]" type="email" value="{$field.default}" placeholder="mail@example.org">
                    </fieldset>
                {else}

                    {if $field.type != "hidden"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <span class="text-hint" title="{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}">&#8727;&nbsp;</span>{/if}</legend>
                        <input type="text" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control" placeholder="{$field.name}">
                    </fieldset>
                    {else}

                    <div>
                        <input type="hidden" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}">
                    </div>
                    {/if}
                {/if}
            {/foreach}

            <div class="form-group form-hook">
                {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            </div>

            <div class="form-group">
                <input id="serendipity_submit" name="serendipity[submit]" class="btn btn-dark btn-sm" type="submit" value="{$CONST.SUBMIT_COMMENT}">
            </div>
        </form>
    </div>
{/if}

</article>