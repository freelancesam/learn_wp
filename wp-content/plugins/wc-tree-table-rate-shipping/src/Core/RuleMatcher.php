<?php
namespace Trs\Core;

use Trs\Core\Interfaces\ICondition;
use Trs\Core\Interfaces\IMatcher;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Package;


class RuleMatcher implements IMatcher
{
    public function __construct(RuleMatcherMeta $meta, ICondition $condition)
    {
        $this->meta = $meta;
        $this->condition = $condition;
    }

    public function getMatchingPackage(IPackage $package)
    {
        $packages = $package->split($this->meta->grouping);

        $matchingPackages = array();
        foreach ($packages as $package) {
            if ($this->condition->isSatisfiedBy($package)) {
                $matchingPackages[] = $package;
            } else if ($this->meta->requireAllPackages) {
                return null;
            }
        }

        if (!$matchingPackages) {
            return null;
        }

        return Package::fromOther($matchingPackages, $package->getDestination(), $package->getCustomer());
    }

    public function isCapturingMatcher()
    {
        return $this->meta->capture;
    }

    private $meta;
    private $condition;
}