<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;


class ItemDimensionsAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        $dimensions = $item->getDimensions();
        $box = array($dimensions->length, $dimensions->width, $dimensions->height);
        return $box;
    }
}