<?php
namespace Trs\Core\Conditions\Common;

use Trs\Core\Interfaces\ICondition;


abstract class GroupCondition extends AbstractCondition
{
    public function __construct(array $conditions)
    {
        $this->conditions = $conditions;
    }

    /** @var ICondition[] */
    protected $conditions;
}