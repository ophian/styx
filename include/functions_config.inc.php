<?php
# Copyright (c) 2003-2005, Jannis Hermanns (on behalf the Serendipity Developer Team)
# All rights reserved.  See LICENSE file for licensing details

declare(strict_types=1);

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

/**
 * Adds a new author account
 *
 * Args:
 *      - New username
 *      - New password
 *      - The realname of the user
 *      - The email address of the user
 *      - The userlevel of a user
 *      - The hashtype, 1 used for S9y versions 1.5 until Styx 2.5.0 as sha1,
 *                      2 for the latter with PASSWORD_BCRYPT
 *                        and at all replacing the very old md5() routine
 * Returns:
 *      - The new user ID of the added author
 * @access public
 */
function serendipity_addAuthor(string $username, #[\SensitiveParameter] string $password, string $realname, string $email, int|string $userlevel = 0, int $hashtype = 2) : int {
    global $serendipity;

    $password = serendipity_hash($password);
    $query = "INSERT INTO {$serendipity['dbPrefix']}authors (username, password, realname, email, userlevel, hashtype)
                        VALUES  ('" . serendipity_db_escape_string($username) . "',
                                 '" . serendipity_db_escape_String($password) . "',
                                 '" . serendipity_db_escape_String($realname) . "',
                                 '" . serendipity_db_escape_String($email) . "',
                                 '" . serendipity_db_escape_String($userlevel) . "',
                                 '" . serendipity_db_escape_String($hashtype) . "'
                                 )";
    serendipity_db_query($query);
    $cid = serendipity_db_insert_id('authors', 'authorid');

    $data = array(
        'authorid' => $cid,
        'username' => $username,
        'realname' => $realname,
        'email'    => $email
    );

    serendipity_insertPermalink($data, 'author');

    return $cid;
}

/**
 * Delete an author account
 *
 * (Note, this function does not delete entries by an author)
 *
 * Args:
 *      - The author ID to delete
 * Returns:
 *      - True on success, false on error or insufficient privileges
 * @access public
 */
function serendipity_deleteAuthor(int $authorid) : bool {
    global $serendipity;

    if (!serendipity_checkPermission('adminUsersDelete')) {
        return false;
    }

     // Do not allow to delete the user SELF
    if ($authorid == $serendipity['authorid']) {
        return false;
    }

    if (serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authors WHERE authorid=" . $authorid)) {
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE authorid=" . $authorid);
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authorgroups WHERE authorid=" . $authorid);
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}permalinks WHERE entry_id=" . $authorid ." and type='author'");
    }

    return true;
}

/**
 * Removes a configuration value from the Serendipity Configuration
 *
 * Global config items have the authorid 0, author-specific configuration items have the corresponding authorid.
 *
 * Args:
 *      - The name of the configuration value
 *      - The ID of the owner of the config value (0: global)
 * Returns:
 *      - void
 * @access public
 */
function serendipity_remove_config_var(string $name, int $authorid = 0) : void {
    global $serendipity;

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name='" . serendipity_db_escape_string($name) . "' AND authorid = " . $authorid);
}

/**
 * Sets a configuration value for the Serendipity Configuration
 *
 * Global config items have the authorid 0, author-specific configuration items have the corresponding authorid.
 *
 * Args:
 *      - The name of the configuration value
 *      - The value of the configuration item
 *      - The ID of the owner of the config value (0: global)
 * Returns:
 *      - void
 * @access public
 */
function serendipity_set_config_var(string $name, string|int|null $val, int $authorid = 0) : void {
    global $serendipity;

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}config WHERE name='" . serendipity_db_escape_string($name) . "' AND authorid = " . $authorid);

    if ($name == 'password' || $name == 'check_password') {
        return;
    }

    $r = serendipity_db_insert('config', array('name' => $name, 'value' => $val, 'authorid' => $authorid)); // see above array type note

    if ($authorid === 0 || (isset($serendipity['authorid']) && $authorid === $serendipity['authorid'])) {
        if ($val === 'false') {
            $serendipity[$name] = false;
        } else {
            $serendipity[$name] = $val;
        }
    }

    if (is_string($r)) {
        # if $r is a string, it is the error-message from the insert
        echo $r;
    }
}

/**
 * Retrieve a global configuration value for a specific item of the current Serendipity Configuration
 *
 * Args:
 *      - The name of the configuration value
 *      - The default value of a configuration item, if not found in the Database
 *      - If set to true, the default value of a configuration item will be returned
 *          if the item is set, but empty. If false, an empty configuration value will
 *          be returned empty. This is required for getting default values if you do
 *          not want to store/allow empty config values.
 * Returns:
 *      - The configuration value content
 * @access public
 */
function serendipity_get_config_var(string $name, bool|string|null $defval = false, bool $empty = false) : bool|int|string|null {
    global $serendipity;

    if (isset($serendipity[$name])) {
        if ($empty && gettype($serendipity[$name]) == 'string' && $serendipity[$name] === '') {
            return $defval;
        } else {
            return $serendipity[$name];
        }
    } else {
        return $defval;
    }
}

/**
 * Retrieve an author-specific configuration value for an item of the Serendipity Configuration stored in the DB
 *
 * Despite the serendipity_get_config_var() function, this will retrieve author-specific values straight from the Database.
 *
 * Args:
 *      - The name of the configuration value
 *      - The ID of the owner of the config value (0: global)
 *      - The default value of a configuration option, if not set in the DB
 * Returns:
 *      - The configuration value content
 * @access public
 */
function serendipity_get_user_config_var(string $name, ?int $authorid, iterable|string|bool|null $default = '') : iterable|string|bool|null {
    global $serendipity;

    $author_sql = '';
    if (!empty($authorid)) {
        $author_sql = 'authorid = ' . (int) $authorid . ' AND ';
    } elseif (isset($serendipity[$name])) {
        return $serendipity[$name];
    }

    $r = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}config WHERE $author_sql name = '" . serendipity_db_escape_string($name) . "' LIMIT 1", true);

    if (is_array($r)) {
        return $r[0];
    } else {
        return $default;
    }
}

/**
 * Retrieves an author-specific account value
 *
 * This retrieves specific account data from the user configuration, not from the serendipity configuration.
 *
 * Args:
 *      - The name of the configuration value
 *      - The ID of the author to fetch the configuration for
 *      - The default value of a configuration option, if not set in the DB
 * Returns:
 *      - The configuration value content
 * @access public
 */
function serendipity_get_user_var(string $name, int $authorid, string $default) : string {
    global $serendipity;

    $r = serendipity_db_query("SELECT $name FROM {$serendipity['dbPrefix']}authors WHERE authorid = " . $authorid, true);

    if (is_array($r)) {
        return $r[0];
    } else {
        return $default;
    }
}

/**
 * Updates data from the author-specific account
 *
 * This sets the personal account data of a serendipity user within the 'authors' DB table
 *
 * Args:
 *      - The name of the configuration value
 *      - The content of the configuration value
 *      - The ID of the author to set the configuration for
 *      - If set to true, the stored config value will be imported to the Session/current config of the user.
 *          This is applied for example when you change your own user's preferences and want it to be immediately reflected in the interface.
 * Returns:
 *      - void
 * @access public
 */
function serendipity_set_user_var(string $name, string $val, int $authorid, bool $copy_to_s9y = true) : void {
    global $serendipity;

    // When inserting a DB value, this array maps the new values to the corresponding s9y variables
    static $user_map_array = array(
        'username'  => 'serendipityUser',
        'email'     => 'serendipityEmail',
        'userlevel' => 'serendipityUserlevel'
    );

    // Special case for inserting a password
    switch($name) {
        case 'check_password':
            //Skip this field.  It doesn't need to be stored.
            return;
        case 'password':
            if (empty($val)) {
                return;
            }

            $val = serendipity_hash($val);
            $copy_to_s9y = false;
            break;

        case 'right_publish':
        case 'mail_comments':
        case 'mail_trackbacks':
            $val = (serendipity_db_bool($val) ? 1 : '0');
            break;
    }

    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}authors SET $name = '" . serendipity_db_escape_string($val) . "' WHERE authorid = " . $authorid);

    if ($copy_to_s9y) {
        if (isset($user_map_array[$name])) {
            $key = $user_map_array[$name];
        } else {
            $key = 'serendipity' . ucfirst($name);
        }

        $_SESSION[$key] = $serendipity[$key] = $val;
    }
}

/**
 * Gets the full filename and path of a template/style/theme file
 *
 * The returned full path is depending on the second parameter, where you can either fetch a HTTP path, or a realpath.
 * The file is searched in the current template, and if it is not found there, it is returned from the default template.
 *
 * Args:
 *      - The filename to search for in the selected template
 *      - The path selector that tells whether to return a HTTP or realpath; an empty string or null will return a relative path
 *      - Enable to include frontend template fallback chaining (used for wysiwyg Editor custom config files, emoticons, etc)
 *      - Enable to check into $serendipity['template'] or its engine, then fall back to $this->pluginFile dir (used by plugins via parseTemplate() method)
 * Returns:
 *      - The full path+filename to the requested file OR false
 * @access public
 */
function serendipity_getTemplateFile(string $file, string $key = 'serendipityHTTPPath', bool $force_frontend_fallback = false, bool $simple_plugin_fallback = false) : string|false {
    global $serendipity;

    $directories = array();

    if (defined('IN_serendipity_admin') && !$serendipity['smarty_preview']) {
        if ($force_frontend_fallback) {
            // If enabled, even when within the admin suite it will be possible to reference files that
            // reside within a frontend-only template directory.
            $directories[] = $serendipity['template'] . '/';
            if (isset($serendipity['template_engine']) && $serendipity['template_engine'] != null) {
                $p = explode(',', $serendipity['template_engine']);
                foreach($p AS $te) {
                    $directories[] = trim($te) . '/';
                }
            }
        }
        if (!$simple_plugin_fallback) {
            // Backend will always use our default backend (=defaultTemplate) as fallback.
            $directories[] = isset($serendipity['template_backend']) ? $serendipity['template_backend'] . '/' : '';
            $directories[] = $serendipity['defaultTemplate'] .'/';
            $directories[] = 'default/';
        }
    } else {
        $directories[] = isset($serendipity['template']) ? $serendipity['template'] . '/' : '';
        if (isset($serendipity['template_engine']) && $serendipity['template_engine'] != null) {
            $p = explode(',', $serendipity['template_engine']);
            foreach($p AS $te) {
                $directories[] = trim($te) . '/';
            }
        }

        if (!$simple_plugin_fallback) {
            // Frontend templates currently need to fall back to "default" (see "idea"), so that they get the output
            // they desire. If templates are based on "pure", they need to set "Engine: pure" in their info.txt file.
            $directories[] = 'default/';
            $directories[] = $serendipity['defaultTemplate'] .'/';
        }
    }

    // Allow to use templatePath _assets directory for bootstrap4 framework cases
    if (preg_match('@\.(css|js)@i', $file)) {
        $directories[] = '_assets/';
    }

    if (!empty($directories)) {
        foreach($directories AS $directory) {
            $templateFile = $serendipity['templatePath'] . $directory . $file; // includes .ext(ension) !
            if (str_contains($templateFile, 'serendipity_styx.js') && file_exists($serendipity['serendipityPath'] . $templateFile . '.tpl')) {
                // catch *.tpl files, used by the backend for serendipity_styx.js.tpl
                return $serendipity['baseURL'] . ($serendipity['rewrite'] == 'none' ? $serendipity['indexFile'] . '?/' : '') . 'plugin/' . $file;
            }
            // Avoid loading a custom fallback user.css file for a theme when having such file in default OR standard themes
            if ($file == 'user.css' && substr($directory, 0, -1) != $serendipity['template']) {
                return false;
            }
            if (file_exists($serendipity['serendipityPath'] . $templateFile)) {
                return (isset($serendipity[$key]) ? $serendipity[$key] . $templateFile : $templateFile); // avoid undefined index Notices if key was called with '', eg. serendipity_getTemplateFile('style_fallback.css', '')
            }
        }
    }

    if (preg_match('@\.(tpl|css|php)@i', $file) && !stristr($file, 'plugin')) {
        return $file;
    }

    return false;
}

/**
 * Loads all configuration values and imports them to the $serendipity array
 *
 * This function may be called twice - once for the global config and once for
 * user-specific config
 *
 * Args:
 *      - The authorid to fetch the configuration from (0: global)
 * Returns:
 *      - True or NULL
 * @access public
 */
