<?php

return array(

    /*
    |--------------------------------------------------------------------------
    | Standard
    |--------------------------------------------------------------------------
    |
    | One or more coding standard do check for violations.
    | Available options are: SocialEngine, PEAR, Squiz, PHPCS, MySource, PSR2 and PSR1
    |
    */
    'standard' => array(
        'SocialEngine',
    ),

    /*
    |--------------------------------------------------------------------------
    | Files to watch
    |--------------------------------------------------------------------------
    |
    | One or more files and/or directories to check
    |
    */
    'files' => [
        'app/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Files to ignore
    |--------------------------------------------------------------------------
    |
    | Sometimes you want Sniffer to run over a very large number of files,
    | but you want some files and folders to be skipped. The ignored config key
    | can be used to tell Sniffer to skip files and folders that match one
    | or more patterns.
    |
    | Ex: 'ignored' => array('*blade.php', 'app/database', 'app/lang'),
    |
    */
    'ignored' => [
        'app/lang',
        'app/views',
        'app/database'
    ],
);
