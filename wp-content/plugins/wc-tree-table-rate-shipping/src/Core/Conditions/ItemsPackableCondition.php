<?php
namespace Trs\Core\Conditions;

use BoxPacking\Packer;
use Trs\Core\Conditions\Common\AbstractCondition;


class ItemsPackableCondition extends AbstractCondition
{
    public function __construct(Packer $packer, $box)
    {
        $this->packer = $packer;
        $this->box = $box;
    }

    public function isSatisfiedBy($boxes)
    {
        return $this->packer->canPack($this->box, $boxes);
    }

    private $packer;
    private $box;
}