function serendipity_load_configuration(?int $author = null) : ?bool {
    global $serendipity;
    static $config_loaded = array();

    if (isset($config_loaded[$author])) {
        return true;
    }

    if (!empty($author)) {
        // Replace default configuration directives with user-relevant data
        $rows =& serendipity_db_query("SELECT name,value
                                         FROM {$serendipity['dbPrefix']}config
                                        WHERE authorid = '". (int) $author ."'");
    } else {
        // Only get default variables, user-independent (frontend)
        $rows =& serendipity_db_query("SELECT name, value
                                         FROM {$serendipity['dbPrefix']}config
                                        WHERE authorid = 0");
    }

    if (is_array($rows)) {
        foreach($rows AS $row) {
            // Convert 'true' and 'false' into booleans
            $serendipity[$row['name']] = serendipity_get_bool($row['value']);
        }
    }
    $config_loaded[$author] = true;

    // Set baseURL to defaultBaseURL
    if ((empty($author) || empty($serendipity['baseURL'])) && isset($serendipity['defaultBaseURL'])) {
        $serendipity['baseURL'] = $serendipity['defaultBaseURL'];
    }

    // Store default language
    $serendipity['default_lang'] = $serendipity['lang'];

    return null;
}

/**
 * Generates a strong password by length and increased variety
 *
 * Args:
 *      - Length of return [Default: 16]
 *      - Uppercased and lowercased strong characters - removed i, l, O
 *      - Whether to include numbers [default: true] - removed 0, 1
 *          1 uses normal special chars, 2 adds extra more may or may not available in every language; Use 0 OR null for none
 * Returns:
 *      - The random password string
 * @access private
 */
function serendipity_generate_password(int $length = 16, bool $ints = true, int $extend = 1) : string {
    $chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghjkmnopqrstuvwxyz';
    if ($ints) {
        $chars .= '23456789';
    }
    if ($extend === 1) {
        $chars .= '!@#$%^&*()';
    } else if ($extend === 2) {
        $chars .= '-=~_+,./<>?;:[]{}\|';
    }

    $pw = '';
    $max = strlen($chars) - 1;

    for ($i=0; $i < $length; $i++) {
        $pw .= $chars[random_int(0, $max)];
    }

    return $pw;
}

/**
 * Perform logout functions (destroys session data)
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function serendipity_logout() : void {
    $_SESSION['serendipityAuthedUser'] = false;
    serendipity_session_destroy();
    serendipity_deleteCookie('author_information');
    serendipity_deleteCookie('author_token');
}

/**
 * Destroys a session, keeps important stuff intact.
 * Args:
 *      -
 * Returns:
 *      - void
 * @access public
 */
function serendipity_session_destroy() : void {
    $_no_smarty = $_SESSION['no_smarty'] ?? null;
    @session_destroy();
    session_start();// set regenerate new to avoid of possible (old) session hijacking
    session_regenerate_id(true);

    $_SESSION['SERVER_GENERATED_SID'] = true;
    $_SESSION['no_smarty']            = $_no_smarty;
}

/**
 * Perform login to Serendipity
 *
 * Args:
 *      - If set to true, external plugins will be queried for getting a login
 * Returns:
 *      - Return true, if the user is logged in. False if not.
 * @access public
 */
function serendipity_login(bool $use_external = true) : bool {
    global $serendipity;

    if (serendipity_authenticate_author('', '', false, $use_external)) {
        #The session has this data already
        #we previously just checked the value of $_SESSION['serendipityAuthedUser'] but
        #we need the authorid still, so call serendipity_authenticate_author with blank
        #params
        return true;
    }
    // Cast POST login values to strings to get the desired error or login
    if (isset($serendipity['POST']['user'])) $serendipity['POST']['user'] = (string)$serendipity['POST']['user'];
    if (isset($serendipity['POST']['pass'])) $serendipity['POST']['pass'] = (string)$serendipity['POST']['pass'];

    // First try login via POST data. If true, the user information will be stored in a cookie (optionally)
    if (isset($serendipity['POST']['user']) && isset($serendipity['POST']['pass']) && serendipity_authenticate_author($serendipity['POST']['user'], $serendipity['POST']['pass'], false, $use_external)) {
        if (empty($serendipity['POST']['auto'])) {
            serendipity_deleteCookie('author_information');
            serendipity_deleteCookie('author_information_iv');
            return false;
        } else {
            serendipity_issueAutologin(
                array('username' => $serendipity['POST']['user'],
                      'password' => $serendipity['POST']['pass']
                )
            );
            return true;
        }
    // Now try login via COOKIE data
    } elseif (isset($serendipity['COOKIE']['author_information']) && !empty($serendipity['COOKIE']['author_information_iv'])) {
        $cookie = serendipity_checkAutologin($serendipity['COOKIE']['author_information'], $serendipity['COOKIE']['author_information_iv']);

        $data = is_array($cookie) ? array('ext' => $use_external, 'mode' => 1, 'user' => $cookie['username'], 'pass' => $cookie['password']) : [];
        serendipity_plugin_api::hook_event('backend_loginfail', $data);

        if (is_array($cookie) && serendipity_authenticate_author($cookie['username'], $cookie['password'], false, $use_external)) {
            return true;
        } else {
            serendipity_deleteCookie('author_information');
            serendipity_deleteCookie('author_information_iv');
            return false;
        }
    }

    $data = array('ext' => $use_external, 'mode' => 2, 'user' => ($serendipity['POST']['user'] ?? null), 'pass' => ($serendipity['POST']['pass'] ?? null));
    serendipity_plugin_api::hook_event('backend_loginfail', $data);

    return false; // default fallback
}

/**
 * Temporary helper function to debug output to the logger file if the $serendipity['logger'] object is not available (in case of log-off actions)
 *
 * Args:
 *      - The file and path
 *      - The message string
 * Returns:
 *      - void
 * @access private
 *//*
function aesDebugFile(string $file, string $str = '') : void {
    $fp = fopen($file, 'a');
    flock($fp, LOCK_EX);
    $nowMT = microtime(true);
    $micro = sprintf("%06d", ($nowMT - floor($nowMT)) * 1000000);
    fwrite($fp, '[' . date('Y-m-d H:i:s.'.$micro, $nowMT) . '] [debug] ' . $str ."\n");
    fclose($fp);
}
*/
/**
 * Login encrypt/decrypt and set autologin cookie by version and lib
 *
 * Args:
 *      - The input data - already serialize(d)
 *      - Sets the encrypt or decrypt
 *      - A non-NULL 12 bytes Initialization Vector
 * Returns:
 *      - The output data OR FALSE
 * @access private
 */
function serendipity_cryptor(#[\SensitiveParameter] string $data, bool $decrypt = false, #[\SensitiveParameter] ?string $iv = null) : string|bool  {
    global $serendipity;

    // DEBUG NOTE: Use locally only OR set the blog into maintenance mode, since decryption logs may contain valid credential data or data that is easy decryptable!
    #$debugfile = __DIR__ . '/../templates_c/logs/log_'.date('Y-m-d').'.txt'; // also see function serendipity_checkAutologin() below

    // CRYPTOR NOTES:
    // From PHP 7.1.3 associated data (GCM tag) can be retrieved.
    // https://crypto.stackexchange.com/questions/30901/how-can-there-be-aes-256-gcm-when-gcm-is-defined-for-128-sized-blocks
    // AES has a block-size of 128 bits in all its variants. The number in AES-128/192/256 is the key-size.
    // Rijndael, the block-cipher that became AES, also supports 256 bit blocks, but that part was not standardized as AES.
    // Since the block-size is 128 bits, GCM works exactly the same way for AES-256 as it does for AES-128.
    $algo = 'aes-256-gcm'; // STRONG Galois/Counter Mode, default for current PHP versions above 70103

    if ($decrypt) {
         // DECRYPT
         // $data returns as serialized RAW array
         // @see notes in ENCRYPT
        if (function_exists('openssl_decrypt')) {
            $key = hex2bin($iv);
            list($bt_ct, $bt_iv, $bt_tg) = explode(".", $data); // ciphertext cookie data, iv, tag
            $cda = hex2bin($bt_ct);
            $iv  = hex2bin($bt_iv);
            $tag = hex2bin($bt_tg);
            try {
                $cipher = openssl_decrypt($cda, $algo, $key, \OPENSSL_RAW_DATA, $iv, $tag);
                #aesDebugFile($debugfile, '#DECRYPT: data = '.$cipher.' key = ' . $key . ' tag = '.$tag.' and iv = '. $iv); // ATTENTION!!
            } catch (\Throwable $t) {
                // Executed in PHP 7 only, will not match in PHP 5.x
                if (!serendipity_db_bool($serendipity['maintenance'])) {
                    trigger_error('Whoops! Your Cookie stored LOGIN key did not match, since: "' . $t->getMessage() . '". You have been logged out automatically for security reasons.', E_USER_NOTICE);
                    serendipity_logout();
                } else {
                    trigger_error( 'Whoops! Your Cookie stored LOGIN key did not match, since: "' . $t->getMessage() . '". For security the encrypted login cookie data was purged. This Warning error message does only show up once for you! Since you are still in maintenance mode, you need to manually delete the $serendipity[\'maintenance\'] variable in your serendipity_config_local.inc.php file to get LOGIN access again.', E_USER_NOTICE);
                }
                $cipher = false; // silent logout
            }

            /* // ATTENTION!!
            if (false === $cipher) {
                aesDebugFile($debugfile, '#DECRYPT: openssl_decrypt returned false:(' . $cipher . ') '.sprintf("OpenSSL error: %s", openssl_error_string()));
            } else {
                aesDebugFile($debugfile, '#DECRYPT: openssl_decrypt returned (' . $cipher . ')');
            }*/
            return $cipher;
        }
        return false;
    } else {
        // ENCRYPT
        // $data comes as serialized RAW, while being a login credential array ...
        // openssl_en/decrypt uses (date(BINARY), method(string from openssl_get_cipher_methods()), key(BINARY), options(INT by constants), iv(BINARY), tag(NULL returns BINARY), aad(BINARY), tag_length(INT=16))
        // GCM runs CTR internally which requires a 16-byte counter. The IV provides 12 of those, the other 4 are an actual block-wise counter. Changing this can therefore only be detrimental to security, never better.
        // CTR/GCM simply performs these calculations to re-generate a 12 byte IV from the given bytes
        // The 16-byte counter is 128 bits - see block-size used, is 128 bits
        if (function_exists('random_bytes') && function_exists('openssl_encrypt')) {
            $tag  = null;
            $iv   = random_bytes(12); // 96 bits - (Setting of IV length for AEAD mode, the expected length is 12 bytes! No matter if GCM 128 or 256 - see upper AES and IV notes!)
            $key  = random_bytes(31); // varchar(64) field -2 = 62
            $ckey = bin2hex($key);    // hex encode key binary hash for iv cookie storage
            $cipher = openssl_encrypt($data, $algo, $key, \OPENSSL_RAW_DATA, $iv, $tag);

            if (false === $cipher) {
                if (is_object($serendipity['logger'])) {
                    $serendipity['logger']->critical('ENCRYPT: openssl_encrypt package returned false and '.sprintf("OpenSSL error: %s", openssl_error_string()));
                }
            } else {
                serendipity_setCookie('author_information_iv', $ckey); // store the key
                /*if (is_object($serendipity['logger'])) {
                    $serendipity['logger']->warning('ENCRYPT: $cipher: '.print_r($cipher, true).' key = ' . $key . ' and iv = '. $iv .' and ivlen='.openssl_cipher_iv_length($algo).' and keyBinLen='.strlen($key).' and keyHexLen='.strlen($ckey));
                }*/
                $cipher = bin2hex($cipher).'.'.bin2hex($iv).'.'.bin2hex($tag); // binary to hex for DB storage of cipher, iv, tag
            }
            return $cipher;
        }
        return false;
    }
}

/**
 * Issue a new auto login cookie
 *
 * Args:
 *      - The input data as array
 * Returns:
 *      - void
 * @access private
 */
function serendipity_issueAutologin(iterable $array) : void {
    global $serendipity;

    $package = serialize($array);

    $package = serendipity_cryptor($package); // encrypt
    if ($package === false) {
        $package = serialize($array); // fallback to session based authentication
    }

    $rnd = md5(uniqid((string)time(), true) . $_SERVER['REMOTE_ADDR']);

    if (stristr($serendipity['dbType'], 'sqlite')) {
        $cast = 'name';
    } elseif (stristr($serendipity['dbType'], 'postgres')) {
        // Adds explicits casting for postgresql.
        $cast = 'cast(name AS integer)';
    } else {
        // and all others eg mysql(i), zend-db, ...
        $cast = 'cast(name AS UNSIGNED)';
    }

    // Delete possible current cookie. Also delete any autologin keys that smell like 3-week-old, dead fish. Be explicit for postgreSQL casts to exclude sysinfo tickers!
    if (isset($serendipity['COOKIE']['author_information'])) {
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}options
                                WHERE okey = 'l_" . serendipity_db_escape_string($serendipity['COOKIE']['author_information']) . "' AND name != 'sysinfo_ticker'
                                   OR (name != 'sysinfo_ticker' AND okey LIKE 'l_%' AND $cast < " . (time() - 1814400) . ")");
    }

    // Issue new autologin cookie
    serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey) VALUES ('" . time() . "', '" . serendipity_db_escape_string($package) . "', 'l_" . $rnd . "')");
    serendipity_setCookie('author_information', $rnd);
}

