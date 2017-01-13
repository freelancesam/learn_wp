<?php
namespace Trs\Migration\Interfaces;


interface IConfigMigration
{
    function migrateConfig(array &$config);
}