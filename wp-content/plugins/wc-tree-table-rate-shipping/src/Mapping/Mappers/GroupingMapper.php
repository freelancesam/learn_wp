<?php
namespace Trs\Mapping\Mappers;

use Trs\Core\Grouping\AttributeGrouping;
use Trs\Core\Grouping\FakeGrouping;
use Trs\Mapping\Interfaces\IMapper;
use Trs\Mapping\Interfaces\IMappingContext;
use Trs\Mapping\Interfaces\IReader;


class GroupingMapper implements IMapper
{
    public function read($data, IReader $reader, IMappingContext $context = null)
    {
        if (!isset($data) || $data === '') {
            return new FakeGrouping();
        }

        $attribute = $reader->read('attribute', $data, $context);
        return new AttributeGrouping($attribute);
    }
}