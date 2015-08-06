<?php

namespace Spin\Template\Engine;

use Dwoo\Core;

class DwooEngine implements EngineInterface
{
    /**
     * The template engine.
     *
     * @var Core
     */
    protected $engine;

    /**
     * Constructor.
     *
     * @param Core $engine
     */
    public function __construct(Core $engine)
    {
        $this->engine = $engine;
    }

    /**
     * Render the template.
     *
     * @param string $file
     * @param array  $vars
     *
     * @return string
     */
    public function render($file, array $vars)
    {
        return $this->engine->get($file, $vars);
    }
}
