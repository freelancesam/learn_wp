<?php
namespace Trs\Core\Conditions\Common\Enum;


class DisjointCondition extends AbstractEnumCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->intersect($value, $this->other) == 0;
    }
}