<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace App\Helpers;


class Placeholder
{

    /**
     * Call this method in blade with this syntax: @directiveMethod();
     * You can also pass arguments but they will be interpreted as strings
     *
     * @return string
     */
    public function directiveMethod()
    {
        return 'Some html output.';
    }

    /**
     * Call this helper method in blade with this syntax: {{ $regularMethod($myName) }}
     * You must provide isDirective annotation block to false
     * if you want to pass objects or arrays to the same from view.
     *
     * @isDirective false
     */
    public function regularMethod($name)
    {
        return ucfirst($name);
    }

    /**
     * Call this static helper method in blade with this syntax:
     *  {{ App\Helpers\Message::staticMethod($myName) }}
     *
     * @param  $name
     * @return string
     */
    public static function staticMethod($name)
    {
        return ucfirst($name);
    }

}