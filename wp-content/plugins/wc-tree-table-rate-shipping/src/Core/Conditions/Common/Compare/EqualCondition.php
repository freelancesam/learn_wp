<?php
namespace Trs\Core\Conditions\Common\Compare;


class EqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->equals($value);
    }
}