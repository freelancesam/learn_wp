<?php
namespace Trs\Core\Conditions\Common\Compare;

use Trs\Common\Interfaces\IComparator;
use Trs\Core\Conditions\Common\AbstractCondition;


abstract class CompareCondition extends AbstractCondition
{
    public function __construct($compareWith, IComparator $comparator)
    {
        $this->compareWith = $compareWith;
        $this->comparator = $comparator;
    }


    protected $compareWith;

    protected function equals($value)
    {
        return $this->comparator->equals($this->compareWith, $value);
    }

    
    private $comparator;
}