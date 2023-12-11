<h2>{$CONST.MEDIA_PROP}</h2>

{if $media.is_edit}
<form id="mediaPropertyForm" action="?{if isset($smarty.get.serendipity.page)}serendipity[page]={$smarty.get.serendipity.page}{/if}" method="POST">
    {$media.token}
    <input name="serendipity[action]" type="hidden" value="admin">
    <input name="serendipity[adminModule]" type="hidden" value="images">
    <input name="serendipity[adminAction]" type="hidden" value="changeProperties">
    <input name="serendipity[adminSubAction]" type="hidden" value="properties">
    {$media.editform_hidden}
{/if}
{$MEDIA_ITEMS}
{if $media.is_edit}
    <div class="form_buttons">
{if NOT $media.case_add}
        <a class="button_link" href="?serendipity[adminModule]=media">{$CONST.BACK}</a>
{/if}
        <input name="submit" type="submit" value="{$CONST.SAVE}">
    </div>
</form>

{/if}
