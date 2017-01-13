<?php
namespace Trs\Core\Conditions\Common\Logic;

use Trs\Core\Conditions\Common\GroupCondition;


class OrCondition extends GroupCondition
{
    public function isSatisfiedBy($value)
    {
        foreach ($this->conditions as $condition) {
            if ($condition->isSatisfiedBy($value)) {
                return true;
            }
        }

        return false;
    }
}