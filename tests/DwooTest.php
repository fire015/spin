<?php

use Spin\Template\Template;
use Spin\Template\Engine\DwooEngine;

class DwooTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group dwoo
     */
    public function testEngine()
    {
        if (!class_exists('Dwoo\Core')) {
            $this->markTestSkipped('Dwoo engine is not installed');
        }

        $compileDir = __DIR__.'/templates_c';

        if (!is_dir($compileDir) && !mkdir($compileDir)) {
            $this->fail('Could not create compile directory '.$compileDir);
        }

        $dwoo = new Dwoo\Core();
        $dwoo->setTemplateDir(__DIR__.'/templates');
        $dwoo->setCompileDir($compileDir);

        $template = new DwooTemplateStub();
        $template->setEngine(new DwooEngine($dwoo));
        $template->name = 'john';

        $this->assertEquals('hello john', $template->render());

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($compileDir, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
            $todo($fileinfo->getRealPath());
        }

        rmdir($compileDir);
    }
}

class DwooTemplateStub extends Template
{
    protected $file = 'test.dwoo';
}
