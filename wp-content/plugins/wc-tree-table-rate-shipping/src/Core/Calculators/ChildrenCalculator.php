<?php
namespace Trs\Core\Calculators;

use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IPackage;
use Trs\Core\Interfaces\IProcessor;


class ChildrenCalculator implements ICalculator
{
    public function __construct(IProcessor $processor, $children)
    {
        $this->processor = $processor;
        $this->children = $children;
    }

    public function calculateRatesFor(IPackage $package)
    {
        return $this->processor->process($this->children, $package);
    }

    public function multipleRatesExpected()
    {
        return !empty($this->children);
    }

    private $processor;
    private $children;
}