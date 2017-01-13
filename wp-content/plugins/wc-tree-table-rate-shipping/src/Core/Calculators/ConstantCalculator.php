<?php
namespace Trs\Core\Calculators;

use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Rate;


class ConstantCalculator implements ICalculator
{
    public function __construct($cost)
    {
        $this->cost = $cost;
    }

    public function calculateRatesFor(IPackage $package)
    {
        return array(new Rate($this->cost));
    }

    public function multipleRatesExpected()
    {
        return false;
    }

    private $cost;
}
