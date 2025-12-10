{* CONDITIONAL SUBSCRIPTION SETS FOR THE EDIT FORM - class selector, checkbox, icons and spacebox -

We have three different states to check for: It always is a comment entry 'edit' and 'doEdit' ONLY case which
 a. either has a commentform_changeReplyTo selectbox or
 b. is the first and only comment of an entry or
 c. is true subscribed already.
This shall set the above "reassign" selector and the commentspacebox for paired placement of form_fields for correct subscripton checkbox and icon placement.
Virgin, un-approved comment edits will NOT show any subscription or reassign editing, so we have a "tri-state" 33% form field order such as in answer to comment "preview".

*}
{if (NOT isset($comments[0]) AND ($commentform_status == 'approved' AND $commentform_subscribed === 'false'))
 OR
    ($commentform_status == 'approved' AND $commentform_subscribed === 'false')
 OR
    $commentform_subscribed === 'true'}{assign var='reassign' value=true}{/if}
{if $smarty.get.serendipity.adminAction == 'edit'}
<h2>{$CONST.EDIT_THIS_CAT|sprintf:"{$CONST.COMMENT} #`$smarty.get.serendipity.id`"|replace:'"':''}</h2>
{/if}

<div id="serendipityCommentFormC" class="serendipity_commentForm{if NOT empty($reassign)} reassign{/if}">
    <div id="serendipity_replyform_0"></div>
    <form id="serendipity_comment" action="{$commentform_action}" method="post">
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
{if (isset($commentform_changeReplyTo) OR $commentform_replyTo == 0) OR (NOT isset($comments[0]) AND empty($commentform_data))}
{if NOT empty($commentform_email) AND $commentform_status == 'approved'}
            <input class="serendipity_commentform_ckbx_subscribe" name="serendipity[subscribe]" type="checkbox" title="{$CONST.SUBSCRIPTION_TO_ENTRY}"{if isset($commentform_subscribed) AND $commentform_subscribed === 'true'} checked="checked"{/if}>
{/if}
{if isset($commentform_subscribed) AND $commentform_subscribed === 'true'}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
              <title>{$CONST.ACTIVE_COMMENT_SUBSCRIPTION}</title>
              <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m1.679-4.493-1.335 2.226a.75.75 0 0 1-1.174.144l-.774-.773a.5.5 0 0 1 .708-.708l.547.548 1.17-1.951a.5.5 0 1 1 .858.514M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
              <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
            </svg>
{else if (empty($commentform_subscribed) AND (!empty($comment.preview_editstatus) AND $commentform_status != 'approved')) OR (isset($commentform_subscribed) AND $commentform_subscribed === 'false')}
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-dash" viewBox="0 0 16 16">
              <title>{$CONST.NO_COMMENT_SUBSCRIPTION}</title>
              <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7M11 12h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1m0-7a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
              <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
            </svg>
{/if}
{/if}
        </div>
        <div class="form_field">
            <label for="serendipity_commentform_url">{$CONST.HOMEPAGE}</label>
            <input id="serendipity_commentform_url" name="serendipity[url]" type="url" value="{$commentform_url}">
        </div>
{if isset($commentform_changeReplyTo) AND (NOT isset($comments[0]) AND empty($commentform_data))}

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
{else}
{* draw a dummy block for reassigned spawned for paired fields with chbx_subscribe *}
{if NOT empty($reassign)}
        <div class="form_field commentspacebox"></div>
{/if}
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
