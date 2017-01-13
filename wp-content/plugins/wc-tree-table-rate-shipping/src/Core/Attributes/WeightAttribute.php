<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IPackage;


class WeightAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        return $package->getWeight();
    }
}