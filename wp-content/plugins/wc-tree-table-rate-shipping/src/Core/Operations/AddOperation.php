<?php
namespace Trs\Core\Operations;

use Exception;
use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Rate;
use Trs\Core\Processing\RateRegister;
use Trs\Core\Processing\Registers;


class AddOperation extends AbstractOperation
{
    public function __construct(ICalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    public function process(Registers $registers, IPackage $package)
    {
        $newRates = isset($this->calculator) ? $this->calculator->calculateRatesFor($package) : array();
        if (!$newRates) {
            return;
        }

        if (count($registers->rates) > 1 && count($newRates) > 1) {
            throw new Exception("Adding up two rate sets is not supported due to ambiguity");
        }

        $registersRates = $registers->rates;
        if (!$registersRates) {
            $registersRates = array(new Rate(0));
        }

        $newRegistersRates = array();
        foreach ($registersRates as $rate1) {
            foreach ($newRates as $rate2) {
                $rate = new RateRegister($rate1->getCost(), $rate1->getTitle());
                $rate->add($rate2);
                $newRegistersRates[] = $rate;
            }
        }

        $registers->rates = $newRegistersRates;
    }

    public function getType()
    {
        return $this->calculator->multipleRatesExpected() ? self::OTHER : self::MODIFIER;
    }

    public function canOperateOnMultipleRates()
    {
        return !$this->calculator->multipleRatesExpected();
    }

    private $calculator;
}