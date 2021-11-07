<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitcc1a0d2d4b28c975120ee1afa5932353
{
    public static $files = array (
        '2df68f9e79c919e2d88506611769ed2e' => __DIR__ . '/..' . '/respect/stringifier/src/stringify.php',
        '0e6d7bf4a5811bfa5cf40c5ccd6fae6a' => __DIR__ . '/..' . '/symfony/polyfill-mbstring/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Whoops\\' => 7,
        ),
        'S' => 
        array (
            'Symfony\\Polyfill\\Mbstring\\' => 26,
            'SRL\\' => 4,
        ),
        'R' => 
        array (
            'Respect\\Validation\\' => 19,
            'Respect\\Stringifier\\' => 20,
        ),
        'P' => 
        array (
            'Psr\\SimpleCache\\' => 16,
            'Psr\\Log\\' => 8,
            'Psr\\Cache\\' => 10,
            'Phpfastcache\\Tests\\' => 19,
            'Phpfastcache\\' => 13,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'M' => 
        array (
            'Monolog\\' => 8,
            'MathPHP\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Whoops\\' => 
        array (
            0 => __DIR__ . '/..' . '/filp/whoops/src/Whoops',
        ),
        'Symfony\\Polyfill\\Mbstring\\' => 
        array (
            0 => __DIR__ . '/..' . '/symfony/polyfill-mbstring',
        ),
        'SRL\\' => 
        array (
            0 => __DIR__ . '/..' . '/simpleregex/srl-php/src',
        ),
        'Respect\\Validation\\' => 
        array (
            0 => __DIR__ . '/..' . '/respect/validation/library',
        ),
        'Respect\\Stringifier\\' => 
        array (
            0 => __DIR__ . '/..' . '/respect/stringifier/src',
        ),
        'Psr\\SimpleCache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/simple-cache/src',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/src',
        ),
        'Psr\\Cache\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/cache/src',
        ),
        'Phpfastcache\\Tests\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpfastcache/phpfastcache/tests/lib',
        ),
        'Phpfastcache\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpfastcache/phpfastcache/lib/Phpfastcache',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Monolog\\' => 
        array (
            0 => __DIR__ . '/..' . '/monolog/monolog/src/Monolog',
        ),
        'MathPHP\\' => 
        array (
            0 => __DIR__ . '/..' . '/markrogoyski/math-php/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'U' => 
        array (
            'Ubench' => 
            array (
                0 => __DIR__ . '/..' . '/devster/ubench/src',
            ),
        ),
        'P' => 
        array (
            'Phpml' => 
            array (
                0 => __DIR__ . '/..' . '/php-ai/php-ml/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitcc1a0d2d4b28c975120ee1afa5932353::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitcc1a0d2d4b28c975120ee1afa5932353::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitcc1a0d2d4b28c975120ee1afa5932353::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitcc1a0d2d4b28c975120ee1afa5932353::$classMap;

        }, null, ClassLoader::class);
    }
}
