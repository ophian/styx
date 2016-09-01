{foreach $plugindata AS $item}
  <li id="{$item.class}">{$item.title}
    {$item.content}
  </li>
{/foreach}
