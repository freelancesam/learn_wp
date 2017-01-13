<?php
namespace Trs\Core\Aggregators;

use Trs\Common\ClassNameAware;
use Trs\Core\Interfaces\IAggregator;
use Trs\Core\Model\Rate;


class AverageAggregator extends ClassNameAware implements IAggregator
{
    public function __construct()
    {
        $this->sum = new SumAggregator();
    }

    public function aggregateRates(array $rates)
    {
        $result = $this->sum->aggregateRates($rates);
        if (isset($result)) {
            $result = new Rate($result->getCost() / count($rates), $result->getTitle());
        }

        return $result;
    }

    private $sum;
}