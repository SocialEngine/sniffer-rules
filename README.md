# Sniffer-Rules (SoicalEngine Laravel Package)
Detect violations of a defined coding standard. It helps your code remains clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, **PEAR**, **Squiz**, **PHPCS** and **SocialEngine**.

Note: This package only works for Laravel 5! If you're using this in a Laravel 4 project, the last compatible tag was
[1.0.3](https://github.com/SocialEngine/sniffer-rules/tree/1.0.3)

## Quick start

### Required setup

In the `require` key of `composer.json` file add the following

    "socialengine/sniffer-rules": "2.0.*"

before the `scripts` key of `composer.json` file add the following
    
    "repositories": [
          {
              "type": "vcs",
              "url": "https://github.com/SocialEngine/sniffer-rules.git"
          }
    ],

Run the Composer update command

    $ composer update

In your `config/app.php` add `'Socialengine\SnifferRules\SnifferRulesServiceProvider'` to the end of the `$providers` array

    'providers' => [

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Socialengine\SnifferRules\SnifferRulesServiceProvider',

    ],

Publish the configuration file:

    `php artisan vendor:publish`

Edit the configuration file `config/sniffer-rules.php` to tweak the sniffer behavior.

### Usage

    `php artisan sniff`
    
To run the sniffer in a CI environment, the -n option should be set:

    `php artisan sniff`

### Coding standards

* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

#### Addendum and Clarifications

* `namespace` should be on the same line as opening php tag. e.g.: `<?php namespace SocialEngine\Amazing`
* Property names should be camelCase
* Test names should use underscores, not camelCase. e.g.: `test_cats_love_catnip`
