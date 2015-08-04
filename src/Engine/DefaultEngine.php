<?php

namespace Spin\Template\Engine;

class DefaultEngine implements Engine
{
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
        if (!is_file($file)) {
            throw new \Exception('Could not find template file '.$file);
        }

        $closure = function ($_spinTemplateFile, $_spinTemplateVars) {
            extract($_spinTemplateVars);
            ob_start();
            include $_spinTemplateFile;

            return ob_get_clean();
        };

        $closure = $closure->bindTo(null);

        return $closure($file, $vars);
    }
}
