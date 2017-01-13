<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IPackage;


abstract class SumAttribute extends MapAttribute
{
    public function getValue(IPackage $package)
    {
        return array_sum(parent::getValue($package));
    }
}