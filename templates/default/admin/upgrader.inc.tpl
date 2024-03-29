<!DOCTYPE html>
<html class="no-js" lang="{$lang}">
<head>
    <meta charset="{$CONST.LANG_CHARSET}">
    <title>{$CONST.SERENDIPITY_ADMIN_SUITE}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{$head_link_stylesheet}" type="text/css">
    <script src="{serendipity_getFile file='admin/js/modernizr.min.js'}"></script>
    <script src="{$serendipityHTTPPath}{$templatePath}jquery.js"></script>
    <script src="{serendipity_getFile file="admin/js/plugins.js"}"></script>
    <script src="{serendipity_getFile file='admin/serendipity_styx.js'}"></script>
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
{if $get.action == 'upgrade'}
{foreach $call_tasks AS $ctask}
{if $is_callable_task}
            <span class="msg_hint upgrade_task"><span class="icon-ok-circled" aria-hidden="true"></span> {$ctask|default:''}</span>
{/if}
{/foreach}
{if !empty($errors)}
            <h2>{$CONST.DIAGNOSTIC_ERROR}</h2>

            <div class="msg_error">
{foreach $errors AS $implode_err}
                <p><span class="icon-attention-circled" aria-hidden="true"></span> {$implode_err}</p>
{/foreach}
            </div>
{/if}
{/if}

{if (($showAbort AND $get.action == 'ignore') OR $get.action == 'upgrade')}
{if $get.action == 'ignore'}
            <span class="msg_notice upgrade_done"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.SERENDIPITY_UPGRADER_YOU_HAVE_IGNORED}</span>
{elseif $get.action == 'upgrade'}
            <span class="msg_success upgrade_done"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.SERENDIPITY_UPGRADER_NOW_UPGRADED|sprintf:$s9y_version}</span>
{/if}
{if $return_here}
{$print_UPGRADER_RETURN_HERE|replace:'?serendipity[action]=upgrade':''}
{* could also be used as:   {$CONST.SERENDIPITY_UPGRADER_RETURN_HERE|sprintf:"<a href='$serendipityHTTPPath'>":'</a>'} *}
{/if}
{else}
{* hey - this replace does not work for [fa], [ko] languages while translated - but since not an absolute need it might be bearable. *}
            <h2>{$CONST.SERENDIPITY_UPGRADER_WELCOME|replace:'Serendipity':'Serendipity Styx'}</h2>

            <p>{$CONST.SERENDIPITY_UPGRADER_PURPOSE|sprintf:$s9y_version_installed}</p>

            <p>{$CONST.SERENDIPITY_UPGRADER_WHY|sprintf:"<b>Styx $s9y_version</b>"}</p>

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
                <span class="msg_notice"><span class="icon-info-circled" aria-hidden="true"></span> {$CONST.PROBLEM_PERMISSIONS_HOWTO|sprintf:'chmod 1777'}</span>
{/if}
{if $errorCount > 0}
                <span class="msg_error"><span class="icon-ok-circled" aria-hidden="true"></span> {$CONST.PROBLEM_DIAGNOSTIC}</span>

                <a class="icon_link block_level" href="serendipity_admin.php"><span class="icon-help-circled" aria-hidden="true"></span> {$CONST.RECHECK_INSTALLATION}</a>
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

            <div class="msg_success"><span class="icon-ok-circled"></span> {$CONST.SERENDIPITY_UPGRADER_CONSIDER_DONE}</div>
            <div><a class="button_link state_submit" href="{$upgradeLoc}">{$CONST.SERENDIPITY_UPGRADER_RETURN_HERE|sprintf:'':''}</a></div>
{/if}
{/if}
{/if}
        </div>
    </main>
</body>
</html>