<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Helpers;

use View;
use Illuminate\Support\Facades\Blade;

class HelperResolver
{

    /**
     * Facade method resolve used to register directives/helpers through app lifecycle.
     * TODO: Serialization of helpers and caching
     *
     * @return HelperResolver
     */
    public static function resolve()
    {
        return (new self())->getHelpers();
    }

    /**
     * Method make is used to register helper methods to as global blade directives.
     *
     * @param  $helperFile
     * @return void
     */
    protected function make($helperFile)
    {
        $reflectedObject = $this->getReflectedObject($helperFile);
        foreach ($reflectedObject->getMethods() as $helperMethod) {

            // Register blade directive through whole application lifecycle
            Blade::directive($helperMethod->getName(), function($expression)
                use($helperMethod)
            {
                // Return stringified method calling
                return $this->stringify($helperMethod, $expression);
            });

        }
    }

    /**
     * Method getHelpers returns helper path and register new blade-directive.
     *
     * @return HelperResolver
     */
    protected function getHelpers()
    {
        $helpersDirectories = [
            glob(__DIR__ . '/Factory/*.php'),
            glob(app_path() . '/' . $this->getHelpersDirectory() . '/*.php')
        ];

        foreach (array_merge($helpersDirectories[0], $helpersDirectories[1]) as $helperFile) {
            $this->make($helperFile);
        }
    }

    /**
     * Methods getHelpersDirectory returns defined helpers directory in app config.
     * Can be overridden within default app config.
     *
     * @return string
     */
    protected function getHelpersDirectory()
    {
        return config('helpers.directory', 'Helpers');
    }

    /**
     * Methods getClassFromFile returns FQN based on file content.
     *
     * @param  $filePath
     * @return mixed|string
     */
    protected function getClassFromFile($filePath)
    {
        //Grab the contents of the file
        $contents  = file_get_contents($filePath);
        $namespace = $class = "";

        // Set helper values to know that we have found the namespace/class
        // token and need to collect the string values after them
        $getting_namespace = $getting_class = false;

        // Go through each token and evaluate it as necessary
        foreach (token_get_all($contents) as $token) {

            // If this token is the namespace declaring, then flag that
            // the next tokens will be the namespace name
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            // If this token is the class declaring, then flag that
            // the next tokens will be the class name
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            // While we're grabbing the namespace name...
            if ($getting_namespace === true) {

                // If the token is a string or the namespace separator...
                if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {
                    // Append the token's value to the name of the namespace
                    $namespace .= $token[1];
                } elseif ($token === ';') {
                    // If the token is the semicolon, then we're done with the namespace declaration
                    $getting_namespace = false;
                }

            }

            // While we're grabbing the class name...
            if ($getting_class === true) {

                // If the token is a string, it's the name of the class
                if(is_array($token) && $token[0] == T_STRING) {
                    // Store the token's value as the class name
                    $class = $token[1];
                    // Got what we need, stop here
                    break;
                }
            }
        }

        // Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;
    }

    /**
     * Method getReflectedObject returns reflection of provided class.
     *
     * @param  $helperFile
     * @return \ReflectionClass $reflectedObject
     */
    protected function getReflectedObject($helperFile)
    {
        if (file_exists($helperFile)) {

            // Try to find FQN in file and then we can include it
            $helperClass = $this->getClassFromFile($helperFile);
            include_once($helperFile);

            // If class exists we will initialize it with Reflector
            // And return Reflected object so we can work with methods, names...
            if (class_exists($helperClass)) {
                $reflectedObject = new \ReflectionClass($helperClass);
                return $reflectedObject;
            }

        }
    }

    /**
     * Method stringify used to convert class and method calling toString as plain php.
     *
     * @param  $helperMethod
     * @param  $expression
     * @return string
     */
    protected function stringify($helperMethod, $expression)
    {
        //TODO: Check if helperMethod is instanceof Reflection and everything exist
        $helperClassName  = $helperMethod->class;
        $helperMethodName = $helperMethod->getName();

        if ($helperMethod->isStatic()) {
            // Return static method calling as plain php
            return "<?php $helperMethod->class::{$helperMethod->getName}({$expression}); ?>";
        }

        // Return normal instance and method calling as plain php
        return "<?php (new $helperClassName)->{$helperMethodName}({$expression}); ?>";
    }
}