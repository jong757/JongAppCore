<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb722603324d1323e84f0f2bc190cdd39
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Jongapp\\Core\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Jongapp\\Core\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb722603324d1323e84f0f2bc190cdd39::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb722603324d1323e84f0f2bc190cdd39::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb722603324d1323e84f0f2bc190cdd39::$classMap;

        }, null, ClassLoader::class);
    }
}
