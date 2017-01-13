<?php
namespace Trs\Common;


class ClassNameAware
{
    public static function className()
    {
        return get_called_class();
    }
}