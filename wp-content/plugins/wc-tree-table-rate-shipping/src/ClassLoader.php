<?php
namespace Trs;


class ClassLoader
{
    public function setup(PluginMeta $pluginMeta)
    {
        /** @var \Composer\AutoloadPsr4\ClassLoader $autoloader */
        $autoloader = include($pluginMeta->getLibsPath('autoload.php'));

        // Main sources
        
        $autoloader->addPsr4('Trs\\', $pluginMeta->getSrcPath());

        
        // tree_table_rate alias class
        $autoloader->addClassMap(array('tree_table_rate' => $pluginMeta->getPath('tree_table_rate.php')));

        // Migrations
        
        $migrationsPath = $pluginMeta->getMigrationsPath();

        $autoloader->addPsr4('Trs\\Migration\\', "{$migrationsPath}/inc");

        spl_autoload_register(function($class) use($migrationsPath) {
            if (preg_match('/Trs\\\\Migration\\\\Migration((_\d+)+)$/', $class, $matches)) {
                require($migrationsPath.'/'.str_replace('_', '.', ltrim($matches[1], '_')).'.php');
            }
        });
    }
    
    
}