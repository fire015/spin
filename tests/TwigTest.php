<?php

use Spin\Template\Template;
use Spin\Template\Engine\TwigEngine;

class TwigTest extends PHPUnit_Framework_TestCase
{
    public function testEngine()
    {
        if (!class_exists('Twig_Environment')) {
            $this->markTestSkipped('Twig engine is not installed');
        }

        $loader = new Twig_Loader_Filesystem(__DIR__.'/templates');
        $twig = new Twig_Environment($loader);

        $template = new TwigTemplateStub();
        $template->setEngine(new TwigEngine($twig));
        $template->name = 'john';

        $this->assertEquals('hello john', $template->render());
    }
}

class TwigTemplateStub extends Template
{
    protected $file = 'test.twig';
}
