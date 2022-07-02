<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsModifierCompiler
 */
/**
 * Smarty escape modifier plugin
 * Type:     modifier
 * Name:     escape
 * Purpose:  escape string for output
 *
 * @link   http://www.smarty.net/docsv2/en/language.modifier.escape count_characters (Smarty online manual)
 * @author Rodney Rehm
 *
 * @param array                                $params parameters
 * @param Smarty_Internal_TemplateCompilerBase $compiler
 *
 * @return string with compiled code
 * @throws \SmartyException
 */
function smarty_modifiercompiler_escape($params, Smarty_Internal_TemplateCompilerBase $compiler)
{
    static $_double_encode = null;
    static $is_loaded = false;
    $compiler->template->_checkPlugins(
        array(
            array(
                'function' => 'smarty_literal_compiler_param',
                'file'     => SMARTY_PLUGINS_DIR . 'shared.literal_compiler_param.php'
            )
        )
    );
    if ($_double_encode === null) {
        $_double_encode = version_compare(PHP_VERSION, '5.2.3', '>=');
    }
    try {
        $esc_type = smarty_literal_compiler_param($params, 1, 'html');
        $char_set = smarty_literal_compiler_param($params, 2, Smarty::$_CHARSET);
        $double_encode = smarty_literal_compiler_param($params, 3, true);
        if (!$char_set) {
            $char_set = Smarty::$_CHARSET;
        }
        switch ($esc_type) {
            case 'html':
                if ($_double_encode) {
                    return 'htmlspecialchars(' . $params[ 0 ] . ', ENT_QUOTES, ' . var_export($char_set, true) . ', ' .
                           var_export($double_encode, true) . ')';
                } elseif ($double_encode) {
                    return 'htmlspecialchars(' . $params[ 0 ] . ', ENT_QUOTES, ' . var_export($char_set, true) . ')';
                } else {
                    // fall back to modifier.escape.php
                }
            // no break
            case 'htmlall':
                if (Smarty::$_MBSTRING) {
                    if ($_double_encode) {
                        // php >=5.2.3 - go native
/*                        return 'mb_convert_encoding(htmlspecialchars(' . $params[ 0 ] . ', ENT_QUOTES, ' .
                               var_export($char_set, true) . ', ' . var_export($double_encode, true) .
                               '), "HTML-ENTITIES", ' . var_export($char_set, true) . ')';*/
                        return 'htmlspecialchars_decode(mb_convert_encoding(htmlentities(' . $params[ 0 ] . ', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ' .
                                var_export($char_set, true) . ', ' . var_export($double_encode, true) .
                                '), ' . var_export($char_set, true) . '))'; // PHP 8.2 sets HTML-ENTITIES deprecated and PHP 8.1 gave up ENT_COMPAT for the triplet default
                    } elseif ($double_encode) {
                        // php <5.2.3 - only handle double encoding
/*                        return 'mb_convert_encoding(htmlspecialchars(' . $params[ 0 ] . ', ENT_QUOTES, ' .
                               var_export($char_set, true) . '), "HTML-ENTITIES", ' . var_export($char_set, true) . ')';*/
                        return 'htmlspecialchars_decode(mb_convert_encoding(htmlentities(' . $params[ 0 ] . ', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ' .
                                var_export($char_set, true) . '), ' . var_export($char_set, true) . '))'; // PHP 8.2 sets HTML-ENTITIES deprecated and PHP 8.1 gave up ENT_COMPAT for the triplet default
                    } else {
                        // fall back to modifier.escape.php
                    }
                }
                // no MBString fallback
                if ($_double_encode) {
                    // php >=5.2.3 - go native
                    return 'htmlentities(' . $params[ 0 ] . ', ENT_QUOTES, ' . var_export($char_set, true) . ', ' .
                           var_export($double_encode, true) . ')';
                } elseif ($double_encode) {
                    // php <5.2.3 - only handle double encoding
                    return 'htmlentities(' . $params[ 0 ] . ', ENT_QUOTES, ' . var_export($char_set, true) . ')';
                } else {
                    // fall back to modifier.escape.php
                }
            // no break
            case 'url':
                return 'rawurlencode(' . $params[ 0 ] . ')';
            case 'urlpathinfo':
                return 'str_replace("%2F", "/", rawurlencode(' . $params[ 0 ] . '))';
            case 'quotes':
                // escape unescaped single quotes
                return 'preg_replace("%(?<!\\\\\\\\)\'%", "\\\'",' . $params[ 0 ] . ')';
            case 'javascript':
                // escape quotes and backslashes, newlines, etc.
                // see https://html.spec.whatwg.org/multipage/scripting.html#restrictions-for-contents-of-script-elements
                return 'strtr(' .
                       $params[ 0 ] .
                       ', array("\\\\" => "\\\\\\\\", "\'" => "\\\\\'", "\"" => "\\\\\"", "\\r" => "\\\\r", "\\n" => "\\\n", "</" => "<\/", "<!--" => "<\!--", "<s" => "<\s", "<S" => "<\S" ))';
        }
    } catch (SmartyException $e) {
        // pass through to regular plugin fallback
    }
    // could not optimize |escape call, so fallback to regular plugin
    if ($compiler->template->caching && ($compiler->tag_nocache | $compiler->nocache)) {
        $compiler->required_plugins[ 'nocache' ][ 'escape' ][ 'modifier' ][ 'file' ] =
            SMARTY_PLUGINS_DIR . 'modifier.escape.php';
        $compiler->required_plugins[ 'nocache' ][ 'escape' ][ 'modifier' ][ 'function' ] =
            'smarty_modifier_escape';
    } else {
        $compiler->required_plugins[ 'compiled' ][ 'escape' ][ 'modifier' ][ 'file' ] =
            SMARTY_PLUGINS_DIR . 'modifier.escape.php';
        $compiler->required_plugins[ 'compiled' ][ 'escape' ][ 'modifier' ][ 'function' ] =
            'smarty_modifier_escape';
    }
    return 'smarty_modifier_escape(' . join(', ', $params) . ')';
}