/**
 * Checks a new auto login cookie
 *
 * Args:
 *      - The input data as array
 * Returns:
 *      - The output data as artray Or string OR FALSE
 * @access private
 */
function serendipity_checkAutologin(#[\SensitiveParameter] string $ident, #[\SensitiveParameter] string $iv) : string|bool|iterable  {
    global $serendipity;

    // DEBUG NOTE: Use locally only OR set the blog into maintenance mode, since decryption logs may contain valid credential data or data that is easy decryptable!
    #$debugfile = __DIR__ . '/../templates_c/logs/log_'.date('Y-m-d').'.txt'; // also see serendipity_cryptor() above

    // Fetch login data from DB
    $autologin =& serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}options WHERE okey = 'l_" . serendipity_db_escape_string($ident) . "' LIMIT 1", true, 'assoc');
    #aesDebugFile($debugfile, '#checkAutologin: '." SELECT * FROM {$serendipity['dbPrefix']}options WHERE okey = 'l_" . serendipity_db_escape_string($ident) . "' LIMIT 1 ".print_r($autologin, true));
    if (!is_array($autologin)) {
        return false;
    }

    $cdata  = $autologin['value'];
    $cookie = serendipity_cryptor($cdata, true, $iv); // decrypt OK with old mcrypt! and the intermediate cryptor class using aes-256-crt or class-less with PHP 70301 plus using strong aes-256-gcm algo
    if ($cookie === false) {
        $cookie = @unserialize(base64_decode($autologin['value']));
    } else {
        $cookie = !is_array($cookie) ? unserialize($cookie) : $cookie;
    }
    #aesDebugFile($debugfile, '#checkAutologin: (' . print_r($cookie, true) . ')'); // ATTENTION!!
    if ($autologin['name'] < (time()-86400)) {
        // Issued autologin cookie has been issued more than 1 day ago. Re-Issue new cookie, invalidate old one to prevent abuse
        if ($serendipity['expose_s9y']) serendipity_header('X-ReIssue-Cookie: +' . (time() - $autologin['name']) . 's');
        serendipity_issueAutologin($cookie);
    }

    return $cookie;
}

/**
 * Set a session cookie which can identify a user across http/https boundaries
 *
 * Args:
 *      -
 * Returns:
 *      - void
 * @access private
 */
function serendipity_setAuthorToken() : void {
    try {
        $string = random_bytes(32);
    } catch (\TypeError $e) {
        // Well, it's an integer, so this IS unexpected.
        #trigger_error('Create author token failed: An unexpected [type] error has occurred');
        $string = sha1(uniqid(mt_rand(), true));
    } catch (\Error $e) {
        // This is also unexpected because 32 is a reasonable integer.
        #trigger_error('Create author token failed: An unexpected error has occurred');
        $string = sha1(uniqid(mt_rand(), true));
    } catch (\Throwable $t) {
        // If you get this message, the CSPRNG failed hard.
        #trigger_error('Create author token failed: Could not generate a random string. Is our OS secure?');
        $string = sha1(uniqid(mt_rand(), true));
    }
    $hash = bin2hex($string);
    serendipity_setCookie('author_token', $hash);
    $_SESSION['author_token'] = $hash;
}

/**
 * Perform user authentication routine
 *
 * If a user is already authenticated via session data, this bypasses some routines.
 * After a user has been authenticated, several SESSION variables are set.
 * If the authentication fails, the session is destroyed.
 *
 * Args:
 *      - The username to check
 *      - The password to check (may contain plaintext or MD5 / SHA1 hashes)
 *      - Indicates whether the input password is already in MD5 format (TRUE) or not (FALSE).
 *      - Indicates whether to query external plugins for authentication
 * Returns:
 *      - True on success, False on error
 * @access public
 */
function serendipity_authenticate_author(string $username = '', #[\SensitiveParameter] string $password = '', bool $is_hashed = false, bool $use_external = true) : bool {
    global $serendipity;
    static $debug = false;
    static $debugc = 0;

    // We don't want to debug noisy bot dummies that just click on every link
    if ($debug && empty($_SESSION['serendipityPassword']) && empty($username) && empty($password)) {
        $debug = false;
    }
    if ($debug) {
        $fp = fopen('login.log', 'a');
        flock($fp, LOCK_EX);
        $debugc++;
        fwrite($fp, date('Y-m-d H:i') . ' - #' . $debugc . ' Login init [' . $username . ',' . $password . ',' . (int)$is_hashed . ',' . (int)$use_external . ']' . ' (' . $_SERVER['REMOTE_ADDR'] . ', ' . $_SERVER['REQUEST_URI'] . ', ' . session_id() . ')' . "\n");
    }

    if (isset($_SESSION['serendipityUser']) && isset($_SESSION['serendipityPassword']) && isset($_SESSION['serendipityAuthedUser']) && $_SESSION['serendipityAuthedUser'] == true) {
        $username = $_SESSION['serendipityUser'];
        $password = $_SESSION['serendipityPassword'];
        // For safety reasons when multiple blogs are installed on the same host, we need to check the current author each time to not let him log into a different blog with the same sessiondata
        #$is_hashed = true;
        if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - Recall from session: ' . $username . ':' . $password . "\n");
    }

    if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - Login ext check' . "\n");
    $is_authenticated = false;
    serendipity_plugin_api::hook_event('backend_login', $is_authenticated, NULL);
    if ($is_authenticated) {
        return true;
    }

    if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - Login username check:' . $username . "\n");
    if ($username != '') {
        if ($use_external) {
            serendipity_plugin_api::hook_event('backend_auth', $is_hashed, array('username' => $username, 'password' => $password));
        }

        $query = "SELECT DISTINCT email, password, realname, authorid, userlevel, right_publish, hashtype
                    FROM {$serendipity['dbPrefix']}authors
                   WHERE username   = '" . serendipity_db_escape_string($username) . "'";
        if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - Login check (' . serialize($is_hashed) . ', ' . $_SESSION['serendipityPassword'] . '):' . $query . "\n");

        $rows =& serendipity_db_query($query, false, 'assoc');
        if (is_array($rows)) {
            foreach($rows AS $row) {
                if (isset($is_valid_user) && $is_valid_user === true) {
                    continue;
                }
                $is_valid_user = false; // init

                if ( ($is_hashed === false && password_verify((string)$password, $row['password'])) ||
                     ($is_hashed !== false && (string)$row['password'] === (string)$password) ) {

                    $is_valid_user = true;
                    if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - Validated ' . $row['password'] . ' == ' . ($is_hashed === false ? 'unhash:' . serendipity_hash($password) : 'hash:' . $password) . "\n");
                } else {
                    if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - INValidated ' . $row['password'] . ' == ' . ($is_hashed === false ? 'unhash:' . serendipity_hash($password) : 'hash:' . $password) . "\n");
                    continue;
                }

                // This code is only reached, if the password before is found valid.
                if ($is_valid_user) {
                    if ($debug) fwrite($fp, date('Y-m-d H:i') . ' [sid:' . session_id() . '] - Success.' . "\n");
                    serendipity_setCookie('old_session', session_id(), false);
                    if (!$is_hashed) {
                        serendipity_setAuthorToken();
                        $_SESSION['serendipityPassword'] = $serendipity['serendipityPassword'] = $password;
                    }

                    $_SESSION['serendipityUser']         = $serendipity['serendipityUser']         = $username;
                    $_SESSION['serendipityRealname']     = $serendipity['serendipityRealname']     = $row['realname'];
                    $_SESSION['serendipityEmail']        = $serendipity['serendipityEmail']        = $row['email'];
                    $_SESSION['serendipityAuthorid']     = $serendipity['authorid']                = $row['authorid'];
                    $_SESSION['serendipityUserlevel']    = $serendipity['serendipityUserlevel']    = $row['userlevel'];
                    $_SESSION['serendipityAuthedUser']   = $serendipity['serendipityAuthedUser']   = true;
                    $_SESSION['serendipityRightPublish'] = $serendipity['serendipityRightPublish'] = $row['right_publish'];
                    $_SESSION['serendipityHashType']     = $serendipity['serendipityHashType']     = $row['hashtype'];

                    serendipity_load_configuration((int) $serendipity['authorid']);
                    serendipity_setCookie('userDefLang', $serendipity['lang'], false);
                    return true;
                }
            }
        }

        // Only reached, when proper login did not yet return true.
        if ($debug) fwrite($fp, date('Y-m-d H:i') . ' - FAIL.' . "\n");

        $_SESSION['serendipityAuthedUser'] = false;
        serendipity_session_destroy();
    }

    if ($debug) {
        fwrite($fp, date('Y-m-d H:i') . ' [sid:' . session_id() . '] - Uninit' . "\n");
        fclose($fp);
    }

    return false;
}

/**
 * Check if a user is logged in
 *
 * Args:
 *      -
 * Returns:
 *      - TRUE when logged in, FALSE when not
 * @access public
 */
function serendipity_userLoggedIn() : bool {
    if (isset($_SESSION['serendipityAuthedUser']) && $_SESSION['serendipityAuthedUser'] === true && IS_installed) {
        return true;
    } else {
        return false;
    }
}

/**
 * A clone of an ifsetor() function to set a variable conditional on if the target already exists
 *
 * The function sets the contents of $source into the $target variable, but only if $target is not yet set. Eases up some if...else logic or multiple ternary operators
 *
 * Args:
 *      - Source variable that should be set into the target variable (reference call!)
 *      - Target variable, that should get the contents of the source variable (reference call!)
 * Returns:
 *      - True, when $target was not yet set and has been altered. False when no changes where made.
 * @access public
 */
function serendipity_restoreVar(mixed &$source, mixed &$target) : bool {
    if (isset($source) && !isset($target)) {
        $target = $source;
        return true;
    }
    return false;
}

/**
 * Set a Cookie via HTTP calls, and update $_COOKIE plus $serendipity['COOKIE'] array.
 *
 * Args:
 *      - The name of the cookie variable
 *      - The contents of the cookie variable
 *      - Set the Secure flag
 *      - Cookie validity (unix timestamp) as int or bool
 *      - Set the “sameSite” HttpOnly flag
 * Returns:
 *      - void
 * @access public
 */
function serendipity_setCookie(string $name, string $value, bool $securebyprot = true, int|bool $custom_timeout = false, bool $httpOnly = true) : void {
    global $serendipity;

    $host = $_SERVER['HTTP_HOST'];
    if ($securebyprot) {
        $secure = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? true : false;
        if ($pos = strpos($host, ':')) {
            $host = substr($host, 0, $pos);
        }
    } else {
        $secure = false;
    }

    // If HTTP-Hosts like "localhost" are used, current browsers reject cookies.
    // In this case, we disregard the HTTP host to be able to set that cookie.
    if (substr_count($host, '.') < 1) {
        $host = '';
    }

    if ($custom_timeout === false) {
        $custom_timeout = time() + 60*60*24*30;
    }

    $options = [
        'expires'   => $custom_timeout,
        'path'      => $serendipity['serendipityHTTPPath'],
        'domain'    => $host,
        'secure'    => $secure,
        'httponly'  => $httpOnly,
        'samesite'  => 'Lax'
    ];
    #setcookie("serendipity[$name]", $value, $custom_timeout, $serendipity['serendipityHTTPPath'], $host, $secure, $httpOnly);
    // As $options array for use with 6th param 'sameSite' ! Requires PHP 7.3.0 ++ !!
    setcookie("serendipity[$name]", $value, $options);
    $_COOKIE[$name] = $value;
    $serendipity['COOKIE'][$name] = $value;
}

