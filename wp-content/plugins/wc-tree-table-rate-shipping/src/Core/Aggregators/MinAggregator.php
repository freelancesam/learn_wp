<?php
namespace Trs\Core\Aggregators;

use Trs\Core\Interfaces\IRate;


class MinAggregator extends ReduceAggregator
{
    protected function reduce(IRate $carry = null, IRate $current)
    {
        if (!isset($carry) || $carry->getCost() > $current->getCost()) {
            $carry = $current;
        }

        return $carry;
    }
}