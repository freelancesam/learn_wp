<?php
namespace Trs\Core\Conditions\Common\Compare;

use Trs\Common\Interfaces\IComparator;
use Trs\Common\Range;
use Trs\Core\Conditions\Common\AbstractCondition;


class BetweenCondition extends AbstractCondition
{
    public function __construct(Range $range, IComparator $comparator)
    {
        $this->range = $range;
        $this->comparator = $comparator;
    }

    public function isSatisfiedBy($value)
    {
        $min = $this->range->min;
        $max = $this->range->max;

        return
            (!isset($min) || $this->comparator->equals($value, $min) || $value > $min) &&
            (!isset($max) || $this->comparator->equals($value, $max) || $value < $max);
    }

    private $range;
    private $comparator;
}