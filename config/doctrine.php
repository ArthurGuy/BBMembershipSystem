<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Development state
    |--------------------------------------------------------------------------
    |
    | If set to false, metadata caching will become active
    |
    */
    'dev'                       => config('app.debug'),
    /*
    |--------------------------------------------------------------------------
    | Entity Mangers
    |--------------------------------------------------------------------------
    |
    */
    'managers'                  => [
        'default' => [
            'dev'        => env('APP_DEBUG'),
            'meta'       => env('DOCTRINE_METADATA', 'annotations'),
            'connection' => env('DB_CONNECTION', 'mysql'),
            'namespaces' => [
                'BB'
            ],
            'paths'      => [
                base_path('app')
            ],
            'repository' => Doctrine\ORM\EntityRepository::class,
            'proxies'    => [
                'namespace'     => false,
                'path'          => storage_path('proxies'),
                'auto_generate' => env('DOCTRINE_PROXY_AUTOGENERATE', false)
            ],
            /*
            |--------------------------------------------------------------------------
            | Doctrine events
            |--------------------------------------------------------------------------
            |
            | If you want to use the Doctrine Extensions from Gedmo,
            | you'll have to set this setting to true.
            |
            | The listener array expects the key to be a Doctrine event
            | e.g. Doctrine\ORM\Events::onFlush
            |
            */
            'events'     => [
                'listeners'   => [],
                'subscribers' => []
            ],
            'filters' => []
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine Extensions
    |--------------------------------------------------------------------------
    |
    | Enable/disable Doctrine Extensions by adding or removing them from the list
    |
    | If you want to require custom extensions you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'extensions'                => [
        //LaravelDoctrine\ORM\Extensions\TablePrefix\TablePrefixExtension::class,
        //LaravelDoctrine\Extensions\Timestamps\TimestampableExtension::class,
        //LaravelDoctrine\Extensions\SoftDeletes\SoftDeleteableExtension::class,
        //LaravelDoctrine\Extensions\Sluggable\SluggableExtension::class,
        //LaravelDoctrine\Extensions\Sortable\SortableExtension::class,
        //LaravelDoctrine\Extensions\Tree\TreeExtension::class,
        //LaravelDoctrine\Extensions\Loggable\LoggableExtension::class,
        //LaravelDoctrine\Extensions\Blameable\BlameableExtension::class,
        //LaravelDoctrine\Extensions\IpTraceable\IpTraceableExtension::class,
        //LaravelDoctrine\Extensions\Translatable\TranslatableExtension::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom types
    |--------------------------------------------------------------------------
    */
    'custom_types'              => [
        'json' => LaravelDoctrine\ORM\Types\Json::class,
        //'enum' => Doctrine\DBAL\Types\StringType::class,
        //'CarbonDate'       => DoctrineExtensions\Types\CarbonDateType::class,
        'CarbonDateTime'   => DoctrineExtensions\Types\CarbonDateTimeType::class,
        //'CarbonDateTimeTz' => DoctrineExtensions\Types\CarbonDateTimeTzType::class,
        //'CarbonTime'       => DoctrineExtensions\Types\CarbonTimeType::class,
        'jsonPhotoCollection' => \BB\Domain\JsonPhotoCollectionDoctrineType::class,
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom datetime functions
    |--------------------------------------------------------------------------
    */
    'custom_datetime_functions' => [
        //'DATEADD'  => DoctrineExtensions\Query\Mysql\DateAdd::class,
        //'DATEDIFF' => DoctrineExtensions\Query\Mysql\DateDiff::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Doctrine custom numeric functions
    |--------------------------------------------------------------------------
    */
    'custom_numeric_functions'  => [
        //'ACOS'    => DoctrineExtensions\Query\Mysql\Acos::class,
        //'ASIN'    => DoctrineExtensions\Query\Mysql\Asin::class,
        //'ATAN'    => DoctrineExtensions\Query\Mysql\Atan::class,
        //'ATAN2'   => DoctrineExtensions\Query\Mysql\Atan2::class,
        //'COS'     => DoctrineExtensions\Query\Mysql\Cos::class,
        //'COT'     => DoctrineExtensions\Query\Mysql\Cot::class,
        //'DEGREES' => DoctrineExtensions\Query\Mysql\Degrees::class,
        //'RADIANS' => DoctrineExtensions\Query\Mysql\Radians::class,
        //'SIN'     => DoctrineExtensions\Query\Mysql\Sin::class,
        //'TAN'     => DoctrineExtensions\Query\Mysql\Ta::class
    ],
    /*
    |--------------------------------------------------------------------------
    | DQL custom string functions
    |--------------------------------------------------------------------------
    */
    'custom_string_functions'   => [
        //'CHAR_LENGTH' => DoctrineExtensions\Query\Mysql\CharLength::class,
        //'CONCAT_WS'   => DoctrineExtensions\Query\Mysql\ConcatWs::class,
        //'FIELD'       => DoctrineExtensions\Query\Mysql\Field::class,
        //'FIND_IN_SET' => DoctrineExtensions\Query\Mysql\FindInSet::class,
        //'REPLACE'     => DoctrineExtensions\Query\Mysql\Replace::class,
        //'SOUNDEX'     => DoctrineExtensions\Query\Mysql\Soundex::class,
        //'STR_TO_DATE' => DoctrineExtensions\Query\Mysql\StrToDat::class
    ],
    /*
    |--------------------------------------------------------------------------
    | Enable query logging with laravel file logging,
    | debugbar, clockwork or an own implementation.
    | Setting it to false, will disable logging
    |
    | Available:
    | - LaravelDoctrine\ORM\Loggers\LaravelDebugbarLogger
    | - LaravelDoctrine\ORM\Loggers\ClockworkLogger
    | - LaravelDoctrine\ORM\Loggers\FileLogger
    |--------------------------------------------------------------------------
    */
    'logger'                    => env('DOCTRINE_LOGGER', false),
    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    |
    | Configure meta-data, query and result caching here.
    | Optionally you can enable second level caching.
    |
    | Available: acp|array|file|memcached|redis
    |
    */
    'cache'                     => [
        'default'                => env('DOCTRINE_CACHE', 'array'),
        'namespace'              => null,
        'second_level'           => false,
    ],
    /*
    |--------------------------------------------------------------------------
    | Gedmo extensions
    |--------------------------------------------------------------------------
    |
    | Settings for Gedmo extensions
    | If you want to use this you will have to require
    | laravel-doctrine/extensions in your composer.json
    |
    */
    'gedmo'                     => [
        'all_mappings' => false
    ]
];
