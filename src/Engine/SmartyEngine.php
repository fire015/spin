<?php

namespace Spin\Template\Engine;

use Smarty;

class SmartyEngine implements EngineInterface
{
    /**
     * The template engine.
     *
     * @var Smarty
     */
    protected $engine;

    /**
     * Constructor.
     *
     * @param Smarty $engine
     */
    public function __construct(Smarty $engine)
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
        foreach ($vars as $key => $value) {
            $this->engine->assign($key, $value);
        }

        return $this->engine->fetch($file);
    }
}
