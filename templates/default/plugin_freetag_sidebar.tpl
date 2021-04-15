{* A simplified freetag sidebar template example *}
<ul class="plainList">
{foreach $tags AS $tag => $val}
    <li><a href="{$val.href}"> {$tag} ({$val.count})</a></li>
{/foreach}
</ul>