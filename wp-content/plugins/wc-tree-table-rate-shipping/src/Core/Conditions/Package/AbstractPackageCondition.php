<?php
namespace Trs\Core\Conditions\Package;

use Trs\Core\Conditions\Common\AbstractCondition;
use Trs\Core\Interfaces\IPackage;


abstract class AbstractPackageCondition extends AbstractCondition
{
    public function isSatisfiedBy($package)
    {
        return $this->isSatisfiedByPackage($package);
    }

    abstract protected function isSatisfiedByPackage(IPackage $package);
}