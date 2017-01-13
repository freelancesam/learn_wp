<?php
namespace Trs\Core\Operations;

use InvalidArgumentException;
use Trs\Common\Range;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Processing\Registers;


class ClampOperation extends AbstractOperation
{
    public function __construct(Range $range)
    {
        if (!isset($range)) {
            throw new InvalidArgumentException();
        }

        $this->range = $range;
    }

    public function process(Registers $registers, IPackage $package)
    {
        foreach ($registers->rates as $rate) {
            $rate->cost = $this->range->clamp($rate->cost);
        }
    }

    public function getType()
    {
        return self::MODIFIER;
    }

    private $range;
}