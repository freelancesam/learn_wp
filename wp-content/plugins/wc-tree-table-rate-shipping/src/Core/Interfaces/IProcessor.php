<?php
namespace Trs\Core\Interfaces;

use Traversable;


interface IProcessor
{
    /**
     * @param Traversable|IRule[] $rules
     * @param IPackage $package
     * @return IRate[]
     */
    public function process($rules, IPackage $package);
}