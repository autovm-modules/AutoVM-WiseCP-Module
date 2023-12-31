<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit4399fc73ab01e5cf61178570a9b99bb5
{
    public static $files = array (
        'b41be569c48b1947114fd50c1b47bf80' => __DIR__ . '/..' . '/persgeek/request/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PG\\Request\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PG\\Request\\' => 
        array (
            0 => __DIR__ . '/..' . '/persgeek/request/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit4399fc73ab01e5cf61178570a9b99bb5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit4399fc73ab01e5cf61178570a9b99bb5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit4399fc73ab01e5cf61178570a9b99bb5::$classMap;

        }, null, ClassLoader::class);
    }
}
