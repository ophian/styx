{if isset($importForm) AND $importForm}
    {if isset($die) AND $die}
        <h2>{$CONST.IMPORT_ENTRIES}</h2>

        <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.INCLUDE_ERRORFAILURE|sprintf:'importer'}</span>
    {else}
        {if isset($validateData) AND $validateData}
        <h2>{$CONST.IMPORT_ENTRIES}</h2>

        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.IMPORT_STARTING}</span>
            {if $result === false}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.IMPORT_FAILED}</span>
            {else if $result === true}
            <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.IMPORT_DONE}</span>
            {else}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$CONST.IMPORT_FAILED}: {$result|default:"Unknown error occurred"}</span>
            {/if}
        {else}
            <h2>{$CONST.IMPORT_PLEASE_ENTER}</h2>

            <div id="waitingspin" class="pulsator busy_importing" style="display: none"><div></div><div></div></div>

            <form action="" method="POST" enctype="multipart/form-data">
                {$formToken}
                {if $notes}
                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.IMPORT_NOTES}: {$notes}</span>
                {/if}
                <dl class="importer_data">
                {foreach $fields AS $field}
                    <dt>{$field.text}</dt>
                    <dd class="clearfix">{$field.guessedInput}</dd>
                {/foreach}
                </dl>

                <div class="form_buttons">
                    <button type="submit" class="button_link state_import">{$CONST.IMPORT_NOW}</button>
                </div>
            </form>
        {/if}
    {/if}
{else}
    <form action="" method="GET">
        <input name="serendipity[adminModule]" type="hidden" value="import">
        {$formToken}

        <div class="form_select">
            <label for="import_from">{$CONST.IMPORT_WEBLOG_APP}</label>
            <select id="import_from" name="serendipity[importFrom]">
                <option value="none"></option>
            {foreach $list AS $v => $k}
                <option value="{$v}">{$k}</option>
            {/foreach}
            </select>

            <input type="submit" value="{$CONST.GO}">
        </div>
    </form>
{/if}
