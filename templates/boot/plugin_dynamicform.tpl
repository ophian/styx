<article class="page staticpage_plugin_contactform">
    <h2>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h2>

    <div class="page_content page_preface">
    {$plugin_contactform_preface}
    </div>
{if NOT empty($is_contactform_sent)}

    <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$plugin_contactform_sent}</p>
{else}
    {if NOT empty($is_contactform_error)}

    <p class="alert alert-danger" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$plugin_contactform_error}</p>
    {foreach $comments_messagestack AS $message}

    <p class="alert alert-secondary" role="alert">{$message}</p>
    {/foreach}
    {/if}

    <div class="serendipity_commentForm">
        <a id="serendipity_CommentForm"></a>
        <form id="serendipity_comment" class="form-vertical" action="{$commentform_action}#feedback" method="post">
            <div>
                <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
                <input type="hidden" name="serendipity[commentform]" value="true">
            </div>
            {foreach $commentform_dynamicfields AS $field}
                {if $field.type == "checkbox"}

                    <fieldset class="form-group">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <div class="form-check{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} text-danger{/if}">
                            <label class="form-check-label">
                                <input type="checkbox" name="{$field.id}" id="{$field.id}" {$field.default|default:''} class="form-check-label">
                                {$field.message|default:''}
                            </label>
                        </div>
                    </fieldset>
                {elseif $field.type == "radio"}

                    <fieldset class="form-group">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                      {foreach $field.options AS $option}

                        <div class="form-check form-check-inline">
                            <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>
                                {$option.name}
                            </label>
                        </div>
                    {/foreach}

                    </fieldset>
                {elseif $field.type == "select"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND $selectset != 'true'} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <select name="{$field.id}" class="form-select form-control">
                            {foreach $field.options AS $option}

                                <option name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default|default:''}>{$option.name}</option>
                            {/foreach}

                        </select>
                    </fieldset>
                {elseif $field.type == "password"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <input type="password" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control">
                    </fieldset>
                {elseif $field.type == "textarea"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <textarea id="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}serendipity_commentform_comment{else}serendipity_contactform_{$field.id}{/if}" class="form-control" name="serendipity[{$field.id}]" rows="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}10{else}4{/if}" placeholder="{$field.name}">{$field.default}</textarea>
                        {* If you do NOT need AND run the emoticonchooser plugin, you can as well just use serendipity_contactform_{$field.id} here! *}

                    </fieldset>
                {elseif $field.type == "email"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <input id="{$field.id}" class="form-control" name="serendipity[{$field.id}]" type="email" value="{$field.default}" placeholder="mail@example.org">
                    </fieldset>
                {else}

                    {if $field.type != "hidden"}

                    <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND empty($field.default)} has-error{/if}">
                        <legend>{$field.name}{if $field.required} <svg class="bi me-1 mb-1" width="16" height="16" role="img" aria-labelledby="title"><title id="title_required_{$field.type}">{$CONST.PLUGIN_CONTACTFORM_REQUIRED_FIELD}</title><use xlink:href="#required-field-asterisk"/></svg>{/if}</legend>
                        <input type="text" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control" placeholder="{$field.name}">
                    </fieldset>
                    {else}

                    <div>
                        <input type="hidden" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}">
                    </div>
                    {/if}
                {/if}
            {/foreach}

            <div class="form-group">
                {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
            </div>
            <div class="form-group">
                <input id="serendipity_submit" name="serendipity[submit]" class="btn btn-dark btn-sm" type="submit" value="{$CONST.SUBMIT_COMMENT}">
            </div>
        </form>
    </div>
{/if}

</article>