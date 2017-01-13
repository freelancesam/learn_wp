<?php
namespace Trs\Core\Conditions\Common\Logic;

use Trs\Core\Conditions\Common\GroupCondition;


class AndCondition extends GroupCondition
{
    public function isSatisfiedBy($value)
    {
        foreach ($this->conditions as $condition) {
            if (!$condition->isSatisfiedBy($value)) {
                return false;
            }
        }

        return true;
    }
}