<?php
namespace Trs\Core\Calculators;

use Exception;
use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IGrouping;
use Trs\Core\Interfaces\IOperation;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Processing\RateRegister;
use Trs\Core\Processing\Registers;


class RuleCalculator implements ICalculator
{
    public function __construct(IOperation $operation, IGrouping $grouping)
    {
        if ($this->operationMayProduceMultipleRates($operation) && $grouping->multiplePackagesExpected()) {
            self::throwAmbiguityError();
        }

        $this->operation = $operation;
        $this->grouping = $grouping;
    }

    public function calculateRatesFor(IPackage $package)
    {
        $subPackageRateSets = array();

        foreach ($package->split($this->grouping) as $subPackage) {
            $registers = new Registers();
            $this->operation->process($registers, $subPackage);
            $subPackageRateSets[] = $registers->rates;
        }

        if (count($subPackageRateSets) > 1) {

            $rate = null;

            foreach ($subPackageRateSets as $rates) {

                if (count($rates) != 1) {
                    if ($rates) {
                        self::throwAmbiguityError();
                    } else {
                        continue;
                    }
                }

                if (!isset($rate)) {
                    $rate = new RateRegister();
                }

                $rate->add(reset($rates));
            }

            $subPackageRateSets = array(isset($rate) ? array($rate) : array());
        }

        if (!($rates = reset($subPackageRateSets))) {
            $rates = array();
        }

        return $rates;
    }

    public function multipleRatesExpected()
    {
        return
            !$this->grouping->multiplePackagesExpected() &&
            $this->operationMayProduceMultipleRates($this->operation);
    }

    private $operation;
    private $grouping;

    private static function throwAmbiguityError()
    {
        throw new Exception('Cannot aggregate multiple rates for multiple packages');
    }

    private static function operationMayProduceMultipleRates(IOperation $operation)
    {
        return !in_array(
            $operation->getType(),
            array(IOperation::MODIFIER, IOperation::AGGREGATOR)
        );
    }
}