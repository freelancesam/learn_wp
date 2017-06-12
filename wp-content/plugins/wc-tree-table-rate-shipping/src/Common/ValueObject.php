<?php
namespace Trs\Common;


class ValueObject
{
    public function __get($property)
    {
        return $this->{$property};
    }

    public function __isset($property)
    {
        return isset($this->{$property});
    }
}