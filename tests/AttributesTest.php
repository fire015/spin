<?php

use Spin\Template\Template;

class AttributesTest extends PHPUnit_Framework_TestCase
{
    public function testAttributeManipulation()
    {
        $template = new TemplateStub(['weather' => 'hot']);
        $template->name = 'foo';
        $template['age'] = 90;
        $this->assertEquals('foo', $template->name);
        $this->assertEquals('hot', $template->weather);
        $this->assertEquals('90', $template->age);
        $this->assertTrue(isset($template->name));
        unset($template->name);
        unset($template['age']);
        $this->assertFalse(isset($template->name));
        $this->assertFalse(isset($template->blah));
        $this->assertFalse(isset($template['age']));
        $this->assertNull($template->blah);
        $this->assertNull($template['blah']);
    }

    public function testAttributeMutation()
    {
        $template = new TemplateStub();
        $template->list_items = ['name' => 'taylor'];
        $this->assertEquals(['name' => 'taylor'], $template->list_items);
        $attributes = $template->getAttributes();
        $this->assertEquals(json_encode(['name' => 'taylor']), $attributes['list_items']);
    }

    public function testAttributeArray()
    {
        $template = new TemplateStub();
        $template->name = 'foo';
        $template->list_items = ['name' => 'taylor'];
        $array = $template->toArray();
        $this->assertTrue(is_array($array));
        $this->assertEquals('foo', $array['name']);
        $this->assertEquals('taylor', $array['list_items']['name']);
    }
}

class TemplateStub extends Template
{
    public function getListItemsAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setListItemsAttribute($value)
    {
        $this->attributes['list_items'] = json_encode($value);
    }

    public function getNothingAttribute($value)
    {
        //
    }
}
