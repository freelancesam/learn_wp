<?php
namespace Trs\Common;

/**
 * @property-read mixed $min
 * @property-read mixed $max
 */
class Range extends ValueObject
{
    public function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
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

    protected $min;
    protected $max;
}