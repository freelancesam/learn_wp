<?php
namespace Trs\Core\Aggregators;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IAggregator;


class FirstAggregator extends ClassNameAware implements IAggregator
{
    public function aggregateRates(array $rates)
    {
        return $rates ? reset($rates) : null;
    }
}