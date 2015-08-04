<?php

use Spin\Template\Template;
use Spin\Template\Engine\PlatesEngine;

class PlatesTest extends PHPUnit_Framework_TestCase
{
    public function testEngine()
    {
        if (!class_exists('League\Plates\Engine')) {
            $this->markTestSkipped('Plates engine is not installed');
        }

        $plates = new League\Plates\Engine(__DIR__.'/templates', 'plates');

        $template = new PlatesTemplateStub();
        $template->setEngine(new PlatesEngine($plates));
        $template->name = 'john';

        $this->assertEquals('hello john', $template->render());
    }
}

class PlatesTemplateStub extends Template
{
    protected $file = 'test';
}
