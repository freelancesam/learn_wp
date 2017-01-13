<?php
namespace Trs\Core\Conditions\Common\Stub;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\ICondition;


class TrueCondition extends ClassNameAware implements ICondition
{
    public function isSatisfiedBy($value)
    {
        return true;
    }
}