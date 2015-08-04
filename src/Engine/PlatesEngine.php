<?php

namespace Spin\Template\Engine;

use League\Plates\Engine as LeaguePlatesEngine;

class PlatesEngine implements Engine
{
    /**
     * The template engine.
     *
     * @var LeaguePlatesEngine
     */
    protected $engine;

    /**
     * Constructor.
     *
     * @param LeaguePlatesEngine $engine
     */
    public function __construct(LeaguePlatesEngine $engine)
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
        return $this->engine->render($file, $vars);
    }
}
