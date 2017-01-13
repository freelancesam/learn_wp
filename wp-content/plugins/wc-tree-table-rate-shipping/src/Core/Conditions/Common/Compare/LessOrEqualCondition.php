<?php
namespace Trs\Core\Conditions\Common\Compare;

use Trs\Core\Conditions\Common\AggregateCondition;
use Trs\Core\Conditions\Common\Logic\OrCondition;


class LessOrEqualCondition extends CompareCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->equals($value) || $value < $this->compareWith;
    }
}