<?php
namespace Trs\Core\Operations;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IOperation;


abstract class AbstractOperation extends ClassNameAware implements IOperation
{
    public function getType()
    {
        return self::OTHER;
    }

    public function canOperateOnMultipleRates()
    {
        return true;
    }
}