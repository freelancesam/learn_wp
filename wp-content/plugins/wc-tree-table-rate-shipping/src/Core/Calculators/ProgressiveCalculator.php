<?php
namespace Trs\Core\Calculators;

use InvalidArgumentException;
use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Rate;


class ProgressiveCalculator implements ICalculator
{
    public function __construct(IAttribute $attribute, $cost, $step = 0, $skip = 0)
    {
        if (!self::receive($cost) || !self::receive($step) || !self::receive($skip)) {
            throw new InvalidArgumentException("Invalid progressive rate '{$cost}/{$step}/{$skip}'");
        }

        $this->attribute = $attribute;
        $this->cost = $cost;
        $this->step = $step;
        $this->skip = $skip;
    }
    
    public function calculateRatesFor(IPackage $package)
    {
        $result = 0;

        $value = $this->attribute->getValue($package);

        if ($value > $this->skip) {

            $value -= $this->skip;

            if ($this->step == 0) {
                $result = $value * $this->cost;
            } else {
                $result = ceil(round($value / $this->step, 5)) * $this->cost;
            }
        }

        return array(new Rate($result));
    }

    public function multipleRatesExpected()
    {
        return false;
    }
    
    private $attribute;
    private $cost;
    private $step;
    private $skip;

    static private function receive(&$value)
    {
        if (!isset($value)) {
            $value = 0;
        }

        return is_numeric($value);
    }

}