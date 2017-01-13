<?php
namespace Trs\Core\Aggregators;

use Trs\Core\Interfaces\IRate;
use Trs\Core\Processing\RateRegister;


class SumAggregator extends ReduceAggregator
{
    protected function reduce(IRate $carry = null, IRate $current)
    {
        if (!isset($carry)) {
            $carry = new RateRegister();
        }

        $carry->add($current);

        return $carry;
    }
}