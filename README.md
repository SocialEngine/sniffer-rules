# Sniffer-Rules (SoicalEngine Laravel Package)
Detect violations of a defined coding standard. It helps your code remain 
clean and consistent. Available options are: **PSR2**, **PSR1**, **Zend**, 
**PEAR**, **Squiz**, **PHPCS** and **SocialEngine**.

Note: This is a hybrid package that works with both laravel 4 and 5.

The only limitation is that you have to create config manually

### Setup

In the `require` key of `composer.json` file add the following

    "socialengine/sniffer-rules": "2.1.*"

Run the Composer update command

    $ composer update

In your `config/app.php` add `'SocialEngine\SnifferRules\SnifferRulesServiceProvider'` 
to the end of the `$providers` array

    'providers' => [

        'Illuminate\Foundation\Providers\ArtisanServiceProvider',
        'Illuminate\Auth\AuthServiceProvider',
        ...
        'SocialEngine\SnifferRules\SnifferRulesServiceProvider',

    ],

**Laravel 5**:Publish the configuration file:

    $ php artisan vendor:publish

**Laravel 4**: Manually create `config/sniffer-rules.php` by copying 
[config](blob/master/src/Socialengine/SnifferRules/config/config.php)

Edit configuration file `config/sniffer-rules.php` to tweak the sniffer behavior.

### Usage

    php artisan sniff
    
To run the sniffer in a CI environment, the `-n` option should be set to remove
interaction:

    php artisan sniff -n

### Coding standards

* [PSR 2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
* [PSR 1 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
* [PSR 0 Coding Standards](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)

#### Addendum and Clarifications

* `namespace` should be on the same line as opening php tag. e.g.: `<?php namespace SocialEngine\Amazing`
* Property names should be camelCase
* Test names should use underscores, not camelCase. e.g.: `test_cats_love_catnip`
