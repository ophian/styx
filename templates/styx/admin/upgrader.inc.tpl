<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$CONST.LANG_CHARSET}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{serendipity_getFile file='admin/installer.min.css'}" type="text/css">
    <script>
        document.documentElement.className = 'js';
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-color-mode', 'dark');
            document.currentScript.insertAdjacentHTML('afterend', '<link id="dark-scheme-icon" rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}styx/sty.xd.png" type="image/x-icon">')
        } else {
            document.currentScript.insertAdjacentHTML('afterend', '<link id="light-scheme-icon" rel="shortcut icon" href="{$serendipityBaseURL}{$templatePath}styx/sty.x.png" type="image/x-icon">')
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
{* called tasks *}
{if $get.action == 'upgrade'}
{foreach $call_tasks AS $ctask}
{if $is_callable_task}
            <span class="msg_hint upgrade_task"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                  <path d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05"/>
                </svg> {$ctask|default:''}</span>
{/if}
{/foreach}
{if !empty($errors)}
            <h2>{$CONST.DIAGNOSTIC_ERROR}</h2>

            <div class="msg_error">
{foreach $errors AS $implode_err}
                <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                      <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z"/>
                    </svg> {$implode_err}</p>
{/foreach}
            </div>
{/if}
{/if}

{if (($showAbort AND $get.action == 'ignore') OR $get.action == 'upgrade')}
{if $get.action == 'ignore'}
            <span class="msg_notice upgrade_done"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                  <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                  <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                </svg> {$CONST.SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED}</span>
{elseif $get.action == 'upgrade'}
            <span class="msg_success upgrade_done"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg> {$CONST.SERENDIPITY_UPGRADER_NOW_UPGRADED|sprintf:$s9y_version}</span>
{/if}
{if $return_here}
{$print_UPGRADER_RETURN_HERE|replace:'?serendipity[action]=upgrade':''}
{* could also be used as:   {$CONST.SERENDIPITY_UPGRADER_RETURN_HERE|sprintf:"<a href='$serendipityHTTPPath'>":'</a>'} *}
{/if}
{else}
{* hey - this replace does not work for [fa], [ko] languages while translated - but since not an absolute need it might be bearable. *}
            <h2>{$CONST.SERENDIPITY_UPGRADER_WELCOME|replace:'Serendipity':'Serendipity Styx'}</h2>

            <p>{$CONST.SERENDIPITY_UPGRADER_PURPOSE|sprintf:$s9y_version_installed}</p>

            <p class="msg_hint">{$CONST.SERENDIPITY_UPGRADER_WHY|sprintf:"<b>Styx $s9y_version</b>"}</p>

            <h3>{$CONST.FIRST_WE_TAKE_A_LOOK}</h3>

            <div class="diagnose">
                {$result_diagnose}

{if isset($checksums) AND $checksums}
                <h4>{$CONST.INTEGRITY}</h4>

                <ul class="plainList">
{foreach $upgraderResultDiagnose1 AS $urd1}
                    <li>{$urd1}</li>
{/foreach}
                </ul>
{/if}
                <h4>{$CONST.PERMISSIONS}</h4>

                <dl class="upgrader_perms">
                    <dt>{$basedir}</dt>
{foreach $upgraderResultDiagnose2 AS $urd2}
                    <dd>{$urd2}</dd>
{/foreach}
                    <dt>{$basedir}{$CONST.PATH_SMARTY_COMPILE}</dt>
{foreach $upgraderResultDiagnose3 AS $urd3}
                    <dd>{$urd3}</dd>
{/foreach}
{if $isdir_uploadpath}
                    <dt>{$basedir}{$uploadHTTPPath}</dt>
{foreach $upgraderResultDiagnose4 AS $urd4}
                    <dd>{$urd4}</dd>
{/foreach}
{/if}
                </dl>
{if $showWritableNote}
                <span class="msg_notice"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                      <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/>
                    </svg> {$CONST.PROBLEM_PERMISSIONS_HOWTO|sprintf:'chmod 1777'}</span>
{/if}
{if $errorCount > 0}
                <span class="msg_error"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg> {$CONST.PROBLEM_DIAGNOSTIC}</span>

                <a class="icon_link block_level" href="serendipity_admin.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                      <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                      <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286m1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94"/>
                    </svg> {$CONST.RECHECK_INSTALLATION}</a>
{/if}
            </div>
{if $errorCount < 1}
{if isset($sqlfiles) AND count($sqlfiles) > 0}

            <h3>{$database_update_types}:</h3>

            <p>{$CONST.SERENDIPITY_UPGRADER_FOUND_SQL_FILES}:</p>
{if is_array($sqlfiles) AND !empty($sqlfiles)}
            <ul>
{foreach $sqlfiles AS $sqlfile}
                <li>{$sqlfile}</li>
{/foreach}
            </ul>
{/if}
{/if}

            <h3>{$CONST.SERENDIPITY_UPGRADER_VERSION_SPECIFIC}:</h3>
{if is_array($tasks) AND !empty($tasks)}
            <span class="msg_notice"><strong>Please, take your time to READ !! Open up details, and better make a full browser screenshot to re-read later on !</strong></span>
{/if}
{if is_array($tasks) AND !empty($tasks)}

            <dl class="upgrader_tasks">
{foreach $tasks AS $task}
                <dt>{$task.version} - {$task.title}</dt>
                <dd>{$task.desc|nl2br}</dd>
{/foreach}
            </dl>
{/if}
{if ($taskCount == 0)}

            <p>{$CONST.SERENDIPITY_UPGRADER_NO_VERSION_SPECIFIC}</p>
{/if}
{if $taskCount > 0 OR (isset($sqlfiles) AND count($sqlfiles) > 0)}

            <h3>{$CONST.SERENDIPITY_UPGRADER_PROCEED_QUESTION} ({$CONST.RECOMMENDED})</h3>

            <p><em>{$CONST.SERENDIPITY_UPGRADER_PROCEED_WITH_TASK}</em></p>

            <a class="button_link state_submit" href="{$upgradeLoc}">{$CONST.SERENDIPITY_UPGRADER_PROCEED_DOIT}</a>
{if $showAbort}
            <a class="button_link state_cancel" href="{$abortLoc}">{$CONST.SERENDIPITY_UPGRADER_PROCEED_ABORT}</a>
{/if}
{else}

            <p>{$CONST.SERENDIPITY_UPGRADER_NO_UPGRADES}</p>

            <div class="msg_success"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg> {$CONST.SERENDIPITY_UPGRADER_CONSIDER_DONE}</div>
            <div><a class="button_link state_submit" href="{$upgradeLoc}">{$CONST.SERENDIPITY_UPGRADER_RETURN_HERE|sprintf:'':''}</a></div>
{/if}
{/if}
{/if}
        </div>
    </main>
</body>
</html>