/**
 * Echo Javascript code to set a cookie variable
 *
 * This function is useful if your HTTP headers were already sent, but you still want to set a cookie
 * Note that contents are echoed, not returned. Can be used by plugins.
 *
 * Args:
 *      - The name of the cookie variable
 *      - The contents of the cookie variable
 * Returns:
 *      - void
 * @access public
 */
function serendipity_JSsetCookie(string $name, string $value) : void {
    $name  = htmlentities($name);
    $value = urlencode($value);

    echo '    <script type="text/javascript">serendipity.SetCookie("' . $name . '", unescape("' . $value . '"))</script>';
}

/**
 * Deletes an existing cookie value
 *
 * LONG
 *
 * Args:
 *      - Name of the cookie to delete
 * Returns:
 *      - void
 * @access public
 */
function serendipity_deleteCookie(string $name) : void {
    global $serendipity;

    $host = $_SERVER['HTTP_HOST'];
    if ($pos = strpos($host, ':')) {
        $host = substr($host, 0, $pos);
    }

    // If HTTP-Hosts like "localhost" are used, current browsers reject cookies.
    // In this case, we disregard the HTTP host to be able to set that cookie.
    if (substr_count($host, '.') < 1) {
        $host = '';
    }

    $secure = (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on') ? true : false;
    $options = [
        'expires'   => time()-4000,
        'path'      => $serendipity['serendipityHTTPPath'],
        'domain'    => $host,
        'secure'    => $secure,
        'httponly'  => true,
        'samesite'  => 'Lax'
    ];
    #setcookie("serendipity[$name]", '', time()-4000, $serendipity['serendipityHTTPPath'], $host);
    // As $options array for use with 6th param 'sameSite' ! Requires PHP 7.3.0 ++ !! Avoids additional errors for secure and sameSite attributes.
    setcookie("serendipity[$name]", '', $options);
    unset($_COOKIE[$name]);
    unset($serendipity['COOKIE'][$name]);
}

/**
 * Performs a check whether an iframe for the admin section shall be emitted
 *
 * The iframe is used for previewing an entry with the stylesheet of the frontend.
 * It fetches its data from the session input data.
 *
 * Args:
 *      -
 * Returns:
 *      - True, if iframe was requested, false if not
 * @access private
 */
function serendipity_is_iframe() : bool {
    global $serendipity;

    if (isset($serendipity['GET']['is_iframe']) && $serendipity['GET']['is_iframe'] == 'true' && isset($_SESSION['save_entry']) && is_array($_SESSION['save_entry'])) {
        if (!is_object($serendipity['smarty'])) {
            // We need Smarty also in the iframe to load a template's config.inc.php and register possible event hooks.
            serendipity_smarty_init();
        }
        return true;
    }
    return false;
}

/**
 * Prints the content of the iframe.
 *
 * Called by serendipity_is_iframe, when preview is requested. Fetches data from session.
 * An iframe is used so that a single s9y page must not timeout on intensive operations,
 * and so that the frontend stylesheet can be embedded without screwing up the backend.
 *
 * Args:
 *      - The entry array (comes from session variable)
 *      - Indicates whether an entry is previewed or saved. Save performs XML-RPC calls.
 *      - Use Smarty templating?
 * Returns:
 *      - Indicates whether iframe data was printed
 * @access private
 * @see serendipity_is_iframe()
 */
function serendipity_iframe(iterable &$entry, ?string $mode = null) : string|bool {
    global $serendipity;

    if (empty($mode) || !is_array($entry)) {
        return false;
    }

    $data = array();
    $data['is_preview'] = true;
    $data['mode'] = $mode;

    switch ($mode) {
        case 'save':
            ob_start();
            $res = serendipity_updertEntry($entry);
            $data['updertHooks'] = ob_get_contents();
            ob_end_clean();
            if (is_string($res)) {
                $data['res'] = $res;
            } else {
                $data['res'] = null;
            }
            if (!empty($serendipity['lastSavedEntry'])) {
                $data['lastSavedEntry'] = $serendipity['lastSavedEntry'];
            }
            $data['entrylink'] = serendipity_archiveURL($res, $entry['title'], 'serendipityHTTPPath', true, array('timestamp' => $entry['timestamp']));
            break;

        case 'preview':
            if ($serendipity['template'] == 'default-php') {
                // catch the entry already parsed through entries.tpl
                ob_start();
                echo serendipity_printEntries(array($entry), !empty($entry['extended']), true); //ok
                $php_preview = ob_get_contents();
                ob_end_clean();
            }

            // If 'smarty_preview' global is set true && the 'use_iframe' global is set false,
            // the default/admin/index.tpl etc. is used, which has no dark mode !! and this preview backend page turns white !!
            if ($serendipity['use_iframe'] !== false) {
                $serendipity['smarty_preview'] = true;
            }

            if (!empty($php_preview)) {
                $data['preview'] = $php_preview;
            } else {
                $data['preview'] = serendipity_printEntries(array($entry), !empty($entry['extended']), true);
            }
            if ($serendipity['use_iframe'] === false) {
                // just return the content in a pre configured container
                $data['preview'] = '<span class="msg_success"><span class="icon-ok-circled" aria-hidden="true"></span> ' . IFRAME_PREVIEW . " (<span class=\"icon-attention-circled\" aria-hidden=\"true\"></span>  A preview <strong>w/o iframe</strong> has <strong>no</strong> frontend theme styles ! )</span>\n"
                . '<div class="is_entry_preview no_iframe_data">'
                . $data['preview']
                . "</div>\n";
                return $data['preview'];
            }
            break;
    }

    // The "hybrid" preview_iframe is not that easy to parse through template_api.inc, thus we workaround it for the PHP template
    if ($serendipity['template'] == 'default-php' && (!empty($php_preview) || $mode == 'save')) {
        $data['lang']                           = $serendipity['smarty']->tpl_vars['lang']->value;
        $data['iconizr']                        = serendipity_getTemplateFile('admin/preview_iconizr.css'); // unforced since backend
        $data['modernizr']                      = serendipity_getTemplateFile('admin/js/modernizr.min.js'); // dito
        $data['head_charset']                   = $serendipity['smarty']->tpl_vars['head_charset']->value;
        $data['serendipityVersion']             = $serendipity['smarty']->tpl_vars['serendipityVersion']->value;
        $data['head_link_stylesheet']           = $serendipity['smarty']->tpl_vars['head_link_stylesheet']->value;
        $data['head_link_stylesheet_frontend']  = $serendipity['smarty']->tpl_vars['head_link_stylesheet_frontend']->value;
        $data['serendipityHTTPPath']            = $serendipity['smarty']->tpl_vars['serendipityHTTPPath']->value;
        $data['serendipityRewritePrefix']       = $serendipity['smarty']->tpl_vars['serendipityRewritePrefix']->value;
        // mode save vars already are in $data array
        $GLOBALS['tpl'] = $data; // assign to
        // catch the right preview_iframe file
        ob_start();
        include serendipity_getTemplateFile('preview_iframe.tpl', 'serendipityPath', true); // forced!
        $php_iframe = ob_get_contents();
        ob_end_clean();
        unset ($GLOBALS['tpl']);
        return $php_iframe;
    }

    return serendipity_smarty_showTemplate('preview_iframe.tpl', $data);
}

/**
 * Creates the necessary session data to be used by later iframe calls
 *
 * This function emits the actual <iframe> call.
 *
 * Args:
 *      - Indicates whether an entry is previewed or saved. Save performs XML-RPC calls.
 *      - The entry array (comes from HTTP POST request)
 * Returns:
 *      - Indicates whether iframe data was stored
 * @access private
 * @see serendipity_is_iframe()
 */
function serendipity_iframe_create(string $mode, iterable &$entry) : string|bool {
    global $serendipity;

    if (!empty($serendipity['POST']['no_save'])) {
        return true;
    }

    if (!serendipity_checkFormToken()) {
        return false;
    }

    $_SESSION['save_entry']      = $entry;
    $_SESSION['save_entry_POST'] = $serendipity['POST'];

    $attr = '';
    switch($mode) {
        case 'save':
            $attr = ' height="100" ';
            break;

        case 'preview':
            $attr = ' height="300" ';
            break;
    }

    // This IFRAME_WARNING, for case the browser in use does not support it, is written beneath closing iframe </html> element and so is readable in the source which may confuse users. This is a Chromium only issue. Firefox is sane !
    return '<iframe src="serendipity_admin.php?serendipity[is_iframe]=true&amp;serendipity[iframe_mode]=' . $mode . '" id="serendipity_iframe" name="serendipity_iframe" ' . $attr . ' width="100%" frameborder="0" marginwidth="0" marginheight="0" scrolling="auto" title="Serendipity">'
         . IFRAME_WARNING
         . '</iframe>';
}

/**
 * Pre-Checks certain server environments to indicate available options when installing Serendipity
 *
 * Args:
 *      - The name of the configuration option that needs to be checked for environmental data.
 * Returns:
 *      - Returns the array of available options for the requested config option
 * @access public
 */
function serendipity_probeInstallation(string $item) : iterable {
    $res = NULL;

    switch ($item) {
        case 'dbType' :
            $res =  array();
            if (extension_loaded('mysqli')) {
                $res['mysqli'] = 'MySQLi (default)';
            }

            if (extension_loaded('PDO') && in_array('pgsql', PDO::getAvailableDrivers())) {
                $res['pdo-postgres'] = 'PDO::PostgreSQL';
            }
            if (extension_loaded('pgsql')) {
                $res['postgres'] = 'PostgreSQL';
            }

            if (extension_loaded('sqlite') && function_exists('sqlite_open')) {
                $res['sqlite'] = 'SQLite';
            }
            if (extension_loaded('SQLITE3') && function_exists('sqlite3_open')) {
                $res['sqlite3'] = 'SQLite3';
            }
            if (extension_loaded('PDO') && in_array('sqlite', PDO::getAvailableDrivers())) {
                $res['pdo-sqlite'] = 'PDO::SQLite';
                $has_pdo = true;
            } else {
                $has_pdo = false;
            }
            if (class_exists('SQLite3')) {
                if ($has_pdo) {
                    $res['sqlite3oo'] = 'SQLite3 (OO - Preferably use PDO-SQlite!)';
                } else {
                    $res['sqlite3oo'] = 'SQLite3 (OO)';
                }
            }

            if (function_exists('sqlrcon_alloc')) {
                $res['sqlrelay'] = 'SQLRelay';
            }
            break;

        case 'rewrite' :
            $res = array();
            $res['none'] = 'Disable URL Rewriting';
            $res['errordocs'] = 'Use Apache errorhandling';
            if (!function_exists('apache_get_modules') || in_array('mod_rewrite', apache_get_modules())) {
                $res['rewrite'] = 'Use Apache mod_rewrite';
                $res['rewrite2'] = 'Use Apache mod_rewrite (for 1&amp;1 and problematic servers)';
            }
            break;
    }

    return $res;
}

/**
 * Sets a HTTP header
 *
 * Args:
 *      - The HTTP header to set
 * Returns:
 *      - void
 * @access public
 */
function serendipity_header(string $header) : void {
    if (!headers_sent()) {
        header($header);
    }
}

/**
 * Gets the currently selected language. Either from the browser, or the personal configuration, or the global configuration.
 *
 * This function also sets HTTP Headers and cookies to contain the language for follow-up requests
 * TODO:
 * This previously was handled inside a plugin with an event hook, but caching
 * the event plugins that early in sequence created trouble with plugins not
 * having loaded the right language.
 * Find a way to let plugins hook into that sequence :-)
 *
 * Args:
 *      -
 * Returns:
 *      - Returns the name of the selected language
 * @access public
 */
function serendipity_getSessionLanguage() : string {
    global $serendipity;

    // DISABLE THIS! Probably a debug session before Aug 2, 2006.
/*
    if ($_SESSION['serendipityAuthedUser']) {
        serendipity_header('X-Serendipity-InterfaceLangSource: Database');
        return $serendipity['lang'];
    }
*/
    // Global 'lang' is SET but NOT in language list, USE 'autolang' == 'en'
    if (isset($serendipity['lang']) && !isset($serendipity['languages'][$serendipity['lang']])) {
        $serendipity['lang'] = $serendipity['autolang'];
    }
    // Customized valid 'user_language' requested by [-multilingual plugins-] SET 'serendipityLanguage' COOKIE
    if (isset($_REQUEST['user_language']) && (!empty($serendipity['languages'][$_REQUEST['user_language']])) && !headers_sent()) {
        serendipity_setCookie('serendipityLanguage', $_REQUEST['user_language'], false);
    }
    // Pre-checked internal $lang variable for the 'detected_lang' global
    // Check the 'serendipityLanguage' COOKIE, then valid ['GET']['lang_selected'], then 'lang_content_negotiation' true for detectLang fallback
    if (isset($serendipity['COOKIE']['serendipityLanguage'])) {
        if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLangSource: Cookie');
        $lang = $serendipity['COOKIE']['serendipityLanguage'];
    } elseif (isset($serendipity['GET']['lang_selected']) && !empty($serendipity['languages'][$serendipity['GET']['lang_selected']])) {
        if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLangSource: GET');
        $lang = $serendipity['GET']['lang_selected'];
    } elseif (serendipity_db_bool($serendipity['lang_content_negotiation'])) {
        if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLangSource: Content-Negotiation');
        $lang = serendipity_detectLang();
    }

    // Globally store detected $lang
    if (isset($lang)) {
        $serendipity['detected_lang'] = $lang;
    } else {
        // Case configuration form
        // Keep the current configured 'lang' global to later show up as the selected and configured language in the configuration form, to indicate the public non-logged-in frontend language set
        if (!empty($serendipity['lang']) && isset($serendipity['GET']['adminModule']) && $serendipity['GET']['adminModule'] == 'configuration') {
            $serendipity['configurated_lang'] = $serendipity['lang'];
        }
        // Now, set the internal $lang variable to return, by SESSION or COOKIE'userDefLang'] or 'lang' global
        if (! empty($_SESSION['serendipityLanguage'])) {
            $lang = $_SESSION['serendipityLanguage'];
        } else {
            if (isset($serendipity['COOKIE']['userDefLang']) && ! empty($serendipity['COOKIE']['userDefLang'])) {
                $lang = $serendipity['COOKIE']['userDefLang'];
            } else {
                $lang = $serendipity['lang'];
            }
        }
        // Reset a possible 'detected_lang' cached global
        $serendipity['detected_lang'] = null;
    }

    // Only allow valid $lang sets, ELSE set the SESSION['serendipityLanguage']
    if (!isset($serendipity['languages'][$lang])) {
        $serendipity['detected_lang'] = null;

        return $serendipity['lang'];
    } else {
        $_SESSION['serendipityLanguage'] = $lang;
        if (!is_null($serendipity['detected_lang'])) {
            if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLang: ' . $lang);
        }
    }

    return $lang;
}

/**
 * Gets the selected language from personal configuration if needed
 *
 * This function also sets HTTP Headers and cookies to contain the language for follow-up requests
 *
 * Args:
 *      -
 * Returns:
 *      - Returns the name of the selected language
 * @access public
 */
function serendipity_getPostAuthSessionLanguage() : string {
    global $serendipity;

    if (! is_null($serendipity['detected_lang'])) {
        return $serendipity['detected_lang'];
    }

    if ($_SESSION['serendipityAuthedUser']) {
        if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLangSource: Database');
        $lang = $serendipity['lang'];
    } else {
        $lang = $_SESSION['serendipityLanguage'] ?? $serendipity['lang'];
    }

    if (!isset($serendipity['languages'][$lang])) {
        $lang = $serendipity['lang'];
    }

    $_SESSION['serendipityLanguage'] = $lang;

    if ($serendipity['expose_s9y']) serendipity_header('X-Serendipity-InterfaceLang: ' . $lang);

    if ($lang != $serendipity['lang']) {
        $serendipity['content_lang'] = $lang;
    }

    return $lang;
}

/**
 * Retrieves an array of applying permissions to an author
 *
 * The privileges of each group an author is a member of are aggregated
 * and stored in a larger array. So both memberships and all applying
 * privileges are returned.
 *
 * Args:
 *      - The ID of the author to fetch permissions/group memberships for
 * Returns:
 *      - Multi-dimensional associative array which holds a 'membership' and permission name data
 * @access public
 */
function &serendipity_getPermissions(int $authorid) : iterable {
    global $serendipity;

    // Get group information
    $groups =& serendipity_db_query("SELECT ag.groupid, g.name, gc.property, gc.value
                                      FROM {$serendipity['dbPrefix']}authorgroups AS ag
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}groups AS g
                                        ON ag.groupid = g.id
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}groupconfig AS gc
                                        ON gc.id = g.id
                                     WHERE ag.authorid = " . $authorid);
    $perm = array('membership' => array());
    if (is_array($groups)) {
        foreach($groups AS $group) {
            $perm['membership'][$group['groupid']]       = $group['groupid'];
            $perm[$group['groupid']][$group['property']] = $group['value'];
        }
    }

    return $perm;
}

