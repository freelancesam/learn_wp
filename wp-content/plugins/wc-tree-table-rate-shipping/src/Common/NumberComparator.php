<?php
namespace Trs\Common;

use Trs\Common\Interfaces\IComparator;


class NumberComparator implements IComparator
{
    public function __construct($precision = null)
    {
        $this->precision = $precision;
    }

    public function equals($a, $b)
    {
        return $this->normalize($a) == $this->normalize($b);
    }

    private $precision;

    private function normalize($value)
    {
        return isset($this->precision) ? round($value * $this->precision) : $value;
    }
}