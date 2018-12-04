{foreach $comments_by_authors AS $entry_comments}

<article id="e{$entry_comments@key}" class="clearfix serendipity_entry">
    <h3><a href="{$entry_comments.link}">{$entry_comments.title|default:$entry_comments.link}</a></h3>
    <div class="comments_for_entry">
        {$entry_comments.tpl_comments}
    </div>
</article>
{/foreach}

{serendipity_hookPlugin hook="comments_by_author_footer" hookAll="true"}
