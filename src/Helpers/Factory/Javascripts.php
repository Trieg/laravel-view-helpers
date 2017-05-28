<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Helpers\Factory;


use Cubes\View\Helpers\Helpers\Factory\Holders\ScriptHolder;

class Javascripts extends ScriptHolder
{

    /**
     * Javascripts Helper constructor.
     */
    public function __construct()
    {
        $this->defineStackHolder('javascript'); //TODO: get stack default name from config
    }

    /**
     * @isDirective true
     */
    public function javascript()
    {
        return $this->compileStack($this->stackHolder);
    }

    /**
     * javascripts Blade directive used to start script libraries and inline scripts capturing.
     *
     * @isDirective true
     * @return      string
     */
    public function javascripts()
    {
        return $this->compilePush($this->stackHolder);
    }

    /**
     * endjavascripts Blade directive used to end script libraries and inline scripts capturing.
     *
     * @isDirective true
     * @return      string
     */
    public function endjavascripts()
    {
        return $this->compileEndpush();
    }

    /**
     * includeJs Blade directive used to capture and normalize passed libraries as construct to directive and
     * to return valid script element outputs.
     *
     * @param  $expression
     * @return mixed
     */
    public function includeJs($expression)
    {
        $normalizedLibraries = $this->normalizer($expression);

        $output = '';
        if (strpos($normalizedLibraries, ',') !== false) {
            $libraries = explode(',', $normalizedLibraries);
            foreach ($libraries as $key => $library) {
                $output .= str_replace('@path',
                    trim(preg_replace('/\s*\([^)]*\)/', '', $library)),
                $this->scriptHolder) . PHP_EOL;
            }
        }

        return trim($output);
    }

    /**
     * Normalizer used to clean string from brackets, parentheses...
     *
     * @param  $expression
     * @return string
     */
    private function normalizer($expression)
    {
        return strtr(
            preg_replace('/\s+/', '', $expression),
            array('[' => '', ']' => '')
        );
    }

}