<?php
namespace Trs\Core\Interfaces;


interface ICondition
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function isSatisfiedBy($value);
}