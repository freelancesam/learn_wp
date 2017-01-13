<?php
namespace Trs\Core\Grouping;

use Trs\Core\Interfaces\IGrouping;
use Trs\Core\Interfaces\IItem;


class FakeGrouping implements IGrouping
{
    public function __construct(array $packageIds = array('all'))
    {
        $this->packageIds = $packageIds;
    }

    public function getPackageIds(IItem $item)
    {
        return $this->packageIds;
    }

    public function multiplePackagesExpected()
    {
        return count($this->packageIds) > 1;
    }

    private $packageIds;
}