<h2>{$CONST.CONFIGURATION}</h2>
{if $installAction == 'check'}
    {if $diagnosticError}
            <h4>{$CONST.DIAGNOSTIC_ERROR}</h4>
        {foreach $res AS $r}
            <span class="msg_error"><span class="icon-attention-circled" aria-hidden="true"></span> {$r}</span>
        {/foreach}
    {else}
        {if $htaccessRewrite}
            <p>{$CONST.ATTEMPT_WRITE_FILE|sprintf:"{$serendipityPath}htaccess"}</p>
            {if is_array($res)}
                {foreach $res AS $r}
                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$r}</span>
                {/foreach}
            {else}
                <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.DONE}</span>
            {/if}
        {/if}
        <span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.WRITTEN_N_SAVED}</span>
    {/if}
{/if}
{$CONFIG}
