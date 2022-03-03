<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2f56059095d55056f48c0c294f79e0ce
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2f56059095d55056f48c0c294f79e0ce::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2f56059095d55056f48c0c294f79e0ce::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit2f56059095d55056f48c0c294f79e0ce::$classMap;

        }, null, ClassLoader::class);
    }
}
