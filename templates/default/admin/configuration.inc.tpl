<h2>{$CONST.CONFIGURATION}</h2>
{if isset($installAction) AND $installAction == 'check'}
    {if isset($diagnosticError) AND $diagnosticError}
            <h4>{$CONST.DIAGNOSTIC_ERROR}</h4>
        {foreach $res AS $r}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$r}</span>
        {/foreach}
    {else}
        {if isset($htaccessRewrite) AND $htaccessRewrite}
            <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.ATTEMPT_WRITE_FILE|sprintf:"{$serendipityPath}.htaccess"}</span>
            {if is_array($res)}
                {foreach $res AS $r}
                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$r}</span>
                {/foreach}
            {else}
                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}</span>
            {/if}
        {/if}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.WRITTEN_N_SAVED}</span>
        <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.RELOAD_THIS_PAGE|sprintf:'?serendipity[adminModule]=configuration':{$CONST.CONFIGURATION}}</span>
    {/if}
{/if}
{$CONFIG}
