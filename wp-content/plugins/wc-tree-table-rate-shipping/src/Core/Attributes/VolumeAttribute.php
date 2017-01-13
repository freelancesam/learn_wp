<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;


class VolumeAttribute extends SumAttribute
{
    protected function getItemValue(IItem $item)
    {
        $dimensions = $item->getDimensions();
        $volume = $dimensions->length * $dimensions->width * $dimensions->height;
        return $volume;
    }
}