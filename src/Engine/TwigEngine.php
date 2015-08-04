<?php

namespace Spin\Template\Engine;

use Twig_Environment;

class TwigEngine implements Engine
{
    /**
     * The template engine.
     *
     * @var Twig_Environment
     */
    protected $engine;

    /**
     * Constructor.
     *
     * @param Twig_Environment $engine
     */
    public function __construct(Twig_Environment $engine)
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
