<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center"> <b>View Helpers</b> </p>

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

