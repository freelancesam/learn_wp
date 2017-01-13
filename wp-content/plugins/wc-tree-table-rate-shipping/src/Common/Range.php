<?php
namespace Trs\Common;


class Range
{
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function getMin()
    {
        return $this->min;
    }

    public function getMax()
    {
        return $this->max;
    }

    public function clamp($value)
    {
        if (isset($this->min)) {
            $value = max($this->min, $value);
        }

        if (isset($this->max)) {
            $value = min($this->max, $value);
        }

        return $value;
    }

    private $min;
    private $max;
}