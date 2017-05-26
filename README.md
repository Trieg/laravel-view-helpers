<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center"> <b>View Helpers</b> </p>
========================

[![GitHub Version](https://img.shields.io/github/tag/robinradic/blade-extensions.svg?style=flat-square&label=version)](http://badge.fury.io/gh/cubes-doo/%2Flaravel-view-helpers)
[![License](http://img.shields.io/badge/license-MIT-ff69b4.svg?style=flat-square)](https://mit-license.org/)
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
php artisan make:helper {HelperName} {--dummy=true}
```
* { HelperName } &nbsp; - Name of View Helper
* { --dummy=true } - Generate dummy methods withing newly created helper

Command will create directory and Helper for you.


## Notes
If you experience permission error while using commands make sure you grant permissions
for Helpers directory.

