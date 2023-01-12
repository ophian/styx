    <div id="serendipity_faq_plugin" class="clearfix serendipity_entry faq-searchresults">
        <div class="faq-results">
            <h3>{$CONST.FAQ_SEARCHRESULTS|sprintf:$faq_searchresults}</h3>
        {if !empty($faq_results)}

            <ul class="faq_result faq-faqs">
            {foreach $faq_results AS $result}

                <li><icon class="faq_question-icon"></icon> <strong><a href="{$serendipityBaseURL}{$faq_pluginpath}/{$result.cid}/{$result.id}" title="{$result.question|truncate:50:" ... "|escape}">{$result.question|truncate:50:" ... "}</a></strong><br />
                {$result.answer|truncate:200:" ... "}</li>
            {/foreach}

            </ul>
        {else}

        <p class="serendipity_msg_notice">{$CONST.NO_ENTRIES_TO_PRINT}</p>
        {/if}

        </div>
    </div>
