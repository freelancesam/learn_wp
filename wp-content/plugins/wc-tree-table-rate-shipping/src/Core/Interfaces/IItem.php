<?php
namespace Trs\Core\Interfaces;

use Trs\Core\Model\Dimensions;


interface IItem extends IItemAggregatables
{
    /**
     * @return string
     */
    function getProductId();

    /**
     * @return string
     */
    function getProductVariationId();

    /**
     * @return Dimensions
     */
    function getDimensions();
}