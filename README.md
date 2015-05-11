# phpcs 2.0+ Laravel 4/5 Command

This is a [Laravel](http://laravel.com/) 4/5 package that hooks up 
[SquizLabs CodeSniffer 2.0](https://github.com/squizlabs/PHP_CodeSniffer) 
into Laravel-based apps.

Detect violations of a defined coding standard. It helps your code remain 
clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, 
**PEAR**, **Squiz**, **PHPCS** and **SocialEngine**.

Note: This is a hybrid package that works with both Laravel 4 and 5.

The only limitation for L4 is that you have to create your config manually.

### Setup

Require this package in composer:

```
$ composer require socialengine/sniffer-rules
```

In your `config/app.php` add `'SocialEngine\SnifferRules\ServiceProvider'` 
to `$providers` array:
```php
'providers' => [
    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    
    'SocialEngine\SnifferRules\ServiceProvider',

],
```
**Laravel 5**: Publish the configuration file:

    $ php artisan vendor:publish

**Laravel 4**: Manually create `app/config/sniffer-rules.php` by copying 
[config](src/SocialEngine/SnifferRules/config/config.php)

Edit configuration file `config/sniffer-rules.php` to tweak the sniffer behavior.

### Usage

    $ php artisan sniff
    
To run the sniffer in a CI environment, the `-n` option should be set to remove
interaction:

    $ php artisan sniff -n

## SocialEngine Coding Standards

### Coding standards

* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

#### Addendum and Clarifications

* `namespace` should be on the same line as opening php tag. e.g.: `<?php namespace SocialEngine\Amazing`
* Property names should be camelCase
* Test names should use underscores, not camelCase. e.g.: `test_cats_love_catnip`

## License

MIT
