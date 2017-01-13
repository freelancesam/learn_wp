<?php
namespace Trs\Core\Aggregators;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IAggregator;


class EndAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        return $rates ? end($rates) : null;
    }
}