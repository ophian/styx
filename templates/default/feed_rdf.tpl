<?xml version="1.0" encoding="utf-8" ?>

<rdf:RDF {$namespace_display_dat}
    xmlns="http://purl.org/rss/1.0/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">
<channel>
    <title>{$metadata.title}</title>
    <link>{$metadata.link}</link>
    <description>{$metadata.description}</description>
    <dc:language>{$metadata.language}</dc:language>
{if $metadata.showMail}
    <admin:errorReportsTo rdf:resource="mailto:{$metadata.email}" />
{/if}

    {$metadata.additional_fields.image_rss10_channel}
    <items>
      <rdf:Seq>
{foreach $entries AS $entry}
        <rdf:li resource="{serendipity_rss_getguid entry=$entry is_comments=$is_comments}" />
{/foreach}
      </rdf:Seq>
    </items>
</channel>

{$metadata.additional_fields.image_rss10_rdf}
{$once_display_dat}

{foreach $entries AS $entry}
<item rdf:about="{$entry.feed_guid}">
    <title>{$entry.feed_title}</title>
    <link>{$entry.feed_entryLink}{if $is_comments}#c{$entry.commentid}{/if}</link>
{if NOT empty($entry.body)}
    <description>
    {$entry.feed_body|escape} {$entry.feed_ext|escape}
    </description>
{/if}

    <dc:publisher>{$entry.feed_blogTitle}</dc:publisher>
    <dc:creator>{$entry.feed_email} ({$entry.feed_author})</dc:creator>
    <dc:subject>
    {foreach $entry.categories AS $cat}{$cat.feed_category_name}, {/foreach}</dc:subject>
    <dc:date>{$entry.feed_timestamp}</dc:date>
    {$entry.per_entry_display_dat}
</item>
{/foreach}

</rdf:RDF>

