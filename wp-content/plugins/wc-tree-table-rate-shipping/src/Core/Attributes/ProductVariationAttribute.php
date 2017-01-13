<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;


class ProductVariationAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        $id = $item->getProductVariationId();
        $id = isset($id) ? $id : $item->getProductId();
        return $id;
    }
}