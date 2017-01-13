<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IPackage;


class DestinationAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        return $package->getDestination();
    }
}