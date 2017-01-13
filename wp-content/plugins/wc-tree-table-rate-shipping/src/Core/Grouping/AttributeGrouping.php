<?php
namespace Trs\Core\Grouping;

use Trs\Core\Interfaces\IAttribute;
use Trs\Core\Interfaces\IGrouping;
use Trs\Core\Interfaces\IItem;
use Trs\Core\Model\Package;


class AttributeGrouping implements IGrouping
{
    public function __construct(IAttribute $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getPackageIds(IItem $item)
    {
        return self::ids($this->attribute->getValue(new Package(array($item))));
    }

    public function multiplePackagesExpected()
    {
        return true;
    }

    private $attribute;

    private static function ids($value, $allowArray = true)
    {
        $ids = array();

        if (is_array($value) && $allowArray) {
            foreach ($value as $item) {
                $ids = array_merge($ids, self::ids($item, false));
            }
        } else if (is_object($value)) {
            /** @noinspection PhpParamsInspection */
            $ids[] = spl_object_hash($value);
        } else {
            $ids[] = (string)$value;
        }

        return $ids;
    }
}