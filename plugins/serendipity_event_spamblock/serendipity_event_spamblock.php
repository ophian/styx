<?php

if (IN_serendipity !== true) {
    die ("Don't hack!");
}

@serendipity_plugin_api::load_language(dirname(__FILE__));

class serendipity_event_spamblock extends serendipity_event
{
    var $filter_defaults;

    function introspect(&$propbag)
    {
        global $serendipity;

        $this->title = PLUGIN_EVENT_SPAMBLOCK_TITLE;

        $propbag->add('name',          PLUGIN_EVENT_SPAMBLOCK_TITLE);
        $propbag->add('description',   PLUGIN_EVENT_SPAMBLOCK_DESC);
        $propbag->add('stackable',     false);
        $propbag->add('author',        'Garvin Hicking, Sebastian Nohn, Grischa Brockhaus, Ian Styx');
        $propbag->add('requirements',  array(
            'serendipity' => '2.3.1',
            'smarty'      => '3.1.0',
            'php'         => '7.0.0'
        ));
        $propbag->add('version',       '2.54');
        $propbag->add('event_hooks',    array(
            'frontend_saveComment' => true,
            'external_plugin'      => true,
            'frontend_comment'     => true,
            'fetchcomments'        => true,
            'css_backend'          => true,
            'backend_maintenance'  => true,
            'backend_comments_top' => true,
            'backend_view_comment' => true,
            'frontend_display:html:per_entry'  => true/*,
            'backend_sidebar_admin' => true,*/
        ));
        $propbag->add('configuration', array(
            'config_mainconfiggrouper',
            'killswitch',
            'hide_for_authors',
            'bodyclone',
            'entrytitle',
            'ipflood',
            'csrf',
            'captchas',
            'captchas_ttl',
            'captcha_color',
            'forceopentopublic',
            'forceopentopublic_treat',
            'forcemoderation',
            'forcemoderation_treat',
            'trackback_ipvalidation',
            'trackback_ipvalidation_url_exclude',
            'forcemoderationt',
            'forcemoderationt_treat',
            'disable_api_comments',
            'trackback_check_url',
            'links_moderate',
            'links_reject',
            'contentfilter_activate',
            'contentfilter_urls',
            'contentfilter_authors',
            'contentfilter_words',
            'contentfilter_emails',
            'akismet',
            'akismet_server',
            'akismet_filter',
            'hide_email',
            'checkmail',
            'required_fields',
            'automagic_htaccess',
            'logtype',
            'logfile'));
        $propbag->add('groups', array('ANTISPAM', 'BACKEND_ADMIN', 'BACKEND_FEATURES', 'BACKEND_MAINTAIN', 'MAINTENANCE'));
        $propbag->add('config_groups', array(
                'Content Filter' => array(
                    'contentfilter_activate',
                    'contentfilter_urls',
                    'contentfilter_authors',
                    'contentfilter_words',
                    'contentfilter_emails',
                    'akismet',
                    'akismet_server',
                    'akismet_filter',
                ),
                'Trackbacks' => array(
                    'trackback_ipvalidation',
                    'trackback_ipvalidation_url_exclude',
                    'forcemoderationt',
                    'forcemoderationt_treat',
                    'trackback_check_url',
                )
        ));
        $this->filter_defaults = array(
                                'authors' => 'casino;phentermine;credit;loans;poker',
                                'emails'  => '',
                                'urls'    => '8gold\.com;911easymoney\.com;canadianlabels\.net;condodream\.com;crepesuzette\.com;debt-help-bill-consolidation-elimination\.com;fidelityfunding\.net;flafeber\.com;gb\.com;houseofsevengables\.com;instant-quick-money-cash-advance-personal-loans-until-pay-day\.com;mediavisor\.com;newtruths\.com;oiline\.com;onlinegamingassociation\.com;online\-+poker\.com;popwow\.com;royalmailhotel\.com;spoodles\.com;sportsparent\.com;stmaryonline\.org;thatwhichis\.com;tmsathai\.org;uaeecommerce\.com;learnhowtoplay\.com',
                                'words'   => 'very good site!;Real good stuff!'
        );
        $propbag->add('legal',    array(
            'services' => array(
                'akismet' => array(
                    'url'  => 'https://www.akismet.com',
                    'desc' => 'Transmits comment data (and metadata) to check whether it is spam: User-Agent, HTTP Referer, IP [can be anonymized], Author name [can be anonymized], Author mail [can be anonymized], Author URL [can be anonymized], comment body'
                ),
                'tpas' => array(
                    'url'  => 'http://api.antispam.typepad.com/',
                    'desc' => 'Transmits comment data (and metadata) to check whether it is spam: User-Agent, HTTP Referer, IP [can be anonymized], Author name [can be anonymized], Author mail [can be anonymized], Author URL [can be anonymized], comment body'
                )
            ),
            'frontend' => array(
                'To check a comment for spam, the Akismet/Typepad service can be enabled and receives comment data of the user and its metadata: User-Agent, HTTP Referer, IP [can be anonymized], Author name [can be anonymized], Author mail [can be anonymized], Author URL [can be anonymized], comment body.',
                'Submitted and also rejected comments can be saved to a logfile.',
                'When Captchas are enabled, the displayed graphic key is stored in the session data and uses a PHP session cookie.'
            ),
            'backend' => array(
                'To report a comment for spam, the Akismet/Typepad service can be enabled and receives comment data of the user and its metadata: User-Agent, HTTP Referer, IP [can be anonymized], Author name [can be anonymized], Author mail [can be anonymized], Author URL [can be anonymized], comment body.',
            ),
            'cookies' => array(
                'When Captchas are enabled, the displayed graphic key is stored in the session data and uses a PHP session cookie.'
            ),
            'stores_user_input'     => true,
            'stores_ip'             => true,
            'uses_ip'               => true,
            'transmits_user_input'  => true
        ));
    }

