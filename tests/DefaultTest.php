<?php

use Spin\Template\Template;

class DefaultTest extends PHPUnit_Framework_TestCase
{
    public function testEngine()
    {
        $template = new DefaultTemplateStub();
        $template->name = 'john';

        $this->assertEquals('hello john', $template->render());
        $this->assertInstanceOf('Spin\Template\Engine\DefaultEngine', $template->getEngine());
    }

    /**
     * @expectedException Exception
     */
    public function testEngineException()
    {
        $template = new DefaultTemplateStubException();
        $template->name = 'john';
        $template->render();
    }
}

class DefaultTemplateStub extends Template
{
    public function __construct()
    {
        $this->file = __DIR__.'/templates/test.default';
    }
}

class DefaultTemplateStubException extends Template
{
    protected $file = '/x/y/z.does.not.exist';
}
