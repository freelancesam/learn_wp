<?php
namespace Trs\Mapping\Interfaces;

use Traversable;
use Trs\Core\Interfaces\IRule;


interface IMappingContext
{
    /**
     * @return IRule[]|Traversable
     */
    function getCurrentRuleChildren();
}