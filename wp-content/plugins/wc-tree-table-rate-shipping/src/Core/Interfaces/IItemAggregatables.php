<?php
namespace Trs\Core\Interfaces;

use Trs\Core\Model\Price;


interface IItemAggregatables
{
    /**
     * @param int $flags
     * @return float
     */
    function getPrice($flags = Price::BASE);

    /**
     * @return float
     */
    function getWeight();
    
    /**
     * @return string[]
     */
    function getTerms($taxonomy);
}