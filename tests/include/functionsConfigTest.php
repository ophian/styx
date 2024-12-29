<?php

$serendipity['dbType'] = 'pdo-sqlite';
define('IN_serendipity', true);
require_once dirname(__FILE__) . '/../../include/functions_config.inc.php';

use PHPUnit\Framework\Attributes\Test;

/**
 * Class functionsTest
 */
class functionsConfigTest extends PHPUnit\Framework\TestCase
{
    #[Test]
    public function test_serendipity_getTemplateFile()
    {
        global $serendipity;
        $serendipity['template'] = 'b53';
        $serendipity['template_engine'] = 'boot';
        $serendipity['defaultTemplate'] = 'pure';
        $serendipity['templatePath'] = '/templates/';
        $serendipity['template_backend'] = 'styx';
        $serendipity['serendipityPath'] = realpath('../');
        $serendipity['serendipityHTTPPath'] = realpath('/');

        $this->assertStringEndsWith('b53/index.tpl', serendipity_getTemplateFile('index.tpl'));
        define('IN_serendipity_admin', true);
        $this->assertStringEndsWith('styx/admin/index.tpl', serendipity_getTemplateFile('admin/index.tpl'));
        $this->assertStringEndsWith('b53/index.tpl', serendipity_getTemplateFile('index.tpl', 'serendipityHTTPPath', true));
    }

}
