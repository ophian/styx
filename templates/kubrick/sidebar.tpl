<ul>
{foreach $plugindata AS $item}
{if NOT empty($item.content)}
    <li>
        {if $item.title != ""}<h2>{$item.title}</h2>{/if}
        {$item.content}
    </li>
{/if}
{/foreach}
</ul>