    function introspect_config_item($name, &$propbag)
    {
        global $serendipity;

        switch($name) {

            case 'disable_api_comments':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_API_COMMENTS_DESC);
                $propbag->add('default', 'none');
                $propbag->add('radio', array(
                    'value' => array('moderate', 'reject', 'none'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT, NONE)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'trackback_ipvalidation':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_DESC);
                $propbag->add('default', 'moderate');
                $propbag->add('radio', array(
                    'value' => array('no', 'moderate', 'reject'),
                    'desc'  => array(NO, PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'trackback_ipvalidation_url_exclude':
                $propbag->add('type', 'text');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKIPVALIDATION_URL_EXCLUDE_DESC);
                $propbag->add('rows', 2);
                $propbag->add('default', $this->get_default_exclude_urls());
                break;

            case 'trackback_check_url':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_TRACKBACKURL_DESC);
                $propbag->add('default', 'false');
                break;

            case 'automagic_htaccess':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_HTACCESS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_HTACCESS_DESC);
                $propbag->add('default', 'false');
                break;

            case 'hide_email':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_DESC);
                $propbag->add('default', 'true');
                break;

            case 'csrf':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_CSRF);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_CSRF_DESC);
                $propbag->add('default', 'true');
                break;

            case 'entrytitle':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_TITLE_DESC);
                $propbag->add('default', 'true');
                break;

            case 'checkmail':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_DESC);
                $propbag->add('default', 'false');
                $propbag->add('radio', array(
                    'value' => array('false', 'true', 'verify_once', 'verify_always'),
                    'desc'  => array(NO, YES, PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ONCE, PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_ALWAYS)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'required_fields':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_REQUIRED_FIELDS_DESC);
                $propbag->add('default', 'name,comment');
                break;

            case 'bodyclone':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_BODYCLONE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_BODYCLONE_DESC);
                $propbag->add('default', 'true');
                break;

            case 'captchas':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_DESC);
                $propbag->add('default', 'yes');
                $propbag->add('radio', array(
                    'value' => array(true, 'no', 'scramble'),
                    'desc'  => array(YES, NO, PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_SCRAMBLE)
                ));
                break;

            case 'hide_for_authors':
                $_groups =& serendipity_getAllGroups();
                $groups = array(
                    'all'  => ALL_AUTHORS,
                    'none' => NONE
                );

                foreach($_groups AS $group) {
                    $groups[$group['confkey']] = $group['confvalue'];
                }

                $propbag->add('type', 'multiselect');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_HIDE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_HIDE_DESC);
                $propbag->add('select_values', $groups);
                $propbag->add('select_size',   5);
                $propbag->add('default', 'all');
                break;

            case 'config_mainconfiggrouper':
                $propbag->add('type',    'content');
                $propbag->add('name',    'Configuration Preferences');
                $propbag->add('default', '<h3>' . PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION . '</h3><em>'.PLUGIN_EVENT_SPAMBLOCK_MAIN_CONFIGURATION_DESC.'</em>');
                break;

            case 'killswitch':
                $propbag->add('type', 'boolean');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_KILLSWITCH_DESC);
                $propbag->add('default', 'false');
                break;

            case 'contentfilter_activate':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_ACTIVATE_DESC);
                $propbag->add('default', 'moderate');
                $propbag->add('radio', array(
                    'value' => array('moderate', 'reject', 'none'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT, NONE)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'akismet':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_AKISMET);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_AKISMET_DESC);
                $propbag->add('default', '');
                break;

            case 'akismet_server':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_AKISMET_SERVER_DESC);
                // If the user has an API key, but hasn't set a server, he
                // must be using an older version of the plugin; default
                // to akismet.  Otherwise, encourage adoption of the Open
                // Source alternative, TypePad Antispam.
                $curr_key = $this->get_config('akismet', false);
                $propbag->add('default', (empty($curr_key) ? 'akismet' : 'tpas'));
                $propbag->add('radio', array(
                    'value' => array('tpas', 'akismet', 'anon-tpas', 'anon-akismet'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS, PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET,
                                     PLUGIN_EVENT_SPAMBLOCK_SERVER_TPAS_ANON, PLUGIN_EVENT_SPAMBLOCK_SERVER_AKISMET_ANON
                    )
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'akismet_filter':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_AKISMET_FILTER);
                $propbag->add('description', '');
                $propbag->add('default', 'reject');
                $propbag->add('radio', array(
                    'value' => array('moderate', 'reject', 'none'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT, NONE)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'contentfilter_urls':
                $propbag->add('type', 'text');
                $propbag->add('rows', 3);
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS_DESC);
                $propbag->add('default', $this->filter_defaults['urls']);
                break;

            case 'contentfilter_authors':
                $propbag->add('type', 'text');
                $propbag->add('rows', 3);
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC);
                $propbag->add('default', $this->filter_defaults['authors']);
                break;

            case 'contentfilter_words':
                $propbag->add('type', 'text');
                $propbag->add('rows', 3);
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC);
                $propbag->add('default', $this->filter_defaults['words']);
                break;

            case 'contentfilter_emails':
                $propbag->add('type', 'text');
                $propbag->add('rows', 3);
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS_DESC);
                $propbag->add('default', $this->filter_defaults['emails']);
                break;

            case 'logfile':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_LOGFILE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_LOGFILE_DESC);
                $propbag->add('default', $serendipity['serendipityPath'] . 'spamblock-%Y-%m-%d.log');
                $propbag->add('validate', '@\.(log|txt)$@imsU');
                $propbag->add('validate_error', PLUGIN_EVENT_SPAMBLOCK_LOGFILE_VALIDATE);
                break;

            case 'logtype':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_LOGTYPE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DESC);
                $propbag->add('default', 'none');
                $propbag->add('radio',         array(
                    'value' => array('file', 'db', 'none'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_FILE, PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_DB, PLUGIN_EVENT_SPAMBLOCK_LOGTYPE_NONE)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'ipflood':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_IPFLOOD);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_IPFLOOD_DESC);
                $propbag->add('default', 0);
                break;

            case 'captchas_ttl':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_TTL_DESC);
                $propbag->add('default', '7');
                break;

            case 'captcha_color':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_CAPTCHA_COLOR_DESC);
                $propbag->add('default', '255,255,255');
                $propbag->add('validate', '@^[0-9]{1,3},[0-9]{1,3},[0-9]{1,3}$@');
                break;

            case 'forceopentopublic':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_DESC);
                $propbag->add('default', 0);
                break;

            case 'forceopentopublic_treat':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FORCEOPENTOPUBLIC_TREAT_DESC);
                $propbag->add('default', 'no');
                $propbag->add('radio', array(
                    'value' => array('yes', 'no'),
                    'desc'  => array(YES, NO . ' (default)')
                ));
                break;

            case 'forcemoderation':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_DESC);
                $propbag->add('default', '30');
                break;

            case 'forcemoderation_treat':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATION_TREAT);
                $propbag->add('description', '');
                $propbag->add('default', 'moderate');
                $propbag->add('radio', array(
                    'value' => array('moderate', 'reject'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'forcemoderationt':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_DESC);
                $propbag->add('default', '30');
                break;

            case 'forcemoderationt_treat':
                $propbag->add('type', 'radio');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_FORCEMODERATIONT_TREAT_DESC);
                $propbag->add('default', 'moderate');
                $propbag->add('radio', array(
                    'value' => array('moderate', 'reject'),
                    'desc'  => array(PLUGIN_EVENT_SPAMBLOCK_API_MODERATE, PLUGIN_EVENT_SPAMBLOCK_API_REJECT)
                ));
                $propbag->add('radio_per_row', '1');
                break;

            case 'links_moderate':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_LINKS_MODERATE_DESC);
                $propbag->add('default', '7');
                break;

            case 'links_reject':
                $propbag->add('type', 'string');
                $propbag->add('name', PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT);
                $propbag->add('description', PLUGIN_EVENT_SPAMBLOCK_LINKS_REJECT_DESC);
                $propbag->add('default', '13');
                break;

            default:
                return false;
        }
        return true;
    }

    function get_default_exclude_urls()
    {
        return '^https?://identi\.ca/notice/\d+$';
    }

    function htaccess_update($new_ip)
    {
        global $serendipity;

        serendipity_db_query("INSERT INTO {$serendipity['dbPrefix']}spamblock_htaccess (ip, timestamp) VALUES ('" . serendipity_db_escape_string($new_ip) . "', '" . time() . "')");

        // Limit number of banned IPs to prevent .htaccess growing too large. The query selects at max 20*$blocklist_chunksize entries from the last two days.
        $blocklist_chunksize = 177;
        $q = "SELECT ip FROM {$serendipity['dbPrefix']}spamblock_htaccess WHERE timestamp > " . (time() - 86400*2) . " GROUP BY ip ORDER BY timestamp, ip DESC LIMIT " . 20*$blocklist_chunksize;
        $rows = serendipity_db_query($q, false, 'assoc');

        $deny = array();
        if (is_array($rows)) {
            foreach($rows AS $row) {
                $deny[] = $row['ip'];
            }
        }

        $hta = $serendipity['serendipityPath'] . '.htaccess';
        $blocklist_size = count($deny);
        if ($blocklist_size > 0 && file_exists($hta) && is_writable($hta)) {
            $blocklist = "#IP count: " . $blocklist_size . ", last update: " . date('Y-m-d H:i:s') . "\n";
            for ($i = 0; $i < ((int) (($blocklist_size-1) / $blocklist_chunksize))+1; $i++) {
                $blocklist = $blocklist . "Deny From " . implode(" ", array_slice($deny, $i*$blocklist_chunksize, $blocklist_chunksize)) . "\n";
            }
            $fp = @fopen($hta, 'r+');
            if (!$fp) {
                return false;
            }
            if (flock($fp, LOCK_EX|LOCK_NB)) {
                $htaccess = file_get_contents($hta);
                if (!$htaccess) {
                    fclose($fp);  // also releases the lock
                    return false;
                }
                // Check if an old htaccess file existed and try to preserve its contents. Otherwise completely wipe the file.
                if ($htaccess != '' && preg_match('@^(.*)#SPAMDENY.*Deny From.+#/SPAMDENY(.*)$@imsU', $htaccess, $match)) {
                    // Code outside from s9y-code was found.
                    $content = trim($match[1]) . "\n#SPAMDENY\n" . $blocklist . "#/SPAMDENY\n" . trim($match[2]);
                } else {
                    $content = trim($htaccess) . "\n#SPAMDENY\n" . $blocklist . "#/SPAMDENY\n";
                }
                ftruncate($fp, 0);
                fwrite($fp, $content);
                fclose($fp);
                return true;
            } else {
                fclose($fp);
                return false;
            }
        }
        return false;
    }

    function akismetRequest($api_key, $data, &$ret, $action = 'comment-check', $eventData = null, $addData = null)
    {
        global $serendipity;

        $options = array(
            'timeout'           => 20,
            'follow_redirects'  => true,
            'max_redirects'     => 3,
        );

        // Default server type to akismet, in case user has an older version of the plugin
        // where no server was set
        $server_type = $this->get_config('akismet_server', 'akismet');
        $server = '';
        $anon = false;

        switch ($server_type) {
            case 'anon-tpas':
                $anon = true;
            case 'tpas':
                $server = 'api.antispam.typepad.com';
                break;

            case 'anon-akismet':
                $anon = true;
            case 'akismet':
                $server = 'rest.akismet.com';
                break;
        }

        if ($anon) {
            $data['comment_author'] = 'John Doe';
            $data['comment_author_email'] = '';
            $data['comment_author_url'] = '';
        }

        if (empty($server)) {
            $this->log($this->logfile, (is_null($eventData) ? 0 : $eventData['id']), 'AKISMET_SERVER', 'No Akismet server found', $addData);
            $ret['is_spam'] = false;
            $ret['message'] = 'No server for Akismet request';
            return;
        } else {
            // DEBUG
            //$this->log($this->logfile, $eventData['id'], 'AKISMET_SERVER', 'Using Akismet server at ' . $server, $addData);
        }
        $req = serendipity_request_object('http://' . $server . '/1.1/verify-key', 'post', $options);

        $req->addPostParameter('key',  $api_key);
        $req->addPostParameter('blog', $serendipity['baseURL']);

        try {
            $response = $req->send();
            if ($response->getStatus() != '200') {
                throw new HTTP_Request2_Exception('Statuscode not 200, Akismet HTTP verification request failed.');
            }
            $reqdata = $response->getBody();
        } catch (HTTP_Request2_Exception $e) {
            $ret['is_spam'] = false;
            $ret['message'] = 'API Verification Request failed';
            $this->log($this->logfile, $eventData['id'], 'API_ERROR', 'Akismet HTTP verification request failed.', $addData);
            return;
        }

        if (!preg_match('@valid@i', $reqdata)) {
            $ret['is_spam'] = false;
            $ret['message'] = 'API Verification failed';
            $this->log($this->logfile, $eventData['id'], 'API_ERROR', 'Akismet API verification failed: ' . $reqdata, $addData);
            return;
        }

        $req = serendipity_request_object('http://' . $api_key . '.' . $server . '/1.1/' . $action, 'post', $options);

        $req->addPostParameter('blog', $serendipity['baseURL']);

        foreach($data AS $key => $value) {
            $req->addPostParameter($key, $value);
        }

        try {
            $response = $req->send();
            if ($response->getStatus() != '200') {
                throw new HTTP_Request2_Exception('Statuscode not 200, Akismet HTTP request failed.');
            }
            $reqdata = $response->getBody();
        } catch (HTTP_Request2_Exception $e) {
            $ret['is_spam'] = false;
            $ret['message'] = 'Akismet Request failed';
            $this->log($this->logfile, $eventData['id'], 'API_ERROR', 'Akismet HTTP request failed.', $addData);
            return;
        }

        if ($action == 'comment-check' && preg_match('@true@i', $reqdata)) {
            $ret['is_spam'] = true;
            $ret['message'] = $reqdata;
            // DEBUG
            //$this->log($this->logfile, $eventData['id'], 'AKISMET_SPAM', 'Akismet API returned spam', $addData);
        } elseif ($action == 'comment-check' && preg_match('@false@i', $reqdata)) {
            $ret['is_spam'] = false;
            $ret['message'] = $reqdata;
            // DEBUG
            //$this->log($this->logfile, $eventData['id'], 'AKISMET_PASS', 'Passed Akismet verification', $addData);
        } elseif ($action != 'comment-check' && preg_match('@received@i', $reqdata)) {
            $ret['is_spam'] = ($action == 'submit-spam');
            $ret['message'] = $reqdata;
            $this->log($this->logfile, $eventData['id'], 'API_ERROR', 'Akismet API failure: ' . $reqdata, $addData);
        } else {
            $ret['is_spam'] = false;
            $ret['message'] = 'Akismet API failure';
            $this->log($this->logfile, $eventData['id'], 'API_ERROR', 'Akismet API failure: ' . $reqdata, $addData);
        }
    }

    function tellAboutComment($where, $api_key, $comment_id, $is_spam)
    {
        global $serendipity;

        $comment = serendipity_db_query(" SELECT C.*, L.useragent AS log_useragent, E.title AS entry_title "
                                      . " FROM {$serendipity['dbPrefix']}comments C, {$serendipity['dbPrefix']}spamblocklog L , {$serendipity['dbPrefix']}entries E "
                                      . " WHERE C.id = '" . (int)$comment_id . "' AND C.entry_id=L.entry_id AND C.entry_id=E.id "
                                      . " AND C.author=L.author AND C.url=L.url AND C.referer=L.referer "
                                      . " AND C.ip=L.ip AND C.body=L.body", true, 'assoc');
        if (!is_array($comment)) return;

        serendipity_request_start();

        switch($where) {
            case 'akismet.com':
                // DEBUG
                //$this->log($this->logfile, $eventData['id'], 'AKISMET_SAFETY', 'Akismet verification takes place', $addData);
                $ret  = array();
                $data = array(
                  'blog'                    => $serendipity['baseURL'],
                  'user_agent'              => $comment['log_useragent'],
                  'referrer'                => $comment['referer'],
                  'user_ip'                 => $comment['ip'],
                  'permalink'               => serendipity_archiveURL($comment['entry_id'], $comment['entry_title'], 'serendipityHTTPPath', true, array('timestamp' => $comment['timestamp'])),
                  'comment_type'            => ($comment['type'] == 'NORMAL' ? 'comment' : strtolower($comment['type'])), // second: pingback or trackback.
                  'comment_author'          => $comment['author'],
                  'comment_author_email'    => $comment['email'],
                  'comment_author_url'      => $comment['url'],
                  'comment_content'         => $comment['body']
                );

                $this->akismetRequest($api_key, $data, $ret, ($is_spam ? 'submit-spam' : 'submit-ham'));

                break;
        }

        serendipity_request_end();
    }

    function &getBlacklist($where, $api_key, &$eventData, &$addData)
    {
        global $serendipity;

        $ret = false;

        serendipity_request_start();

        // this switch statement is a leftover from blogg.de support (i.e. there were more options than just one). Leaving it in place in case we get more options again in the future.
        switch($where) {
            case 'akismet.com':
                // DEBUG
                //$this->log($this->logfile, $eventData['id'], 'AKISMET_SAFETY', 'Akismet verification takes place', $addData);
                $ret  = array();
                $data = array(
                    'blog'                    => $serendipity['baseURL'],
                    'user_agent'              => $_SERVER['HTTP_USER_AGENT'],
                    'referrer'                => $_SERVER['HTTP_REFERER'],
                    'user_ip'                 => $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR') ? $_SERVER['REMOTE_ADDR'] : getenv('HTTP_X_FORWARDED_FOR'),
                    'permalink'               => serendipity_archiveURL($eventData['id'], $eventData['title'], 'serendipityHTTPPath', true, array('timestamp' => $eventData['timestamp'])),
                    'comment_type'            => ($addData['type'] == 'NORMAL' ? 'comment' : strtolower($addData['type'])), // second: pingback or trackback.
                    'comment_author'          => $addData['name'],
                    'comment_author_email'    => $addData['email'],
                    'comment_author_url'      => $addData['url'],
                    'comment_content'         => $addData['comment']
                );

                $this->akismetRequest($api_key, $data, $ret);
                break;

            default:
                break;
        }

        serendipity_request_end();

        return $ret;
    }

    function checkScheme()
    {
        global $serendipity;

        $dbversion = $this->get_config('dbversion', '1');

        if ($dbversion == '1') {
            $q   = "CREATE TABLE {$serendipity['dbPrefix']}spamblocklog (
                        timestamp int(10) {UNSIGNED} default null,
                        type varchar(255),
                        reason text,
                        entry_id int(10) {UNSIGNED} not null default '0',
                        author varchar(80),
                        email varchar(200),
                        url varchar(200),
                        useragent varchar(255),
                        ip varchar(45),
                        referer varchar(255),
                        body text) {UTF_8}";
            $sql = serendipity_db_schema_import($q);

            $q   = "CREATE INDEX kspamidx ON {$serendipity['dbPrefix']}spamblocklog (timestamp);";
            $sql = serendipity_db_schema_import($q);

            if ($serendipity['dbType'] == 'mysqli') {
                $serendipity['db_server_info'] = $serendipity['db_server_info'] ?? mysqli_get_server_info($serendipity['dbConn']); // eg.  == 5.5.5-10.4.11-MariaDB
                // be a little paranoid...
                if (str_starts_with($serendipity['db_server_info'], '5.5.5-')) {
                    // strip any possible added prefix having this 5.5.5 version string (which was never released). PHP up from 8.0.16 now strips it correctly.
                    $serendipity['db_server_info'] = str_replace('5.5.5-', '', $serendipity['db_server_info']);
                }
                $db_version_match = explode('-', $serendipity['db_server_info']);
                if (stristr(strtolower($serendipity['db_server_info']), 'mariadb')) {
                    if (version_compare($db_version_match[0], '10.5.0', '>=')) {
                        $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type);";
                    } elseif (version_compare($db_version_match[0], '10.3.0', '>=')) {
                        $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type(250));"; // max key 1000 bytes
                    } else {
                        $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type(191));"; // 191 - old MyISAMs
                    }
                } else {
                    // Oracle MySQL - https://dev.mysql.com/doc/refman/5.7/en/innodb-limits.html
                    if (version_compare($db_version_match[0], '5.7.7', '>=')) {
                        $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type);"; // Oracle Mysql/InnoDB max key up to 3072 bytes
                    } else {
                        $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type(191));"; // Oracle Mysql/InnoDB max key 767 bytes
                    }
                }
            } else {
                $q = "CREATE INDEX kspamtypeidx ON {$serendipity['dbPrefix']}spamblocklog (type);";
            }
            $sql = serendipity_db_schema_import($q);

            $q   = "CREATE INDEX kspamentryidx ON {$serendipity['dbPrefix']}spamblocklog (entry_id);";
            $sql = serendipity_db_schema_import($q);

            $q   = "CREATE TABLE {$serendipity['dbPrefix']}spamblock_htaccess (
                        timestamp int(10) {UNSIGNED} default null,
                        ip varchar(15))";
            $sql = serendipity_db_schema_import($q);

            $q   = "CREATE INDEX kshtaidx ON {$serendipity['dbPrefix']}spamblock_htaccess (timestamp);";
            $sql = serendipity_db_schema_import($q);

            $this->set_config('dbversion', '3');
        }

        if ($dbversion == '2') {
            if (preg_match('@(postgres|pgsql)@i', $serendipity['dbType'])) {
                $q = "ALTER TABLE {$serendipity['dbPrefix']}spamblocklog ALTER COLUMN ip TYPE VARCHAR(45)";
                $sql = serendipity_db_schema_import($q);

                $q = "ALTER TABLE {$serendipity['dbPrefix']}spamblock_htaccess ALTER COLUMN ip TYPE VARCHAR(45)";
                $sql = serendipity_db_schema_import($q);
            } else {
                $q = "ALTER TABLE {$serendipity['dbPrefix']}spamblocklog CHANGE COLUMN ip ip VARCHAR(45)";
                $sql = serendipity_db_schema_import($q);

                $q = "ALTER TABLE {$serendipity['dbPrefix']}spamblock_htaccess CHANGE COLUMN ip ip VARCHAR(45)";
                $sql = serendipity_db_schema_import($q);
            }

            $this->set_config('dbversion', '3');
        }

        return true;
    }

    function generate_content(&$title)
    {
        $title = $this->title;
    }

    // This method will be called on "fatal" spam errors that are unlikely to occur accidentally by users.
    // Their IPs will be constantly blocked.
    function IsHardcoreSpammer()
    {
        global $serendipity;

        if (serendipity_db_bool($this->get_config('automagic_htaccess', 'false'))) {
            $this->htaccess_update($_SERVER['REMOTE_ADDR']);
        }
    }

    // Checks whether the current author is contained in one of the groups that need no spam checking
    function inGroup()
    {
        global $serendipity;

        $checkgroups = explode('^', $this->get_config('hide_for_authors', 'all'));

        if (!isset($serendipity['authorid']) || !is_array($checkgroups)) {
            return false;
        }

        $mygroups =& serendipity_getGroups($serendipity['authorid'], true);
        if (!is_array($mygroups)) {
            return false;
        }

        foreach($checkgroups AS $key => $groupid) {
            if ($groupid == 'all') {
                return true;
            } elseif (in_array($groupid, $mygroups)) {
                return true;
            }
        }

        return false;
    }

    function example()
    {
        return '<p id="captchabox" class="msg_hint">' . PLUGIN_EVENT_SPAMBLOCK_LOOK . $this->show_captcha() . '</p>';
    }

    function show_captcha($use_gd = false)
    {
        global $serendipity;

        if ($use_gd || (function_exists('imagettftext') && function_exists('imagejpeg'))) {
            $max_char = 5;
            $min_char = 3;
            $use_gd   = true;
        } else {
            $max_char = $min_char = 5;
            $use_gd   = false;
        }

        if ($use_gd) {
            return sprintf('<img src="%s" onclick="this.src=this.src + \'1\'" title="%s" alt="CAPTCHA" class="captcha" />',
                $serendipity['baseURL'] . ($serendipity['rewrite'] == 'none' ? $serendipity['indexFile'] . '?/' : '') . 'plugin/captcha_' . md5(time()),
                serendipity_specialchars(PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2)
            );
        } else {
            $bgcolors = explode(',', $this->get_config('captcha_color', '255,0,255'));
            $hexval   = '#' . dechex(trim($bgcolors[0])) . dechex(trim($bgcolors[1])) . dechex(trim($bgcolors[2]));
            $this->random_string($max_char, $min_char);
            $output = '<div class="serendipity_comment_captcha_image" style="background-color: ' . $hexval . '">';
            for ($i = 1; $i <= $max_char; $i++) {
                $output .= sprintf('<img src="%s" title="%s" alt="CAPTCHA ' . $i . '" class="captcha" />',
                    $serendipity['baseURL'] . ($serendipity['rewrite'] == 'none' ? $serendipity['indexFile'] . '?/' : '') . 'plugin/captcha_' . $i . '_' . md5(time()),
                    serendipity_specialchars(PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC2)
                );
            }
            $output .= '</div>';
            return $output;
        }
    }

    function event_hook($event, &$bag, &$eventData, $addData = null)
    {
        global $serendipity;
        $debug = true;

        $hooks = &$bag->get('event_hooks');

        if (isset($hooks[$event])) {

            $captchas_ttl = $this->get_config('captchas_ttl', 7);
            $_captchas    = $this->get_config('captchas', 'yes');
            $captchas     = ($_captchas !== 'no' && ($_captchas === 'yes' || $_captchas === 'scramble' || serendipity_db_bool($_captchas)));

            // Check if the entry is older than the allowed amount of time. Enforce Captchas if that is true
            // or if Captchas are activated for every entry
            $show_captcha = ($captchas && isset($eventData['timestamp']) && ($captchas_ttl < 1 || ($eventData['timestamp'] < (time() - ($captchas_ttl*60*60*24)))) ? true : false);

            // Plugins can override with custom captchas
            if (isset($serendipity['plugins']['disable_internal_captcha'])) {
                $show_captcha = false;
            }

            $forceopentopublic = $this->get_config('forceopentopublic', 0);
            $forcemoderation = $this->get_config('forcemoderation', 30);
            $forcemoderation_treat = $this->get_config('forcemoderation_treat', 'moderate');
            $forcemoderationt = $this->get_config('forcemoderationt', 30);
            $forcemoderationt_treat = $this->get_config('forcemoderationt_treat', 'moderate');

            $links_moderate  = $this->get_config('links_moderate', 10);
            $links_reject    = $this->get_config('links_reject', 20);

            if (function_exists('imagettftext') && function_exists('imagejpeg')) {
                $max_char = 5;
                $min_char = 3;
                $use_gd   = true;
            } else {
                $max_char = $min_char = 5;
                $use_gd   = false;
            }

            $logmethod = $this->get_config('logtype', 'none');

            switch($event) {

                case 'frontend_display:html:per_entry':
                    // we have $eventData['id'] on 'entries' page too, so better ensure this to run once on 'entry' only by GET OR uniquely for commentpopup
                    if ((isset($eventData['id']) && isset($serendipity['GET']['id'])) || (!empty($serendipity['GET']['entry_id']) && $serendipity['GET']['type'] == 'comments')) {
                        // globally assign to theme COMMENT forms
                        $required_fieldstr = $this->get_config('required_fields', 'name,comment');
                        if (!empty($required_fieldstr)) {
                            $required_fields = explode(',', $required_fieldstr);
                            $smarty_required_fields = array();

                            foreach($required_fields AS $required_field) {
                                $required_field = trim($required_field);

                                if (empty($required_field)) continue;
                                $smarty_required_fields[$required_field] = $required_field;
                            }

                            if (is_array($eventData)) {
                                // the proper way
                                $eventData['required_fields'] = $smarty_required_fields; // push into entry array and assign latterly via comment_add_data
                            } else {
                                // An easy out-of-scope workaround ( nobody should really need to use this popup option any more, (and) some themes don't even support it; Standard does, out of compat! )
                                $serendipity['smarty']->assign('required_fields', $smarty_required_fields); // or directly here for commentpopup, since $eventData is a scalar value here!
                            }
                        }

                        if ($forceopentopublic > 0) {
                            $serendipity['commentaire']['opentopublic'] = ($forceopentopublic * 86400);
                        }
                    }
                    break;

                case 'fetchcomments':
                    // Check for global emergency moderation
                    if (!serendipity_checkPermission('adminTemplates') && serendipity_db_bool($this->get_config('killswitch', 'false')) === true) {
                        $serendipity['commentaire']['killswitch'] = true;
                        return false;
                    }
                    if (is_array($eventData) && !$_SESSION['serendipityAuthedUser'] && serendipity_db_bool($this->get_config('hide_email', 'true'))) {
                        // Will force emails to be not displayed in comments and RSS feed for comments. Will not apply to logged in admins (so not in the backend as well)
                        foreach($eventData AS $idx => $comment) {
                            $eventData[$idx]['no_email'] = true;
                        }
                    }
                    break;

                case 'frontend_saveComment':
                /*
                    $fp = fopen('/tmp/spamblock2.log', 'a');
                    fwrite($fp, date('Y-m-d H:i') . "\n" . print_r($eventData, true) . "\n" . print_r($addData, true) . "\n");
                    fclose($fp);
                */

                    $_disallow_trackbacks_passthrough = false;
                    if ($addData['type'] != 'NORMAL' && $forceopentopublic > 0 && $this->get_config('forceopentopublic_treat', 'no') == 'yes' && $eventData['timestamp'] < (time() - ($forceopentopublic * 60 * 60 * 24))) {
                        $_disallow_trackbacks_passthrough = true;
                    }

                    if (!is_array($eventData) || serendipity_db_bool($eventData['allow_comments'])) {
                        $this->checkScheme();

                        $serendipity['csuccess']  = 'true';
                        $logfile = $this->logfile = $this->get_config('logfile', $serendipity['serendipityPath'] . 'spamblock.log');
                        $required_fields          = $this->get_config('required_fields', 'name,comment');
                        $checkmail                = $this->get_config('checkmail', 'false'); // string

                        // Check CSRF [comments only, cannot be applied to trackbacks]
                        if ($addData['type'] == 'NORMAL' && serendipity_db_bool($this->get_config('csrf', 'true'))) {
                            if (!serendipity_checkFormToken(false)) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_CSRF_REASON;
                            }
                        }

                        // Check required fields
                        if ($addData['type'] == 'NORMAL' && !empty($required_fields)) {
                            $required_field_list = explode(',', $required_fields);
                            foreach($required_field_list AS $required_field) {
                                $required_field = trim($required_field);
                                if (empty($addData[$required_field])) {
                                    $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD, $addData);
                                    $eventData = array('allow_comments' => false);
                                    $serendipity['messagestack']['comments'][] = sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_REQUIRED_FIELD, $required_field);
                                    return false;
                                }
                            }
                        }

                        /*
                        if ($addData['type'] != 'NORMAL' && empty($addData['name'])) {
                            $eventData = array('allow_coments' => false);
                            $this->log($logfile, $eventData['id'], 'INVALIDGARV', 'INVALIDGARV', $addData);
                            return false;
                        }
                        */

                        // Check whether to allow comments from registered authors - sadly this COMMENTS only.
                        // Since track-/pingbacks are API comments this never works! The API is not a valid permissive author and thus cannot be checked by userLoggedIn() etc !
                        if (serendipity_userLoggedIn() && $this->inGroup()) {
                            return true;
                        }

                        // Check if the user has verified himself via email already.
                        if ($addData['type'] == 'NORMAL' && (string)$checkmail === 'verify_once') {
                            $auth = serendipity_db_query("SELECT *
                                                            FROM {$serendipity['dbPrefix']}options
                                                           WHERE okey  = 'mail_confirm'
                                                             AND name  = '" . serendipity_db_escape_string($addData['email']) . "'
                                                             AND value = '" . serendipity_db_escape_string($addData['name']) . "'", true);
                            if (!is_array($auth)) {
                                // Filter authors names, Filter URL, Filter Content, Filter Emails, Check for maximum number of links before rejecting
                                // moderate false
                                if (false === $this->wordfilter($logfile, $eventData, $addData, true)) {
                                    // already there #$this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS, $addData);
                                    // already there #$eventData = array('allow_comments' => false);
                                    // already there #$serendipity['messagestack']['emails'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                                    return false;
                                } elseif (serendipity_db_bool($this->get_config('killswitch', 'false')) === true) {
                                    $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH, $addData);
                                    $eventData = array('allow_comments' => false);
                                    $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH;
                                    return false;
                                } else {
                                    $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL, $addData);
                                    $eventData['moderate_comments'] = true;
                                    $eventData['status']            = 'confirm1';
                                    $serendipity['csuccess']        = 'moderate';
                                    $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL;
                                    return false;
                                }
                            } else {
                                // User is allowed to post message, bypassing other checks as if he were logged in.
                                return true;
                            }
                        }

                        // Check if entry title is the same as comment body or a combination of blog title and entry title (bot spam)
                        if (serendipity_db_bool($this->get_config('entrytitle', 'true'))) {
                            // Remove the blog name from the comment which might be in <title>
                            $comment = str_replace($serendipity['blogTitle'], '', $addData['comment']);
                            $comment = str_replace($eventData['title'], '', $comment);
                            // Now blog- and entry title was stripped from comment.
                            // Remove special letters, that might have been between them:
                            $comment = trim(preg_replace('@[\s\-_:\(\)\|/]*@', '', $comment));

                            // Now that we stripped blog and entry title: Do we have an empty comment?
                            if (empty($comment)) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_TITLE, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                                return false;
                            }
                        }

                        // Check for global emergency moderation
                        if (serendipity_db_bool($this->get_config('killswitch', 'false')) === true) {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_KILLSWITCH, $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_KILLSWITCH;
                            return false;
                        }

                        // Check for not allowing trackbacks/pingbacks/wfwcomments
                        if (($addData['type'] != 'NORMAL' || $addData['source'] == 'API')
                        &&   $this->get_config('disable_api_comments', 'none') != 'none') {
                            if ($this->get_config('disable_api_comments') == 'reject') {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_API, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_REASON_API;
                                return false;
                            } elseif ($this->get_config('disable_api_comments') == 'moderate') {
                                $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_REASON_API, $addData);
                                $eventData['moderate_comments'] = true;
                                $serendipity['csuccess']        = 'moderate';
                                $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_REASON_API;
                            }
                        }

                        // Check if sender ip is matching trackback/pingback ip (ip validation)
                        $trackback_ipvalidation_option = $this->get_config('trackback_ipvalidation', 'moderate');
                        if (($addData['type'] == 'TRACKBACK' || $addData['type'] == 'PINGBACK') && $trackback_ipvalidation_option != 'no') {
                            $this->IsHardcoreSpammer();
                            $exclude_urls = explode(';', $this->get_config('trackback_ipvalidation_url_exclude', $this->get_default_exclude_urls()));
                            $found_exclude_url = false;
                            foreach ($exclude_urls AS $exclude_url) {
                                $exclude_url = trim($exclude_url);
                                if (empty($exclude_url)) continue;
                                $found_exclude_url = preg_match('@' . $exclude_url . '@', $addData['url']);
                                if ($found_exclude_url) {
                                    break;
                                }
                            }
                            if (!$found_exclude_url) {
                                $parts = @parse_url($addData['url']);
                                $tipval_method = $trackback_ipvalidation_option == 'reject' ? 'REJECTED' : 'MODERATE';
                                // Getting host from url successfully?
                                if (!is_array($parts)) { // not a valid URL
                                    $this->log($logfile, $eventData['id'], $tipval_method, sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $addData['url'], '', ''), $addData);
                                    if ($trackback_ipvalidation_option == 'reject') {
                                        $eventData = array('allow_comments' => false);
                                        $serendipity['messagestack']['comments'][] = sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $addData['url']);
                                        return false;
                                    } else {
                                        $eventData['moderate_comments'] = true;
                                        $serendipity['csuccess']        = 'moderate';
                                        $serendipity['moderate_reason'] = sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $addData['url']);
                                    }
                                }
                                // Not whitelisted? Check by IP then.
                                $trackback_ip = preg_replace('/[^0-9.]/', '', gethostbyname($parts['host'])); // IPv4
                                $sender_ip    = preg_replace('/[^0-9.]/', '', $_SERVER['REMOTE_ADDR']); // But can return servers IPv6 ...
                                $sender_ua    = $debug ? ', ua="' . $_SERVER['HTTP_USER_AGENT'] . '"' : '';
                                // Is host IP and sender IP matching? Comparable only, if both are in same IPv4 format. Else use the whitelist!
                                if ($trackback_ip != $sender_ip) {
                                    $this->log($logfile, $eventData['id'], $tipval_method, sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $parts['host'], $trackback_ip, $sender_ip  . $sender_ua), $addData);
                                    if ($trackback_ipvalidation_option == 'reject' && $is_ipv6 == false) {
                                        $eventData = array('allow_comments' => false);
                                        $serendipity['messagestack']['comments'][] = sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $parts['host'], $trackback_ip, $sender_ip . $sender_ua);
                                        return false;
                                    } else {
                                        $eventData['moderate_comments'] = true;
                                        $serendipity['csuccess']        = 'moderate';
                                        $serendipity['moderate_reason'] = sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_IPVALIDATION, $parts['host'], $trackback_ip, $sender_ip . $sender_ua);
                                    }
                                }
                            } else {
                                $tb_notwhtlst = false;
                            }
                        }

                        // Filter Akismet Blacklist?
                        $akismet_apikey = $this->get_config('akismet');
                        $akismet        = $this->get_config('akismet_filter');
                        if (!empty($akismet_apikey) && ($akismet == 'moderate' || $akismet == 'reject') && !isset($addData['skip_akismet'])) {
                            $spam = $this->getBlacklist('akismet.com', $akismet_apikey, $eventData, $addData);
                            if ($spam['is_spam'] !== false) {
                                $this->IsHardcoreSpammer();
                                if ($akismet == 'moderate') {
                                    $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST . ': ' . $spam['message'], $addData);
                                    $eventData['moderate_comments'] = true;
                                    $serendipity['csuccess']        = 'moderate';
                                    $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY . ' (Akismet)';
                                } else {
                                    $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_AKISMET_SPAMLIST . ': ' . $spam['message'], $addData);
                                    $eventData = array('allow_comments' => false);
                                    $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                                    return false;
                                }
                            }
                        }

                        // Check Trackback URLs?
                        if (($addData['type'] == 'TRACKBACK' || $addData['type'] == 'PINGBACK') && serendipity_db_bool($this->get_config('trackback_check_url', 'false'))) {
                            serendipity_request_start();

                            $options = array('follow_redirects' => true, 'max_redirects' => 5, 'timeout' => 10);
                            $req = serendipity_request_object($addData['url'], 'get', $options);

                            $is_valid = false;
                            try {
                                $response = $req->send();
                                if ($response->getStatus() != '200') {
                                    throw new HTTP_Request2_Exception('could not get origin url: status != 200');
                                }
                                $fdata = $response->getBody();

                                // Check if the target page contains a link to our blog
                                if (preg_match('@' . preg_quote($serendipity['baseURL'], '@') . '@i', $fdata)) {
                                    $is_valid = true;
                                } else {
                                    $is_valid = false;
                                }
                            } catch (HTTP_Request2_Exception $e) {
                                $is_valid = false;
                            }

                            serendipity_request_end();

                            if ($is_valid === false) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_REASON_TRACKBACKURL;
                                return false;
                            }
                        }

                        if (false === $this->wordfilter($logfile, $eventData, $addData)) {
                            return false;
                        }

                        // Check for maximum number of links before rejecting
                        $link_count = substr_count(strtolower($addData['comment']), 'http://');
                        if ($links_reject > 0 && $link_count > $links_reject) {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT, $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                            return false;
                        }

                        // Captcha checking
                        if ($show_captcha && $addData['type'] == 'NORMAL') {
                            if (!isset($_SESSION['spamblock']['captcha']) || !isset($serendipity['POST']['captcha']) || strtolower($serendipity['POST']['captcha']) != strtolower($_SESSION['spamblock']['captcha'])) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', sprintf(PLUGIN_EVENT_SPAMBLOCK_REASON_CAPTCHAS, $serendipity['POST']['captcha'], $_SESSION['spamblock']['captcha']), $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_CAPTCHAS;
                                return false;
                            } else {
// DEBUG
//                                $this->log($logfile, $eventData['id'], 'REJECTED', 'Captcha passed: ' . $serendipity['POST']['captcha'] . ' / ' . $_SESSION['spamblock']['captcha'] . ' // Source: ' . $_SERVER['REQUEST_URI'], $addData);
                            }
                        } else {
// DEBUG
//                            $this->log($logfile, $eventData['id'], 'REJECTED', 'Captcha not needed: ' . $serendipity['POST']['captcha'] . ' / ' . $_SESSION['spamblock']['captcha'] . ' // Source: ' . $_SERVER['REQUEST_URI'], $addData);
                        }

                        // Check for forced comment moderation (X days)
                        if ($addData['type'] == 'NORMAL' && $forcemoderation > 0 && $eventData['timestamp'] < (time() - ($forcemoderation * 60 * 60 * 24))) {
                            $fm_method = $forcemoderation_treat == 'reject' ? 'REJECTED' : 'MODERATE';
                            $this->log($logfile, $eventData['id'], $fm_method, PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION, $addData);
                            if ($forcemoderation_treat == 'reject') {
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION;
                                return false;
                            } else {
                                $eventData['moderate_comments'] = true;
                                $serendipity['csuccess']        = 'moderate';
                                $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION;
                            }
                        }

                        // Check for forced trackback moderation
                        if ($addData['type'] != 'NORMAL' && $forcemoderationt > 0 && $eventData['timestamp'] < (time() - ($forcemoderationt * 60 * 60 * 24))) {
                            $fmt_method = $forcemoderationt_treat == 'reject' ? 'REJECTED' : 'MODERATE';
                            $this->log($logfile, $eventData['id'], $fmt_method, PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION, $addData);
                            if ($forcemoderationt_treat == 'reject') {
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION;
                                return false;
                            } else {
                                $eventData['moderate_comments'] = true;
                                $serendipity['csuccess']        = 'moderate';
                                $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION;
                            }
                        }

                        // Check for disallowed trackback/pingback to pass-through outside of time-framed allowed comments (@see forceopentopublic)
                        if ($_disallow_trackbacks_passthrough) {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_DATE, $addData);
                            $eventData['moderate_comments'] = false;
                            $serendipity['csuccess']        = 'reject'; // overwrites the 'true' value probably
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_REASON_DATE;
                            $eventData = array('allow_trackbacks_passthrough' => false);
                        }

                        // Check for maximum number of links before forcing moderation
                        if ($links_moderate > 0 && $link_count > $links_moderate) {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE, $addData);
                            $eventData['moderate_comments'] = true;
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_MODERATE;
                        }

                        // Check for identical comments. We allow to bypass trackbacks from our server to our own blog.
                        if (serendipity_db_bool($this->get_config('bodyclone', 'true')) === true && ($_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) && $addData['type'] != 'PINGBACK') {
                            $query = "SELECT count(id) AS counter FROM {$serendipity['dbPrefix']}comments WHERE type = '" . $addData['type'] . "' AND body = '" . serendipity_db_escape_string($addData['comment']) . "'";
                            $row   = serendipity_db_query($query, true);
                            if (is_array($row) && $row['counter'] > 0) {
                                // WHAT WE NEED HERE: Is a check, if someone posts an entry with more than 1 valid trackback URLs to this current blog.
                                //      EG., a weekly blog "summary" linklist entry with some trackback links to different articles of this current blog.
                                // These trackbacks look like bodyclones, since using the same entry as referrer, but are valid and appreciated trackbacks.
                                $mtbcase = false;
                                // non valid multi trackback case
                                if ($addData['type'] == 'TRACKBACK') {
                                    $trackback_ip = $trackback_ip ?? preg_replace('/[^0-9.]/', '', gethostbyname($parts['host'])); // IPv4
                                    $sender_ip    = $sender_ip    ?? preg_replace('/[^0-9.]/', '', $_SERVER['REMOTE_ADDR']); // But can return servers IPv6 ...
                                    $mtbcase      = $tb_notwhtlst ?? $trackback_ip != $sender_ip; // Is host IP and sender IP matching (IPv4 validation)? Else set false for whitelist.
                                }
                                // we could now even extend this special trackback case to send all follow-up-siblings to state 'moderate' (and/or after n $row['counter'])
                                if ($addData['type'] == 'NORMAL' || $mtbcase == true) {
                                    $this->IsHardcoreSpammer();
                                    $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_BODYCLONE, $addData);
                                    $eventData = array('allow_comments' => false);
                                    $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                                    return false;
                                }
                            }
                        }

                        // Check last IP
                        if ($addData['type'] == 'NORMAL' && $this->get_config('ipflood', 2) != 0 ) {
                            $query = "SELECT max(timestamp) AS last_post FROM {$serendipity['dbPrefix']}comments WHERE ip = '" . serendipity_db_escape_string($_SERVER['REMOTE_ADDR']) . "'";
                            $row   = serendipity_db_query($query, true);
                            if (is_array($row) && $row['last_post'] > (time() - $this->get_config('ipflood', 2)*60)) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_IPFLOOD, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_IP;
                                return false;
                            }
                        }

                        if ($addData['type'] == 'NORMAL' && (string)$checkmail === 'verify_always') {
                            $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL, $addData);
                            $eventData['moderate_comments'] = true;
                            $eventData['status']            = 'confirm';
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_MAIL;
                            return false;
                        }

                        // Check invalid email
                        if ($addData['type'] == 'NORMAL' && serendipity_db_bool($this->get_config('checkmail', 'false'))) {
                            if (!empty($addData['email']) && strstr($addData['email'], '@') === false) {
                                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL, $addData);
                                $eventData = array('allow_comments' => false);
                                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_REASON_CHECKMAIL;
                                return false;
                            }
                        }

                        if (isset($eventData['moderate_comments']) && ($eventData['moderate_comments'] === true || $eventData['moderate_comments'] == 'true')) {
                            if ($serendipity['csuccess'] == 'true') $serendipity['csuccess'] = 'moderate'; // for an unchecked hidden case
                            return false;
                        }
                    }
                    break;

                case 'frontend_comment':
                    if (serendipity_db_bool($this->get_config('hide_email', 'true'))) {
                        echo '<div class="serendipity_commentDirection serendipity_comment_spamblock">' . PLUGIN_EVENT_SPAMBLOCK_HIDE_EMAIL_NOTICE . '</div>';
                    }

                    $_checkmail = (string)$this->get_config('checkmail', 'false');
                    if ($_checkmail === 'verify_always' || $_checkmail === 'verify_once') {
                        echo '<div class="serendipity_commentDirection serendipity_comment_spamblock">' . PLUGIN_EVENT_SPAMBLOCK_CHECKMAIL_VERIFICATION_INFO . '</div>';
                    }

                    if (serendipity_db_bool($this->get_config('csrf', 'true'))) {
                        echo serendipity_setFormToken('form');
                    }

                    // Check whether to allow comments from registered authors
                    if (serendipity_userLoggedIn() && $this->inGroup()) {
                        return true;
                    }
                    $_show_captcha = $show_captcha ?? ($captchas && (@$serendipity['GET']['subpage'] == 'adduser' || @$serendipity['POST']['subpage'] == 'adduser'));

                    if ($_show_captcha) {
                        echo '<div class="serendipity_commentDirection serendipity_comment_captcha">'."\n";
                        if (!isset($serendipity['POST']['preview']) || strtolower($serendipity['POST']['captcha'] != @strtolower($_SESSION['spamblock']['captcha']))) {
                            echo PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC . "<br />\n";
                            echo $this->show_captcha($use_gd);
                            echo '<br /><label for="captcha">'. PLUGIN_EVENT_SPAMBLOCK_CAPTCHAS_USERDESC3 . '</label>';
                            echo '<input id="captcha" class="input_textbox" type="text" size="5" name="serendipity[captcha]" value="" />';
                        } elseif (isset($serendipity['POST']['captcha'])) {
                            echo '<input type="hidden" name="serendipity[captcha]" value="' . serendipity_specialchars($serendipity['POST']['captcha']) . '" />';
                        }
                        echo "\n</div>\n";
                    }
                    break;

                case 'external_plugin':
                    $this->_create_captcha($eventData, $_captchas, $use_gd, $max_char, $min_char);
                    if ($logmethod == 'db' || empty($logmethod)) {
                        $this->_clean_spam($eventData);
                    }
                    break;

                case 'backend_maintenance':
                    if ($logmethod == 'db' || empty($logmethod)) {
                        if (!serendipity_checkPermission('adminUsers')) {
                            return false;
                        }
                        $this->checkScheme();
                        $allnum = @serendipity_db_query("SELECT count(1) FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' OR type LIKE 'reject' OR type LIKE 'MODERATE'", true);
                        $allnum = (isset($allnum[0]) && is_numeric($allnum[0])) ? $allnum[0] : 0;

?>

    <section id="maintenance_cleanspam" class="quick_list">
        <h3><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_TITLE; ?></h3>
        <div>
            <?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN; ?>
            <button class="toggle_info button_link cleanspam_info" type="button" data-href="#cleanspam_info_desc"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
            <button class="toggle_info button_link cleanspam_info cleanspam_toggle" type="button" data-href="#cleanspam_action_access"><span class="icon-sort" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo TOGGLE_OPTION; ?></span></button>
        </div>
        <span id="cleanspam_info_desc" class="comment_status additional_info"><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MAINTAIN_DESC; ?></span>
<?php
if (isset($serendipity['GET']['cleanspamsg'])) {
    switch ($serendipity['GET']['cleanspamsg']) {
        case 'true':
            echo '<p class="msg_success" style="margin:0"><span class="icon-ok-circled" aria-hidden="true"></span> ' . PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MSG_DONE . "<p>\n";
            break;
        case 'false':
            echo '<p class="msg_error" style="margin:0"><span class="icon-attention-circled" aria-hidden="true"></span> ' . ERROR_SOMETHING . "<p>\n";
            break;
        case 'logged':
            echo '<p class="msg_success" style="margin:0"><span class="icon-ok-circled" aria-hidden="true"></span> ' . PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_LOGMSG_DONE . "<p>\n";
            break;
        default:
            //void
            break;
    }
}
?>

        <div id="cleanspam_action_access" class="additional_info">
            <a id="cpmall" class="button_link state_submit" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/all" title=""><span><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_BUTTON; ?></span></a> (<?php echo $allnum ?>)
            <button class="toggle_info button_link" style="margin: 1em 0" type="button" data-href="#cpmall_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
            <span id="cpmall_info" class="comment_status additional_info"><?php echo sprintf(PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_ALL_DESC, $allnum); ?></span>
            <div class="serendipity_cpmdiff" style="margin-top: .5em;">
                <div>
                    <?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SELECT; ?>
                    <button class="toggle_info button_link" type="button" data-href="#cleanspam_access_multi_reasons"><span class="icon-sort" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo TOGGLE_OPTION; ?></span></button>
                </div>
                <form id="maintenance_cleanspam_multi" enctype="multipart/form-data" action="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/multi" method="post">
                    <select id="cleanspam_access_multi_reasons" class="additional_info" name="serendipity[cleanspam][multi_reasons][]" multiple="multiple">
                        <option value="">- - -</option>
                        <option value="api">LIKE "<?php echo PLUGIN_EVENT_SPAMBLOCK_REASON_API; ?>"</option>
                        <option value="api">LIKE "<?php echo PLUGIN_EVENT_SPAMBLOCK_REASON_DATE; ?>"</option>
                        <option value="amx">LIKE "<?php echo PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION; ?>"</option>
                        <option value="filter">LIKE "Wordfilter for urls, authors, words, emails"</option>
                        <option value="hpot">LIKE "BEE Honeypot%"</option>
                        <option value="hcap">LIKE "BEE HiddenCaptcha%"</option>
                        <option value="cbay">LIKE "Caught by the Bayes-Plugin%"</option>
                        <option value="ipv">LIKE "IP validation%" in (de, en, cs, cz, sk) languages</option>
                    </select>
                    <div class="form_buttons form_cpm">
                        <input class="state_submit" name="spamclean_multi" type="submit" value="<?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_BUTTON; ?>">
                        <button class="toggle_info button_link" type="button" data-href="#cpmdiff_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
                    </div>
                    <span id="cpmdiff_info" class="comment_status additional_info"><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_MULTI_DESC; ?></span>
                </form>
            </div>
            <div class="serendipity_cpmsavetolog">
                <a id="cpmsave" class="button_link" href="<?php echo $serendipity['serendipityHTTPPath'] . (($serendipity['rewrite'] == 'rewrite') ? '' : 'index.php?/') ?>plugin/cleanspam/log" title=""><span><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_BUTTON; ?></span></a>
                <button class="toggle_info button_link" style="margin: 1em 0" type="button" data-href="#cpmsave_info"><span class="icon-info-circled" aria-hidden="true"></span><span class="visuallyhidden"> <?php echo MORE; ?></span></button>
                <span id="cpmsave_info" class="comment_status additional_info"><?php echo PLUGIN_EVENT_SPAMBLOCK_CLEANSPAM_SAVE_DESC; ?></span>
            </div>
        </div>

    </section>
<?php
                    }
                    break;

                case 'css_backend':
                    if ($logmethod == 'db' || empty($logmethod)) {
                        $eventData .= '

/* serendipity_event_spamblock_start */

#maintenance_cleanspam .comment_status {
    float: none;
    margin: 0 0 .5em;
}
.no-flexbox #maintenance_cleanspam.quick_list {
    margin: 0 0 1em 2%;
}
#maintenance_cleanspam .form_cpm,
#maintenance_cleanspam .toggle_info,
#maintenance_cleanspam .toggle_info:visited,
#maintenance_cleanspam .toggle_info:hover,
#maintenance_cleanspam .toggle_info:focus,
#maintenance_cleanspam .toggle_info:active {
    margin: .5em 0;
}
#maintenance_cleanspam .cleanspam_toggle {
    float: right;
}

