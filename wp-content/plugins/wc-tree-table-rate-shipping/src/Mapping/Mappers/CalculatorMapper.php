<?php
namespace Trs\Mapping\Mappers;

use Exception;
use Trs\Core\Calculators\AttributeMultiplierCalculator;
use Trs\Core\Calculators\ConstantCalculator;
use Trs\Core\Calculators\FreeCalculator;
use Trs\Core\Calculators\ProgressiveCalculator;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class CalculatorMapper extends AbstractMapper
{
    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        $calculator = null;

        switch ($type = @$data['calculator']) {

            case 'free':
                $calculator = new FreeCalculator();
                break;

            case 'const':
                $calculator = new ConstantCalculator(self::receiveFloat(@$data['value']));
                break;

            case 'percentage':
                if (($target = @$data['target']) != 'package_price') {
                    throw new Exception("Unknown percentage calculator target '{$target}'");
                }

                if (!is_numeric($percentage = @$data['value'])) {
                    throw new Exception("Percentage value is not a number: '{$percentage}'");
                }

                $attribute = $reader->read('attribute', array(
                    'attribute' => 'price',
                    'price_kind' => @$data['price_kind'],
                ), $context);

                $calculator = new AttributeMultiplierCalculator($attribute, $percentage/100);

                break;

            case 'weight':
            case 'count':
            case 'volume':
                $attribute = $reader->read('attribute', $type, $context);
                $calculator = new ProgressiveCalculator(
                    $attribute,
                    self::receiveFloat($data['cost']),
                    self::receiveFloat(@$data['step'], 1),
                    self::receiveFloat(@$data['skip'])
                );
                break;

            case 'shipping_method':
                $calculator = $reader->read('shipping_method_calculator', $data, $context);
                break;

            case 'children':
                $calculator = $reader->read('children_calculator', $data, $context);
                break;

            default:
                throw new Exception("Uknown calculator type '{$type}'");
        }

        return $calculator;
    }

    static private function receiveFloat($value, $default = 0)
    {
        if ($value === '') {
            $value = null;
        }

        if (!isset($value)) {
            $value = $default;
        }

        if (isset($value)) {

            if (!is_numeric($value)) {
                throw new Exception("Invalid number value '{$value}'");
            }

            $value = (float)$value;
        }

        return $value;
    }
}