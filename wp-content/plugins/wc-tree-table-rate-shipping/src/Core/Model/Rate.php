<?php
namespace Trs\Core\Model;

use InvalidArgumentException;
use Trs\Core\Interfaces\IRate;


class Rate implements IRate
{
    public function __construct($cost, $title = null)
    {
        if (!is_numeric($cost)) {
            throw new InvalidArgumentException();
        }

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

    private $cost;
    private $title;
}