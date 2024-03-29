{if NOT $noForm}
<form action="?" method="POST">
    <input type="hidden" name="serendipity[adminModule]" value="installer">
    <input type="hidden" name="installAction" value="check">
    {$formToken}
{/if}
{if count($config) > 1 AND $allowToggle}

    <a id="show_config_all" class="button_link toggle_config" href="#serendipity_config_options" title="{$CONST.TOGGLE_ALL}"><span class="icon-right-dir" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.TOGGLE_ALL}</span></a>
{/if}

    <div id="serendipity_config_options">
{foreach $config AS $category}

        <div class="configuration_group">
{if count($config) > 1}
{if $allowToggle}
            <h3 class="toggle_headline">
                <button id="optionel{$category@iteration}" class="show_config_option icon_link{if $category@first} show_config_option_hide{/if}" type="button" data-href="#el{$category@index}" title="{$CONST.TOGGLE_OPTION}"><span class="icon-right-dir" aria-hidden="true"></span> {$category.title}</button>
            </h3>
{else}
            <h3>{$category.title}</h3>
{/if}
{/if}

            <div id="el{$category@index}" class="config_optiongroup{if isset($config_groupkeys) AND $config_groupkeys@last} config_optiongroup_last{/if} option_list">
                <legend class="visuallyhidden">{$category.description}</legend>
{foreach $category.items AS $item}{cycle assign='zebra_class' values='odd,even'}{if isset($item.guessedInput) AND $item.guessedInput}
{if $item.type == 'bool'}

                <fieldset class="clearfix {$zebra_class}{if $item.description != ''} has_info{/if}">
                    <span class="wrap_legend"><legend>{$item.title}{if $item.description != ''} <button class="toggle_info button_link" type="button" data-href="#{$item.var}_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>{/if}</legend></span>
{if $item.description != ''}
                    <span id="{$item.var}_info" class="field_info additional_info">{$item.description}</span>
{/if}

                    <div class="clearfix grouped">
{* indent 0 is indent 1 for guessedInput returns !! *}
{$item.guessedInput}
                    </div>
                </fieldset>
{else}

                <div class="clearfix {$zebra_class} form_{if $item.type == 'list'}select{elseif $item.type == 'multilist'}multiselect{elseif $item.type == 'textarea'}area{else}field{/if}{if $item.description != ''} has_info{/if}">
                    <label for="{$item.var}">{$item.title}{if $item.description != ''} <button class="toggle_info button_link" type="button" data-href="#{$item.var}_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>{/if}</label>
{if $item.description != ''}
                    <span id="{$item.var}_info" class="field_info additional_info">{$item.description}</span>
{/if}
{* indent 0 is indent 1 for guessedInput returns !! *}
{$item.guessedInput}
                </div>
{/if}
{/if}
{/foreach}
            </div>
        </div>
{/foreach}

    </div>
{if NOT $noForm}

    <div class="form_buttons">
        <input type="submit" value="{$CONST.CHECK_N_SAVE}">
    </div>
</form>
{/if}
