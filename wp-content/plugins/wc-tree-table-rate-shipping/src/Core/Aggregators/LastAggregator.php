<?php
namespace Trs\Core\Aggregators;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IAggregator;


class LastAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        return $rates ? end($rates) : null;
    }
}