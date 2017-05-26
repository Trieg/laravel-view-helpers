<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center"> <b>View Helpers</b> </p>

<p align="center">
<a href="https://cubes.rs/"><img src="https://badge.fury.io/gh/cubes-doo%2Flaravel-view-helpers.svg" alt="Version"></a>
<a href="https://mit-license.org/"><img src="http://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square" alt="License"></a>
</p>

*Laravel view helpers package lets you use and create custom global view helpers and blade directives.*

## Installation
Pull package:
```php
composer require cubes-doo/laravel-view-helpers
```
Register service provider in config/app
```php
...
Cubes\View\Helpers\ViewHelperServiceProvider::class,
```

## Example
Create app/Helpers directory or run command:
```php
php artisan make:helper Hello {--dummy=true}
```
* { HelperName } &nbsp; - Name of View Helper
* { --dummy=true } - Generate dummy methods withing newly created helper

Command will create directory and Helper for you.

- Helper
```php
<?php

namespace App\Helpers;

class Hello
{
    public function sayHello()
    {
        return 'Hello world';
    }
    
    public function sayHelloTo($name)
    {
        return 'Hello ' . ucfirst($name);
    }
    
    public function sayHelloToPeople(array $people)
    {
        $helloMessages = '';
        foreach ($people as $person) {
            $helloMessages .= 'Hello ' . ucfirst($person->getName());
        }
    }
}
```
- In blade 
```blade 
@sayHello
// outputs: Hello world

@sayHelloTo('nick')
// outputs: Hello Nick

@sayHelloToPeople($peopleObject)
// outputs: Exception: Expected array of $people but got string

```
By default method is interpreted as <a href="https://zaengle.com/blog/exploring-laravels-custom-blade-directives" alt="Blade directive">Blade directive</a>.
<br> So you cannot pass data/objects/arrays from view to method. Everything 'll be interpreted as string. <br>

##### How can we call them helpers if they can not interact with regular data/objects/arrays ?
They can, and here's how:
```php
....
    
    /**
    *
    * @isDirective = false
    */
    public function sayHelloToPeople(array $people)
    {
        $helloMessages = '';
        foreach ($people as $person) {
            $helloMessages .= 'Hello ' . ucfirst($person->getName()) . PHP_EOL;
        }
    }

```
Define annotation param @isDirective to false
```blade 
   
   {{ $sayHelloToPeople($peopleObject) }}
   // outputs: Hello Nick, \n Hello George, \n Hello Sara
   
```

## Notes
If you experience permission error while using commands make sure you grant permissions
for Helpers directory.

