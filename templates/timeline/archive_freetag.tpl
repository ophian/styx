{if NOT empty($tags)}
    <h3>{$CONST.EDITOR_TAGS}</h3>
    <div class="timeline_freeTag">
        {foreach $tags AS $tag_name => $plugin_tags}
            <a href="{$plugin_tags.href}">{$tag_name}</a>
        {/foreach}
    </div>
{else}
    <p class="alert alert-warning"><span class="fa-stack" aria-hidden="true"><i class="far fa-circle fa-stack-2x"></i><i class="fas fa-exclamation fa-stack-1x"></i></span> {$CONST.TAGS_ON_ARCHIVE_DESC}</p>
{/if}