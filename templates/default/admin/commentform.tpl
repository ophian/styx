{if $smarty.get.serendipity.adminAction == 'edit'}
<h2>{$CONST.EDIT_THIS_CAT|sprintf:"{$CONST.COMMENT} #`$smarty.get.serendipity.id`"|replace:'"':''}</h2>
{/if}

<div id="serendipityCommentFormC" class="serendipity_commentForm{if isset($commentform_changeReplyTo)} reassign{/if}">
    <div id="serendipity_replyform_0"></div>
    <form id="serendipity_comment" action="{$commentform_action}#feedback" method="post">
        <div>
            <input type="hidden" name="serendipity[comment_id]" value="{$smarty.get.serendipity.id}">
            <input type="hidden" name="serendipity[entry_id]" value="{$commentform_id}">
            <input type="hidden" name="serendipity[replyTo]" value="{$commentform_replyTo}">
            <input type="hidden" name="serendipity[status]" value="{$commentform_status}">
            <input type="hidden" name="serendipity[subscribed]" value="{$commentform_subscribed}">
        </div>
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
{if isset($commentform_changeReplyTo)}

        <div class="form_select">
            <label for="serendipity_commentform_replyToParent">{$CONST.IN_REPLY_TO} {$CONST.COMMENT} ID</label>
            <select id="serendipity_commentform_replyToParent" name="serendipity[commentform][replyToParent]">
{foreach $commentform_changeReplyTo AS $copa}
{if empty($copa.id)}
                <option value="{$copa}"{if $commentform_replyTo == $copa} selected="selected"{/if}> {$CONST.TOP_LEVEL}</option>
{else}
                <option value="{$copa.id}"{if $commentform_replyTo == $copa.id} selected="selected"{/if}>{$copa.name} #c{$copa.id}</option>
{/if}
{/foreach}
            </select>
            <button class="toggle_info button_link" type="button" data-href="#copa_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> {$CONST.MORE}</span></button>
            <span id="copa_info" class="comment_status additional_info"><em>{$CONST.COMMENT_CHANGE_PARENT_INFO}</em></span>
        </div>
{/if}

        <div class="form_tarea">
            <label for="serendipity_commentform_comment">{$CONST.COMMENT}</label>
            <textarea id="serendipity_commentform_comment" data-tarea="serendipity_commentform_comment" name="serendipity[comment]" rows="10">{$commentform_data}</textarea>
        </div>
        {if $comment_wysiwyg}{$secure_simple_rteditor}{/if}

        {serendipity_hookPlugin hook="frontend_comment"}
{* We do not need any commentform data (array), since we do not have or even need any - this is a hook for s9ymarkup/spamblock/emoticonchooser and alike plugins. *}
        <div class="clearfix empty">&nbsp;</div>

        <div class="form_button">
            <input id="serendipity_preview" class="entry_preview comment_preview" name="serendipity[preview]" type="submit" value="{$CONST.PREVIEW}">
            <input id="serendipity_submit" name="serendipity[submit]" type="submit" value="{$CONST.SUBMIT_COMMENT}">
        </div>
    </form>
</div>
{if $comment_wysiwyg}

{if NOT empty($comments)}{* comment preview only: HIGHLIGHT.JS code parts *}
{if $darkmode}
<link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}templates/_assets/highlight/github-dark.min.css">
{else}
<link rel="stylesheet" type="text/css" href="{$serendipityHTTPPath}templates/_assets/highlight/github.min.css">
{/if}
<script src="{$serendipityHTTPPath}templates/_assets/highlight/highlight.min.js" data-manual></script>
<script>
  const elements = document.querySelectorAll("pre");
  elements.forEach(item => {
    // Replace matching unknown enabled highlight class names
    item.classList.replace("language-smarty", "language-perl"); /* perl better than php else you may get unescaped HTM errors from highlightjs */
    item.classList.replace("language-log", "language-yaml"); /* -bash is good also */
    item.classList.replace("language-markup", "language-plaintext");
  })
</script>
<script>
    // launch the code snippets highlight
    hljs.configure({
      tabReplace: '    ', // 4 spaces
    });
    hljs.highlightAll();
</script>
{else}
<script src="{$serendipityHTTPPath}templates/_assets/prism/prism.js" data-manual></script>
{/if}
<script src="{$serendipityHTTPPath}templates/_assets/tinymce6/js/tinymce/tinymce.min.js"></script>
{/if}
