<?php
namespace Trs\Core\Calculators;

use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Rate;


class AttributeMultiplierCalculator implements ICalculator
{
    public function __construct(IAttribute $attribute, $multiplier = 1)
    {
        $this->attribute = $attribute;
        $this->multiplier = $multiplier;
    }

    public function calculateRatesFor(IPackage $package)
    {
        return array(new Rate($this->attribute->getValue($package) * $this->multiplier));
    }

    public function multipleRatesExpected()
    {
        return false;
    }

    private $attribute;
    private $multiplier;
}