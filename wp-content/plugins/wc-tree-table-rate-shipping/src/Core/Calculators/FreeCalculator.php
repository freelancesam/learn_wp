<?php
namespace Trs\Core\Calculators;

use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Rate;


class FreeCalculator implements ICalculator
{
    public function calculateRatesFor(IPackage $package)
    {
        return array(new Rate(0));
    }

    public function multipleRatesExpected()
    {
        return false;
    }
}