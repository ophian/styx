<?php

/**
 *  @version
 *  @author Tadashi Jokagi <elf2000@users.sourceforge.net>
 *  EN-Revision: 690
 */

@define('PLUGIN_EVENT_SPARTACUS_NAME', 'Spartacus');
@define('PLUGIN_EVENT_SPARTACUS_DESC', '[S]erendipity [P]lugin [A]ccess [R]epository [T]ool [A]nd [C]ustomization/[U]nification [S]ystem - オンラインリポジトリからプラグインのダウンロードを可能にします。');
@define('PLUGIN_EVENT_SPARTACUS_FETCH', '新しい%sを Serendipity オンラインリポジトリから取得する');
@define('PLUGIN_EVENT_SPARTACUS_FETCHERROR', 'URL %s にアクセスできませんでした。おそらく github か mirror のサーバーがダウンしています - すみませんが、あとで再度試してください。 Try to reload (F5) the page first.');
@define('PLUGIN_EVENT_SPARTACUS_FETCHING', 'URL %s にアクセスを試みます...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_URL', 'Fetched %s bytes from the URL above. Saving file as %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_BYTES_CACHE', 'Fetched %s bytes from already existing file on your server. Saving file as %s...');
@define('PLUGIN_EVENT_SPARTACUS_FETCHED_DONE', 'データの取得に成功しました。');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_XML', 'ファイル/ミラーの場所 (XML メタデータ)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_FILES', 'ファイル/ミラーの場所 (ファイル)');
@define('PLUGIN_EVENT_SPARTACUS_MIRROR_DESC', 'Choose a download location. Do not change this value unless you know what you are doing and if servers get outdated. This option is available mainly for forward compatibility.');
@define('PLUGIN_EVENT_SPARTACUS_CHOWN', 'Owner of downloaded files');
@define('PLUGIN_EVENT_SPARTACUS_CHOWN_DESC', 'Here you can enter the (FTP/Shell) owner (like "nobody") of files downloaded by Spartacus. If empty, no changes are made to the ownership.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD', 'Permissions downloaded files');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DESC', 'Here you can enter the octal mode (like "0777") of the file permissions for files (FTP/Shell) downloaded by Spartacus. If empty, the default permission mask of the system are used. Note that not all servers allow changing/setting permissions. Pay attention that the applied permissions allow reading and writing for the webserver user. Else spartacus/Serendipity cannot overwrite existing files.');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR', 'Permissions downloaded directories');
@define('PLUGIN_EVENT_SPARTACUS_CHMOD_DIR_DESC', 'Here you can enter the octal mode (like "0777") of the directory permissions for directories (FTP/Shell) downloaded by Spartacus. If empty, the default permission mask of the system are used. Note that not all servers allow changing/setting permissions. Pay attention that the applied permissions allow reading and writing for the webserver user. Else spartacus/Serendipity cannot overwrite existing directories.');

