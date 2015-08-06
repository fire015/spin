<?php

namespace Spin\Template\Engine;

interface EngineInterface
{
    /**
     * Render the template.
     *
     * @param string $file
     * @param array  $vars
     *
     * @return string
     */
    public function render($file, array $vars);
}
