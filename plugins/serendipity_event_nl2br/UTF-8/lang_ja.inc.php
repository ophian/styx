<?php

/**
 *  @version 
 *  @author Tadashi Jokagi <elf2000@users.sourceforge.net>
 *  EN-Revision: 1501
 */

@define('PLUGIN_EVENT_NL2BR_NAME', 'マークアップ: NL2BR');
@define('PLUGIN_EVENT_NL2BR_DESC', '改行を BR タグに変換します。');

@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS', 'Exceptions for all following rules');
@define('PLUGIN_EVENT_NL2BR_ISOLATE_TAGS_DESC', 'A list of HTML-tags where no breaks shall be converted. 提案: "pre,geshi,textarea". 複数のタグをカンマ(「,」)で分けます。ヒント: 入力されたタグは正規表現として評価されます。');

