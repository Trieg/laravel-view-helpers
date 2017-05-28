<?php return
[

    /*
    |--------------------------------------------------------------------------
    | Package Helpers
    |--------------------------------------------------------------------------
    |
    | The package helpers listed here will be automatically loaded on the
    | boot of your application. These are predefined view/blade helpers.
    |
    */
    'package_helpers' => [

        Cubes\View\Helpers\Helpers\Factory\Javascripts::class,
        Cubes\View\Helpers\Helpers\Factory\Stylesheets::class,

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Helpers
    |--------------------------------------------------------------------------
    |
    | The custom helpers listed here will be automatically loaded on the
    | boot of your application. These are injected view/blade helpers while
    | resolving helpers directory and helpers object/methods with reflector.
    |
    */
    'custom_helpers'  => [],

    /*
    |--------------------------------------------------------------------------
    | Directory
    |--------------------------------------------------------------------------
    |
    | The default helpers directory. Feel free to override it within your app
    | config.
    |
    */
    'directory'       => 'Helpers'

];