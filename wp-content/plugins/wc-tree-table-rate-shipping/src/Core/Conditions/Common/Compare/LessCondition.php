<?php
namespace Trs\Core\Conditions\Common\Compare;


class LessCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return !$this->equals($value) && $value < $this->compareWith;
    }
}