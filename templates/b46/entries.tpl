{serendipity_hookPlugin hook="entries_header" addData="$entry_id"}
{if NOT empty($entries)}{* catch a staticpage startpage which has no $entries array set *}
{foreach $entries AS $dategroup}
{foreach $dategroup.entries AS $entry}
{if $is_single_entry AND $view == 'entry'}{assign var="entry" value=$entry scope="root"}{* See scoping issue(s) for comment "_self" - $entry array relates in trackbacks - and index.tpl Rich Text Editor asset includes *}{/if}

    <article class="post{if $is_single_entry} post_single{/if}{if $dategroup.is_sticky} post_sticky{/if}{if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview} col-sm-6 col-lg-4{/if} mb-4">
      {if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.hugo == 0}<div class="col d-flex flex-column position-static">{* START HOUSE OF CARDS *}{/if}

        <header>
            {if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.hugo == 0}<h2 class="text-truncate" title="{$entry.title}">{$entry.title}{else}<h2><a href="{$entry.link}">{$entry.title}</a>{/if}</h2>

            <ul class="post_byline plainList">
                <li class="d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-person-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="title">{$CONST.POSTED_BY}</title>
                      <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    <a href="{$entry.link_author}">{$entry.author}</a>
                </li>
                <li class="d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-calendar3" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="title">{$CONST.ON}</title>
                      <path fill-rule="evenodd" d="M14 0H2a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 3.857C1 3.384 1.448 3 2 3h12c.552 0 1 .384 1 .857v10.286c0 .473-.448.857-1 .857H2c-.552 0-1-.384-1-.857V3.857z"/>
                      <path fill-rule="evenodd" d="M6.5 7a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm-9 3a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm3 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    <time datetime="{$entry.timestamp|serendipity_html5time}">{$entry.timestamp|formatTime:$template_option.date_format}</time>
                </li>
            </ul>
        </header>

        <div class="post_content clearfix{if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.hugo == 0} row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative{/if}">
        {if NOT empty($entry.categories) AND $template_option.hugo == 0 AND $template_option.card == 0}{foreach $entry.categories AS $entry_category}{if $entry_category.category_icon}<a href="{$entry_category.category_link}"><img class="serendipity_entryIcon" title="{$entry_category.category_name|escape}{$entry_category.category_description|emptyPrefix}" alt="{$entry_category.category_name|escape}" src="{$entry_category.category_icon|escape}"></a>{/if}{/foreach}{/if}
        {***** HUGONIZE *****}
        {if $template_option.hugo > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.card == 0}{assign "hugo" value=$entry.body|strip_tags|truncate:$template_option.hugo:''}

          <details>
            <summary title="{$CONST.B46_HUGO_TTT}"><div class="post_summary">{if isset($hugo) AND $hugo|count_characters !== 0}{$hugo}{else if $entry.has_extended}{$entry.extended|strip_tags|truncate:$template_option.hugo:''}{else}{$CONST.B46_HUGO_TITLE_ELSE}{/if}&hellip;</div></summary>
            <div class="post_details">{$entry.body}
            {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended}
            <a class="post_more btn btn-secondary btn-sm d-inline-block mb-3" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>
            {/if}</div>
          </details>

        </div>{* /.post_content hugo end *}

        {else if $template_option.card == 0 OR $is_single_entry OR $is_preview}{$entry.body}{/if}
        {if $entry.has_extended AND NOT $is_single_entry AND NOT $entry.is_extended AND $template_option.hugo == 0 AND $template_option.card == 0}
        <a class="post_more btn btn-secondary btn-sm d-inline-block mb-3" href="{$entry.link}#extended">{$CONST.VIEW_EXTENDED_ENTRY|sprintf:$entry.title}</a>
        {/if}{if ($template_option.hugo == 0 AND $template_option.card == 0) OR $is_single_entry OR $is_preview}</div><!-- /.post_content entry end -->
        {if $entry.is_extended}

        <div id="extended" class="post_content clearfix">
        {$entry.extended}
        </div>
        {/if}{/if}{* HUGO END *}
        {***** HOUSE OF CARDS *****}
        {if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.hugo == 0}{assign "card" value=$entry.body|strip_tags|truncate:$template_option.card:''}

          <div class="card-body">
            <p class="card-text post_summary mb-0">{if isset($card) AND $card|count_characters !== 0}{$card}{else if $entry.has_extended}{$entry.extended|strip_tags|truncate:$template_option.card:''}{else}{$CONST.B46_CARD_TITLE_ELSE}{/if}&hellip;</p>
            <div class="text-sm-right"><a href="{$entry.link}" class="btn btn-secondary btn-sm stretched-link">{$CONST.MORE}</a></div>
          </div>

        </div>{* /.post_content card end *}

        {/if}
{if NOT $is_preview}

        <footer class="post_info">
        {if NOT empty($entry.categories) OR $entry.has_comments}

            <ul class="post_meta plainList">
            {if NOT empty($entry.categories)}
                <li class="post_category d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tag-fill" role="img" fill="#dc3545" xmlns="http://www.w3.org/2000/svg">
                      <title id="bitcat">{$CONST.CATEGORIES}</title>
                      <path fill-rule="evenodd" d="M2 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 6.586 1H2zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                    </svg>
                    {foreach $entry.categories AS $entry_category}<a class="post_category" href="{$entry_category.category_link}">{$entry_category.category_name|escape}</a>{if NOT $entry_category@last}, {/if}{/foreach}
                {if $entry.has_comments OR NOT empty($entry.freetag.tags)}
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-horizontal" role="img" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                      <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                {/if}
                </li>
            {/if}
            {if $entry.has_comments}
                <li class="post_comments d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chat-right-dots-fill" role="img" fill="#17a2b8" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bitcom">{$entry.label_comments}</title>
                      <path fill-rule="evenodd" d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353V2zM5 6a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    </svg>
                    <a href="{$entry.link}#comments" title="{$entry.comments} {$entry.label_comments}{if $entry.has_trackbacks}, {$entry.trackbacks} {$entry.label_trackbacks}{/if}">{$entry.comments}</a>
                    {if $entry.allow_comments}
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-horizontal" role="img" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                      <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <a class="post_toform" href="{$entry.link}#reply"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="toform">{$CONST.ADD_COMMENT}</title>
                      <path d="M6.354 5.5H4a3 3 0 0 0 0 6h3a3 3 0 0 0 2.83-4H9c-.086 0-.17.01-.25.031A2 2 0 0 1 7 10.5H4a2 2 0 1 1 0-4h1.535c.218-.376.495-.714.82-1z"/>
                      <path d="M9 5.5a3 3 0 0 0-2.83 4h1.098A2 2 0 0 1 9 6.5h3a2 2 0 1 1 0 4h-1.535a4.02 4.02 0 0 1-.82 1H12a3 3 0 1 0 0-6H9z"/>
                    </svg></a>{/if}
                    {if NOT empty($entry.freetag.tags.tags) OR (NOT empty($entry.is_entry_owner) AND NOT $is_preview AND empty($entry.freetag.tags.tags))}
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-horizontal" role="img" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                      <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>{/if}
                </li>
            {/if}
            {if NOT empty($entry.freetag.tags.tags)}
                <li class="post_tags d-inline-block">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-tags-fill" role="img" fill="#20c997" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
                      <title id="bittag">Free Tags</title>
                      <path fill-rule="evenodd" d="M3 1a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l4.586-4.586a1 1 0 0 0 0-1.414l-7-7A1 1 0 0 0 7.586 1H3zm4 3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                      <path d="M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1v5.086z"/>
                    </svg>
                    {if NOT $is_preview}{foreach $entry.freetag.tags.tags AS $tag}{$tag}{if NOT $tag@last}, {/if}{/foreach}{/if}
                {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-grip-horizontal" role="img" fill="#343a40" xmlns="http://www.w3.org/2000/svg">
                      <path d="M7 2a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 5a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zM7 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm-3 3a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                {/if}
                </li>
            {/if}
            {if NOT empty($entry.is_entry_owner) AND NOT $is_preview}
                <li class="post_admin d-inline-block text-editicon editentrylink btn btn-admin btn-sm">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="bitmod">{$CONST.EDIT_ENTRY}</title>
                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                    </svg>
                    <a href="{$entry.link_edit}">{$CONST.EDIT}</a>
                </li>
            {/if}

            </ul>
        {/if}
            {$entry.add_footer|default:''}{* disable to stop adding tags *}

        </footer>
        <!--
        <rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                 xmlns:trackback="http://madskills.com/public/xml/rss/module/trackback/"
                 xmlns:dc="http://purl.org/dc/elements/1.1/">
        <rdf:Description
                 rdf:about="{$entry.link_rdf}"
                 trackback:ping="{$entry.link_trackback}"
                 dc:title="{$entry.title_rdf|default:$entry.title}"
                 dc:identifier="{$entry.rdf_ident}" />
        </rdf:RDF>
        -->
        {$entry.plugin_display_dat}
{/if}{* footer!preview end *}
{if $is_single_entry AND NOT $is_preview}
    {if $CONST.DATA_UNSUBSCRIBED}
        <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.DATA_UNSUBSCRIBED|sprintf:$CONST.UNSUBSCRIBE_OK}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_DELETED}
        <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.DATA_TRACKBACK_DELETED|sprintf:$CONST.TRACKBACK_DELETED}</p>
    {/if}
    {if $CONST.DATA_TRACKBACK_APPROVED}
        <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.DATA_TRACKBACK_APPROVED|sprintf:$CONST.TRACKBACK_APPROVED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_DELETED}
        <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.DATA_COMMENT_DELETED|sprintf:$CONST.COMMENT_DELETED}</p>
    {/if}
    {if $CONST.DATA_COMMENT_APPROVED}
        <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.DATA_COMMENT_APPROVED|sprintf:$CONST.COMMENT_APPROVED}</p>
    {/if}
    {if $entry.trackbacks > 0}

    <section id="trackbacks">
        <h3>{$CONST.TRACKBACKS}</h3>

        <span class="d-block mb-2">
            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-link-45deg" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg" aria-labelledby="title">
              <title id="title">{$CONST.TRACKBACK_SPECIFIC}</title>
              <path d="M4.715 6.542L3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.001 1.001 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
              <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 0 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 0 0-4.243-4.243L6.586 4.672z"/>
            </svg>
            <a id="trackback_url" rel="nofollow" href="{$entry.link_trackback}" onclick="alert('{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;'); return false;" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK} URL</a>
        </span>

        {serendipity_printTrackbacks entry=$entry.id}
    </section>
    {else}

    <a id="trackback_url" rel="nofollow" href="{$entry.link_trackback}" title="{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;{$entry.rdf_ident|escape}&laquo;">{$CONST.TRACKBACK_SPECIFIC}</a>
    <p class="alert alert-info alert-trackback" role="alert">{$CONST.TRACKBACK_SPECIFIC_ON_CLICK|escape} &raquo;<u>{$entry.rdf_ident|escape}</u>&laquo;</p>
    {/if}

    <section id="comments">
        <h3>{$CONST.COMMENTS}</h3>

        {serendipity_printComments entry=$entry.id mode=$entry.viewmode}
    {if NOT empty($entry.is_entry_owner)}
        {if $entry.allow_comments}
        <a class="comments-enable" href="{$entry.link_deny_comments}">{$CONST.COMMENTS_DISABLE}</a>
        {else}
        <a class="comments-enable" href="{$entry.link_allow_comments}">{$CONST.COMMENTS_ENABLE}</a>
        {/if}
    {/if}
    </section>

    <a id="feedback"></a>
    {foreach $comments_messagestack AS $message}
    <p class="alert alert-danger" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$message}</p>
    {/foreach}
    {if $is_comment_added}
    <p class="alert alert-success" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-check-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/></svg> {$CONST.COMMENT_ADDED|sprintf:"<a href=\"{if $is_logged_in}$commentform_action}{/if}#c{$smarty.get.last_insert_cid}\">#{$smarty.get.last_insert_cid}</a> "}</p>
    {if $is_logged_in}
    <section id="reply" class="clearfix">
        <h3>{$CONST.ADD_COMMENT}</h3>
        {$COMMENTFORM}
    </section>
    {/if}
    {elseif $is_comment_moderate}
    <p class="alert alert-secondary" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$CONST.COMMENT_ADDED|sprintf:''} {$CONST.THIS_COMMENT_NEEDS_REVIEW}</p>
    {elseif NOT $entry.allow_comments}
    <p class="alert alert-secondary" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-exclamation-circle-fill" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg> {$CONST.COMMENTS_CLOSED}</p>
    {else}
    <section id="reply" class="clearfix">
        <h3>{$CONST.ADD_COMMENT}</h3>
        {$COMMENTFORM}
    </section>
    {/if}
{/if}
    {$entry.backend_preview}
      {if $template_option.card > 0 AND NOT $is_single_entry AND NOT $is_preview AND $template_option.hugo == 0}</div>{* END HOUSE OF CARDS *}{/if}

    </article>
    {/foreach}

