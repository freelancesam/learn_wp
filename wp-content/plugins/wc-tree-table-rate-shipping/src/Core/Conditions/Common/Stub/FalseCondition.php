<?php
namespace Trs\Core\Conditions\Common\Stub;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\ICondition;


class FalseCondition extends ClassNameAware implements ICondition
{
    public function isSatisfiedBy($value)
    {
        return false;
    }
}