/**
 * Returns the list of available internal Serendipity permission field names
 *
 * This function also maps which function was available to which userlevel in older Serendipity versions.
 * Thus if an author does not have a certain privilege he should have because of his userlevel, this can be reverse-mapped.
 *
 * Args:
 *      -
 * Returns:
 *      - Multi-dimensional associative array which the list of all permission items plus their userlevel associations
 * @access public
 */
function serendipity_getPermissionNames() : iterable {
    return array(
        'personalConfiguration'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'personalConfigurationUserlevel'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'personalConfigurationNoCreate'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'personalConfigurationRightPublish'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'siteConfiguration'
            => array(USERLEVEL_ADMIN),
        'siteAutoUpgrades'
            => array(USERLEVEL_ADMIN),
        'blogConfiguration'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminEntries'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminEntriesMaintainOthers'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminImport'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminCategories'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminCategoriesMaintainOthers'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminCategoriesDelete'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminUsers'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminUsersDelete'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminUsersEditUserlevel'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminUsersMaintainSame'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminUsersMaintainOthers'
            => array(USERLEVEL_ADMIN),
        'adminUsersCreateNew'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminUsersGroups'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminPlugins'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminPluginsMaintainOthers'
            => array(USERLEVEL_ADMIN),

        'adminImages'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminImagesDirectories'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminImagesAdd'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminImagesDelete'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminImagesMaintainOthers'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),
        'adminImagesViewOthers'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminImagesView'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF, USERLEVEL_EDITOR),
        'adminImagesSync'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminComments'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'adminTemplates'
            => array(USERLEVEL_ADMIN, USERLEVEL_CHIEF),

        'hiddenGroup'
            => array(-1)
    );
}

/**
 * Checks if a permission is granted to a specific author
 *
 * This function caches all permission checks in static function variables to not fetch all permissions time and again.
 * The permission checks are performed against the values of each group. If a privilege is set in one of the groups
 *     the author is a user of, the function returns true.
 * If a privilege is not set, the userlevel of an author is checked to act for backwards-compatibility.
 *
 * Args:
 *      -
 * Returns:
 *      - Either returns true if a permission check is performed or false if not, or returns an array of group memberships. This depends on the $returnMyGroups variable
 * @access public
 * @see serendipity_getPermissionNames()
 */
function serendipity_checkPermission(?string $permName = null, ?int $authorid = null, bool|string $returnMyGroups = false) : bool|iterable {
    global $serendipity;
    // Define old serendipity permissions
    static $permissions = null;
    static $group = null;

    if (IS_installed !== true) {
        return true;
    }

    if ($permissions === null) {
        $permissions = serendipity_getPermissionNames();
    }

    if ($group === null) {
        $group = array();
    }

    if ($authorid === null) {
        $authorid = $serendipity['authorid'] ?? 0;
    }

    if (!isset($group[$authorid])) {
        $group[$authorid] = serendipity_getPermissions((int) $authorid);
    }

    if ($returnMyGroups) {
        if ($returnMyGroups === 'all') {
            return $group[$authorid];
        } else {
            return $group[$authorid]['membership'];
        }
    }

    if (!empty($authorid) && !empty($serendipity['authorid']) && $authorid == $serendipity['authorid'] && $serendipity['no_create']) {
        // This no_create user privilege overrides other permissions.
        return false;
    }

    $return = true;

    foreach($group[$authorid] AS $item) {
        if (!isset($item[$permName])) {
            continue;
        }

        if ($item[$permName] === 'true') {
            return true;
        } else {
            $return = false;
        }
    }

    // If the function did not yet return it means there's a check for a permission which is not defined anywhere.
    // Let's use a backwards compatible way.
    if ($return && isset($permissions[$permName]) && isset($serendipity['serendipityUserlevel']) && in_array($serendipity['serendipityUserlevel'], $permissions[$permName])) {
        return true;
    }

    return false;
}

/**
 * Update author group membership(s)
 *
 * Args:
 *      - The array of groups the author should be a member of. All memberships that were present before and not contained in this array will be removed.
 *      - The ID of the author to update
 *      - If set to true, the groups can only be updated if the user has the adminUsersMaintainOthers privilege.
 *          If set to false, group memberships will be changeable for any user.
 * Returns:
 *      - True on success, False on failed permissions
 * @access public
 */
function serendipity_updateGroups(iterable $groups, int $authorid, bool $apply_acl = true) : bool {
    global $serendipity;

    if ($apply_acl && !serendipity_checkPermission('adminUsersMaintainOthers')) {
        return false;
    }

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authorgroups WHERE authorid = " . $authorid);

    foreach($groups AS $group) {
        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}authorgroups (authorid, groupid) VALUES (" . $authorid . ", " . (int) $group . ")");
    }

    return true;
}

/**
 * Returns all authorgroups that are available
 *
 * If a groupname is prefixed with "USERLEVEL_" then the constant of that name is used to
 * return the name of the group. This allows inserting the special groups Chief Editor, Editor
 * and admin and still being able to use multilingual names for these groups.
 *
 * Args:
 *      - If set to an author ID value, only groups are fetched that this author is a member of.
 *          If set to false, all groups are returned, also those that the current user has no access to.
 * Returns:
 *      - An associative array of group names
 * @access public
 */