{/foreach}
{else}
    {if NOT $plugin_clean_page AND $view != '404'}
    <p class="alert alert-info alert-404" role="alert"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle-fill" role="img" fill="#17a2b8" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM8 5.5a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/></svg> {$CONST.NO_ENTRIES_TO_PRINT}</p>
    {/if}
{/if}
{if NOT $is_single_entry AND NOT $is_preview AND NOT $plugin_clean_page AND (NOT empty($footer_prev_page) OR NOT empty($footer_next_page))}

    <nav{if $template_option.card > 0 AND NOT $is_single_entry AND $template_option.hugo == 0} class="col-sm-12"{/if} aria-label="{$footer_info|default:''}" title="{$footer_info|default:''}">
        <ul class="entries_pagination pagination justify-content-between">
            <li class="page-item prev{if empty($footer_prev_page)} disabled{/if}">
            {if $footer_prev_page}
                <a class="page-link" href="{$footer_prev_page}">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-left" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="title">{$CONST.PREVIOUS_PAGE}</title>
                      <path fill-rule="evenodd" d="M8.354 1.646a.5.5 0 0 1 0 .708L2.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                      <path fill-rule="evenodd" d="M12.354 1.646a.5.5 0 0 1 0 .708L6.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"/>
                    </svg>
                    <span class="sr-only">{$CONST.PREVIOUS_PAGE}</span>
                </a>
            {/if}
            </li>
            <li class="page-item info{if empty($footer_info)} disabled{/if}">{$footer_info}</li>
            <li class="page-item next{if empty($footer_next_page)} disabled{/if}">
            {if $footer_next_page}
                <a class="page-link" href="{$footer_next_page}">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-double-right" role="img" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                      <title id="title">{$CONST.NEXT_PAGE}</title>
                      <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
                      <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                    <span class="sr-only">{$CONST.NEXT_PAGE}</span>
                </a>
            {/if}
            </li>
        </ul>
    </nav>
{/if}
{serendipity_hookPlugin hook="entries_footer"}
