<?php
namespace Trs\Core\Processing;

use Trs\Core\Interfaces\IRate;


class RateRegister implements IRate
{
    public $cost = 0;
    public $title = null;

    public function __construct($cost = 0, $title = null)
    {
        $this->cost = $cost;
        $this->title = $title;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function add(IRate $other)
    {
        $this->cost += $other->getCost();

        if ($title = $other->getTitle()) {
            $this->title = $title;
        }
    }
}