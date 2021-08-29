<article class="serendipity_staticpage staticpage_plugin_contactform{if $plugin_contactform_articleformat} post serendipity_entry{/if}">
    <h3>{if $plugin_contactform_articleformat}{$plugin_contactform_name}{else}{$plugin_contactform_pagetitle}{/if}</h3>
    <section id="entry">
        <div class="content serendipity_entry_body">
            {if NOT empty($is_contactform_error)}
                 <div id="search-block" class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p class="alert alert-danger alert-error"><span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$plugin_contactform_error}</p>
                    </div>
                </div>
                {foreach $comments_messagestack AS $message}
                    <div id="search-block" class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <p class="alert alert-danger alert-error"><span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$message}</p>
                        </div>
                    </div>
                {/foreach}
            {/if}
            {if empty($is_contactform_sent) AND $plugin_contactform_preface}
               <div class="contactform_preface">{$plugin_contactform_preface}</div>
            {/if}
            {if NOT empty($is_contactform_sent)}
                <p class="alert alert-success"><span class="fa-stack text-success" aria-hidden="true"></i><i class="far fa-smile fa-2x"></i></span> {$plugin_contactform_sent}</p>
            {else}
            <div id="serendipityCommentForm" class="serendipity_commentForm">
                <a id="serendipity_CommentForm"></a>
                <form id="serendipity_comment" class="form-vertical" action="{$commentform_action}#feedback" method="post">
                    <div>
                        <input type="hidden" name="serendipity[subpage]" value="{$commentform_sname}">
                        <input type="hidden" name="serendipity[commentform]" value="true">
                    </div>
                    {foreach $commentform_dynamicfields AS $field}
                        {if $field.type != "hidden"}
                            {if $field.type == "checkbox"}
                                <fieldset class="form-group">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <div class="form-check{if NOT empty($is_contactform_error) AND $field.required AND NOT $field.default} text-danger{/if}">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="{$field.id}" id="{$field.id}" {$field.default} class="form-check-label">
                                            {$field.message}
                                        </label>
                                    </div>
                                </fieldset>
                            {elseif $field.type == "radio"}
                                {assign var="radioset" value=''}
                                {foreach $field.options AS $option}
                                    {if $option.default}{assign var="radioset" value='true'}{/if}
                                {/foreach}
                                <fieldset class="form-group">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <div class="form-check{if NOT empty($is_contactform_error) AND $field.required AND $radioset!='true'} text-danger{/if}">
                                        {foreach $field.options AS $option}
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default}>
                                                {$option.name}
                                            </label>
                                        {/foreach}
                                    </div>
                                </fieldset>
                            {elseif $field.type == "select"}
                                {assign var="selectset" value=''}
                                {foreach $field.options AS $option}
                                    {if $option.default}{assign var="selectset" value='true'}{/if}
                                {/foreach}
                                <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND $selectset != 'true'} has-error{/if}">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <select name="{$field.id}" class="form-control">
                                        {if $selectset != 'true'}<option value="" disabled selected style="display: none;">{$CONST.PLEASESELECT}...</option>{/if}
                                        {foreach $field.options AS $option}
                                            <option name="{$field.id}" id="{$field.id}.{$option.id}" value="{$option.value}" {$option.default} >{$option.name}</option>
                                        {/foreach}
                                    </select>
                                </fieldset>
                            {elseif $field.type == "password"}
                                <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND NOT $field.default} has-error{/if}">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <input type="password" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control">
                                </fieldset>
                            {elseif $field.type == "textarea"}
                                <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND NOT $field.default} has-error{/if}">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <textarea id="{if $field.name == $CONST.PLUGIN_CONTACTFORM_MESSAGE}serendipity_commentform_comment{else}serendipity_contactform_{$field.id}{/if}" class="form-control" name="serendipity[{$field.id}]" rows="10" placeholder="{$field.name}">{$field.default}</textarea>
                                    {* If you do NOT need AND run the emoticonchooser plugin, you can as well just use serendipity_contactform_{$field.id} here! *}
                                </fieldset>
                            {elseif $field.type == "email"}
                                <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND NOT $field.default} has-error{/if}">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <input id="{$field.id}" class="form-control" name="serendipity[{$field.id}]" type="email" value="{$field.default}" placeholder="mail@example.org">
                                </fieldset>
                            {else}
                                <fieldset class="form-group{if NOT empty($is_contactform_error) AND $field.required AND NOT $field.default} has-error{/if}">
                                    <legend>{$field.name}{if $field.required} <span class="text-danger">&#8727;</span>{/if}</legend>
                                    <input type="text" id="serendipity_contactform_{$field.id}" name="serendipity[{$field.id}]" value="{$field.default}" class="form-control" placeholder="{$field.name}">
                                </fieldset>
                            {/if}
                        {/if}
                    {/foreach}
                    <div class="form-group">
                        {serendipity_hookPlugin hook="frontend_comment" data=$commentform_entry}
                    </div>
                    <div class="form-group">
                        <input id="serendipity_submit" name="serendipity[submit]" class="btn btn-secondary btn-theme" type="submit" value="{$CONST.SUBMIT_COMMENT}">
                    </div>
                </form>
            </div>
            {/if}{* FIX POSITION IN CONTACT FORM TOO *}
        </div>
    </section>
</article>