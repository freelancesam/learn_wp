<?php
namespace Trs\Core\Processing;

use Trs\Common\Arrays;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Interfaces\IProcessor;
use Trs\Core\Interfaces\IRate;
use Trs\Core\Interfaces\IRule;
use Trs\Core\Model\Rate;


class Processor implements IProcessor
{
    public function process($rules, IPackage $package)
    {
        $allRates = array();

        foreach ($rules as $rule) {
            /** @var IRule $rule */

            $matcher = $rule->getMatcher();
            $matchingPackage = $matcher->getMatchingPackage($package);

            if (isset($matchingPackage)) {

                $rates = $rule->getCalculator()->calculateRatesFor($matchingPackage);
                $rates = $this->assignTitles($rule->getMeta()->getTitle(), $rates);
                $allRates = array_merge($allRates, $rates);

                if ($matcher->isCapturingMatcher()) {

                    $package = $package->exclude($matchingPackage);

                    if ($package->isEmpty()) {
                        break;
                    }
                }
            }
        }

        return $allRates;
    }

    private function assignTitles($title, $rates)
    {
        if ($title) {
            $rates = Arrays::map($rates, function (IRate $rate) use ($title) {
                if (!$rate->getTitle()) {
                    $rate = new Rate($rate->getCost(), $title);
                }
                return $rate;
            });
        }

        return $rates;
    }
}