/* serendipity_event_spamblock_end */

';
                    }
                    break;

                case 'backend_comments_top':

                    // Tell Akismet about spam or not spam
                    $tell_id = null;
                    if (isset($serendipity['GET']['spamIsSpam'])) {
                        $tell_spam = true;
                        $tell_id = $serendipity['GET']['spamIsSpam'];
                    }
                    if (isset($serendipity['GET']['spamNotSpam'])) {
                        $tell_spam = false;
                        $tell_id = $serendipity['GET']['spamNotSpam'];
                    }
                    if ($tell_id !== null) {
                        $akismet_apikey = $this->get_config('akismet');
                        $akismet        = $this->get_config('akismet_filter');
                        if (!empty($akismet_apikey)) {
                            $this->tellAboutComment('akismet.com', $akismet_apikey, $tell_id, $tell_spam);
                        }
                    }

                    // Add Author to blacklist. If already filtered, it will be removed from the filter. (AKA "Toggle")
                    if (isset($serendipity['GET']['spamBlockAuthor'])) {
                        $item    = $this->getComment('author', $serendipity['GET']['spamBlockAuthor']);
                        $items   = &$this->checkFilter('authors', $item, true);
                        $this->set_config('contentfilter_authors', implode(';', $items));
                    }

                    // Add URL to blacklist. If already filtered, it will be removed from the filter. (AKA "Toggle")
                    if (isset($serendipity['GET']['spamBlockURL'])) {
                        $item    = $this->getComment('url', $serendipity['GET']['spamBlockURL']);
                        $items   = &$this->checkFilter('urls', $item, true);
                        $this->set_config('contentfilter_urls', implode(';', $items));
                    }

                    // Add E-mail to blacklist. If already filtered, it will be removed from the filter. (AKA "Toggle")
                    if (isset($serendipity['GET']['spamBlockEmail'])) {
                        $item    = $this->getComment('email', $serendipity['GET']['spamBlockEmail']);
                        $items   = &$this->checkFilter('emails', $item, true);
                        $this->set_config('contentfilter_emails', implode(';', $items));
                    }

                    if (serendipity_checkPermission('adminUsers')) {
                        echo '<a class="button_link" title="' . PLUGIN_EVENT_SPAMBLOCK_CONFIG . '" href="serendipity_admin.php?serendipity[adminModule]=plugins&amp;serendipity[plugin_to_conf]=' . $this->instance . '"><span class="icon-medkit" aria-hidden="true"></span><span class="visuallyhidden"> ' . PLUGIN_EVENT_SPAMBLOCK_CONFIG . '</span></a>';
                    }
                    break;

                case 'backend_view_comment':
                    $author_is_filtered = $this->checkFilter('authors', $eventData['author']);
                    $clink = 'comment_' . $eventData['id'];
                    $randomString = '&amp;random=' . substr(sha1(rand()), 0, 10);    # the random string will force browser to reload the page,
                                                                                     # so the server knows who to block/unblock when clicking again on the same link,
                                                                                     # see http://stackoverflow.com/a/2573986/2508518, http://stackoverflow.com/a/14043346/2508518
                    $akismet_apikey = $this->get_config('akismet');
                    $akismet        = $this->get_config('akismet_filter');
                    if (!empty($akismet_apikey)) {
                        $eventData['action_more'] .= ' <a class="button_link actions_extra" title="' . PLUGIN_EVENT_SPAMBLOCK_SPAM . '" href="serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[spamIsSpam]=' . $eventData['id'] . $addData . '#' . $clink . '"><span class="icon-block" aria-hidden="true"></span><span class="visuallyhidden"> ' . PLUGIN_EVENT_SPAMBLOCK_SPAM . '</span></a>';
                        $eventData['action_more'] .= ' <a class="button_link actions_extra" title="' . PLUGIN_EVENT_SPAMBLOCK_NOT_SPAM . '" href="serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[spamNotSpam]=' . $eventData['id'] . $addData . '#' . $clink . '"><span class="icon-ok-circled" aria-hidden="true"></span><span class="visuallyhidden"> ' . PLUGIN_EVENT_SPAMBLOCK_NOT_SPAM . '</span></a>';
                    }

                    if (!isset($eventData['action_author'])) $eventData['action_author'] = '';
                    $eventData['action_author'] .= ' <a class="button_link" title="' . ($author_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR : PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR) . '" href="serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[spamBlockAuthor]=' . $eventData['id'] . $addData . $randomString . '#' . $clink . '"><span class="icon-' . ($author_is_filtered ? 'ok-circled' : 'block') .'" aria-hidden="true"></span><span class="visuallyhidden"> ' . ($author_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_AUTHOR : PLUGIN_EVENT_SPAMBLOCK_ADD_AUTHOR) . '</span></a>';

                    if (!empty($eventData['url'])) {
                        $url_is_filtered = $this->checkFilter('urls', $eventData['url']);
                        if (!isset($eventData['action_url'])) $eventData['action_url'] = '';
                        $eventData['action_url'] .= ' <a class="button_link" title="' . ($url_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL : PLUGIN_EVENT_SPAMBLOCK_ADD_URL) . '" href="serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[spamBlockURL]=' . $eventData['id'] . $addData . $randomString . '#' . $clink . '"><span class="icon-' . ($url_is_filtered ? 'ok-circled' : 'block') .'" aria-hidden="true"></span><span class="visuallyhidden"> ' . ($url_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_URL : PLUGIN_EVENT_SPAMBLOCK_ADD_URL) . '</span></a>';
                    }

                    if (!empty($eventData['email'])) {
                        $email_is_filtered = $this->checkFilter('emails', $eventData['email']);
                        if (!isset($eventData['action_email'])) $eventData['action_email'] = '';
                        $eventData['action_email'] .= ' <a class="button_link" title="' . ($email_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL : PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL) . '" href="serendipity_admin.php?serendipity[adminModule]=comments&amp;serendipity[spamBlockEmail]=' . $eventData['id'] . $addData . $randomString . '#' . $clink . '"><span class="icon-' . ($email_is_filtered ? 'ok-circled' : 'block') .'" aria-hidden="true"></span><span class="visuallyhidden"> ' . ($email_is_filtered ? PLUGIN_EVENT_SPAMBLOCK_REMOVE_EMAIL : PLUGIN_EVENT_SPAMBLOCK_ADD_EMAIL) . '</span></a>';
                    }
                    // init assign
                    $eventData['action_email'] = $eventData['action_email'] ?? null;
                    $eventData['action_ip'] = $eventData['action_ip'] ?? null;
                    $eventData['action_referer'] = $eventData['action_referer'] ?? null;
                    break;
