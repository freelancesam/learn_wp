<?php
namespace Trs\Mapping\Mappers;

use Trs\Core\Calculators\AggregatedCalculator;
use Trs\Core\Calculators\ChildrenCalculator;
use Trs\Core\Interfaces\IProcessor;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class ChildrenCalculatorMapper extends AbstractMapper
{
    public function __construct(IProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        return new AggregatedCalculator(
            new ChildrenCalculator(
                $this->processor,
                $context->getCurrentRuleChildren()
            ),
            $reader->read('aggregator', @$data['aggregator'])
        );
    }


    private $processor;
}