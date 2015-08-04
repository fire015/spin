<?php

namespace Spin\Template\Engine;

interface Engine
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
