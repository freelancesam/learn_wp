<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;


class ProductAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        return $item->getProductId();
    }
}