function &serendipity_getAllGroups(int|string|bool $apply_ACL_user = false) : iterable {
    global $serendipity;

    if ($apply_ACL_user) {
        $groups =& serendipity_db_query("SELECT g.id   AS confkey,
                                                g.name AS confvalue,
                                                g.id   AS id,
                                                g.name AS name
                                           FROM {$serendipity['dbPrefix']}authorgroups AS ag
                                LEFT OUTER JOIN {$serendipity['dbPrefix']}groups AS g
                                             ON g.id = ag.groupid
                                          WHERE ag.authorid = " . (int) $apply_ACL_user . "
                                       ORDER BY g.name", false, 'assoc');
    } else {
        $groups =& serendipity_db_query("SELECT g.id   AS confkey,
                                                g.name AS confvalue,
                                                g.id   AS id,
                                                g.name AS name
                                          FROM {$serendipity['dbPrefix']}groups AS g
                                      ORDER BY  g.name", false, 'assoc');
    }
    if (is_array($groups)) {
        // exclude hidden groups for certain case USERLEVEL_CHIEF and hiddengroup has a 'siteAutoUpgrades' flag permission
        $hgroup = serendipity_db_query("SELECT id FROM {$serendipity['dbPrefix']}groupconfig WHERE property = 'hiddenGroup' AND value = 'true'", true, 'assoc');

        foreach($groups AS $k => $v) {
            // build the USERLEVEL_constant names
            if (str_starts_with($v['confvalue'], 'USERLEVEL_')) {
                $groups[$k]['confvalue'] = $groups[$k]['name'] = constant($v['confvalue']);
            }
            if (in_array($v['confvalue'], ['USERLEVEL_ADMIN_DESC', 'USERLEVEL_CHIEF_DESC', 'USERLEVEL_EDITOR_DESC'])) {
                $groups[$k]['shortname'] = strtolower(explode('_', $v['confvalue'])[1]);
            }
            $groups[$k]['shortname'] = $groups[$k]['shortname'] ?? null;
            // Check CHIEF against hiddenGroup
            if (!$apply_ACL_user && $serendipity['serendipityUserlevel'] == USERLEVEL_CHIEF) {
                if (!isset($hgroup[0]) && isset($hgroup['id']) && $v['id'] == $hgroup['id']) {
                    unset($groups[$k]);
                } else if (isset($hgroup[0]) && is_array($hgroup[0])) {
                    foreach($hgroup AS $hg) {
                        if (isset($hg['id']) && $v['id'] == $hg['id']) {
                            unset($groups[$k]);
                        }
                    }
                }
            }
        }
        // sort natural by name - start mattering if having additional groups
        usort($groups, function($a, $b) {
            return strnatcasecmp($a['name'], $b['name']);
        });
    }

    return $groups;
}

/**
 * Fetch the permissions of a certain group
 *
 * Args:
 *      - The ID of the group that the permissions are fetched for. KEEP string type for compat since mostly stems from GET POST array data
 * Returns:
 *      - The associative array of permissions of a group
 * @access public
 */
function &serendipity_fetchGroup(int|string $groupid) : iterable {
    global $serendipity;

    serendipity_typeCompatCheckID($groupid);

    $conf = array();
    $groups =& serendipity_db_query("SELECT g.id        AS confkey,
                                            g.name      AS confvalue,
                                            g.id        AS id,
                                            g.name      AS name,

                                            gc.property AS property,
                                            gc.value    AS value
                                      FROM {$serendipity['dbPrefix']}groups AS g
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}groupconfig AS gc
                                        ON g.id = gc.id
                                     WHERE g.id = " . (int) $groupid, false, 'assoc');

    if (is_array($groups)) {
        foreach($groups AS $group) {
            $conf[$group['property']] = $group['value'];
        }
    }

    if (!empty($conf) && !empty($groups[0])) {
        // The following are unique
        $conf['name']      = $groups[0]['name'] ?? null;
        $conf['id']        = $groups[0]['id'] ?? null;
        $conf['confkey']   = $groups[0]['confkey'] ?? null;
        $conf['confvalue'] = $groups[0]['confvalue'] ?? null;
    }

    return $conf;
}

/**
 * Gets all groups a user is a member of
 *
 * Args:
 *      - The authorid to fetch groups for .... KEEP string type allowance for compat since most stem from global serendipity array
 *      - Indicate whether the original multi-dimensional DB result array shall be returned (FALSE) or if the array shall be flattened to be 1-dimensional (TRUE).
 * Returns:
 *      - The associative array of groups
 * @access public
 */
function &serendipity_getGroups(int|string $authorid, bool $sequence = false) : iterable {
    global $serendipity;

    serendipity_typeCompatCheckID($authorid);

    $_groups =& serendipity_db_query("SELECT g.id  AS confkey,
                                            g.name AS confvalue,
                                            g.id   AS id,
                                            g.name AS name
                                      FROM {$serendipity['dbPrefix']}authorgroups AS ag
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}groups AS g
                                        ON g.id = ag.groupid
                                     WHERE ag.authorid = " . (int) $authorid, false, 'assoc');
    if (!is_array($_groups)) {
        $groups = array();
    } else {
        $groups =& $_groups;
    }

    if ($sequence) {
        $rgroups = [];
        foreach($groups AS $grouprow) {
            $rgroups[] = $grouprow['confkey'];
        }
    } else {
        $rgroups =& $groups;
    }

    return $rgroups;
}

/**
 * Gets all author IDs of a specific group
 *
 * Args:
 *      -
 * Returns:
 *      - The associative array of author IDs and names
 * @access public
 *      - The ID of the group to fetch the authors of
 */
function &serendipity_getGroupUsers(int $groupid) : iterable|bool {
    global $serendipity;

    $groups =& serendipity_db_query("SELECT g.name     AS name,
                                            a.realname AS author,
                                            a.authorid AS id
                                      FROM {$serendipity['dbPrefix']}authorgroups AS ag
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}groups AS g
                                        ON g.id = ag.groupid
                           LEFT OUTER JOIN {$serendipity['dbPrefix']}authors AS a
                                        ON ag.authorid = a.authorid
                                     WHERE ag.groupid = " . $groupid, false, 'assoc');
    return $groups;
}

/**
 * Deletes a specific author group by ID
 *
 * Args:
 *      - The group ID to delete
 * Returns:
 *      - Return true if group could be deleted, false if insufficient privileges
 * @access public
 */
function serendipity_deleteGroup(int $groupid) : bool {
    global $serendipity;

    if (!serendipity_checkPermission('adminUsersGroups')) {
        return false;
    }

    if (!serendipity_checkPermission('adminUsersMaintainOthers')) {
        // Only groups should be accessible where a user has access rights.
        $my_groups = serendipity_getGroups($serendipity['authorid'], true);
        if (!in_array($groupid, $my_groups)) {
            return false;
        }
    }

    // Do not allow to delete the administrators (1) GROUP named USERLEVEL_ADMIN_DESC (3)
    $self  = serendipity_db_query("SELECT authorid FROM {$serendipity['dbPrefix']}authorgroups WHERE groupid = " . $groupid . " LIMIT 1", true, 'assoc');
    $group = serendipity_db_query("SELECT name FROM {$serendipity['dbPrefix']}groups WHERE id = " . $groupid . " LIMIT 1", true, 'assoc');
    if ($serendipity['authorid'] == 1 && $group['name'] == 'USERLEVEL_ADMIN_DESC' && $self['authorid'] == $serendipity['authorid']) {
        return false;
    }

    if (serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}groups WHERE id = " . $groupid)) {
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authorgroups WHERE groupid = " . $groupid);
    }

    return true;
}

/**
 * Creates a new author group
 *
 * Args:
 *      - The name of the new group
 * Returns:
 *      - The id of the created group
 * @access public
 */
function serendipity_addGroup(string $name) : int {
    global $serendipity;

    serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}groups (name) VALUES ('" . serendipity_db_escape_string($name) . "')");
    $gid = serendipity_db_insert_id('groups', 'id');

    return $gid;
}

/**
 * Returns a list of all existing permission names.
 *
 * Additional plugins might insert specific properties into the groupconfig database to handle their own privileges.
 * This call returns an array of all available permission names so that it can be intersected with the list of internal
 * permission names (serendipity_getPermissionNames()) and then be distincted.
 *
 * Args:
 *      -
 * Returns:
 *      - associative array of all available permission names
 * @access public
 * @see serendipity_getPermissionNames()
 */
function &serendipity_getDBPermissionNames() : iterable|bool {
    global $serendipity;

    $config =& serendipity_db_query("SELECT property FROM {$serendipity['dbPrefix']}groupconfig GROUP BY property ORDER BY property", false, 'assoc');

    return $config;
}

/**
 * Gets the list of all Permissions and merges the two arrays
 *
 * The first call will fetch all existing permission names of the database. Then it will
 * fetch the list of defined internal permission names, which has an array with extra information
 * (like userlevel). This array will be merged with those permission names only found in the
 * database. The returned array will then hold as much information about permission names as is
 * available.
 * TODO Might need further pushing and/or an event hook so that external plugins using the
 * permission system can inject specific information into the array
 *
 * Args:
 *      -
 * Returns:
 *      - Returns the array with all information about all permission names
 * @access public
 * @see serendipity_getPermissionNames()
 * @see serendipity_getDBPermissionNames()
 */
function &serendipity_getAllPermissionNames() : iterable {

    $DBperms =& serendipity_getDBPermissionNames();
    $perms   = serendipity_getPermissionNames();

    foreach($DBperms AS $perm) {
        if (!isset($perms[$perm['property']])) {
            $perms[$perm['property']] = array();
        }
    }

    return $perms;
}

/**
 * Checks if two users are members of the same group
 *
 * This function will retrieve all group memberships of a foreign user ($checkuser) and yourself ($myself).
 * Then it will check if there is any group membership that those two users have in common.
 * It can be used for detecting if a different author should be allowed to access your entries,
 * because he's in the same group, for example.
 *
 * Args:
 *      - ID of the first author to check group memberships
 *      - ID of the second author to check group memberships
 * Returns:
 *      - True if a membership intersects, false if not
 * @access public
 */
function serendipity_intersectGroup(?int $checkuser = null, ?int $myself = null) : bool {
    global $serendipity;

    if ($myself === null) {
        $myself = $serendipity['authorid'];
    }

    $my_groups  = serendipity_getGroups($myself, true);
    $his_groups = serendipity_getGroups($checkuser, true);

    foreach($his_groups AS $his_group) {
        if (in_array($his_group, $my_groups)) {
            return true;
        }
    }

    return false;
}

/**
 * Updates the configuration of permissions of a specific group
 *
 * This function ensures that a group can only be updated from users that have permissions to do so.
 * Args:
 *      - The ID of the group to update
 *      - The associative array of permission names
 *      - The associative array of new values for the permissions. Needs the same associative keys like the $perms array.
 *      - Indicates if an all new privilege should be inserted (true) or if an existing privilege is going to be checked
 *      - The associative array of plugin permission names
 *      - The associative array of plugin permission hooks
 * Returns:
 *      - True on success, False on empty or failed permissions
 * @access public
 */
function serendipity_updateGroupConfig(int $groupid, iterable &$perms, iterable &$values, bool $isNewPriv = false, ?iterable $forbidden_plugins = null, ?iterable $forbidden_hooks = null) : bool {
    global $serendipity;

    if (!serendipity_checkPermission('adminUsersGroups')) {
        return false;
    }

    if (!serendipity_checkPermission('adminUsersMaintainOthers')) {
        // Only groups should be accessible where a user has access rights.
        $my_groups = serendipity_getGroups($serendipity['authorid'], true);
        if (!in_array($groupid, $my_groups)) {
            return false;
        }
    }

    $storage =& serendipity_fetchGroup($groupid);

    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}groupconfig WHERE id = " . $groupid);
    foreach($perms AS $perm => $userlevels) {
        if (str_starts_with($perm, 'f_')) {
            continue;
        }

        if (isset($values[$perm]) && $values[$perm] == 'true') {
            $value = 'true';
        } elseif (isset($values[$perm]) && $values[$perm] === 'false') {
            $value = 'false';
        } elseif (isset($values[$perm])) {
            $value = $values[$perm];
        } else {
            $value = 'false';
        }
        // excludes hiddenGroup and siteAutoUpgrades per ADMINISTRATOR from possible permission denied ..
        if ($isNewPriv == false && !serendipity_checkPermission($perm) && $perm != 'hiddenGroup' && $perm != 'siteAutoUpgrades') {
            if (!isset($storage[$perm])) {
                $value = 'false';
            } else {
                $value = $storage[$perm];
            }
        }

        serendipity_db_query(
            sprintf("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES (%d, '%s', '%s')",
                $groupid,
                serendipity_db_escape_string($perm),
                serendipity_db_escape_string($value)
            )
        );
    }

    if (is_array($forbidden_plugins)) {
        foreach($forbidden_plugins AS $plugid) {
            serendipity_db_query(
                sprintf("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES (%d, '%s', 'true')",
                    $groupid,
                    serendipity_db_escape_string('f_' . urldecode($plugid))
                )
            );
        }
    }

    if (is_array($forbidden_hooks)) {
        foreach($forbidden_hooks AS $hook) {
            serendipity_db_query(
                sprintf("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES (%d, '%s', 'true')",
                    $groupid,
                    serendipity_db_escape_string('f_' . urldecode($hook))
                )
            );
        }
    }

    serendipity_db_query("UPDATE {$serendipity['dbPrefix']}groups SET name = '" . serendipity_db_escape_string($values['name']) . "' WHERE id = " . $groupid);

    if (isset($values['members']) && is_array($values['members'])) {
        serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}authorgroups WHERE groupid = " . $groupid);
        foreach($values['members'] AS $member) {
            serendipity_db_query(
                sprintf("INSERT INTO {$serendipity['dbPrefix']}authorgroups (groupid, authorid) VALUES (%d, %d)",
                    $groupid,
                    (int) $member
                )
            );
        }
    }

    return true;
}

/**
 * Adds a default internal group (Editor, Chief Editor, Admin)
 *
 * Args:
 *      - The name of the group to insert
 *      - The userlevel that represents this group (0|1|255 for Editor/Chief/Admin).
 * Returns:
 *      - true
 * @access public
 */
function serendipity_addDefaultGroup(string $name, int $level) : true {
    global $serendipity;
    static $perms = null;

    if ($perms === null) {
        $perms = serendipity_getPermissionNames();
    }

    serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}groups (name) VALUES ('" . serendipity_db_escape_string($name) . "')");
    $gid = (int)serendipity_db_insert_id('groups', 'id');
    serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES ($gid, 'userlevel', '" . $level . "')");

    $authors = serendipity_db_query("SELECT * FROM {$serendipity['dbPrefix']}authors WHERE userlevel = " . $level);

    if (is_array($authors)) {
        foreach($authors AS $author) {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}authorgroups (authorid, groupid) VALUES ('{$author['authorid']}', '$gid')");
        }
    }

    foreach($perms AS $permName => $permArray) {
        if (in_array($level, $permArray)) {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES ($gid, '" . serendipity_db_escape_string($permName) . "', 'true')");
        } else {
            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}groupconfig (id, property, value) VALUES ($gid, '" . serendipity_db_escape_string($permName) . "', 'false')");
        }
    }

    return true;
}

