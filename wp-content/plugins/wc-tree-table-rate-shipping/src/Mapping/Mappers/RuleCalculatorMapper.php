<?php
namespace Trs\Mapping\Mappers;

use Trs\Common\Arrays;
use Trs\Core\Calculators\RuleCalculator;
use Trs\Core\Operations\GroupOperation;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class RuleCalculatorMapper extends AbstractMapper
{
    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        $operations = Arrays::map((array)@$data['list'], function($operationData) use($reader, $context) {
            return $reader->read('package_operation', $operationData, $context);
        });

        $grouping = $reader->read('grouping', @$data['meta']['grouping'], $context);

        return new RuleCalculator(new GroupOperation($operations), $grouping);
    }
}