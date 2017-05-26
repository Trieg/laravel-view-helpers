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
        View\Helpers\Registry\Javascripts::class,
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