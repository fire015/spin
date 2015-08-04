<?php

namespace Spin\Template;

use ArrayAccess;
use Illuminate\Support\Str;

abstract class Template implements ArrayAccess
{
    /**
     * The template file.
     *
     * @var string
     */
    protected $file;

    /**
     * The template engine.
     *
     * @var mixed
     */
    protected $engine;

    /**
     * The template's attributes.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static $mutatorCache = [];

    /**
     * Create a new template instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Fill the template with an array of attributes.
     *
     * @param array $attributes
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Set the template engine.
     *
     * @param Engine $engine
     */
    public function setEngine(Engine\Engine $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Render the template.
     *
     * @return string
     */
    public function render()
    {
        if (is_null($this->engine)) {
            $engine = new Engine\DefaultEngine();
        } else {
            $engine = $this->engine;
        }

        return $engine->render($this->file, $this->toArray());
    }

    /**
     * Convert the template instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = $this->getAttributes();
        $mutatedAttributes = $this->getMutatedAttributes();

        foreach ($mutatedAttributes as $key) {
            if (!array_key_exists($key, $attributes)) {
                continue;
            }

            $attributes[$key] = $this->mutateAttribute($key, $attributes[$key]);
        }

        return $attributes;
    }

    /**
     * Get all of the current attributes on the template.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Get an attribute from the template.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        if (array_key_exists($key, $this->attributes)) {
            $value = $this->attributes[$key];

            if ($this->hasGetMutator($key)) {
                return $this->mutateAttribute($key, $value);
            }

            return $value;
        }
    }

    /**
     * Set a given attribute on the template.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setAttribute($key, $value)
    {
        if ($this->hasSetMutator($key)) {
            $method = 'set'.Str::studly($key).'Attribute';

            return $this->{$method}($value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function hasGetMutator($key)
    {
        return method_exists($this, 'get'.Str::studly($key).'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        return $this->{'get'.Str::studly($key).'Attribute'}($value);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     *
     * @return bool
     */
    protected function hasSetMutator($key)
    {
        return method_exists($this, 'set'.Str::studly($key).'Attribute');
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    protected function getMutatedAttributes()
    {
        $class = get_class($this);

        if (!isset(static::$mutatorCache[$class])) {
            static::cacheMutatedAttributes($class);
        }

        return static::$mutatorCache[$class];
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param string $class
     */
    protected static function cacheMutatedAttributes($class)
    {
        $mutatedAttributes = [];

        foreach (get_class_methods($class) as $method) {
            if (strpos($method, 'Attribute') !== false && preg_match('/^get(.+)Attribute$/', $method, $matches)) {
                $matches[1] = Str::snake($matches[1]);
                $mutatedAttributes[] = lcfirst($matches[1]);
            }
        }

        static::$mutatorCache[$class] = $mutatedAttributes;
    }

    /**
     * Dynamically retrieve attributes on the template.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the template.
     *
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->setAttribute($key, $value);
    }

    /**
     * Determine if the given attribute exists.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }

    /**
     * Get the value for a given offset.
     *
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    /**
     * Set the value for a given offset.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    /**
     * Unset the value for a given offset.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }

    /**
     * Determine if an attribute exists on the template.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Unset an attribute on the template.
     *
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }
}
