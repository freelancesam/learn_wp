<?php
namespace Trs\Mapping\Mappers;

use Trs\Core\Model\Rule;
use Trs\Core\Model\RuleMeta;
use Trs\Mapping\Interfaces\ILazyFactory;
use Trs\Mapping\Interfaces\ILazyFactoryAware;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;
use Trs\Mapping\MappingContext;


class RuleMapper extends AbstractMapper implements ILazyFactoryAware
{
    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        $this->requireType($data, 'array');

        if (($enable = @$data['meta']['enable']) !== null && !$enable) {
            return null;
        }

        $children = $reader->read('rules', @$data['children'], $context);

        $context = new MappingContext($children);
        {
            $meta = new RuleMeta(@$data['meta']['title']);

            $matcher = $this->lazyFactory->lazyMatcher(function() use($reader, $data, $context) {
                return $reader->read('rule_matcher', @$data['conditions'], $context);
            });

            $calculator = $this->lazyFactory->lazyCalculator(function() use($reader, $data, $context) {
                return $reader->read('rule_calculator', @$data['operations'], $context);
            });

            return new Rule($meta, $matcher, $calculator);
        }
    }

    public function setLazyFactory(ILazyFactory $lazyFactory)
    {
        $this->lazyFactory = $lazyFactory;
    }

    /** @var ILazyFactory */
    private $lazyFactory;
}