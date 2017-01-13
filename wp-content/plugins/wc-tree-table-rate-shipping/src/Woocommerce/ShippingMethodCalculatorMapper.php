<?php
namespace Trs\Woocommerce;

use Trs\Core\Calculators\AggregatedCalculator;
use Trs\Core\Conditions\Common\StringPatternCondition;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;
use Trs\Mapping\Mappers\AbstractMapper;
use WC_Shipping_Method;


class ShippingMethodCalculatorMapper extends AbstractMapper
{
    public function __construct(array $availableShippingMethods)
    {
        $this->availableShippingMethods = array();
        foreach ($availableShippingMethods as $method) {
            $this->availableShippingMethods[self::getShippingMethodPersistentId($method)] = $method;
        }
    }

    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        $methods = array();
        if (isset($data['ids'])) {
            foreach ($data['ids'] as $methodId) {
                if ($method = @$this->availableShippingMethods[$methodId]) {
                    $methods[] = $method;
                }
            }
        }

        $rateNamePattern = (string)@$data['rate_name'];
        $rateNameFilter = new StringPatternCondition($rateNamePattern === '' ? '*' : $rateNamePattern);

        return new AggregatedCalculator(
            new ShippingMethodCalculator($methods, $rateNameFilter),
            $reader->read('aggregator', @$data['aggregator'], $context)
        );
    }

    public static function getShippingMethodPersistentId(WC_Shipping_Method $shippingMethod)
    {
        $id = null;

        if (!empty($shippingMethod->instance_id)) {
            $id = $shippingMethod->instance_id;
        } else {
            $id = $shippingMethod->id;
            if (!empty($shippingMethod->number)) {
                $id .= '_'.$shippingMethod->number;
            }
        }

        return $id;
    }

    private $availableShippingMethods;
}