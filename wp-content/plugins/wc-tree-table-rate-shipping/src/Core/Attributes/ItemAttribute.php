<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;


class ItemAttribute extends MapAttribute
{
    protected function getItemValue(IItem $item)
    {
        return spl_object_hash($item);
    }
}