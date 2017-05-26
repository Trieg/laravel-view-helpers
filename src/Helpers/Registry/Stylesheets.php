<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Registry;

use Cubes\View\Helpers\Registry\Holders\StyleHolder;

class Stylesheets extends StyleHolder
{
    /**
     * Stylesheets Helper constructor.
     */
    public function __construct()
    {
        $this->defineStackHolder('stylesheet'); //TODO: get stack default name from config
    }

    /**
     * @isDirective true
     */
    public function stylesheet()
    {
        return $this->compileStack($this->stackHolder);
    }

    /**
     * stylesheets Blade directive used to start script libraries and inline scripts capturing.
     *
     * @isDirective true
     * @return      string
     */
    public function stylesheets()
    {
        return $this->compilePush($this->stackHolder);
    }

    /**
     * endstylesheets Blade directive used to end script libraries and inline scripts capturing.
     *
     * @isDirective true
     * @return      string
     */
    public function endstylesheets()
    {
        return $this->compileEndpush();
    }

    /**
     * includeCss Blade directive used to capture and normalize passed libraries as construct to directive and
     * to return valid style element outputs.
     *
     * @param  $expression
     * @return mixed
     */
    public function includeCss($expression)
    {
        $normalizedLibraries = $this->normalizer($expression);

        $output = '';
        if (strpos($normalizedLibraries, ',') !== false) {
            $libraries = explode(',', $normalizedLibraries);
            foreach ($libraries as $key => $library) {
                $output .= str_replace('@path',
                        trim(preg_replace('/\s*\([^)]*\)/', '', $library)),
                        $this->styleHolder) . PHP_EOL;
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