{if $init == false}

<script src="{$serendipityHTTPPath}templates/_assets/ckebasic/ckeditor.js"></script>
<script src="{$serendipityHTTPPath}templates/_assets/ckebasic_plugin.js"></script>

{/if}

<script>
    $('document').ready(function() {
        CKEDITOR.plugins.add('styx_mediaLibrary_{$item}', {
            init: function( editor ) {
                editor.addCommand( 'openML', {
                    exec : function( editor ) {
                        serendipity.openPopup('serendipity_admin.php?serendipity[adminModule]=media&serendipity[noBanner]=true&serendipity[noSidebar]=true&serendipity[noFooter]=true&serendipity[showMediaToolbar]=false&serendipity[showUpload]=true&serendipity[textarea]={$item}');
                    }
                });
                editor.ui.addButton('styx_mediaLibrary_{$item}', {
                    label: '{$CONST.MEDIA_LIBRARY}',
                    command: 'openML',
                    icon: '{serendipity_getFile file="admin/img/thumbnail.png"}'
                });
                editor.addCommand( 'openMLG', {
                    exec : function( editor ) {
                        serendipity.openPopup('serendipity_admin.php?serendipity[adminModule]=media&serendipity[noBanner]=true&serendipity[noSidebar]=true&serendipity[noFooter]=true&serendipity[showMediaToolbar]=false&serendipity[showGallery]=true&serendipity[textarea]={$item}');
                    }
                });
                editor.ui.addButton('styx_mediaGallery_{$item}', {
                    label:    'StyxMediaGallery',
                    title:    'Styx Media Gallery',
                    icon: '{serendipity_getFile file="admin/img/mlgallery.png"}',
                    iconName: 'styxMLG_{$item}_icon',
                    command:  'openMLG'
                });
            }
        });

        styxmediabuttons.push('styx_mediaLibrary_{$item}');
        styxmediabuttons.push('styx_mediaGallery_{$item}');

        {foreach $buttons AS $button}

            CKEDITOR.plugins.add('{$button.id}', {
                init: function( editor ) {
                    editor.addCommand( '{$button.name}', {
                        exec : function( editor ) {
                            popupEditorInstance = editor;
                            ( {$button.javascript} () )
                        }
                    });
                    editor.ui.addButton('{$button.id}', {
                        label: '{$button.name}',
                        title: '{$button.name} Plugin',
                        command: '{$button.name}',
                        icon: '{$button.img_url}',
                        iconName: '{$button.id}_icon'
                    });
                }
            });

            styxpluginbuttons.push('{$button.id}');

        {/foreach}

        var styxplugins = styxcustomplugins.concat('styx_mediaLibrary_{$item}{foreach $buttons AS $button},{$button.id}{/foreach}');

        CKEDITOR.replace($('#'+serendipity.escapeBrackets('{$item}')).get(0), {
            extraPlugins : styxplugins,
            {if $use_autosave == 'true'}

            on: {
                instanceReady: function( evt ) {
                    if(Modernizr.indexeddb) {
                        CKEDITOR.instances["{$item}"].document.once('keyup', function() {
                            setInterval(function() {
                                serendipity.cache("{$item}", CKEDITOR.instances["{$item}"].getData());
                            }, 5000)
                        });
                    }
                }
            }
            {/if}

        });
    });
</script>
