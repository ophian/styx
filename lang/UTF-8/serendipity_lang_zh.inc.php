<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details
# Translated by
# (c) 2006 Aphonex Li <aphonex.li@gmail.com>
#          http://www.cexten.com
/* vim: set sts=4 ts=4 expandtab : */

@define('LANG_CHARSET', 'UTF-8');
@define('SQL_CHARSET', 'utf8');
@define('DATE_LOCALES', 'zh_CN.UTF-8, cn, zh, zh_GB, zh_CN');
@define('DATE_FORMAT_SHORT', '%Y-%m-%d %H:%M');
@define('WYSIWYG_LANG', 'zh_CN');
@define('NUMBER_FORMAT_DECIMALS', '2');
@define('NUMBER_FORMAT_DECPOINT', '.');
@define('NUMBER_FORMAT_THOUSANDS', ',');
@define('LANG_DIRECTION', 'ltr');

@define('SERENDIPITY_ADMIN_SUITE', 'Serendipity鏅鸿兘鍗氬绯荤粺'); // 鍚庡彴绠＄悊椤
@define('HAVE_TO_BE_LOGGED_ON', '璇峰厛鐧诲叆');
@define('WRONG_USERNAME_OR_PASSWORD', '杈撳叆鐨勫笎鍙锋垨瀵嗙爜鏈夎');
@define('APPEARANCE', '涓昏閰嶇疆');
@define('MANAGE_STYLES', '涓婚绠＄悊');
@define('CONFIGURE_PLUGINS', '璁惧畾澶栨寕');
@define('CONFIGURATION', '绠＄悊璁惧畾');
@define('BACK_TO_BLOG', '缃戠珯棣栭〉');
@define('LOGIN', '鐧诲叆');
@define('LOGOUT', '鐧诲嚭');
@define('LOGGEDOUT', '鐧诲嚭锛?);
@define('CREATE', '寤虹珛');
@define('SAVE', '淇濆瓨');
@define('NAME', '鍚嶇О');
@define('CREATE_NEW_CAT', '娣诲姞');
@define('I_WANT_THUMB', '鍦ㄦ枃绔犲唴浣跨敤缂╁浘.');
@define('I_WANT_BIG_IMAGE', '鍦ㄦ枃绔犲唴浣跨敤澶у瀷鍥剧墖.');
@define('I_WANT_NO_LINK', '浠ュ浘鐗囨樉绀?);
@define('I_WANT_IT_TO_LINK', '浠ヨ繛鎺ユ樉绀鸿繖涓綉鍧€:');
@define('BACK', '杩斿洖');
@define('FORWARD', '鍓嶈繘');
@define('ANONYMOUS', '鍖垮悕');
@define('NEW_TRACKBACK_TO', '鏂扮殑寮曠敤鍒?);
@define('NEW_COMMENT_TO', '鏂扮殑鍥炲鍒?);
@define('RECENT', '鏂版枃搴?..');
@define('OLDER', '鏃ф枃搴?..');
@define('DONE', '瀹屾垚');
@define('WELCOME_BACK', '闈炲父楂樺叴瑙佸埌浣?');
@define('TITLE', '鏍囬');
@define('DESCRIPTION', '绠€浠?);
@define('PLACEMENT', '鍦板潃');
@define('DELETE', '鍒犻櫎');
@define('SAVE', '淇濆瓨');
@define('UP', '涓?);
@define('DOWN', '涓?);
@define('ENTRIES', '鏂囩珷绠＄悊');
@define('NEW_ENTRY', '鏂板鏂囩珷');
@define('EDIT_ENTRIES', '缂栬緫鏂囩珷');
@define('CATEGORIES', '绫诲埆绠＄悊');
@define('WARNING_THIS_BLAHBLAH', "璀﹀憡:\\n濡傛灉鏈夊緢澶氬浘鐗囩殑璇濓紝鍙兘闇€瑕佸緢闀挎椂闂淬€?);
@define('CREATE_THUMBS', '閲嶅缓缁嗗浘');
@define('MANAGE_IMAGES', '绠＄悊鍥剧墖');
@define('NAME', '鍚嶇О');
@define('EMAIL', '鐢甸偖');
@define('HOMEPAGE', '缃戝潃');
@define('COMMENT', '鍥炲');
@define('REMEMBER_INFO', '璁板綍璧勬枡');
@define('SUBMIT_COMMENT', '鍙戦€佸洖澶?);
@define('NO_ENTRIES_TO_PRINT', '娌℃湁鏂囩珷');
@define('COMMENTS', '鍥炲'); // 棣栭〉
@define('ADD_COMMENT', '鏂板鍥炲');
@define('NO_COMMENTS', '娌℃湁鍥炲');
@define('POSTED_BY', '浣滆€?);
@define('ON', '鍦?);
@define('A_NEW_COMMENT_BLAHBLAH', '鏂板洖澶嶅凡鍙戣〃鍦ㄧ綉绔?"%s", 鍦ㄨ繖涓枃绔犻噷闈?"%s"銆?);
@define('A_NEW_TRACKBACK_BLAHBLAH', '浣犵殑鏂囩珷 "%s" 宸叉湁鏂扮殑寮曠敤銆?);
@define('NO_CATEGORY', '娌℃湁绫诲埆');
@define('ENTRY_BODY', '鏂囩珷涓诲唴瀹?);
@define('EXTENDED_BODY', '鏂囩珷鍓唴瀹?);
@define('CATEGORY', '鍏ㄩ儴绫诲埆'); // 棣栭〉
@define('EDIT', '缂栬緫');
@define('NO_ENTRIES_BLAHBLAH', '鎵句笉鍒版煡璇?%s 鐨勬枃绔? . "\n");
@define('YOUR_SEARCH_RETURNED_BLAHBLAH', '浣犳悳瀵荤殑 %s 鏄剧ず浜?%s 缁撴灉:');
@define('IMAGE', '鍥剧墖');
@define('ERROR_FILE_NOT_EXISTS', '閿欒: 鏂囦欢涓嶅瓨鍦紒');
@define('ERROR_FILE_EXISTS', '閿欒: 鏂囦欢鍚嶅凡琚娇鐢? 璇烽噸鏂拌緭鍏ワ紒');
@define('ERROR_SOMETHING', '閿欒锛?);
@define('ADDING_IMAGE', '鏂板鍥剧墖...');
@define('THUMB_CREATED_DONE', '缂╁浘寤虹珛锛?br>瀹屾垚锛?);
@define('ERROR_FILE_EXISTS_ALREADY', '閿欒: 鏂囦欢宸插瓨鍦?);
@define('ERROR_UNKNOWN_NOUPLOAD', '鍙戠敓閿欒, 鏂囦欢娌℃湁涓婁紶锛屽彲鑳藉洜涓轰綘鐨勬枃浠惰秴杩囬檺鍒剁殑澶у皬锛?璇疯闂綘鐨勪富鏈哄晢鎴栦慨鏀逛綘鐨?php.ini 鏂囦欢灞炴€с€?);
@define('GO', '缁х画');
@define('NEWSIZE', '澶у皬: ');
@define('RESIZE_BLAHBLAH', '<b>閲嶈澶у皬 %s</b>');
@define('ORIGINAL_SIZE', '鍘熸湁鐨勫ぇ灏? <i>%sx%s</i> 鍍忕礌');
@define('HERE_YOU_CAN_ENTER_BLAHBLAH', '<p>鍦ㄨ繖閲屼綘鍙互淇敼鍥剧墖澶у皬锛佸鏋滀綘瑕佷慨鏀规垚鐩稿悓鐨勫浘鐗囨瘮渚? 浣犲彧闇€瑕佽緭鍏ヤ竴涓暟鍊肩劧鍚庢寜 TAB -- 绯荤粺浼氳嚜鍔ㄥ府浣犺绠楁瘮渚嬩互鍏嶅嚭閿欍€?/p>');
@define('QUICKJUMP_CALENDAR', '鏃ュ巻蹇€熻烦璺?);
@define('QUICKSEARCH', '蹇€熸悳瀵?);
@define('SEARCH_FOR_ENTRY', '鎼滃鏂囩珷');
@define('ARCHIVES', '鏂囩珷褰掓。');
@define('BROWSE_ARCHIVES', '浠ユ湀浠戒繚瀛樻枃绔?);
@define('TOP_REFERRER', '涓昏鏉ユ簮');
@define('SHOWS_TOP_SITES', '鏄剧ず杩炴帴鍒颁綘鐨勭綉绔?);
@define('TOP_EXITS', '涓昏鍑烘簮');
@define('SHOWS_TOP_EXIT', '鏄剧ず缃戠珯鐨勪富瑕佸嚭婧?);
@define('SYNDICATION', '鏂囩珷鍚屾');
@define('SHOWS_RSS_BLAHBLAH', '鏄剧ず RSS 鍚屾杩炴帴');
@define('ADVERTISES_BLAHBLAH', '瀹ｄ紶浣犵殑鏃ュ織');
@define('HTML_NUGGET', 'HTML 淇℃伅');
@define('HOLDS_A_BLAHBLAH', '鏄剧ず HTML 璁伅鍒颁晶鍒?);
@define('TITLE_FOR_NUGGET', '淇℃伅鏍囬');
@define('THE_NUGGET', 'HTML 璁伅');
@define('SYNDICATE_THIS_BLOG', '闆嗗悎鑿滃崟');
@define('YOU_CHOSE', '浣犻€夋嫨 %s');
@define('IMAGE_SIZE', '鍥剧墖澶у皬');
@define('IMAGE_AS_A_LINK', '杈撳叆鍥剧墖');
@define('POWERED_BY', '缃戠珯淇℃伅');
@define('TRACKBACKS', '寮曠敤');
@define('TRACKBACK', '寮曠敤');
@define('NO_TRACKBACKS', '娌℃湁寮曠敤');
@define('TOPICS_OF', '涓婚');
@define('VIEW_FULL', '娴忚鍏ㄩ儴');
@define('VIEW_TOPICS', '娴忚涓婚');
@define('AT', '鏃堕棿');
@define('SET_AS_TEMPLATE', '浣跨敤涓婚');
@define('IN', '鍒嗙被');
@define('EXCERPT', '鎽樿');
@define('TRACKED', '寮曠敤');
@define('LINK_TO_ENTRY', '杩炴帴鍒版枃绔?);
@define('LINK_TO_REMOTE_ENTRY', '杩炴帴鍒拌繙绔枃绔?);
@define('IP_ADDRESS', 'IP 鍦板潃');
@define('USER', '浣滆€?);
@define('THUMBNAIL_USING_OWN', '浣跨敤 %s 褰撳畠鐨勭缉鍥惧昂瀵稿洜涓哄浘鐗囧凡缁忓緢灏忎簡銆?);
@define('THUMBNAIL_FAILED_COPY', '浣跨敤 %s 褰撳畠鐨勭缉鍥? 浣嗘槸鏃犳硶澶嶅埗锛?);
@define('AUTHOR', '鍙戣〃鑰?);
@define('LAST_UPDATED', '鏈€鍚庢洿鏂?);
@define('TRACKBACK_SPECIFIC', '寮曠敤姝ゆ枃绔犵壒瀹氱殑缃戝潃');
@define('DIRECT_LINK', '鐩存帴鐨勬枃绔犺繛鎺?);
@define('COMMENT_ADDED', '浣犵殑鍥炲宸叉垚鍔熸坊鍔?');
@define('COMMENT_ADDED_CLICK', '鐐?%s杩欓噷杩斿洖%s 鍒板洖澶? 鍜岀偣 %s杩欓噷鍏抽棴%s 杩欎釜瑙嗙獥銆?);
@define('COMMENT_NOT_ADDED_CLICK', '鐐?%s杩欓噷杩斿洖%s 鍒板洖澶? 鍜岀偣 %s杩欓噷鍏抽棴%s 杩欎釜瑙嗙獥銆?);
@define('COMMENTS_DISABLE', '涓嶅厑璁稿洖澶嶅埌杩欑瘒鏂囩珷');
@define('COMMENTS_ENABLE', '鍏佽鍥炲鍒拌繖绡囨枃绔?);
@define('COMMENTS_CLOSED', '浣滆€呬笉鍏佽鍥炲鍒拌繖绡囨枃绔?);
@define('EMPTY_COMMENT', '浣犵殑鍥炲娌℃湁浠讳綍淇℃伅, 璇?%s杩斿洖%s 閲嶈瘯');
@define('ENTRIES_FOR', '鏂囩珷缁?%s');
@define('DOCUMENT_NOT_FOUND', '鎵句笉鍒版绡囨枃浠?%s');
@define('USERNAME', '甯愬彿');
@define('PASSWORD', '瀵嗙爜');
@define('AUTOMATIC_LOGIN', '鑷姩鐧诲叆');
@define('SERENDIPITY_INSTALLATION', 'Serendipity 瀹夎绋嬪簭');
@define('LEFT', '宸?);
@define('RIGHT', '鍙?);
@define('HIDDEN', '闅愯棌');
@define('REMOVE_TICKED_PLUGINS', '绉婚櫎鍕鹃€夌殑澶栨寕');
@define('SAVE_CHANGES_TO_LAYOUT', '淇濆瓨涓婚閰嶇疆');
@define('COMMENTS_FROM', '鍥炲鏉ユ簮');
@define('ERROR', '閿欒');
@define('ENTRY_SAVED', '浣犵殑鏂囩珷宸蹭繚瀛?);
@define('DELETE_SURE', '纭畾瑕佸垹闄?#%s 鍚楋紵');
@define('NOT_REALLY', '閲嶆潵...');
@define('DUMP_IT', '鍒犻櫎');
@define('RIP_ENTRY', 'R.I.P. 鏂囩珷 #%s');
@define('CATEGORY_DELETED_ARTICLES_MOVED', '绫诲埆 #%s 宸插垹闄? 鏃ф枃绔犲凡琚Щ鍔ㄥ埌绫诲埆 #%s');
@define('CATEGORY_DELETED', '绫诲埆 #%s 宸插垹闄わ紒');
@define('INVALID_CATEGORY', '娌℃湁鎻愪緵鍒犻櫎鐨勭被鍒?);
@define('CATEGORY_SAVED', '绫诲埆宸蹭繚瀛?);
@define('SELECT_TEMPLATE', '璇烽€夋嫨鏃ュ織鐨勪富棰?);
@define('ENTRIES_NOT_SUCCESSFULLY_INSERTED', '娌℃湁瀹屾垚澧炲叆鏂囩珷锛?);
@define('MT_DATA_FILE', 'Movable Type 鏁版嵁');
@define('FORCE', '寮哄埗');
@define('CREATE_AUTHOR', '鏂板浣滆€?\'%s\'.');
@define('CREATE_CATEGORY', '鏂板绫诲埆 \'%s\'.');
@define('MYSQL_REQUIRED', '浣犲繀椤昏鏈?MySQL 鐨勬墿鍏呭姛鑳芥墠鑳芥墽琛岃繖涓姩浣?);
@define('COULDNT_CONNECT', '涓嶈兘杩炴帴鍒?MySQL 璧勬枡搴? %s.');
@define('COULDNT_SELECT_DB', '涓嶈兘閫夋嫨鏁版嵁搴? %s.');
@define('COULDNT_SELECT_USER_INFO', '涓嶈兘閫夋嫨浣跨敤鑰呯殑璧勬枡: %s.');
@define('COULDNT_SELECT_CATEGORY_INFO', '涓嶈兘閫夋嫨绫诲埆鐨勮祫鏂? %s.');
@define('COULDNT_SELECT_ENTRY_INFO', '涓嶈兘閫夋嫨鏂囩珷鐨勮祫鏂? %s.');
@define('COULDNT_SELECT_COMMENT_INFO', '涓嶈兘閫夋嫨鍥炲鐨勮祫鏂? %s.');
@define('YES', '鏄?);
@define('NO', '鍚?);
@define('USE_DEFAULT', '棰勮');
@define('CHECK_N_SAVE', '淇濆瓨');
@define('DIRECTORY_WRITE_ERROR', '涓嶈兘璇诲啓鏂囦欢澶?%s锛岃妫€鏌ユ潈闄愶紒');
@define('DIRECTORY_CREATE_ERROR', '鏂囦欢澶?%s 涓嶅瓨鍦ㄤ篃鏃犳硶寤虹珛锛岃鑷繁寤虹珛杩欎釜鏂囦欢澶癸紒');
@define('DIRECTORY_RUN_CMD', '&nbsp;-&gt; run <i>%s %s</i>');
@define('CANT_EXECUTE_BINARY', '鏃犳硶鎵ц %s 鏂囦欢锛?);
@define('FILE_WRITE_ERROR', '鏃犳硶璇诲啓鏂囦欢 %s锛?);
@define('FILE_CREATE_YOURSELF', '璇疯嚜宸卞缓绔嬭繖涓枃浠舵垨妫€鏌ユ潈闄?);
@define('COPY_CODE_BELOW', '<br />* 璇峰鍒朵笅闈㈢殑浠ｇ爜鐒跺悗鏀惧叆 %s 鍒颁綘鐨?%s 鏂囦欢澶?<b><pre>%s</pre></b>' . "\n");
@define('WWW_USER', '璇锋敼鍙?www 鍒颁娇鐢ㄨ€呯殑 Apache (i.e. nobody)锛?);
@define('BROWSER_RELOAD', '瀹屾垚涔嬪悗, 閲嶆柊鍒锋柊浣犵殑娴忚鍣?');
@define('DIAGNOSTIC_ERROR', '绯荤粺妫€娴嬪埌涓€浜涢敊璇?');
@define('SERENDIPITY_NOT_INSTALLED', 'Serendipity 杩樻病瀹夎瀹屾垚. 璇锋寜 <a href="%s">瀹夎</a>.');
@define('INCLUDE_ERROR', 'serendipity 閿欒: 鏃犳硶鍖呮嫭 %s - 閫€鍑猴紒');
@define('DATABASE_ERROR', 'serendipity 閿欒: 鏃犳硶杩炴帴鍒板簱 - 閫€鍑猴紒');
@define('CHECK_DATABASE_EXISTS', '妫€鏌ユ暟鎹簱鏄惁瀛樺湪銆?濡傛灉浣犵湅鍒版暟鎹簱鏌ヨ閿欒, 鏄惁闇€瑕侀噸瑁?..');
@define('CREATE_DATABASE', '寤虹珛棰勮鏁版嵁搴撳簱璁惧畾...');
@define('ATTEMPT_WRITE_FILE', '璇诲啓 %s 鏂囦欢...');
@define('SERENDIPITY_INSTALLED', 'Serendipity 绠€浣撲腑鏂囩増鏈?宸插畨瑁呭畬鎴愶紒%s 璇疯寰椾綘鐨勫瘑鐮? "%s", 浣犵殑甯愬彿鏄?"%s".%s浣犵幇鍦ㄥ彲浠ュ埌鏂板缓绔嬬殑 <a href="%s">缃戠粶鏃ヨ</a>');
@define('WRITTEN_N_SAVED', '淇濆瓨瀹屾瘯');
@define('IMAGE_ALIGNMENT', '鍥剧墖瀵归綈');
@define('ENTER_NEW_NAME', '杈撳叆鏂板悕绉? ');
@define('RESIZING', '閲嶈澶у皬');
@define('RESIZE_DONE', '瀹屾垚 (閲嶈 %s 涓浘鐗?');
@define('SYNCING', '杩涜鏁版嵁搴撳拰鍥剧墖鏂囦欢澶规暟鎹悓姝?);
@define('SYNC_OPTION_LEGEND', 'Thumbnail Synchronization Options');
@define('SYNC_OPTION_KEEPTHUMBS', 'Keep all existing thumbnails');
@define('SYNC_OPTION_SIZECHECKTHUMBS', 'Keep existing thumbnails only if they are the correct size');
@define('SYNC_OPTION_DELETETHUMBS', 'Regenerate all thumbnails');
@define('SYNC_DONE', '瀹屾垚 (鍚屾浜?%s 涓浘鐗?');
@define('FILE_NOT_FOUND', '鎵句笉鍒版枃浠?<b>%s</b>, 鍙兘宸茶鍒犻櫎');
@define('ABORT_NOW', '鏀惧純');
@define('REMOTE_FILE_NOT_FOUND', '鏂囦欢涓嶅湪杩滅▼涓绘満鍐? 浣犵‘瀹氳繖涓綉鍧€: <b>%s</b> 鏄纭殑銆?);
@define('FILE_FETCHED', '%s 鍙栧洖涓?%s');
@define('FILE_UPLOADED', '鏂囦欢 %s 涓婁紶涓?%s');
@define('WORD_OR', '鎴?);
@define('SCALING_IMAGE', '缂╂斁 %s 鍒?%s x %s 鍍忕礌');
@define('KEEP_PROPORTIONS', '缁存寔姣斾緥');
@define('REALLY_SCALE_IMAGE', '纭畾瑕佺缉鏀惧浘鐗囧悧? 杩欎釜鍔ㄤ綔涓嶈兘鎭㈠锛?);
@define('TOGGLE_ALL', '鍒囨崲灞曞紑');
@define('TOGGLE_OPTION', '鍒囨崲閫夐」');
@define('SUBSCRIBE_TO_THIS_ENTRY', '璁㈤槄杩欑瘒鏂囩珷');
@define('UNSUBSCRIBE_OK', "%s 宸插彇娑堣闃呰繖绡囨枃绔?);
@define('NEW_COMMENT_TO_SUBSCRIBED_ENTRY', '鏂板洖澶嶅埌璁㈤槄鐨勬枃绔?"%s"');
@define('SUBSCRIPTION_MAIL', "浣犲ソ %s,\n\n浣犺闃呯殑鏂囩珷鏈変簡鏂扮殑鍥炲鍦?\"%s\", 鏍囬鏄?\"%s\"\n鍥炲鐨勫彂琛ㄨ€呮槸: %s\n\n浣犲彲浠ュ湪杩欐壘鍒版鏂囩珷: %s\n\n浣犲彲浠ョ偣杩欎釜杩炴帴鍙栨秷璁㈤槄: %s\n");
@define('SUBSCRIPTION_TRACKBACK_MAIL', "浣犲ソ %s,\n\n浣犺闃呯殑鏂囩珷鏈変簡鏂扮殑寮曠敤鍦?\"%s\", 鏍囬鏄?\"%s\"\n寮曠敤鐨勪綔鑰呮槸: %s\n\n浣犲彲浠ュ湪杩欐壘鍒版鏂囩珷: %s\n\n浣犲彲浠ョ偣杩欎釜杩炴帴鍙栨秷璁㈤槄: %s\n");
@define('SIGNATURE', "\n-- \n%s is powered by Serendipity.\n <http://www.s9y.org>");
@define('SYNDICATION_PLUGIN_091', 'RSS 0.91 feed');
@define('SYNDICATION_PLUGIN_10', 'RSS 1.0 feed');
@define('SYNDICATION_PLUGIN_20', 'RSS 2.0 feed');
@define('SYNDICATION_PLUGIN_20c', 'RSS 2.0 comments');
@define('SYNDICATION_PLUGIN_ATOM03', 'ATOM 0.3 feed');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR', '鑿滃崟 "managingEditor"');
@define('SYNDICATION_PLUGIN_WEBMASTER',  '鑿滃崟 "webMaster"');
@define('SYNDICATION_PLUGIN_BANNERURL', 'RSS feed 鐨勫浘鐗?);
@define('SYNDICATION_PLUGIN_BANNERWIDTH', '鍥剧墖瀹藉害');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT', '鍥剧墖楂樺害');
@define('SYNDICATION_PLUGIN_WEBMASTER_DESC',  '绠＄悊鍛樼殑鐢靛瓙閭欢, 濡傛灉鏈夛細 (绌虹櫧: 闅愯棌) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_MANAGINGEDITOR_DESC', '浣滆€呯殑鐢靛瓙閭欢, 濡傛灉鏈夛細 (绌虹櫧: 闅愯棌) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_BANNERURL_DESC', '鍥剧墖鐨勪綅鍧€ URL, 浠?GIF/JPEG/PNG 鏍煎紡, 濡傛灉鏈夛細 (绌虹櫧: serendipity-logo)');
@define('SYNDICATION_PLUGIN_BANNERWIDTH_DESC', '鍍忕礌, 鏈€澶? 144');
@define('SYNDICATION_PLUGIN_BANNERHEIGHT_DESC', '鍍忕礌, 鏈€澶? 400');
@define('SYNDICATION_PLUGIN_TTL', '鑿滃崟 "ttl" (time-to-live)');
@define('SYNDICATION_PLUGIN_TTL_DESC', '鍦ㄥ嚑鍒嗛挓鍚庯紝鏂囩珷涓嶄細琚叾瀹冪殑缃戠珯鎴栫▼搴忚褰?(绌虹櫧: 闅愯棌) [RSS 2.0]');
@define('SYNDICATION_PLUGIN_PUBDATE', '鏍忎綅 "pubDate"');
@define('SYNDICATION_PLUGIN_PUBDATE_DESC', '"pubDate"-鑿滃崟闇€瑕佸唴宓屽埌RSS-棰戦亾, 浠ユ樉绀烘渶鍚庢枃绔犵殑鏃ユ湡鍚楋紵');
@define('CONTENT', '鍐呭');
@define('TYPE', '绫诲瀷');
@define('DRAFT', '鑽夌');
@define('PUBLISH', '鍏紑');
@define('PREVIEW', '棰勮');
@define('DATE', '鏃ユ湡');
@define('DATE_FORMAT_2', 'Y-m-d H:i'); // Needs to be ISO 8601 compliant for date conversion!
@define('DATE_INVALID', '璀﹀憡: 鎻愪緵鐨勬棩鏈熶笉姝ｇ‘. 瀹冨繀椤绘槸 YYYY-MM-DD HH:MM 鐨勬牸寮?);
@define('CATEGORY_PLUGIN_DESC', '鏄剧ず绫诲埆娓呭崟');
@define('ALL_AUTHORS', '鍏ㄩ儴浣滆€?);
@define('CATEGORIES_TO_FETCH', '鏄剧ず绫诲埆');
@define('CATEGORIES_TO_FETCH_DESC', '鏄剧ず鍝綅浣滆€呯殑绫诲埆锛?);
@define('PAGE_BROWSE_ENTRIES', '椤垫暟 %s 鍏?%s, 鎬诲叡 %s 绡囨枃绔?);
@define('PREVIOUS_PAGE', '涓婁竴椤?);
@define('NEXT_PAGE', '涓嬩竴椤?);
@define('ALL_CATEGORIES', '鍏ㄩ儴绫诲埆');
@define('DO_MARKUP', '鎵ц鏍囪杞崲');
@define('GENERAL_PLUGIN_DATEFORMAT', '鏃ユ湡鏍煎紡');
@define('GENERAL_PLUGIN_DATEFORMAT_BLAHBLAH', '鏂囩珷鐨勬棩鏈熸牸寮? 浣跨敤 PHP 鐨?strftime() 鍙樻暟. (棰勮: "%s")');
@define('ERROR_TEMPLATE_FILE', '鏃犳硶寮€鍚富棰樻枃浠? 璇锋洿鏂扮郴缁燂紒');
@define('ADVANCED_OPTIONS', '楂樼骇閫夐」');
@define('EDIT_ENTRY', '缂栬緫鏂囩珷');
@define('HTACCESS_ERROR', '瑕佹鏌ヤ綘鐨勫畨瑁呰瀹? 绯荤粺闇€瑕佽鍐?".htaccess"锛屼絾鏄洜涓烘潈闄愰敊璇? 娌℃湁鍔炴硶涓轰綘妫€鏌ワ紝璇锋敼鍙樻枃浠舵潈闄? <br />&nbsp;&nbsp;%s<br />鐒跺悗鍒锋柊銆?);
@define('SIDEBAR_PLUGINS', '渚у垪澶栨寕');
@define('EVENT_PLUGINS', '浜嬩欢澶栨寕');
@define('SORT_ORDER', '鎺掑簭');
@define('SORT_ORDER_NAME', '鏂囦欢鍚嶇О');
@define('SORT_ORDER_EXTENSION', '鍓枃浠跺悕');
@define('SORT_ORDER_SIZE', '鏂囦欢澶у皬');
@define('SORT_ORDER_WIDTH', '鍥剧墖瀹藉害');
@define('SORT_ORDER_HEIGHT', '鍥剧墖闀垮害');
@define('SORT_ORDER_DATE', '涓婁紶鏃ユ湡');
@define('SORT_ORDER_ASC', '閫掑鎺掑簭');
@define('SORT_ORDER_DESC', '閫掑噺鎺掑簭');
@define('THUMBNAIL_SHORT', '缂╁浘');
@define('ORIGINAL_SHORT', '鍘熷');
@define('APPLY_MARKUP_TO', '濂楃敤鏍囪鍒?%s');
@define('CALENDAR_BEGINNING_OF_WEEK', '涓€鍛ㄧ殑绗竴澶?);
@define('SERENDIPITY_NEEDS_UPGRADE', '绯荤粺鍒颁綘鐨勭増鏈槸 %s, 浣?Serendipity 鐜板湪鐨勭増鏈槸 %s, 璇锋洿鏂颁綘鐨勭▼搴忥紒<a href="%s">鏇存柊</a>');
@define('SERENDIPITY_UPGRADER_WELCOME', '浣犲ソ, 娆㈣繋浣跨敤 Serendipity 鐨勬洿鏂扮▼搴?);
@define('SERENDIPITY_UPGRADER_PURPOSE', '鏇存柊绋嬪簭浼氬府浣犳洿鏂板埌 Serendipity 鐗堟湰 %s.');
@define('SERENDIPITY_UPGRADER_WHY', '浣犲凡缁忔洿鏂?Serendipity %s, 璇锋斁蹇冪郴缁熸病鏈夋洿鏀逛綘鐨勬暟鎹簱锛?);
@define('SERENDIPITY_UPGRADER_DATABASE_UPDATES', '鏁版嵁搴撴洿鏂?(%s)');
@define('SERENDIPITY_UPGRADER_FOUND_SQL_FILES', '绯荤粺鎵惧埌浠ヤ笅鐨?.sql 琛? 杩欎簺鏁版嵁蹇呴』鍏堟墽琛屾墠鑳界户缁畨瑁?Serendipity');
@define('SERENDIPITY_UPGRADER_VERSION_SPECIFIC',  '鐗瑰畾鐨勭増鏈换鍔?);
@define('SERENDIPITY_UPGRADER_NO_VERSION_SPECIFIC', '娌℃湁鐗瑰畾鐨勭増鏈换鍔?);
@define('SERENDIPITY_UPGRADER_PROCEED_QUESTION', '纭畾瑕佹墽琛屼互涓婄殑浠诲姟鍚?');
@define('SERENDIPITY_UPGRADER_PROCEED_ABORT', '鎴戣嚜宸辨墽琛?);
@define('SERENDIPITY_UPGRADER_PROCEED_DOIT', '璇峰府鎴戞墽琛?);
@define('SERENDIPITY_UPGRADER_NO_UPGRADES', '涓嶉渶瑕佽繘琛屼换浣曟洿鏂?);
@define('SERENDIPITY_UPGRADER_CONSIDER_DONE', 'Serendipity 鏇存柊瀹屾垚');
@define('SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED', '浣犺烦杩囦簡鏇存柊浠诲姟, 璇风‘瀹氭暟鎹簱宸插畨瑁呭畬鎴? 鍜屽叾瀹冪殑浠诲姟瀹夎鏃犺锛?);
@define('SERENDIPITY_UPGRADER_NOW_UPGRADED', '浣犵殑 Serendipity 宸茬粡鏇存柊鐗堟湰涓?%s');
@define('SERENDIPITY_UPGRADER_RETURN_HERE', '浣犲彲浠ョ偣 %s杩欓噷%s 杩斿洖缃戠珯棣栭〉');
@define('MANAGE_USERS', '绠＄悊浣滆€?);
@define('CREATE_NEW_USER', '鏂板浣滆€?);
@define('CREATE_NOT_AUTHORIZED', '浣犱笉鑳戒慨鏀硅窡浣犵浉鍚屾潈闄愮殑浣滆€?);
@define('CREATE_NOT_AUTHORIZED_USERLEVEL', '浣犱笉鑳芥柊澧炴瘮浣犳洿楂樻潈闄愮殑浣滆€?);
@define('CREATED_USER', '鏂颁綔鑰?%s 宸茬粡鏂板');
@define('MODIFIED_USER', '浣滆€?%s 鐨勮祫鏂欏凡缁忔洿鏀?);
@define('USER_LEVEL', '浣滆€呮潈闄?);
@define('DELETE_USER', '浣犺鍒犻櫎杩欎釜浣滆€?#%d %s? 杩欎細鍦ㄤ富椤甸殣钘忎粬鎵€鍐欑殑浠讳綍鏂囩珷銆?);
@define('DELETED_USER', '浣滆€?#%d %s 宸茶鍒犻櫎.');
@define('LIMIT_TO_NUMBER', '瑕佹樉绀哄灏戦」');
@define('ENTRIES_PER_PAGE', '姣忛〉鏄剧ず鐨勬枃绔?);
@define('XML_IMAGE_TO_DISPLAY', 'XML 鎸夐挳');
@define('XML_IMAGE_TO_DISPLAY_DESC','杩炴帴鍒?XML Feeds 鐨勯兘浼氱敤杩欎釜鍥剧墖琛ㄧず. 涓嶅～鍐欏皢浼氫娇鐢ㄩ璁剧殑鍥剧墖, 鎴栬緭鍏?\'none\' 鍏抽棴杩欎釜鍔熻兘銆?);

@define('DIRECTORIES_AVAILABLE', '浣犲彲浠ュ湪杩欓噷寤虹珛濯掍綋瀛樻斁鐨勭洰褰?);
@define('ALL_DIRECTORIES', '鍏ㄩ儴鐩綍');
@define('MANAGE_DIRECTORIES', '绠＄悊鐩綍');
@define('DIRECTORY_CREATED', '鐩綍 <strong>%s</strong> 宸茬粡鏂板');
@define('PARENT_DIRECTORY', '涓荤洰褰?);
@define('CONFIRM_DELETE_DIRECTORY', '纭畾瑕佸垹闄よ繖涓洰褰曞唴鐨勫叏閮ㄥ唴瀹?%s');
@define('ERROR_NO_DIRECTORY', '閿欒: 鐩綍 %s 涓嶅瓨鍦?);
@define('CHECKING_DIRECTORY', '妫€鏌ユ鐩綍鐨勬枃浠?%s');
@define('DELETING_FILE', '鍒犻櫎鏂囦欢 %s...');
@define('ERROR_DIRECTORY_NOT_EMPTY', '涓嶈兘鍒犻櫎鏈竻绌虹殑鐩綍. 鍕鹃€?"寮哄埗鍒犻櫎" 濡傛灉浣犵‘瀹氳鍒犻櫎杩欎簺鏂囦欢, 鐒跺悗鍦ㄧ户缁紝 瀛樺湪鐨勬枃浠舵槸:');
@define('DIRECTORY_DELETE_FAILED', '涓嶈兘鍒犻櫎鐩綍 %s. 璇锋鏌ユ潈闄愭垨鐪嬩笂闈㈢殑璁伅.');
@define('DIRECTORY_DELETE_SUCCESS', '鐩綍 %s 鎴愬姛鍒犻櫎.');
@define('SKIPPING_FILE_EXTENSION', '璺宠繃鏂囦欢: 娌℃湁 %s 鐨勫壇妗ｅ悕');
@define('SKIPPING_FILE_UNREADABLE', '鐣ヨ繃鏂囦欢: %s 涓嶈兘璇诲彇');
@define('FOUND_FILE', '鎵惧埌 鏂?淇敼 杩囩殑妗ｆ: %s');
@define('ALREADY_SUBCATEGORY', '%s 宸茬粡鏄绫诲埆鐨勫瓙鍒嗙被 %s');
@define('PARENT_CATEGORY', '涓荤被鍒?);
@define('IN_REPLY_TO', '鍥炲鍒?);
@define('TOP_LEVEL', '鏈€楂樺眰');
@define('SYNDICATION_PLUGIN_GENERIC_FEED', '%s feed');
@define('PERMISSIONS', '鏉冮檺');
@define('INTEGRITY', 'Verify Installation Integrity');
@define('CHECKSUMS_NOT_FOUND', 'Unable to compare checksums! (No checksums.inc.php in main directory)');
@define('CHECKSUMS_PASS', 'All required files verified.');
@define('CHECKSUM_FAILED', '%s corrupt or modified: failed verification');
@define('SETTINGS_SAVED_AT', '鏂拌瀹氬凡缁忚淇濆瓨鍒?%s');

/* DATABASE SETTINGS */
@define('INSTALL_CAT_DB', '鏁版嵁搴撹瀹?);
@define('INSTALL_CAT_DB_DESC', '浣犲彲浠ュ湪杩欒緭鍏ュ叏閮ㄧ殑鏁版嵁搴撹祫鏂欙紝绯荤粺闇€瑕佽繖浜涜祫鏂欐墠鑳芥甯歌繍浣?);
@define('INSTALL_DBTYPE', '鏁版嵁搴撶被鍨?);
@define('INSTALL_DBTYPE_DESC', '鏁版嵁搴撶被鍨?);
@define('INSTALL_DBHOST', '鏁版嵁搴撲富鏈?);
@define('INSTALL_DBHOST_DESC', '鏁版嵁搴撲富鏈哄悕绉?);
@define('INSTALL_DBUSER', '鏁版嵁搴撳笎鍙?);
@define('INSTALL_DBUSER_DESC', '鐧诲叆鏁版嵁搴撶殑甯愬彿');
@define('INSTALL_DBPASS', '鏁版嵁瀵嗙爜');
@define('INSTALL_DBPASS_DESC', '浣犵殑鏁版嵁搴撳瘑鐮?);
@define('INSTALL_DBNAME', '鏁版嵁鍚嶇О');
@define('INSTALL_DBNAME_DESC', '璧勬枡搴撳悕绉?);
@define('INSTALL_DBPREFIX', '琛ㄥ墠缃悕绉?);
@define('INSTALL_DBPREFIX_DESC', '琛ㄧ殑鍓嶇疆鍚嶇О, 渚嬪 serendipity_');

/* PATHS */
@define('INSTALL_CAT_PATHS', '璺緞璁惧畾');
@define('INSTALL_CAT_PATHS_DESC', '缁欐枃浠跺す鐨勮矾寰? 涓嶈蹇樹簡鏈€鍚庣殑鏂滅嚎!');
@define('INSTALL_FULLPATH', '瀹屽叏璺緞');
@define('INSTALL_FULLPATH_DESC', '绯荤粺瀹夎鐨勫畬鏁磋矾寰勫拰缁濆璺緞');
@define('INSTALL_UPLOADPATH', '涓婁紶璺緞');
@define('INSTALL_UPLOADPATH_DESC', '鍏ㄩ儴涓婁紶鐨勬枃浠朵細瀛樺埌杩欓噷, 浠?\'瀹屽叏璺緞\' 琛ㄧず鐨勭浉瀵硅矾寰?- 渚嬪 \'uploads/\'');
@define('INSTALL_RELPATH', '鐩稿璺緞');
@define('INSTALL_RELPATH_DESC', '缁欐祻瑙堝櫒鐨勮矾寰? 渚嬪 \'/serendipity/\'');
@define('INSTALL_RELTEMPLPATH', '鐩稿鐨勪富棰樿矾寰?);
@define('INSTALL_RELTEMPLPATH_DESC', '涓婚鐨勮矾寰?- 浠?\'鐩稿璺緞\' 琛ㄧず鐨勭浉瀵硅矾寰?);
@define('INSTALL_RELUPLOADPATH', '鐩稿鐨勪笂浼犺矾寰?);
@define('INSTALL_RELUPLOADPATH_DESC', '缁欐祻瑙堝櫒涓婁紶鏂囦欢鐨勮矾寰?- 浠?\'鐩稿璺緞\' 琛ㄧず鐨勭浉瀵硅矾寰?);
@define('INSTALL_URL', '缃戠珯鍦板潃');
@define('INSTALL_URL_DESC', '绯荤粺瀹夎鐨勫熀鏈湴鍧€');
@define('INSTALL_INDEXFILE', 'Index 鏂囦欢');
@define('INSTALL_INDEXFILE_DESC', '绯荤粺鐨?index 鏂囦欢');

/* Generel settings */
@define('INSTALL_CAT_SETTINGS', '涓€鑸瀹?);
@define('INSTALL_CAT_SETTINGS_DESC', '绯荤粺鐨勪竴鑸瀹?);
@define('INSTALL_USERNAME', '绠＄悊鍛樺笎鍙?);
@define('INSTALL_USERNAME_DESC', '绠＄悊鍛樼櫥闄嗙郴缁熺殑甯愬彿');
@define('INSTALL_PASSWORD', '绠＄悊鍛樺瘑鐮?);
@define('INSTALL_PASSWORD_DESC', '绠＄悊鍛樼櫥闄嗙郴缁熺殑瀵嗙爜');
@define('INSTALL_EMAIL', '鐢靛瓙閭欢');
@define('INSTALL_EMAIL_DESC', '绠＄悊鍛樼殑鐢靛瓙閭欢');
@define('INSTALL_SENDMAIL', '鍙戦€佺數瀛愰偖浠剁粰绠＄悊鍛橈紵');
@define('INSTALL_SENDMAIL_DESC', '褰撴湁浜哄洖澶嶄綘鐨勬枃绔犳椂瑕佹敹鍒扮數瀛愰偖浠堕€氱煡鍚楋紵');
@define('INSTALL_SUBSCRIBE', '鍏佽浣跨敤鑰呰闃呮枃绔?');
@define('INSTALL_SUBSCRIBE_DESC', '浣犲彲浠ュ厑璁镐娇鐢ㄨ€呮敹鍒扮數瀛愰偖浠堕€氱煡, 褰撴湁鍥炲鏃朵粬浠細鏀跺埌閫氱煡銆?);
@define('INSTALL_BLOGNAME', '缃戠珯鍚嶇О');
@define('INSTALL_BLOGNAME_DESC', '浣犵綉绔欑殑鍚嶇О');
@define('INSTALL_BLOGDESC', '缃戠珯绠€浠?);
@define('INSTALL_BLOGDESC_DESC', '浠嬬粛浣犵殑鏃ュ織');
@define('INSTALL_LANG', '璇█');
@define('INSTALL_LANG_DESC', '浣犵綉绔欎娇鐢ㄧ殑璇█');

/* Appearance and options */
@define('INSTALL_CAT_DISPLAY', '涓婚鍙婇€夐」璁惧畾');
@define('INSTALL_CAT_DISPLAY_DESC', '璁惧畾绯荤粺鐨勪富棰樺拰鍏跺畠璁惧畾');
@define('INSTALL_WYSIWYG', '浣跨敤 WYSIWYG 缂栬緫鍣?);
@define('INSTALL_WYSIWYG_DESC', '浣犺浣跨敤 WYSIWYG 缂栬緫鍣ㄥ悧锛?鍙湪 IE5+ 浣跨敤, 鏌愪簺閮ㄥ垎鍙娇鐢ㄤ簬 Mozilla 1.3+)');
@define('INSTALL_XHTML11', '寮哄埗绗﹀悎 XHTML 1.1 瑕佹眰');
@define('INSTALL_XHTML11_DESC', '璁╀綘鐨勭郴缁熷己鍒剁鍚?XHTML 1.1 瑕佹眰 (瀵规棫鐨勬祻瑙堝櫒鍙兘鏈夐棶棰?');
@define('INSTALL_POPUP', '浣跨敤寮瑰嚭绐楀彛');
@define('INSTALL_POPUP_DESC', '浣犺鍦ㄥ洖澶嶃€佸紩鐢ㄧ瓑鍦版柟浣跨敤寮瑰嚭绐楀彛鍚楋紵');
@define('INSTALL_EMBED', '浣跨敤鍐呭祵鍔熻兘?');
@define('INSTALL_EMBED_DESC', '濡傛灉浣犺灏?Serendipity 浠ュ唴宓岀殑鏂瑰紡鏀惧埌缃戦〉鍐? 閫夋嫨 鏄?浼氳浣犳斁寮冧换浣曟爣棰樼劧鍚庡彧鏄剧ず缃戠珯鍐呭銆?浣犲彲浠ョ敤 indexFile 鏉ヨ瀹氳繖涓姛鑳姐€傝鎯呰鏌ヨ README 鏂囦欢!');
@define('INSTALL_TOP_AS_LINKS', '浠ヨ繛鎺ユ柟寮忔樉绀?涓昏鍑烘簮/涓昏鏉ユ簮');
@define('INSTALL_TOP_AS_LINKS_DESC', '"鍚?: 鍑烘簮鍜屾潵婧愬皢鐢ㄦ枃瀛楁樉绀猴紝閬垮厤 google 鐨勫箍鍛娿€?"鏄?: 鍑烘簮鍜屾潵婧愬皢鐢ㄨ繛鎺ユ樉绀? "棰勮": 鐢ㄥ叏鍖洪噷闈㈢殑璁惧畾 (寤鸿)');
@define('INSTALL_BLOCKREF', '闃绘尅鏉ユ簮');
@define('INSTALL_BLOCKREF_DESC', '鏈夌壒娈婄殑缃戠珯涓嶅湪鏉ユ簮閲屾樉绀哄悧? 鐢?\';\' 鏉ュ垎寮€缃戠珯鍚嶇О, 娉ㄦ剰绋嬪簭鏄互瀛楃鏂瑰紡闃绘尅鐨勶紒');
@define('INSTALL_REWRITE', 'URL Rewriting');
@define('INSTALL_REWRITE_DESC', '璇烽€夋嫨 URL Rewriting 鏂瑰紡锛屽紑鍚?rewrite 瑙勫垯浼氫互姣旇緝娓呮鐨勬柟寮忔樉绀?URL, 浠ヤ究鎼滅储缃戠珯鑳芥纭殑鏀跺綍浣犵殑鏂囩珷锛屼笉杩囦綘鐨勪富鏈哄繀椤绘敮鎸?mod_rewrite 鎴?"AllowOverride All" 鐨勫姛鑳姐€俒棰勮鐨勮瀹氭槸绯荤粺鑷姩甯綘妫€娴媇');

/* Imageconversion Settings */
@define('INSTALL_CAT_IMAGECONV', '鍥剧墖杞崲璁惧畾');
@define('INSTALL_CAT_IMAGECONV_DESC', '璇疯瀹氬浘鐗囪浆鎹㈢殑鏂瑰紡');
@define('INSTALL_IMAGEMAGICK', '浣跨敤 Imagemagick');
@define('INSTALL_IMAGEMAGICK_DESC', '濡傛灉瀹夎 image magick, 浣犺鐢ㄥ畠鏉ユ敼鍙樺浘鐗囧ぇ灏忓悧?');
@define('INSTALL_IMAGEMAGICKPATH', '杞崲绋嬪紡璺緞');
@define('INSTALL_IMAGEMAGICKPATH_DESC', 'image magick 杞崲绋嬪紡鐨勫畬鍏ㄨ矾寰勫拰鍚嶇О');
@define('INSTALL_THUMBSUFFIX', '缂╁浘鍚庣疆瀛楃');
@define('INSTALL_THUMBSUFFIX_DESC', '缂╁浘浼氫互涓嬮潰鐨勬牸寮忛噸鏂板懡鍚? original.[鍚庣疆瀛楃].ext');
@define('INSTALL_THUMBWIDTH', '缂╁浘澶у皬');
@define('INSTALL_THUMBWIDTH_DESC', '鑷姩寤虹珛缂╁浘鐨勬渶澶у搴?);
@define('INSTALL_THUMBDIM', 'Thumbnail constrained dimension');
@define('INSTALL_THUMBDIM_LARGEST', 'Largest');
@define('INSTALL_THUMBDIM_WIDTH', 'Width');
@define('INSTALL_THUMBDIM_HEIGHT', 'Height');
@define('INSTALL_THUMBDIM_DESC', 'Dimension to be constrained to the thumbnail max size. The default "' .
    INSTALL_THUMBDIM_LARGEST .  '" limits both dimensions, so neither can be greater than the max size; "' .
    INSTALL_THUMBDIM_WIDTH . '" and "' .  INSTALL_THUMBDIM_HEIGHT .
    '" only limit the chosen dimension, so the other could be larger than the max size.');

/* Personal details */
@define('USERCONF_CAT_PERSONAL', '涓汉璧勬枡璁惧畾');
@define('USERCONF_CAT_PERSONAL_DESC', '鏀瑰彉浣犵殑涓汉璧勬枡');
@define('USERCONF_USERNAME', '浣犵殑甯愬彿');
@define('USERCONF_USERNAME_DESC', '浣犵櫥鍏ョ郴缁熺殑鍚嶇О');
@define('USERCONF_PASSWORD', '浣犵殑瀵嗙爜');
@define('USERCONF_PASSWORD_DESC', '浣犵櫥鍏ョ郴缁熺殑瀵嗙爜');
@define('USERCONF_EMAIL', '浣犵殑鐢靛瓙閭欢');
@define('USERCONF_EMAIL_DESC', '浣犱娇鐢ㄧ殑鐢靛瓙閭欢');
@define('USERCONF_SENDCOMMENTS', '瀵勯€佸洖澶嶉€氱煡');
@define('USERCONF_SENDCOMMENTS_DESC', '褰撴湁鏂板洖澶嶆椂浣跨敤閫氱煡');
@define('USERCONF_SENDTRACKBACKS', '瀵勯€佸紩鐢ㄩ€氱煡?');
@define('USERCONF_SENDTRACKBACKS_DESC', '褰撴湁鏂板紩鐢ㄦ椂浣跨敤閫氱煡');
@define('USERCONF_ALLOWPUBLISH', '鏉冮檺: 鍙彂甯冩枃绔?);
@define('USERCONF_ALLOWPUBLISH_DESC', '鍏佽杩欎綅浣滆€呭彂甯冩枃绔?);
@define('SUCCESS', '瀹屾垚');
@define('POWERED_BY_SHOW_TEXT', '浠ユ枃瀛楁樉绀?"Serendipity"');
@define('POWERED_BY_SHOW_TEXT_DESC', '灏嗙敤鏂囧瓧鏄剧ず "Serendipity Weblog"');
@define('POWERED_BY_SHOW_IMAGE', '浠?logo 鏄剧ず "Serendipity"');
@define('POWERED_BY_SHOW_IMAGE_DESC', '鏄剧ず Serendipity 鐨?logo');
@define('PLUGIN_ITEM_DISPLAY', '璇ラ」鐩殑鏄剧ず鍦板潃');
@define('PLUGIN_ITEM_DISPLAY_EXTENDED', '鍙湪鍓唴瀹规樉绀?);
@define('PLUGIN_ITEM_DISPLAY_OVERVIEW', '鍙湪妗嗘灦鍐呮樉绀?);
@define('PLUGIN_ITEM_DISPLAY_BOTH', '姘歌繙鏄剧ず');

@define('COMMENTS_WILL_BE_MODERATED', '鍙戝竷鐨勫洖澶嶉渶瑕佺鐞嗗憳瀹℃牳');
@define('YOU_HAVE_THESE_OPTIONS', '浣犳湁浠ヤ笅閫夋嫨:');
@define('THIS_COMMENT_NEEDS_REVIEW', '璀﹀憡: 杩欎釜鍥炲椤诲鏍告墠浼氭樉绀猴紒');
@define('DELETE_COMMENT', '鍒犻櫎鍥炲');
@define('APPROVE_COMMENT', '瀹℃牳鍥炲');
@define('REQUIRES_REVIEW', '闇€瑕佸鏍?);
@define('COMMENT_APPROVED', '鍥炲 #%s 宸茬粡閫氳繃瀹℃牳');
@define('COMMENT_DELETED', '鍥炲 #%s 宸茬粡鎴愬姛鍒犻櫎');
@define('COMMENTS_MODERATE', '鍥炲鍜屽紩鐢ㄩ渶瑕佺鐞嗗憳瀹℃牳');
@define('THIS_TRACKBACK_NEEDS_REVIEW', '璀﹀憡: 杩欎釜寮曠敤闇€瑕佺鐞嗗憳瀹℃牳鎵嶄細鏄剧ず锛?);
@define('DELETE_TRACKBACK', '鍒犻櫎寮曠敤');
@define('APPROVE_TRACKBACK', '瀹℃牳寮曠敤');
@define('TRACKBACK_APPROVED', '寮曠敤 #%s 宸茬粡閫氳繃瀹℃牳');
@define('TRACKBACK_DELETED', '寮曠敤 #%s 宸茬粡鎴愬姛鍒犻櫎');
@define('VIEW', '娴忚');
@define('COMMENT_ALREADY_APPROVED', '鍥炲 #%s 宸茬粡閫氳繃瀹℃牳');
@define('COMMENT_EDITED', '鏂囩珷宸茶缂栬緫');
@define('HIDE', '闅愯棌');
@define('VIEW_EXTENDED_ENTRY', '缁х画闃呰 "%s"');
@define('TRACKBACK_SPECIFIC_ON_CLICK', '杩欎釜杩炴帴涓嶆槸鐢ㄦ潵鐐圭殑. 瀹冨寘鍚簡杩欎釜鏂囩珷鐨勫紩鐢?URI. 浣犲彲浠ヤ粠杩欎釜 URI 鏉ヤ紶閫?ping 鍜屽紩鐢ㄥ埌杩欎釜鏂囩珷. 濡傛灉瑕佸鍒惰繖涓繛鎺? 鍦ㄨ繛鎺ヤ笂鐐瑰彸閿劧鍚庨€夋嫨 "澶嶅埗杩炴帴" (IE) 鎴?"澶嶅埗杩炴帴鍦板潃" (Mozilla).');
@define('PLUGIN_SUPERUSER_HTTPS', '鐢?https 鐧诲叆');
@define('PLUGIN_SUPERUSER_HTTPS_DESC', '浣跨敤 https 缃戝潃銆備綘鐨勪富鏈哄繀椤绘敮鎸佽繖椤瑰姛鑳?);
@define('INSTALL_SHOW_EXTERNAL_LINKS', '璁╁閮ㄨ繛鎺ユ樉绀?);
@define('INSTALL_SHOW_EXTERNAL_LINKS_DESC', '"鍚?: 澶栭儴杩炴帴 (涓昏鍑烘簮, 涓昏鏉ユ簮, 鍥炲) 閮戒笉浼氫互鏂囧瓧鏄剧ず锛岄伩鍏?google 骞垮憡 (寤鸿浣跨敤)锛?"鏄?: 澶栨潵杩炴帴灏嗕互瓒呰繛鎺ョ殑鏂瑰紡鏄剧ず锛?鍙互鍦ㄤ晶鍒楀鎸傝鐩栨璁惧畾銆?);
@define('PAGE_BROWSE_COMMENTS', '椤垫暟 %s 鍏?%s, 鎬诲叡 %s 涓洖澶?);
@define('FILTERS', '杩囨护');
@define('FIND_ENTRIES', '鎼滅储鏂囩珷');
@define('FIND_COMMENTS', '鎼滅储鍥炲');
@define('FIND_MEDIA', '鎼滅储濯掍綋');
@define('FILTER_DIRECTORY', '鐩綍');
@define('SORT_BY', '鎺掑簭');
@define('TRACKBACK_COULD_NOT_CONNECT', '娌℃湁閫佸嚭寮曠敤: 鏃犳硶寮€鍚矾寰勫埌 %s 鐢ㄨ繛鎺ュ埌 %d');
@define('MEDIA', '濯掍綋绠＄悊');
@define('MEDIA_LIBRARY', '濯掍綋鍥惧簱');
@define('ADD_MEDIA', '鏂板濯掍綋');
@define('ENTER_MEDIA_URL', '璇疯緭鍏ユ枃浠跺湴鍧€:');
@define('ENTER_MEDIA_UPLOAD', '璇烽€夋嫨瑕佷笂浼犵殑鏂囦欢:');
@define('SAVE_FILE_AS', '淇濆瓨鏂囦欢:');
@define('STORE_IN_DIRECTORY', '淇濆瓨鍒颁互涓嬬洰褰? ');
@define('ADD_MEDIA_BLAHBLAH', '<b>鏂板鏂囦欢鍒板獟浣撶洰褰?</b><p>浣犲彲浠ヤ笂浼犲獟浣撴枃浠? 鎴栧憡璇夌郴缁熷埌鍝鎵俱€傚鏋滀綘娌℃湁鎯宠鐨勫浘鐗? 浣犲彲浠ュ埌 <a href="http://images.google.com" target="_blank">google鎼滅储鍥剧墖</a>.<p><b>閫夋嫨鏂瑰紡:</b><br>');
@define('MEDIA_RENAME', '鏇存敼鏂囦欢鍚嶇О');
@define('IMAGE_RESIZE', '鏇存敼鍥剧墖灏哄');
@define('MEDIA_DELETE', '鍒犻櫎杩欎釜鏂囦欢');
@define('FILES_PER_PAGE', '姣忛〉鏄剧ず鐨勬枃浠舵暟');
@define('CLICK_FILE_TO_INSERT', '鐐归€変綘瑕佽緭鍏ョ殑鏂囦欢:');
@define('SELECT_FILE', '閫夋嫨瑕佽緭鍏ョ殑鏂囦欢');
@define('MEDIA_FULLSIZE', '瀹屾暣灏哄');
@define('CALENDAR_BOW_DESC', '涓€涓槦鏈熺殑绗竴澶棰勮鏄槦鏈熶竴]');
@define('SUPERUSER', '绯荤粺绠＄悊');
@define('ALLOWS_YOU_BLAHBLAH', '鍦ㄤ晶鍒楁彁渚涜繛鎺ュ埌鏃ュ織绠＄悊');
@define('CALENDAR', '绔欑偣鏃ュ巻');
@define('SUPERUSER_OPEN_ADMIN', '寮€鍚鐞嗛〉闈?);
@define('SUPERUSER_OPEN_LOGIN', '寮€鍚櫥鍏ラ〉闈?);
@define('INVERT_SELECTIONS', '鍙嶅嬀閫?);
@define('COMMENTS_DELETE_CONFIRM', '纭畾瑕佸垹闄ゅ嬀閫夌殑鍥炲鍚楋紵');
@define('COMMENT_DELETE_CONFIRM', '纭畾瑕佸垹闄ゅ洖澶?#%d, 鍙戝竷鑰呮槸 %s锛?);
@define('DELETE_SELECTED_COMMENTS', '鍒犻櫎鍕鹃€夌殑鍥炲');
@define('VIEW_COMMENT', '娴忚鍥炲');
@define('VIEW_ENTRY', '娴忚鏂囩珷');
@define('DELETE_FILE_FAIL' , '鏃犳硶鍒犻櫎鏂囦欢 <b>%s</b>');
@define('DELETE_THUMBNAIL', '鍒犻櫎鍥剧墖缂╁浘 <b>%s</b>');
@define('DELETE_FILE', '鍒犻櫎鏂囦欢 <b>%s</b>');
@define('ABOUT_TO_DELETE_FILE', '浣犲皢鍒犻櫎鏂囦欢 <b>%s</b><br />濡傛灉浣犳湁鍦ㄥ叾瀹冪殑鏂囩珷鍐呬娇鐢ㄨ繖涓枃浠? 閭ｄ釜杩炴帴鎴栧浘鐗囧皢浼氭棤鏁?br />纭畾瑕佺户缁悧锛?br /><br />');
@define('TRACKBACK_SENDING', '浼犻€佸紩鐢ㄥ埌 URI %s...');
@define('TRACKBACK_SENT', '寮曠敤瀹屾垚');
@define('TRACKBACK_FAILED', '寮曠敤閿欒: %s');
@define('TRACKBACK_NOT_FOUND', '鎵句笉鍒板紩鐢ㄧ殑鍦板潃');
@define('TRACKBACK_URI_MISMATCH', '鑷姩鎼滃鐨勫紩鐢ㄨ窡寮曠敤鐩爣涓嶇浉鍚?');
@define('TRACKBACK_CHECKING', '鎼滃 <u>%s</u> 鐨勫紩鐢?..');
@define('TRACKBACK_NO_DATA', '鐩爣娌℃湁浠讳綍璧勬枡');
@define('TRACKBACK_SIZE', '鐩爣鍦板潃瓒呭嚭浜嗗厑璁哥殑 %s bytes 鏂囦欢澶у皬.');
@define('COMMENTS_VIEWMODE_THREADED', '鍒嗙嚎绋?);
@define('COMMENTS_VIEWMODE_LINEAR', '鐩寸嚎绋?);
@define('DISPLAY_COMMENTS_AS', '鍥炲鏄剧ず鏂瑰紡');
@define('COMMENTS_FILTER_SHOW', '鏄剧ず');
@define('COMMENTS_FILTER_ALL', '鍏ㄩ儴');
@define('COMMENTS_FILTER_APPROVED_ONLY', '鏄剧ず瀹℃牳鍥炲');
@define('COMMENTS_FILTER_NEED_APPROVAL', '鏄剧ず绛夊緟瀹℃牳');
@define('RSS_IMPORT_BODYONLY', '灏嗚緭鍏ョ殑鏂囧瓧鏀惧埌涓诲唴瀹? 灏嗕笉鎷嗗紑杩囬暱鐨勬枃绔犲埌鍓唴瀹?);
@define('SYNDICATION_PLUGIN_FULLFEED', '鍦?RSS feed 閲屾樉绀哄叏閮ㄧ殑鏂囩珷');
@define('WEEK', '鍛?);
@define('WEEKS', '鍛?);
@define('MONTHS', '鏈?);
@define('DAYS', '鏃?);
@define('ARCHIVE_FREQUENCY', '淇濆瓨鏂囦欢鐨勯鐜?);
@define('ARCHIVE_FREQUENCY_DESC', '淇濆瓨鏂囦欢浣跨敤鐨勯」鐩竻鍗曢棿闅?);
@define('ARCHIVE_COUNT', '淇濆瓨鏂囦欢鐨勯」鐩暟');
@define('ARCHIVE_COUNT_DESC', '鏄剧ず鐨勬湀, 鍛? 鎴栨棩');
@define('BELOW_IS_A_LIST_OF_INSTALLED_PLUGINS', '涓嬮潰鏄畨瑁呭ソ鐨勫鎸?);
@define('SIDEBAR_PLUGIN', '渚у垪澶栨寕');
@define('EVENT_PLUGIN', '浜嬩欢澶栨寕');
@define('CLICK_HERE_TO_INSTALL_PLUGIN', '鐐硅繖閲屽畨瑁呮柊 %s');
@define('VERSION', '鐗堟湰');
@define('INSTALL', '瀹夎');
@define('ALREADY_INSTALLED', '宸茬粡瀹夎');
@define('SELECT_A_PLUGIN_TO_ADD', '璇烽€夋嫨瑕佸畨瑁呯殑澶栨寕');
@define('RSS_IMPORT_CATEGORY', '鐢ㄨ繖涓被鍒粰涓嶇浉鍚岀殑鏂囩珷');

@define('INSTALL_OFFSET', '涓绘満鏃堕棿'); // Translate
@define('STICKY_POSTINGS', '缃《鏂囩珷'); // Translate
@define('INSTALL_FETCHLIMIT', '鍦ㄤ富椤垫樉绀虹殑鏂囩珷'); // Translate
@define('INSTALL_FETCHLIMIT_DESC', '鍦ㄤ富椤垫樉绀烘枃绔犵殑鏁伴噺'); // Translate
@define('IMPORT_ENTRIES', '瀵煎叆鏁版嵁'); // Translate
@define('EXPORT_ENTRIES', '瀵煎嚭鏁版嵁'); // Translate
@define('IMPORT_WELCOME', '娆㈣繋浣跨敤Serendipity鐨勬暟鎹浆鎹㈠伐鍏?); // Translate
@define('IMPORT_WHAT_CAN', '浣犲彲浠ュ鍏ュ叾瀹冪▼搴忕殑鏂囩珷'); // Translate
@define('IMPORT_SELECT', '璇烽€夋嫨浣犺瀵煎叆鐨勭▼搴?); // Translate
@define('IMPORT_PLEASE_ENTER', '璇疯緭鍏ヨ祫鏂?); // Translate
@define('IMPORT_NOW', '寮€濮嬪鍏?); // Translate
@define('IMPORT_STARTING', '姝ｅ湪瀵煎叆...'); // Translate
@define('IMPORT_FAILED', '瀵煎叆澶辫触'); // Translate
@define('IMPORT_DONE', '瀵煎叆鎴愬姛'); // Translate
@define('IMPORT_WEBLOG_APP', '绋嬪簭'); // Translate
@define('EXPORT_FEED', '杈撳嚭 RSS feed'); // Translate
@define('STATUS', '瀵煎嚭鍚庣殑鐘舵€?); // Translate
@define('IMPORT_GENERIC_RSS', '涓€鑸?RSS 瀵煎叆'); // Translate
@define('ACTIVATE_AUTODISCOVERY', '浼犻€佹枃绔犲唴寮曠敤鐨勮繛鎺?); // Translate
@define('WELCOME_TO_ADMIN', '娆㈣繋鐧婚檰Serendipity鏅鸿兘鍗氬绯荤粺');
@define('PLEASE_ENTER_CREDENTIALS', '璇疯緭鍏ユ纭殑鐧婚檰甯愬彿'); // Translate
@define('ADMIN_FOOTER_POWERED_BY', 'Powered by Serendipity %s and PHP %s'); // Translate
@define('INSTALL_USEGZIP', '浣跨敤 gzip 鍘嬬缉缃戦〉'); // Translate
@define('INSTALL_USEGZIP_DESC', '涓轰簡璁╃綉椤佃繍琛屽緱鏇村揩, 绯荤粺灏嗕細鍘嬬缉鍚庢樉绀? 濡傛灉璁垮浣跨敤鐨勬祻瑙堝櫒鏀寔鍘嬬缉缃戦〉鐨勮瘽锛屽缓璁娇鐢ㄣ€?); // Translate
@define('INSTALL_SHOWFUTURE', '鏄剧ず鏈潵鏂囩珷'); // Translate
@define('INSTALL_SHOWFUTURE_DESC', '濡傛灉鎵撳紑, 绯荤粺灏嗕細鏄剧ず鏈潵鍙戣〃鐨勬枃绔狅紝棰勮鏄瀹氭湭鏉ユ枃绔犻殣钘忥紝鐒跺悗鍒板彂甯冩棩鑷姩鏄剧ず銆?); // Translate
@define('INSTALL_DBPERSISTENT', '浣跨敤鎸佺画杩炴帴'); // Translate
@define('INSTALL_DBPERSISTENT_DESC', '瀵规暟鎹簱浣跨敤鎸佺画杩炴帴, 璇︽儏鍙傞槄 <a href="http://php.net/manual/features.persistent-connections.php" target="_blank">杩欓噷</a>锛岄€氬父涓嶅缓璁娇鐢ㄣ€?); // Translate
@define('NO_IMAGES_FOUND', '鎵句笉鍒版枃浠?); // Translate
@define('PERSONAL_SETTINGS', '涓汉璁剧疆'); // Translate
@define('REFERER', '鏉ユ簮'); // Translate
@define('NOT_FOUND', '鎵句笉鍒?); // Translate
@define('NOT_WRITABLE', '涓嶅彲璇诲啓'); // Translate
@define('WRITABLE', '鍙鍐?); // Translate
@define('PROBLEM_DIAGNOSTIC', '鍥犱负涓婇潰鍑虹幇闂,浣犲繀椤绘妸闂瑙ｅ喅浜嗘墠鑳藉畨瑁呫€?); // Translate
@define('SELECT_INSTALLATION_TYPE', '璇烽€夋嫨瀹夎绫诲瀷'); // Translate
@define('WELCOME_TO_INSTALLATION', '娆㈣繋浣跨敤 Serendipity 绠€鍗曚綋涓枃鐗?); // Translate
@define('FIRST_WE_TAKE_A_LOOK', '棣栧厛绯荤粺浼氭鏌ヤ綘鐨勮缃互閬垮厤瀹夎鍑洪敊'); // Translate
@define('ERRORS_ARE_DISPLAYED_IN', '閿欒鏄剧ず %s, 寤鸿 %s 瑙ｅ喅閿欒 %s'); // Translate
@define('RED', '绾?); // Translate
@define('YELLOW', '榛?); // Translate
@define('GREEN', '缁?); // Translate
@define('PRE_INSTALLATION_REPORT', 'Serendipity Blog v%s 瀹夎鍓嶆姤鍛?); // Translate
@define('RECOMMENDED', '寤鸿'); // Translate
@define('ACTUAL', '瀹為檯'); // Translate
@define('PHPINI_CONFIGURATION', 'php.ini 璁剧疆'); // Translate
@define('PHP_INSTALLATION', 'PHP 瀹夎'); // Translate
@define('THEY_DO', '閫氳繃'); // Translate
@define('THEY_DONT', 'they don\'t'); // Translate
@define('SIMPLE_INSTALLATION', '蹇€熷畨瑁?); // Translate
@define('EXPERT_INSTALLATION', '楂樼骇瀹夎'); // Translate
@define('COMPLETE_INSTALLATION', '瀹屾暣瀹夎'); // Translate
@define('WONT_INSTALL_DB_AGAIN', '涓嶄細閲嶅瀹夎鏁版嵁搴?); // Translate
@define('CHECK_DATABASE_EXISTS', '妫€鏌ユ暟鎹槸鍚﹀瓨鍦?); // Translate
@define('CREATING_PRIMARY_AUTHOR', '璁惧畾绠＄悊鍛?\'%s\''); // Translate
@define('SETTING_DEFAULT_TEMPLATE', '璁惧畾涓婚'); // Translate
@define('INSTALLING_DEFAULT_PLUGINS', '瀹夎棰勮瀹氬鎸?); // Translate
@define('SERENDIPITY_INSTALLED', 'Serendipity绠€浣撲腑鏂囩増瀹夎瀹屾垚'); // Translate
@define('VISIT_BLOG_HERE', '寮€濮嬫祻瑙堜綘鐨勫崥瀹?); // Translate
@define('THANK_YOU_FOR_CHOOSING', '闈炲父鎰熻阿浣犻€夋嫨 Serendipity绠€浣撲腑鏂囩増'); // Translate
@define('ERROR_DETECTED_IN_INSTALL', '瀹夎鏃跺彂鐢熼敊璇?); // Translate
@define('OPERATING_SYSTEM', '绯荤粺绠＄悊'); // Translate
@define('WEBSERVER_SAPI', '涓绘満 SAPI'); // Translate
@define('TEMPLATE_SET', '\'%s\' 宸茶璁惧畾涓轰富棰?); // Translate
@define('SEARCH_ERROR', '鎼滅储鍔熻兘鍑虹幇閿欒锛屾姤鍛婄鐞嗗憳:鍙戠敓杩欎釜閿欒鍙兘鏁版嵁搴撴病鏈夋纭殑index keys,濡傛灉浣跨敤MYSQL锛屼綘鐨勫笎鍙峰繀椤诲彲浠ユ墽琛?<pre>CREATE FULLTEXT INDEX entry_idx on %sentries (title,body,extended)</pre> 鐨勬潈闄愶紝鏁版嵁搴撴樉绀虹殑閿欒鏄? <pre>%s</pre>'); // Translate
@define('EDIT_THIS_CAT', '缂栬緫 "%s"'); // Translate
@define('CATEGORY_REMAINING', '鍒犻櫎杩欎釜鍒嗙被鐒跺悗灏嗘枃绔犺浆鍒拌繖涓垎绫?); // Translate
@define('CATEGORY_INDEX', '涓嬮潰鏄彲浠ヨ浆绉荤殑鍒嗙被'); // Translate
@define('NO_CATEGORIES', '娌℃湁鍒嗙被'); // Translate
@define('RESET_DATE', '閲嶈鏃ユ湡'); // Translate
@define('RESET_DATE_DESC', '鐐硅繖閲岄噸璁炬棩鏈?); // Translate
@define('PROBLEM_PERMISSIONS_HOWTO', '鏉冮檺鍙互浣跨敤涓嬮潰鐨?shell 鏉ユ墽琛? `<em>%s</em>` 鐒跺悗鎵ц瑕佹洿鏀圭殑鏂囦欢澶? 鎴栦娇鐢?FTP 杞欢'); // Translate
@define('WARNING_TEMPLATE_DEPRECATED', '璀﹀憡:  浣犵洰鍓嶄娇鐢ㄧ殑涓婚鏄棫鏂规硶鍒朵綔鐨勶紝璇峰敖蹇洿鏂?); // Translate
@define('ENTRY_PUBLISHED_FUTURE', '杩欑瘒鏂囩珷鏈叕寮€'); // Translate
@define('ENTRIES_BY', '浣滆€?%s'); // Translate
@define('PREVIOUS', '涓婁竴椤?); // Translate
@define('NEXT', '涓嬩竴椤?);
@define('APPROVE', '瀹℃牳');

@define('DO_MARKUP_DESCRIPTION', '濂楃敤鑷姩鍖栨牸寮忓埌鏂囩珷鍐?(琛ㄦ儏, 绗﹀彿 *, /, _, ...)銆傚叧闂繖椤瑰姛鑳藉皢浼氫繚瀛樹换浣曟枃绔犲唴鍑虹幇鐨?HTML 璇硶銆?);
@define('CATEGORY_ALREADY_EXIST', '绫诲埆 "%s" 宸茬粡瀛樺湪');
@define('IMPORT_NOTES', '娉ㄦ剰:');
@define('ERROR_FILE_FORBIDDEN', '浣犱笉鑳戒笂浼犳鏂囦欢');
@define('ADMIN', '涓昏璁惧畾');
@define('ADMIN_FRONTPAGE', '棣栭〉');
@define('QUOTE', '寮曠敤');
@define('IFRAME_SAVE', '姝ｅ湪淇濆瓨鏂囩珷锛屽缓绔嬪紩鐢ㄥ拰鎵ц XML-RPC calls锛岃绋嶅悗..');
@define('IFRAME_SAVE_DRAFT', '鏂囩珷鑽夌宸茶淇濆瓨');
@define('IFRAME_PREVIEW', '姝ｅ湪寤虹珛浣犵殑棰勮鏂囩珷...');
@define('IFRAME_WARNING', '浣犵殑娴忚鍣ㄤ笉鏀寔 iframes. 璇锋墦寮€ serendipity_config.inc.php 鏂囦欢鐒跺悗璁惧畾 $serendipity[\'use_iframe\'] 涓?FALSE銆?);
@define('NONE', 'None');
@define('USERCONF_CAT_DEFAULT_NEW_ENTRY', '鏂版枃绔犲皢浣跨敤棰勮璁惧畾');
@define('UPGRADE', '鏇存柊');
@define('UPGRADE_TO_VERSION', '鏇存柊鑷虫柊鐗堟湰 %s');
@define('DELETE_DIRECTORY', '鍒犻櫎鐩綍');
@define('DELETE_DIRECTORY_DESC', '鍒犻櫎鐩綍鍐呯殑濯掍綋鏂囦欢锛屾敞鎰忔枃浠朵篃璁稿嚭鐜板湪鍏跺畠鏂囩珷鍐呫€?);
@define('FORCE_DELETE', '鍒犻櫎姝ょ洰褰曞唴鐨勬枃浠讹紝鍖呮嫭鏃犳硶璇嗗埆鐨勬枃浠?);
@define('CREATE_DIRECTORY', '寤虹珛鐩綍');
@define('CREATE_NEW_DIRECTORY', '寤虹珛鏂扮洰褰?);
@define('CREATE_DIRECTORY_DESC', '鍦ㄨ繖閲屼綘鍙互寤虹珛鏂扮殑鐩綍鏉ュ瓨鏀惧獟浣撴枃浠躲€傝緭鍏ョ洰褰曞悕绉板悗浣犲彲浠ラ€夋嫨鏄惁灏嗗畠鏀惧埌涓荤洰褰曞唴銆?);
@define('BASE_DIRECTORY', '鍩烘湰鐩綍');
@define('USERLEVEL_EDITOR_DESC', '涓€鑸綔鑰?);
@define('USERLEVEL_CHIEF_DESC', '涓荤紪');
@define('USERLEVEL_ADMIN_DESC', '绠＄悊鍛?);
@define('USERCONF_USERLEVEL', '鏉冮檺');
@define('USERCONF_USERLEVEL_DESC', '杩欎釜閫夐」鍙互璁惧畾姝や綔鑰呭湪杩欎釜鏃ュ織鍐呯殑鏉冮檺');
@define('USER_SELF_INFO', '鐧诲叆鐢ㄦ埛鏄?%s (%s)');
@define('ADMIN_ENTRIES', '鏂囩珷绠＄悊');// 杩欐槸鍚庡彴鑿滃崟鐨勬枃绔犵鐞
@define('RECHECK_INSTALLATION', '閲嶆柊妫€鏌ュ畨瑁呯▼搴?);
@define('IMAGICK_EXEC_ERROR', '鏃犳硶鎵ц: "%s", 閿欒: %s, 绯绘暟: %d');
@define('INSTALL_OFFSET_DESC', '浠ュ皬鏃惰绠楋紝璇疯緭鍏ヤ富鏈虹殑鏃堕棿 (鐩墠: %clock%) 璺熶綘鐨勬椂宸?);
@define('UNMET_REQUIREMENTS', '鏈揪鍒伴渶姹? %s');
@define('CHARSET', '缂栫爜');
@define('AUTOLANG', '浣跨敤娴忚鍣ㄥ唴璁惧畾鐨勭紪鐮?);
@define('AUTOLANG_DESC', '濡傛灉寮€鍚繖涓姛鑳藉皢浣跨敤娴忚鍣ㄥ唴璁惧畾鐨勭紪鐮?);
@define('INSTALL_AUTODETECT_URL', '鑷姩妫€娴?HTTP-Host');
@define('INSTALL_AUTODETECT_URL_DESC', '濡傛灉璁惧畾涓?"true"锛孒TTP Host 璺熷熀鏈殑鍦板潃璁惧畾鐩稿悓銆傚紑鍚繖椤瑰姛鑳藉彲浠ュ厑璁镐綘浣跨敤澶氫釜鐨勫煙鍚嶇殑鏃ュ織鍜屼娇鐢ㄨ繖涓棩蹇楀煙鍚嶈繛鎺ャ€?);
@define('CONVERT_HTMLENTITIES', '鑷姩鏀瑰彉 HTML 鐨勬爣绛?);
@define('EMPTY_SETTING', '浣犳病鏈夋彁渚?"%s" 鐨勬纭弬鏁?);
@define('USERCONF_REALNAME', '鍏ㄥ悕');
@define('USERCONF_REALNAME_DESC', '浣滆€呭叏鍚嶏紝灏嗘樉绀哄叏閮ㄨ鑰?);
@define('HOTLINK_DONE', '鏂囦欢澶栭儴杩炴帴<br />缁撴潫銆?);
@define('ENTER_MEDIA_URL_METHOD', '鍙栧緱鏂规硶:');
@define('ADD_MEDIA_BLAHBLAH_NOTE', '娉ㄦ剰:濡傛灉浣犻€夋嫨澶栭儴杩炴帴锛岃鍏堝緱鍒版潵婧愮綉绔欑殑鍏佽銆傚閮ㄨ繛鎺ュ厑璁镐綘鐢ㄥ叾瀹冪綉绔欑殑鍥剧墖鑰屼笉闇€瑕佸皢鍥剧墖淇濆瓨鍦ㄤ綘鐨勪富鏈哄唴銆?);
@define('MEDIA_HOTLINKED', '澶栭儴杩炴帴鍥剧墖');
@define('FETCH_METHOD_IMAGE', '涓嬭浇鍥剧墖鍒颁富鏈?);
@define('FETCH_METHOD_HOTLINK', '澶栭儴杩炴帴鍒颁富鏈?);
@define('DELETE_HOTLINK_FILE', '鍒犻櫎澶栭儴杩炴帴鐨勬枃浠?<b>%s</b>');
@define('SYNDICATION_PLUGIN_SHOW_MAIL', '鏄剧ず鐢靛瓙閭欢');
@define('IMAGE_MORE_INPUT', '鏂板鍥剧墖');
@define('BACKEND_TITLE', '澶栨寕閰嶇疆椤甸潰鐨勯澶栦俊鎭?);
@define('BACKEND_TITLE_FOR_NUGGET', '杩欓噷浣犲彲浠ヨ瀹氫竴浜涜嚜瀹氭枃瀛楋紝瀹冭窡 HTML Nugget 澶栨寕涓€鏍蜂細鏄剧ず杞藉鎸傞厤缃〉闈€傚鏋滀綘鏈夊涓爣棰樼殑 HTML Nuggets锛岃繖涓彲浠ヨ浣犲垎杈ㄥ涓浉鍚岀殑澶栨寕銆?);
@define('CATEGORIES_ALLOW_SELECT', '鍏佽璁垮鏄剧ず澶氫釜绫诲埆');
@define('CATEGORIES_ALLOW_SELECT_DESC', '濡傛灉寮€鍚繖涓€夐」锛屽湪 sidebar 澶栨寕閲岀殑绫诲埆鏃佽竟浼氬嚭鐜板嬀閫夎彍鍗曘€備細鍛樺彲浠ュ嬀閫夎鏄剧ず鐨勭被鍒€?);
@define('PAGE_BROWSE_PLUGINS', '椤垫暟 %s 鍏?%s, 鎬诲叡 %s 涓鎸傘€?);
@define('INSTALL_CAT_PERMALINKS', '闈欐€佽繛鎺?);
@define('INSTALL_CAT_PERMALINKS_DESC', '鍒╃敤鍚勭涓嶅悓鍦板潃鏍峰紡鏉ュ畾涔夐潤鎬佽繛鎺ャ€傚缓璁綘鐢ㄩ璁剧殑鏍峰紡锛屾垨浣跨敤 %id% 鍊兼潵閬垮厤鏁版嵁搴撳鎵惧湴鍧€鐩爣銆?);
@define('INSTALL_PERMALINK', '鏂囩珷鐨勯潤鎬佽繛鎺?);
@define('INSTALL_PERMALINK_DESC', '杩欓噷鍙互璁╀綘璁惧畾浠ュ熀鏈綅缃潵璁＄畻鏂囩珷鐨勭浉瀵硅繛鎺ャ€備綘鍙互鐢ㄤ互涓嬪弬鏁帮細%id%, %title%, %day%, %month%, %year% 鎴栧叾瀹冨瓧绗︺€?);
@define('INSTALL_PERMALINK_AUTHOR', '浣滆€呯殑闈欐€佽繛鎺?);
@define('INSTALL_PERMALINK_AUTHOR_DESC', '杩欓噷鍙互璁╀綘璁惧畾浠ュ熀鏈綅缃潵璁＄畻鏂囩珷鐨勭浉瀵硅繛鎺ャ€備綘鍙互鐢ㄤ互涓嬪弬鏁帮細%id%, %realname%, %username%, %email% 鎴栧叾瀹冨瓧绗︺€?);
@define('INSTALL_PERMALINK_CATEGORY', '绫诲埆鐨勯潤鎬佽繛鎺?);
@define('INSTALL_PERMALINK_CATEGORY_DESC', '杩欓噷鍙互璁╀綘璁惧畾浠ュ熀鏈綅缃潵璁＄畻鏂囩珷鐨勭浉瀵硅繛鎺ャ€備綘鍙互鐢ㄤ互涓嬪弬鏁帮細%id%, %name%, %parentname%, %description% 鎴栧叾瀹冨瓧绗︺€?);
@define('INSTALL_PERMALINK_FEEDCATEGORY', 'RSS-Feed 绫诲埆鐨勯潤鎬佽繛鎺?);
@define('INSTALL_PERMALINK_FEEDCATEGORY_DESC', '杩欓噷鍙互璁╀綘璁惧畾浠ュ熀鏈綅缃潵璁＄畻 RSS-Feed 绫诲埆鏂囩珷鐨勭浉瀵硅繛鎺ャ€備綘鍙互鐢ㄤ互涓嬪弬鏁帮細%id%, %name%, %description% 鎴栧叾瀹冨瓧绗︺€?);
@define('INSTALL_PERMALINK_ARCHIVESPATH', '淇濆瓨鏂囦欢璺緞');
@define('INSTALL_PERMALINK_ARCHIVEPATH', '淇濆瓨鏂囦欢璺緞');
@define('INSTALL_PERMALINK_CATEGORIESPATH', '绫诲埆璺緞');
@define('INSTALL_PERMALINK_UNSUBSCRIBEPATH', '鍙嶈闃呭洖澶嶈矾寰?);
@define('INSTALL_PERMALINK_DELETEPATH', '鍒犻櫎鍥炲璺緞');
@define('INSTALL_PERMALINK_APPROVEPATH', '鏍稿噯鍥炲璺緞');
@define('INSTALL_PERMALINK_FEEDSPATH', 'RSS Feeds 璺緞');
@define('INSTALL_PERMALINK_PLUGINPATH', '鍗曞鎸傝矾寰?);
@define('INSTALL_PERMALINK_ADMINPATH', '绠＄悊璺緞');
@define('INSTALL_PERMALINK_SEARCHPATH', '鎼滅储璺緞');
@define('INSTALL_CAL', '鏃ュ巻绫诲瀷');
@define('INSTALL_CAL_DESC', '璇烽€夋嫨浣犺鐨勬棩鍘嗙被鍨?);
@define('REPLY', '鍥炲');
@define('USERCONF_GROUPS', '浼氬憳缇ょ粍');
@define('USERCONF_GROUPS_DESC', '姝や細鍛樹笅闈㈢殑缇ょ粍缁勫憳銆備細鍛樺彲浠ュ姞鍏ュ涓兢缁勩€?);
@define('MANAGE_GROUPS', '绠＄悊缇ょ粍');
@define('DELETED_GROUP', '缇ょ粍 #%d %s 宸插垹闄?);
@define('CREATED_GROUP', '鏂扮兢缁?%s 宸叉柊澧?);
@define('MODIFIED_GROUP', '缇ょ粍 %s 鐨勮瀹氬凡琚敼鍙?);
@define('GROUP', '缇ょ粍');
@define('CREATE_NEW_GROUP', '鏂板缇ょ粍');
@define('DELETE_GROUP', '纭畾瑕佸垹闄ょ兢缁?#%d %s ');
@define('USERLEVEL_OBSOLETE', '娉ㄦ剰: 浼氬憳鏉冮檺鐨勫睘鎬у彧鏄负浜嗗尯鍒嗗洖澶嶇殑鍏煎鍜屽鎸傛巿鏉冦€傜郴缁熺幇鍦ㄤ娇鐢ㄤ簡鏂扮殑浼氬憳鏉冮檺銆?);
@define('SYNDICATION_PLUGIN_FEEDBURNERID', 'FeedBurner ID');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_DESC', '浣犺鍙戝竷鏂囩珷鐨?ID');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG', 'FeedBurner 鍥剧墖');
@define('SYNDICATION_PLUGIN_FEEDBURNERIMG_DESC', '浣嶄簬 feedburner.com 鐨勫浘鐗囨樉绀虹殑鍚嶇О (鎴栫┖鐧芥樉绀烘暟閲?銆備緥濡傦細fbapix.gif');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE', 'FeedBurner 鏍囬');
@define('SYNDICATION_PLUGIN_FEEDBURNERTITLE_DESC', '鏄剧ず浜庡浘鐗囨梺鐨勬爣棰?(濡傛灉鏈?');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT', 'FeedBurner 鍥剧墖鏂囧瓧');
@define('SYNDICATION_PLUGIN_FEEDBURNERALT_DESC', '榧犳爣鍦ㄥ浘鐗囨椂鏄剧ず鐨勬枃瀛?(濡傛灉鏈?');
@define('SEARCH_TOO_SHORT', '鎼滃瀛楀繀椤诲ぇ浜?瀛楄妭銆備綘鍙互浣跨敤 * 鏉ヤ唬鏇匡紝濡傛灉鎼滃瀛楄妭灏忎簬 3 瀛楄妭銆備緥濡傦細s9y*銆?);
@define('INSTALL_DBPORT', '鏁版嵁搴撹繛鎺ョ');
@define('INSTALL_DBPORT_DESC', '杩炴帴鏁版嵁搴撴墍浣跨敤鐨勮繛鎺ョ');
@define('PLUGIN_GROUP_FRONTEND_EXTERNAL_SERVICES', '鍓嶇锛氬閮ㄦ湇鍔?);
@define('PLUGIN_GROUP_FRONTEND_FEATURES', '鍓嶇锛氬姛鑳?);
@define('PLUGIN_GROUP_FRONTEND_FULL_MODS', '鍓嶇锛氬畬鏁村鎸?);
@define('PLUGIN_GROUP_FRONTEND_VIEWS', '鍓嶇锛氭祻瑙?);
@define('PLUGIN_GROUP_FRONTEND_ENTRY_RELATED', '鍓嶇锛氱浉鍏虫枃绔?);
@define('PLUGIN_GROUP_BACKEND_EDITOR', '鍚庣锛氱紪杈戝櫒');
@define('PLUGIN_GROUP_BACKEND_USERMANAGEMENT', '鍚庣锛氫細鍛樼鐞?);
@define('PLUGIN_GROUP_BACKEND_METAINFORMATION', '鍚庣锛歁eta 璧勬枡');
@define('PLUGIN_GROUP_BACKEND_TEMPLATES', '鍚庣锛氫富棰?);
@define('PLUGIN_GROUP_BACKEND_FEATURES', '鍚庣锛氬姛鑳?);
@define('PLUGIN_GROUP_IMAGES', '鍥剧墖');
@define('PLUGIN_GROUP_ANTISPAM', '闃叉骞垮憡');
@define('PLUGIN_GROUP_MARKUP', '鏍囪');
@define('PLUGIN_GROUP_STATISTICS', '缁熻璧勬枡');
@define('PERMISSION_PERSONALCONFIGURATION', '璇诲彇绉佷汉璁惧畾');
@define('PERMISSION_PERSONALCONFIGURATIONUSERLEVEL', '鏀瑰彉浼氬憳鏉冮檺');
@define('PERMISSION_PERSONALCONFIGURATIONNOCREATE', '鍙樻洿 "绂佹寤虹珛鏂囩珷"');
@define('PERMISSION_PERSONALCONFIGURATIONRIGHTPUBLISH', '鍙樻洿鍙戝竷鏂囩珷鐨勬潈闄?);
@define('PERMISSION_SITECONFIGURATION', '璇诲彇绯荤粺璁惧畾');
@define('PERMISSION_BLOGCONFIGURATION', '璇诲彇鏃ュ織璁惧畾');
@define('PERMISSION_ADMINENTRIES', '绠＄悊鏂囩珷');
@define('PERMISSION_ADMINENTRIESMAINTAINOTHERS', '绠＄悊浼氬憳鐨勬枃绔?);
@define('PERMISSION_ADMINIMPORT', '杈撳叆鏂囩珷');
@define('PERMISSION_ADMINCATEGORIES', '绠＄悊绫诲埆');
@define('PERMISSION_ADMINCATEGORIESMAINTAINOTHERS', '绠＄悊浼氬憳鐨勭被鍒?);
@define('PERMISSION_ADMINCATEGORIESDELETE', '鍒犻櫎');
@define('PERMISSION_ADMINUSERS', '绠＄悊浼氬憳');
@define('PERMISSION_ADMINUSERSDELETE', '鍒犻櫎浼氬憳');
@define('PERMISSION_ADMINUSERSEDITUSERLEVEL', '鍙樻洿鏉冮檺');
@define('PERMISSION_ADMINUSERSMAINTAINSAME', '绠＄悊鐩稿悓缇ょ粍鐨勪細鍛?);
@define('PERMISSION_ADMINUSERSMAINTAINOTHERS', 'admin: maintainOthers');
@define('PERMISSION_ADMINUSERSCREATENEW', '鏂板浼氬憳');
@define('PERMISSION_ADMINUSERSGROUPS', '绠＄悊缇ょ粍');
@define('PERMISSION_ADMINPLUGINS', '绠＄悊澶栨寕');
@define('PERMISSION_ADMINPLUGINSMAINTAINOTHERS', '绠＄悊浼氬憳鐨勫鎸?);
@define('PERMISSION_ADMINIMAGES', '绠＄悊濯掍綋鏂囦欢');
@define('PERMISSION_ADMINIMAGESDIRECTORIES', '绠＄悊濯掍綋鐩綍');
@define('PERMISSION_ADMINIMAGESADD', '鏂板濯掍綋鏂囦欢');
@define('PERMISSION_ADMINIMAGESDELETE', '鍒犻櫎濯掍綋鏂囦欢');
@define('PERMISSION_ADMINIMAGESMAINTAINOTHERS', '绠＄悊浼氬憳鐨勫獟浣撴枃浠?);
@define('PERMISSION_ADMINIMAGESVIEW', '娴忚濯掍綋鏂囦欢');
@define('PERMISSION_ADMINIMAGESSYNC', '鍚屾缂╁浘');
@define('PERMISSION_ADMINCOMMENTS', '绠＄悊鍥炲');
@define('PERMISSION_ADMINTEMPLATES', '绠＄悊涓婚');
@define('INSTALL_BLOG_EMAIL', '缃戠珯鐨勭數瀛愰偖浠?);
@define('INSTALL_BLOG_EMAIL_DESC', '杩欎細璁惧畾浣犵殑鐢靛瓙閭欢锛屼换浣曟棩蹇楀唴瀵勫嚭鐨勯偖浠跺皢浼氭樉绀鸿繖涓數瀛愰偖浠跺湴鍧€銆傝寰楄繖涓數瀛愰偖浠跺繀椤荤敤鍦ㄤ綘鐨勪富鏈哄唴锛屽緢澶氫富鏈轰細鎷掔粷鎺ユ敹涓嶆槑鐨勯偖浠躲€?);
@define('CATEGORIES_PARENT_BASE', '鍙樉绀轰互涓嬬被鍒?..');
@define('CATEGORIES_PARENT_BASE_DESC', '浣犲彲浠ラ€夋嫨涓€涓富绫诲埆锛屽彧鏄剧ず瀹冧笅闈㈢殑瀛愮被鍒€?);
@define('CATEGORIES_HIDE_PARALLEL', '闅愯棌涓嶅湪绫诲埆缁撴瀯鐨勭被鍒?);
@define('CATEGORIES_HIDE_PARALLEL_DESC', '濡傛灉闅愯棌浣嶄簬鍏跺畠绫诲埆缁撴瀯鐨勭被鍒紝浣犲繀椤诲厛寮€鍚繖涓瀹氥€傝繖涓姛鑳介€氬父鏄敤鍦ㄥ閲嶆棩蹇楃殑澶栨寕銆?);
@define('PERMISSION_ADMINIMAGESVIEWOTHERS', '娴忚浼氬憳鐨勫獟浣撴枃浠?);
@define('CHARSET_NATIVE', '棰勮');
@define('INSTALL_CHARSET', '璇█閫夐」');
@define('INSTALL_CHARSET_DESC', '杩欓噷鍙互璁╀綘杞崲 UTF-8 鎴栭璁剧紪鐮?(ISO, UTF-8, ...)鏈変簺璇█鍖呭彧鏈?UTF-8 缂栫爜锛屾墍浠ユ崲鎴愰璁捐瑷€鏈変笉浼氫换浣曟敼鍙樸€傛柊瀹夎鐨勬棩蹇楀缓璁娇鐢?UTF-8 缂栫爜銆傝寰椾笉瑕佹敼鍙樿繖涓瀹氬鏋滀綘宸茬粡鍙戝竷浜嗘枃绔犮€傝鎯呭弬闃?http://www.s9y.org/index.php?node=46');
@define('CALENDAR_ENABLE_EXTERNAL_EVENTS', 'External Events');
@define('CALENDAR_EXTEVENT_DESC', '濡傛灉寮€鍚紝澶栨寕鍙互鍦ㄦ棩鍘嗗唴浠ラ鑹叉樉绀哄畠鐨勪簨浠躲€傚鏋滄病鏈変娇鐢ㄨ繖浜涚壒娈婄殑澶栨寕锛屽缓璁笉瑕佷娇鐢ㄣ€?);
@define('XMLRPC_NO_LONGER_BUNDLED', 'XML-RPC API 鍔熻兘涓嶄細娣诲姞鍦?s9y 鐨勫畨瑁呴噷锛屽洜涓烘紡娲炲拰涓嶅浜轰娇鐢ㄧ殑鍏崇郴銆傛墍浠ュ繀椤诲畨瑁?XML-RPC 鐨勫鎸傚鏋滀娇鐢?XML-RPC API銆傛墍鏈夌殑 URL 涓嶄細鍥犳鏀瑰彉锛屽畨瑁呰繖涓鎸傚悗椹笂浣跨敤銆?);
@define('PERM_READ', '璇诲彇鏉冮檺');
@define('PERM_WRITE', '鍐欏叆鏉冮檺');

@define('PERM_DENIED', '鏉冮檺鎷掔粷');
@define('INSTALL_ACL', '濂楀叆璇诲彇鐨勬潈闄愬埌绫诲埆');
@define('INSTALL_ACL_DESC', '濡傛灉寮€鍚紝缇ょ粍瀵圭被鍒殑鏉冮檺璁惧畾灏嗕細濂楃敤鍒扮櫥鍏ョ殑浼氬憳銆傚鏋滃叧闂紝绫诲埆鐨勮鍙栨潈闄愪笉浼氳浣跨敤锛屼絾鏄細鍔犲揩浣犳棩蹇楃殑閫熷害銆傚鏋滀綘涓嶉渶瑕佸涓娇鐢ㄨ€呯殑璇诲彇鏉冮檺锛屽缓璁綘灏嗚繖涓瀹氬叧闂€?);
@define('PLUGIN_API_VALIDATE_ERROR', '閰嶇疆鐨勮瀹?"%s" 璇硶鏈夎锛岄渶瑕?"%s" 绫诲瀷銆?);
@define('USERCONF_CHECK_PASSWORD', '鏃у瘑鐮?);
@define('USERCONF_CHECK_PASSWORD_DESC', '濡傛灉浣犺鏇存敼瀵嗙爜锛岃灏嗘柊瀵嗙爜杈撳叆鍒拌繖閲屻€?);
@define('USERCONF_CHECK_PASSWORD_ERROR', '浣犳彁渚涗簡閿欒鐨勪箙瀵嗙爜鎵€浠ヤ笉鑳芥洿鏀瑰瘑鐮併€備綘鐨勮瀹氭湭淇濆瓨銆?);
@define('ERROR_XSRF', '浣犵殑娴忚鍣ㄤ紶閫佷簡閿欒鐨?HTTP-Referrer 瀛楃銆傚彲鑳芥槸鍥犱负 browser/proxy 鐨勯敊璇瀹氭垨鏄?Cross Site Request Forgery (XSRF) 鐨勫叧绯汇€備綘鐨勬搷浣滄棤娉曞畬鎴愩€?);
@define('INSTALL_PERMALINK_FEEDAUTHOR_DESC', '杩欓噷鍙互璁╀綘瀹氫箟鐩稿 URL 锛屼粠鍩烘湰 URL 鍒颁細鍛樿鍙栫殑 RSS-feeds 涓烘爣鍑嗐€備綘鍙互鐢ㄨ繖浜涘弬鏁?%id%, %realname%, %username%, %email% 鎴栧叾瀹冨瓧绗︺€?);
@define('INSTALL_PERMALINK_FEEDAUTHOR', 'Permalink RSS-Feed 浣滆€呯殑 URL');
@define('INSTALL_PERMALINK_AUTHORSPATH', '浣滆€呰矾寰?);
@define('AUTHORS', '浣滆€?);
@define('AUTHORS_ALLOW_SELECT', '鍏佽璁垮鏄剧ず澶氫綅浣滆€?');
@define('AUTHORS_ALLOW_SELECT_DESC', '濡傛灉鍏佽杩欎釜閫夐」锛岃瀹㈠彲浠ュ嬀閫夎璇诲彇鐨勪綔鑰呯殑鏂囩珷銆?);
@define('AUTHOR_PLUGIN_DESC', '鏄剧ず浣滆€呭垪琛?);
@define('CATEGORY_PLUGIN_TEMPLATE', '寮€鍚?Smarty-Templates');
@define('CATEGORY_PLUGIN_TEMPLATE_DESC', '濡傛灉寮€鍚繖涓€夐」锛屽鎸備細鍒╃敤 Smarty-Templating 鐨勫姛鑳芥潵杈撳嚭绫诲埆鍒楄〃銆備綘涔熷彲浠ョ敤 "plugin_categories.tpl" 鐨勬ā鐗堟枃浠舵潵鏀瑰彉澶栬銆傝繖涓€夐」浼氬噺浣庣綉椤电殑鏄剧ず閫熷害锛屽鏋滀綘涓嶅仛浠讳綍鏀瑰彉锛屾渶濂藉叧闂繖涓€夐」銆?);
@define('CATEGORY_PLUGIN_SHOWCOUNT', '鏄剧ず姣忎釜绫诲埆鐨勬枃绔犳暟');
@define('AUTHORS_SHOW_ARTICLE_COUNT', '鏄剧ず浣滆€呯殑鏂囩珷鏁?);
@define('AUTHORS_SHOW_ARTICLE_COUNT_DESC', '濡傛灉寮€鍚繖涓瀹氾紝浣滆€呯殑鏂囩珷浼氭樉绀哄湪鍚嶇О鏃併€?);
@define('CUSTOM_ADMIN_INTERFACE', '鍙敤鑷鐨勭鐞嗙晫闈?);

@define('COMMENT_NOT_ADDED', '浣犵殑鍥炲涓嶈兘鍔犲叆鍥犱负杩欑瘒鏂囩珷涓嶅厑璁稿洖澶嶏紝杈撳叆浜嗛敊璇俊鎭紝鎴栦笉閫氳繃鍨冨溇绠＄悊銆?);
@define('INSTALL_TRACKREF', '璁板綍鏉ユ簮');
@define('INSTALL_TRACKREF_DESC', '寮€鍚褰曟潵婧愪細鏄剧ず閭ｄ釜缃戠珯寮曠敤浜嗕綘鐨勬枃绔犮€備綘鍙互鍏抽棴杩欎釜鍔熻兘濡傛灉浣犳敹鍒板お澶氬瀮鍦惧箍鍛娿€?);
@define('CATEGORIES_HIDE_PARENT', '闅愯棌閫夋嫨鐨勭被鍒?);
@define('CATEGORIES_HIDE_PARENT_DESC', '褰撲綘闄愬埗绫诲埆鏄剧ず鐨勫垪琛紝棰勮鏄細鏄剧ず涓荤被鍒殑鍚嶇О銆傚鏋滃紑鍚繖涓姛鑳斤紝涓荤被鍒殑鍚嶇О灏嗕笉浼氭樉绀恒€?);
@define('WARNING_NO_GROUPS_SELECTED', '璀﹀憡锛氫綘娌℃湁閫夋嫨浼氬憳缇ょ粍銆傝繖浼氬皢浣犵櫥鍑虹兢缁勭殑绠＄悊锛屼細鍛樼殑缇ょ粍涓嶄細琚敼鍙樸€?);
@define('INSTALL_RSSFETCHLIMIT', 'Entries to display in Feeds');
@define('INSTALL_RSSFETCHLIMIT_DESC', 'RSS Feed 椤甸潰閲屾樉绀虹殑鏂囩珷鏁伴噺銆?);
@define('INSTAL_DB_UTF8', '寮€鍚暟鎹簱缂栫爜杞崲');
@define('INSTAL_DB_UTF8_DESC', '浣跨敤 MySQL 鐨?"SET NAMES" 鏌ヨ鏉ヨ瀹氱紪鐮併€傚鏋滄枃绔犲嚭鐜颁贡鐮佸彲浠ュ皢杩欒瀹氭墦寮€鎴栧叧闂€?);
@define('ONTHEFLYSYNCH', '寮€鍚獟浣撳悓姝?);
@define('ONTHEFLYSYNCH_DESC', '濡傛灉寮€鍚紝Serendipity Blog浼氭瘮杈冩暟鎹簱鍜屽獟浣撶洰褰曠殑鏂囦欢锛岀劧鍚庤繘琛屾暟鎹悓姝ャ€?);
@define('USERCONF_CHECK_USERNAME_ERROR', '甯愬彿涓嶈兘绌虹櫧');
@define('FURTHER_LINKS', '鏇村杩炴帴');
@define('FURTHER_LINKS_S9Y', '瀹樻柟棣栭〉');
@define('FURTHER_LINKS_S9Y_DOCS', '涓枃鏀寔');
@define('FURTHER_LINKS_S9Y_BLOG', '椹跨珯鏃ュ織');
@define('FURTHER_LINKS_S9Y_FORUMS', '涓枃璁哄潧');
@define('FURTHER_LINKS_S9Y_SPARTACUS', '澶栨寕涓婚');
@define('COMMENT_IS_DELETED', '(鍥炲琚垹闄?');

@define('CURRENT_AUTHOR', '鐩墠鐨勪綔鑰?);

@define('WORD_NEW', '鏂?);
@define('SHOW_MEDIA_TOOLBAR', '鍦ㄩ€夋嫨濯掍綋鐨勮绐楅噷鏄剧ず宸ュ叿鏍?);
@define('MEDIA_KEYWORDS', '濯掍綋鐨勫叧閿瓧');
@define('MEDIA_KEYWORDS_DESC', '杈撳叆棰勮鐨勫獟浣撳叧閿瓧锛岀敤 ";" 鏉ュ垎寮€姣忎釜鍏抽敭瀛椼€?);
@define('MEDIA_EXIF', '杈撳叆 EXIF/JPEG 鍥剧墖璧勬枡');
@define('MEDIA_EXIF_DESC', '濡傛灉寮€鍚紝EXIF/JPEG 鍥惧簱閲岀殑 metadata 浼氳嚜鍔ㄤ繚瀛樺埌鏁版嵁搴撱€?);
@define('MEDIA_PROP', '濯掍綋鍐呭');


@define('GO_ADD_PROPERTIES', '杈撳叆鍐呭');
@define('MEDIA_PROPERTY_DPI', 'DPI');
@define('MEDIA_PROPERTY_COPYRIGHT', '鐗堟潈');
@define('MEDIA_PROPERTY_COMMENT1', '鐭粙缁?);
@define('MEDIA_PROPERTY_COMMENT2', '闀夸粙缁?);
@define('MEDIA_PROPERTY_TITLE', '鏍囬');
@define('MEDIA_PROP_DESC', '杈撳叆濯掍綋浣跨敤鐨勫唴瀹硅彍鍗曪紝鐢?";" 鏉ュ垎寮€姣忎釜鑿滃崟鐨勫悕绉?);
@define('MEDIA_PROP_MULTIDESC', '(浣犲彲浠ュ湪鍚嶇О鍚庨潰鍔犱笂 ":MULTI" 鏉ヨ瀹氬姞澶у畠鐨勬枃瀛楅檺鍒?');

@define('STYLE_OPTIONS_NONE', '杩欎釜涓婚娌℃湁鐗瑰埆鐨勯€夐」銆傚鏋滆鍦ㄤ綘鐨勪富棰橀噷鍔犱笂閫夐」锛岃娴忚 www.s9y.org 鍐呯殑 Technical Documentation锛岀劧鍚?"Configuration of Theme options"銆?);
@define('STYLE_OPTIONS', '涓婚閫夐」');

@define('PLUGIN_AVAILABLE_COUNT', '鎬诲叡锛?%d 涓鎸?);

@define('SYNDICATION_RFC2616', '寮€鍚緷鐓т弗鏍肩殑 RFC2616 RSS-Feed');
@define('SYNDICATION_RFC2616_DESC', '涓嶅己鍒?RFC2616 琛ㄧず鍏ㄩ儴鏈夋潯浠剁殑 GETs 鍒?Serendipity Blog 鍙細浼犲洖鏈€鍚庝慨鏀圭殑鏂囩珷銆傚鏋滆瀹氫负 "false" 琛ㄧず璁垮鎺ュ彈鍏ㄩ儴鐨勬枃绔犮€備笉杩囷紝涓€浜涙棩蹇楃殑绋嬪簭鍍?Planet 浼氬嚭鐜板鎬幇璞°€傚鏋滃嚭鐜板鎬幇璞¤〃绀哄畠杩濆弽浜?RFC2616 鐨勬爣鍑嗐€傛墍浠ヨ瀹氫负 "TRUE" 琛ㄧず浣犻伒浠?RFC 鐨勬爣鍑嗭紝浣嗚瀹㈠彲鑳借鍙栦笉鍒板叏閮ㄦ枃绔犮€傛暣浣撴潵璇达紝涓嶇鎬庢牱閮芥棤娉曠収椤惧埌涓ゆ柟銆傝鎯呰鍙傞槄锛?a href="https://sourceforge.net/tracker/index.php?func=detail&amp;aid=1461728&amp;group_id=75065&amp;atid=542822" target="_blank" rel="nofollow">SourceForge</a>');
@define('MEDIA_PROPERTY_DATE', '鐩稿叧鏃ユ湡');
@define('MEDIA_PROPERTY_RUN_LENGTH', '闀垮害');
@define('FILENAME_REASSIGNED', '鑷姩鎸囧畾鏂版枃浠跺悕绉帮細 %s');
@define('MEDIA_UPLOAD_SIZE', '鏂囦欢澶у皬鐨勪笂闄?);
@define('MEDIA_UPLOAD_SIZE_DESC', '杈撳叆鏂囦欢鐨勬渶澶у€笺€傝繖涓瀹氫篃鍙互浠庝富鏈哄唴鐨?PHP.ini 鏂囦欢鏀瑰彉锛?upload_max_filesize, post_max_size, max_input_time 鍏ㄩ儴閮借兘璁╄繖閲岀殑璁惧畾鏃犳晥銆傚鏋滀笉杈撳叆琛ㄧず閬典粠涓绘満鐨勯檺鍒躲€?);
@define('MEDIA_UPLOAD_SIZEERROR', '閿欒锛氫綘涓嶈兘涓婁紶澶т簬 %s 瀛楄妭鐨勬枃浠?);
@define('MEDIA_UPLOAD_MAXWIDTH', '鍥剧墖鏈€澶у搴?);
@define('MEDIA_UPLOAD_MAXWIDTH_DESC', '杈撳叆涓婁紶鍥剧墖鏈€澶у搴︺€?);
@define('MEDIA_UPLOAD_MAXHEIGHT', '鍥剧墖鏈€澶ч暱搴?);
@define('MEDIA_UPLOAD_MAXHEIGHT_DESC', '杈撳叆涓婁紶鍥剧墖鏈€澶ч暱搴︺€?);
@define('MEDIA_UPLOAD_DIMERROR', '閿欒锛氫綘涓嶈兘涓婁紶澶т簬 %s x %s 鐨勫浘鐗?);

@define('MEDIA_TARGET', '杩炴帴鐨勭洰鏍?);
@define('MEDIA_TARGET_JS', '寮瑰嚭鍑哄彛 (浣跨敤 JavaScript)');
@define('MEDIA_ENTRY', '闅旂鏂囩珷');
@define('MEDIA_TARGET_BLANK', '寮瑰嚭绐楀彛 (浣跨敤 target=_blank)');

@define('MEDIA_DYN_RESIZE', '鍏佽鏀瑰彉鍥剧墖澶у皬');
@define('MEDIA_DYN_RESIZE_DESC', '濡傛灉寮€鍚紝濯掍綋鐨勯€夋嫨瑙嗙獥閲屾樉绀轰緷鐓?GET 鍙傛暟鎵€璁惧畾鐨勫浘鐗囧ぇ灏忋€傚浘鐗囦細淇濆瓨浜庣紦瀛樺唴锛屾墍浠ュ父浣跨敤浼氬崰鐢ㄤ富鏈虹殑绌洪棿銆?);

@define('MEDIA_DIRECTORY_MOVED', 'Directory and files were successfully moved to %s');
@define('MEDIA_DIRECTORY_MOVE_ERROR', 'Directory and files could not be moved to %s!');
@define('MEDIA_DIRECTORY_MOVE_ENTRY', 'On Non-MySQL databases, iterating through every article to replace the old directory URLs with new directory URLs is not possible. You will need to manually edit your entries to fix new URLs. You can still move your old directory back to where it was, if that is too cumbersome for you.');
@define('MEDIA_DIRECTORY_MOVE_ENTRIES', 'Moved the URL of the moved directory in %s entries.');
@define('PLUGIN_ACTIVE', 'Active');
@define('PLUGIN_INACTIVE', 'Inactive');
@define('PREFERENCE_USE_JS', 'Enable advanced JS usage?');
@define('PREFERENCE_USE_JS_DESC', 'If enabled, advanced JavaScript sections will be enabled for better usability, like in the Plugin Configuration section you can use drag and drop for re-ordering plugins.');
@define('PREFERENCE_USE_JS_WARNING', '(This page uses advanced JavaScripting. If you are having functionality issues, please disable the use of advanced JS usage in your personal preferences or disable your browser\'s JavaScript)');
@define('INSTALL_PERMALINK_COMMENTSPATH', 'Path to comments');
@define('PERM_SET_CHILD', 'Set the same permissions on all child directories');
@define('PERMISSION_FORBIDDEN_PLUGINS', 'Forbidden plugins');
@define('PERMISSION_FORBIDDEN_HOOKS', 'Forbidden events');
@define('PERMISSION_FORBIDDEN_ENABLE', 'Enable Plugin ACL for usergroups?');
@define('PERMISSION_FORBIDDEN_ENABLE_DESC', 'If the option "Plugin ACL for usergroups" is enabled in the configuration, you can specify which usergroups are allowed to execute certain plugins/events.');
@define('DELETE_SELECTED_ENTRIES', 'Delete selected entries');
@define('PLUGIN_AUTHORS_MINCOUNT', 'Show only authors with at least X articles');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET', 'Bookmarklet');
@define('FURTHER_LINKS_S9Y_BOOKMARKLET_DESC', 'Bookmark this link and then use it on any page you want to blog about to quickly access your Serendipity Blog.');
@define('IMPORT_WP_PAGES', 'Also fetch attachments and staticpages as normal blog entries?');
@define('USERCONF_CREATE', 'Disable user / forbid activity?');
@define('USERCONF_CREATE_DESC', 'If selected, the user will not have any editing or creation possibilities on the blog anymore. When logging in to the backend, he cannot do anything else apart from logging out and viewing his personal configuration.');
@define('CATEGORY_HIDE_SUB', 'Hide postings made to sub-categories?');
@define('CATEGORY_HIDE_SUB_DESC', 'By default, when you browse a category also entries of any subcategory are displayed. If this option is turned on, only postings of the currently selected category are displayed.');
@define('PINGBACK_SENDING', 'Sending pingback to URI %s...');
@define('PINGBACK_SENT', 'Pingback successful');
@define('PINGBACK_FAILED', 'Pingback failed: %s');
@define('PINGBACK_NOT_FOUND', 'No pingback-URI found.');
@define('CATEGORY_PLUGIN_HIDEZEROCOUNT', 'Hide archives link when no entries were made in that timespan (requires counting entries)');
@define('RSS_IMPORT_WPXRSS', 'WordPress eXtended RSS import, requires PHP5 and might take up much memory');
@define('SET_TO_MODERATED', 'Moderate');
@define('COMMENT_MODERATED', 'Comment #%s has successfully been set as moderated');
@define('CENTER', 'center');
@define('FULL_COMMENT_TEXT', 'Yes, with full comment text');

@define('COMMENT_TOKENS', 'Use Tokens for Comment Moderation?');
@define('COMMENT_TOKENS_DESC', 'If tokens are used, comments can be approved and deleted by clicking the email links without requiring login access to the blog. Note that this is a convenience feature, and if your mails get hijacked, those people can approve/delete the referenced comment without further authentication.');
@define('COMMENT_NOTOKENMATCH', 'Moderation link has expired or comment #%s has already been approved or deleted');
@define('TRACKBACK_NOTOKENMATCH', 'Moderation link has expired or trackback #%s has already been approved or deleted');
@define('BADTOKEN', 'Invalid Moderation Link');

@define('CONFIRMATION_MAIL_ALWAYS', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n");
@define('CONFIRMATION_MAIL_ONCE', "Hello %s,\n\nYou have sent a new comment to \"%s\". Your comment was:\n\n%s\n\nThe owner of the blog has enabled one-time mail verification, so you need to click on the following link to authenticate your comment:\n<%s>\n\nAfter you have done that, you can always post comments on that blog with your username and e-mail address without receiving such notifications.");
@define('INSTALL_SUBSCRIBE_OPTIN', 'Use Double-Opt In for comment subscriptions?');
@define('INSTALL_SUBSCRIBE_OPTIN_DESC', 'If enabled, when a comment is made where the person wants to be notified via e-mail about new comments to the same entry, he must confirm his subscription to the entry. This Double-Opt In is required by german law, for example.');
@define('CONFIRMATION_MAIL_SUBSCRIPTION', "Hello %s,\n\nYou have requested to be notified for comments to \"%s\" (<%s>). To approve this subscription (\"Double Opt In\") please click this link:\n<%s>\n.");
@define('NOTIFICATION_CONFIRM_SUBMAIL', 'Your confirmation of your comment subscription has been successfully entered.');
@define('NOTIFICATION_CONFIRM_MAIL', 'Your confirmation of the comment has been successfully entered.');
@define('NOTIFICATION_CONFIRM_SUBMAIL_FAIL', 'Your comment subscription could not be confirmed. Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must request a new confirmation mail.');
@define('NOTIFICATION_CONFIRM_MAIL_FAIL', 'Your comment confirmation could not be confirmed.  Please check the link you clicked on for completion. If the link was sent more than 3 weeks ago, you must send your comment again.');
@define('PLUGIN_DOCUMENTATION', 'Documentation');
@define('PLUGIN_DOCUMENTATION_LOCAL', 'Local Documentation');
@define('PLUGIN_DOCUMENTATION_CHANGELOG', 'Version history');
@define('SYNDICATION_PLUGIN_BIGIMG', 'Big Image');
@define('SYNDICATION_PLUGIN_BIGIMG_DESC', 'Display a (big) image at the top of the feeds in sidebar, enter full or absolute URL to image file.');
@define('SYNDICATION_PLUGIN_FEEDNAME', 'Displayed name for "feed"');
@define('SYNDICATION_PLUGIN_FEEDNAME_DESC', 'Enter an optional custom name for the feeds (defaults to "feed" when empty)');
@define('SYNDICATION_PLUGIN_COMMENTNAME', 'Displayed name for "comment" feed');
@define('SYNDICATION_PLUGIN_COMMENTNAME_DESC', 'Enter an optional custom name for the comment feed');
@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD', '(If you enter an absolute URL with http://... here, this URL will be used as the redirection target in case you have enabled the "Force" option for FeedBurner. Note that this can also be a URL independent to FeedBurner. For new Google FeedBurner feeds, you need to enter http://feeds2.feedburner.com/yourfeedname here)');

@define('SYNDICATION_PLUGIN_FEEDBURNERID_FORWARD2', 'If you set this option to "Force" you can forward the RSS feed to any webservice, not only FeedBurner. Look at the option "Feedburner ID" below to enter an absolute URL)');
@define('COMMENTS_FILTER_NEED_CONFIRM', 'Pending user confirmation');
@define('NOT_WRITABLE_SPARTACUS', ' (Only required when you plan to use Spartacus plugin for remote plugin download)');
@define('MEDIA_ALT', 'ALT-Attribute (depiction or short description)');
@define('MEDIA_PROPERTY_ALT', 'Depiction (summary for ALT-Attribute)');

@define('MEDIA_TITLE', 'TITLE-Attribute (will be displayed on mouse over)');

@define('QUICKSEARCH_SORT', 'How should search-results be sorted?');

@define('QUICKSEARCH_SORT_RELEVANCE', 'Relevance');

@define('PERMISSION_HIDDENGROUP', 'Hidden group / Non-Author');

@define('SEARCH_FULLENTRY', 'Show full entry');
@define('NAVLINK_AMOUNT', 'Enter number of links in the navbar (needs reload of the Manage Styles page)');
@define('NAV_LINK_TEXT', 'Enter the navbar link text');
@define('NAV_LINK_URL', 'Enter the full URL of your link');
@define('MODERATE_SELECTED_COMMENTS', 'Accept selected comments');
@define('WEBLOG', 'Weblog');
@define('ACTIVE_COMMENT_SUBSCRIPTION', 'Subscribed');
@define('PENDING_COMMENT_SUBSCRIPTION', 'Pending confirmation');
@define('NO_COMMENT_SUBSCRIPTION', 'Not subscribed');
@define('SUMMARY', 'Summary');

// Next lines were added on 2012/05/29
@define('ABOUT_TO_DELETE_FILES', 'You are about to delete a bunch of files at once.<br />If you are using these in some of your entries, it will cause dead links or images<br />Are you sure you wish to proceed?<br /><br />');
@define('ARCHIVE_SORT_STABLE', 'Stable Archives');
@define('ARCHIVE_SORT_STABLE_DESC', 'Sort the archive-pages descending, so they are stable and search-crawler do not have to reindex them.');
@define('PLAIN_ASCII_NAMES', '(no special characters, umlauts)');
// New 2.0 constants
@define('SIMPLE_FILTERS', 'Simplified filters');
@define('SIMPLE_FILTERS_DESC', 'When enabled, search forms and filter functions are reduced to essential options. When disabled, you will see every possible filter option, i.e. in the media library or the entry editor.');
@define('TOGGLE_SELECT', 'Mark for selection');
@define('MORE', 'More');
@define('ENTRY_STATUS', 'Entry status');
@define('SCHEDULED', 'Scheduled');
@define('PUBLISHED', 'Published');
@define('ENTRY_METADATA', 'Entry metadata');
@define('NAVIGATION', 'Navigation');
@define('MAIN_MENU', 'Main menu');
@define('MENU_PERSONAL', 'Personal menu');
@define('MENU_DASHBOARD', 'Dashboard');
@define('MENU_ACTIVITY', 'Activity');
@define('MENU_SETTINGS', 'Settings');
@define('MENU_TEMPLATES', 'Templates');
@define('MENU_PLUGINS', 'Plugins');
@define('MENU_USERS', 'Users');
@define('MENU_GROUPS', 'Groups');
@define('MENU_MAINTENANCE', 'Maintenance');
@define('ALIGN_TOP', 'Top');
@define('ALIGN_LEFT', 'Left');
@define('ALIGN_RIGHT', 'Right');
@define('SHOW_METADATA', 'Show metadata');
@define('RANGE_FROM', 'From');
@define('RANGE_TO', 'To');
@define('UPLOAD', 'Upload');
@define('DOWNLOAD', 'Download');
@define('ENTRY_PUBLISHED', 'Entry #%s published');
@define('PUBLISH_ERROR', 'Error publishing entry:');
@define('UPDATE_NOTIFICATION', 'Update notification');
@define('NEW_VERSION_AVAILABLE', 'New stable Serendipity version available: ');
@define('MOVE', 'Move');
@define('MOVE_UP', 'Move up');
@define('MOVE_DOWN', 'Move down');
@define('INSTALL_NEW_SIDEBAR_PLUGIN', 'Install a new sidebar plugin');
@define('INSTALL_NEW_EVENT_PLUGIN', 'Install a new event plugin');
@define('TEMPLATE_OPTIONS', 'Template options');
@define('CURRENT_TEMPLATE', 'Current Template');
@define('TEMPLATE_INFO', 'Show template info');
@define('AVAILABLE_TEMPLATES', 'Available Templates');
@define('TIMESTAMP_RESET', 'The timestamp has been reset to the current time.');

@define('CLEANCOMPILE_PASS', '[smarty clearCompiledTemplate(%s)]');
@define('CLEANCOMPILE_FAIL', 'No files available for clearing.');
@define('CLEANCOMPILE_TITLE', 'Clear template cache');
@define('CLEANCOMPILE_INFO', 'This will purge all compiled template files of the currently active template. Compiled templates will be automatically re-created on demand by the Smarty framework.');
@define('INSTALLER_KEY', 'Key');
@define('INSTALLER_VALUE', 'Value');
@define('CURRENT_TAB', 'Current tab: ');
@define('PINGBACKS', 'Pingbacks');
@define('NO_PINGBACKS', 'No Pingbacks');
@define('GROUP_NAME_DESC', "Use as uppercased eg. 'EXAMPLE_GROUP' name, but not as a constant 'USERLEVEL_XYZ' group name.");
@define('INSTALLER_CLI_TOOLS', 'Server-side command line tools');
@define('INSTALLER_CLI_TOOLNAME', 'CLI tool');
@define('INSTALLER_CLI_TOOLSTATUS', 'Executable?');
@define('VIDEO', 'Video');
@define('RESET_FILTERS', 'Reset filters');
@define('UPDATE_FAILMSG', 'Check for new Serendipity version failed. This can happen because either the URL https://raw.github.com/s9y/Serendipity/master/docs/RELEASE is down, your server blocks outgoing connections or there are other connection issues.');
@define('UPDATE_FAILACTION', 'Disable automatic update check');
@define('UPDATE_NOTIFICATION_DESC', 'Show the update notification in the Dashboard, and for which channel?');
@define('FRONTEND', 'Frontend');
@define('BACKEND', 'Backend');
@define('MEDIA_UPLOAD_RESIZE', 'Resize before Upload');
@define('MEDIA_UPLOAD_RESIZE_DESC', 'Resize images before the upload using Javascript. This will also change the uploader to use Ajax and thus remove the Property-Button');
@define('LOG_LEVEL', 'Log Level');
@define('LOG_LEVEL_DESC', 'At certain places in the Serendipity code we have placed debugging breakpoints. If this option is set to "Debug", it will write this debug output to templates_c/logs/. You should only enable this option if you are experiencing bugs in those areas, or if you are a developer. Setting this option to "Error" will enable logging PHP errors, overwriting the PHP error_log setting.');
@define('DEBUG', 'Debug');
@define('CUSTOM_CONFIG', 'Custom configuration file');
@define('PLUGIN_ALREADY_INSTALLED', 'Plugin already installed, and does not support multiple installation ("stackable").');
@define('INSTALL_DBPREFIX_INVALID', 'The database table name prefix must not be empty and may only contain letters, numbers and the underscore character.');
@define('SYNDICATION_PLUGIN_SUBTOME', 'subToMe');
@define('SYNDICATION_PLUGIN_SUBTOME_DESC', 'Show the subToMe button, a layer to make feed subscription easier');
@define('SYNDICATE_THIS_BLOG', 'Subscribe');
@define('SYNDICATION_PLUGIN_BIGIMG_DESC', 'Display a (big) image at the top of the feeds in sidebar, enter full or absolute URL to image file. Set to "none" to show a textlink (the old default)');
@define('INSTALL_BACKENDPOPUP', 'Enable use of popup windows for the backend');
@define('INSTALL_BACKENDPOPUP_DESC', 'Do you want to use popup windows for some backend functionality? When disabled (default), inline modal dialogs will be used for e.g. the category selector and media library. On the other hand this popup-window option only works for some elements, like the media library and some plugins. Others, like categories, will show up embedded.');
@define('UPDATE_STABLE', 'stable');
@define('UPDATE_BETA', 'beta');
@define('SYNDICATION_PLUGIN_FEEDFORMAT', 'Feed format');
@define('SYNDICATION_PLUGIN_FEEDFORMAT_DESC', 'Which format shall be used for all feeds. Both are supported in all common readers');
@define('SYNDICATION_PLUGIN_COMMENTFEED', 'Comment feed');
@define('SYNDICATION_PLUGIN_COMMENTFEED_DESC', 'Show an additional link to a comment feed. This should be interesting only to the blogauthor itself');
@define('SYNDICATION_PLUGIN_FEEDICON', 'Feed icon');
@define('SYNDICATION_PLUGIN_FEEDICON_DESC', 'Show a (big) icon insteaf of a textlink to the feed. Set to "none" to deactivate, or to "feedburner" to show a feedburner counter if an id is given below');
@define('SYNDICATION_PLUGIN_CUSTOMURL', 'Custom URL');
@define('SYNDICATION_PLUGIN_CUSTOMURL_DESC', 'If you want to link to the custom feed specified in the blog configuration, enable this option.');
@define('FEED_CUSTOM', 'Custom feed URL');
@define('FEED_CUSTOM_DESC', 'If set, a custom feed URL can be set to forward Feedreaders to a specific URL. Useful for statistical analyzers like Feedburner, in which case you would enter your Feedburner-URL here.');
@define('FEED_FORCE', 'Force custom feed URL?');
@define('FEED_FORCE_DESC', 'If enabled, the URL entered above will be mandatory for Feedreaders, and your usual feed cannot be accessed from clients.');
@define('NO_UPDATES', 'No plugin updates are available');
@define('PLUGIN_GROUP_ALL', 'All categories');

@define('CONF_USE_AUTOSAVE', 'Enable autosave-feature');
@define('CONF_USE_AUTOSAVE_DESC', 'When enabled, the text you enter into blog entries will be periodically saved in your browser\'s session storage. If your browser crashes during writing, the next time you create a new entry, the text will be restored from this autosave.');
@define('INSTALL_CAT_FEEDS', 'Feed Settings');
@define('USERCONF_USE_CORE_WYSIWYG_TOOLBAR', 'Toolbar for WYSIWYG editor');
@define('USERCONF_USE_CORE_WYSIWYG_TOOLBAR_DESC', 'Sets the list of available toolbar buttons for the WYSIWYG-Editor. If you need to further change those presets, you can create a file templates/XXX/admin/ckeditor_custom_config.js. For further details please check out the files htmlarea/ckeditor_s9y_config.js and htmlarea/ckeditor_s9y_plugin.js.');
@define('USERCONF_WYSIWYG_PRESET_S9Y', 'Serendipity (default)');
@define('USERCONF_WYSIWYG_PRESET_BASIC', 'Reduced');
@define('USERCONF_WYSIWYG_PRESET_FULL', 'Full');
@define('USERCONF_WYSIWYG_PRESET_STANDARD', 'Alternate');
@define('USERCONF_WYSIWYG_PRESET_CKE', 'CKEditor Full');
@define('USERCONF_WYSIWYG_PRESET_NOCC_S9Y', 'Force: Serendipity');
@define('USERCONF_WYSIWYG_PRESET_NOCC_BASIC', 'Force: Reduced');
@define('USERCONF_WYSIWYG_PRESET_NOCC_FULL', 'Force: Full');
@define('USERCONF_WYSIWYG_PRESET_NOCC_STANDARD', 'Force: Alternate');
@define('USERCONF_WYSIWYG_PRESET_NOCC_CKE', 'Force: CKEditor Full');

@define('CATEGORY_PLUGIN_SHOWALL', 'Show a link to "All categories"?');
@define('CATEGORY_PLUGIN_SHOWALL', 'If enabled, a link for the visitor to display the blog with no category restriction will be added.');
@define('SERENDIPITY_PHPVERSION_FAIL', 'Serendipity requires a PHP version >= %2$s - you are running a lower version (%1$s) and need to upgrade your PHP version. Most providers offer you to switch to newer PHP versions through their admin panels or .htaccess directives.');
@define('TOGGLE_VIEW', 'Switch category view mode');
@define('PUBLISH_NOW', 'Publish this entry now (sets current time and date)');
@define('EDITOR_TAGS', 'Tags');
@define('EDITOR_NO_TAGS', 'No tags');
@define('DASHBOARD_ENTRIES', 'In Progress');
@define('START_UPDATE', 'Starting Update ...');
@define('UPDATE_ALL', 'Update All');
@define('INSTALL_PASSWORD2', 'Admin password (verify)');
@define('INSTALL_PASSWORD2_DESC', 'Password for admin login, enter again to verify.');
@define('INSTALL_PASSWORD_INVALID', 'Your entered passwords for the administrator user do not match.');
@define('INSTALL_BACKENDPOPUP_GRANULAR', 'Force specific backend popup behavior');
@define('INSTALL_BACKENDPOPUP_GRANULAR_DESC', 'If you generally disable upper backend popup option, you can specifically force using popups, respectively the embedded mode for specific places by entering a comma separated list of places here. Available places are: ');
@define('JS_FAILURE', 'The Serendipity JavaScript-library could not be loaded. This can happen due to PHP or Plugin errors, or even a malformed browser cache. To check the exact error please open <a href="%1$s">%1$s</a> manually in your browser and check for error messages.');
@define('THEMES_PREVIEW_BLOG', 'See demo on blog.s9y.org');
@define('SYNDICATION_PLUGIN_XML_DESC', 'Set to "none" if you only want to show a text link.');
@define('MULTICHECK_NO_ITEM', 'No item selected, please check at least one. <a href="%s">Return to previous page</a>.');
@define('MULTICHECK_NO_DIR', 'No directory selected, please choose one. <a href="%s">Return to previous page</a>.');
@define('BULKMOVE_INFO', 'Bulk-move info');
@define('BULKMOVE_INFO_DESC', 'You can select multiple files to bulk-move them to a new location. <strong>Note:</strong> This action cannot be undone, just like bulk-deletion of multiple files. All checked files will be physically moved, and referring blog entries are rewritten to point to the new location.');
@define('FIRST_PAGE', 'First Page');
@define('LAST_PAGE', 'Last Page');
@define('MEDIA_PROPERTIES_DONE', 'Properties of #%d changed.');
@define('DIRECTORY_INFO', 'Directory info');
@define('DIRECTORY_INFO_DESC', 'Directories reflect their physical folder directory name. If you want to change or move directories which contain items, you have two choices. Either create the directory or subdirectory you want, then move the items to the new directory via the media library and afterwards, delete the empty old directory there. Or completely change the whole old directory via the edit directory button below and rename it to whatever you like (existing subdir/ + newname). This will move all directories and items and change referring blog entries.');
@define('MEDIA_RESIZE_EXISTS', 'File dimensions already exist!');
@define('USE_CACHE', 'Enable caching');
@define('USE_CACHE_DESC', 'Enables an internal cache to not repeat specific database queries. This reduces the load on servers with medium to high traffic and improves page load time.');
@define('CONFIG_PERMALINK_PATH_DESC', 'Please note that you have to use a prefix so that Serendipity can properly map the URL to the proper action. You may change the prefix to any unique name, but not remove it. This applies to all path prefix definitions.');

@define('HIDE_SUBDIR_FILES', 'Hide Files of Subdirectories');
@define('USERCONF_DASHBOARD_DEFAULT_WIDGETS', 'Default dashboard widgets?');
@define('USERCONF_DASHBOARD_DEFAULT_WIDGETS_DESC', 'Show default and hardcoded dashboard widgets, like draft entries and last comments.');

@define('UPDATE_NOTIFICATION_URL', 'Serendipity update RELEASE file URL');
@define('UPDATE_NOTIFICATION_URL_DESC', 'Do not change, if not applying a different RELEASE file location for custom core downloads in combination with the Serendipity Autopudate plugin. The default value to apply here is "https://raw.githubusercontent.com/s9y/Serendipity/master/docs/RELEASE" and points to a file containing Serendipity stable and beta version numbers per line, eg. "stable:5.3.0".');

@define('URL_NOT_FOUND', '[ 404 ] - The page you have requested could not be found. Continue reading here.');

@define('CONFIG_ALLOW_LOCAL_URL', 'Allow to fetch data from local URLs');
@define('CONFIG_ALLOW_LOCAL_URL_DESC', 'By default, it is forbidden due to security constrains to fetch data from local URLs to prevent Server Side Request Forgers (SSRF). If you use a local intranet, you can enable this option to allow fetching data.');
@define('REMOTE_FILE_INVALID', 'The given URL appears to be local and is not allowed to be fetched. You can allow this by setting the option "Allow to fetch data from local URLs" in your blog configuration.');

