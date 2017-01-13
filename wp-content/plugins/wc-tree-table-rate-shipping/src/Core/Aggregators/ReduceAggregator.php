<?php
namespace Trs\Core\Aggregators;


use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IAggregator;
use Trs\Core\Interfaces\IRate;
use Trs\Core\Model\Rate;
use Trs\Core\Processing\RateRegister;


abstract class ReduceAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        $rate = $this->_reduce($rates);

        if ($rate instanceof RateRegister) {
            $rate = new Rate($rate->cost, $rate->title);
        }

        return $rate;
    }

    /**
     * @param IRate $carry
     * @param IRate $current
     * @return IRate
     */
    protected abstract function reduce(IRate $carry = null, IRate $current);

    private function _reduce(array $rates)
    {
        $carry = null;
        foreach ($rates as $rate) {
            $carry = $this->reduce($carry, $rate);
        }

        return $carry;
    }
}