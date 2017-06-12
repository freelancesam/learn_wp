<?php
namespace Trs\Woocommerce\Model\Shipping;

use Trs\Common\ValueObject;


/**
 * @property-read string $title
 */
class ShippingZone extends ValueObject
{
    /** @var ShippingZone */
    static $GLOBAL;


    public function __construct($title)
    {
        $this->title = $title;
    }

    protected $title;
}

ShippingZone::$GLOBAL = new ShippingZone('Global');