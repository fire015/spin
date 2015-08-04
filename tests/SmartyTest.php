<?php

use Spin\Template\Template;
use Spin\Template\Engine\SmartyEngine;

class SmartyTest extends PHPUnit_Framework_TestCase
{
    public function testEngine()
    {
        if (!class_exists('Smarty')) {
            $this->markTestSkipped('Smarty engine is not installed');
        }

        $compileDir = __DIR__.'/templates_c';

        if (!is_dir($compileDir) && !mkdir($compileDir)) {
            $this->fail('Could not create compile directory '.$compileDir);
        }

        $smarty = new Smarty();
        $smarty->setTemplateDir(__DIR__.'/templates');
        $smarty->setCompileDir($compileDir);

        $template = new SmartyTemplateStub();
        $template->setEngine(new SmartyEngine($smarty));
        $template->name = 'john';

        $this->assertEquals('hello john', $template->render());

        $smarty->clearCompiledTemplate();
        rmdir($compileDir);
    }
}

class SmartyTemplateStub extends Template
{
    protected $file = 'test.smarty';
}
