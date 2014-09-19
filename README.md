# Sniffer-Rules (SoicalEngine Laravel Package)
Detect violations of a defined coding standard. It helps your code remains clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, **PEAR**, **Squiz**, **PHPCS** and **SocialEngine**.


## Quick start

### Required setup

In the `require` key of `composer.json` file add the following

    "socialengine/sniffer-rules": "dev-master"

before the `scripts` key of `composer.json` file add the following
    
    "repositories": [
          {
              "type": "vcs",
              "url": "https://github.com/SocialEngine/sniffer-rules.git"
          }
    ],

Run the Composer update comand

    $ composer update

In your `config/app.php` add `'Socialengine\SnifferRules\SnifferRulesServiceProvider'` to the end of the `$providers` array

    'providers' => [

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'Socialengine\SnifferRules\SnifferRulesServiceProvider',

    ],

Publish the configuration file:

    php artisan config:publish socialengine/sniffer-rules

Edit the configuration file `app/config/packages/socialengine/sniffer-rules/config.php` to tweak the sniffer behavior.

### Usage

    php artisan sniff

### Coding standards

* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

#### Addendum and Clarifications

* `namespace` should be on the same line as opening php tag: `<?php namespace SocialEngine\Amazing`
* Property names should be camelCase
* Test names should use underscores, not camelCase. e.g.: `test_cats_love_catnip`