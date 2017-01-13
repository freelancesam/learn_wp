<?php
namespace Trs\Core\Conditions\Common\Compare;


class NotEqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return !$this->equals($value);
    }
}