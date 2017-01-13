<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IPackage;
use Trs\Core\Model\Price;


class PriceAttribute extends AbstractAttribute
{
    public function __construct($flags = Price::BASE)
    {
        $this->flags = $flags;
    }

    public function getValue(IPackage $package)
    {
        return $package->getPrice($this->flags);
    }

    private $flags;
}