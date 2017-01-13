<?php
namespace Trs\Migration\Interfaces;


interface IMigration
{
    function migrate(array &$config);
}