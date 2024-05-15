<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit3c77ed7099b3246f8e9fec43d6df0479
{
    public static $prefixLengthsPsr4 = array (
        'D' => 
        array (
            'DASPRiD\\Enum\\' => 13,
        ),
        'B' => 
        array (
            'BaconQrCode\\' => 12,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'DASPRiD\\Enum\\' => 
        array (
            0 => __DIR__ . '/..' . '/dasprid/enum/src',
        ),
        'BaconQrCode\\' => 
        array (
            0 => __DIR__ . '/..' . '/bacon/bacon-qr-code/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit3c77ed7099b3246f8e9fec43d6df0479::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit3c77ed7099b3246f8e9fec43d6df0479::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit3c77ed7099b3246f8e9fec43d6df0479::$classMap;

        }, null, ClassLoader::class);
    }
}
