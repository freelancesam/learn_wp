<?php
namespace Trs\Core\Attributes;

use Trs\Core\Interfaces\IItem;
use Trs\Core\Interfaces\IPackage;


abstract class MapAttribute extends AbstractAttribute
{
    public function getValue(IPackage $package)
    {
        $result = array();

        foreach ($package->getItems() as $key => $item) {
            $result[$key] = $this->getItemValue($item);
        }

        return $result;
    }

    protected abstract function getItemValue(IItem $item);
}