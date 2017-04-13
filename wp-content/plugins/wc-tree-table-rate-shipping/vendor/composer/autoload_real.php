<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb0e118804a249b69a9550b3d2e25d7e1
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\AutoloadPsr4\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitb0e118804a249b69a9550b3d2e25d7e1', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\AutoloadPsr4\ClassLoader();
        spl_autoload_unregister(array('ComposerAutoloaderInitb0e118804a249b69a9550b3d2e25d7e1', 'loadClassLoader'));

        $useStaticLoader = PHP_VERSION_ID >= 50600 && !defined('HHVM_VERSION');
        if ($useStaticLoader) {
            require_once __DIR__ . '/autoload_static.php';

            call_user_func(\Composer\Autoload\ComposerStaticInitb0e118804a249b69a9550b3d2e25d7e1::getInitializer($loader));
        } else {
            $map = require __DIR__ . '/autoload_namespaces.php';
            foreach ($map as $namespace => $path) {
                $loader->set($namespace, $path);
            }

            $map = require __DIR__ . '/autoload_psr4.php';
            foreach ($map as $namespace => $path) {
                $loader->setPsr4($namespace, $path);
            }

            $classMap = require __DIR__ . '/autoload_classmap.php';
            if ($classMap) {
                $loader->addClassMap($classMap);
            }
        }

        $loader->register(true);

        return $loader;
    }
}
