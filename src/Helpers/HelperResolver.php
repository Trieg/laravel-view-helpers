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
    protected $isDirectiveKey = 'isDirective';
    protected $isDirectiveVal = ['true', 'false'];

    /**
     * Facade method resolve used to register helpers in app container.
     *
     * @return HelperResolver
     */
    public static function resolve()
    {
        return (new self())->getHelpers();
    }

    /**
     * Method make is used to register helper methods to as global closures/blade directives.
     *
     * @param  $helperFile
     * @return void
     */
    protected function make($helperFile)
    {
        $reflectedObject = $this->getReflectedObject($helperFile);
        foreach ($reflectedObject->getMethods() as $helperMethod) {

            if (!$this->isDirective($helperMethod)) {

                // Register as global view callback
                $helperFunction = function(...$params) use ($helperMethod) {
                    $helperClassName  = $helperMethod->class;
                    $helperMethodName = $helperMethod->getName();
                    if ($helperMethod->isStatic()) {
                        return call_user_func("$helperClassName::{$helperMethodName}", $params);
                    }

                    return (new $helperClassName)->{$helperMethodName}(...$params);
                };

                // Share closures to View
                View::share($helperMethod->getName(), $helperFunction);

            } else {

                // Register blade directive to app container
                Blade::directive($helperMethod->getName(), function(...$params) use($helperMethod) {
                    $helperClassName  = $helperMethod->class;
                    $helperMethodName = $helperMethod->getName();
                    return (new $helperClassName)->{$helperMethodName}(...$params);
                });

            }

        }
    }

    /**
     * Method isDirective checks for passed method to be registered
     * as Blade directive or as default global closure.
     *
     * @param  \Reflection  $helperMethod
     * @return boolean      $status
     * @throws \Exception
     */
    protected function isDirective($helperMethod)
    {
        $directiveDocBlock = $helperMethod->getDocComment();
        if (!$directiveDocBlock) {
            return true;
        }

        preg_match_all('#@(.*?)\n#s', $directiveDocBlock, $annotationResult);
        if (empty($annotationResult[0]) || empty($annotationResult)) {
            return true;
        }

        $isDirectiveBlock = $annotationResult[1][0];
        if (strpos($isDirectiveBlock, $this->isDirectiveKey) !== false) {
            $isDirectiveValue = preg_replace(
                '/\s+/', '',    str_replace($this->isDirectiveKey, '', $isDirectiveBlock)
            );

            if (empty($isDirectiveValue) || $isDirectiveValue == '') {
                throw new \Exception('You must provide boolean value for isDirective annotation block');
            }

            if (!in_array($isDirectiveValue, $this->isDirectiveVal)) {
                throw new \Exception(
                    'You can just provide "true" or "false" values for isDirective annotation block, ' .
                    'but you provided ' . $isDirectiveValue
                );
            }

            // We break trough exceptions, now return status
            return ($isDirectiveValue == $this->isDirectiveVal[0]) ? true : false;
        }

        return true;
    }

    /**
     * Method getHelpers returns helper path and register new blade-directive/closure.
     *
     * @return HelperResolver
     */
    protected function getHelpers()
    {
        $helpersDirectories = [
            glob(__DIR__ . '/Registry/*.php'),
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
     * Methods getClassFromFile returns FQN.
     *
     * @param  $filePath
     * @return mixed|string
     */
    protected function getClassFromFile($filePath)
    {
        //Grab the contents of the file
        $contents  = file_get_contents($filePath);
        $namespace = $class = "";

        //Set helper values to know that we have found the namespace/class token and need to collect the string values after them
        $getting_namespace = $getting_class = false;

        //Go through each token and evaluate it as necessary
        foreach (token_get_all($contents) as $token) {


            //If this token is the namespace declaring, then flag that the next tokens will be the namespace name
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            //If this token is the class declaring, then flag that the next tokens will be the class name
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            //While we're grabbing the namespace name...
            if ($getting_namespace === true) {

                //If the token is a string or the namespace separator...
                if(is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {

                    //Append the token's value to the name of the namespace
                    $namespace .= $token[1];

                }
                else if ($token === ';') {

                    //If the token is the semicolon, then we're done with the namespace declaration
                    $getting_namespace = false;

                }
            }

            //While we're grabbing the class name...
            if ($getting_class === true) {

                //If the token is a string, it's the name of the class
                if(is_array($token) && $token[0] == T_STRING) {

                    //Store the token's value as the class name
                    $class = $token[1];

                    //Got what we need, stope here
                    break;
                }
            }

        }

        //Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;
    }

    /**
     * Method getReflectedObject returns reflector of provided class.
     *
     * @param  $helperFile
     * @return \ReflectionClass $reflectedObject
     */
    protected function getReflectedObject($helperFile)
    {
        if (file_exists($helperFile)) {
            $helperClass = $this->getClassFromFile($helperFile);
            include_once($helperFile);
            if (class_exists($helperClass)) {
                $reflectedObject = new \ReflectionClass($helperClass);
                return $reflectedObject;
            }
        }
    }
}