{if $is_logged_in AND $comment_wysiwyg}
<script>
if (!window.CKEDITOR) {
    document.write('<script src="{$serendipityHTTPPath|replace:'/':'\/'}htmlarea\/ckeditor\/ckeditor.js"><\/script>');
}
</script>
{/if}

{if $smarty.get.serendipity.adminAction == 'edit'}
<h2>{$CONST.EDIT_THIS_CAT|sprintf:"{$CONST.COMMENT} #`$smarty.get.serendipity.id`"|replace:'"':''}</h2>
{/if}
<div id="serendipityCommentFormC" class="serendipityCommentForm">
    <div id="serendipity_replyform_0"></div>
    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <div><input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}"></div>
        <div class="form_field">
            <label for="serendipity_commentform_name">{$CONST.NAME}</label>
            <input id="serendipity_commentform_name" name="serendipity[name]" type="text" value="{$commentform_name}">
        </div>
        <div class="form_field">
            <label for="serendipity_commentform_email">{$CONST.EMAIL}</label>
            <input id="serendipity_commentform_email" name="serendipity[email]" type="email" value="{$commentform_email}">
        </div>
        <div class="form_field">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
            <input id="serendipity_commentform_url" name="serendipity[url]" type="url" value="{$commentform_url}">
        </div>
        <div class="form_tarea">
            <label for="serendipity_backend_commentform_comment">{$CONST.COMMENT}</label>
            <textarea id="serendipity_backend_commentform_comment" data-tarea="serendipity_backend_commentform_comment" name="serendipity[comment]" rows="10">{$commentform_data}</textarea>
        </div>
        {if $is_logged_in AND $comment_wysiwyg}
        {* no 'Smiley', nor 'link' and no 'img' toolbar buttons, since any possible external or exploitable is removed and not allowed *}
        {* check if CKE plus is installed and active, else we need to load cores CKE lib, see file start *}
        <script>
            window.onload = function() {
                var plugIN = (typeof CKECONFIG_CODE_ON === 'undefined' || !CKECONFIG_CODE_ON) ? '' : 'codesnippet';
                CKEDITOR.replace( 'serendipity_backend_commentform_comment',
                {
                    toolbar : [['Undo', 'Redo'],['Format'],['Bold','Italic','Underline','Strike','Superscript','TextColor','-','NumberedList','BulletedList','Outdent','Blockquote'],['JustifyBlock','JustifyCenter'],['SpecialChar'],['Maximize'],['CodeSnippet'],['Source']],
                    toolbarGroups: null,
                    entities: false,
                    htmlEncodeOutput: false,
                    extraAllowedContent: 'div(*);p(*);ul(*);pre;code{*}(*)',
                    extraPlugins: plugIN
                });
            }
        </script>
        {/if}
        <div class="form_field">
            <label id="reply-to-hint" for="serendipity_replyTo">{$CONST.IN_REPLY_TO}</label>
            {$commentform_replyTo}
        </div>
        <div class="clearfix empty">&nbsp;</div>

        <div class="form_button">
            <input id="serendipity_preview" class="entry_preview" name="serendipity[preview]" type="submit" value="{$CONST.PREVIEW}">
            <input id="serendipity_submit" name="serendipity[submit]" type="submit" value="{$CONST.SUBMIT_COMMENT}">
        </div>
    </form>
</div>
