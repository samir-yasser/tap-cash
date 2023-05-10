<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbdda2e5c14eaae08bca0ac183db09127
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

    public static $prefixesPsr0 = array (
        'M' => 
        array (
            'Monolog' => 
            array (
                0 => __DIR__ . '/..' . '/monolog/monolog/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbdda2e5c14eaae08bca0ac183db09127::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbdda2e5c14eaae08bca0ac183db09127::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitbdda2e5c14eaae08bca0ac183db09127::$prefixesPsr0;
            $loader->classMap = ComposerStaticInitbdda2e5c14eaae08bca0ac183db09127::$classMap;

        }, null, ClassLoader::class);
    }
}