<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$CONST.LANG_CHARSET}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{serendipity_getFile file='admin/installer.css'}" type="text/css">
    <script>
        document.documentElement.className = 'js';
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-color-mode', 'dark');
            document.write('    <link rel="stylesheet" href="{serendipity_getFile file='admin/styx_dark.min.css'}" type="text/css">');
        }
    </script>
</head>
<body id="serendipity_admin_page">
    <header id="top">
        <div class="clearfix">
            <div id="banner_install">
                <h1>{$CONST.SERENDIPITY_INSTALLATION}</h1>
            </div>
        </div>
    </header>

    <main class="clearfix serendipityAdminContent installer">
        <div id="content" class="clearfix">
{if $is_errors AND is_array($errors)}
            <ul class="plainList">
{foreach $errors AS $error}
                <li><span class="msg_error list"><svg class="bi bi-exclamation-triangle-fill" width="16" height="16" role="img" aria-label="Error:"><title>{$CONST.ERROR}</title><use xlink:href="#exclamation-triangle-fill"></use></svg> {$error}</span></li>
{/foreach}
            </ul>
{if NOT empty($prevstep)}
            <div>
                <a class="button_link" href="serendipity_admin.php?serendipity[step]={$prevstep}">{$CONST.PREVIOUS_PAGE}</a>
            </div>
{/if}
{/if}
{if $install_blank}
            <h3>{$CONST.SERENDIPITY_ADMIN_SUITE}:</h3>
            <p class="msg_hint">{$CONST.INSTALLER_TOKEN_NOTE|sprintf:$install_token_file:$install_token:$install_lifetime}</p>
            <div>
                <a class="block_level" href="index.php">{$CONST.RECHECK_INSTALLATION}</a>
            </div>
{elseif $install_token_fail}
            <h3>{$CONST.ERROR}:</h3>
            <p class="msg_error"><svg class="bi bi-exclamation-triangle-fill" width="16" height="16" role="img" aria-label="Error:"><title>{$CONST.ERROR}</title><use xlink:href="#exclamation-triangle-fill"></use></svg> {$CONST.INSTALLER_TOKEN_MISMATCH|sprintf:$install_token:$install_token_file}</p>
            <div>
                <a class="block_level" href="index.php">{$CONST.RECHECK_INSTALLATION}</a>
            </div>
{elseif $getstepint0}
            <h2>{$CONST.WELCOME_TO_INSTALLATION}</h2>

            <p>{$CONST.FIRST_WE_TAKE_A_LOOK}</p>

            <p>{$print_ERRORS_ARE_DISPLAYED_IN}</p>

            <h3>{$CONST.PRE_INSTALLATION_REPORT|sprintf:$s9yversion}</h3>

            <div id="diagnose">
                <h4>{$CONST.INTEGRITY}</h4>

                <ul class="plainList">
{foreach $installerResultDiagnose_CHECKSUMS AS $cksum}
                    <li>{$cksum}</li>
{/foreach}
                </ul>

                <table>
                    <caption>{$CONST.PHP_INSTALLATION}</caption>
                    <thead>
                        <tr>
                            <th>{$CONST.INSTALLER_KEY}</th>
                            <th>{$CONST.INSTALLER_VALUE}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$CONST.OPERATING_SYSTEM}</td>
                            <td><span class="msg_hint">{$php_uname}</span></td>
                        </tr>
                        <tr>
                            <td>{$CONST.WEBSERVER_SAPI}</td>
                            <td><span class="msg_hint">{$php_sapi_name}</span></td>
                        </tr>
                        <tr>
                            <td>PHP version >= 7.3</td>
                            <td>{$installerResultDiagnose_VERSION}</td>
                        </tr>
                        <tr>
                            <td>Database extensions</td>
                            <td>{$installerResultDiagnose_DBTYPE}</td>
                        </tr>
                        <tr>
                            <td>Session extension</td>
                            <td>{$installerResultDiagnose_SESSION}</td>
                        </tr>
                        <tr>
                            <td>PCRE extension</td>
                            <td>{$installerResultDiagnose_PCRE}</td>
                        </tr>
                        <tr>
                            <td>GDlib extension</td>
                            <td>{$installerResultDiagnose_GD}</td>
                        </tr>
                        <tr>
                            <td>OpenSSL extension</td>
                            <td>{$installerResultDiagnose_OPENSSL}</td>
                        </tr>
                        <tr>
                            <td>OpenSSL version >= 1.1.1d</td>
                            <td>{$installerResultDiagnose_OPENSSL_version|default:'-'}</td>
                        </tr>
                        <tr>
                            <td>mbstring extension</td>
                            <td>{$installerResultDiagnose_MBSTR}</td>
                        </tr>
                        <tr>
                            <td>iconv extension</td>
                            <td>{$installerResultDiagnose_ICONV}</td>
                        </tr>
                        <tr>
                            <td>XML extension</td>
                            <td>{$installerResultDiagnose_XML}</td>
                        </tr>
                        <tr>
                            <td>zlib extension</td>
                            <td>{$installerResultDiagnose_ZLIB}</td>
                        </tr>
                        <tr>
                            <td>Imagemagick binary </td>
                            <td>{$installerResultDiagnose_IM}</td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <caption>{$CONST.PHPINI_CONFIGURATION}</caption>
                    <thead>
                        <tr>
                            <th>&nbsp;</th>
                            <th>{$CONST.RECOMMENDED}</th>
                            <th>{$CONST.ACTUAL}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>safe_mode</td>
                            <td><strong>OFF</strong></td>
                            <td>{$installerResultDiagnose_SSM}</td>
                        </tr>
                        <tr>
                            <td>register_globals</td>
                            <td><strong>OFF</strong></td>
                            <td>{$installerResultDiagnose_SRG}</td>
                        </tr>
                        <tr>
                            <td>session.use_trans_sid</td>
                            <td><strong>OFF</strong></td>
                            <td>{$installerResultDiagnose_SSUTS}</td>
                        </tr>
                        <tr>
                            <td>allow_url_fopen</td>
                            <td><strong>ON</strong></td>
                            <td>{$installerResultDiagnose_SAUF}</td>
                        </tr>
                        <tr>
                            <td>file_uploads</td>
                            <td><strong>ON</strong></td>
                            <td>{$installerResultDiagnose_SFU}</td>
                        </tr>
                        <tr>
                            <td>post_max_size</td>
                            <td><strong>10M</strong></td>
                            <td>{$installerResultDiagnose_SPMS}</td>
                        </tr>
                        <tr>
                            <td>upload_max_filesize</td>
                            <td><strong>10M</strong></td>
                            <td>{$installerResultDiagnose_SUMF}</td>
                        </tr>
                        <tr>
                            <td>memory_limit</td>
                            <td><strong>{($CONST.PHP_INT_SIZE == 4) ? '8M' : '16M'}</strong></td>
                            <td>{$installerResultDiagnose_SML}</td>
                        </tr>
                    </tbody>
                </table>

                <table>
                    <caption>{$CONST.PERMISSIONS}</caption>
                    <thead>
                        <tr>
                            <th>{$CONST.FILTER_DIRECTORY}</th>
                            <th>{$CONST.PERMISSIONS}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><h5>{$basedir}</h5></td>
                            <td>{$installerResultDiagnose_BASE_WRITABLE}</td>
                        </tr>
                        <tr>
                            <td><h5>{$basedir}{$CONST.PATH_SMARTY_COMPILE}</h5></td>
                            <td>{$installerResultDiagnose_COMPILE}</td>
                        </tr>
                        <tr>
                            <td><h5>{$basedir}archives/</h5></td>
                            <td>{$installerResultDiagnose_ARCHIVES}</td>
                        </tr>
                        <tr>
                            <td><h5>{$basedir}plugins</h5></td>
                            <td>{$installerResultDiagnose_PLUGINS}</td>
                        </tr>
{if $is_dir_uploads}
                            <tr>
                                <td><h5>{$basedir}uploads/</h5></td>
                                <td>{$installerResultDiagnose_UPLOADS}</td>
                            </tr>
{/if}
                    </tbody>
                </table>
{if $showWritableNote}

                <span class="msg_notice"><svg class="bi bi-info-circle" width="16" height="16" role="img" aria-label="Info: {$CONST.PERMISSIONS}"><title>{$CONST.DESCRIPTION}</title><use xlink:href="#info-circle"></use></svg> {$CONST.PROBLEM_PERMISSIONS_HOWTO|sprintf:'chmod 1777'}</span>
{/if}

                <table>
                    <caption>{$CONST.INSTALLER_CLI_TOOLS}</caption>
                    <thead>
                        <tr>
                            <th>{$CONST.INSTALLER_CLI_TOOLNAME}</th>
                            <th>{$CONST.INSTALLER_CLI_TOOLSTATUS}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Execute Imagemagick binary</td>
                            <td>{$installerResultDiagnose_IMB}</td>
                        </tr>

                    </tbody>
                </table>
{if $errorCount > 0}

                <hr />
                <span class="msg_error"><svg class="bi bi-exclamation-triangle-fill" width="16" height="16" role="img" aria-label="Error:"><title>{$CONST.ERROR}</title><use xlink:href="#exclamation-triangle-fill"></use></svg> {$CONST.PROBLEM_DIAGNOSTIC}</span>
                <div class="form_buttons">
                    <a class="block_level" href="serendipity_admin.php">{$CONST.RECHECK_INSTALLATION}</a>
                </div>
{elseif $install_token_pass}

                <table>
                    <caption>{$CONST.SECURITY}</caption>
                    <thead>
                        <tr>
                            <th>{$CONST.INSTALLER_TOKEN}</th>
                            <th>{$CONST.INSTALLER_TOKEN_STATUS}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{$CONST.INSTALLER_TOKEN_CHECK}</td>
                            <td><span class="msg_success">{$CONST.INSTALLER_TOKEN_MATCH}</span></td>
                        </tr>
                    </tbody>
                </table>

                <p><strong>{$CONST.SELECT_INSTALLATION_TYPE}:</strong></p>

                <div class="form_buttons">
                    <a class="button_link state_submit" href="?serendipity[step]=2a">{$CONST.SIMPLE_INSTALLATION}</a>
                    <a class="button_link state_submit" href="?serendipity[step]=2b">{$CONST.EXPERT_INSTALLATION}</a>
                </div>
{/if}
{* Step: 0 - Diagnose end *}
            </div>
{elseif $s9yGETstep == '2a' AND $install_token_pass}

            <form action="?" method="post">
                <input name="serendipity[step]" type="hidden" value="{$s9yGETstep}">
                <input name="serendipity[getstep]" type="hidden" value="3">
{if $ob_serendipity_printConfigTemplate}
                {$ob_serendipity_printConfigTemplate}
{/if}
                <div class="form_buttons">
                    <a class="button_link" href="serendipity_admin.php">{$CONST.BACK}</a>
                    <input name="submit" type="submit" value="{$CONST.COMPLETE_INSTALLATION}">
                </div>
            </form>
{elseif $s9yGETstep == '2b' AND $install_token_pass}

            <form action="?" method="post">
                <input name="serendipity[step]" type="hidden" value="{$s9yGETstep}">
                <input name="serendipity[getstep]" type="hidden" value="3">
{if $ob_serendipity_printConfigTemplate}
                {$ob_serendipity_printConfigTemplate}
{/if}
                <div class="form_buttons">
                    <a class="button_link" href="serendipity_admin.php">{$CONST.BACK}</a>
                    <input name="submit" type="submit" value="{$CONST.COMPLETE_INSTALLATION}">
                </div>
            </form>
{elseif $s9yGETstep == '3' AND $install_token_pass}

            <h3>{$CONST.CHECK_DATABASE_EXISTS}</h3>
{if is_array($authors_query)}
            <span class="msg_success"><svg class="bi bi-check-circle-fill" width="16" height="16" role="img" aria-label="OK:"><title>{$CONST.DONE}</title><use xlink:href="#check-circle-fill"></use></svg> <strong>{$CONST.THEY_DO}</strong>, {$CONST.WONT_INSTALL_DB_AGAIN}</span>
{else}
            <span class="msg_success"><svg class="bi bi-check-circle-fill" width="16" height="16" role="img" aria-label="OK:"><title>{$CONST.DONE}</title><use xlink:href="#check-circle-fill"></use></svg> <strong>{$CONST.THEY_DONT}</strong></span>

            <ol>
                <li>{$CONST.CREATE_DATABASE}{if $install_DB} <strong>{$CONST.DONE}</strong>{/if}</li>
                <li>{$CONST.CREATING_PRIMARY_AUTHOR|sprintf:"{$smarty.post.user|escape}"}{if $add_authors} <strong>{$CONST.DONE}</strong>{/if}</li>
                <li>{$CONST.SETTING_DEFAULT_TEMPLATE}{if $set_template_vars} <strong>{$CONST.DONE}</strong>{/if}</li>
                <li>{$CONST.INSTALLING_DEFAULT_PLUGINS}{if $register_default_plugins} <strong>{$CONST.DONE}</strong>{/if}</li>
            </ol>
{/if}
            <h3>{$CONST.ATTEMPT_WRITE_FILE|sprintf:'.htaccess'}</h3>
{if $errors_sif === true}
            <span class="msg_success"><svg class="bi bi-check-circle-fill" width="16" height="16" role="img" aria-label="OK:"><title>{$CONST.DONE}</title><use xlink:href="#check-circle-fill"></use></svg> {$CONST.DONE}</span>
{else}
            <h4>{$CONST.FAILED}</h4>

            <ul class="plainList">
{foreach $errors_sif AS $error_f}
                <li><span class="msg_error list"><svg class="bi bi-exclamation-triangle-fill" width="16" height="16" role="img" aria-label="Error:"><title>{$CONST.ERROR}</title><use xlink:href="#exclamation-triangle-fill"></use></svg> {$error_f}</span></li>
{/foreach}
            </ul>
            <div>
                <a class="button_link" href="serendipity_admin.php?serendipity[step]={$prevstep}">{$CONST.PREVIOUS_PAGE}</a>
            </div>
{/if}
{if $s9y_installed}
            <h3>{$CONST.INSTALLER_COMPLETE}</h3>
            <span class="msg_success"><svg class="bi bi-check-circle-fill" width="16" height="16" role="img" aria-label="OK:"><title>{$CONST.DONE}</title><use xlink:href="#check-circle-fill"></use></svg> {$CONST.SERENDIPITY_INSTALLED}</span>

            <p><strong>{$CONST.THANK_YOU_FOR_CHOOSING}</strong></p>

            <a class="button_link state_submit" href="{$smarty.post.serendipityHTTPPath}">{$CONST.VISIT_BLOG_HERE}</a>
{else}
            <span class="msg_error"><svg class="bi bi-exclamation-triangle-fill" width="16" height="16" role="img" aria-label="Error:"><title>{$CONST.ERROR}</title><use xlink:href="#exclamation-triangle-fill"></use></svg> {$CONST.ERROR_DETECTED_IN_INSTALL}</span>
{/if}
{* end of step 3 *}
{/if}
        </div>
    </main>

    <footer id="meta">
        <p>{$CONST.ADMIN_FOOTER_POWERED_BY|sprintf:$styxversion:$phpversion}</p>
    </footer>
{if $s9yGETstep != 0 AND NOT isset($s9y_installed)}

    <script>
        // toggle info containers
        var hasinfo = document.querySelectorAll(".has_info");
        for (i=0; i < hasinfo.length; i++) {
          const xpnd = hasinfo[i];
          const btns = xpnd.querySelector('.toggle_info.button_link');
          btns.innerHTML = '<svg class="bi bi-info-circle" width="16" height="16" role="img" aria-label="Info:"><title>{$CONST.MORE}</title><use xlink:href="#info-circle"/></svg>';
          const href = btns.dataset.href;
          btns.addEventListener('click', (e) => ((target) => {
            xpnd.classList.toggle('info_expanded');
            btns.classList.toggle('active');
            let it_toggle = document.querySelector(target);
                it_toggle.classList.toggle('additional_info');
          })(href));
        }
    </script>
{/if}

    <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="info-circle" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
      </symbol>
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>
</body>
</html>