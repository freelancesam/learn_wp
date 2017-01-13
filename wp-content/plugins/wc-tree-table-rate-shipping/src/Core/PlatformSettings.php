<?php
namespace Trs\Core;


/**
 * @property-read float $weightPrecision
 * @property-read float $dimensionPrecision
 * @property-read float $pricePrecision
 */
class PlatformSettings
{
    public function __construct($weightPrecision, $dimensionPrecision, $pricePrecision)
    {
        $this->weightPrecision = $weightPrecision;
        $this->dimensionPrecision = $dimensionPrecision;
        $this->pricePrecision = $pricePrecision;
    }

    public function __get($property)
    {
        return $this->{$property};
    }


    private $weightPrecision;
    private $dimensionPrecision;
    private $pricePrecision;
}