{if isset($showSubmit_head) AND $showSubmit_head}

    <div class="form_buttons">
        {if $button_postKey == 'template'}
        <a class="button_link" href="?serendipity[adminModule]=templates">{$CONST.BACK}</a>
        {elseif $button_postKey == 'categorytemplate'}
        <a class="button_link" href="?serendipity[adminModule]=category&amp;serendipity[adminAction]=edit&amp;serendipity[cid]={$smarty.get.serendipity.catid}">{$CONST.BACK}</a>
        {/if}
        <input name="SAVECONF" type="submit" value="{$CONST.SAVE}">
    </div>
{/if}
{if is_array($config_groups)}

    <button id="show_config_all" class="button_link" type="button" data-href="#serendipity_config_options" title="{$CONST.TOGGLE_ALL}">
        <span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TOGGLE_ALL}</span>
    </button>

    <div id="serendipity_config_options">
    {foreach $config_groups AS $config_header => $config_groupkeys}

        <div class="configuration_group">
            <h3 class="toggle_headline"><button id="optionel{$config_groupkeys@iteration}" class="show_config_option show_config_option_hide" type="button" data-href="#el{$config_groupkeys@iteration}" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$config_header}</button></h3>

            <fieldset id="el{$config_groupkeys@iteration}" class="config_optiongroup{if $config_groupkeys@last} config_optiongroup_last{/if} additional_info">
            {foreach $config_groupkeys AS $config_groupkey}
                {if isset($plugin_options[$config_groupkey]) AND $plugin_options[$config_groupkey]['ctype'] == 'separator'}
                    {$plugin_options[$config_groupkey]['config']}
                {else}

                    <div class="{cycle values='odd,even'}">
                        {$plugin_options[$config_groupkey]['config']|default:''}
                    </div>
                {/if}
            {/foreach}

            </fieldset>
        </div>
    {/foreach}

    </div>
{/if}

    <div class="configuration_group main_group">
{foreach $plugin_options_ungrouped AS $plugin_option}{if !isset($plugin_option)}{continue}{/if}
    {if in_array($plugin_option['ctype'], ['separator', 'suboption', 'hidden'])}
        {$plugin_option['config']}
    {else if !empty($plugin_option['config'])}

        <div class="{cycle values='odd,even'}">
            {$plugin_option['config']}
        </div>
    {/if}
{/foreach}
</div><!-- main group end -->
{if isset($showSubmit_foot) AND $showSubmit_foot AND !empty($postKey)}

    <div class="form_buttons">
    {if $button_postKey == 'categorytemplate'}
        <a class="button_link" href="?serendipity[adminModule]=category&amp;serendipity[adminAction]=edit&amp;serendipity[cid]={$smarty.get.serendipity.catid}">{$CONST.BACK}</a>
    {else}
        <a class="button_link" href="?serendipity[adminModule]={$postKey}s">{$CONST.BACK}</a>
    {/if}
        <input name="SAVECONF" type="submit" value="{$CONST.SAVE}">
    </div>
{/if}
{if isset($showExample) AND $showExample}

    <div>{$plugin_example}</div>
{/if}
{if isset($spawnNuggets) AND $spawnNuggets}
    {serendipity_hookPlugin hook="backend_wysiwyg_nuggets" data=$ev hookAll=true}

    {if $ev['skip_nuggets'] === false AND (!isset($init) OR $init !== false)}

    <script>
    function Spawnnugget() {
        /* init plugin nuggets when not using the default wysiwyg-editor */
        {foreach $ev['nuggets'] AS $htmlnuggetid}

        if (window.Spawnnuggets) Spawnnuggets('{$htmlnuggetid}');
        {/foreach}

    }
    </script>
    {/if}

{/if}