/*
                case 'backend_sidebar_admin': // this is section: settings - append
                    echo '<li><a href="serendipity_admin.php?serendipity[adminModule]=plugins&amp;serendipity[plugin_to_conf]=' . $this->instance . '">' . PLUGIN_EVENT_SPAMBLOCK_TITLE . "</a></li>\n";
                    break;
*/
                default:
                    return false;
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * wordfilter, email and additional link check moved to this function, to allow comment user to opt-in (verify_once), but reject all truly spam comments before.
     */
    function wordfilter($logfile, &$eventData, $addData, $ftc = false)
    {
        global $serendipity;

        // Check for word filtering
        if ($filter_type = $this->get_config('contentfilter_activate', 'moderate')) {

            if ($ftc) $filter_type = 'reject';

            // Filter authors names
            $filter_authors = explode(';', $this->get_config('contentfilter_authors', $this->filter_defaults['authors']));
            if (is_array($filter_authors)) {
                foreach($filter_authors AS $filter_author) {
                    $filter_author = preg_quote(trim($filter_author));
                    if (empty($filter_author)) {
                        continue;
                    }
                    if (preg_match('@(' . $filter_author . ')@i', $addData['name'], $wordmatch)) {
                        if ($filter_type == 'moderate') {
                            $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS . ': ' . $wordmatch[1], $addData);
                            $eventData['moderate_comments'] = true;
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY . ' (' . PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS . ': ' . $wordmatch[1] . ')';
                        } else {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS . ': ' . $wordmatch[1], $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                            return false;
                        }
                    }
                }
            }

            // Filter URL
            $filter_urls = explode(';', $this->get_config('contentfilter_urls', $this->filter_defaults['urls']));
            if (is_array($filter_urls)) {
                foreach($filter_urls AS $filter_url) {
                    $filter_url = trim($filter_url);
                    if (empty($filter_url)) {
                        continue;
                    }
                    if (preg_match('@(' . $filter_url . ')@i', $addData['url'], $wordmatch)) {
                        if ($filter_type == 'moderate') {
                            $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS . ': ' . $wordmatch[1], $addData);
                            $eventData['moderate_comments'] = true;
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY . ' (' . PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS . ': ' . $wordmatch[1] . ')';
                        } else {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS . ': ' . $wordmatch[1], $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                            return false;
                        }
                    }
                }
            }

            // Filter Content
            $filter_bodys = explode(';', $this->get_config('contentfilter_words', $this->filter_defaults['words']));
            if (is_array($filter_bodys)) {
                foreach($filter_bodys AS $filter_body) {
                    $filter_body = trim($filter_body);
                    if (empty($filter_body)) {
                        continue;
                    }
                    if (preg_match('@(' . $filter_body . ')@i', $addData['comment'], $wordmatch)) {
                        if ($filter_type == 'moderate') {
                            $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS . ': ' . $wordmatch[1], $addData);
                            $eventData['moderate_comments'] = true;
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY . ' (' . PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS . ': ' . $wordmatch[1] . ')';
                        } else {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS . ': ' . $wordmatch[1], $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                            return false;
                        }
                    }
                }
            }

            // Filter Emails
            $filter_emails = explode(';', $this->get_config('contentfilter_emails', $this->filter_defaults['emails']));
            if (is_array($filter_emails)) {
                foreach($filter_emails AS $filter_email) {
                    $filter_email = trim($filter_email);
                    if (empty($filter_email)) {
                        continue;
                    }
                    if (preg_match('@(' . $filter_email . ')@i', $addData['email'], $wordmatch)) {
                        $this->IsHardcoreSpammer();
                        if ($filter_type == 'moderate') {
                            $this->log($logfile, $eventData['id'], 'MODERATE', PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS . ': ' . $wordmatch[1], $addData);
                            $eventData['moderate_comments'] = true;
                            $serendipity['csuccess']        = 'moderate';
                            $serendipity['moderate_reason'] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY . ' (' . PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS . ': ' . $wordmatch[1] . ')';
                        } else {
                            $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS . ': ' . $wordmatch[1], $addData);
                            $eventData = array('allow_comments' => false);
                            $serendipity['messagestack']['emails'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                            return false;
                        }
                    }
                }
            }
        } // Content filtering end

        if ($ftc) {
            // Check for maximum number of links before rejecting
            $link_count = substr_count(strtolower($addData['comment']), 'http://');
            $links_reject = $this->get_config('links_reject', 20);
            if ($links_reject > 0 && $link_count > $links_reject) {
                $this->log($logfile, $eventData['id'], 'REJECTED', PLUGIN_EVENT_SPAMBLOCK_REASON_LINKS_REJECT, $addData);
                $eventData = array('allow_comments' => false);
                $serendipity['messagestack']['comments'][] = PLUGIN_EVENT_SPAMBLOCK_ERROR_BODY;
                return false;
            }
        }

    } // function wordfilter end

    function &checkFilter($what, $match, $getItems = false)
    {
        $items = explode(';', $this->get_config('contentfilter_' . $what, $this->filter_defaults[$what]));

        $filtered = false;
        if (is_array($items)) {
            foreach($items AS $key => $item) {
                if (empty($match)) {
                    continue;
                }

                if (empty($item)) {
                    unset($items[$key]);
                    continue;
                }

                if (preg_match('@' . preg_quote($item) . '@', $match)) {
                    $filtered = true;
                    unset($items[$key]);
                }
            }
        }

        if ($getItems) {
            if (!$filtered && !empty($match)) {
                $items[] = preg_quote($match, '@');
            }

            return $items;
        }

        return $filtered;
    }

    function getComment($key, $id)
    {
        global $serendipity;
        $c = serendipity_db_query("SELECT $key FROM {$serendipity['dbPrefix']}comments WHERE id = '" . (int)$id . "'", true, 'assoc');

        if (!is_array($c) || !isset($c[$key])) {
            return false;
        }

        return $c[$key];
    }

    function random_string($max_char, $min_char)
    {
        $this->chars = array(2, 3, 4, 7, 9); // 1, 5, 6 and 8 may look like characters.
        $this->chars = array_merge($this->chars, array('A','B','C','D','E','F','H','J','K','L','M','N','P','Q','R','T','U','V','W','X','Y','Z')); // I, O, S may look like numbers

        $strings   = array_rand($this->chars, mt_rand($min_char, $max_char));
        $string    = '';
        foreach($strings AS $idx => $charidx) {
            $string .= $this->chars[$charidx];
        }
        $_SESSION['spamblock'] = array('captcha' => $string);

        return $strings;
    }

    function log($logfile, $id, $switch, $reason, $comment)
    {
        global $serendipity;

        $method = $this->get_config('logtype', 'none');

        switch($method) {
            case 'file':
                if (empty($logfile)) {
                    return;
                }
                if (strpos($logfile, '%') !== false) {
                    $logfile = strftime($logfile);
                }

                $fp = @fopen($logfile, 'a+');
                if (!is_resource($fp)) {
                    return;
                }

                fwrite($fp, sprintf(
                    '[%s] - [%s: %s] - [#%s, Name "%s", E-Mail "%s", URL "%s", User-Agent "%s", IP %s] - [%s]' . "\n",
                    date('Y-m-d H:i:s', serendipity_serverOffsetHour()),
                    $switch,
                    $reason,
                    $id,
                    str_replace("\n", ' ', $comment['name']),
                    str_replace("\n", ' ', $comment['email']),
                    str_replace("\n", ' ', $comment['url']),
                    str_replace("\n", ' ', $_SERVER['HTTP_USER_AGENT']),
                    $_SERVER['REMOTE_ADDR'],
                    str_replace("\n", ' ', $comment['comment'])
                ));

                fclose($fp);
                break;

            case 'none':
                return;
                break;

            case 'db':
            default:
                $q = sprintf("INSERT INTO {$serendipity['dbPrefix']}spamblocklog
                                          (timestamp, type, reason, entry_id, author, email, url,  useragent, ip,   referer, body)
                                   VALUES (%d,        '%s',  '%s',  '%d',     '%s',   '%s',  '%s', '%s',      '%s', '%s',    '%s')",

                           serendipity_serverOffsetHour(),
                           serendipity_db_escape_string($switch),
                           serendipity_db_escape_string($reason),
                           serendipity_db_escape_string($id),
                           serendipity_db_escape_string($comment['name']),
                           serendipity_db_escape_string($comment['email']),
                           serendipity_db_escape_string($comment['url']),
                           substr(serendipity_db_escape_string($_SERVER['HTTP_USER_AGENT']), 0, 255),
                           serendipity_db_escape_string($_SERVER['REMOTE_ADDR']),
                           substr(serendipity_db_escape_string(isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : $_SERVER['HTTP_REFERER']), 0, 255),
                           serendipity_db_escape_string($comment['comment'])
                );

                serendipity_db_query($q);
                break;
        }
    }

    /**
     * Create captcha image
     */
    function _create_captcha(&$eventData, $_captchas = true, $use_gd = false, $max_char = 5, $min_char = 3)
    {
        global $serendipity;

        $parts = explode('_', (string)$eventData);
        if (!empty($parts[1])) {
            $param = (int)$parts[1]; // get the sessions random data string
        } else {
            $param = null;
        }

        $methods = array('captcha');

        if (!in_array($parts[0], $methods)) {
            return;
        }

        list($musec, $msec) = explode(' ', microtime());
        $srand = (float) $msec + ((float) $musec * 100000);
        @srand($srand); // silence nasty "Implicit conversion from float 1634150650.2 to int loses precision" error, destroying the image
        @mt_srand($srand); // silence nasty "Implicit conversion from float 1634150650.2 to int loses precision" error, destroying the image
        $width = 120;
        $height = 40;

        $bgcolors = explode(',', $this->get_config('captcha_color', '255,255,255'));
        $fontfiles = array('Vera.ttf', 'VeraSe.ttf', 'chumbly.ttf', '36daysago.ttf');

        if ($use_gd) {
            $strings  = $this->random_string($max_char, $min_char);
            $fontname = $fontfiles[array_rand($fontfiles)];
            $font     = $serendipity['serendipityPath'] . 'plugins/serendipity_event_spamblock/' . $fontname;

            if (!file_exists($font)) {
                // Search in shared plugin directory
                $font = S9Y_INCLUDE_PATH . 'plugins/serendipity_event_spamblock/' . $fontname;
            }

            if (!file_exists($font)) {
                die(PLUGIN_EVENT_SPAMBLOCK_ERROR_NOTTF);
            }

            header('Content-Type: image/jpeg');
            $image  = imagecreate($width, $height); // recommended use of imagecreatetruecolor() returns a black backgroundcolor
            $bgcol  = imagecolorallocate($image, trim($bgcolors[0]), trim($bgcolors[1]), trim($bgcolors[2]));
            // imagettftext($image, 10, 1, 1, 15, imagecolorallocate($image, 255, 255, 255), $font, 'String: ' . $string);

            $pos_x  = 5;
            foreach($strings AS $idx => $charidx) {
                $color = imagecolorallocate($image, mt_rand(50, 235), mt_rand(50, 235), mt_rand(50,235));
                $size  = mt_rand(15, 21);
                $angle = mt_rand(-20, 20);
                $pos_y = ceil($height - (@mt_rand($size/3, $size/2))); // silence nasty "Implicit conversion from float 1.2 to int loses precision" errors, destroying the image

                imagettftext(
                  $image,
                  $size,
                  $angle,
                  $pos_x,
                  $pos_y,
                  $color,
                  $font,
                  $this->chars[$charidx]
                );

                $pos_x = $pos_x + $size + 2;
            }

            if ($_captchas === 'scramble') {
                $line_diff = mt_rand(5, 15);
                $pixel_col = imagecolorallocate($image, trim($bgcolors[0])-mt_rand(10,50), trim($bgcolors[1])-mt_rand(10,50), trim($bgcolors[2])-mt_rand(10,50));
                for ($y = $line_diff; $y < $height; $y += $line_diff) {
                    $row_diff = mt_rand(5, 15);
                    for ($x = $row_diff; $x < $width; $x+= $row_diff) {
                        imagerectangle($image, $x, $y, $x+1, $y+1, $pixel_col);
                    }
                }
            }
            imagejpeg($image, NULL, 90); // NULL fixes https://bugs.php.net/bug.php?id=63920
            imagedestroy($image);
        } else {
            header('Content-Type: image/png');
            $output_char = strtolower($_SESSION['spamblock']['captcha'][$param - 1]);
            $cap = $serendipity['serendipityPath'] . 'plugins/serendipity_event_spamblock/captcha_' . $output_char . '.png';
            if (!file_exists($cap)) {
                $cap = S9Y_INCLUDE_PATH . 'plugins/serendipity_event_spamblock/captcha_' . $output_char . '.png';
            }

            if (file_exists($cap)) {
                echo file_get_contents($cap);
            }
        }
    }

    /**
     * Maintenance task to clean up spamblock database logs
     */
    function _clean_spam(&$eventData)
    {
        global $serendipity;

        if (!serendipity_checkPermission('adminUsers')) {
            if (defined('IN_serendipity_admin')) echo "Don't hack! Admin permissions required.";
            return false;
        }
        $part = explode('/', $eventData);
        if ($part[0] == 'cleanspam') {
            $sbldone = false;
            $append = 'false';

            if ($part[1] == 'log') {
                $cleanspamlog = serendipity_db_query("SELECT * , from_unixtime( timestamp ) AS tdate FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' OR type LIKE 'reject' OR type LIKE 'MODERATE' ORDER BY tdate DESC");
                if (is_object($serendipity['logger'])) {
                    $serendipity['logger']->debug("\n" . str_repeat(" <<< ", 10) . "DEBUG START serendipity_event_spamblock (cleanspam) SEPARATOR" . str_repeat(" <<< ", 10) . "\n");
                    $serendipity['logger']->debug("LOG: " . print_r($cleanspamlog,1));
                    $append = 'logged';
                }
                unset($cleanspamlog);
            }

            // we can cleanup all field-"type" ('REJECTED' or 'MODERATE') which are probably all the spammer logs at once
            if ($part[1] == 'all') {
                @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' OR type LIKE 'reject'");
                @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND body=''"); // To make this case_sensitive, use WHERE BINARY type LIKE, but we don't care since we search for empty bodies
                $sbldone = true;
            }

            // or do it by field-"type" ('REJECTED' or 'MODERATE') and field-"reason" 'No API-created comments allowed', 'BEE Honeypot%', 'BEE HiddenCaptcha%', 'Caught by the Bayes-Plugin%', 'IP validation%', 'IP Validierung%', 'Kontrola IP adresy%'
            if ($part[1] == 'multi') {
                $multir = $serendipity['POST']['cleanspam']['multi_reasons'];
                if (is_array($multir) & !empty($multir)) {
                    foreach($multir AS $p) {
                        if ($p == 'api') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason='".PLUGIN_EVENT_SPAMBLOCK_REASON_API."'"); // (since already translated variously.., we have to use the constant
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason='".PLUGIN_EVENT_SPAMBLOCK_REASON_API."'"); // (since already translated variously.., we have to use the constant
                            $sbldone = true;
                        }
                        if ($p == 'amx') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason='".PLUGIN_EVENT_SPAMBLOCK_REASON_FORCEMODERATION."' OR reason='Auto-moderation after X days')"); // (Auto-moderation after X days, we use the constant and <en> lang
                            $sbldone = true;
                        }
                        if ($p == 'filter') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason LIKE '".PLUGIN_EVENT_SPAMBLOCK_FILTER_URLS."%' OR reason LIKE 'Wordfilter for URLs%')"); // wordfilter for ... lang constant and <en>
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason LIKE '".PLUGIN_EVENT_SPAMBLOCK_FILTER_AUTHORS."%' OR reason LIKE 'Wordfilter for author names%')");
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason LIKE '".PLUGIN_EVENT_SPAMBLOCK_FILTER_WORDS."%' OR reason LIKE 'Wordfilter for comment body%')");
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason LIKE '".PLUGIN_EVENT_SPAMBLOCK_FILTER_EMAILS."%' OR reason LIKE 'Wordfilter for comment E-mail%')");
                            $sbldone = true;
                        }
                        if ($p == 'hpot') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'BEE Honeypot%'"); // (approximately big data)
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'BEE Honeypot%'"); // (approximately big data)
                            $sbldone = true;
                        }
                        if ($p == 'hcap') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'BEE HiddenCaptcha%'"); // (approximately small data)
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'BEE HiddenCaptcha%'"); // (approximately small data)
                            $sbldone = true;
                        }
                        if ($p == 'ipv') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND (reason LIKE 'IP validation%' OR reason LIKE 'IP Validierung%' OR reason LIKE 'Kontrola IP adresy%')"); // (en, de, cs, cz, sk) (approximately mid-big data)
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND (reason LIKE 'IP validation%' OR reason LIKE 'IP Validierung%' OR reason LIKE 'Kontrola IP adresy%')"); // (en, de, cs, cz, sk) (approximately mid-big data)
                            $sbldone = true;
                        }
                        if ($p == 'cbay') {
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'REJECTED' AND reason LIKE 'Caught by the Bayes-Plugin%'"); // (approximately mid-big data)
                            @serendipity_db_query("DELETE FROM {$serendipity['dbPrefix']}spamblocklog WHERE type LIKE 'MODERATE' AND reason LIKE 'Caught by the Bayes-Plugin%'"); // (approximately mid-big data)
                            $sbldone = true;
                        }
                    }
                }
            }

            if ($sbldone) {
                switch($serendipity['dbType']) {
                    case 'sqlite':
                    case 'sqlite3':
                    case 'sqlite3oo':
                    case 'pdo-sqlite':
                        $sql = "VACUUM";
                        break;
                    case 'pdo-postgres':
                    case 'postgres':
                        $sql = "VACUUM";
                        break;
                    case 'mysqli':
                        $sql = "OPTIMIZE TABLE {$serendipity['dbPrefix']}spamblocklog";
                        break;
                }
                if (isset($sql)) {
                    @serendipity_db_query($sql);
                    $append = 'true';
                }
            }
            // exit
            header('Location: ' . $serendipity['baseURL'] . 'serendipity_admin.php' . ($serendipity['rewrite'] == 'none' ? '?/' : '?') . 'serendipity[adminModule]=maintenance&serendipity[cleanspamsg]='.$append);
        }
    }

}

/* vim: set sts=4 ts=4 expandtab : */

?>