/**
 * Allow access to a specific item (category or entry) for a specific usergroup
 *
 * ACL are Access Control Lists. They indicate which read/write permissions a specific item has for specific usergroups.
 * An artifact in terms of Serendipity can be either a category or an entry, or anything beyond that for future compatibility.
 * This function sets up the ACLs.
 *
 * Args:
 *      - The ID of the artifact to set the access
 *      - The type of an artifact (category|entry)
 *      - The type of access to grant (read|write)
 *      - The ID of the group to grant access to
 *      - A variable option for an artifact
 * Returns:
 *      - True if ACL was applied, false if not
 * @access public
 */
function serendipity_ACLGrant(int $artifact_id, string $artifact_type, string $artifact_mode, iterable $groups, string $artifact_index = '') : bool {
    global $serendipity;

    if (empty($groups) || !is_array($groups)) {
        return false;
    }

    // Delete all old existing relations.
    serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}access
                                WHERE artifact_id    = " . $artifact_id . "
                                  AND artifact_type  = '" . serendipity_db_escape_string($artifact_type) . "'
                                  AND artifact_mode  = '" . serendipity_db_escape_string($artifact_mode) . "'
                                  AND artifact_index = '" . serendipity_db_escape_string($artifact_index) . "'");

    $data = array(
        'artifact_id'    => $artifact_id,
        'artifact_type'  => $artifact_type,
        'artifact_mode'  => $artifact_mode,
        'artifact_index' => $artifact_index
    );

    if (count($data) < 1) {
        return true;
    }

    foreach($groups AS $group) {
        $data['groupid'] = $group;
        serendipity_db_insert('access', $data);
    }

    return true;
}

/**
 * Checks if a specific item (category or entry) can be accessed by a specific usergroup
 *
 * ACL are Access Control Lists. They indicate which read/write permissions a specific item has for specific usergroups.
 * An artifact in terms of Serendipity can be either a category or an entry, or anything beyond that for future compatibility.
 * This function retrieves the ACLs.
 *
 * Args:
 *      - The ID of the artifact to set the access
 *      - The type of an artifact (category|entry)
 *      - The type of access to check for (read|write)
 *      - A variable option for an artifact
 * Returns:
 *      - Returns an array of all groups that are allowed for this kind of access. You can then check if you are the member of any of the groups returned here
 * @access public
 */
function serendipity_ACLGet(int $artifact_id, string $artifact_type, string $artifact_mode, string $artifact_index = '') : iterable|bool {
    global $serendipity;

    $sql = "SELECT groupid, artifact_index FROM {$serendipity['dbPrefix']}access
                    WHERE artifact_type  = '" . serendipity_db_escape_string($artifact_type) . "'
                      AND artifact_id    = '" . $artifact_id . "'
                      AND artifact_mode  = '" . serendipity_db_escape_string($artifact_mode) . "'
                      AND artifact_index = '" . serendipity_db_escape_string($artifact_index) . "'";
    $rows =& serendipity_db_query($sql, false, 'assoc');

    if (!is_array($rows)) {
        return false;
    }

    $acl = array();
    foreach($rows AS $row) {
        $acl[$row['groupid']] = $row['artifact_index'];
    }

    return $acl;
}

/**
 * Checks if a specific item (category or entry) can be accessed by a specific Author
 *
 * ACL are Access Control Lists. They indicate which read/write permissions a specific item has for specific usergroups.
 * An artifact in terms of Serendipity can be either a category or an entry, or anything beyond that for future compatibility.
 * This function retrieves the ACLs for a specific user.
 *
 * Args:
 *      - The ID of the author to check against.
 *      - The ID of the artifact to set the access
 *      - The type of an artifact ('category', more to come)
 *      - The type of access to check for (read|write)
 * Returns:
 *      - Returns true, if the author has access to this artifact. False if not
 * @access public
 */
function serendipity_ACLCheck(int $authorid, int $artifact_id, string $artifact_type, string $artifact_mode) : bool {
    global $serendipity;

    $artifact_sql = array();

    // TODO: If more artifact_types are available, the JOIN needs to be edited so that the first AND portion is not required, and the join is fully made on that condition.
    switch($artifact_type) {
        default:
        case 'category':
            $artifact_sql['unique']= "atf.categoryid";
            $artifact_sql['cond']  = "atf.categoryid = " . $artifact_id;
            $artifact_sql['where'] = "     ag.groupid = a.groupid
                                        OR a.groupid  = 0
                                        OR (a.artifact_type IS NULL AND (atf.authorid = " . $authorid . " OR atf.authorid = 0 OR atf.authorid IS NULL))";
            $artifact_sql['table'] = 'category';
    }

    $sql = "SELECT {$artifact_sql['unique']} AS result
              FROM {$serendipity['dbPrefix']}{$artifact_sql['table']} AS atf
   LEFT OUTER JOIN {$serendipity['dbPrefix']}authorgroups AS ag
                ON ag.authorid = ". $authorid . "
   LEFT OUTER JOIN {$serendipity['dbPrefix']}access AS a
                ON (    a.artifact_type = '" . serendipity_db_escape_string($artifact_type) . "'
                    AND a.artifact_id   = " . $artifact_id . "
                    AND a.artifact_mode = '" . serendipity_db_escape_string($artifact_mode) . "'
                   )

             WHERE {$artifact_sql['cond']}
               AND ( {$artifact_sql['where']} )
          GROUP BY result";
    // die($sql);
    // look my friend, as long as these categories authorid are 0=all the ACL permission check does not care to deny permission
    $res =& serendipity_db_query($sql, true, 'assoc');
    if (is_array($res) && !empty($res['result'])) {
        return true;
    }

    return false;
}

/**
 * Prepares a SQL statement to be used in queries that should be ACL restricted.
 *
 * ACL are Access Control Lists. They indicate which read/write permissions a specific item has for specific usergroups.
 * An artifact in terms of Serendipity can be either a category or an entry, or anything beyond that for future compatibility.
 * This function evaluates and applies the SQL statements required.
 * It is currently only written for retrieving Category ACLs.
 * All of the SQL code that will be used in serendipity_fetchEntries will be stored within the referenced $cond array.
 *
 * Args:
 *      - Associative array that holds the SQL part array to be used in other functions like serendipity_fetchEntries()
 *      - Some queries do not need to joins categories. When ACLs need to be applied, this column is required, so if $append_category is set to true it will perform this missing JOIN.
 *      - The ACL type ('category', 'directory')
 *      - ACL mode
 * Returns:
 *      - True if ACLs were applied, false if not
 * @access private
 */
