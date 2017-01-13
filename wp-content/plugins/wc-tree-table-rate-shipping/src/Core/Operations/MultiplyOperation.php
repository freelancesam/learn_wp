<?php
namespace Trs\Core\Operations;

use InvalidArgumentException;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Processing\Registers;


class MultiplyOperation extends AbstractOperation
{
    public function __construct($multiplier)
    {
        if (!is_numeric($multiplier)) {
            throw new InvalidArgumentException();
        }

        $this->multiplier = $multiplier;
    }

    public function process(Registers $registers, IPackage $package)
    {
        foreach ($registers->rates as $rate) {
            $rate->cost *= $this->multiplier;
        }
    }

    public function getType()
    {
        return self::MODIFIER;
    }

    private $multiplier;
}