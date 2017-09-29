# phpcs 2.0+ Laravel 5, Lumen 5 Command
[![Build Status](https://travis-ci.org/SocialEngine/sniffer-rules.svg?branch=master)](https://travis-ci.org/SocialEngine/sniffer-rules)
[![Latest Stable Version](https://poser.pugx.org/SocialEngine/sniffer-rules/version.png)](https://packagist.org/packages/SocialEngine/sniffer-rules)
[![License](https://poser.pugx.org/SocialEngine/sniffer-rules/license.svg)](https://packagist.org/packages/SocialEngine/sniffer-rules)

This is a [Laravel](http://laravel.com/) 5 package that hooks up 
[SquizLabs CodeSniffer 2](https://github.com/squizlabs/PHP_CodeSniffer) 
into Laravel-based apps. It can also be used manually, so read on.

Detect violations of a defined coding standard. It helps your code remain 
clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, 
**PEAR**, **Squiz**, **PHPCS** and **SocialEngine**.

### Setup

Require this package in composer:

```
$ composer require socialengine/sniffer-rules
```

#### Laravel 5

In your `config/app.php` add `'SocialEngine\SnifferRules\ServiceProvider'` 
to `$providers` array:

```php
'providers' => [
    'Illuminate\Foundation\Providers\ArtisanServiceProvider',
    'Illuminate\Auth\AuthServiceProvider',
    
    'SocialEngine\SnifferRules\ServiceProvider',

],
```
#### Laravel 5: Publish the configuration file

```bash
$ php artisan vendor:publish
```

#### Manual

Install our _Standard_ by configuring **PHP_CodeSniffer** to look for it. 

```bash
$ php ./vendor/bin/phpcs --config-set installed_paths ./vendor/socialengine/src/Socialengine/SnifferRules/Standard/
```

### Usage
#### Laravel
```bash
$ php artisan sniff
```
    
To run the sniffer in a CI environment, the `-n` option should be set to remove
interaction:

```
$ php artisan sniff -n
```

#### Manual

```bash
$ php ./vendor/bin/phpcs --standard=SocialEngine path/to/code 
```

It's encouraged to add a [`Makefile`](Makefile) to your project that makes it 
trivial for other developers. Use `Makefile` in this directory and adjust as 
needed to fit your project requirements.

### Travis

In combination with the [`Makefile`](Makefile), Travis has issues finding the
standard, we had to add a `before_script` to make it work. See 
[Unum](https://github.com/SocialEngine/Unum) repo for example.

```yml
before_script: php ./vendor/bin/phpcs --config-set installed_paths "`pwd`/vendor/socialengine/sniffer-rules/src/SocialEngine/SnifferRules/Standard/"
```

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