function serendipity_ACL_SQL(iterable &$cond, bool|string $append_category = false, string $type = 'category', string $mode = 'read') : bool {
    global $serendipity;

    // A global configuration item controls whether the blog should apply ACLs or not!
    if (!isset($serendipity['enableACL']) || $serendipity['enableACL'] == true) {

        // If the user is logged in, we retrieve his authorid for the upcoming checks
        if (isset($_SESSION['serendipityAuthedUser']) && $_SESSION['serendipityAuthedUser'] === true) {
            $read_id = (int)$serendipity['authorid'];
            $read_id_sql = 'acl_a.groupid OR acl_acc.groupid = 0 ';
        } else {
            // "0" as category property counts as "anonymous viewers"
            $read_id     = 0;
            $read_id_sql = 0;
        }

        if ($append_category) {
            if ($append_category !== 'limited') {
                $cond['joins'] .= " LEFT JOIN {$serendipity['dbPrefix']}entrycat ec
                                           ON e.id = ec.entryid";
            }

            $cond['joins'] .= " LEFT JOIN {$serendipity['dbPrefix']}category c
                                       ON ec.categoryid = c.categoryid";
        }

        switch($type) {
            case 'directory':
                $sql_artifact_column = 'i.path IS NULL OR
                                        acl_acc.groupid IS NULL';
                $sql_artifact = 'AND acl_acc.artifact_index = i.path';
                break;

            case 'category':
                $sql_artifact_column = 'c.categoryid IS NULL';
                $sql_artifact = 'AND acl_acc.artifact_id   = c.categoryid';
                break;
        }

        $cond['joins'] .= "
                    LEFT JOIN {$serendipity['dbPrefix']}authorgroups AS acl_a
                           ON acl_a.authorid = " . $read_id . "
                    LEFT JOIN {$serendipity['dbPrefix']}access AS acl_acc
                           ON (    acl_acc.artifact_mode = '" . $mode . "'
                               AND acl_acc.artifact_type = '" . $type . "'
                               " . $sql_artifact . "
                              )";

        if (empty($cond['and'])) {
            $cond['and'] .= ' WHERE ';
        } else {
            $cond['and'] .= '                   AND ';
        }

        // When in Admin-Mode, apply readership permissions.
        if (isset($serendipity['GET']['adminModule']) && $serendipity['GET']['adminModule'] == 'entries' && !serendipity_checkPermission('adminEntriesMaintainOthers')) {
            $rperm = " AND ( c.authorid IS NULL OR c.authorid = 0 OR c.authorid = " . $read_id . " )";
        }
        $rperm  = $rperm ?? '';
        $rperm .= " )
                         )";
        $cond['and'] .= "
                         (
                            " . $sql_artifact_column . "
                            OR ( acl_acc.groupid = " . $read_id_sql . ")
                            OR ( acl_acc.artifact_id IS NULL" . $rperm;
        return true;
    }

    return false;
}

/**
 * Check for Cross-Site-Request-Forgery attacks because of missing HTTP Referer
 *
 * https://de.wikipedia.org/wiki/XSRF
 * https://en.wikipedia.org/wiki/HTTP_referer about the Etymology of the HTTP referer (originally a misspelling of referrer)
 * This function checks the HTTP referer, and if it is part of the current Admin panel.
 *
 * Args:
 *      -
 * Returns:
 *      - Returns true if XSRF was detected, false if not. The script should abort, if TRUE is returned
 * @access public
 */
function serendipity_checkXSRF() : bool {
    global $serendipity;

    // If no module was requested, the user has just logged in and no action will be performed.
    if (empty($serendipity['GET']['adminModule'])) {
        return false;
    }

    // The referrer was empty. Deny access.
    if (empty($_SERVER['HTTP_REFERER'])) {
        echo serendipity_reportXSRF(1, true, true);
        return false;
    }

    // Parse the Referrer host. Abort if not parseable.
    $hostinfo = @parse_url($_SERVER['HTTP_REFERER']);
    if (!is_array($hostinfo)) {
        echo serendipity_reportXSRF(2, true, true);
        return true;
    }

    // Get the server against we will perform the XSRF check.
    $server = '';
    if (empty($_SERVER['HTTP_HOST'])) {
        $myhost = @parse_url($serendipity['baseURL']);
        if (is_array($myhost)) {
            $server = $myhost['host'];
        }
    } else {
        $server = $_SERVER['HTTP_HOST'];
    }

    // If the current server is different than the referred server, deny access.
    if ($hostinfo['host'] != $server) {
        echo serendipity_reportXSRF(3, true, true);
        return true;
    }

    return false;
}

/**
 * Report a XSRF attempt to the Serendipity Interface
 *
 * https://de.wikipedia.org/wiki/XSRF
 *
 * LONG
 *
 * Args:
 *      - The type of XSRF check that got hit. Used for CSS formatting.
 *      - If true, the XSRF error should be fatal
 *      - If true, tell Serendipity to check the $serendipity['referrerXSRF'] config option to decide if an error should be reported or not.
 * Returns:
 *      - Returns the HTML error report
 * @access public
 * @see serendipity_checkXSRF()
 */
function serendipity_reportXSRF(string|int $type = 0, bool $reset = true, bool $use_config = false) : string {
    global $serendipity;

    // Set this in your serendipity_config_local.inc.php if you want HTTP Referrer blocking:
    // $serendipity['referrerXSRF'] = true;

    $string = '<div class="msg_error XSRF_' . $type . '"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_XSRF . '</div>';
    if ($reset) {
        // Config key "referrerXSRF" can be set to enable blocking based on HTTP Referrer. Recommended for Paranoia.
        if (($use_config && isset($serendipity['referrerXSRF']) && $serendipity['referrerXSRF']) || $use_config === false) {
            $serendipity['GET']['adminModule'] = '';
        } else {
            // Paranoia not enabled. Do not report XSRF.
            $string = '';
        }
    }

    return $string;
}

/**
 * Prevent XSRF attacks by checking for a form token
 *
 * https://de.wikipedia.org/wiki/XSRF
 *
 * This function checks, if a valid Form token was posted to the site.
 *
 * Args:
 *      - Whether to output or not
 * Returns:
 *      - Returns true, if XSRF attempt was found and the token was missing
 * @access public
 * @see serendipity_setFormToken()
 */
function serendipity_checkFormToken(bool $output = true) : bool {
    global $serendipity;

    $token = '';
    if (!empty($serendipity['POST']['token'])) {
        $token = $serendipity['POST']['token'];
    } elseif (!empty($serendipity['GET']['token'])) {
        $token = $serendipity['GET']['token'];
    }

    if (empty($token)) {
        if ($output) echo serendipity_reportXSRF('token', false);
        return false;
    }

    if ($token != hash('xxh3', session_id()) &&
        $token != hash('xxh3', $serendipity['COOKIE']['old_session'])) {
        if ($output) echo serendipity_reportXSRF('token', false);
        return false;
    }

    return true;
}

/**
 * Prevent XSRF attacks by setting a form token within HTTP Forms
 *
 * https://de.wikipedia.org/wiki/XSRF
 *
 * By inserting a unique Form token that holds the session id, all requests
 * to serendipity HTTP forms can only be processed if the token is present.
 * This effectively makes XSRF attacks impossible. Only bundled with XSS
 * attacks it can be bypassed.
 *
 * 'form' type tokens can be embedded within the <form> script.
 * 'url' type token can be embedded within HTTP GET calls.
 *
 * Args:
 *      - The type of token to return (form|url|plain)
 * Returns:
 *      - Returns the form token to be used in your functions
 * @access public
 */
function serendipity_setFormToken(string $type = 'form') : string {
    if ($type == 'form') {
        return '<input name="serendipity[token]" type="hidden" value="' . hash('xxh3', session_id()) . '">';
    } elseif ($type == 'url') {
        return 'serendipity[token]=' . hash('xxh3', session_id());
    } else {
        return hash('xxh3', session_id());
    }
}

/**
 * Check remote system notification XML for the Backend Dashboard ticker
 *
 * Args:
 *      - The return type to store or show
 *      - The Serendipity user identifier
 * Returns:
 *      - The cashed string or bool false
 * @access private
 * @param array     Have read XML item notification hash(es)
 */
function serendipity_sysInfoTicker(bool $check = false, string $whoami = '', iterable $exclude_hashes = []) : iterable|bool {
    if ($check === true) {
        global $serendipity;

        $xml = []; // create array for multiple notifications

        // Get XML via response blah blah or curl or temporary by an old copy fallback file
        $remoteURL = 'https://raw.githubusercontent.com/ophian/styx/master/tests/remote_notifications.xml';
        $target    = $serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/sysnotes/remote_notifications.xml';
        $context   = stream_context_create(array('http' => array('timeout' => 5.0)));
        // get and read and write to target
        $xmlstr   = @file_get_contents($remoteURL, false, $context);
        // Some servers return a " Warning: file_get_contents(): https:// wrapper is disabled in the server configuration by allow_url_fopen=0 " so we use Curl instead
        if (false === $xmlstr) {
            if (function_exists('curl_init')) {
                $ch = curl_init($remoteURL);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT, '5');
                $xmlstr = curl_exec($ch);
                curl_close($ch);
            }
        }
        // use fallback
        if (false === $xmlstr) {
            try {
                $xmlstr = @file_get_contents($target);
            } catch(\Throwable $t) {
                trigger_error('Error: The URL for the remote ticker could not be opened (' . $t->getMessage() . '), nor has a callback file been created yet. There may be server or network problems.', E_USER_NOTICE);
            }
        }

        // check the remote file string
        if (is_string($xmlstr) && !empty($xmlstr)) {
            $syscall = new SimpleXMLElement($xmlstr);

            foreach ($syscall->notification AS $n) {
                $hash = hash('xxh128', (string) $n->note); // hash-it
                $comb = hash('xxh128', "{$whoami}-{$hash}"); // new hash combo of user and the 32 length note hash
                if (!in_array($hash, $exclude_hashes)) {
                    $xml[] = array('author' => $n->author, 'title' => $n->title, 'msg' => $n->note, 'hash' => $hash, 'ts' => $n->timestamp, 'priority' => (int)$n->priority);
                    // store each hash to options table - checked against is stored already
                    $is_hash = serendipity_db_query("SELECT value FROM {$serendipity['dbPrefix']}options
                                                      WHERE name = 'sysinfo_ticker' AND value = '$hash' AND okey = 'l_sysinfo_{$comb}'", true); // is single
                    if (!is_array($is_hash)) {
                        // okey needs to be unique enough for Duplicate entry 'sysinfo_ticker-l_sysinfo_John Doe_1' for possible key 'PRIMARY' index key (also see above)
                        // but also must be short enough for the varchar(64) field length
                        try {
                            serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}options (name, value, okey) VALUES ('sysinfo_ticker', '{$hash}', 'l_sysinfo_{$comb}')");
                        } catch (\Throwable $t) {
                            trigger_error('Error: The \'sysinfo_ticker\' hashes could not be stored to DB (' . $t->getMessage() . '). Please examine the message.', E_USER_NOTICE);
                        }
                    }
                }
            }
            if (!empty($xml)) {
                // add fallback target
                if (is_string($xmlstr) && !empty($xmlstr)) {
                    if (! file_exists($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/sysnotes')) {
                        mkdir($serendipity['serendipityPath'] . PATH_SMARTY_COMPILE . '/sysnotes');
                    }
                    file_put_contents($target, $xmlstr, LOCK_EX);
                }
                return $xml; // escape is done in template
            }
        }
    }

    return false;
}

/**
 * Load available/configured options for a specific theme (through config.inc.php of a template directory)
 * into an array.
 *
 * Args:
 *      - Referenced variable coming from the config.inc.php file, where the config values will be stored in
 *      - The stored okey (option key) type
 *      - Use true boolean mode in array $template_config in the config.inc.php file
 * Returns:
 *      - Final return array with default values
 * @access private
 */
function &serendipity_loadThemeOptions(iterable &$template_config, string $okey = '', bool $bc_bool = false) : iterable {
    global $serendipity;

    if (empty($okey)) {
        $okey = $serendipity['template'];
    }

    $sql = "SELECT name, value FROM {$serendipity['dbPrefix']}options
             WHERE okey = 't_" . serendipity_db_escape_string($okey) . "'
                OR okey = 't_global'";

    $_template_vars =& serendipity_db_query($sql, false, 'assoc', false, 'name', 'value');
    if (!is_array($_template_vars)) {
        $template_vars = array();
    } else {
        $template_vars =& $_template_vars;
    }

    foreach($template_config AS $key => $item) {
        if (!isset($item['var'])) continue;
        if (!isset($template_vars[$item['var']])) {
            $template_vars[$item['var']] = $item['default'] ?? null;
        }
    }
    if ($bc_bool) {
        foreach($template_vars AS $k => $i) {
            if ($i == 'true' || $i == 'false') {
                $template_vars[$k] = serendipity_db_bool($i);
            }
        }
        //reset Smarty compiled template ?
    }

    return $template_vars;
}

/**
 * Load global available/configured options for a specific theme
 * into an array.
 *
 * Args:
 *      - Referenced variable coming from the config.inc.php file, where the config values will be stored in
 *      - Current template configuration
 *      -
 * Returns:
 *      - Final return array with default values
 * @access private
 */
function serendipity_loadGlobalThemeOptions(iterable &$template_config, iterable &$template_loaded_config, iterable $supported = []) : void {
    global $serendipity;

    if ($supported['navigation']) {
        $navlinks = array();

        $conf_amount = array(
                'var'           => 'amount',
                'name'          => NAVLINK_AMOUNT,
                'type'          => 'string',
                'default'       => '5',
                'scope'         => 'global'
        );

        // This always needs to be present, if not it could happen that the template options do have an older version of this variable
        $template_config[] = $conf_amount;

        if (!isset($template_loaded_config['amount']) || empty($template_loaded_config['amount'])) {
            $template_loaded_config['amount'] = $conf_amount['default'];
        }

        // Check if we are currently inside the admin interface.
        if (isset($serendipity['POST']['adminModule']) && $serendipity['POST']['adminModule'] == 'templates' && $serendipity['POST']['adminAction'] == 'configure' && !empty($serendipity['POST']['template']['amount'])) {
            $template_loaded_config['amount'] = (int)$serendipity['POST']['template']['amount'];
        }

        for ($i = 0; $i < $template_loaded_config['amount']; $i++) {
            $navlinks[] = array(
                'title' => ($template_loaded_config['navlink' . $i . 'text'] ?? null),
                'href'  => ($template_loaded_config['navlink' . $i . 'url']  ?? null)
            );

            $template_config[] = array(
                'var'           => 'navlink' . $i . 'text',
                'name'          => NAV_LINK_TEXT . ' #' . ($i+1),
                'type'          => 'string',
                'default'       => 'Link #' . ($i+1),
                'scope'         => 'global'
            );
            $template_config[] = array(
                'var'           => 'navlink' . $i . 'url',
                'name'          => NAV_LINK_URL . ' #' . ($i+1),
                'type'          => 'string',
                'default'       => '#',
                'scope'         => 'global'
            );
        }

        $serendipity['smarty']->assignByRef('navlinks', $navlinks);
    }

    // Forward thinking. ;-)
    serendipity_plugin_api::hook_event('backend_templates_globalthemeoptions', $template_config, $supported);
}

/**
 * Check if a member of a group has permissions to execute a plugin
 *
 * Args:
 *      - Pluginname
 *      - ID of the group of which the members should be checked OR NULL
 * Returns:
 *      - boolean
 * @access private
 */
function serendipity_hasPluginPermissions(string $plugin, ?int $groupid = null) : bool {
    global $serendipity;
    static $forbidden = null;

    if (empty($serendipity['authorid'])) {
        return true;
    }

    if ($forbidden === null || ($groupid !== null && !isset($forbidden[$groupid]))) {
        $forbidden = array();

        if ($groupid === null) {
            $groups = serendipity_checkPermission(returnMyGroups: 'all');
        } else {
            $groups = array($groupid => serendipity_fetchGroup($groupid));
        }

        foreach($groups AS $idx => $group) {
            if ($idx == 'membership') {
                continue;
            }
            foreach($group AS $key => $val) {
                if (str_starts_with($key, 'f_')) {
                    $forbidden[$groupid][$key] = true;
                }
            }
        }
    }

    if (isset($forbidden[$groupid]['f_' . $plugin])) {
        return false;
    } else {
        return true;
    }
}

/**
 * Return the BCRYPT of a value
 *
 * Args:
 *      - The string to hash
 * Returns:
 *      - The hashed string
 * @access private
 */
function serendipity_hash(#[\SensitiveParameter] string $string) : string {
    return password_hash($string, PASSWORD_BCRYPT); // we have a varchar(64) field here, thus we cannot use PASSWORD_DEFAULT
}

/**
 * Return the SHA1 (with pre-hash) of a value
 *
 * Args:
 *      - The string to hash
 * Returns:
 *      - The hashed string
 * @access private
 * @param string
 */
function serendipity_sha1_hash(string $string) : string {
    global $serendipity;

    if (empty($serendipity['hashkey'])) {
        serendipity_set_config_var('hashkey', time(), 0);
    }

    return sha1($serendipity['hashkey'] . $string);
}

/**
 * Backwards-compatibility to recognize old-style MD5 passwords to allow migration
 *
 * Args:
 *      - The string to hash
 * Returns:
 *      - Either SHA1 or MD5 hash, depending on value
 * @access private
 */
function serendipity_passwordhash(#[\SensitiveParameter] string $cleartext_password) : string {
    global $serendipity;

    if ($_SESSION['serendipityHashType'] > 0) {
        return serendipity_hash($cleartext_password);
    } else {
        return md5($cleartext_password);
    }
}

/* vim: set sts=4 ts=4 expandtab : */
