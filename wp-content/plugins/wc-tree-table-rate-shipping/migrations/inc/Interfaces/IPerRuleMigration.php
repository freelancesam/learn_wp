<?php
namespace Trs\Migration\Interfaces;


interface IPerRuleMigration
{
    function migrateRule(array &$rule);
}