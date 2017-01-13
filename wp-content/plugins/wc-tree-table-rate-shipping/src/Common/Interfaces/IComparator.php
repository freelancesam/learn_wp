<?php
namespace Trs\Common\Interfaces;


interface IComparator
{
    /**
     * @mixed $a
     * @mixed $b
     * @return bool
     */
    function equals($a, $b);
}