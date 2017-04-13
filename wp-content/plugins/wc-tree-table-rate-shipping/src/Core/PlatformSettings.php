<?php
namespace Trs\Core;

use Trs\Common\ValueObject;


/**
 * @property-read float $weightPrecision
 * @property-read float $dimensionPrecision
 * @property-read float $pricePrecision
 */
class PlatformSettings extends ValueObject
{
    public function __construct($weightPrecision, $dimensionPrecision, $pricePrecision)
    {
        $this->weightPrecision = $weightPrecision;
        $this->dimensionPrecision = $dimensionPrecision;
        $this->pricePrecision = $pricePrecision;
    }


    protected $weightPrecision;
    protected $dimensionPrecision;
    protected $pricePrecision;
}