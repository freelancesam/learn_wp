<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IPackage;


class CountAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        return count($package->getItems());
    }
}