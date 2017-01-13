<?php
namespace Trs\Mapping\Interfaces;

use Traversable;
use Trs\Core\Interfaces\ICalculator;
use Trs\Core\Interfaces\IMatcher;


interface ILazyFactory
{
    /**
     * @param callable $loader
     * @param int $count
     * @param bool $readonly
     * @return array|Traversable
     */
    function lazyArray($loader, $count = null, $readonly = false);

    /**
     * @param callable $loader
     * @return ICalculator
     */
    function lazyCalculator($loader);

    /**
     * @param callable $loader
     * @return IMatcher
     */
    function lazyMatcher($loader);
}