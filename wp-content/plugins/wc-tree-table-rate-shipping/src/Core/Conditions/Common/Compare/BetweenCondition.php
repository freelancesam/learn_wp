<?php
namespace Trs\Core\Conditions\Common\Compare;

use Trs\Common\ClassNameAware;
use Trs\Common\Interfaces\IComparator;
use Trs\Common\Range;
use Trs\Core\Conditions\Common\AbstractCondition;
use Trs\Core\Conditions\Common\AggregateCondition;
use Trs\Core\Conditions\Common\Logic\AndCondition;
use Trs\Core\Conditions\Common\Logic\OrCondition;
use Trs\Core\Interfaces\ICondition;


class BetweenCondition extends AbstractCondition
{
    public function __construct(Range $range, IComparator $comparator)
    {
        $this->range = $range;
        $this->comparator = $comparator;
    }

    public function isSatisfiedBy($value)
    {
        $min = $this->range->getMin();
        $max = $this->range->getMax();

        return
            (!isset($min) || $this->comparator->equals($value, $min) || $value > $min) &&
            (!isset($max) || $this->comparator->equals($value, $max) || $value < $max);
    }

    private $range;
    private $comparator;
}