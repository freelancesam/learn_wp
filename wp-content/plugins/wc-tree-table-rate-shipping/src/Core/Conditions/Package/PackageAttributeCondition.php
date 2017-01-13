<?php
namespace Trs\Core\Conditions\Package;

use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\ICondition;
use Trs\Core\Interfaces\IPackage;


class PackageAttributeCondition extends AbstractPackageCondition
{
    public function __construct(ICondition $condition, IAttribute $attribute)
    {
        $this->condition = $condition;
        $this->attribute = $attribute;
    }

    public function isSatisfiedByPackage(IPackage $package)
    {
        return $this->condition->isSatisfiedBy($this->attribute->getValue($package));
    }

    private $condition;
    private $attribute;
}