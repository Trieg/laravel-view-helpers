<?php

/**
 * This file is part of the Laravel View Helpers package.
 *
 * @website http://cubes.rs/
 * @license https://mit-license.org MIT License
 * @author  Djordje Stojiljkovic <djordje.stojiljkovic@cubes.rs>
 *
 */
namespace Cubes\View\Helpers\Registry\Holders;


interface HolderInterface
{

    /**
     * Method defineStackHolder used to build Blade Stack holder for injecting Assets.
     *
     * @param  string  - name of stack holder
     * @return mixed
     */
    public function defineStackHolder($name);

}