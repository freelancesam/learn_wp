<?php
namespace Trs\Core\Conditions\Common;

use Trs\Core\Interfaces\ICondition;


class AggregateCondition extends AbstractCondition
{
    public function isSatisfiedBy($value)
    {
        return $this->condition->isSatisfiedBy($value);
    }

    /** @var ICondition */
    protected $condition;
}