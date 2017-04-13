<?php
namespace Trs\Core\Conditions\Common\Compare;


class LessOrEqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->equals($value) || $value < $this->compareWith;
    }
}