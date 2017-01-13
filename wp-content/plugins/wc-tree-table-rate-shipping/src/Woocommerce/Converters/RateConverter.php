<?php
namespace Trs\Woocommerce\Converters;

use Trs\Core\Interfaces\IRate;
use Trs\Core\Model\Rate;
use WC_Shipping_Rate;


class RateConverter
{
    public static function fromWoocommerceToCore(WC_Shipping_Rate $wcRate)
    {
        return new Rate($wcRate->cost, $wcRate->label);
    }

    public static function fromCoreToWoocommerce(IRate $rate, $id, $defaultTitle)
    {
        return array(
            'id' => "{$id}",
            'label' => $rate->getTitle() ?: $defaultTitle,
            'cost' => $rate->getCost(),
        );
    }
}