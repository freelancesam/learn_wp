<?php
namespace Trs\Core\Conditions\Common\Logic;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\ICondition;


class NotCondition extends ClassNameAware implements ICondition
{
    public function __construct(ICondition $condition)
    {
        $this->condition = $condition;
    }

    public function isSatisfiedBy($value)
    {
        return !$this->condition->isSatisfiedBy($value);
    }

    private $condition;
}