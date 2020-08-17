<style> .nasa_image img { width: 1.6rem; }</style>
{foreach $remoterss_items.items AS $item}

<div class="rss_item">
    <div class="nasa_caption"><strong>{$item.title}</strong></div>
    <div class="nasa_image"><a href="{$item.link}"><img width="195" src="{$remoterss_items.nasa_image.url}" /></a></div>
    <div class="nasa_desc"><em>{$item.description|default:''}</em></div>
</div>

{/foreach}
